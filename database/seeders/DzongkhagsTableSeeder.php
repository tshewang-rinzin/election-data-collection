<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DzongkhagsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $dzongkhags = [
            ['name' => 'Bumthang'],
            ['name' => 'Chukha'],
            ['name' => 'Dagana'],
            ['name' => 'Gasa'],
            ['name' => 'Haa'],
            ['name' => 'Lhuentse'],
            ['name' => 'Mongar'],
            ['name' => 'Paro'],
            ['name' => 'Pemagatshel'],
            ['name' => 'Punakha'],
            ['name' => 'Samdrup Jongkhar'],
            ['name' => 'Samtse'],
            ['name' => 'Sarpang'],
            ['name' => 'Thimphu'],
            ['name' => 'Trashigang'],
            ['name' => 'Trashiyangtse'],
            ['name' => 'Trongsa'],
            ['name' => 'Tsirang'],
            ['name' => 'Wangdue Phodrang'],
            ['name' => 'Zhemgang'],
        ];

        // Insert data into the table
        DB::table('dzongkhags')->insert($dzongkhags);
    }
}
