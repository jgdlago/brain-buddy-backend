<?php

namespace App\Services;

use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection as SupportCollection;

class PlayerProgressServiceReport
{
    protected Request $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @return array
     */
    public function getGraphData(): array
    {
        $baseQuery = $this->buildBaseQuery();

        // Calcula totais gerais
        $totals = $this->calculateAggregates($baseQuery);

        // Calcula totais individuais por player
        $players = $this->calculatePerPlayer($baseQuery);

        return [
            'totals' => $totals,
            'players' => $players
        ];
    }

    /**
     * @return QueryBuilder
     */
    protected function buildBaseQuery(): QueryBuilder
    {
        $query = DB::table('player_progress')
            ->join('players', 'player_progress.player_id', '=', 'players.id')
            ->join('groups', 'players.group_id', '=', 'groups.id')
            ->leftJoin('player_help_flags', function($join) {
                $join->on('player_help_flags.player_id', '=', 'player_progress.player_id')
                    ->on('player_help_flags.level_id', '=', 'player_progress.level_id');
            })
            ->where('groups.responsible_user_id', Auth::id());

        $this->applyFiltersToQuery($query);

        return $query;
    }

    /**
     * @param QueryBuilder $query
     * @return void
     */
    protected function applyFiltersToQuery(QueryBuilder $query): void
    {
        if ($players = $this->request->get('player')) {
            $query->whereIn('player_progress.player_id', (array)$players);
        }

        if ($gender = $this->request->get('gender')) {
            $query->where('players.gender', $gender);
        }

        if ($educationLevel = $this->request->get('education_level')) {
            $query->whereIn('groups.education_level', (array)$educationLevel);
        }

        if ($activityArea = $this->request->get('activity_area')) {
            $query->join('institutions', 'groups.institution_id', '=', 'institutions.id')
                ->whereIn('institutions.activity_area_id', (array)$activityArea);
        }

        if ($character = $this->request->get('character')) {
            $query->where('players.character', $character);
        }

        if ($this->request->filled('age_min') || $this->request->filled('age_max')) {
            $query->whereBetween('players.age', [
                $this->request->input('age_min', 6),
                $this->request->input('age_max', 12)
            ]);
        }

        if ($level = $this->request->get('level')) {
            $query->whereIn('player_progress.level_id', (array)$level);
        }
    }

    /**
     * @param QueryBuilder $baseQuery
     * @return int[]
     */
    protected function calculateAggregates(QueryBuilder $baseQuery): array
    {
        $result = $baseQuery->clone()
            ->selectRaw('
                COALESCE(SUM(player_progress.total_correct), 0) as total_correct,
                COALESCE(SUM(player_progress.total_wrong), 0) as total_wrong,
                COALESCE(SUM(player_progress.total_attempts), 0) as total_attempts,
                COUNT(DISTINCT CASE WHEN player_progress.completed = true THEN player_progress.player_id END) as total_completed,
                COUNT(DISTINCT player_help_flags.id) as total_help_flags
            ')
            ->first();

        return [
            'total_correct' => (int) ($result->total_correct ?? 0),
            'total_wrong' => (int) ($result->total_wrong ?? 0),
            'total_attempts' => (int) ($result->total_attempts ?? 0),
            'total_completed' => (int) ($result->total_completed ?? 0),
            'total_help_flags' => (int) ($result->total_help_flags ?? 0),
        ];
    }

    /**
     * @param QueryBuilder $baseQuery
     * @return SupportCollection
     */
    protected function calculatePerPlayer(QueryBuilder $baseQuery): SupportCollection
    {
        return $baseQuery->clone()
            ->groupBy(
                'players.id',
                'players.name',
                'players.age',
                'players.character',
                'players.performance_flag'
            )
            ->selectRaw('
                players.id,
                players.name,
                players.age,
                players.character,
                players.performance_flag,
                COALESCE(SUM(player_progress.total_correct), 0) as total_correct,
                COALESCE(SUM(player_progress.total_wrong), 0) as total_wrong,
                COALESCE(SUM(player_progress.total_attempts), 0) as total_attempts,
                SUM(CASE WHEN player_progress.completed = true THEN 1 ELSE 0 END) as total_levels_completed,
                COUNT(player_help_flags.id) as total_help_flags
            ')
            ->get()
            ->map(function ($player) {
                return [
                    'id' => $player->id,
                    'name' => $player->name,
                    'age' => $player->age,
                    'character' => $player->character,
                    'performance_flag' => $player->performance_flag,
                    'totals' => [
                        'correct' => (int) $player->total_correct,
                        'wrong' => (int) $player->total_wrong,
                        'attempts' => (int) $player->total_attempts,
                        'levels_completed' => (int) $player->total_levels_completed,
                        'help_flags' => (int) $player->total_help_flags,
                    ]
                ];
            });
    }
}
