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
				<h1 class="h3 mb-3">Salary Management</h1>
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
								<div class="form-row">
									<div class="col-md-6">
										<h5 class="card-title" style="padding:8px">Salary</h5>
									</div>
									<div class="col-md-6">
										<h5 class="card-title float-right" style="padding:8px"><a href="<?=base_url()?>index.php/BusinessAdmin/CheckEmployeeSalary/">Check Salary</a></h5>
									</div>
								</div>
							</div>
							<div class="card-body">
								<div class="col-md-12 col-lg-12 d-flex">
									<div class="card flex-fill w-100">
									<div class="card-header">
										<div class="card-actions float-right">
											<div class="dropdown show">
												<a href="#" data-toggle="dropdown" data-display="static">
													<i class="align-middle" data-feather="more-horizontal"></i>
												</a>

												<div class="dropdown-menu dropdown-menu-right">
													<a class="dropdown-item" href="#">Action</a>
													<a class="dropdown-item" href="#">Another action</a>
													<a class="dropdown-item" href="#">Something else here</a>
												</div>
											</div>
										</div>
										<h5 class="card-title mb-0">Salary / Commission</h5>
									</div>
									<div class="card-body d-flex w-100">
										<div class="align-self-center chart chart-lg">
											<canvas id="chartjs-dashboard-bar"></canvas>
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
<script>
		$(function() {
			// Bar chart
			new Chart(document.getElementById("chartjs-dashboard-bar"), {
				type: "bar",
				data: {
					labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
					datasets: [{
						label: "Salary",
						backgroundColor: window.theme.primary,
						borderColor: window.theme.primary,
						hoverBackgroundColor: window.theme.primary,
						hoverBorderColor: window.theme.primary,
						data: [54, 67, 41, 55, 62, 45, 55, 73, 60, 76, 48, 79]
					}, {
						label: "Commission",
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
								stepSize: 1000
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
