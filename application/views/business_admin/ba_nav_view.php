<nav class="sidebar <?php if(isset($sidebar_collapsed)){ if($sidebar_collapsed){ echo 'sidebar-collapsed toggled'; }} ?>">
	<div class="sidebar-content">
		<a class="sidebar-brand" href="<?=base_url()?>index.php/BusinessAdmin/">
 		 <img src="<?=base_url()?>public/images/marks_logo.png" width="25px" height="25px" class="img-responsive">
	   <span class="align-middle">MarkS ReTech</span>
    </a>

		<ul class="sidebar-nav">
			<?php
				if(array_search('POS System', $business_admin_packages) !== false):
			?>
			<li class="sidebar-item">
				<a href="#dashboards" data-toggle="collapse" class="sidebar-link collapsed">
		      <i class="align-middle" data-feather="sliders" style="color:#0070c0;"></i> <span class="align-middle">Business Panel</span>
		    </a>
				<ul id="dashboards" class="sidebar-dropdown list-unstyled collapse show">
					<li class="sidebar-item"><a class="sidebar-link" href="<?=base_url()?>index.php/BusinessAdmin/Dashboard/"><i data-feather="grid" style="color:#0070c0;"></i>Dashboard</a></li>
					<li class="sidebar-item">
						<a href="#configurations" data-toggle="collapse" class="sidebar-link collapsed">
		          <span class="align-middle"><i data-feather="settings" style="color:#d50f25;"></i>Configurations</span>
		        </a>
				    <ul id="configurations" class="sidebar-dropdown list-unstyled collapse">
			        <li class="sidebar-item"><a class="sidebar-link" href="<?=base_url()?>index.php/BusinessAdmin/AddOutlet/"><i data-feather="home" style="color:#eeb211;"></i>Outlets</a></li>
			    		<li class="sidebar-item"><a class="sidebar-link" href="<?=base_url()?>index.php/BusinessAdmin/Inventory/"><i data-feather="grid" style="color:#009925;"></i>Composition</a></li>
			    		<!--<li class="sidebar-item"><a class="sidebar-link" href="<?=base_url()?>index.php/BusinessAdmin/InventoryHealth/"><i data-feather="grid" style="color:#d50f25;"></i>Inventory Health</a></li>-->
			    		<li class="sidebar-item"><a class="sidebar-link" href="<?=base_url()?>index.php/BusinessAdmin/MenuManagement/"><i data-feather="menu" style="color:#d50f25;"></i>Menu Management</a></li>
			    		<li class="sidebar-item"><a class="sidebar-link" href="<?=base_url()?>index.php/BusinessAdmin/BusinessAdminAddPackage/"><i data-feather="gift" style="color:#0070c0;"></i>Packages</a></li>
			    		<li class="sidebar-item"><a class="sidebar-link" href="<?=base_url()?>index.php/BusinessAdmin/AddEmployee/"><i data-feather="users" style="color:d50f25;"></i>Employee</a></li>
						<li class="sidebar-item"><a class="sidebar-link" href="<?=base_url()?>index.php/BusinessAdmin/ConfigExpense/"><i data-feather="file-text" style="color:#eeb211;"></i>Expense</a></li>
					
				    </ul>
					</li>
						<li class="sidebar-item"><a class="sidebar-link" href="<?=base_url()?>index.php/BusinessAdmin/ReportsManagement"><i data-feather="book" style="color:#009925;"></i>Reports</a></li>	
				    
				</ul>
			</li>
			
			<li class="sidebar-item">
                <a href="#inventory" data-toggle="collapse" class="sidebar-link collapsed">
              <i class="align-middle" data-feather="users" style="color:#0070c0;"></i> <span class="align-middle">Inventory</span>
            </a>
                <ul id="inventory" class="sidebar-dropdown list-unstyled collapse">
                    <li class="sidebar-item"><a class="sidebar-link" href="<?=base_url()?>index.php/BusinessAdmin/InventoryHealth/"><i data-feather="grid" style="color:#d50f25;"></i>Inventory Health</a></li>
                    <li class="sidebar-item"><a class="sidebar-link" href="<?=base_url()?>index.php/BusinessAdmin/AddInventory/"><i data-feather="grid" style="color:#d50f25;"></i>Inventory</a></li>
                </ul>
            </li>
			<!--  -->
			<?php
				endif;

				if(array_search('Marks360', $business_admin_packages) !== false):
			?>
			<li class="sidebar-item">
				<a href="#autoengage" data-toggle="collapse" class="sidebar-link collapsed">
		      <i class="align-middle" data-feather="users" style="color:#0070c0;"></i> <span class="align-middle">Loyalty</span>
		    </a>
				<ul id="autoengage" class="sidebar-dropdown list-unstyled collapse">
					  
					<!--<li class="sidebar-item"><a class="sidebar-link" href="<?=base_url()?>index.php/BusinessAdmin/Loyalty"><i class="fa fa-rupee-sign" style="color:#009925;"></i>Loyalty Wallet</a></li>-->
					<li class="sidebar-item"><a class="sidebar-link" href="<?=base_url()?>index.php/BusinessAdmin/LoyaltyDashBoardIntegrated"><i class="fa fa-rupee-sign" style="color:#009925;"></i>Loyalty Management</a></li>
				    
				</ul>
			</li>
			<?php
			endif;	
			if(array_search('Deals&Discount', $business_admin_packages) !== false):
					?>
					<li class="sidebar-item">
						<a href="#deals" data-toggle="collapse" class="sidebar-link collapsed">
							<i class="align-middle" data-feather="gift" style="color:#0070c0;"></i> <span class="align-middle">Deals & Discount</span>
						</a>
						<ul id="deals" class="sidebar-dropdown list-unstyled collapse">
							 <li class="sidebar-item"><a class="sidebar-link" href="<?=base_url()?>index.php/BusinessAdmin/DealsDiscount"><i data-feather="clock" style="color:#0070c0;"></i>Deals & Discount</a></li>
							<!--<li class="sidebar-item"><a class="sidebar-link" href="<?=base_url()?>index.php/BusinessAdmin/Loyalty"><i class="fa fa-rupee-sign" style="color:#009925;"></i>Loyalty Wallet</a></li>-->
							<!-- <li class="sidebar-item"><a class="sidebar-link" href="<?=base_url()?>index.php/BusinessAdmin/LoyaltyDashBoardIntegrated"><i class="fa fa-rupee-sign" style="color:#009925;"></i>Loyalty Management</a></li> -->
								
						</ul>
					</li>
					<?php
				endif;
				if(array_search('customer_engagement', $business_admin_packages) !== false):
			?>
			<li class="sidebar-item">
                <a href="#customers" data-toggle="collapse" class="sidebar-link collapsed">
              <i class="align-middle" data-feather="users" style="color:#0070c0;"></i> <span class="align-middle">Customers Engagement</span>
            </a>
                <ul id="customers" class="sidebar-dropdown list-unstyled collapse">
                     <!--<li class="sidebar-item"><a class="sidebar-link" href="<?=base_url()?>index.php/BusinessAdmin/CustomerBirthDayAnniver"><i data-feather="clock" style="color:#0070c0;"></i>Bday & Anniversary</a></li> -->
                    <li class="sidebar-item"><a class="sidebar-link" href="<?=base_url()?>index.php/BusinessAdmin/TxnHistory"><i data-feather="clock" style="color:#0070c0;"></i>Transaction History</a></li>
                    <li class="sidebar-item"><a class="sidebar-link" href="<?=base_url()?>index.php/BusinessAdmin/Engagement"><i data-feather="book" style="color:#0070c0;"></i>Engagement</a></li>
                     <li class="sidebar-item"><a class="sidebar-link" href="<?=base_url()?>index.php/BusinessAdmin/Campaigns"><i data-feather="calendar" style="color:blue;"></i>Campaign Manager</a></li>
                    <li class="sidebar-item"><a class="sidebar-link" href="<?=base_url()?>index.php/BusinessAdmin/AutoEngage"><i data-feather="clock" style="color:#0070c0;"></i>Auto Engage</a></li>
                    <li class="sidebar-item"><a class="sidebar-link" href="<?=base_url()?>index.php/BusinessAdmin/Tags"><i data-feather="calendar" style="color:blue;"></i>Tags</a></li>
                    <li class="sidebar-item"><a class="sidebar-link" href="<?=base_url()?>index.php/BusinessAdmin/GoogleReviews"><i data-feather="book" style="color:#0070c0;"></i>Google Reviews</a></li>
                </ul>
            </li>
            
            <?php
				endif;
				if(array_search('EMSS', $business_admin_packages) !== false):
			?>
			<li class="sidebar-item">
				<a href="#EMSS" data-toggle="collapse" class="sidebar-link collapsed">
		      <i class="align-middle" data-feather="user" style="color:#0070c0;"></i> <span class="align-middle">People</span>
		    </a>
				<ul id="EMSS" class="sidebar-dropdown list-unstyled collapse">
					<li class="sidebar-item"><a class="sidebar-link" href="<?=base_url()?>index.php/BusinessAdmin/Employee_details"><i data-feather="book" style="color:#0070c0;"></i>Details</a></li>
					<li class="sidebar-item"><a class="sidebar-link" href="<?=base_url()?>index.php/BusinessAdmin/Attendance"><i data-feather="calendar" style="color:blue;"></i>Attendance</a></li>
					<li class="sidebar-item"><a class="sidebar-link" href="<?=base_url()?>index.php/BusinessAdmin/CheckEmployeeSalary"><i class="fa fa-rupee-sign" style="color:red;"></i>Salary</a></li>
					<li class="sidebar-item"><a class="sidebar-link" href="<?=base_url()?>index.php/BusinessAdmin/AdvancePayment"><i class="fa fa-rupee-sign" style="color:red;"></i>Advance Payment</a></li>
					<li class="sidebar-item"><a class="sidebar-link" href="<?=base_url()?>index.php/BusinessAdmin/Commission_opening"><i class="fa fa-rupee-sign" style="color:#009925;"></i>Commission</a></li>
					<li class="sidebar-item"><a class="sidebar-link" href="<?=base_url()?>index.php/BusinessAdmin/Holidays"><i data-feather="sun" style="color:orange;"></i>Holidays</a></li>
				</ul>
			</li>
		</li>
			<?php
				endif;
			?>
			<?php
				if(array_search('Appointments', $business_admin_packages) !== false):
			?>
			<li class="sidebar-item">
				<a href="#tryst" data-toggle="collapse" class="sidebar-link collapsed">
					<i class="align-middle" data-feather="clock" style="color:#0070c0"></i> <span class="align-middle">TRYST</span>
				</a>
				<ul id="tryst" class="sidebar-dropdown list-unstyled collapse ">
					<!-- <li class="sidebar-item"><a class="sidebar-link" href="<?=base_url()?>index.php/BusinessAdmin/Appointment/"><i data-feather="watch" style="color:orange"></i>View Appointment</a></li> -->
					<li class="sidebar-item"><a class="sidebar-link" href="<?=base_url()?>index.php/BusinessAdmin/IntegrateFacebook/"><i data-feather="watch" style="color:orange"></i>Integrate Facebook Appointment</a></li>
				
				</ul>
			</li>
			
			<?php
				endif;
			?>
			<!--  -->
			<!--<li class="sidebar-item">-->
   <!--             <a href="#Engagement" data-toggle="collapse" class="sidebar-link collapsed">-->
   <!--           <i class="align-middle" data-feather="user" style="color:#0070c0;"></i> <span class="align-middle">Engagement</span>-->
   <!--         </a>-->
   <!--             <ul id="Engagement" class="sidebar-dropdown list-unstyled collapse">-->
                    
   <!--             </ul>-->
   <!--         </li>-->
            <!-- -->
			<li class="sidebar-header">
				Other Options
			</li>
			<li class="sidebar-item">
				<a href="#ui" data-toggle="collapse" class="sidebar-link collapsed">
		          <i class="align-middle" data-feather="settings" style="color:#0070c0;"></i> <span class="align-middle">Settings</span>
		        </a>
				<ul id="ui" class="sidebar-dropdown list-unstyled collapse ">
					<li class="sidebar-item"><a class="sidebar-link" href="<?=base_url()?>index.php/BusinessAdmin/Logout/"><i data-feather="log-out" style="color:#eeb211;"></i>Logout</a></li>
					<li class="sidebar-item"><a class="sidebar-link" href="<?=base_url()?>index.php/BusinessAdmin/ResetBusinessAdminPassword/"><i data-feather="grid" style="color:#3369e8;"></i>Reset Password</a></li>
				</ul>
			</li>
		</ul>
		<div class="sidebar-bottom d-none d-lg-block">
			<div class="media">
				<img class="rounded-circle mr-3" src="<?=base_url()?>public/images/default.png" alt="Business Admin" width="40" height="40">
				<div class="media-body">
					<h5 class="mb-1"><?=$business_admin_details['business_admin_first_name']?></h5>
					<div>
						<i class="fas fa-circle text-success"></i> Online
					</div>
				</div>
			</div>
		</div>
	</div>
</nav>
