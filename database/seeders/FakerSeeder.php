<?php

namespace Database\Seeders;

use App\Models\Holiday;
use App\Models\PasswordShare;
use App\Models\PasswordVault;
use App\Models\Timesheet;
use App\Models\User;
use Illuminate\Database\Seeder;

class FakerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if ($this->command->confirm('¿Desea ejecutar el FakerSeeder?')) {
            User::factory()->count(10)->create([]);
            PasswordVault::factory()->count(10)->create([]);
            PasswordShare::factory()->count(10)->create([]);
            Holiday::factory()->count(10)->create([]);
            Timesheet::factory()->count(10)->create([]);
        }
    }
}
