<?php

namespace App\Http\Responses\Estimates;

use Illuminate\Contracts\Support\Responsable;
use PDF;

class PDFResponse implements Responsable
{
    private $payload;

    public function __construct($payload = [])
    {
        $this->payload = $payload;
    }

    /**
     * Render the view.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function toResponse($request)
    {
        // Set data to an array
        $data = $this->payload;

        // View estimate in the browser (https://domain.com/estimate/1/pdf?view=preview)
        if (request('view') == 'preview') {
            config(['css.bill_mode' => 'pdf-mode-preview']);
            return view('pages/bill/bill-pdf', $data)->render();
        }

        // Download PDF view
        config(['css.bill_mode' => 'pdf-mode-download']);
        $pdf = PDF::loadView('pages/bill/bill-pdf', $data);
        $filename = strtoupper(__('lang.estimate')) . '-' . $data['bill']->formatted_bill_estimateid . '.pdf';
        return $pdf->download($filename);
    }
}
