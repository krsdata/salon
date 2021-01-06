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
				<h1 class="h3 mb-3">Inventory Management</h1>
				<div class="row">
					<?php
						if(empty($business_outlet_details)){
					?>	
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
					<?php
						}

						if(!isset($selected_outlet)){
					?>
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
					<?php
						}
						else{
					?>
					<div class="col-md-12">
						<div class="card">
								<div class="card-header" style="margin-left:10px;">
									<ul class="nav nav-pills card-header-pills pull-right" role="tablist" style="font-weight: bolder">
										<li class="nav-item">
											<a class="nav-link active" data-toggle="tab" href="#tab-1">Add Stock</a>
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
										<li class="nav-item">
											<a class="nav-link" data-toggle="tab" href="#tab-7">Invoice Tracker</a>
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
																					<!-- <option value="warehouse">Warehouse</option> -->
																					<!-- <option value="branch">Branch</option> -->
																					<option value="vendor">Vendor</option>
																					<!-- <option value="return">Sales return</option> -->
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
																							<input type="text" class="form-control product_barcode" name="product_barcode[]" readonly >
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
																							<label>MRP</label>
																							<input type="text" class="form-control mrp" name="product_mrp[]" required readonly>
																						</div>
																					</td>
																					<td>
																						<div class="form-group">
																							<label>Qty</label>
																							<input type="text" class="form-control product_qty" name="product_qty[]" required>
																						</div>
																					</td>
																					<td>
																						<div class="form-group">
																							<label>Cost/Unit(<small>before tax</small>)</label>
																							<input type="text" class="form-control product_price" name="product_price[]" required>
																						</div>
																					</td>
																					<td>
																						<div class="form-group">
																							<label>GST %</label>
																							<input type="text" class="form-control gst" name="product_gst[]" required readonly>
																						</div>
																					</td>
																					
																					<td>
																						<div class="form-group">
																							<label>Total Cost</label>
																							<input type="text" class="form-control total_cost" name="total_cost[]" required readonly>
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
																				<input type="number" name="amount_paid" class="form-control" min="0" placeholder="Amount Paid">
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
																				<input type="text" class="form-control" name="payment_status" placeholder="Payment Status" readonly>
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
																					<!-- <option value="return">Sales return</option> -->
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
																							<label>MRP</label>
																							<input type="text" class="form-control mrp" name="product_mrp[]" required readonly>
																						</div>
																					</td>
																					<td>
																						<div class="form-group">
																							<label>Qty</label>
																							<input type="text" class="form-control product_qty" name="product_qty[]" required>
																						</div>
																					</td>
																					<td>
																						<div class="form-group">
																							<label>Cost/Unit(<small>before tax</small>)</label>
																							<input type="text" class="form-control product_price" name="product_price[]" required>
																						</div>
																					</td>
																					<td>
																						<div class="form-group">
																							<label>GST %</label>
																							<input type="text" class="form-control gst" name="product_gst[]" required readonly>
																						</div>
																					</td>
																					
																					<td>
																						<div class="form-group">
																							<label>Total Cost</label>
																							<input type="text" class="form-control total_cost" name="total_cost[]" required readonly>
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
											<!-- nested tab -->
											<div class="col-md-12">
												<div class="card">
													<div class="card-header" style="margin-left:10px;">
														<ul class="nav nav-pills card-header-pills pull-right" role="tablist" style="font-weight: bolder">
															<li class="nav-item">
																<a class="nav-link active" data-toggle="tab" href="#tab1-1">Stock(<small>Itemwise</small>)</a>
															</li>
															<li class="nav-item">
																<a class="nav-link" data-toggle="tab" href="#tab1-2">Stock</a>
															</li>
														</ul>
													</div>
													<div class="card-body">
														<div class="tab-content">
															<div class="tab-pane show active" id="tab1-1" role="tabpanel">
																<div class="card">
																	<div class="card-header">													
																		<div class="row">
																			<div class="col-md-2">
																				<h3>Total Stock</h3>
																			</div>
																			<div class="col-md-8">
																				<form class="form-inline" >
																					<select class="form-control" id="exp_date">
																						<option value="" selected="selected" disabled>Select Expiry Range</option>
																						<option value="less_than_three">Expiring in 3 Months</option>
																						<option value="three_to_six">Expiring in 3-6 Months</option>
																						<option value="more_than_six">Expiring in >6 Months</option>
																					</select>
																				</form>
																			</div>
																			<div class="col-md-2">
																			<button class="btn btn-primary" onclick="exportTableToExcel('availableStock','Product Stock')"><i class="fa fa-file-export"></i>Download</button>
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
																				<th>Stock in Volume</th>
																				<th>Last Updated</th>
																				<th>Location</th>
																			</thead>
																			<tbody>
																				<?php $count=1; foreach($itemwise_stock as $stock){ ?>
																					<tr>
																						<td style="width:5%;"><?=$count?></td>
																						<td style="width:15%;"><?=$stock['service_name'];?></td>
																						<td style="width:8%;"><?=$stock['inventory_type'];?></td>
																						<td style="width:8%;"><?=$stock['barcode'];?></td>
																						<td style="width:8%;"><?=$stock['qty_per_item'].' '.$stock['service_unit'];?></td>
																						<td style="width:8%;"><?php if(empty($stock['total_stock']) || $stock['total_stock']==""){echo 0;}else{echo $stock['total_stock'];}?></td>
																						<td style="width:8%;"><?php if(empty($stock['stock_in_unit']) || $stock['stock_in_unit']==""){echo "0"." ".$stock['service_unit'];}else{echo $stock['stock_in_unit']." ".$stock['service_unit'];} ?></td>
																						<td style="width:8%;"><?=$stock['updated_on'];?></td>
																						<td style="width:8%;"><?=$stock['business_outlet_name'];?></td>
																					</tr>
																				<?php $count++; }?>
																			</tbody>
																		</table>
																	</div>
																</div>
															</div>
															<div class="tab-pane" id="tab1-2" role="tabpanel">
																<div class="card">
																	<div class="card-header">													
																		<div class="row">
																			<div class="col-md-10">
																				<h3>Available Stock</h3>
																			</div>
																			<div class="col-md-2">
																			<button class="btn btn-primary" onclick="exportTableToExcel('availableStock','Product Stock')"><i class="fa fa-file-export"></i>Download</button>
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
																				<th>Stock in Volume</th>
																				<th>Last Updated</th>
																				<th>Expiry Date</th>
																				<th>Location</th>
																				<th>Action</th>
																			</thead>
																			<tbody>
																				<?php $count=1; foreach($total_stock as $stock){ ?>
																					<tr>
																						<td style="width:5%;"><?=$count?></td>
																						<td style="width:15%;"><?=$stock['service_name'];?></td>
																						<td style="width:8%;"><?=$stock['inventory_type'];?></td>
																						<td style="width:8%;"><?=$stock['barcode'];?></td>
																						<td style="width:8%;"><?=$stock['qty_per_item'].' '.$stock['service_unit'];?></td>
																						<td style="width:8%;"><?php if(empty($stock['total_stock']) || $stock['total_stock']==""){echo 0;}else{echo $stock['total_stock'];}?></td>
																						<td style="width:8%;"><?php if(empty($stock['stock_in_unit']) || $stock['stock_in_unit']==""){echo "0"." ".$stock['service_unit'];}else{echo $stock['stock_in_unit']." ".$stock['service_unit'];} ?></td>
																						<td style="width:8%;"><?=$stock['updated_on'];?></td>
																						<td style="width:8%;color:red;"><?=$stock['expiry_date'];?></td>
																						<td style="width:8%;"><?=$stock['business_outlet_name'];?></td>
																						<td style="width:8%;">
																							<button class="btn btn-primary EditInventory" data-toggle="modal" id="" data-target="#ModalEditInventory" service_id="<?=$stock['service_id'];?>" product_qty="<?php if(empty($stock['total_stock']) || $stock['total_stock']==""){echo 0;}else{echo $stock['total_stock'];}?>"><i class="fa fa-pen"></i></button>
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
											<!-- end -->										
										</div>										
										<div class="tab-pane" id="tab-4" role="tabpanel">
											<div class="row">
												<div class="col-md-12">
													<div class="card">
														<div class="card-header">
															<h4>Incoming Stock</h4>
														</div>
														<div class="card-body">
															<table class="table table-hover datatables-basic" style="width: 100%;">
																<thead>
																	<th>Sr. No.</th>
																	<th>Txn Id</th>
																	<th>Product Name</th>
																	<th>Type</th>
																	<th>Barcode</th>
																	<th>SKU size</th>
																	<th>Product Qty</th>
																	<th>MRP</th>
																	<th>Invoice Date</th>
																	<th>Source Name</th>
																	<th>Destination Name</th>
																	<th>Actions</th>
																</thead>
																<tbody>
																	<?php $count=1; foreach($stock_incoming as $incoming){ ?>
																		<tr>
																	<td><?=$count?></td>
																	<td><?=$incoming['inventory_transfer_data_id'];?></td>
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
											<div class="row">
												<div class="col-md-12">
													<div class="card">
														<div class="card-header">
															<div class="row">
																	<div class="col-md-2">
																		<h5 class="card-title">Inventory Details</h5>
																	</div>
																	<div class="col-md-5">
																		<form class="form-inline" style="width:100%;" method="POST" action="#" id="txn200">
																			<label>Select Invoice Range</label>
																			<div class="form-group col-md-6">
																				<input type="text" class="form-control" name="daterange" value="<?=date('Y-m-d');?>" >
																			</div>
																			<div class="form-group col-md-2">
																				<input type="submit" class="btn btn-primary" id="get_txn"  value="Submit" />
																			</div>
																		</form>
																	</div>
																	<div class="col-md-3">
																		<form class="form-inline" style="width:100%;">
																		<label>Inventory Health</label>
																			<div class="form-group col-md-3">
																			<select id="inventory_status" name="status" class="form-control">
																				<option selected="selected" disabled>Select Status</option>
																				<option value="regular">Regular Stock</option>																
																				<option value="slow">Slow Moving</option>
																				<option value="dead">Dead Stock</option>
																			</select>
																			</div>
																		</form>
																	</div>
																	<div class="col-md-2">
																	<button class="btn btn-primary" onclick="exportTableToExcel('inv_table','Inventory')"><i class="fa fa-download"></i> Download</button>
																	</div>
																</div>
														</div>
														<div class="card-body">
														<table class="table table-hover datatables-basic" id="inv_table" style="width: 100%;">
																<thead>
																	<th>Sr. No.</th>
																	<th>Invoice Number</th>
																	<th>Invoice Date</th>
																	<th>Product Name</th>
																	<th>Type</th>
																	<th>Barcode</th>
																	<th>SKU size</th>
																	<th>Product Qty</th>
																	<th>MRP</th>
																	<th>Vendor Name</th>
																</thead>
																<tbody>
																	<?php $count=1; foreach($inventory_details as $inventory){ ?>
																		<tr>
																	<td><?=$count?></td>
																	<td><a class="showInventory" inventory_id="<?=$inventory['inventory_id']?>" style="color:blue;" data-toggle="modal" data-target="#ModalShowInvoiceDetails"><?=$inventory['invoice_number'];?></a></td>
																	<td><?=$inventory['invoice_date'];?></td>
																	<td><?=$inventory['product_name'];?></td>
																	<td><?=$inventory['product_type'];?></td>
																	<td><?=$inventory['product_barcode'];?></td>
																	<td><?=$inventory['sku_size'].' '.$stock['service_unit'];?></td>
																	<td><?=$inventory['product_qty'];?></td>
																	<td><?=$inventory['product_mrp'];?></td>
																	<td><?=$inventory['vendor_name'];?></td>
																	</tr>
																	<?php $count++; }?>
																</tbody>
															</table>

														</div>
													</div>
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
															<th>Txn id.</th>
															<th>Product Name</th>
															<th>Type</th>
															<th>Barcode</th>
															<th>SKU size</th>
															<th>Product Qty</th>
															<th>MRP</th>
															<th>Transfer Date</th>
															<th>Destination</th>
															<th>Status</th>
														</thead>
														<tbody>
															<?php $count=1; foreach($stock_outgoing as $outgoing){ ?>
																<tr>
															<td><?=$count?></td>
															<td><?=$outgoing['inventory_transfer_id'];?></td>
															<td><?=$outgoing['product_name'];?></td>
															<td><?=$outgoing['product_type'];?></td>
															<td><?=$outgoing['product_barcode'];?></td>
															<td><?=$outgoing['sku_size'].' '.$stock['service_unit'];?></td>
															<td><?=$outgoing['product_qty'];?></td>
															<td><?=$outgoing['product_mrp'];?></td>
															<td><?=$outgoing['invoice_date'];?></td>
															<td><?=$outgoing['business_outlet_name'];?></td>
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
										<div class="tab-pane" id="tab-6" role="tabpanel">
											<div class="card">
												<div class="card-header">
													<h5 class="card-title">Inventory Health</h5>
												</div>
												<div class="card-body">
													<div class="row">
														<div class="col-md-3">
															<h5>Stock Value (MRP)</h5>
															<label class="btn btn-danger" id="labeltotal" style="width:130px">Rs <?=$stockvalue?></label>
														</div>
														<div class="col-md-3">
															<h5>Stock Status</h5>
															<select id="status" name="status" class="form-control">
																<option value="Regular">Regular Stock</option>
																<option value="Dead">Dead Stock</option>
																<option value="slow">Slow Moving</option>
															</select>
														</div>
													</div>
													<div class="row">
															<div class="col-md-12">
																	<table id="details" class="table table-striped" style="text-align:center">
																			<thead>
																					<th>Bucket</th>
																					<th>Item Name</th>
																					<th>Sub Category</th>
																					<th>Category</th>
																					<th>SKU Size</th>
																					<th>Current Stock</th>
																					<th>Stock In Regular-Stock Stage</th>
																					<th>Entry Date</th>
																					<th>No of Days since entry Date</th>
																					<th>Total Revenue Stuck(Rs)</th>
																			</thead>
																				<?php
																					foreach($stockdetails as $key=> $value){
																						?>
																						<tr>
																							<td>Regular Stock</td>
																							<td><?=$value['service_name']?></td>
																							<td><?=$value['sub_category_name']?></td>
																							<td><?=$value['category_name']?></td>
																							<td><?=$value['sku_size']?></td>
																							<td><?=$value['sku_count']?></td>
																							<td><?=$value['deadstock']?></td>
																							<td><?=$value['entrydate']?></td>
																							<td><?=$value['days']?></td>
																							<td><?=$value['Total']?></td>
																						</tr>
																						<?php
																					}
																				?>
																	</table>
															</div>
													</div>
												</div>
											</div>
										</div>
										<div class="tab-pane" id="tab-7" role="tabpanel">
											<div class="card">
												<div class="card-header">
													<h5 class="card-title">Inventory History</h5>
												</div>
												<div class="card-body">
													<div class="row">
															<div class="col-md-12">
															<table class="table table-hover table-striped datatables-basic" id="adminExpense" style="width: 100%;text-align:center">
                                            <thead>
                                                <tr class="text-primary">
																										<th>Sr. No.</th>
																										<th>Invoice No.</th>
																										<th>Date</th>	
																										<th>Vendor Name</th>
																										<th>Invoice Amount</th>																										
																										<th>Amount Paid</th>
																										<th>Pending Amount</th>																								
																										<th>Updated By</th>
                                                    <th>Payment Status</th>
                                                    <th>Tender</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $i=0;
																								foreach ($all_expenses as $expense):
																								?>
                                                <tr>
																										<td><?=$i=$i+1;?></td>																										
																										<td><?=$expense['invoice_number']?></td>
																										<td><?=$expense['expense_date']?></td>
																										<td><?=$expense['vendor_name']?></td>
																										<td><?=$expense['total_amount']?></td>																										 
																										<td><?=$expense['amount']?></td>
																										<td><?=$expense['pending_amount']?></td>
																										<td><?=$expense['employee_name']?></td>
																										<td><?=$expense['expense_status']?></td>                                                                                        
                                                    <td><?=$expense['payment_mode']?></td>
                                                    
                                                </tr>
                                                <?php	                    	
																								endforeach;
																								?>
                                            </tbody>
                                        </table>
															</div>
													</div>
												</div>
											</div>
											<div class="card">
												<div class="card-header">
													<h5 class="card-title">Pending Payments</h5>
												</div>
												<div class="card-body">
													<div class="row">
														<div class="col-md-12">
															<table class="table table-hover table-striped datatables-basic" id="PendingPayment" style="width: 100%;">
																	<thead>
																			<tr class="text-primary">
																				<th>Sr. No.</th>
																				<!-- <th>Exp Id</th> -->
																				<th>Invoice No.</th>
																				<th>Date</th>	
																				<th>Vendor Name</th>
																				<th>Invoice Amount</th>																				
																				<th>Amount Paid</th>
																				<th>Pending Amount</th>																								
																				<th>Updated By</th>
																				<th>Payment Status</th>
																				<th>Tender</th>																					
																					<th>Action</th>
																			</tr>
																	</thead>
																	<tbody>
																			<?php
																			$i=0;
																				foreach ($pending_payment as $pending):
																				?>
																			<tr>
																				<td><?=$i=$i+1;?></td>																										
																				<!-- <td><?=$pending['expense_unique_serial_id']?></td> -->
																				<td><?=$pending['invoice_number']?></td>
																				<td><?=$pending['expense_date']?></td>
																				<td><?=$pending['payment_to_name']?></td> 
																				<td><?=$pending['total_amount']?></td>																				
																				<td><?=$pending['amount']?></td>
																				<td><?=$pending['pending_amount']?></td>
																				<td><?=$pending['employee_name']?></td>
																				<td><?=$pending['expense_status']?></td>                                                                                        
																				<td><?=$pending['payment_mode']?></td>																					
																					<td>
																							<button type="button"
																									class="btn btn-primary pending-expense-edit-btn"
																									expense_id="<?=$pending['expense_id']?>"
																									expense_date="<?=$pending['expense_date']?>"
																									expense_name="<?=$pending['item_name']?>"
																									expense_type="<?=$pending['expense_type']?>"
																									employee_name="<?=$pending['employee_name']?>"
																									payment_type="<?=$pending['payment_type']?>"
																									payment_to="<?=$pending['payment_to']?>"
																									payment_to_name="<?=$pending['payment_to_name']?>"
																									total_amount="<?=$pending['total_amount']?>"
																									pending_amount="<?=$pending['pending_amount']?>"
																									amount="<?=$pending['amount']?>"
																									payment_mode="<?=$pending['payment_mode']?>"
																									expense_status="<?=$pending['expense_status']?>"
																									invoice_number="<?=$pending['invoice_number']?>"
																									expense_date="<?=$pending['expense_date']?>"
																									expense_type_id="<?=$pending['expense_type_id']?>"
																									remark="<?=$pending['remarks']?>">
																									<i class="align-middle" data-feather="edit-2"></i>
																							</button>
																					</td>
																			</tr>
																			<?php	                    	
																				endforeach;
																			?>
																	</tbody>
															</table>
														</div>
													</div>
												</div>
										</div>									
									</div>
								</div>
								<!-- modal -->
								<div class="modal fade" id="ModalEditInventory" tabindex="-1" role="dialog" aria-hidden="true">
									<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
										<div class="modal-content">
											<div class="modal-header">
												<h5 class="modal-title text-white">Edit Inventory</h5>
												<button type="button" class="close" data-dismiss="modal" aria-label="Close">
													<span aria-hidden="true">×</span>
												</button>
											</div>
											<div class="modal-body">
												<form id="EditInventory" method="POST" action="#">
													<div class="form-row">
														<div class="form-group col-md-4">
															<label>Product Qty</label>
															<input type="number" class="form-control" placeholder="Quantity"  name="product_qty" required>
														</div>														
														<div class="form-group col-md-4">
															<label>Date</label>
															<input type="date" class="form-control"  value="<?=date('Y-m-d');?>" name="invoice_date">
														</div>
														<div class="form-group col-md-4">
															<label>Invoice Number</label>
															<input type="text" class="form-control" placeholder="Invoice Number" name="invoice_number">
														</div>														
													</div>
													<div class="form-row">
													<div class="form-group col-md-4">
															<label>Product Name</label>
															<input type="text" class="form-control" placeholder="Product Name" name="product_name" readonly>
														</div>
														<div class="form-group col-md-4">
															<label>Product Brand</label>
															<input type="text" class="form-control" placeholder="Product Brand" name="product_brand" readonly>
														</div>													
														<div class="form-group col-md-4">
															<label>SKU Size</label>
															<input type="number" class="form-control" placeholder="SKU SIZE" name="sku_size" readonly>
														</div>														
													</div>
													<div class="form-row">	
														<div class="form-group col-md-4">
															<label>Cost/Unit(<small>before tax</small>)</label>
															<input type="number" class="form-control" name="product_price" placeholder="Cost/Unit">
														</div>
														<div class="form-group col-md-4">
														<label class="form-label">Product gst</label>
															<input type="number" class="form-control" name="product_gst" placeholder="GST">
														</div>
														<div class="form-group col-md-4">
														<label class="form-label">Product MRP</label>
															<input type="number" class="form-control" name="product_mrp" placeholder="Product MRP">
														</div>
													</div>
													<div class="row">
													<div class="form-group col-md-4">
														<label class="form-label">Product Expiry</label>
															<input type="date" class="form-control" name="product_expiry_date" required>
														</div>
														<div class="form-group col-md-8">
															<label class="form-labl">Remarks</label>
															<textarea class="form-control" name="remarks" placeholder="Enter Remarks" ></textarea> 
														</div>
													</div>
													<div class="form-row">
														<div class="form-group col-md-12">
															<input type="hidden" name="service_id">
															<button type="submit" class="btn btn-primary">Submit</button>
														</div>
													</div>
												</form>
												<div class="alert alert-dismissible feedback" role="alert" style="margin:0px;">
													<button type="button" class="close" data-dismiss="alert" aria-label="Close">
													<span aria-hidden="true">&times;</span>
													</button>
													<div class="alert-message">
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="modal fade" id="ModalShowInvoiceDetails" tabindex="-1" role="dialog" aria-hidden="true">
									<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
										<div class="modal-content">
											<div class="modal-header">
												<h5 class="modal-title text-white">Inventory Details</h5>
												<button type="button" class="close" data-dismiss="modal" aria-label="Close">
													<span aria-hidden="true">×</span>
												</button>
											</div>
											<div class="modal-body">
												<table class="table table-hover" width="100%" >
													<thead>
														<th>Invoice Number</th>
														<th>Invoice Date</th>
														<th>Product Name</th>
														<th>Product Qty</th>
														<th>Product Price</th>
														<th>Product Type</th>
														<th>Vendor Name</th>
													</thead>
													<tbody id="invoice_Details">
													</tbody>
												</table>
											</div>
										</div>
									</div>
								</div>
								<!-- update expense -->
								<div class="modal fade" id="UpdatePendingExpense" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Update Expense</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body m-3">
                                    <form id="UpdateDailyExpenses" method="POST" action="#">
                                        <div class="row">
                                            <div class="form-group col-md-3">
                                                <label>Entry Date</label>
                                                <input type="date"  name="entry_date" style="width:100%" class="form-control" readonly>
                                            </div>
                                            <div class="form-group col-md-3">
                                                <label>Expense Name</label>
                                                <input type="text" class="form-control" name="item_name" readonly>
                                            </div>
                                            <div class="form-group col-md-3">
                                                <label>Expense Type</label>
                                                <input type="text" class="form-control" name="expense_type" readonly>
                                            </div>
                                            <div class="form-group col-md-3">
                                                <label>Employee Name</label>
                                                <input type="text" class="form-control" name="employee_name" readonly>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-md-3">
                                                <label>Paid To</label>
                                                <input name="payment_to_name" class="form-control" readonly>
                                            </div>
                                            <div class="form-group col-md-3">
                                                <label>Pending Amount</label>
                                                <input type="number" class="form-control" min="0" name="pending_amount" id="pendamtValue"
                                                    readonly>
                                            </div>
                                            <div class="form-group col-md-3">
                                                <label>Amount</label>
                                                <input type="number" class="form-control" min="0" name="amount1" id="reamt" onkeyup="calPendAmt()">
                                            </div>                                         
                                            <div class="form-group col-md-3">
                                                <label>Remaing Amount</label>
                                                <input type="number" class="form-control" name="remaining_amt"  id="remaining_amt" readonly>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-md-3">
                                                <label>Payment Mode</label>
                                                <select class="form-control" name="payment_mode">
                                                    <option value="cash" selected>Cash</option>
                                                    <option value="card">Card</option>
                                                    <option value="cank">Cheque/Bank</option>
                                                    <option value="wallet">Wallet/ Others</option>
                                                    <select>
                                            </div>
                                            <div class="form-group col-md-3">
                                                <label>Payment Status</label>
                                                <select class="form-control" name="expense_status" id="select_expense_status">
                                                    <option disabled selected>Select</option>
                                                    <option value="Paid">Paid</option>
                                                    <!-- <option value="Advance">Advance</option> -->
                                                    <option value="Partialy Paid">Partialy Paid</option>
                                                    <select>
                                            </div>
                                            
                                            <div class="form-group col-md-3">
                                                <label>Remarks</label>
                                                <input type="text" class="form-control" name="remarks" readonly>
                                            </div>
                                            <div class="form-group col-md-3">
                                                <label>Invoice (If any)</label>
                                                <input type="text" name="invoice_number" class="form-control">
                                            </div>
                                            <div class="form-group col-md-3">
                                                <input type="text" name="expense_id" class="form-control" hidden>
                                                <input type="text" name="expense_type_id" class="form-control" hidden>
                                                <input type="text" name="item_name" class="form-control" hidden>
                                                <input type="number" name="amount" hidden>
                                                <input type="number" name="total_amount" hidden>
                                                <input type="text" name="payment_type" hidden>
                                                <input type="text" name="payment_to" hidden>
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                    </form>
                                </div>
                                <div class="modal-footer">
                                    <!-- <button type="button" class="btn btn-success" data-dismiss="modal">Submit</button> -->
                                </div>
                            </div>
                        </div>
                    </div>
								<!-- end -->
							</div>	
						</div>
					<?php
						}
					?>
				</div>
			</div>
		</main>
<?php
	$this->load->view('business_admin/ba_footer_view');
?>
<script type="text/javascript">

			function calPendAmt() {
            var t1 = parseInt(document.getElementById("reamt").value);
            var t2 = parseInt(document.getElementById("pendamtValue").value);
            
            if(t1 <= t2){
                var t3 = t2 - t1;
               document.getElementById("remaining_amt").value = t3;
            }else{
                alert("Amount is Greater than Pending Amount");
                document.getElementById("reamt").value=0;
                document.getElementById("remaining_amt").value = t2;
                document.getElementById("reamt").autofocus;
                
            }
            if(t3 == 0){
                document.getElementById("select_expense_status").options[1].disabled = false;
                document.getElementById("select_expense_status").options[1].selected = true; 
            }
            else{
                document.getElementById("select_expense_status").options[2].selected = true;
                document.getElementById("select_expense_status").options[1].disabled = true;
            }
        }

	$(".date").daterangepicker({
		singleDatePicker: true,
		showDropdowns: true,
		autoUpdateInput : false,
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
	$('.date').on('apply.daterangepicker', function(ev, picker) {
    $(this).val(picker.startDate.format('YYYY-MM-DD'));
  });
</script>
<script type="text/javascript">
	$(document).ready(function(){		
		$(".datatables-basic").DataTable({
			responsive: true
		});

		// Initialize Select2 select box
		$("#RawMaterialCategoryId").select2({
			allowClear: true,
			placeholder: "Select...",
		}).change(function() {
			$(this).valid();
		});

		$("#AddProduct select[name=source_type]").on('change',function(e){
  		var parameters = {
  			'source_type' :  $(this).val()
			};
			// alert($(this).val());
    	$.getJSON("<?=base_url()?>BusinessAdmin/GetBranchAndVendor", parameters)
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
    	$.getJSON("<?=base_url()?>BusinessAdmin/GetBranchAndVendor", parameters)
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
			
			$("#addProductTable tr:last").after("<tr><td>"+rowno+"</td><td><div class=\"form-group\"><input type=\"text\" class=\"form-control searchProductByName\" name=\"product_name[]\" readonly><input type=\"hidden\" class=\"product_id\" name=\"product_id[]\"></div></td><td><div class=\"form-group\"><input type=\"text\" class=\"form-control product_type\" name=\"product_type[]\" readonly></div></td><td><div class=\"form-group\"><input type=\"text\" class=\"form-control product_barcode\" name=\"product_barcode[]\" readonly></div></td><td><div class=\"form-group\" ><input type=\"text\" class=\"form-control sku_size\" name=\"sku_size[]\" readonly></div></td><td><div class=\"form-group\"><input type=\"number\" class=\"form-control mrp\" name=\"product_mrp[]\" readonly></div></td><td><div class=\"form-group\"><input type=\"number\" class=\"form-control product_qty\" name=\"product_qty[]\"></div></td><td><div class=\"form-group\"><input type=\"number\" class=\"form-control product_price\" name=\"product_price[]\"></div></td><td><div class=\"form-group\"><input type=\"number\" class=\"form-control gst\" name=\"product_gst[]\" readonly></div></td><td><div class=\"form-group\"><input type=\"number\" class=\"form-control total_cost\" name=\"total_cost[]\" readonly></div></td><td><div class=\"form-group\"><input type=\"date\" class=\"form-control\" value=\"<?=date('Y-m-d',strtotime('+ 1 year', strtotime(date('Y-m-d'))));?>\" name=\"product_exp_date[]\" ></div></td></tr>");
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
		var service_name="",product_id="", service_type="", service_barcode="", sku_size="", mrp="",gst="";

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
			gst=suggestion.service_gst_percentage;

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
        url: "<?=base_url()?>BusinessAdmin/GetProductData",
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
					$("#AddProduct input[name=payment_status]").attr('value',"Paid");
				}else if(amt_paid > 0 && amt_paid < invoice_amount){
					$("#AddProduct input[name=payment_status]").attr('value',"Partialy Paid");
				}else if(amt_paid == 0){
					$("#AddProduct input[name=payment_status]").attr('value',"Unpaid");
				}else{
					alert("Amount Paid is much than Invoice Amount.");
					$("#AddProduct input[name=payment_status]").attr('value',"Over Paid");
				}	
    });

		// $("#addProductTable tr:last .gst").on('input',function(e){			
		$('body').on('input','#addProductTable tr:last .product_price',function(e){	
  			var product_qty=  Number($("#addProductTable tr:last .product_qty").val());
				var product_price=  Number($("#addProductTable tr:last .product_price").val());
				var product_gst=  Number($("#addProductTable tr:last .gst").val());
				var mrp = Number((product_price+(product_price*product_gst*.01))* product_qty);
				$("#addProductTable tr:last .total_cost").val(mrp);				
								
    });
		// $("#transProductTable tr:last .gst").on('input',function(e){		
			$('body').on('input','#transProductTable tr:last .product_price',function(e){		
  			var product_qty=  Number($("#transProductTable tr:last .product_qty").val());
				var product_price=  Number($("#transProductTable tr:last .product_price").val());
				var product_gst=  Number($("#transProductTable tr:last .gst").val());
				var mrp = Number((product_price+(product_price*product_gst*.01))* product_qty);
				$("#transProductTable tr:last .total_cost").val(mrp);
								
    });

		$('body').on('blur','#addProductTable tr:last .product_price',function(e){		
			var total_len=$("#AddProduct input[name^=total_cost]").length;
				var array= $("#AddProduct input[name^=total_cost]");
				var t_cost=0;
				for(var i=0;i< total_len;i++){
					t_cost+=Number(array[i].value);
				}
				// alert(t_cost);
				$("#AddProduct input[name=amount_paid]").val(t_cost);

				// var amt_paid=  Number($(this).val());
				var invoice_amount= Number($("#AddProduct input[name=invoice_amount]").val());
				if(t_cost==invoice_amount){
					$("#AddProduct input[name=payment_status]").attr('value',"Paid");
				}else if(t_cost > 0 && t_cost < invoice_amount){
					$("#AddProduct input[name=payment_status]").attr('value',"Partialy Paid");
				}else if(t_cost == 0){
					$("#AddProduct input[name=payment_status]").attr('value',"Unpaid");
				}else{
					alert("Amount Paid is much than Invoice Amount.");
					$("#AddProduct input[name=payment_status]").attr('value',"Over Paid");
				}	
								
    });

		$('body').on('blur','#transProductTable tr:last .product_price',function(e){		
			var total_len=$("#TransProduct input[name^=total_cost]").length;
				var array= $("#TransProduct input[name^=total_cost]");
				var t_cost=0;
				for(var i=0;i< total_len;i++){
					t_cost+=Number(array[i].value);
				}
				// alert(t_cost);
				$("#TransProduct input[name=amount_paid]").val(t_cost);
				
								
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
				var total_len=$("#AddProduct input[name^=total_cost]").length;
				var inv_amt= $("#AddProduct input[name=invoice_amount]").val();
				var array= $("#AddProduct input[name^=total_cost]");
				var t_cost=0;
				for(var i=0;i< total_len;i++){
					t_cost+=Number(array[i].value);
				}
				if(t_cost < inv_amt){
					let result = confirm('Total Cost is less than Invoice Amount. Do you want to Continue ?');
					if(result){
						alert("Transaction will be Processed");
					}else{
						alert("Transaction Canceled");
						return false;
					}
				}else if(t_cost > inv_amt){
					let result = confirm('Total Cost is More than Invoice Amount. Do you want to Continue ?');					
					if(result){
						alert("Transaction will be Processed");
					}else{
						alert("Transaction Canceled");
						return false;
					}			
				}				
				var formData = $("#AddProduct").serialize(); 
				$.ajax({
	        url: "<?=base_url()?>BusinessAdmin/AddInventory",
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
				
				$("#transProductTable tr:last").after("<tr><td>"+rowno+"</td><td><div class=\"form-group\"><input type=\"text\" class=\"form-control searchProductByName\" name=\"product_name[]\" readonly><input type=\"hidden\" class=\"product_id\" name=\"product_id[]\"></div></td><td><div class=\"form-group\"><input type=\"text\" class=\"form-control product_type\" name=\"product_type[]\" readonly></div></td><td><div class=\"form-group\"><input type=\"text\" class=\"form-control product_barcode\" name=\"product_barcode[]\" readonly></div></td><td><div class=\"form-group\" ><input type=\"text\" class=\"form-control sku_size\" name=\"sku_size[]\" readonly></div></td><td><div class=\"form-group\"><input type=\"number\" class=\"form-control mrp\" name=\"product_mrp[]\" readonly></div></td><td><div class=\"form-group\"><input type=\"number\" class=\"form-control product_qty\" name=\"product_qty[]\"></div></td><td><div class=\"form-group\"><input type=\"number\" class=\"form-control product_price\" name=\"product_price[]\"></div></td><td><div class=\"form-group\"><input type=\"number\" class=\"form-control gst\" name=\"product_gst[]\" readonly></div></td><td><div class=\"form-group\"><input type=\"number\" class=\"form-control total_cost\" name=\"total_cost[]\" readonly></div></td><td><div class=\"form-group\"><input type=\"date\" class=\"form-control\" value=\"<?=date('Y-m-d',strtotime('+ 1 year', strtotime(date('Y-m-d'))));?>\" name=\"product_exp_date[]\" ></div></td></tr>");
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
		var service_name="",product_id="", service_type="", service_barcode="", sku_size="", mrp="",gst="";

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
        url: "<?=base_url()?>BusinessAdmin/GetProductData",
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
					$("#TransProduct input[name=payment_status]").attr('value',"Paid");
				}else if(amt_paid > 0 && amt_paid < invoice_amount){
					$("#TransProduct input[name=payment_status]").attr('value',"Partialy Paid");
				}else if(amt_paid == 0){
					$("#TransProduct input[name=payment_status]").attr('value',"Unpaid");
				}else{
					alert("Amount Paid is much than Invoice Amount.");
					$("#TransProduct input[name=payment_status]").attr('value',"Over Paid");
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
	        url: "<?=base_url()?>BusinessAdmin/TransferInventory",
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
				url: "<?=base_url()?>BusinessAdmin/TransferFinalInventory",
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
				url: "<?=base_url()?>BusinessAdmin/RejectTransferInventory",
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


		$(document).on('click','.EditInventory',function(event) {
      event.preventDefault();
      this.blur(); // Manually remove focus from clicked link.
      var parameters = {
            service_id : $(this).attr('service_id')
      };
			var product_qty=$(this).attr('product_qty');
      $.getJSON("<?=base_url()?>BusinessAdmin/EditInventory", parameters)
        .done(function(data, textStatus, jqXHR) {            
					
					mrp=(Number(data.message.service_price_inr)+Number(data.message.service_price_inr)*Number(.18));
            $("#ModalEditInventory input[name=product_name]").attr('value',data.message.service_name);
            $("#ModalEditInventory input[name=product_gst]").attr('value',data.message.service_gst_percentage);
            $("#ModalEditInventory input[name=product_brand]").attr('value',data.message.service_brand);
            $("#ModalEditInventory input[name=sku_size]").attr('value',data.message.qty_per_item);
            $("#ModalEditInventory input[name=product_price]").attr('value',data.message.service_price_inr);
            $("#ModalEditInventory input[name=service_id]").attr('value',data.message.service_id);
						$("#ModalEditInventory input[name=product_qty]").attr('value',product_qty);
						$("#ModalEditInventory input[name=product_mrp]").attr('value',mrp);
            $("#ModalEditInventory").modal('show');
        })
        .fail(function(jqXHR, textStatus, errorThrown) {
            console.log(errorThrown.toString());
        });
    	});


		$("#EditInventory").validate({
	  	errorElement: "div",
	    rules: {
	        "product_name" : {
            required : true
	        },
					"product_qty" : {
            required : true
	        }    
	    },
	    submitHandler: function(form) {
				var formData = $("#EditInventory").serialize(); 
				$.ajax({
		        url: "<?=base_url()?>BusinessAdmin/EditInventory",
		        data: formData,
		        type: "POST",
						cache: false,
		    		success: function(data) {
              if(data.success == 'true'){
								$("#ModalEditInventory").modal('hide');
              	alert("Inventory Added");
								window.location.reload();
              }
              else if (data.success == 'false'){                   
                $('.alert-message').html("").html(data.message);
								window.location.reload(); 
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

	// Update
	// Update Expenses 
	$("#UpdateDailyExpenses").validate({
                errorElement: "div",
                // rules: {
                // 		"entry_date":{
                // 			required:true
                // 		},
                //   "expense_type" : {
                //   required : true	
                // },
                // "item_name" : {
                //   required : true,
                //   maxlength : 50
                // },
                // "amount" : {
                //   required : true,
                // 		digits : true
                // }, 
                // "payment_mode" : {
                //   required : true
                // },    
                // "expense_status" : {
                //   required : true
                // },
                // "employee_name" : {
                // 	required : true,
                // 	maxlength : 100
                // }
                // },
                submitHandler: function(form) {
                    var formData = $("#UpdateDailyExpenses").serialize();
                    $.ajax({
                        url: "<?=base_url()?>BusinessAdmin/UpdateExpense",
                        data: formData,
                        type: "POST",
                        cache: false,
                        success: function(data) {
                            if (data.success == 'true') {
                                $("#UpdatePendingExpense").modal('hide');
                                toastr["success"](data.message,"", {
																positionClass: "toast-top-right",
																progressBar: "toastr-progress-bar",
																newestOnTop: "toastr-newest-on-top",
																rtl: $("body").attr("dir") === "rtl" || $("html").attr("dir") === "rtl",
																timeOut: 500
						});
						setTimeout(function () { window.location.reload();}, 500);
                            } else if (data.success == 'false') {
                                if ($('.feedback').hasClass('alert-success')) {
                                    $('.feedback').removeClass('alert-success')
                                        .addClass('alert-danger');
                                } else {
                                    $('.feedback').addClass('alert-danger');
                                }
                                $('.alert-message').html("").html(data.message);
                            }
                        },
                        error: function(data) {
                            $("#UpdatePendingExpense").modal('hide');
                            $('#defaultModalDanger').modal('show').on('shown.bs.modal',
                                function(e) {
                                    $("#ErrorModalMessage").html("").html(
                                        "<p>Error, Try again later!</p>");
                                }).on('hidden.bs.modal', function(e) {
                                window.location.reload();
                            });
                        }
                    });
                },
            }); 
	// 

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
</script>
<script type="text/javascript">		
	$("input[name=\"expiry_date\"]").daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            autoUpdateInput: false,
            locale: {
                format: 'YYYY-MM'
            }
        });

        $('.date').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('MM yy'));
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
	// updatePendingAmount
	$(document).on('click', '.pending-expense-edit-btn', function(event) {
            event.preventDefault();
            this.blur(); // Manually remove focus from clicked link.
            //   var parameters = {
            var expense_type = $(this).attr('expense_type');

            //   };
            //   $.getJSON("<?=base_url()?>BusinessAdmin/EditPendingExpense", parameters)
            //   .done(function(data, textStatus, jqXHR) { 
            $("#UpdatePendingExpense input[name=expense_id]").attr('value', $(this).attr('expense_id'));
            $("#UpdatePendingExpense input[name=expense_type]").attr('value', expense_type);
            $("#UpdatePendingExpense input[name=entry_date]").attr('value', $(this).attr('expense_date'));
            $("#UpdatePendingExpense input[name=item_name]").attr('value', $(this).attr('expense_name'));
            $("#UpdatePendingExpense input[name=expense_type_id]").attr('value', $(this).attr(
                'expense_type_id'));
            $("#UpdatePendingExpense input[name=employee_name]").attr('value', $(this).attr('employee_name'));
            $("#UpdatePendingExpense input[name=pending_amount]").attr('value', $(this).attr('pending_amount'));
            $("#UpdatePendingExpense input[name=payment_to_name]").attr('value', $(this).attr('payment_to_name'));
            $("#UpdatePendingExpense input[name=amount]").attr('value', $(this).attr('amount'));
            $("#UpdatePendingExpense input[name=remarks]").attr('value', $(this).attr('remark'));
            $("#UpdatePendingExpense input[name=invoice_number]").attr('value', $(this).attr('invoice_number'));
            $("#UpdatePendingExpense input[name=total_amount]").attr('value', $(this).attr('total_amount'));
            $("#UpdatePendingExpense input[name=payment_type]").attr('value', $(this).attr('payment_type'));
            $("#UpdatePendingExpense input[name=payment_to]").attr('value', $(this).attr('payment_to'));
            // $("#UpdatePendingExpense input[name=invoice_number]").attr('value', $(this).attr('invoice_number'));
            // $("#EditExpenseCategory textarea[name=expense_type_description]").val(data.expense_type_description);
            // $("#EditExpenseCategory input[name=expense_type_id]").attr('value',data.expense_type_id);

            $("#UpdatePendingExpense").modal('show');
            // })
            // .fail(function(jqXHR, textStatus, errorThrown) {
            // console.log(errorThrown.toString());
            // });
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
	$(document).ready(function(){
		$("#inventory_status").on('change',function(e){
			var parameters = {
				'status' :  $(this).val()
			};
			$.getJSON("<?=base_url()?>BusinessAdmin/GetInventoryStatus", parameters)
			.done(function(data, textStatus, jqXHR) {
				var temp_str="";
					for(var i = 0;i < data.message.length;i++){				
					temp_str += "<tr>";
								temp_str += "<td>"+(i+1)+" </td>";
								temp_str += "<td>" + data.message[i].invoice_number + "</td>";
								temp_str += "<td>" + data.message[i].invoice_date + "</td>";
								temp_str += "<td>" + data.message[i].product_name+"</td>";
								temp_str += "<td>" + data.message[i].product_type + "</td>";
								temp_str += "<td>" + data.message[i].product_barcode+ "</td>";
								temp_str += "<td>" + data.message[i].sku_size+ "</td>";
								temp_str += "<td>" + data.message[i].product_qty+ "</td>";
								temp_str += "<td>" + data.message[i].product_mrp+ "</td>";
								temp_str += "<td>" + data.message[i].vendor_name+ "</td>";
								temp_str += "</tr>";
					}
					$("#inv_table tbody tr").remove();
					$("#inv_table tbody").append(temp_str);
					if($("#inventory_status").val()=='regular'){
						$("#inv_table tbody tr").css('color','green');
					}else if($("#inventory_status").val()=='slow'){
						$("#inv_table tbody tr").css('color','blue');
					}else{
						$("#inv_table tbody tr").css('color','red');
					}
				
			})
			.fail(function(jqXHR, textStatus, errorThrown) {
			console.log(errorThrown.toString());
			});
		});

		$(".showInventory").on('click',function(e){
			var parameters = {
				'inventory_id' :  $(this).attr('inventory_id')
			};
			
			$.getJSON("<?=base_url()?>BusinessAdmin/GetInventoryDetails", parameters)
			.done(function(data, textStatus, jqXHR) {
				var temp_str="";
					for(var i = 0;i < data.message.length;i++){				
						temp_str += "<tr>";
						temp_str += "<td>" + data.message[i]['invoice_number'] + "</td>";
						temp_str += "<td>" + data.message[i]['invoice_date'] + "</td>";
						temp_str += "<td>" + data.message[i]['product_name']+"</td>";
						temp_str += "<td>" + data.message[i]['product_qty'] + "</td>";
						temp_str += "<td>" + data.message[i]['product_mrp']+ "</td>";
						temp_str += "<td>" + data.message[i]['product_type']+ "</td>";
						temp_str += "<td>" + data.message[i]['vendor_name']+ "</td>";
						temp_str += "</tr>";
					}
					$("#invoice_Details").html(temp_str);
					$("#ModalShowInvoiceDetails").modal('show');
			})
			.fail(function(jqXHR, textStatus, errorThrown) {
			console.log(errorThrown.toString());
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
				$.getJSON("<?=base_url()?>BusinessAdmin/GetInventoryTransactions", parameters)
				.done(function(data, textStatus, jqXHR) {
					if(data.success == 'true'){
						var str_2 = "";
						// alert(data.service.res_arr.length);
						for(var i=0;i< data.message.length;i++){
							str_2+="<tr>";
							str_2 += "<td>" + parseInt(i+1) + "</td>";
							str_2 += "<td>" + data.message[i].invoice_number + "</td>";
							str_2 += "<td>" + data.message[i].invoice_date + "</td>";
							str_2 += "<td>" + data.message[i].product_name + "</td>";
							str_2 += "<td>" + data.message[i].product_type + "</td>";	
							str_2 += "<td>" + data.message[i].product_barcode + "</td>";
							str_2 += "<td>" + data.message[i].sku_size + "</td>";
							str_2 += "<td>" + data.message[i].product_qty + "</td>";
							str_2 += "<td>" + data.message[i].product_mrp + "</td>";
							str_2 += "<td>" + data.message[i].vendor_name + "</td>";
							str_2+="</tr>";
						}
						$("#inv_table tbody tr").remove();
						$("#inv_table tbody").append(str_2);
					}
				})
				.fail(function(jqXHR, textStatus, errorThrown) {
					console.log(errorThrown.toString());
			});
  	});
		//Fetch data according to Exp Date
		$(document).on('change',"#exp_date",function(event){
    	event.preventDefault();
      this.blur();
			// alert($(this).val());
	      var parameters = {
	        exp_date :  $(this).val()
	      };
				$.getJSON("<?=base_url()?>BusinessAdmin/GetInventoryData", parameters)
				.done(function(data, textStatus, jqXHR) {
					if(data.success == 'true'){
						var str_2 = "";
						// alert(data.service.res_arr.length);
						for(var i=0;i< data.message.length;i++){
							str_2+="<tr>";
							str_2 += "<td>" + parseInt(i+1) + "</td>";
							str_2 += "<td>" + data.message[i].service_name + "</td>";
							str_2 += "<td>" + data.message[i].inventory_type+"</td>";
							str_2 += "<td>" + data.message[i].barcode + "</td>";
							str_2 += "<td>" + data.message[i].qty_per_item + "</td>";	
							str_2 += "<td>" + data.message[i].total_stock + "</td>";
							str_2 += "<td>" + data.message[i].stock_in_unit + "</td>";
							str_2 += "<td>" + data.message[i].updated_on + "</td>";
							str_2 += "<td>" + data.message[i].business_outlet_name+ "</td>";
							str_2+="</tr>";
						}
						$("#availableStock tbody tr").remove();
						$("#availableStock tbody").append(str_2);
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
