
<script>
var id;
 $(document).ready(function () { 
		
		$('#att_photo,#att_id').on('change',function(){
			$('.full_sreeen_overlay').show();
			id = $(this).attr('id');
			var user_type = $(this).attr('data-user-type');
			$('#'+id+'_progress').show();
			var data = new FormData();
			data.append('img', $('#'+id).prop('files')[0]);
			data.append('user_type',user_type);
			$.ajax({
				url: '<?php echo site_url();?>ajax/upload_images_callback_func',
				type: 'POST',
				xhr: function() {
					var myXhr = $.ajaxSettings.xhr();
					if(myXhr.upload){
						myXhr.upload.addEventListener('progress',progress, false);
					}
					return myXhr;
				},
				success: function (res) {
					$('#'+id).parent().hide();
					$('#'+id+'_progress').hide();
					$('#'+id+'_hidden').val(res.img_name);
					$('a#'+id+'_link').attr('href',res.img_url).attr('download',res.img_name);
					$('a#'+id+'_link img').attr('src',res.img_url);
					$('a#'+id+'_link').show();
					$('a#'+id+'_remove_img').show();
					$('.full_sreeen_overlay').hide();
				},
				data: data,
				cache: false,
				contentType: false,
				processData: false,
			});
				
		 });
		
		
		$('a.remove_img').click(function() {
			var id = $(this).attr('data-name');
			var thiss = $(this);
			var img_name = $('#'+id+'_hidden').val();
			var user_type =  $('#'+id).attr('data-user-type');
			
			var strconfirm = confirm("Are you sure you want to delete?");
			if (strconfirm == true)
			{
					$('.full_sreeen_overlay').show();
					$.ajax({
						url: '<?php echo site_url();?>ajax/delete_images_callback_func',
						type: 'POST',
						success: function (res) {
							if(res == 'success')
							{
								$('a#'+id+'_link').removeAttr('href').removeAttr('download');
								$('a#'+id+'_link img').removeAttr('src');
								$('#'+id+'_link').hide();
								$('#'+id).parent().show();
								thiss.hide();
								$('#'+id+'_hidden').val('');
							}
							$('.full_sreeen_overlay').hide();
						},
						data: {img_name : img_name,user_type : user_type},
						cache: false
					});
				
			}
			return false;
		});
		
		
		$('#UserName').keyup(function() {
			var user_name = $(this).val();
			var thiss = $(this);
			
			$.ajax({
				url: '<?php echo site_url();?>ajax/check_username_existence',
				type: 'POST',
				success: function (res) {
					thiss.parents('.form-group').removeClass('has-success');
					thiss.parents('.form-group').removeClass('has-error');
					if(res == 'success')
					{
						thiss.parents('.form-group').addClass('has-success');
					}
					else
					{
						thiss.parents('.form-group').addClass('has-error');
					}	
					
				},
				data: {user_name : user_name},
				cache: false
			});
			
			return false;
		});
		
		$('#RepeatPassword').keyup(function() {
			var password = $('#Password').val();
			var thiss = $(this);
			thiss.parents('.form-group').removeClass('has-success');
			thiss.parents('.form-group').removeClass('has-error');
			if(password == $(this).val())
			{
				thiss.parents('.form-group').addClass('has-success');
			}
			else
			{
				thiss.parents('.form-group').addClass('has-error');
			}	
		});
		
		$('#ClientId').keyup(function() {
			var acc_no = $(this).val();
			var thiss = $(this);
			
			$.ajax({
				url: '<?php echo site_url();?>ajax/check_account_no_existence',
				type: 'POST',
				success: function (res) {
					thiss.parents('.form-group').removeClass('has-success');
					thiss.parents('.form-group').removeClass('has-error');
					if(res == 'success')
					{
						thiss.parents('.form-group').addClass('has-success');
					}
					else
					{
						thiss.parents('.form-group').addClass('has-error');
					}	
					
				},
				data: {acc_no : acc_no},
				cache: false
			});
			
			return false;
		});
		
    }); 
   
    function progress(e){
        if(e.lengthComputable){
           $('#'+id+'_progress').show();
            $('progress').attr({value:e.loaded,max:e.total});
        }
    }
	
</script>
<style>
#att_photo,#att_id,#guarantor_1_photo, #guarantor_1_id, #guarantor_2_photo, #guarantor_2_id {
    display: none;
}
.custom-file-upload {
    border: 1px solid #ccc;
    display: inline-block;
    padding: 6px 12px;
    cursor: pointer;
	font-weight: 500;
}
a.remove_img {
	background-color: #f2f2f2;
    border: 1px solid #ddd;
    color: #999;
    padding: 0 3px;
    position: relative;
    
	top: -8px;
	left: -12px;
	border-radius: 10px;
	vertical-align: top;
	
	-webkit-transition:  0.4s ease-out;
    -moz-transition: 0.4s ease-out ;
    -o-transition: 0.4s ease-out ;
    transition: 0.4s ease-out;
}
a.remove_img:hover {
    background-color: #ddd;
}
.form-group a img {
    border: 1px solid #f2f2f2;
    max-width: 150px;
    min-width: auto;
}
</style>
      <?php $this->load->view("default/header-top");?>
      
	  <?php $this->load->view("default/sidebar-left");?>
      

      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1> Add New Client </h1>
          
        </section>

        <!-- Main content -->
        <section class="content">
			<!-- form start -->
               <!-- <form role="form">-->
             <?php 
			/* echo '<pre>';
			 print_r($_SESSION);
			 echo '</pre>';*/
			 
			 
			$attributes = array('name' => 'add_form_post','class' => 'form');		 			
			echo form_open_multipart('client/add_new',$attributes); ?>
			<input type="hidden" name="user_id" class="user_id" value="<?php echo $this->session->userdata('user_id'); ?>">
			
			<div class="row">
			<div class="col-md-8">   
				
			<!-- general form elements -->
              <div class="box box-primary">
				
                <div class="box-header with-border">
                  <h3 class="box-title">Client Details</h3>
				  <div class="box-tools pull-right">
					<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
					<!--<button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>-->
				  </div>
                </div><!-- /.box-header -->
                  <div class="box-body">
                    
					<?php if( form_error('ClientName')) 	  { 	echo form_error('ClientName'); 	  } ?>
					<?php if( form_error('ClientId')) 	  { 	echo form_error('ClientId'); 	  } ?>
					<?php if( form_error('UserName')) 	  { 	echo form_error('UserName'); 	  } ?>
					<?php if( form_error('Password')) 	  { 	echo form_error('Password'); 	  } ?>
					<?php if( form_error('RepeatPassword')) 	  { 	echo form_error('RepeatPassword'); 	  } ?>
                    
                    <div class="form-group">
					  <select id="branch_id" name="branch_id" class="form-control select2 input-sm branch_list">	
							<option value="">Select Branch/Code</option>
							<?php 
                            if(isset($branch_list) && $branch_list->num_rows() > 0)
							{
								foreach($branch_list->result() as $branch_row)
								{
									echo '<option value="'.$branch_row->id.'">
											'.ucfirst($branch_row->branch_name).' ('.($branch_row->branch_code).')</option>';
								}
							}
							?>
					  </select>
					</div>
                    
					<div class="form-group">
                      <label for="ClientName">Client Name</label>
                      <input type="text" class="form-control"  name="ClientName" id="ClientName" 
					  placeholder="Enter Name of Client " value="<?php echo set_value('ClientName'); ?>">
                    </div>
					
					<div class="form-group">
                      <label for="ClientId">Client ID</label>
                      <input type="text" class="form-control" name="ClientId" id="ClientId" 
					  placeholder="Enter ID of Client " value="<?php echo set_value('ClientId'); ?>">
                    </div>

                    <div class="form-group">
                      <label for="ClientId">Account Manager</label>
                      <select name="account_manager" id="account_manager" class="form-control select2 input-sm">
                      	<option value="">Select Account Manager</option>
                      	<?php 
                            if(isset($manage) && $manage->num_rows() > 0)
							{
								foreach($manage->result() as $manages)
								{ $name='';
									if($myHelpers->global->get_user_meta($manages->user_id,'first_name'))
											$name = $myHelpers->global->get_user_meta($manages->user_id,'first_name').' '.$myHelpers->global->get_user_meta($manages->user_id,'last_name');
									echo '<option value="'.$manages->user_id.'">
											'.ucfirst($name).'</option>';
								}
							}
							?>
                      </select>
                     </div>
					
					<div class="form-group">
                      <label for="FatherName">Father/Spouse Name</label>
                      <input type="text" class="form-control"  name="FatherName" id="FatherName" 
					  placeholder="Enter Name of Father/Spouse " value="<?php echo set_value('FatherName'); ?>">
                    </div>
					
					<div class="form-group">
                      <label for="MotherName">Mother Name</label>
                      <input type="text" class="form-control" name="MotherName" id="MotherName" 
					  placeholder="Enter Name of Mother " value="<?php echo set_value('MotherName'); ?>">
                    </div>
					
					<div class="form-group">
                      <label for="ClientMobile">Client Mobile No.</label>
                      <input type="text" class="form-control" id="ClientMobile" name="ClientMobile" 
					  placeholder="Enter Mobile of Client " value="<?php echo set_value('ClientMobile'); ?>">
					</div>
					
					<div class="form-group">
                      <label for="ClientAddress">Client Address</label>
                      <textarea class="form-control" rows="3" id="ClientAddress" name="ClientAddress" 
					  placeholder="Enter Address of Client" value="<?php echo set_value('ClientAddress'); ?>"></textarea>
                    </div>
                    <div class="form-group">
                      <label for="ClientRemarks">Age</label>
                        <input type="text" class="form-control" id="age" name="age" 
					  placeholder="Enter Age" value="<?php echo set_value('age'); ?>">
                    </div>
                    <div class="form-group">
                      <label for="ClientRemarks">Nominee</label>
                        <input type="text" class="form-control" id="nominee" name="nominee" 
					  placeholder="Enter Nominee " value="<?php echo set_value('nominee'); ?>">
                    </div>
                    <div class="form-group">
                      <label for="ClientRemarks">Age of nominee</label>
                        <input type="text" class="form-control" id="age_of_nominee" name="age_of_nominee" 
					  placeholder="Enter Age of nominee " value="<?php echo set_value('age_of_nominee'); ?>">
                    </div>
                    <div class="form-group">
                      <label for="ClientRemarks">Relation with nominee</label>
                        <input type="text" class="form-control" id="relation_with_nominee" name="relation_with_nominee" 
					  placeholder="Relation with nominee" value="<?php echo set_value('relation_with_nominee'); ?>">
                    </div>
                    
                   	<div class="form-group">
                      <label for="ClientRemarks">Remarks</label>
                        <input type="text" class="form-control" id="remarks" name="remarks" 
					  placeholder="Write Remarks " value="<?php echo set_value('remark'); ?>">
                    </div>
					
					<div class="box-header with-border">
					  <h3 class="box-title">Attachments</h3>
	                </div>
					<div class="row">
					
        			<div class="col-md-6">
						<div class="form-group">
						  <label for="exampleInputFile" style="display: block;">Photo Attached</label>
							<label class="custom-file-upload">
								<input type="file" accept="image/*" id="att_photo" name="attachments" data-type="photo" data-user-type="client"/>
								<i class="fa fa-cloud-upload"></i> Upload Image
							</label>
							<progress id="att_photo_progress" value="0" max="100" style="display:none;"></progress>
							<a id="att_photo_link" href="" download="" style="display:none;">
								<img src="">
							</a>
							<a class="remove_img" id="att_photo_remove_img" data-name="att_photo" title="Remove Image" href="#" style="display:none;"><i class="fa fa-remove"></i></a>
							<input type="hidden" name="client_photo_proof" value="" id="att_photo_hidden">
							
						</div>
					</div>
					
					<div class="col-md-6">
						<div class="form-group">
						  <label for="exampleInputFile" style="display: block;">ID Attached</label>
						   <label class="custom-file-upload">
								<input type="file" accept="image/*" id="att_id" name="attachments" data-type="id" data-user-type="client"/>
								<i class="fa fa-cloud-upload"></i> Upload Image
							</label>
							<progress id="att_id_progress" value="0" max="100" style="display:none;"></progress>
							<a id="att_id_link" href="" download="" style="display:none;">
								<img src="" >
							</a>
							<a class="remove_img" id="att_id_remove_img" title="Remove Image" data-name="att_id" href="#" style="display:none;"><i class="fa fa-remove"></i></a>
							<input type="hidden" name="client_id_proof" value="" id="att_id_hidden">
						  
						</div>
					</div>
        	             
					
					<div class="col-md-12">
							<div class="box-header with-border">
							  <h3 class="box-title">Login Details</h3>
							</div><br>
						</div>
						
						<div class="col-md-6">
							<div class="form-group">
							  <label for="UserType">User Type</label>
							  <select class="form-control"  name="UserType" required id="UserType">
								<option value="user">User</option>
							  </select>
							</div>
						</div>
						
						<div class="col-md-6">
							<div class="form-group">
							  <label for="UserName">User Name</label>
							  <input type="text" class="form-control" required name="UserName" id="UserName" 
							  placeholder="Enter User Name " value="<?php //if(isset($_POST['UserName'])) echo $_POST['UserName'];?>">
							</div>
						</div>
						
						<div class="col-md-6">
							<div class="form-group">
							  <label for="Password">Password</label>
							  <input type="password" class="form-control" required name="Password" id="Password" 
							  placeholder="Enter Password ">
							</div>
						</div>
						
						<div class="col-md-6">
							<div class="form-group">
							  <label for="RepeatPassword">Repeat Password</label>
							  <input type="password" class="form-control" required name="RepeatPassword" id="RepeatPassword" 
							  placeholder="Enter Repeat Password ">
							</div>
						</div>
					
					</div>	
					
					
					
                    
                  </div>
                
              </div>
		</div><!-- end col-md-8-->
		  
		  <div class="col-md-4">
		  <div class="box box-primary">
			  <div class="box-header with-border">
                  <h3 class="box-title"> Status</h3>
				  <div class="box-tools pull-right">
					<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
					<!--<button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>-->
				  </div>
                </div><!-- /.box-header -->
				 <div class="box-body">
					<!--<span > Account No. : </span> <?php if(isset($account_no)) echo $account_no; ?><br>-->
				 	<span > Current Status : </span> Draft
				 </div>
			  	 <div class="box-footer">
					<button type="submit" name="draft" class="btn btn-draft btn-default" id="save_draft">Save Draft</button>
                    <button name="submit" type="submit" class="btn btn-primary pull-right" id="save_publish">Save Publish</button>
                  </div>
			  </div><!-- /.box -->	  
		  </div><!-- end col-md-4-->
		  
		  
		  </div><!-- end row 1-->	  
		  
		  
		  
			  
			  </form>
        </section>
      </div>