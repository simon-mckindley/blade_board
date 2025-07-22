<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Post;
use App\Models\Tag;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $user = User::factory()->create([
            'name' => 'Super Admin',
            'email' => 'super@mail.com',
            'role' => 'super',
            'password' => Hash::make('super123'),
        ]);

        User::factory(3)->create();

        $tags = Tag::factory()->count(5)->create(); // If you create a TagFactory

        $post = Post::factory()
            ->for($user)
            ->create();

        $post->tags()->attach($tags->random(2)); // Attach 2 random tags to post
    }
}
