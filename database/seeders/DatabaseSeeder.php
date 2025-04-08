<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Category;
use App\Models\Project;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $user = User::create([
            "name" => "Test User",
            "email" => "test@example.com",
            "password" => bcrypt("password"),
            "role" => "student",
        ]);

        $category = Category::create(["name" => "Technology"]);

        Project::create([
            "title" => "Sample Project",
            "description" => "A test project.",
            "video_url" => "https://example.com/video",
            "status" => "Published",
            "category_id" => $category->id,
            "uploaded_by" => $user->id,
        ]);

        Project::create([
            "title" => "Another Project",
            "description" => "Another test project.",
            "video_url" => "https://example.com/video2",
            "status" => "Published",
            "category_id" => $category->id,
            "uploaded_by" => $user->id,
        ]);
    }
}
