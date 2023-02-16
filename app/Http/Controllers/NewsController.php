<?php

namespace App\Http\Controllers;

use App\Providers\NewsDataProvider;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    protected $newsDataProvider;

    public function __construct(NewsDataProvider $newsDataProvider)
    {
        $this->newsDataProvider = $newsDataProvider;
    }

    public function index(Request $request)
    {
        $newsData = $this->newsDataProvider->getNewsData(
            $request->get('category'),
            $request->get('source'),
            $request->get('query'),
            $request->get('page'),
        );

        return response()->json([
            'news' => $newsData
        ], 200);
    }
}
