<?php

namespace App\Http\Controllers;

use App\Http\Requests\PlayerRequest;
use App\Http\Resources\PlayerResource;
use App\Models\Group;
use App\Models\Player;
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
     * @param Group $group
     * @param PlayerRequest $request
     * @return JsonResource
     */
    public function store(Group $group, PlayerRequest $request): JsonResource
    {
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
}
