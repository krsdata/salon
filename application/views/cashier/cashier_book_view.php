<?php
	$this->load->view('cashier/cashier_header_view');
?>
<div class="wrapper">
	<?php
		$this->load->view('cashier/cashier_nav_view');
	?>
	<div class="main">
		<?php
			$this->load->view('cashier/cashier_top_nav_view');
		?>
		<main class="content">
			<div class="container-fluid p-0">
				<h1 class="h3 mb-3">Day Book</h1>
				<div class="row">
					
				<div  class="col-md-12">
						<div class="card flex-fill w-100">
							<div class="card-header">
														
								
								<div id="ModalCashIn" tabindex="-1" role="dialog" aria-hidden="true" class="modal">
							<div role="document" class="modal-dialog modal-md">
								<div class="modal-content">
									<div class="modal-header">
										<h5 class="modal-title text-white">Cash In</h5>
										<button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true" class="text-white">x</span></button>
									</div>
									<div class="modal-body m-3">
										<form action="#" method="POST" id="cashin">
											<div class="form-row">
												<div class="form-group col-md-12">
													<label class="form-label">Cash</label>
													<input type="text" data-mask="0000000000" placeholder="" class="form-control" name="cash_in">
												</div>												
											</div>											
											<div class="form-row">
												<div class="form-group">
													<button type="submit" class="btn btn-primary">Submit</button>
												</div>
											</div>
										</form>
										<div class="alert alert-dismissible feedback" style="margin:0px;" role="alert">
											<button type="button" class="close" data-dismiss="alert" aria-label="Close">
												<span aria-hidden="true">&times;</span>
					            </button>
												<div class="alert-message"></div>
										</div>
									</div>
								</div>
							</div>
						</div>

						<div id="ModalCashOut" tabindex="-1" role="dialog" aria-hidden="true" class="modal">
							<div role="document" class="modal-dialog modal-md">
								<div class="modal-content">
									<div class="modal-header">
										<h5 class="modal-title text-white">Cash Out</h5>
										<button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true" class="text-white">x</span></button>
									</div>
									<div class="modal-body m-3">
										<form action="#" method="POST" id="cashout">
											<div class="form-row">
												<div class="form-group col-md-6">
													<label>Payment Mode</label>
													<select class="form-control" name="paymod_mode">
														<?php
														if(!empty($payment_type_arr)){
															foreach ($payment_type_arr as $key => $p) {
																if(!in_array(strtolower($p), array('split Payment','virtual_wallet','loyalty_wallet','select payment method','split'))){
																	?>
																	<option value="<?php echo ucfirst(str_replace("_", " ", $p));?>"><?php echo ucfirst(str_replace("_", " ", $p));?></option>
																	<?php

																}		
															}
														}
														?>
													</select>
												</div>
												<div class="form-group col-md-6">
													<label class="form-label">Cash</label>
													<input type="text" data-mask="0000000000" placeholder="" class="form-control" name="cash_out">
												</div>
											</div>
											<div class="form-row">
												<div class="form-group">
													<button type="submit" class="btn btn-primary">Submit</button>
												</div>
											</div>
										</form>
										<div class="alert alert-dismissible feedback" style="margin:0px;" role="alert">
											<button type="button" class="close" data-dismiss="alert" aria-label="Close">
												<span aria-hidden="true">&times;</span>
					            </button>
												<div class="alert-message"></div>
										</div>
									</div>
								</div>
							</div>
						</div>
<?php
								if(!empty($this->session->userdata['outlets']['current_outlet'])){
						            ?>
						            	<form action="<?php echo base_url('BusinessAdmin/daybook')?>" method="POST" id="GetBills1">
						            <?php
						        }elseif(!empty($this->session->userdata['logged_in']['business_outlet_id'])){
						            ?>
						            <form action="<?php echo base_url('Cashier/daybook')?>" method="POST" id="GetBills1">
						            <?php
						        }
							?>	


                <div class="form-row"> 
									<div class="col-md-3">
										<a href="javascript::void(0);"  data-toggle="modal" data-target="#ModalCashIn" class="btn btn-primary">Cash In</a>
										  <a href="javascript::void(0);"  data-toggle="modal" data-target="#ModalCashOut" class="btn btn-primary">Cash Out</a>
									</div>					                  
					                  <div class="col-md-5" style="text-align:end;float:right;">
					                    Date <i class="fa fa-calendar" style="color:red;"></i>
					                  </div>
					                  <div class="from-group col-md-2">
					                    <input type="text" class="form-control" value="<?php echo $date;?>" name="to_date" id="to_date" />
					                  </div>
					                  <div class="from-group col-md-2">
					                    <button class="btn btn-primary" id="get_bill1" >Submit</button>
					                  </div>     
					                </div>
					              </form>
							</div>
							<div class="card-body">
							
						
								<table class="table datatables-basic1 table-hover" style="width:100%;">
									<thead>
										<tr>
											<th>Tender type</th>
											<th>Opening Balance</th>
											<th>Cash In</th>
											<th>Cash Out</th>
											<th>Collection (From Sales)</th>
											<th>Due Amount 	Reced</th>
											<th>Expenses Paid</th>
											<th>Closing Balance</th>											
										</tr>
									</thead>
									<tbody>
										<?php
										$total_o = 0;
										$total_t = 0;
										$total_ci = 0;
										$total_co = 0;
										$total_p = 0;
										$total_e = 0;
										foreach ($p_mode as $key => $p) {
											?>
											<tr <?php if($p == "virtual_wallet"){ ?> style='background-color: red;' <?php } ?>>
												<td><?php echo ucfirst(str_replace("_", " ", $p));?></td>
												<td><?php echo $opening_balance_data[$p];
												if($p == "virtual_wallet"){
													$total_o = $total_o-$opening_balance_data[$p];
												}else{
													$total_o = $total_o+$opening_balance_data[$p];
												}												
												?></td>
												<td><?php echo $cashin[$p];												
													$total_ci = $total_ci+$cashin[$p];
												?></td>
												<td><?php echo $cashout[$p];												
													$total_co = $total_co+$cashout[$p];
												?></td>
												<td><?php echo $transaction_data[$p];
												if($p == "virtual_wallet"){
													$total_t = $total_t-$transaction_data[$p];
												}else{
													$total_t = $total_t+$transaction_data[$p];
												}												
												?></td>
												<td><?php echo $pending_amount_data[$p];
												if($p == "virtual_wallet"){
													$total_p = $total_p-$pending_amount_data[$p];
												}else{
													$total_p = $total_p+$pending_amount_data[$p];
												}												
												?></td>
												<td><?php echo $expenses_data[$p];
												if($p == "virtual_wallet"){
													$total_e = $total_e-$expenses_data[$p];
												}else{
													$total_e = $total_e+$expenses_data[$p];
												}												
												?></td>
												<td><?php echo ($opening_balance_data[$p]+$cashin[$p]-$cashout[$p]+$transaction_data[$p]+$pending_amount_data[$p]-$expenses_data[$p]);?></td>
											</tr>
											<?php
										}
										?>
										<tr>
												<td><strong>Total</strong></td>
												<td><strong><?php echo $total_o;?></strong></td>
												<td><strong><?php echo $total_ci;?></strong></td>
												<td><strong><?php echo $total_co;?></strong></td>
												<td><strong><?php echo $total_t;?></strong></td>
												<td><strong><?php echo $total_p;?></strong></td>
												<td><strong><?php echo $total_e;?></strong></td>
												<td><strong><?php echo ($total_t+$total_p+$total_ci-$total_co+$total_o-$total_e);?></strong></td>
											</tr>
									</tbody>
								</table>
							</div>
						</div>
				</div>
				
			</div>
		</main>

		
<?php
	$this->load->view('business_admin/ba_footer_view');
?>

<script type="text/javascript">
	$("input[name=\"from_date\"]").daterangepicker({
		singleDatePicker: true,
		showDropdowns: true,
		locale: {
    format: 'YYYY-MM-DD'
		}
	});
  $("input[name=\"to_date\"]").daterangepicker({
		singleDatePicker: true,
		showDropdowns: true,
		locale: {
    format: 'YYYY-MM-DD'
		}
	});
</script>
<script>
	$(document).ready(function(){
	
		$(document).ajaxStart(function() {
      $("#load_screen").show();
    });

    $(document).ajaxStop(function() {
      $("#load_screen").hide();
    });
  });
	$(".datatables-basic").DataTable({
			responsive: true,
			"searching": false,			
		});

	$("#cashin").validate({
	  	errorElement: "div",
	    rules: {
	        "cash_in" : {
            	required : true
	        }
	    },
	    submitHandler: function(form) {
				var formData = $("#cashin").serialize(); 
				$.ajax({
	        url: "<?=base_url()?>Cashier/AddCashIn",
	        data: formData,
	        type: "POST",
	        // crossDomain: true,
					cache: false,
	        // dataType : "json",
	    		success: function(data) {
            if(data.success == 'true'){
            	$("#ModalCashIn").modal('hide'); 
            	window.location.reload();
							/*$('#centeredModalSuccess').modal('show').on('shown.bs.modal', function (e){
								$("#SuccessModalMessage").html("").html(data.message);
							}).on('hidden.bs.modal', function (e) {*/
									// window.location.reload();
									// window.location.href="<?=base_url()?>Cashier/PerformBilling/<?=$customer['customer_id']?>";
							/*});	*/
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


$("#cashout").validate({
	  	errorElement: "div",
	    rules: {
	        "cash_out" : {
            	required : true
	        }
	    },
	    submitHandler: function(form) {
				var formData = $("#cashout").serialize(); 
				$.ajax({
	        url: "<?=base_url()?>Cashier/AddCashOut",
	        data: formData,
	        type: "POST",
	        // crossDomain: true,
					cache: false,
	        // dataType : "json",
	    		success: function(data) {
            if(data.success == 'true'){
            	$("#ModalCashOut").modal('hide'); 
            	window.location.reload();
							/*$('#centeredModalSuccess').modal('show').on('shown.bs.modal', function (e){
								$("#SuccessModalMessage").html("").html(data.message);
							}).on('hidden.bs.modal', function (e) {*/
									// window.location.reload();
									// window.location.href="<?=base_url()?>Cashier/PerformBilling/<?=$customer['customer_id']?>";
							/*});	*/
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


</script>

 
