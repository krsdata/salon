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
				<h1 class="h3">Expense Tracker</h1>
				<div class="row">
					<div class="col-md-12">
						<div class="card">
							<div class="card-header">
								<h5 class="card-title">Expenses</h5>
							</div>
							<div class="card-body">
							<div class="row">
								<div class="form-group col-md-6">
									<button class="btn btn-primary mb-2" data-toggle="modal" id="AddExpense" data-target="#ModalAddExpense"><i class="fas fa-fw fa-plus"></i>Add Expense</button>
								</div>
								<div class="form-group col-md-2" style="float: right">       
										<select id="expense_group" class="btn bg-white" style="float: right">
											<option selected disabled>Select</option>
											<option value="7days">Last 7 Days</option>
											<option value="30days">Last 30 Days</option>
											<option value="mtd">MTD</option>
											<option value="range">Date Range</option>
										</select>
								</div> 
                                <div class="form-group col-md-2" id="altrdiv">

								</div>       
								<div class="form-group col-md-2" id="hide" hidden>        
										<!-- <input type="text" name="to_date"  id="to_date" class="btn bg-white date" placeholder="To Date" hidden> -->
										<input class="form-control" type="text" id="daterange" name="daterange" style="float: right" placeholder="Select Range"/>
								</div>

								<div class="form-group col-md-2">		
									<button class="btn btn-primary download float-right mb-2" id="download" style="float: right"><i class="fa fa-file-export"></i>Download</button> 
								</div>
							</div>		
								<!-- Modals -->
								
								<?php
									$this->load->view('cashier/cashier_success_modal_view');
									$this->load->view('cashier/cashier_error_modal_view');
								?>
								<div class="modal" id="ModalAddExpense" tabindex="-1" role="dialog" aria-hidden="true">
									<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
										<div class="modal-content">
											<div class="modal-header" style="background-color:#47bac1;">
												<h5 class="modal-title" style="color: white;">Add Expense</h5>
												<button type="button" class="close" data-dismiss="modal" aria-label="Close">
										<span aria-hidden="true" style="color: white;">&times;</span>
										</button>
											</div>
											<div class="modal-body">
												<div class="row">
													<div class="col-md-12">
													
														<form id="AddDailyExpenses" method="POST" action="#">
															<div class="row">
															<div class="form-group col-md-3">
																<label>Entry Date</label>
																<input type="text" name="entry_date" class="form-control">
															</div>
															<div class="form-group col-md-3">
																<label>Expense Name</label>
																<input type="text" class="form-control" placeholder="Item Name"
																	name="item_name" autofocus>
															</div>
															<div class="form-group col-md-3">
																<label>Expense Type</label>
																<select class="form-control" name="expense_type_id">
																	<?php
																	foreach ($expense_types  as $type):
																?>
																	<option value="<?=$type['expense_type_id']?>">
																		<?=$type['expense_type']?></option>
																	<?php	
																	endforeach;
																?>
																</select>
															</div>
															<div class="form-group col-md-3">
																<label>Cashier Name</label>
																<input type="text" class="form-control"
																	value="<?=$cashier['employee_name']?>"
																	name="employee_name" readonly>
															</div>
															</div>
																<div class="row">
																	<div class="form-group col-md-3">
																		<label>Payment Type</label>
																		<select name="payment_type" id="payment_type"
																			class="form-control">
																			<option value="" disabled selected>Select</option>
																			<option value="vendor">Vendor</option>
																			<option value="employee">Employee</option>
																			<option value="others">Others</option>
																		</select>

																	</div>
																	<div class="form-group col-md-3">
																		<label>Paid To</label>
																		<select name="payment_to" id="payment_to" class="form-control" >
																		</select>
																		<input type="text" name="payment_to_others" id="payment_to_text"
																			class="form-control" hidden>
																			<input type="text" name="payment_to_name" id="payment_to_name"
																			class="form-control" hidden>    
																		
																	</div>
																	<div class="form-group col-md-3">
																		<label>Total Amount Payable</label>
																		<input type="number" class="form-control" placeholder="Total Amount Payable"
																			name="total_amt" id="addtotalpay" min="0">
																	</div>
																	<div class="form-group col-md-3">
																		<label>Amount</label>
																		<input type="number" class="form-control" 
																			placeholder="Amount Paid Now" name="amount" id="addamt">
																	</div>
																	
																</div>
																<div class="row">
																	<div class="form-group col-md-3">
																		<label>Payment Mode</label>
																		<select class="form-control" name="payment_mode">
																			<option value="Cash" selected>Cash</option>
																			<option value="Card">Card</option>
																			<option value="Bank">Cheque/Bank</option>
																			<option value="Wallet">Wallet/ Others</option>
																			<select>
																	</div>
																	<div class="form-group col-md-3">
																		<label>Payment Status</label>
																		<select class="form-control" name="expense_status"
																			id="expense_status">
																			<option value="Paid" selected>Paid</option>
																			<option value="Advance">Advance</option>
																			<option value="Unpaid">Unpaid</option>
																			<option value="Partialy Paid">Partialy paid</option>
																			<select>
																	</div>
																	<div class="form-group col-md-3" id="pend_amt" hidden>
																		<label>Pending Amount</label>
																		<input type="text" name="pending_amount" class="form-control"
																			placeholder="Pending Amount" id="addpendamt">
																	</div>

																	<div class="form-group col-md-3">
																		<label>Invoice (If any)</label>
																		<input type="text" name="invoice_number" class="form-control"
																			placeholder=" Invoice number">
																	</div>
																	<div class="form-group col-md-3">
																		<label>Remarks</label>
																		<input type="text" class="form-control" placeholder="Remarks"
																			name="remarks">
																	</div>
																</div>
																<button type="submit" class="btn btn-primary">Submit</button>
                                           				</form>
														<div class="alert alert-dismissible feedback mt-2" role="alert">
															<button type="button" class="close" data-dismiss="alert" aria-label="Close">
																<span aria-hidden="true">&times;</span>
									           				</button>
															<div class="alert-message"></div>
														</div>
													</div>
												</div>
												<!-- <div class="modal-footer">
													<button type="button" class="btn btn-success" data-dismiss="modal">Close</button>
												</div> -->
											</div>
										</div>
									</div>
								</div>
									
								<table class="table table-striped datatables-basic" id="expenseTable" style="width: 100%;">
									<thead>
										<tr class="text-primary">
											<th>Sr.No.</th>
											<th>Date</th>
											<th>Expense Id</th>
											<th>Expense Type</th>
											<th>Item Name</th>
											<th>Cashier Name</th>
											<th>Total Amount</th>
	                   						<th>Paid</th>
											<th>Pending Amount</th>
											<!-- <th>Units</th> -->
											<th>Mode</th>
	                    					<th>Payment Status</th>
										</tr>
									</thead>
									<tbody>
										<?php
										$i=1;
	                   					foreach ($all_expenses as $expense):
										?>
										<tr>
											<th><?=$i?></th>
											<td><?=$expense['expense_date']?></td>
											<td><?=$expense['expense_unique_serial_id']?></td>
											<td><?=$expense['expense_type']?></td>
											<td><?=$expense['item_name']?></td>
	                    					<td><?=$expense['employee_name']?></td>
											<td><?=$expense['total_amount']?></td>
											<td><?=$expense['amount']?></td>
											<td><?=$expense['pending_amount']?></td>
											<td><?=$expense['payment_mode']?></td>
											<td><?=$expense['expense_status']?></td>								
										</tr>	
										  <?php
										  	$i++;	                    	
											endforeach;
										?>
									</tbody>
								</table>
							</div>               
						</div>	
					</div>
				</div>
				<!--  -->
				<div class="row">
					<div class="col-md-6">
						<!-- <div class="card">
							<div class="card-header">
								<div class="card-title">Expenses Summary</div>
								<form method="GET" action="#" id="GetExpensesSummary">
									<div class="row">
										<div class="form-group col-md-1">
											<label class="form-label">From</label>
										</div>
										<div class="form-group col-md-3">
											<input class="form-control" type="date" name="from_date">
										</div>
										<div class="form-group col-md-1">
											<label class="form-label">To</label>
										</div>
										<div class="form-group col-md-3">
											<input class="form-control" type="date" name="to_date"/>
										</div>
										<div class="form-group col-md-2">
										<button type="submit" class="btn btn-primary">Submit</button>
										</div>
									</div>
								
								</form>
							</div>
							<div class="card-body">
								<table id="datatables-buttons" class="table table-striped" style="width:100%">
									<thead>
										<tr>
											<th>Day</th>
											<th>Total Outflow</th>
										</tr>
									</thead>
									<tbody id="ExpensesSummaryJS">
										<?php
										foreach ($expenses_summary as $expense):
										?>
										<tr>
											<td><?=$expense['expense_date']?></td>
											<td><?=$expense['outflow']?></td>								
										</tr>	
										<?php	                    	
											endforeach;
										?>
									</tbody>
								</table>
							</div>
						</div> -->
					</div>
					<div class="col-md-6">
						<!-- <div class="card">
							<div class="card-header">
								<div class="card-title">Top 5 Expenses</div>
								<form method="GET" action="#" id="GetTopExpenses">
									<div class="row">
										<div class="form-group col-md-1">
											<label class="form-label">From</label>
										</div>
										<div class="form-group col-md-3">
											<input class="form-control" type="date" name="from_date">
										</div>
										<div class="form-group col-md-1">
											<label class="form-label">To</label>
										</div>
										<div class="form-group col-md-3">
											<input class="form-control" type="date" name="to_date"/>
										</div>
										<div class="form-group col-md-3">
											<button type="submit" class="btn btn-primary">Submit</button>
										</div>
										</div>
										
								</form>
							</div>
							<div class="card-body">
								<div class="chart chart-lg">
									<canvas id="chartjs-dashboard-bar3"></canvas>
								</div>					
							</div>
						</div> -->
					</div>
					<!-- <div class="col-md-12">
						<div class="card flex-fill w-100">
							<div class="card-header" style="border-bottom-width: 0px;">
							    <span class="badge badge-primary float-right">Fortnightly</span>
								<h5 class="card-title mb-0">Expense Summary</h5>							
							</div>
							<div class="card-body">
            					<div class="chart chart-lg">
										<canvas id="chartjs-line-revenue"></canvas>
								</div>			 
							</div>
						</div>
					</div>
				</div> -->
				<!--  -->
			</div>			
		</main>	
<?php
	$this->load->view('cashier/cashier_footer_view');
?>
 <script type="text/javascript">
        $("input[name=\"from_date\"]").daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            autoUpdateInput: false,
            locale: {
                format: 'YYYY-MM-DD'
            }
        });
        $("input[name=\"to_date\"]").daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            autoUpdateInput: false,
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
		
		$(document).ajaxStart(function() {
      $("#load_screen").show();
    });

    $(document).ajaxStop(function() {
      $("#load_screen").hide();
    });

    $(".datatables-basic").DataTable({
			responsive: true
		});
	  	
  	$("#AddDailyExpenses").validate({
	  	errorElement: "div",
	    rules: {
	      "expense_type" : {
          required : true	
        },
        "item_name" : {
          required : true,
          maxlength : 50
        },
        "total_amt" : {
          required : true,
  				digits : true
        },
       
        "payment_mode" : {
          required : true
        },    
        "expense_status" : {
          required : true
        },
        "employee_name" : {
        	required : true,
        	maxlength : 100
        }
	    },
	    submitHandler: function(form) {
				var formData = $("#AddDailyExpenses").serialize(); 
				$.ajax({
	        url: "<?=base_url()?>Cashier/Expenses",
	        data: formData,
	        type: "POST",
	        // crossDomain: true,
					cache: false,
	        // dataType : "json",
	    		success: function(data) {
            if(data.success == 'true'){ 
            	$("#ModalAddExpense").modal('hide');
							// $('#centeredModalSuccess').modal('show').on('shown.bs.modal', function (e) {
							// 	$("#SuccessModalMessage").html("").html(data.message);
							// }).on('hidden.bs.modal', function (e) {
							// 		window.location.reload();
							// });
							toastr["success"](data.message,"", {
							positionClass: "toast-top-right",
							progressBar: "toastr-progress-bar",
							newestOnTop: "toastr-newest-on-top",
							rtl: $("body").attr("dir") === "rtl" || $("html").attr("dir") === "rtl",
							timeOut: 500
						});
						setTimeout(function () { window.location.reload();}, 500);
						// setTimeout(function () { window.location.href = "<?=base_url()?>Cashier/Dashboard"; }, 500);
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
          	$("#ModalAddExpense").modal('hide');
  					$('#centeredModalDanger').modal('show').on('shown.bs.modal', function (e) {
							$("#ErrorModalMessage").html("").html("<p>Error, Try again later!</p>");
						}).on('hidden.bs.modal', function (e) {
								window.location.reload();
						});
          }
				});
			},
		});

		$("#GetExpensesSummary").validate({
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
				var formData = $("#GetExpensesSummary").serialize(); 
				$.ajax({
	        url: "<?=base_url()?>Cashier/ExpensesSummaryRange",
	        data: formData,
	        type: "GET",
	        crossDomain: true,
					cache: false,
	        dataType : "json",
	    		success: function(data) {
						var str = "";
						for(var i=0;i<data.length;i++){
							str += "<tr>";
							str += 	"<td>"+data[i].expense_date+"</td>";
							str += 	"<td>"+data[i].outflow+"</td>";
							str += "</tr>";
						}
						$("#ExpensesSummaryJS").html("").html(str);
				
          },
          error: function(data){
  					$('#centeredModalDanger').modal('show').on('shown.bs.modal', function (e) {
							$("#ErrorModalMessage").html("").html("<p>Error, Try again later!</p>");
						}).on('hidden.bs.modal', function (e) {
								window.location.reload();
						});
          }
				});
			},
		});


		$("#GetTopExpenses").validate({
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
				var formData = $("#GetTopExpenses").serialize(); 
				$.ajax({
	        url: "<?=base_url()?>Cashier/TopExpensesSummaryRange",
	        data: formData,
	        type: "GET",
	        // crossDomain: true,
					cache: false,
	        // dataType : "json",
	    		success: function(data) {
	    			var date_array = [];
	    			var outflow_array = [];
	    			for(var i=0;i<data.length;i++){
	    				date_array.push(data[i].item_name);
	    				outflow_array.push(data[i].outflow);	
	    			}
						new Chart(document.getElementById("chartjs-dashboard-bar3"), {
							type: "bar",
							data: {
								labels:date_array,
								datasets: [{
									label: "Expenses (In Rupees)",
									backgroundColor: window.theme.warning,
									borderColor: window.theme.primary,
									hoverBackgroundColor: window.theme.primary,
									hoverBorderColor: window.theme.primary,
									data: outflow_array
								}]
							},
							options: {
								maintainAspectRatio: false,
								legend: {
									display: true
								},
								scales: {
									yAxes: [{
										gridLines: {
											display: false
										},
										stacked: false,
										ticks: {
											stepSize: 500
										}
									}],
									xAxes: [{
										barPercentage: .75,
										categoryPercentage: .5,
										stacked: false,
										gridLines: {
											color: "transparent"
										}
									}]
								}
							}
						});
          },
          error: function(data){
  					$('#centeredModalDanger').modal('show').on('shown.bs.modal', function (e) {
							$("#ErrorModalMessage").html("").html("<p>Error, Try again later!</p>");
						}).on('hidden.bs.modal', function (e) {
								window.location.reload();
						});
          }
				});
			},
		});
  });
</script>

<script type="text/javascript">
           $("#payment_type").on('change', function(e) {
            $('#payment_to').attr("hidden", false);

            var employees = <?= json_encode($employees) ?>;
            var vendors = <?= json_encode($vendors) ?>;
            if ($('#payment_type').val() == 'vendor') {
                $('#payment_to').empty();
                $("#payment_to_text").attr('hidden', true);
                for (var i = 0; i < vendors.length; i++) {
                    $("#AddDailyExpenses select[name=payment_to]").append("<option value=" + vendors[i].vendor_id + ">" + vendors[i].vendor_name + "</option>")
                      
                }
            } else if ($('#payment_type').val() == 'employee') {
                $('#payment_to').empty();
                $("#payment_to_text").attr('hidden', true);
                for (var i = 0; i < employees.length; i++) {

                    $("#AddDailyExpenses select[name=payment_to]").append("<option value=" + employees[i].employee_id +  ">" + employees[i].employee_first_name + ' ' + employees[i]
                        .employee_last_name + "</option>")
                       
                        
                }
            } else {
                $('#payment_to').empty();
                $("#payment_to").attr('hidden', true);
                $("#payment_to_text").removeAttr('hidden');
                //   var temp = "<input type='text' name='payment_to'>";
                //   $("#AddDailyExpenses select[name=payment_to]").append(temp);
            }

             });
		</script>
		<script type="text/javascript">
        $("#expense_status").on('change', function(e) {
            var exp_status = document.getElementById('expense_status').value;
            // alert(exp_status);
            if (exp_status == 'Partialy Paid') {
                $("#pend_amt").removeAttr('hidden');
            }
        });
		</script>
		<script type="text/javascript">
            $("#addamt").on('keyup', function(e) {
                var amt = document.getElementById('addamt').value;
                var total_amt = document.getElementById('addtotalpay').value;
                    $("#pend_amt").removeAttr('hidden');
                    var pend = total_amt - amt;
                    document.getElementById('addpendamt').value= pend;
                    if(document.getElementById('addpendamt').value > 0)
                    {
                        document.getElementById("expense_status").options[3].selected = true;
                    }
                    else if(document.getElementById('addpendamt').value == 0)
                    {
                        document.getElementById("expense_status").options[0].selected = true;
                    }
                   if(document.getElementById("addamt").value == 0)
                    {
                        document.getElementById("expense_status").options[2].selected = true;
                    }
                    else if(document.getElementById("addamt").value == total_amt){
                        document.getElementById("expense_status").options[0].selected = true;
                    }
            });
		
			$(function() {
			// Line chart
			revenue_weekly=	new Chart(document.getElementById("chartjs-line-revenue"), {
				type: "line",
				data: {
					labels: labels_revenue,
					datasets: [{
						label: "Revenue (Rs.)",
						fill: true,
						backgroundColor: "transparent",
						borderColor: window.theme.primary,
						data: amt
					}
					, {
						label: "Package Revenue(Rs.)",
						fill: true,
						backgroundColor: "transparent",
						borderColor: window.theme.tertiary,
						borderDash: [4, 4],
						data: amt2
					}
					]
				},
				options: {
					maintainAspectRatio: false,
					legend: {
						display: false
					},
					tooltips: {
						intersect: false
					},
					hover: {
						intersect: true
					},
					plugins: {
						filler: {
							propagate: false
						}
					},
					scales: {
						xAxes: [{
							reverse: true,
							gridLines: {
								color: "rgba(0,0,0,0.05)"
							}
						}],
						yAxes: [{
							ticks: {
								stepSize: 5000
							},
							display: true,
							borderDash: [5, 5],
							gridLines: {
								color: "rgba(0,0,0,0)",
								fontColor: "#fff"
							}
						}]
					}
				}
			});
		});
		</script>
		<script type="text/javascript">
            $(document).on('click',"#download",function(event){
				event.preventDefault();
				this.blur();
				var group = document.getElementById('expense_group').value;
                    // alert(group);
                    if(group == 'range'){
                        var from_date = document.getElementById('daterange').value;
                        // var to_date = document.getElementById('to_date').value;
						// alert(from_date);
                        var parameters = {
                            group : group,
                            from_date :from_date,
                            
                        };
                    }
                    else{
                        var parameters = {
                            group : group
                        };
                    }
				    $.getJSON("<?=base_url()?>Cashier/ExpenseReport",parameters)
					.done(function(data, textStatus, jqXHR) {
                        // alert(data.result); 
						JSONToCSVConvertor(data.result,"Overall Expense Report", true);
					})
					.fail(function(jqXHR, textStatus, errorThrown) {
						console.log(errorThrown.toString());
					});
  				});
			

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
        $("#expense_group").on('change', function(e) {
            var exp_group = document.getElementById('expense_group').value;
            // alert(exp_group);
            if (exp_group == 'range') {
                $("#hide").removeAttr('hidden');
				$("#altrdiv").attr('hidden', true); 
                $("#to_date").removeAttr('hidden');
            }
            else{
                $("#hide").attr('hidden', true); 
                $("#to_date").attr('hidden', true);
            }
        });
</script>
<script>
		$(function(){
			// Daterangepicker
			$("input[name=\"daterange\"]").daterangepicker({
				opens: "left",
				locale: {
                format: 'YYYY/MM/DD'
            }
			});
			
		});

		$("input[name=\"entry_date\"]").daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
           
            locale: {
                format: 'YYYY-MM-DD'
            }
        });
</script>
<script type="text/javascript">
<?php if($modal == 1) { ?>
			$(document).ready(function(){        
				$('#AddExpense').click();
			}); 
<?php } ?>			
</script>
