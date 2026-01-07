<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Imports\ProductsImport;
use App\Models\Import;
use App\Models\Product;
use App\Models\Category;
use App\Models\Admin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use PHPUnit\Framework\Attributes\Test;

class ProductsImportTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        if (!is_dir(storage_path('app/imports'))) {
            mkdir(storage_path('app/imports'), 0755, true);
        }
        if (!is_dir(storage_path('app/imports/failed'))) {
            mkdir(storage_path('app/imports/failed'), 0755, true);
        }
    }

    #[Test]
    public function it_imports_valid_products_from_csv()
    {
  
        $import = Import::factory()->create();
        

        $csvContent = "name,description,price,stock,category\n";
        $csvContent .= "Product 1,Description 1,100,10,Electronics\n";
        $csvContent .= "Product 2,Description 2,200,20,Clothing\n";
        
        $csvPath = storage_path('app/imports/test_valid.csv');
        file_put_contents($csvPath, $csvContent);


        Excel::import(new ProductsImport($import->id), $csvPath);


        $this->assertDatabaseHas('products', [
            'name' => 'Product 1',
            'price' => 100,
            'stock' => 10,
        ]);
        
        $this->assertDatabaseHas('products', [
            'name' => 'Product 2',
            'price' => 200,
            'stock' => 20,
        ]);


        $this->assertDatabaseHas('categories', [
            'name' => 'Electronics',
        ]);
        
        $this->assertDatabaseHas('categories', [
            'name' => 'Clothing',
        ]);

 
        if (file_exists($csvPath)) {
            unlink($csvPath);
        }
    }

    #[Test]
    public function it_creates_category_if_not_exists()
    {

        $import = Import::factory()->create();
        
        $csvContent = "name,description,price,stock,category\n";
        $csvContent .= "Test Product,Test Description,50,5,New Category\n";
        
        $csvPath = storage_path('app/imports/test_category.csv');
        file_put_contents($csvPath, $csvContent);


        Excel::import(new ProductsImport($import->id), $csvPath);


        $this->assertDatabaseHas('categories', [
            'name' => 'New Category',
        ]);
        
        $category = Category::where('name', 'New Category')->first();
        $this->assertDatabaseHas('products', [
            'name' => 'Test Product',
            'category_id' => $category->id,
        ]);


        if (file_exists($csvPath)) {
            unlink($csvPath);
        }
    }

    #[Test]
    public function it_reuses_existing_category()
    {
  
        $existingCategory = Category::create(['name' => 'Existing Category']);
        $import = Import::factory()->create();
        
        $csvContent = "name,description,price,stock,category\n";
        $csvContent .= "Product A,Description A,100,10,Existing Category\n";
        $csvContent .= "Product B,Description B,200,20,Existing Category\n";
        
        $csvPath = storage_path('app/imports/test_reuse_category.csv');
        file_put_contents($csvPath, $csvContent);


        Excel::import(new ProductsImport($import->id), $csvPath);


        $this->assertEquals(1, Category::where('name', 'Existing Category')->count());
        
 
        $this->assertDatabaseHas('products', [
            'name' => 'Product A',
            'category_id' => $existingCategory->id,
        ]);
        
        $this->assertDatabaseHas('products', [
            'name' => 'Product B',
            'category_id' => $existingCategory->id,
        ]);


        if (file_exists($csvPath)) {
            unlink($csvPath);
        }
    }

    #[Test]
    public function it_updates_existing_product_with_same_name()
    {

        $category = Category::create(['name' => 'Test Category']);
        $existingProduct = Product::create([
            'name' => 'Existing Product',
            'description' => 'Old Description',
            'price' => 50,
            'stock' => 5,
            'category_id' => $category->id,
        ]);
        
        $import = Import::factory()->create();
        
        $csvContent = "name,description,price,stock,category\n";
        $csvContent .= "Existing Product,New Description,150,15,Test Category\n";
        
        $csvPath = storage_path('app/imports/test_update.csv');
        file_put_contents($csvPath, $csvContent);

 
        Excel::import(new ProductsImport($import->id), $csvPath);


        $this->assertEquals(1, Product::where('name', 'Existing Product')->count());
        
        $updatedProduct = Product::where('name', 'Existing Product')->first();
        $this->assertEquals('New Description', $updatedProduct->description);
        $this->assertEquals(150, $updatedProduct->price);
        $this->assertEquals(15, $updatedProduct->stock);


        if (file_exists($csvPath)) {
            unlink($csvPath);
        }
    }
    #[Test]
    public function it_skips_invalid_rows_and_logs_to_failed_csv()
    {
  
        $import = Import::factory()->create();
        

        $csvContent = "name,description,price,stock,category\n";
        $csvContent .= "Valid Product,Valid Description,100,10,Electronics\n";
        $csvContent .= "Invalid Product 1,No Price,,5,Electronics\n"; 
        $csvContent .= "Invalid Product 2,Negative Stock,100,-5,Electronics\n"; 
        $csvContent .= ",Empty Name,100,5,Electronics\n";
        
        $csvPath = storage_path('app/imports/test_invalid.csv');
        file_put_contents($csvPath, $csvContent);


        Excel::import(new ProductsImport($import->id), $csvPath);


        $this->assertDatabaseHas('products', [
            'name' => 'Valid Product',
        ]);


        $this->assertDatabaseMissing('products', [
            'name' => 'Invalid Product 1',
        ]);
        
        $this->assertDatabaseMissing('products', [
            'name' => 'Invalid Product 2',
        ]);

   
        $import->refresh();
        $this->assertGreaterThan(0, $import->failed_rows, 'Failed rows should be tracked');

        $failedCsvPath = storage_path('app/imports/failed/failed_' . $import->id . '.csv');
        if (file_exists($failedCsvPath)) {
            $failedContent = file_get_contents($failedCsvPath);
            $this->assertStringContainsString('Invalid Product 1', $failedContent);
        }


        if (file_exists($csvPath)) {
            unlink($csvPath);
        }
    }
    #[Test]
    public function it_tracks_processed_and_failed_rows()
    {
       
        $import = Import::factory()->create([
            'processed_rows' => 0,
            'failed_rows' => 0,
        ]);
        
        $csvContent = "name,description,price,stock,category\n";
        $csvContent .= "Valid Product 1,Description,100,10,Category1\n";
        $csvContent .= "Valid Product 2,Description,200,20,Category2\n";
        $csvContent .= "Invalid Product,Description,,5,Category3\n"; 
        
        $csvPath = storage_path('app/imports/test_tracking.csv');
        file_put_contents($csvPath, $csvContent);


        Excel::import(new ProductsImport($import->id), $csvPath);


        $import->refresh();
        $this->assertEquals(3, $import->processed_rows);
        $this->assertEquals(1, $import->failed_rows);


        if (file_exists($csvPath)) {
            unlink($csvPath);
        }
    }

    #[Test]
    public function it_updates_import_status_to_completed()
    {

        $import = Import::factory()->create(['status' => 'processing']);
        
        $csvContent = "name,description,price,stock,category\n";
        $csvContent .= "Product 1,Description,100,10,Category\n";
        
        $csvPath = storage_path('app/imports/test_status.csv');
        file_put_contents($csvPath, $csvContent);

  
        Excel::import(new ProductsImport($import->id), $csvPath);


        $import->refresh();
        $this->assertEquals('completed', $import->status);


        if (file_exists($csvPath)) {
            unlink($csvPath);
        }
    }

    #[Test]
    public function it_updates_import_status_to_completed_with_errors()
    {
        
        $import = Import::factory()->create(['status' => 'processing']);
        
        $csvContent = "name,description,price,stock,category\n";
        $csvContent .= "Valid Product,Description,100,10,Category\n";
        $csvContent .= "Invalid Product,Description,,5,Category\n"; 
        
        $csvPath = storage_path('app/imports/test_status_error.csv');
        file_put_contents($csvPath, $csvContent);


        Excel::import(new ProductsImport($import->id), $csvPath);


        $import->refresh();
        $this->assertEquals('completed_with_errors', $import->status);
        $this->assertNotNull($import->failed_file);


        if (file_exists($csvPath)) {
            unlink($csvPath);
        }
    }
    #[Test]
    public function it_sets_default_image_if_not_provided()
    {

        $import = Import::factory()->create();
        
        $csvContent = "name,description,price,stock,category\n";
        $csvContent .= "Product Without Image,Description,100,10,Category\n";
        
        $csvPath = storage_path('app/imports/test_image.csv');
        file_put_contents($csvPath, $csvContent);


        Excel::import(new ProductsImport($import->id), $csvPath);


        $this->assertDatabaseHas('products', [
            'name' => 'Product Without Image',
            'image' => 'products/default.png',
        ]);


        if (file_exists($csvPath)) {
            unlink($csvPath);
        }
    }

    #[Test]
    public function it_trims_whitespace_from_values()
    {

        $import = Import::factory()->create();
        
        $csvContent = "name,description,price,stock,category\n";
        $csvContent .= "  Product With Spaces  ,  Description  ,100,10,  Category  \n";
        
        $csvPath = storage_path('app/imports/test_trim.csv');
        file_put_contents($csvPath, $csvContent);


        Excel::import(new ProductsImport($import->id), $csvPath);


        $this->assertDatabaseHas('products', [
            'name' => 'Product With Spaces',
        ]);
        
        $this->assertDatabaseHas('categories', [
            'name' => 'Category',
        ]);


        if (file_exists($csvPath)) {
            unlink($csvPath);
        }
    }
    #[Test]
    public function import_belongs_to_admin()
    {
      
        $admin = Admin::factory()->create();
        $import = Import::factory()->create(['admin_id' => $admin->id]);

        
        $relatedAdmin = $import->admin;


        $this->assertInstanceOf(Admin::class, $relatedAdmin);
        $this->assertEquals($admin->id, $relatedAdmin->id);
    }
    #[Test]
    protected function tearDown(): void
    {

        $importDir = storage_path('app/imports');
        $failedDir = storage_path('app/imports/failed');
        
        if (is_dir($importDir)) {
            $files = glob("$importDir/*.csv");
            foreach ($files as $file) {
                if (is_file($file)) {
                    unlink($file);
                }
            }
        }
        
        if (is_dir($failedDir)) {
            $files = glob("$failedDir/*.csv");
            foreach ($files as $file) {
                if (is_file($file)) {
                    unlink($file);
                }
            }
        }
        
        parent::tearDown();
    }
}