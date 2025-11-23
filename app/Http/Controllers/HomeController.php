<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use App\Models\Category;

class HomeController extends Controller
{
    public function index()
    {
        $posts = Category::with('posts')->get();

        return view('main', ['posts' => $posts]);
    }
}
