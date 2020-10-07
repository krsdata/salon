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
				<h1 class="h3 mb-3">Reports Management</h1>
				<div class="row">
					<div class="col-md-12">
						<div class="card">
							<div class="card-header">
								<form action="#" method="GET" id="GetResults">
									<div class="form-row">
									<div class="form-group col-md-2">
										<select class="form-control" id="groupName" name="group_name">
											<option selected>Report Group</option>
												<option value="Sales">Sales</option>
												<option value="Inventory">Inventory</option>
												<option value="Employee">Employee</option>
											</select>
									</div> 
									<div class=" from-group col-md-2">
										<select class="form-control" id="reportName" name="report_name">
										</select>
									</div>  
									<div class="col-md-1" style="text-align:end;">
										From <i class="fa fa-calendar" style="color:red;"></i>
									</div>
									<div class="from-group col-md-2">
										<input type="text" class="form-control" name="from_date" Placeholder="Enter Date">
									</div>
									<div class="col-md-1" style="text-align:end;">
										To <i class="fa fa-calendar" style="color:red;"></i>
									</div>
									<div class="from-group col-md-2">
										<input type="text" class="form-control" name="to_date" Placeholder="Enter Date">
									</div>
									<div class="from-group col-md-2">
										<button class="btn btn-primary">Submit</button>
									</div>     
									</div>
								</form>

	              <!--Modal Area Start-->
								<div class="modal" id="defaultModalSuccess" tabindex="-1" role="dialog" aria-hidden="true">
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
										</div>
									</div>
								</div>

								<div class="modal" id="defaultModalDanger" tabindex="-1" role="dialog" aria-hidden="true">
									<div class="modal-dialog" role="document">
										<div class="modal-content">
											<div class="modal-header">
												<h5 class="modal-title">Error</h5>
												<button type="button" class="close" data-dismiss="modal" aria-label="Close">
													<span aria-hidden="true">&times;</span>
												</button>
											</div>
											<div class="modal-body m-3">
												<p class="mb-0" id="ErrorModalMessage"></p>
											</div>
											<div class="modal-footer">
												<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
											</div>
										</div>
									</div>
								</div>
								<!--Modal Area Ends--->
	            </div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<div class="card">
							<div class="card-header" style="margin-left:10px;">
								<ul class="nav nav-pills card-header-pills pull-right" role="tablist" style="font-weight: bolder">
									<li class="nav-item">
										<a class="nav-link active" data-toggle="tab" href="#tab-4">Sales Details</a>
									</li>
									<li class="nav-item">
										<a class="nav-link " data-toggle="tab" href="#tab-1">Transactions</a>
									</li>
									<li class="nav-item">
										<a class="nav-link" data-toggle="tab" href="#tab-2">Expert-wise</a>
									</li>
									<li class="nav-item">
										<a class="nav-link" data-toggle="tab" href="#tab-3">Service-wise</a>
									</li>
									<li class="nav-item">
										<a class="nav-link" data-toggle="tab" href="#tab-5">Product-wise</a>
									</li>
									<li class="nav-item">
										<a class="nav-link" data-toggle="tab" href="#tab-6">Last 200 Transactions</a>
									</li>
								</ul>
							</div>
							<div class="card-body">
								<div class="tab-content">
									<div class="tab-pane" id="tab-1" role="tabpanel">
										<div class="accordion" id="accordionExample">
													<div class="card">
														<div class="card-header" id="headingOne">
															<h5 class="card-title my-2">
																<a href="#" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
																	Services Transaction
																</a>
															</h5>
														</div>
														<div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionExample">
															<div class="card-body" style="margin-right:10px;">
															<table class="table table-striped datatables-basic " style="width:100%;text-align:center">
																
																<thead>
																	<tr>
																		<th>Bill No.</th>
																		<th>Mobile No.</th>
																		<th>Name</th>																		
																		<th>MRP Amount</th>
																		<th>Discount</th>
																		<th>Net Amount</th>
																		<th>Total Tax(Rs.)</th>
																		<th>Pending Amount</th>
																		<th>Payment Way</th>
																	</tr>
																</thead>
																<tbody>
																	<?php 
																	if(isset($bill)){
																		$t_mrp=0;
																		$t_disc=0;
																		$t_pending=0;
																		$t_net=0;
																		$t_tax=0;
																	foreach($bill as $bill){
																		$t_mrp+=$bill['mrp_amt'];
																		$t_disc+=$bill['discount'];
																		$t_pending+=$bill['pending_amt'];
																		$t_net+=$bill['net_amt'];
																		$t_tax+=$bill['total_tax'];
																	?>
																	
																	<tr>
																		<td data-target="#BillModal" data-toggle="modal" class="showBilledServices" txn_id="<?=$bill['bill_no']?>"><?=$bill['Txn_id']?></td>												
																		<td><?=$bill['mobile']?></td>
																		<td><?=$bill['name']?></td>
																		<td><?=$bill['mrp_amt']?></td>
																		<td><?=$bill['discount']?></td>
																		<td><?=$bill['net_amt']?></td>
																		<td><?=$bill['total_tax']?></td>
																		<td><?=$bill['pending_amt']?></td>														
																		<?php 
																		if($bill['settlement_way']=='Split Payment'){
																			$var=$bill['payment_way'];
																			$var=json_decode($var);
																			$a=array();	
																			?>
																			<td>
																			<?php										
																			foreach($var as $v){
																				echo $v->payment_type.', ';
																			}
																			?>
																			</td>
																			<?php
																		}else{?>
																			<td><?=$bill['payment_way']?></td>
																		<?php

																		}
																		?>
																	</tr>
																	<?php
																	}
																}
																	?>
																	<tr style="color:blue; font-weight:bold;">
																		<td>Total (Rs.)</td>
																		<td></td>
																		<td></td>
																		<td><?=$t_mrp?></td>
																		<td><?=$t_disc?></td>
																		<td><?=$t_net?></td>
																		<td><?=$t_tax?></td>
																		<td><?=$t_pending?></td>
																		<td></td>
																	</tr>
																</tbody>
															</table>
															</div>
														</div>
													</div>
													<div class="card">
														<div class="card-header" id="headingTwo">
															<h5 class="card-title my-2">
																<a href="#" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true" aria-controls="collapseOne">
										              				Packages Transaction
										           				 </a>
															</h5>
														</div>
														<div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionExample">
															<div class="card-body" >
															<table class="table table-striped datatables-basic " style="width:100%;text-align:center">
																
																<thead>
																	<tr>
																		<th>Bill No.</th>
																		<th>Mobile No.</th>
																		<th>Name</th>
																		<th>MRP Amount</th>
																		<th>Discount</th>
																		<th>Net Amount</th>
																		<!-- <th>Total Tax(Rs.)</th>
																		<th>Pending Amount</th> -->
																		<th>Payment Way</th>
																	</tr>
																</thead>
																<tbody>
																	<?php 
																	if(isset($package_transaction)){
																		$pt_mrp=0;
																		$pt_disc=0;
																		$pt_pending=0;
																		$pt_net=0;
																		
																	foreach($package_transaction as $package_transaction){
																		$pt_mrp+=$package_transaction['mrp_amt'];
																		$pt_disc+=$package_transaction['discount'];
																		$pt_net+=$package_transaction['net_amt'];
																		
																	?>
																	
																	<tr>
																		<td data-target="" data-toggle="modal" class="showBilledPackages" txn_id="<?=$package_transaction['bill_no']?>"><?=$package_transaction['Txn_id']?></td>												
																		<td><?=$package_transaction['mobile']?></td>
																		<td><?=$package_transaction['name']?></td>
																		<td><?=$package_transaction['mrp_amt']?></td>
																		<td><?=$package_transaction['discount']?></td>
																		<td><?=$package_transaction['net_amt']?></td>
																		<!-- <td><?=$package_transaction['total_tax']?></td> -->
																		<!-- <td><?=$package_transaction['pending_amt']?></td>														 -->
																		<?php 
																		if($package_transaction['settlement_way']=='Split Payment'){
																			$var=$package_transaction['payment_way'];
																			$var=json_decode($var);
																			$a=array();	
																			?>
																			<td>
																			<?php										
																			foreach($var as $v){
																				echo $v->payment_type.', ';
																			}
																			?>
																			</td>
																			<?php
																		}else{?>
																			<td><?=$package_transaction['payment_way']?></td>
																		<?php

																		}
																		?>
																	</tr>
																	<?php
																	}
																}
																	?>
																	<tr style="color:blue; font-weight:bold;">
																		<td>Total (Rs.)</td>
																		<td></td>
																		<td></td>
																		<td><?=$pt_mrp?></td>
																		<td><?=$pt_disc?></td>
																		<td><?=$pt_net?></td>
																		<td></td>
																	</tr>
																</tbody>
															</table>
															</div>
														</div>
													</div>
													<div class="card">
														<div class="card-header" id="headingThree">
															<h5 class="card-title my-2">
																<a href="#" data-toggle="collapse" data-target="#collapseThree" aria-expanded="true" aria-controls="collapseOne">
										             				 Total Transaction
										         			   </a>
															</h5>
														</div>
														<div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordionExample">
															<div class="card-body" id="Services-Data">
															<table class="table table-striped" style="width:100%;text-align:center">
																
																	<thead>
																		<tr>
																			<th></th>
																			<th>Total MRP Amount</th>
																			<th>Total Discount</th>
																			<th>Total Net Amount</th>
																			<th>Total Tax(Rs.)</th>
																			<th>Total Pending Amount</th>
																			
																		</tr>
																		<tr style="color:blue; font-weight:bold;">
																			<td>Total (Rs.)</td>
																			<td><?=$t_mrp+$pt_mrp-($cards_data['payment_wise']['virtual_wallet']+$package_payment_wise['virtual_wallet'])?></td>
																			<td><?=$t_disc+$pt_disc?></td>
																			<td><?=($t_net+$pt_net)-($cards_data['payment_wise']['virtual_wallet']+$package_payment_wise['virtual_wallet'])?></td>
																			<td><?=$t_tax?></td>
																			<td><?=$t_pending?></td>
																			<td></td>
																		</tr>
																	</thead>
																</table>
															</div>
														</div>
													</div>
												</div>
										<!-- modal -->
										
										<div class="modal" id="PackageBillModal" tabindex="-1" role="dialog" aria-hidden="true">	
											<div class="modal-dialog modal-dialog-centered modal-md" role="document">
												<div class="modal-content">
													<div class="modal-header">
														<h5 class="modal-title text-white">Package Bill Details</h5>
														<button type="button" class="close" data-dismiss="modal" aria-label="Close">
																<span aria-hidden="true">&times;</span>
														</button>
													</div>
													<div class="modal-body">
													<table id="" style="width:100%;text-align:center">
														<thead>
															<tr>
																<th>Package Name</th>
																<th>Price</th>
																<th>Discount </th>
																<th>Customer Name</th>
																<th>Expert</th>
															</tr>
														</thead>
														<tbody id="package_billed">
															
														</tbody>
													</table>
													</div>
												</div>
											</div>
										</div>
										<!-- end -->
									</div>
									
									<div class="tab-pane" id="tab-2" role="tabpanel">
										
												<div class="accordion" id="accordionExample1">
													<div class="card">
														<div class="card-header" id="headingOneE">														
															<div class="row">
														<div class="col-md-3">
														<h5 class="card-title my-2">
														<a href="#" data-toggle="collapse" data-target="#collapseOneE" aria-expanded="true" aria-controls="collapseOneE">
																	Service Transaction
																</a>
																</h5>
														</div>
														<form class="form-inline" style="width:60%;" method="POST" action="#" id="txnExpertWise">
															<div class="form-group col-md-3">
																<input type="text" class="form-control" name="daterange" value="<?=date('Y-m-d');?>" >
															</div>
															<div class="form-group col-md-2">
																<input type="submit" class="btn btn-primary" id="getExpertTxn"  value="Submit" />
															</div>
														</form>
													</div>	
														</div>
														<div id="collapseOneE" class="collapse show" aria-labelledby="headingOneE" data-parent="#accordionExample1">
															<div class="card-body" style="margin-right:10px;">
															<table class="table table-striped datatables-basic" style="width: 100%;text-align:center" id="expertWiseTable">
																
																<thead>
																	<tr>
																		<th>Expert Name</th>
																		<th>Emp-Id</th>
																		<th>Mobile</th>
																		<th>Revenue</th>
																		<th>Service Count</th>
																	</tr>
																</thead>
																<tbody>
																	<?php foreach($expert as $expert){													
																		?>
																		<tr>
																		<td><?=$expert['expert_name']?></td>
																		<td>EMP_0<?=$expert['emp_id']?></td>
																		<td><?=$expert['employee_mobile']?></td>
																		<td><?=$expert['discounted_amt']?></td>
																		<td><?=$expert['count']?></td>
																		</tr>
																	<?php 
																}?>
																</tbody>
															</table>
															</div>
														</div>
													</div>
													<div class="card">
														<div class="card-header" id="headingTwoE">
															<h5 class="card-title my-2">
																<a href="#" data-toggle="collapse" data-target="#collapseTwoE" aria-expanded="true" aria-controls="collapseOneE">
																	Package Transaction
										            			</a>
															</h5>
														</div>
														<div id="collapseTwoE" class="collapse" aria-labelledby="headingTwoE" data-parent="#accordionExample1">
															<div class="card-body" id="EPackages_Data">
															<table class="table table-hover table-striped datatables-basic" style="width: 100%;text-align:center">
																
																<thead>
																	<tr>
																		<th>Expert Name</th>
																		<th>Emp-Id</th>
																		<th>Mobile</th>
																		<th>Revenue Today(Rs.)</th>
																		
																	</tr>
																</thead>
																<tbody>
																	<?php foreach($package_expert as $pexpert){													
																		?>
																		<tr>
																		<td><?=$pexpert['expert_name']?></td>
																		<td>EMP_0<?=$pexpert['emp_id']?></td>
																		<td><?=$pexpert['employee_mobile']?></td	>
																		<td><?=$pexpert['package_sales']?></td>
																		
																		</tr>
																	<?php 
																}?>
																</tbody>
															</table>
															</div>
														</div>
													</div>
													<div class="card">
														<div class="card-header" id="headingThree">
															<!-- <h5 class="card-title my-2">
																<a href="#" data-toggle="collapse" data-target="#collapseThreeE" aria-expanded="true" aria-controls="collapseOne">
										           				   Total Transaction
										           				 </a>
															</h5> -->
														</div>
														<div id="collapseThreeE" class="collapse" aria-labelledby="headingThree" data-parent="#accordionExample">
															<div class="card-body" id="Services-Data">
															<table class="table table-hover table-striped datatables-basic" style="width: 100%;">
																<head><h3>Total Transaction</h3></head>
																<thead>
																	<tr>
																		<th>Expert Name</th>
																		<th>Emp-Id</th>
																		<th>Mobile</th>
																		<th>Total Revenue Today(Rs.)</th>
																	</tr>
																</thead>
																<tbody>
																	<?php foreach($total_expert_wise as $texpert){													
																		?>
																		<tr>
																		<td><?=$texpert['expert_name']?></td>
																		<td>EMP_0<?=$texpert['emp_id']?></td>
																		<td><?=$texpert['employee_mobile']?></td	>
																		<td><?=$texpert['package_sales']?></td>
																		
																		</tr>
																	<?php 
																}?>
																</tbody>
															</table>
															</div>
														</div>
													</div>
												</div>
									</div>
									<div class="tab-pane" id="tab-3" role="tabpanel">
										<div class="row">
											<div class="col-md-12">
												<div class="card">
													<div class="card-body">
													<button type="button" style="float:right" class="btn btn-primary" id="servicedownload" onclick="exportTableToExcel('servicetable','ServiceSales')">Download</button>
														<table id="servicetable" class="table table-striped" style="width:100%;text-align:center">
															<thead>
																<tr>
																	<th>SrNo</th>
																	<th>Service</th>
																	<th>Category</th>
																	<th>Sub-category</th>
																	<th>Count</th>
																	<!-- <th>MRP</th> -->
																	<!-- <th>Discount</th> -->
																	<th>Net Revenue</th>
																</tr>
															</thead>
															<tbody>
																<?php 
																	$i=1;
																	$total_count=0;
																	$total_net_amt=0;
																	$total_discount=0;
															foreach($service as $service){																
																	$total_count+=$service['count'];
																	// $total_net_amt+=($service['net_amt']);
																	// $total_discount+=$service['discount']
																?>
																<tr>
																	<td><?php echo $i;?></td>
																	<td><?=$service['service']?></td>
																	<td><?=$service['category']?></td>
																	<td><?=$service['sub_category']?></td>
																	<td><?=$service['count']?></td>
																	<!-- <td><?=($service['net_amt']+$service['discount'])?></td> -->
																	<!-- <td><?=$service['discount']?></td> -->
																	<td><?=$service['net_amt']?></td>
																</tr>
																<?php
																$i=$i+1;	
																}
																?>
																<tr style="color:blue; font-weight:bold;">
																	<td>Total (Rs.)</td>
																	<td></td>
																	<td></td>
																	<td></td>
																	<td><?=$total_count?></td>
																	<!-- <td><?=$total_net_amt?></td> -->
																	<!-- <td><?=$total_discount?></td> -->
																	<td><?=($total_net_amt-$total_discount)?></td>
																</tr>
															</tbody>
														</table>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="tab-pane show active" id="tab-4" role="tabpanel">
										<div class="row">	
											<div class="col-md-3">
												<div class="card flex-fill" style="height:144px;">
													<div class="card-header">
														<span class="badge badge-success float-right">Today</span>
														<h5 class="card-title mb-0">Sales in <i class="fa fa-rupee-sign"></i></h5>
													</div>
													<div class="card-body">
														<div class="row d-flex align-items-center mb-1">
															<div class="col-md-12">
															<table style="width:100%;">
																	<tr><th><h5><b>Total</b></h5></th><th>
																	<?php
																	
																	if($cards_data['sales']['sales'] != ''){
																		echo "<h5><b>".($cards_data['sales']['sales']+$card_data[0]['package_sales']+$productsales['productsales']-($cards_data['payment_wise']['virtual_wallet']+$package_payment_wise['virtual_wallet']))."</b></h5>";
																	}
																	else{
																		echo (0+$card_data[0]['package_sales']);
																	}
																	?>
																</th></tr>
																
																<tr><td>Services : </td><td><?=$cards_data['sales']['sales']?></td></tr>
																<tr><td>Packages : </td><td><?=$card_data[0]['package_sales']?></td></tr>
																<tr><td>Products : </td><td><?=$productsales['productsales']?></td></tr>
																<tr><td>VW : </td><td><?=$cards_data['payment_wise']['virtual_wallet']+$package_payment_wise['virtual_wallet']?></td></tr>
																</table>	
															</div>
														</div>
													</div>
												</div>
											</div>
											<div class="col-md-3">
												<div class="card flex-fill" style="height:144px;">
													<div class="card-header">
														<span class="badge badge-success float-right">Today</span>
														<h5 class="card-title mb-0">Collections in <i class="fa fa-rupee-sign"></i></h5>
													</div>
													<div class="card-body">
														<div class="row d-flex align-items-center mb-1">
															<div class="col-md-12">
																<table style="width:100%;">
																<tr><td><h5><b>Total</h5></b></td><td>
																	<?php												
																		echo "<h5><b>".($cards_data['payment_wise']['cash']+$package_payment_wise['cash']+$cards_data['payment_wise']['credit_card']+$package_payment_wise['credit_card']+$cards_data['payment_wise']['debit_card']+$package_payment_wise['debit_card']+$cards_data['payment_wise']['paytm']+$package_payment_wise['paytm']+$cards_data['payment_wise']['phone_pe']+$package_payment_wise['phone_pe']+$cards_data['payment_wise']['google_pay']+$package_payment_wise['google_pay']+$package_payment_wise['virtual_wallet']-$loyalty_payment+$cards_data['payment_wise']['others']+$pending_amount_received-$paid_back)."</h5></b>";
																	?>
																</td></tr>
																<tr><td>Cash : </td><td><?=($cards_data['payment_wise']['cash']+$package_payment_wise['cash']-$paid_back)?></td></tr>
																<tr><td>Cards : </td><td><?=($cards_data['payment_wise']['credit_card']+$package_payment_wise['credit_card']+$cards_data['payment_wise']['debit_card']+$package_payment_wise['debit_card'])?></td></tr>
																<tr><td>W+Others : </td><td><?=($cards_data['payment_wise']['paytm']+$package_payment_wise['paytm']+$cards_data['payment_wise']['phone_pe']+$package_payment_wise['phone_pe']+$cards_data['payment_wise']['google_pay']+$package_payment_wise['google_pay']+$cards_data['payment_wise']['others']+$pending_amount_received)?></td></tr>
																
															
																</table>
															</div>
														</div>
													</div>
												</div>
											</div>
											<div class="col-md-3">
												<div class="card flex-fill" style="height:144px;">
													<div class="card-header">
														<span class="badge badge-success float-right">Today</span>
														<!-- <h5 class="card-title mb-0">Credit Card</h5> -->
														<h5 class="card-title mb-0">Expenses in <i class="fa fa-rupee-sign"></i></h5>
													</div>
													<div class="card-body">
														<div class="row d-flex align-items-center mb-1">
															<div class="col-md-12">
																<div class="d-flex align-items-left mb-0 font-weight-light">											
																	<table style="width:100%;">
																	<tr><td>Today's : </td><td>
																<?php
																	if($cards_data['expenses']['expenses'] != ''){
																		echo $cards_data['expenses']['expenses'];
																	}
																	else{
																		echo "0";
																	}
																	?>
																	</td></tr>
																		<tr>
																			<td>Yesterday : </td><td><?php if($cards_data['yest_expenses']['yest_expenses'] != ''){
																		echo $cards_data['yest_expenses']['yest_expenses'];
																	}
																	else{
																		echo "0";
																	}?></td>
																		</tr>
																	</table>
																</div>
															</div>
														</div>
													</div>
												</div>
											</div>
											<div class="col-md-3">
												<div class="card flex-fill" style="height:144px;">
													<div class="card-header">
														<span class="badge badge-success float-right">Today</span>
														<h5 class="card-title mb-0">Due Amount in <i class="fa fa-rupee-sign"></i></h5>
													</div>
													<div class="card-body">
														<div class="row d-flex align-items-center mb-1">
															<div class="col-md-12">
																<div class="d-flex align-items-center mb-0 font-weight-light">
																	
																	
																	<table style="width:100%;">
																	<tr><td>Generated :</td><td>
																	<?php echo ($due_amount+$package_due_amount);
																		// echo ($cards_data['payment_wise']['debit_card']+$package_payment_wise['debit_card']);
																			
																	?>
																	</td></tr>
																		<tr><td>Received : </td><td><?=$pending_amount_received?></td></tr>
																	</table>
																</div>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="tab-pane" id="tab-5" role="tabpanel">
										<button type="button" style="float:right" onclick="exportTableToExcel('producttable','ProductTable')" class="btn btn-primary" id="productdownload">Download</button>
										<table class="table table-hover" id="producttable" style="width:100%;">
											<thead>
												<tr>
												<th>Sr.No.</th>
													<th>Service</th>
													<th>Category</th>
													<th>Sub-category</th>
													<th>Count</th>
													<!-- <th>MRP</th> -->
													<!-- <th>Discount</th> -->
													<th>Net Revenue</th>
												</tr>
											</thead>
											<tbody>
											<?php 
											$ptxn_mrp=0;
											$ptxn_disc=0;
											$ptxn_value=0;
											$ptxn_count=0;
													if($product_sale_today != 0){
													$i=1;	
													foreach($product_sale_today as $prodsales){
														// $ptxn_mrp+=$prodsales['txn_value']+$prodsales['txn_discount'];
														// $ptxn_disc+=$prodsales['txn_discount'];
														$ptxn_value+=$prodsales['txn_service_discounted_price'];
														$ptxn_count+=$prodsales['count'];
														
													?>

													<tr>
														<td><?php echo $i;?></td>
														<td><?=$prodsales['service_name']?></td>
														<td><?=$prodsales['category_name']?></td>
														<td><?=$prodsales['sub_category_name']?></td>
														<td><?=$prodsales['count']?></td>
														<!-- <td><?=$prodsales['txn_value']+$prodsales['txn_discount']?></td> -->
														<!-- <td><?=$prodsales['txn_discount']?></td> -->
														<td><?=$prodsales['txn_service_discounted_price']?></td>
																										
														
													</tr>
													<?php
													$i=$i+1;
													}
												}
													?>
													<tr style="color:blue; font-weight:bold;">
														<td>Total (Rs.)</td>
														<td></td>
														<td></td>
														<td></td>
														<td><?=$ptxn_count?></td>
														<!-- <td><?=$ptxn_mrp?></td> -->
														<!-- <td><?=$ptxn_disc?></td> -->
														<td><?=$ptxn_value?></td>
														<td></td>
													</tr>	
											</tbody>
												
										</table>
									</div>									
									<div class="tab-pane" id="tab-6" role="tabpanel">
										<div class="card">
											<div class="card-header">
												<div class="card-title my-2">
													<div class="row">
														<div class="col-md-3">
														Last 200 Transaction
														</div>
														<form class="form-inline" style="width:60%;" method="POST" action="#" id="txn200">
															<div class="form-group col-md-3">
																<input type="text" class="form-control" name="daterange" value="<?=date('Y-m-d');?>" >
															</div>
															<div class="form-group col-md-2">
																<input type="submit" class="btn btn-primary" id="get_txn"  value="Submit" />
															</div>
														</form>
													</div>														
												</div>
											</div>
											<div class="card-body" style="margin-right:10px;">
												<table class="table table-striped datatables-basic " style="width:100%;text-align:center" id="txnTable">	
												<thead>
													<tr>
														<th>Bill No.</th>
														<th>Date</th>																												
														<th>Mobile No.</th>
														<th>Name</th>
														<th>Type</th>
														<th>MRP Amount</th>
														<th>Discount</th>
														<th>Net Amount</th>
														<th>Total Tax(Rs.)</th>
														<th>Pending Amount</th>
														<th>Action</th>
													</tr>
												</thead>
												<tbody>
													<?php foreach($last_txn as $txn){?>
														<tr>																											
															<td class="showBilledServices" txn_id="<?=$txn['bill_no']?>" bill_type="<?=$txn['Type']?>" style="color:blue;"><?=$txn['txn_id']?></td>
															<td><?=$txn['billing_date']?></td>
															<td><?=$txn['mobile']?></td>
															<td><?=$txn['name']?></td>
															<td><?=$txn['Type']?></td>
															<td><?=number_format($txn['mrp_amt'])?></td>
															<td><?=number_format($txn['discount'])?></td>
															<td><?=number_format($txn['net_amt'])?></td>
															<td><?=$txn['total_tax']?></td>
															<td><?=$txn['pending_amt']?></td>
															<?php
																if($txn['Type'] == "Package"){
																	?>
																		<td><button data-type="package" class='btn btn-warning sendSmsBtn'  txn_id='<?=$txn['bill_no']?>'><i class='fa fa-sms'></i></button>
															<a href='<?=base_url()?>Cashier/RePrintPackageBill/<?=$txn['bill_no']?>' target='_blank' class='btn btn-danger' ><i class='fa fa-print'></i></a>
														</td>
																	<?php
																}else{
																	?>
																	<td><button  data-type="service" class='btn btn-warning sendSmsBtn'  txn_id='<?=$txn['bill_no']?>'><i class='fa fa-sms'></i></button>
															<a href='<?=base_url()?>Cashier/RePrintBill/<?=$txn['bill_no']?>' target='_blank' class='btn btn-danger' ><i class='fa fa-print'></i></a>
														</td>
																	<?php
																}
															?>
														</tr>
													<?php }?>
												</tbody>
												</table>
											</div>
										</div>								
									</div>									
								</div>
							</div>
							<!-- common modal start-->
								<div class="modal" id="BillModal" tabindex="-1" role="dialog" aria-hidden="true">	
									<div class="modal-dialog modal-dialog-centered modal-md" role="document">
										<div class="modal-content">
											<div class="modal-header">
												<h5 class="modal-title text-white">Bill Details</h5>
												<button type="button" class="close" data-dismiss="modal" aria-label="Close">
														<span aria-hidden="true">&times;</span>
												</button>
											</div>
											<div class="modal-body">
											<table id="show_Bill" style="width:100%;text-align:center">
												<thead>
													<tr>
														<th>Service</th>
														<th>Price</th>
														<th>Discount %</th>
														<th>Discount abs</th>
														<th>Quantity</th>
														<th>Expert</th>
													</tr>
												</thead>
												<tbody id="tabid">
													
												</tbody>
											</table>
											</div>
										</div>
									</div>
								</div>
								<div class="modal" id="BillModal2" tabindex="-1" role="dialog" aria-hidden="true">	
									<div class="modal-dialog modal-dialog-centered modal-md" role="document">
										<div class="modal-content">
											<div class="modal-header">
												<h5 class="modal-title text-white">Package Bill Details</h5>
												<button type="button" class="close" data-dismiss="modal" aria-label="Close">
														<span aria-hidden="true">&times;</span>
												</button>
											</div>
											<div class="modal-body">
											<table id="show_Bill2" style="width:100%;text-align:center">
												<thead>
													<tr>
														<th>Package Name</th>
														<th>Type</th>
														<th>Txn Value</th>
														<th>Discount</th>
														<th>Customer Name</th>
														<th>Expert</th>
													</tr>
												</thead>
												<tbody id="tabid2">
													
												</tbody>
											</table>
											</div>
										</div>
									</div>
								</div>
							<!-- end -->
						</div>
					</div>
				</div>			
		</main>
<?php
	$this->load->view('cashier/cashier_footer_view');
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

	$("input[name=\"daterange\"]").daterangepicker({
		daterangepicker: true,
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
			"order": [[ 1, "desc" ]]
		});

	//
	$(document).on('click',".showBilledServices",function(event){
				event.preventDefault();
				this.blur();
	      var parameters = {
	        txn_id: $(this).attr('txn_id'),
					type : $(this).attr('bill_type')
	      };
	    //  alert($(this).attr('txn_id'));
					$.getJSON("<?=base_url()?>Cashier/GetBilledServices", parameters)
					.done(function(data, textStatus, jqXHR) { 
						if(data[0].service=='service'){
							var str_2 = "";	
							for(var i=0;i<data.length;i++){						
								str_2 += "<tr>";
								str_2 += "<td>"+data[i].service_name+"</td>";
								str_2 += "<td>"+data[i].txn_service_discounted_price+"</td>";
								str_2 += "<td>"+data[i].disc1+"</td>";
								str_2 += "<td>"+data[i].disc2+"</td>";
								str_2 += "<td>"+data[i].txn_service_quantity+"</td>";
								str_2 += "<td>"+data[i].employee_first_name+"</td>";
								str_2 += "</tr>";
							}				
							$("#tabid").html(str_2);
							$("#BillModal").modal('show');
						}else{
							var str_2 = "";	
							for(var i=0;i<data.length;i++){						
								str_2 += "<tr>";
								str_2 += "<td>"+data[0].salon_package_name+"</td>";
								str_2 += "<td>"+data[0].package+"</td>";
								str_2 += "<td>"+data[0].package_txn_value+"</td>";
								str_2 += "<td>"+data[0].package_txn_discount+"</td>";
								str_2 += "<td>"+data[0].customer_name+"</td>";
								str_2 += "<td>"+data[0].employee_first_name+"</td>";
								str_2 += "</tr>";
							}				
							$("#tabid2").html(str_2);
							$("#BillModal2").modal('show');
						}
					})
					.fail(function(jqXHR, textStatus, errorThrown) {
						console.log(errorThrown.toString());
				});
  		});

		  $(document).on('click',".showBilledPackages",function(event){
				event.preventDefault();
				this.blur();
	      var parameters = {
	        txn_id: $(this).attr('txn_id')
	      };
	    	//  alert($(this).attr('txn_id'));
					$.getJSON("<?=base_url()?>Cashier/GetBilledPackages", parameters)
					.done(function(data, textStatus, jqXHR) { 
						var str_2 = "";	
						for(var i=0;i<data.length;i++){						
							str_2 += "<tr>";
							str_2 += "<td>"+data[i].salon_package_name+"</td>";
							str_2 += "<td>"+data[i].package_txn_value+"</td>";
							str_2 += "<td>"+data[i].package_txn_discount+"</td>";
							str_2 += "<td>"+data[i].customer_name+"</td>";
							str_2 += "<td>"+data[i].employee_first_name+"</td>";
							str_2 += "</tr>";
						}				
						$("#package_billed").html(str_2);
						$("#PackageBillModal").modal('show');
					})
					.fail(function(jqXHR, textStatus, errorThrown) {
						console.log(errorThrown.toString());
				});

  		}); 

			//ReSend Bill SMS
		$(document).on('click','.sendSmsBtn',function(event) {
      event.preventDefault();
      this.blur(); // Manually remove focus from clicked link.
			var parameters={
				"txn_id" : $(this).attr('txn_id'),
				"type":$(this).attr('data-type')
			};
			$.ajax({
		        url: "<?=base_url()?>Cashier/ReSendBill",
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
		 						setTimeout(function () { location.reload(1); }, 500);
	            }else if (data.success == 'false'){   
								$("#ModalCancelBill").modal('hide');    
	            	$("#ErrorMod	alMessage").html("").html(data.message);            
	        	    $("#defaultModalDanger").modal('show');
	            }
	          },
	          error: function(data){
							$("#ModalCancelBill").modal('hide');
	  					$("#ErrorModalMessage").html("").html(data.message);            
	        	  $("#defaultModalDanger").modal('show');
	          }
					});
    });

</script>
<script>
	function JSONToCSVConvertor(JSONData, ReportTitle, ShowLabel) {
    //If JSONData is not an object then JSON.parse will parse the JSON string in an Object
    var arrData = typeof JSONData != 'object' ? JSON.parse(JSONData) : JSONData;
    
    var CSV = '';    
    //Set Report title in first row or line
    
    CSV += ReportTitle + '\r\n\n';

    //This condition will generate the Label/Header
    if (ShowLabel) {
        var row = "";
        
        //This loop will extract the label from 1st index of on array
        for (var index in arrData[0]) {
            
            //Now convert each value to string and comma-seprated
            row += index + ',';
        }

        row = row.slice(0, -1);
        
        //append Label row with line break
        CSV += row + '\r\n';
    }
    
    //1st loop is to extract each row
    for (var i = 0; i < arrData.length; i++) {
        var row = "";
        
        //2nd loop will extract each column and convert it in string comma-seprated
        for (var index in arrData[i]) {
            row += '"' + arrData[i][index] + '",';
        }

        row.slice(0, row.length - 1);
        
        //add a line break after each row
        CSV += row + '\r\n';
    }

    if (CSV == '') {        
        alert("Invalid data");
        return;
    }   
    
    //Generate a file name
    var fileName = "MSS_";
    //this will remove the blank-spaces from the title and replace it with an underscore
    fileName += ReportTitle.replace(/ /g,"_");   
    
    //Initialize file format you want csv or xls
    var uri = 'data:text/csv;charset=utf-8,' + escape(CSV);
    
    // Now the little tricky part.
    // you can use either>> window.open(uri);
    // but this will not work in some browsers
    // or you will not get the correct file extension    
    
    //this trick will generate a temp <a /> tag
    var link = document.createElement("a");    
    link.href = uri;
    
    //set the visibility hidden so it will not effect on your web-layout
    link.style = "visibility:hidden";
    link.download = fileName + ".csv";
    
    //this part will append the anchor tag and remove it after automatic click
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}
</script>
<script>
  $(document).ready(function() {
		var Sales = [
			{
				display: "Bill Wise Sales and Product Report",
				value: "BWSR"
			},
			{
				display : "Pending Amount Transaction Report",
				value : "PATR"
			}
		];

		var Inventory = [
			{
				display: "Inventory Stock Report",
				value: "SROTC"
			},
			{
				display: "Transaction Wise Report",
				value: "TWR"
			}
		];

		var Employee = [
			{
				display: "Employee Attendance Report",
				value: "EAR"
			}
		];

	

		// Function executes on change of first select option field.
		$(document).on('change','#groupName',function(event) {    
			var select = $("#groupName option:selected").val();
		
			switch (select) {
				case "Sales":
				AddSubGroup(Sales);	
				break;

				case "Inventory":
				AddSubGroup(Inventory);	
				break;


				case "Employee":
				AddSubGroup(Employee);	
				break;
				
				default:
					$("#reportName").empty();
				break;
			}
		});
	
		function AddSubGroup(arr) {
			$("#reportName").empty(); 
			$(arr).each(function(i) { 
				$("#reportName").append("<option value=" + arr[i].value + ">" + arr[i].display + "</option>");
			});
		}


		$(document).ready(function(){

			$("#GetResults").validate({
		  	errorElement: "div",
		    rules: {
		        "group_name" : {
	            required : true
		        },
		        "report_name" :{
		        	required : true
		        },
		        "from_date" : {
		        	required : true
		        },
		        "to_date" : {
		        	required : true
		        }
		    },
		    submitHandler: function(form) {
					var formData = $("#GetResults").serialize(); 
					$.ajax({
		        url: "<?=base_url()?>Cashier/ReportsManagement",
		        data: formData,
		        type: "GET",
		        // crossDomain: true,
						cache: false,
		        // dataType : "json",
		    		success: function(data) {
	            if(data.success == 'true'){
								JSONToCSVConvertor(data.result, "Reports", true);
	            }
	            else if (data.success == 'false'){       
	            	$("#ErrorModalMessage").html("").html(data.message);            
	        	    $("#defaultModalDanger").modal('show');
	            }
	          },
	          error: function(data){
	  					$("#ErrorModalMessage").html("").html(data.message);            
	        	  $("#defaultModalDanger").modal('show');
	          }
					});
				},
			});
		});

		$(document).on('click',"#get_txn",function(event){
    	event.preventDefault();
      this.blur();
			var dr=$("#txn200 input[name=daterange]").val();
	      var parameters = {
	        from_date : dr.substring(0, 10),
					to_date :	dr.substring(12, 23)
	      };
				$.getJSON("<?=base_url()?>Cashier/GetLastTransactions", parameters)
				.done(function(data, textStatus, jqXHR) {
					if(data.success == 'true'){
						var str_2 = "";
						// alert(data.service.res_arr.length);
						for(var i=0;i< data.message.length;i++){
							str_2+="<tr>";
							str_2 += "<td>" + parseInt(i+1) + "</td>";
							str_2 += "<td>" + data.message[i].txn_id + "</td>";
							str_2 += "<td>" + data.message[i].billing_date + "</td>";
							str_2 += "<td>" + data.message[i].mobile + "</td>";
							str_2 += "<td>" + data.message[i].Type + "</td>";	
							str_2 += "<td>" + (new Intl.NumberFormat().format(data.message[i].mrp_amt)) + "</td>";
							str_2 += "<td>" + data.message[i].discount + "</td>";
							str_2 += "<td>" + (new Intl.NumberFormat().format(data.message[i].net_amt)) + "</td>";
							str_2 += "<td>" + data.message[i].total_tax + "</td>";
							str_2 += "<td>" + data.message[i].pending_amt + "</td>";
							if(data.message[i].Type=='Package'){
								str_2 += "<td><button data-type='package' class='btn btn-warning sendSmsBtn'  txn_id='"+data.message[i].bill_no+"'><i class='fa fa-sms'></i></button> <a href=\"<?=base_url()?>Cashier/RePrintPackageBill/"+data.message[i].bill_no+"\" target=\"_blank\" class=\"btn btn-danger\" ><i class=\"fa fa-print\"></i></a></td>";
							}else{
								str_2 += "<td><button  data-type='service' class='btn btn-warning sendSmsBtn'  txn_id='"+data.message[i].bill_no+"'><i class='fa fa-sms'></i></button> <a href=\"<?=base_url()?>Cashier/RePrintBill/"+data.message[i].bill_no+"\" target='_blank' class='btn btn-danger' ><i class='fa fa-print'></i></a> </td>";
							}
							str_2+="</tr>";
						}
						$("#txnTable tbody tr").remove();
						$("#txnTable tbody").append(str_2);
					}
				})
				.fail(function(jqXHR, textStatus, errorThrown) {
					console.log(errorThrown.toString());
			});
  	});

		$(document).on('click',"#getExpertTxn",function(event){
    	event.preventDefault();
      this.blur();
			var dr=$("#txnExpertWise input[name=daterange]").val();
	      var parameters = {
	        from_date : dr.substring(0, 10),
					to_date :	dr.substring(12, 23)
	      };
				$.getJSON("<?=base_url()?>Cashier/GetExpertTransactions", parameters)
				.done(function(data, textStatus, jqXHR) {
					if(data.success == 'true'){
						var str_2 = "";
						for(var i=0;i< data.message.length;i++){
							str_2+="<tr>";
							str_2 += "<td>" + data.message[i].expert_name + "</td>";
							str_2 += "<td> EMP0" + data.message[i].emp_id + "</td>";
							str_2 += "<td>" + data.message[i].employee_mobile + "</td>";
							str_2 += "<td>" + (new Intl.NumberFormat().format(data.message[i].net_amt)) + "</td>";	
							str_2 += "<td>" + data.message[i].count + "</td>";
							str_2+="</tr>";
						}
						$("#expertWiseTable tbody tr").remove();
						$("#expertWiseTable tbody").append(str_2);
					}
				})
				.fail(function(jqXHR, textStatus, errorThrown) {
					console.log(errorThrown.toString());
			});
  	});


	});
</script>
<script>
  function exportTableToExcel(tableID, filename = ''){
	
      var downloadLink;
      var dataType = 'application/vnd.ms-excel';
      var tableSelect = document.getElementById(tableID);
      var tableHTML = tableSelect.outerHTML.replace(/ /g, '%20');
      
      // Specify file name
      filename = filename?filename+'.xls':'excel_data.xls';
      
      // Create download link element
      downloadLink = document.createElement("a");
      
      document.body.appendChild(downloadLink);
      
      if(navigator.msSaveOrOpenBlob){
          var blob = new Blob(['\ufeff', tableHTML], {
              type: dataType
          });
          navigator.msSaveOrOpenBlob( blob, filename);
      }else{
          // Create a link to the file
          downloadLink.href = 'data:' + dataType + ', ' + tableHTML;
      
          // Setting the file name
          downloadLink.download = filename;
          
          //triggering the function
          downloadLink.click();
      }
  }
</script>
