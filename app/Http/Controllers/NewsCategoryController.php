<?php

namespace App\Http\Controllers;

use App\Http\Resources\NewsCategoryResource;
use App\Models\NewsCategory;
use Illuminate\Http\Request;

class NewsCategoryController extends Controller
{
    public function index()
    {
        $categories = NewsCategory::all();
        return NewsCategoryResource::collection($categories);
    }
}
