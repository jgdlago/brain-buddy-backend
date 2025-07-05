<?php

namespace App\Http\Controllers;

use App\Enums\CharacterEnum;
use App\Enums\EducationLevelEnum;
use App\Enums\GenderEnum;
use App\Http\Requests\GroupRequest;
use App\Http\Resources\GroupResource;
use App\Models\Group;
use App\Services\GroupReportService;
use Dedoc\Scramble\Attributes\QueryParameter;
use Illuminate\Database\Eloquent\Collection;
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
     * @param Group $group
     * @return JsonResource
     */
    public function show(Group $group): JsonResource
    {
        return new GroupResource($group);
    }

    /**
     * @param Group $group
     * @param GroupRequest $request
     * @return JsonResource
     */
    public function update(Group $group, GroupRequest $request): JsonResource
    {
        $group->update($request->validated());
        return new GroupResource($group);
    }

    /**
     * @param Group $group
     * @return Response
     */
    public function destroy(Group $group): Response
    {
        $group->delete();
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

    /**
     * @return Response
     */
    public function listEducationLevel(): Response
    {
        return response(EducationLevelEnum::list());
    }
}
