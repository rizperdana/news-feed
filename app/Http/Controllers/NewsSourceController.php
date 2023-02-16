<?php

namespace App\Http\Controllers;

use App\Http\Resources\NewsSourceResource;
use App\Models\NewsSource;
use Illuminate\Http\Request;

class NewsSourceController extends Controller
{
    public function index()
    {
        $sources = NewsSource::all();
        return NewsSourceResource::collection($sources);
    }
}
