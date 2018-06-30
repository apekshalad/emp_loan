<?php $this->load->view("default/header-top");?>
<?php $this->load->view("default/sidebar-left");?>
<script>
 $(function() {
    $('.alert').parent().delay(5000).fadeOut('slow');
});
</script> 
<style>
    .ui-datepicker-next,.ui-datepicker-prev{display:none;}
    .ui-datepicker {
   background: #3c8dbc;
  
   color: #fff;
 }
 .ui-datepicker .ui-datepicker-calendar .ui-state-highlight a{
         background: #222d32;
 }
</style>
      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1> Off-Day Setting </h1>
          
        </section>

        <!-- Main content -->
        <section class="content">
			<!-- form start -->
               <!-- <form role="form">-->
   			<div class="row">
			<div class="col-md-12">   
			   
			<!-- general form elements -->
              <div class="box box-primary">
                <div class="box-header with-border">
                  <h3 class="box-title">Off-Day Setting</h3>
				  <div class="box-tools pull-right">
					<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
					<!--<button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>-->
				  </div>
                </div><!-- /.box-header -->
					
				
                  <div class="box-body">
                      <span style="color:red"><?php echo validation_errors();?></span>
                    <div id="mdp-demo"></div>
					
                  </div>
                <?php 
    $year = $this->Common_model->commonQuery('select option_value FROM `options` where option_key = "day_off"')->row_array();
    $a='';
    if(count($year)){
    
    $dts= explode(',', $year['option_value']);

foreach ($dts as $dat){
    $a .='"'.trim($dat).'",';
}
$a= rtrim($a,',');
}
?>	
 <div class="box-footer">
                                     <form method="post" >
                                         <input type="hidden" name="dayoff" id="dayoff" value="<?= $year['option_value']?>">
					<button name="submit" type="submit" class="btn btn-primary pull-right" id="save_publish">Save Changes</button>
                   </form>
                                 </div>
              </div>
			  
			  
		  </div><!-- end col-md-8-->
	  
		 
		  </div><!-- end row 1-->	  
		  
		  
			
		  
		 
		  
		  
			  
			
        </section><!-- /.content -->
      </div><!-- /.content-wrapper -->
    <?php 
echo script_tag("https://code.jquery.com/ui/1.12.1/jquery-ui.min.js");    
echo script_tag("themes/$theme/js/jquery-ui.multidatespicker.js");
?>
<script>
      jQuery(document).ready(function(){
      var today = new Date();
var y = today.getFullYear();
jQuery('#mdp-demo').multiDatesPicker({
    addDates: [<?= $a?>],
onSelect: function () {
dates = $('#mdp-demo').multiDatesPicker('value')
      $('#dayoff').val(dates);
      },
	numberOfMonths: [3,4],
	defaultDate: '1/1/'+y
});
});
      </script> 