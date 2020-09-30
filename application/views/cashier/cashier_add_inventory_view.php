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
				<h1 class="h3 mb-3">Inventory Management</h1>
				<div class="row">
					<div class="col-md-12">
						<div class="card">
							<div class="card-header" style="margin-left:10px;">
								<ul class="nav nav-pills card-header-pills pull-right" role="tablist" style="font-weight: bolder">
									<li class="nav-item">
										<a class="nav-link active" data-toggle="tab" href="#tab-1">Add Stock</a>
									</li>
									<li class="nav-item">
										<a class="nav-link" data-toggle="tab" href="#tab-2">Stock Transfer</a>
									</li>
									<li class="nav-item">
										<a class="nav-link" data-toggle="tab" href="#tab-3">Stock Level</a>
									</li>
									<li class="nav-item">
										<a class="nav-link" data-toggle="tab" href="#tab-4">Incoming Stock</a>
									</li>
									<li class="nav-item">
										<a class="nav-link" data-toggle="tab" href="#tab-5">Outgoing Stock</a>
									</li>
								</ul>
							</div>
							<div class="card-body">
								<div class="tab-content">
									<div class="tab-pane show active" id="tab-1" role="tabpanel">
										<div class="row">
											<div class="col-md-12">
												<div class="card">
													<div class="card-header">
														<h4>Stock Entry</h4>
													</div>
													<div class="card-body">
														<form action="#" id="AddProduct" method="POST">
															<div class="form-row">
																<div class="col-md-6">
																	<div class="row">
																		<div class="form-group col-md-4">
																		<input type="text" class="form-control" name="invoice_number" placeholder="Invoice No.">
																		</div>
																	</div>
																	<div class="row">
																		<div class="form-group col-md-4">
																		<input type="date" class="form-control" value="<?=date('Y-m-d');?>" name="invoice_date"  placeholder="Entry Date">
																		</div>
																	</div>
																	<div class="row">
																		<div class="form-group col-md-4">
																		<input type="text" class="form-control" name="invoice_amount" min="0" placeholder="Invoice Value">
																		</div>
																	</div>
																	<div class="row">
																		<div class="form-group col-md-4">
																		<input type="text" class="form-control" name="invoice_tax" min="0"  placeholder="Extra Freight Charges">
																		</div>
																	</div>
																</div>
																<div class="col-md-6">
																	<div class="row">
																		<div class="form-group col-md-4">
																			<select name="source_type" class="form-control" required>
																				<option value="" disabled="disabled" selected>Select Source Type</option>
																				<option value="warehouse">Warehouse</option>
																				<option value="branch">Branch</option>
																				<option value="vendor">Vendor</option>
																				<option value="return">Sales return</option>
																			</select>
																		</div>
																	</div>
																	<div class="row">
																		<div class="form-group col-md-4">
																			<select name="source_name" class="form-control" required>
																			
																			</select>
																		</div>
																	</div>
																	<div class="row">
																		<div class="form-group col-md-4">
																			<select name="invoice_type" class="form-control" required>
																			<option value="" disabled="disabled" selected>Select Invoice Type</option>
																				<option value="tax">Tax Invoice</option>
																				<option value="lumpsum">Lumpsum</option>
																				<option value="challan">Delivery Challan</option>
																			</select>
																		</div>
																	</div>
																</div>
																<div class="col-md-12">
																	<div class="form-group col-md-3">
																		<input type="text" id="searchProductByName" class="form-control" placeholder="Search Product By Name or Barcode">
																	</div>
																</div>
																<div id="productTable">
																	<table id="addProductTable" class="table table-hover table-bordered mb-1">
																		<tbody>
																			<tr>
																				<td>1</td>
																				<td>
																					<div class="form-group">
																						<label>Product Name</label>
																						<input type="text" class="form-control searchProductByName" name="product_name[]" readonly required>
																						<input type="hidden" class="product_id" name="product_id[]" >
																					</div>
																				</td>
																				<td>
																					<div class="form-group">
																						<label>Type</label>
																						<input type="text" class="form-control product_type" name="product_type[]" readonly required>
																					</div>
																				</td>
																				<td>
																					<div class="form-group">
																						<label>Barcode</label>
																						<input type="text" class="form-control product_barcode" name="product_barcode[]" readonly>
																					</div>
																				</td>
																				<td>
																					<div class="form-group">
																						<label>SKU Size</label>
																						<input type="text" class="form-control sku_size" name="sku_size[]" temp="service_price_inr" readonly required>
																					</div>
																				</td>
																				<td>
																					<div class="form-group">
																						<label>Qty</label>
																						<input type="text" class="form-control" name="product_qty[]" required>
																					</div>
																				</td>
																				<td>
																					<div class="form-group">
																						<label>Cost/Unit(<small>before tax</small>)</label>
																						<input type="text" class="form-control" name="product_price[]" required>
																					</div>
																				</td>
																				<td>
																					<div class="form-group">
																						<label>GST %</label>
																						<input type="text" class="form-control gst" name="product_gst[]" required>
																					</div>
																				</td>
																				<td>
																					<div class="form-group">
																						<label>MRP</label>
																						<input type="text" class="form-control mrp" name="product_mrp[]" required>
																					</div>
																				</td>
																				<td>
																					<div class="form-group">
																						<label>Expiry</label>
																						<input type="date" class="form-control" value="<?=date('Y-m-d',strtotime('+ 1 year', strtotime(date('Y-m-d'))));?>" name="product_exp_date[]" temp="Count" required>
																					</div>
																				</td>
																			</tr>
																		</tbody>
																	</table>
																	<button type="button" class="btn btn-success" id="AddRowProductTable">Add <i class="fa fa-plus" aria-hidden="true"></i></button>&ensp;
																	<button type="button" class="btn btn-danger" id="DeleteRowProductTable">Delete <i class="fa fa-trash" aria-hidden="true"></i></button>
																</div>										
															</div>
															<div class="form-row mt-2">
																<div class="col-md-6">
																	<div class="row">
																		<div class="form-group col-md-12">
																			<textarea class="form-control" name="note" placeholder="Notes :-"></textarea>
																		</div>
																	</div>
																</div>
																<div class="col-md-6">
																	<div class="row">
																		<div class="form-group col-md-4">
																			<input type="number" name="amount_paid" class="form-control" min="0" placeholder="Enter Amount">
																		</div>
																		<div class="form-group col-md-4">
																			<select name="payment_mode" class="form-control">
																			<!-- <option value="" disabled="disabled" selected>Payment Mode</option> -->
																					<option value="cash">Cash</option>
																					<option value="credit_card">Credit Card</option>
																					<option value="debit_card">Debit Card</option>
																					<option value="phonepe">Phonepe</option>
																					<option value="google_pay">Google Pay</option>
																					<option value="paytm">Paytm</option>
																					<option value="bank">Bank A/C</option>
																			</select>
																		</div>
																		<div class="form-group col-md-4">
																			<input type="text name" class="form-control" name="payment_status" placeholder="Payment Status" readonly>
																		</div>
																	</div>
																</div>
															</div>
															<div class="form-row">
																<div class="form-group ml-2">
																	<button type="submit" value="" class="btn btn-primary ">Submit</button>
																</div>
															</div>
														</form>
													</div>
												</div>
											</div>
										</div>
									</div>									
									<div class="tab-pane" id="tab-2" role="tabpanel">
										<div class="row">
											<div class="col-md-12">
												<div class="card">
													<div class="card-header">
														<h4>Stock Transfer</h4>
													</div>
													<div class="card-body">
														<form action="#" id="TransProduct" method="POST">
															<div class="form-row">
																<div class="col-md-6">
																	<div class="row">
																		<div class="form-group col-md-4">
																		<input type="text" class="form-control" name="invoice_number" placeholder="Tranf. Order">
																		</div>
																	</div>
																	<div class="row">
																		<div class="form-group col-md-4">
																		<input type="date" class="form-control" value="<?=date('Y-m-d');?>" name="invoice_date"  placeholder="Entry Date">
																		</div>
																	</div>
																	<div class="row">
																		<div class="form-group col-md-4">
																		<input type="text" class="form-control" name="invoice_amount" min="0" placeholder="Invoice Value">
																		</div>
																	</div>
																	<div class="row">
																		<div class="form-group col-md-4">
																		<input type="text" class="form-control" name="invoice_tax" min="0"  placeholder="Extra Freight Charges">
																		</div>
																	</div>
																</div>
																<div class="col-md-6">
																	<div class="row">
																		<div class="form-group col-md-4">
																			<select name="destination_type" class="form-control" required>
																				<option value="" disabled="disabled" selected>Select Destination Type</option>
																				<option value="warehouse">Warehouse</option>
																				<option value="branch">Branch</option>
																				<option value="vendor">Vendor</option>
																				<option value="return">Sales return</option>
																			</select>
																		</div>
																	</div>
																	<div class="row">
																		<div class="form-group col-md-4">
																			<select name="destination_name" class="form-control" required>
																			
																			</select>
																		</div>
																	</div>
																	<div class="row">
																		<div class="form-group col-md-4">
																			<select name="invoice_type" class="form-control" required>
																			<option value="" disabled="disabled" selected>Select Invoice Type</option>
																				<option value="tax">Tax Invoice</option>
																				<option value="lumpsum">Lumpsum</option>
																				<option value="challan">Delivery Challan</option>
																			</select>
																		</div>
																	</div>
																</div>
																<div class="col-md-12">
																	<div class="form-group col-md-3">
																		<input type="text" id="searchProductTransferByName" class="form-control" placeholder="Search Product By Name or Barcode">
																	</div>
																</div>
																<div id="transferTable">
																	<table id="transProductTable" class="table table-hover table-bordered mb-1">
																		<tbody>
																			<tr>
																				<td>1</td>
																				<td>
																					<div class="form-group">
																						<label>Product Name</label>
																						<input type="text" class="form-control searchProductByName" name="product_name[]" readonly required>
																						<input type="hidden" class="product_id" name="product_id[]">
																					</div>
																				</td>
																				<td>
																					<div class="form-group">
																						<label>Type</label>
																						<input type="text" class="form-control product_type" name="product_type[]" readonly required>
																					</div>
																				</td>
																				<td>
																					<div class="form-group">
																						<label>Barcode</label>
																						<input type="text" class="form-control product_barcode" name="product_barcode[]" readonly>
																					</div>
																				</td>
																				<td>
																					<div class="form-group">
																						<label>SKU Size</label>
																						<input type="text" class="form-control sku_size" name="sku_size[]" temp="service_price_inr" readonly required>
																					</div>
																				</td>
																				<td>
																					<div class="form-group">
																						<label>Qty</label>
																						<input type="text" class="form-control" name="product_qty[]" required>
																					</div>
																				</td>
																				<td>
																					<div class="form-group">
																						<label>Cost/Unit(<small>before tax</small>)</label>
																						<input type="text" class="form-control" name="product_price[]" required>
																					</div>
																				</td>
																				<td>
																					<div class="form-group">
																						<label>GST %</label>
																						<input type="text" class="form-control gst" name="product_gst[]" required>
																					</div>
																				</td>
																				<td>
																					<div class="form-group">
																						<label>MRP</label>
																						<input type="text" class="form-control mrp" name="product_mrp[]" required>
																					</div>
																				</td>
																				<td>
																					<div class="form-group">
																						<label>Expiry</label>
																						<input type="date" class="form-control" value="<?=date('Y-m-d',strtotime('+ 1 year', strtotime(date('Y-m-d'))));?>" name="product_exp_date[]" temp="Count">
																					</div>
																				</td>
																			</tr>
																		</tbody>
																	</table>
																	<button type="button" class="btn btn-success" id="AddRowProductTransTable">Add <i class="fa fa-plus" aria-hidden="true"></i></button>&ensp;
																	<button type="button" class="btn btn-danger" id="DeleteRowProductTransTable">Delete <i class="fa fa-trash" aria-hidden="true"></i></button>
																</div>										
															</div>
															<div class="form-row mt-2">
																<div class="col-md-6">
																	<div class="row">
																		<div class="form-group col-md-12">
																			<textarea class="form-control" name="note" placeholder="Notes :-"></textarea>
																		</div>
																	</div>
																</div>
																<div class="col-md-6">
																	<div class="row">
																		<div class="form-group col-md-4">
																			<input type="number" name="amount_paid" class="form-control" min="0" placeholder="Enter Amount">
																		</div>
																		<div class="form-group col-md-4">
																			<select name="payment_mode" class="form-control">
																				<!-- <option value="" disabled="disabled" selected>Payment Mode</option> -->
																				<option value="cash">Cash</option>
																					<option value="credit_card">Credit Card</option>
																					<option value="debit_card">Debit Card</option>
																					<option value="phonepe">Phonepe</option>
																					<option value="google_pay">Google Pay</option>
																					<option value="paytm">Paytm</option>
																					<option value="bank">Bank A/C</option>
																			</select>
																		</div>
																		<div class="form-group col-md-4">
																			<input type="text name" class="form-control" name="payment_status" placeholder="Payment Status" readonly>
																		</div>
																	</div>
																</div>
															</div>
															<div class="form-row">
																<div class="form-group ml-2">
																	<button type="submit" value="" class="btn btn-primary ">Submit</button>
																</div>
															</div>
														</form>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="tab-pane" id="tab-3" role="tabpanel">
										<div class="card">
											<div class="card-header">
												<!-- <form action="#" class="form-inlne" method="POST">
													<div class="form-row">
														<div class="form-group col-md-2">
															<input type="date" class="form-control" name="invoice_number" value="<?=date('Y-m-d');?>">
														</div>										
														<div class="form-group col-md-2">
															<select name="" class="form-control" required>
															<option selected="selected" disabled>Select type</option>
																<option value="">Warehouse</option>
																<option value="">Branch</option>
															</select>
														</div>
														<div class="form-group col-md-3">
															<select name="" class="form-control" required>
																<option value="">Warehouse</option>
																<option value="">Branch</option>
															</select>
														</div>
														<div class="form-group col-md-3">
															<select name="" class="form-control" required>
																<option selected="selected" disabled>Stock Category</option>
																<option value="">All</option>
																<option value="">Regular</option>
																<option value="">Slow moving</option>
																<option value="">Dead</option>
															</select>
														</div>
														<div class="form-group col-md-2">
															<button type="submit" value="" class="btn btn-primary ">Submit</button>
														</div>
													</div>
												</form> -->
												<div class="row">
													<div class="col-md-10">
														<h3>Available Stock</h3>
													</div>
													<div class="col-md-2">
													<button class="btn btn-primary" onclick="exportTableToExcel('availableStock','Product Stock')"><i class="fa fa-download"></i> Download</button>
													</div>
												</div>												
											</div>
											<div class="card-body">
												<table class="table table-hover datatables-basic" style="width: 100%;" id="availableStock">
													<thead>
														<th>Sr. No.</th>
														<th>Product Name</th>
														<th>Type</th>
														<th>Barcode</th>
														<th>SKU size</th>
														<th>Total Stock</th>
														<th>Stock in Unit</th>
														<th>Last Updated</th>
														<th>Location</th>
													</thead>
													<tbody>
														<?php $count=1; foreach($stock as $stock){ ?>
															<tr>
														<td><?=$count?></td>
														<td><?=$stock['service_name'];?></td>
														<td><?=$stock['inventory_type'];?></td>
														<td><?=$stock['barcode'];?></td>
														<td><?=$stock['qty_per_item'].' '.$stock['service_unit'] ; ?></td>
														<td><?php if(empty($stock['total_stock']) || $stock['total_stock']==""){echo 0;}else{echo $stock['total_stock'];}?></td>
														<td><?php if(empty($stock['stock_in_unit']) || $stock['stock_in_unit']==""){echo "0"." ".$stock['service_unit'];}else{echo $stock['stock_in_unit']." ".$stock['service_unit'];} ?></td>
														<td><?=$stock['updated_on'];?></td>
														<td><?=$stock['business_outlet_name'];?></td>
														</tr>
														<?php $count++; }?>
													</tbody>
												</table>
											</div>
										</div>
									</div>
									<div class="tab-pane" id="tab-4" role="tabpanel">
										<div class="card">
											<div class="card-header">
												<h4>Incoming Stock</h4>
											</div>
											<div class="card-body">
												<table class="table table-hover datatables-basic" style="width: 100%;">
													<thead>
														<th>Sr. No.</th>
														<th>Product Name</th>
														<th>Type</th>
														<th>Barcode</th>
														<th>SKU size</th>
														<th>Product Qty</th>
														<th>MRP</th>
														<th>Entry Date</th>
														<th>Source</th>
														<th>Destnation</th>
														<th>Actions</th>
													</thead>
													<tbody>
														<?php $count=1; foreach($stock_incoming as $incoming){ ?>
															<tr>
														<td><?=$count?></td>
														<td><?=$incoming['product_name'];?></td>
														<td><?=$incoming['product_type'];?></td>
														<td><?=$incoming['product_barcode'];?></td>
														<td><?=$incoming['sku_size'].' '.$stock['service_unit'];?></td>
														<td><?=$incoming['product_qty'];?></td>
														<td><?=$incoming['product_mrp'];?></td>
														<td><?=$incoming['invoice_date'];?></td>
														<td><?=$incoming['source'];?></td>
														<td><?=$incoming['destination'];?></td>
														<td>
															<?php if($incoming['transfer_status']==0){?>
															<button class='btn btn-success acceptInventory'  trans_data_id='<?=$incoming['inventory_transfer_data_id']?>' total_stock='<?=$incoming['product_qty']?>' stock_service_id='<?=$incoming['service_id']?>' sku_size="<?=$incoming['sku_size']?>" sender_outlet='<?=$incoming['business_outlet_id']?>'><i class='fa fa-check'>Accept</i></button>
															<button class='btn btn-danger rejectInventory'  trans_data_id='<?=$incoming['inventory_transfer_data_id']?>'><i class='fa fa-times'>Reject</i></button>
															<?php }else{?>
																Accepted
															<?php } ?>
														</td>
														</tr>
														<?php $count++; }?>
													</tbody>
												</table>
											</div>
										</div>
									</div>
									<div class="tab-pane" id="tab-5" role="tabpanel">
										<div class="card">
											<div class="card-header">
												<h4>Outgoing Stock</h4>
											</div>
											<div class="card-body">
												<table class="table table-hover datatables-basic" style="width: 100%;">
													<thead>
														<th>Sr. No.</th>
														<th>Product Name</th>
														<th>Type</th>
														<th>Barcode</th>
														<th>SKU size</th>
														<th>Product Qty</th>
														<th>MRP</th>
														<th>Last Updated</th>
														<th>Status</th>
													</thead>
													<tbody>
														<?php $count=1; foreach($stock_outgoing as $outgoing){ ?>
															<tr>
														<td><?=$count?></td>
														<td><?=$outgoing['product_name'];?></td>
														<td><?=$outgoing['product_type'];?></td>
														<td><?=$outgoing['product_barcode'];?></td>
														<td><?=$outgoing['sku_size'].' '.$stock['service_unit'];?></td>
														<td><?=$outgoing['product_qty'];?></td>
														<td><?=$outgoing['product_mrp'];?></td>
														<td><?=$outgoing['invoice_date'];?></td>
														
														<td>
															<?php if($outgoing['transfer_status']==0){?>
															<button class='btn btn-warning' disabled>No action</button>
															<?php }else if($outgoing['transfer_status']==1){?>
															<button class='btn btn-success' disabled>Accepted</button>
															<?php }else{?>
															<button class='btn btn-danger' disabled>Rejected</button>
															<?php }?>
														</td>
														</tr>
														<?php $count++; }?>
													</tbody>
												</table>
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
		$(".datatables-basic").DataTable({
			responsive: true
		});

		$("#AddProduct select[name=source_type]").on('change',function(e){
  		var parameters = {
  			'source_type' :  $(this).val()
			};
			// alert($(this).val());
    	$.getJSON("<?=base_url()?>Cashier/GetBranchAndVendor", parameters)
      .done(function(data, textStatus, jqXHR) {
				// alert(data.message);
				$('#AddProduct select[name=source_name]').children().remove();
      	for(var i=0; i < data.message.length;i++ ){
					console.log(data.message[i].source_id);
					$("#AddProduct select[name=source_name]").append("<option value=" + data.message[i].source_id+ ">" + data.message[i].source_name+ "</option>")
				}
    	})
    	.fail(function(jqXHR, textStatus, errorThrown) {
        console.log(errorThrown.toString());
   		});
		});
		$("#TransProduct select[name=destination_type]").on('change',function(e){
  		var parameters = {
  			'source_type' :  $(this).val()
			};
			// alert($(this).val());
    	$.getJSON("<?=base_url()?>Cashier/GetBranchAndVendor", parameters)
      .done(function(data, textStatus, jqXHR) {
				// alert(data.message);
				$('#TransProduct select[name=destination_name]').children().remove();
      	for(var i=0; i < data.message.length;i++ ){
					console.log(data.message[i].source_id);
					$("#TransProduct select[name=destination_name]").append("<option value=" + data.message[i].source_id+ ">" + data.message[i].source_name+ "</option>")
				}
    	})
    	.fail(function(jqXHR, textStatus, errorThrown) {
        console.log(errorThrown.toString());
   		});
    });

		$("#AddRowProductTable").click(function(event){
			event.preventDefault();
			this.blur();
			var rowno = $("#addProductTable tr").length;
			
			rowno = rowno+1;
			
			$("#addProductTable tr:last").after("<tr><td>"+rowno+"</td><td><div class=\"form-group\"><input type=\"text\" class=\"form-control searchProductByName\" name=\"product_name[]\" readonly><input type=\"hidden\" class=\"product_id\" name=\"product_id[]\"></div></td><td><div class=\"form-group\"><input type=\"text\" class=\"form-control product_type\" name=\"product_type[]\" readonly></div></td><td><div class=\"form-group\"><input type=\"text\" class=\"form-control product_barcode\" name=\"product_barcode[]\" readonly></div></td><td><div class=\"form-group\" ><input type=\"text\" class=\"form-control sku_size\" name=\"sku_size[]\" readonly></div></td><td><div class=\"form-group\"><input type=\"number\" class=\"form-control\" name=\"product_qty[]\"></div></td><td><div class=\"form-group\"><input type=\"number\" class=\"form-control\" name=\"product_price[]\"></div></td><td><div class=\"form-group\"><input type=\"number\" class=\"form-control gst\" name=\"product_gst[]\"></div></td><td><div class=\"form-group\"><input type=\"number\" class=\"form-control mrp\" name=\"product_mrp[]\"></div></td><td><div class=\"form-group\"><input type=\"date\" class=\"form-control\" value=\"<?=date('Y-m-d',strtotime('+ 1 year', strtotime(date('Y-m-d'))));?>\" name=\"product_exp_date[]\" ></div></td></tr>");
		});

		$("#DeleteRowProductTable").click(function(event){
			event.preventDefault();
			this.blur();
			var rowno = $("#addProductTable tr").length;
			if(rowno > 1){
				$('#addProductTable tr:last').remove();
			}
		});

			//Search Product By Name

		$("#searchProductByName").typeahead({	
      autoselect: true,
      highlight: true,
      minLength: 1
     },
     {
      source: searchProductByName,
      templates: {
        empty: "No Product Found!",
        suggestion: _.template("<p class='service_search'><%- service_name %>(<%- qty_per_item %><%- service_unit %>),(<%- sub_category_name %>), (<%- category_name %>),Rs <%- service_price_inr %> </p>")
      }
    });
       
    // var to_fill = "";
		var service_name="",product_id="", service_type="", service_barcode="", sku_size="", gst="", mrp="";

    $("#searchProductByName").on("typeahead:selected", function(eventObject, suggestion, name) {
      var loc = "#addProductTable tr:last .searchProductByName";
			var loc2 = "#addProductTable tr:last .product_type";
			var loc3 = "#addProductTable tr:last .product_barcode";
			var loc4 = "#addProductTable tr:last .sku_size";
			var loc5 = "#addProductTable tr:last .mrp";
			var loc6 = "#addProductTable tr:last .product_id";
			var loc7 = "#addProductTable tr:last .gst";
      service_name = suggestion.service_name;
			product_id = suggestion.service_id;
			service_type = suggestion.inventory_type;
			service_barcode = suggestion.barcode;
			sku_size = suggestion.qty_per_item+suggestion.service_unit;
			mrp = suggestion.mrp;
			gst = suggestion.service_gst_percentage;
      setVals(loc,service_name,suggestion);
			setVals(loc2,service_type,suggestion);
			setVals(loc3,service_barcode,suggestion);
			setVals(loc4,sku_size,suggestion);
			setVals(loc5,mrp,suggestion);
			setVals(loc6,product_id,suggestion);
			setVals(loc7,gst,suggestion);
    });

    $("#searchProductByName").blur(function(){
      $("#addProductTable tr:last .searchProductByName").val(service_name);
			$("#addProductTable tr:last .product_id").val(product_id);
			$("#addProductTable tr:last .product_type").val(service_type);
			$("#addProductTable tr:last .product_barcode").val(service_barcode);
			$("#addProductTable tr:last .sku_size").val(sku_size);
			$("#addProductTable tr:last .mrp").val(mrp);
			$("#addProductTable tr:last .gst").val(gst);
      service_name = "";
			product_id="";
			service_type = "";
			service_barcode = "";
			sku_size = "";
			mrp="";
			gst="";
    });

    function searchProductByName(query, cb){
		var inventory = 'Retail Product';
      var parameters = {
        query : query,
		inventory_type : inventory
      };
      
      $.ajax({
        url: "<?=base_url()?>Cashier/GetProductData",
        data: parameters,
        type: "GET",
				cache: false,
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
    }


		$("#AddProduct input[name=amount_paid]").on('input',function(e){
  			var amt_paid=  Number($(this).val());
				var invoice_amount= Number($("#AddProduct input[name=invoice_amount]").val());
				if(amt_paid==invoice_amount){
					$("#AddProduct input[name=payment_status]").attr('value',"paid");
				}else if(amt_paid > 0 && amt_paid < invoice_amount){
					$("#AddProduct input[name=payment_status]").attr('value',"partial paid");
				}else if(amt_paid == 0){
					$("#AddProduct input[name=payment_status]").attr('value',"unpaid");
				}else{
					alert("Amount Paid is much than Invoice Amount.");
					$("#AddProduct input[name=payment_status]").attr('value',"over paid");
				}	
    });

		$("#AddProduct").validate({
	  	errorElement: "div",
	    rules: {	       
	        "source_type" : {
	        	required : true
	        },
					"product_name" : {
	        	required : true
	        }

	    },
	    submitHandler: function(form) {
			// alert(document.getElementById('expiry').value);
				var formData = $("#AddProduct").serialize(); 
				$.ajax({
	        url: "<?=base_url()?>Cashier/AddInventory",
	        data: formData,
	        type: "POST",
					cache: false,
	    		success: function(data) {
            if(data.success == 'true'){
            	var message1 = data.message;
							var title = "Inventory Addition";
							var type = "success";
							toastr[type](message1, title, {
								positionClass: "toast-top-right",
								progressBar: "toastr-progress-bar",
								newestOnTop: "toastr-newest-on-top",
								rtl: $("body").attr("dir") === "rtl" || $("html").attr("dir") === "rtl",
								timeOut: 1000
							});
							setTimeout(function () { location.reload(1); }, 1000);
            }
            else if (data.success == 'false'){                   
        	    alert("Error in Adding Inventory.");
            }
          },
          error: function(data){
  					$('.feedback').addClass('alert-danger');
  					$('.alert-message').html("").html(data.message); 
          }
				});
			},
		});

		//addproductend
		//Transfer Product start
			$("#AddRowProductTransTable").click(function(event){
				event.preventDefault();
				this.blur();
				var rowno = $("#transProductTable tr").length;
				
				rowno = rowno+1;
				
				$("#transProductTable tr:last").after("<tr><td>"+rowno+"</td><td><div class=\"form-group\"><input type=\"text\" class=\"form-control searchProductByName\" name=\"product_name[]\" readonly><input type=\"hidden\" class=\"product_id\" name=\"product_id[]\"></div></td><td><div class=\"form-group\"><input type=\"text\" class=\"form-control product_type\" name=\"product_type[]\" readonly></div></td><td><div class=\"form-group\"><input type=\"text\" class=\"form-control product_barcode\" name=\"product_barcode[]\" readonly></div></td><td><div class=\"form-group\" ><input type=\"text\" class=\"form-control sku_size\" name=\"sku_size[]\" readonly></div></td><td><div class=\"form-group\"><input type=\"number\" class=\"form-control\" name=\"product_qty[]\"></div></td><td><div class=\"form-group\"><input type=\"number\" class=\"form-control\" name=\"product_price[]\"></div></td><td><div class=\"form-group\"><input type=\"number\" class=\"form-control gst\" name=\"product_gst[]\"></div></td><td><div class=\"form-group\"><input type=\"number\" class=\"form-control mrp\" name=\"product_mrp[]\"></div></td><td><div class=\"form-group\"><input type=\"date\" class=\"form-control\" value=\"<?=date('Y-m-d',strtotime('+ 1 year', strtotime(date('Y-m-d'))));?>\" name=\"product_exp_date[]\" ></div></td></tr>");
			});

			$("#DeleteRowProductTransTable").click(function(event){
				event.preventDefault();
				this.blur();
				var rowno = $("#transProductTable tr").length;
				if(rowno > 1){
					$('#transProductTable tr:last').remove();
				}
			});

			//Search Product By Name

		$("#searchProductTransferByName").typeahead({	
      autoselect: true,
      highlight: true,
      minLength: 1
     },
     {
      source: searchProductTransByName,
      templates: {
        empty: "No Product Found!",
        suggestion: _.template("<p class='service_search'><%- service_name %>(<%- qty_per_item %><%- service_unit %>),(<%- sub_category_name %>), (<%- category_name %>),Rs <%- service_price_inr %> </p>")
      }
    });
       
    // var to_fill = "";
		var service_name="",product_id="", service_type="", service_barcode="", sku_size="", gst="", mrp="";

    $("#searchProductTransferByName").on("typeahead:selected", function(eventObject, suggestion, name) {
      var loc = "#transProductTable tr:last .searchProductByName";
			var loc2 = "#transProductTable tr:last .product_type";
			var loc3 = "#transProductTable tr:last .product_barcode";
			var loc4 = "#transProductTable tr:last .sku_size";
			var loc5 = "#transProductTable tr:last .mrp";
			var loc6 = "#transProductTable tr:last .product_id";
			var loc7 = "#transProductTable tr:last .gst";
      service_name = suggestion.service_name;
			service_type = suggestion.inventory_type;
			service_barcode = suggestion.barcode;
			sku_size = suggestion.qty_per_item;
			mrp = suggestion.mrp;
			product_id=suggestion.service_id;
			gst=suggestion.service_gst_percentage;

      setVals(loc,service_name,suggestion);
			setVals(loc2,service_type,suggestion);
			setVals(loc3,service_barcode,suggestion);
			setVals(loc4,sku_size,suggestion);
			setVals(loc5,mrp,suggestion);
			setVals(loc6,product_id,suggestion);
			setVals(loc7,gst,suggestion);
    });

    $("#searchProductTransferByName").blur(function(){
      $("#transProductTable tr:last .searchProductByName").val(service_name);
			$("#transProductTable tr:last .product_type").val(service_type);
			$("#transProductTable tr:last .product_barcode").val(service_barcode);
			$("#transProductTable tr:last .sku_size").val(sku_size);
			$("#transProductTable tr:last .mrp").val(mrp);
			$("#transProductTable tr:last .product_id").val(product_id);
			$("#transProductTable tr:last .gst").val(gst);

      service_name = "";
			service_type = "";
			service_barcode = "";
			sku_size = "";
			mrp="";
			product_id="";
			gst="";
    });

    function searchProductTransByName(query, cb){
		var inventory = 'Retail Product';
      var parameters = {
        query : query,
		inventory_type : inventory
      };
      
      $.ajax({
        url: "<?=base_url()?>Cashier/GetProductData",
        data: parameters,
        type: "GET",
				cache: false,
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
    }


		$("#TransProduct input[name=amount_paid]").on('input',function(e){
  			var amt_paid=  Number($(this).val());
				var invoice_amount= Number($("#TransProduct input[name=invoice_amount]").val());
				if(amt_paid==invoice_amount){
					$("#TransProduct input[name=payment_status]").attr('value',"paid");
				}else if(amt_paid > 0 && amt_paid < invoice_amount){
					$("#TransProduct input[name=payment_status]").attr('value',"partial paid");
				}else if(amt_paid == 0){
					$("#TransProduct input[name=payment_status]").attr('value',"unpaid");
				}else{
					alert("Amount Paid is much than Invoice Amount.");
					$("#TransProduct input[name=payment_status]").attr('value',"over paid");
				}	
    });

		$("#TransProduct").validate({
	  	errorElement: "div",
	    rules: {	       
	        "destination_type" : {
	        	required : true
	        },
					"product_name" : {
	        	required : true
	        }

	    },
	    submitHandler: function(form) {
			// alert(document.getElementById('expiry').value);
				var formData = $("#TransProduct").serialize(); 
				$.ajax({
	        url: "<?=base_url()?>Cashier/TransferInventory",
	        data: formData,
	        type: "POST",
					cache: false,
	    		success: function(data) {
            if(data.success == 'true'){
            	var message1 = data.message;
							var title = "Inventory Transfer";
							var type = "success";
							toastr[type](message1, title, {
								positionClass: "toast-top-right",
								progressBar: "toastr-progress-bar",
								newestOnTop: "toastr-newest-on-top",
								rtl: $("body").attr("dir") === "rtl" || $("html").attr("dir") === "rtl",
								timeOut: 1000
							});
							setTimeout(function () { location.reload(1); }, 1000);
            }
            else if (data.success == 'false'){                   
        	    alert(data.message);
            }
          },
          error: function(data){
  					$('.feedback').addClass('alert-danger');
  					$('.alert-message').html("").html(data.message); 
          }
				});
			},
		});


		$(document).on('click',".acceptInventory",function(event){
    	event.preventDefault();
			this.blur();
			var parameters = {
					transfer_data_id : $(this).attr('trans_data_id'),
					total_stock : $(this).attr('total_stock'),
					stock_in_unit:$(this).attr('sku_size'),
					service_id	:	$(this).attr('stock_service_id'),
					sender_outlet_id	:	$(this).attr('sender_outlet')
			};	
			$.ajax({
				url: "<?=base_url()?>Cashier/TransferFinalInventory",
				data: parameters,
				type: "POST",
				cache: false,
	    	success: function(data) {
					if(data.success == 'true'){
						alert("Inventory Updated");
						window.location.reload();
					}
					else if (data.success == 'false'){                   
						alert("Inventory Error");
						window.location.reload();
					}	
				}
			}); 
		});	
		
		$(document).on('click',".rejectInventory",function(event){
    	event.preventDefault();
			this.blur();
			var parameters = {
				transfer_data_id : $(this).attr('trans_data_id')
			};	
			$.ajax({
				url: "<?=base_url()?>Cashier/RejectTransferInventory",
				data: parameters,
				type: "POST",
				cache: false,
	    	success: function(data) {
					if(data.success == 'true'){
						alert("Inventory Rejected");
						window.location.reload();
					}
					else if (data.success == 'false'){                   
						alert("Inventory Error");
						window.location.reload();
					}	
				}
			}); 
    });
		//trans end
		$("#RawMaterialCategoryId").on('change',function(e){
  		var parameters = {
  			'raw_material_category_id' :  $(this).val()
  		};
    	$.getJSON("<?=base_url()?>Cashier/GetRawMaterial", parameters)
      .done(function(data, textStatus, jqXHR) {
      	$("#AddRawMaterialInventory input[name=rm_brand]").attr('value',data.raw_material_brand);
        $("#AddRawMaterialInventory input[name=rm_category_id]").attr('value',data.raw_material_category_id);
        $("#AddRawMaterialInventory select[name=rm_unit]").val(data.raw_material_unit);	
    	})
    	.fail(function(jqXHR, textStatus, errorThrown) {
        console.log(errorThrown.toString());
   		});
    });

    $("#AddRawMaterialInventory").validate({
	  	errorElement: "div",
	    rules: {
	        "rmc_id" : {
            required : true
	        },
	        "rm_sku" : {
	        	required : true,
	        	digits : true
	        },
	        "rm_quantity":{
	        	required : true,
	        	digits : true
	        }
	    },
	    submitHandler: function(form) {
				var formData = $("#AddRawMaterialInventory").serialize(); 
				$.ajax({
	        url: "<?=base_url()?>Cashier/AddRawMaterialInventory",
	        data: formData,
	        type: "POST",
	        // crossDomain: true,
					cache: false,
	        // dataType : "json",
	    		success: function(data) {
            if(data.success == 'true'){
            	$("#ModalAddRawMaterial").modal('hide'); 
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

	  $("#AddOTCInventory").validate({
	  	errorElement: "div",
	    rules: {	       
	        "sku_count" : {
	        	required : true,
	        	digits : true
	        }

	    },
	    submitHandler: function(form) {
			// alert(document.getElementById('expiry').value);
				var formData = $("#AddOTCInventory").serialize(); 
				$.ajax({
	        url: "<?=base_url()?>Cashier/AddOTCInventory",
	        data: formData,
	        type: "POST",
	        // crossDomain: true,
					cache: false,
	        // dataType : "json",
	    		success: function(data) {
            if(data.success == 'true'){
            	$("#ModalAddOTCStock").modal('hide'); 
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

	});

	 //functionality for getting the dynamic input data
	 $("#SearchServiceByName").typeahead({
      autoselect: true,
      highlight: true,
      minLength: 1
     },
     {
      source: SearchServiceByName,
      templates: {
        empty: "No Services Found!",
        suggestion: _.template("<p class='service_search'><%- service_name %>(<%- qty_per_item %><%- service_unit %>),(<%- sub_category_name %>), (<%- category_name %>),Rs <%- service_price_inr %> </p>")
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
		var inventory = document.getElementById("otc_inventory_type").value;
      var parameters = {
        query : query,
		inventory_type : inventory
      };
      
      $.ajax({
        url: "<?=base_url()?>Cashier/GetProductData",
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


	$(document).on('click',"#SearchServiceButton",function(event){
    	event.preventDefault();
      	this.blur();
		//   var product = $(this).attr('service-id');
		//   alert(product);
		var parameters = {
        product : $(this).attr('service-name'),
		inventory_type : $(this).attr('otc_inventory_type')
		};	
		//   alert(parameters);
		$.ajax({
	        url: "<?=base_url()?>Cashier/GetProductDetails",
	        data: parameters,
	        type: "GET",
	        // crossDomain: true,
					cache: false,
	        // dataType : "json",
	    	success: function(data) {
            if(data.success == 'true'){
							document.getElementById("category").value = data.result[0]['category_name'];
							document.getElementById("sub_category").value = data.result[0]['sub_category_name'];
							document.getElementById("otc_item").value = data.result[0]['service_name'];
							document.getElementById("OTC-Category-Id").value = data.result[0]['category_id'];
							document.getElementById("barcode").value = data.result[0]['barcode'];
							document.getElementById("iproduct_name").value = data.result[0]['service_name'];
							document.getElementById("unit").value = data.result[0]['service_unit'];
							// document.getElementById("barcode_id").value = data.result[0]['barcode_id']; 
							for(var i=0; i < data.result.length;i++ ){
								$("#AddOTCInventory select[name=sku_size]").append("<option value=" + data.result[i]['service_id']+","+data.result[i]['qty_per_item'] + ">" + data.result[i]['qty_per_item'] + "</option>")
							}
            }
            else if (data.success == 'false'){                   
        	    $('#centeredModalDanger').modal('show').on('shown.bs.modal', function (e) {
								$("#ErrorModalMessage").html("").html(data.message);
							});
            }	
			}
		}); 
    });
	$(document).on('click',"#SearchItemButton",function(event){
    	event.preventDefault();
      	this.blur();
		  var product = document.getElementById("ServiceOTCId").value;
		  var inventory = document.getElementById("otc_inventory_type").value;
		//   alert(product);
		var parameters = {
        product : product,
		inventory_type : inventory
		};	
		//   alert(parameters);
		$.ajax({
				url: "<?=base_url()?>Cashier/GetProductDetail",
				data: parameters,
				type: "GET",
				crossDomain: true,
				cache: false,
				dataType : "json",
	    	success: function(data) {
					if(data.success == 'true'){
						document.getElementById("category").value = data.result[0]['category_name'];
						document.getElementById("sub_category").value = data.result[0]['sub_category_name'];
						document.getElementById("otc_item").value = data.result[0]['service_name'];
						document.getElementById("OTC-Category-Id").value = data.result[0]['category_id'];
						for(var i=0; i < data.result.length;i++ ){
							$("#AddOTCInventory select[name=sku_size]").append("<option value=" + data.result[i]['service_id']+","+data.result[i]['qty_per_item'] + ">" + data.result[i]['qty_per_item'] + "</option>")
						}
					}
					else if (data.success == 'false'){                   
						$('#centeredModalDanger').modal('show').on('shown.bs.modal', function (e) {
							$("#ErrorModalMessage").html("").html(data.message);
						});
					}	
				}
			}); 
    });
	// function GetServiceid(){
	// 	var a = document.getElementById("Service_SKU_Size").value;
	// 	alert(a);
	// }
	$("#otc_inventory_type").on('change',function(e){
    	var parameters = {
    		'inventory_type' :  $(this).val()
    	};
		// alert($(this).val());
    	$.getJSON("<?=base_url()?>Cashier/GetCategoriesByInventory", parameters)
      .done(function(data, textStatus, jqXHR) {
		//   alert(data);
      		var options = "<option value='' selected></option>"; 
       		for(var i=0;i<data.res_arr.length;i++){
       			options += "<option value="+data.res_arr[i].category_id+">"+data.res_arr[i].category_name+"</option>";
       		}
       		$("#OTC-Category-Id").html("").html(options);
    	})
    	.fail(function(jqXHR, textStatus, errorThrown) {
        console.log(errorThrown.toString());
   		});
    });
	$("#OTC-Category-Id").on('change',function(e){
    	var parameters = {
    		'category_id' :  $(this).val()
    	};
		// alert($(this).val());
    	$.getJSON("<?=base_url()?>Cashier/GetSubCategoriesByCatId", parameters)
      .done(function(data, textStatus, jqXHR) {
      		var options = "<option value='' selected></option>"; 
       		for(var i=0;i<data.length;i++){
       			options += "<option value="+data[i].sub_category_id+">"+data[i].sub_category_name+"</option>";
       		}
       		$("#OTC-Sub-Category-Id").html("").html(options);
    	})
    	.fail(function(jqXHR, textStatus, errorThrown) {
        console.log(errorThrown.toString());
   		});
    });

    $("#OTC-Sub-Category-Id").on('change',function(e){
		// alert("hii");
    	var type = document.getElementById('otc_inventory_type').value;
		var parameters = {
    		'sub_category_id' :  $(this).val(),
			'type'	: type
    	};
    	$.getJSON("<?=base_url()?>Cashier/GetServicesBySubCatId", parameters)
      .done(function(data, textStatus, jqXHR) {
      		var options = "<option value='' selected></option>"; 
       		for(var i=0;i<data.length;i++){
       			options += "<option value='"+data[i].service_name+"'>"+data[i].service_name+"</option>";
       		}
       		$("#ServiceOTCId").html("").html(options);
    	})
    	.fail(function(jqXHR, textStatus, errorThrown) {
        console.log(errorThrown.toString());
   		});
    });
</script>
<script type="text/javascript">
	<?php if($modal == 1) { ?>
			$(document).ready(function(){        
				$('#AddProduct').click();
			}); 
	<?php } ?>		
	$("input[name=\"expiry_date\"]").daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            autoUpdateInput: false,
            locale: {
                format: 'YYYY-MM'
            }
        });

        $('.date').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('YYYY-MM'));
        });	
</script>
<script type="text/javascript">
	$(document).on('click',"#download",function(event){		
		event.preventDefault();
		this.blur();			
			$.getJSON("<?=base_url()?>Cashier/InventoryDownload")	
			.done(function(data, textStatus, jqXHR) {					
				JSONToCSVConvertor(data.result," ", true);
			})
			.fail(function(jqXHR, textStatus, errorThrown) {
				console.log(errorThrown.toString());
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
  $(".smartwizard-arrows-primary").smartWizard({
			theme: "arrows",
			showStepURLhash: false
		});
    $("#Service_SKU_Size").on('change',function(e){
    	var parameters = {
    		'item_id' :  $(this).val()
    	};
		// alert($(this).val());
    	$.getJSON("<?=base_url()?>Cashier/GetProduct", parameters)
      .done(function(data, textStatus, jqXHR) {
          var mrp = parseInt(data.result[0]['service_price_inr'])+parseInt((data.result[0]['service_price_inr']*data.result[0]['service_gst_percentage'])/100);
        //   alert(mrp);
            document.getElementById("imrptemp").value = mrp;
            document.getElementById("imrp").value = mrp;
            document.getElementById("igsttemp").value = data.result[0]['service_gst_percentage'];
            document.getElementById("igst").value = data.result[0]['service_gst_percentage'];
            document.getElementById("ipricetemp").value = data.result[0]['service_price_inr'];
    	})
    	.fail(function(jqXHR, textStatus, errorThrown) {
        console.log(errorThrown.toString());
   		});
    });

	$("#ibase_cost").on('keyup',function(e){
    	
		// alert($(this).val());
        var sku=parseInt(document.getElementById("sku_count").value);
        // document.getElementById("ibase_cost").value = baseamount;
        var baseamount = sku * $(this).val();
        var totalamount = parseInt( baseamount ) + parseInt((baseamount * document.getElementById("igsttemp").value)/100);
        // alert(totalamount);
        document.getElementById("itotal_cost").value = totalamount;
        
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
