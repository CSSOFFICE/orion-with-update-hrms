<table>
    <tr>
        <td><strong>ORION INTEGRATED SERVICES PTE LTD</strong></td>
    </tr>
    <tr>

    </tr>
    <tr>
        <td colspan="3" style="text-align:left!important; background-color:yellow;height:100px;vertical-align:top;">
            <strong>{{ strtoupper($project_name)??'No Project Attached' }}</strong><br>
            <strong>{{ strtoupper($client_name) }}</strong><br>
            <strong>{{ strtoupper($client_email) }}</strong><br>
            <strong>{{ strtoupper($client_phone) }}</strong><br>

        </td>
    </tr>
</table>

<table>
    <thead>
        <tr>
            <th></th>
            <th>DESCRIPTION</th>
            <th style="width:100px">AMOUNT</th>
        </tr>
    </thead>
    <tbody>
        @php
            $letterIndex = 0; // Start with 0 (for 'A')

        @endphp
        @foreach ($summary_data as $item)
            {{-- @php
        // Generate the letter dynamically from the letter index
        $letter = chr(65 + $letterIndex); // 65 is the ASCII value for 'A'
        $letterIndex++; // Increment the index for the next row  
        @endphp       --}}


            @if ($item->description == "NETT MAIN CONTRACTOR'S PRICE")
                <tr>
                    <td style="text-align:right;"><b>{{ $item->letter }}</b></td>
                    <td style="width:350px"><b>{{ $item->description }}</b></td>
                    <td><b>$ {{ number_format($item->amount, 2) }}</b></td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            @elseif($item->description == 'Others')
                <tr>
                    <td style="text-align:right;">{{ $item->letter }}</td>
                    <td style="width:350px">{{ $item->description }}</td>
                    <td>$ {{ number_format($item->amount, 2) }}</td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            @elseif($item->description == "TOTAL TENDER / QUOTATION AMOUNT")
            <tr>
                <td style="text-align:right;"><b>{{ $item->letter }}</b></td>
                <td style="width:350px"><b>{{ $item->description }}</b></td>
                <td><b>$ {{ number_format($item->amount, 2) }}</b></td>
            </tr>
            @elseif($item->description == " ")
            <tr>
                <td style="text-align:right;"><b>{{ $item->letter }}</b></td>
                <td style="width:350px"><b>{{ $item->description }}</b></td>
                <td style="border:1px solid black;"><b>$ {{ number_format($item->amount, 2) }}</b></td>
            </tr>
            @else
                <tr>
                    <td style="text-align:right;">{{ $item->letter }}</td>
                    <td style="width:350px">{{ $item->description }}</td>
                    <td>$ {{ number_format($item->amount, 2) }}</td>
                </tr>
            @endif
        @endforeach
    </tbody>
</table>
