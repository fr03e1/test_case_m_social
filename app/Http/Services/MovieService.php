<?php

declare(strict_types=1);

namespace App\Http\Services;

use App\Models\Movie;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class MovieService
{

    public function __construct(
        private Movie $movie,
        private User $user,

    )
    {
    }

    public function getAllMovies(?int $limit, ?int $page ): LengthAwarePaginator
    {
        return $this->movie::paginate($limit ?? 10, ['*'],'page',$page ?? 1);
    }

    public function addToFavorite(int $userId, Movie $movie): array
    {
        return $movie->users()->sync([$userId]);
    }

    public function deleteFromFavorite(int $userId, Movie $movie): array
    {
       return $movie->users()->detach($userId);
    }

    public function notInFavoriteSql(int $userId): Collection
    {

        $movies = $this->movie::with('users')
            ->whereDoesntHave('users', function($query) use ($userId) {
                $query->where('users.id', $userId);
            })
            ->get();

        return $movies;
    }

    public function notInFavoriteMemory(int $userId): Collection
    {
        $movies = $this->movie::all();
        $user = $this->user::find($userId);
        $favoritesMovies = $user->movies()->get();
        $diff = $movies->diff($favoritesMovies);
        return $diff;
    }
}
