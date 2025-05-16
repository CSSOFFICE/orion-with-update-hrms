@php
    $purchase_purpose = DB::table('purchase_purpose')->get();
    $all_customers = DB::table('xin_employees')->get();
    $all_products = DB::table('product')->get();

@endphp


@if (isset($page['section']) && $page['section'] == 'show')
    <div class="row">
        <div class="col-lg-12">

            <!--title-->


            <!--description-->
            <div class="form-group row">
                <label>Purpose of Purchase</label>
                <select class="form-control" name="pp" @readonly(true)>
                    <option>Select</option>
                    @foreach ($purchase_purpose as $purchase)
                        <option value="{{ $purchase->purpose_title }}"
                            {{ $note->purchase == $purchase->purpose_title ? 'selected' : '' }}>
                            {{ $purchase->purpose_title ?? '' }}</option>
                    @endforeach
                </select>
            </div>
            <input type="hidden" name="uniq_id" id="" value="{{ $note->purchase_requistion_id }}">
            <input type="hidden" name="project_id" id="" value="{{ $note->project_id }}">
            <!--tags-->
            <div class="form-group row">
                <label>Date</label>
                <input type="date" name="pur_date" @readonly(true) value="{{ $note->required_date }}"
                    class="form-control">
            </div>
            <div class="form-group row">
                <label for="customer_id">Employee Name<i class="hrsale-asterisk">*</i></label>
                <select name="customer_id" id="customer_id" @readonly(true) class="form-control"
                    data-plugin="select_hrm" data-placeholder="Employee Name">
                    <option value="">Select Employee</option>
                    @foreach ($all_customers as $customer)
                        <option value="{{ $customer->user_id }}" {{ $note->customer_id ? 'selected' : '' }}>
                            {{ $customer->first_name }}{{ $customer->last_name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group row">
                <label>Project Site Address</label>
                <textarea class="form-control" @readonly(true) name="s_address" id="s_address">{{ $note->site_address ?? '' }}</textarea>
            </div>
            <div class="form-group row">
                <label>Location:</label>
                <select name="location" class="form-control" id="" @readonly(true)>
                    <option value="{{ $note->location ?? '' }}">{{ $note->location ?? '' }}</option>
                    <option value="workshop">Workshop</option>
                    <option value="site">Site</option>
                    <option value="office">Office</option>
                </select>

            </div>
            <div class="form-group row">
                <div class="table-responsive my-3 purchaseTable">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Sl</th>
                                <th>Item</th>
                                <th>Description</th>
                                <th>Quantity</th>
                                <th>Remark</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody class="AddItem" id="vendor_items_table1">
                            @foreach ($note_item as $k => $i)
                                <tr>
                                    <td style="min-width:130px">
                                        <label>{{ $k + 1 }}<label>
                                    </td>
                                    <td style="min-width:200px">
                                        <select class="packing_dropdown form-control select22" @readonly(true)
                                            name="product_id[{{ $k + 1 }}]" id="product_{{ $k + 1 }}"
                                            onchange="getProductDetail(this.value,{{ $k + 1 }})">
                                            <option value="">Select product</option>
                                            @foreach ($all_products as $product)
                                                <option value="{{ $product->product_id }}"
                                                    {{ $i->product_id == $product->product_id ? 'selected' : '' }}>
                                                    {{ $product->product_name }}</option>
                                            @endforeach
                                        </select>
                                    </td>

                                    <td>
                                        <textarea @readonly(true) id="description{{ $k + 1 }}" @readonly(true) class="form-control"
                                            name="description[{{ $k + 1 }}]" placeholder="Description">{{ $i->description }}</textarea>
                                    </td>
                                    <td style="min-width:200px">
                                        <input @readonly(true) type="number" min="0" id="quantity` + item + `"
                                            value="{{ $i->qty }}" class="form-control"
                                            name="quantity[{{ $k + 1 }}]" placeholder="Quantity">
                                    </td>
                                    <td style="min-width:200px">
                                        <input @readonly(true) type="text" id="remark{{ $k + 1 }}"
                                            class="form-control" name="remark[{{ $k + 1 }}]"
                                            placeholder="Remark" value="{{ $i->remark }}">
                                    </td>

                                    <td>

                                    </td>
                                </tr>
                            @endforeach

                        </tbody>

                    </table>
                </div>
            </div>
            <!--/#tags-->


            <!--pass source-->
            <input type="hidden" name="source" value="{{ request('source') }}">

            <!--notes-->
            <div class="row">
                <div class="col-12">
                    <div><small><strong>* {{ cleanLang(__('lang.required')) }}</strong></small></div>
                </div>
            </div>

            <!--info-->


        </div>
    </div>


    <script>
        $(document).ready(function() {

            $('#addButton1').on('click', function() {
                var number = $('.AddItem tr').length;
                var item = number + 1;
                $('.AddItem').append(`
                    <tr>
                    <td style="min-width:130px">
                            <label>` + item +
                    `<label>
                        </td>
                        <td style="min-width:200px">
                             <select class="packing_dropdown form-control select22" name="product_id[${item}]" id="product_"` +
                    item +
                    ` onchange="getProductDetail(this.value,` + item + `)">
                                <option value="">Select product</option>
                                <?php foreach ($all_products as $product) {
                                    echo '<option value="' . $product->product_id . '">' . $product->product_name . '</option>';
                                } ?>
                            </select>
                        </td>

                        <td >
                            <textarea id="description` + item + `" class="form-control" name="description[${item}]" placeholder="Description"></textarea>
                        </td>
                        <td style="min-width:200px">
                            <input type="number" min="0" id="quantity` + item + `" value="" class="form-control" name="quantity[${item}]" placeholder="Quantity">
                        </td>
                        <td style="min-width:200px">
                            <input type="text" id="remark` + item + `" class="form-control" name="remark[${item}]" placeholder="Remark">
                        </td>

                        <td>
                            <button type="button" name="clear" id="clear" class="btn btn-danger remove-input-field"><i class="ti-trash"></i></button>
                        </td>
                    </tr>
                `);

            });

        });


        function getProductDetail(id, number) {


            $.ajax({
                type: "get",

                url: "{{ url('get_product_details') }}",
                data: {
                    id
                },
                success: function(data) {
                    var product_data = jQuery.parseJSON(data);

                    $("#description" + number).text(product_data[0].description);
                },
                error: function() {
                    toastr.error("Description Not Found");
                }
            });
        }



        $(document).on('click', '.remove-input-field', function() {
            $(this).parents('tr').remove();


        });
    </script>
@endif
@if (isset($page['section']) && $page['section'] == 'edit')
    <div class="row">
        <div class="col-lg-12">

            <!--title-->


            <!--description-->
            <div class="form-group row">
                <label>Purpose of Purchase</label>
                <select class="form-control" name="pp">
                    <option>Select</option>
                    @foreach ($purchase_purpose as $purchase)
                        <option value="{{ $purchase->purpose_title }}"
                            {{ $note->purchase == $purchase->purpose_title ? 'selected' : '' }}>
                            {{ $purchase->purpose_title ?? '' }}</option>
                    @endforeach
                </select>
            </div>
            <input type="hidden" name="uniq_id" id="" value="{{ $note->purchase_requistion_id }}">
            <input type="hidden" name="project_id" id="" value="{{ $note->project_id }}">
            <!--tags-->
            <div class="form-group row">
                <label>Date</label>
                <input type="date" name="pur_date" value="{{ $note->required_date }}" class="form-control">
            </div>
            <div class="form-group row">
                <label for="customer_id">Employee Name<i class="hrsale-asterisk">*</i></label>
                <select name="customer_id" id="customer_id" class="form-control" data-plugin="select_hrm"
                    data-placeholder="Employee Name">
                    <option value="">Select Employee</option>
                    @foreach ($all_customers as $customer)
                        <option value="{{ $customer->user_id }}" {{ $note->customer_id ? 'selected' : '' }}>
                            {{ $customer->first_name }}{{ $customer->last_name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group row">
                <label>Project Site Address</label>
                <textarea class="form-control" name="s_address" id="s_address">{{ $note->site_address ?? '' }}</textarea>
            </div>
            <div class="form-group row">
                <label>Location:</label>
                <select name="location" class="form-control" id="">
                    <option value="{{ $note->location ?? '' }}">{{ $note->location ?? '' }}</option>
                    <option value="workshop">Workshop</option>
                    <option value="site">Site</option>
                    <option value="office">Office</option>
                </select>

            </div>
            <div class="form-group row">
                <div class="table-responsive my-3 purchaseTable">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Sl</th>
                                <th>Item</th>
                                <th>Description</th>
                                <th>Quantity</th>
                                <th>Remark</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody class="AddItem" id="vendor_items_table1">
                            @foreach ($note_item as $k => $i)
                                <tr>
                                    <td style="min-width:130px">
                                        <label>{{ $k + 1 }}<label>
                                    </td>
                                    <td style="min-width:200px">
                                        <select class="packing_dropdown form-control select22"
                                            name="product_id[{{ $k + 1 }}]" id="product_{{ $k + 1 }}"
                                            onchange="getProductDetail(this.value,{{ $k + 1 }})">
                                            <option value="">Select product</option>
                                            @foreach ($all_products as $product)
                                                <option value="{{ $product->product_id }}"
                                                    {{ $i->product_id == $product->product_id ? 'selected' : '' }}>
                                                    {{ $product->product_name }}</option>
                                            @endforeach
                                        </select>
                                    </td>

                                    <td>
                                        <textarea id="description{{ $k + 1 }}" class="form-control" name="description[{{ $k + 1 }}]"
                                            placeholder="Description">{{ $i->description }}</textarea>
                                    </td>
                                    <td style="min-width:200px">
                                        <input type="number" min="0" id="quantity` + item + `"
                                            value="{{ $i->qty }}" class="form-control"
                                            name="quantity[{{ $k + 1 }}]" placeholder="Quantity">
                                    </td>
                                    <td style="min-width:200px">
                                        <input type="text" id="remark{{ $k + 1 }}" class="form-control"
                                            name="remark[{{ $k + 1 }}]" placeholder="Remark"
                                            value="{{ $i->remark }}">
                                    </td>

                                    <td>
                                        <button type="button" name="clear" id="clear"
                                            class="btn btn-danger remove-input-field"><i
                                                class="ti-trash"></i></button>
                                    </td>
                                </tr>
                            @endforeach

                        </tbody>
                        <tfoot>
                            <tr>
                                <th style="border: none !important;">
                                    <a href="javascript:void(0)" class="btn-sm btn-success" id="addButton1">Add</a>
                                </th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            <!--/#tags-->


            <!--pass source-->
            <input type="hidden" name="source" value="{{ request('source') }}">

            <!--notes-->
            <div class="row">
                <div class="col-12">
                    <div><small><strong>* {{ cleanLang(__('lang.required')) }}</strong></small></div>
                </div>
            </div>

            <!--info-->


        </div>
    </div>


    <script>
        $(document).ready(function() {

            $('#addButton1').on('click', function() {
                var number = $('.AddItem tr').length;
                var item = number + 1;
                $('.AddItem').append(`
                    <tr>
                    <td style="min-width:130px">
                            <label>` + item +
                    `<label>
                        </td>
                        <td style="min-width:200px">
                             <select class="packing_dropdown form-control select22" name="product_id[${item}]" id="product_"` +
                    item +
                    ` onchange="getProductDetail(this.value,` + item + `)">
                                <option value="">Select product</option>
                                <?php foreach ($all_products as $product) {
                                    echo '<option value="' . $product->product_id . '">' . $product->product_name . '</option>';
                                } ?>
                            </select>
                        </td>

                        <td >
                            <textarea id="description` + item + `" class="form-control" name="description[${item}]" placeholder="Description"></textarea>
                        </td>
                        <td style="min-width:200px">
                            <input type="number" min="0" id="quantity` + item + `" value="" class="form-control" name="quantity[${item}]" placeholder="Quantity">
                        </td>
                        <td style="min-width:200px">
                            <input type="text" id="remark` + item + `" class="form-control" name="remark[${item}]" placeholder="Remark">
                        </td>

                        <td>
                            <button type="button" name="clear" id="clear" class="btn btn-danger remove-input-field"><i class="ti-trash"></i></button>
                        </td>
                    </tr>
                `);

            });

        });


        function getProductDetail(id, number) {


            $.ajax({
                type: "get",

                url: "{{ url('get_product_details') }}",
                data: {
                    id
                },
                success: function(data) {
                    var product_data = jQuery.parseJSON(data);

                    $("#description" + number).text(product_data[0].description);
                },
                error: function() {
                    toastr.error("Description Not Found");
                }
            });
        }



        $(document).on('click', '.remove-input-field', function() {
            $(this).parents('tr').remove();


        });
    </script>
@endif
@if (isset($page['section']) && $page['section'] == 'create')
    <div class="row">
        <div class="col-lg-12">

            <!--title-->


            <!--description-->
            <div class="form-group row">
                <label>Purpose of Purchase</label>
                <select class="form-control" name="pp">
                    <option>Select</option>
                    <?php foreach ($purchase_purpose as $purchase) { ?>
                    <option value="<?php echo $purchase->purpose_title; ?>"><?php echo $purchase->purpose_title; ?></option>
                    <?php } ?>
                </select>
            </div>

            <!--tags-->
            <div class="form-group row">
                <label>Date</label>
                <input type="date" name="pur_date" class="form-control">
            </div>
            <div class="form-group row">
                <label for="customer_id">Employee Name<i class="hrsale-asterisk">*</i></label>
                <select name="customer_id" id="customer_id" class="form-control" data-plugin="select_hrm"
                    data-placeholder="Employee Name">
                    <option value="">Select Employee</option>
                    <?php foreach ($all_customers as $customer) { ?>
                    <option value="<?php echo $customer->user_id; ?>"> <?php echo $customer->first_name . ' ' . $customer->last_name; ?>
                    </option>
                    <?php } ?>
                </select>
            </div>
            <div class="form-group row">
                <label>Project Site Address</label>
                <textarea class="form-control" name="s_address" id="s_address"></textarea>
            </div>

            <div class="form-group row">
                <label>Location:</label>
                <select name="location" class="form-control" id="">

                    <option value="workshop">Workshop</option>
                    <option value="site">Site</option>
                    <option value="office">Office</option>
                </select>

            </div>

            <div class="form-group row">
                <div class="table-responsive my-3 purchaseTable">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Sl</th>
                                <th>Item</th>
                                <th>Description</th>
                                <th>Quantity</th>
                                <th>Remark</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody class="AddItem" id="vendor_items_table1"></tbody>
                        <tfoot>
                            <tr>
                                <th style="border: none !important;">
                                    <a href="javascript:void(0)" class="btn-sm btn-success" id="addButton1">Add</a>
                                </th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            <!--/#tags-->


            <!--pass source-->
            <input type="hidden" name="source" value="{{ request('source') }}">

            <!--notes-->
            <div class="row">
                <div class="col-12">
                    <div><small><strong>* {{ cleanLang(__('lang.required')) }}</strong></small></div>
                </div>
            </div>

            <!--info-->


        </div>
    </div>


    <script>
        $(document).ready(function() {

            $('#addButton1').on('click', function() {
                var number = $('.AddItem tr').length;
                var item = number + 1;
                $('.AddItem').append(`
                    <tr>
                    <td style="min-width:130px">
                            <label>` + item +
                    `<label>
                        </td>
                        <td style="min-width:200px">
                             <select class="packing_dropdown form-control select22" name="product_id[${item}]" id="product_"` +
                    item +
                    ` onchange="getProductDetail(this.value,` + item + `)">
                                <option value="">Select product</option>
                                <?php foreach ($all_products as $product) {
                                    echo '<option value="' . $product->product_id . '">' . $product->product_name . '</option>';
                                } ?>
                            </select>
                        </td>

                        <td >
                            <textarea id="description` + item + `" class="form-control" name="description[${item}]" placeholder="Description"></textarea>
                        </td>
                        <td style="min-width:200px">
                            <input type="number" min="0" id="quantity` + item + `" value="0" class="form-control" name="quantity[${item}]" placeholder="Quantity">
                        </td>
                        <td style="min-width:200px">
                            <input type="text" id="remark` + item + `" class="form-control" name="remark[${item}]" placeholder="Remark">
                        </td>

                        <td>
                            <button type="button" name="clear" id="clear" class="btn btn-danger remove-input-field"><i class="ti-trash"></i></button>
                        </td>
                    </tr>
                `);

            });

        });


        function getProductDetail(id, number) {


            $.ajax({
                type: "get",

                url: "{{ url('get_product_details') }}",
                data: {
                    id
                },
                success: function(data) {
                    var product_data = jQuery.parseJSON(data);

                    $("#description" + number).text(product_data[0].description);
                },
                error: function() {
                    toastr.error("Description Not Found");
                }
            });
        }



        $(document).on('click', '.remove-input-field', function() {
            $(this).parents('tr').remove();


        });
    </script>
@endif
