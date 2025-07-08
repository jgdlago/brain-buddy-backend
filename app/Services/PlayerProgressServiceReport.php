<?php

namespace App\Services;

use App\Models\PlayerProgress;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PlayerProgressServiceReport
{
    protected Builder $progressBuilder;
    protected Request $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->progressBuilder = PlayerProgress::query();
    }

    public function getGraphData(): array
    {
        $this->applyBaseConditions();
        $this->applyFilters();

        return $this->calculateAggregates();
    }

    protected function applyBaseConditions(): void
    {
        $this->progressBuilder->whereHas('player.group', function (Builder $query) {
            $query->where('responsible_user_id', Auth::id());
        });
    }

    protected function applyFilters(): void
    {
        if ($players = $this->request->get('player')) {
            $this->progressBuilder->whereIn('player_id', (array)$players);
        }

        if ($gender = $this->request->get('gender')) {
            $this->progressBuilder->whereHas('player', function (Builder $query) use ($gender) {
                $query->where('gender', $gender);
            });
        }

        if ($educationLevel = $this->request->get('education_level')) {
            $this->progressBuilder->whereHas('player.group', function (Builder $query) use ($educationLevel) {
                $query->whereIn('education_level', (array)$educationLevel);
            });
        }

        if ($activityArea = $this->request->get('activity_area')) {
            $this->progressBuilder->whereHas('player.group.institution', function (Builder $query) use ($activityArea) {
                $query->whereIn('activity_area_id', (array)$activityArea);
            });
        }

        if ($character = $this->request->get('character')) {
            $this->progressBuilder->whereHas('player', function (Builder $query) use ($character) {
                $query->where('character', $character);
            });
        }

        if ($this->request->filled('age_min') || $this->request->filled('age_max')) {
            $this->progressBuilder->whereHas('player', function (Builder $query) {
                $query->whereBetween('age', [
                    $this->request->input('age_min', 6),
                    $this->request->input('age_max', 12)
                ]);
            });
        }

        if ($level = $this->request->get('level')) {
            $this->progressBuilder->whereIn('level_id', (array)$level);
        }
    }

    protected function calculateAggregates(): array
    {
        // Construir query otimizada com JOINs
        $query = $this->buildOptimizedQuery();

        $result = $query->first();

        return [
            'total_correct' => (int) ($result->total_correct ?? 0),
            'total_wrong' => (int) ($result->total_wrong ?? 0),
            'total_attempts' => (int) ($result->total_attempts ?? 0),
            'total_completed' => (int) ($result->total_completed ?? 0),
            'total_help_flags' => (int) ($result->total_help_flags ?? 0),
        ];
    }

    protected function buildOptimizedQuery()
    {
        return DB::table('player_progress')
            ->join('players', 'player_progress.player_id', '=', 'players.id')
            ->join('groups', 'players.group_id', '=', 'groups.id')
            ->leftJoin('player_help_flags', function($join) {
                $join->on('player_help_flags.player_id', '=', 'player_progress.player_id')
                    ->on('player_help_flags.level_id', '=', 'player_progress.level_id');
            })
            ->where('groups.responsible_user_id', Auth::id())
            ->when($this->request->get('player'), function ($query, $players) {
                $query->whereIn('player_progress.player_id', (array)$players);
            })
            ->when($this->request->get('gender'), function ($query, $gender) {
                $query->where('players.gender', $gender);
            })
            ->when($this->request->get('education_level'), function ($query, $educationLevel) {
                $query->whereIn('groups.education_level', (array)$educationLevel);
            })
            ->when($this->request->get('activity_area'), function ($query, $activityArea) {
                $query->join('institutions', 'groups.institution_id', '=', 'institutions.id')
                    ->whereIn('institutions.activity_area_id', (array)$activityArea);
            })
            ->when($this->request->get('character'), function ($query, $character) {
                $query->where('players.character', $character);
            })
            ->when($this->request->filled('age_min') || $this->request->filled('age_max'), function ($query) {
                $query->whereBetween('players.age', [
                    $this->request->input('age_min', 6),
                    $this->request->input('age_max', 12)
                ]);
            })
            ->when($this->request->get('level'), function ($query, $level) {
                $query->whereIn('player_progress.level_id', (array)$level);
            })
            ->selectRaw('
                COALESCE(SUM(player_progress.total_correct), 0) as total_correct,
                COALESCE(SUM(player_progress.total_wrong), 0) as total_wrong,
                COALESCE(SUM(player_progress.total_attempts), 0) as total_attempts,
                COUNT(DISTINCT CASE WHEN player_progress.completed = true THEN player_progress.player_id END) as total_completed,
                COUNT(DISTINCT player_help_flags.id) as total_help_flags
            ');
    }
}
