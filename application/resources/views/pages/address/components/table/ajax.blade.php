@foreach ($address as $k => $address)
    <!--each row-->
    <tr id="contact_{{ $address->id }}">
        @if (config('visibility.address_col_checkboxes'))
            <td class="address_col_checkbox checkitem " id="address_col_checkbox_{{ $address->id }}">
                <!--list checkbox-->
                <span class="list-checkboxes display-inline-block w-px-20">
                    <input type="checkbox" id="listcheckbox-address-{{ $address->id }} "  name="ids[{{ $address->id }}]"
                        class="listcheckbox  listcheckbox-address filled-in chk-col-light-blue"
                         data-actions-container-class="address-checkbox-actions-container">

                    <label for="listcheckbox-address-{{ $address->id }}"></label>
                </span>
            </td>
        @endif
        <td class="address_col_first_name" id="address_col_first_name_{{ $address->id }}">
            <span>{{ $k + 1 }}</span>

        </td>
        <td class="address_col_first_name" id="address_col_first_name_{{ $address->id }}">
            <span>{{ $address->street }}</span>

        </td>

        <td class="address_col_company" id="address_col_company_{{ $address->id }}">
            <a href="{{ url('/clients') }}/{{ $address->clientid }}">{{ $address->p_unit }}</a>
        </td>
        <td class="address_col_email" id="address_col_email_{{ $address->id }}">
            {{ $address->country }}
        </td>
        <td class="address_col_email" id="address_col_email_{{ $address->id }}">
            {{ $address->zipcode }}
        </td>
        <td class="billadd" id="">
            <input class="form-check-input" {{($address->d_address==true)?'checked':''}} type="checkbox" id="exampleCheckbox{{ $address->id }}" data-id="{{ $address->id }}" data-client-id="{{$address->client_id}}">
            <label class="form-check-label" for="exampleCheckbox{{ $address->id }}"></label>
        </td>
        {{-- <td class="address_col_phone" id="address_col_phone_{{ $address->id }}">{{ $address->country ?? '---'}}</td> --}}


        <td class="address_col_action actions_column" id="address_col_action_{{ $address->id }}">
            <!--action button-->
            <span class="list-table-action dropdown font-size-inherit">
                <!--delete-->
                @if(config('visibility.action_buttons_delete'))

                <button type="button" title="{{ cleanLang(__('lang.delete')) }}"
                    class="data-toggle-action-tooltip btn btn-outline-danger btn-circle btn-sm confirm-action-danger"
                    data-confirm-title="{{ cleanLang(__('lang.delete_user')) }}"
                    data-confirm-text="{{ cleanLang(__('lang.are_you_sure')) }}" data-ajax-type="DELETE"
                    data-url="{{ url('/') }}/address/{{ $address->id }}">
                    <i class="sl-icon-trash"></i>
                </button>

                @endif
                <!--edit-->
                @if (config('visibility.action_buttons_edit'))
                    <button type="button" title="{{ cleanLang(__('lang.edit')) }}"
                        class="data-toggle-action-tooltip btn btn-outline-success btn-circle btn-sm edit-add-modal-button js-ajax-ux-request reset-target-modal-form"
                        data-toggle="modal" data-target="#commonModal"
                        data-url="{{ urlResource('/address/' . $address->id . '/edit') }}"
                        data-loading-target="commonModalBody" data-modal-title="Edit Address"
                        data-action-url="{{ urlResource('/address/' . $address->id . '?ref=list') }}"
                        data-action-method="PUT" data-action-ajax-class=""
                        data-action-ajax-loading-target="address-td-container">
                        <i class="sl-icon-note"></i>
                    </button>
                @endif
            </span>
            <!--action button-->
        </td>

    </tr>
@endforeach
<!--each row-->
<script>
    $(document).on("change", ".form-check-input", function(e) {
        let id = $(this).data('id'); // Use data() method to get the value of the data-id attribute
        let clientId = $(this).data('client-id');
        let status = confirm("Do You Want To Set Default Address");

        if (status == true) {
            $.ajax({
                url: "{{ route('setDefaultAddress') }}",
                type: "get",
                data: {
                    id: id,
                    client_id: clientId
                },
                success: function(res) {
                    window.location.reload(1);
                }
            });
        }
    });
</script>
