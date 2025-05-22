<?php

namespace App\Http\Controllers;

use App\Http\Requests\PlayerRequest;
use App\Http\Resources\PlayerResource;
use App\Models\Player;
use Dedoc\Scramble\Attributes\Group;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;
use Illuminate\Pagination\LengthAwarePaginator;

#[Group('Players')]
class PlayerController extends Controller
{
    /**
     * @param Request $request
     * @return LengthAwarePaginator
     */
    public function index(Request $request): LengthAwarePaginator
    {
        return Player::getToApi($request);
    }

    /**
     * @param PlayerRequest $request
     * @return JsonResource
     */
    public function store(PlayerRequest $request): JsonResource
    {
        return new PlayerResource(
            Player::create($request->validated())
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
