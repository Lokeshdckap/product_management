<?php

namespace Database\Factories;

use App\Models\Import;
use App\Models\Admin;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ImportFactory extends Factory
{
    protected $model = Import::class;

    public function definition(): array
    {
        return [
            'uuid' => Str::uuid()->toString(),
            'import_type' => 'products', // or use fake()->randomElement(['products', 'categories'])
            'admin_id' => Admin::factory(), // Creates an admin automatically
            'original_name' => 'test_import.csv',
            'original_file' => 'imports/test_import.csv',
            'status' => 'pending',
            'processed_rows' => 0,
            'failed_rows' => 0,
        ];
    }

    /**
     * Indicate that the import is processing.
     */
    public function processing(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'processing',
        ]);
    }

    /**
     * Indicate that the import is completed.
     */
    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'completed',
            'processed_rows' => 100,
        ]);
    }

    /**
     * Indicate that the import has failed.
     */
    public function failed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'failed',
            'failed_rows' => 50,
        ]);
    }
}