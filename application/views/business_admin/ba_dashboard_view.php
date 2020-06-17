<?php
	$this->load->view('business_admin/ba_header_view');
?>
<div class="wrapper">
	<?php
		$this->load->view('business_admin/ba_nav_view');
	?>
	<div class="main">
		<?php
			$this->load->view('business_admin/ba_top_nav_view');
		?>	
		<main class="content">
			<div class="container-fluid p-0">				
				<?php
					if(empty($business_outlet_details)){						
        ?>
        <div class="row">
					<div class="col-md-12">
						<div class="card">
							<div class="card-header">
								<h5 class="card-title">Add Outlets</h5>
							</div>
							<div class="card-body">
								<p>Please add outlet to proceed.</p>
							</div>
						</div>
          </div>
        </div>
				<?php
					}					
					if(!isset($selected_outlet)){
        ?>
        <div class="row">
					<div class="col-md-12">
						<div class="card">
							<div class="card-header">
								<h5 class="card-title">Select Outlet</h5>
							</div>
							<div class="card-body">
								<p>Please select outlet to proceed.</p>
							</div>
						</div>
          </div>
        </div>
				<?php
					}
					else{
				?>
				
			<ul class="nav nav-pills card-header-pills pull-right" role="tablist" style="font-weight: bolder">
								<li class="nav-item">
									<a class="nav-link active" data-toggle="tab" href="#tab-1">Home</a>
								</li>
								<li class="nav-item">
									<a class="nav-link" data-toggle="tab" href="#tab-2">Analytics</a>
								</li>
			</ul>			
			<div class="tab-content">
				<div class="tab-pane show active" id="tab-1" role="tabpanel">			
					<!--Cards-->			
					<div class="row">
						<div class="col-md-2">
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
													echo "<h5><b>".($cards_data['sales']['sales']+$card_data[0]['package_sales']-($cards_data['payment_wise']['virtual_wallet']+$package_payment_wise['virtual_wallet'])+$cards_data['productsales']['productsales'])."</b></h5>";
												}
												else{
													echo (0+$card_data[0]['package_sales']);
												}
												?>
											</th></tr>
											
											<tr><td>Services : </td><td><?=($cards_data['sales']['sales'])?></td></tr>
											<tr><td>Packages : </td><td><?=$card_data[0]['package_sales']?></td></tr>
											<tr><td>Product : </td><td><?=$cards_data['productsales']['productsales']?></td></tr>
											<tr style="color: red"><td>VW : </td><td>-<?=$cards_data['payment_wise']['virtual_wallet']+$package_payment_wise['virtual_wallet']?></td></tr>
										</table>	
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-md-2">
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
													echo "<h5><b>".($cards_data['payment_wise']['cash']+$package_payment_wise['cash']+$cards_data['payment_wise']['credit_card']+$package_payment_wise['credit_card']+$cards_data['payment_wise']['debit_card']+$package_payment_wise['debit_card']+$cards_data['payment_wise']['paytm']+$package_payment_wise['paytm']+$cards_data['payment_wise']['phone_pe']+$package_payment_wise['phone_pe']+$cards_data['payment_wise']['google_pay']+$package_payment_wise['google_pay']+$package_payment_wise['virtual_wallet']-$loyalty_payment+$cards_data['payment_wise']['others']+$pending_amount_received)."</h5></b>";
												?>
											</td></tr>
											<tr><td>Cash : </td><td><?=($cards_data['payment_wise']['cash']+$package_payment_wise['cash'])?></td></tr>
											<tr><td>Cards : </td><td><?=($cards_data['payment_wise']['credit_card']+$package_payment_wise['credit_card']+$cards_data['payment_wise']['debit_card']+$package_payment_wise['debit_card'])?></td></tr>
											<tr><td>W+Others : </td><td><?=($cards_data['payment_wise']['paytm']+$package_payment_wise['paytm']+$cards_data['payment_wise']['phone_pe']+$package_payment_wise['phone_pe']+$cards_data['payment_wise']['google_pay']+$package_payment_wise['google_pay']+$cards_data['payment_wise']['others']+$pending_amount_received)?></td></tr>
										
											</table>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-md-2">
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
						<div class="col-md-2">
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
												<?php echo $due_amount;
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
						<div class="col-md-2">
							<div class="card flex-fill" style="height:144px;">
								<div class="card-header">
									<span class="badge badge-success float-right">Today</span>
									<h5 class="card-title mb-0">Loyalty Wallet in <i class="fa fa-rupee-sign"></i></h5>
								</div>
								<div class="card-body">
									<div class="row d-flex align-items-center mb-1">
										<div class="col-md-12">
											<table style="width:100%;">
												<!-- <tr>
													<td>
											<div class="d-flex align-items-center mb-0 font-weight-light">
												<?php
												if($cards_data['customer_count']['customer_count'] != ''){
													echo $cards_data['customer_count']['customer_count'];
												}
												else{
													echo "0";
												}
												?> -->
											</div></td></tr>
											<tr><td>Generated : </td><td><?=$loyalty_points_given?></td></tr>
											<tr><td>Received : </td><td><?=$loyalty_payment?></td></tr>
											</table>
											
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-md-2">
							<div class="card flex-fill" style="height:144px;">
								<div class="card-header">
									<span class="badge badge-warning float-right">Lifetime</span>
									<h5 class="card-title mb-0">Due Amount</h5>
								</div>
								<div class="card-body">
									<div class="row d-flex align-items-center mb-1">
										<div class="col-md-12">
										<table style="width:100%;">
											<tr><td>Opening Balance : </td><td>
												<?php
													echo ($total_due_amount['total_due_amount']);
												?></td>
											</tr>
											<tr><td>Generated : </td><td><?=$due_amount?></td></tr>
											<tr><td>Received : </td><td><?=$pending_amount_received?></td></tr>
											<tr><td>Closing Balance : </td><td><?=($total_due_amount['total_due_amount']+$due_amount-$pending_amount_received)?></td></tr>
										</table>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-2">
							<div class="card flex-fill" style="height:144px;">
								<div class="card-header">
									<span class="badge badge-primary float-right">MTD</span>
									<h5 class="card-title mb-0">Sales</h5>
								</div>
								<div class="card-body">
									<div class="row d-flex align-items-center mb-1">
										<div class="col-md-12 d-flex align-items-center mb-0 font-weight-light">
											<table style="width:100%;">
												<tr><td><h5><b>Total</b></h5></td><td>
													<?php
														echo "<h5><b>".(($sales_till_date+$package_sales_till_date)-($monthly_sales_payment_wise['monthly_virtual_wallet']+$monthly_package_sales_payment_wise['virtual_wallet'])+$product_sales_till_date)."</h5></b>";
													?>
												</td></tr>
												<tr><td>Services : </td><td><?=$sales_till_date?></td></tr>
												<tr><td>Packages : </td><td><?=$package_sales_till_date?></td></tr>
												<tr><td>Product : </td><td><?=$product_sales_till_date?></td></tr>
												<tr style="color: red"><td>VW : </td><td>-<?=($monthly_sales_payment_wise['monthly_virtual_wallet']+$monthly_package_sales_payment_wise['virtual_wallet'])?></td></tr>
												
											</table>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-md-2">
							<div class="card flex-fill" style="height:144px;">
								<div class="card-header">
									<span class="badge badge-primary float-right">MTD</span>
									<h5 class="card-title mb-0">Collections</h5>
								</div>
								<div class="card-body">
									<div class="row d-flex align-items-center mb-1">											
											<div class="col-md-12 d-flex align-items-center mb-0 font-weight-light">
											<table style="width:100%;">
												<tr><td><h5><b>Total</b></h5></td><td class="font-weight-bold">
													<?php
														echo "<h5><b>".($monthly_sales_payment_wise['monthly_cash']+$monthly_sales_payment_wise['monthly_credit_card']+$monthly_sales_payment_wise['monthly_debit_card']+$monthly_sales_payment_wise['monthly_google_pay']+$monthly_sales_payment_wise['monthly_phone_pe']+$monthly_sales_payment_wise['monthly_paytm']+$monthly_sales_payment_wise['monthly_others']+$monthly_pending_amount_received)."</h5></b>";
													?></td>
												</tr>
												<tr><td>Cash : </td><td><?=$monthly_sales_payment_wise['monthly_cash']?></td></tr>
												<tr><td>Cards : </td><td><?=($monthly_sales_payment_wise['monthly_credit_card']+$monthly_sales_payment_wise['monthly_debit_card'])?></td></tr>
												<tr><td>W+Others : </td><td><?=($monthly_sales_payment_wise['monthly_google_pay']+$monthly_sales_payment_wise['monthly_phone_pe']+$monthly_sales_payment_wise['monthly_paytm']+$monthly_sales_payment_wise['monthly_others'])+$monthly_pending_amount_received?></td></tr>
											</table>
											</div>
										</div>
								</div>
							</div>
						</div>
						<div class="col-md-2">
							<div class="card flex-fill" style="height:144px;">
								<div class="card-header">
									<span class="badge badge-primary float-right">MTD</span>
									<h5 class="card-title mb-0">Expenses</h5>
								</div>
								<div class="card-body">
									<div class="row d-flex align-items-center mb-1">
										<div class="col-md-12">
											<div class="d-flex align-items-center mb-0 font-weight-light">
												<table style="width:100%;">
													<tr>
														<td><h5><b>Total</b></h5></td>
														<td><?php
													echo "<h5><b>".($cards_data['monthly_expenses']['monthly_expenses'])."</h5></b>";
												?></td>
													</tr>
												</table>
												
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-md-2">
							<div class="card flex-fill" style="height:144px;">
								<div class="card-header">
									<span class="badge badge-primary float-right">MTD</span>
									<h5 class="card-title mb-0">Due Amount</h5>
								</div>
								<div class="card-body">
									<div class="row d-flex align-items-center mb-1">
										<div class="col-md-12">
											<div class="d-flex align-items-center mb-0 font-weight-light">
												<table style="width:100%;">													
												<tr><td>Generatd : </td><td><?=$monthly_due_amount?></td></tr>
												<tr><td>Received : </td><td><?=$monthly_pending_amount_received?></td></tr>
											</table>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-md-2">
							<div class="card flex-fill" style="height:144px;">
								<div class="card-header">
									<span class="badge badge-primary float-right">MTD</span>
									<h5 class="card-title mb-0">Loyalty Wallet</h5>
								</div>
								<div class="card-body">
									<div class="row d-flex align-items-center mb-1">
										<div class="col-md-12">
											<div class="d-flex align-items-center mb-0 font-weight-light">
											<table style="width:100%;">
												<tr><td>Generated : </td><td><?=$monthly_loyalty_points_given?></td></tr>
												<tr><td>Redeemed : </td><td><?=$monthly_loyalty_payment?></td></tr>
											</table>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-md-2">
							<div class="card flex-fill" style="height:144px;">
								<div class="card-header">
									<span class="badge badge-warning float-right">Lifetime</span>
									<h5 class="card-title mb-0">Virtual Wallet</h5>
								</div>
								<div class="card-body">
									<div class="row d-flex align-items-center mb-1">
										<div class="col-md-12">
										<table style="width:100%;">
										<tr><td>Opening Balance : </td><td><?=($total_virtual_wallet)?></td></tr>
										<tr><td>Generated : </td><td><?=$today_virtual_wallet_sales?></td></tr>
										<tr><td>Received : </td><td><?=$cards_data['payment_wise']['virtual_wallet']?></td></tr>
										<tr><td>Closing Balanace : </td><td><?=($total_virtual_wallet+$package_payment_wise['virtual_wallet']-$cards_data['payment_wise']['virtual_wallet'])?></td></tr>
										</table>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>					
					<div class="row">
						<div class="col-md-2">
							<div class="card flex-fill" style="height:144px;">
								<div class="card-header">
									<span class="badge badge-info float-right">LMTD</span>
									<h5 class="card-title mb-0">Sales</h5>
								</div>
								<div class="card-body">
									<div class="row d-flex align-items-center mb-1">
										<div class="col-md-12">
											<table style="width:100%;">
												<tr><td><h5><b>Total</b></h5></td><td><?php echo "<h5><b>".($cards_data['last_month_sales']['last_month_sales']+$last_month_package_sales-($last_month_package_sales_payment_wise['virtual_wallet']+$last_month_sales_payment_wise['last_month_virtual_wallet'])+$cards_data['last_month_product_sales']['last_month_product_sales'])."</h5></b>"?></td></tr>
												<tr><td>Services : </td><td><?=$cards_data['last_month_sales']['last_month_sales']?></td></tr>
												<tr><td>Packages : </td><td><?=$last_month_package_sales?></td></tr>
												<tr><td>Product : </td><td><?=$cards_data['last_month_product_sales']['last_month_product_sales']?></td></tr>
												<tr style="color: red"><td>VW : </td><td>-<?=$last_month_package_sales_payment_wise['virtual_wallet']+$last_month_sales_payment_wise['last_month_virtual_wallet']?></td></tr>
											</table>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-md-2">
							<div class="card flex-fill" style="height:144px;">
								<div class="card-header">
									<span class="badge badge-info float-right">LMTD</span>
									<h5 class="card-title mb-0">Collections</h5>
								</div>
								<div class="card-body">
									<div class="row d-flex align-items-center mb-1">
										<div class="col-md-12">
										<table style="width:100%;">
											<tr><td><h5><b>Total</b></h5></td><td>
												<?php
													echo "<h5><b>".($last_month_sales_payment_wise['last_month_cash']+$last_month_sales_payment_wise['last_month_credit_card']+$last_month_sales_payment_wise['last_month_debit_card']+$last_month_sales_payment_wise['last_month_google_pay']+$last_month_sales_payment_wise['last_month_phone_pe']+$last_month_sales_payment_wise['last_month_paytm']+$last_month_sales_payment_wise['last_month_others']+$last_month_pending_amount_received)."</h5></b>";
												?></td>
											</tr>
											<tr><td>Cash : </td><td><?=$last_month_sales_payment_wise['last_month_cash']?></td></tr>
											<tr><td>Cards : </td><td><?=($last_month_sales_payment_wise['last_month_credit_card']+$last_month_sales_payment_wise['last_month_debit_card'])?></td></tr>
											<tr><td>W+Others : </td><td><?=($last_month_sales_payment_wise['last_month_google_pay']+$last_month_sales_payment_wise['last_month_phone_pe']+$last_month_sales_payment_wise['last_month_paytm']+$last_month_sales_payment_wise['last_month_others']+$last_month_pending_amount_received)?></td></tr>
										</table>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-md-2">
							<div class="card flex-fill" style="height:144px;">
								<div class="card-header">
									<span class="badge badge-info float-right">LMTD</span>
									<h5 class="card-title mb-0">Expenses</h5>
								</div>
								<div class="card-body">
									<div class="row d-flex align-items-center mb-1">
										<div class="col-md-12" style="width:100%;">
										<table style="width:100%;">
										<tr><td><h5><b>Total</b></h5></td><td>
										<?php
											echo "<h5><b>".($cards_data['last_month_expense']['last_month_expense'])."</h5></b>";
										?></td></tr>
										</table>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-md-2">
							<div class="card flex-fill" style="height:144px;">
								<div class="card-header">
									<span class="badge badge-info float-right">LMTD</span>
									<h5 class="card-title mb-0">Due Amount</h5>
								</div>
								<div class="card-body">
									<div class="row d-flex align-items-center mb-1">
										<div class="col-md-12">
											<table style="width:100%;">													
												<tr><td>Generated : </td><td><?=$last_month_due_amount?></td></tr>
												<tr><td>Received : </td><td><?=$last_month_pending_amount_received?></td></tr>
											</table>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-md-2">
							<div class="card flex-fill" style="height:144px;">
								<div class="card-header">
									<span class="badge badge-info float-right">LMTD</span>
									<h5 class="card-title mb-0">Loyalty Wallet</h5>
								</div>
								<div class="card-body">
									<div class="row d-flex align-items-center mb-1">
										<div class="col-md-12">
										<table style="width:100%;">
											<tr><td>Generated : </td><td><?=$last_month_loyalty_points_given?></td></tr>
											<tr><td>Redeemed : </td><td><?=$last_month_loyalty_payment?></td></tr>
										</table>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-2">
							<div class="card flex-fill" style="height:144px;">
								<div class="card-header">
									<span class="badge badge-info float-right">LMTD</span>
									<h5 class="card-title mb-0">Sales</h5>
								</div>
								<div class="card-body">
									<div class="row d-flex align-items-center mb-1">
										<div class="col-md-12">
											<table style="width:100%;">
												<tr><td><h5><b>Total</b></h5></td><td><?php echo "<h5><b>".($cards_data['last_month_sales']['last_month_sales']+$last_month_package_sales-($last_month_package_sales_payment_wise['virtual_wallet']+$last_month_sales_payment_wise['last_month_virtual_wallet'])+$cards_data['last_month_product_sales']['last_month_product_sales'])."</h5></b>"?></td></tr>
												<tr><td>Services : </td><td><?=$cards_data['last_month_sales']['last_month_sales']?></td></tr>
												<tr><td>Packages : </td><td><?=$last_month_package_sales?></td></tr>
												<tr><td>Product : </td><td><?=$cards_data['last_month_product_sales']['last_month_product_sales']?></td></tr>
												<tr style="color: red"><td>VW : </td><td>-<?=$last_month_package_sales_payment_wise['virtual_wallet']+$last_month_sales_payment_wise['last_month_virtual_wallet']?></td></tr>
											</table>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-md-2">
							<div class="card flex-fill" style="height:144px;">
								<div class="card-header">
									<span class="badge badge-info float-right">LMTD</span>
									<h5 class="card-title mb-0">Collections</h5>
								</div>
								<div class="card-body">
									<div class="row d-flex align-items-center mb-1">
										<div class="col-md-12">
										<table style="width:100%;">
											<tr><td><h5><b>Total</b></h5></td><td>
												<?php
													echo "<h5><b>".($last_month_sales_payment_wise['last_month_cash']+$last_month_sales_payment_wise['last_month_credit_card']+$last_month_sales_payment_wise['last_month_debit_card']+$last_month_sales_payment_wise['last_month_google_pay']+$last_month_sales_payment_wise['last_month_phone_pe']+$last_month_sales_payment_wise['last_month_paytm']+$last_month_sales_payment_wise['last_month_others']+$last_month_pending_amount_received)."</h5></b>";
												?></td>
											</tr>
											<tr><td>Cash : </td><td><?=$last_month_sales_payment_wise['last_month_cash']?></td></tr>
											<tr><td>Cards : </td><td><?=($last_month_sales_payment_wise['last_month_credit_card']+$last_month_sales_payment_wise['last_month_debit_card'])?></td></tr>
											<tr><td>W+Others : </td><td><?=($last_month_sales_payment_wise['last_month_google_pay']+$last_month_sales_payment_wise['last_month_phone_pe']+$last_month_sales_payment_wise['last_month_paytm']+$last_month_sales_payment_wise['last_month_others']+$last_month_pending_amount_received)?></td></tr>
										</table>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-md-2">
							<div class="card flex-fill" style="height:144px;">
								<div class="card-header">
									<span class="badge badge-info float-right">LMTD</span>
									<h5 class="card-title mb-0">Expenses</h5>
								</div>
								<div class="card-body">
									<div class="row d-flex align-items-center mb-1">
										<div class="col-md-12" style="width:100%;">
										<table style="width:100%;">
										<tr><td><h5><b>Total</b></h5></td><td>
										<?php
											echo "<h5><b>".($cards_data['last_month_expense']['last_month_expense'])."</h5></b>";
										?></td></tr>
										</table>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-md-2">
							<div class="card flex-fill" style="height:144px;">
								<div class="card-header">
									<span class="badge badge-info float-right">LMTD</span>
									<h5 class="card-title mb-0">Due Amount</h5>
								</div>
								<div class="card-body">
									<div class="row d-flex align-items-center mb-1">
										<div class="col-md-12">
											<table style="width:100%;">													
												<tr><td>Generated : </td><td><?=$last_month_due_amount?></td></tr>
												<tr><td>Received : </td><td><?=$last_month_pending_amount_received?></td></tr>
											</table>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-md-2">
							<div class="card flex-fill" style="height:144px;">
								<div class="card-header">
									<span class="badge badge-info float-right">LMTD</span>
									<h5 class="card-title mb-0">Loyalty Wallet</h5>
								</div>
								<div class="card-body">
									<div class="row d-flex align-items-center mb-1">
										<div class="col-md-12">
										<table style="width:100%;">
											<tr><td>Generated : </td><td><?=$last_month_loyalty_points_given?></td></tr>
											<tr><td>Redeemed : </td><td><?=$last_month_loyalty_payment?></td></tr>
										</table>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<div class="card flex-fill w-100">
								<div class="card-header" style="border-bottom-width: 0px;">
									<span class="badge badge-primary float-right">Fortnightly</span>
									<h5 class="card-title mb-0">Service Revenue</h5>							
								</div>
								<div class="card-body">
						<!-- <div class="chart chart-lg">
										<canvas id="chartjs-dashboard-bar-revenue-weekly"></canvas>
									</div>	 -->
									<div class="chart chart-lg">
											<canvas id="chartjs-line-revenue"></canvas>
										</div>			
								</div>
							</div>
						</div>
						<!-- <div class="col-md-4">
							<div class="card flex-fill w-100">
								<div class="card-header">
									<h5 class="card-title mb-0">Customer</h5>							
								</div>
								<div class="card-body">
						<div class="chart chart-lg">
										<canvas id="chartjs-dashboard-bar-customer-weekly"></canvas>
									</div>					
								</div>
							</div>
						</div>
						<div class="col-md-4">
							<div class="card flex-fill w-100">
								<div class="card-header">
									<h5 class="card-title mb-0">Visits</h5>							
								</div>
								<div class="card-body">
						<div class="chart chart-lg">
										<canvas id="chartjs-dashboard-bar-visits-weekly"></canvas>
									</div>					
								</div>
							</div>
						</div> -->
					</div>
				</div>
				<div class="tab-pane" id="tab-2" role="tabpanel">	
					<!-- Revenue Trends -->
					<div class="row">
						<div class="col-md-1"></div>
						<div class="col-md-2" style="margin-left:20px">
							<div class="card flex-fill w-100">
								<div class="card-header" style="border-bottom-width: 0px;">
									<span class="badge badge-primary float-right"></span>
									<h5 class="card-title mb-0">Revenue/Client</h5>							
								</div>
								<div class="card-body">
									<div class="chart chart-sm">
											<!-- <canvas id="apexcharts-area"></canvas> -->
											<div id="apexcharts-area"></div>
									</div>			
								</div>
								<div class="card-footer">
									<table style="width:100%">
										<tr><th>7 Days Avg:</th><td>Rs.<?=$client_avg?></td></tr>
										<tr><th>MTD Avg:</th><td>Rs.<?=$mtd_client_avg?></td></tr>
										<tr><th>LMTD Avg:</th><td>Rs.<?=$lmtd_client_avg?></td></tr>
									</table>
								</div>
							</div>
						</div>
						<div class="col-md-2">
							<div class="card flex-fill w-100">
								<div class="card-header" style="border-bottom-width: 0px;">
									<span class="badge badge-primary float-right"></span>
									<h5 class="card-title mb-0">Revenue/Visit</h5>							
								</div>
								<div class="card-body">
									<div class="chart chart-sm">
											<!-- <canvas id="chartjs-line-revenue_visits"></canvas> -->
											<div id="apexcharts-area1"></div>
									</div>			
								</div>
								<div class="card-footer">
									<table style="width:100%">
										<tr><th>7 Days Avg:</th><td>Rs.<?=$visit_avg?></td></tr>
										<tr><th>MTD Avg:</th><td>Rs.<?=$mtd_visit_avg?></td></tr>
										<tr><th>LMTD Avg:</th><td>Rs.<?=$lmtd_visit_avg?></td></tr>
									</table>
								</div>
							</div>
						</div>
						<div class="col-md-2">
							<div class="card flex-fill w-100">
								<div class="card-header" style="border-bottom-width: 0px;">
									<span class="badge badge-primary float-right"></span>
									<h5 class="card-title mb-0">Revenue/Day</h5>							
								</div>
								<div class="card-body">
									<div class="chart chart-sm">
											<!-- <canvas id="chartjs-line-revenue_perday"></canvas> -->
											<div id="apexcharts-area2"></div>
									</div>			
								</div>
								<div class="card-footer">
									<table style="width:100%">
										<tr><th>7 Days Avg:</th><td>Rs.<?=$revenue_avg?></td></tr>
										<tr><th>MTD Avg:</th><td>Rs.<?=$mtd_revenue_avg?></td></tr>
										<tr><th>LMTD Avg:</th><td>Rs.<?=$lmtd_revenue_avg?></td></tr>
									</table>
								</div>
							</div>
						</div>
						<div class="col-md-2">
							<div class="card flex-fill w-100">
								<div class="card-header" style="border-bottom-width: 0px;">
									<span class="badge badge-primary float-right"></span>
									<h5 class="card-title mb-0">Service Revenue/Day</h5>							
								</div>
								<div class="card-body">
									<div class="chart chart-sm">
											<!-- <canvas id="chartjs-line-service_revenue_perday"></canvas>-->
											<div id="apexcharts-area3"></div>
									</div>			
								</div>
								<div class="card-footer">
									<table style="width:100%">
										<tr><th>7 Days Avg:</th><td>Rs.<?=$service_avg?></td></tr>
										<tr><th>MTD Avg:</th><td>Rs.<?=$mtd_service_avg?></td></tr>
										<tr><th>LMTD Avg:</th><td>Rs.<?=$lmtd_service_avg?></td></tr>
									</table>
								</div>
							</div>
						</div>

						<div class="col-md-2" style="margin-right:30px">
							<div class="card flex-fill w-100">
								<div class="card-header" style="border-bottom-width: 0px;">
									<span class="badge badge-primary float-right"></span>
									<h5 class="card-title mb-0">Product Revenue/Day</h5>							
								</div>
								<div class="card-body">
									<div class="chart chart-sm">
											<!-- <canvas id="chartjs-line-Product_revenue_perday"></canvas> -->
											<div id="apexcharts-area4"></div>
									</div>			
								</div>
								<div class="card-footer">
									<table style="width:100%">
										<tr><th>7 Days Avg:</th><td>Rs.<?=$otc_avg?></td></tr>
										<tr><th>MTD Avg:</th><td>Rs.<?=$mtd_otc_avg?></td></tr>
										<tr><th>LMTD Avg:</th><td>Rs.<?=$lmtd_otc_avg?></td></tr>
									</table>
								</div>
							</div>
						</div>
						<!-- <div class="col-md-1"></div> -->
					</div>				
					<!-- Product Penetration Trends -->
					<div class="row">
						<div class="col-md-3">
							<div class="card flex-fill w-100">
								<div class="card-header" style="border-bottom-width: 0px;">
									<span class="badge badge-primary float-right"></span>
									<h5 class="card-title mb-0">Retail Product Buyers</h5>							
								</div>
								<div class="card-body">
									<div class="chart chart-sm">
											<!-- <canvas id="chartjs-line-productb_trends"></canvas> -->
											<div id="apexcharts-area5"></div>
									</div>			
								</div>
								<div class="card-footer">
									<table style="width:100%">
										<tr><th>MTD :</th><td><?=$mtd_retail_prod?></td></tr>
										<tr><th>MTD :</th><td><?=$mtd_retail_prod_perc?>%</td></tr>
										<tr><th>&nbsp;</th><td></td></tr>
									</table>
								</div>
							</div>
						</div>
						<div class="col-md-3">
							<div class="card flex-fill w-100">
								<div class="card-header" style="border-bottom-width: 0px;">
									<span class="badge badge-primary float-right"></span>
									<h5 class="card-title mb-0">Package Buyers</h5>							
								</div>
								<div class="card-body">
									<div class="chart chart-sm">
											<!-- <canvas id="chartjs-line-package_buyers"></canvas> -->
											<div id="apexcharts-area6"></div>
									</div>			
								</div>
								<div class="card-footer">
									<table style="width:100%">
										<tr><th>MTD :</th><td><?=$mtd_package?></td></tr>
										<tr><th>MTD :</th><td><?=$mtd_package_perc?>%</td></tr>
										<tr><th>&nbsp;</th><td></td></tr>
									</table>
								</div>
							</div>
						</div>
						<div class="col-md-3">
							<div class="card flex-fill w-100">
								<div class="card-header" style="border-bottom-width: 0px;">
									<span class="badge badge-primary float-right"></span>
									<h5 class="card-title mb-0">Product Sales Ratio</h5>							
								</div>
								<div class="card-body">
									<div class="chart chart-sm">
											<!-- <canvas id="chartjs-line-product_sales_ratio"></canvas> -->
											<div id="apexcharts-area7"></div>
									</div>			
								</div>
								<div class="card-footer">
									<table style="width:100%">
										<tr><th>&nbsp;</th><td></td></tr>
										<tr><th>MTD :</th><td><?=$mtd_product_ratio_perc?>%</td></tr>
										<tr><th>&nbsp;</th><td></td></tr>
									</table>
								</div>
							</div>
						</div>
						<div class="col-md-3">
							<div class="card flex-fill w-100">
								<div class="card-header" style="border-bottom-width: 0px;">
									<span class="badge badge-primary float-right"></span>
									<h5 class="card-title mb-0">Product Non-Buyers</h5>							
								</div>
								<div class="card-body">
									<div class="chart chart-sm">
											<!-- <canvas id="chartjs-line-product_nonbuyers"></canvas> -->
											<div id="apexcharts-area8"></div>
									</div>			
								</div>
								<div class="card-footer">
									<table style="width:100%">
										<tr><th>Total UU:</th><td><?=$UU?></td></tr>
										<tr><th>Non-Users:</th><td><?=$non_user?></td></tr>
										<tr><th>90D Non Users:</th><td><?=$otcdays?></td></tr>
									</table>
								</div>
							</div>
						</div>
					</div>
					<!-- Appointment DashBoard -->
					<div class="row">
						<div class="col-md-3">
							<div class="card flex-fill w-100">
								<div class="card-header" style="border-bottom-width: 0px;">
									<span class="badge badge-primary float-right"></span>
									<h5 class="card-title mb-0">Appointment/Day</h5>							
								</div>
								<div class="card-body">
									<div class="chart chart-sm">
											<!-- <canvas id="chartjs-line-appointment_per_day"></canvas> -->
											<div id="apexcharts-area9"></div>
									</div>			
								</div>
								<div class="card-footer">
									<table style="width:100%">
										<tr><th>MTD :</th><td><?=$appointment_mtd?></td></tr>
										<tr><th>LMTD :</th><td><?=$appointment_lmtd?></td></tr>
										<tr><th>&nbsp;</th><td></td></tr>
									</table>
								</div>
							</div>
						</div>
						<div class="col-md-3">
							<div class="card flex-fill w-100">
								<div class="card-header" style="border-bottom-width: 0px;">
									<span class="badge badge-primary float-right"></span>
									<h5 class="card-title mb-0">Total Visit</h5>							
								</div>
								<div class="card-body">
									<div class="chart chart-sm">
											<!-- <canvas id="chartjs-line-appointmentvisits"></canvas> -->
											<div id="apexcharts-area10"></div>
									</div>			
								</div>
								<div class="card-footer">
									<table style="width:100%">
										<tr><th>MTD :</th><td><?=$appointment_visit_mtd?></td></tr>
										<tr><th>LMTD :</th><td><?=$appointment_visit_lmtd?></td></tr>
										<tr><th>&nbsp;</th><td></td></tr>
									</table>
								</div>
							</div>
						</div>
						<div class="col-md-3">
							<div class="card flex-fill w-100">
								<div class="card-header" style="border-bottom-width: 0px;">
									<span class="badge badge-primary float-right"></span>
									<h5 class="card-title mb-0">No Show</h5>							
								</div>
								<div class="card-body">
									<div class="chart chart-sm">
											<!-- <canvas id="chartjs-line-appointmentnoshow"></canvas> -->
											<div id="apexcharts-area11"></div>
									</div>			
								</div>
								<div class="card-footer">
									<table style="width:100%">
										<tr><th>MTD</th><td><?=$appointment_noshow_mtd?></td></tr>
										<tr><th>LMTD :</th><td><?=$appointment_noshow_lmtd?></td></tr>
										<tr><th>&nbsp;</th><td></td></tr>
									</table>
								</div>
							</div>
						</div>
						<div class="col-md-3">
							<div class="card flex-fill w-100">
								<div class="card-header" style="border-bottom-width: 0px;">
									<span class="badge badge-primary float-right"></span>
									<h5 class="card-title mb-0">Cancellation</h5>							
								</div>
								<div class="card-body">
									<div class="chart chart-sm">
											<!-- <canvas id="chartjs-line-cancellation"></canvas> -->
											<div id="apexcharts-area12"></div>
									</div>			
								</div>
								<div class="card-footer">
									<table style="width:100%">
										<tr><th> MTD:</th><td><?=$appointment_cancellation_mtd?></td></tr>
										<tr><th>LMTD:</th><td><?=$appointment_cancellation_lmtd?></td></tr>
										<tr><th>&nbsp;</th><td></td></tr>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>		
			</div>	
				<?php
					}
				?>
			</div>	
		</main>
<?php
	$this->load->view('business_admin/ba_footer_view');
?>
<script type="text/javascript">
	function updateChart(chart,label,data) {
		chart.data.labels = label;
		chart.data.datasets[0].data = data;
		chart.update();
	}
</script>
<script type="text/javascript">
	$("input[name=\"fromdate\"]").daterangepicker({
		singleDatePicker: true,
		showDropdowns: true,
		autoUpdateInput : false,
		locale: {
      format: 'YYYY-MM-DD'
		}
	});
	$("input[name=\"todate\"]").daterangepicker({
		singleDatePicker: true,
		showDropdowns: true,
		autoUpdateInput : false,
		locale: {
      format: 'YYYY-MM-DD'
		}
	});

	$('.date').on('apply.daterangepicker', function(ev, picker) {
    $(this).val(picker.startDate.format('YYYY-MM-DD'));
  });</script>
<script>
	$(document).ready(function(){
		// $(document).ajaxStart(function() {
    //   $("#load_screen").show();
    // });

    // $(document).ajaxStop(function() {
    //   $("#load_screen").hide();
    // });

    $(".select2").each(function() {
			$(this)
				.wrap("<div class=\"position-relative\"></div>")
				.select2({
					placeholder: "Select value",
					dropdownParent: $(this).parent()
				});
		});


    //Default Empty Chart
		yearly_chart = new Chart(document.getElementById("chartjs-dashboard-bar-yearly"), {
			type: "bar",
			data: {
				labels: ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'],
				datasets: [{
					label: "This year",
					backgroundColor: "blue",
					borderColor: "#E8EAED",
					hoverBackgroundColor: "#E8EAED",
					hoverBorderColor: "#E8EAED",
					data: [0,0,0,0,0,0,0,0,0,0,0,0]
				}]
			},
			options: {
				maintainAspectRatio: false,
				legend: {
					display: true
				},
				scales: {
					yAxes: [{
						gridLines: {
							display: false
						},
						stacked: false,
						ticks: {
							stepSize: 500
						}
					}],
					xAxes: [{
						barPercentage: .75,
						categoryPercentage: .5,
						stacked: false,
						gridLines: {
							color: "transparent"
						}
					}]
				}
			}
		});

		$("#GetSRBC").validate({
	  	errorElement: "div",
	  	rules : {
	  		'category_id' : {
	  			required : true
	  		}
	  	},
	    submitHandler: function(form) {
				var formData = $("#GetSRBC").serialize();
		    $.ajax({
		      url: "<?=base_url()?>index.php/BusinessAdmin/BarChartYearly/",
		      type: "GET",
		      data : formData,
		      crossDomain: true,
					cache: false,
		      dataType : "json",
		  		success: function(data) {
		  			var label_month = [];
		  			var yearly_data = [];
		
		  			for(var i=0;i<data.length;i++){
		  				label_month.push(data[i].month);
		  				yearly_data.push(parseInt(data[i].yearly_data));	
		  			}
		  			// Bar chart
		  			updateChart(yearly_chart,label_month,yearly_data);
					
			    },
		      error: function(data){
						console.log("Some error occured!");
		      }
				});
		  }
		});
	});</script>
<script>
	$(document).ready(function(){
		
		// Bar chart
		// line chart for

		//
		var revenue_weekly = null;
		var customer_weekly = null;
		var visits_weekly = null;
		var package_revenue = null;
		$.ajax({
      url: "<?=base_url()?>index.php/BusinessAdmin/GetRCVData/",
      type: "GET",
      crossDomain: true,
			cache: false,
      dataType : "json",
  		success: function(data) {
  			var labels_revenue = [];
  			var labels_customer = [];
  			var labels_visits = [];
				var labels_package=[];
  			var amt = [];
				var amt2 = [];
  			var visits = [];
  			var added_customer = [];

  			for(var i=0;i<data.revenue.length;i++){
  				labels_revenue.push(moment(data.revenue[i].bill_date).format('DD-MMM-YYYY'));
  				amt.push(parseInt(data.revenue[i].sales));	
  			}
				//
				for(var i=0;i<data.package_revenue.length;i++){
  				labels_package.push(data.package_revenue[i].bill_date);
  				amt.push(parseInt(data.package_revenue[i].sales));	
  			}
					//
  			for(var i=0;i<data.customer.length;i++){
  				labels_customer.push(data.customer[i].add_date);
  				added_customer.push(parseInt(data.customer[i].total));	
  			}

  			for(var i=0;i<data.visits.length;i++){
  				labels_visits.push(data.visits[i].visit_date);
  				visits.push(parseInt(data.visits[i].total_visits));	
  			}
				//
		$(function() {
				// Line chart
				revenue_weekly=	new Chart(document.getElementById("chartjs-line-revenue"), {
				type: "line",
				data: {
					labels: labels_revenue,
					datasets: [{
						label: "Revenue (Rs.)",
						fill: true,
						backgroundColor: "transparent",
						borderColor: window.theme.primary,
						data: amt
					}
					, {
						label: "Package Revenue(Rs.)",
						fill: true,
						backgroundColor: "transparent",
						borderColor: window.theme.tertiary,
						borderDash: [4, 4],
						data: amt2
					}
					]
				},
				options: {
					maintainAspectRatio: false,
					legend: {
						display: false
					},
					tooltips: {
						intersect: false
					},
					hover: {
						intersect: true
					},
					plugins: {
						filler: {
							propagate: false
						}
					},
					scales: {
						xAxes: [{
							reverse: true,
							gridLines: {
								color: "rgba(0,0,0,0.05)"
							}
						}],
						yAxes: [{
							ticks: {
								stepSize: 5000
							},
							display: true,
							borderDash: [5, 5],
							gridLines: {
								color: "rgba(0,0,0,0)",
								fontColor: "#fff"
							}
						}]
					}
				}
			});
		});
				//
  			// revenue_weekly = new Chart(document.getElementById("chartjs-dashboard-bar-revenue-weekly"), {
				// 	type: "bar",
				// 	data: {
				// 		labels: labels_revenue,
				// 		datasets: [{
				// 			label: "Revenue (In Numbers)",
				// 			backgroundColor: window.theme.primary,
				// 			borderColor: window.theme.primary,
				// 			hoverBackgroundColor: window.theme.primary,
				// 			hoverBorderColor: window.theme.primary,
				// 			data: amt
				// 		}]
				// 	},
				// 	options: {
				// 		maintainAspectRatio: false,
				// 		legend: {
				// 			display: true
				// 		},
				// 		scales: {
				// 			yAxes: [{
				// 				gridLines: {
				// 					display: false
				// 				},
				// 				stacked: false,
				// 				ticks: {
				// 					stepSize: 5000
				// 				}
				// 			}],
				// 			xAxes: [{
				// 				barPercentage: .75,
				// 				categoryPercentage: .5,
				// 				stacked: false,
				// 				gridLines: {
				// 					color: "transparent"
				// 				}
				// 			}]
				// 		}
				// 	}
				// });

				customer_weekly = new Chart(document.getElementById("chartjs-dashboard-bar-customer-weekly"), {
					type: "bar",
					data: {
						labels: labels_customer,
						datasets: [{
							label: "Added Customers (In Numbers)",
							backgroundColor: window.theme.primary,
							borderColor: window.theme.primary,
							hoverBackgroundColor: window.theme.primary,
							hoverBorderColor: window.theme.primary,
							data: added_customer
						}]
					},
					options: {
						maintainAspectRatio: false,
						legend: {
							display: true
						},
						scales: {
							yAxes: [{
								gridLines: {
									display: false
								},
								stacked: false,
								ticks: {
									stepSize: 10
								}
							}],
							xAxes: [{
								barPercentage: .75,
								categoryPercentage: .5,
								stacked: false,
								gridLines: {
									color: "transparent"
								}
							}]
						}
					}
				});

				visits_weekly = new Chart(document.getElementById("chartjs-dashboard-bar-visits-weekly"), {
					type: "bar",
					data: {
						labels: labels_visits,
						datasets: [{
							label: "Visits (In Numbers)",
							backgroundColor: window.theme.primary,
							borderColor: window.theme.primary,
							hoverBackgroundColor: window.theme.primary,
							hoverBorderColor: window.theme.primary,
							data: visits
						}]
					},
					options: {
						maintainAspectRatio: false,
						legend: {
							display: true
						},
						scales: {
							yAxes: [{
								gridLines: {
									display: false
								},
								stacked: false,
								ticks: {
									stepSize: 10
								}
							}],
							xAxes: [{
								barPercentage: .75,
								categoryPercentage: .5,
								stacked: false,
								gridLines: {
									color: "transparent"
								}
							}]
						}
					}
				});
				
      },
      error: function(data){
				console.log("Some error occured!");
      }
		});

		$("#GetRCV").validate({
	  	errorElement: "div",
	    rules: {
	        "fromdate" : {
            required : true
	        },
	        "todate" : {
            required : true
	        }
	    },
	    submitHandler: function(form) {
				var formData = $("#GetRCV").serialize(); 
				$.ajax({
	        url: "<?=base_url()?>index.php/BusinessAdmin/GetRCVData/",
	        data: formData,
	        type: "GET",
	        crossDomain: true,
					cache: false,
	        dataType : "json",
	    		success: function(data) {
            var labels_revenue = [];
		  			var labels_customer = [];
		  			var labels_visits = []
		  			var amt = [];
		  			var visits = [];
		  			var added_customer = [];

		  			for(var i=0;i<data.revenue.length;i++){
		  				labels_revenue.push(data.revenue[i].bill_date);
		  				amt.push(parseInt(data.revenue[i].sales));	
		  			}

		  			for(var i=0;i<data.customer.length;i++){
		  				labels_customer.push(data.customer[i].add_date);
		  				added_customer.push(parseInt(data.customer[i].total));	
		  			}

		  			for(var i=0;i<data.visits.length;i++){
		  				labels_visits.push(data.visits[i].visit_date);
		  				visits.push(parseInt(data.visits[i].total_visits));	
		  			}

						updateChart(revenue_weekly,labels_revenue,amt);
						updateChart(customer_weekly,labels_customer,added_customer);
						updateChart(visits_weekly,labels_visits,visits);
          },
          error: function(data){
  					console.log("Some error occured!");
          }
				});
			}
		});
	});</script>
<script>
	$(document).ready(function(){
		$.ajax({
      url: "<?=base_url()?>index.php/BusinessAdmin/LowStockItems/",
      type: "GET",
      crossDomain: true,
			cache: false,
      dataType : "json",
  		success: function(data) {
  			var labels = [];
  			var qty = [];
  			for(var i=0;i<data.length;i++){
  				labels.push(data[i].service_name);
  				qty.push(parseInt(data[i].qty));	
  			}

  			// Bar chart
				new Chart(document.getElementById("chartjs-dashboard-bar-otc"), {
					type: "bar",
					data: {
						labels: labels,
						datasets: [{
							label: "Quantity (In Numbers)",
							backgroundColor: window.theme.primary,
							borderColor: window.theme.primary,
							hoverBackgroundColor: window.theme.primary,
							hoverBorderColor: window.theme.primary,
							data: qty
						}]
					},
					options: {
						maintainAspectRatio: false,
						legend: {
							display: true
						},
						scales: {
							yAxes: [{
								gridLines: {
									display: false
								},
								stacked: false,
								ticks: {
									stepSize: 10
								}
							}],
							xAxes: [{
								barPercentage: .75,
								categoryPercentage: .5,
								stacked: false,
								gridLines: {
									color: "transparent"
								}
							}]
						}
					}
				});		
      },
      error: function(data){
				console.log("Some error occured!");
      }
		});
	});</script>
<script>
	$(document).ready(function(){
		$.ajax({
      url: "<?=base_url()?>index.php/BusinessAdmin/GenderDistribution/",
      type: "GET",
      crossDomain: true,
			cache: false,
      dataType : "json",
  		success: function(data) {
  			var count_male = 0;
  			var count_female = 0;

  			if(data.length == 2){
  				count_male = parseInt(data[0].count_gender);
  				count_female = parseInt(data[1].count_gender);
  			}
  			else if(data.length == 1){
  				if(data[0].customer_title == 'Mr.'){
  					count_male = parseInt(data[0].count_gender);
  					count_female = parseInt(0);
  				}
  				else{
  					count_male = parseInt(0);
  					count_female = parseInt(data[0].count_gender);
  				}
  			}
  			else{
  				count_female = parseInt(0);
  				count_male = parseInt(0)
  			}
  
  			// Bar chart
				var options = {
					chart: {
						height: 350,
						type: "donut",
					},
					dataLabels: {
						enabled: true
					},
	        labels: ["Male", "Female"],
					series: [count_male,count_female]
				}
				var chart = new ApexCharts(
					document.querySelector("#apexcharts-pie"),
					options
				);
				chart.render();
      },
      error: function(data){
				console.log("Some error occured!");
      }
		});
	});</script>
<script>
	$(document).ready(function(){
		$.ajax({
      url: "<?=base_url()?>index.php/BusinessAdmin/AgeDistribution/",
      type: "GET",
      crossDomain: true,
			cache: false,
      dataType : "json",
  		success: function(data) {
  			var age_group = [];
  			var age_count = [];
  			for(var i=0;i<data.length;i++){
  				age_group.push(data[i].age_group);
  				age_count.push(parseInt(data[i].age_count));	
  			}
  			var options = {
					chart: {
						height: 350,
						type: "donut",
					},
					dataLabels: {
						enabled: true
					},
	        labels: age_group,
					series: age_count
				}
				var chart = new ApexCharts(
					document.querySelector("#apexcharts-pie2"),
					options
				);
				chart.render();
	    },
      error: function(data){
				console.log("Some error occured!");
      }
		});
	});</script>
<script type="text/javascript">
	$(document).ready(function(){
		
		$("#Category-Id").on('change',function(e){
    	var parameters = {
    		'category_id' :  $(this).val()
    	};
    	$.getJSON("<?=base_url()?>index.php/BusinessAdmin/GetSubCategoriesByCatId/", parameters)
      .done(function(data, textStatus, jqXHR) {
      		var options = "<option value='' selected></option>"; 
       		for(var i=0;i<data.length;i++){
       			options += "<option value="+data[i].sub_category_id+">"+data[i].sub_category_name+"</option>";
       		}
       		$("#Sub-Category-Id").html("").html(options);
    	})
    	.fail(function(jqXHR, textStatus, errorThrown) {
        console.log(errorThrown.toString());
   		});
    });

    $("#Sub-Category-Id").on('change',function(e){
    	var parameters = {
    		'sub_category_id' :  $(this).val()
    	};
    	$.getJSON("<?=base_url()?>index.php/BusinessAdmin/GetServicesBySubCatId/", parameters)
      .done(function(data, textStatus, jqXHR) {
      		var options = "<option value='' selected></option>"; 
       		for(var i=0;i<data.length;i++){
       			options += "<option value="+data[i].service_id+">"+data[i].service_name+"</option>";
       		}
       		$("#Service-Id").html("").html(options);
    	})
    	.fail(function(jqXHR, textStatus, errorThrown) {
        console.log(errorThrown.toString());
   		});
    });

  });

  		
  		
		
	
		
		
		
		
		// Product Peneteration Trends
	
</script>
<script>
	// Revenue Client
    $(function() {
      // Area chart
	  var labels = <?php echo json_encode($client_revenue_labels);?>;
	  var sales = <?php echo json_encode($client_revenue_sales);?>;
      var options = {
        chart: {
          height: 200,
          type: "area",
        },
        dataLabels: {
          enabled: false
        },
        stroke: {
          curve: "smooth"
        },
        series: [{
          name: "Revenue (Rs)",
          data: sales
        }],
        xaxis: {
          type: "datetime",
          categories: labels
        },
        tooltip: {
          x: {
            format: "dd/MM/yyyy"
          },
        }
      }
      var chart = new ApexCharts(
        document.querySelector("#apexcharts-area"),
        options
      );
      chart.render();
    });
	// Revenue Visit
	$(function() {
      // Area chart
	  var labels = <?php echo json_encode($revenue_labels);?>;
				var sales = <?php echo json_encode($revenue_sales);?>;
      var options = {
        chart: {
          height: 200,
          type: "area",
        },
        dataLabels: {
          enabled: false
        },
        stroke: {
          curve: "smooth"
        },
        series: [{
			name: "Revenue (Rs)",
          data: sales
        }],
        xaxis: {
          type: "datetime",
          categories: labels
        },
        tooltip: {
          x: {
            format: "dd/MM/yyyy"
          },
        }
      }
      var chart = new ApexCharts(
        document.querySelector("#apexcharts-area1"),
        options
      );
      chart.render();
    });
	//Revenue Day
	$(function() {
      // Area chart
	  var labels = <?php echo json_encode($perday_labels);?>;
	  var sales = <?php echo json_encode($perday_sales);?>;
      var options = {
        chart: {
          height: 200,
          type: "area",
        },
        dataLabels: {
          enabled: false
        },
        stroke: {
          curve: "smooth"
        },
        series: [{
			name: "Revenue (Rs)",
          data: sales
        }],
        xaxis: {
          type: "datetime",
          categories: labels
        },
        tooltip: {
          x: {
            format: "dd/MM/yyyy"
          },
        }
      }
      var chart = new ApexCharts(
        document.querySelector("#apexcharts-area2"),
        options
      );
      chart.render();
    });
	// // Service Revenue per Day
	$(function() {
      // Area chart
	  var labels = <?php echo json_encode($service_perday_labels);?>;
				var sales = <?php echo json_encode($service_perday_sales);?>;
      var options = {
        chart: {
          height: 200,
          type: "area",
        },
        dataLabels: {
          enabled: false
        },
        stroke: {
          curve: "smooth"
        },
        series: [{
			name: "Revenue (Rs)",
          data: sales
        }],
        xaxis: {
          type: "datetime",
          categories: labels
        },
        tooltip: {
          x: {
            format: "dd/MM/yyyy"
          },
        }
      }
      var chart = new ApexCharts(
        document.querySelector("#apexcharts-area3"),
        options
      );
      chart.render();
    });
	// Product Revenue per Day
	$(function() {
      // Area chart
	  var labels = <?php echo json_encode($otc_perday_labels);?>;
				var sales = <?php echo json_encode($otc_perday_sales);?>;
      var options = {
        chart: {
          height: 200,
          type: "area",
        },
        dataLabels: {
          enabled: false
        },
        stroke: {
          curve: "smooth"
        },
        series: [{
			name: "Revenue (Rs)",
          data: sales
        }],
        xaxis: {
          type: "datetime",
          categories: labels
        },
        tooltip: {
          x: {
            format: "dd/MM/yyyy"
          },
        }
      }
      var chart = new ApexCharts(
        document.querySelector("#apexcharts-area4"),
        options
      );
      chart.render();
    });
	// Product Peneteration Trends
	$(function() {
      // Area chart
	  var labels = <?php echo json_encode($retail_product_labels);?>;
				var sales = <?php echo json_encode($retail_product_sales);?>;
      var options = {
        chart: {
          height: 200,
          type: "area",
        },
        dataLabels: {
          enabled: false
        },
        stroke: {
          curve: "smooth"
        },
        series: [{
          name: "Buyers ",
          data: sales
        }],
        xaxis: {
          type: "date",
          categories: labels
        },
        tooltip: {
          x: {
            format: "MM/yyyy"
          },
        }
      }
      var chart = new ApexCharts(
        document.querySelector("#apexcharts-area5"),
        options
      );
      chart.render();
    });
	// Package Buyers
	$(function() {
      // Area chart
	  var labels = <?php echo json_encode($package_labels);?>;
				var sales = <?php echo json_encode($package_sales);?>;
      var options = {
        chart: {
          height: 200,
          type: "area",
        },
        dataLabels: {
          enabled: false
        },
        stroke: {
          curve: "smooth"
        },
        series: [{
          name: "Buyers",
          data: sales
        }],
        xaxis: {
          type: "date",
          categories: labels
        },
        tooltip: {
          x: {
            format: "MM/yyyy"
          },
        }
      }
      var chart = new ApexCharts(
        document.querySelector("#apexcharts-area6"),
        options
      );
      chart.render();
    });
	// product sales ratio
	$(function() {
      // Area chart
	  var labels = <?php echo json_encode($product3_labels);?>;
				var sales = <?php echo json_encode($product3_sales);?>;
      var options = {
        chart: {
          height: 200,
          type: "area",
        },
        dataLabels: {
          enabled: false
        },
        stroke: {
          curve: "smooth"
        },
        series: [{
          name: "Ratio %",
          data: sales
        }],
        xaxis: {
          type: "date",
          categories: labels
        },
        tooltip: {
          x: {
            format: "MM/yyyy"
          },
        }
      }
      var chart = new ApexCharts(
        document.querySelector("#apexcharts-area7"),
        options
      );
      chart.render();
    });
	// product non buyers
	$(function() {
      // Area chart
	  var labels = <?php echo json_encode($label_non_buyers);?>;
	  var sales = <?php echo json_encode($sale_non_buyers);?>;
      var options = {
        chart: {
          height: 200,
          type: "area",
        },
        dataLabels: {
          enabled: false
        },
        stroke: {
          curve: "smooth"
        },
        series: [{
          name: "Count",
          data: sales
        }],
        xaxis: {
          type: "date",
          categories: labels
        },
        tooltip: {
          x: {
            format: "MM/yyyy"
          },
        }
      }
      var chart = new ApexCharts(
        document.querySelector("#apexcharts-area8"),
        options
      );
      chart.render();
    });
	// appointment per day
	$(function() {
      // Area chart
	  var labels = <?php echo json_encode($appointment_per_day_labels);?>;
				var sales = <?php echo json_encode($appointmnet_per_day_count);?>;
      var options = {
        chart: {
          height: 200,
          type: "area",
        },
        dataLabels: {
          enabled: false
        },
        stroke: {
          curve: "smooth"
        },
        series: [{
          name: "count",
          data: sales
        }],
        xaxis: {
          type: "datetime",
          categories: labels
        },
        tooltip: {
          x: {
            format: "dd/MM/yyyy"
          },
        }
      }
      var chart = new ApexCharts(
        document.querySelector("#apexcharts-area9"),
        options
      );
      chart.render();
    });
	// appointment Cancellation
	$(function() {
      // Area chart
	  var labels = <?php echo json_encode($appointment_per_day_labels);?>;
				var sales = <?php echo json_encode($appointmnet_per_day_count);?>;
      var options = {
        chart: {
          height: 200,
          type: "area",
        },
        dataLabels: {
          enabled: false
        },
        stroke: {
          curve: "smooth"
        },
        series: [{
          name: "count",
          data: sales
        }],
        xaxis: {
          type: "datetime",
          categories: labels
        },
        tooltip: {
          x: {
            format: "dd/MM/yyyy"
          },
        }
      }
      var chart = new ApexCharts(
        document.querySelector("#apexcharts-area10"),
        options
      );
      chart.render();
    });
	// Appointmnet visit
	$(function() {
      // Area chart
	  var labels = <?php echo json_encode($appointment_visit_labels);?>;
				var sales = <?php echo json_encode($appointment_visit_count);?>;
      var options = {
        chart: {
          height: 200,
          type: "area",
        },
        dataLabels: {
          enabled: false
        },
        stroke: {
          curve: "smooth"
        },
        series: [{
          name: "count",
          data: sales
        }],
        xaxis: {
          type: "datetime",
          categories: labels
        },
        tooltip: {
          x: {
            format: "dd/MM/yyyy"
          },
        }
      }
      var chart = new ApexCharts(
        document.querySelector("#apexcharts-area11"),
        options
      );
      chart.render();
    });
	// appointment No Show
	$(function() {
      // Area chart
	  var labels = <?php echo json_encode($appointment_noshow_labels);?>;
				var sales = <?php echo json_encode($appointment_noshow_count);?>;
      var options = {
        chart: {
          height: 200,
          type: "area",
        },
        dataLabels: {
          enabled: false
        },
        stroke: {
          curve: "smooth"
        },
        series: [{
          name: "count",
          data: sales
        }],
        xaxis: {
          type: "datetime",
          categories: labels
        },
        tooltip: {
          x: {
            format: "dd/MM/yyyy"
          },
        }
      }
      var chart = new ApexCharts(
        document.querySelector("#apexcharts-area12"),
        options
      );
      chart.render();
    });
	
  </script>