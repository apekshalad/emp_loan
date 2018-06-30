
      <?php $this->load->view("default/header-top");?>
      
	  <?php $this->load->view("default/sidebar-left");?>
      
<script>
 $(function() {
    $('.alert').delay(5000).fadeOut('slow');
	
	$('#logo,#fev_icon').on('change',function()
	{			
		$('.full_sreeen_overlay').show();			
		id = $(this).attr('id');			
		var image_type = $(this).attr('data-type');			
		$('#'+id+'_progress').show();			
		var data = new FormData();			
		data.append('img', $('#'+id).prop('files')[0]);			
		data.append('image_type',image_type);			
		$.ajax({				
			url: '<?php echo site_url();?>ajax/upload_logo_images_callback_func',				
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
					url: '<?php echo site_url();?>ajax/delete_logo_images_callback_func',						
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
        <section class="content-header">
          <h1> General Settings </h1>
          
        </section>

        <!-- Main content -->
        <section class="content">
			<!-- form start -->
               <!-- <form role="form">-->
             <?php 
			$attributes = array('name' => 'add_form_post','class' => 'form');		 			
			echo form_open_multipart('settings/general_settings',$attributes); 
			
			?>
			<input type="hidden" name="user_id" class="user_id" value="<?php echo $this->session->userdata('user_id'); ?>">	
			<div class="row">
			<div class="col-md-8">   
			   
			<div class="box box-primary">
                <div class="box-header with-border">
                  <h3 class="box-title">General Settings</h3>
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
                      <label for="company_title">Company Title</label>
                      <input type="text" class="form-control" required="required" 
					  name="options[company_title]" id="company_title" placeholder="Enter Company Title" value="<?php if(isset($company_title)) echo $company_title; ?>">
                    </div>
					
					<div class="form-group">
                      <label for="company_address">Company Address</label>
                      <textarea id="company_address" class="form-control" 
					  placeholder="Enter Company Address" name="options[company_address]" 
					  rows="3"><?php if(isset($company_address)) echo $company_address; ?></textarea>
					</div>
					
					<div class="form-group">
                      <label for="company_mob">Mobile No.</label>
                      <input type="text" class="form-control" required="required" 
					  name="options[company_mob]" id="company_mob" placeholder="Enter Mobile No." 
					  value="<?php if(isset($company_mob)) echo $company_mob; ?>">
                    </div>
					
					<div class="form-group">
                      <label for="company_tel">Company Telephone No.</label>
                      <input type="text" class="form-control" required="required" 
					  name="options[company_tel]" id="company_tel" placeholder="Enter Company Telephone No." 
					  value="<?php if(isset($company_tel)) echo $company_tel; ?>">
                    </div>
					
					<div class="form-group">
                      <label for="company_website">Company Website</label>
                      <input type="text" class="form-control" required="required" 
					  name="options[company_website]" id="company_website" placeholder="Enter Company Website" 
					  value="<?php if(isset($company_website)) echo $company_website; ?>">
                    </div>
					
					<div class="form-group">
                      <label for="company_email">Company E-mail</label>
                      <input type="text" class="form-control" required="required" 
					  name="options[company_email]" id="company_email" placeholder="Enter Company E-mail" 
					  value="<?php if(isset($company_email)) echo $company_email; ?>">
                    </div>
                   	<div class="form-group">
                      <label for="company_email">Logo Text</label>
                      <input type="text" class="form-control" required="required" 
					  name="options[custum_text]" id="custum_text" placeholder="Enter Custom Text" 
					  value="<?php if(isset($custum_text)) echo $custum_text; ?>">
                    </div>				
                    <div class="row">
                         <div class="col-md-4">
                           	<div class="form-group">					  
        						<label for="exampleInputFile" style="display: block;">Website Logo</label>						
        						<label class="custom-file-upload" <?php if(isset($website_logo) && !empty($website_logo)) echo 'style="display:none;"'; ?>>	
        							<input type="file" accept="image/*" class="att_photo" id="logo" name="attachments" data-type="logo">							
        							<i class="fa fa-cloud-upload"></i> Upload Image						
        						</label>						
        						<progress id="logo_progress" value="0" max="100" style="display:none;"></progress>						
        						<a id="logo_link" href="<?php if(isset($website_logo) && !empty($website_logo)) 
        							echo base_url().'uploads/'.$website_logo; ?>" 						
        						download="<?php if(isset($website_logo) && !empty($website_logo)) 
        							echo base_url().'uploads/'.$website_logo; ?>" 
        						<?php if(!isset($website_logo)) echo 'style="display:none;"'; ?>>							
        							<img src="<?php if(isset($website_logo) && !empty($website_logo)) 
        								echo base_url().'uploads/'.$website_logo; ?>" style="max-width:150px;">						
        						</a>						
        						<a class="remove_img" id="logo_remove_img" data-name="logo" title="Remove Image" 
        						href="#" <?php if(!isset($website_logo) || empty($website_logo)) echo 'style="display:none;"'; ?>>
        						<i class="fa fa-remove"></i></a>						
        						<input type="hidden" name="options[website_logo]" 
        						value="<?php if(isset($website_logo) && !empty($website_logo)) echo $website_logo; ?>" id="logo_hidden">											
        					</div>
                         </div>
                        <div class="col-md-4">
                            <div class="form-group">
                              <label for="company_title">Top Logo Width</label>
                              <input type="text" class="form-control" required="required" 
        					  name="options[logo_width]" id="logo_width " placeholder="Enter Width" value="<?php if(isset($logo_width)) echo $logo_width; ?>">
                            </div>
                        </div>
                         <div class="col-md-4">
                            <div class="form-group">
                              <label for="company_title">Top Logo Height</label>
                              <input type="text" class="form-control" required="required" 
        					  name="options[logo_height]" id="logo_height" placeholder="Enter Height" value="<?php if(isset($logo_height)) echo $logo_height; ?>">
                            </div>
                        </div>
                    </div>
					
					<div class="form-group">					  
						<label for="exampleInputFile" style="display: block;">Fevicon Icon</label>						
						<label class="custom-file-upload" <?php if(isset($fevicon_icon) && !empty($fevicon_icon)) echo 'style="display:none;"'; ?>>	
							<input type="file" accept="image/*" class="att_photo" id="fev_icon" name="attachments" data-type="fevi">							
							<i class="fa fa-cloud-upload"></i> Upload Image						
						</label>						
						<progress id="fev_icon_progress" value="0" max="100" style="display:none;"></progress>						
						<a id="fev_icon_link" href="<?php if(isset($fevicon_icon) && !empty($fevicon_icon)) 
							echo base_url().'uploads/'.$fevicon_icon; ?>" 						
						download="<?php if(isset($fevicon_icon) && !empty($fevicon_icon)) 
							echo base_url().'uploads/'.$fevicon_icon; ?>" <?php if(!isset($fevicon_icon)) echo 'style="display:none;"'; ?>>							
							<img src="<?php if(isset($fevicon_icon) && !empty($fevicon_icon)) 
								echo base_url().'uploads/'.$fevicon_icon; ?>" style="max-width:150px;">						
						</a>						
						<a class="remove_img" id="fev_icon_remove_img" data-name="fev_icon" title="Remove Image" 
						href="#" <?php if(!isset($fevicon_icon) || empty($fevicon_icon)) echo 'style="display:none;"'; ?>>
						<i class="fa fa-remove"></i></a>						
						<input type="hidden" name="options[fevicon_icon]" 
						value="<?php if(isset($fevicon_icon) && !empty($fevicon_icon)) echo $fevicon_icon; ?>" id="fev_icon_hidden">											
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
                      <div class="box-body">
                           <div class="form-group">
                              <label for="company_title">Day Off (Select Date for Day off)</label>
                              <select class="form-control" name="options[day_off]" id="day_off" >
                                  <option value="">Select Date For Day-off</option>
                                  <?php   for ($index = 1;$index < 32;$index++) {?>
                                        
                                  <option value="<?= $index?>" <?php if($index == $day_off) echo 'selected';?>><?= $index;?></option>
                                   <?php }?>
                              </select>
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
      