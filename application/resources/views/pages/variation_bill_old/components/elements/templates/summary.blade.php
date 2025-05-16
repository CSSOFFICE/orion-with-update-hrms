<?php

use Illuminate\Support\Facades\DB;

// Fetch the grouped quotation templates
$quotation_templates = DB::table('variation_templates')
    ->select('template_id', DB::raw('SUM(amount) as total_amount'))
    ->where('estimates_id', $bill->vo_id)
    ->groupBy('template_id')
    ->get();

?>

<table class="table table-bordered invoice-table">
    <thead>
        <tr>
            <th></th>
            <th>DESCRIPTION</th>
            <th>AMOUNT</th>
        </tr>
    </thead>
    <tbody>
        @php
        $total_amount=0;
        $letterIndex = 0; // Start with 0 (for 'A')
        $sum = 0; // Initialize the sum for template_id 1 to 6
        $sum2 = 0; // Initialize the sum for template_id 1 to 6
        @endphp

        @foreach ($quotation_templates as $template)
        @php
        // Generate the letter dynamically from the letter index
        $letter = chr(65 + $letterIndex); // 65 is the ASCII value for 'A'
        $letterIndex++; // Increment the index for the next row

        // Add to sum if template_id is between 1 and 6
        if ($template->template_id >= 1 && $template->template_id <= 6) {
            $sum +=$template->total_amount*0.1+$template->total_amount;

            }
            @endphp

            @if ($template->template_id == 1)
            <tr>
                <td>{{ $letter }}</td>
                <td>General Preliminaries</td>
                <td>$ {{ number_format(($template->total_amount*0.1)+$template->total_amount, 2) }}</td>
            </tr>
            @elseif ($template->template_id == 2)
            <tr>
                <td>{{ $letter }}</td>
                <td>Insurances</td>
                <td>$ {{ number_format(($template->total_amount*0.1)+$template->total_amount, 2) }}</td>
            </tr>
            @elseif ($template->template_id == 3)
            <tr>
                <td>{{ $letter }}</td>
                <td>Proposed Building Works</td>
                <td>$ {{ number_format(($template->total_amount*0.1)+$template->total_amount, 2) }}</td>
            </tr>
            @elseif ($template->template_id == 4)
            <tr>
                <td>{{ $letter }}</td>
                <td>Proposed Electrical & ACMV Works</td>
                <td>$ {{ number_format(($template->total_amount*0.1)+$template->total_amount, 2) }}</td>
            </tr>
            @elseif ($template->template_id == 5)
            <tr>
                <td>{{ $letter }}</td>
                <td>Proposed Plumbing & Sanitary Works</td>
                <td>$ {{ number_format(($template->total_amount*0.1)+$template->total_amount, 2) }}</td>
            </tr>
            @elseif ($template->template_id == 6)
            <tr>
                <td>{{ $letter }}</td>
                <td>Proposed External Works</td>
                <td>$ {{ number_format(($template->total_amount*0.1)+$template->total_amount, 2) }}</td>
            </tr>
            @endif
            @endforeach

            <!-- Spacer Rows -->
            <tr style="height: 2.2em;">
                <td></td>
                <td></td>
                <td style="border-top: 2px solid #000;">$ {{ number_format($sum, 2) }}</td>
            </tr>

            <!-- Contractor Profit Section -->
            <tr>
                @php
                $letter = chr(65 + $letterIndex); // Continue with the next letter
                $letterIndex++; // Increment for next section
                @endphp
                <td>{{ $letter }}</td>
                <td>Profit & Attendance Allowance (%)</td>
                <td>$ {{ number_format(0.05*$sum, 2) }}</td>
            </tr>

            <!-- Nett Main Contractor's Price Section -->
            <tr class="fw-bold">
                @php
                $letter = chr(65 + $letterIndex); // Continue with the next letter
                $letterIndex++; // Increment for next section
                @endphp
                <td>{{ $letter }}</td>
                <td><strong>NETT MAIN CONTRACTOR'S PRICE</strong></td>
                <td style="border-top: 2px solid #000;"><strong>$ {{ number_format($sum+0.05*$sum, 2) }}</strong></td>
            </tr>

            <!-- Spacer Rows -->
            <tr style="height: 2.2em;">
                <td></td>
                <td></td>
                <td></td>
            </tr>

            @foreach ($quotation_templates as $template)
            @if ($template->template_id == 7)

            <tr>
                @php
                $letter = chr(65 + $letterIndex); // Continue with the next letter
                $letterIndex++;
                // Add to sum if template_id is between 1 and 6
                if ($template->template_id >= 7 && $template->template_id <= 8) {
                    $sum2 +=$template->total_amount;
                    }
                    @endphp
                    <td>{{ $letter }}</td>
                    <td>PC & Provisional Sums</td>
                    <td>$ {{ number_format($template->total_amount, 2) }}</td>
            </tr>
            @endif
            @endforeach
            <tr>
                @php
                $letter = chr(65 + $letterIndex); // Continue with the next letter
                $letterIndex++;
                @endphp
                <td>{{ $letter }}</td>
                <td>Contingency Sums</td>
                <td>$ </td>
            </tr>


            <!-- Final Spacer -->
            <tr style="height: 2.2em;">
                <td></td>
                <td></td>
                <td></td>
            </tr>

            <!-- Total Tender / Quotation Amount -->
            <tr class="fw-bold">
                <td></td>
                <td><strong>TOTAL TENDER / QUOTATION AMOUNT</strong></td>
                <td style="border-top: 2px solid #000; border-bottom: 2px solid #000;"><strong>$ {{ number_format($sum2+$sum+0.05*$sum, 2) }}</strong></td>
            </tr>
    </tbody>
</table>
