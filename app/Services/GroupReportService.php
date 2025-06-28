<?php

namespace App\Services;

use App\Models\Group;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GroupReportService
{
    protected Builder $groupBuilder;
    protected Request $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->groupBuilder = Group::query();
    }

    public function build(): Builder
    {
        $this->setGroup();
        $this->setFilters();
        return $this->groupBuilder;
    }

    /**
     * @return void
     */
    protected function setGroup(): void
    {
        $this->request->has('group')
            ? $this->groupBuilder->where('id', $this->request->get('group'))
            : $this->groupBuilder->where('responsible_user_id', Auth::id())
            ->orderBy('created_at')
            ->limit(1);
    }

    /**
     * @return void
     */
    private function setFilters(): void
    {
        if ($this->request->has('player')) {
            $this->groupBuilder->whereIn('player_id', $this->request->get('player'));
        }

        if ($this->request->has('gender')) {
            $this->groupBuilder->where('gender', $this->request->get('gender'));
        }

        if ($this->request->has('education_level')) {
            $this->groupBuilder->whereIn('education_level', $this->request->get('education_level'));
        }

        if ($this->request->has('activity_area')) {
            $this->groupBuilder->whereHas('institution', function (Builder $query) {
                $query->whereIn('activity_area_id', $this->request->get('activity_area'));
            });
        }

        if ($this->request->has('character')) {
            $this->groupBuilder->whereHas('players', function (Builder $query) {
                $query->whereIn('character', $this->request->get('character'));
            });
        }

        if ($this->request->filled('age_min') || $this->request->filled('age_max')) {
            $this->groupBuilder->whereHas('players', function (Builder $query) {
                $minAge = $this->request->get('age_min');
                $maxAge = $this->request->get('age_max');

                if ($minAge && $maxAge) {
                    $query->whereBetween('age', [$minAge, $maxAge]);
                } elseif ($minAge) {
                    $query->where('age', '>=', $minAge);
                } elseif ($maxAge) {
                    $query->where('age', '<=', $maxAge);
                }
            });
        }
    }
}
