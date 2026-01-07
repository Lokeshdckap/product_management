<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Import extends Model
{
    protected $fillable = [
        "uuid",
        "import_type",
        "original_file",
        "failed_file",
        "status",
        "total_rows",
        "processed_rows",
        "failed_rows",
    ];

    protected static function booted()
    {
        static::creating(function ($import) {
            $import->uuid = Str::uuid();
        });
    }
}
