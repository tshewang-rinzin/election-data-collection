<?php

namespace Database\Seeders\Auth;

use App\Domains\Auth\Models\User;
use Database\Seeders\Traits\DisableForeignKeys;
use Illuminate\Database\Seeder;

/**
 * Class UserTableSeeder.
 */
class UserSeeder extends Seeder
{
    use DisableForeignKeys;

    /**
     * Run the database seed.
     */
    public function run()
    {
        $this->disableForeignKeys();

        // Add the master administrator, user id of 1
        User::create([
            'type' => User::TYPE_ADMIN,
            'name' => 'Super Admin',
            'email' => 'admin@admin.com',
            'password' => 'secret',
            'email_verified_at' => now(),
            'active' => true,
        ]);


        $users = [
            ['name' => 'Karma Samten Wangda', 'email' => 'kenpachisamten97@gmail.com', 'password' => bcrypt('KarmaS@2024')],
            ['name' => 'Kinley Dem', 'email' => 'Kinley232@gmail.com', 'password' => bcrypt('KinleyDem@2024')],
            ['name' => 'Devika Pradhan', 'email' => 'devikaa100@gmail.com', 'password' => bcrypt('Devi@2024')],
            ['name' => 'Tshewang', 'email' => 'tshewang77312183@gmail.com', 'password' => bcrypt('T@2024123')],
            ['name' => 'Tshering Wangchuk', 'email' => 'shatootsheri808@gmail.com', 'password' => bcrypt('Twangchuk@123')],
            ['name' => 'Namgay Dema', 'email' => 'namgaydolmak@gmail.com', 'password' => bcrypt('Ndema@2024')],
            ['name' => 'Karma Wangdi', 'email' => 'kamwgthai@gmail.com', 'password' => bcrypt('Kwangdi@2024')],
            ['name' => 'Namgay Wangchuk', 'email' => 'Nwangchuk1716@gmail.com', 'password' => bcrypt('Namgay@1234')],
            ['name' => 'Thinley Dorji', 'email' => 'bbspgreporter@gmail.com', 'password' => bcrypt('Thinley@12345')],
            ['name' => 'Tashi Yangden', 'email' => 'tyangdenn@gmail.com', 'password' => bcrypt('TashiY@2024')],
            ['name' => 'Kinley Wangchuk', 'email' => 'kinleyw707@gmail.com', 'password' => bcrypt('KinleyWangchuk@2024')],
            ['name' => 'Passang Dorji', 'email' => 'kinleynnamsangdorji@gmail.com', 'password' => bcrypt('Pdorji@2024')],
            ['name' => 'Karma Wangdi', 'email' => 'kwangdi@bbs.bt', 'password' => bcrypt('Karma2024@')],
            ['name' => 'Sonam Darjay', 'email' => 'pemathuktop2014@gmail.com', 'password' => bcrypt('Sdarjay@2024')],
            ['name' => 'Ngawang Tenzin', 'email' => 'Tenzingya7@gmail.com', 'password' => bcrypt('NTenzin1234@')],
            ['name' => 'Sonam Tshering', 'email' => 'manosst95@gmail.com', 'password' => bcrypt('Sonamtshering@1234')],
            ['name' => 'Pema Tshewang', 'email' => 'pematshewang945@gmail.com', 'password' => bcrypt('PemaT@2024$')],
            ['name' => 'Changa Dorji', 'email' => 'fifteendorjee@gmail.com', 'password' => bcrypt('Cdorji@123456')],
            ['name' => 'Pema Samdrup', 'email' => 'pemasamdrup76@gmail.com', 'password' => bcrypt('SamdrupP@2024')],
            ['name' => 'Kinzang Lhadon', 'email' => 'Klhaday98@gmail.com', 'password' => bcrypt('KinzangLha@2024')],
        ];

        foreach ($users as $userData) {
            User::create([
                'type' => User::TYPE_ADMIN,
                'name' => $userData['name'],
                'email' => $userData['email'],
                'password' => $userData['password'],
                'email_verified_at' => now(),
                'active' => true,
            ]);
        }

        // if (app()->environment(['local', 'testing'])) {
        //     User::create([
        //         'type' => User::TYPE_USER,
        //         'name' => 'Test User',
        //         'email' => 'user@user.com',
        //         'password' => 'secret',
        //         'email_verified_at' => now(),
        //         'active' => true,
        //     ]);
        // }

        $this->enableForeignKeys();
    }
}
