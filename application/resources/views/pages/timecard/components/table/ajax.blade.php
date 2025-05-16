@php
$totalh = 0.00;
@endphp
@foreach ($bigarray as $keyy => $value)
    <tr>
        <td>{{ $keyy + 1 }} </td>
        <td>{{ $value->id_no }}</td>
        <td>{{ $value->first_name }} <input type="hidden" class="user_id" value="{{ $value->user_id }}" /></td>

        @php
            $userDataFound = false;
             $totalh= "0.00";
        @endphp

        @foreach ($emph as $key => $val)
            @if ($val->user_id == $value->user_id)
                @foreach ($dayyr as $key => $day)
                    @php

                        $totalh += (float)$val->$day;
                    @endphp


                    <td>
                        <input type="text" class="form-control edit_ppe" data-field_id="{{ $value->user_id }}"
                            value="{{ $val->$day }}" name="{{ $day }}" disabled style="width: 70px;" />
                    </td>
                @endforeach
                @php
                    $userDataFound = true;
                @endphp
            @break
        @endif
    @endforeach

    @if (!$userDataFound)
        @foreach ($dayyr as $key => $day)
            <td>
                <input type="text" class="form-control edit_ppe" data-field_id="{{ $value->user_id }}"
                    value="" name="{{ $day }}" disabled style="width: 70px;" />
            </td>
        @endforeach
    @endif
    <td >{{ $totalh }}</td>
    <td>
        <span data-toggle="tooltip" data-placement="top" title="edit">
            <button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light edit_ppe"
                data-field_id="{{ $value->user_id }}">
                <i class="fa fa-pencil"></i>
            </button>
        </span>

        <span data-toggle="tooltip" data-placement="top" title="delete">
            <button type="button" class="btn icon-btn btn-xs btn-danger waves-effect waves-light delete"
                data-toggle="modal" data-target=".delete-modal" data-record-id="{{ $value->user_id }}"
                data-token_type="delete_ppe">
                <span class="fa fa-trash"></span>
            </button>
        </span>
    </td>
</tr>
@endforeach
