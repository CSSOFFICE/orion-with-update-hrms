<style>

        [data-tab-info] {
            display: none;
        }
 
        .active[data-tab-info] {
            display: block;
        }
 
        .tab-content {
            margin-top: 1rem;
            padding-left: 1rem;
            font-size: 14px;
            font-family: sans-serif;
            font-weight: bold;
            color: rgb(0, 0, 0);
        }
 
        .tabs {
            /* border-bottom: 1px solid grey; */
            background-color:  solid #d2d6de !important;
            font-size: 14px;
            color: rgb(0, 0, 0);
            display: flex;
            margin: 0;
        }
 
        .tabs span {
            background: #f8f9fa;
            padding: 10px;
            border: 1px solid rgb(255, 255, 255);
        }
 
        .tabs span:hover {
            background: rgb(55, 219, 46);
            cursor: pointer;
            color: black;
        }
    </style>
<style>
    * {
	 box-sizing: border-box;
}
 ul {
	 list-style-type: none;
	 margin: 0;
	 padding: 0;
}
 .drag-container {
	 max-width: 1600px;
	 /* margin: 20px auto; */
}
.drag-container p{
    margin:0;
}
 .drag-list {
	 display: flex;
	 align-items: flex-start;
}
 @media (max-width: 690px) {
	 .drag-list {
		 display: block;
	}
}
 .drag-column {
	 flex: 1;
	 margin: 0 10px;
	 position: relative;
	 background: rgba(0, 0, 0, 0.2);
	 overflow: hidden;
     border-radius: 7px;
}
 @media (max-width: 690px) {
	 .drag-column {
		 margin-bottom: 30px;
	}
}
 .drag-column h2 {
	 font-size: 0.8rem;
	 margin: 0;
	 text-transform: uppercase;
	 font-weight: 600;
     color: #fff;
}
 .drag-column-on-hold .drag-column-header, .drag-column-on-hold .is-moved, .drag-column-on-hold .drag-options {
	 background: #fb7d44;
}
 .drag-column-in-progress .drag-column-header, .drag-column-in-progress .is-moved, .drag-column-in-progress .drag-options {
	 background: #9d249d;
     color: #fff;
}
 .drag-column-needs-review .drag-column-header, .drag-column-needs-review .is-moved, .drag-column-needs-review .drag-options {
	 background: #f4ce46;
}
 .drag-column-approved .drag-column-header, .drag-column-approved .is-moved, .drag-column-approved .drag-options {
	 background: #00b961;
}
.drag-column-approve .drag-column-header, .drag-column-approve .is-moved, .drag-column-approve .drag-options {
	 background: #00b961;
}


.drag-column-expire span.drag-column-header {
    background: #f10000;
    color: #fff;
}
.drag-column-decline span.drag-column-header {
    background: #0e65d1;
    color: #fff;
}

 .drag-column-header {
	 display: flex;
	 align-items: center;
	 justify-content: space-between;
	 padding: 10px;
}
 .drag-inner-list {
	 min-height: 50px;
}
 .drag-item {
	 margin: 10px;
	 height: 100px;
	 background: #fff;
	 transition: all 0.3s cubic-bezier(0.23, 1, 0.32, 1);
     padding: 5px;
}
 .drag-item.is-moving {
	 transform: scale(1.5);
	 background: rgba(0, 0, 0, 0.8);
}
 .drag-header-more {
	 cursor: pointer;
}
 .drag-options {
	 position: absolute;
	 top: 44px;
	 left: 0;
	 width: 100%;
	 height: 100%;
	 padding: 10px;
	 transform: translateX(100%);
	 opacity: 0;
	 transition: all 0.3s cubic-bezier(0.23, 1, 0.32, 1);
}
 .drag-options.active {
	 transform: translateX(0);
	 opacity: 1;
}
 .drag-options-label {
	 display: block;
	 margin: 0 0 5px 0;
}
 .drag-options-label input {
	 opacity: 0.6;
}
 .drag-options-label span {
	 display: inline-block;
	 font-size: 0.9rem;
	 font-weight: 400;
	 margin-left: 5px;
}
/* Dragula CSS */
 .gu-mirror {
	 position: fixed !important;
	 margin: 0 !important;
	 z-index: 9999 !important;
	 opacity: 0.8;
	 list-style-type: none;
}
 .gu-hide {
	 display: none !important;
}
 .gu-unselectable {
	 -webkit-user-select: none !important;
	 -moz-user-select: none !important;
	 -ms-user-select: none !important;
	 user-select: none !important;
}
 .gu-transit {
	 opacity: 0.2;
}
/* Demo info */
 
</style>

<style>
    #add_form{
        height:100%!important;
    }
</style>
<?php $session = $this->session->userdata('username');?>
<?php $get_animate = $this->Xin_model->get_content_animate();?>
<?php $role_resources_ids = $this->Xin_model->user_role_resource(); ?>
<?php if(in_array('3002',$role_resources_ids)) {?>

<div class="box mb-4 <?php echo $get_animate;?>">
    <div id="accordion">
        <div class="box-header with-border">
            <h3 class="box-title"><?php echo $this->lang->line('xin_add_new');?>
                <?php echo $this->lang->line('xin_quotation');?></h3>
            <div class="box-tools pull-right"> <a class="text-dark collapsed" data-toggle="collapse" href="#add_form"
                    aria-expanded="false">
                    <button type="button" class="btn btn-xs btn-primary"> <span class="ion ion-md-add"></span>
                        <?php echo $this->lang->line('xin_add_new');?></button>
                </a> </div>
        </div>
        <div id="add_form" class="collapse add-form <?php echo $get_animate;?>" data-parent="#accordion" style="height: auto!important;">
            <div class="box-body">
                <?php $attributes = array('name' => 'add_quotation', 'id' => 'xin-form', 'autocomplete' => 'off');?>
                <?php $hidden = array('user_id' => $session['user_id']);?>
                <?php echo form_open('admin/finance/add_quotation', $attributes, $hidden);?>
                <div class="form-body">
                    <div class="row">
                        <div class="col-md-12">                                   
                                <div class="row">
                                <div class="col-md-6">
                                        <label for="quotation_amount">Quotation Subject Title <i class="hrsale-asterisk">*</i></label>
                                        <input type="text" name="q_title" id="q_title" class="form-control"
                                            placeholder="Quotation Subject Title">
                                    </div>
                                    <!-- <div class="col-md-6">
                                        <label for="quotation_amount">Project Name<i class="hrsale-asterisk">*</i></label>
                                        <input type="text" name="proj_name" id="proj_name" class="form-control"
                                            placeholder="Project Name">
                                    </div> -->
                                    <div class="col-md-6">
                                        <label>Customer Type<i class="hrsale-asterisk">*</i></label>
                                        <select id="quotation_for" name="quotation_for" class="form-control" onchange="getCustomers()">
                                            <option value="" selected="selected">Select Quotation For</option>
                                            <option value="0">Company Customer</option>
                                            <option value="1">Individual Customer</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="bill_clientid"><?php echo $this->lang->line('xin_customer');?>
                                            <i class="hrsale-asterisk">*</i>
                                        </label>
                                        <select id="customerName" name="bill_clientid" class="form-control">
                                            <option value="" disabled>Select Customer Name</option>
                                        </select>
                                        <!-- <div  id="supplier_address"></div> -->
                                    </div>
                                </div>
                               
                            <!-- <div class="form-group">
                                <label for="project_id"><?php //echo $this->lang->line('xin_project');?>
                                    <i class="hrsale-asterisk">*</i>
                                </label>
                               <input type="text" name="project_id" class="form-control" placeholder="Project Name">
                            </div> -->
                           
                            <div class="form-group">
                                <div class="row">                                                                        
                                    <div class="col-md-6">
                                        <label for="acceptance_letter_no">Project Site Address <i class="hrsale-asterisk">*</i></label>
                                        <textarea class="form-control" placeholder="Project Site"
                                            name="project_s_add" ></textarea>
                                    </div>                                                           

                                        <div class="col-md-6">                                   
                                        <label for="q_validity">Quotation Validity <i class="hrsale-asterisk">*</i></label>
                                        <input class="form-control date" placeholder="Select Required date" name="q_validity" id="q_validity" type="text">
                                    </div>
                                   
                                    <div class="col-md-6">                                
                                        <div class="col-md-12">
                                        <label for="pic_name">Person in charge <i class="hrsale-asterisk">*</i></label>
                                        <input type="text" name="pic_name" id="pic_name" class="form-control" placeholder="Person in charge">
                                        </div>
                                        <div class="col-md-12">                                    
                                        <label for="pic_email">PIC Email</label>
                                        <input type="email" name="pic_email" id="pic_email" class="form-control" placeholder="PIC Email">
                                        </div>
                                        <div class="col-md-12">                                    
                                        <label for="pic_phone">PIC Phone</label>
                                        <input type="number" name="pic_phone" id="pic_phone" class="form-control number" placeholder="PIC Phone">
                                        </div>
                               
                                </div>
                                    <div class="col-md-6">                                                            
                                      <label for="remark">Remarks</label>
                                      <textarea class="form-control" name="remark" id="remark"></textarea>   
                                    </div> 
                            
                                </div>
                                <div class="form-group row">
                                        <label class="col-12 text-left control-label col-form-label">Terms</label>
                                        <div class="col-12">
                                            <?php $query=$this->db->select('settings_estimates_default_terms_conditions')->where('settings_id',1)->get('settings')->result();?>
                                            <textarea id="bill_terms" name="bill_terms" class="tinymce-textarea">
                                                <?php echo $query[0]->settings_estimates_default_terms_conditions;?>
                                        </textarea>
                                        </div>
                                    </div>
                                
                                
                            </div>

                           
                                        
                                
                    
                        </div>


                    </div>
                </div>
                <div class="form-actions box-footer">
                    <button type="submit" class="btn btn-primary"> <i class="fa fa-check-square-o"></i>
                        <?php echo $this->lang->line('xin_save');?> </button>
                </div>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>
<?php } ?>
<div class="box <?php echo $get_animate;?>">
        <div id="btnContainer" style="text-align: right;">            
            <button class="btn" onclick="listView()" id="bars"><i class="fa fa-bars"></i></button> 
            <button class="btn active" onclick="gridView()" id="grid"><i class="fa fa-th-large"></i></button>
          </div>

            <div class="box-body" id="list">
                 

                    <div class="box-header with-border">
                        <h3 class="box-title"> <?php echo $this->lang->line('xin_list_all');?>
                            <?php echo $this->lang->line('xin_quotation');?> </h3>
                    </div>

                                          
                   
                    <div class="box-body">
                        <div class="box-datatable table-responsive">
                        <table class="datatables-demo table table-striped table-bordered" id="xin_table" style="width:100%!important;">
                            <thead>
                                <tr>
                                    <th><?php echo "Sl No";?></th>                                             
                                    <th><?php echo $this->lang->line('xin_action');?></th>
                                    <th><?php echo "Quotation No";?></th>
                                    <th><?php echo "Project Title";?></th>
                                    <th><?php echo "Client Name";?></th>                                                            
                                    <th><?php echo "Quotation Create Date";?></th>                                                            
                                    <th><?php echo "Status";?></th>                                                        
                                </tr>
                            </thead>
                        </table>
                        </div>
                    </div>

              
                </div>
                <br>


                            <div class="card d-none" id="kanban">
                                <div class="drag-container">
                                    <ul class="drag-list">
                                        <li class="drag-column drag-column-on-hold">
                                            <span class="drag-column-header">
                                                <h2>Draft</h2>
                                                <!-- <svg class="drag-header-more" data-target="options1" fill="#FFFFFF" height="24" viewBox="0 0 24 24" width="24"><path d="M0 0h24v24H0z" fill="none"/><path d="M12 8c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm0 2c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm0 6c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z"/></svg> -->
                                            </span>
                                                
                                            <div class="drag-options" id="options1"></div>
                                            
                                            <ul class="drag-inner-list" id="1">
                                            <?php foreach($get_all_draft as $draft){?>

                                                <li class="drag-item px-3 py-3">
                                                    <p><a href="<?php echo site_url().'admin/finance/read_quotation_view/'.$draft->quotation_id ?>"><?php echo $draft->quotation_no;?></a>
                                                    </p>
                                                    <p>
                                                        <a href="<?php echo site_url().'admin/finance/read_quotation_view/'.$draft->quotation_id ?>"><?php echo $draft->project_name;?></a>
                                                    </p>                                            
                                                </li>
                                                
                                                <?php }?>
                                            </ul>
                                        </li>
                                        <li class="drag-column drag-column-in-progress">
                                            <span class="drag-column-header">
                                                <h2>Approved by Management</h2>
                                                <!-- <svg class="drag-header-more" data-target="options2" fill="#FFFFFF" height="24" viewBox="0 0 24 24" width="24"><path d="M0 0h24v24H0z" fill="none"/><path d="M12 8c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm0 2c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm0 6c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z"/></svg> -->
                                            </span>
                                            <div class="drag-options" id="options2"></div>
                                            <ul class="drag-inner-list" id="2">
                                                <?php foreach($get_all_manage as $manage){?>
                                                    
                                                    <li class="drag-item px-3 py-3">                                            
                                                    <p><a href="<?php echo site_url().'admin/finance/read_quotation_view/'.$manage->quotation_id ?>"><?php echo $manage->quotation_no;?></a></p>
                                                    <p><a href="<?php echo site_url().'admin/finance/read_quotation_view/'.$manage->quotation_id ?>"><?php echo $manage->project_name;?></a></p>                                                   
                                                    </li>

                                                <?php }?>
                                                
                                            </ul>
                                        </li>
                                        <li class="drag-column drag-column-needs-review">
                                            <span class="drag-column-header">
                                                <h2>Pending Customer’s Approval</h2>
                                                <!-- <svg data-target="options3" class="drag-header-more" fill="#FFFFFF" height="24" viewBox="0 0 24 24" width="24"><path d="M0 0h24v24H0z" fill="none"/><path d="M12 8c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm0 2c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm0 6c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z"/></svg> -->
                                            </span>
                                            <div class="drag-options" id="options3"></div>
                                            <ul class="drag-inner-list" id="3">
                                                <?php foreach($get_all_cust_approve as $cust){?>
                                                    <li class="drag-item px-3 py-3">
                                                    <p><a href="<?php echo site_url().'admin/finance/read_quotation_view/'.$cust->quotation_id ?>"><?php echo $cust->quotation_no;?></a></p>
                                                    <p><a href="<?php echo site_url().'admin/finance/read_quotation_view/'.$cust->quotation_id ?>"><?php echo $cust->project_name;?></a></p> 
                                                    </li>
                                                <?php }?>
                                                
                                            </ul>
                                        </li>
                                        <li class="drag-column drag-column-approve">
                                            <span class="drag-column-header">
                                                <h2>Approved</h2>
                                                <!-- <svg data-target="options3" class="drag-header-more" fill="#FFFFFF" height="24" viewBox="0 0 24 24" width="24"><path d="M0 0h24v24H0z" fill="none"/><path d="M12 8c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm0 2c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm0 6c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z"/></svg> -->
                                            </span>
                                            <div class="drag-options" id="options3"></div>
                                            <ul class="drag-inner-list" id="6">
                                                <?php foreach($get_all_appove as $app){?>
                                                <li class="drag-item px-3 py-3">
                                                    <p><a href="<?php echo site_url().'admin/finance/read_quotation_view/'.$app->quotation_id ?>"><?php echo $app->quotation_no;?></a></p>
                                                    <p><a href="<?php echo site_url().'admin/finance/read_quotation_view/'.$app->quotation_id ?>"><?php echo $app->project_name;?></a></p>
                                                </li>
                                                <?php }?>
                                            </ul>
                                        </li>
                                        <li class="drag-column drag-column-decline">
                                            <span class="drag-column-header">
                                                <h2>Declined</h2>
                                                <!-- <svg data-target="options4" class="drag-header-more" fill="#FFFFFF" height="24" viewBox="0 0 24 24" width="24"><path d="M0 0h24v24H0z" fill="none"/><path d="M12 8c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm0 2c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm0 6c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z"/></svg> -->
                                            </span>
                                            <div class="drag-options" id="options4"></div>
                                            <ul class="drag-inner-list" id="4">
                                                <?php foreach($get_all_rejected as $rej){?>
                                                    <li class="drag-item px-3 py-3">
                                                        <p><a href="<?php echo site_url().'admin/finance/read_quotation_view/'.$rej->quotation_id ?>"><?php echo $rej->quotation_no;?></a></p>
                                                        <p><a href="<?php echo site_url().'admin/finance/read_quotation_view/'.$rej->quotation_id ?>"><?php echo $rej->project_name;?></a></p>
                                                    </li>
                                                <?php }?>
                                            </ul>
                                        </li>
                                            <li class="drag-column drag-column-expire">
                                            <span class="drag-column-header">
                                                <h2 class="text-white">Expired</h2>
                                                <!-- <svg data-target="options3" class="drag-header-more" fill="#FFFFFF" height="24" viewBox="0 0 24 24" width="24"><path d="M0 0h24v24H0z" fill="none"/><path d="M12 8c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm0 2c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm0 6c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z"/></svg> -->
                                            </span>
                                            <div class="drag-options" id="options6"></div>
                                            <ul class="drag-inner-list" id="5">
                                                <?php foreach($get_all_expire as $expire){?>
                                                <li class="drag-item px-3 py-3">
                                                    <p><a href="<?php echo site_url().'admin/finance/read_quotation_view/'.$expire->quotation_id ?>"><?php echo $expire->quotation_no;?></a></p>
                                                    <p><a href="<?php echo site_url().'admin/finance/read_quotation_view/'.$expire->quotation_id ?>"><?php echo $expire->project_name;?></a></p> 
                                                </li>
                                                <?php }?>                                        
                                            </ul>
                                        </li>
                                        
                                    </ul>
                                </div>
                            </div>            </div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/45226/dragula.min.js"></script>
<script>
    dragula([
	document.getElementById('1'),
	document.getElementById('2'),
	document.getElementById('3'),
	document.getElementById('4'),
	document.getElementById('5'),
    document.getElementById('6'),
	document.getElementById('7')
])

.on('drag', function(el) {
	
	// add 'is-moving' class to element being dragged
	el.classList.add('is-moving');
})
.on('dragend', function(el) {
	
	// remove 'is-moving' class from element after dragging has stopped
	el.classList.remove('is-moving');
	
	// add the 'is-moved' class for 600ms then remove it
	window.setTimeout(function() {
		el.classList.add('is-moved');
		window.setTimeout(function() {
			el.classList.remove('is-moved');
		}, 600);
	}, 100);
});


var createOptions = (function() {
	var dragOptions = document.querySelectorAll('.drag-options');
	
	// these strings are used for the checkbox labels
	// var options = ['Research', 'Strategy', 'Inspiration', 'Execution'];
	
	// create the checkbox and labels here, just to keep the html clean. append the <label> to '.drag-options'
	function create() {
		for (var i = 0; i < dragOptions.length; i++) {

			options.forEach(function(item) {
				var checkbox = document.createElement('input');
				var label = document.createElement('label');
				var span = document.createElement('span');
				checkbox.setAttribute('type', 'checkbox');
				span.innerHTML = item;
				label.appendChild(span);
				label.insertBefore(checkbox, label.firstChild);
				label.classList.add('drag-options-label');
				dragOptions[i].appendChild(label);
			});

		}
	}
	
	return {
		create: create
	}
	
	
}());

var showOptions = (function () {
	
	// the 3 dot icon
	var more = document.querySelectorAll('.drag-header-more');
	
	function show() {
		// show 'drag-options' div when the more icon is clicked
		var target = this.getAttribute('data-target');
		var options = document.getElementById(target);
		options.classList.toggle('active');
	}
	
	
	function init() {
		for (i = 0; i < more.length; i++) {
			more[i].addEventListener('click', show, false);
		}
	}
	
	return {
		init: init
	}
}());

createOptions.create();
showOptions.init();






</script>
<script>

// Get the elements with class="column"
var elements = document.getElementsByClassName("column");

// Declare a loop variable
var i;

// List View
function listView() {
//   for (i = 0; i < elements.length; i++) {
//     elements[i].style.width = "100%";
//   }
var element = document.getElementById("kanban");
        element.classList.remove('d-none');
}

// Grid View
function gridView() {

    var element = document.getElementById("kanban");
        element.classList.remove('d-none');
       

  for (i = 0; i < elements.length; i++) {
    elements[i].style.width = "50%";
  }
}



  $("#grid").click(function(){
    $("#list").hide();
    $("#list1").hide();
    $("#kanban").show();
  });


  $("#bars").click(function(){
    $("#list").show();
    $("#list1").show();
    $("#kanban").hide();
  });

</script>
<script>
$(document).ready(function() {
    var counter=0;
    $("#task_div").hide();
    var input = $('.timepicker_m').clockpicker({
        placement: 'bottom',
        align: 'left',
        autoclose: true,
        'default': 'now'
    });
    $(".task").on('click', function() {
        counter +=1;
        $("#task_div").show();
        $("#task_div").append(`<div style="margin-top:10px;"><div class="row">
                                    <div class="col-md-12">

                                        <div class="form-group">
                                            <div class="row">

                                                <div class="col-md-12">
                                                   <label>Task`+counter+`</label>
                                                    <input type="text" name="task_name[]" class="form-control"
                                                        placeholder="Task Name">
                                                </div>

                                            </div>
                                        </div>


                                    </div>

                                    <div class="col-md-12">

                                        <ul class="nav nav-tabs" role="tablist">
                                            <li class="nav-item">
                                                <a class="nav-link active" data-toggle="tab" href="#tabs-1-`+counter+`"
                                                    role="tab">Task Description</a>
                                            </li>
                                           
                                            <li class="nav-item">
                                                <a class="nav-link" data-toggle="tab" href="#tabs-2-`+counter+`" role="tab">Sub Tasks</a>
                                            </li>
                                        </ul><!-- Tab panes -->
                                        <div class="tab-content"
                                            style="border: 1px solid; border-top: none; border-color: #dee2e6;">
                                            <div class="tab-pane active" id="tabs-1-`+counter+`" role="tabpanel">
                                                <div class="container-fluid">
                                                <textarea name="task_description[]" id="task_description`+counter+`" placeholder="Detail" style="width:250px"></textarea>
                                                </div>

                                            </div>
                                           
                                            <div class="tab-pane" id="tabs-2-`+counter+`" role="tabpanel">
                                                <div class="container-fluid">
                                                    <table class="table">
                                                        <thead>
                                                            <tr>
                                                                <th>Item</th>
                                                                <th>Description</th>
                                                                <th>Detail</th>
                                                                <th>Unit</th>
                                                                <th>Price</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody class="AddItem" id="vendor_items_table`+counter+`"></tbody>
                                                        <tfoot>
                                                            <tr>
                                                                <th style="border: none !important;">
                                                                    <a href="javascript:void(0)"
                                                                        class="btn-sm btn-success addButton1" onclick="sub_tasks('`+counter+`')">Add</a>
                                                                </th>
                                                            </tr>
                                                        </tfoot>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                </div></div>`);
    });
    $("#term_condition_id").on('change',function(){
        var id=$(this).val();
        $.ajax({
            type:'GET',
            url: base_url + "/get_term_details/" + id,
            data: JSON,

            success: function(JSON) {
                 var data= jQuery.parseJSON(JSON);
               $("#terms_condition").val(data[0].term_description)
            },
            error: function() {
                toastr.error("Something went wrong");
            }

        });
    });
   

});
$(document).on('click', '.remove-input-field', function() {
    $(this).parents('tr').remove();

    updateCalculationPQ();
});
function sub_tasks(id){
    var number = $('#vendor_items_table'+id+' tr').length;
        var item = number + 1;
        $('#vendor_items_table'+id).append(`
                    <tr>
                    <td style="min-width:100px">
                            <label>` + item + `<label>
                        </td>
                        <td style="min-width:250px">
                            <textarea name="description[]" id="description`+id+`" placeholder="Description" style="width:250px"></textarea>
                        </td>
                        <td style="min-width:100px">
                        <textarea name="detail[]" id="detail`+id+`" placeholder="Detail" style="width:250px"></textarea>
                        </td>
                        <td style="min-width:100px">
                             <select class="packing_dropdown form-control select22" name="unit_id[]">
                             <option value="">Select Unit</option>
                             <?php foreach($all_units->result() as $unit){?>
                                <option value="<?php echo $unit->unit_id;?>"><?php echo $unit->unit;?></option>
                                <?php } ?>
                               
                            </select>
                        </td>
                        
                        <td style="min-width:100px">
                            <input type="text" name="unit_rate[]" id="unit_rate`+id+`"  placeholder="Unit Rate" class="form-control calculate ">
                        </td>
                       
                        <td>
                            <button type="button" name="clear" id="clear" class="btn btn-danger remove-input-field"><i class="ti-trash"></i></button>
                        </td>
                    </tr>
                `);
    }
function updateCalculationPQ() {

    var total = 0;
    var total_tax = 0;
    var untaxed = 0;
    var total_amount = 0;
    var tax = parseFloat($('#tax').val());

    $('#vendor_items_table1 > tr').each(function() {

        total_amount += parseFloat($(this).find('input[name="amount[]"]').val());

    });

    if ($('#tax_inclusive1').prop('checked') == true) {
        total_tax = (parseFloat(total_amount) * tax) / 100;
        total = total_amount + total_tax;
    } else {
        total = total_amount;
    }

    $('#sub_total').val(total_amount.toFixed(2));

    $('#total_gst1').val(total_tax.toFixed(2));

    $('#total_amount1').val(total.toFixed(2));

}

function calculation(id) {
    var total = 0;
    var total_tax = 0;
    var final_total = 0;
    var total_amount = 0;

    var unit_price = $("#cost_field" + id).val();
    var quantity = $("#quantity" + id).val();

    var total = parseFloat(unit_price) * parseFloat(quantity);
    if (total > 0) {
        $("#amount" + id).val(total);
    } else {
        $("#amount" + id).val('0');
    }





    $('#vendor_items_table1 > tr').each(function() {

        total_amount += parseFloat($(this).find('input[name="amount[]"]').val());

    });

    // if($('#tax_inclusive1').prop('checked') == true){
    //     total_tax = (parseFloat(total_amount)*tax)/100;
    //     total = total_amount+total_tax;
    // }else{
    //     total = total_amount;
    // }
    //var tax = parseFloat($('#total_gst1').val());
    var tax = parseFloat($("#total_gst1 option:selected").text());
    if (tax > 0) {
        var final_total = total_amount + total_amount * (tax / 100);

    } else {
        var final_total = total_amount;
    }
    $('#sub_total').val(total_amount.toFixed(2));

    $('#total_gst1').val(total_tax.toFixed(2));

    $('#total_amount1').val(final_total.toFixed(2));
}

function totalGSTAmount() {
    var total_amount = parseFloat($('#sub_total').val());
    var tax = parseFloat($("#total_gst1 option:selected").text());
    var total = total_amount + total_amount * (tax / 100);
    $('#total_amount1').val(total);
}
jQuery("#customer_id").change(function() {

    jQuery.get(base_url + "/get_supplier_address/" + jQuery(this).val(), function(data, status) {
        jQuery('#supplier_address').html(data);
    });
});

       
       // function to get each tab details
       const tabs = document.querySelectorAll('[data-tab-value]')
       const tabInfos = document.querySelectorAll('[data-tab-info]')

       tabs.forEach(tab => {
           tab.addEventListener('click', () => {
               const target = document
                   .querySelector(tab.dataset.tabValue);
               tabInfos.forEach(tabInfo => {
                   tabInfo.classList.remove('active')
               })
               target.classList.add('active');
           })
       })
       function btn_reject($id){                    
            $.ajax({
                type:"POST",
                url:"<?php echo base_url().'admin/finance/btn_decline/';?>"+$id, 
                success:function(data){
                    toastr.success("Quotation Rejected");
                    setInterval(function () {   
                    window.location.href="<?php echo base_url().'admin/finance/quotation_list/';?>",1000
                    });
                },
                error:function(){
                    toastr.error("Failed to Reject");
                }
            });
         }

    function btn_confirm($id){
            
        
            $.ajax({
                type:"POST",
                url:"<?php echo base_url().'admin/finance/get_details/';?>"+$id, 
                success:function(data){
                    toastr.success("Quotation Approved by Management");
                    setInterval(function () {   
                    window.location.href="<?php echo base_url().'admin/finance/quotation_list/';?>",1000
                    });
                },
                error:function(){
                    toastr.error("Failed to Approve");
                }
            });
         }

        function btn_cust_confirm($id){
            $.ajax({
                type:"POST",
                url:"<?php echo base_url().'admin/finance/cust_confirm/';?>"+$id, 
                success:function(data){
                    toastr.success("Quotation Sent for Customer Approval");
                    setInterval(function () {   
                    window.location.href="<?php echo base_url().'admin/finance/quotation_list/';?>",1000
                    });
                },
                error:function(){
                    toastr.error("Failed to Send");
                }
            });
        }

        function btn_approve($id){
            $.ajax({
                type:"POST",
                url:"<?php echo base_url().'admin/finance/btn_approve/';?>"+$id, 
                success:function(data){
                    toastr.success("Quotation Approved");
                    setInterval(function () {   
                    window.location.href="<?php echo base_url().'admin/finance/quotation_list/';?>",1000
                    });
                },
                error:function(){
                    toastr.error("Failed to Approve");
                }
            });
        }


function confirm(id) {
        //var id = $('#btn_confirm').data('quotation_id');

        $.ajax({
            type: "POST",
            url: base_url + "/get_details/" + id,
            success: function(data) {
                toastr.success("Quotation has been confirmed");
                setInterval(function() {
                    window.location.href = base_url + "/quotation_list/", 1000
                });
            },
            error: function() {
                toastr.error("Quotation Not confirmed");
            }
        });
    }

    function getCustomers() {
        // / $('#kanban').removeClass
        
            // Get selected customer type
            var customerType = $("#quotation_for").val();

            // Clear previous options
            $("#customerName").empty();

            // Fetch customer names based on the selected type using AJAX
            $.ajax({
                url: "<?php echo base_url('admin/finance/get_customer_name/'); ?>" + customerType,
                type: "GET",
                dataType: "json",
                success: function (customers) {
                    // Populate the customer name select box
                    var customerNameSelect = $("#customerName");
                    $.each(customers, function (type, customersOfType) {
                        $.each(customersOfType, function (index, customer) {
                            var value = (type === 'Company') ? customer.f_name : customer.f_name;
                        var option = $("<option>").val(customer.crm_id).text(value);
                        customerNameSelect.append(option);
                        });
                    });
                },
                error: function () {
                    console.log("Error fetching customer names");
                }
            });
        }
</script>