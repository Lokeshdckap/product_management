<?php

namespace App\Imports;

use App\Models\Product;
use App\Models\Category;
use App\Models\Import;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Row;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Storage;

class ProductsImport implements 
    OnEachRow,
    WithHeadingRow,
    WithChunkReading,
    WithBatchInserts,
    ShouldQueue
{
    protected ?Import $import = null;
    protected int $importId;
    protected $failedFile;
    protected string $failedFilePath;

    public function __construct(int $importId)
    {
        $this->importId = $importId;
        $this->failedFilePath = "imports/failed/failed_{$importId}.csv";

        Storage::makeDirectory('imports/failed');

        if (!Storage::exists($this->failedFilePath)) {
            Storage::put($this->failedFilePath, "name,price,stock,category,error\n");
        }
    }

    protected function getImport(): ?Import
    {
        if ($this->import === null) {
            $this->import = Import::find($this->importId);
        }
        return $this->import;
    }

    public function onRow(Row $row)
    {
        $data = $row->toArray();

        $validator = Validator::make($data, [
            'name' => 'required|string',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'category' => 'required|string',
        ]);

        if ($validator->fails()) {
            if (!$this->failedFile) {
                $this->failedFile = fopen(storage_path('app/' . $this->failedFilePath), 'a');
            }

            fputcsv($this->failedFile, [
                $data['name'] ?? '',
                $data['price'] ?? '',
                $data['stock'] ?? '',
                $data['category'] ?? '',
                implode(' | ', $validator->errors()->all())
            ]);

            $import = $this->getImport();
             if ($import) {
                $import->increment('failed_rows');
                $import->increment('processed_rows');
            }

            return;
        }


        $category = Category::firstOrCreate(['name' => $data['category']]);

        $product = new Product();
        $product->name = $data['name'];
        $product->description = $data['description'] ?? null;
        $product->price = $data['price'];
        $product->stock = $data['stock'];
        $product->category_id = $category->id;
        $product->image = $data['image'] ?? 'products/default.png';
        $product->save();

        $import = $this->getImport();
        if ($import) {
            $import->increment('processed_rows');
        }
    }

    public function chunkSize(): int
    {
        return 1000;
    }

    public function batchSize(): int
    {
        return 1000;
    }

    public function __destruct()
    {
        if (is_resource($this->failedFile)) {
            fclose($this->failedFile);
        }

        $import = $this->getImport();
        if ($import) {
            $import->update([
                'status' => $import->failed_rows > 0
                    ? 'completed_with_errors'
                    : 'completed',
            ]);
        }
    }
}

