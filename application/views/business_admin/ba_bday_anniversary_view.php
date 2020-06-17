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
				<h1 class="h3 mb-3"></h1>
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
                    <ul class="nav nav-pills card-header-pills pull-right" role="tablist" style="font-weight: bolder;">
                        <li class="nav-item">
                            <a class="nav-link active" data-toggle="tab" href="#tab-1">Birth Day's</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#tab-2">Anniversary</a>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content">
                        <!-- </div> -->
                        <div class="tab-pane fade show active" id="tab-1" role="tabpanel">
                            <div class="card-header">
                                
                                <form class="row" action="#" method="POST" >
                                    <div class="col-md-4">
                                        <h5 style="background:#2c7be5;width:100px;color:white;height:20px;text-align:center;padding-top:2px;float:right">
                                            Select Month</h5>
                                    </div>
                                   <div class="col-md-2">
                                        <select name="month_name" id="month" class="form-control" style="width:100px;">
                                            <option selected disabled>Month</option>
                                            <option value="01">January</option>
                                            <option value="02">February</option>
                                            <option value="03">March</option>
                                            <option value="04">April</option>
                                            <option value="05">May</option>
                                            <option value="06">June</option>
                                            <option value="07">July</option>
                                            <option value="08">August</option>
                                            <option value="09">September</option>
                                            <option value="10">October</option>
                                            <option value="11">November</option>
                                            <option value="12">December</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <button type="submit" id="submitbtn"
                                            class="btn btn-primary submitbtn" onclick="enableButton()">Submit</button>
                                    </div>
                                    <div class="col-md-2"> 
                                        <button class="btn btn-primary mb-2" id="button" style="float:right" onclick="exportTableToExcel('bday','Birthday')" disabled><i class="fa fa-file-export"></i>Export</button>
                                    </div>
                                </form>
                                <!-- </div>									 -->
                            </div>
                            <table class="table table-hover table-striped datatables-basic" id="bday"
                                style="width: 100%;">
                                <thead>

                                </thead>
                                <tbody>

                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="tab-pane fade" id="tab-2" role="tabpanel">
                            <div class="tab-pane fade show active" id="tab-2" role="tabpanel">
                                <div class="card-header">
                                    <!-- <div class="row"> -->
                                    <form class="row" action="#" method="POST" >
                                    <div class="col-md-4">
                                        <h5 style="background:#2c7be5;width:100px;color:white;height:20px;text-align:center;padding-top:2px;float:right">
                                            Select Month</h5>
                                    </div>
                                   <div class="col-md-2">
                                        <select name="month_name" id="month2" class="form-control" style="width:100px;">
                                            <option selected disabled>Month</option>
                                            <option value="01">January</option>
                                            <option value="02">February</option>
                                            <option value="03">March</option>
                                            <option value="04">April</option>
                                            <option value="05">May</option>
                                            <option value="06">June</option>
                                            <option value="07">July</option>
                                            <option value="08">August</option>
                                            <option value="09">September</option>
                                            <option value="10">October</option>
                                            <option value="11">November</option>
                                            <option value="12">December</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <button type="submit"
                                            class="btn btn-primary anniversary" onclick="enableButton1()">Submit</button>
                                    </div>
                                    <div class="col-md-2"> 
                                        <button class="btn btn-primary mb-2" id="button1" style="float:right" onclick="exportTableToExcel('anniversary','Anniversary')" disabled><i class="fa fa-file-export"></i>Export</button>
                                    </div>
                                </form>
                                    <!-- </div>									 -->
                                </div>
                                <table class="table table-hover table-striped datatables-basic" id="anniversary"
                                style="width: 100%;">
                                <thead>

                                </thead>
                                <tbody>
                                    <tr>
                                      
                                    </tr>
                                </tbody>
                            </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="modal" id="ModalCustomerDetails" tabindex="-1" role="dialog" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered modal-md" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title text-white">Customer Details</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true" class="text-white">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <form id="EditCustomerDetails" method="POST" action="#">
                                                        <div class="smartwizard-arrows-primary wizard wizard-primary">
                                                            <ul>
                                                               
                                                            </ul>
                                                            <div>
                                                                <div id="arrows-primary-step-1" class="">
                                                                    <div class="form-row">
                                                                        <div class="form-group col-md-4">
                                                                            <label>Title</label>
                                                                            <select class="form-control" name="customer_title" disabled>
                                                                            <option value="Mr.">Mr.</option>
																			<option value="Ms.">Ms.</option>
                                                                            </select>
                                                                        </div>
                                                                        <div class="form-group col-md-4">
                                                                            <label>Name</label>
                                                                            <input type="text" class="form-control"
                                                                                name="customer_name" disabled>
                                                                        </div>
                                                                        <div class="form-group col-md-4">
                                                                            <label>Mobile</label>
                                                                            <input type="text" class="form-control"
                                                                                placeholder="Mobile Number" data-mask="0000000000"
                                                                                maxlength="10" minlength="10"
                                                                                name="customer_mobile" disabled>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-row">
                                                                        <div class="form-group col-md-4">
                                                                            <label>Next Appointment Date</label>
                                                                            <input type="text" class="form-control date"
                                                                                name="next_appointment_date" disabled>
                                                                        </div>
                                                                        <div class="form-group col-md-4">
                                                                            <label>Date of Birth</label>
                                                                            <input type="text" class="form-control date"
                                                                                name="customer_dob" disabled>
                                                                        </div>
                                                                        <div class="form-group col-md-4">
                                                                            <label>Date of Anniversary</label>
                                                                            <input type="text" class="form-control date"
                                                                                placeholder="Date of Addition" name="customer_doa" disabled>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-row">
                                                                        <div class="form-group col-md-4">
                                                                            <label>Total Billing</label>
                                                                            <input type="number" class="form-control"
                                                                                name="total_billing" disabled>
                                                                        </div>
                                                                        <div class="form-group col-md-4">
                                                                            <label>Average Order Value</label>
                                                                            <input type="text" class="form-control" name="avg_value"
                                                                                disabled>
                                                                        </div>
                                                                        <div class="form-group col-md-4">
                                                                            <label>Last visit order value</label>
                                                                            <input type="text" class="form-control"
                                                                                name="last_order_value" disabled>
                                                                        </div>


                                                                    </div>
                                                                    <div class="form-row">
                                                                        <div class="form-group col-md-4">
                                                                            <label>Pending Amount</label>
                                                                            <input type="Number" class="form-control"
                                                                                name="customer_pending_amount" readonly>
                                                                        </div>
                                                                        <div class="form-gorup col-md-4">
                                                                            <label>Virtual Wallet Balance(Rs.)</label>
                                                                            <input type="number" class="form-control"
                                                                                placeholder="Virtual Wallet"
                                                                                name="customer_virtual_wallet" readonly>
                                                                        </div>
                                                                        <div class="form-group col-md-4">
                                                                            <label>Wallet Expiry Date</label>
                                                                            <input type="text" class="form-control"
                                                                                placeholder="Wallet money expiry date"
                                                                                name="customer_wallet_expiry_date" readonly>
                                                                        </div>


                                                                    </div>
                                                                    <div class="form-row">
                                                                        <div class="form-group col-md-4">
                                                                            <label>Total Visit</label>
                                                                            <input type="text" class="form-control"
                                                                                name="total_visit" disabled>
                                                                        </div>

                                                                        <div class="form-group col-md-4">
                                                                            <label>Last Visit Date</label>
                                                                            <input type="text" class="form-control date"
                                                                                name="last_visit" disabled>
                                                                        </div>
                                                                        <div class="form-group col-md-4" readonly>
                                                                            <label>Customer Segment</label>
                                                                            <input type="text" name="customer_segment" readonly
                                                                                class="form-control">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                               
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                         </div>
                    </div>
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

<script type="text/javascript">
        
    $(document).ready(function() {
        $(document).on('click', '.submitbtn', function(event) {
            event.preventDefault();
            this.blur(); // Manually remove focus from clicked link.
            var month = document.getElementById("month").value;
            // alert(month);
            var parameters = {
                month
            };
            $.getJSON("<?=base_url()?>index.php/BusinessAdmin/GetCustomerHistory/", parameters)
                .done(function(data, textStatus, jqXHR) {
                    var temp_str =
                        "<tr><th>Sr.No</th><th>Name</th><th>Mobile No</th><th>Birthday</th><th>Lifetime Billing</th><th>Last Visit Date</th><th>Configure Message</th></tr>";
                        if(data.success == 'true'){ 
                            for (var i = 0; i < data.result.length; i++) {
                                temp_str += "<tr>";
                                temp_str += "<td>" + (i + 1) + "</td>";
                                temp_str += "<td>" + data.result[i].txn_customer_name + "</td>";
                                temp_str += "<td><a class='EditViewCustomer' cust_id="+data.result[i].customer_id+">" +
                                    data.result[i].txn_customer_number + "</a></td>";
                                temp_str += "<td>" + data.result[i].customer_dob + "</td>";
                                temp_str += "<td>" + data.result[i].amt + "</td>";
                                temp_str += "<td>" + data.result[i].last_visit + "</td>";
                                temp_str += "<td><button type='submit' class='btn btn-primary SendMessage' option='bday' cust_name="+data.result[i].txn_customer_name+" cust_no="+data.result[i].txn_customer_number+">Send</button></td>";
                                temp_str += "</tr>";
                            }
                            $("#bday").html("").html(temp_str);
                        }else if(data.success == 'false'){
                                toastr["success"](data.message,"", {
								positionClass: "toast-top-right",
								progressBar: "toastr-progress-bar",
								newestOnTop: "toastr-newest-on-top",
								rtl: $("body").attr("dir") === "rtl" || $("html").attr("dir") === "rtl",
								timeOut: 2000
							});
							setTimeout(function () { location.reload(1); }, 2000);
					}
                    // $("#ModalCustomerDetails").modal('show');
                })
                .fail(function(jqXHR, textStatus, errorThrown) {
                    console.log(errorThrown.toString());
                });
        });
    });
    $(document).ready(function() {
        $(document).on('click', '.anniversary', function(event) {
            event.preventDefault();
            this.blur(); // Manually remove focus from clicked link.
            var month = document.getElementById("month2").value;
            // alert("fdhj");
            var parameters = {
                month
            };
            $.getJSON("<?=base_url()?>index.php/BusinessAdmin/GetCustomerHistoryAnniversary/", parameters)
                .done(function(data, textStatus, jqXHR) {
                    // alert(data);
                    if(data.success == 'true'){
                        var temp_str ="<tr><th>Sr.No</th><th>Name</th><th>Mobile No</th><th>Anniversary</th><th>Lifetime Billing</th><th>Last Visit Date</th><th>Configure Message</th></tr>";
                        for (var i = 0; i < data.result.length; i++) {
                            temp_str += "<tr>";
                            temp_str += "<td>" + (i + 1) + "</td>";
                            temp_str += "<td>" + data.result[i].txn_customer_name + "</td>";
                            temp_str += "<td><a class='EditViewCustomer' cust_id="+data.result[i].customer_id+">" +
                                data.result[i].txn_customer_number + "</a></td>";
                            temp_str += "<td>" + data.result[i].customer_doa + "</td>";
                            temp_str += "<td>" + data.result[i].amt + "</td>";
                            temp_str += "<td>" + data.result[i].last_visit + "</td>";
                            temp_str += "<td><button type='submit' class='btn btn-primary SendMessage' option='Anniversary' cust_name="+data.result[i].txn_customer_name+" cust_no="+data.result[i].txn_customer_number+">Send</button></td>";
                            temp_str += "</tr>";
                        }
                        $("#anniversary").html("").html(temp_str);
                        // $("#ModalCustomerDetails").modal('show');
                    }
                    else if(data.success == 'false'){   
							    toastr["success"](data.message,"", {
								positionClass: "toast-top-right",
								progressBar: "toastr-progress-bar",
								newestOnTop: "toastr-newest-on-top",
								rtl: $("body").attr("dir") === "rtl" || $("html").attr("dir") === "rtl",
								timeOut: 2000
							});
							setTimeout(function () { location.reload(1); }, 2000);
						}
                })
                .fail(function(jqXHR, textStatus, errorThrown) {
                    // console.log(errorThrown.toString());
                    toastr["success"](data.message,"", {
								positionClass: "toast-top-right",
								progressBar: "toastr-progress-bar",
								newestOnTop: "toastr-newest-on-top",
								rtl: $("body").attr("dir") === "rtl" || $("html").attr("dir") === "rtl",
								timeOut: 2000
							});
							setTimeout(function () { location.reload(1); }, 2000);
                });
        });
    });
    $(document).on('click', ".EditViewCustomer", function(event) {
        event.preventDefault();
        $(this).blur();
        //   alert($(this).attr('cust_id'));
            var parameters = {
            customer_id: $(this).attr('cust_id')
        };
        $.getJSON("<?=base_url()?>index.php/BusinessAdmin/GetCustomer/", parameters)
            .done(function(data, textStatus, jqXHR) {
                // alert(data);
                $("#EditCustomerDetails select[name=customer_title]").val(data.customer_title);
                $("#EditCustomerDetails input[name=customer_name]").attr('value', data.customer_name);
                $("#EditCustomerDetails input[name=customer_mobile]").attr('value', data.customer_mobile);
                $("#EditCustomerDetails input[name=customer_doa]").attr('value', moment(data.customer_doa)
                    .format('DD-MM-YYYY'));
                $("#EditCustomerDetails input[name=customer_dob]").attr('value', moment(data.customerdob)
                    .format('DD-MM-YYYY'));
                $("#EditCustomerDetails input[name=customer_pending_amount]").attr('value', data
                    .customer_pending_amount);
                $("#EditCustomerDetails input[name=customer_virtual_wallet]").attr('value', data
                    .customer_virtual_wallet);
                $("#EditCustomerDetails input[name=customer_wallet_expiry_date]").attr('value', data
                    .customer_wallet_expiry_date);
                $("#EditCustomerDetails input[name=customer_segment]").val(data.customer_segment);
                $("#EditCustomerDetails input[name=customer_id]").attr('value', data.customer_id);
                $("#EditCustomerDetails input[name=next_appointment_date]").attr('value', data
                    .customer_next_appointment_date);
                $("#EditCustomerDetails input[name=total_billing]").val(data.customer_transaction[0].total);
                $("#EditCustomerDetails input[name=total_visit]").val(data.customer_transaction[0].total_visit);
                $("#EditCustomerDetails input[name=avg_value]").val(data.customer_transaction[0].avg_value);
                $("#EditCustomerDetails input[name=last_visit]").val(data.customer_transaction[0].last_visit);


                var temp_str = "";

                for (var i = 0; i < data.transactions.length; i++) {
                    temp_str += "<tr>";
                    temp_str += "<td>" + (i + 1) + "</td>";
                    temp_str += "<td>" + data.transactions[i].txn_value + "/-</td>";
                    temp_str += "<td>" + data.transactions[i].txn_discount + "/-</td>";
                    $("#EditCustomerDetails input[name=last_order_value]").val(data.transactions[0].txn_value);
                    temp_str += "<td>" + data.transactions[i].BillDate + "</td>";
                    temp_str += "</tr>";
                    $("#EditCustomerDetails input[name=last_order_value]").val(data.transactions[0].txn_value);
                }
                $("#FillTxnDetails").html("").html(temp_str);

                $("#ModalCustomerDetails").modal('show');
            })
            .fail(function(jqXHR, textStatus, errorThrown) {
                console.log(errorThrown.toString());
            });
    });
    $(document).on('click', ".SendMessage", function(event) {
        event.preventDefault();
        $(this).blur();
        //   alert($(this).attr('option'));

            var parameters = {
            customer_number: $(this).attr('cust_no'),
            customer_name:$(this).attr('cust_name'),
            option:$(this).attr('option')
        };
        $.getJSON("<?=base_url()?>index.php/BusinessAdmin/SendSms/", parameters)
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
        function enableButton() {
			
            document.getElementById("button").disabled = false;
        }
        function enableButton1() {
			
            document.getElementById("button1").disabled = false;
        }
    </script>