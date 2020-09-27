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
				<div class="row">
					<div class="col-md-4">
						<div class="form-group">
							<div class="input-group" style="width:300px;">
								<input type="text" placeholder="Search Customer by name/contact no." class="form-control" id="SearchCustomer">
								<span class="input-group-append">
						      <button class="btn btn-success" type="button" id="SearchCustomerButton" Customer-Id="Nothing">Go</button>
						    </span>
							</div>
						</div>
					</div>
					<div class="col-md-2">
						<button data-toggle="modal" data-target="#ModalAddCustomer" class="btn btn-primary"><i class="fa fa-plus"></i> New Customer</button>
						
						<!--MODAL AREA START---->

						<?php
							$this->load->view('cashier/cashier_success_modal_view');
							$this->load->view('cashier/cashier_error_modal_view');
						?>
						
						<div id="ModalAddCustomer" tabindex="-1" role="dialog" aria-hidden="true" class="modal">
							<div role="document" class="modal-dialog modal-md">
								<div class="modal-content">
									<div class="modal-header">
										<h5 class="modal-title text-white">Add New Customer</h5>
										<button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true" class="text-white">x</span></button>
									</div>
									<div class="modal-body m-3">
										<form action="#" method="POST" id="AddNewCustomer">
											<div class="form-row">
												<div class="form-group col-md-6">
													<label>Title</label>
													<select class="form-control" name="customer_title">
														<option value="Ms.">Ms.</option>
														<option value="Mr.">Mr.</option>
													</select>
												</div>
												<div class="form-group col-md-6">
													<label class="form-label">Name</label>
													<input type="text" placeholder="Enter Name" class="form-control" name="customer_name" value="Customer">
												</div>
											</div>
											<div class="form-row">
												<div class="form-group col-md-6">
													<label class="form-label">Mobile</label>
													<input type="text" data-mask="0000000000" placeholder="" class="form-control" name="customer_mobile" value="<?php for ($randomNumber = 2, $i = 1; $i < 10; $i++) {
															$randomNumber .= mt_rand(0, 9);
													} echo $randomNumber;?>">
												</div>
												<div class="form-group col-md-6">
													<label class="form-labe">Email</label>
													<input type="email" class="form-control" placeholder="Enter Email-ID" name="customer_email">
												</div>
											</div>
											<div class="form-row">                                               
                                                <div class="form-group col-md-6">
                                                    <label class="form-label">Date of birth</label>
                                                    <input type="text" class="form-control date" value="01-01-1996" name="customer_dob">
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label class="form-label">Date of Aniversary</label>
                                                    <input type="text" class="form-control date" value="01-01-2015" name="customer_doa">
                                                </div>
                                            </div>
											<input type="hidden" name="is_package_customer" value="false">
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

						<div class="modal" id="ModalCustomerDetails" tabindex="-1" role="dialog" aria-hidden="true">
							<div class="modal-dialog modal-dialog-centered modal-md" role="document">
								<div class="modal-content">
									<div class="modal-header">
										<h5 class="modal-title text-white">Customer Details</h5>
										<button type="button" class="close" data-dismiss="modal" aria-label="Close">
            						<span aria-hidden="true" class="text-white">&times;</span>
          					</button>
									</div>
									<div class="modal-body">
										<div class="row">
											<div class="col-md-12">
												<form id="EditCustomerDetails" method="POST" action="#">
													<div  class="smartwizard-arrows-primary wizard wizard-primary">
														<ul>
															<li><a href="#arrows-primary-step-1" class="sw-btn-prev">Personal Details<br /></a></li>
															<li><a href="#arrows-primary-step-3" class="sw-btn-next">Transactional Details<br/></a></li>
														</ul>

														<div>
															<div id="arrows-primary-step-1" class="">
																<div class="form-row">
																	<div class="form-group col-md-4">
																		<label>Title</label>
																		<select class="form-control" name="customer_title">
																			<option value="Mr.">Mr.</option>
																			<option value="Ms.">Ms.</option>
																		</select>
																	</div>
																	<div class="form-group col-md-4">
																		<label>Name</label>
																		<input type="text" class="form-control"  name="customer_name">
																	</div>
																	<div class="form-group col-md-4">
																		<label>Mobile</label>
																		<input type="text" class="form-control" placeholder="Mobile Number" data-mask="0000000000" maxlength="10" minlength="10" name="customer_mobile">
																	</div>
																	</div>
																<div class="form-row">
																	<div class="form-group col-md-4">
																		<label>Next Appointment Date</label>
																		<input type="text" class="form-control date" name="next_appointment_date" disabled>
																	</div>
																	<div class="form-group col-md-4">
																		<label>Date of Birth</label>
																		<input type="text" class="form-control date"  name="customer_dob">
																	</div>
																	<div class="form-group col-md-4">
																		<label>Date of Anniversary</label>
																		<input type="text" class="form-control date" placeholder="Date of Addition" name="customer_doa">
																	</div>
																</div>
																<div class="form-row">
																	<div class="form-group col-md-4">
																		<label>Total Billing</label>
																		<input type="number" class="form-control" name="total_billing" disabled>
																	</div>
																	<div class="form-group col-md-4">
																		<label>Average Order Value</label>
																		<input type="text" class="form-control"  name="avg_value" disabled>
																	</div>
																	<div class="form-group col-md-4">
																		<label>Last visit order value</label>
																		<input type="text" class="form-control" name="last_order_value" disabled>
																	</div>
																	
																	
																</div>
																<div class="form-row">
																	<div class="form-group col-md-4">
																		<label>Pending Amount</label>
																		<input type="Number" class="form-control" name="customer_pending_amount" readonly>
																	</div>
																	<div class="form-gorup col-md-4">
																		<label>Virtual Wallet Balance(Rs.)</label>
																		<input type="number" class="form-control" placeholder="Virtual Wallet" name="customer_virtual_wallet" readonly>
																	</div>
																	<div class="form-group col-md-4">
																		<label>Wallet Expiry Date</label>
																		<input type="text" class="form-control" placeholder="Wallet money expiry date" name="customer_wallet_expiry_date" readonly>
																	</div>
																	
																	
																	</div>
																<div class="form-row">
																	<div class="form-group col-md-4">
																		<label>Total Visit</label>
																		<input type="text" class="form-control" name="total_visit" disabled>
																	</div>
																	
																	<div class="form-group col-md-4">
																		<label>Last Visit Date</label>
																		<input type="text" class="form-control date" name="last_visit" disabled>
																	</div>
																	<div class="form-group col-md-4" readonly>
																		<label>Customer Segment</label>
																		<input type="text" name="customer_segment" readonly class="form-control">
																	</div>
																</div>
															</div>
															<div id="arrows-primary-step-3" class="">
																<div class="form-group">
						                      <input class="form-control" type="hidden" name="customer_id" readonly="true">
						                    </div>
						                    <div class="form-group">
						                    	<div class="card">
																		<div class="card-header">
																			<h5 class="card-title">Last 4 Transactions</h5>
																		</div>
																		<div class="card-body" id="TransactionalBills">
																			<div class="row">
																				<div class="col-md-12">
																					<table class="table table-striped table-hover" style="width: 100%;">
																						<thead>
																							<tr>
																								<th>Sno.</th>
																								<th>Bill Amt</th>
																								<th>Discount</th>
																								<th>Billing Date</th>
																							</tr>
																						</thead>
																						<tbody id="FillTxnDetails">
																						</tbody>
																					</table>
																				</div>
																			</div>
																			
																		</div>
																		
																	</div>
																	<div class="row">
																		<div class="col-md-9"></div>
																		<div class="col-md-3 mt-2">
																			<button type="submit" class="btn btn-primary">Submit</button>
																		</div>
																	</div>
																	
						                    </div>
																
															</div>
														</div>
													</div>	
												</form>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>

						<div class="modal" id="ModalClearDues" tabindex="-1" role="dialog"  aria-modal="true">
							<div class="modal-dialog modal-sm" role="document">
								<div class="modal-content">
									<div class="modal-header">
										<h5 class="modal-title text-white">Clear Due Amount</h5>
										<button type="button" class="close" data-dismiss="modal" aria-label="Close">
											<span aria-hidden="true">×</span>
										</button>
									</div>
									<div class="modal-body ">
											<form method="POST" action="#" id="ClearPendingAmountForm">
											<div class="form-row">
												<div class="form-group col-md-6">
													<label>Due Amount</label>
													<input type="number" class="form-control" name="due_amount" readonly>
												</div>
												<div class="form-group  col-md-6">
													<label>Balance Amount Due</label>
													<input type="number" class="form-control" name="balance_left" min="0">
												</div>
											</div>
											<div class="form-row">
												<div class="form-group col-md-6">
													<label>Amount Pay Now</label>
													<input type="number" class="form-control" name="amount_paid_now" value="0">
												</div>
												<div class="form-group  col-md-6">
													<label>Payment Type</label>
													<select name="payment_type" class="form-control">
														<option value="cash">Cash</option>
														<option value="credit_card">Credit Card</option>
														<option value="debit_card">Debit Card</option>
														<option value="paytm">Paytm</option>
														<option value="google_pay">Google Pay</option>
														<option value="phonepe">PhonePe</option>
													</select>
												</div>
											</div>									
												<input type="number" name="customer_id" readonly hidden>											
											<div class="form-row">
												<div class="form-group col-md-12">
													<label>Clear from dashboard ?</label>
													<!-- <input type="checkbox" name="choice"> -->
													<select class="form-control" name="remove_from_dashboard">
													  <option value="Yes" selected>Yes</option>
														<option value="No" >No</option>														
													</select>
												</div>	
												<input type="number" name="customer_id" readonly hidden>
											</div>
											<div class="form-row">
												<div class="form-group">
													<button class="btn btn-primary btn-md">Submit</button>
												</div>
											</div>
										</form>
									</div>
								</div>
							</div>
						</div>

						<!--MODAL AREA END-->
					</div>
				</div>
				
				<div class="row">
					<?php
						if(!isset($customers) || empty($customers)){
					?>
					<div class="col-md-6">
						<div class="alert alert-danger alert-outline alert-dismissible" role="alert">
							<button type="button" class="close" data-dismiss="alert" aria-label="Close">
	            	<span aria-hidden="true">×</span>
	          	</button>
							<div class="alert-icon">
								<i class="far fa-fw fa-bell"></i>
							</div>
							<div class="alert-message">
								<strong>Hello there!</strong> No Customer is currently being served!
							</div>
						</div>
					</div>
					<?php
						}
						else{
							foreach ($customers as $customer) {
								?>
								<div class="col-md-1 col-sm-3 d-flex mb-2">
									<div class="packageCard flex-fill">
										<div class="row">
												<div class="col-md-6">
														<div style="color:blue;text-decoration:underline;"><a class="EditViewCustomer" CustomerId="<?=$customer['customer_id']?>" ><?=$customer['customer_name'];?></a></div>
												</div>
												<div class="col-md-6">
														<?php 
															if(isset($customer['is_package_customer'])){ 
																if($customer['is_package_customer']['service_count'] > 0){ 
																	echo "<i class='fa fa-parking' style='font-size:21px;color:green;float:right'></i>";
																}
															}
														?>
												</div>
												<div class="col-md-12">
														<?=$dispnum = substr($customer['customer_mobile'], 0, 0) . str_repeat("*",6).substr($customer['customer_mobile'], 6,10)?>
												</div>
												<div class="col-md-12">
														<small><a  class="ClearPendingAmount text-danger"  title="Pending Amount" PendingAmount="<?=$customer['customer_pending_amount']?>"  CustomerId="<?=$customer['customer_id']?>">Due Amount : <?=$customer['customer_pending_amount']?>/-</a></small>    
												</div>
												<div class="col-md-12">
														<small>Wallet Balance: <?=$customer['customer_virtual_wallet']?></small>
												</div>
												
										</div>
										<div class="row">
												<div class="col-lg-12" style="text-align: center">  
														<a href="<?=base_url()?>Cashier/BuyPackages/<?=$customer['customer_id']?>" title="Packages"> <button type="button" class="btn btn-warning" style="width:150px">Buy Package</button></a>
												</div>
										</div>
										<div class="row" style="padding:2px">
												<div class="col-lg-12" style="text-align: center">  
														<a href="<?=base_url()?>Cashier/PerformBilling/<?=$customer['customer_id']?>" title="Billit"> <button type="button" class="btn btn-success" style="width:150px">BILL IT</button></a>
												</div>
										</div>
									</div>
								</div>
								&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;
								<?php   
										}
								}
						?>
				</div>
			</div>
		</main>
<?php
	$this->load->view('cashier/cashier_footer_view');
?>
<script type="text/javascript">
	$(".date").daterangepicker({
		singleDatePicker: true,
		showDropdowns: true,
		autoUpdateInput : false,
		locale: {
      format: 'YYYY-MM-DD'
		}
	});

	$('.date').on('apply.daterangepicker', function(ev, picker) {
    $(this).val(picker.startDate.format('YYYY-MM-DD'));
  });
</script>
<script type="text/javascript">
	$(document).ready(function(){
		$(document).ajaxStart(function() {
      $("#load_screen").show();
    });

    $(document).ajaxStop(function() {
      $("#load_screen").hide();
    });
    
    $("#AddNewCustomer input[name=customer_mobile]").on('click',function(e){
			$(this).val("");
    });
    
    $("#AddNewCustomer input[name=customer_name]").on('click',function(e){
			$(this).val("");
    });
    

    $(document).on('click',".ClearPendingAmount",function(event){
    	event.preventDefault();
    	$(this).blur();
    	$("#ClearPendingAmountForm input[name=due_amount]").val($(this).attr('PendingAmount'));
    	$("#ClearPendingAmountForm input[name=balance_left]").val($(this).attr('PendingAmount'));
    	$("#ClearPendingAmountForm input[name=customer_id]").val($(this).attr('CustomerId'));
    	$("#ModalClearDues").modal('show');
    });

    $(document).on('click',".EditViewCustomer",function(event){
    	event.preventDefault();
    	$(this).blur();
    	var parameters = { customer_id : $(this).attr('CustomerId')};
    	$.getJSON("<?=base_url()?>Cashier/GetCustomer", parameters)
	      .done(function(data, textStatus, jqXHR) { 
	      	$("#EditCustomerDetails select[name=customer_title]").val(data.customer_title);
	        $("#EditCustomerDetails input[name=customer_name]").attr('value',data.customer_name);
	        $("#EditCustomerDetails input[name=customer_mobile]").attr('value',data.customer_mobile);
	        $("#EditCustomerDetails input[name=customer_doa]").attr('value',moment(data.customer_doa).format('DD-MM-YYYY'));
            $("#EditCustomerDetails input[name=customer_dob]").attr('value',moment(data.customerdob).format('DD-MM-YYYY'));
	        $("#EditCustomerDetails input[name=customer_pending_amount]").attr('value',data.customer_pending_amount);
	        $("#EditCustomerDetails input[name=customer_virtual_wallet]").attr('value',data.customer_virtual_wallet);
	        $("#EditCustomerDetails input[name=customer_wallet_expiry_date]").attr('value',moment(data.customer_wallet_expiry_date).format('DD-MM-YYYY'));
	        $("#EditCustomerDetails input[name=customer_segment]").val(data.customer_segment);
					$("#EditCustomerDetails input[name=customer_id]").attr('value',data.customer_id);
					$("#EditCustomerDetails input[name=next_appointment_date]").attr('value',moment(data.customer_next_appointment_date).format('DD-MM-YYYY'));
					$("#EditCustomerDetails input[name=total_billing]").val(data.customer_transaction[0].total);
					$("#EditCustomerDetails input[name=total_visit]").val(data.customer_transaction[0].total_visit);
					$("#EditCustomerDetails input[name=avg_value]").val(data.customer_transaction[0].avg_value);
					$("#EditCustomerDetails input[name=last_visit]").val(moment(data.customer_transaction[0].last_visit).format('DD-MM-YYYY'));	
	

					var temp_str = "";

					for(var i = 0;i<data.transactions.length;i++){
						temp_str += "<tr>";
						temp_str += 	"<td>"+(i+1)+"</td>";
						temp_str += 	"<td>"+data.transactions[i].txn_value+"/-</td>";
						temp_str += 	"<td>"+data.transactions[i].txn_discount+"/-</td>";$("#EditCustomerDetails input[name=last_order_value]").val(data.transactions[0].txn_value);
						temp_str += 	"<td>"+data.transactions[i].BillDate+"</td>";
						temp_str += "</tr>";
						$("#EditCustomerDetails input[name=last_order_value]").val(data.transactions[0].txn_value);
					}
					$("#FillTxnDetails").html("").html(temp_str);

	        $("#ModalCustomerDetails").modal('show');
	    	})
	    	.fail(function(jqXHR, textStatus, errorThrown) {
	        console.log(errorThrown.toString());
	   		});
    });

    $("#ClearPendingAmountForm input[name=amount_paid_now]").on('input',function(){
    	var billable_amt = $("#ClearPendingAmountForm input[name=due_amount]").val();
    	if(billable_amt - $(this).val() >= 0){
  			$("#ClearPendingAmountForm input[name=balance_left]").val(billable_amt - $(this).val());
  		}
  		else{
  			$("#ClearPendingAmountForm input[name=balance_left]").val(0);	
  		}
    });

		$(".smartwizard-arrows-primary").smartWizard({
			theme: "arrows",
			showStepURLhash: false
		});

		//functionality for getting the dynamic input data
    $("#SearchCustomer").typeahead({
      autoselect: true,
      highlight: true,
      minLength: 1
    },
    {
      source: SearchCustomer,
      templates: {
      	header: ' <button data-toggle="modal" data-target="#ModalAddCustomer" class="btn btn-primary"><i class="fa fa-plus"></i> New Customer</button>',
        empty: 'No Customer Found!',
        suggestion: _.template("<p class='customer_search'><%- customer_name %>, <%- customer_mobile %></p>"),
      }
    });
       
    var to_fill = '';

    $("#SearchCustomer").on("typeahead:selected", function(eventObject, suggestion, name) {
      var loc = "#SearchCustomer";
      to_fill = "Test111111111111";
      to_fill = suggestion.customer_name+","+suggestion.customer_mobile;
      setVals(loc,to_fill,suggestion.customer_id);
    });
    	$(document).on('click',".customer_search",function(event){
    	event.preventDefault();
      this.blur();
			// alert("Click click");
			$("#SearchCustomerButton").click();
    });

    $("#SearchCustomer").blur(function(){
      $("#SearchCustomer").val(to_fill);
      to_fill = '';
    });

    function SearchCustomer(query, cb){
      var parameters = {
        query : query
      };

      $.ajax({
        url: "<?=base_url()?>Cashier/GetCustomerData",
        data: parameters,
        type: "GET",
        // crossDomain: true,
				cache: false,
        // dataType : "json",
        global : false,
    		success: function(data) {
         	cb(data.message);
        },
        error: function(data){
					console.log("Some error occured!");
        }
			});
    }  

    function setVals(element,fill,customer_id){
      $(element).attr('value',fill);
      $(element).val(fill);
      $("#SearchCustomerButton").attr('Customer-Id',customer_id);
    }

    $(document).on('click',"#SearchCustomerButton",function(event){
    	event.preventDefault();
      this.blur();
      var url= "<?=base_url()?>Cashier/AddCustomerCardToDashboard";
      var customer_id = $(this).attr('Customer-Id');
      if(customer_id == "Nothing"){
      	$('#centeredModalDanger').modal('show').on('shown.bs.modal', function (e) {
					$("#ErrorModalMessage").html("").html("Please select customer!");
				});
      }
      else{
	      var parameters = {
	        customer_id : $(this).attr('Customer-Id')
	      };
					console.log(parameters);
	      
	      // $("#SearchCustomerButton").attr('Customer-Id',"Nothing");
	      
	      $.ajax({
	        url: "<?=base_url()?>Cashier/AddCustomerCardToDashboard",
	        data: {customer_id : $(this).attr('Customer-Id')},
	        type: "POST",
					cache: false,

    		success: function(data) {
            if(data.success == 'true'){
							// window.location.reload();
							window.location.href="<?=base_url()?>Cashier/PerformBilling/"+customer_id+"";
            }
            else if (data.success == 'false'){                   
        	    $('#centeredModalDanger').modal('show').on('shown.bs.modal', function (e) {
								$("#ErrorModalMessage").html("").html(data.message);
							});
            }
          },
          error: function(data){
  					$('#centeredModalDanger').modal('show').on('shown.bs.modal', function (e) {
							$("#ErrorModalMessage").html("").html("Some error occured, Please try again later!");
						}); 
          }
				});
	    }
    });

    $("#EditCustomerDetails").validate({
	  	errorElement: "div",
	    rules: {
	    	"customer_name" : {
	    		required : true,
	    		maxlength : 100
	    	},
	    	"customer_mobile" : {
	    		required : true,
	    		minlength : 10,
	    		maxlength : 10
	    	},
	    	"customer_title" : {
	    		required : true
	    	}
	    },
	    submitHandler: function(form) {
				var formData = $("#EditCustomerDetails").serialize(); 
				$.ajax({
		        url: "<?=base_url()?>Cashier/EditCustomerDetails",
		        data: formData,
		        type: "POST",
		        // crossDomain: true,
						cache: false,
		        // dataType : "json",
		    		success: function(data) {
              if(data.success == 'true'){
              	$("#ModalCustomerDetails").modal('hide'); 
								$('#centeredModalSuccess').modal('show').on('shown.bs.modal', function (e){
									$("#SuccessModalMessage").html("").html(data.message);
								}).on('hidden.bs.modal', function (e) {
										window.location.reload();
								});
              }
              else if (data.success == 'false'){  
              	$("#ModalCustomerDetails").modal('hide');                  
          	   	$('#centeredModalDanger').modal('show').on('shown.bs.modal', function (e){
									$("#ErrorModalMessage").html("").html(data.message);
								}).on('hidden.bs.modal', function (e) {
										window.location.reload();
								}); 
              }
            },
            error: function(data){
            	$("#ModalCustomerDetails").modal('hide'); 
    					$('#centeredModalDanger').modal('show').on('shown.bs.modal', function (e){
								$("#ErrorModalMessage").html("").html(data.message);
							}).on('hidden.bs.modal', function (e) {
									window.location.reload();
							});  
            }
				});
			},
		});

    $("#AddNewCustomer").validate({
	  	errorElement: "div",
	    rules: {
	        "customer_title" : {
            required : true
	        },
	        "customer_name" : {
            required : true,
            maxlength : 100
	        },
	        "customer_mobile" : {
	          required : true,
	          maxlength : 10,
	          minlength : 10
	        }
	    },
	    submitHandler: function(form) {
				var formData = $("#AddNewCustomer").serialize(); 
				$.ajax({
	        url: "<?=base_url()?>Cashier/AddNewCustomer",
	        data: formData,
	        type: "POST",
	        // crossDomain: true,
					cache: false,
	        // dataType : "json",
	    		success: function(data) {
            if(data.success == 'true'){
            	$("#ModalAddCustomer").modal('hide'); 
							/*$('#centeredModalSuccess').modal('show').on('shown.bs.modal', function (e){
								$("#SuccessModalMessage").html("").html(data.message);
							}).on('hidden.bs.modal', function (e) {*/
									window.location.reload();
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

	  $("#ClearPendingAmountForm").validate({
	  	errorElement: "div",
	    rules: {
	        "balance_left" : {
            required : true,
            digits : true
	        },
	        "amount_paid_now" : {
	        	required : true,
	        	digits : true
	        },
	        "due_amount"  : {
	        	required : true,
	        	digits : true
	        },
	        "remove_from_dashboard" : {
	        	required : true
	        }
	    },
	    submitHandler: function(form) {
				var formData = $("#ClearPendingAmountForm").serialize(); 
				$.ajax({
	        url: "<?=base_url()?>Cashier/ClearPendingAmount",
	        data: formData,
	        type: "POST",
	        // crossDomain: true,
					cache: false,
	        // dataType : "json",
	    		success: function(data) {
            if(data.success == 'true'){
            	$("#ModalClearDues").modal('hide'); 
							$('#centeredModalSuccess').modal('show').on('shown.bs.modal', function (e){
								$("#SuccessModalMessage").html("").html(data.message);
							}).on('hidden.bs.modal', function (e) {
									window.location.reload();
							});
            }
            else if (data.success == 'false'){
            	$("#ModalClearDues").modal('hide');                   
        	    $('#centeredModalDanger').modal('show').on('shown.bs.modal', function (e) {
								$("#ErrorModalMessage").html("").html(data.message);
							});
            }
          },
          error: function(data){
          	$("#ModalClearDues").modal('hide');
  					$('#centeredModalDanger').modal('show').on('shown.bs.modal', function (e) {
							$("#ErrorModalMessage").html("").html("Some problem in processing right now!");
						});
          }
				});
			},
		});
	});
</script>
<script>
	var input = document.getElementById("SearchCustomer");
	input.addEventListener("keyup", function(event) {
		if (event.keyCode === 13) {
		event.preventDefault();
		document.getElementById("SearchCustomerButton").click();
		}
	});
</script>
