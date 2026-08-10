<?php

namespace App\Exports;

use App\Models\EventRegistration;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;

class EventParticipantSheet implements
    FromCollection,
    WithHeadings,
    WithEvents,
    WithTitle
{
    protected $eventId;
    protected $eventTitle;
    protected $sheetTitle;
    protected $categoryId;

    public function __construct(
        $eventId,
        $eventTitle,
        $sheetTitle,
        $categoryId = null
    ) {
        $this->eventId = $eventId;
        $this->eventTitle = $eventTitle;
        $this->sheetTitle = $sheetTitle;
        $this->categoryId = $categoryId;
    }

    /**
     * Nama sheet Excel
     */
    public function title(): string
    {
        // Excel maksimal 31 karakter
        return mb_substr($this->sheetTitle, 0, 31);
    }

    /**
     * Data peserta
     */
    public function collection()
    {
        $query = EventRegistration::where(
            'event_id',
            $this->eventId
        )->with('competitionCategories');

        /*
        |--------------------------------------------------------------------------
        | FILTER BERDASARKAN KATEGORI
        |--------------------------------------------------------------------------
        |
        | Jika categoryId tidak null, hanya peserta yang
        | mengikuti kategori tersebut yang ditampilkan.
        |
        */

        if ($this->categoryId !== null) {
            $query->whereHas('competitionCategories', function ($q) {
                $q->where(
                    'competition_categories.id',
                    $this->categoryId
                );
            });
        }

        $participants = $query
            ->orderBy('participant_name')
            ->get();

        return $participants->map(function ($participant, $index) {

            $birthdate = $participant->participant_birthdate
                ? Carbon::parse($participant->participant_birthdate)
                : null;

            $age = $birthdate
                ? $birthdate->age
                : '-';

            /*
            |--------------------------------------------------------------------------
            | KATEGORI
            |--------------------------------------------------------------------------
            |
            | Sheet "Semua Peserta":
            | tampilkan semua kategori peserta.
            |
            | Sheet kategori:
            | tampilkan hanya kategori sheet tersebut.
            |
            */

            if ($this->categoryId !== null) {

                $categories = $participant
                    ->competitionCategories
                    ->where('id', $this->categoryId)
                    ->pluck('name')
                    ->implode(', ');

            } else {

                $categories = $participant
                    ->competitionCategories
                    ->pluck('name')
                    ->implode(', ');
            }

            return [
                $index + 1,
                $participant->participant_name,
                $participant->participant_whatsapp,
                $birthdate
                    ? $birthdate->format('d-m-Y') . " ({$age} tahun)"
                    : '-',
                $categories ?: '-',
            ];
        });
    }

    /**
     * Header tabel
     */
    public function headings(): array
    {
        return [
            'No',
            'Nama',
            'WhatsApp',
            'Tanggal Lahir',
            'Kategori Lomba',
        ];
    }

    /**
     * Styling sheet
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {

                $sheet = $event->sheet->getDelegate();

                /*
                |--------------------------------------------------------------------------
                | JUDUL
                |--------------------------------------------------------------------------
                */

                $sheet->insertNewRowBefore(1, 2);

                $sheet->mergeCells('A1:E1');

                $sheet->setCellValue(
                    'A1',
                    'DAFTAR PESERTA'
                );

                $sheet->mergeCells('A2:E2');

                $sheet->setCellValue(
                    'A2',
                    $this->eventTitle
                );

                /*
                |--------------------------------------------------------------------------
                | STYLE JUDUL
                |--------------------------------------------------------------------------
                */

                $sheet->getStyle('A1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 16,
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                ]);

                $sheet->getStyle('A2')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 13,
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                ]);

                /*
                |--------------------------------------------------------------------------
                | HEADER TABEL
                |--------------------------------------------------------------------------
                |
                | SEMUA SHEET MENGGUNAKAN HEADER DI ROW 3
                |
                */

                $headerRow = 3;

                $sheet->getStyle(
                    "A{$headerRow}:E{$headerRow}"
                )->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'color' => [
                            'rgb' => '000000',
                        ],
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'color' => [
                            'rgb' => 'FFFF00',
                        ],
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                ]);

                /*
                |--------------------------------------------------------------------------
                | BORDER
                |--------------------------------------------------------------------------
                */

                $lastRow = $sheet->getHighestRow();

                $sheet->getStyle(
                    "A{$headerRow}:E{$lastRow}"
                )
                    ->getBorders()
                    ->getAllBorders()
                    ->setBorderStyle(
                        Border::BORDER_THIN
                    );

                /*
                |--------------------------------------------------------------------------
                | ALIGNMENT DATA
                |--------------------------------------------------------------------------
                */

                $dataStartRow = 4;

                if ($lastRow >= $dataStartRow) {

                    $sheet->getStyle(
                        "A{$dataStartRow}:A{$lastRow}"
                    )
                        ->getAlignment()
                        ->setHorizontal(
                            Alignment::HORIZONTAL_CENTER
                        );

                    $sheet->getStyle(
                        "D{$dataStartRow}:E{$lastRow}"
                    )
                        ->getAlignment()
                        ->setHorizontal(
                            Alignment::HORIZONTAL_CENTER
                        );
                }

                /*
                |--------------------------------------------------------------------------
                | AUTO WIDTH
                |--------------------------------------------------------------------------
                */

                foreach (range('A', 'E') as $column) {
                    $sheet->getColumnDimension($column)
                        ->setAutoSize(true);
                }

                /*
                |--------------------------------------------------------------------------
                | WIDTH MINIMUM
                |--------------------------------------------------------------------------
                */

                $sheet->getColumnDimension('A')->setWidth(8);
                $sheet->getColumnDimension('B')->setWidth(28);
                $sheet->getColumnDimension('C')->setWidth(32);
                $sheet->getColumnDimension('D')->setWidth(20);
                $sheet->getColumnDimension('E')->setWidth(25);

                /*
                |--------------------------------------------------------------------------
                | TINGGI BARIS
                |--------------------------------------------------------------------------
                */

                $sheet->getRowDimension(1)->setRowHeight(25);
                $sheet->getRowDimension(2)->setRowHeight(22);
                $sheet->getRowDimension(3)->setRowHeight(25);

                /*
                |--------------------------------------------------------------------------
                | PAGE SETUP
                |--------------------------------------------------------------------------
                */

                $sheet->getPageSetup()->setOrientation(
                    PageSetup::ORIENTATION_LANDSCAPE
                );

                $sheet->getPageSetup()->setPaperSize(
                    PageSetup::PAPERSIZE_A4
                );

                $sheet->getPageSetup()->setFitToWidth(1);
                $sheet->getPageSetup()->setFitToHeight(0);

                /*
                |--------------------------------------------------------------------------
                | CENTER HORIZONTAL
                |--------------------------------------------------------------------------
                */

                $sheet->getPageSetup()->setHorizontalCentered(true);

                /*
                |--------------------------------------------------------------------------
                | MARGIN
                |--------------------------------------------------------------------------
                */

                $sheet->getPageMargins()
                    ->setLeft(0.5)
                    ->setRight(0.5)
                    ->setTop(0.5)
                    ->setBottom(0.5);
            },
        ];
    }
}
