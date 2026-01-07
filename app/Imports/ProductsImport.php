<?php

namespace App\Imports;

use App\Models\Product;
use App\Models\Category;
use App\Models\Import;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Row;
use Maatwebsite\Excel\Concerns\{
    OnEachRow,
    WithHeadingRow,
    WithChunkReading,
    SkipsOnError,
    SkipsEmptyRows,
    WithEvents
};
use Maatwebsite\Excel\Events\AfterImport;
use Throwable;

class ProductsImport implements
    OnEachRow,
    WithHeadingRow,
    WithChunkReading,
    SkipsOnError,
    SkipsEmptyRows,
    WithEvents,
    ShouldQueue
{
    protected int $importId;
    protected string $failedFilePath;

    protected array $categoryCache = [];

    public function __construct(int $importId)
    {
        $this->importId = $importId;
        $this->failedFilePath = "imports/failed/failed_{$importId}.csv";
    }

    public function onRow(Row $row): void
    {
        $data = [];

        try {
            $data = $row->toArray();

            if ($this->isEmptyRow($data)) {
                return;
            }

            foreach ($data as $k => $v) {
                if (is_string($v)) {
                    $data[$k] = trim($v);
                }
            }

            $validator = Validator::make($data, [
                "name" => "required|string|max:255",
                "price" => "required|numeric|min:0",
                "stock" => "required|integer|min:0",
                "category" => "required|string|max:255",
            ]);

            if ($validator->fails()) {
                $this->writeFailedRow($data, $validator->errors()->all());
                $this->incrementCounters(true);
                return;
            }

            $categoryName = $data["category"];

            if (!isset($this->categoryCache[$categoryName])) {
                $this->categoryCache[$categoryName] = Category::firstOrCreate([
                    "name" => $categoryName,
                ])->id;
            }

            Product::updateOrCreate(
                ["name" => $data["name"]],
                [
                    "description" => $data["description"] ?? null,
                    "price" => $data["price"],
                    "stock" => $data["stock"],
                    "category_id" => $this->categoryCache[$categoryName],
                    "image" => $data["image"] ?? "products/default.png",
                ]
            );

            $this->incrementCounters(false);
        } catch (\Exception $e) {
            $this->writeFailedRow($data, ["Exception: " . $e->getMessage()]);
            Log::error("Import error", ["message" => $e->getMessage()]);
            $this->incrementCounters(true);
        }
    }

    protected function writeFailedRow(array $data, array $errors): void
    {
        $csv =
            $this->csv($data["name"] ?? "") .
            "," .
            $this->csv($data["price"] ?? "") .
            "," .
            $this->csv($data["stock"] ?? "") .
            "," .
            $this->csv($data["category"] ?? "") .
            "," .
            $this->csv(implode(" | ", $errors)) .
            "\n";

        Storage::append($this->failedFilePath, $csv);
    }

    protected function incrementCounters(bool $failed): void
    {
        Import::where("id", $this->importId)->increment("processed_rows");

        if ($failed) {
            Import::where("id", $this->importId)->increment("failed_rows");
        }
    }

    protected function isEmptyRow(array $data): bool
    {
        foreach ($data as $v) {
            if (trim((string) $v) !== "") {
                return false;
            }
        }
        return true;
    }

    public function chunkSize(): int
    {
        return 1000;
    }

    protected function csv($value): string
    {
        $value = (string) $value;

        if (strpbrk($value, ",\"\n\r") !== false) {
            return '"' . str_replace('"', '""', $value) . '"';
        }

        return $value;
    }

    public function registerEvents(): array
    {
        return [
            AfterImport::class => function () {
                $import = Import::find($this->importId);

                if (!$import) {
                    return;
                }

                $import->update([
                    "status" =>
                        $import->failed_rows > 0
                            ? "completed_with_errors"
                            : "completed",
                    "failed_file" =>
                        $import->failed_rows > 0 ? $this->failedFilePath : null,
                ]);
            },
        ];
    }

    public function onError(Throwable $e): void
    {
        Log::error("Excel error", ["message" => $e->getMessage()]);
        $this->incrementCounters(true);
    }
}
