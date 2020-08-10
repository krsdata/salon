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
				<h1 class="h3 mb-3">Reports Management</h1>
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
													<div class="alert alert-dismissible feedback1" style="margin:0px;" role="alert">
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
													<div class="alert alert-dismissible feedback1" style="margin:0px;" role="alert">
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
							<div class="modal fade show" id="ModalEditBill" tabindex="-1" role="dialog" aria-hidden="true">
								<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
									<div class="modal-content">
										<div class="modal-header">
											<h5 class="modal-title text-white">
												Edit Bill
											</h5>
											<button type="button" class="close" data-dismiss="modal" aria-label="Close">
											<span aria-hidden="true">&times;</span>
											</button>
										</div>
										<div class="modal-body">
											<div class="row">
												<div class="col-md-12">
													<form id="edit_bill" method="POST" action="#">
														<div class="form-row">
															<table id="edit_bill">
																<thead>
																	<th>Service</th>
																	<th>Mrp</th>
																	<th>Discount %</th>
																	<th>Discount Abs</th>
																	<th>Net Amount</th>
																	<th>Txn Date </th>
																	<th>Expert</th>
																	<th colspan="2">Action</th>
																</thead>
																<tbody id="edit_bill">
																	
																</tbody>		
															</table>												
														</div>
														<button type="button" id="close_edit_btn" class="btn btn-primary float-right" data-dismiss="modal">Close</button>
													</form>
													<!-- <div class="alert alert-dismissible feedback1" style="margin:0px;" role="alert">
														<button type="button" class="close" data-dismiss="alert" aria-label="Close">
															<span aria-hidden="true">&times;</span>
														</button>
														<div class="alert-message">
														</div>
													</div> -->
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<!--Modal Area Ends-->
            </div>
					</div>
					<?php
						}
					?>
				</div>
				<div  class="col-md-12">
						<div class="card flex-fill w-100">
							<div class="card-header">								
							<form action="#" method="GET" id="GetBills">
                <div class="form-row"> 
									<div class="col-md-3">
									<h3>Generate Bills</h3>
									</div>
                  <div class="col-md-1" style="text-align:end;">
                    From <i class="fa fa-calendar" style="color:red;"></i>
                  </div>
                  <div class="from-group col-md-2">
                    <input type="text" class="form-control" name="from_date" id="from_date" />
                  </div>
                  <div class="col-md-1" style="text-align:end;">
                    To <i class="fa fa-calendar" style="color:red;"></i>
                  </div>
                  <div class="from-group col-md-2">
                    <input type="text" class="form-control" name="to_date" id="to_date" />
                  </div>
                  <div class="from-group col-md-2">
                    <button class="btn btn-primary" id="get_bill" >Submit</button>
                  </div>     
                </div>
              </form>
							</div>
							<div class="card-body">
							<?php
				$temp = array();
				$i=0;
				foreach($expert as $expert1){ 
					$temp[$i]['id'] = $expert1['employee_id'];
					$temp[$i]['name'] = $expert1['employee_first_name'];
					$i++;
				
					}
					$temp = json_encode($temp);
					?>
					<div style="display:none;" id="emp_data"><?php echo $temp;?></div>
								<table class="table datatables-basic table-hover" id="bill_data" style="width:100%;">
									<thead>
										<tr>
											<th>Bill No.</th>
											<th>Mobile No.</th>
											<th>Name</th>
											<th>Bill Date</th>
											<th>MRP Amount</th>
											<th>Discount</th>
											<th>Net Amount</th>
											<th colspan="4">Action</th>
										</tr>
									</thead>
									<tbody>
										
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
				display: "OverAll Bill Wise Sales Report",
				value: "OBWSR"
			},
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
			},
			{
				display : "Cancelled Transaction Report",
				value:"CT"
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
				display: "Inventory Stock Report",
				value: "SROTC"
			},
			{
				display: "Transaction Wise Report",
				value: "TWR"
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
					var formData = $("#GetResults").serialize(); 
					$.ajax({
		        url: "<?=base_url()?>BusinessAdmin/ReportsManagement",
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

			// GetBills
			$(document).on('click',"#get_bill",function(event){
				event.preventDefault();
				this.blur();
				// alert($("#from_date").val());
				var parameters = {
					from_date: $("#from_date").val(),
							to_date	:	$("#to_date").val()
				};
	    
					$.getJSON("<?=base_url()?>BusinessAdmin/GenerateCustomerBill", parameters)
					.done(function(data, textStatus, jqXHR) { 
						var str_2 = "";		
						for(var i=0;i<data.length;i++){
							str_2 += "<tr>";
						str_2 += "<td>"+data[i].txn_id+"</td>";
						str_2 += "<td>"+data[i].mobile+"</td>";
						str_2 += "<td>"+data[i].name+"</td>";
							str_2 += "<td>"+data[i].billing_date+"</td>";
						str_2 += "<td>"+data[i].mrp_amt+"</td>";
						str_2 += "<td>"+data[i].discount+"</td>";						
						str_2 += "<td>"+data[i].net_amt+"</td>";
						// str_2 += "<td>"+data[i].settlement_way+"</td>";
						var edit_bill="<?php if(array_search('Edit_Bill', $business_admin_packages) !== false){ echo 'Edit_Bill';}?>";
						
						if(data[i].txn_status==1 && data[i].net_amt > 0){
							// alert(edit_bill);
							if(edit_bill!=''){
							// str_2 += "<td><button class='btn btn-primary editBtn' data-toggle='Modal' data-target='#ModalVerifyPassword' txn_id='"+data[i].bill_no+"' settlement_way='"+data[i].settlement_way
							// +"' ><i class='fa fa-edit'></i></button></td>";
							}
							str_2 += "<td><button class='btn btn-success cancelBtn' data-toggle='Modal' data-target='#ModalCancelBill' txn_id='"+data[i].bill_no+"' ><i class='fa fa-trash'></i></button></td>";
							str_2 += "<td><button class='btn btn-warning sendSmsBtn'  txn_id='"+data[i].bill_no+"'><i class='fa fa-sms'></i></button></td>";
							str_2 += "<td><a href='<?=base_url()?>BusinessAdmin/RePrintBill/"+data[i].bill_no+"' target='_blank' class='btn btn-danger' ><i class='fa fa-print'></i></a></td>";
						}else{
							if(edit_bill!=''){
							str_2 += "<td><button class='btn btn-primary editBtn' data-toggle='Modal' data-target='#ModalEditBill' txn_id='"+data[i].txn_id+"' disabled><i class='fa fa-edit'></i></button></td>";
							}
							str_2 += "<td><button class='btn btn-danger cancelBtn' data-toggle='Modal' data-target='#ModalCancelBill' txn_id='"+data[i].bill_no+"' disabled><i class='fa fa-trash'></i></button></td>";
						}
					
						str_2 += "</tr>";
						}				
						
						$("#bill_data tbody tr").remove();
						$("#bill_data tbody").append(str_2);
					})
					.fail(function(jqXHR, textStatus, errorThrown) {
						console.log(errorThrown.toString());
					});
  				});
			});
		//Verify Password
		$(document).on('click',"#verify_btn",function(event){
				event.preventDefault();
				this.blur();
	      var parameters = {
	        txn_id: $("#verify_password input[name='txn_id']").val(),
					admin_password : $("#verify_password input[name='admin_password']").val()
	      };
	     
					$.getJSON("<?=base_url()?>BusinessAdmin/VerifyPassword", parameters)
					.done(function(data, textStatus, jqXHR) { 
						$("#ModalVerifyPassword").modal('hide');
						var str_2 = "";		
								
						for(var i=0;i<data.length;i++){						
							str_2 += "<tr>";
							str_2 += "<td><div class='form-group'><input type='text' class='form-control editTransaction' name='service_name[]' value='"+data[i].service_name+"' readonly></div></td>";
							str_2 += "<td><div class='form-group'><input type='number' class='form-control editTransaction' name='txn_discounted_price[]' value="+data[i].mrp+" readonly></div></td>";
							str_2 += "<td><div class='form-group'><input type='number' class='form-control editTransaction' name='txn_discount_percent[]' value="+data[i].disc1+" readonly></div></td>";
							str_2 += "<td><div class='form-group'><input type='number' class='form-control serviceAbsDisc editTransaction' name='txn_discount_abs[]' value="+data[i].disc2+" readonly></div></td>";
							str_2 += "<td><div class='form-group'><input type='number' class='form-control editTransaction' name='txn_discounted_price[]' value="+data[i].txn_service_discounted_price+" readonly></div></td>";
							str_2 += "<td><div class='form-group'><input type='date' class='form-control serviceTxnDate editTransaction' name='txn_datetime' value="+data[i].date+"></div></td>";
							str_2 += "<td><div class='form-group'><select class='form-control serviceExpert' name='expert[]'><option value='"+data[i].txn_service_expert_id+"' selected >"+data[i].expert+"</option><?php foreach($expert as $expert){ echo "<option value=".$expert['employee_id'].">".$expert['employee_first_name']."</option>";}?></select></div></td>";
							str_2 += "<td><div class='form-group'><input type='hidden'  name='txn_id' value="+data[i].txn_id+"></div></td>";
							str_2 += "<td><div class='form-group'><button class='btn btn-sm btn-danger  Edit_individual_service' txn_id='"+data[i].txn_id+"' txn_service_service_id='"+data[i].service_id+"' txn_service_discounted_price='"+data[i].txn_service_discounted_price+"'> <i class='fa fa-trash'></i></button><div></td>";
							str_2 += "<td><div class='form-group'><button class='btn btn-sm btn-success updateService' txn_id='"+data[i].txn_id+"' txn_service_service_id='"+data[i].service_id+"' txn_service_id='"+data[i].txn_service_id+"' old_txn_date='"+data[i].date+"' old_txn_expert='"+data[i].txn_service_expert_id+"'  old_abs_disc='"+data[i].disc2+"'>Save</button></div></td>";

						str_2 += "</tr>";
						}				
			
						$("#edit_bill tbody tr").remove();
						$("#edit_bill tbody").append(str_2);
						$("#ModalEditBill").modal('show');
					})
					.fail(function(jqXHR, textStatus, errorThrown) {
						console.log(errorThrown.toString());
				});
  		});
			$(document).on('change','.serviceExpert',function(event){
				$('.updateService').attr('txn_expert',$(this).val());
			});
			$(document).on('change','.serviceTxnDate',function(event){
				$('.updateService').attr('txn_date',$(this).val());
			});

			$(document).on('click','#close_edit_btn',function(event){
				window.location.reload();
			});
			$(document).on('click',".updateService",function(event){
				event.preventDefault();
				this.blur();
				var parameters = {
        "txn_id" : $(this).attr('txn_id'),
        "txn_expert" : $(this).attr('txn_expert'),
        "txn_date" : $(this).attr('txn_date'),
				"txn_service_id" : $(this).attr('txn_service_id'),
				"old_txn_date"	: $(this).attr('old_txn_date'),
				"old_txn_expert" : $(this).attr('old_txn_expert')
      };
			
      $.ajax({
        url: "<?=base_url()?>BusinessAdmin/UpdateTransaction",
        data: parameters,
        type: "POST",
        // crossDomain: true,
				cache: false,
        // dataType : "json",
    		success: function(data) {
          if(data.success == 'true'){
						// $("#ModalEditBill").modal('hide');
						// 		toastr["success"](data.message,"", {
						// 		positionClass: "toast-top-right",
						// 		progressBar: "toastr-progress-bar",
						// 		newestOnTop: "toastr-newest-on-top",
						// 		rtl: $("body").attr("dir") === "rtl" || $("html").attr("dir") === "rtl",
						// 		timeOut: 500
						// 	});
						// 	setTimeout(function () { location.reload(1); }, 500);
          }
          else if (data.success == 'false'){  
						$("#ModalEditBill").modal('hide');                 
      	    $('#defaultModalDanger').modal('show').on('shown.bs.modal', function (e){
							$("#ErrorModalMessage").html("").html(data.message);
						})
          }
        }
			});
				//
				// var rowno = $("#edit_bill tr").length;				
				// rowno = rowno+1;

				// var emp_data = JSON.parse($('#emp_data').text());
				// var drp = '<select  class="form-control" name="expert[]">';
				// $(emp_data).each(function(index,value){
				// 	drp += "<option value='"+value.id+"'>"+value.name+"</option>";
				
				// });
				// drp += "</select>";
				
				// $("#edit_bill tr:last").after("<tr><td><div class=\"form-group\"><input type=\"text\" name=\"service_name\" class=\"form-control\"></div></td><td><div class=\"form-group\"><input type=\"number\" class=\"form-control\" name=\"txn_service_discounted_price[]\" temp=\"Count\" ></div><td><div class=\"form-group\"><input type=\"number\" name=\"service_name\" class=\"form-control\"></div></td><td><div class=\"form-group\"><input type=\"number\" class=\"form-control\" name=\"txn_service_discounted_price[]\" temp=\"Count\" ></div></td><td><div class=\"form-group\">"+drp+"</div></td></tr>");
			});
			
		//EDIT Bills with remarks
		$(document).on('click','.editBtn',function(event) {
      event.preventDefault();
      this.blur(); // Manually remove focus from clicked link.
				$("#verify_password input[name=txn_id]").val($(this).attr('txn_id'));
      	$("#ModalVerifyPassword").modal('show');		      
    });
		//delete individual service
		$(document).on('click','.Edit_individual_service',function(event) {
      event.preventDefault();
      this.blur(); // Manually remove focus from clicked link.
      var parameters = {
        "txn_id" : $(this).attr('txn_id'),
        "txn_service_service_id" : $(this).attr('txn_service_service_id'),
        "txn_service_discounted_price" : $(this).attr('txn_service_discounted_price')
      };
      $.ajax({
        url: "<?=base_url()?>BusinessAdmin/EditBills",
        data: parameters,
        type: "POST",
        // crossDomain: true,
				cache: false,
        // dataType : "json",
    		success: function(data) {
          if(data.success == 'true'){
						$("#ModalEditBill").modal('hide');
								toastr["success"](data.message,"", {
								positionClass: "toast-top-right",
								progressBar: "toastr-progress-bar",
								newestOnTop: "toastr-newest-on-top",
								rtl: $("body").attr("dir") === "rtl" || $("html").attr("dir") === "rtl",
								timeOut: 500
							});
							setTimeout(function () { location.reload(1); }, 500);
          }
          else if (data.success == 'false'){  
						$("#ModalEditBill").modal('hide');                 
      	    $('#defaultModalDanger').modal('show').on('shown.bs.modal', function (e){
							$("#ErrorModalMessage").html("").html(data.message);
						})
          }
        }
			});
    });

		//ReSend Bill SMS
		$(document).on('click','.sendSmsBtn',function(event) {
      event.preventDefault();
      this.blur(); // Manually remove focus from clicked link.
			// alert($(this).attr('txn_id'));
			var parameters={
				"txn_id" : $(this).attr('txn_id')
			};
			$.ajax({
		        url: "<?=base_url()?>BusinessAdmin/ReSendBill",
		        data: parameters,
		        type: "POST",
		        // crossDomain: true,
						cache: false,
		        // dataType : "json",
		    		success: function(data) {
	            if(data.success == 'true'){
		 							toastr["success"](data.message,"", {
		 							positionClass: "toast-top-right",
		 							progressBar: "toastr-progress-bar",
									newestOnTop: "toastr-newest-on-top",
		 							rtl: $("body").attr("dir") === "rtl" || $("html").attr("dir") === "rtl",
									timeOut: 500
		 						});
		 						setTimeout(function () { location.reload(1); }, 500);
	            }else if (data.success == 'false'){   
								$("#ModalCancelBill").modal('hide');    
	            	$("#ErrorModalMessage").html("").html(data.message);            
	        	    $("#defaultModalDanger").modal('show');
	            }
	          },
	          error: function(data){
							$("#ModalCancelBill").modal('hide');
	  					$("#ErrorModalMessage").html("").html(data.message);            
	        	  $("#defaultModalDanger").modal('show');
	          }
					});
    });
		//Cancel Bills with remarks
		$(document).on('click','.cancelBtn',function(event) {
      event.preventDefault();
      this.blur(); // Manually remove focus from clicked link.
			// alert($(this).attr('txn_id'));
			$("#cancel_bill input[name=txn_id]").val($(this).attr('txn_id'));
      $("#ModalCancelBill").modal('show');
      
    });
		//
		$("#cancel_bill").validate({
		  	errorElement: "div",
		    rules: {
						"admin_password":{
							required : true
						},
		        "remarks" : {
	            required : true
		        },
		        "txn_id" :{
		        	required : true
		        }
		    },
		    submitHandler: function(form) {
					var formData = $("#cancel_bill").serialize(); 
					$.ajax({
		        url: "<?=base_url()?>BusinessAdmin/DeleteBills",
		        data: formData,
		        type: "POST",
		        // crossDomain: true,
						cache: false,
		        // dataType : "json",
		    		success: function(data) {
	            if(data.success == 'true'){
								$("#ModalCancelBill").modal('hide');
		 							toastr["success"](data.message,"", {
		 							positionClass: "toast-top-right",
		 							progressBar: "toastr-progress-bar",
									newestOnTop: "toastr-newest-on-top",
		 							rtl: $("body").attr("dir") === "rtl" || $("html").attr("dir") === "rtl",
									timeOut: 500
		 						});
		 						setTimeout(function () { location.reload(1); }, 500);
	            }else if (data.success == 'false'){   
								$("#ModalCancelBill").modal('hide');    
	            	$("#ErrorModalMessage").html("").html(data.message);            
	        	    $("#defaultModalDanger").modal('show');
	            }
	          },
	          error: function(data){
							$("#ModalCancelBill").modal('hide');
	  					$("#ErrorModalMessage").html("").html(data.message);            
	        	  $("#defaultModalDanger").modal('show');
	          }
					});
				},
			});

	});
</script>
 