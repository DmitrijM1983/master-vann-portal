<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RolesSeeder extends Seeder
{
    public array $roles = [
        'master',
        'customer'
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $items = collect($this->roles)->map(function ($item) {
            return ['title' => $item];
        })->toArray();

        Role::insert($items);
    }
}
