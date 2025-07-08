<?php

namespace App\Http\Controllers;

use App\Services\PlayerProgressServiceReport;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class PlayerProgressController extends Controller
{
    protected PlayerProgressServiceReport $playerProgressServiceReport;
    public function __construct(PlayerProgressServiceReport $playerProgressServiceReport)
    {
        $this->playerProgressServiceReport = $playerProgressServiceReport;
    }

    /**
     * @param Request $request
     * @return Collection
     */
    public function index(Request $request): Collection
    {
        return collect($this->playerProgressServiceReport->getGraphData($request));
    }
}
