<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Worker;

class HashPasswordsSeeder extends Seeder
{
    public function run()
    {
        $workers = Worker::all();
        foreach ($workers as $worker) {
            // Pastikan password di-hash dengan Bcrypt
            if (!Hash::needsRehash($worker->password)) {
                $worker->password = Hash::make($worker->password);
                $worker->save();
            }
        }
    }
}