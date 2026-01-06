<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\MasterContent;

class UpdateArticleViewsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * This seeder updates article views with random values for testing purposes
     */
    public function run(): void
    {
        // Get all articles
        $articles = MasterContent::where('type_of_page', 'article')
            ->where('status', 1)
            ->get();

        // Update each article with random views
        foreach ($articles as $article) {
            $article->views = rand(50, 1000);
            $article->save();
        }

        $this->command->info('Article views updated successfully!');
        $this->command->info('Total articles updated: ' . $articles->count());
    }
}
