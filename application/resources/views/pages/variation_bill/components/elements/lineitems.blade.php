

<?php $i=1 ?>
@foreach($lineitems as $lineitem)

<tr>
    <!-- SR No -->
    <td class="x-quantity sr_no"> {{ $i }}</td>
    <td class="x-quantity sr_no"> {{$lineitem->item }}</td>
    <!--description-->
    <td class="x-description text-center">{{ $lineitem->lineitem_description }}</td>
    <!--quantity-->
    @if($lineitem->lineitem_type == 'plain'||$lineitem->lineitem_type == 'product')
    <td class="x-quantity text-center">{{ $lineitem->lineitem_quantity }}</td>
    @else
    <td class="x-quantity text-center">
        @if($lineitem->lineitem_time_hours > 0)
        {{ $lineitem->lineitem_time_hours }}{{ strtolower(__('lang.hrs')) }}&nbsp;
        @endif
        @if($lineitem->lineitem_time_minutes > 0)
        {{ $lineitem->lineitem_time_minutes }}{{ strtolower(__('lang.mins')) }}
        @endif
    </td>
    @endif
    <!--unit price-->
    <td class="x-unit text-center">{{ $lineitem->lineitem_unit }}</td>
    <!--rate-->
    <td class="x-rate text-center">{{ runtimeNumberFormat($lineitem->lineitem_rate) }}</td>
    <!--tax-->
    {{-- <td class="x-tax {{ runtimeVisibility('invoice-column-inline-tax', $bill->bill_tax_type) }}"></td> --}}
    <!--total-->
    <td class="x-total text-center">{{ runtimeNumberFormat($lineitem->lineitem_total) }}</td>
</tr>
<?php $i++

?>
@endforeach
@php
$total = $lineitems->sum('lineitem_total');
@endphp
<tr>
    <td class="x-quantity sr_no" colspan='5'> </td>
    <td class="billing-sums-total"  style="color:black; font-weight:600; font-size:15px;">Total:</td>
    <td id="billing-sums-total" style="color:black; font-weight:600; font-size:15px;">
        <span>{{ runtimeMoneyFormat($total) }}</span>
    </td>
    </tr>
