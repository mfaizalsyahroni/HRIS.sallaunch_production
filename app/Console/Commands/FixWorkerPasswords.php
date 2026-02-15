<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Worker;
use Illuminate\Support\Facades\Hash;

class FixWorkerPasswords extends Command
{
    /**
     * Nama command yang dipakai di terminal.
     *
     *
     */
    protected $signature = 'fix:worker-passwords';

    /**
     * Deskripsi command (muncul di php artisan list)
     *
     * 
     */
    protected $description = 'Meng-hash semua password Worker yang belum menggunakan bcrypt';

    /**
     * Jalankan perintah.
     */
    public function handle()
    {
        $this->info('🔍 Mengecek password Worker...');

        $fixed = 0;
        $workers = Worker::all();

        foreach ($workers as $worker) {
            // Cek apakah password sudah di-hash bcrypt (biasanya diawali "$2y$")
            if (!str_starts_with($worker->password, '$2y$')) {
                $worker->password = Hash::make($worker->password);
                $worker->save();
                $this->info("✅ Password untuk {$worker->employee_id} ({$worker->fullname}) sudah di-hash.");
                $fixed++;
            }
        }

        if ($fixed > 0) {
            $this->info("🎉 Selesai! {$fixed} password berhasil diperbaiki.");
        } else {
            $this->info('✅ Semua password sudah aman menggunakan bcrypt.');
        }

        return 0;
    }
}