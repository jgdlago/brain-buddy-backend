<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserInstitutionRequest;
use App\Http\Resources\UserResource;
use App\Models\Institution;
use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;

class UserController extends Controller
{
    /**
     * @param User $user
     * @param UserInstitutionRequest $request
     * @return JsonResource
     */
    public function addToInstitution(User $user, UserInstitutionRequest $request): JsonResource
    {
        $user->institutions()->attach($request->validated()['institutions']);
        return new UserResource($user);
    }

    /**
     * @param User $user
     * @param Institution $institution
     * @return JsonResource
     */
    public function removeFromInstitution(User $user, Institution $institution): JsonResource
    {
        $user->institutions()->detach($institution);
        return new UserResource($user);
    }

    /**
     * @return Response
     */
    public function list(): Response
    {
        return response(
            User::all()->list('id', 'name')
        );
    }
}
