<!-- DataTables -->
<?php 
echo link_tag("themes/$theme/plugins/datatables/dataTables.bootstrap.css");
?>
 <script>
 $(function() {
    $('.alert').delay(5000).fadeOut('slow');
	
	$('.add_loan_model').click(function() {
		$('.full_sreeen_overlay').show();
		var customer_id = $(this).attr('data-customer-id');
		$.ajax({
			url: '<?php echo site_url();?>ajax/open_add_loan_model_callback_func',
			type: 'POST',
			success: function (res) {
				//$('.content-wrapper').append(res);
				$('.model_wrapper').html(res);
				$('.modal').show();
				$('.full_sreeen_overlay').hide();
			},
			data: {'customer_id' : customer_id},
			cache: false
			
		});
		return false;
	});
	
	$('.pay_now_model').click(function() {
		$('.full_sreeen_overlay').show();
		var loan_id = $(this).attr('data-id');
		var customer_name = $(this).attr('data-customer-name');
		var user_id = "<?php echo $_SESSION['user_id'];?>";
		if(user_id && user_id == 0)
		{
			window.location.href = '<?php echo site_url(); ?>logins';
		}
		
		$.ajax({
			url: '<?php echo site_url();?>ajax/open_pay_now_model_callback_func',
			type: 'POST',
			success: function (res) {
				//$('.content-wrapper').append(res);
				
				$('.model_wrapper').html(res);
				$('.modal').show();
				$('.full_sreeen_overlay').hide();
			},
			data: {'loan_id' : loan_id, 'customer_name' : customer_name},
			cache: false
			
		});
		return false;
	});
	
});



</script>   
<style type="text/css">
.fa-2x {
    font-size: 1.2em;
}
.w62 { width:62px;}
.pad-right-5 { padding-right:5px!important; }
</style>
      <?php $this->load->view("default/header-top");?>
      
	  <?php $this->load->view("default/sidebar-left");?>
      

      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1> Manage Clients </h1>
          <!--<ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#">Forms</a></li>
            <li class="active">Advanced Elements</li>
          </ol>-->
        </section>

        <!-- Main content -->
        <section class="content">

          <!-- SELECT2 EXAMPLE -->
          <div class="box box-default">
            <div class="box-header with-border">
              <h3 class="box-title"></h3>
              
            </div><!-- /.box-header -->
            <div class="box-body">
               <?php if(isset($_SESSION['msg']) && !empty($_SESSION['msg']))
					{
						echo $_SESSION['msg'];
						unset($_SESSION['msg']);
					}
				/*	 echo '<pre>';
			 print_r($_SESSION);
			 echo '</pre>';*/
			?> 
            </div><!-- /.box-body -->
            <div class="box-body content-box">
                  <table id="example2" class="table table-bordered table-hover">
                    <thead>
                      <tr>
                        <!--
						<th width="10px" class="bSortable"><input type="checkbox" class="checkbox select-all" /></th>
                        -->
						
                        <th width="75px" class="pad-right-5" >Account No.</th>
                        <th>Name</th>
                        <th>Branch</th>
                        <th class="pad-right-5" >Mobile No.</th>
						 <th  class="pad-right-5" >Address</th>
						<th width="150px" class="pad-right-5" >Action</th>
                      </tr>
                    </thead>
                    <tbody>
<?php  if ($query->num_rows() > 0)
	   {					
		foreach ($query->result() as $row)
		{ 
			
?>						
                      <tr>
                        <!--
						<td><input type="checkbox" class="checkbox" name="rowid[]" value="<?php echo  $row->customer_ID; ?>" /></td>						<td><?php echo  $row->customer_ID; ?></td>
						-->
						
						<td><?php echo  $row->client_acc_no; ?></td>
                        <td> <?php echo  $row->client_name; ?></td>
                        <td> <?php echo  $row->branch_name; ?></td>
                        <td><?php echo  $row->client_mobile; ?></td>
						<td><?php echo  $row->client_address; ?></td>
						<td class="action_block">
							
					<?php 
					if($myHelpers->has_permission("client" , "view"))
					{
					?>
					
					<a href="<?php $segments = array('client','view',$myHelpers->EncryptClientId($row->client_ID)); 
					echo site_url($segments);?>" title="View"><i class="fa fa-eye fa-2x"></i></a>
					<?php } ?>	
					
					<?php 
					if($myHelpers->has_permission("client" , "edit"))
					{
					?>
					
					<a href="<?php $segments = array('client','edit',$myHelpers->EncryptClientId($row->client_ID)); 
					echo site_url($segments);?>" title="Edit"><i class="fa fa-edit fa-2x"></i></a>
					<?php } ?>	
					
					<?php 
					if($myHelpers->has_permission("client" , "delete"))
					{
					?>		
					
					<a href="<?php $segments = array('client','delete',$myHelpers->EncryptClientId($row->client_ID)); 
					echo site_url($segments);?>" title="Delete" ><i class="fa fa-remove fa-2x"></i></a>
				<?php } ?>	
					
					
						</td>
                      </tr>
<?php 	}
	}	?>                      
                      
                     
                     
					 
                    </tbody>
                    
                  </table>
                </div>
          </div><!-- /.box -->

          <!-- /.row -->

        </section><!-- /.content -->
      </div><!-- /.content-wrapper -->

	  <!-- DataTables -->
<?php 
	echo script_tag("themes/$theme/plugins/datatables/jquery.dataTables.min.js");
	echo script_tag("themes/$theme/plugins/datatables/dataTables.bootstrap.min.js");
?>	  
    <!-- SlimScroll -->
<?php 
	echo script_tag("themes/$theme/plugins/slimScroll/jquery.slimscroll.min.js");
?>
	<!-- FastClick -->
<?php 
	echo script_tag("themes/$theme/plugins/fastclick/fastclick.min.js");
?>	  
  
	   <script>
      $(function () {
        //$("#example1").DataTable();
        $('#example2').DataTable({
          "paging": true,
          "lengthChange": false,
          "searching": true,
          "ordering": false,
          "info": true,
          "autoWidth": false
        });
		
		
		$('.content-box .select-all').click(function () {
			//alert($(this).is(':checked'));
			if ($(this).is(':checked'))
				$(this).parent().parent().parent().parent().find(':checkbox').attr('checked', true);
			else
			{
				$(this).parent().parent().parent().parent().find(':checkbox').attr('checked', false); 
				$(this).parent().parent().parent().parent().find(':checkbox').removeAttr('checked'); 
			}	
		});
      });
    </script>