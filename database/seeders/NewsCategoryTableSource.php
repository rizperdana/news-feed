<?php

namespace Database\Seeders;

use App\Models\NewsCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class NewsCategoryTableSource extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        NewsCategory::truncate();
        $sources = [
            ['name'=> 'politics', 'is_active' => 1],
            ['name'=> 'sports', 'is_active' => 1],
            ['name'=> 'entertainments', 'is_active' => 1],
            ['name'=> 'technologies', 'is_active' => 1],
            ['name'=> 'games', 'is_active' => 1],
            ['name'=> 'healths', 'is_active' => 1],
            ['name'=> 'educations', 'is_active' => 1],
            ['name'=> 'sciences', 'is_active' => 1],
            ['name'=> 'business', 'is_active' => 1],
            ['name'=> 'travels', 'is_active' => 1],
        ];

        foreach($sources as $source) {
            NewsCategory::create($source);
        }
    }
}
