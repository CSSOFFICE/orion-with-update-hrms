<!--bulk actions-->


<!--main table view-->
<?php echo $__env->make('pages.timecard.components.table.table', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<!--filter-->
<?php if(auth()->user()->is_team): ?>
    <?php echo $__env->make('pages.timecard.components.misc.filter-timesheets', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php endif; ?>
<!--timesheets-->

</head>
<script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.5/FileSaver.min.js"></script>

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.5/xlsx.full.min.js"></script>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-timepicker/1.10.0/jquery.timepicker.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/exceljs/4.4.0/exceljs.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-timepicker/1.10.0/jquery.timepicker.min.js"></script>
<script>
    $("body").on("dblclick", ".edit_ppe", function(e) {
        e.preventDefault();

        var $row = $(this).closest('tr');
        $row.find('.form-control').removeAttr('disabled');

        $row.find(".edit_ppe").find('i').addClass("fa-save");
        $row.find(".edit_ppe").addClass("save_ppe");
        $row.find(".edit_ppe").removeClass("edit_ppe");
        $('.save_ppe').timepicker({
            timeFormat: 'H.i',
            interval: 15,
            dropdown: true,
            //   scrollbar: true
        });
    });

    $("body").on("dblclick", ".save_ppe", function(e) {
        e.preventDefault()
        var _token = $('meta[name="csrf-token"]').attr('content');
        let id = $(this).data('field_id');
        var $row = $(this).closest('tr');
        var data = $row.find('input, select').serialize();


        $.ajax({
            url: "<?php echo e(route('timecard.store')); ?>",
            type: "post",
            data: data + "&user_id=" + id + "&_token=" + _token,
            success: function(JSON) {

                if (JSON) {
                    window.location.reload(1);
                }

            }
        })

    })
    $("body").on("click", ".delete", function(e) {
        e.preventDefault()
        let dd = confirm("Are You Sure");

        var _token = $('meta[name="csrf-token"]').attr('content');
        let id = $(this).data('record-id');

        if (dd) {


            $.ajax({
                url: "<?php echo e(route('timecard.delete_att')); ?>",
                type: "post",
                data: {
                    _token,
                    id
                },
                success: function(JSON) {

                    if (JSON) {
                        window.location.reload(1);
                    }

                }
            })
        }

    })
    $('#uploadForm').on("submit", function(e) {
        e.preventDefault()
        var _token = $('meta[name="csrf-token"]').attr('content');
        var formData = new FormData(this);


        $.ajax({
            url: "<?php echo e(route('timecard.bulk')); ?>",
            type: 'post',
            data: formData,
            contentType: false,
            processData: false,
            success: function(data) {
                if (data) {
                    window.location.reload(1);
                }
            },
            error: function(error) {
                console.error('Error uploading data:', error);
            }
        });
    });
</script>

<script>
    function downloadDummyArray() {
        var dummyArray = [
            ['sr ','user_id','employee',1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31],
            [1,'1','ranveer','04.09','09.10'],
            [2,'245','Los Angeles','04.09','09.10'],
            [3,'244','Chicago','04.09','09.10']

        ];

        var workbook = new ExcelJS.Workbook();
        var worksheet = workbook.addWorksheet('Sheet 1');
        worksheet.addRows(dummyArray);

        workbook.xlsx.writeBuffer().then(function(data) {
            var blob = new Blob([data], {
                type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
            });
            saveAs(blob, 'dummy-array.xlsx');
        });
    }
</script>
<!-- Button to Open the Modal -->

<!-- The Modal -->
<div class="modal" id="myModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="uploadForm" enctype="multipart/form-data">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Please Upload Bulk</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <!-- Modal body -->
                <div class="modal-body">
                    <?php echo csrf_field(); ?>
                    <input type="file" class="form-control" name="excelFile" accept=".xls, .xlsx">


                </div>

                <!-- Modal footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-danger" id="uploadBtn">Upload</button>
                </div>
            </form>

        </div>
    </div>
</div>
<?php /**PATH /www/wwwroot/orion.braincave.work/application/resources/views/pages/timecard/components/table/wrapper.blade.php ENDPATH**/ ?>