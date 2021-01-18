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
                    <h1 class="h3 mb-3">Customers Active Packages </h1>
                    <div class="col-12">
							<div class="card">
								<div class="card-header">
									<h5 class="card-title">Packages List</h5>
									
								</div>
								<div class="card-body">
									<table id="datatables-buttons" class="table table-striped" style="width: 100%;">
										<thead>
											<tr>
												<th>Bill No</th>
												<th>Customer Name</th>
												<th>Customer Mobile</th>
												<th>Purchase Date</th>
												<th>Package Name</th>
												<th>Package Type</th>
												<th>Bill Amt</th>
												<th>Payment Mode</th>
												<th>Expiry Date</th>
												<th>Expert Name</th>												
											</tr>
										</thead>
										<tbody>
											<?php
											foreach($customerPackages as $packages):
											?>
											<tr>
												<td><?=$packages['package_txn_unique_serial_id']?></td>
												<td><?=$packages['customer_name']?></td>
												<td><?=$packages['customer_mobile']?></td>
												<td><?=$packages['date']?></td>
												<td><?=$packages['salon_package_name']?></td>
												<td><?=$packages['salon_package_type']?></td>
												<td><?=$packages['package_txn_value']?></td>	
												<td><?php ($arr=array(json_decode($packages['payment_mode'],TRUE))); 
												foreach($arr[0] as $k=>$v){
													echo ($v['payment_type']);
													} ?></td>										
												<td><?=$packages['package_expiry_date']?></td>
												<td><?=$packages['employee_first_name']?></td>
											
											</tr>
											<?php
												endforeach;
											?>
										</tbody>
									</table>
								</div>
							</div>
						</div>					
					
				</div> <!-- End row-->
					<!--MODAL AREA START-->
					<div id="ModalWalletPackageDetails" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
						<div role="document" class="modal-dialog modal-md">
							<div class="modal-content">
								<div class="modal-header">
									<h5 class="modal-title text-white font-weight-bold" >Package Details</h5>
									<button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
								</div>
								<div class="modal-body mb-3">
									<table class="table table-striped table-hove" width="100%">
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
								<div class="modal-header">
									<h5 class="modal-title text-white font-weight-bold" >Package Details</h5>
									<button type="button" data-dismiss="modal" aria-label="Close" class="close"><span class="text-white" aria-hidden="true">×</span></button>
								</div>
								<div class="modal-body ">
									<table class="table table-striped">
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
						<div role="document" class="modal-dialog modal-sm">
							<div class="modal-content">
								<div class="modal-header">
									<h5 class="modal-title text-white font-weight-bold" >Package Details</h5>
									<button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
								</div>
								<div class="modal-body ">
									<table class="table table-striped table-responsive">
										<thead>
											<th>Sno.</th>
											<th>Bundled Services</th>
											<th>Discount</th>
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
		</main>	
		<?php
						$this->load->view('cashier/cashier_footer_view');
					?>
	</div>
</div>
<script>
		$(function() {
			// Datatables basic
			$("#datatables-basic").DataTable({
				responsive: true
			});
			// Datatables with Buttons
			var datatablesButtons = $("#datatables-buttons").DataTable({
				responsive: true,
				lengthChange: !1
				// buttons: ["copy", "print"]
			});
			datatablesButtons.buttons().container().appendTo("#datatables-buttons_wrapper .col-md-6:eq(0)");
			// Datatables with Multiselect
			var datatablesMulti = $("#datatables-multi").DataTable({
				responsive: true,
				select: {
					style: "multi"
				}
			});
		});

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
	</script>
<script>
	function exportTableToExcel(tableID, filename = 'customerlist'){
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
