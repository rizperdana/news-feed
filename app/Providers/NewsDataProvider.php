<?php

namespace App\Providers;

use Exception;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\ValidationException;
use NewsdataIO\NewsdataApi;

class NewsDataProvider extends ServiceProvider
{
    protected $apiKey;


    public function __construct()
    {
        $this->apiKey = env('NEWSDATA_API_KEY');
    }


    public function getNewsData($category, $source, $query, $page)
    {
        try {
            $this->validateInput($category, $source, $query, $page);
            $newsDataApiObj = new NewsdataApi($this->apiKey);
            $newsData = $this->getNewsDataResource($newsDataApiObj, $category, $source, $query, $page);

            return $newsData;
        } catch (ValidationException $e) {
            return response()->json([
                'error' => $e->validator->getMessageBag()
            ], 400);
        } catch (Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }


    private function getNewsDataResource($newsDataApiObj, $category, $source, $query, $page)
    {
        $cacheKey = "news-data-{$category}-{$source}-{$query}-";

        if (Redis::exists($cacheKey.$page)) {
            $newsDataCachePage = json_decode(Redis::get($cacheKey.$page));
            return $newsDataCachePage;
        }

        $requestData = [
            'category' => $category,
            'domain' => $source,
            'q' => $query
        ];


        $newsData = $this->getNewsDataStoreCache($newsDataApiObj, $requestData, $cacheKey, $page);
        return  $newsData;
    }


    private function getNewsDataStoreCache($newsDataApiObj, $requestData, $cacheKey, $page, $currentPageIndex = 1)
    {
        $newsData = $newsDataApiObj->get_latest_news($requestData);
        $newsDataJsonify = json_encode($newsData);

        $this->storeNewsDatainRedis($cacheKey, $currentPageIndex, $newsDataJsonify);

        if ($currentPageIndex < $page) {
            $currentPageIndex++;
            $requestData['page'] = $currentPageIndex;

            return $this->getNewsDataStoreCache($newsDataApiObj, $requestData, $cacheKey, $page, $currentPageIndex);
        }

        return $newsData;
    }


    private function storeNewsDatainRedis($cacheKey, $pageIndex, $newsData)
    {
        if ($newsData) {
            Redis::set($cacheKey.$pageIndex, $newsData);
        }
    }


    private function validateInput($category, $source, $query, $page)
    {
        $validator = Validator::make([
            'category' => $category,
            'source' => $source,
            'query' => $query,
            'page' => $page,
        ], [
            'category' => 'string',
            'source' => 'string',
            'query' => 'string',
            'page' => 'numeric|min:1',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
    }
}
