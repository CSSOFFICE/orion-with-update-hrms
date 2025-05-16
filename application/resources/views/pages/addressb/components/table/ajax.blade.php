@foreach($addressb as $k=>$addressb)
<!--each row-->
<tr id="contact_{{ $addressb->id }}">
    @if(config('visibility.address_col_checkboxes'))
    <td class="address_col_checkbox checkitem " id="address_col_checkbox_{{ $addressb->id }}">
        <!--list checkbox-->
        <span class="list-checkboxes display-inline-block w-px-20">
            <input type="checkbox" id="listcheckbox-address-{{ $addressb->id }}" name="ids[{{ $addressb->id }}]"
                class="listcheckbox listcheckbox-address filled-in chk-col-light-blue"
                data-actions-container-class="address-checkbox-actions-container"

                <label for="listcheckbox-address-{{ $addressb->id }}"></label>
        </span>
    </td>
    @endif
    <td class="address_col_first_name" id="address_col_first_name_{{ $addressb->id }}">
        <span>{{ $k+1 }}</span>

    </td>
    <td class="address_col_first_name" id="address_col_first_name_{{ $addressb->id }}">
        <span>{{ $addressb->street }}</span>

    </td>

    <td class="address_col_company" id="address_col_company_{{ $addressb->id }}">
        <a href="{{ url('/clients') }}/{{ $addressb->clientid }}">{{ $addressb->p_unit }}</a>
    </td>
    <td class="address_col_company" id="address_col_company_{{ $addressb->id }}">
        <a href="{{ url('/clients') }}/{{ $addressb->clientid }}">{{ $addressb->country }}</a>
    </td>
    <td class="address_col_email" id="address_col_email_{{ $addressb->id }}">
        {{ $addressb->zipcode }}
    </td>
    {{-- <td class="address_col_phone" id="address_col_phone_{{ $addressb->id }}">{{ $addressb->country ?? '---'}}</td> --}}


    <td class="address_col_action actions_column" id="address_col_action_{{ $addressb->id }}">
        <!--action button-->
        <span class="list-table-action dropdown font-size-inherit">
            <!--delete-->
            @if(config('visibility.action_buttons_delete'))

            <button type="button" title="{{ cleanLang(__('lang.delete')) }}"
                class="data-toggle-action-tooltip btn btn-outline-danger btn-circle btn-sm confirm-action-danger"
                data-confirm-title="{{ cleanLang(__('lang.delete_user')) }}" data-confirm-text="{{ cleanLang(__('lang.are_you_sure')) }}"
                data-ajax-type="DELETE" data-url="{{ url('/') }}/addressb/{{ $addressb->id }}">
                <i class="sl-icon-trash"></i>
            </button>

            @endif
            <!--edit-->
            @if(config('visibility.action_buttons_edit'))
            <button type="button" title="{{ cleanLang(__('lang.edit')) }}"
                class="data-toggle-action-tooltip btn btn-outline-success btn-circle btn-sm edit-add-modal-button js-ajax-ux-request reset-target-modal-form"
                data-toggle="modal" data-target="#commonModal"
                data-url="{{ urlResource('/addressb/'.$addressb->id.'/edit') }}" data-loading-target="commonModalBody"
                data-modal-title="Edit Address"
                data-action-url="{{ urlResource('/addressb/'.$addressb->id.'?ref=list') }}" data-action-method="PUT"
                data-action-ajax-class="" data-action-ajax-loading-target="address-td-container">
                <i class="sl-icon-note"></i>
            </button>
            @endif
        </span>
        <!--action button-->
    </td>

</tr>
@endforeach
<!--each row-->
