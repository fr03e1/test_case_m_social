<?php

namespace App\Console\Commands;

use App\Models\Movie;
use Illuminate\Console\Command;
use Illuminate\Http\Client\Pool;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FetchMovieCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'movie:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetching movies from api';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {

            $responses = Http::pool(fn(Pool $pool) => [
                $pool->withToken(env('MOVIE_API_TOKEN'))
                    ->get(env('MOVIE_API_URL') . '?page=1&limit=3'),
                $pool->withToken(env('MOVIE_API_TOKEN'))
                    ->get(env('MOVIE_API_URL') . '?page=2&limit=3'),
                $pool->withToken(env('MOVIE_API_TOKEN'))
                    ->get(env('MOVIE_API_URL') . '?page=3&limit=3'),
            ]);

            foreach ($responses as $response) {

                if ($response->successful()) {
                    $data = $response['docs'];

                    foreach ($data as $movie) {
                        Movie::firstOrCreate([
                            'name' => $movie['name'],
                            'budgetInMillions' => $movie['budgetInMillions']
                        ]);
                    }
                }

                if ($response->failed()) {
                    return new JsonResponse(['error' => 'Internal Server Error'], 500);
                }
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage() . 'Error occurred while fetching data in cron');
        }
    }
}
