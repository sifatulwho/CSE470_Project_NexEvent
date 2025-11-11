<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $adminEmail = config('nexevent.admin_email');

        if (filled($adminEmail) && User::query()->where('role', User::ROLE_ADMIN)->doesntExist()) {
            User::factory()->admin()->create([
                'name' => 'NexEvent Administrator',
                'email' => $adminEmail,
                'password' => Hash::make('password'),
            ]);
        }

        User::factory(5)->organizer()->create();
        User::factory(15)->create();
    }
}
