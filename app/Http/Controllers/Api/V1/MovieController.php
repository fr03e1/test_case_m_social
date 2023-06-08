<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Services\MovieService;
use App\Models\Movie;
use http\Env\Response;
use Illuminate\Http\Client\Pool;
use Illuminate\Support\Facades\Http;

class MovieController extends Controller
{

    public function __construct(
        private MovieService $movieService,
    )
    {
        $this->middleware('customAuth')->except('index');
    }

    public function index()
    {

        $responses = Http::pool(fn(Pool $pool) => [
            $pool->withToken('cyTrsonKnUSSReE8jR1m')
                ->get('https://the-one-api.dev/v2/movie?page=1&limit=3'),
            $pool->withToken('cyTrsonKnUSSReE8jR1m')
                ->get('https://the-one-api.dev/v2/movie?page=2&limit=3'),
            $pool->withToken('cyTrsonKnUSSReE8jR1m')
                ->get('https://the-one-api.dev/v2/movie?page=3&limit=3'),
        ]);

        foreach ($responses as $eachResponse) {
            $data = $eachResponse['docs'];

            foreach ($data as $movie) {
                Movie::firstOrCreate([
                    'name' => $movie['name'],
                    'budgetInMillions' => $movie['budgetInMillions']
                ]);
            }
        }

        return $data;
    }
}
