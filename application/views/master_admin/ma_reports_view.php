<?php
	$this->load->view('master_admin/ma_header_view');
?>
<div class="wrapper">
	<?php
		$this->load->view('master_admin/ma_nav_view');
	?>
	<div class="main">
		<?php
			$this->load->view('master_admin/ma_top_nav_view');
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
                      <option value="Customer">Customer</option>
                      <option value="Inventory">Inventory</option>
                      <option value="Discount">Discount</option>
                      <option value="Employee">Employee</option>
                      <option value="Package">Package</option>
                      <!-- <option value="Tax">Tax</option> -->
                      <option value="Appointment">Appointment</option>
                    </select>
                  </div> 
                  <div class=" form-group col-md-2">
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
                <div class="form-row">
                <table class="table table-striped datatables-basic" style="width: 100%;">
											<thead>
												<tr>
													<th>Sno.</th>
													<th>Outlet Name</th>
													<th>Business Admin Name</th>
													<!-- <th>Description</th> -->
													<th>Actions</th>
												</tr>
											</thead>
											<tbody>
                        <?php
                          $index = 1;
                          foreach($business_admin_details as $business_admin){
                            ?>
                            <tr>
                              <td><?=$index;?></td>
                              <td><?=$business_admin['business_outlet_name']?></td>
                              <td><?=$business_admin['business_admin_first_name']?></td>
							  <td><input type="checkbox" id="bill<?=$index;?>" name="business_outlet[]" business_admin_id = "<?=$business_admin['business_admin_id']?>" business_outlet_id="<?=$business_admin['business_outlet_id']?>"></td>
							  
                            </tr>  
                            <?php
                            $index+=1;
                          }
                        ?>
                      </tbody>
                </table>
				</div>
				<input type="text" name="file_contents[]" class="form-control" readonly hidden id="file_contents">
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

							<div class="modal" id="ModalCancelBill" tabindex="-1" role="dialog" aria-hidden="true">
								<div class="modal-dialog modal-dialog-centered modal-md" role="document">
									<div class="modal-content">
										<div class="modal-header">
											<h5 class="modal-title text-white">Cancel Bill</h5>
											<button type="button" class="close" data-dismiss="modal" aria-label="Close">
											<span aria-hidden="true">&times;</span>
											</button>
										</div>
										<div class="modal-body">
											<div class="row">
												<div class="col-md-12">
													<form id="cancel_bill" method="POST" action="#">
														<div class="form-row">
															<div class="col-md-12">
																<label>Admin Password</label>
																<input type="password" class="form-control" name="password" id="admin_password">
															</div>
														</div>
														<div class="form-row">
															<div class="form-group col-md-12">
																<label>Reason for Bill Cancellation</label>
																<textarea class="form-control" name="remarks" required ></textarea>
															</div>
															
														</div>
														<input type="hidden" class="form-control" name="txn_id" id="txn_id" />
														<button type="submit" class="btn btn-primary">Submit</button>
													</form>
													<div class="alert alert-dismissible feedmack1" style="margin:0px;" role="alert">
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
								</div>
							</div>
							<div class="modal" id="ModalVerifyPassword" tabindex="-1" role="dialog" aria-hidden="true">
								<div class="modal-dialog modal-dialog-centered modal-md" role="document">
									<div class="modal-content">
										<div class="modal-header">
											<h5 class="modal-title text-white">Validate Password</h5>
											<button type="button" class="close" data-dismiss="modal" aria-label="Close">
											<span aria-hidden="true">&times;</span>
											</button>
										</div>
										<div class="modal-body">
											<div class="row">
												<div class="col-md-12">
													<form id="verify_password" method="GET" action="#" enctype="multipart/form-data">
													<div class="form-row">
														<div class="form-group col-md-12">
															<input type="password" name="admin_password" class="form-control" placeholder="Enter Password">
															<input type="hidden" name="txn_id">
														</div>														
													</div>
														<!-- <input type="hidden" class="form-control" name="txn_id" id="txn_id" /> -->
														<button type="button" class="btn btn-primary" id="verify_btn">Submit</button>
														<!-- <button type="button" class="btn btn-warning" onclick="javascript:window.location.reload();">Cancel</button> -->
													</form>
													<div class="alert alert-dismissible feedmack1" style="margin:0px;" role="alert">
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
								</div>
							</div>
							<div class="modal" id="ModalEditBill" tabindex="-1" role="dialog" aria-hidden="true">
								<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
									<div class="modal-content">
										<div class="modal-header">
											<h5 class="modal-title text-white">Edit Bill</h5>
											<button type="button" class="close" data-dismiss="modal" aria-label="Close">
											<span aria-hidden="true">&times;</span>
											</button>
										</div>
										<div class="modal-body">
											<div class="row">
												<div class="col-md-12">
													<form id="edit_bill" method="POST" action="#">
														<div class="form-row">
															<table>
																<thead>
																	<th>Service</th>
																	<!-- <th>Customer ID</th> -->
																	<th>Price</th>
																	<th>Discount %</th>
																	<th>Discount abs</th>
																	<th>Quantity</th>
																	<!-- <th>Payment Type</th>
																	<th>Payment Mode</th> -->
																	<th colspan="2">Action</th>
																</thead>
																<tbody id="edit_bill">
																	
																</tbody>		
															</table>												
														</div>
														<!-- <button type="submit" class="btn btn-primary">Submit</button> -->
													</form>
													<div class="alert alert-dismissible feedmack1" style="margin:0px;" role="alert">
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
								</div>
							</div>
							<!--Modal Area Ends--->
            </div>
					</div>
				</div>
			</div>
		</main>
<?php
	$this->load->view('master_admin/ma_footer_view');
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
	$(".datatables-masic").DataTable({
			responsive: true
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
				display: "Bill Wise Sales Report",
				value: "BWSR"
			},
			{
				display: "Date Wise Category Sales report",
				value: "DWCSR"
			},
			{
				display: "Sub-Category Wise Sales Report",
				value: "SCWSR"
			},
			
			{
				display: "Item Wise Sales Report",
				value: "IWSR"
			},
			{
				display : "Payment Wise Report",
				value : "PWR"
			},
			{
				display : "Pending Amount Transaction Report",
				value : "PATR"
			}
		];

		var Customer = [
			{
				display: "Item Wise Customer report",
				value: "IWCR"
			},
			{
				display : "Virtual Wallet Report",
				value : "VWR"
			},
			{
				display : "Pending Amount Report",
				value : "PAR"
			}
		];

		var Inventory = [
			{
				display: "Stock Report OTC",
				value: "SROTC"
			},
			{
				display: "Stock Report Raw Material",
				value: "SRRM"
			}
		];

		var Discount = [
			{
				display: "Bill Wise Discont Report",
				value: "BWDR"
			}
		];

		var Employee = [
			{
				display: "Employee Wise Performance Report",
				value: "EWPR"
			}
		];

		var Package = [
			{
				display: "Package Report",
				value: "PR"
			},
			{
				display: "Package Wise Sales Report",
				value: "PWSR"
			}
		];
    var Appointment= [
			{
				display: "Appointment Report",
				value: "AR"
			}
		];
		/*var Tax = [
			{
					display: "Item Wise Tax Report",
					value: "IWTR"
			}
		];*/

		// Function executes on change of first select option field.
		$(document).on('change','#groupName',function(event) {    
			var select = $("#groupName option:selected").val();
		
			switch (select) {
				case "Sales":
				AddSubGroup(Sales);	
				break;

				case "Customer":
				AddSubGroup(Customer);
				break;

				case "Inventory":
				AddSubGroup(Inventory);
				break;

				case "Discount":
				AddSubGroup(Discount);
				break;

				case "Employee":
				AddSubGroup(Employee);
				break;

				case "Package":
				AddSubGroup(Package);
				break;
				
				/*case "Tax":
				AddSubGroup(Tax);
				break;*/
					case "Appointment":
				AddSubGroup(Appointment);
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
				var business_details=[];
				for(var count = 1;count< <?=count($business_admin_details);?>;count++)
				{
					if($("#bill"+count).is(":checked"))
					{
						business_details.push($("#bill"+count).attr("business_outlet_id"));
					}
					    
				}
				$("#file_contents").val(business_details);
				var formData = $("#GetResults").serialize(); 
				$.ajax({
		        url: "<?=base_url()?>MasterReport/ReportsManagement",
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


		//EDIT Bills with remarks
		$(document).on('click','.editBtn',function(event) {
      event.preventDefault();
      this.blur(); // Manually remove focus from clicked link.
			// alert($(this).attr('txn_id'));
			if($(this).attr('settlement_way')=='Full Payment'){
				$("#verify_password input[name=txn_id]").val($(this).attr('txn_id'));
      	$("#ModalVerifyPassword").modal('show');
			}else{
				alert("Bill Can be edited only on  Full Payment.");
			}
		      
    });
		//

	});
</script>
 