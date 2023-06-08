<?php

namespace App\Console\Commands;

use App\Models\Movie;
use Illuminate\Console\Command;
use Illuminate\Http\Client\Pool;
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

    }
}
