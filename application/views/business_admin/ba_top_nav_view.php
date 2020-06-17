<nav class="navbar navbar-expand navbar-light bg-white">
	<a class="sidebar-toggle d-flex mr-2">
		<i class="hamburger align-self-center"></i>
	</a>
	<div class="navbar-collapse collapse">
		<ul class="navbar-nav ml-auto">
			<li class="nav-item dropdown">
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
			</li>
			<li class="nav-item dropdown">
				<a class="nav-link dropdown-toggle d-none d-sm-inline-block text-white" href="#" data-toggle="dropdown">
			    <img src="<?=base_url()?>public/images/default.png" class="avatar img-fluid rounded-circle mr-1" alt="Business Admin" /> <span class="text-white"><?=$business_admin_details['business_admin_first_name']?> <?=$business_admin_details['business_admin_last_name']?></span>
			  	</a>
				<div class="dropdown-menu dropdown-menu-right">
					<a class="dropdown-item" href="<?=base_url()?>index.php/BusinessAdmin/Profile/"><i class="align-middle mr-1" data-feather="user"></i> Profile</a>
					<a class="dropdown-item" href="<?=base_url()?>index.php/BusinessAdmin/Logout/">Sign out</a>
				</div>
			</li>
		</ul>
	</div>
</nav>