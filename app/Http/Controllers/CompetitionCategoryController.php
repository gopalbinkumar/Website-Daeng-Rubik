<?php

namespace App\Http\Controllers;
use App\Models\CompetitionCategory;
use Illuminate\Http\Request;

class CompetitionCategoryController extends Controller
{
    public function index()
    {
         $competitionCategories = CompetitionCategory::all();

        return view('admin.events.index', compact('competitionCategories'));
    }
}
