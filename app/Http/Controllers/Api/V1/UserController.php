<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\User\UserRequest;
use App\Http\Resources\V1\UserResource;
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

    public function show(Request $request): JsonResponse
    {
        $userid = $request->header('User_id');

        if(!$userid) {
            return new JsonResponse('Not Authorized',401);
        }

        $user = $this->user->find($userid);
        return new JsonResponse(UserResource::make($user));
    }

    public function update(UserRequest $request): JsonResponse
    {
        $data = $request->validated();
        $userid = $request->header('User_id');

        if(!$userid) {
            return new JsonResponse('Not Authorized',401);
        }

        $this->user->where('id',$userid)->update($data);
        return new JsonResponse('User was successfully updated',201);
    }

    public function delete(Request $request): JsonResponse
    {
        $userid = $request->header('User_id');

        if(!$userid) {
            return new JsonResponse('Not Authorized',401);
        }

        $this->user->where('id',$userid)->delete();
        return new JsonResponse('User was successfully deleted',201);
    }
}
