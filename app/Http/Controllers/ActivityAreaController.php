<?php

namespace App\Http\Controllers;

use App\Http\Requests\ActivityAreaRequest;
use App\Http\Resources\ActivityAreaResource;
use App\Models\ActivityArea;
use Dedoc\Scramble\Attributes\Group;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;
use Illuminate\Pagination\LengthAwarePaginator;

#[Group('Área de atividade')]
class ActivityAreaController extends Controller
{
    /**
     * @param Request $request
     * @return LengthAwarePaginator
     */
    public function index(Request $request): LengthAwarePaginator
    {
        return ActivityArea::getToApi($request);
    }

    /**
     * @param ActivityAreaRequest $request
     * @return JsonResource
     */
    public function store(ActivityAreaRequest $request): JsonResource
    {
        return new ActivityAreaResource(
            ActivityArea::create($request->validated())
        );
    }

    /**
     * @param ActivityArea $activityArea
     * @return JsonResource
     */
    public function show(ActivityArea $activityArea): JsonResource
    {
        return new ActivityAreaResource($activityArea);
    }

    /**
     * @param ActivityArea $activityArea
     * @param ActivityAreaRequest $request
     * @return JsonResource
     */
    public function update(ActivityArea $activityArea, ActivityAreaRequest $request): JsonResource
    {
        $activityArea->update($request->validated());
        return new ActivityAreaResource($activityArea);
    }

    /**
     * @param ActivityArea $activityArea
     * @return Response
     */
    public function destroy(ActivityArea $activityArea): Response
    {
        $activityArea->delete();
        return response()->noContent(204);
    }

    /**
     * @return Response
     */
    public function list(): Response
    {
        return response(
            ActivityArea::all()->list('id', 'name')
        );
    }
}
