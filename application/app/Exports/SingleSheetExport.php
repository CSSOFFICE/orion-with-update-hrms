<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;

class SingleSheetExport implements FromArray
{
    private $data;
    private $title;

    public function __construct(array $data, string $title)
    {
        $this->data = $data;
        $this->title = $title;
    }

    public function array(): array
    {
        return $this->data;
    }
}
