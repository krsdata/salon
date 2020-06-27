<?php
	$this->load->view('master_admin/ma_header_view');
?>
<div class="wrapper">
	<?php
		$this->load->view('master_admin/ma_nav_view');
	?>
	<div class="main">
		<?php
			$this->load->view('master_admin/ma_top_nav_view');
		?>
		<main class="content">
			<div class="container-fluid p-0">
				<div class="row">
					<div class="col-md-12">
						<div class="tab">
            				<ul class="nav nav-pills" role="tablist">
                				<li class="nav-item"><a class="nav-link active" href="#tab-1" data-toggle="tab" role="tab" aria-selected="false" style="font-size:14px;">Dashboard</a></li>
                  				<!--<li class="nav-item"><a class="nav-link" href="#tab-2" data-toggle="tab" role="tab" aria-selected="false" style="font-size:14px;">Daily Trends</a></li>-->
                      			<li class="nav-item"><a class="nav-link" href="#tab-3" data-toggle="tab" role="tab" aria-selected="false" style="font-size:14px;">Historic Trends</a></li>
                        		<!--<li class="nav-item"><a class="nav-link" href="#tab-4" data-toggle="tab" role="tab" aria-selected="false" style="font-size:14px;">Store Wise</a></li> -->
              				</ul>							
								<div class="tab-content">
									<div class="tab-pane active" id="tab-1" role="tabpanel">
										<div class="card">
										<div class="row">
                    						<div class="col-md-5">
                      							<div class="row">
                        							<div class="col-md-6">
                          								<div class="card flex-fill" style="height: 194px">
                            								<div class="card-header">
                              									<h5><b style="color: #2c7be5">Sales in</b> <i class="fa fa-rupee-sign" style="color:green"></i></h5>
                            								</div>
																<div class="card-body">
																	<b>Current Month Sales:</b><b style="color:green" class="float-right"><?=$current_month_service[0]['total_sales']+$current_pack_sales[0]['packages']+$current_month_products[0]['total_sales']?></b><br><br>
																	<b>Last Month Till Date:</b><b style="color:green" class="float-right"><?=$previous_pack_till_date[0]['packages']+$previous_month_till_service[0]['total_sales']+$previous_month_till_products[0]['total_sales']?></b><br>
																	<b>Last Full Month:</b><b style="color:green" class="float-right"><?=$previous_month_total_service[0]['total_sales']+$previous_month_total_products[0]['total_sales']+$previous_month_pack_sales[0]['packages']?></b>                            
																</div>
														  </div>
														  </div>
														<div class="col-md-6">
															<div class="card flex-fill" style="height: 194px">
																<div class="card-header">
																	<h5><b style="color: #2c7be5">Customer Visit</b> <i class="fa fa-walking" style="color:green"></i><h5></b></h5>
																</div>
																	<div class="card-body">
																		<b>Current Month Walkin:</b>  <b style="color:green" class="float-right"><?=$current_till_bill_count[0]['total_bill']?></b><br><br>
																		<b>Last Month Till Date:</b><b style="color:green" class="float-right"><?=$prev_till_bill_count[0]['total_bill']?></b><br>
																		<b>Last Full Month:</b><b style="color:green" class="float-right"><?=$prev_bill_count[0]['total_bill']?></b>
																	</div>
																</div>
															</div>
                      								</div>
														<div class="row">
															<!--<div class="col-md-6">-->
															<!--	<div class="card flex-fill" style="height: 184px">-->
															<!--		<div class="card-header">-->
															<!--			<h5><b style="color:#2c7be5">Active Employees</b> <i class="fa fa-handshake" style="color:green"></i></h5>-->
															<!--		</div>-->
															<!--			<div class="card-body">-->
															<!--			<b>Current Month:</b><b style="color:green" class="float-right">22</b><br><br>-->
															<!--			<b>Last Month Till Date:</b><b style="color:green" class="float-right">31</b><br>-->
															<!--			<b>Last Full Month:</b><b style="color:green" class="float-right">43</b>                            -->
															<!--			</div>-->
															<!--	</div>-->
															<!--</div>-->
																<div class="col-md-6">
																	<div class="card flex-fill" style="height: 184px">
																		<div class="card-header">
																			<h5><b style="color: #2c7be5">Avg. Daily Sales</b> <i class="fa fa-money-bill" style="color:green"></i></h5>
																		</div>
																			<div class="card-body">
																				<b>Current Month:</b><b style="color:green" class="float-right"><?=$avg_current_month_sales[0]['avg_sales']?></b><br><br><br>
																				<b>Last Month Run Rate:</b> <b style="color:green" class="float-right"><?=$avg_previous_month_sales[0]['avg_sales']?></b>
																			</div>
																	</div>
																</div>
														</div>
                    						</div>
												<div class="col-md-7">
													<div class="card-body">
													<i style="background: #2c7be5;color:white;border:1px solid #2c7be5;">Last 15 days overall revenue trends</i><br><br>
														<div class="chart chart-lg">
														<canvas id="chartjs-dashboard-line"></canvas>
														</div>
													</div>
												</div>
											</div>
                  						</div>  <!--dashboard first part ends-->
											<div class="card">
												<div class="row">
												<div class="col-md-12">
													<div class="card-header">
													<form class="form-inline" method="post" action="#" id="FifteenSort">
														<div class="form-group col-md-2">
															<select class="form-control" name="state" onchange="getcity(this.value)">
                                                                <option>             </option>
                                                                <?php
                                                                  foreach($outlet_state as $state=>$value)
                                                                  {
                                                                    ?>
                                                                    <option value="<?=$value['business_outlet_state']?>"><?=$value['business_outlet_state']?></option>
                                                                    <?php
                                                                  }
                                                                ?>
															</select>
														</div>
														<div class="form-group col-md-2">
															<select class="form-control" name="city" onchange="getoutlet(this.value)">
																<option>             </option>
															</select>
														</div>
															<div  class="form-group col-md-2">
																<select class="form-control" name="outlet">
																	<option>              </option>
																</select>
															</div>
															<button class="btn btn-primary sortby_outlet">
																<i class="fa fa-paper-plane ::before"></i>
																	Submit 
															</button>
													</form>
													</div>
												</div> 
												</div><!--dashboard 2nd menu-->
													<div class="card-body">
														<div class="row">
															<div class="col-md-12">
																<i style="background:#2c7be5;color:white;border:1px solid blue;margin-left:20px">Last 15 Days Revenue</i><br><br>
																<table class="table table-striped datatables-basic" style="width: 100%;" id="LastFifteenTrends">
															  <thead>
																<tr>
																	<th>Last 15 days</td>
																	<th>Total Sales</th>
																	<th>Service Revenue</th>
																	<th>Product Revenue</th>
																	<th>Package Revenue</th>
																	<th>#Visits</th>
																	<!-- <th>Unique User</th>
																	<th>New Users</th>
																	<th>Old Users</th> -->
																</tr>
															  </thead>
                                <tbody>
                                  <?php 
                                  $total_sales = $total_pack=$total_prod = $total_service =$total_bill = 0;
                                  foreach($labels as $key=>$value)
                                  {
                                    $total_sales  = $total_sales+$data_sales[$key];
                                    $total_service = $total_service+$data_service[$key];
                                    $total_prod = $total_prod + $data_prod[$key];
                                    $total_pack = $total_pack + $data_pack[$key];
                                    $total_bill = $total_bill + $bill_count[$key];
                                    ?>
                                      <tr>
                                        <td><?=$value?></td>
                                        <td><?=$data_sales[$key]?></td>
                                        <td><?=$data_service[$key]?></td>
                                        <td><?=$data_prod[$key]?></td>
                                        <td><?=$data_pack[$key]?></td>
                                        <td><?=$bill_count[$key]?></td>
                                      </tr> 
                                  <?php
                                }?>
                                      <tr>
                                        <td>Total</td>
                                        <td><?=$total_sales?></td>
                                        <td><?=$total_service?></td>
                                        <td><?=$total_prod?></td>
                                        <td><?=$total_pack?></td>
                                        <td><?=$total_bill?></td>
                                      </tr>
															  </tbody>
														  </table>
															</div>
															<!-- <div class="col-md-1"></div> -->
															<!-- <div class="col-md-6">
																<i style="background:#2c7be5;color:white;border:1px solid blue">Last 15 Days Cust. footfall</i><br><br>
																<div class="chart chart-lg">
																<canvas id="dashboard-graph-footfall" style="margin-right:20px"></canvas>
																</div>
															</div> -->
														</div>
                                                    </div>
                                                       <!--dashboard 2nd part graph-->
												<!--</div>-->
											</div> <!--dashboard 2nd part-->
													<!-- <div class="row">
														<div class="col-md-12">
															<div class="card-header">
															<form class="form-inline" method="post" action="#">
																	<div class="form-group col-md-2">
																		<select class="form-control">
																			<option>             </option>
																		</select>
																	</div>
																	<div class="form-group col-md-2">
																		<select class="form-control">
																			<option>             </option>
																		</select>
																	</div>
																	<div  class="form-group col-md-2">
																		<select class="form-control">
																			<option>              </option>
																		</select>
																	</div>
																		<button class="btn btn-primary">
																			<i class="fa fa-paper-plane ::before"></i>
																			Submit 
																		</button>
															</form>
															</div>
														</div>
													</div><br> -->
																<div class="row">
																	<div class="col-md-12">
																		<div class="card-header">
																			<form class="form-inline" method="post" action="#" id="SortDaily">
												<!-- <h5>8-Feb-2020</h5> -->
													<div class="form-group col-md-2">
														<select class="form-control" name="state" onchange="DailyGetCity(this.value)" style="overflow: hidden;">
                              <option>             </option>
                              <?php
                                  foreach($outlet_state as $state=>$value)
                                  {
                              ?>
                                  <option value="<?=$value['business_outlet_state']?>"><?=$value['business_outlet_state']?></option>
                              <?php
                                  }
                              ?>
														</select>
													</div>
													<div class="form-group col-md-2">
														<select class="form-control" name="city" onchange="DailyGetOutlet(this.value)" style="overflow: hidden;">
															<option>             </option>
														</select>
													</div>
													<div class="form-group col-md-2">
														<select class="form-control" name="outlet" style="overflow: hidden;">
															<option>              </option>
														</select>
													</div>
												<button class="btn btn-primary">
													<i class="fa fa-paper-plane ::before"></i>
													Submit 
												</button>
												</form>
																		</div>
																		
																	</div>
																
																</div>
																		<div class="card">
																			<div class="row">
																				<div class="col-md-12">
																					<table class="table table-striped datatables-basic" style="width: 100%;" id="dailyTrends">
															<thead>
																<tr>
																	<th>#</td>
																	<th>Total Sales</th>
																	<th>Service Sale</th>
																	<th>Package Sale</th>
																	<th>Product Sale</th>
																	<th>#Bill</th>
																	<!-- <th>Unique User</th>
																	<th>New Users</th>
																	<th>Old Users</th> -->
																</tr>
															</thead>
															<tbody>
																<tr>
																	<td>FTD</td>
																	<td id="ftd_total"><?=($today_sales_service[0]['total_sales']+$today_sales_package[0]['package_sales']+$today_sales_products[0]['total_sales'])?></td>
																	<td id="ftd_service"><?=$today_sales_service[0]['total_sales']?></td>
																	<td id="ftd_package"><?=$today_sales_package[0]['package_sales']?></td>
																	<td id="ftd_product"><?=$today_sales_products[0]['total_sales']?></td>
																	<td id="ftd_bill_count"><?=$today_bill_count?></td>
																	<!-- <td>krjhtkjreht</td>
																	<td>krjhtkjreht</td>
																	<td>krjhtkjreht</td> -->
																</tr>
																<tr>
																	<td>MTD</td>
																	<td id="mtd_total"><?=$current_month_service[0]['total_sales']+$current_pack_sales[0]['packages']+$current_month_products[0]['total_sales']?></td>
																	<td id="mtd_service"><?=$current_month_service[0]['total_sales']?></td>
																	<td id="mtd_package"><?=$current_pack_sales[0]['packages']?></td>
																	<td id="mtd_product"><?=$current_month_products[0]['total_sales']?></td>
																	<td id="mtd_bill_count"><?=$current_till_bill_count[0]['total_bill']?></td>
																	<!-- <td>krjhtkjreht</td>
																	<td>krjhtkjreht</td>
																	<td>krjhtkjreht</td> -->
																</tr>
																<tr>
																	<td>LMTD</td>
																	<td id="lmtd_total"><?=$previous_pack_till_date[0]['packages']+$previous_month_till_service[0]['total_sales']+$previous_month_till_products[0]['total_sales']?></td>
																	<td id="lmtd_service"><?=$previous_month_till_service[0]['total_sales']?></td>
																	<td id="lmtd_package"><?=$previous_pack_till_date[0]['packages']?></td>
																	<td id="lmtd_product"><?=$previous_month_till_products[0]['total_sales']?></td>
																	<td id="lmtd_bill_count"><?=$prev_till_bill_count[0]['total_bill']?></td>
																	<!-- <td>krjhtkjreht</td>
																	<td>krjhtkjreht</td>
																	<td>krjhtkjreht</td> -->
																</tr>
																<tr>
																	<td>Last Month Full</td>
																	<td id="lmf_total"><?=$previous_month_total_service[0]['total_sales']+$previous_month_total_products[0]['total_sales']+$previous_month_pack_sales[0]['packages']?></td>
																	<td id="lmf_service"><?=$previous_month_total_service[0]['total_sales']?></td>
																	<td id="lmf_package"><?=$previous_month_pack_sales[0]['packages']?></td>
																	<td id="lmf_product"><?=$previous_month_total_products[0]['total_sales']?></td>
																	<td id="lmf_bill_count"><?=$prev_bill_count[0]['total_bill']?></td>
																	<!-- <td>krjhtkjreht</td>
																	<td>krjhtkjreht</td>
																	<td>krjhtkjreht</td> -->
																</tr>
															</tbody>
														</table>
																				</div>
																			</div>
																		</div><br>
																<!-- <div class="row">
																	<div class="col-md-5">
																		<h5><b>State/City Wise Footfall</b></h5><br>
																			<div class="chart chart-lg">
																				<canvas id="bar-chart-historic-city-wise-cust-footfall"></canvas>
																			</div>
																	</div>
																		<div class="col-md-1"></div>
																			<div class="col-md-6">
																				<h5><b>Active Employees</b></h5><br>
																				<div class="chart chart-lg">
																					<canvas id="bar-chart-historic-city-wise-active-employee"></canvas>
																				</div>
																			</div>
																</div> -->
                				</div>
								<!-- Master Admin Dashboard -->
								<div class="tab-pane" id="tab-2" role="tabpanel">
									<div class="card">
										<div class="row">
											<div class="col-md-12">
												<form class="form-inline" method="post" action="#" id="SortDaily">
												<!-- <h5>8-Feb-2020</h5> -->
													<div class="form-group col-md-2">
														<select class="form-control" onchange="DailyGetCity(this.value)" style="overflow: hidden;">
                              <option>             </option>
                              <?php
                                  foreach($outlet_state as $state=>$value)
                                  {
                              ?>
                                  <option value="<?=$value['business_outlet_state']?>"><?=$value['business_outlet_state']?></option>
                              <?php
                                  }
                              ?>
														</select>
													</div>
													<div class="form-group col-md-2">
														<select class="form-control" name="city" onchange="DailyGetOutlet(this.value)" style="overflow: hidden;">
															<option>             </option>
														</select>
													</div>
													<div class="form-group col-md-2">
														<select class="form-control" name="outlet" style="overflow: hidden;">
															<option>              </option>
														</select>
													</div>
												<button class="btn btn-primary">
													<i class="fa fa-paper-plane ::before"></i>
													Submit 
												</button>
												</form>
											</div>
                      	<div class="card-body">
													<div class="col-md-12">
														<table class="table table-striped datatables-basic" style="width: 100%;" id="dailyTrends">
															<thead>
																<tr>
																	<th>#</td>
																	<th>Total Sales</th>
																	<th>Service Sale</th>
																	<th>Package Sale</th>
																	<th>Product Sale</th>
																	<th>#Bill</th>
																	<!-- <th>Unique User</th>
																	<th>New Users</th>
																	<th>Old Users</th> -->
																</tr>
															</thead>
															<tbody>
																<tr>
																	<td>FTD</td>
																	<td id="ftd_total"><?=($today_sales_service[0]['total_sales']+$today_sales_package[0]['package_sales']+$today_sales_products[0]['total_sales'])?></td>
																	<td id="ftd_service"><?=$today_sales_service[0]['total_sales']?></td>
																	<td id="ftd_package"><?=$today_sales_package[0]['package_sales']?></td>
																	<td id="ftd_product"><?=$today_sales_products[0]['total_sales']?></td>
																	<td id="ftd_bill_count"><?=$today_bill_count?></td>
																	<!-- <td>krjhtkjreht</td>
																	<td>krjhtkjreht</td>
																	<td>krjhtkjreht</td> -->
																</tr>
																<tr>
																	<td>MTD</td>
																	<td id="mtd_total"><?=$current_month_service[0]['total_sales']+$current_pack_sales[0]['packages']+$current_month_products[0]['total_sales']?></td>
																	<td id="mtd_service"><?=$current_month_service[0]['total_sales']?></td>
																	<td id="mtd_package"><?=$current_pack_sales[0]['packages']?></td>
																	<td id="mtd_product"><?=$current_month_products[0]['total_sales']?></td>
																	<td id="mtd_bill_count"><?=$current_till_bill_count[0]['total_bill']?></td>
																	<!-- <td>krjhtkjreht</td>
																	<td>krjhtkjreht</td>
																	<td>krjhtkjreht</td> -->
																</tr>
																<tr>
																	<td>LMTD</td>
																	<td id="lmtd_total"><?=$previous_pack_till_date[0]['packages']+$previous_month_till_service[0]['total_sales']+$previous_month_till_products[0]['total_sales']?></td>
																	<td id="lmtd_service"><?=$previous_month_till_service[0]['total_sales']?></td>
																	<td id="lmtd_package"><?=$previous_pack_till_date[0]['packages']?></td>
																	<td id="lmtd_product"><?=$previous_month_till_products[0]['total_sales']?></td>
																	<td id="lmtd_bill_count"><?=$prev_till_bill_count[0]['total_bill']?></td>
																	<!-- <td>krjhtkjreht</td>
																	<td>krjhtkjreht</td>
																	<td>krjhtkjreht</td> -->
																</tr>
																<tr>
																	<td>Last Month Full</td>
																	<td id="lmf_total"><?=$previous_month_total_service[0]['total_sales']+$previous_month_total_products[0]['total_sales']+$previous_month_pack_sales[0]['packages']?></td>
																	<td id="lmf_service"><?=$previous_month_total_service[0]['total_sales']?></td>
																	<td id="lmf_package"><?=$previous_month_pack_sales[0]['packages']?></td>
																	<td id="lmf_product"><?=$previous_month_total_products[0]['total_sales']?></td>
																	<td id="lmf_bill_count"><?=$prev_bill_count[0]['total_bill']?></td>
																	<!-- <td>krjhtkjreht</td>
																	<td>krjhtkjreht</td>
																	<td>krjhtkjreht</td> -->
																</tr>
															</tbody>
														</table>
													</div>
                      							</div>
                    					</div>
                  					</div>
								</div>
									<div class="tab-pane" id="tab-3" role="tabpanel">
										<div class="row">
											<div class="col-md-12">
											<div class="card">
												<div class="card-header">
												<form class="form-inline" method="post" action="#" id="HistoricTrends">
													<div class="form-group col-md-2">
                          <select class="form-control" onchange="HistoricGetCity(this.value)" name="state" style="overflow: hidden;">
                                              <option style="overflow: hidden;">             </option>
                                              <?php
                                                foreach($outlet_state as $state=>$value)
                                                {
                                                  ?>
                                                  <option value="<?=$value['business_outlet_state']?>" style="overflow: hidden;"><?=$value['business_outlet_state']?></option>
                                                  <?php
                                                }
                                              ?>
																						</select>
													</div>
													<div class="form-group col-md-2">
                          <select class="form-control" name="city" onchange="HistoricGetOutlet(this.value)" style="overflow: hidden;">
															<option>             </option>
														</select>
													</div>
													<div  class="form-group col-md-2">
													<select class="form-control" name="outlet" style="overflow: hidden;">
															<option>              </option>
														</select>
													</div>
														<button class="btn btn-primary">
															<i class="fa fa-paper-plane ::before"></i>
															Submit 
														</button>
												</form>
												</div>
													<div class="card-body">
														<h6>Last 6 Months Trends</h6>
													<div class="col-md-12">
														<table class="table table-striped datatables-basic" style="width: 100%;">
															<thead>
																<tr>
																	<th>#</td>
																	<th>Total Sales</th>
																	<th>Service Sale</th>
																	<th>Package Sale</th>
																	<th>Product Sale</th>
																	<th>#Bill</th>
																	<!--<th>Unique User</th>-->
																	<!--<th>New Users</th>-->
																	<!--<th>Old Users</th>-->
																</tr>
															</thead>
															<tbody>
                                <?php
                                  $count = 0;
                                  if($six_months_service!='')
                                  {
                                    foreach($six_months_service as $service=>$value)
                                    {
                                      ?>
                                      <tr>
    																	<td><?=$value['date']?></td>
    																	<td><?=$value['total_service']+$six_months_product[$count]['total_service']+$six_months_package[$count]['package_sales']?></td>
    																	<td><?=$value['total_service']?></td>
    																	<td><?=$six_months_package[$count]['package_sales']?></td>
    																	<td><?=$six_months_product[$count]['total_service']?></td>
    																	<td><?=$six_months_package[$count]['package_count']+$value['service_count']?></td>
    																	<!--<td>krjhtkjreht</td>-->
    																	<!--<td>krjhtkjreht</td>-->
    																	<!--<td>krjhtkjreht</td>-->
                                    </tr>
                                    <?php
                                      $count++;
                                    }
                                  }
                                  else
                                  {
                                    ?>
                                    
                                    <h4>Last Six Months Data Not Available</h4>
                                    <?php
                                  }
                                ?>
															
																<!-- <tr>
																	<td>Dec 19</td>
																	<td>krjhtkjreht</td>
																	<td>krjhtkjreht</td>
																	<td>krjhtkjreht</td>
																	<td>krjhtkjreht</td>
																	<td>krjhtkjreht</td>
																	<td>krjhtkjreht</td>
																	<td>krjhtkjreht</td>
																	<td>krjhtkjreht</td>
																</tr>
																<tr>
																	<td>Nov 19</td>
																	<td>krjhtkjreht</td>
																	<td>krjhtkjreht</td>
																	<td>krjhtkjreht</td>
																	<td>krjhtkjreht</td>
																	<td>krjhtkjreht</td>
																	<td>krjhtkjreht</td>
																	<td>krjhtkjreht</td>
																	<td>krjhtkjreht</td>
																</tr>
																<tr>
																	<td>Oct 19</td>
																	<td>krjhtkjreht</td>
																	<td>krjhtkjreht</td>
																	<td>krjhtkjreht</td>
																	<td>krjhtkjreht</td>
																	<td>krjhtkjreht</td>
																	<td>krjhtkjreht</td>
																	<td>krjhtkjreht</td>
																	<td>krjhtkjreht</td>
																</tr>
																<tr>
																	<td>Sep 19</td>
																	<td>krjhtkjreht</td>
																	<td>krjhtkjreht</td>
																	<td>krjhtkjreht</td>
																	<td>krjhtkjreht</td>
																	<td>krjhtkjreht</td>
																	<td>krjhtkjreht</td>
																	<td>krjhtkjreht</td>
																	<td>krjhtkjreht</td>
																</tr>
																<tr>
																	<td>Aug 19</td>
																	<td>krjhtkjreht</td>
																	<td>krjhtkjreht</td>
																	<td>krjhtkjreht</td>
																	<td>krjhtkjreht</td>
																	<td>krjhtkjreht</td>
																	<td>krjhtkjreht</td>
																	<td>krjhtkjreht</td>
																	<td>krjhtkjreht</td>
																</tr> -->
															</tbody>
														</table>
													</div>
													</div>
											</div>
											</div>
										</div>
									<div class="card">
										<div class="card-header">
										<div class="col-md-12">
											<table class="table table-striped datatables-basic" style="width: 100%;">
												<thead>
													<tr>
														<th>Store Name</th>
														<th>Store Admin Name</th>
														<th>City</th>
														<th>State</th>
														<th>Total Sales</th>
														<th>Service Sale</th>
														<th>Package Sale</th>
														<th>Product Sale</th>
														<th>#Bill</th>
													</tr>
												</thead>
													<tbody>
															<!-- table body goes here -->
													</tbody>
											</table>
										</div>
										</div> 							
										<div class="card-body">
											<div class="row">
												<div class="col-md-12">
												<div class="card-header">
													<form class="form-inline" method="post" action="#">
														<div class="form-group col-md-2">
															<select class="form-control">
																<option>             </option>
															</select>
														</div>
														<div class="form-group col-md-2">
															<select class="form-control">
																<option>             </option>
															</select>
														</div>
														<div class="form-group col-md-2">
															<select class="form-control">
																<option>              </option>
															</select>
													</div>
														<button class="btn btn-primary">
															<i class="fa fa-paper-plane ::before"></i>
															Submit 
														</button>
													</form>
													</div>
												</div>
											</div><br> <!--menu area tab3-->
												<div class="row">
													<div class="col-md-5">
														<i style="background: #2c7be5;color:white;border:1px solid blue" width="20px" height="20px">Last 6 Months Revenue</i><br><br>
															<div class="chart chart-lg">
																<canvas id="chartjs-dashboard-bar-devices"></canvas>
															</div>
													</div>
														<div class="col-md-1"></div>
															<div class="col-md-5">
																<i style="background: #2c7be5;color:white;border:1px solid blue" width="20px" height="20px">Last 6 Months Cust. Footfall</i><br><br>
																	<div class="chart chart-lg">
																		<canvas id="bar-chart-historic"></canvas>
																	</div>
															</div>
												</div>
										</div>										
									</div>
									</div>
								  <div class="tab-pane" id="tab-4" role="tabpanel">
									  <div class="card">
										<div class="card-header">
											<div class="row">
												<div class="col-md-12">
													<form class="form-inline" method="post" action="#">
														<div class="form-group col-md-2">
															<select class="form-control">
																<option>             </option>
															</select>
														</div>
														<div class="form-group col-md-2">
															<select class="form-control">
																<option>             </option>
															</select>
														</div>
														<div class="form-group col-md-2">
															<select class="form-control">
																<option>              </option>
															</select>
														</div>
														<div class="form-group col-md-2">
															<select class="form-control">
																<option>Select KPI</option>
															</select>
														</div>
													<button class="btn btn-primary">
														<i class="fa fa-paper-plane ::before"></i>
														Submit 
													</button>
													</form> 
													
												</div>
											</div>
										</div> 					
										<div class="card-body">
											<div class="row">
												<div class="col-md-12">
												<table class="table table-striped datatables-basic" style="width: 100%;">
															<thead>
																<tr>
																	<th>Store Name</th>
																	<th>Admin Name</th>
																	<th>City</th>
																	<th>State</th>
																	<th>FTD Rev</th>
																	<th>MTD Rev</th>
																	<th>LMTD Rev</th>
																</tr>
															</thead>
																<tbody>
																	<tr>
																		<td>lskjflskj</td>
																		<td>lskjflskj</td>
																		<td>lskjflskj</td>
																		<td>lskjflskj</td>
																		<td>lskjflskj</td>
																		<td>lskjflskj</td>
																		<td>lskjflskj</td>
																	</tr>
																	<tr>
																		<td>lskjflskj</td>
																		<td>lskjflskj</td>
																		<td>lskjflskj</td>
																		<td>lskjflskj</td>
																		<td>lskjflskj</td>
																		<td>lskjflskj</td>
																		<td>lskjflskj</td>
																	</tr>
																	<tr>
																		<td>lskjflskj</td>
																		<td>lskjflskj</td>
																		<td>lskjflskj</td>
																		<td>lskjflskj</td>
																		<td>lskjflskj</td>
																		<td>lskjflskj</td>
																		<td>lskjflskj</td>
																	</tr>
																	
																</tbody>
                                                    </table>
												</div>
											</div>
										</div>
									</div>
									<div class="card">
										<div class="row">
											<div class="col-md-12">
												<div class="card-header">
												<form class="form-inline" method="post" action="#">
													<div class="form-group col-md-2">
														<select class="form-control">
															<option>             </option>
														</select>
													</div>
													<div class="form-group col-md-2">
														<select class="form-control">
															<option>             </option>
														</select>
													</div>
														<div class="form-group col-md-2">
															<select class="form-control">
																<option>              </option>
															</select>
													</div>
														<div class="col-md-2"></div>
															<button class="btn btn-primary">
																<i class="fa fa-paper-plane ::before"></i>
																Submit 
															</button>
													</form> 
												</div>
											</div>
										</div>
									</div>
										<div class="card">
											<div class="row">
												<div class="col-md-12">
													<div class="card-header">
														<h5>MTD</h5>
													</div>
														<div class="card-body">
														<table class="table table-striped datatables-basic" style="width: 100%;">
															<thead>
																<tr>
																	<th>Store Name</th>
																	<th>Admin Name</th>
																	<th>City</th>
																	<th>State</th>
																	<th>FTD Rev</th>
																	<th>MTD Rev</th>
																	<th>LMTD Rev</th>
																</tr>
															</thead>
																<tbody>
																	<tr>
																		<td>lskjflskj</td>
																		<td>lskjflskj</td>
																		<td>lskjflskj</td>
																		<td>lskjflskj</td>
																		<td>lskjflskj</td>
																		<td>lskjflskj</td>
																		<td>lskjflskj</td>
																	</tr>
																	<tr>
																		<td>lskjflskj</td>
																		<td>lskjflskj</td>
																		<td>lskjflskj</td>
																		<td>lskjflskj</td>
																		<td>lskjflskj</td>
																		<td>lskjflskj</td>
																		<td>lskjflskj</td>
																	</tr>
																	<tr>
																		<td>lskjflskj</td>
																		<td>lskjflskj</td>
																		<td>lskjflskj</td>
																		<td>lskjflskj</td>
																		<td>lskjflskj</td>
																		<td>lskjflskj</td>
																		<td>lskjflskj</td>
																	</tr>	
																</tbody>
                                                    </table>
														</div>
												</div>
											</div>
										</div>
										<div class="card">
										<div class="row">
											<div class="col-md-12">
												<div class="card-header">
												<form class="form-inline" method="post" action="#">
													<div class="form-group col-md-2">
														<select class="form-control">
															<option>             </option>
														</select>
													</div>
													<div class="form-group col-md-2">
														<select class="form-control">
															<option>             </option>
														</select>
													</div>
														<div class="form-group col-md-2">
															<select class="form-control">
																<option>              </option>
															</select>
													</div>
														<div class="col-md-2"></div>
															<button class="btn btn-primary">
																<i class="fa fa-paper-plane ::before"></i>
																Submit 
															</button>
													</form> 
												</div>
											</div>
										</div>
									</div>
										<div class="card">
											<div class="row">
												<div class="col-md-12">
													<div class="card-header">
														<h5>LMTD</h5>
													</div>
														<div class="card-body">
														<table class="table table-striped datatables-basic" style="width: 100%;">
															<thead>
																<tr>
																	<th>Store Name</th>
																	<th>Admin Name</th>
																	<th>City</th>
																	<th>State</th>
																	<th>FTD Rev</th>
																	<th>MTD Rev</th>
																	<th>LMTD Rev</th>
																</tr>
															</thead>
																<tbody>
																	<tr>
																		<td>lskjflskj</td>
																		<td>lskjflskj</td>
																		<td>lskjflskj</td>
																		<td>lskjflskj</td>
																		<td>lskjflskj</td>
																		<td>lskjflskj</td>
																		<td>lskjflskj</td>
																	</tr>
																	<tr>
																		<td>lskjflskj</td>
																		<td>lskjflskj</td>
																		<td>lskjflskj</td>
																		<td>lskjflskj</td>
																		<td>lskjflskj</td>
																		<td>lskjflskj</td>
																		<td>lskjflskj</td>
																	</tr>
																	<tr>
																		<td>lskjflskj</td>
																		<td>lskjflskj</td>
																		<td>lskjflskj</td>
																		<td>lskjflskj</td>
																		<td>lskjflskj</td>
																		<td>lskjflskj</td>
																		<td>lskjflskj</td>
																	</tr>	
																</tbody>
                                                    </table>
														</div>
												</div>
											</div>
										</div>
								</div> 
							</div>
						</div>
				</main>
	</div>
</div>
<?php
	$this->load->view('master_admin/ma_footer_view');
?>

<script>
		$(function() {
      // Line chart
      var data_labels  =  <?php echo json_encode($labels);?>;
      var data_sales   =  <?php echo json_encode($data_sales);?>;
			new Chart(document.getElementById("chartjs-dashboard-line"), {
				type: "line",
				data: {
					labels: data_labels,
					datasets: [{
						label: "Sales (Rs.)",
						fill: true,
						backgroundColor: "transparent",
						borderColor: window.theme.primary,
						data: data_sales
					}]
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
								stepSize: 10000
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
  
  <!-- last 15 days revenue -->
 <script>
		$(function() {
			// Line chart
			var data_labels  =  <?php echo json_encode($labels);?>;
      		var data_service  =  <?php echo json_encode($data_service);?>;
			var data_product  = <?php echo json_encode($data_prod);?>;
			var data_pack 	  =     <?php echo json_encode($data_pack);?>;
			new Chart(document.getElementById("dashboard-graph"), {
				type: "line",
				data: {
					labels: data_labels,
					datasets: [{
						label: "Service (Rs.)",
						fill: true,
						backgroundColor: "transparent",
						borderColor: window.theme.primary,
						data: data_service
						}, {
						label: "Products(Rs.)",
						fill: true,
						backgroundColor: "transparent",
						borderColor: window.theme.secondary,
						borderDash: [4, 4],
						data: data_product
					},{
						label: "Package(Rs.)",
						fill: true,
						backgroundColor: "transparent",
						borderColor: window.theme.teritary,
						borderDash: [2, 2],
						data: data_pack
					}]
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
								stepSize: 10000
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
  
		
<!-- <script>
	
		$(function() {
			// Line chart
			new Chart(document.getElementById("dashboard-graph-footfall"), {
				type: "line",
				data: {
					labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
					datasets: [{
						label: "Sales ($)",
						fill: true,
						backgroundColor: "transparent",
						borderColor: window.theme.primary,
						data: [2015, 1465, 1487, 1796, 1387, 2123, 2866, 2548, 3902, 4938, 3917, 4927]
					}, {
						label: "Orders",
						fill: true,
						backgroundColor: "transparent",
						borderColor: window.theme.tertiary,
						borderDash: [4, 4],
						data: [928, 734, 626, 893, 921, 1202, 1396, 1232, 1524, 2102, 1506, 1887]
					}]
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
								stepSize: 500
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
</script> -->


<!-- <script>
		$(function() {
			// Line chart
			new Chart(document.getElementById("dashboard-home-line-graph"), {
				type: "line",
				data: {
					labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
					datasets: [{
						label: "Sales ($)",
						fill: true,
						backgroundColor: "transparent",
						borderColor: window.theme.primary,
						data: [2015, 1465, 1487, 1796, 1387, 2123, 2866, 2548, 3902, 4938, 3917, 4927]
					}, {
						label: "Orders",
						fill: true,
						backgroundColor: "transparent",
						borderColor: window.theme.tertiary,
						borderDash: [4, 4],
						data: [928, 734, 626, 893, 921, 1202, 1396, 1232, 1524, 2102, 1506, 1887]
					}]
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
								stepSize: 500
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
	</script> -->



<script>
	$(function() {
		// Bar chart
		new Chart(document.getElementById("chartjs-dashboard-bar-devices"), {
			type: "bar",
			data: {
				labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
				datasets: [{
					label: "Mobile",
					backgroundColor: window.theme.primary,
					borderColor: window.theme.primary,
					hoverBackgroundColor: window.theme.primary,
					hoverBorderColor: window.theme.primary,
					data: [54, 67, 41, 55, 62, 45, 55, 73, 60, 76, 48, 79]
				}, {
					label: "Desktop",
					backgroundColor: "#E8EAED",
					borderColor: "#E8EAED",
					hoverBackgroundColor: "#E8EAED",
					hoverBorderColor: "#E8EAED",
					data: [69, 66, 24, 48, 52, 51, 44, 53, 62, 79, 51, 68]
				}]
			},
			options: {
				maintainAspectRatio: false,
				legend: {
					display: false
				},
				scales: {
					yAxes: [{
						gridLines: {
							display: false
						},
						stacked: false,
						ticks: {
							stepSize: 20
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
	});
</script>



<script>
	$(function() {
		// Bar chart
		new Chart(document.getElementById("bar-chart-historic"), {
			type: "bar",
			data: {
				labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
				datasets: [{
					label: "Mobile",
					backgroundColor: window.theme.primary,
					borderColor: window.theme.primary,
					hoverBackgroundColor: window.theme.primary,
					hoverBorderColor: window.theme.primary,
					data: [54, 67, 41, 55, 62, 45, 55, 73, 60, 76, 48, 79]
				}, {
					label: "Desktop",
					backgroundColor: "#E8EAED",
					borderColor: "#E8EAED",
					hoverBackgroundColor: "#E8EAED",
					hoverBorderColor: "#E8EAED",
					data: [69, 66, 24, 48, 52, 51, 44, 53, 62, 79, 51, 68]
				}]
			},
			options: {
				maintainAspectRatio: false,
				legend: {
					display: false
				},
				scales: {
					yAxes: [{
						gridLines: {
							display: false
						},
						stacked: false,
						ticks: {
							stepSize: 20
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
	});
</script>

<script>
		$(function() {
			// Pie chart
      var service = <?php echo json_encode($current_month_service[0]['total_sales'])?>;
      var prod = <?php echo json_encode($current_month_products[0]['total_sales'])?>;
      var pack = <?php echo json_encode($current_pack_sales[0]['packages'])?>;
			new Chart(document.getElementById("chartjs-dashboard-pie"), {
				type: "pie",
				data: {
					labels: ["Service", "Products", "Package"],
					datasets: [{
						data: [service,prod,pack],
						backgroundColor: [
							window.theme.primary,
							window.theme.warning,
							window.theme.danger,
							"#E8EAED"
						],
						borderColor: "transparent"
					}]
				},
				options: {
					responsive: !window.MSInputMethodContext,
					maintainAspectRatio: false,
					legend: {
						display: true
					}
				}
			});
		});
</script>

	<script>
		$(function() {
			$("#datatables-dashboard-projects").DataTable({
				pageLength: 6,
				lengthChange: false,
				bFilter: false,
				autoWidth: false
			});
		});
	</script>


<!-- <script>
		$(function() {
			// Line chart
			new Chart(document.getElementById("dashboard-graph-city-wise-footfall"), {
				type: "line",
				data: {
					labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
					datasets: [{
						label: "Sales ($)",
						fill: true,
						backgroundColor: "transparent",
						borderColor: window.theme.primary,
						data: [2015, 1465, 1487, 1796, 1387, 2123, 2866, 2548, 3902, 4938, 3917, 4927]
					}, {
						label: "Orders",
						fill: true,
						backgroundColor: "transparent",
						borderColor: window.theme.tertiary,
						borderDash: [4, 4],
						data: [928, 734, 626, 893, 921, 1202, 1396, 1232, 1524, 2102, 1506, 1887]
					}]
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
								stepSize: 500
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
	</script> -->


<!-- <script>
		$(function() {
			// Line chart
			new Chart(document.getElementById("dashboard-graph-footfall-active-employees"), {
				type: "line",
				data: {
					labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
					datasets: [{
						label: "Sales ($)",
						fill: true,
						backgroundColor: "transparent",
						borderColor: window.theme.primary,
						data: [2015, 1465, 1487, 1796, 1387, 2123, 2866, 2548, 3902, 4938, 3917, 4927]
					}, {
						label: "Orders",
						fill: true,
						backgroundColor: "transparent",
						borderColor: window.theme.tertiary,
						borderDash: [4, 4],
						data: [928, 734, 626, 893, 921, 1202, 1396, 1232, 1524, 2102, 1506, 1887]
					}]
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
								stepSize: 500
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
</script> -->



<script>
	$(function() {
		// Bar chart
		var outlet_name = <?php echo json_encode($outlet_name);?>;
		var lmtd_sales = <?php echo json_encode($lmtd_total_sales);?>;
		var mtd_sales  = <?php echo json_encode($mtd_total_sales);?>;
		new Chart(document.getElementById("bar-chart-historic-city-wise"), {
			type: "bar",
			data: {
				labels: outlet_name,
				datasets: [{
					label: "MTD",
					backgroundColor: window.theme.primary,
					borderColor: window.theme.primary,
					hoverBackgroundColor: window.theme.primary,
					hoverBorderColor: window.theme.primary,
					data: mtd_sales
				}, {
					label: "LMTD",
					backgroundColor: "#E8EAED",
					borderColor: "#E8EAED",
					hoverBackgroundColor: "#E8EAED",
					hoverBorderColor: "#E8EAED",
					data: lmtd_sales
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
							stepSize: 20000
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
	});
</script>



<!-- <script>
	$(function() {
		// Bar chart
		new Chart(document.getElementById("bar-chart-historic-city-wise-cust-footfall"), {
			type: "bar",
			data: {
				labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
				datasets: [{
					label: "Mobile",
					backgroundColor: window.theme.primary,
					borderColor: window.theme.primary,
					hoverBackgroundColor: window.theme.primary,
					hoverBorderColor: window.theme.primary,
					data: [54, 67, 41, 55, 62, 45, 55, 73, 60, 76, 48, 79]
				}, {
					label: "Desktop",
					backgroundColor: "#E8EAED",
					borderColor: "#E8EAED",
					hoverBackgroundColor: "#E8EAED",
					hoverBorderColor: "#E8EAED",
					data: [69, 66, 24, 48, 52, 51, 44, 53, 62, 79, 51, 68]
				}]
			},
			options: {
				maintainAspectRatio: false,
				legend: {
					display: false
				},
				scales: {
					yAxes: [{
						gridLines: {
							display: false
						},
						stacked: false,
						ticks: {
							stepSize: 20
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
	});
</script> -->



<!-- <script>
	$(function() {
		// Bar chart
		new Chart(document.getElementById("bar-chart-historic-city-wise-active-employee"), {
			type: "bar",
			data: {
				labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
				datasets: [{
					label: "Mobile",
					backgroundColor: window.theme.primary,
					borderColor: window.theme.primary,
					hoverBackgroundColor: window.theme.primary,
					hoverBorderColor: window.theme.primary,
					data: [54, 67, 41, 55, 62, 45, 55, 73, 60, 76, 48, 79]
				}, {
					label: "Desktop",
					backgroundColor: "#E8EAED",
					borderColor: "#E8EAED",
					hoverBackgroundColor: "#E8EAED",
					hoverBorderColor: "#E8EAED",
					data: [69, 66, 24, 48, 52, 51, 44, 53, 62, 79, 51, 68]
				}]
			},
			options: {
				maintainAspectRatio: false,
				legend: {
					display: false
				},
				scales: {
					yAxes: [{
						gridLines: {
							display: false
						},
						stacked: false,
						ticks: {
							stepSize: 20
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
	});
</script> -->
<script>
  function getcity(state)
  {
    var parameters = { state : state};
    $.getJSON("<?=base_url()?>MasterAdmin/GetCityStateWise",parameters)
    .done(function(data,textStatus,jqXHR){
      var options = "<option value='' selected></option>";
      for(var i=0;i<data.length;i++)
      {
        options += "<option value="+data[i].business_outlet_city+">"+data[i].business_outlet_city+"</option>";
      }
      $("#FifteenSort select[name=city]").html("").html(options);
    })
    .fail(function(jqXHR,textStatus,errorThrown){
      console.log(errorThrown.toString());
    })
  }
  function getoutlet(city)
  {
    var parameters = {city : city};
    $.getJSON("<?=base_url()?>MasterAdmin/GetOutlet",parameters)
    .done(function(data,textStatus,jqXHR){
      var options = "<option value='' selected></option>";
      for(var i=0;i<data.length;i++)
      {
        options += "<option value="+data[i].business_outlet_id+">"+data[i].business_outlet_name+"</option>";
      }
      $("#FifteenSort select[name=outlet]").html("").html(options);
    })
    .fail(function(jqXHR,texStatus,errorThrown){
      console.log(errorThrown.toString());
    })
  }
  function SalesGetCity(state)
  {
    var parameters = { state : state};
    $.getJSON("<?=base_url()?>MasterAdmin/GetCityStateWise",parameters)
    .done(function(data,textStatus,jqXHR){
      var options = "<option value='' selected></option>";
      for(var i=0;i<data.length;i++)
      {
        options += "<option value="+data[i].business_outlet_city+">"+data[i].business_outlet_city+"</option>";
      }
      $("#SalesSortBy select[name=city]").html("").html(options);
    })
    .fail(function(jqXHR,textStatus,errorThrown){
      console.log(errorThrown.toString());
    })
  }
  function SalesGetOutlet(city)
  {
    var parameters = {city : city};
    $.getJSON("<?=base_url()?>MasterAdmin/GetOutlet",parameters)
    .done(function(data,textStatus,jqXHR){
      var options = "<option value='' selected></option>";
      for(var i=0;i<data.length;i++)
      {
        options += "<option value="+data[i].business_outlet_id+">"+data[i].business_outlet_name+"</option>";
      }
      $("#SalesSortBy select[name=outlet]").html("").html(options);
    })
    .fail(function(jqXHR,texStatus,errorThrown){
      console.log(errorThrown.toString());
    })
  }
  function DailyGetCity(state)
  {
    var parameters = { state : state};
    $.getJSON("<?=base_url()?>MasterAdmin/GetCityStateWise",parameters)
    .done(function(data,textStatus,jqXHR){
      var options = "<option value='' selected></option>";
      for(var i=0;i<data.length;i++)
      {
        options += "<option value="+data[i].business_outlet_city+">"+data[i].business_outlet_city+"</option>";
      }
      $("#SortDaily select[name=city]").html("").html(options);
    })
    .fail(function(jqXHR,textStatus,errorThrown){
      console.log(errorThrown.toString());
    })
  }
  function DailyGetOutlet(city)
  {
    var parameters = {city : city};
    $.getJSON("<?=base_url()?>MasterAdmin/GetOutlet",parameters)
    .done(function(data,textStatus,jqXHR){
      var options = "<option value='' selected></option>";
      for(var i=0;i<data.length;i++)
      {
        options += "<option value="+data[i].business_outlet_id+">"+data[i].business_outlet_name+"</option>";
      }
      $("#SortDaily select[name=outlet]").html("").html(options);
    })
    .fail(function(jqXHR,texStatus,errorThrown){
      console.log(errorThrown.toString());
    })
  }
  function HistoricGetOutlet(city)
  {
    var parameters = {city : city};
    $.getJSON("<?=base_url()?>MasterAdmin/GetOutlet",parameters)
    .done(function(data,textStatus,jqXHR){
      var options = "<option value='' selected></option>";
      for(var i=0;i<data.length;i++)
      {
        options += "<option value="+data[i].business_outlet_id+">"+data[i].business_outlet_name+"</option>";
      }
      $("#HistoricTrends select[name=outlet]").html("").html(options);
    })
    .fail(function(jqXHR,texStatus,errorThrown){
      console.log(errorThrown.toString());
    })
  }
  function HistoricGetCity(state)
  {
    var parameters = { state : state};
    $.getJSON("<?=base_url()?>MasterAdmin/GetCityStateWise",parameters)
    .done(function(data,textStatus,jqXHR){
      var options = "<option value='' selected></option>";
      for(var i=0;i<data.length;i++)
      {
        options += "<option value="+data[i].business_outlet_city+">"+data[i].business_outlet_city+"</option>";
      }
      $("#HistoricTrends select[name=city]").html("").html(options);
    })
    .fail(function(jqXHR,textStatus,errorThrown){
      console.log(errorThrown.toString());
    })
  }
</script>
<script>
  $("#FifteenSort").validate({
    errorElement : "div",
    submitHandler:function(form){
      var formData = $("#FifteenSort").serialize();
      $.ajax({
        url:"<?=base_url()?>MasterAdmin/GetFifteenSalesBy",
        data : formData,
        type : "POST",
        // crossDomain : true,
        cache : false,
        // dataType : "json",
        success : function(data)
        {
          // alert("Hii");
          if(data.success == 'true')
          {
            var tableFifteen = '<thead><tr><th>Last 15 days</th><th>Total Sales</th><th>Service Revenue</th><th>Product Revenue</th><th>Package Revenue</th><th>#Visits</th></tr></thead><tbody>';
        	 totalsales = totalservice = totalpack = totalprod = totalbill = 0;
        //    alert(data.labels.length);
            for(i=0;i<data.labels.length;i++)
            {
				// alert(data.data_sales[i]);
              tableFifteen+="<tr>";
              tableFifteen+="<td>"+data.labels[i]+"</td>";
              tableFifteen+="<td>"+data.data_sales[i]+"</td>";
              tableFifteen+="<td>"+data.data_service[i]+"</td>";
              tableFifteen+="<td>"+data.data_prod[i]+"</td>";
			  tableFifteen+="<td>"+data.data_pack[i]+"</td>";
              tableFifteen+="<td>"+data.bill_count[i]+"</td>";
              tableFifteen+="</tr>";
              totalsales = parseInt(totalsales)+parseInt(data.data_sales[i]);
              totalservice = parseInt(totalservice)+parseInt(data.data_service[i]);
              totalprod = parseInt(totalprod)+parseInt(data.data_prod[i]);
            	totalpack = parseInt(totalpack)+parseInt(data.data_pack[i]);
             totalbill = parseInt(totalbill)+parseInt(data.bill_count[i]);

            }
			tableFifteen+="<tr><td>Total</td><td>"+totalsales+"</td><td>"+totalservice+"</td><td>"+totalprod+"</td><td>"+totalpack+"</td><td>"+totalbill+"</td></tr>";
			tableFifteen+="</tbody>";
            
            toastr["success"](data.message,"", {
                      positionClass: "toast-top-right",
                      progressBar: "toastr-progress-bar",
                      newestOnTop: "toastr-newest-on-top",
                      rtl: $("body").attr("dir") === "rtl" || $("html").attr("dir") === "rtl",
                      timeOut: 1000,
                      
                    });
					
                    $('#LastFifteenTrends').html("").html(tableFifteen);
          }
          else if (data.success == 'false')
          {
            
          }
        },
        error:function(data)
        {
          
        }
      });
    },
  });
</script>
<script>
  $().validate({
    errorElement : "div",
    rules:{
      "state" : {required : true},
      "city" : {required :true},
      "outlet" : {required : true}
    },
    submitHandler:function(form){
      var formData = $().serialize();
      $.ajax({
        url:"<?=base_url()?>MasterAdmin/GetFifteenSalesByOutletId/",
        data : formData,
        type : "POST",
        // crossDomain : true,
        cache : false,
        // dataType : "json",
        success : function(data)
        {
          if(data.success == 'true')
          {

          }
          else if (data.success == 'false')
          {

          }
        },
        error:function(data)
        {
          
        }
      });
    },
  });
</script>
<script>
  $('#SortDaily').validate({
    errorElement : "div",
    submitHandler:function(form){
      var formData = $('#SortDaily').serialize();
      $.ajax({
        url:"<?=base_url()?>MasterAdmin/GetDailyTrendsByOutlet",
        data : formData,
        type : "POST",
        // crossDomain : true,
        cache : false,
        // dataType : "json",
        success : function(data)
        {
          var tableDaily ='<thead><tr><th>#</td><th>Total Sales</th><th>Service Sale</th><th>Package Sale</th><th>Product Sale</th><th>#Bill</th></tr></thead><tbody><tr><td>FTD</td><td id="ftd_total">'+data[12]+'</td><td id="ftd_service">'+data[0].total_sales+'</td><td id="ftd_package">'+data[8].package_sales+'</td><td id="ftd_product">'+data[1].total_sales+'</td><td id="ftd_bill_count">'+data[13]+'</td></tr><tr><td>MTD</td><td id="mtd_total">'+data[14]+'</td><td id="mtd_service">'+parseInt(data[2].total_sales)+'</td><td id="mtd_package">'+parseInt(data[9].packages)+'</td><td id="mtd_product">'+parseInt(data[3].total_sales)+'</td><td id="mtd_bill_count">'+parseInt(data[15])+'</td></tr><tr><td>LMTD</td><td id="lmtd_total">'+parseInt(data[16])+'</td><td id="lmtd_service">'+parseInt(data[4].total_sales)+'</td><td id="lmtd_package">'+parseInt(data[10].packages)+'</td><td id="lmtd_product">'+parseInt(data[5].total_sales)+'</td><td id="lmtd_bill_count">'+data[17]+'</td></tr><tr><td>Last Month Full</td><td id="lmf_total">'+parseInt(data[18])+'</td><td id="lmf_service">'+parseInt(data[6].total_sales)+'</td><td id="lmf_package">'+parseInt(data[11].packages)+'</td><td id="lmf_product">'+parseInt(data[7].total_sales)+'</td><td id="lmf_bill_count">'+data[19]+'</td></tr></tbody>'
			// if(data.success == 'true'){ 
                      toastr["success"](data.message,"", {
                      positionClass: "toast-top-right",
                      progressBar: "toastr-progress-bar",
                      newestOnTop: "toastr-newest-on-top",
                      rtl: $("body").attr("dir") === "rtl" || $("html").attr("dir") === "rtl",
                      timeOut: 1000,
                      
                    });
                    $('#dailyTrends').html("").html(tableDaily);
                    
                  // else if (data.success == 'false'){                   
                  //   if($('.feedback1').hasClass('alert-success')){
                  //     $('.feedback1').removeClass('alert-success').addClass('alert-danger');
                      
                  //   }
                  //   else{
                  //     $('.feedback1').addClass('alert-danger');
                  //   }
                  //   $('.alert-message').html("").html(data.message); 
                  // }
                },
                error: function(data){
                  $('.feedback1').addClass('alert-danger');
                  $('.alert-message').html("").html(data.message); 
                }
      });
    },
  });
</script>