<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Profile;
use Carbon\Carbon;

class ProfileSeeder extends Seeder
{
    public function run(): void
    {
        Profile::updateOrCreate(
            ['email' => 'semastikom@gmail.com'], // Ensure uniqueness based on email
            [
                'full_name' => 'STIKOM Yos Sudarso Purwokerto',
                'nick_name' => 'Senat Mahasiswa',
                'logo' => 'profiles/logos/01JPDR3VQ0R7TZ57REXZXW16S5.png',
                'description' => '<p>Senat Mahasiswa STIKOM Yos Sudarso Purwokerto adalah wadah aspirasi dan suara mahasiswa yang berperan dalam menjembatani komunikasi dengan pihak kampus serta menciptakan lingkungan akademik yang kondusif dan berdaya saing.<br><br>Dengan semangat kolaborasi, Senat Mahasiswa aktif menginisiasi kegiatan yang mendukung pengembangan karakter, kreativitas, dan kepemimpinan untuk membentuk generasi yang berintegritas dan siap menghadapi tantangan masa depan.</p>',
                'address' => 'Jl. SMP 5, Windusara, Karangklesem, Kec. Purwokerto Sel., Kabupaten Banyumas, Jawa Tengah 53144',
                'email' => 'semastikom@gmail.com',
                'phone_number' => '081234567891',
                'instagram_account_link' => 'https://www.instagram.com/senatstikom?utm_source=ig_web_button_share_sheet&igsh=ZDNlZDc0MzIxNw==',
                'tiktok_account_link' => 'https://www.tiktok.com/@senma.sys?is_from_webapp=1&sender_device=pc',
                'whatsapp_account_link' => 'https://wa.me/6281234567891',
                'created_at' => Carbon::parse('2025-03-16 03:32:18'),
                'updated_at' => Carbon::parse('2025-03-16 03:42:34'),
            ]
        );
    }
}
