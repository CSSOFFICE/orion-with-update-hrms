<?php


namespace App\Http\Controllers;

use App\Exports\MultiSheetExport;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use Maatwebsite\Excel\Events\AfterSheet;

class ExportController extends Controller
{
    public function downloadMultiSheet($estimate_id)
    {
        // Query to join 'milestone_categories' with 'quotation_templates'
        $results = DB::table('milestone_categories')
            ->join('quotation_templates', 'milestone_categories.milestonecategory_id', '=', 'quotation_templates.template_id')
            ->join('estimates', 'estimates.bill_estimateid', '=', 'quotation_templates.estimates_id')
            ->leftJoin('projects', 'projects.project_id', '=', 'estimates.bill_projectid')
            ->where('quotation_templates.estimates_id', $estimate_id)
            ->select('milestone_categories.*', 'quotation_templates.*', 'projects.project_title')
            ->get();

        $groupedData = [];
        $milestoneTitles = [];
        $totalPerTitle = []; // To store totals for each title
        $amountPerTitle = []; // To store amounts for each title

        // Loop through the results and group by `milestonecategory_title`
        foreach ($results as $item) {
            $projectname = $item->project_title;
            $title = $item->milestonecategory_title;
            $quotation_no = $item->quotation_no;

            // Collect unique milestone titles
            if (!in_array($title, $milestoneTitles)) {
                $milestoneTitles[] = $title;
            }

            // Initialize group for each title if not already set
            if (!isset($groupedData[$title])) {
                $groupedData[$title] = [
                    'total' => 0,
                    'amount' => 0,
                    'data' => [],
                ];
            }

            // Add current item's total and amount to the title group
            $groupedData[$title]['total'] += $item->total ?? 0;
            $groupedData[$title]['amount'] += $item->amount ?? 0;

            // Append item data to the title group
            $groupedData[$title]['data'][] = $item;

            // Track individual totals per title
            $totalPerTitle[$title] = $groupedData[$title]['total'];
            $amountPerTitle[$title] = $groupedData[$title]['amount'];
        }




        // Convert milestone titles to a comma-separated string
        // $quotationTemplate = implode(', ', $milestoneTitles);
        $quotation_no = DB::table('estimates')->where('bill_estimateid', $estimate_id)->value('quotation_no');
        $summery = DB::table('summary_details')->where('quotation_id', $quotation_no)->get();
        $result = [
            'project_name' => $projectname ?? '--',
            'quotation_no' => $quotation_no ?? '--',
            'quotation_template' => $milestoneTitles,
            'quotation_data' => $groupedData,
            'summary_data' => $summery
        ];

        // return $result;

        // // Create a mapping of categories to their template IDs
        // $templateIds = [];
        // foreach ($results as $result) {
        //     $templateIds[$result->milestonecategory_title] = $result->template_id;
        // }

        // // Organizing the results to map categories with their respective templates
        // $organizedData = [];
        // $categoryIndexes = []; // To track index for each category separately

        // // Variables to store category sums (initialized dynamically)
        // $categorySums = [];

        // // Loop through each result to organize by milestone category
        // foreach ($results as $result) {
        //     if (!$result->template_id) {
        //         continue;
        //     }

        //     if (!isset($categorySums[$result->milestonecategory_title])) {
        //         $categorySums[$result->milestonecategory_title] = 0;
        //     }

        //     $categorySums[$result->milestonecategory_title] += $result->amount;

        //     if (!isset($organizedData[$result->milestonecategory_title])) {
        //         $organizedData[$result->milestonecategory_title] = [
        //             ['Item', 'Description', 'Unit', 'Qty', 'Labour', 'Material', 'Misc', 'Wastage %', 'Wastage $', 'S/C', 'Total', 'Amount'],
        //         ];

        //         $categoryIndexes[$result->milestonecategory_title] = 1;
        //     }

        //     $organizedData[$result->milestonecategory_title][] = [
        //         'template_name' => $categoryIndexes[$result->milestonecategory_title]++,
        //         'template_description' => $result->description,
        //         'unit' => $result->unit,
        //         'qty' => $result->qty,
        //         'labour' => $result->labour,
        //         'material' => $result->material,
        //         'misc' => $result->misc,
        //         'wastage_percent' => $result->wastage_percent,
        //         'wastage_amount' => $result->wastage_amount,
        //         'sc' => $result->sc,
        //         'total' => $result->total,
        //         'amount' => $result->amount,
        //     ];
        // }

        // $summaryData = [[' ', 'DESCRIPTION', 'AMOUNT']];
        // $i = "A";
        // $pcPsSum = 0;

        // foreach ($categorySums as $category => $sum) {
        //     if ($category == 'PC & Provisional Sums' && $templateIds[$category] == 7) {
        //         $pcPsSum = $sum;
        //     }
        //     $summaryData[] = [$i, $category, number_format($sum, 2)];
        //     $i++;
        // }

        // $totalSum = array_sum($categorySums);
        // $summaryData[] = ['', '', number_format($totalSum, 2)];
        // $profitAllowance = 0.05 * $totalSum;
        // $summaryData[] = [$i, 'Profit & Attendance Allowance (%)', number_format($profitAllowance, 2)];
        // $i++;

        // $nettPrice = $totalSum + $profitAllowance;
        // $summaryData[] = [$i, 'NETT MAIN CONTRACTOR PRICE', number_format($nettPrice, 2)];
        // $i++;
        // $summaryData[] = ['', '', ''];

        // $contingencySum = 50;
        // $summaryData[] = [$i, 'Contingency Sums', number_format($contingencySum, 2)];
        // $i++;

        // $combinedSum = $pcPsSum + $contingencySum;
        // $summaryData[] = ['', '', number_format($combinedSum, 2)];

        // $totalTenderAmount = $nettPrice + $combinedSum;
        // $summaryData[] = [$i, 'TOTAL TENDER / QUOTATION AMOUNT', number_format($totalTenderAmount, 2)];

        // $organizedData = ['SUMMARY' => $summaryData] + $organizedData;

        $fileName = preg_replace('/[\/\\\\]/', '-', $result['quotation_no'] ?? 'Quotation') . '.xlsx';

        return Excel::download(new MultiSheetExport($result), $fileName);
    }
}
