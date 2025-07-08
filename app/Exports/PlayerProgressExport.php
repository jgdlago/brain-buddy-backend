<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PlayerProgressExport implements FromArray, WithHeadings, WithTitle, WithStyles
{
    protected array $reportData;

    public function __construct(array $reportData)
    {
        $this->reportData = $reportData;
    }

    public function array(): array
    {
        $data = [];

        $data[] = [
            'TOTAIS GERAIS',
            $this->reportData['totals']['total_correct'],
            $this->reportData['totals']['total_wrong'],
            $this->reportData['totals']['total_attempts'],
            $this->reportData['totals']['total_completed'],
            $this->reportData['totals']['total_help_flags'],
            '', '', '', ''
        ];

        $data[] = [];

        $data[] = [
            'Nome', 'Idade', 'Personagem', 'Performance Flag',
            'Respostas Corretas', 'Respostas Erradas', 'Tentativas',
            'Níveis Completados', 'Ajudas Utilizadas'
        ];

        // Adiciona dados dos players
        foreach ($this->reportData['players'] as $player) {
            $data[] = [
                $player['name'],
                $player['age'],
                $player['character'],
                $player['performance_flag'],
                $player['totals']['correct'],
                $player['totals']['wrong'],
                $player['totals']['attempts'],
                $player['totals']['levels_completed'],
                $player['totals']['help_flags'],
            ];
        }

        return $data;
    }

    public function headings(): array
    {
        return [];
    }

    public function title(): string
    {
        return 'Relatório de aprendizado';
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '2E75B6']]
            ],

            3 => ['font' => ['bold' => true]],
        ];
    }
}
