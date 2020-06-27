<?php
	$this->load->view('business_admin/ba_header_view');
?>
<div class="wrapper">
	<?php
		$this->load->view('business_admin/ba_nav_view');
	?>
	<div class="main" onLoad=" LoadOnce()">
		<?php
			$this->load->view('business_admin/ba_top_nav_view');
		?>
		<main class="content">
			<div class="container-fluid p-0">
				<h1 class="h3 mb-3">Loyalty Management</h1>
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
								<h5 class="card-title">Existing Conversion Ratio</h5>
							</div>
							<div class="card-body">
							<table class="table table-hover" style="width: 100%;">
									<thead>
										<tr>
											<th>Amount(Rs.)</th>
											<th>Cashback</th>
											<th>Validity</th>
											<th>Actions</th>

										</tr>
									</thead>
									<tbody>
										<?php 
										foreach($loyalty as $loyalty){
										?>
										<tr>
											<td><?=$loyalty['loyalty_conversion']?></td>
											<td><?=$loyalty['loyalty_rate']?></td>
											<td><?=$loyalty['validity']?></td>
											<td>
											<button type="button" class="btn btn-primary loyalty_conversion_btn" loyalty_id="<?=$loyalty['loyalty_id']?>">
												<i class="align-middle" data-feather="edit-2"></i>
											</button>
											</td>
										</tr>
										<?php
										}
										?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				
					<div class="col-md-12">
						<div class="card">
							<div class="card-header">
								<div class="row">
									<div class="col-md-4">
										<h5 class="card-title">Cashback Customer Data</h5>
									</div>
									<div class="col-md-5">
										<div class="form-group">
											<div class="input-group">
												<input type="text" placeholder="Search Customer by name/contact no." class="form-control" id="SearchCustomer">
												<span class="input-group-append">
													<button class="btn btn-success" type="button" id="SearchCustomerButton" Customer-Id="Nothing" style="padding:0px 0px;">Search</button>
												</span>
											</div>
										</div>
									</div>
									<div class="col-md-3">
										<button class="btn btn-primary float-right" onclick="exportTableToExcel('cust_data','Cashback')"><i class="fa fa-file"></i> Export Data</button>
									</div>
									
								</div>
							</div>
							<div class="card-body">
							<table class="table table-hover datatables-basic" style="width: 100%;" id="cust_data">
									<thead>
										<tr>
											<th>S.No.</th>
											<th>Mobile</th>
											<th>Name</th>
											<th>Total Amount spent</th>
											<th>Cashback Balance</th>
											<!-- <th>Cashback Redeemed</th>
											<th>Balance Cashback</th> -->
											<th>Update date</th>
										</tr>
									</thead>
									<tbody>
										<?php foreach($cust_data as $data){
										?>
										<tr>
											<td></td>
											<td><?=$data['customer_mobile']?></td>
											<td><?=$data['customer_name']?></td>		
											<td><?=$data['total_spent']?></td>										
											<td><?=$data['customer_cashback']?></td>											
											<td><?=$data['last_txn_date']?></td>
										</tr>
										<?php
										}
										?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
					<!-- modal -->
					<div class="modal fade" id="defaultModalSuccess" tabindex="-1" role="dialog" aria-hidden="true">
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
					<div class="modal fade" id="defaultModalDanger" tabindex="-1" role="dialog" aria-hidden="true">
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
					<div class="modal fade" id="ModalEditConversionRatio" tabindex="-1" role="dialog" aria-hidden="true">
						<div class="modal-dialog modal-dialog-centered modal-md" role="document">
							<div class="modal-content">
								<div class="modal-header">
									<h5 class="modal-title text-white">Edit Conversion Ratio</h5>
									<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
									</button>
								</div>
								<div class="modal-body">
									<div class="row">
										<div class="col-md-12">
											<form id="EditConversionRatio" method="POST" action="#">
											<div class="form-row">
												<div class="form-group col-md-4">
													<label>Rs.</label>
													<input type="number" class="form-control" name="conversion_ratio">
												</div>
												<div class="form-group col-md-4">
													<label>Cashback</label>
													<input type="text" class="form-control" name="conversion_rate" onkeypress="return validateFloatKeyPress(this,event);" />
												</div>													
												<div class="form-group col-md-4">
													<label>Validity<small>(In Months)</small></label>
													<input type="number" class="form-control" name="validity" min="0">
												</div>	
											</div>
												<input type="hidden" class="form-control" name="loyalty_id" id="" />
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
					<!-- end -->
					<?php
						}
					?>
				</div>
			</div>
		</main>
<?php
	$this->load->view('business_admin/ba_footer_view');
?>
<script language="JavaScript" >
	function LoadOnce() 
	{ 
	window.location.reload(); 
	} 

</script>
<script>
		$(document).ajaxStart(function() {
      $("#load_screen").show();
		
    });

    $(document).ajaxStop(function() {
      $("#load_screen").hide();
    });

		$(".datatables-basic").DataTable({
			responsive: true,
			searching : false
		});
		//AddConversion Ratio
		$("#conversion").validate({
	  	errorElement: "div",
	    rules: {
	        "rupee" : {
            required : true
	        },
	        "rate" :{
	        	required : true
	        }
	    },
	    submitHandler: function(form) {
				var formData = $("#conversion").serialize(); 
				$.ajax({
		        url: "<?=base_url()?>BusinessAdmin/AddConversionRatio",
		        data: formData,
		        type: "POST",
		        // crossDomain: true,
						cache: false,
		        // dataType : "json",
		    		success: function(data) {
              if(data.success == 'true'){ 
              	// $("#ModalEditRawMaterial").modal('hide');
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
		//edit Conersion ratio
		$(document).on('click','.loyalty_conversion_btn',function(event) {
      event.preventDefault();
      this.blur(); // Manually remove focus from clicked link.
      var parameters = {
        loyalty_id : $(this).attr('loyalty_id')
      };

      $.getJSON("<?=base_url()?>BusinessAdmin/GetConversionRatio", parameters)
      .done(function(data, textStatus, jqXHR) { 
        $("#EditConversionRatio input[name=conversion_ratio]").attr('value',data.loyalty_conversion);
        $("#EditConversionRatio input[name=conversion_rate]").attr('value',data.loyalty_rate);
				$("#EditConversionRatio input[name=validity]").attr('value',data.validity);
				$("#EditConversionRatio input[name=loyalty_id]").attr('value',data.loyalty_id);
        $("#ModalEditConversionRatio").modal('show');
    	})
    	.fail(function(jqXHR, textStatus, errorThrown) {
        console.log(errorThrown.toString());
   		});
    });

		$("#EditConversionRatio").validate({
	  	errorElement: "div",
	    rules: {
	        "conversion_ratio" : {
            required : true
	        },
	        "conversion_rate" :{
	        	required : true
	        }
	    },
	    submitHandler: function(form) {
				var formData = $("#EditConversionRatio").serialize(); 
				$.ajax({
		        url: "<?=base_url()?>BusinessAdmin/UpdateConversionRatio",
		        data: formData,
		        type: "POST",
		        // crossDomain: true,
						cache: false,
		        // dataType : "json",
		    		success: function(data) {
              if(data.success == 'true'){ 
              	$("#ModalEditConversionRatio").modal('hide');
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
          	    if($('.feedback1').hasClass('alert-success')){
                  $('.feedback1').removeClass('alert-success').addClass('alert-danger');
                }
                else{
                  $('.feedback1').addClass('alert-danger');
                }
                $('.alert-message').html("").html(data.message); 
              }
            },
            error: function(data){
    					$('.feedback1').addClass('alert-danger');
    					$('.alert-message').html("").html(data.message); 
            }
				});
			},
		});
		//
		//functionality for getting the dynamic input data
    $("#SearchCustomer").typeahead({
      	autoselect: true,
				highlight: true,
				minLength: 1
			},
			{
				source: SearchCustomer,
				templates: {
					empty: "No Customer Found!",
					suggestion: _.template("<p><%- customer_name %>, <%- customer_mobile %></p>")
				}
    });
       
    var to_fill = "";

    $("#SearchCustomer").on("typeahead:selected", function(eventObject, suggestion, name) {
      var loc = "#SearchCustomer";
      to_fill = suggestion.customer_name+","+suggestion.customer_mobile;
      setVals(loc,to_fill,suggestion.customer_id);
    });

    $("#SearchCustomer").blur(function(){
      $("#SearchCustomer").val(to_fill);
      to_fill = "";
    });

    function SearchCustomer(query, cb){
      var parameters = {
        query : query
      };
			
      $.ajax({
        url: "<?=base_url()?>BusinessAdmin/GetCustomerData",
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

    function setVals(element,fill,customer_id){
      $(element).attr('value',fill);
      $(element).val(fill);
      $("#SearchCustomerButton").attr('Customer-Id',customer_id);
    }

    $(document).on('click',"#SearchCustomerButton",function(event){
    	event.preventDefault();
      this.blur();
			var customer_id = $(this).attr('Customer-Id');
			
      if(customer_id == "Nothing"){
      	$('#centeredModalDanger').modal('show').on('shown.bs.modal', function (e) {
					$("#ErrorModalMessage").html("").html("Please select customer!");
				});
      }
      else{
	      var parameters = {
	        customer_id : $(this).attr('Customer-Id')
	      };
	      
				$("#SearchCustomerButton").attr('Customer-Id',"Nothing");
					$.getJSON("<?=base_url()?>BusinessAdmin/AddCustomerDataInTable", parameters)
					.done(function(data, textStatus, jqXHR) { 
						var str_2 = "";
						
						str_2 += "<tr>";
						str_2 += "<td>1</td>";
						str_2 += "<td>"+data[0].customer_mobile+"</td>";
						str_2 += "<td>"+data[0].customer_name+"</td>";
						str_2 += "<td>"+data[0].total_spent+"</td>";
						str_2 += "<td>"+data[0].customer_rewards+"</td>";						
						str_2 += "<td>"+data[0].last_txn_date+"</td>";
						str_2 += "</tr>";
						$("#cust_data tbody tr").remove();
						$("#cust_data tbody").append(str_2);
					})
					.fail(function(jqXHR, textStatus, errorThrown) {
						console.log(errorThrown.toString());
				});
	    }
  	});
	
</script>
<script type="text/javascript">
	function validateFloatKeyPress(el, evt) {
    var charCode = (evt.which) ? evt.which : event.keyCode;
    var number = el.value.split('.');
    if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    }
    //just one dot
    if(number.length>1 && charCode == 46){
         return false;
    }
    //get the carat position
    var caratPos = getSelectionStart(el);
    var dotPos = el.value.indexOf(".");
    if( caratPos > dotPos && dotPos>-1 && (number[1].length > 1)){
        return false;
    }
    return true;
	}

	//thanks: http://javascript.nwbox.com/cursor_position/
	function getSelectionStart(o) {
		if (o.createTextRange) {
			var r = document.selection.createRange().duplicate()
			r.moveEnd('character', o.value.length)
			if (r.text == '') return o.value.length
			return o.value.lastIndexOf(r.text)
		} else return o.selectionStart
	}
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
<script>
	var input = document.getElementById("SearchCustomer");
	input.addEventListener("keyup", function(event) {
		if (event.keyCode === 13) {
		event.preventDefault();
		document.getElementById("SearchCustomerButton").click();
		}
	});
	
</script>

