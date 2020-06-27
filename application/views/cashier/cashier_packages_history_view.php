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
					<h1 class="h3 mb-3">Redemption History</h1>
					<div class="col-12">
						<div class="card">
							<div class="card-header">
								<!-- <div class="row"> -->
									<form class="row" action="#" method="POST" id="redemptionDate">
										<div class="col-md-3">
											<h5> Select Redemption Date</h5>
										</div>									
										<div class="col-md-1">
											From <i class="align-middle" data-feather="calendar" style="color:red;"></i>
										</div>
										<div class="col-md-2">
											<input type="text" class="form-control date-picker" name="from_date" id="fromdate">
										</div>
										<div class="col-md-1">
											To <i class="align-middle" data-feather="calendar" style="color:red;"></i>
										</div>
										<div class="col-md-2">
											<input type="text" class="form-control" name="to_date" id="todate">
										</div>
										<div class="col-md-2">
											<button type="submit" id="redemptionHistory" class="btn btn-primary">Submit</button>
										</div>
									</form>
								<!-- </div>									 -->
							</div>
							<div class="card-body">
								<table id="datatables-buttons" class="table table-striped text-center" style="width: 100%;">
									<thead>
										<tr>
											<th>Sr. No.</th>
											<th>Customer Name</th>
											<th>Mobile No.</th>
											<th>Package Name</th>
											<th>Qty Redeemed</th>
											<th>Service Left</th>
											<th>Type</th>
											<th>Deduction</th>
											<th>Redemption Date</th>
											<th>Purchase Date</th>
											<th>Expiry Date</th>
										</tr>
										
									</thead>
									<tbody>
								 <?php
										$count=1;	
										foreach($redeemedPackages as $packages):
										?>
										<tr>
											<td><?=$count?></td>
											<td><?=$packages['customer_name']?></td>
											<td><?=$packages['customer_mobile']?></td>
											<td><?=$packages['salon_package_name']?></td>
											<td><?=$packages['qty']?></td>
											<td><?=$packages['service_count']?></td>
											<td><?=$packages['salon_package_type']?></td>
											<td><?=$packages['service_name']?></td>
											<td><?=$packages['redemption_date']?></td>	
											<td><?php $pdate=$packages['datetime_of_purchase'];
													echo substr($pdate,0,10);
												?></td>										
											<td><?=$packages['package_expiry_date']?></td>
											
										</tr>
										<?php
											$count++;
											endforeach;
										?> 
									</tbody>
								</table>
							</div>
						</div>
					</div>					
				</div> <!-- End row-->					
		</main>	
		<?php
						$this->load->view('cashier/cashier_footer_view');
					?>
	</div>
</div>
<script>
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
	//
	$("#redemptionDate").validate({
	  	errorElement: "div",
	    rules: {
	        "from_date" : {
            required : true
	        },
	        "to_date" : {
            required : true
	        }
	    },
	    submitHandler: function(form) {
				var formData = $("#redemptionDate").serialize(); 
				$.ajax({
	        url: "<?=base_url()?>Cashier/FilterRedemption",
	        data: formData,
	        type: "POST",
	        // crossDomain: true,
					cache: false,
	        // dataType : "json",
	    		success: function(data) {
            if(data.success == 'true'){
							var row="";
							for(var i=0;i<data.message.length;i++){							
								row+="<tr><td>"+(i+1)+"</td><td>"+data.message[i]['customer_name']+"</td><td>"+data.message[i]['customer_mobile']+"</td><td>"+data.message[i]['salon_package_name']+"</td><td>"+data.message[i]['qty']+"</td><td>"+data.message[i]['service_count']+"</td><td>"+data.message[i]['salon_package_type']+"</td><td>"+data.message[i]['service_name']+"</td><td>"+data.message[i]['redemption_date']+"</td><td>"+data.message[i]['datetime_of_purchase'].substr(0,10)+"</td><td>"+data.message[i]['package_expiry_date']+"</td></tr>";
							}
							$("#datatables-buttons tbody").html("").html(row);
							
						}
          },
          error: function(data){
  					$('.feedback').addClass('alert-danger');
  					$('.alert-message').html("").html(data.message); 
          }
				});
			},
		});

</script>
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
			// datatablesButtons.buttons().container().appendTo("#datatables-buttons_wrapper .col-md-6:eq(0)");
			// // Datatables with Multiselect
			// var datatablesMulti = $("#datatables-multi").DataTable({
			// 	responsive: true,
			// 	select: {
			// 		style: "multi"
			// 	}
			// });
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
