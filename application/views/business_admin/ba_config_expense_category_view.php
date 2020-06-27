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
                <h1 class="h3 mb-3">Expense Management</h1>
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
                    <!--  -->
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header" style="margin-left:10px;">
                                <ul class="nav nav-pills card-header-pills pull-right" role="tablist" style="font-weight: bold">
                                    <li class="nav-item">
                                        <a class="nav-link active" data-toggle="tab" href="#tab-1">History</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" data-toggle="tab" href="#tab-2">Category</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" data-toggle="tab" href="#tab-4">Vendors</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" data-toggle="tab" href="#tab-5">Pending Payments</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" data-toggle="tab" href="#tab-3">Insights</a>
                                    </li>
                                </ul>
                            </div>
                            <div class="card-body">
                                <div class="tab-content">
                                    <div class="tab-pane fade show active" id="tab-1" role="tabpanel">
                                    <div class="row">
										<div class="form-group col-md-6">
                                            <button class="btn btn-success mb-2" data-toggle="modal"
                                            data-target="#ModalAddExpense"><i class="fas fa-fw fa-plus"></i>Add
                                            Expense</button>
                                            <!-- <button class="btn btn-primary float-right mb-2"
                                            onclick="exportTableToExcel('adminExpense','Expense')"><i
                                                class="fa fa-file-export"></i>Export</button> -->
                                        </div> 
                                        <div class="form-group col-md-2">       
                                                <select id="expense_group" class="btn form-control" style="font-weight:bold;background-color:#f7f7f8"> 
                                                    <option selected disabled>Select</option>
                                                    <option value="7days">Last 7 Days</option>
                                                    <option value="30days">Last 30 Days</option>
                                                    <option value="mtd">MTD</option>
                                                    <option value="range">Date Range</option>
                                                </select>
                                        </div> 
                                        <!--<div class="form-group col-md-2" id="altrdiv">-->

                                        <!--</div>       -->
                                        <div class="form-group col-md-2" id="hide" hidden>        
                                                <!-- <input type="text" name="to_date"  id="to_date" class="btn bg-white date" placeholder="To Date" hidden> -->
                                                <input class="form-control" type="text" id="daterange" name="daterange" style="float: right" placeholder="Select Range"/>
                                        </div>
                                        <!-- <div class="btn bg-white col-sm-2">       
                                                <input type="text" name="from_date" id="from_date" style="float: left" class="btn bg-white date" placeholder="From Date" hidden>
                                        </div>
                                        <div class="btn bg-white col-md-2">        
                                                <input type="text" name="to_date" style="float: left" id="to_date" class="btn bg-white date" placeholder="To Date" hidden>
                                        </div> -->
                                        <div class="form-group col-md-2">        
                                            <button class="btn btn-primary download float-left mb-2" style="float: right" id="download"><i
                                                class="fa fa-download"></i>Download</button>  
                                        </div>
                                    </div>    
                                        <table class="table table-hover table-striped datatables-basic"
                                            id="adminExpense" style="width: 100%;text-align:center">
                                            <thead>
                                                <tr class="text-primary">
                                                    <!--<th>Sr No</th>-->
                                                    <th>Expense Id</th>
                                                    <th>Date</th>
                                                    <th>Expense Type</th>
                                                    <th>Item Name</th>
                                                    <th>Cashier Name</th>
                                                    <th>Payement Type</th>
                                                    <th>Paid To</th>
                                                    <th>Total Amount</th>
                                                    <th>Amount</th>
                                                    <th>Pending Amount</th>
                                                    <th>Mode</th>
                                                    <th>Payment Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $i=0;
											foreach ($all_expenses as $expense):
											?>
                                                <tr>
                                                    <!--<td><?=$i=$i+1;?></td>-->
                                                    <td><?=$expense['expense_unique_serial_id']?></td>
                                                    <td><?=$expense['expense_date']?></td>
                                                    <td><?=$expense['expense_type']?></td>
                                                    <td><?=$expense['item_name']?></td>
                                                    <td><?=$expense['employee_name']?></td>
                                                    <td><?=$expense['payment_type']?></td>
                                                    <td><?=$expense['payment_to_name']?></td>
                                                    <td><?=$expense['total_amount']?></td>
                                                    <td><?=$expense['amount']?></td>
                                                    <td><?=$expense['pending_amount']?></td>
                                                    <td><?=$expense['payment_mode']?></td>
                                                    <td><?=$expense['expense_status']?></td>
                                                </tr>
                                                <?php	                    	
												endforeach;
											?>
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="tab-pane fade" id="tab-2" role="tabpanel">
                                        <button class="btn btn-success" data-toggle="modal"
                                            data-target="#ModalAddExpenseCategory"><i class="fas fa-fw fa-plus"></i>Add
                                            Expense Category</button><br><br>
                                        <table class="table table-hover table-striped datatables-basic"
                                            style="width: 100%;">
                                            <thead>
                                                <tr class="text-primary">
                                                    <th>Category Code</th>
                                                    <th>Name</th>
                                                    <th>Description</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
													foreach ($expense_types as $type):
												?>
                                                <tr>
                                                    <td><?=$type['expense_type_id']?></td>
                                                    <td><?=$type['expense_type']?></td>
                                                    <td><?=$type['expense_type_description']?></td>
                                                    <td class="table-action">
                                                        <button type="button"
                                                            class="btn btn-primary expense-type-edit-btn"
                                                            expense_type_id="<?=$type['expense_type_id']?>">
                                                            <i class="align-middle" data-feather="edit-2"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                                <?php		
													endforeach;
												?>
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="tab-pane fade" id="tab-3" role="tabpanel">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="card">
                                                    <div class="card-header">
                                                        <div class="card-title">Expenses Summary</div>
                                                        <form method="GET" action="#" id="GetExpensesSummary">
                                                            <div class="row">
                                                                <div class="form-group col-md-1">
                                                                    <label class="form-label">From</label>
                                                                </div>
                                                                <div class="form-group col-md-3">
                                                                    <input class="form-control date" type="text"
                                                                        name="from_date">
                                                                </div>
                                                                <div class="form-group col-md-1">
                                                                    <label class="form-label">To</label>
                                                                </div>
                                                                <div class="form-group col-md-3">
                                                                    <input class="form-control date" type="text"
                                                                        name="to_date" />
                                                                </div>
                                                                <div class="form-group col-md-2">
                                                                    <button type="submit"
                                                                        class="btn btn-primary">Submit</button>
                                                                </div>
                                                            </div>

                                                        </form>
                                                    </div>
                                                    <div class="card-body">
                                                        <table id="datatables-buttons" class="table table-striped"
                                                            style="width:100%">
                                                            <thead>
                                                                <tr class="text-primary">
                                                                    <th>Day</th>
                                                                    <th>Total Outflow</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody id="ExpensesSummaryJS">
                                                                <?php
																foreach ($expenses_summary as $expense):
																?>
                                                                <tr>
                                                                    <td><?=$expense['expense_date']?></td>
                                                                    <td><?=$expense['outflow']?></td>
                                                                </tr>
                                                                <?php	                    	
																	endforeach;
																?>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="card">
                                                    <div class="card-header">
                                                        <div class="card-title">Top 5 Expenses</div>
                                                        <form method="GET" action="#" id="GetTopExpenses">
                                                            <div class="row">
                                                                <div class="form-group col-md-1">
                                                                    <label class="form-label">From</label>
                                                                </div>
                                                                <div class="form-group col-md-3">
                                                                    <input class="form-control date" type="text"
                                                                        name="from_date">
                                                                </div>
                                                                <div class="form-group col-md-1">
                                                                    <label class="form-label">To</label>
                                                                </div>
                                                                <div class="form-group col-md-3">
                                                                    <input class="form-control date" type="text"
                                                                        name="to_date" />
                                                                </div>
                                                                <div class="form-group col-md-3">
                                                                    <button type="submit"
                                                                        class="btn btn-primary">Submit</button>
                                                                </div>
                                                            </div>

                                                        </form>
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="chart chart-lg">
                                                            <canvas id="chartjs-dashboard-bar3"></canvas>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Vendor Tab Jitesh -->
                                    <div class="tab-pane fade" id="tab-4" role="tabpanel">
                                        <button class="btn btn-success" data-toggle="modal"
                                            data-target="#ModalAddVendors"><i class="fas fa-fw fa-plus"></i>Add
                                            Vendor</button><br><br>
                                        <table class="table table-hover table-striped datatables-basic"
                                            style="width :100%">
                                            <thead class="text-primary">
                                                <th>Vendor Name</th>
                                                <th>Contact Person</th>
                                                <th>Deals In</th>
                                                <th>Contact No</th>
                                                <th>State</th>
                                                <th>GST No</th>
                                                <th>Edit</th>
                                            </thead>
                                            <tbody>

                                                <?php
													foreach($vendors as $vendor){
												?> <tr>
                                                    <th><?=$vendor['vendor_name']?></th>
                                                    <th><?=$vendor['vendor_contact_person']?></th>
                                                    <th><?=$vendor['vendor_deals_in']?></th>
                                                    <td><?=$vendor['vendor_contact_no']?></td>
                                                    <th><?=$vendor['vendor_state']?></th>
                                                    <th><?=$vendor['vendor_gst_no']?></th>
                                                    <th><button class="btn btn-primary EditVendor" vendor_id='<?=$vendor['vendor_id']?>'><i class="fa fa-edit"></i></button></th>
                                                </tr>
                                                <?php
													}
												?>

                                            </tbody>
                                        </table>
                                    </div>
                                    <!-- End of Vendor Tab -->
                                    <div class="tab-pane fade" id="tab-5" role="tabpanel">
                                        <button class="btn btn-success mb-2" data-toggle="modal"
                                            data-target="#ModalAddExpense"><i class="fas fa-fw fa-plus"></i>Add
                                            Expense</button>
                                        <button class="btn btn-primary float-right mb-2"
                                            onclick="exportTableToExcel('adminExpense','Expense')"><i
                                                class="fa fa-file-export"></i>Export</button>
                                        <table class="table table-hover table-striped datatables-basic"
                                            id="PendingPayment" style="width: 100%;">
                                            <thead>
                                                <tr class="text-primary">
                                                    <!-- <th>Expense Id</th> -->
                                                    <th>Date</th>
                                                    <th>Expense Name</th>
                                                    <th>Expense Type</th>
                                                    <th>Total Amount</th>
                                                    <th>Amount Pending</th>
                                                    <th>Invoice No</th>
                                                    <th>Mode</th>
                                                    <th>Status </th>
                                                    <th>Paid To</th>
                                                    <th>Received By</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
											foreach ($pending_payment as $pending):
											?>
                                                <tr>
                                                    <!-- <td><?=$pending['expense_id']?></td> -->
                                                    <td><?=$pending['expense_date']?></td>
                                                    <td><?=$pending['item_name']?></td>
                                                    <td><?=$pending['expense_type']?></td>
                                                    <td><?=$pending['total_amount']?></td>
                                                    <td><?=$pending['pending_amount']?></td>
                                                    <td><?=$pending['invoice_number']?></td>
                                                    <td><?=$pending['payment_mode']?></td>
                                                    <td><?=$pending['expense_status']?></td>
                                                    <td><?=$pending['payment_type']?></td>
                                                    <td><?=$pending['payment_to_name']?></td>
                                                    <!-- <td><?=$pending['employee']?></td> -->


                                                    
                                                    <td>
                                                        <button type="button"
                                                            class="btn btn-primary pending-expense-edit-btn"
                                                            expense_id="<?=$pending['expense_id']?>"
                                                            expense_date="<?=$pending['expense_date']?>"
                                                            expense_name="<?=$pending['item_name']?>"
                                                            expense_type="<?=$pending['expense_type']?>"
                                                            employee_name="<?=$pending['employee_name']?>"
                                                            payment_type="<?=$pending['payment_type']?>"
                                                            payment_to="<?=$pending['payment_to']?>"
                                                            payment_to_name="<?=$pending['payment_to_name']?>"
                                                            total_amount="<?=$pending['total_amount']?>"
                                                            pending_amount="<?=$pending['pending_amount']?>"
                                                            amount="<?=$pending['amount']?>"
                                                            payment_mode="<?=$pending['payment_mode']?>"
                                                            expense_status="<?=$pending['expense_status']?>"
                                                            invoice_number="<?=$pending['invoice_number']?>"
                                                            expense_date="<?=$pending['expense_date']?>"
                                                            expense_type_id="<?=$pending['expense_type_id']?>"
                                                            remark="<?=$pending['remarks']?>">
                                                            <i class="align-middle" data-feather="edit-2"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                                <?php	                    	
												endforeach;
											?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--  -->
                    <!-- Modals -->
                    <div class="modal fade" id="defaultModalSuccess" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Success</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body m-3">
                                    <p class="mb-0" id="SuccessModalMessage">
                                        <p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-success" data-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal fade" id="defaultModalDanger" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Error</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body m-3">
                                    <p class="mb-0" id="ErrorModalMessage"></p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal fade" id="ModalAddExpenseCategory" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title text-white">Add Expense Category</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <form id="AddExpenseCategory" method="POST" action="#">
                                                <div class="form-group">
                                                    <label>Account type</label>
                                                    <input type="text" class="form-control" placeholder="Account Type"
                                                        name="account_type">
                                                </div>
                                                <div class="form-group">
                                                    <label>Category Name</label>
                                                    <input type="text" class="form-control" placeholder="Category Name"
                                                        name="expense_type">
                                                </div>
                                                <div class="form-group">
                                                    <label>Description</label>
                                                    <textarea class="form-control" placeholder="Description"
                                                        name="expense_type_description"></textarea>
                                                </div>
                                                <button type="submit" class="btn btn-primary">Submit</button>
                                            </form>
                                            <div class="alert alert-dismissible feedback" style="margin:0px;"
                                                role="alert">
                                                <button type="button" class="close" data-dismiss="alert"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                                <div class="alert-message">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal fade" id="ModalEditExpenseCategory" tabindex="-1" role="dialog"
                        aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
                            <div class="modal-content">
                                <div class="modal-header" style="background-color:#47bac1;">
                                    <h5 class="modal-title text-white">Edit Expense Category</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body m-3">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <form id="EditExpenseCategory" method="POST" action="#">
                                                <div class="form-group">
                                                    <label>Category Name</label>
                                                    <input type="text" class="form-control" placeholder="Category Name"
                                                        name="expense_type">
                                                </div>
                                                <div class="form-group">
                                                    <label>Description</label>
                                                    <textarea class="form-control" placeholder="Description"
                                                        name="expense_type_description"></textarea>
                                                </div>
                                                <div class="form-group">
                                                    <input class="form-control" type="hidden" name="expense_type_id"
                                                        readonly="true">
                                                </div>
                                                <button type="submit" class="btn btn-primary">Submit</button>
                                            </form>
                                            <div class="alert alert-dismissible feedback" style="margin:0px;"
                                                role="alert">
                                                <button type="button" class="close" data-dismiss="alert"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                                <div class="alert-message">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal fade" id="ModalAddExpense" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title text-white">Add Expense</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true" style="color: white;">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <form id="AddDailyExpenses" method="POST" action="#">
                                                <div class="row">
                                                    <div class="form-group col-md-3">
                                                        <label align="center">Entry Date</label>
                                                        <br>
                                                        <!-- <label><?php echo date('d-m-Y'); ?></label> -->
                                                        <input class="form-control" type="text" name="entry_date"  >
                                                    </div>
                                                    <div class="form-group col-md-3">
                                                        <label>Expense Name</label>
                                                        <input type="text" class="form-control" placeholder="Item Name"
                                                            name="item_name" autofocus>
                                                    </div>
                                                    <div class="form-group col-md-3">
                                                        <label>Expense Type</label>
                                                        <select class="form-control" name="expense_type_id">
                                                            <?php
															foreach ($expense_types  as $type):
														?>
                                                            <option value="<?=$type['expense_type_id']?>">
                                                                <?=$type['expense_type']?></option>
                                                            <?php	
															endforeach;
														?>
                                                        </select>
                                                    </div>
                                                    <div class="form-group col-md-3">
                                                        <label>Cashier Name</label>
                                                        <input type="text" class="form-control"
                                                            value="<?=$business_admin['employee_name']?>"
                                                            name="employee_name" readonly>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="form-group col-md-3">
                                                        <label>Payment Type</label>
                                                        <select name="payment_type" id="payment_type"
                                                            class="form-control">
                                                            <option value="" disabled selected>Select</option>
                                                            <option value="vendor">Vendor</option>
                                                            <option value="employee">Employee</option>
                                                            <option value="others">Others</option>
                                                        </select>

                                                    </div>
                                                    <div class="form-group col-md-3">
                                                        <label>Paid To</label>
                                                        <select name="payment_to" id="payment_to" class="form-control" >
                                                        </select>
                                                        <input type="text" name="payment_to_others" id="payment_to_text"
                                                            class="form-control" hidden>
                                                            <input type="text" name="payment_to_name" id="payment_to_name"
                                                            class="form-control" hidden>    
                                                          
                                                    </div>
                                                    <div class="form-group col-md-3">
                                                        <label>Total Amount Payable</label>
                                                        <input type="number" class="form-control" placeholder="Total Amount Payable"
                                                            name="total_amt" id="addtotalpay" min="0">
                                                    </div>
                                                    <div class="form-group col-md-3">
                                                        <label>Amount Paid Now</label>
                                                        <input type="number" class="form-control" min="0"
                                                            placeholder="Amount Paid Now" id="addamt" name="amount">
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="form-group col-md-3">
                                                        <label>Payment Mode</label>
                                                        <select class="form-control" name="payment_mode">
                                                            <option value="Cash" selected>Cash</option>
                                                            <option value="Card">Card</option>
                                                            <option value="Bank">Cheque/Bank</option>
                                                            <option value="Wallet">Wallet/ Others</option>
                                                            <select>
                                                    </div>
                                                    <div class="form-group col-md-3">
                                                        <label>Payment Status</label>
                                                        <select class="form-control" name="expense_status"
                                                            id="expense_status">
                                                            <option value="Paid" selected>Paid</option>
                                                            <option value="Advance">Advance</option>
                                                            <option value="Unpaid">Unpaid</option>
                                                            <option value="Partialy_paid">Partialy paid</option>
                                                            <select>
                                                    </div>
                                                    <div class="form-group col-md-3" id="pend_amt" hidden>
                                                        <label>Pending Amount</label>
                                                        <input type="text" name="pending_amount" id="addpendamt" class="form-control"
                                                            placeholder="Pending Amount">
                                                    </div>

                                                    <div class="form-group col-md-3">
                                                        <label>Invoice (If any)</label>
                                                        <input type="text" name="invoice_number" class="form-control"
                                                            placeholder=" Invoice number">
                                                    </div>
                                                    <div class="form-group col-md-3">
                                                        <label>Remarks</label>
                                                        <input type="text" class="form-control" placeholder="Remarks"
                                                            name="remarks">
                                                    </div>
                                                </div>
                                                <div class="row">
                                                   
                                                </div>
                                                <button type="submit" class="btn btn-primary">Submit</button>
                                            </form>
                                            <div class="alert alert-dismissible feedback" style="margin:0px;"
                                                role="alert">
                                                <button type="button" class="close" data-dismiss="alert"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>

                                                <div class="alert-message"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- update expense -->
                    <div class="modal fade" id="UpdatePendingExpense" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Update Expense</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body m-3">
                                    <form id="UpdateDailyExpenses" method="POST" action="#">
                                        <div class="row">
                                            <div class="form-group col-md-3">
                                                <label>Entry Date</label>
                                                <input type="date"  name="entry_date" style="width:100%" class="form-control" readonly>
                                            </div>
                                            <div class="form-group col-md-3">
                                                <label>Expense Name</label>
                                                <input type="text" class="form-control" name="item_name" readonly>
                                            </div>
                                            <div class="form-group col-md-3">
                                                <label>Expense Type</label>
                                                <input type="text" class="form-control" name="expense_type" readonly>
                                            </div>
                                            <div class="form-group col-md-3">
                                                <label>Employee Name</label>
                                                <input type="text" class="form-control" name="employee_name" readonly>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-md-3">
                                                <label>Paid To</label>
                                                <input name="payment_to_name" class="form-control" readonly>
                                            </div>
                                            <div class="form-group col-md-3">
                                                <label>Pending Amount</label>
                                                <input type="number" class="form-control" min="0" name="pending_amount" id="pendamtValue"
                                                    readonly>
                                            </div>
                                            <div class="form-group col-md-3">
                                                <label>Amount</label>
                                                <input type="number" class="form-control" min="0" name="amount1" id="reamt" onkeyup="calPendAmt()">
                                            </div>
                                         
                                            <div class="form-group col-md-3">
                                                <label>Remaing Amount</label>
                                                <input type="number" class="form-control" name="remaining_amt"  id="remaining_amt" readonly>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-md-3">
                                                <label>Payment Mode</label>
                                                <select class="form-control" name="payment_mode">
                                                    <option value="Cash" selected>Cash</option>
                                                    <option value="Card">Card</option>
                                                    <option value="Bank">Cheque/Bank</option>
                                                    <option value="Wallet">Wallet/ Others</option>
                                                    <select>
                                            </div>
                                            <div class="form-group col-md-3">
                                                <label>Payment Status</label>
                                                <select class="form-control" name="expense_status" id="select_expense_status">
                                                    <option disabled selected>Select</option>
                                                    <option value="Paid" disabled>Paid</option>
                                                    <!-- <option value="Advance">Advance</option> -->
                                                    <option value="Partialy_paid">Partialy Paid</option>
                                                    <select>
                                            </div>
                                            
                                            <div class="form-group col-md-3">
                                                <label>Remarks</label>
                                                <input type="text" class="form-control" name="remarks" readonly>
                                            </div>
                                            <div class="form-group col-md-3">
                                                <label>Invoice (If any)</label>
                                                <input type="text" name="invoice_number" class="form-control">
                                            </div>
                                            <div class="form-group col-md-3">
                                                <input type="text" name="expense_id" class="form-control" hidden>
                                                <input type="text" name="expense_type_id" class="form-control" hidden>
                                                <input type="text" name="item_name" class="form-control" hidden>
                                                <input type="number" name="amount" hidden>
                                                <input type="number" name="total_amount" hidden>
                                                <input type="text" name="payment_type" hidden>
                                                <input type="text" name="payment_to" hidden>
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                        <span aria-hidden="true">&times;&times;</span>
                                    </form>
                                </div>
                                <div class="modal-footer">
                                    <!-- <button type="button" class="btn btn-success" data-dismiss="modal">Submit</button> -->
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Modal TO Add Vendor-->
                    <div class="modal fade" id="ModalAddVendors" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title test-white">Add Vendor</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true" style="color:white;">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <form id="AddVendor" method="POST" action="#">
                                                <div class="row">
                                                    <div class="form-group col-md-3">
                                                        <label>Vendor Name</label>
                                                        <input type="text" name="vendor_name" class="form-control"
                                                            placeholder="Enter Vendor Name">
                                                    </div>
                                                    <div class="form-group col-md-3">
                                                        <label>Contact Person</label>
                                                        <input type="text" name="vendor_contact_person"
                                                            class="form-control" placeholder="Enter Contact Person">
                                                    </div>
                                                    <div class="form-group col-md-3">
                                                        <label>Deals In</label>
                                                        <input type="text" name="vendor_products" class="form-control"
                                                            placeholder="Enter Products Supplied">
                                                    </div>
                                                    <!-- <div class="form-group col-md-3">
                                                        <label>Vendor Code </label>
                                                        <input type="text" name="vendor_code" class="form-control" placeholder="Enter Vendor Code" readonly>
                                                    </div> -->
                                                </div>
                                                <div class="row">
                                                    <div class="form-group col-md-3">
                                                        <label>Contact No.</label>
                                                        <input type="number" name="vendor_contact_no"
                                                            class="form-control" placeholder="Enter Contact No."
                                                            onKeyPress="if(this.value.length == 10)return false;">
                                                    </div>
                                                    <div class="form-group col-md-3">
                                                        <label>Landline No.</label>
                                                        <input type="number" class="form-control"
                                                            name="vendor_landline_no" placeholder="Enter Landline No."
                                                            onKeyPress="if(this.value.length == 10) return false;">
                                                    </div>
                                                    <div class="form-group col-md-3">
                                                        <label>State</label>
                                                        <select name="vendor_state" id="state" class="form-control">
                                                            <option value="Andhra Pradesh">Andhra Pradesh</option>
                                                            <option value="Andaman and Nicobar Islands">Andaman and
                                                                Nicobar Islands</option>
                                                            <option value="Arunachal Pradesh">Arunachal Pradesh</option>
                                                            <option value="Assam">Assam</option>
                                                            <option value="Bihar">Bihar</option>
                                                            <option value="Chandigarh">Chandigarh</option>
                                                            <option value="Chhattisgarh">Chhattisgarh</option>
                                                            <option value="Dadar and Nagar Haveli">Dadar and Nagar
                                                                Haveli</option>
                                                            <option value="Daman and Diu">Daman and Diu</option>
                                                            <option value="Delhi">Delhi</option>
                                                            <option value="Lakshadweep">Lakshadweep</option>
                                                            <option value="Puducherry">Puducherry</option>
                                                            <option value="Goa">Goa</option>
                                                            <option value="Gujarat">Gujarat</option>
                                                            <option value="Haryana">Haryana</option>
                                                            <option value="Himachal Pradesh">Himachal Pradesh</option>
                                                            <option value="Jammu and Kashmir">Jammu and Kashmir</option>
                                                            <option value="Jharkhand">Jharkhand</option>
                                                            <option value="Karnataka">Karnataka</option>
                                                            <option value="Kerala">Kerala</option>
                                                            <option value="Madhya Pradesh">Madhya Pradesh</option>
                                                            <option value="Maharashtra">Maharashtra</option>
                                                            <option value="Manipur">Manipur</option>
                                                            <option value="Meghalaya">Meghalaya</option>
                                                            <option value="Mizoram">Mizoram</option>
                                                            <option value="Nagaland">Nagaland</option>
                                                            <option value="Odisha">Odisha</option>
                                                            <option value="Punjab">Punjab</option>
                                                            <option value="Rajasthan">Rajasthan</option>
                                                            <option value="Sikkim">Sikkim</option>
                                                            <option value="Tamil Nadu">Tamil Nadu</option>
                                                            <option value="Telangana">Telangana</option>
                                                            <option value="Tripura">Tripura</option>
                                                            <option value="Uttar Pradesh">Uttar Pradesh</option>
                                                            <option value="Uttarakhand">Uttarakhand</option>
                                                            <option value="West Bengal">West Bengal</option>
                                                        </select>
                                                    </div>
                                                    <div class="form-group col-md-3">
                                                        <label>City</label>
                                                        <input type="text" name="vendor_city" class="form-control">
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="form-group col-md-3">
                                                        <label>Address</label>
                                                        <input type="textbox" name="vendor_address" class="form-control"
                                                            placeholder="Enter Address"
                                                            onKeyPress="if(this.value.length == 150) return false;">
                                                    </div>
                                                    <div class="form-group col-md-3">
                                                        <label>GST No.</label>
                                                        <input type="text" name="gst_no" class="form-control"
                                                            placeholder="Enter GST No">
                                                    </div>
                                                    <div class="form-group col-md-3">
                                                        <label>Bank A/c No.</label>
                                                        <input type="text" name="bank_no" class="form-control"
                                                            placeholder="Enter A/C No.">
                                                    </div>
                                                    <div class="form-group col-md-3">
                                                        <label>Ifsc Code</label>
                                                        <input type="text" class="form-control" name="ifsc_code"
                                                            placeholder="Enter IFSC Code"
                                                            onKeyPress="if(this.value.length == 11) return false;">
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="form-group col-md-1">
                                                        <button type="submit" class="btn btn-primary">Submit</button>
                                                    </div>
                                                    <!-- <div class="form-group col-md-3">
                                                        <button type="reset" class="btn btn-danger"
                                                            float="left">Cancel</button>
                                                    </div> -->
                                                </div>
                                            </form>
                                            <div class="alert alert-dismissible feedback" style="margin:0px;"
                                                role="alert">
                                                <button type="button" class="close" data-dismiss="alert"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>

                                                <div class="alert-message"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End of Adding Vendor -->
                     <!-- Modal TO Update Vendor-->
                     <div class="modal fade" id="ModalUpdateVendors" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title test-white">Update Vendor</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true" style="color:white;">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <form id="Update" method="POST" action="#">
                                                <div class="row">
                                                    <div class="form-group col-md-3">
                                                        <label>Vendor Name</label>
                                                        <input type="text" name="vendor_name" class="form-control"
                                                            placeholder="Enter Vendor Name">
                                                    </div>
                                                    <div class="form-group col-md-3">
                                                        <label>Contact Person</label>
                                                        <input type="text" name="vendor_contact_person"
                                                            class="form-control" placeholder="Enter Contact Person">
                                                    </div>
                                                    <div class="form-group col-md-3">
                                                        <label>Deals In</label>
                                                        <input type="text" name="vendor_products" class="form-control"
                                                            placeholder="Enter Products Supplied">
                                                    </div>
                                                    <!-- <div class="form-group col-md-3"> -->
                                                        <!-- <label>Vendor Code </label> -->
                                                        <input type="int" name="vendor_id" class="form-control" hidden>
                                                    <!-- </div> -->
                                                </div>
                                                <div class="row">
                                                    <div class="form-group col-md-3">
                                                        <label>Contact No.</label>
                                                        <input type="number" name="vendor_contact_no"
                                                            class="form-control" placeholder="Enter Contact No."
                                                            onKeyPress="if(this.value.length == 10)return false;">
                                                    </div>
                                                    <div class="form-group col-md-3">
                                                        <label>Landline No.</label>
                                                        <input type="number" class="form-control"
                                                            name="vendor_landline_no" placeholder="Enter Landline No."
                                                            onKeyPress="if(this.value.length == 10) return false;">
                                                    </div>
                                                    <div class="form-group col-md-3">
                                                        <label>State</label>
                                                        <select name="vendor_state" id="updatestate" class="form-control">
                                                            <option value="Andhra Pradesh">Andhra Pradesh</option>
                                                            <option value="Andaman and Nicobar Islands">Andaman and
                                                                Nicobar Islands</option>
                                                            <option value="Arunachal Pradesh">Arunachal Pradesh</option>
                                                            <option value="Assam">Assam</option>
                                                            <option value="Bihar">Bihar</option>
                                                            <option value="Chandigarh">Chandigarh</option>
                                                            <option value="Chhattisgarh">Chhattisgarh</option>
                                                            <option value="Dadar and Nagar Haveli">Dadar and Nagar
                                                                Haveli</option>
                                                            <option value="Daman and Diu">Daman and Diu</option>
                                                            <option value="Delhi">Delhi</option>
                                                            <option value="Lakshadweep">Lakshadweep</option>
                                                            <option value="Puducherry">Puducherry</option>
                                                            <option value="Goa">Goa</option>
                                                            <option value="Gujarat">Gujarat</option>
                                                            <option value="Haryana">Haryana</option>
                                                            <option value="Himachal Pradesh">Himachal Pradesh</option>
                                                            <option value="Jammu and Kashmir">Jammu and Kashmir</option>
                                                            <option value="Jharkhand">Jharkhand</option>
                                                            <option value="Karnataka">Karnataka</option>
                                                            <option value="Kerala">Kerala</option>
                                                            <option value="Madhya Pradesh">Madhya Pradesh</option>
                                                            <option value="Maharashtra">Maharashtra</option>
                                                            <option value="Manipur">Manipur</option>
                                                            <option value="Meghalaya">Meghalaya</option>
                                                            <option value="Mizoram">Mizoram</option>
                                                            <option value="Nagaland">Nagaland</option>
                                                            <option value="Odisha">Odisha</option>
                                                            <option value="Punjab">Punjab</option>
                                                            <option value="Rajasthan">Rajasthan</option>
                                                            <option value="Sikkim">Sikkim</option>
                                                            <option value="Tamil Nadu">Tamil Nadu</option>
                                                            <option value="Telangana">Telangana</option>
                                                            <option value="Tripura">Tripura</option>
                                                            <option value="Uttar Pradesh">Uttar Pradesh</option>
                                                            <option value="Uttarakhand">Uttarakhand</option>
                                                            <option value="West Bengal">West Bengal</option>
                                                        </select>
                                                    </div>
                                                    <div class="form-group col-md-3">
                                                        <label>City</label>
                                                        <input type="text" name="vendor_city" class="form-control">
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="form-group col-md-3">
                                                        <label>Address</label>
                                                        <input type="textbox" name="vendor_address" class="form-control"
                                                            placeholder="Enter Address"
                                                            onKeyPress="if(this.value.length == 150) return false;">
                                                    </div>
                                                    <div class="form-group col-md-3">
                                                        <label>GST No.</label>
                                                        <input type="text" name="gst_no" class="form-control"
                                                            placeholder="Enter GST No">
                                                    </div>
                                                    <div class="form-group col-md-3">
                                                        <label>Bank A/c No.</label>
                                                        <input type="text" name="bank_no" class="form-control"
                                                            placeholder="Enter A/C No.">
                                                    </div>
                                                    <div class="form-group col-md-3">
                                                        <label>Ifsc Code</label>
                                                        <input type="text" class="form-control" name="ifsc_code"
                                                            placeholder="Enter IFSC Code"
                                                            onKeyPress="if(this.value.length == 11) return false;">
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="form-group col-md-1">
                                                        <button type="submit" class="btn btn-primary">Submit</button>
                                                    </div>
                                                    <!-- <div class="form-group col-md-3">
                                                        <button type="reset" class="btn btn-danger"
                                                            float="left">Cancel</button>
                                                    </div> -->
                                                </div>
                                            </form>
                                            <div class="alert alert-dismissible feedback" style="margin:0px;"
                                                role="alert">
                                                <button type="button" class="close" data-dismiss="alert"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>

                                                <div class="alert-message"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End of Update Vendor -->
                    <!-----END------>
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
        $("input[name=\"from_date\"]").daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            autoUpdateInput: false,
            locale: {
                format: 'YYYY-MM-DD'
            }
        });
        $("input[name=\"entry_date\"]").daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
           
            locale: {
                format: 'YYYY-MM-DD'
            }
        });
        $("input[name=\"to_date\"]").daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            autoUpdateInput: false,
            locale: {
                format: 'YYYY-MM-DD'
            }
        });

        $('.date').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('YYYY-MM-DD'));
        });
        </script>
        <script type="text/javascript">
        $(document).ready(function() {

            $(document).ajaxStart(function() {
                $("#load_screen").show();
            });

            $(document).ajaxStop(function() {
                $("#load_screen").hide();
            });

            $(".datatables-basic").DataTable({
                responsive: true
            });

            $("#AddExpenseCategory").validate({
                errorElement: "div",
                rules: {
                    "expense_type": {
                        required: true,
                        maxlength: 100
                    }
                },
                submitHandler: function(form) {
                    var formData = $("#AddExpenseCategory").serialize();
                    $.ajax({
                        url: "<?=base_url()?>BusinessAdmin/AddExpenseCategory",
                        data: formData,
                        type: "POST",
                        // crossDomain: true,
                        cache: false,
                        // dataType: "json",
                        success: function(data) {
                            if (data.success == 'true') {
                                $("#ModalAddExpenseCategory").modal('hide');
                                toastr["success"](data.message,"", {
							positionClass: "toast-top-right",
							progressBar: "toastr-progress-bar",
							newestOnTop: "toastr-newest-on-top",
							rtl: $("body").attr("dir") === "rtl" || $("html").attr("dir") === "rtl",
							timeOut: 500
						});
						setTimeout(function () { window.location.reload();}, 500);
                            } else if (data.success == 'false') {
                                if ($('.feedback').hasClass('alert-success')) {
                                    $('.feedback').removeClass('alert-success')
                                        .addClass('alert-danger');
                                } else {
                                    $('.feedback').addClass('alert-danger');
                                }
                                $('.alert-message').html("").html(data.message);
                            }
                        },
                        error: function(data) {
                            $('.feedback').addClass('alert-danger');
                            $('.alert-message').html("").html(data.message);
                        }
                    });
                },
            });

            $("#EditExpenseCategory").validate({
                errorElement: "div",
                rules: {
                    "expense_type": {
                        required: true,
                        maxlength: 100
                    }
                },
                submitHandler: function(form) {
                    var formData = $("#EditExpenseCategory").serialize();
                    $.ajax({
                        url: "<?=base_url()?>BusinessAdmin/EditExpenseCategory",
                        data: formData,
                        type: "POST",
                        // crossDomain: true,
                        cache: false,
                        // dataType: "json",
                        success: function(data) {
                            if (data.success == 'true') {
                                $("#ModalEditExpenseCategory").modal('hide');
                                toastr["success"](data.message,"", {
							positionClass: "toast-top-right",
							progressBar: "toastr-progress-bar",
							newestOnTop: "toastr-newest-on-top",
							rtl: $("body").attr("dir") === "rtl" || $("html").attr("dir") === "rtl",
							timeOut: 500
						});
						setTimeout(function () { window.location.reload();}, 500);
                            } else if (data.success == 'false') {
                                if ($('.feedback').hasClass('alert-success')) {
                                    $('.feedback').removeClass('alert-success')
                                        .addClass('alert-danger');
                                } else {
                                    $('.feedback').addClass('alert-danger');
                                }
                                $('.alert-message').html("").html(data.message);
                            }
                        },
                        error: function(data) {
                            $('.feedback').addClass('alert-danger');
                            $('.alert-message').html("").html(data.message);
                        }
                    });
                },
            });

            $(document).on('click', '.expense-type-edit-btn', function(event) {
                event.preventDefault();
                this.blur(); // Manually remove focus from clicked link.
                var parameters = {
                    expense_type_id: $(this).attr('expense_type_id')
                };
                $.getJSON("<?=base_url()?>BusinessAdmin/GetExpenseType", parameters)
                    .done(function(data, textStatus, jqXHR) {
                        $("#EditExpenseCategory input[name=expense_type]").attr('value', data
                            .expense_type);
                        $("#EditExpenseCategory textarea[name=expense_type_description]").val(data
                            .expense_type_description);
                        $("#EditExpenseCategory input[name=expense_type_id]").attr('value', data
                            .expense_type_id);

                        $("#ModalEditExpenseCategory").modal('show');
                    })
                    .fail(function(jqXHR, textStatus, errorThrown) {
                        console.log(errorThrown.toString());
                    });
            });

            $("#AddDailyExpenses").validate({
                errorElement: "div",
                rules: {
                    "entry_date": {
                        required: true
                    },
                    "expense_type": {
                        required: true
                    },
                    "item_name": {
                        required: true,
                        maxlength: 50
                    },
                    "total_amt": {
                        required: true,
                        digits: true
                    },  
                    "payment_mode": {
                        required: true
                    },
                    "expense_status": {
                        required: true
                    },
                    "employee_name": {
                        required: true,
                        maxlength: 100
                    }
                },
                submitHandler: function(form) {
                    var formData = $("#AddDailyExpenses").serialize();
                    $.ajax({
                        url: "<?=base_url()?>BusinessAdmin/ConfigExpense",
                        data: formData,
                        type: "POST",
                        // crossDomain: true,
                        cache: false,
                        // dataType: "json",
                        success: function(data) {
                            if (data.success == 'true') {
                                $("#ModalAddExpense").modal('hide');
                                toastr["success"](data.message,"", {
							positionClass: "toast-top-right",
							progressBar: "toastr-progress-bar",
							newestOnTop: "toastr-newest-on-top",
							rtl: $("body").attr("dir") === "rtl" || $("html").attr("dir") === "rtl",
							timeOut: 500
						});
						setTimeout(function () { window.location.reload();}, 500);
                            } else if (data.success == 'false') {
                                if ($('.feedback').hasClass('alert-success')) {
                                    $('.feedback').removeClass('alert-success')
                                        .addClass('alert-danger');
                                } else {
                                    $('.feedback').addClass('alert-danger');
                                }
                                $('.alert-message').html("").html(data.message);
                            }
                        },
                        error: function(data) {
                            $("#ModalAddExpense").modal('hide');
                            $('#defaultModalDanger').modal('show').on('shown.bs.modal',
                                function(e) {
                                    $("#ErrorModalMessage").html("").html(
                                        "<p>Error, Try again later!</p>");
                                }).on('hidden.bs.modal', function(e) {
                                window.location.reload();
                            });
                        }
                    });
                },
            });
            // Update Expenses 
            $("#UpdateDailyExpenses").validate({
                errorElement: "div",
                // rules: {
                // 		"entry_date":{
                // 			required:true
                // 		},
                //   "expense_type" : {
                //   required : true	
                // },
                // "item_name" : {
                //   required : true,
                //   maxlength : 50
                // },
                // "amount" : {
                //   required : true,
                // 		digits : true
                // }, 
                // "payment_mode" : {
                //   required : true
                // },    
                // "expense_status" : {
                //   required : true
                // },
                // "employee_name" : {
                // 	required : true,
                // 	maxlength : 100
                // }
                // },
                submitHandler: function(form) {
                    var formData = $("#UpdateDailyExpenses").serialize();
                    $.ajax({
                        url: "<?=base_url()?>BusinessAdmin/UpdateExpense",
                        data: formData,
                        type: "POST",
                        // crossDomain: true,
                        cache: false,
                        // dataType: "json",
                        success: function(data) {
                            // alert(data.success);

                            if (data.success == 'true') {
                                $("#UpdatePendingExpense").modal('hide');
                                toastr["success"](data.message,"", {
							positionClass: "toast-top-right",
							progressBar: "toastr-progress-bar",
							newestOnTop: "toastr-newest-on-top",
							rtl: $("body").attr("dir") === "rtl" || $("html").attr("dir") === "rtl",
							timeOut: 500
						});
						setTimeout(function () { window.location.reload();}, 500);
                            } else if (data.success == 'false') {
                                if ($('.feedback').hasClass('alert-success')) {
                                    $('.feedback').removeClass('alert-success')
                                        .addClass('alert-danger');
                                } else {
                                    $('.feedback').addClass('alert-danger');
                                }
                                $('.alert-message').html("").html(data.message);
                            }
                        },
                        error: function(data) {
                            $("#UpdatePendingExpense").modal('hide');
                            $('#defaultModalDanger').modal('show').on('shown.bs.modal',
                                function(e) {
                                    $("#ErrorModalMessage").html("").html(
                                        "<p>Error, Try again later!</p>");
                                }).on('hidden.bs.modal', function(e) {
                                window.location.reload();
                            });
                        }
                    });
                },
            });

            $("#GetExpensesSummary").validate({
                errorElement: "div",
                rules: {
                    "from_date": {
                        required: true
                    },
                    "to_date": {
                        required: true
                    }
                },
                submitHandler: function(form) {
                    var formData = $("#GetExpensesSummary").serialize();
                    $.ajax({
                        url: "<?=base_url()?>BusinessAdmin/ExpensesSummaryRange",
                        data: formData,
                        type: "GET",
                        // crossDomain: true,
                        cache: false,
                        // dataType: "json",
                        success: function(data) {
                            var str = "";
                            for (var i = 0; i < data.length; i++) {
                                str += "<tr>";
                                str += "<td>" + data[i].expense_date + "</td>";
                                str += "<td>" + data[i].outflow + "</td>";
                                str += "</tr>";
                            }
                            $("#ExpensesSummaryJS").html("").html(str);

                        },
                        error: function(data) {
                            $('#defaultModalDanger').modal('show').on('shown.bs.modal',
                                function(e) {
                                    $("#ErrorModalMessage").html("").html(
                                        "<p>Error, Try again later!</p>");
                                }).on('hidden.bs.modal', function(e) {
                                window.location.reload();
                            });
                        }
                    });
                },
            });


            $("#GetTopExpenses").validate({
                errorElement: "div",
                rules: {
                    "from_date": {
                        required: true
                    },
                    "to_date": {
                        required: true
                    }
                },
                submitHandler: function(form) {
                    var formData = $("#GetTopExpenses").serialize();
                    $.ajax({
                        url: "<?=base_url()?>BusinessAdmin/TopExpensesSummaryRange",
                        data: formData,
                        type: "GET",
                        // crossDomain: true,
                        cache: false,
                        // dataType: "json",
                        success: function(data) {
                            var date_array = [];
                            var outflow_array = [];
                            for (var i = 0; i < data.length; i++) {
                                date_array.push(data[i].expense_date);
                                outflow_array.push(data[i].outflow);
                            }
                            new Chart(document.getElementById(
                                "chartjs-dashboard-bar3"), {
                                type: "bar",
                                data: {
                                    labels: date_array,
                                    datasets: [{
                                        label: "Expenses (In Rupees)",
                                        backgroundColor: window.theme
                                            .warning,
                                        borderColor: window.theme
                                            .primary,
                                        hoverBackgroundColor: window
                                            .theme.primary,
                                        hoverBorderColor: window.theme
                                            .primary,
                                        data: outflow_array
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
                                                stepSize: 500
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
                        },
                        error: function(data) {
                            $('#defaultModalDanger').modal('show').on('shown.bs.modal',
                                function(e) {
                                    $("#ErrorModalMessage").html("").html(
                                        "<p>Error, Try again later!</p>");
                                }).on('hidden.bs.modal', function(e) {
                                window.location.reload();
                            });
                        }
                    });
                },
            });
        });

        //add vendors
        $("#AddVendor").validate({
            errorElement: "div",
            rules: {
                "vendor_name": {
                    required: true
                },
                "vendor_contact_person": {
                    required: true
                },
                "vendor_products": {
                    required: true,
                    maxlength: 50
                },
                "state": {
                    required: true,
                    digits: true
                },
                "city": {
                    required: true
                },


            },
            submitHandler: function(form) {
                var formData = $("#AddVendor").serialize();
                $.ajax({
                    url: "<?=base_url()?>BusinessAdmin/AddVendor",
                    data: formData,
                    type: "POST",
                    // crossDomain: true,
                    cache: false,
                    // dataType: "json",
                    success: function(data) {
                        if (data.success == 'true') {
                            $("#ModalAddVendors").modal('hide');
                            toastr["success"](data.message,"", {
							positionClass: "toast-top-right",
							progressBar: "toastr-progress-bar",
							newestOnTop: "toastr-newest-on-top",
							rtl: $("body").attr("dir") === "rtl" || $("html").attr("dir") === "rtl",
							timeOut: 500
						});
						setTimeout(function () { window.location.reload();}, 500);
                        } else if (data.success == 'false') {
                            if ($('.feedback').hasClass('alert-success')) {
                                $('.feedback').removeClass('alert-success').addClass(
                                    'alert-danger');
                            } else {
                                $('.feedback').addClass('alert-danger');
                            }
                            $('.alert-message').html("").html(data.message);
                        }
                    },
                    error: function(data) {
                        $("#ModalAddVendors").modal('hide');
                        $('#defaultModalDanger').modal('show').on('shown.bs.modal', function(
                            e) {
                            $("#ErrorModalMessage").html("").html(
                                "<p>Error, Try again later!</p>");
                        }).on('hidden.bs.modal', function(e) {
                            window.location.reload();
                        });
                    }
                });
            },
        });

        //update vendor details
        $("#Update").validate({
            errorElement: "div",
            rules: {
                "vendor_name": {
                    required: true
                },
                "vendor_contact_person": {
                    required: true
                },
                "vendor_products": {
                    required: true,
                    maxlength: 50
                },
                "state": {
                    required: true,
                    digits: true
                },
                "city": {
                    required: true
                },


            },
            submitHandler: function(form) {
                // alert("hii");
                var formData = $("#Update").serialize();
                $.ajax({
                    url: "<?=base_url()?>BusinessAdmin/UpdateVendor",
                    data: formData,
                    type: "POST",
                    // crossDomain: true,
                    cache: false,
                    // dataType: "json",
                    success: function(data) {
                        if (data.success == 'true') {
                            $("#ModalAddVendors").modal('hide');
                            toastr["success"](data.message,"", {
							positionClass: "toast-top-right",
							progressBar: "toastr-progress-bar",
							newestOnTop: "toastr-newest-on-top",
							rtl: $("body").attr("dir") === "rtl" || $("html").attr("dir") === "rtl",
							timeOut: 500
						});
						setTimeout(function () { window.location.reload();}, 500);
                        } else if (data.success == 'false') {
                            if ($('.feedback').hasClass('alert-success')) {
                                $('.feedback').removeClass('alert-success').addClass(
                                    'alert-danger');
                            } else {
                                $('.feedback').addClass('alert-danger');
                            }
                            $('.alert-message').html("").html(data.message);
                        }
                    },
                    error: function(data) {
                        $("#ModalAddVendors").modal('hide');
                        $('#defaultModalDanger').modal('show').on('shown.bs.modal', function(
                            e) {
                            $("#ErrorModalMessage").html("").html(
                                "<p>Error, Try again later!</p>");
                        }).on('hidden.bs.modal', function(e) {
                            window.location.reload();
                        });
                    }
                });
            },
        });

        // updatePendingAmount
        $(document).on('click', '.pending-expense-edit-btn', function(event) {
            event.preventDefault();
            this.blur(); // Manually remove focus from clicked link.
            //   var parameters = {
            var expense_type = $(this).attr('expense_type');

            //   };
            //   $.getJSON("<?=base_url()?>BusinessAdmin/EditPendingExpense", parameters)
            //   .done(function(data, textStatus, jqXHR) { 
            $("#UpdatePendingExpense input[name=expense_id]").attr('value', $(this).attr('expense_id'));
            $("#UpdatePendingExpense input[name=expense_type]").attr('value', expense_type);
            $("#UpdatePendingExpense input[name=entry_date]").attr('value', $(this).attr('expense_date'));
            $("#UpdatePendingExpense input[name=item_name]").attr('value', $(this).attr('expense_name'));
            $("#UpdatePendingExpense input[name=expense_type_id]").attr('value', $(this).attr(
                'expense_type_id'));
            $("#UpdatePendingExpense input[name=employee_name]").attr('value', $(this).attr('employee_name'));
            $("#UpdatePendingExpense input[name=pending_amount]").attr('value', $(this).attr('pending_amount'));
            $("#UpdatePendingExpense input[name=payment_to_name]").attr('value', $(this).attr('payment_to_name'));
            $("#UpdatePendingExpense input[name=amount]").attr('value', $(this).attr('amount'));
            $("#UpdatePendingExpense input[name=remarks]").attr('value', $(this).attr('remark'));
            $("#UpdatePendingExpense input[name=invoice_number]").attr('value', $(this).attr('invoice_number'));
            $("#UpdatePendingExpense input[name=total_amount]").attr('value', $(this).attr('total_amount'));
            $("#UpdatePendingExpense input[name=payment_type]").attr('value', $(this).attr('payment_type'));
            $("#UpdatePendingExpense input[name=payment_to]").attr('value', $(this).attr('payment_to'));
            // $("#UpdatePendingExpense input[name=invoice_number]").attr('value', $(this).attr('invoice_number'));
            // $("#EditExpenseCategory textarea[name=expense_type_description]").val(data.expense_type_description);
            // $("#EditExpenseCategory input[name=expense_type_id]").attr('value',data.expense_type_id);

            $("#UpdatePendingExpense").modal('show');
            // })
            // .fail(function(jqXHR, textStatus, errorThrown) {
            // console.log(errorThrown.toString());
            // });
        });
        </script>
        <script>
        function exportTableToExcel(tableID, filename = '') {
            var downloadLink;
            var dataType = 'application/vnd.ms-excel';
            var tableSelect = document.getElementById(tableID);
            var tableHTML = tableSelect.outerHTML.replace(/ /g, '%20');

            // Specify file name
            filename = filename ? filename + '.xls' : 'excel_data.xls';

            // Create download link element
            downloadLink = document.createElement("a");

            document.body.appendChild(downloadLink);

            if (navigator.msSaveOrOpenBlob) {
                var blob = new Blob(['\ufeff', tableHTML], {
                    type: dataType
                });
                navigator.msSaveOrOpenBlob(blob, filename);
            } else {
                // Create a link to the file
                downloadLink.href = 'data:' + dataType + ', ' + tableHTML;

                // Setting the file name
                downloadLink.download = filename;

                //triggering the function
                downloadLink.click();
            }
        }
        </script>
         <script type="text/javascript">
           $("#payment_type").on('change', function(e) {
            $('#payment_to').attr("hidden", false);

            var employees = <?= json_encode($employees) ?>;
            var vendors = <?= json_encode($vendors) ?>;
            if ($('#payment_type').val() == 'vendor') {
                $('#payment_to').empty();
                $("#payment_to_text").attr('hidden', true);
                for (var i = 0; i < vendors.length; i++) {
                    $("#AddDailyExpenses select[name=payment_to]").append("<option value=" + vendors[i].vendor_id + ">" + vendors[i].vendor_name + "</option>")
                      
                }
            } else if ($('#payment_type').val() == 'employee') {
                $('#payment_to').empty();
                $("#payment_to_text").attr('hidden', true);
                for (var i = 0; i < employees.length; i++) {

                    $("#AddDailyExpenses select[name=payment_to]").append("<option value=" + employees[i].employee_id +  ">" + employees[i].employee_first_name + ' ' + employees[i]
                        .employee_last_name + "</option>")
                       
                        
                }
            } else {
                $('#payment_to').empty();
                $("#payment_to").attr('hidden', true);
                $("#payment_to_text").removeAttr('hidden');
                //   var temp = "<input type='text' name='payment_to'>";
                //   $("#AddDailyExpenses select[name=payment_to]").append(temp);
            }

             });
        </script>
        

        <script type="text/javascript">
        $("#expense_status").on('change', function(e) {
            var exp_status = document.getElementById('expense_status').value;
            // alert(exp_status);
            if (exp_status == 'Partialy_paid') {
                $("#pend_amt").removeAttr('hidden');
            }
        });
        </script>
        <script type="text/javascript">
            $("#expense_status").on('change', function(e) {
                var exp_status = document.getElementById('expense_status').value;
                // alert(exp_status);
                if (exp_status == 'Partialy_paid') {
                    $("#pend_amt").removeAttr('hidden');
                }
            });
        </script>
        <script type="text/javascript">
            $("#addamt").on('keyup', function(e) {
                var amt = document.getElementById('addamt').value;
                var total_amt = document.getElementById('addtotalpay').value;
                    $("#pend_amt").removeAttr('hidden');
                    var pend = total_amt - amt;
                    document.getElementById('addpendamt').value= pend;
                    if(document.getElementById('addpendamt').value > 0)
                    {
                        document.getElementById("expense_status").options[3].selected = true;
                    }
                    else if(document.getElementById('addpendamt').value == 0)
                    {
                        document.getElementById("expense_status").options[0].selected = true;
                    }
                   if(document.getElementById("addamt").value == 0)
                    {
                        document.getElementById("expense_status").options[2].selected = true;
                    }
                    else if(document.getElementById("addamt").value == total_amt){
                        document.getElementById("expense_status").options[0].selected = true;
                    }
            });
        </script>
        <script type="text/javascript">
        function calPendAmt() {
            var t1 = parseInt(document.getElementById("reamt").value);
            var t2 = parseInt(document.getElementById("pendamtValue").value);
            
            if(t1 <= t2){
                var t3 = t2 - t1;
               document.getElementById("remaining_amt").value = t3;
            }else{
                alert("Amount is Greater than Pending Amount");
                document.getElementById("reamt").value=0;
                document.getElementById("remaining_amt").value = t2;
                document.getElementById("reamt").autofocus;
                
            }
            if(t3 == 0){
                document.getElementById("select_expense_status").options[1].disabled = false;
                document.getElementById("select_expense_status").options[1].selected = true; 
            }
            else{
                document.getElementById("select_expense_status").options[2].selected = true;
                document.getElementById("select_expense_status").options[1].disabled = true;
            }
        }
        </script>
        <script type="text/javascript">
            $(document).on('click',"#download",function(event){
				event.preventDefault();
				this.blur();
                    var group = document.getElementById('expense_group').value;
                    // alert(group);
                    if(group == 'range'){
                        var from_date = document.getElementById('daterange').value;
                        // var to_date = document.getElementById('to_date').value;
                        // alert(from_date);
                        var parameters = {
                            group : group,
                            from_date :from_date,
                        
                        };
                    }
                    else{
                        var parameters = {
                            group : group
                        };
                    }
				    $.getJSON("<?=base_url()?>BusinessAdmin/ExpenseReport",parameters)
					.done(function(data, textStatus, jqXHR) {
                         
						JSONToCSVConvertor(data.result,"Overall Expense Report", true);
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
			fileName += ReportTitle.replace(/ /g,"_");   
			
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
        $("#expense_group").on('change', function(e) {
            var exp_group = document.getElementById('expense_group').value;
            // alert(exp_group);
            if (exp_group == 'range') {
                $("#hide").removeAttr('hidden');
				$("#altrdiv").attr('hidden', true); 
                $("#to_date").removeAttr('hidden');
            }
            else{
                $("#hide").attr('hidden', true); 
                $("#to_date").attr('hidden', true);
            }
        });

        $(document).on('click', ".EditVendor", function(event) {
        event.preventDefault();
        $(this).blur();
        //   alert($(this).attr('vendor_id'));

            var parameters = {
            vendor_id: $(this).attr('vendor_id')
        };
        $.getJSON("<?=base_url()?>BusinessAdmin/GetVendorDetails", parameters)
            .done(function(data, textStatus, jqXHR) {
                // alert(data);
                $("#Update input[name=vendor_id]").attr('value', data.vendor_id);
                $("#Update input[name=vendor_name]").attr('value', data.vendor_name);
                $("#Update input[name=vendor_contact_person]").attr('value', data.vendor_contact_person);
                $("#Update input[name=vendor_products]").attr('value', data.vendor_deals_in);
                $("#Update input[name=vendor_contact_no]").attr('value', data.vendor_contact_no);
                $("#Update input[name=vendor_landline_no]").attr('value', data.vendor_landline_no);
                $("#Update input[name=vendor_city]").val(data.vendor_city);
                $("#Update input[name=vendor_address]").attr('value', data.vendor_address);
                $("#Update input[name=gst_no]").attr('value', data.vendor_gst_no);
                $("#Update input[name=bank_no]").attr(data.vandor_bank_acc_no);
                $("#Update input[name=ifsc_code]").val(data.vandor_bank_ifsc_bank);
                // $("#Update input[select=vendor_state]").val(data.vendor_state);
                // $("#Update input[name=last_visit]").val(data.customer_transaction[0].last_visit);
                document.getElementById("updatestate").value = ""+data.vendor_state+"";
                 $("#ModalUpdateVendors").modal('show');
            })
            .fail(function(jqXHR, textStatus, errorThrown) {
                console.log(errorThrown.toString());
            });
    });
</script>
<script>
		$(function(){
			// Daterangepicker
			$("input[name=\"daterange\"]").daterangepicker({
				opens: "left",
				locale: {
                format: 'YYYY/MM/DD'
            }
			});
			
		});
</script>
