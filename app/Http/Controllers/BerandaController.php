<?php

namespace App\Http\Controllers;

use App\Models\Berita;
use App\Models\Profile;
use App\Models\Unggulan;
use Illuminate\Http\Request;

class BerandaController extends Controller
{
    public function index()
    {
        $profile = Profile::first();
        $carousels = Berita::orderBy('created_at', 'desc')->take(5)->get();
        $unggulan = Unggulan::orderBy('created_at', 'desc')->take(3)->get();
        return view('pages.beranda', compact('profile', 'carousels', 'unggulan'));
    }
}
