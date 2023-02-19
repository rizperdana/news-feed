<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\NewsDataService;
use App\Models\Country;

class NewsDataController extends Controller
{
    protected $newsDataService;

    public function __construct(NewsDataService $newsDataService)
    {
        $this->newsDataService = $newsDataService;
    }

    /**
     * @OA\Post(
     *     path="/news",
     *     operationId="getNews",
     *     tags={"news"},
     *     summary="Fetch News",
     *     description="Fetch news from various source and filter by certain keyword",
     *     security={ {"bearer": {} }},
     *     @OA\Response(response="200", description="Fetch news response with criteria."),
     *     @OA\RequestBody(
     *          required=true,
     *          description="Find news with criteria",
     *          @OA\JsonContent(
     *             required={"countryCode","languageCode", "category"},
     *             @OA\Property(property="countryCode", type="string", example="us"),
     *             @OA\Property(property="languageCode", type="string", example="en"),
     *             @OA\Property(property="category", type="string", example="technology"),
     *             @OA\Property(property="query", type="string", example="Php"),
     *          ),
     *     )
     * )
     */
    public function index(Request $request)
    {
        $page = 1;
        if ($request->get('page')) {
            $page = $request->get('page');
        }

        $newsData = $this->newsDataService->getNewsData(
            $request->get('countryCode'),
            $request->get('languageCode'),
            $request->get('category'),
            $request->get('query'),
            $page
        );

        return response()->json([
            'news' => $newsData
        ], 200);
    }

    /**
     * Retrieve news data from https://newsdata.io/ based on the given country
     *
     * This function uses information from the database to request a response
     * from newsData.io API
     *
     * @param string $countryCode
     * @param int $page
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function countryNewsData($countryCode, $page = 1)
    {
        $country = Country::where('code', $countryCode)->first();

        // Check if the country exists
        if (!$country) {
            return response()->json(['error' => 'Country not found'], 404);
        }

         // Check if the country has a category
         if (!$country->categories()->exists()) {
            return response()->json([
                'error' => 'Country does not have any category to search'
            ], 400);
        }

        // Create a string of languageCode and category
        $languageCode = $country->languages()->pluck('language')->implode(',');
        $category = $country->categories()->pluck('name')->implode(',');

        $newsData = $this->newsDataService->getNewsData(
            $countryCode,
            $languageCode,
            $category,
            $page
        );

        return response()->json([
            'news' => $newsData
        ], 200);
    }
}
