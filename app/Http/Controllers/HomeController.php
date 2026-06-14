<?php

namespace App\Http\Controllers;

use App\Models\Category;

class HomeController extends Controller
{
    public function index()
    {
        $posts = Category::with(['posts' => function ($q) {
            $q->where('show_on_landing', true);
        }])
            ->whereNull('parent_id')
            ->orderBy('order')
            ->get();

        return view('main', ['posts' => $posts]);
    }
}
