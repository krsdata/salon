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
				<h1 class="h3 mb-3">Package Details</h1>
				<div  class="row ">
					<div class="col-md-12">
					  <div class="row">
							<div class="form-group col-md-3">
								<div class="pck_detail_lable">Package Name : </div><div class="pck_detail_value"><?=$res_arr[0]['salon_package_name']; ?></div>
							</div>
							<div class="form-group col-md-3">
								<div class="pck_detail_lable">Price(Rs.) : </div><div class="pck_detail_value"><?=$res_arr[0]['salon_package_price']; ?></div>
							</div>
							<div class="form-group col-md-3">
								<div class="pck_detail_lable">GST : </div><div class="pck_detail_value"><?=$res_arr[0]['service_gst_percentage']; ?></div>
							</div>
							<div class="form-group col-md-3">
								<div class="pck_detail_lable">Total Amount(Rs.) : </div><div class="pck_detail_value"><?=$res_arr[0]['salon_package_upfront_amt']; ?></div>
							</div>
						</div>
						<div class="row">
							<div class="form-group col-md-3">
								<div class="pck_detail_lable">Package Type : </div><div class="pck_detail_value"><?=$res_arr[0]['salon_package_type']; ?></div>
							</div>
							<div class="form-group col-md-3">
								<div class="pck_detail_lable">Total Count of Services : </div><div class="pck_detail_value"><?=$res_arr[0]['total_count_of_services']; ?></div>
							</div>
							<div class="form-group col-md-3">
								<div class="pck_detail_lable">Validity(In Days) : </div><div class="pck_detail_value"><?=$res_arr[0]['salon_package_validity']; ?></div>
							</div>
							<div class="form-group col-md-3">
								<div class="pck_detail_lable">Package End Date : </div><div class="pck_detail_value"><?=$res_arr[0]['salon_package_end_date']; ?></div>
							</div>
						</div>
					</div>
					<div  class="col-md-12">
						<table class="table datatables-basic1 table-hover" style="width:100%;">
							<thead>
								<tr>
									<th>Sno.</th>
									<th>Bundled Services</th>
									<th>Count</th>
								</tr>
							</thead>
							<tbody>
								 <?php foreach($res_arr as $key=>$packages){ ?>
									<tr>
									
											<td><?=($key+1);?></td>
											<td><?=$packages['service_name']."-".$packages['service_price_inr'].""?></td>
											<td><?=$packages['service_count']?></td>
									
									</tr>
								<?php } ?>	
							</tbody>
						</table>
						
					</div>
				</div>
			</div>
		</main>
<style>
.pck_detail_lable{float:left;padding-right:5px;font-weight:600;}
.pck_detail_value{float:left;}
</style>
		
<?php
	$this->load->view('business_admin/ba_footer_view');
?>


 
