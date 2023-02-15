<?php

namespace Database\Seeders;

use App\Models\NewsSource;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class NewsSourceTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        NewsSource::truncate();
        $sources = [
            [
                'name' => 'CNN',
                'news_code' => 'cnn',
                'description' => 'Cable News Network (CNN) is a multinational news-based pay television channel.',
                'url' => 'https://www.cnn.com/',
                'is_active' => 1,
            ],
            [
                'name' => 'BBC News',
                'news_code' => 'bbc',
                'description' => 'BBC News is an operational business division of the British Broadcasting Corporation responsible for the gathering and broadcasting of news and current affairs.',
                'url' => 'https://www.bbc.com/news',
                'is_active' => 1,
            ],
            [
                'name' => 'Reuters',
                'news_code' => 'reuters',
                'description' => 'Reuters is an international news organization owned by Thomson Reuters.',
                'url' => 'https://www.reuters.com/',
                'is_active' => 1,
            ],
            // add more sources here
        ];

        foreach ($sources as $source) {
            NewsSource::create($source);
        }
    }
}
