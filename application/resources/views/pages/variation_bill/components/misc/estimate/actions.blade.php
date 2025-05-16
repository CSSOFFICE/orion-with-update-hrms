<!--CRUMBS CONTAINER (RIGHT)-->
<div class="col-md-12  col-lg-5 align-self-center text-right p-b-9  {{ $page['list_page_actions_size'] ?? '' }} {{ $page['list_page_container_class'] ?? '' }}"
    id="list-page-actions-container">
    <div id="list-page-actions">
        @if (auth()->user()->is_team && auth()->user()->role->role_estimates > 2)
        <!--publish-->
        @if ($bill->bill_status == 'draft')
        <button type="button" data-toggle="tooltip" title="{{ cleanLang(__('lang.publish_estimate')) }}"
            class="list-actions-button btn btn-page-actions waves-effect waves-dark confirm-action-info"
            href="javascript:void(0)" data-confirm-title="{{ cleanLang(__('lang.publish_estimate')) }}"
            data-confirm-text="{{ cleanLang(__('lang.the_estimate_will_be_sent_to_customer')) }}"
            data-url="{{ urlResource('/estimates/' . $bill->bill_estimateid . '/publish') }}"
            id="estimate-action-publish-estimate"><i class="sl-icon-share-alt"></i></button>
        @endif
        <!--mark as revised-->
        @if ($bill->bill_status == 'declined')
        <button type="button" data-toggle="tooltip"
            title="{{ cleanLang(__('lang.publish_revised_estimate')) }}"
            class="list-actions-button btn btn-page-actions waves-effect waves-dark confirm-action-info"
            href="javascript:void(0)" data-confirm-title="{{ cleanLang(__('lang.publish_revised_estimate')) }}"
            data-confirm-text="{{ cleanLang(__('lang.the_estimate_will_be_marked_as_revised')) }}"
            data-url="{{ urlResource('/estimates/' . $bill->bill_estimateid . '/publish-revised') }}"
            id="estimate-action-publish-revised-estimate"><i class="sl-icon-share-alt"></i></button>
        @endif
        @if ($bill->bill_status == 'accepted')
        <button type="button"
            class="list-actions-button btn btn-page-actions waves-effect waves-dark actions-modal-button js-ajax-ux-request reset-target-modal-form"
            title="Change Status" data-toggle="modal" data-target="#actionsModal"
            data-modal-title="Add Form"
            data-url="{{ urlResource('/estimates/' . $bill->bill_estimateid . '/add-form') }}"
            data-action-url="{{ urlResource('/estimates/' . $bill->bill_estimateid . '/add-form') }}"
            data-loading-target="actionsModalBody" data-action-method="POST">
            <i class="fa fa-plus-square-o" aria-hidden="true"></i>
        </button>
        @endif
        @if ($bill->bill_status == 'waiting_for_approvall')
        <!--Approved from Management-->
        <button type="button" data-toggle="tooltip" title="Approved From Management"
            class="list-actions-button btn  reset-target-modal-form btn-page-actions" id="status_change"
            value="{{ $bill->bill_estimateid }}"><i class="ti-thumb-up"></i></button>

        @endif
        <!--actions button - change category-->

        <button type="button"
            class="list-actions-button hidden btn btn-page-actions waves-effect waves-dark actions-modal-button js-ajax-ux-request reset-target-modal-form"
            title="Change Status" data-toggle="modal" data-target="#actionsModal"
            data-modal-title="{{ cleanLang(__('lang.change_status')) }}"
            data-url="{{ urlResource('/estimates/' . $bill->bill_estimateid . '/change-status') }}"
            data-action-url="{{ urlResource('/estimates/' . $bill->bill_estimateid . '/change-status') }}"
            data-loading-target="actionsModalBody" data-action-method="POST">
            <i class="ti-more-alt"></i></button>

        <!--email estimate-->
        <button type="button" data-toggle="tooltip" title="{{ cleanLang(__('lang.send_email')) }}"
            class="list-actions-button btn hidden btn-page-actions waves-effect waves-dark confirm-action-info"
            href="javascript:void(0)" data-confirm-title="{{ cleanLang(__('lang.send_email')) }}"
            data-confirm-text="{{ cleanLang(__('lang.confirm')) }}"
            data-url="{{ urlResource('/estimates/' . $bill->bill_estimateid . '/resend') }}"
            id="estimate-action-email-estimate"><i class="ti-email"></i></button>
        <!--edit-->
        <span class="dropdown">
            <button type="button" data-toggle="dropdown" id="defaultneed" title="{{ cleanLang(__('lang.edit')) }}"
                aria-haspopup="true" aria-expanded="false"
                class="data-toggle-tooltip list-actions-button btn btn-page-actions waves-effect waves-dark">
                <i class="sl-icon-note"></i>
            </button>
            <div class="dropdown-menu" aria-labelledby="listTableAction">
                <a class="dropdown-item"
                href="{{ url('project/' . $bill->bill_projectid . '/estimates/'.$bill->vo_id)}}/edit-estimate">Edit Variation Order</a>
                <!--attach project-->
                <a class="dropdown-item confirm-action-danger hidden {{ runtimeVisibility('dettach-estimate', $bill->bill_projectid) }}"
                    href="javascript:void(0)" data-confirm-title="{{ cleanLang(__('lang.detach_from_project')) }}"
                    id="bill-actions-dettach-project" data-confirm-text="{{ cleanLang(__('lang.are_you_sure')) }}"
                    data-url="{{ urlResource('/estimates/' . $bill->bill_estimateid . '/detach-project') }}">
                    {{ cleanLang(__('lang.detach_from_project')) }}</a>
                <!--deattach project-->
                <a class="dropdown-item hidden actions-modal-button js-ajax-ux-request reset-target-modal-form {{ runtimeVisibility('attach-estimate', $bill->bill_projectid) }}"
                    href="javascript:void(0)" data-toggle="modal" data-target="#actionsModal"
                    id="bill-actions-attach-project"
                    data-modal-title="{{ cleanLang(__('lang.attach_to_project')) }}"
                    data-url="{{ urlResource('/estimates/' . $bill->bill_estimateid . '/attach-project?client_id=' . $bill->bill_clientid) }}"
                    data-action-url="{{ urlResource('/estimates/' . $bill->bill_estimateid . '/attach-project') }}"
                    data-loading-target="actionsModalBody" data-action-method="POST">
                    {{ cleanLang(__('lang.attach_to_project')) }}</a>
            </div>
        </span>

        <!--delete-->
        <button type="button" data-toggle="tooltip" title="Delete Quotation"
            class="list-actions-button btn btn-page-actions waves-effect waves-dark confirm-action-danger"
            data-confirm-title="Delete Quotation" data-confirm-text="{{ cleanLang(__('lang.are_you_sure')) }}"
            data-ajax-type="DELETE"
            data-url="{{ url('/') }}/estimates/{{ $bill->bill_estimateid }}?source=page"><i
                class="sl-icon-trash"></i></button>

        <script>
            $('#status_change').on('click', function() {
                var csrf_token = $('meta[name="csrf-token"]').attr('content');

                $.ajax({
                    url: "{{ urlResource('/estimates/' . $bill->bill_estimateid . '/change-status') }}",
                    type: "POST",
                    data: {
                        iiid: this.value,
                        _token: csrf_token
                    },
                    success: function(JSON) {
                        if (JSON) {
                            alert("Status Updated Successfully")

                        }
                    },
                    error: function() {

                    }
                });

            });
        </script>

        @endif

        <!--Download PDF-->
        {{-- {{ url('/')}}/estimates/export-quotation/{{$bill->bill_estimateid}} --}}
        <a href="{{url('/')}}/estimates/export-quotation/{{$bill->bill_estimateid}}"
        data-toggle="tooltip" id="download-button" title="{{ cleanLang(__('lang.download')) }}"
            class="list-actions-button btn btn-page-actions waves-effect waves-dark">
            <i class="mdi mdi-download"></i>
        </a>

    </div>
</div>
