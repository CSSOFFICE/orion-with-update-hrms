<div class="row">
    <div class="col-lg-12">
        <input type="hidden" id="prj_id" name="prj_id" value="{{ $project_id }}">
        <input type="hidden" id="product_id" name="product_id" value="{{ $product_id }}">
        <input type="hidden" id="old_ware" name="old_ware" value="{{ request('old_ware') }}">

        <!--title-->
        <div class="form-group row">
            <label for="Product Name">Product Name</label>
            <input type="text" value="{{ $product->product_name ?? '' }}" readonly class="form-control">
            <label>Quantity</label>
            <input type="number" value="1" min="1" max="{{ $total_quantity }}" class="form-control"
                id="quantity" name="quantity" onblur="checkQty(this)" required>
            <label>Warehouse</label>
            <select class="form-control" name="warehouse" id="warehouse" required>
                <option value="">Select Warehouse</option>
                @foreach ($warehouse as $wh)
                <option value="{{ $wh->w_id }}">{{ $wh->w_name }}</option>
                @endforeach
            </select>

            <label>Date</label>
            <input type="date" value="<?php echo date('Y-m-d'); ?>" class="form-control" name="date" required>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $("#commonModalForm").attr("id", "inventoryReturnModalForm");
        $("#inventoryReturnModalForm").attr("action", " ");
        $("#commonModalSubmitButton").attr("data-url", " ");
        $('#inventoryReturnModalForm').on('submit', function(e) {
            e.preventDefault();
            var inp_qty = $('#quantity').val();
            var max_qtn1 = '{{ $total_quantity }}';


            if (inp_qty > max_qtn1) {
                noty({
                    text: 'Avalable Quantity: ' + max_qtn1,
                    layout: 'bottomLeft',
                    type: 'warning',
                    timeout: 3000,
                    progressBar: false,
                    closeWith: ['click', 'button', 'backdrop'],
                });
                $(el).val(0);
                return false;
            } else {

                $.ajax({
                    type: "POST",
                    url: "{{ route('projectinventory.inventory-return-submit') }}",
                    data: $(this).serialize(),
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                            'content') // Add CSRF token to header
                    },
                    success: function(response) {
                        console.log(response)
                        if (response.status == "error") {
                            $.each(response.error, function(key, value) {
                                noty({
                                    text: value,
                                    layout: 'bottomLeft',
                                    type: 'warning',
                                    timeout: 3000,
                                    progressBar: false,
                                    closeWith: ['click', 'button',
                                        'backdrop'
                                    ],
                                });
                                // alert(value);

                            });
                        } else if (response.status == "success") {
                            noty({
                                text: response.status,
                                layout: 'bottomLeft',
                                type: 'success',
                                timeout: 3000,
                                progressBar: false,
                                closeWith: ['click', 'button', 'backdrop'],
                            });
                            location.reload();
                        } else {
                            noty({
                                text: response.message,
                                layout: 'bottomLeft',
                                type: 'warning',
                                timeout: 3000,
                                progressBar: false,
                                closeWith: ['click', 'button', 'backdrop'],
                            });
                        }
                    }
                });
            }


        });
    });

    function checkQty(el) {
        var input_qty = $(el).val();

        // var w_qty=$(el).parents('tr').find('.whouse').data('quantity')
        var max_qtn = '{{ $total_quantity }}';
        console.log(input_qty);
        console.log(max_qtn);

        if (input_qty > max_qtn) {
            noty({
                text: 'Avalable Quantity: ' + max_qtn,
                layout: 'bottomLeft',
                type: 'warning',
                timeout: 3000,
                progressBar: false,
                closeWith: ['click', 'button', 'backdrop'],
            });
            $(el).val(1);
        }

    }
</script>
