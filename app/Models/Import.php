<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Import extends Model
{
    use HasFactory;
    protected $fillable = [
        "uuid",
        "import_type",
        "original_file",
        "failed_file",
        "status",
        "processed_rows",
        "failed_rows",
    ];

    protected static function booted()
    {
        static::creating(function ($import) {
            $import->uuid = Str::uuid();
        });
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }
}
