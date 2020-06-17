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
									
								</div>
							</div>
							<div class="card-body">
							<table class="table table-hover datatables-basic" style="width: 100%;" id="cust_data">
									<thead>
										<tr>
											<th>S.No.</th>
											<th>Mobile</th>
											<th>Name</th>
											<th>Total Amount Spent</th>
											<?php
											if(!empty($rules))
											{
												if($rules['rule_type'] =='Offers Single Rule' ||$rules['rule_type'] =='Offers Multiple Rule' || $rules['rule_type'] =='Offers LTV Rule')
												{
												?>
												<th>Loyalty Points</th>
												<?php
												}
												else if($rules['rule_type'] == 'Cashback Single Rule' || $rules['rule_type'] == 'Cashback Multiple Rule' || $rules['rule_type'] == 'Cashback LTV Rule')
												{
												?>
												<th>Loyalty Cashback</th>
												<?php
												}
											}
											?>
											<!-- <th>Cashback Redeemed</th>
											<th>Balance Cashback</th> -->
											<th>Update date</th>
										</tr>
									</thead>
									<tbody>
									 <?php $count=1; foreach($cashback as $data){
										?>
										<tr>
											<td><?=$count?></td>
											<td><?=$data['customer_mobile']?></td>
											<td><?=$data['customer_name']?></td>	
											<td><?=$data['total_spent']?></td>										
											<?php
											if(!empty($rules))
											{
												if($rules['rule_type'] =='Offers Single Rule' ||$rules['rule_type'] =='Offers Multiple Rule' || $rules['rule_type'] =='Offers LTV Rule')
												{
												?>
													<td><?=$data['customer_rewards']?></td>
													<?php
												}
												else if($rules['rule_type'] == 'Cashback Single Rule' || $rules['rule_type'] == 'Cashback Multiple Rule' || $rules['rule_type'] == 'Cashback LTV Rule')
												{
													?>
													<td><?=$data['customer_cashback']?></td>
													<?php
												}
											}
											?>											
											<td><?=$data['last_txn_date']?></td>
										</tr>
										<?php
										$count++;
										}
										?> 
									</tbody>
								</table>
							</div>
						</div>
					</div>
<?php
	$this->load->view('cashier/cashier_footer_view');
?>
<script>
		$(document).ajaxStart(function() {
      $("#load_screen").show();
    });

    $(document).ajaxStop(function() {
      $("#load_screen").hide();
    });

		$(".datatables-basic").DataTable({
			responsive: true
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
        url: "<?=base_url()?>index.php/Cashier/GetCustomerData/",
        data: parameters,
        type: "GET",
        crossDomain: true,
				cache: false,
        dataType : "json",
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
					$.getJSON("<?=base_url()?>index.php/Cashier/AddCustomerDataInTable/", parameters)
					.done(function(data, textStatus, jqXHR) { 
						var str_2 = "";
						
						str_2 += "<tr>";
						str_2 += "<td>1</td>";
						str_2 += "<td>"+data[0].customer_mobile+"</td>";
						str_2 += "<td>"+data[0].customer_name+"</td>";
						str_2 += "<td>"+data[0].total_spent+"</td>";
						<?php
							if(!empty($rules))
							{
								if($rules['rule_type'] =='Offers Single Rule' ||$rules['rule_type'] =='Offers Multiple Rule' || $rules['rule_type'] =='Offers LTV Rule')
								{
								?>
									str_2 += "<td>"+data[0].customer_rewards+"</td>";
									<?php
								}
								else if($rules['rule_type'] == 'Cashback Single Rule' || $rules['rule_type'] == 'Cashback Multiple Rule' || $rules['rule_type'] == 'Cashback LTV Rule')
								{
									?>
									str_2 += "<td>"+data[0].customer_cashback+"</td>";
									<?php
								}
							}
							?>						
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
<script>
	var input = document.getElementById("SearchCustomer");
	input.addEventListener("keyup", function(event) {
		if (event.keyCode === 13) {
		event.preventDefault();
		document.getElementById("SearchCustomerButton").click();
		}
	});
</script>
