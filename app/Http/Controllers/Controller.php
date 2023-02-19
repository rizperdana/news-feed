<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * @OA\Info(
 *          title="NewsFeed API",
 *          version="0.1",
 *          description="This is a news feed api documentation",
 *          version="1.0.0",
 *          @OA\Contact(
 *              email="perdana.rizki16@gmail.com"
 *          )
 * ),
 * @OA\Server(
 *     description="NewsFeed API Server",
 *     url="http://127.0.0.1:8000/api"
 * )
 */
class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
}
