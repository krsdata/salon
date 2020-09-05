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
					<div class="col-md-1 mt-1">
						<button class="btn btn-primary btn-lg EditViewCustomer" CustomerId="<?=$individual_customer['customer_id']?>"><?=$individual_customer['customer_name']?></button><br>
						<p class="ClearPendingAmount" customer_id="<?=$individual_customer['customer_id']?>" pending_amount="<?=$individual_customer['customer_pending_amount']?>" style="color:red;">Due : Rs. <?=$individual_customer['customer_pending_amount']?></p>
					</div>
					<div class="col-md-1 mt-1">
						<p class="font-weight-bold" style="text-decoration:underline;" id="SwapWithExistingCustomer" CustomerId="<?=$individual_customer['customer_id']?>"><i>Swap</i></p>
					</div>
					<div class="col-md-5 mt-1">
						<div class="form-group ">
							<div class="input-group">
								<input type="text" placeholder="Search Service By Name" class="form-control" id="SearchServiceByName">
								<span class="input-group-append">
									<button class="btn btn-success ProvideServiceDetails" type="button" id="SearchServiceButton" service-id="Na" service-name="Na" service-price-inr="Na" service-gst-percentage="Na" service-est-time="Na">Go</button>
								</span>
							</div>
						</div>
					</div>
					
				</div>
				<div class="row">
					<?php
						$this->load->view('cashier/cashier_success_modal_view');
						$this->load->view('cashier/cashier_error_modal_view');
					?>
					<div class="col-md-12 mt-2">
						<div class="row">
							<div class="col-md-7">
								<div class="tab">
									<ul class="nav nav-pills" role="tablist">
										<li class="nav-item"><a class="nav-link active" href="#tab-1" data-toggle="tab" role="tab" aria-selected="false" style="font-size:14px;">Services</a></li>
										<li class="nav-item"><a class="nav-link" href="#tab-4" data-toggle="tab" role="tab" aria-selected="false" style="font-size:14px;">Retail Products</a></li>
										<li class="nav-item"><a class="nav-link" href="#tab-2" data-toggle="tab" role="tab" aria-selected="false" style="font-size:14px;">Packages</a></li>
										<?php foreach($business_admin_packages as $key => $value)
										{
											if($value == 'Marks360')
											{
											    ?>
														<li class="nav-item"><a class="nav-link" href="#tab-3" data-toggle="tab" role="tab" aria-selected="false" style="font-size:14px;">Redeem Points</a></li>
												<?php
										    }
										}?>
										
										
										<li class="nav-item"><a class="nav-link" href="#tab-5" data-toggle="tab" role="tab" aria-selected="false" style="font-size:14px;">Customer coupon</a></li>
									</ul>
									<div class="tab-content">
										<div class="tab-pane active" id="tab-1" role="tabpanel">											
											<div id="ServiceSection">
												<!----- Modal Area------->
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
																					<li><a href="#arrows-primary-step-1">Personal Details<br /></a></li>
																					<li><a href="#arrows-primary-step-3">Transactional Details<br/></a></li>
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
																								<input type="text" class="form-control" placeholder="Name" name="customer_name">
																							</div>
																							<div class="form-group col-md-4">
																								<label>Mobile</label>
																								<input type="text" class="form-control" placeholder="Mobile Number" data-mask="0000000000" name="customer_mobile" minlength="10" maxlength="10">
																							</div>
																							</div>
																						<div class="form-row">
																							<div class="form-group col-md-4">
																								<label>Next Appointment Date</label>
																								<input type="text" class="form-control date"  name="next_appointment_date" disabled>
																							</div>
																							<div class="form-group col-md-4">
																								<label>Date of Birth</label>
																								<input type="text" class="form-control" placeholder="Date of Birth" name="customer_dob">
																							</div>
																							<div class="form-group col-md-4">
																								<label>Date Of Anniversary</label>
																								<input type="text" class="form-control" placeholder="Date of Addition" name="customer_doa">
																							</div>
																							
																						</div>
																						<div class="form-row">
																							
																						
																						</div>
																						<div class="form-row">
																							<div class="form-group col-md-4">
																								<label>Total Billing</label>
																								<input type="number" class="form-control"  name="total_billing" disabled>
																							</div>
																							<div class="form-group col-md-4">
																								<label>Average Order Value</label>
																								<input type="text" class="form-control" name="avg_value" disabled>
																							</div>
																							<div class="form-group col-md-4">
																								<label>Last visit order value</label>
																								<input type="text" class="form-control" name="last_order_value" disabled>
																							</div>
																							
																							
																							</div>
																						<div class="form-row">
																							<div class="form-group col-md-4">
																								<label>Pending Amount</label>
																								<input type="Number" class="form-control" placeholder="Pending Amount" name="customer_pending_amount" readonly>
																							</div>
																							<div class="form-gorup col-md-4">
																								<label>Virtual Wallet</label>
																								<input type="number" class="form-control" placeholder="Virtual Wallet" name="customer_virtual_wallet" readonly>
																							</div>
																							<div class="form-group col-md-4">
																								<label>Wallet Expiry Date</label>
																								<input type="text" class="form-control" placeholder="Wallet money expiry date" name="customer_wallet_expiry_date" readonly>
																							</div>
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
																									<h6 class="card-subtitle text-muted">Transaction Details</h6>
																								</div>
																								<div class="card-body" id="TransactionalBills">
																									<div class="row">
																										<div class="col-md-12 mt-1">
																											<div class="card" >
																												<div class="card-body">
																													<table class="table table-striped table-hover" style="width: 100%;">
																														<thead>
																															<tr>
																																<th>Sno.</th>
																																<th>Transaction Value</th>
																																<th>Discount Applied</th>
																																<th>Transaction Date</th>
																															</tr>
																														</thead>
																														<tbody id="FillTxnDetails">
																															
																														</tbody>
																													</table>
																												</div>
																											</div>
																										</div>
																									</div>
																								</div>
																							</div>
												                    </div>
																						<button type="submit" class="btn btn-primary">Submit</button>
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

											    <!-- Due Modal -->
											<div class="modal" id="ModalClearDues" tabindex="-1" role="dialog"  aria-modal="true">
												<div class="modal-dialog modal-sm" role="document">
													<div class="modal-content">
														<div class="modal-header" style="background-color:#47bac1;">
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
																	<input type="number" name="customer_id" readonly hidden>
																</div>
																<div class="form-row">
																	<div class="form-group col-md-12">
																		<label></label>
																			<input type="hidden" name="remove_from_dashboard" value="No">
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
											<!--  -->
												
												<div id="ModalServiceEditDetails" tabindex="-1" role="dialog" aria-hidden="true" class="modal">
													<div role="document" class="modal-dialog modal-md">
														<div class="modal-content">
															<div class="modal-header">
																<h5 class="modal-title text-white">Service/Product Details</h5>
																<button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true" class="text-white" >×</span></button>
															</div>
															<div class="modal-body m-3">
																<form action="#" method="POST" id="ServiceEditDetails">
																	<div class="form-row">
																	<div class="form-group col-md-4">
																		<label class="form-label">Service/Product</label>
																		<input type="text" class="form-control" name="service_name" readonly>
																	</div>
																	<div class="form-group col-md-2">
																		<label class="form-label">Quantity</label>
																		<input type="number" placeholder="Enter Quantity" class="form-control" name="service_quantity" min="1">
																	</div>
																	<div class="form-group col-md-3">
																			<label class="form-label">Rate (Rs.)</label>
																			<input type="number" class="form-control" name="service_price_inr" readonly>
																		</div>
																		<div class="form-group col-md-3">
																			<label class="form-label">Total Value</label>
																			<input type="number" class="form-control" name="service_total_value" readonly >
																		</div>
																	
																	</div>
																	<div class="form-row">
																	
																		<div class="form-group col-md-3">
																			<label class="form-label">Discount Absolute</label>
																			<input type="number" class="form-control" name="service_discount_absolute" placeholder="Enter absolute value only" min="0" value="0">
																		</div>
																		<div class="form-group col-md-3">
																			<label class="form-label">Discount %</label>
																			<input type="number" class="form-control" name="service_discount_percentage" placeholder="Enter % value only" min="0" value="0">
																		</div>
																		<div class="form-group col-md-3">
																			<label class="form-label">Add on Price</label>
																			<input type="number" class="form-control" name="service_add_on_price" placeholder="Add on Price" value="0" min="0">
																		</div>
																		<div class="form-group col-md-3">
																		<label>Expert Name</label>
																		<select class="form-control" name="service_expert_id">
																			<?php
																				foreach ($experts as $expert):
																			?>
																					<option value="<?=$expert['employee_id']?>"><?=$expert['employee_first_name']?></option>
																			<?php		
																				endforeach;
																			?>
																		</select>
																	</div>
																	</div>
																	<div class="form-row">
																		<div class="form-group col-md-12">
																			<label>Remarks</label>
																			<textarea class="form-control" name="service_remarks"></textarea>
																		</div>
																	</div>
																	<div class="form-group">
																		<input class="form-control" type="hidden" name="service_id" readonly="true">
																		</div>
																		<div class="form-group">
																			<input class="form-control" type="hidden" name="customer_id" readonly="true">
																		</div>
																		<div class="form-group">
																			<input class="form-control" type="hidden" name="service_gst_percentage" readonly="true">
																		</div>
																		<div class="form-group">
																			<input class="form-control" type="hidden" name="service_est_time" readonly="true">
																		</div>
																		<div class="form-group">
																			<input class="form-control" type="hidden" name="customer_package_profile_id" readonly="true">
																		</div>
																	<button type="submit" class="btn btn-primary">Submit</button>
																</form>
																<div class="alert alert-dismissible feedback mt-0 mb-0" role="alert">
																	<button type="button" class="close" data-dismiss="alert" aria-label="Close">
																		<span aria-hidden="true">&times;</span>
											            </button>
																    <div class="alert-message"></div>
																</div>
															</div>
														</div>
													</div>
												</div>
												
												<div id="ModalSwapExistingCustomer" tabindex="-1" role="dialog" aria-hidden="true" class="modal">
													<div role="document" class="modal-dialog modal-sm">
														<div class="modal-content">
															<div class="modal-header">
																<h5 class="modal-title text-white">Swap With Existing Customer</h5>
																<button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true" class="text-white">×</span></button>
															</div>
															<div class="modal-body m-3">
																<form action="#" method="POST" id="SwapCustomer">
																	<div class="form-group">
																		<label>Customer Mobile</label>
																		<input type="text" class="form-control" placeholder="Mobile Number" data-mask="0000000000" name="customer_mobile" minlength="10" maxlength="10">
																	</div>
																	<div class="form-group">
							                      <input class="form-control" type="hidden" name="customer_id" readonly="true">
							                    </div>
																	<button type="submit" class="btn btn-primary">Swap Customer</button>
																</form>
																<div class="alert alert-dismissible feedback mt-0 mb-0" role="alert">
																	<button type="button" class="close" data-dismiss="alert" aria-label="Close">
																		<span aria-hidden="true">&times;</span>
											            </button>
																	<div class="alert-message"></div>
																</div>
															</div>
														</div>
													</div>
												</div>

												<!-- Jitesh Loyalty Modal -->
												<div id="ModalLoyaltyServiceEditDetails" tabindex="-1" role="dialog" aria-hidden="true" class="modal">
                          <div role="document" class="modal-dialog modal-md">
                            <div class="modal-content">
                              <div class="modal-header">
                                <h5 class="modal-title text-white">Service/Product Details</h5>
                                <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true" class="text-white" >×</span></button>
                              </div>
                              <div class="modal-body m-3">
                                <form action="#" method="POST" id="LoyaltyServiceEditDetails">
                                  <div class="form-row">
                                  <div class="form-group col-md-4">
                                    <label class="form-label">Service/Product</label>
                                    <input type="text" class="form-control" name="service_name" readonly>
                                  </div>
                                  <div class="form-group col-md-2">
                                    <label class="form-label">Quantity</label>
                                    <input type="number" placeholder="Enter Quantity" class="form-control" name="service_quantity" min="1" readonly>
                                  </div>
                                  <div class="form-group col-md-3">
                                      <label class="form-label">Rate (Rs.)</label>
                                      <input type="number" class="form-control" name="service_price_inr" readonly>
                                    </div>
                                    <div class="form-group col-md-3">
                                      <label class="form-label">Total Value</label>
                                      <input type="number" class="form-control" name="service_total_value" readonly >
                                    </div>
                                  
                                  </div>
                                  <div class="form-row">
                                  
                                    <div class="form-group col-md-4">
                                      <label class="form-label">Discount Absolute</label>
                                      <input type="number" class="form-control" name="service_discount_absolute" placeholder="Enter absolute value only" value="0" readonly>
                                    </div>
                                    <div class="form-group col-md-4">
                                    <label class="form-label">Discount %</label>
                                    <input type="number" class="form-control" name="service_discount_percentage" placeholder="Enter % value only" value="0" readonly>
                                  </div>
                                    <div class="form-group col-md-4">
                                    <label>Expert Name</label>
                                    <select class="form-control" name="service_expert_id">
                                      <?php
                                        foreach ($experts as $expert):
                                      ?>
                                          <option value="<?=$expert['employee_id']?>"><?=$expert['employee_nick_name']?></option>
                                      <?php   
                                        endforeach;
                                      ?>
                                    </select>
                                  </div>
                                  </div>
                                  
                                  <div class="form-group">
                                    <input class="form-control" type="hidden" name="service_id" readonly="true">
                                    </div>
                                    <div class="form-group">
                                      <input class="form-control" type="hidden" name="customer_id" readonly="true">
                                    </div>
                                    <div class="form-group">
                                      <input class="form-control" type="hidden" name="service_gst_percentage" readonly="true">
                                    </div>
                                    <div class="form-group">
                                      <input class="form-control" type="hidden" name="service_est_time" readonly="true">
                                    </div>
                                    <div class="form-group">
                                      <input class="form-control" type="hidden" name="customer_package_profile_id" readonly="true">
                                    </div>
                                  <button type="submit" class="btn btn-primary">Submit</button>
                                </form>
                                <div class="alert alert-dismissible feedback mt-0 mb-0" role="alert">
                                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                               </button>
                                     <div class="alert-message"></div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
												<!------Modal Area End--->
												
												<!----Accordion Start---->
												<div class="accordion" id="accordionExample">
													<div class="card">
														<div class="card-header" id="headingOne">
															<h5 class="card-title my-2">
																<a href="#" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
										              Categories
										            </a>
															</h5>
														</div>
														<div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionExample">
															<div class="card-body" style="margin-right:10px;">
																<div class="row" style="margin-right:10px;">
																	<?php
																		foreach ($categories as $category):
																	?>																		
																		<div class="col-md-2 col-sm-4">
																			<a class="ShowSubCategories" category-id="<?=$category['category_id']?>">
																				<div class="card customized-category-card">
																					<div class="card-body" style="text-align: center;padding:.25rem!important;">
																						<p class="card-text"><?=$category['category_name']?></p>
																					</div>
																				</div>
																			</a>		
																		</div>

																	<?php		
																		endforeach;
																	?>
																	
																</div>
															</div>
														</div>
													</div>
													<div class="card">
														<div class="card-header" id="headingTwo">
															<h5 class="card-title my-2">
																<a href="#" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true" aria-controls="collapseOne">
										              Sub-Categories
										            </a>
															</h5>
														</div>
														<div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionExample">
															<div class="card-body" id="Sub-Categories-Data">
																
															</div>
														</div>
													</div>
													<div class="card">
														<div class="card-header" id="headingThree">
															<h5 class="card-title my-2">
																<a href="#" data-toggle="collapse" data-target="#collapseThree" aria-expanded="true" aria-controls="collapseOne">
										              Services
										            </a>
															</h5>
														</div>
														<div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordionExample">
															<div class="card-body" id="Services-Data">
															</div>
														</div>
													</div>
												</div>
												<!----Accordion End------>
											</div>
										</div>
										<div class="tab-pane" id="tab-2" role="tabpanel">
											<?php
												if(!isset($active_packages_categories) || empty($active_packages_categories)){
											?>	
												<div class="alert alert-info alert-outline alert-dismissible" role="alert">
													<button type="button" class="close" data-dismiss="alert" aria-label="Close">
							            	<span aria-hidden="true">×</span>
							          	</button>
													<div class="alert-icon">
														<i class="far fa-fw fa-bell"></i>
													</div>
													<div class="alert-message">
														<strong>Hello there!</strong> You have no active packages!
													</div>
												</div>
											<?php		
												}
												else{	
											?>
												<!----Accordion Start---->
												<div class="accordion" id="accordionPackages">
													<div class="card">
														<div class="card-header" id="headingOnePackages">
															<h5 class="card-title my-2">
																<a href="#" data-toggle="collapse" data-target="#collapseOnePackages" aria-expanded="true" aria-controls="collapseOnePackages">
										              Categories
										            </a>
															</h5>
														</div>
														<div id="collapseOnePackages" class="collapse show" aria-labelledby="headingOnePackages" data-parent="#accordionPackages">
															<div class="card-body">
																<div class="row" style="margin-right:10px;">
																	<?php
																		foreach ($active_packages_categories as $category):
																	?>
																		
																		<div class="col-md-2 col-sm-4">
																			<a class="ShowPackageSubCategories" category-id="<?=$category['category_id']?>" customer-id="<?=$category['customer_id']?>">
																				<div class="card customized-category-card">
																					<div class="card-body" style="text-align: center;padding:.25rem!important;">
																						<p class="card-text"><?=$category['category_name']?></p>
																					</div>
																				</div>
																			</a>		
																		</div>

																	<?php		
																		endforeach;
																	?>
																	
																</div>
															</div>
														</div>
													</div>
													<div class="card">
														<div class="card-header" id="headingTwoPackages">
															<h5 class="card-title my-2">
																<a href="#" data-toggle="collapse" data-target="#collapseTwoPackages" aria-expanded="true" aria-controls="collapseOnePackages">
										              Sub-Categories
										            </a>
															</h5>
														</div>
														<div id="collapseTwoPackages" class="collapse" aria-labelledby="headingTwoPackages" data-parent="#accordionPackages">
															<div class="card-body" id="Sub-Categories-Packages-Data">
																
															</div>
														</div>
													</div>
													<div class="card">
														<div class="card-header" id="headingThreePackages">
															<h5 class="card-title my-2">
																<a href="#" data-toggle="collapse" data-target="#collapseThreePackages" aria-expanded="true" aria-controls="collapseOnePackages">
										              Services
										            </a>
															</h5>
														</div>
														<div id="collapseThreePackages" class="collapse" aria-labelledby="headingThreePackages" data-parent="#accordionPackages">
															<div class="card-body" id="Services-Packages-Data">
															</div>
														</div>
													</div>
												</div>
												<!----Accordion End------>
												
												<!--Modal Section for packages-->
												<div id="ModalChooseServiceFromPackage" tabindex="-1" role="dialog" aria-hidden="true" class="modal">
													<div role="document" class="modal-dialog modal-md">
														<div class="modal-content">
															<div class="modal-header">
																<h5 class="modal-title text-white">Service Details</h5>
																<button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true" class="text-white">×</span></button>
															</div>
															<div class="modal-body m-3">
																<form action="#" method="POST" id="PackageRedemption">
																	<div class="form-row">
																		<div class="form-group col-md-4">
																			<label class="form-label">Service</label>
																			<input type="text" class="form-control" name="service_name" readonly>
																		</div>
																		<div class="form-group col-md-4">
																			<label class="form-label">Quantity Left</label>
																			<input type="number" class="form-control" name="service_quantity" readonly>
																		</div>
																		<div class="form-group col-md-4">
																			<label class="form-label">Redeem Quantity</label>
																			<input type="number" placeholder="Enter Quantity" class="form-control" name="service_quantity_redeemable" min="1">
																		</div>
																	</div>
																	<div class="form-row">
																		<div class="form-group col-md-4">
																			<label class="form-label">Discount %</label>
																			<input type="number" class="form-control" name="service_discount_percentage" min="0" readonly>
																		</div>
																		<div class="form-group col-md-4">
																			<label class="form-label">Rate (Rs.)</label>
																			<input type="number" class="form-control" name="service_price_inr" readonly>
																		</div>
																		<div class="form-group col-md-4">
																			<label class="form-label">Total Value</label>
																			<input type="number" class="form-control" name="service_total_value" readonly>
																		</div>
																	</div>
																	<div class="form-group">
																		<label class="form-label">Package Name</label>
																		<input type="text" name="salon_package_name" readonly class="form-control">
																	</div>
																	<div class="form-group">
																		<label>Expert Name</label>
																		<select class="form-control" name="service_expert_id">
																			<?php
																				foreach ($experts as $expert):
																			?>
																					<option value="<?=$expert['employee_id']?>"><?=$expert['employee_nick_name']?></option>
																			<?php		
																				endforeach;
																			?>
																		</select>
																	</div>
																	<div class="form-group">
							                      <input class="form-control" type="hidden" name="service_id" readonly="true">
							                    </div>
							                    <div class="form-group">
							                      <input class="form-control" type="hidden" name="customer_id" readonly="true">
							                    </div>
							                    <div class="form-group">
							                      <input class="form-control" type="hidden" name="service_gst_percentage" readonly="true">
							                    </div>
							                    <div class="form-group">
							                      <input class="form-control" type="hidden" name="service_est_time" readonly="true">
							                    </div>
							                    <div class="form-group">
							                      <input class="form-control" type="hidden" name="customer_package_profile_id" readonly="true">
							                    </div>
																	<button type="submit" class="btn btn-primary">Submit</button>
																</form>
																<div class="alert alert-dismissible feedback mt-0 mb-0" role="alert">
																	<button type="button" class="close" data-dismiss="alert" aria-label="Close">
																		<span aria-hidden="true">&times;</span>
											            </button>
																	<div class="alert-message"></div>
																</div>
															</div>
														</div>
													</div>
												</div>
												<!--Modal Section Ends-->
											<?php
												}
											?>
										</div>
										<!-- loyalty redeem -->'
										
										<div class="tab-pane" id="tab-3" role="tabpanel">
                      <div class="row">
                      <div class="col-md-12"> 
                      <div class="card">
                        <div class="card-header"><h5>Redeem offer</h5>
                        </div>
                        <div class="card-body">
                          
                        <?php
                        if(isset($rules)){
                            if($rules['rule_type'] == 'Cashback Single Rule' || $rules['rule_type'] == 'Cashback Multiple Rule' || $rules['rule_type'] == 'Cashback LTV Rule') 
                            {
                              ?>
                              <label><b>Current Cashback : </b></label>
                              <input type="text" id="cust_rewards" class="float-right" style="border :cornsilk" value="<?=$individual_customer['customer_cashback'] ?>" readonly>
                              <table class="table" id="CashbackTable">
                                <h5>To redeem cashback select loyalty wallet for payment</h5>
                              </table>
                              <?php
                            }   
                            else
                            {
													$count = 1;
                              if(!isset($loyalty_offer) || empty($loyalty_offer)){
                                ?>  
                                <div class="alert alert-info alert-outline alert-dismissible" role="alert">
                                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                  </button>
                                  <div class="alert-icon">
                                    <i class="far fa-fw fa-bell"></i>
                                  </div>
                                  <div class="alert-message">
                                    <strong>No Offers Found !</strong>
                                  </div>
                                </div>
                                <?php
                              }
                           
                          else if($loyalty_offer[0]['rule_type'] == 'Cashback Visits')
                          {
                            ?>
                            <label><b>Current Points : </b></label>
                            <input type="text" id="cust_rewards" class="float-right" style="border :cornsilk" value="<?= $individual_customer['customer_rewards']?>" readonly>
                            <table class="table" id="VisitTable">
                              <th>Visits</th>
                              <th colspan="2">Offers</th>
                              <th >Action</th>
                            <?php
                            foreach($loyalty_offer as $loyalty_offer)
                            {
                              if($loyalty_offer['offers_status'] == 1)
                              {
                              ?>
                              
                              <tr>
                                <td>
                                 <input type="number" placeholder="Enter Visit" name="points" class="form-control points" value="<?= $loyalty_offer['visits']?>" readonly>
                                </td>
                                <td><label><b>=</b></label></td>
                                <td>
                                  <input type="text" placeholder="Enter Offers" name="offers" class="form-control offer" value="<?=$loyalty_offer['offers']?>" readonly>
                                </td>
                                <td>
                                <button type="submit" class="btn btn-danger" offer_selected="<?=$loyalty_offer['offer_id']?>" id=<?=$count?> onclick="redeem_offer(this.id)" individual_customer ="<?=$individual_customer['customer_id']?>" cashier_id = "<?=$cashier_details['employee_id'];?>" ><i class="fa fa-gift"></i> Redeem</button>
                                </td>
                              </tr>
                              <?php
                              $count++;
                            }
                          }
                            ?>
                            </table>
                            <?php
                          }
                          else if($loyalty_offer[0]['rule_type'] == 'Cashback Single Rule' || $loyalty_offer[0]['rule_type'] == 'Cashback Multiple Rule' || $loyalty_offer[0]['rule_type'] == 'Cashback LTV Rule') 
                          {
                            ?>
                            <label><b>Current Points : </b></label>
                            <input type="text" id="cust_rewards" class="float-right" style="border :cornsilk" value="<?=$individual_customer['customer_rewards']?>" readonly>
                            <table class="table" id="CashbackTable">
                              <h5>To redeem cashback select loyalty wallet for payment</h5>
                            </table>
                            <?php
                          }
                          else 
                          {
                            ?>
                            <form id="RedeemOffer" action="#" method="POST">
                            <label><b>Current Points : </b></label>
                            <input type="text" id="cust_rewards" class="float-right" style="border :cornsilk" value="<?=$individual_customer['customer_rewards']?>" readonly>
                            
                              <table class="table" id="OfferTable">
                                <th>Points</th>
                                <th></th>
                                <th>Offers</th>
                                <th>Action</th>
                                <?php
                                foreach($loyalty_offer as $loyalty_offer)
                                {
                                  if($loyalty_offer['offers_status'] == 1)
                                  { 
                                ?>
                                <tr>
                                  <td>
                                  <input type="number" placeholder="Enter points" name="points" class="form-control points" value="<?= $loyalty_offer['points']?>" readonly>
                                  </td>
                                  <td><label><b>=</b></label></td>
                                  <td>
                                    <input type="text" placeholder="Enter Offers" name="offers" class="form-control offer" value="<?=$loyalty_offer['offers']?>" readonly>
                                  </td>
                                  <td>
                                  <button type="submit" class="btn btn-danger offer_btn" offer_selected="<?=$loyalty_offer['offer_id']?>" <?php if($individual_customer['customer_rewards']< $loyalty_offer['points']) echo 'disabled';?> id=<?=$count?> onclick="redeem_offer(this.id)" individual_customer ="<?=$individual_customer['customer_id']?>" cashier_id = "<?=$cashier_details['employee_id'];?>"><i class="fa fa-gift" ></i> Redeem</button>
                                  </td>
                                </tr>
                                <?php
                                $count++;
                                }
                              }
                                ?>
                                </table>
                            </form>
                          <?php
                          }
                        }
                        }
                        else
                        {
                        ?>
                          <div class="alert alert-info alert-outline alert-dismissible" role="alert">
                                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                  </button>
                                  <div class="alert-icon">
                                    <i class="far fa-fw fa-bell"></i>
                                  </div>
                                  <div class="alert-message">
                                    <strong>No Rules Defined !</strong>
                                  </div>
                                </div>
                    <?php
                        }
                      ?>
                         </div>
                        </div>
                      </div>
                      </div>
                      
                      
                    </div>
										<!-- end -->
										<!-- Retail product -->
										<div class="tab-pane" id="tab-4" role="tabpanel">
											<?php
												if(!isset($categories_products) || empty($categories_products)){

											?>	
												<div class="alert alert-info alert-outline alert-dismissible" role="alert">
													<button type="button" class="close" data-dismiss="alert" aria-label="Close">
							            	<span aria-hidden="true">×</span>
							          	</button>
													<div class="alert-icon">
														<i class="far fa-fw fa-bell"></i>
													</div>
													<div class="alert-message">
														<strong>No Product Category Found !</strong>
													</div>
												</div>
											<?php		
												}
												else{	
											?>
												<!----Accordion Start---->
												<div class="accordion" id="accordionPackages1">
													<div class="card">
														<div class="card-header" id="headingOne">
															<h5 class="card-title my-2">
																<a href="#" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
										              Categories
										            </a>
															</h5>
														</div>
														<div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionPackages1">
															<div class="card-body">
																<div class="row" style="margin-right:10px;">
																	<?php
																		foreach ($categories_products as $cp):
																	?>
																		
																		<div class="col-md-2 col-sm-4">
																			<a class="ShowProductSubCategories" category-id="<?=$cp['category_id']?>" >
																				<div class="card customized-category-card">
																					<div class="card-body" style="text-align: center;padding:.25rem!important;">
																						<p class="card-text"><?=$cp['category_name']?></p>
																					</div>
																				</div>
																			</a>		
																		</div>

																	<?php		
																		endforeach;
																	?>
																	
																</div>
															</div>
														</div>
													</div>
													<div class="card">
														<div class="card-header" id="headingTwo">
															<h5 class="card-title my-2">
																<a href="#" data-toggle="collapse" data-target="#collapseSubCategory" aria-expanded="true" aria-controls="collapseOne">
										              Sub-Categories
										            </a>
															</h5>
														</div>
														<div id="collapseSubCategory" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionPackages1">
															<div class="card-body" id="Otc_Sub_Categories">
																
															</div>
														</div>
													</div>
													<div class="card">
														<div class="card-header" id="headingThree">
															<h5 class="card-title my-2">
																<a href="#" data-toggle="collapse" data-target="#collapseServices" aria-expanded="true" aria-controls="collapseone">
																	Products
																</a>
															</h5>
														</div>
														<div id="collapseServices" class="collapse" aria-labelledby="headingThree" data-parent="#accordionPackages1">
															<div class="card-body" id="Product_Services">
															</div>
														</div>
													</div>
													<div class="card">
														<div class="card-header" id="headingfour">
															<h5 class="card-title my-2">
																<a href="#" data-toggle="collapse" data-target="#collapseServices" aria-expanded="true" aria-controls="collapseone">
																	SKU Size
																</a>
															</h5>
														</div>
														<div id="collapseServices1" class="collapse" aria-labelledby="headingFour" data-parent="#accordionPackages1">
															<div class="card-body" id="Products">
															</div>
														</div>
													</div>
												</div>
												<!----Accordion End------>
												
												<!--Modal Section for packages-->
												<!-- <div id="ModalChooseServiceFromPackage" tabindex="-1" role="dialog" aria-hidden="true" class="modal">
													<div role="document" class="modal-dialog modal-md">
														<div class="modal-content">
															<div class="modal-header">
																<h5 class="modal-title text-white">Service Details</h5>
																<button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true" class="text-white">×</span></button>
															</div>
															<div class="modal-body m-3">
																<form action="#" method="POST" id="PackageRedemption">
																	<div class="form-row">
																		<div class="form-group col-md-4">
																			<label class="form-label">Service</label>
																			<input type="text" class="form-control" name="service_name" readonly>
																		</div>
																		<div class="form-group col-md-4">
																			<label class="form-label">Quantity Left</label>
																			<input type="number" class="form-control" name="service_quantity" readonly>
																		</div>
																		<div class="form-group col-md-4">
																			<label class="form-label">Redeem Quantity</label>
																			<input type="number" placeholder="Enter Quantity" class="form-control" name="service_quantity_redeemable" min="1">
																		</div>
																	</div>
																	<div class="form-row">
																		<div class="form-group col-md-4">
																			<label class="form-label">Discount %</label>
																			<input type="number" class="form-control" name="service_discount_percentage" min="0" readonly>
																		</div>
																		<div class="form-group col-md-4">
																			<label class="form-label">Rate (Rs.)</label>
																			<input type="number" class="form-control" name="service_price_inr" readonly>
																		</div>
																		<div class="form-group col-md-4">
																			<label class="form-label">Total Value</label>
																			<input type="number" class="form-control" name="service_total_value" readonly>
																		</div>
																	</div>
																	<div class="form-group">
																		<label class="form-label">Package Name</label>
																		<input type="text" name="salon_package_name" readonly class="form-control">
																	</div>
																	<div class="form-group">
																		<label>Expert Name</label>
																		<select class="form-control" name="service_expert_id">
																			<?php
																				foreach ($experts as $expert):
																			?>
																					<option value="<?=$expert['employee_id']?>"><?=$expert['employee_nick_name']?></option>
																			<?php		
																				endforeach;
																			?>
																		</select>
																	</div>
																	<div class="form-group">
							                      <input class="form-control" type="hidden" name="service_id" readonly="true">
							                    </div>
							                    <div class="form-group">
							                      <input class="form-control" type="hidden" name="customer_id" readonly="true">
							                    </div>
							                    <div class="form-group">
							                      <input class="form-control" type="hidden" name="service_gst_percentage" readonly="true">
							                    </div>
							                    <div class="form-group">
							                      <input class="form-control" type="hidden" name="service_est_time" readonly="true">
							                    </div>
							                    <div class="form-group">
							                      <input class="form-control" type="hidden" name="customer_package_profile_id" readonly="true">
							                    </div>
																	<button type="submit" class="btn btn-primary">Submit</button>
																</form>
																<div class="alert alert-dismissible feedback mt-0 mb-0" role="alert">
																	<button type="button" class="close" data-dismiss="alert" aria-label="Close">
																		<span aria-hidden="true">&times;</span>
											            </button>
																	<div class="alert-message"></div>
																</div>
															</div>
														</div>
													</div>
												</div>  -->
												<!--Modal Section Ends-->
											<?php
												}
											?>
                    </div>
										<!--  -->
										<!-- Coupon  tab-->
										<div class="tab-pane" id="tab-5" role="tabpanel">
											<?php
												if(!isset($coupon) || empty($coupon)){

											?>	
												<div class="alert alert-info alert-outline alert-dismissible" role="alert">
													<button type="button" class="close" data-dismiss="alert" aria-label="Close">
							            	<span aria-hidden="true">×</span>
							          	</button>
													<div class="alert-icon">
														<i class="far fa-fw fa-bell"></i>
													</div>
													<div class="alert-message">
														<strong>No Coupon Found !</strong>
													</div>
												</div>
											<?php		
												}
												else{	
											?>
												<!----Accordion Start---->											
												<div class="row" style="margin-right:10px;">
		
													<?php if($coupon[0]['service_monthly_discount'] > 0){?>														
													<div class="col-md-3 col-sm-6">
														<a class="ProvideCouponDetails" coupon_id="<?=$coupon[0]['coupan_id']?>" redemption_date="<?=$coupon[0]['redemption_date']?>" customer_id="<?=$coupon[0]['customer_id']?>" monthly_discount="<?=$coupon[0]['service_monthly_discount']?>" birthday_discount="<?=$coupon[0]['birthday_discount']?>" anniversary_discount="<?=$coupon[0]['anni_discount']?>"  >
															<div class="card coupon-card">
																<div class="card-body" style="text-align: center;padding:.25rem!important;">
																	<h6 class="card-text text-white"><?=$coupon[0]['coupan_code']?></h6>
																	<small class="card-text text-white"><i class="fa fa-rupee-sign"></i><?=$coupon[0]['service_monthly_discount']?></small>
																	<?php if($coupon[0]['redemption_date']!="2019-01-01"){?>
																	<small class="card-text text-white"><?=$coupon[0]['redemption_date']?></small>
																	<?php }else{?>
																		-----
																	<?php }?>
																</div>
															</div>
														</a>		
													</div>
													<?php } if($coupon[1]['birthday_discount']>0 && substr($individual_customer['customer_dob'],5,2)==date('m')){?>
														<div class="col-md-3 col-sm-6">
														<a class="ProvideCouponDetails" coupon_id="<?=$coupon[1]['coupan_id']?>" redemption_date="<?=$coupon[1]['redemption_date']?>" customer_id="<?=$coupon[1]['customer_id']?>" monthly_discount="<?=$coupon[1]['service_monthly_discount']?>" birthday_discount="<?=$coupon[1]['birthday_discount']?>" anniversary_discount="<?=$coupon[1]['anni_discount']?>"  >
															<div class="card coupon-card1">
																<div class="card-body" style="text-align: center;padding:.25rem!important;">
																	<h6 class="card-text text-white"><?=$coupon[1]['coupan_code']?></h6>
																	<small class="card-text text-white"><i class="fa fa-rupee-sign"></i><?=$coupon[1]['birthday_discount']?></small>
																	
																	<?php if($coupon[1]['redemption_date']!="2019-01-01"){?>
																	<small class="card-text text-white"><?=$coupon[1]['redemption_date']?></small>
																	<?php }else{?>
																		-----
																	<?php }?>
																</div>
															</div>
														</a>		
													</div>
													<?php } if(substr($individual_customer['customer_doa'],5,2)==date('m')){?>
														<div class="col-md-3 col-sm-6">
														<a class="ProvideCouponDetails" coupon_id="<?=$coupon[2]['coupan_id']?>" redemption_date="<?=$coupon[2]['redemption_date']?>" customer_id="<?=$coupon[2]['customer_id']?>" monthly_discount="<?=$coupon[2]['service_monthly_discount']?>" birthday_discount="<?=$coupon[2]['birthday_discount']?>" anniversary_discount="<?=$coupon[2]['anni_discount']?>"  >
															<div class="card coupon-card2">
																<div class="card-body" style="text-align: center;padding:.25rem!important;">
																	<h6 class="card-text text-white"><?=$coupon[2]['coupan_code']?></h6>
																	<small class="card-text text-white"><i class="fa fa-rupee-sign"></i><?=$coupon[2]['anni_discount']?></small>
																	
																	<?php if($coupon[2]['redemption_date']!="2019-01-01"){?>
																	<small class="card-text text-white"><?=$coupon[2]['redemption_date']?></small>
																	<?php }else{?>
																		-----
																	<?php }?>
																</div>
															</div>
														</a>		
													</div>
													<?php }?>
												</div>															
											<?php
												}
											?>
                    </div>
										<!--  -->
									</div>
									<!-- modal area -->
									<div class="modal" id="ModalServiceDetails" tabindex="-1" role="dialog" aria-hidden="true">
										<div role="document" class="modal-dialog modal-md">
											<div class="modal-content">
												<div class="modal-header">
													<h5 class="modal-title text-white">Service/Product Details</h5>
													<button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true" class="text-white">&times;</span></button>
												</div>
												<div class="modal-body m-3">
													<form action="#" method="POST" id="ServiceDetails">
														<div class="form-row">
															<div class="form-group col-md-3">
																<label class="form-label">Service/Product</label>
																<input type="text" class="form-control" name="service_name" readonly>
															</div>
															<div class="form-group col-md-2">
																<label class="form-label">Quantity</label>
																<input type="number" placeholder="Enter Quantity" class="form-control" name="service_quantity" min="1">
															</div>
															<div class="form-group col-md-3">
																<label class="form-label">Rate (Rs.)</label>
																<input type="number" class="form-control" name="service_price_inr" readonly>
															</div>
															<div class="form-group col-md-3">
																<label class="form-label">Total Value</label>
																<input type="number" class="form-control" name="service_total_value" readonly>
															</div>														
														</div>
														<div class="form-row">
															<div class="form-group col-md-4">
																<label class="form-label">Discount Absolute</label>
																<input type="number" class="form-control" name="service_discount_absolute" placeholder="Only absolute value" value="0">
															</div>
															<div class="form-group col-md-4">
																<label class="form-label">Discount %</label>
																<input type="number" class="form-control" name="service_discount_percentage" placeholder="Only % value" value="0">
															</div>															
															<div class="form-group col-md-4">
																<label>Expert Name</label>
																<select class="form-control" name="service_expert_id">
																	<?php
																		foreach ($experts as $expert):
																	?>
																			<option value="<?=$expert['employee_id']?>"><?=$expert['employee_nick_name']?></option>
																	<?php		
																		endforeach;
																	?>
																</select>
															</div>
														</div>
														<div class="form-row">
															<div class="form-group">
																<label>Remarks</label>
																<textarea class="form-control" name="service_remarks"></textarea>
															</div>
														</div>
														<div class="form-group">
															<input class="form-control" type="hidden" name="service_id" readonly="true">
														</div>
														<div class="form-group">
															<input class="form-control" type="hidden" name="customer_id" readonly="true">
														</div>
														<div class="form-group">
															<input class="form-control" type="hidden" name="service_gst_percentage" readonly="true">
														</div>
														<div class="form-group">
															<input class="form-control" type="hidden" name="service_est_time" readonly="true">
														</div>
														<button type="submit" class="btn btn-primary" id="ServiceDetailsButton">Submit</button>
													</form>
													<div class="alert alert-dismissible feedback mt-0 mb-0" role="alert">
														<button type="button" class="close" data-dismiss="alert" aria-label="Close">
															<span aria-hidden="true">&times;</span>
														</button>
															<div class="alert-message"></div>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="modal" id="ModalDiscountDetails" tabindex="-1" role="dialog" aria-hidden="true">
										<div role="document" class="modal-dialog modal-md">
											<div class="modal-content">
												<div class="modal-header">
													<h5 class="modal-title text-white">Discount Details</h5>
													<button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true" class="text-white">&times;</span></button>
												</div>
												<div class="modal-body m-3">
													<form action="#" method="POST" id="DiscountDetails">
														<div class="form-row">
															<div class="form-group col-md-3">
																<label class="form-label">Service/Product</label>
																<input type="text" class="form-control" name="service_name" readonly>
															</div>
															<div class="form-group col-md-3">
																<label class="form-label">Quantity</label>
																<input type="number" placeholder="Enter Quantity" class="form-control" name="service_quantity" min="1" readonly>
															</div>
															<div class="form-group col-md-3">
																<label class="form-label">Rate (Rs.)</label>
																<input type="number" class="form-control" name="service_price_inr" readonly>
															</div>
															<div class="form-group col-md-3">
																<label class="form-label">Total Value</label>
																<input type="number" class="form-control" name="service_total_value" readonly>
															</div>
														
														</div>
														<div class="form-row">
															<div class="form-group col-md-3">
																<label class="form-label">Monthly Discount</label>
																<input type="number" class="form-control" name="service_monthly_discount"  value="0" readonly>
															</div>
															<div class="form-group col-md-3">
																<label class="form-label">Birthday Discount</label>
																<input type="number" class="form-control" name="birthday_discount" value="0" readonly>
															</div>
															<div class="form-group col-md-3">
																<label class="form-label">Anniversary Discount</label>
																<input type="number" class="form-control" name="anni_discount" value="0" readonly>
															</div>
															<div class="form-group col-md-3">
															<label>Expert Name</label>
															<select class="form-control" name="service_expert_id">
																// <?php
																// 	foreach ($experts as $expert):
																// ?>
																		<option value="<?=$cashier_details['employee_id']?>"><?=$cashier_details['employee_nick_name']?></option>
																// <?php		
																// 	endforeach;
																// ?>
															</select>
														</div>
														</div>
														
														<div class="form-group">
															<input class="form-control" type="hidden" name="service_id" readonly="true">
														</div>
														<div class="form-group">
															<input class="form-control" type="hidden" name="customer_id" readonly="true">
														</div>
														<div class="form-group">
															<input class="form-control" type="hidden" name="service_gst_percentage" readonly="true">
															<input class="form-control" type="hidden" name="service_discount_percentage" readonly="true" value="0">
															<input class="form-control" type="hidden" name="service_discount_absolute" readonly="true" value="0">
															<input  type="hidden" name="coupon_id">
														</div>
														<div class="form-group">
															<input class="form-control" type="hidden" name="service_est_time" readonly="true">
														</div>
														<button type="submit" class="btn btn-primary" id="DiscountDetailsButton">Submit</button>
													</form>
													<div class="alert alert-dismissible feedback mt-0 mb-0" role="alert">
														<button type="button" class="close" data-dismiss="alert" aria-label="Close">
															<span aria-hidden="true">&times;</span>
														</button>
															<div class="alert-message"></div>
													</div>
												</div>
											</div>
										</div>
									</div>
									<!--  -->
								</div>
								  

                  	           <div class="row" style="margin-left:0px;">										
										<div class="card" style="padding:1.25rem;">
											<div class="card-header">
													<h5>Quick Bill</h5>
											</div>
											<?php
											$cust_id=$individual_customer['customer_id'];
								// 			print_r($cust_id);
								// 			print_r($_SESSION['recommended_ser']);
											if($_SESSION['recommended_ser'][$cust_id] != 0){
												$recommended=$_SESSION['recommended_ser'][$cust_id];
											?>
												<div class="card-body"  >	
													<div class="row">							
														<?php		
														if(isset($recommended['res_arr'])){
														    $recommended=$recommended['res_arr'];
														}
														if ($recommended != 0) {
														  //  print_r($recommended);
															foreach ($recommended as $data) { ?>
																<div class="col-md-2 col-sm-4" style="margin-right:0px">
																	<a class="ProvideServiceDetails" service-id="<?= $data['service_id'] ?>" service-name="<?= $data['service_name'] ?>" service-price-inr="<?= $data['service_price_inr'] ?>" service-gst-percentage="<?= $data['service_gst_percentage'] ?>" service-est-time="<?= $data['service_est_time'] ?>" title="<?= $data['service_name'].', '.round($data['service_price_inr'])?>">
																		<div class="card customized-category-card border-dark">
																			<div class="card-body" style="text-align: center;padding:.5rem!important;background-color:white;border-radius:5px;">
																				<p class="card-text" style="color: black;" ><?= $data['service_name'] ?></p>
																			</div>
																		</div>
																	</a>
																</div>
														<?php
															}
														}
														?>
													</div>
												</div>
											<?php
											}
											else{
											?>
												<div class="card-body"  >	
													<div class="row">							
														
														<?php
																			
														if ($recommended != 0) {
															foreach ($recommended as $data) { ?>
																<div class="col-md-2 col-sm-4">
																	<a class="ProvideServiceDetails" service-id="<?= $data['service_id'] ?>" service-name="<?= $data['service_name'] ?>" service-price-inr="<?= $data['service_price_inr'] ?>" service-gst-percentage="<?= $data['service_gst_percentage'] ?>" service-est-time="<?= $data['service_est_time'] ?>" title="<?= $data['service_name'].',  '.round($data['service_price_inr']) ?>">
																		<div class="card customized-category-card border-dark" style="width:100%">
																			<div class="card-body" style="text-align: center;padding:.5rem!important;background-color:white;  border-radius:5px;">
																				<p class="card-text" style="color: black;" ><?= $data['service_name'] ?></p>
																			</div>
																		</div>
																	</a>
																</div>
														<?php
															}
														}
														?>
													</div>
												</div>
											<?php
											}
											?>
											
										</div>								
				  				</div>
				  <div class="col-md-2 col-sm-4"></div>
                  <br>
							</div>
							<div class="col-md-5">
								<?php
									if(!isset($cart) || empty($cart)){
								?>
								
									<div class="alert alert-danger alert-outline alert-dismissible" role="alert">
										<button type="button" class="close" data-dismiss="alert" aria-label="Close">
				            	<span aria-hidden="true">×</span>
				          	</button>
										<div class="alert-icon">
											<i class="far fa-fw fa-bell"></i>
										</div>
										<div class="alert-message">
											<strong>Hello there!</strong> Cart is empty!
										</div>
									</div>
								
								<?php
									}
									else{
								?>
								<div class="row" >
									<div class="col-md-12">
										<div class="card" id="billingboard">
											<div class="card-body" style="padding:0px!important">	
												<table class="table table-hover fixed_headers table-sm font-weight-bold">
													<thead>
														<tr>
															<th>Service</th>
															<th>MRP</th>
															<th>Qty</th>
															<th>Disc</th>															
															<!-- <th>GST</th> -->
															<th><i class="fa fa-plus"></th>
															<th>Total</th>
															<th>Expert</th>
															<th>Actions</th>
														</tr>
													</thead>
													<tbody>
														<?php
															$total_service_value=0;
															$total_base_price=0;
															$total_gst=0;
															$total_discount=0;
															$new_gst=0;
															$discount=0;	
															$total_value=0;	
															$gross_price=0;
															// print_r($cart);													
															foreach ($cart as $item):															
																if($item['service_discount_percentage']>0){
																	$discount=$item['service_discount_percentage'];
																	$price= $item['service_price_inr'];
																	$gst=$item['service_price_inr']*$item['service_gst_percentage']/100;
																	$mrp=$price+$gst;
																	$total_value=$mrp*$item['service_quantity'];
																	$gross_price+=$total_value+$item['service_add_on_price'];
																	if($item['service_add_on_price'] >0){
																		$total_value+=$item['service_add_on_price'];

																	}
																	$discount=$total_value*$discount/100;																
																	$total_value-=$discount;
																	$gst=($total_value*($item['service_gst_percentage']/100)/(1+($item['service_gst_percentage']/100)));														
																													
																}else{
																	$discount=$item['service_discount_absolute'];
																	$price= $item['service_price_inr'];
																	$gst=$item['service_price_inr']*$item['service_gst_percentage']/100;
																	$mrp=$price+$gst;
																	$total_value=$mrp*$item['service_quantity'];
																	$gross_price+=$total_value+$item['service_add_on_price'];
																	if($discount >0){
																		$total_value-=$discount;
																	}
																	if($item['service_add_on_price'] >0){
																		$total_value+=$item['service_add_on_price'];
																	}
																	$gst=($total_value*($item['service_gst_percentage']/100)/(1+($item['service_gst_percentage'])/100));
																
																	
																}
														?>
														<tr <?php if($item['customer_package_profile_id'] != -999){ echo 'style="background-color:#f7c4c4;"'; }?>>
															<td class="d-inline-block"><?=$item['service_name']?></td>
															
															<td><?php 	
															$total_base_price+=$total_value;
															
															echo abs(round($mrp));
														
															// echo round($item['service_price_inr']+($item['service_price_inr']*$item['service_gst_percentage'])/100);
															// echo round($total_price);
														
															?>															
															</td>
															<td class="text-center"><?=$item['service_quantity']?></td>
															<td class="text-center">
															<?php 
																echo round($discount);
																$total_discount+=$discount;
															?>
														</td>
														<!-- <td>
															<?php 
																echo abs(round($gst));
																$total_gst+=$gst;
															?>
														</td> -->
														<td class="text-center">
															<?php 
																echo abs(round($item['service_add_on_price']));
																// $total_gst+=$new_gst;
															?>
														</td>
														<td class="text-center">
															<?php 
																// echo round($new_gst+$new_base_price);
																echo round($total_value);
																$total_service_value+=$total_value;
															?>
														</td>																														
														<td class="text-center" >
                                  <select name="employee[]" class="form-control"  style="padding-left :0px;" onchange="update_expert_name(this.value,<?=$item['service_id']?>,<?=$item['customer_package_profile_id']?>,<?=$individual_customer['customer_id']?>)" service-id="<?=$item['service_id']?>" package-profile-id="<?=$item['customer_package_profile_id']?>" >
                                    <option value="<?=$expert['employee_id']?>" selected><?php $key = array_search($item['service_expert_id'], array_column($experts,'employee_id'));echo $experts[$key]['employee_nick_name'];?></option>
                                    <?php
                                        foreach ($experts as $expert):
                                      ?>
                                          <option value="<?=$expert['employee_id']?>"><?=$expert['employee_first_name']?></option>
                                      <?php   
                                        endforeach;
                                      ?>
                                  </select>
                                </td>
															<td class="table-action">
																<button type="button" class="btn btn-info btn-small cart-edit-btn" service-id="<?=$item['service_id']?>" service-name="<?=$item['service_name']?>" service-quantity="<?=$item['service_quantity']?>" discount-percentage="<?=$item['service_discount_percentage']?>" discount-absolute="<?=$item['service_discount_absolute']?>" service-price-inr="<?=$item['service_price_inr']?>" service_add_on_price="<?=$item['service_add_on_price']?>" service-total-value="<?=$item['service_total_value']?>" expert-name="<?=$item['service_expert_id']?>" gst-percentage="<?=$item['service_gst_percentage']?>" est-time="<?=$item['service_est_time']?>" package-profile-id="<?=$item['customer_package_profile_id']?>">
																	<i class="fa fa-pen" aria-hidden="true"></i>	
																</button>
																<button type="button" class="btn btn-danger btn-small cart-delete-btn" service-id="<?=$item['service_id']?>">
																<i class="fa fa-trash" aria-hidden="true"></i>
																</button>
															</td>	
														</tr>	
														<?php		
															endforeach;
														?>
													</tbody>
												</table>
												<?php
													}
												?>
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12">
										<div id="TotalSection">
											<div class="card">
												<div class="card-body">
												
													<!--------------MODAL Section for Payments---------------->

													<div id="ModalFullPayment" tabindex="-1" role="dialog" aria-hidden="true" class="modal">
														<div role="document" class="modal-dialog modal-md">
															<div class="modal-content">
																<div class="modal-header">
																	<h5 class="modal-title text-white">Full Payment</h5>
																	<button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true" class="text-white" >×</span></button>
																</div>
																<div class="modal-body m-3">
																	<form action="#" method="POST" id="FullPaymentInfo" onsubmit="return getInputValue(this,event);">
																	<div class="row">
																		<div class="form-group col-md-6">
																			<label class="form-label">Payment Type</label>
																			<select name="payment_type" class="form-control" Wallet-Balance="<?=$individual_customer['customer_virtual_wallet']?>" id="pay_type">
																				<option value="Cash">Cash</option>
																				<option value="Credit_Card">Credit Card</option>
																				<option value="Debit_Card">Debit Card</option>
																				<option value="Paytm">Paytm</option>
																				<option value="Phonepe">Phonepe</option>
																				<option value="Google_Pay">Google Pay</option>
																				<?php 
																				if($individual_customer['customer_virtual_wallet'] > 0){?>
																				<option value="Virtual_Wallet">Virtual Wallet  -- Balance : <?=$individual_customer['customer_virtual_wallet']?></option>
																				<?php
																				}
																				else{?>
																					<option value="Virtual_Wallet" disabled>Virtual Wallet --Insufficient Balance : <?=$individual_customer['customer_virtual_wallet']?></option>
																				<?php
																				}
																				?>
																				<!--  -->
																				<?php 
																				echo $actual_bill;
																				 if(!empty($rules))
																					{
																						if($rules['rule_type'] == 'Cashback Single Rule' || $rules['rule_type'] == 'Cashback Multiple Rule' || $rules['rule_type'] == 'Cashback LTV Rule')
																						{
																							if($individual_customer['customer_rewards'] >$actual_bill){?>
																								<option value="loyalty_wallet">Loyalty Wallet : <?=$individual_customer['customer_rewards']?> </option>
																								<?php
																							}
																							else{?>
																									<option value="loyalty_wallet" disabled>Loyalty Wallet : <?=$individual_customer['customer_rewards']?></option>
																								<?php
																							}
																						}
																				}
																			    ?>
																				<!--  -->
																			</select>
																		</div>
																		<div class="form-group col-md-6">
																			<label class="form-label">Amount Received</label>
																			<input type="number" class="form-control" name="total_amount_received"  id="amt_rc">
																		</div>
																	</div>
																	<div class="row">
																		<div class="form-group col-md-4">
																			<label class="form-label">Total Amount</label>
																			<input type="number" class="form-control" name="total_final_bill" readonly>
																		</div>
																		<div class="form-group col-md-4">
																			<label class="form-label">Balance to be paid back</label>
																			<input type="number" class="form-control" name="balance_to_be_paid_back" readonly>
																		</div>
																		<div class="form-group col-md-4">
																			<label class="form-label">Pending Amount</label>
																			<input type="number" class="form-control" name="pending_amount" readonly>
																		</div>
																	</div>
								                    <div class="form-group">
								                      <input class="form-control" type="hidden" name="customer_id" readonly="true">
								                    </div>
																		<button type="submit" class="btn btn-primary">Submit</button>
																	</form>
																	<div class="alert alert-dismissible feedback mt-0 mb-0" role="alert">
																		<button type="button" class="close" data-dismiss="alert" aria-label="Close">
																			<span aria-hidden="true">&times;</span>
												            </button>
																			<div class="alert-message"></div>
																	</div>
																</div>
															</div>
														</div>
													</div>

													<div id="ModalSplitPayment" tabindex="-1" role="dialog" aria-hidden="true" class="modal">
														<div role="document" class="modal-dialog modal-md">
															<div class="modal-content">
																<div class="modal-header">
																	<h5 class="modal-title text-white">Split Payment</h5>
																	<button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true" class="text-white" >×</span></button>
																</div>
																<div class="modal-body m-3">
																	<form action="#" method="POST" id="SplitPaymentInfo" >
																		<div class="form-row">	
																			<div class="form-group col-md-6">
																				<label class="form-label">Total Amount</label>
																				<input type="number" class="form-control" name="total_final_bill" readonly>
																			</div>
																			<div class="form-group col-md-6">
																				<label class="form-label">Amount Received</label>
																				<input type="number" class="form-control" name="total_amount_received" readonly>
																			</div>
																		</div>
																		<div class="form-row">
																			<div class="form-group col-md-6">
																				<label class="form-label">Balance to be paid back</label>
																				<input type="number" class="form-control" name="balance_to_be_paid_back" readonly>
																			</div>
																			<div class="form-group col-md-6">
																				<label class="form-label">Pending Amount</label>
																				<input type="number" class="form-control" name="total_pending_amount" readonly>
																			</div>
																		</div>
																		<div class="form-row">
																		<table class="table table-hover table-bordered" id="Split-Payment-Info-Table">
																			<tbody>
															       		<tr>
															        		<td>1</td>
															        		<td>
															        			<div class="form-group">
															        				<label class="form-label">Payment Mode</label>
																        			<select name="payment_type[]" class="form-control" id="split_pay_type" required>
																								<option value="" selected disabled>Select Mode</option>
																								<option value="Cash">Cash</option>
																								<option value="Credit_Card">Credit Card</option>
																								<option value="Debit_Card">Debit Card</option>
																								<option value="Paytm">Paytm</option>
																								<option value="Phonepe">Phonepe</option>
																								<option value="Google_Pay">Google Pay</option>
																								<option value="Virtual_Wallet">Virtual Wallet -- Balance : <?=$individual_customer['customer_virtual_wallet']?></option>
																								<option value="loyalty_wallet">Loyalty Wallet: <?=$individual_customer['customer_rewards']?></option>
																							</select>
															        			</div>
															        		</td>
															        		<td>
															        			<div class="form-group">
																							<label class="form-label">Amount Received</label>
																							<input type="number" placeholder="Amount in INR" class="form-control" name="amount_received[]"  onchange="return validateSplitPayment(this,event);" required>
																						</div>
															        		</td>
															       		</tr>
															       	</tbody>
															      </table>
																		</div>
								                    <div class="form-group">
								                      <input class="form-control" type="hidden" name="customer_id" readonly="true">
								                    </div>
								                    <button type="button" class="btn btn-success" id="AddRow">Add <i class="fa fa-plus" aria-hidden="true"></i></button>
		    														<button type="button" class="btn btn-danger" id="DeleteRow">Delete <i class="fa fa-trash" aria-hidden="true"></i></button>
																		<button type="submit" class="btn btn-primary">Submit</button>
																	</form>
																	<div class="alert alert-dismissible feedback mt-0 mb-0" role="alert">
																		<button type="button" class="close" data-dismiss="alert" aria-label="Close">
																			<span aria-hidden="true">&times;</span>
												            </button>
																			<div class="alert-message"></div>
																	</div>
																</div>
															</div>
														</div>
													</div>

													<div id="ModalApplyExtraDiscount" tabindex="-1" role="dialog" aria-hidden="true" class="modal">
														<div role="document" class="modal-dialog modal-md">
															<div class="modal-content">
																<div class="modal-header">
																	<h5 class="modal-title text-white">Extra Discount</h5>
																	<button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
																</div>
																<div class="modal-body m-3">
																	<form action="#" method="POST" id="ApplyExtraDiscount">
																		<div class="form-group">
																			<label class="form-label">Monthly Discount</label>
																			<input type="number" class="form-control" name="cart_discount_percentage" placeholder="Enter % value only" min="0">
																		</div>
																		<div class="form-group">
																			<label class="form-label">Birthday Discount</label>
																			<input type="number" class="form-control" name="cart_discount_absolute" placeholder="Enter absolute value only" min="0">
																		</div>
																		<div class="form-group">
																			<label class="form-label"> Anniversary Discount</label>
																			<input type="number" class="form-control" name="cart_discount_absolute" placeholder="Enter absolute value only" min="0">
																		</div>
								                    <div class="form-group">
								                      <input class="form-control" type="hidden" name="customer_id" readonly="true" value="<?=$individual_customer['customer_id']?>">
								                    </div>
																		<button type="submit" class="btn btn-primary">Submit</button>	
																	</form>
																	<div class="alert alert-dismissible feedback mt-0 mb-0" role="alert">
																		<button type="button" class="close" data-dismiss="alert" aria-label="Close">
																			<span aria-hidden="true">&times;</span>
												            </button>
																			<div class="alert-message"></div>
																	</div>
																</div>
															</div>
														</div>
													</div>
													
													<div id="ModalApplyCode" tabindex="-1" role="dialog" aria-hidden="true" class="modal">
														<div role="document" class="modal-dialog modal-md">
															<div class="modal-content">
																<div class="modal-header">
																	<h5 class="modal-title text-white">Deals & Discount</h5>
																	<button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
																</div>
																<div class="modal-body m-3">
																	<form action="#" method="POST" id="ApplyCode">
																		<div class="form-group">
																			<label class="form-label">Enter Coupon Code</label>
																			<input type="text" class="form-control" name="coupon_code" placeholder="Enter Coupon Code">
																		</div>
								                    <div class="form-group">
																			<input class="form-control" type="hidden" name="customer_id" readonly="true" value="<?=$individual_customer['customer_id']?>">
																			<input type="hidden" name="total_bill" value="<?=$total_service_value?>" readonly="true" />
								                    </div>
																		<button type="submit" class="btn btn-primary">Submit</button>	
																	</form>
																	<div class="alert alert-dismissible feedback mt-0 mb-0" role="alert">
																		<button type="button" class="close" data-dismiss="alert" aria-label="Close">
																			<span aria-hidden="true">&times;</span>
												            </button>
																			<div class="alert-message"></div>
																	</div>
																</div>
															</div>
														</div>
													</div>
													<div id="ModalApplyRemarks" tabindex="-1" role="dialog" aria-hidden="true" class="modal">
														<div role="document" class="modal-dialog modal-md">
															<div class="modal-content">
																<div class="modal-header">
																	<h5 class="modal-title text-white">Transaction Over All Remarks</h5>
																	<button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
																</div>
																<div class="modal-body m-3">
																	<form action="#" method="POST" id="Apply_remarks">
																		<div class="form-group">
																			<label class="form-label">Enter Remarks</label>
																			<textarea class="form-control" name="txn_remarks" placeholder="Transaction Remarks"></textarea>
																		</div>
								                    <div class="form-group">
																			<input class="form-control" type="hidden" name="customer_id" readonly="true" value="<?=$individual_customer['customer_id']?>">
																			<input type="hidden" name="total_bill" value="<?=$total_service_value?>" readonly="true" />
								                    </div>
																		<button type="submit" class="btn btn-primary">Submit</button>	
																	</form>
																	<div class="alert alert-dismissible feedback mt-0 mb-0" role="alert">
																		<button type="button" class="close" data-dismiss="alert" aria-label="Close">
																			<span aria-hidden="true">&times;</span>
												            </button>
																			<div class="alert-message"></div>
																	</div>
																</div>
															</div>
														</div>
													</div>
												<!---------MODAL Section Ends---------->
												<?php
													if(isset($cart) && !empty($cart)):
												?>
												<div class="mb-3">
													<table class="table table-hover table-sm" style="max-height: 200px;">
														<tbody>
														<tr>
																<td colspan="2">
												<label class="custom-control custom-checkbox">
								              		<input type="checkbox" class="custom-control-input" checked="true" id="cashback">
								              		<span class="custom-control-label">Credit Rewards</span>
																</label>																

																<label style="display:none;" class="custom-control custom-checkbox">
								              		<input type="checkbox" class="custom-control-input" checked="true" id="SendSMS">
								              		<span class="custom-control-label">Send SMS</span>
																</label>
																</td>
																<td colspan="2">
															<!--	<label style="display:none;" class="custom-control custom-checkbox">
								              		<input type="checkbox" class="custom-control-input" checked="true" id="cashback">
								              		<span class="custom-control-label">Credit Rewards</span>
																</label>-->
																</td>
															</tr>
															<tr>
															<tr>
																<td>Total Bill</td>
																<td><i class="fas fa-fw fa-rupee-sign" aria-hidden="true"></i>
																<?php
																	// $total_bill_before = 0;
																	// $discount_given = 0;
																	// $total_discount = 0;
																	// $actual_bill = 0;
																	// $total_gst=0;
																	// $discount_absolute=0;
																	// $discount_percentage=0;
																	// $wallet_balance_used = 0;
																	$cart_json = json_encode($cart);
																	// $payment_info_json = "";
																	// foreach ($cart as $item) {
																	// 	$total_bill_before += ((int)$item['service_price_inr'] * (int) $item['service_quantity']);

																		

																		// $discount_given += ((int)$item['service_price_inr'] * (int) $item['service_quantity']) - (int)$item['service_total_value'];
																		// $discount_absolute+=$item['service_discount_absolute'];
																		// $discount+=$item['service_discount_absolute'];


																		// $discount+=$item['service_total_value']*$item['service_discount_percentage']/100;

																		// $total_gst+=($item['service_price_inr']*$item['service_gst_percentage']/100);
																	// }
																	// $total_discount=$discount;
																	// $actual_bill = $total_bill_before - $total_discount;
																	// // $actual_bill = $total_bill_before - $discount_given;

																	if(isset($payment['full_payment_info']) || isset($payment['split_payment_info'])){
																		
																		if(!empty($payment['full_payment_info'])){
																			$payment_info_json = json_encode($payment['full_payment_info']['payment_type']);
																		}

																		if(!empty($payment['split_payment_info'])){
																			$payment_info_json = json_encode($payment['split_payment_info']['multiple_payments']);
																		}
																	}

																	// if(isset($payment['discount_info']['cart_discount_absolute'])){
																	// 	if($payment['discount_info']['cart_discount_percentage'] == 0){
																	// 		$discount_given += (int)$payment['discount_info']['cart_discount_absolute'];
																			
																	// 	}
																	// }

																	// if(isset($payment['discount_info']['cart_discount_percentage'])){
																	// 	if($payment['discount_info']['cart_discount_absolute'] == 0){
																	// 		$discount_given += round((((int)$payment['discount_info']['cart_discount_percentage'])/100) * $actual_bill);
																	// 	}
																	// }	

																	// $actual_bill = $total_bill_before - $discount_given;
																	
																	// echo $total_bill_before;
																?>
																<?php 
																// echo round($total_base_price);
																echo round($gross_price);
																?>
																</td>
																<td>Final Amount</td>
																<td><i class="fas fa-fw fa-rupee-sign" aria-hidden="true"></i>
																	<?= round($actual_bill=$total_service_value)?>
																</td>
															</tr>
															<tr>
																<td>SGST</td>
																<td><i class="fas fa-fw fa-rupee-sign" aria-hidden="true"></i>
																	<?php echo abs(round($total_gst/2));?>
																</td>
																<td>Amount Received</td>
																<td>
																<i class="fas fa-fw fa-rupee-sign" aria-hidden="true"></i>
																	<?= round($payment['split_payment_info']['total_amount_received'])?>
																</td>
															</tr>
															<tr>
																<td>CGST</td>
																<td><i class="fas fa-fw fa-rupee-sign" aria-hidden="true"></i>
																<?php echo abs(round($total_gst/2));?>
																</td>
																<td>Paid Back</td>
																<td>
																<i class="fas fa-fw fa-rupee-sign" aria-hidden="true"></i>
																	<?= round($payment['split_payment_info']['balance_to_be_paid_back'])?>
																</td>
															</tr>
															<tr>
																<td>Discount</td>
																<td><i class="fas fa-fw fa-rupee-sign" aria-hidden="true"></i>
																	<?=round($total_discount)?>
																</td>
																<td>Due Amount</td>
																<td>
																<i class="fas fa-fw fa-rupee-sign" aria-hidden="true"></i>
																	<?= round($payment['split_payment_info']['total_pending_amount'])?>
																</td>
															</tr>
															<tr>
																<td><strong>Tender Details </strong></td>
																<td colspan="3">
																	<?php 
																	foreach($payment['split_payment_info']['multiple_payments'] as $key=>$val){
																		echo $val['payment_type']." : ".$val['amount_received']." ";
																	}
																	?>
																</td>
															</tr>
														</tbody>
													</table>
												</div>
												<div class="mb-3 ml-2">
													<?php if(array_search('Deals&Discount', $business_admin_packages) !== false){?>
													<button class="btn btn-info" data-toggle="modal" data-target="#ModalApplyCode">Apply Code</button>
													<?php }?>
													<!-- <a href="<?=base_url()?>Cashier/JobOrder/<?=$individual_customer['customer_id']?>/" class="btn btn-warning" target="_blank">Job Order</a> -->
													<a href="<?=base_url()?>Cashier/PrintBill/<?=$individual_customer['customer_id']?>/" class="btn btn-success" id="Print-Bill" target="_blank">Print Bill</a>
													<div class="btn-group">
														<?php if(!empty($payment['full_payment_info']) || !empty($payment['split_payment_info'])){ ?>
														<button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" disabled>Pay</button>
														<div class="dropdown-menu">
															<a class="dropdown-item full-payment-btn" href="#">Full Payment</a>
															<a class="dropdown-item split-payment-btn" href="#">Split Payment</a>
														</div>
														<?php
															}
															else{
														?>
														<button type="button" class="btn btn-danger split-payment-btn">Pay</button>
													  <?php } ?>
													</div>
													<?php if(!empty($payment['full_payment_info']) || !empty($payment['split_payment_info'])){?>
													<button class="btn  btn-danger" id="Settle-Final-Order" >Settle Order</button>
													<?php 
														}
														else{
													?>
													<button class="btn  btn-primary" disabled>Settle Order</button>
													<?php
														}
													?>
													<button class="btn btn-info" data-toggle="modal" data-target="#ModalApplyRemarks">Remarks</button>
												</div>
												<?php
													endif;
												?>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
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

    
		$(".smartwizard-arrows-primary").smartWizard({
			theme: "arrows",
			showStepURLhash: false
		});
        
        // Clear Due Amont
		$(document).on('click',".ClearPendingAmount",function(event){
    	event.preventDefault();
    	$(this).blur();
    	$("#ClearPendingAmountForm input[name=due_amount]").val($(this).attr('pending_amount'));
    	$("#ClearPendingAmountForm input[name=balance_left]").val($(this).attr('pending_amount'));
    	$("#ClearPendingAmountForm input[name=customer_id]").val($(this).attr('customer_id'));
    	$("#ModalClearDues").modal('show');
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
        
		$(document).on('click','.ShowSubCategories',function(event){
			event.preventDefault();
			$(this).blur();
			var parameters = {category_id : $(this).attr('category-id')};
			$.getJSON("<?=base_url()?>Cashier/GetSubCategoriesByCatId",parameters)
      .done(function(data, textStatus, jqXHR) {
    		var str = "<div class='row' style='margin-right:10px;'>";
    		for(var i=0;i<data.length;i++){
    			
    			str += "<div class=\"col-md-2 col-sm-4\">\
										<a class=\"ShowServices\" sub-category-id=\""+data[i].sub_category_id+"\">\
											<div class=\"card customized-category-card\">\
												<div class=\"card-body\" style=\"text-align: center;padding:.25rem!important;background-color:#eeb211;border-radius:5px;\">\
													<p class=\"card-text\">"+data[i].sub_category_name+"</p>\
												</div>\
											</div>\
										</a>\
									</div>";
    		}
    		
    		str += "</div>";
    		$("#Sub-Categories-Data").html("").html(str);
     		$("#collapseTwo").collapse('show');
    	})
    	.fail(function(jqXHR, textStatus, errorThrown) {
        console.log(errorThrown.toString());
   		});	
		});

		//Show sub categories for  product
			//Show sub categories for  product
		$(document).on('click','.ShowProductSubCategories',function(event){
					event.preventDefault();
					$(this).blur();
					var parameters = {category_id : $(this).attr('category-id')};
					$.getJSON("<?=base_url()?>Cashier/GetSubCategoriesByCatId",parameters)
					.done(function(data, textStatus, jqXHR) {
					var str = "<div class='row' style='margin-right:10px;'>";
					for(var i=0;i<data.length;i++){    			
						str += "<div class=\"col-md-2 col-sm-4\">\
										<a class=\"ShowProductServices\" sub-category-id=\""+data[i].sub_category_id+"\">\
											<div class=\"card customized-category-card\">\
												<div class=\"card-body\" style=\"text-align: center;padding:.25rem!important;background-color:#eeb211;border-radius:5px;\">\
													<p class=\"card-text\">"+data[i].sub_category_name+"</p>\
												</div>\
											</div>\
										</a>\
									</div>";
					}    		
					str += "</div>";
					$("#Otc_Sub_Categories").html("").html(str);
					$("#collapseSubCategory").collapse('show');
    		})
				.fail(function(jqXHR, textStatus, errorThrown) {
				console.log(errorThrown.toString());
   			});	
		});


		//Show services for  product
		$(document).on('click','.ShowProductServices',function(event){
			event.preventDefault();
			$(this).blur();
			var parameters = {sub_category_id : $(this).attr('sub-category-id')};
			$.getJSON("<?=base_url()?>Cashier/GetServicesBySubCatIdOtc",parameters)
     		 .done(function(data, textStatus, jqXHR) {
    		var str = "<div class='row' style='margin-right:10px;'>";
    		for(var i=0;i<data.length;i++){
					var mrp=Math.round(Number(data[i].service_price_inr)+Number(data[i].service_price_inr)*Number(data[i].service_gst_percentage)*(.01));
    			str += "<div class=\"col-md-2 col-sm-4\">\
										<a class=\"ProvideService\" service-id=\""+data[i].service_id+"\" service-name=\""+data[i].service_name+"\" service-price-inr=\""+data[i].service_price_inr+"\" service-gst-percentage=\""+data[i].service_gst_percentage+"\" service-est-time=\""+data[i].service_est_time+"\" title=\""+data[i].service_name+","+mrp+"\">\
											<div class=\"card customized-category-card\">\
												<div class=\"card-body\" style=\"text-align: center;padding:.25rem!important;background-color:#009925;border-radius:5px;\">\
													<p class=\"card-text\">"+data[i].service_name+"<br> Size:"+data[i].qty_per_item+"</p>\
												</div>\
											</div>\
										</a>\
									</div>";
    		}
    		
				str += "</div>";
				$("#Product_Services").html("").html(str);
				$("#collapseServices").collapse('show');
    		})
			.fail(function(jqXHR, textStatus, errorThrown) {
			console.log(errorThrown.toString());
   			});	
		});

		//show product details
		$(document).on('click','.ProvideService',function(event){
			// alert("hii");
			event.preventDefault();
			$(this).blur();
			
			var parameters = {service_id : $(this).attr('service-id'),service_name:$(this).attr('service-name')

			};
			$.getJSON("<?=base_url()?>Cashier/GetServicesByName",parameters)
     		 .done(function(data, textStatus, jqXHR) {
    		var str = "<div class='row' style='margin-right:10px;'>";
    		for(var i=0;i<data.length;i++){
					var mrp=Math.round(Number(data[i].service_price_inr)+Number(data[i].service_price_inr)*Number(data[i].service_gst_percentage)*(.01));
    			str += "<div class=\"col-md-2 col-sm-4\">\
										<a class=\"ProvideServiceDetails\" service-id=\""+data[i].service_id+"\" service-name=\""+data[i].service_name+"\" service-price-inr=\""+data[i].service_price_inr+"\" service-gst-percentage=\""+data[i].service_gst_percentage+"\" service-est-time=\""+data[i].service_est_time+"\" title=\""+data[i].service_name+","+mrp+"\">\
											<div class=\"card customized-category-card\">\
												<div class=\"card-body\" style=\"text-align: center;padding:.25rem!important;background-color:#009925;border-radius:5px;\">\
													<p class=\"card-text\">"+data[i].qty_per_item+" "+data[i].service_unit+"</p>\
												</div>\
											</div>\
										</a>\
									</div>";
    		}
    		
				str += "</div>";
				$("#Products").html("").html(str);
				$("#collapseServices1").collapse('show');
    		})
			.fail(function(jqXHR, textStatus, errorThrown) {
			console.log(errorThrown.toString());
   			});	
		});
		$(document).on('click','.ShowServices',function(event){
			event.preventDefault();
			$(this).blur();
			var parameters = {sub_category_id : $(this).attr('sub-category-id')};
			$.getJSON("<?=base_url()?>Cashier/GetServicesBySubCatId",parameters)
      .done(function(data, textStatus, jqXHR) {
    		var str = "<div class='row' style='margin-right:10px;'>";
    		for(var i=0;i<data.length;i++){
    			var mrp=Math.round(Number(data[i].service_price_inr)+Number(data[i].service_price_inr)*Number(data[i].service_gst_percentage)*(.01));
    			str += "<div class=\"col-md-2 col-sm-4\">\
										<a class=\"ProvideServiceDetails\" service-id=\""+data[i].service_id+"\" service-name=\""+data[i].service_name+"\" service-price-inr=\""+data[i].service_price_inr+"\" service-gst-percentage=\""+data[i].service_gst_percentage+"\" service-est-time=\""+data[i].service_est_time+"\" title=\""+data[i].service_name+","+mrp+"\">\
											<div class=\"card customized-category-card\">\
												<div class=\"card-body\" style=\"text-align: center;padding:.25rem!important;background-color:#009925;border-radius:5px;\">\
													<p class=\"card-text\">"+data[i].service_name+"</p>\
												</div>\
											</div>\
										</a>\
									</div>";
    		}
    		
    		str += "</div>";
    		$("#Services-Data").html("").html(str);
     		$("#collapseThree").collapse('show');
    	})
    	.fail(function(jqXHR, textStatus, errorThrown) {
        console.log(errorThrown.toString());
   		});	
		});

		$(document).on('click','.ShowPackageSubCategories',function(event){
			event.preventDefault();
			$(this).blur();
			var parameters = {
				category_id : $(this).attr('category-id'),
				customer_id : $(this).attr('customer-id')
		 	};

			$.getJSON("<?=base_url()?>Cashier/GetPackageSubCategories",parameters)
      .done(function(data, textStatus, jqXHR) {
    		var str = "<div class='row'>";
    		for(var i=0;i<data.length;i++){
    			
    			str += "<div class=\"col-md-2 col-sm-4\">\
										<a class=\"ShowPackageServices\" sub-category-id=\""+data[i].sub_category_id+"\" customer-id=\""+data[i].customer_id+"\">\
											<div class=\"card customized-category-card\">\
												<div class=\"card-body\" style=\"text-align: center;padding:.25rem!important;\">\
													<p class=\"card-text\">"+data[i].sub_category_name+"</p>\
												</div>\
											</div>\
										</a>\
									</div>";
    		}
    		
    		str += "</div>";
    		$("#Sub-Categories-Packages-Data").html("").html(str);
     		$("#collapseTwoPackages").collapse('show');
    	})
    	.fail(function(jqXHR, textStatus, errorThrown) {
        console.log(errorThrown.toString());
   		});	
		});

		$(document).on('click','.ShowPackageServices',function(event){
			event.preventDefault();
			$(this).blur();
			var parameters = {
				sub_category_id : $(this).attr('sub-category-id'),
				customer_id : $(this).attr('customer-id')
			};

			$.getJSON("<?=base_url()?>Cashier/GetPackageServices",parameters)
      .done(function(data, textStatus, jqXHR) {
    		var str = "<div class='row'>";
    		for(var i=0;i<data.length;i++){
    			if(data[i].salon_package_type == 'Services'){
	    			str += "<div class=\"col-md-4 col-sm-6\">\
											<a class=\"ChooseServiceFromPackage\" service-id=\""+data[i].service_id+"\" service-name=\""+data[i].service_name+"\" service-price-inr=\""+data[i].service_price_inr+"\" service-gst-percentage=\""+data[i].service_gst_percentage+"\" service-est-time=\""+data[i].service_est_time+"\" service-count=\""+data[i].service_count+"\" service-discount=\""+data[i].service_discount+"\" customer-id=\""+data[i].customer_id+"\" customer-package-profile-id=\""+data[i].customer_package_profile_id+"\" salon-package-name=\""+data[i].salon_package_name+"\">\
												<div class=\"card customized-category-card-package\" style=\"background-color: #eb4153d9;\">\
													<div class=\"card-body\" style=\"text-align: center;padding:.25rem!important;\">\
														<p class=\"card-text mb-0\"><strong style=\"font-size: larger;\">"+data[i].service_name+"</strong></p>\
														<p class=\"card-text mb-0\">Type : "+data[i].salon_package_type+"</p>\
														<p class=\"card-text mb-0\">Discount : "+data[i].service_discount+"%</p>\
														<p class=\"card-text mb-0\">Service Count : "+data[i].service_count+"</p>\
													</div>\
												</div>\
											</a>\
										</div>";
					}
					else if(data[i].salon_package_type == 'Discount'){
						str += "<div class=\"col-md-4 col-sm-6\">\
											<a class=\"ChooseServiceFromPackage\" service-id=\""+data[i].service_id+"\" service-name=\""+data[i].service_name+"\" service-price-inr=\""+data[i].service_price_inr+"\" service-gst-percentage=\""+data[i].service_gst_percentage+"\" service-est-time=\""+data[i].service_est_time+"\" service-count=\""+data[i].service_count+"\" service-discount=\""+data[i].service_discount+"\" customer-id=\""+data[i].customer_id+"\" customer-package-profile-id=\""+data[i].customer_package_profile_id+"\" salon-package-name=\""+data[i].salon_package_name+"\">\
												<div class=\"card customized-category-card-package\" style=\"background-color: #fac23e;\">\
													<div class=\"card-body\" style=\"text-align: center;padding:.25rem!important;\">\
														<p class=\"card-text mb-0\"><strong style=\"font-size: larger;\">"+data[i].service_name+"</strong></p>\
														<p class=\"card-text mb-0\">Type : "+data[i].salon_package_type+"</p>\
														<p class=\"card-text mb-0\">Discount : "+data[i].service_discount+"%</p>\
														<p class=\"card-text mb-0\">Service Count : "+data[i].service_count+"</p>\
													</div>\
												</div>\
											</a>\
										</div>";	
					}
					else if(data[i].salon_package_type == 'special_membership'){
						str += "<div class=\"col-md-4 col-sm-6\">\
											<a class=\"ChooseServiceFromPackage\" service-id=\""+data[i].service_id+"\" service-name=\""+data[i].service_name+"\" service-price-inr=\""+data[i].service_price_inr+"\" service-gst-percentage=\""+data[i].service_gst_percentage+"\" service-est-time=\""+data[i].service_est_time+"\" service-count=\""+data[i].service_count+"\" service-discount=\""+data[i].service_discount+"\" customer-id=\""+data[i].customer_id+"\" customer-package-profile-id=\""+data[i].customer_package_profile_id+"\" salon-package-name=\""+data[i].salon_package_name+"\">\
												<div class=\"card customized-category-card-package\" style=\"background-color: #65187A;\">\
													<div class=\"card-body\" style=\"text-align: center;padding:.25rem!important;\">\
														<p class=\"card-text mb-0\"><strong style=\"font-size: larger;\">"+data[i].service_name+"</strong></p>\
														<p class=\"card-text mb-0\">Type : "+data[i].salon_package_type+"</p>\
														<p class=\"card-text mb-0\">Discount : "+data[i].service_discount+"%</p>\
														<p class=\"card-text mb-0\">Service Count : "+data[i].service_count+"</p>\
													</div>\
												</div>\
											</a>\
										</div>";	
					}
    		}
    		
    		str += "</div>";
    		$("#Services-Packages-Data").html("").html(str);
     		$("#collapseThreePackages").collapse('show');
    	})
    	.fail(function(jqXHR, textStatus, errorThrown) {
        console.log(errorThrown.toString());
   		});	
		});


		var service_total_price = 0;
		var service_total_value = 0;
		var base_price = 0;
		var qty = 0;
		
		$(document).on('click','.ProvideServiceDetails',function(event){
			event.preventDefault();
			$(this).blur();

			service_total_price = Math.round(Number($(this).attr('service-price-inr')) + (Number($(this).attr('service-price-inr')) * Number($(this).attr('service-gst-percentage')/100)));
			$("#ServiceDetails input[name=service_name]").val($(this).attr('service-name'));
			$("#ServiceDetails input[name=service_price_inr]").val($(this).attr('service-price-inr'));
			$("#ServiceDetails input[name=service_total_value]").val(service_total_price);
			$("#ServiceDetails input[name=service_quantity]").val(1);
			$("#ServiceDetails input[name=service_discount_absolute]").val(0);
			$("#ServiceDetails input[name=service_discount_percentage]").val(0);
			$("#ServiceDetails input[name=service_id]").val($(this).attr('service-id'));
			$("#ServiceDetails input[name=customer_id]").val(<?=$individual_customer['customer_id']?>);
			$("#ServiceDetails input[name=service_gst_percentage]").val(Number($(this).attr('service-gst-percentage')));
			$("#ServiceDetails input[name=service_est_time]").val($(this).attr('service-est-time'));
			$("#ServiceDetailsButton").click();	
			// $("#ModalServiceDetails").modal('show');	
		});
		// //Coupon Discount
		$(document).on('click','.ProvideCouponDetails',function(event){
			event.preventDefault();
			$(this).blur();
			// alert("<?php echo date('Y-m-01');?>");
			if($(this).attr('redemption_date') > "<?php echo date('Y-m-01');?>"){
				alert("You have redeemed this coupon on "+$(this).attr('redemption_date'));
				return false;
			}else{
				var d1=Number($(this).attr('monthly_discount'));
				var d2=Number($(this).attr('birthday_discount'));
				var d3=Number($(this).attr('anniversary_discount'));
				var discount=d1+d2+d3; 
				// alert(discount);
					if(d1>0){
					$("#DiscountDetails input[name=service_name]").val(" Monthly Discount");
				}
				if(d2>0){
					$("#DiscountDetails input[name=service_name]").val("Birthday Discount");
				}
				if(d3>0){
					$("#DiscountDetails input[name=service_name]").val(" Anniversary Discount");
				}	
				// $("#DiscountDetails input[name=service_name]").val("Monthly Discount");
				$("#DiscountDetails input[name=service_price_inr]").val(0);
				$("#DiscountDetails input[name=service_total_value]").val(0);
				$("#DiscountDetails input[name=service_quantity]").val(1);
				$("#DiscountDetails input[name=service_monthly_discount]").val($(this).attr('monthly_discount'));
				$("#DiscountDetails input[name=service_discount_absolute]").val(discount);
				$("#DiscountDetails input[name=birthday_discount]").val($(this).attr('birthday_discount'));
				$("#DiscountDetails input[name=anni_discount]").val($(this).attr('anniversary_discount'));
				$("#DiscountDetails input[name=service_discount_percentage]").val(0);
				$("#DiscountDetails input[name=service_id]").val(1);
				$("#DiscountDetails input[name=customer_id]").val($(this).attr('customer_id'));
				$("#DiscountDetails input[name=service_gst_percentage]").val(18);
				$("#DiscountDetails input[name=service_est_time]").val(30);
				$("#DiscountDetails input[name=coupon_id]").val($(this).attr('coupon_id'));

				// $("#DiscountDetailsButton").click();	
				$("#ModalDiscountDetails").modal('show');
			}
		});
		//
		$(document).on('click','.ChooseServiceFromPackage',function(event){
			event.preventDefault();
			$(this).blur();

			service_total_price = $(this).attr('service-price-inr');
			// service_total_price=$(this).attr('service-price-inr');
			
			service_total_value=Math.round(Number($(this).attr('service-price-inr')) + (Number($(this).attr('service-price-inr')) * Number($(this).attr('service-gst-percentage')/100)));
			// service_total_value = service_total_price + Math.round((Number($(this).attr('service-discount')) * service_total_price)/100);

			$("#PackageRedemption input[name=service_name]").val($(this).attr('service-name'));
			$("#PackageRedemption input[name=service_price_inr]").val(service_total_price);
			$("#PackageRedemption input[name=service_total_value]").val(service_total_value);
			$("#PackageRedemption input[name=service_quantity]").val($(this).attr('service-count'));
			$("#PackageRedemption input[name=service_quantity_redeemable]").val(parseInt(1));
			$("#PackageRedemption input[name=service_discount_percentage]").val($(this).attr('service-discount'));
			$("#PackageRedemption input[name=service_id]").val($(this).attr('service-id'));
			$("#PackageRedemption input[name=customer_id]").val($(this).attr('customer-id'));
			$("#PackageRedemption input[name=service_gst_percentage]").val(Number($(this).attr('service-gst-percentage')));
			$("#PackageRedemption input[name=salon_package_name]").val($(this).attr('salon-package-name'));
			$("#PackageRedemption input[name=service_est_time]").val($(this).attr('service-est-time'));

			$("#PackageRedemption input[name=customer_package_profile_id]").val($(this).attr('customer-package-profile-id'));
			
			$("#ModalChooseServiceFromPackage").modal('show');	
		});

		$("#PackageRedemption input[name=service_quantity_redeemable]").on('input',function(){
			
			if(parseInt($(this).val()) <= 0){
				alert("Quantity should be a positive value");
			}
			else if(parseInt($(this).val()) > parseInt($('#PackageRedemption input[name=service_quantity]').val())){
				alert("You cannot redeem more than allocated services");
				
				base_price = parseInt($("#PackageRedemption input[name=service_price_inr]").val());

				service_total_value = base_price - Math.round(($('#PackageRedemption input[name=service_discount_percentage]').val() * base_price)/100);

				$("#PackageRedemption input[name=service_quantity_redeemable]").val(parseInt(1));
				$("#PackageRedemption input[name=service_total_value]").val(service_total_value);
			}
			else if(parseInt($(this).val()) <= parseInt($('#PackageRedemption input[name=service_quantity]').val())){
				base_price = parseInt($("#PackageRedemption input[name=service_price_inr]").val());
				qty = parseInt($(this).val());
				
				service_total_value = (base_price - Math.round(($('#PackageRedemption input[name=service_discount_percentage]').val() * base_price)/100))*qty;
				$("#PackageRedemption input[name=service_total_value]").val(service_total_value);
			}
			else{
				base_price = parseInt($("#PackageRedemption input[name=service_price_inr]").val());

				service_total_value = base_price - Math.round(($('#PackageRedemption input[name=service_discount_percentage]').val() * base_price)/100);

				$("#PackageRedemption input[name=service_quantity_redeemable]").val(parseInt(1));
				$("#PackageRedemption input[name=service_total_value]").val(service_total_value);
			}
		});

		$("#ServiceDetails input[name=service_quantity]").on('input',function(){
			
			if(parseInt($(this).val()) <= 0){
				alert("Quantity should be a positive value");
			}
			else{
				base_price = parseInt($("#ServiceDetails input[name=service_price_inr]").val());
				qty = parseInt($(this).val());
				total_service_price=base_price*qty;
				gst_percentage= $("#ServiceDetails input[name=service_gst_percentage]").val();
				total_service_price+=(base_price*qty)*gst_percentage/100;
				$("#ServiceDetails input[name=service_total_value]").val(total_service_price);
				$("#ServiceDetails input[name=service_quantity]").val($(this).val());
				$("#ServiceDetails input[name=service_discount_absolute]").val(0);
			  $("#ServiceDetails input[name=service_discount_percentage]").val(0);
			}
		});

		$("#ServiceDetails input[name=service_discount_absolute]").on('input',function(){

			base_price =$("#ServiceDetails input[name=service_price_inr]").val();
			qty = parseInt($("#ServiceDetails input[name=service_quantity]").val());
			service_total_value = (base_price*qty);
			service_gst_percentage=$("#ServiceDetails input[name=service_gst_percentage]").val();
			service_total_gst=service_total_value*service_gst_percentage/100;
			service_total_value=service_total_value+service_total_gst;
			// service_total_value = parseInt($("#ServiceDetails input[name=service_total_value]").val());
			
			
			if(parseInt($(this).val()) < service_total_value){
				
				$("#ServiceDetails input[name=service_price_inr]").val(base_price);
				$("#ServiceDetails input[name=service_total_value]").val(service_total_value);
				service_gst_percentage=$("#ServiceDetails input[name=service_gst_percentage]").val();
				$("#ServiceDetails input[name=service_discount_percentage]").val(0);
			}
			else if(parseInt($(this).val()) >= service_total_value){
				alert("Please enter less value than overall value!");
				$("#ServiceDetails input[name=service_discount_absolute]").val(parseInt(0));
				$("#ServiceDetails input[name=service_total_value]").val(base_price*qty);
			}	
			else{
				$("#ServiceDetails input[name=service_discount_absolute]").val(parseInt(0));
				$("#ServiceDetails input[name=service_total_value]").val(base_price*qty);
			}
		});

		$("#ServiceDetails input[name=service_discount_percentage]").on('input',function(){

			base_price = parseInt($("#ServiceDetails input[name=service_price_inr]").val());
			qty = parseInt($("#ServiceDetails input[name=service_quantity]").val());
			service_total_value = base_price * qty;
			service_gst_percentage=$("#ServiceDetails input[name=service_gst_percentage]").val();
			service_total_gst=service_total_value*service_gst_percentage/100;
			service_total_value=service_total_value+service_total_gst;

			if(parseInt($(this).val()) < parseInt(100)){
				discount_percentage=$(this).val();
				discount_amount=service_total_value*discount_percentage/100;
				x=(discount_amount/1.18);
				y=discount_amount-x;
				new_base_price=base_price-x;
				new_gst=service_total_gst-y;
				service_total_value = new_base_price + new_gst
				$("#ServiceDetails input[name=service_discount_absolute]").val(parseInt(0));
			}
			else if(parseInt($(this).val()) >= parseInt(100)){
				alert("Please enter less value than 100% !");
				$("#ServiceDetails input[name=service_discount_percentage]").val(parseInt(0));
				$("#ServiceDetails input[name=service_total_value]").val(base_price*qty);
			}
			else{
				$("#ServiceDetails input[name=service_discount_percentage]").val(parseInt(0));
				$("#ServiceDetails input[name=service_total_value]").val(base_price*qty);	
			}			
		});
		
		$("#ServiceEditDetails input[name=service_quantity]").on('input',function(){
			
			if(parseInt($(this).val()) <= 0){
				alert("Quantity should be a positive value");
			}
			else{
				base_price = $("#ServiceEditDetails input[name=service_price_inr]").val();
				qty = parseInt($(this).val());
				total_service_price=base_price*qty;
				gst_percentage= $("#ServiceEditDetails input[name=service_gst_percentage]").val();
				total_service_price+=(base_price*qty)*gst_percentage/100;
				$("#ServiceEditDetails input[name=service_total_value]").val(total_service_price);
				$("#ServiceEditDetails input[name=service_quantity]").val($(this).val());
				$("#ServiceEditDetails input[name=service_discount_absolute]").val(0);
			  $("#ServiceEditDetails input[name=service_discount_percentage]").val(0);
			}
		});

		$("#ServiceEditDetails input[name=service_discount_absolute]").on('input',function(){

			base_price = $("#ServiceEditDetails input[name=service_price_inr]").val();
			qty = parseInt($("#ServiceEditDetails input[name=service_quantity]").val());
			gst_percentage= $("#ServiceEditDetails input[name=service_gst_percentage]").val();
			service_total_value = base_price * qty;
			service_total_value+=service_total_value*(gst_percentage/100);
			if(parseInt($(this).val()) < service_total_value){
				// service_total_value = service_total_value - parseInt($(this).val());
				$("#ServiceEditDetails input[name=service_total_value]").val(service_total_value);
				$("#ServiceEditDetails input[name=service_discount_percentage]").val(0);
			}
			else if(parseInt($(this).val()) >= service_total_value){
				alert("Please enter less value than overall value!");
				$("#ServiceEditDetails input[name=service_discount_absolute]").val(parseInt(0));
				$("#ServiceEditDetails input[name=service_total_value]").val(base_price*qty);
			}	
			else{
				$("#ServiceEditDetails input[name=service_discount_absolute]").val(parseInt(0));
				$("#ServiceEditDetails input[name=service_total_value]").val(service_total_value);
			}
		});

		$("#ServiceEditDetails input[name=service_discount_percentage]").on('input',function(){

			base_price = parseInt($("#ServiceEditDetails input[name=service_price_inr]").val());
			qty = parseInt($("#ServiceEditDetails input[name=service_quantity]").val());
			gst_percentage= $("#ServiceEditDetails input[name=service_gst_percentage]").val();
			service_total_value = base_price * qty;
			service_total_value+=service_total_value*(gst_percentage/100);
			if(parseInt($(this).val()) < parseInt(100)){
				//service_total_value = Math.round(service_total_value - (service_total_value * ($(this).val()/100)));
				$("#ServiceEditDetails input[name=service_total_value]").val(service_total_value);
				$("#ServiceEditDetails input[name=service_discount_absolute]").val(parseInt(0));
			}
			else if(parseInt($(this).val()) >= parseInt(100)){
				alert("Please enter less value than 100% !");
				$("#ServiceEditDetails input[name=service_discount_percentage]").val(parseInt(0));
				$("#ServiceEditDetails input[name=service_total_value]").val(base_price*qty);
			}
			else{
				$("#ServiceEditDetails input[name=service_discount_percentage]").val(parseInt(0));
				$("#ServiceEditDetails input[name=service_total_value]").val(base_price*qty);	
			}			
		});

		// $("#ServiceEditDetails input[name=service_add_on_price]").on('input',function(){

		// 	// base_price = parseInt($("#ServiceEditDetails input[name=service_price_inr]").val());
		// 	// qty = parseInt($("#ServiceEditDetails input[name=service_quantity]").val());
		// 	// gst_percentage= $("#ServiceEditDetails input[name=service_gst_percentage]").val();
		// 	add_on_price = parseInt($(this).val());

		// 	// service_total_value = (base_price+add_on_price) * qty;
		// 	// service_total_value+=service_total_value*(gst_percentage/100);
		// 	// $("#ServiceEditDetails input[name=service_total_value]").val(service_total_value);
		// 	if(parseInt($(this).val()) < parseInt(0)){
		// 		alert("Please enter a positive value !");
		// 		return false;
		// 	}
		// 	else if(parseInt($(this).val()) > base_price){
		// 		alert("Your add on is greater than your price !");
		// 	}		
		// });

		$("#ApplyExtraDiscount input[name=cart_discount_percentage]").on('input',function(){

			if(parseInt($(this).val()) < 100){
				$("#ApplyExtraDiscount input[name=cart_discount_absolute]").val(parseInt(0));
			}
			else if(parseInt($(this).val()) >= 100){
				alert("Please enter less value than 100% !");
				$("#ApplyExtraDiscount input[name=cart_discount_percentage]").val(parseInt(0));
			}
			else{
				$("#ApplyExtraDiscount input[name=cart_discount_percentage]").val(parseInt(0));	
			}			
		});

		$("#ApplyExtraDiscount input[name=cart_discount_absolute]").on('input',function(){
			var actual_bill_for_discount = "<?php if(isset($actual_bill)){ echo $actual_bill; } else{ echo 0;} ?>";
			if(parseInt($(this).val()) < actual_bill_for_discount){
				$("#ApplyExtraDiscount input[name=cart_discount_percentage]").val(parseInt(0));
			}
			else if(parseInt($(this).val()) >= actual_bill_for_discount){
				alert("Cannot apply discount on complete bill!");
				$("#ApplyExtraDiscount input[name=cart_discount_absolute]").val(parseInt(0));
			}	
			else{
				$("#ApplyExtraDiscount input[name=cart_discount_absolute]").val(parseInt(0));
			}		
		});

		$("#ServiceDetails").validate({
	  	errorElement: "div",
	    rules: {
	        "service_name" : {
            required : true,
            maxlength : 100
	        },
	        "service_total_value" : {
            required : true
	        },
	        "service_quantity" : {
	          required : true,
	          digits : true
	        },
	        "service_expert_id" : {
	        	required : true
	        },
	        "service_discount_percentage" : {
	        	digits : true
	        },
	        "service_discount_absolute" : {
	        	digits : true
	        },
	        "service_price_inr" : {
	        	required : true
	        }
	    },
	    submitHandler: function(form) {
			var formData = $("#ServiceDetails").serialize(); 
			$.ajax({
	        url: "<?=base_url()?>Cashier/AddToCart",
	        data: formData, 
					type: "POST",
					cache: false,

	    		success: function(data) {
            if(data.success == 'true'){
            	$("#ModalServiceDetails").modal('hide'); 
							window.location.reload();
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
		
		//Discount Details
		$("#DiscountDetails").validate({
	  	errorElement: "div",
	    rules: {
	        "service_name" : {
            required : true
	        },
	        "service_total_value" : {
            required : true
	        },
	        "service_quantity" : {
	          required : true
	        },
	        "service_expert_id" : {
	        	required : true
	        },
	        "monthly_discount" : {
	        	digits : true
	        },
	        "service_price_inr" : {
	        	required : true
	        }
	    },
	    submitHandler: function(form) {
				var formData = $("#DiscountDetails").serialize(); 
				$.ajax({
	        url: "<?=base_url()?>Cashier/AddToCart",
	        data: formData,
	        type: "POST",
	        // crossDomain: true,
					cache: false,
	        // dataType : "json",
	    		success: function(data) {
            if(data.success == 'true'){
            	$("#ModalServiceDetails").modal('hide'); 
							window.location.reload();
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
		//
		
		//Apply Coupon Code
		$("#ApplyCode").validate({
	  	errorElement: "div",
	    rules: {
	        "coupon_code" : {
            required : true
	        },
	        "customer_id" : {
            required : true
	        },
					"total_bill" :{
						required : true
					}
	    },
	    submitHandler: function(form) {

				var formData = $("#ApplyCode").serialize(); 
				$.ajax({
	        url: "<?=base_url()?>Cashier/UpdateCartData",
	        data: formData,
	        type: "POST",
	        // crossDomain: true,
					cache: false,
	        // dataType : "json",
	    		success: function(data) {
            if(data.success == 'true'){
            	$("#ModalApplyCode").modal('hide'); 
							toastr["success"](data.message,"", {
							positionClass: "toast-top-right",
							progressBar: "toastr-progress-bar",
							newestOnTop: "toastr-newest-on-top",
							rtl: $("body").attr("dir") === "rtl" || $("html").attr("dir") === "rtl",
							timeOut: 1000
						});
						setTimeout(function () { location.reload(1); }, 1000);
            }
            else if (data.success == 'false'){                   
        	    $("#ModalApplyCode").modal('hide'); 
							toastr["error"](data.message,"", {
							positionClass: "toast-top-right",
							progressBar: "toastr-progress-bar",
							newestOnTop: "toastr-newest-on-top",
							rtl: $("body").attr("dir") === "rtl" || $("html").attr("dir") === "rtl",
							timeOut: 1000
						});
						setTimeout(function () { location.reload(1); }, 1000);
            }
          },
          error: function(data){
  					$('.feedback').addClass('alert-danger');
  					$('.alert-message').html("").html(data.message); 
          }
				});
			},
		}); 
		//
		//Apply Remarks
		$("#Apply_remarks").validate({
	  	errorElement: "div",
	    rules: {
	        "txn_remarks" : {
            required : true
	        },
	        "customer_id" : {
            required : true
	        },
					"total_bill" :{
						required : true
					}
	    },
	    submitHandler: function(form) {
				var formData = $("#Apply_remarks").serialize(); 
				$.ajax({
	        url: "<?=base_url()?>Cashier/AddTxnRemarks",
	        data: formData,
	        type: "POST",
	        // crossDomain: true,
					cache: false,
	        // dataType : "json",
	    		success: function(data) {
            if(data.success == 'true'){
            	$("#ModalApplyRemarks").modal('hide'); 
							toastr["success"](data.message,"", {
							positionClass: "toast-top-right",
							progressBar: "toastr-progress-bar",
							newestOnTop: "toastr-newest-on-top",
							rtl: $("body").attr("dir") === "rtl" || $("html").attr("dir") === "rtl",
							timeOut: 1000
						});
						setTimeout(function () { location.reload(1); }, 1000);
            }
            else if (data.success == 'false'){                   
        	    $("#ModalApplyRemarks").modal('hide'); 
							toastr["error"](data.message,"", {
							positionClass: "toast-top-right",
							progressBar: "toastr-progress-bar",
							newestOnTop: "toastr-newest-on-top",
							rtl: $("body").attr("dir") === "rtl" || $("html").attr("dir") === "rtl",
							timeOut: 1000
						});
						setTimeout(function () { location.reload(1); }, 1000);
            }
          },
          error: function(data){
  					$('.feedback').addClass('alert-danger');
  					$('.alert-message').html("").html(data.message); 
          }
				});
			},
		}); 
		//
		
		$("#ServiceEditDetails").validate({
	  	errorElement: "div",
	    rules: {
	        "service_name" : {
            required : true,
            maxlength : 100
	        },
	        "service_total_value" : {
            required : true
	        },
	        "service_quantity" : {
	          required : true,
	          digits : true
	        },
	        "service_expert_id" : {
	        	required : true
	        },
	        "service_discount_percentage" : {
	        	digits : true
	        },
	        "service_discount_absolute" : {
	        	digits : true
	        },
	        "service_price_inr" : {
	        	required : true
	        }
	    },
	    submitHandler: function(form) {

				var formData = $("#ServiceEditDetails").serialize(); 
				$.ajax({
	        url: "<?=base_url()?>Cashier/EditCart",
	        data: formData,
	        type: "POST",
	        // crossDomain: true,
					cache: false,
	        // dataType : "json",
	    		success: function(data) {
            if(data.success == 'true'){
            	$("#ModalServiceEditDetails").modal('hide'); 
							window.location.reload();
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
		//jitesh loyalty'
		//LoyaltyEditServiceDetails
    $("#LoyaltyServiceEditDetails").validate({
      errorElement: "div",
      rules: {
          "service_name" : {
            required : true,
            maxlength : 100
          },
          "service_total_value" : {
            required : true
          },
          "service_quantity" : {
            required : true,
            digits : true
          },
          "service_expert_id" : {
            required : true
          },
          "service_discount_percentage" : {
            digits : true
          },
          "service_discount_absolute" : {
            digits : true
          },
          "service_price_inr" : {
            required : true
          }
      },
      submitHandler: function(form) {
        var formData = $("#LoyaltyServiceEditDetails").serialize(); 
        $.ajax({
          url: "<?=base_url()?>Cashier/EditCart",
          data: formData,
          type: "POST",
          // crossDomain: true,
          cache: false,
          // dataType : "json",
          success: function(data) {
            if(data.success == 'true'){
              $("#ModalServiceEditDetails").modal('hide'); 
              window.location.reload();
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
		//
		$("#PackageRedemption").validate({
	  	errorElement: "div",
	    rules: {
	        "service_quantity_redeemable" : {
	          required : true,
	          digits : true
	        },
	        "service_expert_id" : {
	        	required : true
	        }
	    },
	    submitHandler: function(form) {
				var formData = $("#PackageRedemption").serialize(); 
				$.ajax({
	        url: "<?=base_url()?>Cashier/AddToCartPackageService",
	        data: formData,
	        type: "POST",
	        // crossDomain: true,
					cache: false,
	        // dataType : "json",
	    		success: function(data) {
            if(data.success == 'true'){
            	$("#ModalChooseServiceFromPackage").modal('hide'); 
							window.location.reload();
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

		$("#ApplyExtraDiscount").validate({
	  	errorElement: "div",
	    rules: {
	        "cart_discount_percentage" : {
	        	digits : true,
	        	required : true
	        },
	        "cart_discount_absolute" : {
	        	digits : true,
	        	required : true
	        }
	    },
	    submitHandler: function(form) {
				var formData = $("#ApplyExtraDiscount").serialize(); 
				$.ajax({
	        url: "<?=base_url()?>Cashier/ApplyExtraDiscount",
	        data: formData,
	        type: "POST",
	        // crossDomain: true,
					cache: false,
	        // dataType : "json",
	    		success: function(data) {
            if(data.success == 'true'){
            	$("#ModalApplyExtraDiscount").modal('hide'); 
							$('#centeredModalSuccess').modal('show').on('shown.bs.modal', function (e){
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

		$("#FullPaymentInfo").validate({
	  	errorElement: "div",
	    rules: {
	        "payment_type" : {
	        	required : true
	        },
	        "total_final_bill" : {
	        	digits : true,
	        	required : true
	        },
	        "total_amount_received" : {
	        	digits : true,
	        	required : true
	        },
	        "pending_amount" : {
	        	digits : true,
	        	required : true
	        },
	        "balance_to_be_paid_back" : {
	        	digits : true,
	        	required : true
	        }
	    },
	    submitHandler: function(form) {
				var formData = $("#FullPaymentInfo").serialize(); 
				$.ajax({
	        url: "<?=base_url()?>Cashier/FullPaymentInfo",
	        data: formData,
	        type: "POST",
	        // crossDomain: true,
					cache: false,
	        // dataType : "json",
	    		success: function(data) {
            if(data.success == 'true'){
            	$("#ModalFullPayment").modal('hide'); 
							// $('#centeredModalSuccess').modal('show').on('shown.bs.modal', function (e){
							// 	$("#SuccessModalMessage").html("").html(data.message);
							// }).on('hidden.bs.modal', function (e) {
									window.location.reload();
							// });
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

		$("#SplitPaymentInfo").validate({
	  	errorElement: "div",
	    rules: {
	        "payment_type" : {
	        	required : true
	        },
	        "total_final_bill" : {
	        	digits : true,
	        	required : true
	        },
	        "total_amount_received" : {
	        	digits : true,
	        	required : true
	        },
	        "total_pending_amount" : {
	        	digits : true,
	        	required : true
	        },
	        "balance_to_be_paid_back" : {
	        	digits : true,
	        	required : true
	        },
	        "amount_received" : {
	        	digits : true,
	        	required : true
	        }
	    },
	    submitHandler: function(form) {
				var formData = $("#SplitPaymentInfo").serialize(); 
				$.ajax({
	        url: "<?=base_url()?>Cashier/SplitPaymentInfo",
	        data: formData,
	        type: "POST",
	        // crossDomain: true,
					cache: false,
	        // dataType : "json",
	    		success: function(data) {
            if(data.success == 'true'){
            	$("#ModalSplitPayment").modal('hide'); 
							// $('#centeredModalSuccess').modal('show').on('shown.bs.modal', function (e){
							// 	$("#SuccessModalMessage").html("").html(data.message);
							// }).on('hidden.bs.modal', function (e) {
									window.location.reload();
							// });
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
		
    $(document).on('click','.cart-delete-btn',function(event) {
      event.preventDefault();
      this.blur(); // Manually remove focus from clicked link.
      var parameters = {
        service_id : $(this).attr('service-id'),
        customer_id : "<?=$individual_customer['customer_id']?>"
      };

      $.ajax({
        url: "<?=base_url()?>Cashier/DeleteCartItem",
        data: parameters,
        type: "GET",
        // crossDomain: true,
				cache: false,
        // dataType : "json",
    		success: function(data) {
          if(data.success == 'true'){
						// $('#centeredModalSuccess').modal('show').on('shown.bs.modal', function (e){
						// 	$("#SuccessModalMessage").html("").html(data.message);
						// }).on('hidden.bs.modal', function (e) {
						// 		window.location.reload();
						// });
						toastr["success"](data.message,"", {
							positionClass: "toast-top-right",
							progressBar: "toastr-progress-bar",
							newestOnTop: "toastr-newest-on-top",
							rtl: $("body").attr("dir") === "rtl" || $("html").attr("dir") === "rtl",
							timeOut: 1000
						});
						setTimeout(function () { location.reload(1); }, 1000);
          }
          else if (data.success == 'false'){                   
      	   	$('#centeredModalDanger').modal('show').on('shown.bs.modal', function (e){
							$("#ErrorModalMessage").html("").html(data.message);
						});
          }
        },
        error: function(data){
					$('#centeredModalDanger').modal('show').on('shown.bs.modal', function (e){
						$("#ErrorModalMessage").html("").html("Some error occured!");
					});
        }
			});
    });
    
    $(document).on('click','.cart-edit-btn',function(event) {
      event.preventDefault();
      this.blur(); // Manually remove focus from clicked link.
			package_profile_id=$(this).attr('package-profile-id');
			// alert(package_profile_id);
			if(package_profile_id>0){
				alert("This is a Package Service. You can't Edit it;");
			}
			else if(package_profile_id == -9999)
      {
        $("#LoyaltyServiceEditDetails input[name=service_name]").val($(this).attr('service-name'));
        $("#LoyaltyServiceEditDetails input[name=service_price_inr]").val($(this).attr('service-price-inr'));
        $("#LoyaltyServiceEditDetails input[name=service_total_value]").val($(this).attr('service-total-value'));
        $("#LoyaltyServiceEditDetails input[name=service_quantity]").val($(this).attr('service-quantity'));
        $("#LoyaltyServiceEditDetails input[name=service_discount_absolute]").val($(this).attr('discount-absolute'));
        $("#LoyaltyServiceEditDetails input[name=service_discount_percentage]").val($(this).attr('discount-percentage'));
        $("#LoyaltyServiceEditDetails select[name=service_expert_id]").val($(this).attr('expert-name'));
        $("#LoyaltyServiceEditDetails input[name=service_id]").val($(this).attr('service-id'));
        $("#LoyaltyServiceEditDetails input[name=customer_id]").val(<?=$individual_customer['customer_id']?>);
        $("#LoyaltyServiceEditDetails input[name=service_gst_percentage]").val(Number($(this).attr('gst-percentage')));
        $("#LoyaltyServiceEditDetails input[name=service_est_time]").val($(this).attr('est-time'));
        $("#LoyaltyServiceEditDetails input[name=customer_package_profile_id]").val($(this).attr('package-profile-id'));
				
        
        $("#ModalLoyaltyServiceEditDetails").modal('show'); 
      }
			else{
				$("#ServiceEditDetails input[name=service_name]").val($(this).attr('service-name'));
				$("#ServiceEditDetails input[name=service_price_inr]").val($(this).attr('service-price-inr'));
				$("#ServiceEditDetails input[name=service_total_value]").val($(this).attr('service-total-value'));
				$("#ServiceEditDetails input[name=service_quantity]").val($(this).attr('service-quantity'));
				$("#ServiceEditDetails input[name=service_discount_absolute]").val($(this).attr('discount-absolute'));
				$("#ServiceEditDetails input[name=service_discount_percentage]").val($(this).attr('discount-percentage'));
				$("#ServiceEditDetails select[name=service_expert_id]").val($(this).attr('expert-name'));
				$("#ServiceEditDetails input[name=service_id]").val($(this).attr('service-id'));
				$("#ServiceEditDetails input[name=customer_id]").val(<?=$individual_customer['customer_id']?>);
				$("#ServiceEditDetails input[name=service_gst_percentage]").val(Number($(this).attr('gst-percentage')));
				$("#ServiceEditDetails input[name=service_est_time]").val($(this).attr('est-time'));
				$("#ServiceEditDetails input[name=customer_package_profile_id]").val($(this).attr('package-profile-id'));
				$("#ServiceEditDetails input[name=service_add_on_price]").val($(this).attr('service_add_on_price'));
				
				$("#ModalServiceEditDetails").modal('show');
			}
    });

    $(document).on('click',".full-payment-btn",function(event){
    	event.preventDefault();
      this.blur(); // Manually remove focus from clicked link.

			$("#FullPaymentInfo input[name=total_final_bill]").val(<?php if(isset($actual_bill)){ echo round($actual_bill); } ?>);
			$("#FullPaymentInfo input[name=total_amount_received]").val('');
			$("#FullPaymentInfo input[name=balance_to_be_paid_back]").val(parseInt(0));
			$("#FullPaymentInfo input[name=pending_amount]").val(parseInt(0));
			$("#FullPaymentInfo input[name=total_amount_received]").val(<?php if(isset($actual_bill)){ echo round($actual_bill); } ?>);
			$("#FullPaymentInfo input[name=customer_id]").val(<?=$individual_customer['customer_id']?>);
			
			$("#ModalFullPayment").modal('show');	
    });

    $(document).on('click',".split-payment-btn",function(event){
    	event.preventDefault();
      this.blur(); // Manually remove focus from clicked link.

			$("#SplitPaymentInfo input[name=total_final_bill]").val(<?php if(isset($actual_bill)){ echo round($actual_bill); } ?>);
		//	$("#amount_recieved").val(<?php //if(isset($actual_bill)){ echo round($actual_bill); } ?>);
			$("#SplitPaymentInfo input[name=balance_to_be_paid_back]").val(parseInt(0));
			$("#SplitPaymentInfo input[name=total_pending_amount]").val(parseInt(0));
			$("#SplitPaymentInfo input[name=total_amount_received]").val(parseInt(0));
			$("#SplitPaymentInfo input[name=customer_id]").val(<?=$individual_customer['customer_id']?>);			
			$("#ModalSplitPayment").modal('show');	
    });

    $("#FullPaymentInfo input[name=total_amount_received]").on('input',function(){
    	var billable_amt = parseInt($("#FullPaymentInfo input[name=total_final_bill]").val());
    	
    	
    	if(parseInt($(this).val()) < billable_amt){
    		$("#FullPaymentInfo input[name=pending_amount]").val(billable_amt - parseInt($(this).val()));
    		$("#FullPaymentInfo input[name=balance_to_be_paid_back]").val(parseInt(0));
    	}
    	else if(billable_amt < parseInt($(this).val())){
    		$("#FullPaymentInfo input[name=pending_amount]").val(parseInt(0));
    		$("#FullPaymentInfo input[name=balance_to_be_paid_back]").val(parseInt($(this).val()) - billable_amt);
    	}
			else if(billable_amt == $(this).val()){
    		$("#FullPaymentInfo input[name=balance_to_be_paid_back]").val(parseInt(0));
    		$("#FullPaymentInfo input[name=pending_amount]").val(parseInt(0));
    	}
    	else{
    		$("#FullPaymentInfo input[name=pending_amount]").val(billable_amt);
    		$("#FullPaymentInfo input[name=balance_to_be_paid_back]").val(parseInt(0));	
    	}
    });

    $("#AddRow").click(function(event){
    	event.preventDefault();
      this.blur();
			//
			// var p_type= $("select[name='payment_type[]']").map(function(){return $(this).val();}).get();
			// var a_received= $("input[name='amount_received[]']").map(function(){return $(this).val();}).get();
			
			// for (var i = 1; i < p_type.length; i++) {
			// 	alert(p_type);
			// 	alert(p_type.length);
			// 	alert(p_type[i+1]);
			// 	if(p_type.includes(p_type[i])){
			// 		alert("You selected a payment Method twice. Please select Other payment type.");
			// 		$("select[name='payment_type[]']").focus();
			// 		return false;
			// 	}
			// }
			//
      var rowno = $("#Split-Payment-Info-Table tr").length;

      rowno = rowno+1;
      
      $("#Split-Payment-Info-Table tr:last").after("<tr><td>"+rowno+"</td><td><div class=\"form-group\"><label class=\"form-label\">Payment Mode</label><select name=\"payment_type[]\" class=\"form-control\" required><option disabled>Select Payment Method</option><option value=\"Cash\">Cash</option><option value=\"Credit_Card\">Credit Card</option><option value=\"Debit_Card\">Debit Card</option><option value=\"Paytm\">Paytm</option><option value=\"Phonepe\">Phonepe</option><option value=\"Google_Pay\">Google Pay</option><option value=\"Virtual_Wallet\" disabled>Virtual Wallet</option><option value=\"loyalty_wallet\" >Loyalty Wallet</option></select></div></td><td><div class=\"form-group\"><label class=\"form-label\">Amount Received</label><input type=\"number\" placeholder=\"Amount in INR\" class=\"form-control\" name=\"amount_received[]\"></div></td></tr>");

				
			
    });

    $("#DeleteRow").click(function(event){
    	event.preventDefault();
      this.blur();
      var rowno = $("#Split-Payment-Info-Table tr").length;
      if(rowno > 1){
      	$('#Split-Payment-Info-Table tr:last').remove();
    	}
    });

    $(document).on('input',"#SplitPaymentInfo input[name^=amount_received]",function(event){
    	var billable_amt = parseInt($("#SplitPaymentInfo input[name=total_final_bill]").val());
    	
    	var amts_received_array = $('#SplitPaymentInfo input[name^=amount_received]').map(function(idx, elem) {
		    return $(elem).val();
		  }).get();

		  event.preventDefault();
		 
		  //console.log(amts_received_array);
		  var sum_amts_recieved = 0;
		  for(var i=0;i<amts_received_array.length;i++){
		  	sum_amts_recieved += Number(amts_received_array[i]);
		  }
		  //console.log(sum_amts_recieved);
    	$("#SplitPaymentInfo input[name=total_amount_received]").val(parseInt(sum_amts_recieved));
    	if(sum_amts_recieved < billable_amt){
    		$("#SplitPaymentInfo input[name=total_pending_amount]").val(billable_amt - sum_amts_recieved);
    		$("#SplitPaymentInfo input[name=balance_to_be_paid_back]").val(parseInt(0));
    	}
    	else if(billable_amt < sum_amts_recieved){
    		$("#SplitPaymentInfo input[name=total_pending_amount]").val(parseInt(0));
    		$("#SplitPaymentInfo input[name=balance_to_be_paid_back]").val(sum_amts_recieved - billable_amt);
    	}
			else if(billable_amt == sum_amts_recieved){
    		$("#SplitPaymentInfo input[name=balance_to_be_paid_back]").val(parseInt(0));
    		$("#SplitPaymentInfo input[name=total_pending_amount]").val(parseInt(0));
    	}
    	else{
    		$("#SplitPaymentInfo input[name=balance_to_be_paid_back]").val(parseInt(0));
    		$("#SplitPaymentInfo input[name=total_pending_amount]").val(billable_amt);	
    	}
    });
    
    $(document).on('click',"#SwapWithExistingCustomer",function(event){
    	event.preventDefault();
    	$(this).blur();
    	$("#SwapCustomer input[name=customer_id]").attr('value',$(this).attr('CustomerId'));
			$("#ModalSwapExistingCustomer").modal('show'); 
    });
    
    $("#SwapCustomer").validate({
	  	errorElement: "div",
	    rules: {
	    	"customer_mobile" : {
	    		required : true,
	    		minlength : 10,
	    		maxlength : 10
	    	}
	    },
	    submitHandler: function(form) {
				var formData = $("#SwapCustomer").serialize(); 
				$.ajax({
		        url: "<?=base_url()?>Cashier/SwapWithExistingCustomer",
		        data: formData,
		        type: "POST",
		        // crossDomain: true,
						cache: false,
		        // dataType : "json",
		    		success: function(data) {
              if(data.success == 'true'){
              	$("#ModalSwapExistingCustomer").modal('hide'); 
								$('#centeredModalSuccess').modal('show').on('shown.bs.modal', function (e){
									$("#SuccessModalMessage").html("").html(data.message);
								}).on('hidden.bs.modal', function (e) {
										window.location.href = "<?=base_url()?>Cashier/Dashboard";
								});
              }
              else if (data.success == 'false'){  
              	$("#ModalSwapExistingCustomer").modal('hide');                  
          	   	$('#centeredModalDanger').modal('show').on('shown.bs.modal', function (e){
									$("#ErrorModalMessage").html("").html(data.message);
								}).on('hidden.bs.modal', function (e) {
										window.location.reload();
								}); 
              }
            },
            error: function(data){
            	$("#ModalSwapExistingCustomer").modal('hide'); 
    					$('#centeredModalDanger').modal('show').on('shown.bs.modal', function (e){
								$("#ErrorModalMessage").html("").html(data.message);
							}).on('hidden.bs.modal', function (e) {
									window.location.reload();
							});  
            }
				});
			},
		});
    
    $(document).on('click','#Settle-Final-Order',function(event){
    	event.preventDefault();
			this.disabled=true;
    	this.blur();			
    	var txn_data = {};
    	var cart_data = {};
    	var txn_settlement = {};
    	var customer_pending_data = {}; 
    	var send_sms = 'true';
    	<?php if(isset($cart_json)): ?>
    		cart_data = <?php echo $cart_json; ?>;
    	<?php endif; ?>

    	<?php 
    	if(!empty($payment['full_payment_info']) || !empty($payment['split_payment_info'])){ if(!empty($payment['full_payment_info'])) { 
    	?>

    	txn_data = {
    		'txn_customer_id' : <?=$individual_customer['customer_id']?>,
    		'txn_discount': <?php if(isset($total_discount)){ echo $total_discount; }else{ echo 0;}?> ,
    		'txn_value' :  <?php if(isset($actual_bill)){ echo $actual_bill; }else{ echo 0;} ?>,
				'txn_total_tax'		:	<?php echo (int)$total_gst;?>,
    		'txn_cashier': <?php echo $cashier_details['employee_id']; ?>,
    		'sender_id'	:	'<?php echo $cashier_details['business_outlet_sender_id']; ?>',
				'api_key'	:   '<?php echo $cashier_details['api_key']?>',
				'txn_pending_amount' : <?=$payment['full_payment_info']['pending_amount']?>,
				'txn_remarks'			:	<?=$txn_remarks?>
    	};

    	txn_settlement = {
    		'txn_settlement_way' : 'Full Payment',
    		'txn_settlement_payment_mode' : <?php echo $payment_info_json; ?>,
    		'txn_settlement_amount_received' : <?=$payment['full_payment_info']['total_amount_received']?>,
    		'txn_settlement_balance_paid' : <?=$payment['full_payment_info']['balance_to_be_paid_back']?>
    	};

    	customer_pending_data = {
    		'customer_id' : <?=$individual_customer['customer_id']?>,
    		'pending_amount' : <?=$payment['full_payment_info']['pending_amount']?>,
    		'customer_mobile' : <?=$individual_customer['customer_mobile']?>
    	}

    	<?php
    		}

    	if(!empty($payment['split_payment_info'])){
    	?>
    	var split_payment_key_val = "";

    	txn_data = {
    		'txn_customer_id' : <?=$individual_customer['customer_id']?>,
    		'txn_discount': <?php if(isset($total_discount)){ echo $total_discount; }else{ echo 0;}?> ,
				'txn_value' :  <?php if(isset($actual_bill)){ echo $actual_bill; }else{ echo 0;} ?>,
				'txn_total_tax'		:	<?php echo (int)$total_gst;?>,
    		'txn_cashier': <?php echo $cashier_details['employee_id']; ?>,
    		'sender_id'	:	'<?php echo $cashier_details['business_outlet_sender_id']; ?>',
				'api_key'	:   '<?php echo $cashier_details['api_key']?>',
				'txn_pending_amount' : <?=$payment['split_payment_info']['total_pending_amount']?>,
				'txn_remarks'			:	'<?=$txn_remarks?>'
    	};

    	txn_settlement = {
    		'txn_settlement_way' : 'Split Payment',
    		'txn_settlement_payment_mode' : '<?php echo $payment_info_json; ?>',
    		'txn_settlement_amount_received' : <?=$payment['split_payment_info']['total_amount_received']?>,
    		'txn_settlement_balance_paid' : <?=$payment['split_payment_info']['balance_to_be_paid_back']?>
    	};

    	customer_pending_data = {
    		'customer_id' : <?=$individual_customer['customer_id']?>,
    		'pending_amount' : <?=$payment['split_payment_info']['total_pending_amount']?>,
    		'customer_mobile' : <?=$individual_customer['customer_mobile']?>
    	};
    	<?php
    		}
    	}
    	?>

    	if ($('#SendSMS').is(":checked"))
			{
  			send_sms = 'true';	
			}
			else{
				send_sms = 'false';
			}
			//Credit Cashback	
			if ($('#cashback').is(":checked"))
				{
					cashback = 1;	
				}else{
					cashback=0
				}			
			//
    	var parameters = {
    		'txn_data' : txn_data,
    		'txn_settlement' : txn_settlement,
    		'customer_pending_data' : customer_pending_data,
    		'cart_data' : cart_data,
    		'send_sms' : send_sms,
				'cashback': cashback 
    	};

    	$.ajax({
        url: "<?=base_url()?>Cashier/DoTransaction",
        data: parameters,
        type: "POST",
        // crossDomain: true,
				cache: false,
        // dataType : "json",
    		success: function(data) {
          if(data.success == 'true'){
						toastr["success"](data.message,"", {
							positionClass: "toast-top-right",
							progressBar: "toastr-progress-bar",
							newestOnTop: "toastr-newest-on-top",
							rtl: $("body").attr("dir") === "rtl" || $("html").attr("dir") === "rtl",
							timeOut: 500
						});
						setTimeout(function () { window.location.href = "<?=base_url()?>Cashier/Dashboard"; }, 500);
          }
          else if (data.success == 'false'){
            alert(data.message);
						window.location.reload();
      	     //   $('#centeredModalDanger').modal('show').on('shown.bs.modal', function (e){
    // 			$("#ErrorModalMessage").html("").html(data.message);
// 			})
          }
        },
        error: function(data){
					$('#centeredModalDanger').modal('show').on('shown.bs.modal', function (e){
						$("#ErrorModalMessage").html("").html("There is some problem!");
					})
        }
			});
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
										window.location.href = "<?=base_url()?>Cashier/Dashboard/";
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
	        $("#EditCustomerDetails input[name=customer_dob]").attr('value',moment(data.customer_dob).format('DD-MM-YYYY'));
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
						temp_str += 	"<td>"+data.transactions[i].txn_value+"</td>";
						temp_str += 	"<td>"+data.transactions[i].txn_discount+"</td>";
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

   //functionality for getting the dynamic input data
    $("#SearchServiceByName").typeahead({
      autoselect: true,
      highlight: true,
      minLength: 3
     },
     {
      source: SearchServiceByName,
      templates: {
        empty: "No Services Found!",
        suggestion: _.template("<p class='service_search'><%- service_name %> (<%- qty_per_item %> <%- service_unit %>), (<%- category_name %>), <%- mrp %> </p>")
      }
    });
       
    var to_fill = "";

    $("#SearchServiceByName").on("typeahead:selected", function(eventObject, suggestion, name) {
      var loc = "#SearchServiceByName";
      to_fill = suggestion.service_name;
      setVals(loc,to_fill,suggestion);
    });

    $("#SearchServiceByName").blur(function(){
      $("#SearchServiceByName").val(to_fill);
      to_fill = "";
    });

    function SearchServiceByName(query, cb){
      var parameters = {
        query : query
      };
      
      $.ajax({
        url: "<?=base_url()?>Cashier/GetSearchServiceData",
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

    function setVals(element,fill,suggestion){
      $(element).attr('value',fill);
      $(element).val(fill);
      
      $("#SearchServiceButton").attr('service-id',suggestion.service_id);
      $("#SearchServiceButton").attr('service-name',suggestion.service_name);
      $("#SearchServiceButton").attr('service-price-inr',suggestion.service_price_inr);
      $("#SearchServiceButton").attr('service-gst-percentage',suggestion.service_gst_percentage);
      $("#SearchServiceButton").attr('service-est-time',suggestion.service_est_time);
    }

	});
</script>	
<script>
	function getInputValue(){
		// validation of payment via full payment from loyalty wallet
		var pay = document.getElementById("pay_type");
		var p = pay.options[pay.selectedIndex].value;
		var amt= document.getElementById("amt_rc").value;
	
		if(p=="loyalty_wallet"){
			if(amt > <?=$individual_customer['customer_rewards']?>)
				alert("Low Loyalty Balance. Please select other payment method.");
				document.getElementById('pay_type').focus();
				return false;
		}
	}
	///
	function validateSplitPayment(){
	//split payment
		var pay = document.getElementById("split_pay_type");
		var p = pay.options[pay.selectedIndex].value;
		var a= document.getElementById("amount_recieved").value;
	
		if(p=="loyalty_wallet"){
			if(a > <?=$individual_customer['customer_rewards']?>)
				alert("Low Loyalty Balance. Please select other payment method.");
				document.getElementById('split_pay_type').focus();
				return false;
		}
	}
</script>
<script>
   var offer = <?=$count?>;
  function redeem_offer(id)
  {
    var button = <?=$count?>;
    for(var i = 1 ; i< button;i++)
    {
      if(i == id)
      {
        var id = id;
       
        // alert(points);
        var parameter = {
          offer_id : $('#'+id).attr('offer_selected'),
          customer_id : $('#'+id).attr('individual_customer'),
          // points : $('#'+id).closest('tr').children('td.points').text(),
          cashier_id : $('#'+id).attr('cashier_id')
          }
          $("#RedeemOffer").validate({
            errorElement: "div",
            rules: {
                "points" : {
                  required : true,
                },
                "offers" : {
                  required : true
                }
            },
            submitHandler: function(form) { 
              $.ajax({
                url: "<?=base_url()?>Cashier/AddToCartRedeemPoints",
                data: parameter,
                type: "POST",
                // crossDomain: true,
                cache: false,
                // dataType : "json",
                success: function(data) {
                  if(data.success == 'true'){
                    event.preventDefault();
                    $(this).blur();
					toastr["success"](data.message,"", {
                    positionClass: "toast-top-right",
                    progressBar: "toastr-progress-bar",
                    newestOnTop: "toastr-newest-on-top",
                    rtl: $("body").attr("dir") === "rtl" || $("html").attr("dir") === "rtl",
                    timeOut: 1000
                    });
                    // $('#ModalVerifyOtp').show();
					window.location.reload();
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
      }   
    }
  }
  
</script>
<script>
		$(document).on('click','.service_search',function(event){
        this.blur();
        event.preventDefault();
        $('#SearchServiceButton').click();
        // $('#ServiceDetailsButton').click();
    })
  function update_expert_name(expert_id,service_id,customer_package,customer_id)
  {
    // alert($(this).attr('service-id'));
    // $("#LoyaltyServiceEditDetails").validate({
    //   errorElement: "div",
      
      // submitHandler : function(form) {
        var parameters = {'service_expert_id' : expert_id,
        'service_id' : service_id , 
        'customer_package_profile_id' :customer_package,
        'customer_id' : customer_id};
        $.ajax({
          url: "<?=base_url()?>Cashier/EditExpertInCart",
          data: parameters,
          type: "POST",
          // crossDomain: true,
          cache: false,
          // dataType : "json",
          success: function(data) {
            if(data.success == 'true'){
              // $("#ModalServiceEditDetails").modal('hide'); 
              window.location.reload();
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
      // },
    // });
  }
</script>
