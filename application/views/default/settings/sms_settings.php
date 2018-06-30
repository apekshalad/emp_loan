
      <?php $this->load->view("default/header-top");?>
      
	  <?php $this->load->view("default/sidebar-left");?>
      
<script>
 $(function() {
    $('.alert').delay(5000).fadeOut('slow');
	
	$('.att_photo').on('change',function()
	{			
		$('.full_sreeen_overlay').show();			
		id = $(this).attr('id');			
		var image_type = $(this).attr('data-type');			
		$('#'+id+'_progress').show();			
		var data = new FormData();			
		data.append('img', $('#'+id).prop('files')[0]);			
		data.append('image_type',image_type);			
		$.ajax({				
			url: '<?php echo site_url();?>/ajax/upload_logo_images_callback_func',				
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
		
		$('a.remove_img').click(function() 
		{			
			var id = $(this).attr('data-name');			
			var thiss = $(this);			
			var img_name = $('#'+id+'_hidden').val();			
			var image_type =  $('#'+id).attr('data-type');			
			var strconfirm = confirm("Are you sure you want to delete?");			
			if (strconfirm == true)			
			{					
				$('.full_sreeen_overlay').show();					
				$.ajax({						
					url: '<?php echo site_url();?>/ajax/delete_logo_images_callback_func',						
					type: 'POST',						
					success: function (res) 
					{							
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
					data: {img_name : img_name,image_type : image_type},						
					cache: false					
				});							
			}			
			return false;		
		});	
});

function progress(e)
{        
	if(e.lengthComputable){           
		$('#'+id+'_progress').show();            
		$('progress').attr({value:e.loaded,max:e.total});        
	}    
}
</script> 
<style>
.att_photo,#att_photo,#att_id,#guarantor_1_photo, #guarantor_1_id, #guarantor_2_photo, #guarantor_2_id ,#media_att_photo{
    display: none !important;}
.custom-file-upload {    
	border: 1px solid #ccc;   
	 display: inline-block;   
	 padding: 6px 12px;    
	 cursor: pointer;	
	 font-weight: 500;
 }
 
 a.remove_img {	background-color: #f2f2f2;   
 border: 1px solid #ddd;    
 color: #999;   
 padding: 0 3px;   
 position: relative;    	
 top: -8px;	left: -12px;	
 border-radius: 10px;	
 vertical-align: top;		
 -webkit-transition:  0.4s ease-out;    
 -moz-transition: 0.4s ease-out ;    
 -o-transition: 0.4s ease-out ;    
 transition: 0.4s ease-out;}
 
 a.remove_img:hover {    background-color: #ddd;}
</style>
<?php 
	if(isset($options_list) && $options_list->num_rows()>0)
	{
		
		foreach($options_list->result() as $row)
		{
			${$row->option_key} = $row->option_value;
		}
	}
?>
      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header"> <h1> SMS Settings </h1> </section>

        <!-- Main content -->
        <section class="content">
			<!-- form start -->
               <!-- <form role="form">-->
             <?php 
			$attributes = array('name' => 'add_form_post','class' => 'form');		 			
			echo form_open_multipart('settings/sms_settings',$attributes); 
			
			?>
			<input type="hidden" name="user_id" class="user_id" value="<?php echo $this->session->userdata('user_id'); ?>">	
			<div class="row">
			<div class="col-md-8">   
			   
			<div class="box box-primary">
                <div class="box-header with-border">
                  <h3 class="box-title">SMS Settings</h3>
				  <div class="box-tools pull-right">
					<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
					</div>
                </div><!-- /.box-header -->
				
				
                  <div class="box-body">
                    
					<?php echo validation_errors(); 
					if(isset($_SESSION['msg']) && !empty($_SESSION['msg']))
					{
						echo $_SESSION['msg'];
						unset($_SESSION['msg']);
					}
					?>
					
					<div class="form-group">
                      <label for="user_name">Username</label>
                      <input type="text" class="form-control" required="required" 
					  name="options[user_name]" id="user_name" placeholder="Enter Username" value="<?php if(isset($user_name)) echo $user_name; ?>">
                    </div>
	
					
					<div class="form-group">
                      <label for="password">Password</label>
                      <input type="text" class="form-control" required="required" 
					  name="options[password]" id="password" placeholder="Enter Mobile No." 
					  value="<?php if(isset($password)) echo $password; ?>">
                    </div>                    
                   	<div class="form-group">
                      <label for="password">SMS Sender ID</label>
                      <input type="text" class="form-control" required="required" 
					  name="options[sender_id]" id="sender_id" placeholder="Enter SMS Sender ID." 
					  value="<?php if(isset($sender_id)) echo $sender_id; ?>">
                    </div>
					
	              </div>

              </div>
			  
			
			<div class="box box-primary">
                <div class="box-header with-border">
                  <h3 class="box-title">Message Settings</h3>
				  <div class="box-tools pull-right">
					<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
					</div>
                </div><!-- /.box-header -->
				
				
                  <div class="box-body">
                    
					
					<div class="form-group">
                      <label for="welcome_message_sms">Welcome Message - Adding Loan</label>
                      <textarea id="welcome_message_sms" class="form-control" 
					  placeholder="Enter Welcome Message" name="options[welcome_message_sms]" 
					  rows="3"><?php if(isset($welcome_message_sms)) echo $welcome_message_sms; ?></textarea>
					</div>
					
					<div class="form-group">
                      <label for="addloan_message_sms">Add Loan Message </label>
                      <textarea id="addloan_message_sms" class="form-control" 
					  placeholder="Enter Add Loan Message" name="options[addloan_message_sms]" 
					  rows="3"><?php if(isset($addloan_message_sms)) echo $addloan_message_sms; ?></textarea>
					</div>
					
					<div class="form-group">
                      <label for="pay_emi_message_sms">After Pay EMI Message </label>
                      <textarea id="pay_emi_message_sms" class="form-control" 
					  placeholder="Enter After Pay EMI Message" name="options[pay_emi_message_sms]" 
					  rows="3"><?php if(isset($pay_emi_message_sms)) echo $pay_emi_message_sms; ?></textarea>
					</div>
					
					<div class="form-group">
                      <label for="charge_message_sms">After Amount Charge Message </label>
                      <textarea id="charge_message_sms" class="form-control" 
					  placeholder="Enter After Amount Charge Message" name="options[charge_message_sms]" 
					  rows="3"><?php if(isset($charge_message_sms)) echo $charge_message_sms; ?></textarea>
					</div>
					
					<div class="form-group">
                      <label for="deposit_message_sms">After Deposit Amount Message </label>
                      <textarea id="deposit_message_sms" class="form-control" 
					  placeholder="Enter Deposit Amount Message" name="options[deposit_message_sms]" 
					  rows="3"><?php if(isset($deposit_message_sms)) echo $deposit_message_sms; ?></textarea>
					</div>
					
					<div class="form-group">
                      <label for="withdraw_message_sms">After Withdraw Amount Message </label>
                      <textarea id="withdraw_message_sms" class="form-control" 
					  placeholder="Enter Withdraw Amount Message" name="options[withdraw_message_sms]" 
					  rows="3"><?php if(isset($withdraw_message_sms)) echo $withdraw_message_sms; ?></textarea>
					</div>
					
					<div class="form-group">
                      <label for="getting_interest_message_sms">After Getting interest Message </label>
                      <textarea id="getting_interest_message_sms" class="form-control" 
					  placeholder="Enter After Getting Interest Message" name="options[getting_interest_message_sms]" 
					  rows="3"><?php if(isset($getting_interest_message_sms)) echo $getting_interest_message_sms; ?></textarea>
					</div>
                    
                    <div class="form-group">
                      <label for="getting_interest_message_sms">Notify After Required Installments</label>
                      <textarea id="notify_install_count_sms" class="form-control" 
					  placeholder="Enter Notify after ____Installments" name="options[notify_install_count_sms]" 
					  rows="3"><?php if(isset($notify_install_count_sms)) echo $notify_install_count_sms; ?></textarea>
					</div>
					
					
	              </div>

              </div>
			
			  
		  </div>
		  
		  <div class="col-md-4">
		  <div class="box box-primary">
			  <div class="box-header with-border">
                  <h3 class="box-title"> Status</h3>
				  <div class="box-tools pull-right">
					<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
				  </div>
                </div>
				 
			  	 <div class="box-footer">
					<button name="submit" type="submit" class="btn btn-primary pull-right" id="save_publish">Save Changes</button>
                  </div>
			  </div>
		  </div>
		  
		  
		  </div>
		  
			</form>
        </section><!-- /.content -->
      </div><!-- /.content-wrapper -->
      