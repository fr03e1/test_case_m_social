<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\MovieResource;
use App\Http\Services\MovieService;
use App\Models\Movie;
use Illuminate\Http\Client\Pool;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MovieController extends Controller
{

    public function __construct(
        private MovieService $movieService,
    )
    {
        $this->middleware('customAuth')->except(['index']);
    }

    public function index(Request $request): JsonResponse
    {
        $movies = $this->movieService->getAllMovies(
            (int) $request->limit,(int) $request->page
        );

        return new JsonResponse(
            MovieResource::collection($movies)
                ->response()
                ->getData(true)
        );
    }


    public function addToFavorite(Request $request, Movie $movie): JsonResponse
    {
        $userId = $request->header('User_id');

        if(!$userId) {
            return new JsonResponse('Not Authorized',401);
        }

        $result = $this->movieService->addToFavorite((int) $userId, $movie);

        if(!$result) {
            return new JsonResponse('Something went wrong',500);
        }

        return new JsonResponse('Movie was successfully added to favorite',201);
    }

    public function deleteFromFavorite(Request $request, Movie $movie): JsonResponse
    {

        $userId = $request->header('User_id');

        if(!$userId) {
            return new JsonResponse('Not Authorized',401);
        }

        $result = $this->movieService->deleteFromFavorite((int) $userId,  $movie);

        if(!$result) {
            return new JsonResponse('Something went wrong',500);
        }

        return new JsonResponse('Movie was successfully deleted from favorite');
    }

    public function getMoviesNotInFavorite(Request $request): JsonResponse
    {
        $userId = $request->header('User_id');

        if(!$userId) {
            return new JsonResponse('Not Authorized',401);
        }

        $loaderType = $request->loaderType ?? 'sql';

        if($loaderType==='sql') {
           $movies = $this->movieService->notInFavoriteSql((int) $userId);
        }

        if($loaderType==='inMemory') {
            $movies = $this->movieService->notInFavoriteMemory((int) $userId);
        }

        return new JsonResponse(MovieResource::collection($movies));
    }
}
