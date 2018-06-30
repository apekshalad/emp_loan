  <?php $this->load->view("default/header-top");?>
  
  <?php $this->load->view("default/sidebar-left");?>
  
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header">
	  <h1> Add New Branch </h1>
	  
	</section>

	<!-- Main content -->
	<section class="content">
		<!-- form start -->
		   <!-- <form role="form">-->
		 <?php 
		$attributes = array('name' => 'add_form_post','class' => 'form');		 			
		echo form_open_multipart('branch/add_new',$attributes); ?>
		   
		<input type="hidden" name="user_id" class="user_id" value="<?php echo $this->session->userdata('user_id'); ?>">
		
		<div class="row">
		<div class="col-md-8">   
		   
		<!-- general form elements -->
		  <div class="box box-primary">
			<div class="box-header with-border">
			  <h3 class="box-title">Branch Details</h3>
			  <div class="box-tools pull-right">
				<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
			  </div>
			</div><!-- /.box-header -->
			  <div class="box-body">
              
                <?php if( form_error('branch_name')) 	  { 	echo form_error('branch_name'); 	  } ?>
				<?php if( form_error('branch_code')) 	  { 	echo form_error('branch_code'); 	  } ?>

                <div class="form-group">
                  <label for="branch_name">Branch Name</label>
                  <input type="text" class="form-control"  name="branch_name" id="branch_name" 
				  placeholder="Enter Name of Branch " value="<?php echo set_value('branch_name'); ?>">
                </div>
				
                <div class="form-group">
                  <label for="branch_code">Branch Code</label>
                  <input type="text" class="form-control" name="branch_code" id="branch_code" 
				  placeholder="Enter ID of Branch Code" value="<?php echo set_value('branch_code'); ?>">
                </div>
                
                <div class="form-group">
                  <label for="branch_address">Branch Address</label>
                  <textarea class="form-control" rows="3" id="branch_address" name="branch_address" 
				  placeholder="Enter Address of Branch" value="<?php echo set_value('branch_address'); ?>"></textarea>
                </div>
				
			  </div><!-- /.box-body -->

		  </div><!-- /.box -->
		  
			  
		  
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
					
				 </div>
				
				 <div class="box-footer">
					<button name="submit" type="submit" class="btn btn-primary pull-right">Save</button>
				  </div>
			  </div><!-- /.box -->	 
			  
			  	
			
	  </div><!-- end col-md-4-->
	  
	  
	  </div><!-- end row-->	  
		  
		  </form>
	</section><!-- /.content -->
  </div><!-- /.content-wrapper -->
  