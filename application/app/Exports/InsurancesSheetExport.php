<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

class InsurancesSheetExport implements FromView, WithTitle
{
    // private $title;
    private $data;

    public function __construct($data)
    {
        // $this->title = $title;
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
        return 'INSURANCES';
    }

    public function view(): View
    {
        $data = $this->data;
        return view('pages.bill.components.export.insurance',$data);        
        

        // if (!empty($data['packer_id'])) {
        //     return view('admin.packer.export.picking-list-export', $data);
        // }
    }
}
