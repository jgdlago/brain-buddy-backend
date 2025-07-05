<?php

namespace App\Http\Controllers;

use App\Http\Requests\HelpFlagRequest;
use App\Http\Resources\HelpFlagResource;
use App\Models\HelpFlag;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;

class HelpFlagController extends Controller
{
    /**
     * @param Request $request
     * @return AnonymousResourceCollection
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        return HelpFlagResource::collection(HelpFlag::getToApi($request));
    }

    /**
     * @param HelpFlagRequest $request
     * @return JsonResource
     */
    public function store(HelpFlagRequest $request): JsonResource
    {
        return new HelpFlagResource(
            HelpFlag::create($request->validated())
        );
    }

    /**
     * @param HelpFlag $helpFlag
     * @return JsonResource
     */
    public function show(HelpFlag $helpFlag): JsonResource
    {
        return new HelpFlagResource($helpFlag);
    }

    /**
     * @param HelpFlag $helpFlag
     * @param HelpFlagRequest $request
     * @return JsonResource
     */
    public function update(HelpFlag $helpFlag, HelpFlagRequest $request): JsonResource
    {
        $helpFlag->update($request->validated());
        return new HelpFlagResource($helpFlag);
    }

    /**
     * @param HelpFlag $helpFlag
     * @return Response
     */
    public function destroy(HelpFlag $helpFlag): Response
    {
        $helpFlag->delete();
        return response()->noContent(204);
    }
}
