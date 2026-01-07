<?php

namespace App\Jobs;

use App\Imports\ProductsImport;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Maatwebsite\Excel\Facades\Excel;

class ProcessProductImport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $timeout = 300;

    protected int $importId;
    protected string $filePath;

    public function __construct(int $importId, string $filePath)
    {
        $this->importId = $importId;
        $this->filePath = $filePath;
    }

    public function handle(): void
    {
        Excel::queueImport(
            new ProductsImport($this->importId),
            $this->filePath,
            "local"
        );
    }
}
