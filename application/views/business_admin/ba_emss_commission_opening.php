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
				<!-- <h1 class="h3 mb-3">Commission Management</h1> -->
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
					<div class="col-xl-12">
						<div class="card">
								<div class="card-header">
														<label style="text-align:left;padding:6px;font-weight:bold" class="btn btn-success btn-sm">Existing Commission Model</label>
													
														<label class="btn btn-primary btn-sm"  style="padding: 6px;float:right;"><a href="<?=base_url()?>index.php/BusinessAdmin/SetCommission" class="text-white">New Commission Model</a></label>
														
														
								</div>
									<div class="card-body">
													<!-- <div class="form-row"> -->
													<table	class="table table-striped datatables-basic" width="100%" style="text-align: center;font-weight:bold">
														<thead>
														<tr>
															<th><label>Sr No</label></th>
															<th><label>Commission Month</label></th>
															<th><label>Commission Name</label></th>
															<th><label>Employee ID</label></th>
															<th><label>Employee Name</label></th>
															<th><label>Target</label></th>
															<th><label>Base KPI</label></th>
															<!-- <th><label>View/Edit &nbsp;Delete</label></th> -->
															<th><label>Delete</label></th>
														</tr>
														</thead>
														<tbody>
														<?php
															$i=0;
                                                            foreach($commission_details as $row)
                                                            {	
																
																$month=strtotime($row['months']);
																if($row['base_value']=='TotalSales')
																{
																	$baseValue="Total Sales";
																}
																else if($row['base_value']=='ServiceSales')
																{
																	$baseValue="Service Sales";
																}
																else{
																	$baseValue=$row['base_value'];
																}
																echo "<tr><td>".($i=$i+1)."</td><td>".date('M-Y',$month)."</td><td><input type='button' id='commname' value='".$row['names']."' class='btn btn-default myBtn' comm_name='".$row['names']."' comm_id=".$row['comm_id']."  month_name=".$row['months']." expert_name=".$row['employee_first_name'],' ',$row['employee_last_name']." emp_id=".$row['employee_id']." base_kpi=".$row['base_kpi']." multiplier=".$row['target_multiplier']." target_kpi=".number_format($row['target_base_kpi'])." target=".number_format($row['targets'])." t1=".$row['set_target1']." t2=".$row['set_target2']." comm%=".$row['commision']." base_value=".$row['base_value']."  comm=".number_format($row['comm'],1)." style='border:none'></td><td>".$row['employee_id']."</td><td>".$row['employee_first_name'],' ',$row['employee_last_name']."</td><td>Rs. ".number_format($row['targets'])."</td><td>".$baseValue."</td>";
																
														?>
															<td>
																<!-- <button type="button" class="btn btn-primary edit-btn" comm_id="<?=$row['comm_id']?>" names="<?=$row['names']?>" months="<?=$row['months']?>">
																<i class="align-right" data-feather="edit-2"></i>
																</button> -->
															
																<button type="button" class="btn btn-danger delete_btn" names="<?=$row['names']?>" months="<?=$row['months']?>"  emp_id="<?=$row['employee_id']?>" comm_id="<?=$row['comm_id']?>">
																<!-- <i class="align-middle" data-feather="user-x"></i> -->
																<i class="fas fa-trash"></i>
															</button></td>
														</tr>				
														<?php		
                                                            }
														?>
                                						</tbody>
                                					</table>
								</div>
							</div>
						</div>
						
						
					</div>

<!-- Modal -->
  <div class="modal fade" id="myModal" role="dialog">
    <div role="document" class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
			<h4>Commission Details</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close" class="close">&times;</button>
          
        </div>
        <div class="modal-body m-3">
			<form action="#" method="POST" id="ServiceEditDetails">
				<div class="form-row">
					<table id="FillCommDetail" class="table table-striped" style="text-align:center">
						
					</table>
				</div>
			</form>
        </div>	
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>
	  </div>
  </div>
</div>
  <!--modal end  -->
  <!-- Modal -->
  <div class="modal fade" id="viewModal" role="dialog">
    <div role="document" class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
			<h4>Commission Details</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close" class="close">&times;</button>
          
        </div>
        <div class="modal-body m-3">
			<form action="#" method="POST" id="CommEditDetails">
				<div class="form-row">
					<table id="edit-comm" class="table table-striped" style="text-align:center">
					<div class="form-row">
						<div class="form-group col-md-3">
							<label>Name</label>
							<input type="text" class="form-control" name="name" >
						</div>
						<div class="form-group col-md-3">
							<label>Month</label>
							<input type="text" class="form-control" name="month" >
						</div>																
						<div class="form-group col-md-3">
							<label>Target</label>
							<input type="number" class="form-control" name="target" >
						</div>
						<div class="form-group col-md-3">
							<label>Base Value</label>
							<input type="text" class="form-control" name="basevalue" >
						</div>						
					</div>
					<div class="form-row">
					<div class="form-group col-md-3">
							<label>Range 1</label>
							<input type="number" class="form-control" name="range1" >
						</div>
						<div class="form-group col-md-3">
							<label>Range 2</label>
							<input type="number" class="form-control" name="range2" >
						</div>	
						<div class="form-group col-md-3">
							<label>Comm%</label>
							<input type="text" class="form-control" name="comm" >
						</div>
						<div class="form-group col-md-3">
							<input type="text" class="form-control" name="comm_id" hidden>
						</div>					
					</div>
					</table>
				</div>
			
        </div>	
			<div class="modal-footer">
					<button type="submit" class="btn btn-success">Submit</button>	
					<!-- <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button> -->
			</div>
		</form>
	  </div>
  </div>
</div>
  <!--modal end  -->
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
$(document).ready(function(){

	$(".datatables-basic").DataTable({
      responsive: true
    });
});


</script>

<script type="text/javascript">
$(document).on('click','.edit-btn',function(event) {
      event.preventDefault();
      this.blur(); // Manually remove focus from clicked link.
      comm_id=$(this).attr('comm_id');
	  comm_name=$(this).attr('names');
	  month=$(this).attr('months');
	  var parameters = {
            comm_id,comm_name,month
      };
	
      $.getJSON("<?=base_url()?>index.php/BusinessAdmin/Commission_edit/", parameters)
      .done(function(data, textStatus, jqXHR) { 
				$("#CommEditDetails input[name=name]").val(data.names);
				$("#CommEditDetails input[name=month]").val(data.months);
				$("#CommEditDetails input[name=target]").val(data.targets);
				$("#CommEditDetails input[name=range1]").val(data.set_target1);
				$("#CommEditDetails input[name=range2]").val(data.set_target2);
				$("#CommEditDetails input[name=comm]").val(data.commision);
				$("#CommEditDetails input[name=basevalue]").val(data.base_value);
				$("#CommEditDetails input[name=comm_id]").val(data.comm_id);
				
        $("#viewModal").modal('show');
    	})
    	.fail(function(jqXHR, textStatus, errorThrown) {
        console.log(errorThrown.toString());
   		});
    });


</script>
<script type="text/javascript">
$(document).on('click','.delete_btn',function(event) {
      event.preventDefault();
      this.blur(); // Manually remove focus from clicked link.
	//   alert("gfhkj");
      comm_id=$(this).attr('comm_id');
	  emp_id=$(this).attr('emp_id');
	  month=$(this).attr('months');
	  names=$(this).attr('names');
	  var parameters = {
            comm_id,emp_id,month,names
      };
	
      $.getJSON("<?=base_url()?>index.php/BusinessAdmin/DeleteCommission/", parameters)
      .done(function(data, textStatus, jqXHR) { 
						toastr["success"](data.message,"", {
									positionClass: "toast-top-right",
									progressBar: "toastr-progress-bar",
									newestOnTop: "toastr-newest-on-top",
									rtl: $("body").attr("dir") === "rtl" || $("html").attr("dir") === "rtl",
									timeOut: 1000
								});
								setTimeout(function () { location.reload(1); }, 1000);	
				
       
    	})
    	.fail(function(jqXHR, textStatus, errorThrown) {
        console.log(errorThrown.toString());
   		});
    });


</script>

<script>
 $(document).ready(function(){
	$(document).on('click','.myBtn',function(event) {
			event.preventDefault();
			this.blur(); // Manually remove focus from clicked link.
			// var emp_id=$(this).attr('employee_id');
			
			// exit;
			var comm_name=$(this).attr('comm_name');
// 			var comm_name=document.getElementById('commname').value;
// 			alert(comm_name);
			var month=$(this).attr('month_name');
			var expert_id=$(this).attr('emp_id');
			
			var parameters = { comm_name,month,expert_id};
		
				// alert(comm_name);
			
						$.getJSON("<?=base_url()?>index.php/BusinessAdmin/GetCommisionDetails/", parameters)
	    		  .done(function(data, textStatus, jqXHR) { 
	   	 	  			var temp_str = "<tr><th>Commission Details</th></tr>";
						// temp_str += 	"<tr><td>Employee Name : "+data.employee_first_name,' ',data.employee_last_name+"</td>";
						// temp_str += 	"<td> Commission Name : "+data[0].names+"</td>";
						// temp_str += 	"<td> Month : "+data[0].months+"</td></tr>";
						temp_str = "<tr><th>Target 1</th><th>Target 2</th><th>Commission %</th><th>Base Value</th></tr>";
						for(var i = 0;i<data.length;i++){
							if(data[i].set_target2==0){
                               var a='-';
							}
							else{
								a=data[i].set_target2;
							}
						temp_str += "<tr>";
						// temp_str += 	"<td>"+(i+1)+"</td>";
						temp_str += 	"<td>"+data[i].set_target1+"</td>";
						temp_str += "<td>"+a+"</td>";
						temp_str += 	"<td>"+data[i].commision+"</td>";
						if(data[i].base_value=='TotalSales')
						{
							var b='Total Sales';
						}
						else{
							var	b=data[i].base_value;
						}
						temp_str += 	"<td>"+b+"</td>";
						
						// temp_str += 	"<td>"+data.present+"</td>";
						temp_str += "</tr>";
						
					}
					$("#FillCommDetail").html("").html(temp_str);


	        $("#myModal").modal('show');
			})
			
	    	.fail(function(jqXHR, textStatus, errorThrown) {
	        console.log(errorThrown.toString());
	   		});		
	
		
		});

  });


  $("#CommEditDetails").validate({
	  	errorElement: "div",
	    rules: {
	        "name" : {
            required : true,
            },
	        "month" : {
            required : true,
            },
	        "target" : {
	          required : true
	        },
	        "basevalue" : {
	        	
	        	required : true,
	        },
	        "range1":{
	        	required : true
	        },
	        "range2" : {
	        	required : true
	        },
	        "comm" : {
	        	required : true
	        }
	        
	    },
	    submitHandler: function(form) {
				var formData = $("#CommEditDetails").serialize(); 
				// alert("hii");
				$.ajax({
		        url: "<?=base_url()?>index.php/BusinessAdmin/UpdateCommission/",
		        data: formData,
		        type: "POST",
		        crossDomain: true,
						cache: false,
		        dataType : "json",
		    		success: function(data) {
              if(data.success == 'true'){ 
              	$("#CommEditDetails").modal('hide');
								toastr["success"](data.message,"", {
									positionClass: "toast-top-right",
									progressBar: "toastr-progress-bar",
									newestOnTop: "toastr-newest-on-top",
									rtl: $("body").attr("dir") === "rtl" || $("html").attr("dir") === "rtl",
									timeOut: 1000
								});
								setTimeout(function () { location.reload(1); }, 1000);
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


</script>




