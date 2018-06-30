<script>
var id;
 $(document).ready(function () { 
		
		$('#att_photo,#att_id').on('change',function()
		{
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
		
		$('#UserAccountNo').keyup(function() {
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
          <h1> Add New User </h1>
          
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
			echo form_open_multipart('user/add_new',$attributes); ?>
			<input type="hidden" name="user_id" class="user_id" value="<?php echo $this->session->userdata('user_id'); ?>">
			
			<div class="row">
			<div class="col-md-8">   
				
			<!-- general form elements -->
              <div class="box box-primary">
				
                <div class="box-header with-border">
                  <h3 class="box-title">User Details</h3>
				  <div class="box-tools pull-right">
					<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
					<!--<button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>-->
				  </div>
                </div><!-- /.box-header -->
                  <div class="box-body">
                    
					<?php if( form_error('user_meta[first_name]')) 	  { 	echo form_error('user_meta[first_name]'); 	  } ?>
					<?php if( form_error('user_meta[last_name]')) 	  { 	echo form_error('user_meta[last_name]'); 	  } ?>
					<?php if( form_error('user_meta[mobile_no]')) 	  { 	echo form_error('user_meta[mobile_no]'); 	  } ?>
					<?php if( form_error('UserType')) 	  { 	echo form_error('UserType'); 	  } ?>
					<?php if( form_error('UserName')) 	  { 	echo form_error('UserName'); 	  } ?>
					<?php if( form_error('Password')) 	  { 	echo form_error('Password'); 	  } ?>
					<?php if( form_error('RepeatPassword')) 	  { 	echo form_error('RepeatPassword'); 	  } ?>
					<div class="row">
					
						<div class="col-md-6">
							<div class="form-group">
							  <label for="FirstName">First Name</label>
							  <input type="text" class="form-control"  name="user_meta[first_name]" id="FirstName" required
							  placeholder="Enter First Name of User " 
							  value="<?php if(isset($_POST['user_meta["first_name"]'])) echo $_POST['user_meta["first_name"]'];?>">
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
							  <label for="LastName">Last Name</label>
							  <input type="text" class="form-control"  name="user_meta[last_name]" id="LastName" required
							  placeholder="Enter Last Name of User "
							  value="<?php if(isset($_POST['user_meta[last_name]'])) echo $_POST['user_meta[last_name]'];?>">
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
							  <label for="UserMobile">Mobile No.</label>
							  <input type="text" class="form-control" name="user_meta[mobile_no]" id="UserMobile" required
							  placeholder="Enter Mobile No. of User "
							  value="<?php if(isset($_POST['user_meta[mobile_no]'])) echo $_POST['user_meta[mobile_no]'];?>">
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
							  <label for="UserEmail">Email Address</label>
							  <input type="email" class="form-control" id="UserEmail" name="UserEmail" required
							  placeholder="Enter Email of User " value="<?php if(isset($_POST['UserEmail'])) echo $_POST['UserEmail'];?>">
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
							  <label for="UserAccountNo">Account No.</label>
							  <input type="text" class="form-control" id="UserAccountNo" name="user_meta[acc_no]" required
							  placeholder="Enter Account No. of User " value="<?php if(isset($_POST['user_meta[acc_no]'])) echo $_POST['user_meta[acc_no]'];?>">
							</div>
						</div>
                                            <div class="col-md-6">
							<div class="form-group">
							  <label for="designation">Designation</label>
							  <select name="designation_id" class="form-control">
								<option value="">Select Designation</option>
								<?php 
								
								if ( isset($designation) && $designation->num_rows() > 0)
								{		
									foreach ($designation->result() as $row)
									{$sel='';
                                                                            if(isset($_POST['designation_id'])){ if($row->id == $_POST['designation_id'] ) { $sel='selected';}}
										echo "<option value='".$row->id."' ".$sel.">".ucfirst($row->designation_name)."</option>";
									}
								}
								?>
						  </select>
							</div>
						</div>
						<div class="col-md-12">
							<div class="form-group">
							  <label for="UserAddress">Address</label>
							  <textarea class="form-control" rows="3" id="UserAddress" name="user_meta[address]" placeholder="Enter Address of User"><?php if(isset($_POST['user_meta[address]'])) echo $_POST['user_meta[address]'];?></textarea>
							</div>
						</div>
						
						<div class="col-md-6">
							<div class="form-group">
							  <label for="exampleInputFile" style="display: block;">Photo</label>
								<label class="custom-file-upload">
									<input type="file" accept="image/*" id="att_photo" name="attachments" data-type="photo" data-user-type="user"/>
									<i class="fa fa-cloud-upload"></i> Upload Image
								</label>
								<progress id="att_photo_progress" value="0" max="100" style="display:none;"></progress>
								<a id="att_photo_link" href="" download="" style="display:none;">
									<img src="">
								</a>
								<a class="remove_img" id="att_photo_remove_img" data-name="att_photo" title="Remove Image" href="#" style="display:none;"><i class="fa fa-remove"></i></a>
								<input type="hidden" name="user_meta[photo_url]" value="" id="att_photo_hidden">
								
							</div>
						</div>
						 
						<div class="col-md-6">
							<div class="form-group">
							  <label for="exampleInputFile" style="display: block;">ID</label>
							   <label class="custom-file-upload">
									<input type="file" accept="image/*" id="att_id" name="attachments" data-type="id" data-user-type="user"/>
									<i class="fa fa-cloud-upload"></i> Upload Image
								</label>
								<progress id="att_id_progress" value="0" max="100" style="display:none;"></progress>
								<a id="att_id_link" href="" download="" style="display:none;">
									<img src="" >
								</a>
								<a class="remove_img" id="att_id_remove_img" title="Remove Image" data-name="att_id" href="#" style="display:none;"><i class="fa fa-remove"></i></a>
								<input type="hidden" name="user_meta[id_url]" value="" id="att_id_hidden">
							  
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
								<option value="">Select User Type</option>
								<option value="admin">Admin</option>
								<option value="manager">Manager</option>
								<option value="moderator">Moderator</option>
								<option value="auditor">Auditor</option>
							  </select>
							</div>
						</div>
						
						<div class="col-md-6">
							<div class="form-group">
							  <label for="UserName">User Name</label>
							  <input type="text" class="form-control" required name="UserName" id="UserName" 
							  placeholder="Enter User Name " value="<?php if(isset($_POST['UserName'])) echo $_POST['UserName'];?>">
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
				
			  	 <div class="box-footer">
					<button name="submit" type="submit" class="btn btn-primary pull-right" id="save_publish">Save Publish</button>
                  </div>
			  </div><!-- /.box -->	  
		  </div><!-- end col-md-4-->
		  
		  
		  </div><!-- end row 1-->	  
		  
		  
		  
			  
			  </form>
        </section>
      </div>