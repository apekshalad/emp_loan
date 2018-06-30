<style type="text/css">


@media print{
	
	.content-wrapper {width:100%;}
	.content, .invoice .row .col-xs-12{padding:0;}
	.invoice{
		padding:0;
		/*border:1px solid #ccc;*/
		/*margin-left:-230px;*/
	}
	.invoice .row{
		margin-left:0;
		margin-right:0;
	}
	.content-wrapper{
		margin-left:0px;
	}
}
</style>

<?php $this->load->view("default/header-top");?>

<?php $this->load->view("default/sidebar-left");?>

	
	
	<script>
	
	$(document).ready(function () {
		
		
		
		
		
		
	});
	
	</script>
<style>
.grand_total {
    border-bottom: 1px dashed;
    border-top: 1px dashed;
    margin-top: 5px;
    padding-bottom: 10px;
    padding-top: 10px;
}
@media print{
	.cash_book_detail .col-md-12{
		padding:0px;
	}
}
</style>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header">
	  <h1> File Import </h1>
	  
	</section>

	
	<section class="content no-print" style="min-height:auto;padding-bottom:0px;">
		<div class="row">
			<div class="col-md-12">
				<div class="box box-primary">
					 <div class="box-body">
						<?php 
					$attributes = array('name' => 'add_form_post','class' => 'form');		 			
					echo form_open_multipart('report/file_import',$attributes); ?>
							
							<div class="row">
								
								<div class="col-md-12">
									<div class="form-group specific_date_container" style="width:50%;">

										<div class="input-group">

										  <div class="input-group-addon">

											<i class="fa fa-calendar"></i>

										  </div>

										  <label class="custom-file-upload">
								<input type="file" accept="image/*" id="att_photo" name="attachments" data-type="photo" data-user-type="client"/>
								<i class="fa fa-cloud-upload"></i> Upload File
							</label>

										</div><!-- /.input group -->

									  </div>
								</div>
								
								
								<div class="col-md-12">
									<div class="">
									  <input type="submit" class="btn" name="submit" >					  
									</div>
								</div>
							 </div>
						</form>
				</div>
			</div>
	</section>
	
	
		
		
		
		<section class="content">
			 <!-- this row will not appear when printing -->
          <div class="row no-print">
            <div class="col-xs-12">
			
			 <a href="#" onclick="window.print();return false;" class="btn btn-primary pull-right">Print <i class="fa fa-print"></i> </a>
             
            </div>
          </div>
		</section>
		
  </div><!-- /.content-wrapper -->
  