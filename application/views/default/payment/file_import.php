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
	
   $('#date_picker').datepicker({
		format: "mm/dd/yyyy",
		Default: true,
		pickDate: true,
		autoclose : true
   });
  $('.form').submit(function() {
			if($('.att_photo').val() == '')
			{
				alert('Please select file for upload.');
				return false;
			}else{
			 var filename = $('input[type=file]').val().split('\\').pop();
			 var extension = filename.replace(/^.*\./, '');
			 extension = extension.toLowerCase();
			 /*if(extension == 'csv'){
				 return true;
			 }else{
			 alert('Please Upload csv file with comma demilited option');
				 return false;
			}*/
            
            if(extension == 'xlsx'){
				 return true;
			 }else{
			 alert('Please Upload xlsx file');
				 return false;
			}
			}
			
		});
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
					echo form_open_multipart('payment/file_import',$attributes); ?>
							
							<div class="row">
								
								<div class="col-md-12">
									

										<div class="input-group">

										  

										  <label class="custom-file-upload">
								<input type="file"  class="att_photo" name="attachments"  accept=".xlsx" data-type="photo" data-user-type="client"/>
								<i class="fa fa-cloud-upload"></i> 
							</label>

										</div>
										
										<div class="form-group">
							  <label for="date_picker">Date</label>
							  <input type="text" class="form-control"  id="date_picker" name="pay_date" placeholder="Enter Date"  value="<?php if(isset($_POST['pay_date'])) echo $_POST['pay_date']; else { echo date('m/d/Y',time()); } ?>">
							</div><!-- /.input group -->

									 
								</div>
								
								
								<div class="col-md-12">
									<div class="">
									  <input type="submit" class="btn" name="submit" >	
                                      <div class="clearfix"></div>				  
									</div>
								</div>
								<div class="clearfix">
								<div class="">
								<?php if($this->session->flashdata('msg1238')): ?>
                                    <?php echo $this->session->flashdata('msg1238'); ?>
                                <?php endif; ?>
								</div>
								<div class="col-md-12">
                                    <p>&nbsp;</p>
                                    <?php if($this->session->flashdata('msg1234')): ?>
                                        <?php echo $this->session->flashdata('msg1234'); ?>
                                    <?php endif; ?>    
        							<?php if($this->session->flashdata('msg1235')): ?>
                                        <?php echo $this->session->flashdata('msg1235'); ?>
                                    <?php endif; ?>
								</div>
								<div class="col-md-12 clearfix">
									<div class="">
        							<?php if($this->session->flashdata('msg1235')): ?>
                                        <?php echo $this->session->flashdata('msg12389'); ?>
                                    <?php endif; ?>
								</div>
							 </div>
						</form>
				</div>
			</div>
	</section>
	
		
  </div><!-- /.content-wrapper -->
  