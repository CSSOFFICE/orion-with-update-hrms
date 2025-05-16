<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class MultiSheetExport implements WithMultipleSheets
{
    private $data;

    public function __construct($data)
    {
        $this->data = $data; // Pass the data you want to use
    }

    public function sheets(): array
    {
        $sheets = [];

        foreach ($this->data as $sheetName => $sheetData) {
            $sheets[] = new DynamicSheetExport($sheetName, $sheetData);
        }

        return $sheets;
    }
}
