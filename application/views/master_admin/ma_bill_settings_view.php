<?php
	$this->load->view('master_admin/ma_header_view');
?>
<div class="wrapper">
	<?php
		$this->load->view('master_admin/ma_nav_view');
	?>
	<div class="main">
		<?php
			$this->load->view('master_admin/ma_top_nav_view');
		?>
		<main class="content">
			<div class="container-fluid p-0">
				<h1 class="h3 mb-3">Bill Settings</h1>
				<div class="row">
					<div class="col-12">
						<div class="card">
							<div class="card-body">
								<div class="modal fade" id="defaultModalSuccess" tabindex="-1" role="dialog" aria-hidden="true">
									<div class="modal-dialog" role="document">
										<div class="modal-content">
											<div class="modal-header">
												<h5 class="modal-title">Success</h5>
												<button type="button" class="close" data-dismiss="modal" aria-label="Close">
												<span aria-hidden="true">&times;</span>
												</button>
											</div>
											<div class="modal-body m-3">
												<p class="mb-0" id="SuccessModalMessage"><p>
											</div>
											<div class="modal-footer">
												<button type="button" class="btn btn-success" data-dismiss="modal">Close</button>
											</div>
										</div>
									</div>
								</div>
								<?php 
								   if(!empty($business_outlet_details)){
										 foreach($business_outlet_details as $outlet){
											 $assignOutletIdsforPackages = $assignOutlets[$value['salon_package_id']];
											
												if(in_array($outlet['business_outlet_id'],$assignOutletIdsforPackages)){
													 $selected = 'selected="selected"';
												 }else{
													 $selected = "";
												 }
												
												 $options .= '<option value="'.$outlet['business_outlet_id'].'" '.$selected.' >'.$outlet['business_outlet_name'].'</option>';
											
										 }
									 }
								?>
								<form id="billSetting" action="#" method="POST">
							
								<div class="form-group">
									<div class="col-sm-12">
									<label class="col-form-label col-sm-2 text-sm-left pt-sm-0">Bill Print Size</label>
								  </div>
								</div> 
								  <div class="form-group" class="col-sm-9">
										<div class="col-sm-3">
											<div class="custom-controls-stacked">
												  <label class="custom-control custom-radio">
													<input name="print_size" type="radio" value="default_size"  class="custom-control-input print_size" checked="">
													<span class="custom-control-label">
													<a href="<?=base_url()?>public/images/sales_invoice_default.jpg">
													    <div ><img src="<?=base_url()?>public/images/sales_invoice_default.jpg"  width="170px" style="border:1px solid #CCCCCC;" ></div>
														<div>Default Size</div>
													</a>
													</span>
												  </label>
											</div>
										</div>
										<div class="col-sm-3">
											<div class="custom-controls-stacked">
												  
												  <label class="custom-control custom-radio">
													<input name="print_size" type="radio" value="a4_size_with_gst" class="custom-control-input print_size" checked="">
													<span class="custom-control-label">
													<a href="<?=base_url()?>public/images/sales_invoice_a4.jpg">
													<div ><img src="<?=base_url()?>public/images/sales_invoice_a4.jpg"  width="200px" style="border:1px solid #CCCCCC;" ></div >
													<div >	A4 Size with GST</div >
													</a>
													</span>
												  </label>
												 
											</div>
										</div>
										<div class="col-sm-3">
											<div class="custom-controls-stacked">
												  
												  <label class="custom-control custom-radio">
													<input name="print_size" type="radio" value="a4_size_without_gst" class="custom-control-input print_size" checked="">
													<span class="custom-control-label">
													<a href="<?=base_url()?>public/images/sales_invoice_a4.jpg">
													<div ><img src="<?=base_url()?>public/images/sales_invoice_a4.jpg"  width="200px" style="border:1px solid #CCCCCC;" ></div >
													<div >	A4 Size without GST</div >
													</a>
													</span>
												  </label>
												 
											</div>
											
										</div>
									</div>
									<div style="clear:both;">&nbsp;</div>
									<div class="form-group" class="col-sm-4">
										
										<div class="col-sm-2">
											<div class="custom-controls-stacked">
												   <label class="custom-control custom-radio">
													<input name="print_size" type="radio" value="thermal_size_with_gst" class="custom-control-input print_size">
													<span class="custom-control-label">
													<a href="<?=base_url()?>public/images/sales_invoice_Thermal.jpg">
													<div ><img src="<?=base_url()?>public/images/sales_invoice_Thermal.jpg" width="100px" style="border:1px solid #CCCCCC;"  ></div >
													<div >Thermal Print With GST</div >
													</a>
													</span>
												  </label>
											</div>
										</div>
										<div class="col-sm-2">
											<div class="custom-controls-stacked">
												   <label class="custom-control custom-radio">
													<input name="print_size" type="radio" value="thermal_size_without_gst" class="custom-control-input print_size">
													<span class="custom-control-label">
													<a href="<?=base_url()?>public/images/sales_invoice_Thermal.jpg">
													<div ><img src="<?=base_url()?>public/images/sales_invoice_Thermal.jpg" width="100px" style="border:1px solid #CCCCCC;"  ></div >
													<div >Thermal Print without GST</div >
													</a>
													</span>
												  </label>
											</div>
										</div>
									</div>
									
								
								<div style="clear:both;">&nbsp;</div>
								   <div class="form-group">
									<div class="col-sm-12">
										<label class="col-form-label col-sm-2 text-sm-left pt-sm-0">Select Outlet</label>
										<div class="col-sm-10"> 
										 <select class="outlet_valid_for" multiple name="outlet_valid_for[]"><?php echo $options; ?></select>
										</div>
									</div>				   
								</div>
									<div class="form-group"><div class="col-sm-12"><button type="submit" class="btn btn-primary">Save changes</button></div></div>
								</form>
								<div class="alert alert-dismissible feedback mt-2" role="alert">
									<button type="button" class="close" data-dismiss="alert" aria-label="Close">
										<span aria-hidden="true">&times;</span>
							</button>
									<div class="alert-message">
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</main>
<?php
	$this->load->view('master_admin/ma_footer_view');
?>

<link href="<?=base_url()?>public/app_stack/css/multiselect/bootstrap.min.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?=base_url()?>public/app_stack/js/multiselect/bootstrap.min.js"></script>
<link href="<?=base_url()?>public/app_stack/css/multiselect/bootstrap-multiselect.css" rel="stylesheet" type="text/css" />
<script src="<?=base_url()?>public/app_stack/js/multiselect/bootstrap-multiselect.js" type="text/javascript"></script>
<script src="<?=base_url()?>public/app_stack/js/multiselect/bootstrap-datepicker.js" type="text/javascript"></script>
<link href="<?=base_url()?>public/app_stack/css/multiselect/bootstrap-datepicker.css" rel="stylesheet" type="text/css" />

<script type="text/javascript">
    var	multiSelectParamsDataForOutlets = { maxHeight:150,numberDisplayed: 2,nSelectedText: 'selected' ,includeSelectAllOption: true ,buttonWidth: 250};
	getSelectedOutlet('a4_size');
	function getSelectedOutlet(print_size){
		    $.ajax({
		        url: "<?=base_url()?>MasterAdmin/GetBillSettings",
		        data: {print_size:print_size},
		        type: "POST",
		        // crossDomain: true,
				cache: false,
		        // dataType : "json",
		    	success: function(data) {
                    console.log(data);
					var selected = []; 
					for(var i=0;i<data.length;i++){
						selected.push(data[i].outlet_admin_id);
					}
					//$("#serviceSubCategoryBulkTable tr:last select[temp=Service_SubCategory_Bulk]").html("").html(options);
					console.log('ccffffff',selected);
				    
					$("select[name='outlet_valid_for[]'] option").filter(function() {
							return ($(this).val() != 0); //To select dropdown record
					}).prop('selected', false);
					
			  
					var obj=$('.outlet_valid_for');
					for (var i in selected) {
						var val=selected[i];
						console.log(val);
					    $("select[name='outlet_valid_for[]'] option").filter(function() {
							return ($(this).val() == val); //To select dropdown record
						}).prop('selected', true);
				    }
				 
				    	
				    $(".outlet_valid_for").multiselect('destroy').multiselect(multiSelectParamsDataForOutlets); 
				}
		});
	}
	$(".print_size").on('change', function(){    // 2nd (A)
      var print_size = $(this).val();
	  getSelectedOutlet(print_size); 
	});

    $(document).ready(function(){
		$('#billSetting select[name="outlet_valid_for[]"]').multiselect(multiSelectParamsDataForOutlets);  
		$("#billSetting").validate({
	  	errorElement: "div",
	    rules: {
	        "print_size" : {
				required : true
	        },
	        "outlet_valid_for[]" : {
				required : true
	        }
	    },
	    submitHandler: function(form) {
				var formData = $("#billSetting").serialize(); 
				$.ajax({
		        url: "<?=base_url()?>MasterAdmin/BillSettings",
		        data: formData,
		        type: "POST",
		        // crossDomain: true,
				cache: false,
		        // dataType : "json",
		    	success: function(data) {
                  if(data.success == 'true'){ 
								$('#defaultModalSuccess').modal('show').on('shown.bs.modal', function (e) {
									$("#SuccessModalMessage").html("").html(data.message);
								}).on('hidden.bs.modal', function (e) {
										window.location.reload();
								});
					  }
					  else if (data.success == 'false'){                   
						if($('.feedback').hasClass('alert-success')){
						  $('.feedback').removeClass('alert-success').addClass('alert-danger');
						}
						else{
						  $('.feedback').addClass('alert-danger');
						}
						$('.alert-message').html("").html(data.message); 
					  }
					},
					error: function(data){
								$('.feedback').addClass('alert-danger');
								$('.alert-message').html("").html(data.message); 
					}
				});
			},
		});
	});
</script>