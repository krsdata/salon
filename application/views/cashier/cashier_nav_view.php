<nav class="sidebar <?php if(isset($sidebar_collapsed)){ if($sidebar_collapsed){ echo 'sidebar-collapsed toggled'; }} ?>">
	<div class="sidebar-content">
		<a class="sidebar-brand" href="<?=base_url()?>Cashier/">
 		 	<img src="<?=base_url()?>public/images/salonfirst.jpeg" width="200px" height=75px" class="img-responsive">
	   	<!-- <span class="align-middle">Salonfirst</span> -->
    </a>
		<ul class="sidebar-nav">
			<?php
				if(array_search('POS System', $business_admin_packages) !== false):
			?>
			<li class="sidebar-item">
				<a href="#dashboards" data-toggle="collapse" class="sidebar-link">
          <i class="align-middle" data-feather="sliders" style="color:#0070c0;"></i> <span class="align-middle">BILLIT</span>
        </a>
				<ul id="dashboards" class="sidebar-dropdown list-unstyled collapse show">
					<li class="sidebar-item active"><a class="sidebar-link" href="<?=base_url()?>Cashier/Dashboard/"><i data-feather="shopping-cart" style="color:red"></i>Billing</a></li>
						<li class="sidebar-item"><a class="sidebar-link" href="<?=base_url()?>Cashier/Expenses/"><i class="fa fa-book" style="color:orange"></i>Expenses</a></li>
						<li class="sidebar-item"><a class="sidebar-link" href="<?=base_url()?>Cashier/Inventory/"><i data-feather="pen-tool" style="color:#0070c0"></i>Inventory</a></li>
					</li>
				</ul>
			</li>
			<li class="sidebar-item">
				<a href="#packages" data-toggle="collapse" class="sidebar-link collapsed">
					<i class="align-middle" data-feather="gift" style="color:#0070c0"></i> <span class="align-middle">Packages</span>
				</a>
				<ul id="packages" class="sidebar-dropdown list-unstyled collapse ">
					<li class="sidebar-item"><a class="sidebar-link" href="<?=base_url()?>Cashier/BuyPackages/"><i data-feather="shopping-bag" style="color:orange"></i>Buy Packages</a></li>
					<li class="sidebar-item"><a class="sidebar-link" href="<?=base_url()?>Cashier/ActivePackages/"><i data-feather="grid" style="color:green"></i>Active Packages</a></li>
					<li class="sidebar-item"><a class="sidebar-link" href="<?=base_url()?>Cashier/PackagesHistory/"><i data-feather="book" style="color:blue"></i>Redemption History</a></li>
				</ul>
			</li>
			<?php
				endif;
			?>			
			<?php
				if(array_search('Appointments', $business_admin_packages) !== false):
			?>
			<li class="sidebar-item">
				<a href="#tryst" data-toggle="collapse" class="sidebar-link collapsed">
					<i class="align-middle" data-feather="clock" style="color:#0070c0"></i> <span class="align-middle">Appointment</span>
				</a>
				<ul id="tryst" class="sidebar-dropdown list-unstyled collapse ">
					<li class="sidebar-item"><a class="sidebar-link" href="<?=base_url()?>Cashier/Appointment/"><i data-feather="watch" style="color:orange"></i>View Appointment</a></li>
				
				</ul>
			</li>
			
			<?php
				endif;
			?>
			<!-- loyalty	 -->
				<?php
				if(array_search('Marks360', $business_admin_packages) !== false):
			?>
			<li class="sidebar-item">
				<a href="#autoengage" data-toggle="collapse" class="sidebar-link collapsed">
		      <i class="align-middle" data-feather="users" style="color:#0070c0;"></i> <span class="align-middle">Loyalty</span>
		    </a>
				<ul id="autoengage" class="sidebar-dropdown list-unstyled collapse">
					<li class="sidebar-item"><a class="sidebar-link" href="<?=base_url()?>Cashier/Loyalty"><i class="fa fa-rupee-sign" style="color:green"></i>Loyalty_wallet</a></li>
				</ul>
			</li>
			<?php
				endif;
			?>
			<li class="sidebar-item">
                <a href="#txnhistory" data-toggle="collapse" class="sidebar-link collapsed">
                    <i class="far fa-credit-card"  style="color:#0070c0"></i> <span class="align-middle">Customers</span>
                </a>
                <ul id="txnhistory" class="sidebar-dropdown list-unstyled collapse ">
                    <li class="sidebar-item"><a class="sidebar-link" href="<?=base_url()?>Cashier/TxnHistory/"><i class="far fa-credit-card"></i>Transaction History</a></li>
                </ul>
                <!--<ul id="txnhistory" class="sidebar-dropdown list-unstyled collapse ">-->
                <!--    <li class="sidebar-item"><a class="sidebar-link" href="<?=base_url()?>Cashier/CustomerBirthDayAnniver/"><i class="far fa-cake"></i>Bday & Anniversary</a></li>-->
                <!--</ul>-->
            </li>
			<?php
				if(array_search('EMSS', $business_admin_packages) !== false):
			?>
			<li class="sidebar-item">
				<a href="#EMSS" data-toggle="collapse" class="sidebar-link collapsed">
		      <i class="align-middle" data-feather="users" style="color:#0070c0;"></i> <span class="align-middle">People</span>
		    </a>
				<ul id="EMSS" class="sidebar-dropdown list-unstyled collapse">
				<li class="sidebar-item"><a class="sidebar-link" href="<?=base_url()?>Cashier/Attendance/"><i data-feather="calendar"></i>Mark Attendance</a></li> 
				</ul>
			</li>
			<?php
				endif;
			?>
			<li class="sidebar-item"><a class="sidebar-link" href="<?=base_url()?>Cashier/ReportsManagement"><i data-feather="book" style="color:#009925;"></i>Reports</a></li>	
			<li class="sidebar-item"><a class="sidebar-link" href="<?=base_url()?>Cashier/daybook"><i data-feather="clipboard" style="color:#009925;"></i>Day Book</a></li>	
			<li class="sidebar-item"><a class="sidebar-link" href="<?=base_url()?>Cashier/cashbook"><i data-feather="clipboard" style="color:#009925;"></i>Cash Book</a></li>	
			<li class="sidebar-item"><a class="sidebar-link" href="<?=base_url()?>Cashier/Video"><i data-feather="video"></i>Demo Videos</a></li>
			<li class="sidebar-header">
				Other Options
			</li>
			<li class="sidebar-item">
				<a href="#ui" data-toggle="collapse" class="sidebar-link collapsed">
		          <i class="align-middle" data-feather="settings" style="color:#0070c0;"></i> <span class="align-middle">Settings</span>
		        </a>
				<ul id="ui" class="sidebar-dropdown list-unstyled collapse ">
					<li class="sidebar-item"><a class="sidebar-link" href="<?=base_url()?>Cashier/Logout/"><i class="align-middle" data-feather="log-out" style="color:red"></i>Logout</a></li>
					<li class="sidebar-item"><a class="sidebar-link" href="<?=base_url()?>Cashier/ResetCashierPassword/"><i class="align-middle" data-feather="grid" style="color:#009925"></i>Reset Password</a></li>
				</ul>
			</li>
		</ul>
		<div class="sidebar-bottom d-none d-lg-block">
			<div class="media">
				<img class="rounded-circle mr-3" src="<?=base_url()?>public/images/default.png" alt="Cashier Admin" width="40" height="40">
				<div class="media-body">
					<h5 class="mb-1"><?=$cashier_details['employee_first_name']?></h5>
					<div>
						<i class="fas fa-circle text-success"></i> Online
					</div>
				</div>
			</div>
		</div>
	</div>
</nav>
