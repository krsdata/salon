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
				<!-- <h1 class="h3 mb-3">Tags</h1> -->
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
								<h2 class="card-title">TAGS</h2>
							</div>
							<div class="card-body">
                               <div class="row">
                                    <div class="col-lg-2 col-lg-2" style="margin-top:20px">
                                       <a href="<?= base_url() ?>BusinessAdmin/SetTags" class="btn btn-primary btn-lg btn-block">Create New Tag</a>
                                   </div> 
                               </div>
                               <div class="row">
                                   
                               </div>
                               <div class="row" style="margin-top:15px;">
                                   <div class="col-md-12">
                                       <table name="tagsdetails" class="table table-hover table-striped" id="tagsdetails">
                                           <thead>
                                               <th>Rule Name</th>
                                               <th>No. Of Rule</th>
                                               <th>Date</th>
                                               <th>Customers</th>
											   <th></th>
											   <th></th>
                                               <th></th>
                                           </thead>
                                           <tbody>
                                               <?php
                                                if($tags !=0 ){
                                                    foreach($tags as $tag){
                                                ?>   <tr> 
                                                    <td><?=$tag['rule_name']?></td>
                                                    <td><?=$tag['no_rules']?></td>
                                                    <td><?=$tag['datetime']?></td>
                                                    <td><?=$tag['customers']?></td>
                                                    <td style="width:30px"><button type="submit" style="float:right;margin-left:0%" class="btn btn-primary Edit" tag_id="<?=$tag['tag_id']?>">Edit</button></td>
                                                    <td style="width:30px"><button type="submit" style="text-align:center" class="btn btn-primary Delete" tag_id="<?=$tag['tag_id']?>">Delete</button></td>
                                                    <td style="width:30px"><button type="submit" style="float:left" class="btn btn-primary Download" tag_id="<?=$tag['tag_id']?>">Download</button></td>
                                                    
                                                    </tr>
                                                <?php
                                                    }    
                                                }
                                               ?>
                                           </tbody>
                                       </table>
                                   </div>
                               </div>
							</div>
							<!-- Modal -->
								<div class="modal fade" id="myModal" role="dialog">
									<div role="document" class="modal-dialog">
									
									<!-- Modal content-->
									<div class="modal-content">
										<div class="modal-header">
											<h4>Tag Details</h4>
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

$("input[name=\"start_range_date\"]").daterangepicker({
	singleDatePicker: true,
	showDropdowns: true,
	autoUpdateInput: false,
	locale: {
		format: 'YYYY-MM-DD'
	}
});
$("input[name=\"end_range_date\"]").daterangepicker({
	singleDatePicker: true,
	showDropdowns: true,
   
	locale: {
		format: 'YYYY-MM-DD'
	}
});
$("input[name=\"date\"]").daterangepicker({
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
     $(document).on('click', ".Delete", function(event) {
        event.preventDefault();
        $(this).blur();
        //   alert($(this).attr('tag_id'));

            var parameters = {
            tag_id: $(this).attr('tag_id'),
            
        };
        $.getJSON("<?=base_url()?>BusinessAdmin/DeleteTag", parameters)
            .done(function(data, textStatus, jqXHR) {
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
							toastr["error"](data.message,"", {
								positionClass: "toast-top-right",
								progressBar: "toastr-progress-bar",
								newestOnTop: "toastr-newest-on-top",
								rtl: $("body").attr("dir") === "rtl" || $("html").attr("dir") === "rtl",
								timeOut: 2000
							});
							setTimeout(function () { location.reload(1); }, 2000);
						}
                
                $("#FillTxnDetails").html("").html(temp_str);

                $("#ModalCustomerDetails").modal('show');
            })
            .fail(function(jqXHR, textStatus, errorThrown) {
                console.log(errorThrown.toString());
            });
    });
</script>
<script type="text/javascript">
     $(document).on('click', ".Edit", function(event) {
        event.preventDefault();
        $(this).blur();
        //   alert($(this).attr('tag_id'));

            var parameters = {
            tag_id: $(this).attr('tag_id'),
            
        };
        $.getJSON("<?=base_url()?>BusinessAdmin/EditTag", parameters)
            .done(function(data, textStatus, jqXHR) {
               
									var temp_str = "";
									
									for(var i = 0;i < data.result.length;i++){
										if(data.result[i].kpi == 'total_amt_spent' || data.result[i].kpi == 'visits' || data.result[i].kpi == 'points' ||data.result[i].kpi == 'age'){
											temp_str +="<tr><td>"+data.result[i].rule_name+"</td>";
											temp_str +="<td>"+data.result[i].kpi+"</td>";
											temp_str +="<td>"+data.result[i].start_range+"</td>";
											temp_str +="<td>"+data.result[i].end_range+"</td></tr>";
											
										}else if(data.result[i].kpi == 'last_visit_date'){
											temp_str +="<tr><td>"+data.result[i].rule_name+"</td>";
											temp_str +="<td>"+data.result[i].kpi+"</td>";
											temp_str +="<td>"+data.result[i].date+"</td></tr>";
										}else if(data.result[i].kpi == 'billing_date' || data.result[i].kpi == 'birthday' || data.result[i].kpi == 'anniversary' ){
											temp_str +="<tr><td>"+data.result[i].rule_name+"</td>";
											temp_str +="<td>"+data.result[i].kpi+"</td>";
											temp_str +="<td>"+data.result[i].start_range_date+"</td>";
											temp_str +="<td>"+data.result[i].end_range_date+"</td></tr>";
										}else if(data.result[i].kpi == 'gender'){
											temp_str +="<tr><td>"+data.result[i].rule_name+"</td>";
											temp_str +="<td>"+data.result[i].kpi+"</td>";
											temp_str +="<td>"+data.result[i].select_option+"</td></tr>";
										}else if(data.result[i].kpi == 'avg_order'){
											temp_str +="<tr><td>"+data.result[i].rule_name+"</td>";
											temp_str +="<td>"+data.result[i].kpi+"</td>";
											temp_str +="<td>"+data.result[i].value+"</td></tr>";
										}
										else if(data.result[i].kpi == 'package_subscriber'){
											temp_str +="<tr><td>"+data.result[i].rule_name+"</td></tr>";
											temp_str +="<td>"+data.result[i].kpi+"</td>";
											// temp_str +="<td>"+data.result[i].value+"</td>";
										}else if(data.result[i].kpi == 'package_expiry'){
											temp_str +="<tr><td>"+data.result[i].rule_name+"</td>";
											temp_str +="<td>"+data.result[i].kpi+"</td>";
											temp_str +="<td>"+data.result[i].value+"</td></tr>";
										}
									}
							$("#FillCommDetail").html("").html(temp_str);
							$("#myModal").modal('show');
						
            })
            .fail(function(jqXHR, textStatus, errorThrown) {
                console.log(errorThrown.toString());
            });
    });
</script>
<script type="text/javascript">
     $(document).on('click', ".Download", function(event) {
        event.preventDefault();
        $(this).blur();
        //   alert($(this).attr('tag_id'));

            var parameters = {
            tag_id: $(this).attr('tag_id'),
            
        };
        $.getJSON("<?=base_url()?>BusinessAdmin/DownloadTag", parameters)
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
			// if (ShowLabel) {
			// 	var row = "";
				
			// 	//This loop will extract the label from 1st index of on array
			// 	for (var index in arrData[0]) {
					
			// 		//Now convert each value to string and comma-seprated
			// 		row += index + ',';
			// 	}

			// 	row = row.slice(0, -1);
				
			// 	//append Label row with line break
			// 	CSV += row + '\r\n';
			// }
			
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