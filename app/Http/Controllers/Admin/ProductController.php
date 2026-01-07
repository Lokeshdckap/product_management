<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Import;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Imports\ProductsImport;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\HeadingRowImport;
use App\Jobs\ProcessProductImport;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with("category")
            ->latest()
            ->paginate(10);

        return view("admin.products.index", compact("products"));
    }

    public function create()
    {
        $categories = Category::orderBy("name")->get();
        return view("admin.products.create", compact("categories"));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "name" => ["required", "string", "max:255"],
            "description" => ["nullable", "string"],
            "price" => ["required", "numeric", "min:0"],
            "image" => ["nullable", "image", "mimes:jpg,jpeg,png"],
            "category_id" => ["nullable", "exists:categories,id"],
            "stock" => ["required", "integer", "min:0"],
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        if ($request->hasFile("image")) {
            $imagePath = $request->file("image")->store("products", "public");
        } else {
            $imagePath = "products/default.png";
        }

        $product = new Product();
        $product->name = $validator->validated()["name"];
        $product->description = $validator->validated()["description"];
        $product->price = $validator->validated()["price"];
        $product->image = $imagePath;
        $product->category_id = $validator->validated()["category_id"];
        $product->stock = $validator->validated()["stock"];

        $product->save();

        return redirect()
            ->route("admin.products.index")
            ->with("success", "Product created successfully");
    }

    public function edit(Product $product)
    {
        $categories = Category::orderBy("name")->get();
        return view("admin.products.edit", compact("product", "categories"));
    }

    public function update(Request $request, Product $product)
    {
        $validator = Validator::make($request->all(), [
            "name" => ["required", "string", "max:255"],
            "description" => ["nullable", "string"],
            "price" => ["required", "numeric", "min:0"],
            "image" => ["nullable", "image", "mimes:jpg,jpeg,png"],
            "category_id" => ["nullable", "exists:categories,id"],
            "stock" => ["required", "integer", "min:0"],
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $validator->validated();

        if ($request->hasFile("image")) {
            if ($product->image && $product->image !== "products/default.png") {
                Storage::disk("public")->delete($product->image);
            }
            $data["image"] = $request
                ->file("image")
                ->store("products", "public");
        }

        $product->update($data);

        return redirect()
            ->route("admin.products.index")
            ->with("success", "Product updated successfully");
    }

    public function destroy(Product $product)
    {
        if ($product->image && $product->image !== "products/default.png") {
            Storage::disk("public")->delete($product->image);
        }

        $product->delete();

        return redirect()
            ->route("admin.products.index")
            ->with("success", "Product deleted successfully");
    }

    public function import(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "file" => ["required", "file", "mimes:csv,xlsx"],
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        $uploadedFile = $request->file("file"); 

        $path = $uploadedFile->store("imports/original");

        $import = new Import();
        $import->import_type = "products";
        $import->admin_id = auth()->id();
        $import->original_file = $path;
        $import->original_name = $uploadedFile->getClientOriginalName();
        $import->status = "pending";
        $import->processed_rows = 0;
        $import->failed_rows = 0;

        $import->save();

        ProcessProductImport::dispatch($import->id, $path);

        return redirect()
            ->back()
            ->with(
                "success",
                "Import started. Products will be added in background."
            );
    }
}
