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
    <div class="col-md-12">
      <div class="card">
        <div class="card-header" style="margin-left:10px;">
          <ul class="nav nav-pills card-header-pills pull-right" role="tablist" style="font-weight: bolder">
            <li class="nav-item">
              <a class="nav-link active" data-toggle="tab" href="#tab-1">Transaction History</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" data-toggle="tab" href="#tab-2">Preferred Services</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" data-toggle="tab" href="#tab-3">Preferred Products</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" data-toggle="tab" href="#tab-4">Birthday</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" data-toggle="tab" href="#tab-5">Anniversary</a>
            </li>
          </ul>
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <div class="input-group" style="margin-left:0px;margin-top:5px">
              <input type="text" placeholder="Search Customer by name/contact no." class="form-control"
                id="SearchCustomer">
              <span class="input-group-append">
                <button class="btn btn-success" type="button" id="SearchCustomerButton" Customer-No="Nothing"
                  style="padding:0px 0px;">Search</button>
              </span>
            </div>
          </div>
        </div>
        <div class="card-body">
          <div class="tab-content">
            <div class="tab-pane fade show active" id="tab-1" role="tabpanel">
              <div class="card-header">
              </div>
              <div class="accordion" id="accordionExample1">
                <div class="card">
                  <div class="card-header" id="headingOneE">
                    <div class="row">
                      <div class="col-md-4">
                        <h5 class="card-title"><a href="#" data-toggle="collapse" data-target="#collapseOneE"
                            aria-expanded="true" aria-controls="collapseOneE">
                            Service Transaction
                          </a></h5>
                      </div>
                    </div>
                  </div>
                  <div id="collapseOneE" class="collapse show" aria-labelledby="headingOneE"
                    data-parent="#accordionExample1">
                    <div class="card-body" style="margin-right:10px;">
                      <table class="table table-hover table-striped datatables-basic" id="TxnHistory"
                        style="width:100%;">
                        <thead>
                          <tr>
                            <th>S.No</th>
                            <th>Outlet</th>
                            <th>Date</th>
                            <th>Transaction Id</th>
                            <th>Name</th>
                            <th>Mobile No.</th>
                            <th>MRP Bill Amt</th>
                            <th>Discount</th>
                            <th>GST</th>
                            <th>Pending Amount</th>
                            <th>Net Payable Amt</th>
                            <th>Loyalty Earned</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php		$i=0;
														if(!empty($txnhistory)){	
															foreach($txnhistory as $txn){
														?>
                          <tr>
                            <td><?=$i=$i+1;?></td>
                            <td><?=$txn['outlet_name']?></td>
                            <td><?php 
															$a=$txn['txn_datetime'];
															$a=strtotime($a);
															echo date("d-m-Y",$a);
														?>
                            </td>
                            <td><?=$txn['txn_unique_serial_id']?></td>
                            <td><?=$txn['txn_customer_name']?></td>
                            <td><?=$txn['txn_customer_number']?></td>
                            <td><?=$txn['txn_value']+$txn['txn_discount']?></td>
                            <td><?=$txn['txn_discount']?></td>
                            <td><?=$txn['txn_total_tax']?></td>
                            <td><?=$txn['txn_pending_amount']?></td>
                            <td><?=$txn['txn_value']?></td>
                            <td><?=$txn['txn_loyalty_points_debited']?></td>
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
                <div class="card">
                  <div class="card-header" id="headingTwoE">
                    <div class="row">
                      <div class="col-md-4">
                        <h5 class="card-title"><a href="#" data-toggle="collapse" data-target="#collapseTwoE"
                            aria-expanded="true" aria-controls="collapseTwoE">
                            Package Transaction
                          </a></h5>
                      </div>
                      <!-- <div class="col-md-5">
																	<div class="form-group">
																		<div class="input-group">
																			<input type="text" placeholder="Search Customer by name/contact no." class="form-control" id="SearchCustomerP">
																			<span class="input-group-append">
																				<button class="btn btn-success" type="button" id="SearchCustomerButtonP" Customer-No="Nothing" style="padding:0px 0px;">Search</button>
																			</span>
																		</div>
																	</div>
																</div> -->
                    </div>
                  </div>
                  <div id="collapseTwoE" class="collapse" aria-labelledby="headingTwoE"
                    data-parent="#accordionExample1">
                    <div class="card-body" id="EPackages_Data">
                      <table class="table table-hover table-striped datatables-basic" id="PackageTxnHistory"
                        style="width:100%;">
                        <thead>
                          <tr>
                            <th>S.No</th>
                            <th>Outlet</th>
                            <th>Date</th>
                            <th>Transaction Id</th>
                            <th>Name</th>
                            <th>Mobile No.</th>
                            <th>MRP Bill Amt</th>
                            <th>Discount</th>
                            <th>GST</th>
                            <th>Net Payable Amt</th>


                          </tr>
                        </thead>
                        <tbody>

                          <?php		$i=0;
																			if(!empty($txnhistorypackages)){
																				foreach($txnhistorypackages as $ptxn){
																				?>
                          <tr>
                            <td><?=$i=$i+1;?></td>
                            <td><?=$ptxn['outlet_name']?></td>
                            <td>
                              <?php
																								$p=$ptxn['datetime'];
																								$p=strtotime($p);
																								echo date("d-m-Y",$p);
																								// date($ptxn['datetime'])
																							?>
                            </td>
                            <td><?=$ptxn['package_txn_unique_serial_id']?></td>
                            <td><?=$ptxn['customer_name']?></td>
                            <td><?=$ptxn['customer_number']?></td>
                            <td><?=$ptxn['package_txn_value']+$ptxn['package_txn_discount']?></td>
                            <td><?=$ptxn['package_txn_discount']?></td>
                            <td><?=$ptxn['total_tax']?></td>
                            <td><?=$ptxn['package_txn_value']?></td>

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

              </div>


            </div>
            <div class="tab-pane fade" id="tab-2" role="tabpanel">
              <div class="tab-pane fade show active" id="tab-2" role="tabpanel">
                <div class="card-header">
                  <div class="row">
                    <div class="col-md-4">

                    </div>
                    <!-- <div class="col-md-5">
													<div class="form-group">
														<div class="input-group">
															<input type="text" placeholder="Search Customer by name/contact no." class="form-control" id="SearchCustomerPS">
															<span class="input-group-append">
																<button class="btn btn-success" type="button" id="SearchCustomerButtonPS" Customer-No="Nothing" style="padding:0px 0px;">Search</button>
															</span>
														</div>
													</div>
												</div> -->
                  </div>

                </div>
                <table class="table table-hover table-striped datatables-basic" id="PrefferedService"
                  style="width: 100%;">
                  <thead>
                    <tr>
                      <th>S.No</th>
                      <th>Outlet</th>
                      <th>Name</th>
                      <th>Mobile No.</th>
                      <th>Service</th>
                      <th>Count</th>
                      <th>Last Purchase Date</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <?php
												$i=0;
											if(!empty($services)){
												foreach($services as $ser){
											?>
                      <td><?=++$i;?></td>
                      <td><?=$ser['business_outlet_name']?></td>
                      <td><?=$ser['cust_name']?></td>
                      <td><?=$ser['cust_mobile']?></td>
                      <td><?=$ser['service_name']?></td>
                      <td><?=$ser['count']?></td>
                      <td><?=$ser['last_visit']?></td>
                    </tr>
                    <?php
												}
											}
											?>
                  </tbody>
                </table>
              </div>
            </div>

            <div class="tab-pane fade" id="tab-3" role="tabpanel">
              <div class="tab-pane fade show active" id="tab-3" role="tabpanel">
                <div class="card-header">
                  <div class="row">
                    <div class="col-md-4">

                    </div>
                    <!-- <div class="col-md-5">
															<div class="form-group">
																<div class="input-group">
																	<input type="text" placeholder="Search Customer by name/contact no." class="form-control" id="SearchCustomerPP">
																	<span class="input-group-append">
																		<button class="btn btn-success" type="button" id="SearchCustomerButtonPP" Customer-No="Nothing" style="padding:0px 0px;">Search</button>
																	</span>
																</div>
															</div>
														</div> -->
                  </div>
                </div>
                <table class="table table-hover table-striped datatables-basic" id="Products" style="width: 100%;">
                  <thead>
                    <tr>
                      <th>S.No</th>
                      <th>Outlet</th>
                      <th>Name</th>
                      <th>Mobile No.</th>
                      <th>Service</th>
                      <th>Count</th>
                      <th>Last Purchase Date</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
													$i=1;
												if(!empty($products)){
													foreach($products as $prod){
												?>
                    <tr>
                      <td><?=$i++?></td>
                      <td><?=$prod['business_outlet_name']?></td>
                      <td><?=$prod['cust_name']?></td>
                      <td><?=$prod['cust_mobile']?></td>
                      <td><?=$prod['service_name']?></td>
                      <td><?=$prod['count']?></td>
                      <td><?=$prod['last_visit']?></td>
                    </tr>
                    <?php
													}
												}
												?>
                  </tbody>
                </table>
              </div>
            </div>

            <div class="tab-pane fade" id="tab-4" role="tabpanel">
              <div class="card-header">
                <!-- <div class="row"> -->
                <!-- <div class="form-group">
											<div class="input-group" style="width:300px;">
												<input type="text" placeholder="Search Customer by name/contact no." class="form-control" id="SearchCustomer">
												<span class="input-group-append">
													<button class="btn btn-success" type="button" id="SearchCustomerButton" Customer-Id="Nothing">Go</button>
												</span>
											</div>
										</div> -->
                <form class="row" action="#" method="POST">
                  <div class="col-md-4">
                    <h5
                      style="background:#2c7be5;width:100px;color:white;height:20px;text-align:center;padding-top:2px;float:right">
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
                    <button type="submit" id="submitbtn" class="btn btn-primary submitbtn">Submit</button>
                  </div>
                </form>
                <!-- </div>									 -->
              </div>
              <table class="table table-hover table-striped datatables-basic" id="bday" style="width: 100%;">
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
            <div class="tab-pane fade" id="tab-5" role="tabpanel">
              <div class="tab-pane fade show active" id="tab-2" role="tabpanel">
                <div class="card-header">
                  <!-- <div class="row"> -->
                  <form class="row" action="#" method="POST">
                    <div class="col-md-4">
                      <h5
                        style="background:#2c7be5;width:100px;color:white;height:20px;text-align:center;padding-top:2px;float:right">
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
                      <button type="submit" class="btn btn-primary anniversary">Submit</button>
                    </div>
                  </form>
                  <!-- </div>									 -->
                </div>
                <table class="table table-hover table-striped datatables-basic" id="anniversary" style="width: 100%;">
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
                                <!-- <li><a href="#arrows-primary-step-1">Personal Details<br /></a></li> -->
                                <!-- <li><a href="#arrows-primary-step-3">Transactional Details<br /></a>
																		</li> -->
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
                                      <input type="text" class="form-control" name="customer_name" disabled>
                                    </div>
                                    <div class="form-group col-md-4">
                                      <label>Mobile</label>
                                      <input type="text" class="form-control" placeholder="Mobile Number"
                                        data-mask="0000000000" maxlength="10" minlength="10" name="customer_mobile"
                                        disabled>
                                    </div>
                                  </div>
                                  <div class="form-row">
                                    <div class="form-group col-md-4">
                                      <label>Next Appointment Date</label>
                                      <input type="text" class="form-control date" name="next_appointment_date"
                                        disabled>
                                    </div>
                                    <div class="form-group col-md-4">
                                      <label>Date of Birth</label>
                                      <input type="text" class="form-control date" name="customer_dob" disabled>
                                    </div>
                                    <div class="form-group col-md-4">
                                      <label>Date of Anniversary</label>
                                      <input type="text" class="form-control date" placeholder="Date of Addition"
                                        name="customer_doa" disabled>
                                    </div>
                                  </div>
                                  <div class="form-row">
                                    <div class="form-group col-md-4">
                                      <label>Total Billing</label>
                                      <input type="number" class="form-control" name="total_billing" disabled>
                                    </div>
                                    <div class="form-group col-md-4">
                                      <label>Average Order Value</label>
                                      <input type="text" class="form-control" name="avg_value" disabled>
                                    </div>
                                    <div class="form-group col-md-4">
                                      <label>Last visit order value</label>
                                      <input type="text" class="form-control" name="last_order_value" disabled>
                                    </div>


                                  </div>
                                  <div class="form-row">
                                    <div class="form-group col-md-4">
                                      <label>Pending Amount</label>
                                      <input type="Number" class="form-control" name="customer_pending_amount" readonly>
                                    </div>
                                    <div class="form-gorup col-md-4">
                                      <label>Virtual Wallet Balance(Rs.)</label>
                                      <input type="number" class="form-control" placeholder="Virtual Wallet"
                                        name="customer_virtual_wallet" readonly>
                                    </div>
                                    <div class="form-group col-md-4">
                                      <label>Wallet Expiry Date</label>
                                      <input type="text" class="form-control" placeholder="Wallet money expiry date"
                                        name="customer_wallet_expiry_date" readonly>
                                    </div>


                                  </div>
                                  <div class="form-row">
                                    <div class="form-group col-md-4">
                                      <label>Total Visit</label>
                                      <input type="text" class="form-control" name="total_visit" disabled>
                                    </div>

                                    <div class="form-group col-md-4">
                                      <label>Last Visit Date</label>
                                      <input type="text" class="form-control date" name="last_visit" disabled>
                                    </div>
                                    <div class="form-group col-md-4" readonly>
                                      <label>Customer Segment</label>
                                      <input type="text" name="customer_segment" readonly class="form-control">
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
  </div>
  <?php
	$this->load->view('cashier/cashier_footer_view');
?>

  <script type="text/javascript">
  $(document).ajaxStart(function() {
    $("#load_screen").show();
  });

  $(document).ajaxStop(function() {
    $("#load_screen").hide();
  });
  $(document).ready(function() {

    $(".datatables-basic").DataTable({
      responsive: true
    });
  });

  // Services
  //functionality for getting the dynamic input data
  $("#SearchCustomer").typeahead({
    autoselect: true,
    highlight: true,
    minLength: 1
  }, {
    source: SearchCustomer,
    templates: {
      empty: "No Customer Found!",
      suggestion: _.template("<p><%- txn_customer_number %>, <%- txn_customer_name %></p>")
    }
  });

  var to_fill = "";

  $("#SearchCustomer").on("typeahead:selected", function(eventObject, suggestion, name) {
    var loc = "#SearchCustomer";
    to_fill = suggestion.txn_customer_name + "," + suggestion.txn_customer_number;
    setVals(loc, to_fill, suggestion.txn_customer_number);
  });

  $("#SearchCustomer").blur(function() {
    $("#SearchCustomer").val(to_fill);
    to_fill = "";
  });

  function SearchCustomer(query, cb) {
    var parameters = {
      query: query
    };

    $.ajax({
      url: "<?=base_url()?>Cashier/TxnHistoryByCustomerS",
      data: parameters,
      type: "GET",
      // crossDomain: true,
      cache: false,
      // dataType : "json",
      global: false,
      success: function(data) {
        cb(data.message);
      },
      error: function(data) {
        console.log("Some error occured!");
      }
    });
  }
  // for services
  function setVals(element, fill, txn_customer_number) {
    $(element).attr('value', fill);
    $(element).val(fill);
    $("#SearchCustomerButton").attr('Customer-No', txn_customer_number);
  }

  $(document).on('click', "#SearchCustomerButton", function(event) {
    event.preventDefault();
    this.blur();
    var customer_no = $(this).attr('Customer-No');
    // var customer_no = document.getElementById('SearchCustomerButton').value;
    // alert(customer_no);
    if (customer_no == "Nothing") {
      $('#centeredModalDanger').modal('show').on('shown.bs.modal', function(e) {
        $("#ErrorModalMessage").html("").html("Please select customer!");
      });
    } else {
      var parameters = {
        customer_no: $(this).attr('Customer-No')
      };

      $("#SearchCustomerButton").attr('Customer-No', "Nothing");
      $.getJSON("<?=base_url()?>Cashier/AddDataInServiceTable", parameters)
        .done(function(data, textStatus, jqXHR) {
          if (data.service.success == 'true') {
            var str_2 = "";
            // alert(data.service.res_arr.length);
            for (var i = 0; i < data.service.res_arr.length; i++) {
              str_2 += "<tr>";
              str_2 += "<td>" + parseInt(i + 1) + "</td>";
              str_2 += "<td>" + data.service.res_arr[i].outlet_name + "</td>";
              str_2 += "<td>" + data.service.res_arr[i].date + "</td>";
              str_2 += "<td>" + data.service.res_arr[i].txn_unique_serial_id + "</td>";
              str_2 += "<td>" + data.service.res_arr[i].txn_customer_name + "</td>";
              str_2 += "<td>" + data.service.res_arr[i].txn_customer_number + "</td>";
              str_2 += "<td>" + data.service.res_arr[i].txn_value + "</td>";
              str_2 += "<td>" + data.service.res_arr[i].txn_discount + "</td>";
              str_2 += "<td>" + data.service.res_arr[i].txn_total_tax + "</td>";
              str_2 += "<td>" + data.service.res_arr[i].txn_pending_amount + "</td>";
              str_2 += "<td>" + data.service.res_arr[i].txn_value + "</td>";
              str_2 += "<td>" + data.service.res_arr[i].txn_loyalty_points_debited + "</td>";
              str_2 += "</tr>";
            }
            $("#TxnHistory tbody tr").remove();
            $("#TxnHistory tbody").append(str_2);
          }

          // package
          if (data.package.success == 'true') {
            var str_3 = "";
            for (var i = 0; i < data.package.res_arr.length; i++) {
              str_3 += "<tr>";
              str_3 += "<td>" + parseInt(i + 1) + "</td>";
              str_3 += "<td>" + data.package.res_arr[i].outlet_name + "</td>";
              str_3 += "<td>" + data.package.res_arr[i].date + "</td>";
              if (data.package.res_arr[i].package_txn_unique_serial_id == null) {
                data.package.res_arr[i].package_txn_unique_serial_id = ' ';
              }
              str_3 += "<td>" + data.package.res_arr[i].package_txn_unique_serial_id + "</td>";
              str_3 += "<td>" + data.package.res_arr[i].customer_name + "</td>";
              str_3 += "<td>" + data.package.res_arr[i].customer_number + "</td>";
              str_3 += "<td>" + parseInt(data.package.res_arr[i].package_txn_value + data.package.res_arr[i]
                .discount) + "</td>";
              str_3 += "<td>" + data.package.res_arr[i].package_txn_discount + "</td>";
              str_3 += "<td>" + data.package.res_arr[i].total_tax + "</td>";
              str_3 += "<td>" + data.package.res_arr[i].package_txn_value + "</td>";
              str_3 += "</tr>";
            }
            $("#PackageTxnHistory tbody tr").remove();
            $("#PackageTxnHistory tbody").append(str_3);
          }
          // preffered service
          if (data.sservice.success == 'true') {
            var str_4 = "";
            for (var i = 0; i < data.sservice.res_arr.length; i++) {
              str_4 += "<tr>";
              str_4 += "<td>" + parseInt(i + 1) + "</td>";
              str_4 += "<td>" + data.sservice.res_arr[i].business_outlet_name + "</td>";
              str_4 += "<td>" + data.sservice.res_arr[i].cust_name + "</td>";
              str_4 += "<td>" + data.sservice.res_arr[i].cust_mobile + "</td>";
              str_4 += "<td>" + data.sservice.res_arr[i].service_name + "</td>";
              str_4 += "<td>" + data.sservice.res_arr[i].count + "</td>";
              str_4 += "<td>" + data.sservice.res_arr[i].last_visit + "</td>";
              str_4 += "</tr>";
            }
            $("#PrefferedService tbody tr").remove();
            $("#PrefferedService tbody").append(str_4);
          }
          // preffered product
          if (data.pproduct.success == 'true') {
            var str_5 = "";
            for (var i = 0; i < data.pproduct.res_arr.length; i++) {
              str_5 += "<tr>";
              str_5 += "<td>" + parseInt(i + 1) + "</td>";
              str_5 += "<td>" + data.pproduct.res_arr[i].business_outlet_name + "</td>";
              str_5 += "<td>" + data.pproduct.res_arr[i].cust_name + "</td>";
              str_5 += "<td>" + data.pproduct.res_arr[i].cust_mobile + "</td>";
              str_5 += "<td>" + data.pproduct.res_arr[i].service_name + "</td>";
              str_5 += "<td>" + data.pproduct.res_arr[i].count + "</td>";
              str_5 += "<td>" + data.pproduct.res_arr[i].last_visit + "</td>";

              str_5 += "</tr>";
            }
            $("#Products tbody tr").remove();
            $("#Products tbody").append(str_5);
          }
        })
        .fail(function(jqXHR, textStatus, errorThrown) {
          console.log(errorThrown.toString());
        });
    }
  });
  // ended
  </script>

  <!-- Packages  -->

  <script type="text/javascript">
  //functionality for getting the dynamic input data
  $("#SearchCustomerP").typeahead({
    autoselect: true,
    highlight: true,
    minLength: 1
  }, {
    source: SearchCustomerP,
    templates: {
      empty: "No Customer Found!",
      suggestion: _.template("<p><%- customer_number %>, <%- customer_name %></p>")
    }
  });

  var to_fill = "";

  $("#SearchCustomerP").on("typeahead:selected", function(eventObject, suggestion, name) {
    var loc = "#SearchCustomerP";
    to_fill = suggestion.customer_name + "," + suggestion.customer_number;
    setValsP(loc, to_fill, suggestion.customer_number);
  });

  $("#SearchCustomerP").blur(function() {
    $("#SearchCustomerP").val(to_fill);
    to_fill = "";
  });

  function SearchCustomerP(query, cbp) {
    var parameters = {
      query: query
    };

    $.ajax({
      url: "<?=base_url()?>Cashier/TxnHistoryByCustomerP",
      data: parameters,
      type: "GET",
      // crossDomain: true,
      cache: false,
      // dataType : "json",
      global: false,
      success: function(data) {
        cbp(data.message);
      },
      error: function(data) {
        console.log("Some error occured!");
      }
    });
  }
  // for services
  function setValsP(element, fill, customer_number) {
    $(element).attr('value', fill);
    $(element).val(fill);
    $("#SearchCustomerButtonP").attr('Customer-No', customer_number);
  }

  $(document).on('click', "#SearchCustomerButtonP", function(event) {
    event.preventDefault();
    this.blur();
    var customer_no = $(this).attr('Customer-No');
    // alert(customer_no);
    if (customer_no == "Nothing") {
      $('#centeredModalDanger').modal('show').on('shown.bs.modal', function(e) {
        $("#ErrorModalMessage").html("").html("Please select customer!");
      });
    } else {
      var parameters = {
        customer_no: $(this).attr('Customer-No')
      };

      $("#SearchCustomerButtonP").attr('Customer-No', "Nothing");
      $.getJSON("<?=base_url()?>Cashier/AddDataInPackageTable", parameters)
        .done(function(data, textStatus, jqXHR) {
          var str_2 = "";
          // alert(data.result.length);
          for (var i = 0; i < data.result.length; i++) {
            str_2 += "<tr>";
            str_2 += "<td>" + parseInt(i + 1) + "</td>";
            str_2 += "<td>" + data.result[i].outlet_name + "</td>";
            str_2 += "<td>" + data.result[i].date + "</td>";
            if (data.result[i].package_txn_unique_serial_id == null) {
              data.result[i].package_txn_unique_serial_id = ' ';
            }
            str_2 += "<td>" + data.result[i].package_txn_unique_serial_id + "</td>";
            str_2 += "<td>" + data.result[i].customer_name + "</td>";
            str_2 += "<td>" + data.result[i].customer_number + "</td>";
            str_2 += "<td>" + parseInt(data.result[i].package_txn_value + data.result[i].discount) + "</td>";
            str_2 += "<td>" + data.result[i].package_txn_discount + "</td>";
            str_2 += "<td>" + data.result[i].total_tax + "</td>";
            str_2 += "<td>" + data.result[i].package_txn_value + "</td>";
            str_2 += "</tr>";
          }
          $("#PackageTxnHistory tbody tr").remove();
          $("#PackageTxnHistory tbody").append(str_2);
        })
        .fail(function(jqXHR, textStatus, errorThrown) {
          console.log(errorThrown.toString());
        });
    }
  });
  // ended
  </script>
  <script>
  $(document).ready(function() {
    $(document).on('click', '.CustTransHist', function(event) {
      event.preventDefault();
      this.blur();

      var from_date = document.getElementById("fromdate").value;
      var to_date = document.getElementById("todate").value;
      alert(from_date);
      var parameters = {
        from_date,
        to_date
      };
      $.getJSON("<?= base_url() ?>Cashier/TxnHistoryByCustomerS", parameters)
        .done(function(data, textStatus, jqXHR) {
          var temp_str =
            "<tr><th>Sr.No</th><th>Employee Id</th><th>Month</th><th>Employee Name</th><th>Calender Days</th><th>Working Days</th><th>Present</th><th>Leave</th><th>OverTime (hrs)</th><th>Week-Off</th><th>Holidays</th><th>Half Days</th><th>Net Days Present</th></tr>";
          for (var i = 0; i < data.result.length; i++) {
            temp_str += "<tr>";

            temp_str += "</tr>";

          }
          $("#FillTxnDetails").html("").html(temp_str);
          $("#ModalCustomerDetails").modal('show');
        })
        .fail(function(jqXHR, textStatus, errorThrown) {
          console.log(errorThrown.toString());
        });
    });
  });
  </script>

  <!-- Preffered Services -->
  <script type="text/javascript">
  //functionality for getting the dynamic input data
  $("#SearchCustomerPS").typeahead({
    autoselect: true,
    highlight: true,
    minLength: 1
  }, {
    source: SearchCustomerPS,
    templates: {
      empty: "No Customer Found!",
      suggestion: _.template("<p><%- cust_mobile %>, <%- cust_name %></p>")
    }
  });

  var to_fill = "";

  $("#SearchCustomerPS").on("typeahead:selected", function(eventObject, suggestion, name) {
    var loc = "#SearchCustomerPS";
    to_fill = suggestion.cust_name + "," + suggestion.cust_mobile;
    setValsPS(loc, to_fill, suggestion.cust_mobile);
  });

  $("#SearchCustomerPS").blur(function() {
    $("#SearchCustomerPS").val(to_fill);
    to_fill = "";
  });

  function SearchCustomerPS(query, cbps) {
    var parameters = {
      query: query
    };

    $.ajax({
      url: "<?=base_url()?>Cashier/TxnHistoryByCustomerPS",
      data: parameters,
      type: "GET",
      // crossDomain: true,
      cache: false,
      // dataType : "json",
      global: false,
      success: function(data) {
        cbps(data.message);
      },
      error: function(data) {
        console.log("Some error occured!");
      }
    });
  }

  function setValsPS(element, fill, customer_number) {
    $(element).attr('value', fill);
    $(element).val(fill);
    $("#SearchCustomerButtonPS").attr('Customer-No', customer_number);
  }

  $(document).on('click', "#SearchCustomerButtonPS", function(event) {
    event.preventDefault();
    this.blur();
    var customer_no = $(this).attr('Customer-No');
    // alert(customer_no);
    if (customer_no == "Nothing") {
      $('#centeredModalDanger').modal('show').on('shown.bs.modal', function(e) {
        $("#ErrorModalMessage").html("").html("Please select customer!");
      });
    } else {
      var parameters = {
        customer_no: $(this).attr('Customer-No')
      };

      $("#SearchCustomerButtonPS").attr('Customer-No', "Nothing");
      $.getJSON("<?=base_url()?>Cashier/AddDataINPrefferedServicesTable", parameters)
        .done(function(data, textStatus, jqXHR) {
          var str_2 = "";
          // alert(data.result.length);
          for (var i = 0; i < data.result.length; i++) {
            str_2 += "<tr>";
            str_2 += "<td>" + parseInt(i + 1) + "</td>";
            str_2 += "<td>" + data.result[i].business_outlet_name + "</td>";
            str_2 += "<td>" + data.result[i].cust_name + "</td>";
            str_2 += "<td>" + data.result[i].cust_mobile + "</td>";
            str_2 += "<td>" + data.result[i].service_name + "</td>";
            str_2 += "<td>" + data.result[i].count + "</td>";
            str_2 += "<td>" + data.result[i].last_visit + "</td>";

            str_2 += "</tr>";
          }
          $("#PrefferedService tbody tr").remove();
          $("#PrefferedService tbody").append(str_2);
        })
        .fail(function(jqXHR, textStatus, errorThrown) {
          console.log(errorThrown.toString());
        });
    }
  });
  // ended
  </script>
  <!-- preffered Product -->
  <script type="text/javascript">
  //functionality for getting the dynamic input data
  $("#SearchCustomerPP").typeahead({
    autoselect: true,
    highlight: true,
    minLength: 1
  }, {
    source: SearchCustomerPP,
    templates: {
      empty: "No Customer Found!",
      suggestion: _.template("<p><%- cust_mobile %>, <%- cust_name %></p>")
    }
  });

  var to_fill = "";

  $("#SearchCustomerPP").on("typeahead:selected", function(eventObject, suggestion, name) {
    var loc = "#SearchCustomerPP";
    to_fill = suggestion.cust_name + "," + suggestion.cust_mobile;
    setValsPP(loc, to_fill, suggestion.cust_mobile);
  });

  $("#SearchCustomerPP").blur(function() {
    $("#SearchCustomerPP").val(to_fill);
    to_fill = "";
  });

  function SearchCustomerPP(query, cbpp) {
    var parameters = {
      query: query
    };

    $.ajax({
      url: "<?=base_url()?>Cashier/TxnHistoryByCustomerPP",
      data: parameters,
      type: "GET",
      // crossDomain: true,
      cache: false,
      // dataType : "json",
      global: false,
      success: function(data) {
        cbpp(data.message);
      },
      error: function(data) {
        console.log("Some error occured!");
      }
    });
  }

  function setValsPP(element, fill, customer_number) {
    $(element).attr('value', fill);
    $(element).val(fill);
    $("#SearchCustomerButtonPP").attr('Customer-No', customer_number);
  }

  $(document).on('click', "#SearchCustomerButtonPP", function(event) {
    event.preventDefault();
    this.blur();
    var customer_no = $(this).attr('Customer-No');
    // alert(customer_no);
    if (customer_no == "Nothing") {
      $('#centeredModalDanger').modal('show').on('shown.bs.modal', function(e) {
        $("#ErrorModalMessage").html("").html("Please select customer!");
      });
    } else {
      var parameters = {
        customer_no: $(this).attr('Customer-No')
      };

      $("#SearchCustomerButtonPP").attr('Customer-No', "Nothing");
      $.getJSON("<?=base_url()?>Cashier/AddDataINPrefferedProductTable", parameters)
        .done(function(data, textStatus, jqXHR) {
          var str_2 = "";
          // alert(data.result.length);
          for (var i = 0; i < data.result.length; i++) {
            str_2 += "<tr>";
            str_2 += "<td>" + parseInt(i + 1) + "</td>";
            str_2 += "<td>" + data.result[i].business_outlet_name + "</td>";
            str_2 += "<td>" + data.result[i].cust_name + "</td>";
            str_2 += "<td>" + data.result[i].cust_mobile + "</td>";
            str_2 += "<td>" + data.result[i].service_name + "</td>";
            str_2 += "<td>" + data.result[i].count + "</td>";
            str_2 += "<td>" + data.result[i].last_visit + "</td>";

            str_2 += "</tr>";
          }
          $("#Products tbody tr").remove();
          $("#Products tbody").append(str_2);
        })
        .fail(function(jqXHR, textStatus, errorThrown) {
          console.log(errorThrown.toString());
        });
    }
  });
  // ended
  </script>
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
      $.getJSON("<?=base_url()?>Cashier/GetCustomerHistory", parameters)
        .done(function(data, textStatus, jqXHR) {
          var temp_str =
            "<tr><th>Sr.No</th><th>Name</th><th>Mobile No</th><th>Birthday</th><th>Lifetime Billing</th><th>Last Visit Date</th></tr>";
          if (data.success == 'true') {
            for (var i = 0; i < data.result.length; i++) {
              temp_str += "<tr>";
              temp_str += "<td>" + (i + 1) + "</td>";
              temp_str += "<td>" + data.result[i].txn_customer_name + "</td>";
              temp_str += "<td><a class='EditViewCustomer' cust_id=" + data.result[i].customer_id + ">" +
                data.result[i].txn_customer_number + "</a></td>";
              temp_str += "<td>" + data.result[i].customer_dob + "</td>";
              temp_str += "<td>" + data.result[i].amt + "</td>";
              temp_str += "<td>" + data.result[i].last_visit + "</td>";
              temp_str +=
                "<td><button type='submit' class='btn btn-primary SendMessage' option='bday' cust_name=" +
                data.result[i].txn_customer_name + " cust_no=" + data.result[i].txn_customer_number +
                ">Send</button></td>";
              temp_str += "</tr>";
            }
            $("#bday").html("").html(temp_str);
          } else if (data.success == 'false') {
            toastr["success"](data.message, "", {
              positionClass: "toast-top-right",
              progressBar: "toastr-progress-bar",
              newestOnTop: "toastr-newest-on-top",
              rtl: $("body").attr("dir") === "rtl" || $("html").attr("dir") === "rtl",
              timeOut: 2000
            });
            setTimeout(function() {
              location.reload(1);
            }, 2000);
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
      $.getJSON("<?=base_url()?>Cashier/GetCustomerHistoryAnniversary", parameters)
        .done(function(data, textStatus, jqXHR) {
          // alert(data);
          if (data.success == 'true') {
            var temp_str =
              "<tr><th>Sr.No</th><th>Name</th><th>Mobile No</th><th>Anniversary</th><th>Lifetime Billing</th><th>Last Visit Date</th></tr>";
            for (var i = 0; i < data.result.length; i++) {
              temp_str += "<tr>";
              temp_str += "<td>" + (i + 1) + "</td>";
              temp_str += "<td>" + data.result[i].txn_customer_name + "</td>";
              temp_str += "<td><a class='EditViewCustomer' cust_id=" + data.result[i].customer_id + ">" +
                data.result[i].txn_customer_number + "</a></td>";
              temp_str += "<td>" + data.result[i].customer_doa + "</td>";
              temp_str += "<td>" + data.result[i].amt + "</td>";
              temp_str += "<td>" + data.result[i].last_visit + "</td>";
              temp_str +=
                "<td><button type='submit' class='btn btn-primary SendMessage' option='Anniversary' cust_name=" +
                data.result[i].txn_customer_name + " cust_no=" + data.result[i].txn_customer_number +
                ">Send</button></td>";
              temp_str += "</tr>";
            }
            $("#anniversary").html("").html(temp_str);
            // $("#ModalCustomerDetails").modal('show');
          } else if (data.success == 'false') {
            toastr["success"](data.message, "", {
              positionClass: "toast-top-right",
              progressBar: "toastr-progress-bar",
              newestOnTop: "toastr-newest-on-top",
              rtl: $("body").attr("dir") === "rtl" || $("html").attr("dir") === "rtl",
              timeOut: 2000
            });
            setTimeout(function() {
              location.reload(1);
            }, 2000);
          }
        })
        .fail(function(jqXHR, textStatus, errorThrown) {
          console.log(errorThrown.toString());
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
    $.getJSON("<?=base_url()?>Cashier/GetCustomer", parameters)
      .done(function(data, textStatus, jqXHR) {
        $("#EditCustomerDetails select[name=customer_title]").val(data.customer_title);
        $("#EditCustomerDetails input[name=customer_name]").attr('value', data.customer_name);
        $("#EditCustomerDetails input[name=customer_mobile]").attr('value', data.customer_mobile);
        $("#EditCustomerDetails input[name=customer_doa]").attr('value', moment(data.customer_doa)
          .format('DD-MM-YYYY'));
        $("#EditCustomerDetails input[name=customer_dob]").attr('value', moment(data.customerdob)
          .format('DD-MM-YYYY'));
        6
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
      customer_name: $(this).attr('cust_name'),
      option: $(this).attr('option')
    };
    $.getJSON("<?=base_url()?>Cashier/SendMessage", parameters)
      .done(function(data, textStatus, jqXHR) {
        // alert(data);
        if (data.success == 'true') {
          toastr["success"](data.message, "", {
            positionClass: "toast-top-right",
            progressBar: "toastr-progress-bar",
            newestOnTop: "toastr-newest-on-top",
            rtl: $("body").attr("dir") === "rtl" || $("html").attr("dir") === "rtl",
            timeOut: 2000
          });
          setTimeout(function() {
            location.reload(1);
          }, 2000);
        } else if (data.success == 'false') {
          toastr["error"](data.message, "", {
            positionClass: "toast-top-right",
            progressBar: "toastr-progress-bar",
            newestOnTop: "toastr-newest-on-top",
            rtl: $("body").attr("dir") === "rtl" || $("html").attr("dir") === "rtl",
            timeOut: 2000
          });
          setTimeout(function() {
            location.reload(1);
          }, 2000);
        }

      })
      .fail(function(jqXHR, textStatus, errorThrown) {
        console.log(errorThrown.toString());
      });
  });
  </script>
  <script type="text/javascript">
  $(document).ready(function() {
    $(document).on('click', '.nav-link', function(event) {

      // alert($(this).text());
      if ($(this).text() == 'Birthday' || $(this).text() == 'Anniversary') {
        $("#searchforcust").attr('hidden', true);
      } else {
        $("#searchforcust").removeAttr('hidden');
      }
    });
  });
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
