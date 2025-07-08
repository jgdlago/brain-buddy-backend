<?php

namespace App\Http\Controllers;

use App\Enums\CharacterEnum;
use App\Enums\EducationLevelEnum;
use App\Enums\GenderEnum;
use App\Exports\PlayerProgressExport;
use App\Services\PlayerProgressServiceReport;
use Dedoc\Scramble\Attributes\QueryParameter;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

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
    #[QueryParameter('group', type: 'array')]
    #[QueryParameter('player', type: 'array')]
    #[QueryParameter('gender', type: GenderEnum::class)]
    #[QueryParameter('education_level', type: EducationLevelEnum::class)]
    #[QueryParameter('activity_area', type: 'array')]
    #[QueryParameter('character', type: CharacterEnum::class)]
    #[QueryParameter('age_min', type: 'int')]
    #[QueryParameter('age_max', type: 'int')]
    public function index(Request $request): Collection
    {
        return collect($this->playerProgressServiceReport->getGraphData($request));
    }

    /**
     * @param Request $request
     * @return BinaryFileResponse
     */
    #[QueryParameter('group', type: 'array')]
    #[QueryParameter('player', type: 'array')]
    #[QueryParameter('gender', type: GenderEnum::class)]
    #[QueryParameter('education_level', type: EducationLevelEnum::class)]
    #[QueryParameter('activity_area', type: 'array')]
    #[QueryParameter('character', type: CharacterEnum::class)]
    #[QueryParameter('age_min', type: 'int')]
    #[QueryParameter('age_max', type: 'int')]
    public function exportToExcel(Request $request): BinaryFileResponse
    {
        $reportService = new PlayerProgressServiceReport($request);
        $reportData    = $reportService->getGraphData();

        return Excel::download(
            new PlayerProgressExport($reportData),
            'relatorio_aprendizado.xlsx'
        );
    }
}
