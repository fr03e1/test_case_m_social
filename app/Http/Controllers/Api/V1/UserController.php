<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\User\UserRequest;
use App\Http\Resources\v1\UserResource;
use App\Http\Services\AuthService;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{

    public function __construct(
        private User $user,
    )
    {
        $this->middleware('customAuth');
    }

    public function show(Request $request): UserResource
    {
        $userid = $request->header('User_id');
        $user = $this->user->find($userid);
        return UserResource::make($user);
    }

    public function update(UserRequest $request): JsonResponse
    {
        $data = $request->validated();
        $userid = $request->header('User_id');
        $this->user->where('id',$userid)->update($data);
        return new JsonResponse('User was successfully updated',201);
    }

    public function delete(Request $request): JsonResponse
    {
        $userid = $request->header('User_id');
        $this->user->where('id',$userid)->delete();
        return new JsonResponse('User was successfully delete',201);
    }
}
