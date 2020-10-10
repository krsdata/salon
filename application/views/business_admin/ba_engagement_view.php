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
              <div class="card-header">
                <h5 class="card-title"></h5>
              </div>
              <div class="card-body">                
                <div class="row">
                  <div class="col-md-3">
                    <div class="card" style="width: 10rem;">
                      <div class="card-body">
                        <h1 class="card-title" style="text-align:center;font-size:20px;font-weight:bolder"><?=$new?>
                        </h1>
                        <h6 class="card-text" style="text-align:center">New Customer</h6>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-3">

                    <div class="card" style="width: 10rem">
                      <div class="card-body">
                        <h1 class="card-title" style="text-align:center;font-size:20px;font-weight:bolder"><?=$repeat?>
                        </h1>
                        <h6 class="card-text" style="text-align:center">Repeating Customer</h6>
                      </div>
                    </div>

                  </div>
                  <div class="col-md-3">
                    <div class="card" style="width: 10rem;">
                      <div class="card-body">
                        <h1 class="card-title" style="text-align:center;font-size:20px;font-weight:bolder"><?=$regular?>
                        </h1>
                        <h6 class="card-text" style="text-align:center">Regular Customer</h6>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="card" style="width: 10rem;">
                      <div class="card-body">
                        <h1 class="card-title" style="text-align:center;font-size:20px;font-weight:bolder">
                          <?=($allcust-($new+$regular+$repeat))?></h1>
                        <h6 class="card-text" style="text-align:center">Never Visited</h6>
                      </div>
                    </div>
									</div>
								</div>
								<div class="row">
                  <div class="col-md-3">
                    <div class="card" style="width: 10rem;">
                      <div class="card-body">
                        <h1 class="card-title" style="text-align:center;font-size:20px;font-weight:bolder"><?=$no_risk?>
                        </h1>
                        <h6 class="card-text" style="text-align:center">No Risk</h6>
                      </div>
                    </div>
									</div>
									<div class="col-md-3">
                    <div class="card" style="width: 10rem;">
                      <div class="card-body">
                        <h1 class="card-title" style="text-align:center;font-size:20px;font-weight:bolder"><?=$dormant?>
                        </h1>
                        <h6 class="card-text" style="text-align:center">Dormant</h6>
                      </div>
                    </div>
									</div>
									<div class="col-md-3">
                    <div class="card" style="width: 10rem;">
                      <div class="card-body">
                        <h1 class="card-title" style="text-align:center;font-size:20px;font-weight:bolder"><?=$risk?>
                        </h1>
                        <h6 class="card-text" style="text-align:center">At Risk</h6>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="card" style="width: 10rem;">
                      <div class="card-body">
                        <h1 class="card-title" style="text-align:center;font-size:20px;font-weight:bolder"> <?=$lost?>
                        </h1>
                        <h6 class="card-text" style="text-align:center">Lost Customer</h6>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row" style="text-align:center">
                  <div class="col-lg-12 col-lg-12" style="margin-top:20px">
                    <a href="<?= base_url() ?>BusinessAdmin/Customertimeline"
                      class="btn btn-success btn-lg btn-block">Customer Timeline</a>
                  </div>
                </div>
                <div class="row">
                  <div class="col-lg-12" style="text-align:right;font-weight:bolder;font-size:large;margin-top:5mm">
                    <label>All Customer : <?=$allcust?></label></div>
                </div>
                <div class="row" style="margin-top:40px">
                  <div class="col-md-3">
                    <label>Select Category</label>
                    <select name="category" id="category" class="form-control" style="width:300px;">
                      <option selected disabled>Select</option>
                      <!-- <option value="All">All Customer</option> -->
                      <option value="New">New Customer</option>
                      <option value="Repeat">Repeat Customer</option>
											<option value="Regular">Regular Customer</option>
											<option value="no_risk">No Risk Customer</option>
											<option value="dormant">Dormant Customer</option>
                      <option value="Risk">Risk Customer</option>
                      <option value="Lost">Lost Customer</option>
                    </select>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group">
                      <div class="input-group" style="width:300px;margin-top:25px">
                        <input type="text" id="SearchCustomer" placeholder="Search Customer by name/contact no."
                          class="form-control">
                        <span class="input-group-append">
                          <button class="btn btn-success" type="button" id="SearchCustomerButton"
                            Customer-Id="Nothing">Go</button>
                        </span>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <!-- <label>&emsp;</label>
                                        <input style="float:right" type="submit" name="download" class="form-control" style="width:100px"> -->
                  </div>
                  <div class="col-md-2">
                    <label>&emsp;</label>
                    <input style="float:right" type="submit" class="btn btn-primary" value="Download" id="download" name="download"
                      class="form-control" style="width:100px">
                  </div>
                </div>
                <table class="table table-striped table-hover" style="text-align:center;width: 100%;font-weight:bold"
                  id="FillTxnDetails">

                  <tbody></tbody>
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
    <script type="text/javascript">
    $("#download").on('click', function(e) {

      var category = document.getElementById('category').value;
      // alert(category);
      var parameters = {
        category: category
      };

      $.getJSON("<?=base_url()?>BusinessAdmin/EngagementReport", parameters)
        .done(function(data, textStatus, jqXHR) {

          JSONToCSVConvertor(data.result, " ", true);
        })
        .fail(function(jqXHR, textStatus, errorThrown) {
          console.log(errorThrown.toString());
        });
    });
    </script>
    <script type="text/javascript">
    $("#category").on('change', function(e) {
      var category = document.getElementById('category').value;
      // alert(category);
      var parameters = {
        category: category
      };

      $.getJSON("<?=base_url()?>BusinessAdmin/GetCustDataForTimeline", parameters)
        .done(function(data, textStatus, jqXHR) {
          if (category == 'All') {
            var temp_str = "<thead><th>S.No</th><th>Name</th><th>Number</th><th>Category</th></thead> ";
            for (var i = 0; i < data.result.length; i++) {
              temp_str += "<tr>";
              temp_str += "<td>" + (i + 1) + "</td>";
              temp_str += "<td>" + data.result[i].Name + "</td>";
              temp_str += "<td>" + data.result[i].Mobile + "</td>";
              temp_str += "<td>" + data.result[i].Category + "</td>";

              temp_str += "</tr>";
            }
          } else {
            var temp_str =
              "<thead><th>S.No</th><th>Name</th><th>Number</th><th>Visits</th><th>Total Spend</th><th>Average Order Value</th><th>Last Visit</th><th> Store</th><th>Rewards</th><th>Wallet</th><th>Due</th><th>Category</th></thead> ";
            for (var i = 0; i < data.result.length; i++) {
              temp_str += "<tr>";
              temp_str += "<td>" + (i + 1) + "</td>";
              temp_str += "<td>" + data.result[i].Name + "</td>";
              temp_str += "<td>" + data.result[i].Mobile + "</td>";
							temp_str += "<td>" + data.result[i].Visits + "</td>";
							temp_str += "<td>" + data.result[i].Total_Spend + "</td>";
							temp_str += "<td>" + data.result[i].aov + "</td>";              
              temp_str += "<td>" + data.result[i].Last_Visit_Date + "</td>";
							temp_str += "<td>" + data.result[i].last_visited_store + "</td>";
							temp_str += "<td>" + data.result[i].rewards + "</td>";
							temp_str += "<td>" + data.result[i].vw_amount + "</td>";
							temp_str += "<td>" + data.result[i].due_amount + "</td>";
							temp_str += "<td>" + data.result[i].Category + "</td>";
              temp_str += "</tr>";
            }
          }

          // alert(data.result[0].Name);

          // temp_str += " ";
          $("#FillTxnDetails").html("").html(temp_str);
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
      fileName += ReportTitle.replace(/ /g, "_");

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
    var input = document.getElementById("SearchCustomer");
    input.addEventListener("keyup", function(event) {
      if (event.keyCode === 13) {
        event.preventDefault();
        document.getElementById("SearchCustomerButton").click();
      }
    });



    //functionality for getting the dynamic input data
    $("#SearchCustomer").typeahead({
      autoselect: true,
      highlight: true,
      minLength: 1
    }, {
      source: SearchCustomer,
      templates: {
        empty: "No Customer Found!",
        suggestion: _.template("<p class='customer_search'><%- customer_name %>, <%- customer_mobile %></p>")
      }
    });

    var to_fill = "";

    $("#SearchCustomer").on("typeahead:selected", function(eventObject, suggestion, name) {
      var loc = "#SearchCustomer";
      to_fill = suggestion.customer_name + "," + suggestion.customer_mobile;
      setVals(loc, to_fill, suggestion.customer_id);
    });
    $(document).on('click', ".customer_search", function(event) {
      event.preventDefault();
      this.blur();
      // alert("Click click");
      $("#SearchCustomerButton").click();
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
        url: "<?=base_url()?>BusinessAdmin/GetCustomerData",
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

    function setVals(element, fill, customer_id) {
      $(element).attr('value', fill);
      $(element).val(fill);
      $("#SearchCustomerButton").attr('Customer-Id', customer_id);
    }

    $(document).on('click', "#SearchCustomerButton", function(event) {
      event.preventDefault();
      this.blur();
      var customer_id = $(this).attr('Customer-Id');
      //   alert(customer_id);
      if (customer_id == "Nothing") {
        $('#centeredModalDanger').modal('show').on('shown.bs.modal', function(e) {
          $("#ErrorModalMessage").html("").html("Please select customer!");
        });
      } else {
        var parameters = {
          customer_id: $(this).attr('Customer-Id')
        };

        $("#SearchCustomerButton").attr('Customer-Id', "Nothing");

        $.ajax({
          url: "<?=base_url()?>BusinessAdmin/GetCustDataForTimelineSearch",
          data: parameters,
          type: "POST",
          // crossDomain: true,
          cache: false,
          // dataType : "json",
          success: function(data) {
            if (data.success == 'true') {
              var temp_str =
                "<thead><th>S.No</th><th>Name</th><th>Number</th><th>Visits</th><th>Total Spend</th><th>Last Visit Date</th></thead> ";
              for (var i = 0; i < data.result.length; i++) {
                temp_str += "<tr>";
                temp_str += "<td>" + (i + 1) + "</td>";
                temp_str += "<td>" + data.result[i].Name + "</td>";
                temp_str += "<td>" + data.result[i].Mobile + "</td>";
                temp_str += "<td>" + data.result[i].Category + "</td>";
                temp_str += "<td>" + data.result[i].Total_Spend + "</td>";
                temp_str += "<td>" + data.result[i].Last_Visit_Date + "</td>";
                temp_str += "</tr>";
              }
              // temp_str += " ";
              $("#FillTxnDetails").html("").html(temp_str);
            } else if (data.success == 'false') {
              $('#centeredModalDanger').modal('show').on('shown.bs.modal', function(e) {
                $("#ErrorModalMessage").html("").html(data.message);
              });
            }
          },
          error: function(data) {
            $('#centeredModalDanger').modal('show').on('shown.bs.modal', function(e) {
              $("#ErrorModalMessage").html("").html("Some error occured, Please try again later!");
            });
          }
        });
      }
    });
    </script>
