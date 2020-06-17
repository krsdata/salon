<nav class="navbar navbar-expand navbar-light bg-white">
	<a class="sidebar-toggle d-flex mr-2">
		<i class="hamburger align-self-center"></i>
	</a>
	<div class="navbar-collapse collapse">
		<ul class="navbar-nav ml-auto">
			<!--  -->
			<!-- <li class="nav-item dropdown">
				<a class="nav-icon dropdown-toggle" href="#" id="alertsDropdown" data-toggle="dropdown">
					<div class="position-relative">
						<i class="align-middle text-white" data-feather="bell"></i>
						<span class="indicator" style="background-color:red;">4</span>
					</div>
				</a>
				<div class="dropdown-menu dropdown-menu-lg dropdown-menu-right py-0" aria-labelledby="alertsDropdown">
					<div class="dropdown-menu-header">
						4 New Notifications
					</div>
					<div class="list-group">
						<a href="#" class="list-group-item">
							<div class="row no-gutters align-items-center">
								<div class="col-2">
									<i class="text-danger" data-feather="alert-circle"></i>
								</div>
								<div class="col-10">
									<div class="text-dark">Update completed</div>
									<div class="text-muted small mt-1">Restart server 12 to complete the update.</div>
									<div class="text-muted small mt-1">2h ago</div>
								</div>
							</div>
						</a>
						<a href="#" class="list-group-item">
							<div class="row no-gutters align-items-center">
								<div class="col-2">
									<i class="text-warning" data-feather="bell"></i>
								</div>
								<div class="col-10">
									<div class="text-dark">Lorem ipsum</div>
									<div class="text-muted small mt-1">Aliquam ex eros, imperdiet vulputate hendrerit et.</div>
									<div class="text-muted small mt-1">6h ago</div>
								</div>
							</div>
						</a>
						<a href="#" class="list-group-item">
							<div class="row no-gutters align-items-center">
								<div class="col-2">
									<i class="text-primary" data-feather="home"></i>
								</div>
								<div class="col-10">
									<div class="text-dark">Login from 192.186.1.1</div>
									<div class="text-muted small mt-1">8h ago</div>
								</div>
							</div>
						</a>
						<a href="#" class="list-group-item">
							<div class="row no-gutters align-items-center">
								<div class="col-2">
									<i class="text-success" data-feather="user-plus"></i>
								</div>
								<div class="col-10">
									<div class="text-dark">New connection</div>
									<div class="text-muted small mt-1">Anna accepted your request.</div>
									<div class="text-muted small mt-1">12h ago</div>
								</div>
							</div>
						</a>
					</div>
					<div class="dropdown-menu-footer">
						<a href="#" class="text-muted">Show all notifications</a>
					</div>
				</div>
			</li> -->
			<!--  -->
			<!-- <li class="nav-item dropdown">
				<a class="nav-flag dropdown-toggle" href="#" id="languageDropdown" data-toggle="dropdown">
					<img src="http:\\localhost\Login\public\app_stack\img\flags\us.png" alt="English" />
				</a>
				<div class="dropdown-menu dropdown-menu-right" aria-labelledby="languageDropdown">
					<a class="dropdown-item" href="#">
						<img src="http:\\localhost\Login\public\app_stack\img\flags\us.png" alt="English" width="20" class="align-middle mr-1" />
						<span class="align-middle">English</span>
					</a>
					<a class="dropdown-item" href="#">
						<img src="http:\\localhost\Login\public\app_stack\img\flags\in.png" alt="Spanish" width="20" class="align-middle mr-1" />
						<span class="align-middle">Hindi</span>
					</a>
					<a class="dropdown-item" href="#">
						<img src="http:\\localhost\Login\public\app_stack\img\flags\es.png" alt="German" width="20" class="align-middle mr-1" />
						<span class="align-middle">German</span>
					</a>
					<a class="dropdown-item" href="#">
						<img src="http:\\localhost\Login\public\app_stack\img\flags\nl.png" alt="Dutch" width="20" class="align-middle mr-1" />
						<span class="align-middle">Dutch</span>
					</a>
				</div>
			</li> -->
			<!--  -->
			<!-- <li class="nav-item dropdown">
				<a class="nav-link dropdown-toggle d-none d-sm-inline-block text-white" href="#" data-toggle="dropdown">
			    <img src="<?=base_url()?>public/images/shop_icon.jpg" class="avatar img-fluid rounded-circle mr-1" alt="Business Admin" /> 
			    <?php
			    	if(!isset($selected_outlet)){
			    ?>
			    	<span class="text-white">Select Outlet</span>
			    <?php
			    	}
			    	else{
			    ?>
			    	<span class="text-white"><?=$selected_outlet['business_outlet_name']?></span>
			    <?php
			    	}
			    ?>
			  	</a>
				<div class="dropdown-menu dropdown-menu-right">
					<?php 
						if(empty($business_outlet_details) || !isset($business_outlet_details)){
					?>
							<a class="dropdown-item" href="#">No outlet added!</a>
					<?php 
						}
						else{
							foreach ($business_outlet_details as $outlet) {
						?>
							<a class="dropdown-item text-dark" href="<?=base_url()?>index.php/BusinessAdmin/SelectOutlet/<?=$outlet['business_outlet_id']?>"><?=$outlet['business_outlet_name']?></a>
					<?php
							}
						}
					?>
				</div>
			</li> -->
			<li class="nav-item dropdown">
				<a class="nav-link dropdown-toggle d-none d-sm-inline-block text-white" href="#" data-toggle="dropdown">
			    <img src="<?=base_url()?>public/images/default.png" class="avatar img-fluid rounded-circle mr-1" alt="Business Admin" /> <span class="text-white"></span>
			  	</a>
				<div class="dropdown-menu dropdown-menu-right">
					<a class="dropdown-item" href="<?=base_url()?>index.php/MasterAdmin/Profile/"><i class="align-middle mr-1" data-feather="user"></i> Profile</a>
					<a class="dropdown-item" href="<?=base_url()?>index.php/MasterAdmin/Logout/">Sign out</a>
				</div>
			</li>
		</ul>
	</div>
</nav>
