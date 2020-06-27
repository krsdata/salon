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
				<!--Search Area Again-->
				<h3 class="mb-3">Buy Package</h3>	
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<div class="input-group">
								<input type="text" placeholder="Search Customer by name/contact no." class="form-control" id="SearchCustomer" autofocus>
								<span class="input-group-append">
						      <button class="btn btn-success" type="button" id="SearchCustomerButton" Customer-Id="Nothing">Go</button>
						    </span>
							</div>
						</div>
					</div>
					
					<div class="col-md-2">
						<button data-toggle="modal" data-target="#ModalAddCustomer" class="btn btn-info">Add</button>
						<!--MODAL AREA START---->
						<?php
							$this->load->view('cashier/cashier_success_modal_view');
							$this->load->view('cashier/cashier_error_modal_view');
						?>
						
						<div id="ModalAddCustomer" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
							<div role="document" class="modal-dialog modal-md">
								<div class="modal-content">
									<div class="modal-header" style="background-color:#47bac1;">
										<h5 class="modal-title text-white">Add New Customer</h5>
										<button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">x</span></button>
									</div>
									<div class="modal-body m-3">
										<form action="#" method="POST" id="AddNewCustomer">
											<div class="form-row">
												<div class="form-group col-md-6">
													<label>Title</label>
													<select class="form-control" name="customer_title">
														<option value="Mr.">Mr.</option>
														<option value="Ms.">Ms.</option>
													</select>
												</div>
												<div class="form-group col-md-6">
													<label class="form-label">Name</label>
													<input type="text" placeholder="Enter Name" class="form-control" name="customer_name">
												</div>
											</div>
											<div class="form-row">
												<div class="form-group col-md-6">
													<label class="form-label">Mobile</label>
													<input type="text" data-mask="0000000000" placeholder="Enter Mobile" class="form-control" name="customer_mobile">
												</div>
												<div class="form-group col-md-6">
													<label class="form-labe">Email</label>
													<input type="email" class="form-control" placeholder="Enter Email-ID" name="customer_email">
												</div>
											</div>
											<div class="form-row">												
												<div class="form-group col-md-6">
													<label class="form-label">Date of birth</label>
													<input type="text" class="form-control date" name="customer_dob">
												</div>
												<div class="form-group col-md-6">
													<label class="form-label">Date of Aniversary</label>
													<input type="text" class="form-control date" name="customer_doa">
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

						<div id="ModalPackageDetails" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
							<div role="document" class="modal-dialog modal-md">
								<div class="modal-content">
									<div class="modal-header">
										<h5 class="modal-title text-white">Package Details</h5>
										<button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
									</div>
									<div class="modal-body">
										<form action="#" method="POST" id="PackageDetails">
										    <div class="form-row">
										        <div class="form-group col-md-6">
												    <label>Customer Name</label>
														<input class="form-control" type="text" name="customer_name" readonly="true">
													</div>
    											<div class="form-group col-md-6">
    												<label class="form-label">Package Name</label>
    												<input type="text" class="form-control" name="package_name" readonly>
    											</div>
										    </div>
										<div class="form-row">	
											<div class="form-group col-md-6">
												<label class="form-label">Package Type</label>
												<input type="text" class="form-control" name="package_type" readonly>
											</div>
											<div class="form-group col-md-6">
												<label class="form-label">Package Validity (In months)</label>
												<input type="number" class="form-control" name="package_validity" readonly>
											</div>
											</div>
											<div class="form-row">
											<div class="form-group col-md-6">
												<label class="form-label">Discount Absolute</label>
												<input type="number" class="form-control" name="package_discount_absolute" placeholder="Enter absolute value only" min="0">
											</div>
											<div class="form-group col-md-6">
												<label class="form-label">Price of Package<small> (All inclusive of taxes)</small></label>
												<input type="number" class="form-control" name="package_price_inr" readonly>
											</div>
											</div>
											<div class="form-row">
											<div class="form-group col-md-6">
												<label class="form-label">Package GST</label>
												<input type="number" class="form-control" name="package_gst" readonly>
											</div>
											<div class="form-group col-md-6">
												<label class="form-label">Select Employee</label>
												<select name="emp_id" class="form-control">
													<option selected disabled>Select Employee</option>
													<?php
														foreach($expert as $expert)
														{
													?>
															<option value="<?=$expert['employee_id']?>,<?=$expert['employee_nick_name']?>"><?=$expert['employee_nick_name']?></option>
													<?php
														}
													?>
												</select>
											</div>
											<div class="form-group col-md-6">
												<label class="form-label">Total Value</label>
												<input type="number" class="form-control" name="package_final_value" readonly>
											</div>
											<div class="form-group">
												<input class="form-control" type="hidden" name="salon_package_id" readonly="true">
											</div>
											
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
						<!----MODAL AREA END--->
					</div>
					<div class="col-md-3">
						<div class="form-group">
						<?php
							if(isset($Package_Customer) || !empty($Package_Customer)){?>
								<label style="background:lightskyblue;font-weight:bolder;padding:4px"><?=$Package_Customer['customer_name']?></label>
							<?php 
							}
						?>
						</div>
					</div>
				</div>
				<!--Search Area Ends--->
				<div class="row">				
					<div class="col-md-12">
						<!--MODAL AREA START-->
						<div id="ModalWalletPackageDetails" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
							<div role="document" class="modal-dialog modal-md">
								<div class="modal-content">
									<div class="modal-header" style="background-color:#47bac1;"	>
										<h5 class="modal-title text-white font-weight-bold" >Package Details</h5>
										<button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
									</div>
									<div class="modal-body mb-3">
										<table class="table table-striped table-responsive">
											<thead>
												<th>Package Name</th>
												<th>Upfront Amount</th>
												<th>Creation Date</th>
												<th>Wallet Balance</th>
												<th>Validity (Months)</th>
											</thead>
											<tbody id="SingleWalletPackageDetails">
											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div> 

						<div id="ModalServicePackageDetails" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
							<div role="document" class="modal-dialog modal-sm">
								<div class="modal-content">
									<div class="modal-header" style="background-color:#47bac1;"	>
										<h5 class="modal-title text-white font-weight-bold" >Package Details</h5>
										<button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
									</div>
									<div class="modal-body ">
										<table class="table table-striped table-responsive">
											<thead>
												<th>Sno.</th>
												<th>Bundled Services</th>
												<th>Count</th>	
											</thead>
											<tbody id="SingleServicePackageDetails">
											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>
				
						<div id="ModalDiscountPackageDetails" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
							<div role="document" class="modal-dialog modal-md">
								<div class="modal-content">
									<div class="modal-header" style="background-color:#47bac1;"	>
										<h5 class="modal-title text-white font-weight-bold" >Package Details</h5>
										<button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
									</div>
									<div class="modal-body ">
										<table class="table table-striped table-responsive">
											<thead>
												<th>Sno.</th>
												<th>Bundled Services</th>
												<th>Discount</th>
												<th>Monthly Discount</th>
												<th>Birthday Discount</th>
												<th>Anniversary Discount</th>
												<th>Count</th>
											</thead>
											<tbody id="SingleDiscountPackageDetails">
											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>
						<!--MODAL AREA END-->
						<div class="row">
							<div class="col-md-7">
								<div class="tab">
									<ul class="nav nav-tabs" role="tablist">
										<li class="nav-item one"><a class="nav-link active" href="#tab-1" data-toggle="tab" role="tab" aria-selected="false">Wallet</a></li>
										<li class="nav-item two"><a class="nav-link" href="#tab-2" data-toggle="tab" role="tab" aria-selected="false">Services</a></li>
										<li class="nav-item three"><a class="nav-link" href="#tab-3" data-toggle="tab" role="tab" aria-selected="true">Membership</a></li>
										<li class="nav-item three"><a class="nav-link" href="#tab-4" data-toggle="tab" role="tab" aria-selected="true">Special Membership</a></li>
									</ul>
									<div class="tab-content">
										<div class="tab-pane active" id="tab-1" role="tabpanel">
											<div class="row">
												<?php
													foreach ($salon_packages as $package) {
														if($package['salon_package_type'] == "Wallet"){
												?>
												<div class="col-md-4 col-sm-6 d-flex">
													<div class="card packageCard">
														<div class="card-body my-2" style="padding:.25rem;">
															<div class="row d-flex align-items-center mb-2">
																<div class="col-12">
																	<p class="d-flex font-weight-light text-wrap"><strong><?=$package['salon_package_name']?></strong></p>
																</div>
																<div class="col-12">
																	<p class="d-flex mb-0 font-weight-light" style="color:red;">Upfront Amt. : Rs <?=$package['salon_package_upfront_amt']?></p>
																	<p class="d-flex mb-0 font-weight-light">Wallet Bal. : Rs <?=$package['virtual_wallet_money']?></p>
																</div>
															</div>
															<div class="row">
																<div class="col-md-6">
																		<?php
																			if(isset($Package_Customer) && !empty($Package_Customer)){
																					$customer_name = $Package_Customer['customer_name'];
																			}
																			else{
																				$customer_name = "";
																			}
																		?>
																		<a href="#" class="btn btn-info btn-sm BuyPackageBtn" salon_package_id="<?=$package['salon_package_id']?>" customer_name="<?=$customer_name?>" package_price="<?=$package['salon_package_price']?>" package_name="<?=$package['salon_package_name']?>" package_type="<?=$package['salon_package_type']?>" package_validity="<?=$package['salon_package_validity']?>" package_gst="<?=$package['service_gst_percentage']?>" package_total_value="<?=$package['salon_package_upfront_amt']?>">Buy</a>
																</div>
																<div class="col-md-6">
																	<button class="btn btn-success btn-sm OpenWalletDetails" salon_package_id="<?=$package['salon_package_id']?>">View</button>
																</div>
															</div>
														</div>
													</div>
												</div>
												
												<?php
													}
												}
												?>
											</div>
										</div>
										<div class="tab-pane" id="tab-2" role="tabpanel">
											<div class="row">
												<?php
														foreach ($salon_packages as $package) {
															if($package['salon_package_type'] == "Services"){
													?>
													<div class="col-md-4 col-sm-6 d-flex">
														<div class="card packageCard">
															<div class="card-body my-2"  style="padding:.25rem;">
																<div class="row d-flex align-items-center mb-2">
																	<div class="col-12">
																		<p class="d-flex font-weight-light text-wrap"><strong><?=$package['salon_package_name']?></strong></p>
																	</div>
																	
																	<div class="col-12">
																		<p class="d-flex mb-0 font-weight-light" style="color:red;">Upfront Amt: Rs <?=$package['salon_package_upfront_amt']?></p>
																		<p class="d-flex mb-0 font-weight-light">Validity: <?=$package['salon_package_validity']?> months</p>
																	</div>
																</div>
																<div class="row">
																	<div class="col-md-6">
																		<?php
																			if(isset($Package_Customer) && !empty($Package_Customer)){
																					$customer_name = $Package_Customer['customer_name'];
																			}
																			else{
																				$customer_name = "";
																			}
																		?>
																		<a href="#" class="btn btn-info btn-sm BuyPackageBtn" salon_package_id="<?=$package['salon_package_id']?>" customer_name="<?=$customer_name?>" package_price="<?=$package['salon_package_price']?>" package_name="<?=$package['salon_package_name']?>" package_type="<?=$package['salon_package_type']?>" package_validity="<?=$package['salon_package_validity']?>" package_gst="<?=$package['service_gst_percentage']?>" package_total_value="<?=$package['salon_package_upfront_amt']?>" >Buy</a>
																	</div>
																	<div class="col-md-6">
																		<button class="btn btn-success btn-sm OpenServiceDetails" salon_package_id="<?=$package['salon_package_id']?>">View</button>
																	</div>
																</div>
															</div>
														</div>
													</div>
													<?php
														}
													}
													?>
											</div>
										</div>
										<div class="tab-pane" id="tab-3" role="tabpanel">
											<div class="row">
												<?php
													foreach ($salon_packages as $package) {
														if($package['salon_package_type'] == "Discount"){
												?>
												<div class="col-md-4 col-sm-6 d-flex">
													<div class="card packageCard">
														<div class="card-body my-2"  style="padding:.25rem;">
															<div class="row d-flex align-items-center mb-2">
																<div class="col-12">
																	<p class="d-flex font-weight-light text-wrap"><strong><?=$package['salon_package_name']?></strong></p>
																</div>
																
																<div class="col-12">
																	<p class="d-flex mb-0 font-weight-light" style="color:red;">Upfront Amt: Rs <?=$package['salon_package_upfront_amt']?></p>
																	<p class="d-flex mb-0 font-weight-light">Validity: <?=$package['salon_package_validity']?> months</p>
																</div>
															</div>
															<div class="row">
																<div class="col-md-6">
																	<?php
																			if(isset($Package_Customer) && !empty($Package_Customer)){
																					$customer_name = $Package_Customer['customer_name'];
																			}
																			else{
																				$customer_name = "";
																			}
																		?>
																		<a href="#" class="btn btn-info btn-sm BuyPackageBtn" salon_package_id="<?=$package['salon_package_id']?>" customer_name="<?=$customer_name?>" package_price="<?=$package['salon_package_price']?>" package_name="<?=$package['salon_package_name']?>" package_type="<?=$package['salon_package_type']?>" package_validity="<?=$package['salon_package_validity']?>" package_gst="<?=$package['service_gst_percentage']?>" package_total_value=<?=$package['salon_package_upfront_amt']?> >Buy</a>
																</div>
																<div class="col-md-6">
																	<button class="btn btn-success btn-sm OpenDiscountDetails" salon_package_id="<?=$package['salon_package_id']?>">View</button>
																</div>
															</div>
														</div>
													</div>
												</div>
												<?php
													}
												}
												?>
											</div>
										</div>
										<div class="tab-pane" id="tab-4" role="tabpanel">
											<div class="row">
												<?php
													foreach ($salon_packages as $package) {
														if($package['salon_package_type'] == "special_membership"){
												?>
												<div class="col-md-4 col-sm-6 d-flex">
													<div class="card packageCard">
														<div class="card-body my-2"  style="padding:.25rem;">
															<div class="row d-flex align-items-center mb-2">
																<div class="col-12">
																	<p class="d-flex font-weight-light text-wrap"><strong><?=$package['salon_package_name']?></strong></p>
																</div>
																
																<div class="col-12">
																	<p class="d-flex mb-0 font-weight-light" style="color:red;">Upfront Amt: Rs <?=$package['salon_package_upfront_amt']?></p>
																	<p class="d-flex mb-0 font-weight-light">Validity: <?=$package['salon_package_validity']?> months</p>
																</div>
															</div>
															<div class="row">
																<div class="col-md-6">
																	<?php
																			if(isset($Package_Customer) && !empty($Package_Customer)){
																					$customer_name = $Package_Customer['customer_name'];
																			}
																			else{
																				$customer_name = "";
																			}
																		?>
																		<a href="#" class="btn btn-info btn-sm BuyPackageBtn" salon_package_id="<?=$package['salon_package_id']?>" customer_name="<?=$customer_name?>" package_price="<?=$package['salon_package_price']?>" package_name="<?=$package['salon_package_name']?>" package_type="<?=$package['salon_package_type']?>" package_validity="<?=$package['salon_package_validity']?>" package_gst="<?=$package['service_gst_percentage']?>" package_total_value=<?=$package['salon_package_upfront_amt']?> >Buy</a>
																</div>
																<div class="col-md-6">
																	<button class="btn btn-success btn-sm OpenDiscountDetails" salon_package_id="<?=$package['salon_package_id']?>">View</button>
																</div>
															</div>
														</div>
													</div>
												</div>
												<?php
													}
												}
												?>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="col-md-5">
							<?php
								if(!isset($Package_Customer) || empty($Package_Customer)){
							?>
								<div class="alert alert-danger alert-outline alert-dismissible" role="alert" style="margin-top: 50px;">
									<button type="button" class="close" data-dismiss="alert" aria-label="Close">
		            	<span aria-hidden="true">×</span>
		          		</button>
									<div class="alert-icon">
										<i class="far fa-fw fa-bell"></i>
									</div>
									<div class="alert-message">
										<strong>Hello there!</strong>No customer right now!
									</div>
								</div>
							<?php
								}
								else{
									if(isset($Package_Customer)){
								// 		print("<pre>".print_r($Package_Customer,true)."</pre>");
								// 		echo "\n";
									}

									if(isset($package_cart)){
										// print("<pre>".print_r($package_cart,true)."</pre>");
									}


									if(isset($package_payment)){
								// 		print("<pre>".print_r($package_payment,true)."</pre>");
									}

									if(isset($package_cart)){
							?>
								<div class="row mt-5">
									<div class="col-md-12">
										<div class="card">
											<div class="card-body">
												<table class="table table-hover fixed_headers_packages table-sm" style="width:100%;">
													<thead>
														<tr>
															<th style="width:20%;">Package Name</th>
															<th class="text-center">Rate</th>
															<th class="text-center">GST</th>
															<th class="text-center">Discount</th>
															<th class="text-center">Total</th>
															<th class="text-center">Expert</th>
															<th class="text-center">Type</th>
															<th class="text-center">Delete</th>
														</tr>
													</thead>
													<tbody>
														<tr>
															<td  style="width:20%;"><?=$package_cart['package_name']?></td>
															<td class="text-center"><?=round($package_cart['package_price_inr'])?></td>
															<td class="text-center"><?php echo round($package_cart['package_gst']);?></td>
															<td class="text-center"><?=$package_cart['package_discount_absolute']?></td>
															<td class="text-center"><?=$package_cart['package_final_value']?></td>
															<td class="text-center"><?=$package_cart['employee_name']?></td>
															
															<td class="text-center"><?=$package_cart['package_type']?></td>
															<td class="table-action">
													      <button type="button" class="btn btn-danger btn-small package-cart-delete-btn" service-id="<?=$package_cart['salon_package_id']?>">
													        <i class="fa fa-trash" aria-hidden="true"></i>
													      </button>
															</td>	
														</tr>	
													</tbody>
												</table>
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12">
										<div class="card">
											<div class="card-body">
												<!--MODAL Section for Payments-->

												<div id="ModalFullPayment" tabindex="-1" role="dialog" aria-hidden="true" class="modal">
													<div role="document" class="modal-dialog modal-sm">
														<div class="modal-content">
															<div class="modal-header">
																<h5 class="modal-title text-white">Full Payment</h5>
																<button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
															</div>
															<div class="modal-body m-3">
																<form action="#" method="POST" id="FullPaymentInfo">
																	<div class="form-group">
																		<label class="form-label">Payment Type</label>
																		<select name="payment_type" class="form-control">
																			<option value="Cash">Cash</option>
																			<option value="Credit_Card">Credit Card</option>
																			<option value="Debit_Card">Debit Card</option>
																			<option value="Paytm">Paytm</option>
																			<option value="Phonepe">Phonepe</option>
																			<option value="Google_Pay">Google Pay</option>
																		</select>
																	</div>
																	<div class="form-group">
																		<label class="form-label">Total Amount</label>
																		<input type="number" class="form-control" name="total_final_bill" readonly>
																	</div>
																	<div class="form-group">
																		<label class="form-label">Amount Received</label>
																		<input type="number" class="form-control" name="total_amount_received" min="0">
																	</div>
																	<div class="form-group">
																		<label class="form-label">Balance to be paid back</label>
																		<input type="number" class="form-control" name="balance_to_be_paid_back" readonly>
																	</div>
																	<div class="form-group">
																		<label class="form-label">Pending Amount</label>
																		<input type="number" class="form-control" name="pending_amount" readonly min="0">
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

												<div id="ModalSplitPayment" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
													<div role="document" class="modal-dialog modal-sm">
														<div class="modal-content">
															<div class="modal-header" style="background-color:#47bac1;">
																<h5 class="modal-title text-white">Split Payment</h5>
																<button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
															</div>
															<div class="modal-body m-3">
																<form action="#" method="POST" id="SplitPaymentInfo">
																	<div class="form-row">	
																		<div class="form-group col-md-6">
																			<label class="form-label">Total Amount</label>
																			<input type="number" class="form-control" name="total_final_bill" readonly>
																		</div>
																		<div class="form-group col-md-6">
																			<label class="form-label">Amount Received</label>
																			<input type="number" class="form-control" name="total_amount_received" readonly min="0">
																		</div>
																	</div>
																	<div class="form-row">
																		<div class="form-group col-md-6">
																			<label class="form-label">Balance to be paid back</label>
																			<input type="number" class="form-control" name="balance_to_be_paid_back" readonly>
																		</div>
																		<div class="form-group col-md-6">
																			<label class="form-label">Pending Amount</label>
																			<input type="number" class="form-control" name="pending_amount" readonly min="0">
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
															        			<select name="payment_type[]" class="form-control">
																					<option value="Cash">Cash</option>
																					<option value="Credit_Card">Credit Card</option>
																					<option value="Debit_Card">Debit Card</option>
																					<option value="Paytm">Paytm</option>
																					<option value="Phonepe">Phonepe</option>
																					<option value="Google_Pay">Google Pay</option>
																				</select>
														        			</div>
														        		</td>
														        		<td>
														        			<div class="form-group">
																				<label class="form-label">Amount Received</label>
																				<input type="number" placeholder="Amount in INR" class="form-control" name="amount_received[]">
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
												<div id="ModalDodarDetail" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
													<div role="document" class="modal-dialog modal-sm">
														<div class="modal-content">
															<div class="modal-header">
																<h5 class="modal-title text-white">Add Payer Detail</h5>
																<button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
															</div>
															<div class="modal-body m-3">
															<form action="#" method="POST" id="GiftDonorDetails">	
																	<div class="form-row">	
																		<div class="form-group col-md-6">
																			<label class="form-label">Enter Name</label>
																			<input type="text" name="donar_name" id="donar_name" placeholder="Enter Name"  class="form-control">
																		</div>
																		<div class="form-group col-md-6">
																			<label class="form-label">Mobile Number</label>
																			<input type="number" name="donar_mob" id="donar_mob" placeholder="Mobile Number"  class="form-control">
																		</div>
																		<div class="form-group col-md-6">	
																			<button type="submit" class="btn btn-primary" id="submitdonor">Submit</button>
																		</div>	
																	</div>	
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

												<!--MODAL AREA ENDS-->
												<!-- Modal -->
												<div id="giftModal" class="modal" role="dialog">
													<div class="modal-dialog">
														<!-- Modal content-->
														<div class="modal-content">
															<div class="modal-header">
																<button type="button" class="close" data-dismiss="modal">&times;</button>
																<h4 class="modal-title">Add Donar Detail</h4>
															</div>
															<div class="modal-body">
																<form action="#" method="POST" id="GiftDonorDetails">	
																	<div class="form-row">	
																		<div class="form-group col-md-6">
																			<label class="form-label">Enter Name</label>
																			<input type="text" name="donar_name" id="donar_name"  class="form-control">
																		</div>
																		<div class="form-group col-md-6">
																			<label class="form-label">Number</label>
																			<input type="number" name="donar_mob" id="donar_mob"  class="form-control">
																		</div>
																		<div class="form-group col-md-6">	
																			<input type="submit" class="btn btn-primary" id="submitdonor">
																		</div>	
																	</div>	
																</form>	
															</div>
															<div class="modal-footer">
																<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
															</div>
														</div>
													</div>
												</div>

												<div class="mb-3">
													<table class="table table-hover table-sm">
														<tbody>
														<tr>
															<td>
																<label class="custom-control custom-checkbox">
																<?php if(!empty($package_cart['donar_name']) || !empty($package_cart['donar_mob'])){?>	
																			<input type="checkbox" class="custom-control-input" id="SendSMS">
																			<span class="custom-control-label">Send SMS</span>
																<?php
																		}
																		else{
																?>			
																			<input type="checkbox" class="custom-control-input" checked="true" id="SendSMS" checked>
																			<span class="custom-control-label">Send SMS</span>
																<?php
																		}
																?>	
																</label>
															</td>
															<td>
																<!-- <label class="custom-control custom-checkbox">
																	<input type="checkbox" class="custom-control-input" checked="true" id="cashback">
																	<span class="custom-control-label">Credit Cashback</span>
																</label> -->
															</td>
															<td>
																<label class="custom-control custom-checkbox">
																<?php if(!empty($package_cart['donar_name']) || !empty($package_cart['donar_mob'])){?>
																	<input type="checkbox" class="custom-control-input" id="gift" onclick="mygift()" checked>
																	<span class="custom-control-label">Gift</span>
																<?php
																}
																else{
																?>	
																	<input type="checkbox" class="custom-control-input" id="gift" onclick="mygift()">
																	<span class="custom-control-label">Gift</span>
																<?php
																}
																?>
																</label>
															</td>
														</tr>
														<tr>
																<td>Total Bill</td>
																<td colspan="2"><i class="fas fa-fw fa-rupee-sign" aria-hidden="true"></i>
																<?php
																	
																	$total_bill_before = ($package_cart['package_price_inr']);
																	
																	$discount_given = $package_cart['package_discount_absolute'];
																	
																	$actual_bill = ($package_cart['package_final_value']);
																	
																	$package_cart_json = json_encode($package_cart);

																	$payment_info_json = "";
																	
																	if(isset($package_payment['full_payment_info']) || isset($package_payment['split_payment_info'])){
																		
																		if(!empty($package_payment['full_payment_info'])){
																			$payment_info_json = json_encode($package_payment['full_payment_info']['payment_type']);
																		}

																		if(!empty($package_payment['split_payment_info'])){
																			$payment_info_json = json_encode($package_payment['split_payment_info']['multiple_payments']);
																		}
																	}
																	
																	echo round($total_bill_before);
																?>
																</td>
															</tr>
															<tr>
																<td>GST</td>
																<td colspan="2"><i class="fas fa-fw fa-rupee-sign" aria-hidden="true"></i>
																	<?= round($package_cart['package_gst'])?>
																</td>
															</tr>
															<tr>
																<td>Discount</td>
																<td colspan="2"><i class="fas fa-fw fa-rupee-sign" aria-hidden="true"></i>
																	<?=$discount_given?>
																</td>
															</tr>
															<tr>
																<td><strong>Final Payable Amount</strong></td>
																<td colspan="2"><i class="fas fa-fw fa-rupee-sign" aria-hidden="true"></i>
																	<?=$actual_bill?>
																</td>
															</tr>
														</tbody>
													</table>
												</div>
												<div class="mb-3">
													<a href="<?=base_url()?>Cashier/PrintBillPackage/<?=$Package_Customer['customer_id']?>" class="btn btn-square btn-success" id="Print-Bill" target="_blank">Print Bill</a>
													<div class="btn-group">
														<?php if(!empty($package_payment['full_payment_info']) || !empty($package_payment['split_payment_info'])){ ?>
														
														<button type="button" class="btn btn-square btn-danger dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" disabled>Pay</button>
														
														<div class="dropdown-menu">
															<a class="dropdown-item full-payment-btn" href="#">Full Payment</a>
															<a class="dropdown-item split-payment-btn" href="#">Split Payment</a>
														</div>
														<?php
															}
															else{
														?>
															<button type="button" class="btn btn-danger split-payment-btn">Pay</button>
														<!--<button type="button" class="btn btn-square btn-danger dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Pay</button>-->
														<!--<div class="dropdown-menu">-->
														<!--	<a class="dropdown-item full-payment-btn" href="#">Full Payment</a>-->
														<!--	<a class="dropdown-item split-payment-btn" href="#">Split Payment</a>-->
														<!--</div>-->
												  <?php } ?>
													</div>
													<?php if(!empty($package_payment['full_payment_info']) || !empty($package_payment['split_payment_info'])){?>
													<button class="btn btn-square btn-primary" id="Settle-Final-Order">Settle Order</button>
													<?php 
														}
														else{
													?>
													<button class="btn btn-square btn-primary" disabled>Settle Order</button>
													<?php
														}
													?>
												</div>
											</div>
										</div>
									</div>
								</div>
							<?php
								}
							}
						  ?>
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
<script>
	$(document).ready(function(){
		/*$(document).ajaxStart(function() {
      $("#load_screen").show();
    });

    $(document).ajaxStop(function() {
      $("#load_screen").hide();
    });
		*/		
		var customer_id = <?php if(isset($Package_Customer)){ echo $Package_Customer['customer_id']; }else{ echo 0;} ?>;


		$(document).on('click',".OpenWalletDetails",function(event) {
      event.preventDefault();
      this.blur(); // Manually remove focus from clicked link.
      var parameters = {
        salon_package_id : $(this).attr('salon_package_id')
      };
      $.getJSON("<?=base_url()?>Cashier/GetPackageWallet", parameters)
      .done(function(data, textStatus, jqXHR) { 
     		var str_2 = ""
     		
   			str_2 += "<tr>";
   			str_2 += "<td>"+data.salon_package_name+"</td>";
   			str_2 += "<td>"+data.salon_package_upfront_amt+"</td>";
   			str_2 += "<td>"+data.salon_package_date+"</td>";
   			str_2 += "<td>"+data.virtual_wallet_money+"/-</td>";
   			str_2 += "<td>"+data.salon_package_validity+"</td>";
   			str_2 += "</tr>";
     		
       	$("#SingleWalletPackageDetails").html("").html(str_2);
        $("#ModalWalletPackageDetails").modal('show');
    	})
    	.fail(function(jqXHR, textStatus, errorThrown) {
        console.log(errorThrown.toString());
   		});
    });

		$(document).on('click',".OpenServiceDetails",function(event) {
      event.preventDefault();
      this.blur(); // Manually remove focus from clicked link.
      var parameters = {
        salon_package_id : $(this).attr('salon_package_id')
      };
      $.getJSON("<?=base_url()?>Cashier/GetPackage", parameters)
      .done(function(data, textStatus, jqXHR) { 
     		var str = ""
     		for(var i=0;i<data.length;i++){
     			str += "<tr>";
     			str += "<td>"+(i+1)+"</td>";
     			str += "<td>"+data[i].service_name+"</td>";
     			str += "<td>"+data[i].service_count+"</td>";
     			str += "</tr>";
     		}


       	$("#SingleServicePackageDetails").html("").html(str);
        $("#ModalServicePackageDetails").modal('show');
    	})
    	.fail(function(jqXHR, textStatus, errorThrown) {
        console.log(errorThrown.toString());
   		});
    });

    $(document).on('click',".OpenDiscountDetails",function(event) {
      event.preventDefault();
      this.blur(); // Manually remove focus from clicked link.
      var parameters = {
        salon_package_id : $(this).attr('salon_package_id')
      };
      $.getJSON("<?=base_url()?>Cashier/GetPackage", parameters)
      .done(function(data, textStatus, jqXHR) { 
       var str_1 = ""
     		for(var i=0;i<data.length;i++){
     			str_1 += "<tr>";
     			str_1 += "<td>"+(i+1)+"</td>";
     			str_1 += "<td>"+data[i].service_name+"</td>";
     			str_1 += "<td>"+data[i].discount_percentage+"%</td>";
				str_1 += "<td>"+data[i].service_monthly_discount+"</td>";
				str_1 += "<td>"+data[i].birthday_discount+"</td>";
				str_1 += "<td>"+data[i].anni_discount+"</td>";
     			str_1 += "<td>"+data[i].service_count+"</td>";
     			str_1 += "</tr>";
     		}
       	$("#SingleDiscountPackageDetails").html("").html(str_1);
        $("#ModalDiscountPackageDetails").modal('show');
    	})
    	.fail(function(jqXHR, textStatus, errorThrown) {
        console.log(errorThrown.toString());
   		});
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
        empty: "No Customer Found!",
        suggestion: _.template("<p><%- customer_name %>, <%- customer_mobile %></p>")
      }
    });
       
    var to_fill = "";

    $("#SearchCustomer").on("typeahead:selected", function(eventObject, suggestion, name) {
      var loc = "#SearchCustomer";
      to_fill = suggestion.customer_name+","+suggestion.customer_mobile;
      setVals(loc,to_fill,suggestion.customer_id);
    });

    $("#SearchCustomer").blur(function(){
      $("#SearchCustomer").val(to_fill);
      to_fill = "";
    });

    function SearchCustomer(query, cb){
      var parameters = {
        query : query
      };
      $.getJSON("<?=base_url()?>Cashier/GetCustomerData", parameters)
      .done(function(data, textStatus, jqXHR) {
        cb(data.message);
      })
      .fail(function(jqXHR, textStatus, errorThrown) {
        // log error to browser's console
        console.log(errorThrown.toString());
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
      var cust_id = $(this).attr('Customer-Id');
      if(cust_id == "Nothing"){
      	$('#centeredModalDanger').modal('show').on('shown.bs.modal', function (e) {
					$("#ErrorModalMessage").html("").html("Please select customer!");
				});
      }
      else{
	      var parameters = {
	        customer_id : $(this).attr('Customer-Id')
	      };
	      
	      $("#SearchCustomerButton").attr('Customer-Id',"Nothing");
	      
	      $.ajax({
	        url: "<?=base_url()?>Cashier/AddCustomerCartToPackage",
	        data: parameters,
	        type: "POST",
	        // crossDomain: true,
					cache: false,
	        // dataType : "json",
	    		success: function(data) {
            if(data.success == 'true'){
							$("#ModalAddAppointment").modal('hide'); 
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
	          maxlength : 15
	        }
	    },
	    submitHandler: function(form) {
				var formData = $("#AddNewCustomer").serialize(); 
				$.ajax({
	        url: "<?=base_url()?>Cashier/AddNewCustomerFromPackage",
	        data: formData,
	        type: "POST",
	        // crossDomain: true,
					cache: false,
	        // dataType : "json",
	    		success: function(data) {
            if(data.success == 'true'){
            	$("#ModalAddCustomer").modal('hide'); 
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

    $(document).on('click','.BuyPackageBtn',function(event){
			event.preventDefault();
			$(this).blur();
			$("#PackageDetails input[name=package_name]").val($(this).attr('package_name'));
			$("#PackageDetails input[name=package_validity]").val($(this).attr('package_validity'));
			$("#PackageDetails input[name=package_price_inr]").val($(this).attr('package_price'));
			$("#PackageDetails input[name=package_final_value]").val($(this).attr('package_total_value'));
			$("#PackageDetails input[name=package_gst]").val($(this).attr('package_gst'));
			$("#PackageDetails input[name=package_discount_absolute]").val(0);
			$("#PackageDetails input[name=salon_package_id]").val($(this).attr('salon_package_id'));
			$("#PackageDetails input[name=customer_name]").val($(this).attr('customer_name'));
			$("#PackageDetails input[name=package_type]").val($(this).attr('package_type'));
			// $("#PackageDetails input[name=expert]").val($(this).attr('emp_name'));
			$("#ModalPackageDetails").modal('show');	
		});

		$(document).on('input','#PackageDetails input[name=package_discount_absolute]',function(){
			
			var price_per_package = parseInt($("#PackageDetails input[name=package_final_value]").val());
			if(parseInt($(this).val()) < price_per_package){
				// $("#PackageDetails input[name=package_final_value]").val(price_per_package - $(this).val());
			}
		
		 if(parseInt($(this).val()) >= price_per_package){
				alert("Please enter value less than package price");
				
				$(this).val(parseInt(0));
				// $("#PackageDetails input[name=package_final_value]").val(price_per_package)
			}			
		});

		$("#PackageDetails").validate({
	  	errorElement: "div",
	    rules: {
	        "package_name" : {
            required : true
	        },
	        "customer_name" : {
            required : true
	        },
	        "package_discount_absolute" : {
	          required : true,
	          digits : true
	        },
	        "package_price_inr" : {
	        	required : true
	        },
	        "package_final_value" : {
	        	required : true
	        },
	        "package_type":{
	        	required : true
	        }
	    },
	    submitHandler: function(form) {
				var formData = $("#PackageDetails").serialize(); 
				$.ajax({
	        url: "<?=base_url()?>Cashier/AddPackageToCart",
	        data: formData,
	        type: "POST",
	        // crossDomain: true,
					cache: false,
	        // dataType : "json",
	    		success: function(data) {
            if(data.success == 'true'){
            	$("#ModalPackageDetails").modal('hide'); 
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
	        url: "<?=base_url()?>Cashier/FullPaymentPackageInfo",
	        data: formData,
	        type: "POST",
	        // crossDomain: true,
					cache: false,
	        // dataType : "json",
	    		success: function(data) {
            if(data.success == 'true'){
            	$("#ModalFullPayment").modal('hide'); 
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
	        "pending_amount" : {
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
	        url: "<?=base_url()?>Cashier/SplitPaymentPackageInfo",
	        data: formData,
	        type: "POST",
	        // crossDomain: true,
					cache: false,
	        // dataType : "json",
	    		success: function(data) {
            if(data.success == 'true'){
            	$("#ModalSplitPayment").modal('hide'); 
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
		
    $(document).on('click','.package-cart-delete-btn',function(event) {
      event.preventDefault();
      this.blur(); // Manually remove focus from clicked link.
      var parameters = {
        customer_id : customer_id,
        salon_package_id : $(this).attr('salon_package_id'),
      };

      $.ajax({
        url: "<?=base_url()?>Cashier/DeleteCartPackageItem",
        data: parameters,
        type: "GET",
        // crossDomain: true,
				cache: false,
        // dataType : "json",
    		success: function(data) {
          if(data.success == 'true'){
						$('#centeredModalSuccess').modal('show').on('shown.bs.modal', function (e){
							$("#SuccessModalMessage").html("").html(data.message);
						}).on('hidden.bs.modal', function (e) {
								window.location.reload();
						});
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

    $(document).on('click',".full-payment-btn",function(event){
    	event.preventDefault();
      this.blur(); // Manually remove focus from clicked link.

			$("#FullPaymentInfo input[name=total_final_bill]").val(<?php if(isset($actual_bill)){ echo $actual_bill; } ?>);
			$("#FullPaymentInfo input[name=total_amount_received]").val(<?php if(isset($actual_bill)){ echo $actual_bill; } ?>);
			$("#FullPaymentInfo input[name=balance_to_be_paid_back]").val(0);
			$("#FullPaymentInfo input[name=pending_amount]").val(0);
			$("#FullPaymentInfo input[name=customer_id]").val(customer_id);
			
			$("#ModalFullPayment").modal('show');	
    });

    $(document).on('click',".split-payment-btn",function(event){
    	event.preventDefault();
      this.blur(); // Manually remove focus from clicked link.

			$("#SplitPaymentInfo input[name=total_final_bill]").val(<?php if(isset($actual_bill)){ echo $actual_bill; } ?>);
			$("#SplitPaymentInfo input[name=balance_to_be_paid_back]").val(0);
			$("#SplitPaymentInfo input[name=pending_amount]").val(0);
			$("#SplitPaymentInfo input[name=total_amount_received]").val(0);
			$("#SplitPaymentInfo input[name=customer_id]").val(customer_id);
			
			$("#ModalSplitPayment").modal('show');	
    });

    $("#FullPaymentInfo input[name=total_amount_received]").on('input',function(){
    	var billable_amt = parseInt($("#FullPaymentInfo input[name=total_final_bill]").val());
    	
    	
    	if(parseInt($(this).val()) < billable_amt){
    		$("#FullPaymentInfo input[name=pending_amount]").val(billable_amt - $(this).val());
    		$("#FullPaymentInfo input[name=balance_to_be_paid_back]").val(parseInt(0));
    	}
    	else if(billable_amt < $(this).val()){
    		$("#FullPaymentInfo input[name=pending_amount]").val(parseInt(0));
    		$("#FullPaymentInfo input[name=balance_to_be_paid_back]").val($(this).val() - billable_amt);
    	}
			else if(billable_amt == $(this).val()){
    		$("#FullPaymentInfo input[name=balance_to_be_paid_back]").val(parseInt(0));
    		$("#FullPaymentInfo input[name=pending_amount]").val(parseInt(0));
    	}
    	else{
    		$("#FullPaymentInfo input[name=total_final_bill]").val(billable_amt);
    		$("#FullPaymentInfo input[name=pending_amount]").val(parseInt(0));
    		$("#FullPaymentInfo input[name=balance_to_be_paid_back]").val(parseInt(0));	
    	}
    });

    $("#AddRow").click(function(event){
    	event.preventDefault();
      this.blur();
      var rowno = $("#Split-Payment-Info-Table tr").length;

      rowno = rowno+1;
      
      $("#Split-Payment-Info-Table tr:last").after("<tr><td>"+rowno+"</td><td><div class=\"form-group\"><label class=\"form-label\">Payment Mode</label><select name=\"payment_type[]\" class=\"form-control\"><option value=\"Cash\">Cash</option><option value=\"Credit_Card\">Credit Card</option><option value=\"Debit_Card\">Debit Card</option><option value=\"Paytm\">Paytm</option><option value=\"Phonepe\">Phonepe</option><option value=\"Google_Pay\">Google Pay</option></select></div></td><td><div class=\"form-group\"><label class=\"form-label\">Amount Received</label><input type=\"number\" placeholder=\"Amount in INR\" class=\"form-control\" name=\"amount_received[]\"></div></td></tr>");
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
    	$("#SplitPaymentInfo input[name=total_amount_received]").val(sum_amts_recieved);
    	
    	if(sum_amts_recieved < billable_amt){
    		$("#SplitPaymentInfo input[name=pending_amount]").val(billable_amt - sum_amts_recieved);
    		$("#SplitPaymentInfo input[name=balance_to_be_paid_back]").val(parseInt(0));
    	}
    	else if(billable_amt < sum_amts_recieved){
    		$("#SplitPaymentInfo input[name=pending_amount]").val(parseInt(0));
    		$("#SplitPaymentInfo input[name=balance_to_be_paid_back]").val(sum_amts_recieved - billable_amt);
    	}
			else if(billable_amt == sum_amts_recieved){
    		$("#SplitPaymentInfo input[name=balance_to_be_paid_back]").val(parseInt(0));
    		$("#SplitPaymentInfo input[name=pending_amount]").val(parseInt(0));
    	}
    	
    });
//gift  donor Details
	$("#GiftDonorDetails").validate({
	  	errorElement: "div",
	    rules: {
	        "donar_name" : {
            required : true
	        },
	        "donar_mob" : {
            required : true,
            maxlength : 10
	        }
	    },
	    submitHandler: function(form) {
				var formData = $("#GiftDonorDetails").serialize(); 
				$.ajax({
	        url: "<?=base_url()?>Cashier/GiftDonarDetails",
	        data: formData,
	        type: "POST",
	        // crossDomain: true,
					cache: false,
	        // dataType : "json",
	    		success: function(data) {
            if(data.success == 'true'){
            	$("#ModalDodarDetail").modal('hide'); 
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

    $(document).on('click','#Settle-Final-Order',function(event){
    	event.preventDefault();
    	this.blur();

    	var txn_data = {
    		'package_txn_customer_id' : customer_id,
    		'package_txn_discount' : <?php if(isset($discount_given)){ echo $discount_given; }else{ echo 0;}?> ,
    		'package_txn_value' :  <?php if(isset($actual_bill)){ echo $actual_bill; }else{ echo 0;} ?>,
				'package_txn_cashier' : <?php echo $cashier_details['employee_id']; ?>,
				'sender_id' : '<?php echo $cashier_details['business_outlet_sender_id']; ?>',
				'api_key' : '<?php echo $cashier_details['api_key']; ?>'

    	};

    	var cart_data = {};
    	var txn_settlement = {};
    	var customer_pending_data = {}; 

    	<?php if(isset($package_cart_json)): ?>
    		cart_data = <?php echo $package_cart_json; ?>;
    	<?php endif; ?>

    	<?php 
    	if(!empty($package_payment['full_payment_info']) || !empty($package_payment['split_payment_info'])){ 
    		if(!empty($package_payment['full_payment_info'])) { 
    	?>

    	txn_settlement = {
    		'settlement_way' : 'Full Payment',
    		'payment_mode' : <?php echo $payment_info_json; ?>,
    		'amount_received' : <?=$package_payment['full_payment_info']['total_amount_received']?>,
    		'balance_paid_back' : <?=$package_payment['full_payment_info']['balance_to_be_paid_back']?>
    	};

    	customer_pending_data = {
    		'customer_id' : customer_id,
    		'pending_amount' : <?=$package_payment['full_payment_info']['pending_amount']?>,
				'customer_mobile':<?=$Package_Customer['customer_mobile']?>
    	}

    	<?php
    		}

    		if(!empty($package_payment['split_payment_info'])){
    	?>		
    	txn_settlement = {
    		'settlement_way' : 'Split Payment',
    		'payment_mode' : '<?php echo $payment_info_json; ?>',
    		'amount_received' : <?=$package_payment['split_payment_info']['total_amount_received']?>,
    		'balance_paid_back' : <?=$package_payment['split_payment_info']['balance_to_be_paid_back']?>
    	};

    	customer_pending_data = {
    		'customer_id' : customer_id,
    		'pending_amount' : <?=$package_payment['split_payment_info']['pending_amount']?>,
				'customer_mobile':<?=$Package_Customer['customer_mobile']?>
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

			//Gift Card	
			// if ($('#gift').is(":checked"))
			// 	{
			// 		var 	
			// 	}else{
			// 		cashback=0;
			// 	}			
			
    	var parameters = {
    		'txn_data' : txn_data,
    		'txn_settlement' : txn_settlement,
    		'customer_pending_data' : customer_pending_data,
    		'cart_data' : cart_data,
			'send_sms' : send_sms,
				
    	};

    	$.ajax({
        url: "<?=base_url()?>Cashier/DoPackageTransaction",
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
								timeOut: 1000
							});
							setTimeout(function () { location.reload(1); }, 1000);
          }
          else if (data.success == 'false'){                   
      	    $('#centeredModalDanger').modal('show').on('shown.bs.modal', function (e){
							$("#ErrorModalMessage").html("").html(data.message);
						})
          }
        },
        error: function(data){
					$('#centeredModalDanger').modal('show').on('shown.bs.modal', function (e){
						$("#ErrorModalMessage").html("").html("There is some problem!");
					})
        }
			});
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
<script>
	function mygift() {
  var checkBox = document.getElementById("gift");
 	// alert(checkBox);
  if (checkBox.checked == true){
	$("#ModalDodarDetail").modal('show');
  } else {
    
  }
}
</script>

