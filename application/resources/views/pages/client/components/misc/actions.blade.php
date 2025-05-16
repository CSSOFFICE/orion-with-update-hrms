<!--CRUMBS CONTAINER (RIGHT)-->
<div class="col-md-12  col-lg-6 align-self-center text-right parent-page-actions p-b-9" id="list-page-actions-container">
    <div id="list-page-actions">
        <!--edit (nb: the second condition is needed for timeline [right actions nav] replacement-->
        @if (auth()->user()->role->role_clients >= 2)
            <span class="dropdown">
                <button type="button" data-toggle="dropdown" title="{{ cleanLang(__('lang.edit')) }}" aria-haspopup="true"
                    aria-expanded="false"
                    class="data-toggle-tooltip list-actions-button btn btn-page-actions waves-effect waves-dark">
                    <i class="sl-icon-note"></i>
                </button>

                <div class="dropdown-menu" aria-labelledby="listTableAction">
                    <a class="dropdown-item edit-add-modal-button js-ajax-ux-request reset-target-modal-form"
                        href="javascript:void(0)" data-toggle="modal" data-target="#commonModal"
                        data-url="{{ urlResource('/clients/' . $client->client_id . '/edit') }}"
                        data-loading-target="commonModalBody" data-modal-title="{{ cleanLang(__('lang.edit_client')) }}"
                        data-action-url="{{ urlResource('/clients/' . $client->client_id . '?ref=list') }}"
                        data-action-method="PUT" data-action-ajax-loading-target="clients-td-container">
                        {{ cleanLang(__('lang.edit_client')) }}</a>
                    <!--upload logo-->
                    <a class="dropdown-item edit-add-modal-button js-ajax-ux-request reset-target-modal-form"
                        href="javascript:void(0)" data-toggle="modal" data-target="#commonModal"
                        data-url="{{ url('/clients/logo?source=page&client_id=' . $client->client_id) }}"
                        data-loading-target="commonModalBody" data-modal-size="modal-sm"
                        data-modal-title="{{ cleanLang(__('lang.update_avatar')) }}" data-header-visibility="hidden"
                        data-header-extra-close-icon="visible"
                        data-action-url="{{ url('/clients/logo?source=page&client_id=' . $client->client_id) }}"
                        data-action-method="PUT">
                        {{ cleanLang(__('lang.change_logo')) }}</a>
                    <a class="dropdown-item delete_logo_crm" data-header-extra-close-icon="visible"
                        href="javascript:void(0)" data-id="{{ $client->client_id }}">
                        {{ 'Delete Logo' }}</a>
                </div>
            </span>
        @endif

        @if (auth()->user()->role->role_clients >= 3)
            <!--delete-->
            <button type="button" data-toggle="tooltip" title="{{ cleanLang(__('lang.delete_client')) }}"
                class="list-actions-button btn btn-page-actions waves-effect waves-dark confirm-action-danger"
                data-confirm-title="{{ cleanLang(__('lang.delete_item')) }}"
                data-confirm-text="{{ cleanLang(__('lang.are_you_sure')) }}" data-ajax-type="DELETE"
                data-url="{{ url('/clients/' . $client->client_id) }}"><i class="sl-icon-trash"></i></button>
        @endif
    </div>
</div>
<script>
    $("body").on("click", ".delete_logo_crm", function() {
        let client_id = $(this).data('id');

        $.ajax({
            url: "{{ route('client.delete_logo') }}",
            type: "get",
            data: {
                client_id
            },
            success: function(res) {
                location.reload();
            }
        })

    })
</script>
