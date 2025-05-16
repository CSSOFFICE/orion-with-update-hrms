<style>
    #plainModalContainer {
        width: 100%; /* Set your desired width */
        max-width: 700px; /* Optionally set a max width */
    }
</style>

<div class="row">
<div class="col-12">
    <div class="table-responsive receipt">
        <table class="table table-bordered">
            <tbody>
                <!--po-->
                <tr>
                    <td>Purchase Order Number</td>
                    <td>{{ $expense->porder_id }}</td>
                </tr>

                <!--date-->
                <tr>
                    <td>{{ cleanLang(__('lang.date')) }}</td>
                    <td>{{ runtimeDate($expense->expense_date) }}</td>
                </tr>

                <!--client-->
                {{-- <tr>
                    <td>{{ cleanLang(__('lang.client')) }}</td>
                    <td>{{ $expense->client_company_name }}</td>
                </tr> --}}

                <!--project-->
                {{-- <tr>
                    <td>{{ cleanLang(__('lang.project')) }}</td>
                    <td>{{ $expense->project_title }}</td>
                </tr> --}}

                <!--user-->
                {{-- <tr>
                    <td>{{ cleanLang(__('lang.recorded_by')) }}</td>
                    <td>{{ $expense->first_name }} {{ $expense->last_name }}</td>
                </tr> --}}

                <!--description-->
                {{-- <tr>
                    <td>{{ cleanLang(__('lang.description')) }}</td>
                    <td>{{ $expense->expense_description }}</td>
                </tr> --}}

                <!--Attchment-->
                {{-- <tr>
                    <td>{{ cleanLang(__('lang.attachement')) }}</td>
                    <td>
                        @foreach($attachments as $attachment)
                        <ul class="p-l-0">
                            <li  id="fx-expenses-files-attached">
                                <a href="expenses/attachments/download/{{ $attachment->attachment_uniqiueid }}" download>
                                    {{ $attachment->attachment_filename }} <i class="ti-download"></i>
                                </a>
                            </li>
                        </ul>
                        @endforeach
                    </td>
                </tr> --}}

                <!--Attchment-->
                {{--<tr>
                    <td>{{ cleanLang(__('lang.attachement')) }}</td>
                    <td>
                        @if (!empty($expense->expense_attachment))
                            <a href="{{url('hrms/uploads/payment/'.$expense->expense_attachment)}}" target="_blank">Click here to view</a>
                        @else
                            ---
                        @endif
                    </td>
                </tr>--}}

                <!--date-->
                <!--description-->
                {{-- <tr>
                    <td>{{ cleanLang(__('lang.financial')) }}</td>
                    <td>
                        <span
                            class="label {{ runtimeExpenseStatusColors($expense->expense_billable, 'label') }}">{{ runtimeLang($expense->expense_billable) }}</span> <span
                            class="label {{ runtimeExpenseStatusColors($expense->expense_billing_status, 'label') }}">{{
                        runtimeLang($expense->expense_billing_status) }}</span>
                    </td>
                </tr> --}}

                <tr>
                    <td id="fx-expenses-td-amount">{{ cleanLang(__('lang.amount')) }}</td>
                    <td id="fx-expenses-td-money">{{ runtimeMoneyFormat($expense->total_amount) }}</td>
                </tr>
            </tbody>
        </table>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <td>Invoice No</td>
                    <td>Total Amount</td>
                    <td>Invoice Date</td>
                    <td>DO Number</td>
                    <td>Attachment</td>
                    <td>Status</td>
                </tr>
            </thead>
            <tbody>
                @foreach ($xin_payable_total as $item)
                    <tr>
                        <td>{{$item->invoice_no}}</td>
                        <td>{{$item->after_gst_po_gt}}</td>
                        <td style="width:200px">{{ runtimeDate($expense->expense_date) }}</td>
                        <td style="width:200px">{{$item->do_no}}</td>
                        <td>

                         @if (!empty($item->exp_attachment))
                            <a href="{{url('hrms/uploads/payment/'.$item->exp_attachment)}}" target="_blank">Click here to view</a>
                        @else
                            ---
                        @endif
                        </td>
                        <td>{{!empty($item->status) ? $item->status : 'Not Paid'}}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
</div>
