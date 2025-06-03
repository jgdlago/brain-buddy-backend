<?php

namespace App\Http\Controllers;

use App\Http\Requests\InstitutionRequest;
use App\Http\Resources\InstitutionResource;
use App\Models\Institution;
use Dedoc\Scramble\Attributes\Group;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;
use Illuminate\Pagination\LengthAwarePaginator;

#[Group('Instituição')]
class InstitutionController extends Controller
{
    /**
     * @param Request $request
     * @return AnonymousResourceCollection
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        return InstitutionResource::collection(Institution::getToApi($request));
    }

    /**
     * @param InstitutionRequest $request
     * @return JsonResource
     */
    public function store(InstitutionRequest $request): JsonResource
    {
        return new InstitutionResource(
            Institution::create($request->validated())
        );
    }

    /**
     * @param Institution $institution
     * @return JsonResource
     */
    public function show(Institution $institution): JsonResource
    {
        return new InstitutionResource($institution);
    }

    /**
     * @param Institution $institution
     * @param InstitutionRequest $request
     * @return JsonResource
     */
    public function update(Institution $institution, InstitutionRequest $request): JsonResource
    {
        $institution->update($request->validated());
        return new InstitutionResource($institution);
    }

    /**
     * @param Institution $institution
     * @return Response
     */
    public function destroy(Institution $institution): Response
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
            Institution::all()->list('id', 'name')
        );
    }
}
