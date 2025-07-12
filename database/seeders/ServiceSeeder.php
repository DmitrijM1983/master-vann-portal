<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    public array $service_name = [
        'Наливной акрил',
        'Эмалировка',
        'Акриловый вкладыш'
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $items = collect($this->service_name)->map(function ($item) {
            return ['name' => $item];
        })->toArray();

        Service::insert($items);
    }
}
