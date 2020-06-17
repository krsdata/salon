<?php
	$this->load->view('universal/header_view');
?>
<div class="wrapper">
	<main class="content" style="width: 80%; margin-left: 10%;">
		<div class="container-fluid p-0">
			<div class="row">
				<div class="col-md-12">
					<div class="row">
						<div class="col-md-4">
						<?php
								echo "<h4 style='text-align: start;'>Customer Name : <strong>".$individual_customer['customer_name']."</strong></h4>";
								echo "<h5 style='text-align: start;'> Date : ".date('d-M-Y h:i A')."</h5>";
								
							?>
						</div>
						<div class="col-md-4">
							<?php
								echo "<h4 style='text-align: center;'>".$shop_details['business_outlet_name']."</h4>";
								echo "<h5 style='text-align: center;'>".$shop_details['business_outlet_address']."</h5>";
								echo "<h6 style='text-align: center;'>".$shop_details['business_outlet_bill_header_msg']."</h6>";
							?>
						</div>
						<div class="col-md-4" style="text-align: end;"><button class="btn btn-primary btn-outline" onClick="window.print()" id="printBill">Print</button></div>
					</div>
				</div>
				<div class="col-md-12">
					<?php
						if(!isset($cart) || empty($cart)){
					?>
					
					<div class="alert alert-secondary alert-outline alert-dismissible" role="alert">
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
					<table class="table table-hover">
						<thead>
							<tr>
								<th>Service Name</th>	
								<th>Expert</th>
								<th>Estimated Time</th>
								<th>FTD Value</th>
							</tr>
						</thead>
						<tbody>
							<?php
								foreach ($cart as $item):
							?>
							<tr>
								<td><?=$item['service_name']?></td>
								<td>
									<?php
										$key = array_search($item['service_expert_id'], array_column($experts, 'employee_id'));
										echo $experts[$key]['employee_first_name'].' '.$experts[$key]['employee_last_name'];
									?>
								</td>	
								<td><?=$item['service_est_time']?></td>
								<td><i class="fas fa-fw fa-rupee-sign" aria-hidden="true"></i><?=$item['service_price_inr']?></td>
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
				<div class="col-md-12 mt-3">
					<?php
						if(isset($cart) && !empty($cart)):
					?>
					<div class="mb-3">
						<table class="table table-hover">
							<tbody>
								<tr>
									<td style="width:30%;">Total Amount</td>
									
									<td style="width:10%;"><i class="fa fa-clock" aria-hidden="true"></i></td>
									<td style="width:20%;"><?php
										$total_amount = 0;
										
										foreach ($cart as $item) {
											$total_amount += ((int)$item['service_est_time']);
										}

										echo $total_amount;
									?></td>
									<td style="width:15%;"><i class="fas fa-fw fa-rupee-sign" aria-hidden="true"></i>
									<?php
										$total_amount = 0;
										
										foreach ($cart as $item) {
											$total_amount += ((int)$item['service_price_inr']);
										}

										echo $total_amount;
									?>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
					<?php
						endif;
					?>
				</div>
				<div class="col-md-12">
					<?php
						echo "<h4 style='text-align: center;margin-top: 30px;'>".$shop_details['business_outlet_bill_footer_msg']."</h4>";
					?>
				</div>
			</div>
		</div>
	</main>
<?php
	$this->load->view('universal/footer_view');
?>
