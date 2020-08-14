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
				<h1 class="h3 mb-3">Day Book</h1>
				<div class="row">
					
				<div  class="col-md-12">
						<div class="card flex-fill w-100">
							<div class="card-header">								
							<form action="<?php echo base_url('BusinessAdmin/expenses')?>" method="POST" id="GetBills1">
                <div class="form-row"> 
									<div class="col-md-3">
									<h3></h3>
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
											<th>Opening Cash</th>
											<th>Collection (From Sales)</th>
											<th>Due Amount 	Reced</th>
											<th>Expenses Paid</th>
											<th>Closing Cash</th>											
										</tr>
									</thead>
									<tbody>
										<?php
										$total_t = 0;
										$total_p = 0;
										$total_e = 0;
										foreach ($p_mode as $key => $p) {
											?>
											<tr <?php if($p == "virtual_wallet"){ ?> style='background-color: red;' <?php } ?>>
												<td><?php echo ucfirst(str_replace("_", " ", $p));?></td>
												<td></td>
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
												<td></td>
												<td><strong><?php echo $total_t;?></strong></td>
												<td><strong><?php echo $total_p;?></strong></td>
												<td><strong><?php echo $total_e;?></strong></td>
												<td><strong><?php echo ($total_t+$total_p+$total_e);?></strong></td>
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

 