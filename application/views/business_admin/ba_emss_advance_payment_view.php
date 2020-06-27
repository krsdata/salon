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
				<h1 class="h3 mb-3">Advance Payment</h1>
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
								<!-- <h5 class="card-title">Salary</h5> -->
							</div>
							<div class="card-body">
                                <form class="" id="form1" method="POST" action="#" style="padding:8px;text-align:center">
                                    <div class="form-row">
                                        <div class="form-group col-md-3">
                                            <label>Select Employee</label>
                                        </div>
                                        <div class="form-group col-md-3">
                                                                <select name="expert_id" id="expert_id" class="form-control" required> 
																		<option value=""  selected="true" disabled="disabled">Select Employee</option>
																				<?php
																				
																				foreach ($emp as $expert){
																					
																				?>
																			<option value="<?=$expert['employee_id']?>"><?=$expert['employee_first_name'],'  ',$expert['employee_last_name'];?></option>
																			<?php	
																					
																				}
																			?>
																</select>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-3">
                                            <label>Select Date</label>
                                        </div>
                                        <div class="form-group col-md-3">
                                            <input type="date" class="form-control" id="date" name="month" required>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-3">
                                            <label>Select Amount</label>
                                        </div>
                                        <div class="form-group col-md-3">
                                            <input type="text" class="form-control" id="amount" name="amount" required>
										</div>
										
									</div>
									<div class="form-row">
												<div class="form-group col-md-3">
													<label>Payment Mode</label>
												</div>
												<div class="form-group col-md-3">	
													<select class="form-control" name="payment_mode" id="payment_mode">
														<option value="Cash" selected>Cash</option>
														<option value="Card">Card</option>
														<option value="Wallet">Wallet</option>
													<select>
                                       			 </div>
									</div>
                                    <div class="form-row">
                                        <div class="form-group col-md-3">
                                            <label>Reason</label>
										</div>
										
                                        <div class="form-group col-md-3">
                                            <textarea cols="5" rows="3" class="form-control" id="reason" name="reason"></textarea>
										</div>
										
                                    </div>
                                    <div class="form-row">
                                      <div class="form-group col-md-6" >
                                      <button type="submit" class="btn btn-success InsertData" style="float: right">Submit</button>
                                        </div>
                                    
                                    </div>
                                </form>    
							</div>
							</div>
							<br><br>
							<div>
							<!-- cart for salary details -->
							<div class="card-body">
													<!-- <div class="form-row"> -->
								<div class="card-header">
								<button type="submit" class="btn btn-primary GetDataAttendance" style="float:right" onclick="exportTableToExcel('FillTxnDetails','Advance_Payment')">Download</button>
								</div>			
                                                        
													<table	class="table table-striped datatables-basic" id="FillTxnDetails" width="100%" style="text-align: center;font-weight:bold">
														<thead>
														<tr>
															<th><label>Date</label></th>
															<th><label>Employee Id</label></th>
															<th><label>Employee Name</label></th>
															<th><label>Advance</label></th>
															<th><label>Payment Mode</label></th>
															<th><label>Reason</label></th>
															<!-- <th><label>Commission</label></th>
															<th><label>Payout</label></th> -->
															<!-- <th><label>Commission</label></th> -->
														</tr>
														</thead>
														<tbody>
														<?php
															
                                                            foreach($salary_details as $row)
                                                            {	
															?>
																<tr>
																
																	<td><?php 
																		$month=strtotime($row['month']);
																		echo date('d-M-Y',$month);?></td>
																	<td><?=$row['employee_id'];?></td>
																	<td><?=$row['employee_first_name'].' '.$row['employee_last_name'];?></td>
																	<td><?=$row['amount'];?></td>
																	<td><?=$row['payment_mode'];?></td>
																	<td><?=$row['reason'];?></td>
																	
																</tr>
															<?php
                                                            }
														?>
                                						</tbody>
                                					</table>
								</div>

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
<script>
	 $(document).ready(function(){
				

				//Amrk Attendance
                $(document).on('click','.InsertData',function(event) {
			event.preventDefault();
			this.blur(); // Manually remove focus from clicked link.
			// var emp_id=$(this).attr('employee_id');
			
			var emp_id=document.getElementById("expert_id").value;
            // alert(emp_id);
			var date=document.getElementById("date").value;
            var amount=document.getElementById("amount").value;
			var payment_mode=document.getElementById("payment_mode").value;
            var reason=document.getElementById("reason").value;

		
				
					
				$.ajax({
					url: "<?=base_url()?>BusinessAdmin/AdvancePayment",
					data: {emp_id:emp_id,date:date,amount:amount,reason:reason,payment_mode},
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
								timeOut: 2000
							});
							setTimeout(function () { location.reload(1); }, 2000);
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
			
		});



});
</script>
<script type="text/javascript">
$(document).ready(function(){

	$(".datatables-basic").DataTable({
      responsive: true
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