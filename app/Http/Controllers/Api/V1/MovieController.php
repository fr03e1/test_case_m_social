<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Services\MovieService;

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

    }
}
