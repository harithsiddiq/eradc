<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use App\Models\Category;

class HomeController extends Controller
{
    public function index()
    {
        $posts = Category::with(['posts' => function ($q) {
                $q->where('show_on_landing', true);
            }])
            ->whereNull('parent_id')
            ->get();

        return view('main', ['posts' => $posts]);
    }
}
