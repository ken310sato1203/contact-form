<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use DateTime;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $params = [
            ['id' => '1', 'content' => '商品のお届けについて'],
            ['id' => '2', 'content' => '商品の交換について'],
            ['id' => '3', 'content' => '商品トラブル'],
            ['id' => '4', 'content' => 'ショップへのお問い合わせ'],
            ['id' => '5', 'content' => 'その他'],
        ];

        $now = new DateTime();
        foreach ($params as $param) {
            $param['created_at'] = $now;
            $param['updated_at'] = $now;
            DB::table('categories')->insert($param);
        }
    }
}
