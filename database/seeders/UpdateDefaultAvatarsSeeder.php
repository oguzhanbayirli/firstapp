<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UpdateDefaultAvatarsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Tüm NULL veya boş avatar'ları default-avatar.svg ile güncelle
        User::whereNull('avatar')
            ->orWhere('avatar', '')
            ->update(['avatar' => 'default-avatar.svg']);
    }
}
