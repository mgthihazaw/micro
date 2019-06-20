<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

	/**
	 * @OA\Info(title="My First API", version="0.1")
	 */

	/**
	 * @OA\Get(
	 *     path="/api/resource.json",
	 *     @OA\Response(response="200", description="An example resource")
	 * )
	 */
}
