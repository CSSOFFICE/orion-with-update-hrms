<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

class DynamicSheetExport implements FromView, WithTitle
{
    private $title;
    private $data;

    public function __construct($title, $data)
    {
        $this->title = $title;
        $this->data = $data;
    }
    // Return the array data for the sheet
    // public function array(): array
    // {
    //     return $this->data;
    // }

    // Set the sheet title
    public function title(): string
    {
        return $this->title;
    }

    public function view(): View
    {
        $data = $this->data;



        // if (!empty($data['packer_id'])) {
        //     return view('admin.packer.export.picking-list-export', $data);
        // }
    }
}
