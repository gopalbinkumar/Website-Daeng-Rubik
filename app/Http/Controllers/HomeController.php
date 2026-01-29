<?php

namespace App\Http\Controllers;
use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function home()
    {
        $featuredProducts = Product::with('primaryImage')
            ->where('is_active', true)
            ->latest()
            ->take(4)
            ->get(); 
            

        return view('pages.home', compact('featuredProducts'));

    }

}
