<!-- Column -->
<div class="card">
    <!--has logo-->
    @if (isset($client['client_logo_folder']) && $client['client_logo_folder'] != '')
        <div class="card-body profile_header">
            <img
                src="{{ url('/') }}/storage/logos/clients/{{ $client['client_logo_folder'] ?? '0' }}/{{ $client['client_logo_filename'] ?? '' }}">
        </div>
    @else
        <!--no logo -->
        <div class="card-body profile_header client logo-text">
            @if ($client->cust_type == 1)
                {{ $client->f_name }}
            @else
                {{ $client->client_company_name }}
            @endif
        </div>
    @endif
    <div class="card-body p-t-0 p-b-0">
        @if (auth()->user()->is_team)
            <div>
                <small class="text-muted">
                    @if ($client->cust_type == 1)
                        Customer Name
                    @else
                        Company Name
                    @endif
                </small>
                <h6>
                    @if ($client->cust_type == 1)
                        {{ $client->f_name }}
                    @else
                        {{ $client->client_company_name }}
                    @endif
                </h6>

                @if ($client->cust_type == 0)
                    <small class="text-muted">Company UEN</small>
                    <h6>{{ $client->com_uen }}</h6>
                @endif

                <small class="text-muted">Contact Number</small>
                <h6>{{ $client->client_phone }}</h6>

                <small class="text-muted">Customer Code</small>
                <h6>{{ $client->cust_code1 }}</h6>

                <small class="text-muted">Email Address</small>
                <h6>{{ $client->u_email }}</h6>


                <span class="text-muted">{{ cleanLang(__('lang.account_owner')) }}</span><span
                    class="text-muted m-1"><button type="button"
                        class="btn btn-light text-muted add_more_than_account_owener">edit</button></span>

                <div class="m-b-10"><img
                        src="{{ getUsersAvatar($owner->avatar_directory ?? '', $owner->avatar_filename ?? '') }}"
                        alt="user"
                        class="img-circle avatar-xsmall">{{ Auth()->user()->first_name }}{{ Auth()->user()->last_name }}
                </div>
                @php
                    $more = DB::table('xin_employees')
                        ->where('client_id', $client->client_id)
                        ->get();
                @endphp
                @foreach ($more as $m)
                <h6>{{ $m->first_name }}</h6>
                @endforeach

                @if ($client->currency)
                    <small class="text-muted">Currency</small>
                    <?php $currency = DB::table('xin_currencies')
                        ->where('currency_id', $client->currency)
                        ->get(); ?>
                    <h6>{{ $currency[0]->name ?? '' }} ({{ $currency[0]->symbol ?? '' }})</h6>
                @endif

                <small class="text-muted ">{{ cleanLang(__('lang.account_status')) }}</small>
                <div class="">
                    @if ($client->client_status == 'active')
                        <a class="dropdown-item client_status_change" data-id="{{ $client->client_id }}" data-type="1"
                            href="javascript:void(0)"><span
                                class="badge badge-pill badge-success">{{ cleanLang(__('lang.active')) }}</span></a>
                    @else
                        <a class="dropdown-item client_status_change" data-id="{{ $client->client_id }}" data-type="0"
                            href="javascript:void(0)"><span
                                class="badge badge-pill badge-danger">{{ cleanLang(__('lang.suspended')) }}</span></a>
                    @endif
                </div>
            </div>
        @endif
    </div>
    <div>
        <hr>
    </div>
    <div class="card-body p-t-0 p-b-0">
        <div>
            <table class="table no-border m-b-0">
                <tbody>
                    <!--invoices-->
                    <tr>
                        <td class="p-l-0 p-t-5"id="fx-client-left-panel-invoices">{{ cleanLang(__('lang.invoices')) }}
                        </td>
                        <td class="font-medium p-r-0 p-t-5">
                            {{ runtimeMoneyFormat($client->sum_invoices_all) }}
                            <div class="progress">
                                <div class="progress-bar bg-info  w-100 h-px-3" role="progressbar" aria-valuenow="25"
                                    aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </td>
                    </tr>
                    <!--payments-->
                    <!-- <tr>
                        <td class="p-l-0 p-t-5">{{ cleanLang(__('lang.payments')) }}</td>
                        <td class="font-medium p-r-0 p-t-5">{{ runtimeMoneyFormat($client->sum_all_payments) }}
                            <div class="progress">
                                <div class="progress-bar bg-success w-100 h-px-3" role="progressbar"aria-valuenow="25" aria-valuemin="0"
                                    aria-valuemax="100"></div>
                            </div>
                        </td>
                    </tr> -->
                    <!--completed projects-->
                    <tr>
                        <td class="p-l-0 p-t-5">{{ cleanLang(__('lang.completed_projects')) }}</td>
                        <td class="font-medium p-r-0 p-t-5">{{ $client->count_projects_completed }}
                            <div class="progress">
                                <div class="progress-bar bg-warning  w-100 h-px-3" role="progressbar" aria-valuenow="25"
                                    aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </td>
                    </tr>
                    <!--open projects-->
                    <tr>
                        <td class="p-l-0 p-t-5">{{ cleanLang(__('lang.open_projects')) }}</td>
                        <td class="font-medium p-r-0 p-t-5">{{ $client->count_projects_pending }}
                            <div class="progress">
                                <div class="progress-bar bg-danger w-100 h-px-3" role="progressbar"aria-valuenow="25"
                                    aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </td>
                    </tr>
                    <!--Credit Limit-->
                    <tr>
                        <td class="p-l-0 p-t-5">Credit Limit</td>
                        <td class="font-medium p-r-0 p-t-5">{{ $client->credit_term }}
                            <div class="progress">
                                <div class="progress-bar bg-success w-100 h-px-3"
                                    role="progressbar"aria-valuenow="{{ $client->credit_term }}" aria-valuemin="0"
                                    aria-valuemax="100"></div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="p-l-0 p-t-5">Out Standing</td>
                        <td class="font-medium p-r-0 p-t-5">{{ $client->out_standing }}
                            <div class="progress">
                                <div class="progress-bar bg-success w-100 h-px-3"
                                    role="progressbar"aria-valuenow="{{ $client->out_standing }}" aria-valuemin="0"
                                    aria-valuemax="100"></div>
                            </div>
                        </td>
                    </tr>

                </tbody>
            </table>
        </div>
    </div>
    <div>
        <hr>
    </div>
    <!--client address-->
    <!-- <div class="card-body p-t-0 p-b-0">
        <small class="text-muted">{{ cleanLang(__('lang.address')) }}</small>
        @if ($client->client_billing_street !== '')
<h6>{{ $client->client_billing_street }}</h6>
@endif
        @if ($client->client_billing_city !== '')
<h6>{{ $client->client_billing_city }}</h6>
@endif
        @if ($client->client_billing_state !== '')
<h6>{{ $client->client_billing_state }}</h6>
@endif
        @if ($client->client_billing_zip !== '')
<h6>{{ $client->client_billing_zip }}</h6>
@endif
        @if ($client->client_billing_country !== '')
<h6>{{ $client->client_billing_country }}</h6>
@endif
    </div> -->

    <div class="d-none last-line">
        <hr>
    </div>
</div>
<!-- Column -->

<script>
    $("body").on("click", ".client_status_change", function() {
        let client_id = $(this).data('id');
        let type = $(this).data('type');


        $.ajax({
            url: "{{ route('client.client_status_change') }}",
            type: "get",
            data: {
                type,
                client_id
            },
            success: function(res) {
                location.reload();
            }
        })

    })
    $(".add_more_than_account_owener").on("click", function() {
        $("#myModal").modal("show");

    })
    $("body").on("click", "#uploadBtn", function(e) {
        e.preventDefault()
        let data = $("#multiuser_client").val();
        let client_id = "{{ $client->client_id }}";



        $.ajax({
            url: "{{ route('client.add_more_tha_owner') }}",
            type: "get",
            data: {
                data,
                client_id
            },
            success: function(res) {
                location.reload();
            }
        })

    })
</script>
@php
    $more_employee = DB::table('xin_employees')->get();
@endphp

<!--The Modal -->
<div class="modal" id="myModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="uploadForm">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Add Account Owner</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <!-- Modal body -->
                <div class="modal-body">
                    @csrf
                    <select name="multiuser_client[]" id="multiuser_client"
                        class="form-control form-control-sm select2-basic select2-multiple select2-tags select2-hidden-accessible"
                        multiple="multiple" tabindex="-1" aria-hidden="true">
                        @foreach ($more_employee as $key=>$em)
                            <option value="{{ $em->user_id }}" {{($more[$key]->user_id??0==$em->user_id)?'selected':''}}>{{ $em->first_name }}</option>
                        @endforeach
                    </select>


                </div>

                <!-- Modal footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-danger" id="uploadBtn">Update</button>
                </div>
            </form>

        </div>
    </div>
</div>
