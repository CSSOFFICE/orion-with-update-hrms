<?php $theme = $this->Xin_model->read_theme_info(1);?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}" id="meta-csrf" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title><?php echo $title;?></title>
    <?php $base_url_hr = str_replace('hrms/', '', base_url()); ?>
    <!--BASEURL-->
    <base href="<?php echo $base_url_hr?>" target="_self">

    <!--JQUERY & OTHER HEADER JS-->
    <script src="public/vendor/js/vendor.header.js?v=1"></script>

    <!--BOOTSTRAP-->
    <link href="public/vendor/css/bootstrap/bootstrap.min.css" rel="stylesheet">


    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="public/vendor/js/html5shiv/html5shiv.js"></script>
    <script src="public/vendor/js/respond/respond.min.js"></script>
    <![endif]-->

    <!--GOOGLE FONTS-->
    <link href="https://fonts.googleapis.com/css?family=Montserrat:300,400,500,600,700" rel="stylesheet"
        type="text/css">


    <!--VENDORS CSS-->
    <link rel="stylesheet" href="public/vendor/css/vendor.css?v=1">

    <!--THEME STYLE-->
    <link href="public/themes/default/css/style.css?v=1" rel="stylesheet">

    <!--USERS CUSTON CSS FILE-->
    <link href="public/css/custom.css?v=1" rel="stylesheet">

    <!--HRMS STYLES-->

    <!-- Bootstrap 3.3.7 -->
    <!--<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/dataTables.jqueryui.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.5.6/css/buttons.jqueryui.min.css">-->
    <!-- Font Awesome -->
    <link rel="stylesheet"
        href="<?php echo base_url();?>skin/hrsale_assets/theme_assets/bower_components/font-awesome/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet"
        href="<?php echo base_url();?>skin/hrsale_assets/theme_assets/bower_components/Ionicons/css/ionicons.min.css">

    <!-- AdminLTE Skins. Choose a skin from the css/skins
    folder instead of downloading all of them to reduce the load. -->
    <?php if($theme[0]->theme_option == 'template_1'):?>
    <link rel="stylesheet"
        href="<?php echo base_url();?>skin/hrsale_assets/theme_assets/dist/css/skins/_all-skins.min.css">
    <link rel="stylesheet" href="<?php echo base_url();?>skin/hrsale_assets/theme_assets/dist/css/AdminLTE.min.css">
    <?php elseif($theme[0]->theme_option == 'template_2'):?>
    <link rel="stylesheet"
        href="<?php echo base_url();?>skin/hrsale_assets/theme_assets/dist/css/skins/_all-skins-template2.min.css">
    <link rel="stylesheet"
        href="<?php echo base_url();?>skin/hrsale_assets/theme_assets/dist/css/AdminLTE_Template2.min.css">
    <?php elseif($theme[0]->theme_option == 'template_3'):?>
    <link rel="stylesheet"
        href="<?php echo base_url();?>skin/hrsale_assets/theme_assets/dist/css/theme_3/_all-skins.css">
    <link rel="stylesheet"
        href="<?php echo base_url();?>skin/hrsale_assets/theme_assets/dist/css/theme_3/AdminLTE_3.css">
    <?php elseif($theme[0]->theme_option == 'template_4'):?>
    <link rel="stylesheet"
        href="<?php echo base_url();?>skin/hrsale_assets/theme_assets/dist/css/theme_4/_all-skins.css">
    <link rel="stylesheet"
        href="<?php echo base_url();?>skin/hrsale_assets/theme_assets/dist/css/theme_4/AdminLTE_4.css">
    <?php elseif($theme[0]->theme_option == 'template_5'):?>
    <link rel="stylesheet"
        href="<?php echo base_url();?>skin/hrsale_assets/theme_assets/dist/css/theme_5/_all-skins.css">
    <link rel="stylesheet"
        href="<?php echo base_url();?>skin/hrsale_assets/theme_assets/dist/css/theme_5/AdminLTE_5.css">
    <?php elseif($theme[0]->theme_option == 'template_6'):?>
    <link rel="stylesheet"
        href="<?php echo base_url();?>skin/hrsale_assets/theme_assets/dist/css/theme_6/_all-skins.css">
    <link rel="stylesheet"
        href="<?php echo base_url();?>skin/hrsale_assets/theme_assets/dist/css/theme_6/AdminLTE_6.css">
    <?php elseif($theme[0]->theme_option == 'template_7'):?>
    <link rel="stylesheet"
        href="<?php echo base_url();?>skin/hrsale_assets/theme_assets/dist/css/theme_7/_all-skins.css">
    <link rel="stylesheet"
        href="<?php echo base_url();?>skin/hrsale_assets/theme_assets/dist/css/theme_7/AdminLTE_7.css">
    <?php elseif($theme[0]->theme_option == 'template_8'):?>
    <link rel="stylesheet"
        href="<?php echo base_url();?>skin/hrsale_assets/theme_assets/dist/css/theme_8/_all-skins.css">
    <link rel="stylesheet"
        href="<?php echo base_url();?>skin/hrsale_assets/theme_assets/dist/css/theme_8/AdminLTE_8.css">
    <?php elseif($theme[0]->theme_option == 'template_9'):?>
    <link rel="stylesheet"
        href="<?php echo base_url();?>skin/hrsale_assets/theme_assets/dist/css/theme_9/_all-skins.min.css">
    <link rel="stylesheet"
        href="<?php echo base_url();?>skin/hrsale_assets/theme_assets/dist/css/theme_9/AdminLTE.min.css">
    <?php else:?>
    <link rel="stylesheet"
        href="<?php echo base_url();?>skin/hrsale_assets/theme_assets/dist/css/skins/_all-skins.min.css">
    <link rel="stylesheet" href="<?php echo base_url();?>skin/hrsale_assets/theme_assets/dist/css/AdminLTE.min.css">
    <?php endif;?>

    <!-- Morris chart -->
    <link rel="stylesheet"
        href="<?php echo base_url();?>skin/hrsale_assets/theme_assets/bower_components/morris.js/morris.css">
    <!-- jvectormap -->
    <link rel="stylesheet"
        href="<?php echo base_url();?>skin/hrsale_assets/theme_assets/bower_components/jvectormap/jquery-jvectormap.css">
    <!-- Date Picker -->
    <link rel="stylesheet"
        href="<?php echo base_url();?>skin/hrsale_assets/theme_assets/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
    <!-- Daterange picker -->
    <link rel="stylesheet"
        href="<?php echo base_url();?>skin/hrsale_assets/theme_assets/bower_components/bootstrap-daterangepicker/daterangepicker.css">
    <!-- bootstrap wysihtml5 - text editor -->
    <link rel="stylesheet"
        href="<?php echo base_url();?>skin/hrsale_assets/theme_assets/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">

    <!-- Theme style -->
    <link rel="stylesheet" href="<?php echo base_url();?>skin/hrsale_assets/theme_assets/plugins/iCheck/all.css">
    <!-- <link rel="stylesheet" href="<?php echo base_url();?>skin/hrsale_assets/theme_assets/bower_components/select2/dist/css/select2.min.css"> -->
    <!-- <link rel="stylesheet" href="<?php echo base_url();?>skin/hrsale_assets/vendor/jquery-ui/jquery-ui.css"> -->
    <link rel="stylesheet" href="<?php echo base_url();?>skin/hrsale_assets/vendor/toastr/toastr.min.css">
    <link rel="stylesheet" href="<?php echo base_url();?>skin/hrsale_assets/vendor/kendo/kendo.common.min.css">
    <link rel="stylesheet" href="<?php echo base_url();?>skin/hrsale_assets/vendor/kendo/kendo.default.min.css">
    <link rel="stylesheet" href="<?php echo base_url();?>skin/hrsale_assets/vendor/Trumbowyg/dist/ui/trumbowyg.css">
    <link rel="stylesheet"
        href="<?php echo base_url();?>skin/hrsale_assets/vendor/clockpicker/dist/bootstrap-clockpicker.min.css">
    <link rel="stylesheet" href="<?php echo base_url();?>skin/hrsale_assets/css/hrsale/animate.css">
    <?php if($theme[0]->theme_option == 'template_1' || $theme[0]->theme_option == 'template_9'):?>
    <link rel="stylesheet" href="<?php echo base_url();?>skin/hrsale_assets/css/hrsale/xin_custom.css">
    <link rel="stylesheet" href="<?php echo base_url();?>skin/hrsale_assets/css/hrsale/xin_hrsale.css">
    <?php elseif($theme[0]->theme_option == 'template_2'):?>
    <link rel="stylesheet" href="<?php echo base_url();?>skin/hrsale_assets/css/hrsale_template2/xin_custom.css">
    <link rel="stylesheet" href="<?php echo base_url();?>skin/hrsale_assets/css/hrsale_template2/xin_hrsale.css">

    <?php else:?>
    <?php /*?>
    <link rel="stylesheet" href="<?php echo base_url();?>skin/hrsale_assets/css/hrsale/xin_custom.css">
    <link rel="stylesheet" href="<?php echo base_url();?>skin/hrsale_assets/css/hrsale/xin_hrsale.css"><?php */?>
    <?php endif;?>
    <link rel="stylesheet" href="<?php echo base_url();?>skin/hrsale_assets/css/hrsale/xin_ihrsale.css">
    <?php if($this->router->fetch_class() =='chat'){?>
    <link rel="stylesheet" href="<?php echo base_url();?>skin/hrsale_assets/css/hrsale/xin_hrsale_chat.css">
    <?php } ?>
    <link rel="stylesheet" href="<?php echo base_url();?>skin/hrsale_assets/css/hrsale/switch.css">
    <link rel="stylesheet" href="<?php echo base_url();?>skin/hrsale_assets/css/hrsale/xin_hrsale_custom.css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->

    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" rel="stylesheet">
    <link rel="stylesheet"
        href="<?php echo base_url();?>/skin/hrsale_assets/vendor/libs/bootstrap-tagsinput/bootstrap-tagsinput.css">
    <link rel="stylesheet"
        href="<?php echo base_url();?>skin/hrsale_assets/theme_assets/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css">
    <?php if($this->router->fetch_class() =='roles') { ?>
    <link rel="stylesheet" href="<?php echo base_url();?>skin/hrsale_assets/vendor/kendo/kendo.common.min.css">
    <link rel="stylesheet" href="<?php echo base_url();?>skin/hrsale_assets/vendor/kendo/kendo.default.min.css">
    <?php } ?>
    <?php if($theme[0]->form_design=='modern_form'):?>
    <link rel="stylesheet" href="<?php echo base_url();?>skin/hrsale_assets/css/hrsale/xin_modern_form.css">
    <?php elseif($theme[0]->form_design=='rounded_form'):?>
    <link rel="stylesheet" href="<?php echo base_url();?>skin/hrsale_assets/css/hrsale/xin_rounded_form.css">
    <?php elseif($theme[0]->form_design=='default_square_form'):?>
    <link rel="stylesheet" href="<?php echo base_url();?>skin/hrsale_assets/css/hrsale/xin_default_square_form.css">
    <?php elseif($theme[0]->form_design=='medium_square_form'):?>
    <link rel="stylesheet" href="<?php echo base_url();?>skin/hrsale_assets/css/hrsale/xin_medium_square_form.css">
    <?php endif;?>
    <?php if($this->router->fetch_class() =='goal_tracking' || $this->router->fetch_method() =='task_details' || $this->router->fetch_class() =='project' || $this->router->fetch_class() =='quoted_projects' || $this->router->fetch_method() =='project_details'){?>
    <link rel="stylesheet"
        href="<?php echo base_url();?>skin/hrsale_assets/vendor/ion.rangeSlider/css/ion.rangeSlider.css">
    <link rel="stylesheet"
        href="<?php echo base_url();?>skin/hrsale_assets/vendor/ion.rangeSlider/css/ion.rangeSlider.skinFlat.css">
    <?php } ?>
    <?php if($this->router->fetch_class() =='calendar' || $this->router->fetch_class() =='dashboard' || $this->router->fetch_method() =='timecalendar' || $this->router->fetch_method() =='projects_calendar' || $this->router->fetch_method() =='tasks_calendar' || $this->router->fetch_method() =='quote_calendar' || $this->router->fetch_method() =='invoice_calendar' || $this->router->fetch_method() =='projects_dashboard' || $this->router->fetch_method() =='accounting_dashboard'){?>
    <link rel="stylesheet"
        href="<?php echo base_url();?>skin/hrsale_assets/theme_assets/bower_components/fullcalendar/dist/fullcalendar.min.css">
    <link rel="stylesheet"
        href="<?php echo base_url();?>skin/hrsale_assets/theme_assets/bower_components/fullcalendar/dist/fullcalendar.print.min.css"
        media="print">
    <?php } ?>
    <?php if($this->router->fetch_method() =='tasks_scrum_board' || $this->router->fetch_method() =='projects_scrum_board') { ?>
    <link rel="stylesheet" href="<?php echo base_url();?>skin/hrsale_assets/css/hrsale/xin_tasks_scrum_board.css">
    <?php } ?>

    <!--END HRMS STYLES-->

    <!--CUSTOM PMS HRMS STYLES-->
    <link rel="stylesheet" href="<?php echo base_url();?>skin/hrsale_assets/css/custom/hrcustom.css">
    <!--END CUSTOM PMS HRMS STYLES-->

    <!-- Favicon icon -->
    <link rel="apple-touch-icon" sizes="57x57" href="public/images/favicon/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="public/images/favicon/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="public/images/favicon/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="public/images/favicon/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="public/images/favicon/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="public/images/favicon/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="public/images/favicon/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="public/images/favicon/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="public/images/favicon/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192" href="public/images/favicon/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="public/images/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="public/images/favicon/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="public/images/favicon/favicon-16x16.png">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="public/images/favicon/ms-icon-144x144.png">
    <meta name="theme-color" content="#ffffff">

    <style>
    .hrsale-asterisk::after {
        content: "*";
        color: red;
    }
        body {
        font-weight: bold !important;
    }
    </style>

    <!--SET DYNAMIC VARIABLE IN JAVASCRIPT-->
    <script type="text/javascript">
    //name space & settings
    NX = (typeof NX == 'undefined') ? {} : NX;
    NXJS = (typeof NXJS == 'undefined') ? {} : NXJS;
    NXLANG = (typeof NXLANG == 'undefined') ? {} : NXLANG;
    NXINVOICE = (typeof NXINVOICE == 'undefined') ? {} : NXINVOICE;
    NX.data = (typeof NX.data == 'undefined') ? {} : NX.data;

    NXINVOICE.DATA = {};
    NXINVOICE.DOM = {};
    NXINVOICE.CALC = {};

    //variables
    NX.site_url = "<?php echo $base_url_hr ?>";
    NX.csrf_token = "vM4yDn9TcLVcUyz2mQ1YwsflfP2vJ6kDbpdMHUgN";
    NX.system_language = "english";
    NX.date_format = "m-d-Y";
    NX.date_picker_format = "mm-dd-yyyy";
    NX.date_moment_format = "MM-DD-YYYY";
    NX.upload_maximum_file_size = "5000";
    NX.settings_system_currency_symbol = "$";
    NX.settings_system_decimal_separator =
        ".";
    NX.settings_system_thousand_separator =
        ",";
    NX.settings_system_currency_position = "left";
    NX.show_action_button_tooltips = "1";
    NX.notification_position = "bottomLeft";
    NX.notification_error_duration = "5000";
    NX.notification_success_duration = "3000";

    //javascript console debug modes
    NX.debug_javascript = "1";

    //popover template
    NX.basic_popover_template = '<div class="popover card-popover" role="tooltip">' +
        '<span class="popover-close" onclick="$(this).closest(\'div.popover\').popover(\'hide\');" aria-hidden="true">' +
        '<i class="ti-close"></i></span>' +
        '<div class="popover-header"></div><div class="popover-body" id="popover-body"></div></div>';


    //lang - used in .js files
    NXLANG.delete_confirmation = "Delete Confirmation";
    NXLANG.are_you_sure_delete = "Are you sure you?";
    NXLANG.cancel = "Cancel";
    NXLANG.continue = "Continue";
    NXLANG.file_too_big = "File is too big";
    NXLANG.maximum = "Maximum";
    NXLANG.generic_error = "An error was encountered processing your request";
    NXLANG.drag_drop_not_supported = "Your browser does not support drag and drop";
    NXLANG.use_the_button_to_upload = "Use the button to upload";
    NXLANG.file_type_not_allowed = "File type is not allowed";
    NXLANG.cancel_upload = "Cancel upload";
    NXLANG.remove_file = "Remove file";
    NXLANG.maximum_upload_files_reached = "Maximum allowed files has been reached";
    NXLANG.upload_maximum_file_size = "lang.upload_maximum_file_size";
    NXLANG.upload_canceled = "Upload cancelled";
    NXLANG.are_you_sure = "Are you sure?";
    NXLANG.image_dimensions_not_allowed = "Images dimensions are not allowed";
    NXLANG.ok = "Ok";
    NXLANG.cancel = "Cancel";
    NXLANG.close = "Close";
    NXLANG.system_default_category_cannot_be_deleted =
        "This is a system default category and cannot be deleted";
    NXLANG.default_category = "Default Category";
    NXLANG.select_atleast_one_item = "You must select at least one item";
    NXLANG.invalid_discount = "The discount is not valid";
    NXLANG.add_lineitem_items_first = "Add line item items first";
    NXLANG.fixed = "Fixed";
    NXLANG.percentage = "Percentage";
    NXLANG.action_not_completed_errors_found = "Action could not be completed";
    NXLANG.selected_expense_is_already_on_invoice =
        "One of the selected expenses is already on the invoice";
    NXLANG.please_wait = "Please wait";
    NXLANG.invoice_time_unit = "Time";
    </script>

    <!--boot js-->
    <script src="public/js/core/head.js?v=1"></script>

    <!--[note: no sanitizing required] for this trusted content, which is added by the admin-->

</head>