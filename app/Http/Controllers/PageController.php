<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use App\Models\User;

use Illuminate\Http\Request;

class PageController extends Controller
{

    public function allPosts()
    {
        $header = 'Posts';
        $title = 'Posts';
        if (request('category')) {
            $category = Category::firstWhere('slug', request('category'));
            $header = 'All Posts In ' . $category->name;
        } elseif (request('user')) {
            $user = User::firstWhere('username', request('user'));
            $header = 'All Posts By ' . $user->name;
        }


        return view('posts', [
            'title' => $title,
            'header' => $header,
            'posts' => Post::latest()->Filter(request(['category', 'search', 'user']))->paginate(8)->withQueryString()
        ]);
    }

    public function viewPost(Post $title)
    {
        return view('post', [
            'post' => $title->load(['category', 'user']),
            'title' => 'Post',

        ]);
    }

    public function byCategory(Category $category)
    {
        return view('posts', [
            'title' => 'Posts In ' . $category->name,
            'posts' => $category->posts->load('category', 'user')

        ]);
    }

    public function byUser(User $user)
    {
        return view('posts', [
            'title' => 'Posts By : ' . $user->name,
            'posts' => $user->posts->load('category', 'user')

        ]);
    }
}