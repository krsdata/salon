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
				<h1 class="h3 mb-3">Cash Book</h1>
				<div class="row">
					
				<div  class="col-md-12">
						<div class="card flex-fill w-100">
							<div class="card-header">
									<?php
												if(!empty($this->session->userdata['outlets']['current_outlet'])){
										            ?>
										            	<form action="<?php echo base_url('BusinessAdmin/cashbook')?>" method="POST" id="GetBills1">
										            <?php
										        }elseif(!empty($this->session->userdata['logged_in']['business_outlet_id'])){
										            ?>
										            <form action="<?php echo base_url('Cashier/cashbook')?>" method="POST" id="GetBills1">
										            <?php
										        }
											?>
											<div class="form-row">
											 
											<div class="col-md-5" style="text-align:end;float:right;">
												From <i class="fa fa-calendar" style="color:red;"></i>
											</div>
											<div class="from-group col-md-2">
												<input type="text" class="form-control" name="from_date" Placeholder="Enter Date"  value="<?php echo $from;?>">
											</div>
											<div class="col-md-1" style="text-align:end;">
												To <i class="fa fa-calendar" style="color:red;"></i>
											</div>
											<div class="from-group col-md-2">
												<input type="text" class="form-control" name="to_date" Placeholder="Enter Date" value="<?php echo $to;?>">
											</div>
											<div class="from-group col-md-2">
												<button class="btn btn-primary">Submit</button>
											</div>     
											</div>
										</form>
									</div>
							<div class="card-body">
							
						
								<table class="table datatables-basic1 table-hover" style="width:100%;">
									<thead>
										<tr>
											<th>Tender type</th>
											<th>Collection (From Sales)</th>
											<th>Due Amount 	Reced</th>
											<th>Expenses Paid</th>
											<th>Total Balance</th>											
										</tr>
									</thead>
									<tbody>
										<?php
										$total_o = 0;
										$total_t = 0;
										$total_p = 0;
										$total_e = 0;
										foreach ($p_mode as $key => $p) {
											?>
											<tr <?php if($p == "virtual_wallet"){ ?> style='background-color: red;' <?php } ?>>
												<td><?php echo ucfirst(str_replace("_", " ", $p));?></td>												
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
												<td><?php echo ($transaction_data[$p]+$pending_amount_data[$p]-$expenses_data[$p]);?></td>
											</tr>
											<?php
										}
										?>
										<tr>
												<td><strong>Total</strong></td>												
												<td><strong><?php echo $total_t;?></strong></td>
												<td><strong><?php echo $total_p;?></strong></td>
												<td><strong><?php echo $total_e;?></strong></td>
												<td><strong><?php echo ($total_t+$total_p-$total_e);?></strong></td>
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
</script>

 
