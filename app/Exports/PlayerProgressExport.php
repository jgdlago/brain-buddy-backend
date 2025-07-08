<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\{
    FromArray,
    WithHeadings,
    WithStyles,
    WithTitle,
    WithEvents,
    WithColumnWidths
};
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PlayerProgressExport implements
    FromArray,
    WithHeadings,
    WithTitle,
    WithStyles,
    WithEvents,
    WithColumnWidths
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
            'TOTAL',
            $this->reportData['totals']['total_correct'],
            $this->reportData['totals']['total_wrong'],
            $this->reportData['totals']['total_attempts'],
            $this->reportData['totals']['total_completed'],
            $this->reportData['totals']['total_help_flags'],
        ];

        $data[] = [];

        $data[] = [
            'Nome', 'Idade', 'Personagem', 'Performance Flag',
            'Respostas Corretas', 'Respostas Erradas', 'Tentativas',
            'Níveis Completados', 'Ajudas Utilizadas'
        ];

        foreach ($this->reportData['players'] as $player) {
            $data[] = [
                $player['name'],
                $player['age'],
                $player['gender'],
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
                'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '2E75B6']],
                'alignment' => ['horizontal' => 'center']
            ],
            3 => [
                'font' => ['bold' => true],
                'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => 'BDD7EE']],
                'alignment' => ['horizontal' => 'center']
            ],
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 25,
            'B' => 10,
            'C' => 15,
            'D' => 20,
            'E' => 18,
            'F' => 18,
            'G' => 12,
            'H' => 18,
            'I' => 18,
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $highestRow = $sheet->getHighestRow();
                $highestCol = $sheet->getHighestColumn();
                $range = "A1:{$highestCol}{$highestRow}";

                $sheet->getStyle($range)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['rgb' => 'CCCCCC'],
                        ],
                    ],
                ]);

                $sheet->freezePane('A4');

                for ($row = 4; $row <= $highestRow; $row++) {
                    if ($row % 2 === 0) {
                        $sheet->getStyle("A{$row}:{$highestCol}{$row}")
                            ->getFill()
                            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                            ->getStartColor()->setRGB('F2F2F2');
                    }
                }

                foreach (range('A', $highestCol) as $col) {
                    $sheet->getColumnDimension($col)->setAutoSize(true);
                }
            },
        ];
    }
}
