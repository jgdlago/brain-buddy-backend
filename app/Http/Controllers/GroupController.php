<?php

namespace App\Http\Controllers;

use App\Http\Requests\GroupRequest;
use App\Http\Resources\GroupResource;
use App\Models\Group;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;
use Illuminate\Pagination\LengthAwarePaginator;
use Dedoc\Scramble\Attributes\Group as ScrambleGroup;

#[ScrambleGroup('Grupos (turmas)')]
class GroupController extends Controller
{
    /**
     * @param Request $request
     * @return AnonymousResourceCollection
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        return GroupResource::collection(Group::getToApi($request));
    }

    /**
     * @param GroupRequest $request
     * @return JsonResource
     */
    public function store(GroupRequest $request): JsonResource
    {
        return new GroupResource(
            Group::create($request->validated())
        );
    }

    /**
     * @param Group $institution
     * @return JsonResource
     */
    public function show(Group $institution): JsonResource
    {
        return new GroupResource($institution);
    }

    /**
     * @param Group $institution
     * @param GroupRequest $request
     * @return JsonResource
     */
    public function update(Group $institution, GroupRequest $request): JsonResource
    {
        $institution->update($request->validated());
        return new GroupResource($institution);
    }

    /**
     * @param Group $institution
     * @return Response
     */
    public function destroy(Group $institution): Response
    {
        $institution->delete();
        return response()->noContent(204);
    }

    /**
     * @return Response
     */
    public function list(): Response
    {
        return response(
            Group::all()->list('id', 'name')
        );
    }
}
