<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $posts = Post::all(); // Ambil semua data post
        return view('dashboard.index', compact('posts'));
    }
}
