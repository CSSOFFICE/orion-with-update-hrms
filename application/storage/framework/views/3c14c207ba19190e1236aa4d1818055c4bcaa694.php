<style type="text/css">
    body {
        margin: 10px auto;
        background: #eee;

    }

    .invoice-brand-img {
        margin: 0;
        padding: 0;
        list-style-type: none;
    }

    .invoice-brand-img li {
        display: inline-block;
    }

    .bg-blue {
        background-color: #00b0f0;
    }

    .quotation-box {
        border: 2px solid #333;
    }

    .quotation-list-details {
        margin: 0;
        padding: 0;
        list-style-type: none;
    }

    .quotation-list-name {
        margin: 0;
        padding: 0;
        list-style-type: none;
        padding-left: 10px;
        height: 150px;
    }

    .quotation-box p {
        margin: 0;
        padding: 0;
    }
</style>
<?php
    use Illuminate\Support\Facades\DB;
    $pro = DB::table('product')->get();
    $p_item = DB::table('product_line_item')
        ->where('quotation_id', $bill->bill_estimateid)
        ->get();

?>


<div class="col-12" id="content-container">
    <form id="templates-form" method="POST" action="<?php echo e(url('/estimates/' . $bill->bill_estimateid . '/edit-estimate')); ?>">
        <?php echo csrf_field(); ?>
        <div id="template-summary" class="template-content">
            <?php echo $__env->make('pages.bill.components.elements.templates.summary', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        </div>
        <div id="template-preliminaries" class="template-content" style="display: none;">
            <?php echo $__env->make('pages.bill.components.elements.templates.preliminaries', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        </div>
        <div id="template-insurance" class="template-content" style="display: none;">
            <?php echo $__env->make('pages.bill.components.elements.templates.insurance', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        </div>
        <div id="template-schedule_of_works" class="template-content" style="display: none;">
            <?php echo $__env->make('pages.bill.components.elements.templates.schedule_of_works', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        </div>
        <div id="template-elec_acme" class="template-content" style="display: none;">
            <?php echo $__env->make('pages.bill.components.elements.templates.elec_acme', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        </div>
        <div id="template-plumbing_sanity" class="template-content" style="display: none;">
            <?php echo $__env->make('pages.bill.components.elements.templates.plumbing_sanity', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        </div>
        <div id="template-external_works" class="template-content" style="display: none;">
            <?php echo $__env->make('pages.bill.components.elements.templates.external_works', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        </div>
        <div id="template-pc_ps_sums" class="template-content" style="display: none;">
            <?php echo $__env->make('pages.bill.components.elements.templates.pc_ps_sums', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        </div>
        <div id="template-others" class="template-content" style="display: none;">
            <?php echo $__env->make('pages.bill.components.elements.templates.others', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        </div>
        <div class="text-right p-t-25">
            <?php if(config('visibility.bill_mode') == 'editing'): ?>
            <button type="submit" class="btn btn-danger btn-sm" id="save-button">Save Changes</button>
            <?php endif; ?>
        </div>
    </form>
</div>


<script>

    document.addEventListener('DOMContentLoaded', function() {
        const contentContainer = document.getElementById('content-container');
        const templates = document.querySelectorAll('.template-content');

        document.querySelectorAll('.option-button').forEach(function(button) {
            button.addEventListener('click', function() {
                const templateName = this.getAttribute('data-template');
                templates.forEach(template => {
                    template.style.display = 'none';
                });
                document.getElementById('template-' + templateName).style.display = 'block';
                // console.log('Selected option:', templateName);
            });
        });
    });

    document.getElementById('save-button').addEventListener('click', function(event) {
    event.preventDefault();

    const form = document.getElementById('templates-form');
    const formData = new FormData(form);

    // Append hidden fields manually
    document.querySelectorAll('div.template-content input, div.template-content select, div.template-content textarea').forEach(input => {
        formData.append(input.name, input.value);
    });

    fetch(form.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json().catch(() => {
            return response.text();
        });
    })
    .then(data => {
        if (typeof data === 'string') {
            document.body.innerHTML = data;
        } else if (data.redirect_url) {
            window.location.href = data.redirect_url;
        } else if (data.error) {
            console.error('Error:', data.error);
            alert('Error: ' + data.error);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred. Please try again.');
    });
});


document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.delete-row-btn').forEach(btn => {
                btn.addEventListener('click', function(event) {
                    event.preventDefault();
                    event.stopPropagation();

                    const row = this.closest('tr');
                    const url = this.getAttribute('data-url');

                    if (confirm('Are you sure you want to delete this row?')) {
                        fetch(url, {
                            method: 'DELETE',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            }
                        })
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Network response was not ok');
                            }
                            return response.json().catch(() => {
                                return response.text();
                            });
                        })
                        .then(data => {
                            console.log(data);
                            if (data.success) {
                                row.remove();
                            } else {
                                alert('Failed to delete the row.');
                            }
                        })
                    }
                });
            });
        });


</script>

<script>
    $("body").on("click", ".babag", function() {

        let key = $("#baba_id").val();
        $("#baba_id").val(key + 1);
    })
    $(".babagd").on("click", function() {
        let key = $("#baba_id").val();
        $("#baba_id").val(key - 1);

    })
    $("#cccc").on("click", function() {

        $("#cccc").hide();
        $("#addressDefault").show();
    })
    $("#addressDefault").on("change", function() {
        let id = $(this).val();
        $.ajax({
            url: "<?php echo e(route('defaoutdataget')); ?>",
            type: "get",
            data: {
                id
            },
            dataType: "json",
            success: function(res) {
                $("#p_phone_d").text(res.p_contact)
                $("#p_ce").val(res.p_contact)
                $("#p_ee").val(res.p_email)
                $("#p_ne").val(res.p_i)
                $("#p_ae").val(res.street)
                $("#p_city").val(res.city)
                $("#p_country").val(res.country)
                $("#p_zipcode").val(res.zipcode)
                $("#p_email_d").text(res.p_email)
                $("#p_address_d").text(res.street)
                $("#p_address_city").text(res.city)
                $("#p_address_country").text(res.country)
                $("#p_address_postalcode").text(res.zipcode)
            }
        })
    })
</script>
<script>
    $("body").on("change", '.product_bame_select', function() {

        var selectedOption = $(this).find('option:selected');
        var productid = selectedOption.data('id');

        const $tr = $(this).closest('tr');

        $.ajax({
            url: "<?php echo e(route('getproductbyid')); ?>",
            data: {
                id: productid
            },
            type: "get",
            dataType: "json",
            success: function(res) {

                $tr.find(".product_description").val(res.description)

            }
        })
    })
    // $("body").on("input", ".p_qty", function() {
    //     let $t = $(this).closest('tr');


    //     $t.find(".p_total").val($t.find(".p_qty").val() * $t.find(".p_rate").val())
    // })
    // $("body").on("input", ".p_rate", function() {
    //     let $t = $(this).closest('tr');

    //     $t.find(".p_total").val($t.find(".p_qty").val() * $t.find(".p_rate").val())
    // })
</script>
<?php /**PATH /www/wwwroot/orion.braincave.work/application/resources/views/pages/bill/components/elements/main-table.blade.php ENDPATH**/ ?>