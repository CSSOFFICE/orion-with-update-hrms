<table>
    <tr>
        <td><strong>ORION INTEGRATED SERVICES PTE LTD</strong></td>
    </tr>
    <tr>

    </tr>
    <tr>
        <td colspan="6" style="text-align:left!important; background-color:yellow;height:100px;vertical-align:top;">
           <strong>{{ strtoupper($project_name)??'No Project Attached' }}</strong><br>
            <strong>{{ strtoupper($client_name) }}</strong><br>
            <strong>{{ strtoupper($client_email) }}</strong><br>
            <strong>{{ strtoupper($client_phone) }}</strong><br>
        </td>
    </tr>
</table>
<table style="border: 1px solid black;!important;">
    <thead style="text-align:center;">
        <tr style="border: 1px solid black;;">
            <th style="text-align:center;vertical-align:middle;border:5px solid black;height:50px;"><b>Item</b></th>
            <th style="text-align:center;vertical-align:middle;border:5px solid black;height:50px;"><b>Description</b>
            </th>
            <th style="text-align:center;vertical-align:middle;border:5px solid black;height:50px;"><b>Unit</b></th>
            <th style="text-align:center;vertical-align:middle;border:5px solid black;height:50px;"><b>Qty</b></th>
            <th style="text-align:center;vertical-align:middle;border:5px solid black;height:50px;"><b>Rate</b></th>
            <th style="text-align:center;vertical-align:middle;border:5px solid black;height:50px;"><b>Total</b></th>
        </tr>
        <tr>
            <th style="border-right:5px solid black;border-left:1px solid black;"></th>
            <th style="border-right:5px solid black;border-left:1px solid black;"></th>
            <th style="border-right:5px solid black;border-left:1px solid black;"></th>
            <th style="border-right:5px solid black;border-left:1px solid black;"></th>
            <th style="border-right:5px solid black;border-left:1px solid black;"></th>
            <th style="border-right:5px solid black;"></th>
        </tr>
        <tr>
            <th style="background-color:#FEF2CB"></th>
            <th
                style="text-align:left!important;background-color:#FEF2CB;border-right:5px solid black;border-left:5px solid black;">
                <b>BILL NO. 2 - INSURANCES</b>
            </th>
            <th
                style="text-align:center;background-color:#FEF2CB;border-right:5px solid black;border-left:5px solid black;">
                <strong></strong>
            </th>
            <th
                style="text-align:center;background-color:#FEF2CB;border-right:5px solid black;border-left:5px solid black;">
            </th>
            <th
                style="text-align:center;background-color:#FEF2CB;border-right:5px solid black;border-left:5px solid black;">
            </th>
            <th
                style="text-align:center;background-color:#FEF2CB;border-right:5px solid black;border-left:5px solid black;">
                <strong>$ {{ number_format($quotation_data['INSURANCES']['total'], 2) }}</strong>
            </th>
        </tr>
        <tr>
            <th style="border-right:5px solid black;border-left:1px solid black;"></th>
            <th style="border-right:5px solid black;border-left:1px solid black;"></th>
            <th style="border-right:5px solid black;border-left:1px solid black;"></th>
            <th style="border-right:5px solid black;border-left:1px solid black;"></th>
            <th style="border-right:5px solid black;"></th>
        </tr>
    </thead>
    <tbody>
        @foreach ($quotation_data['INSURANCES']['data'] as $key => $item)
            <tr>
                <td style="border: 1px solid black;vertical-align:top;">{{ $key + 1 }}</td>
                <td style="border: 1px solid black;width: 350px; word-wrap: break-word; white-space: normal;">
                    {{ $item->description }}</td>
                <td style="border: 1px solid black;width: 50px;text-align:center;vertical-align:top;">
                    {{ $item->unit }}</td>
                <td style="border: 1px solid black;width: 50px;text-align:center;vertical-align:top;">
                    {{ number_format($item->qty, 2) }}</td>
                <td style="border: 1px solid black;width: 50px;text-align:center;vertical-align:top;">
                    {{ number_format($item->rate, 2) }}</td>
                <td style="border: 1px solid black;width: 100px;vertical-align:top;text-align:center;">$
                    {{ number_format($item->total, 2) }}</td>
            </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td style="text-align:center;border-top: 5px solid black;border-bottom: 5px solid black;width: 80px;">
                <strong>Sub-Total</strong>
            </td>
            <td style="border-top: 5px solid black;border-bottom: 5px solid black;"></td>
            <td style="border-top: 5px solid black;border-bottom: 5px solid black;"></td>
            <td style="border-top: 5px solid black;border-bottom: 5px solid black;"></td>
            <td style="border-top: 5px solid black;border-bottom: 5px solid black;"></td>
            <td
                style="text-align:center;border-top: 5px solid black;border-bottom: 5px solid black;border-left:5px solid black;">
                $ {{ number_format($quotation_data['INSURANCES']['total'], 2) }}</td>
        </tr>
        <tr>
            <td style="text-align:center;border-top: 5px solid black;border-bottom: 5px solid black;width: 80px;">
                <strong>TOTAL</strong>
            </td>
            <td style="border-top: 5px solid black;border-bottom: 5px solid black;"></td>
            <td style="border-top: 5px solid black;border-bottom: 5px solid black;"></td>
            <td style="border-top: 5px solid black;border-bottom: 5px solid black;"></td>
            <td style="border-top: 5px solid black;border-bottom: 5px solid black;"></td>
            <td
                style="text-align:center;border-top: 5px solid black;border-bottom: 5px solid black;border-left:5px solid black;">
                $ {{ number_format($quotation_data['INSURANCES']['total'], 2) }}</td>
        </tr>
    </tfoot>
</table>
