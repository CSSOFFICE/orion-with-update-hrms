<?php

namespace App\Exports;

use App\Exports\PreliminariesSheetExport;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class MultiSheetExport implements WithMultipleSheets
{
    private $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function sheets(): array
    {
        $sheets = [];

        $sheets[] = new SummarySheetExport($this->data);

        foreach ($this->data['quotation_template'] as $item) {
            if ($item == 'PRELIMINARIES') {
                // return view('pages.bill.components.export.summary');
                $sheets[] = new PreliminariesSheetExport($this->data);
            }
            if ($item == 'INSURANCES') {
                // return view('pages.bill.components.export.insurance');
                $sheets[] = new InsurancesSheetExport($this->data);
            }
            if ($item == 'SCHEDULE OF WORKS') {
                $sheets[] = new SCSheetExport($this->data);
            }
            if ($item == 'PLUMBING & SANITARY') {
                $sheets[] = new PlumbSheetExport($this->data);
            }
            if ($item == 'ELEC & ACMV') {
                $sheets[] = new ElecSheetExport($this->data);
            }
            if ($item == 'EXTERNAL WORKS') {
                $sheets[] = new ExtSheetExport($this->data);
            }
            if ($item == 'PC & PS SUMS') {
                $sheets[] = new PCSheetExport($this->data);
            }
            if ($item == 'OTHERS') {
                $sheets[] = new OtherSheetExport($this->data);
            }
        }

        return $sheets;
    }
}
