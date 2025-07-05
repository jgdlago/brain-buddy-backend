<?php

namespace App\Http\Controllers;

use App\Enums\CharacterEnum;
use App\Enums\EducationLevelEnum;
use App\Enums\GenderEnum;
use App\Http\Requests\PlayerRequest;
use App\Http\Resources\PlayerResource;
use App\Models\Group;
use App\Models\Player;
use App\Services\PlayerReportService;
use Dedoc\Scramble\Attributes\QueryParameter;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;
use Illuminate\Pagination\LengthAwarePaginator;
use Dedoc\Scramble\Attributes\Group as ScrambleGroup;

#[ScrambleGroup('Players')]
class PlayerController extends Controller
{
    protected PlayerReportService $reportService;
    public function __construct(PlayerReportService $reportService)
    {
        $this->reportService = $reportService;
    }

    /**
     * @param Request $request
     * @return AnonymousResourceCollection
     */
    #[QueryParameter(name: 'group', type: 'int')]
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = Player::query();
        if ($request->has('group')) {
            $query = $query->where('group_id', $request->get('group'));
        }

        return PlayerResource::collection($query->getToApi($request));
    }

    /**
     * @param string $groupAccessCode
     * @param PlayerRequest $request
     * @return JsonResource
     */
    public function store(string $groupAccessCode, PlayerRequest $request): JsonResource
    {
        $group = Group::where('access_code', $groupAccessCode)->firstOrFail();
        return new PlayerResource(
            $group->players()->create($request->validated())
        );
    }

    /**
     * @param Player $player
     * @return JsonResource
     */
    public function show(Player $player): JsonResource
    {
        return new PlayerResource($player);
    }

    /**
     * @param Player $player
     * @param PlayerRequest $request
     * @return JsonResource
     */
    public function update(Player $player, PlayerRequest $request): JsonResource
    {
        $player->update($request->validated());
        return new PlayerResource($player);
    }

    /**
     * @param Player $player
     * @return Response
     */
    public function destroy(Player $player): Response
    {
        $player->delete();
        return response()->noContent(204);
    }

    /**
     * @return Response
     */
    public function list(): Response
    {
        return response(
            Player::all()->list('id', 'name')
        );
    }

    /**
     * @param Request $request
     * @return JsonResource
     */
    #[QueryParameter('group', type: 'array')]
    #[QueryParameter('player', type: 'array')]
    #[QueryParameter('gender', type: GenderEnum::class)]
    #[QueryParameter('education_level', type: EducationLevelEnum::class)]
    #[QueryParameter('activity_area', type: 'array')]
    #[QueryParameter('character', type: CharacterEnum::class)]
    #[QueryParameter('age_min', type: 'int')]
    #[QueryParameter('age_max', type: 'int')]
    public function report(Request $request): JsonResource
    {
        return PlayerResource::collection(
            $this->reportService->build($request)->getToApi($request)
        );
    }
}
