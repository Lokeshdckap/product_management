<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Import;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ImportController extends Controller
{
    public function __invoke(Request $request)
    {
        $imports = Import::latest()->paginate(10);

        return view("admin.imports.index", compact("imports"));
    }

    public function downloadFailed(Import $import)
    {
        if (!$import->failed_file || !Storage::exists($import->failed_file)) {
            return back()->with("error", "No failed products available.");
        }

        return Storage::download(
            $import->failed_file,
            "failed_products_" . $import->uuid . ".csv"
        );
    }
}
