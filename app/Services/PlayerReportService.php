<?php

namespace App\Services;

use App\Models\Player;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class PlayerReportService
{
    protected Builder $playerBuilder;
    protected Request $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->playerBuilder = Player::query();
    }

    /**
     * @return Builder
     */
    public function build(): Builder
    {
        $this->setPlayers();
        $this->setFilters();
        return $this->playerBuilder;
    }

    /**
     * @return void
     */
    protected function setPlayers(): void
    {
        $this->playerBuilder->whereHas('group', function (Builder $query) {
            $query->where('responsible_user_id', auth()->id());
        });
    }

    /**
     * @return void
     */
    protected function setFilters(): void
    {
        if ($player = $this->request->get('player')) {
            $this->playerBuilder->whereIn('id', $player);
        }

        if ($gender = $this->request->get('gender')) {
            $this->playerBuilder->where('gender', $gender);
        }

        if ($educationLevel = $this->request->get('education_level')) {
            $this->playerBuilder->whereHas('group', function (Builder $query) use ($educationLevel) {
                $query->whereIn('education_level', $educationLevel);
            });
        }

        if ($activityArea = $this->request->get('activity_area')) {
            $this->playerBuilder->whereHas('group.institution', function (Builder $query) use ($activityArea) {
                $query->whereIn('activity_area_id', $activityArea);
            });
        }

        if ($character = $this->request->get('character')) {
            $this->playerBuilder->where('character', $character);
        }

        if ($this->request->filled('age_min') || $this->request->filled('age_max')) {
            $this->playerBuilder->whereBetween('age', [
                $this->request->input('age_min', 6),
                $this->request->input('age_max', 12)
            ]);
        }
    }
}
