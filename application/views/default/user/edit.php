
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
				if(id == 'att_photo' || id == 'att_id')
				{
					var client_id = $('input[type="hidden"][name="client_id"]').val();
					var field_name = $('#'+id+'_hidden').attr('name');
					$('.full_sreeen_overlay').show();
					$.ajax({
						url: '<?php echo site_url();?>/ajax/delete_images_callback_func',
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
						data: {img_name : img_name,user_type : user_type,client_id : client_id,field_name : field_name},
						cache: false
					});
				}
				else
				{
					$('.full_sreeen_overlay').show();
					$.ajax({
						url: '<?php echo site_url();?>/ajax/delete_images_callback_func',
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
			}
			return false;
		});
		
		RepeatPassword
		
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
      

<?php 
	if(isset($query) && $query->num_rows() > 0)
	{
		$row = $query->row();
		
		$UserEmail = $row->user_email;
		$user_ID = $row->user_id;
	}
	else
	{
		$UserEmail = "";
		$user_ID = '';
	}
	
	
	
?>	  
	  
      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1> Edit User </h1>
          
        </section>

        <!-- Main content -->
        <section class="content">
			<!-- form start -->
               <!-- <form role="form">-->
             <?php 
			$attributes = array('name' => 'add_form_post','class' => 'form');		 			
			echo form_open_multipart('user/edit',$attributes); ?>
			   <input type="hidden" name="user_id" value="<?php if(isset($user_id) && !empty($user_id)) echo $user_id; ?>">
			<div class="row">
			<div class="col-md-8">   
			   
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
					<div class="row">
					
						<div class="col-md-6">
							<div class="form-group">
							  <label for="FirstName">First Name</label>
							  <input type="text" class="form-control"  name="user_meta[first_name]" id="FirstName" required
							  placeholder="Enter First Name of User " 
							  value="<?php if(isset($_POST['user_meta["first_name"]'])) 
												echo $_POST['user_meta["first_name"]'];
											else if($myHelpers->global->get_user_meta($user_ID,'first_name'))
												echo $myHelpers->global->get_user_meta($user_ID,'first_name');
									 ?>">
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
							  <label for="LastName">Last Name</label>
							  <input type="text" class="form-control"  name="user_meta[last_name]" id="LastName" required
							  placeholder="Enter Last Name of User "
							  value="<?php if(isset($_POST['user_meta[last_name]'])) 
												echo $_POST['user_meta[last_name]'];
											else if($myHelpers->global->get_user_meta($user_ID,'last_name'))
												echo $myHelpers->global->get_user_meta($user_ID,'last_name');
									?>">
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
							  <label for="UserMobile">Mobile No.</label>
							  <input type="text" class="form-control" name="user_meta[mobile_no]" id="UserMobile" required
							  placeholder="Enter Mobile No. of User "
							  value="<?php if(isset($_POST['user_meta[mobile_no]'])) 
												echo $_POST['user_meta[mobile_no]'];
											else if($myHelpers->global->get_user_meta($user_ID,'mobile_no'))
												echo $myHelpers->global->get_user_meta($user_ID,'mobile_no');
									?>">
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
							  <label for="UserEmail">Email Address</label>
							  <input type="email" class="form-control" id="UserEmail" name="UserEmail" 
							  placeholder="Enter Email of User " 
							  value="<?php if(isset($_POST['UserEmail'])) 
												echo $_POST['UserEmail'];
											else if(isset($UserEmail) && !empty($UserEmail))
												echo $UserEmail;
									?>">
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
							  <label for="UserAccountNo">Account No.</label>
							  <input type="text" readonly="readonly" class="form-control" id="UserAccountNo" name="user_meta[acc_no]" 
							  placeholder="Enter Account No. of User " 
							  value="<?php if(isset($_POST['user_meta[acc_no]'])) 
												echo $_POST['user_meta[acc_no]'];
											else if($myHelpers->global->get_user_meta($user_ID,'acc_no'))
												echo $myHelpers->global->get_user_meta($user_ID,'acc_no');?>">
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
									foreach ($designation->result() as $rows)
									{$sel='';
                                                                            if(isset($row->designation_id)){ if($rows->id == $row->designation_id ) { $sel='selected';}}
										echo "<option value='".$rows->id."' ".$sel.">".ucfirst($rows->designation_name)."</option>";
									}
								}
								?>
						  </select>
							</div>
						</div>
						<div class="col-md-12">
							<div class="form-group">
							  <label for="UserAddress">Address</label>
							  <textarea class="form-control" rows="3" id="UserAddress" name="user_meta[address]" placeholder="Enter Address of User"><?php if(isset($_POST['user_meta[address]'])) echo $_POST['user_meta[address]']; else if($myHelpers->global->get_user_meta($user_ID,'address')) echo $myHelpers->global->get_user_meta($user_ID,'address');?></textarea>
							</div>
						</div>
						
						<?php 
							if($myHelpers->global->get_user_meta($user_ID,'photo_url'))
								$photo_url = $myHelpers->global->get_user_meta($user_ID,'photo_url');
							if($myHelpers->global->get_user_meta($user_ID,'id_url'))
								$id_url = $myHelpers->global->get_user_meta($user_ID,'id_url');
						?>
						
						<div class="col-md-6">
							<div class="form-group">
							  <label for="exampleInputFile" style="display: block;">Photo</label>
								<label class="custom-file-upload" <?php if(isset($photo_url) && !empty($photo_url)) { echo 'style="display:none;"';}?>>
									<input type="file" accept="image/*" id="att_photo" name="attachments" data-type="photo" data-user-type="user"/>
									<i class="fa fa-cloud-upload"></i> Upload Image
								</label>
								<progress id="att_photo_progress" value="0" max="100" style="display:none;"></progress>
								<?php if(isset($photo_url) && !empty($photo_url)) { ?>
									<a id="att_photo_link" href="<?php echo base_url().'uploads/user/'.$photo_url; ?>" download="<?php echo $photo_url; ?>" style="">
										<img src="<?php echo base_url().'uploads/user/'.$photo_url; ?>" >
									</a>
									<a class="remove_img" id="att_photo_remove_img" data-name="att_photo" title="Remove Image" href="#"><i class="fa fa-remove"></i></a>
								<?php }else{ ?>
									<a id="att_photo_link" href="" download="" style="display:none;">
										<img src="" >
									</a>
									<a class="remove_img" id="att_photo_remove_img" data-name="att_photo" title="Remove Image" href="#" style="display:none;"><i class="fa fa-remove"></i></a>
								<?php } ?>
								<input type="hidden" name="user_meta[photo_url]" value="<?php if(isset($photo_url) && !empty($photo_url)) echo $photo_url; ?>" id="att_photo_hidden">
							</div>
						</div>
						
						<div class="col-md-6">
							<div class="form-group">
							  <label for="exampleInputFile" style="display: block;">ID</label>
							   <label class="custom-file-upload" 
							   <?php if(isset($id_url) && !empty($id_url)) { echo 'style="display:none;"';}?>>
									<input type="file" accept="image/*" id="att_id" name="attachments" data-type="id" data-user-type="user"/>
									<i class="fa fa-cloud-upload"></i> Upload Image
								</label>
								<progress id="att_id_progress" value="0" max="100" style="display:none;"></progress>
								<?php if(isset($id_url) && !empty($id_url)) { ?>
									<a id="att_id_link" href="<?php echo base_url().'uploads/user/'.$id_url; ?>" download="<?php echo $id_url; ?>" style="">
										<img src="<?php echo base_url().'uploads/user/'.$id_url; ?>" >
									</a>
									<a class="remove_img" id="att_id_remove_img" title="Remove Image" data-name="att_id" href="#" style=""><i class="fa fa-remove"></i></a>
								<?php }else{ ?>
									<a id="att_id_link" href="" download="" style="display:none;">
										<img src="" >
									</a>
									<a class="remove_img" id="att_id_remove_img" title="Remove Image" data-name="att_id" href="#" style="display:none;"><i class="fa fa-remove"></i></a>
								<?php } ?>
								<input type="hidden" name="user_meta[id_url]" value="<?php if(isset($id_url) && !empty($id_url)) echo $id_url; ?>" id="att_id_hidden">
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
							  <select class="form-control"  name="UserType" required id="UserType" disabled>
								<option value="">Select User Type</option>
								<option <?php if($row->user_type == 'admin') echo 'selected="selected"';?> value="admin">Admin</option>
								<option <?php if($row->user_type == 'manager') echo 'selected="selected"';?> value="manager">Manager</option>
								<option <?php if($row->user_type == 'moderator') echo 'selected="selected"';?> value="moderator">Moderator</option>
								<option <?php if($row->user_type == 'auditor') echo 'selected="selected"';?> value="auditor">Auditor</option>
								<option <?php if($row->user_type == 'user') echo 'selected="selected"';?> value="user">User</option>
							  </select>
							</div>
						</div> 
						
						<div class="col-md-6">
							<div class="form-group">
							  <label for="UserName">User Name</label>
							  <input type="text" class="form-control" required name="UserName" readonly id="UserName" 
							  placeholder="Enter User Name " value="<?php if(isset($_POST['UserName'])) echo $_POST['UserName']; 
													else if(isset($row->user_name) && !empty($row->user_name)) echo $row->user_name;?>">
							</div>
						</div>
						
						<div class="col-md-6">
							<div class="form-group">
							  <label for="Password">Reset Password</label>
							  <input type="password" class="form-control" required name="Password" id="Password" 
							  placeholder="Enter Password " <?php if(isset($_POST['Password'])) echo $_POST['Password']; 
													else if(isset($row->user_pass) && !empty($row->user_pass)) echo $row->user_pass;?>>
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
		  
		  
		  </div><!-- end row-->	  
			  
			  
			  
			  
			  </form>
        </section><!-- /.content -->
      </div><!-- /.content-wrapper -->
      