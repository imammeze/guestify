<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $defaultPassword = env('DEFAULT_LOGIN_PASSWORD', 'DindukCapil2025#');

        $users = [
            [
                'name' => 'Yuni Cahya Budi Ningsih',
                'nip' => '197706262024212009',
                'email' => null,
            ],
            [
                'name' => 'Hari Subagyo',
                'nip' => '198611062024211012',
                'email' => null,
            ],
            [
                'name' => 'Gandhi Satria Muhammad',
                'nip' => '199910112024211008',
                'email' => null,
            ],
            [
                'name' => 'Henika Candra Wati',
                'nip' => '199802092023212009',
                'email' => null,
            ],
            [
                'name' => 'Trias Wali Laelan',
                'nip' => '199307022025211038',
                'email' => null,
            ],
            [
                'name' => 'Gladi Eko Prabowo',
                'nip' => '199601102024211010',
                'email' => null,
            ],
            [
                'name' => 'Agus Sriyono',
                'nip' => '196708011992031006',
                'email' => null,
            ],
            [
                'name' => 'Ikhsan Bagus Permadi',
                'nip' => '197905101998031003',
                'email' => null,
            ],
        ];

        foreach ($users as $data) {
            User::updateOrCreate(
                ['nip' => $data['nip']], 
                [
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'password' => Hash::make($defaultPassword),
                ]
            );
        }
    }
}