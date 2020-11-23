<?php
$this->load->view('superAdmin/sa_header_view');
?>
<div class="wrapper">
  <?php
  $this->load->view('superAdmin/sa_nav_view');
  ?>
  <div class="main">
    <?php
    $this->load->view('superAdmin/sa_top_nav_view');
    ?>
    <main class="content">
      <div class="container-fluid p-0">
        <h1 class="h3 mb-3">Business Outlets</h1>
        <div class="row">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <div class="row">
                  <div class="col-md-6">
                    <h5 class="card-title">Outlet List</h5>
                  </div>
                  <div class="col-md-2">
                    <a href="<?= base_url() ?>SuperAdmin/Dashboard" class="btn btn-primary">Back to Dashboard</a>
                  </div>
                  <div class="col-md-4">
                    <button class="btn btn-success  float-right" data-toggle="modal" data-target="#ModalAddOutlet"><i class="fas fa-fw fa-plus"></i>Add Outlet</button>
                  </div>
                </div>
              </div>
              <div class="card-body">
                <table class="table table-hover table-striped" style="width: 100%;">
                  <thead>
                    <tr>
                      <th>Name</th>
                      <th>Mobile</th>
                      <th>Location</th>
                      <th>City</th>
                      <th>Email</th>
                      <th>Total Bill</th>
                      <th>Creation Date</th>
                      <th>Renewal Date</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    foreach ($business_outlet_details as $outlet) :
                    ?>
                      <tr>
                        <td><?= $outlet['business_outlet_name'] ?></td>
                        <td><?= $outlet['business_outlet_mobile'] ?></td>
                        <td><?= $outlet['business_outlet_location'] ?></td>
                        <td><?= $outlet['business_outlet_city'] ?></td>
                        <td><?= $outlet['business_outlet_email'] ?></td>
                        <td><?= $outlet['business_outlet_bill_counter'] ?></td>
                        <td><?= $outlet['business_outlet_creation_date'] ?></td>
                        <td><?= $outlet['business_outlet_expiry_date'] ?></td>
                        <td class="table-action">
                          <button type="button" class="btn btn-primary outlet-edit-btn" business_outlet_id="<?= $outlet['business_outlet_id'] ?>">
                            <i class="align-middle" data-feather="edit-2"></i>
                          </button>
                          <?php
                          if ($outlet['business_outlet_status'] == 0) {
                          ?>
                            <button type="button" class="btn btn-danger outlet-delete" business_outlet_id="<?= $outlet['business_outlet_id'] ?>" business_outlet_status="1">
                              <i class="align-middle" data-feather="delete"></i>
                            </button>
                          <?php
                          } else {
                          ?>
                            <button type="button" class="btn btn-success outlet-delete" business_outlet_id="<?= $outlet['business_outlet_id'] ?>" business_outlet_status="0">
                              <i class="align-middle" data-feather="delete"></i>
                            </button>
                          <?php
                          }
                          ?>


                        </td>
                      </tr>
                    <?php
                    endforeach;
                    ?>
                  </tbody>
                </table>
              </div>
            </div>
            <!-- modal -->
            <div class="modal" id="ModalEditOutlet" tabindex="-1" role="dialog" aria-hidden="true">
              <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title text-white">Edit Details / Define Rules</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body">
                    <div class="tab">
                      <ul class="nav nav-pills card-header-pills pull-right" role="tablist">
                        <li class="nav-item ml-2"><a class="nav-link active" href="#appointment_1" data-toggle="tab" role="tab">Edit Details</a></li>
                        <li class="nav-item"><a class="nav-link" href="#appointment_2" data-toggle="tab" role="tab">Add Rules</a></li>
                      </ul>
                      <div class="tab-content">
                        <div class="tab-pane active" id="appointment_1" role="tabpanel">
                          <div class="row">
                            <div class="modal-body">
                              <form id="EditOutlet" method="POST" action="#">
                                <div class="form-row">
                                  <div class="form-group col-md-4">
                                    <label class="font-weight-bold">Outlet Name</label>
                                    <input type="text" class="form-control" placeholder="Outlet Name" name="business_outlet_name">
                                  </div>
                                  <div class="form-group col-md-4">
                                    <label class="font-weight-bold">Expiry Date</label>
                                    <input type="date" class="form-control" name="business_outlet_expiry_date" id="business_outlet_expiry_date" required>
                                  </div>
                                  <div class="form-group col-md-4">
                                    <label class="font-weight-bold">GSTIN</label>
                                    <input type="text" class="form-control" placeholder="GSTIN" name="business_outlet_gst_in" minlength="15" maxlength="15">
                                  </div>
                                </div>
                                <div class="form-row">
                                  <div class="form-group col-md-4">
                                    <label class="font-weight-bold">Address</label>
                                    <input type="text" class="form-control" placeholder="Apartment, studio, or floor" name="business_outlet_address">
                                  </div>

                                  <div class="form-group col-md-4">
                                    <label class="font-weight-bold">Email</label>
                                    <input type="email" class="form-control" placeholder="Email ID" name="business_outlet_email">
                                  </div>
                                  <div class="form-group col-md-4">
                                    <label class="font-weight-bold">Mobile</label>
                                    <input type="text" class="form-control" placeholder="Mobile Number" data-mask="0000000000" name="business_outlet_mobile">
                                  </div>
                                </div>
                                <div class="form-row">
                                  <div class="form-group col-md-4">
                                    <label class="font-weight-bold">Landline</label>
                                    <input type="text" class="form-control" placeholder="Landline Number" data-mask="0000000000" name="business_outlet_landline">
                                  </div>

                                  <div class="form-group col-md-4">
                                    <label class="font-weight-bold">State</label>
                                    <select name="business_outlet_state" class="form-control">
                                      <option value="" selected>Select State</option>
                                      <option value="Andaman and Nicobar Islands">Andaman and Nicobar Islands</option>
                                      <option value="Andhra Pradesh">Andhra Pradesh</option>
                                      <option value="Arunachal Pradesh">Arunachal Pradesh</option>
                                      <option value="Assam">Assam</option>
                                      <option value="Bihar">Bihar</option>
                                      <option value="Chandigarh">Chandigarh</option>
                                      <option value="Chhattisgarh">Chhattisgarh</option>
                                      <option value="Dadra and Nagar Haveli">Dadra and Nagar Haveli</option>
                                      <option value="Daman and Diu">Daman and Diu</option>
                                      <option value="Delhi">Delhi</option>
                                      <option value="Goa">Goa</option>
                                      <option value="Gujarat">Gujarat</option>
                                      <option value="Haryana">Haryana</option>
                                      <option value="Himachal Pradesh">Himachal Pradesh</option>
                                      <option value="Jammu and Kashmir">Jammu and Kashmir</option>
                                      <option value="Jharkhand">Jharkhand</option>
                                      <option value="Karnataka">Karnataka</option>
                                      <option value="Kerala">Kerala</option>
                                      <option value="Lakshadweep">Lakshadweep</option>
                                      <option value="Madhya Pradesh">Madhya Pradesh</option>
                                      <option value="Maharashtra">Maharashtra</option>
                                      <option value="Manipur">Manipur</option>
                                      <option value="Meghalaya">Meghalaya</option>
                                      <option value="Mizoram">Mizoram</option>
                                      <option value="Nagaland">Nagaland</option>
                                      <option value="Orissa">Orissa</option>
                                      <option value="Pondicherry">Pondicherry</option>
                                      <option value="Punjab">Punjab</option>
                                      <option value="Rajasthan">Rajasthan</option>
                                      <option value="Sikkim">Sikkim</option>
                                      <option value="Tamil Nadu">Tamil Nadu</option>
                                      <option value="Tripura">Tripura</option>
                                      <option value="Uttaranchal">Uttaranchal</option>
                                      <option value="Uttar Pradesh">Uttar Pradesh</option>
                                      <option value="West Bengal">West Bengal</option>
                                    </select>
                                  </div>
                                  <div class="form-group col-md-4">
                                    <label class="font-weight-bold">City</label>
                                    <input type="text" class="form-control" name="business_outlet_city" placeholder="City">
                                  </div>
                                </div>
                                <div class="form-row">
                                  <div class="form-group col-md-4">
                                    <label class="font-weight-bold">ZipCode</label>
                                    <input type="number" pattern="[0-9]+" maxlength="6" min="000000" max="999999" class="form-control" name="business_outlet_pincode" placeholder="Zip">
                                  </div>
                                  <div class="form-group col-md-4">
                                    <label class="font-weight-bold">Facebook URL</label>
                                    <input type="text" class="form-control" name="business_outlet_facebook_url" placeholder="Facebook URL">
                                  </div>
                                  <div class="form-group col-md-4">
                                    <label class="font-weight-bold">Instagram URL</label>
                                    <input type="text" class="form-control" name="business_outlet_instagram_url" placeholder=" Instagram URL">
                                  </div>
                                </div>
                                <div class="form-row">
                                  <div class="form-group col-md-4">
                                    <label class="font-weight-bold">SMS Sender Id</label>
                                    <input type="text" class="form-control" name="business_outlet_sender_id" />
                                  </div>
                                  <div class="form-group col-md-4">
                                    <label class="font-weight-bold">Google My Business</label>
                                    <input type="text" class="form-control" name="business_outlet_google_my_business_url" placeholder="Google My Business URL">
                                  </div>
                                  <div class="form-group col-md-4">
                                    <label class="font-weight-bold">API Key</label>
                                     <input type="text" class="form-control" name="api_key" id="api_key" /> 
                                  </div>
																</div>
																<div class="form-row">
                                  <div class="form-group col-md-4">
                                    <label class="font-weight-bold">Whatsapp Number</label>
                                    <input type="text" class="form-control" name="business_whatsapp_number" />
                                  </div>
                                  <div class="form-group col-md-4">
                                    <label class="font-weight-bold">Whatsapp Userid</label>
                                    <input type="text" class="form-control" name="whatsapp_userid" placeholder="Whatsapp Userid">
                                  </div>
                                  <div class="form-group col-md-4">
                                    <label class="font-weight-bold">Whatsapp Key</label>
                                     <input type="text" class="form-control" name="whatsapp_key" /> 
                                  </div>
                                </div>
                                <div class="form-row ">
                                  <div class="form-group col-md-6">
                                    <label class="font-weight-bold">Bill Header Message</label>
                                    <textarea class="form-control" rows="2" placeholder="Bill Header Message" name="business_outlet_bill_header_msg"></textarea>
                                  </div>
                                  <div class="form-group col-md-6">
                                    <label class="font-weight-bold">Bill Footer Message</label>
                                    <textarea class="form-control" rows="2" placeholder="Bill footer Message" name="business_outlet_bill_footer_msg"></textarea>
                                  </div>
                                </div>
                                <div class="form-row ">
                                  <div class="form-group col-md-4">
                                    <label class="font-weight-bold">Latitude</label>
                                    <input type="text" class="form-control" name="business_outlet_latitude" placeholder="Outlet Latitude">
                                  </div>
                                  <div class="form-group col-md-4">
                                    <label class="font-weight-bold">Longitude</label>
                                    <input type="text" class="form-control" name="business_outlet_longitude" placeholder="Outlet Longitude">
                                  </div>
                                  <div class="form-group col-md-4">
                                    <label class="font-weight-bold">Outlet Location</label>
                                    <input type="text" class="form-control" name="business_outlet_location" placeholder="Outlet Location" required>
                                  </div>
                                </div>
                                <div class="form-row">
                                  <div class="form-group col-md-12">
                                    <input type="hidden" name="business_outlet_id" readonly="true">
                                    <input type="hidden" name="business_outlet_business_admin" value="<?= $admin_id ?>">
                                    <input type="hidden" class="form-control" name="business_outlet_creation_date" id="business_outlet_creation_date">
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                  </div>
                                </div>
                              </form>
                              <div class="alert alert-dismissible feedback" role="alert" style="margin:0px;">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                  <span aria-hidden="true">&times;</span>
                                </button>
                                <div class="alert-message">
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                        <!-- Loyalty Modal -->

                        <?php
                        foreach ($admin_module as $modules) {
                          if ($modules['package_id'] == 9) {
                            echo '<div class="tab-pane" id="appointment_2" role="tabpanel">
                                   <div class="row">
                                    <div class="modal-body">
                                      <div class="form-row">
                                          <div class="form-group col-md-4">
                                            <label>Scheme Configured </label>
                                          </div>
                                      </div>
                                      <form id="ExistingLoyaltyRule" method="POST" action="#">
                                        <div class="form-row">
                                          <div class="form-group col-md-5">
                                            <label>Selected Rule</label>
                                            <input id ="business_rule_selected" name="business_rule_selected" class="form-control" readonly>
                                          </div>
                                          <div class="form-group col-md-5">
                                            <label>Rule Validity</label>
                                            <input class="form-control" id="business_outlet_rule_validity_selected"  name="business_outlet_rule_validity_selected" class="business_outlet_rule_validity_selected" readonly>
                                          </div>
                                          <div class="form-group col-md-2">
                                            <label>Action</label><br>
                                            <button type="button" class="btn btn-primary edit_outlet_rule" business_outlet_id="" business_outlet_admin_id=' . $admin_id . '>EDIT</button>
                                          </div>
                                        </div>
                                        <div class="form-row">
                                          <table id="ExistingRule" class="form-group col-md-12">
                                          </table>
                                        </div>
                                      </form>
                                      <form id="LoyaltyRule" method="POST" action="#">
                                        <div class="form-row">
                                          <div class="form-group col-md-6">
                                            <label>Select Rule</label>
                                            <select id="business_outlet_rule"  name="business_outlet_rule" class="form-control" onchange="toggleRule()" required>
                                            <option value="Select Rule" selected>Select rule</option>
                                            <option value="Offers Single Rule">Offers Single Rule</option>
                                            <option value="Offers Multiple Rule">Offers Multiple Rule</option>
                                            <option value="Cashback Single Rule" >Cashback Single Rule</option>
                                            <option value="Cashback Multiple Rule" >Cashback Multiple Rule</option>
                                            <option value="Offers LTV Rule" >Offers LTV Rule</option>
                                            <option value="Cashback LTV Rule" >Cashback LTV Rule</option>
                                            <option value="Cashback Visits">Cashback Visits</option>
                                            </select>
                                          </div>											
                                          <div class="form-group col-md-6 ">
                                            <label>Rule Validity</label>
                                            <select class="form-control" name="business_outlet_rule_validity" class="business_outlet_rule_validity">
                                            <option selected disabled> -- Select Validity -- </option>
                                            <option value="1 month">1 month</option>
                                            <option value="2 months">2 months</option>
                                            <option value="3 months">3 months</option>
                                            <option value="4 months">4 months</option>
                                            <option value="5 months">5 months</option>
                                            <option value="6 months">6 months</option>
                                            <option value="9 months">9 months</option>
                                            <option value="12 months">12 months</option>
                                            <option value="18 months">18 months</option>
                                            <option value="24 months">24 months</option>
                                            <option value="36 months">36 months</option>
                                            <option value="48 months">48 months</option>
                                            <option value="60 months">60 months</option>
                                            <option value="Lifetime">Lifetime</option>
                                            </select>
                                          </div>
                                        </div>
                                        <div id="rules">
                                        </div>';
                          }
                        }
                        ?>
                        <div class="form-row">
                          <div class="form-group col-md-12">
                            <input type="hidden" name="loyalty_business_outlet_id" readonly="true">
                            <input type="hidden" name="business_outlet_business_admin" value="<?= $admin_id ?>">
                            <!-- <input type="hidden" class="form-control" name="business_outlet_creation_date" id="business_outlet_creation_date"> -->
                            <button type="submit" class="btn btn-primary .outlet-loyalty-btn">Submit</button>
                          </div>
                        </div>
                        </form>
                        <div class="alert alert-dismissible feedback" role="alert" style="margin:0px;">
                          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
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
          </div>
        </div>
        <!-- End of Loyalty Modal -->
      </div>
  </div>
</div>
<!-- Modal -->
<div class="modal" id="ModalAddOutlet" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title text-white">Add Outlet</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="AddOutlet" method="POST" action="#">
          <div class="form-row">
            <div class="form-group col-md-4">
              <label class="font-weight-bold">Outlet Name</label>
              <input type="text" class="form-control" placeholder="Outlet Name" name="business_outlet_name">
            </div>
            <div class="form-group col-md-4">
              <label class="font-weight-bold">Expiry Date</label>
              <input type="date" class="form-control" name="business_outlet_expiry_date">
            </div>

            <div class="form-group col-md-4">
              <label class="font-weight-bold">GSTIN</label>
              <input type="text" class="form-control" placeholder="GSTIN" name="business_outlet_gst_in" minlength="15" maxlength="15">
            </div>
          </div>
          <div class="form-row">
            <div class="form-group col-md-4">
              <label class="font-weight-bold">Address</label>
              <input type="text" class="form-control" placeholder="Apartment, studio, or floor" name="business_outlet_address">
            </div>

            <div class="form-group col-md-4">
              <label class="font-weight-bold">Email</label>
              <input type="email" class="form-control" placeholder="Email ID" name="business_outlet_email">
            </div>
            <div class="form-group col-md-4">
              <label class="font-weight-bold">Mobile</label>
              <input type="text" class="form-control" placeholder="Mobile Number" data-mask="0000000000" name="business_outlet_mobile">
            </div>
          </div>
          <div class="form-row">
            <div class="form-group col-md-4">
              <label class="font-weight-bold">Landline</label>
              <input type="text" class="form-control" placeholder="Landline Number" maxlength="15" name="business_outlet_landline">
            </div>

            <div class="form-group col-md-4">
              <label class="font-weight-bold">State</label>
              <select name="business_outlet_state" class="form-control">
                <option value="" selected>Select State</option>
                <option value="Andaman and Nicobar Islands">Andaman and Nicobar Islands</option>
                <option value="Andhra Pradesh">Andhra Pradesh</option>
                <option value="Arunachal Pradesh">Arunachal Pradesh</option>
                <option value="Assam">Assam</option>
                <option value="Bihar">Bihar</option>
                <option value="Chandigarh">Chandigarh</option>
                <option value="Chhattisgarh">Chhattisgarh</option>
                <option value="Dadra and Nagar Haveli">Dadra and Nagar Haveli</option>
                <option value="Daman and Diu">Daman and Diu</option>
                <option value="Delhi">Delhi</option>
                <option value="Goa">Goa</option>
                <option value="Gujarat">Gujarat</option>
                <option value="Haryana">Haryana</option>
                <option value="Himachal Pradesh">Himachal Pradesh</option>
                <option value="Jammu and Kashmir">Jammu and Kashmir</option>
                <option value="Jharkhand">Jharkhand</option>
                <option value="Karnataka">Karnataka</option>
                <option value="Kerala">Kerala</option>
                <option value="Lakshadweep">Lakshadweep</option>
                <option value="Madhya Pradesh">Madhya Pradesh</option>
                <option value="Maharashtra">Maharashtra</option>
                <option value="Manipur">Manipur</option>
                <option value="Meghalaya">Meghalaya</option>
                <option value="Mizoram">Mizoram</option>
                <option value="Nagaland">Nagaland</option>
                <option value="Orissa">Orissa</option>
                <option value="Pondicherry">Pondicherry</option>
                <option value="Punjab">Punjab</option>
                <option value="Rajasthan">Rajasthan</option>
                <option value="Sikkim">Sikkim</option>
                <option value="Tamil Nadu">Tamil Nadu</option>
                <option value="Tripura">Tripura</option>
                <option value="Uttaranchal">Uttaranchal</option>
                <option value="Uttar Pradesh">Uttar Pradesh</option>
                <option value="West Bengal">West Bengal</option>
              </select>
            </div>
            <div class="form-group col-md-4">
              <label class="font-weight-bold">City</label>
              <input type="text" class="form-control" name="business_outlet_city" placeholder="City">
            </div>
          </div>
          <div class="form-row">
            <div class="form-group col-md-4">
              <label class="font-weight-bold">ZipCode</label>
              <input type="number" pattern="[0-9]+" maxlength="6" min="000000" max="999999" class="form-control" name="business_outlet_pincode" placeholder="Zip">
            </div>
            <div class="form-group col-md-4">
              <label class="font-weight-bold">Facebook URL</label>
              <input type="text" class="form-control" name="business_outlet_facebook_url" placeholder="Facebook URL">
            </div>
            <div class="form-group col-md-4">
              <label class="font-weight-bold">Instagram URL</label>
              <input type="text" class="form-control" name="business_outlet_instagram_url" placeholder="Instagram URL">
            </div>
          </div>
          <div class="form-row">
            <div class="form-group col-md-4">
              <label class="font-weight-bold">SMS Sender Id</label>
              <input type="text" class="form-control" name="business_outlet_sender_id" placeholder="Sender Id">
            </div>
            <div class="form-group col-md-4">
              <label class="font-weight-bold">Google My Business</label>
              <input type="text" class="form-control" name="business_outlet_google_my_business_url" placeholder="Google My Business URL">
            </div>
            <div class="form-group col-md-4">
              <label class="font-weight-bold">API Key</label>
              <input type="text" class="form-control" name="api_key" id="api_key" placeholder="API Key" />
            </div>
					</div>
					<div class="form-row">
            <div class="form-group col-md-4">
              <label class="font-weight-bold">Whatsapp Number</label>
              <input type="text" class="form-control" name="business_whatsapp_number" placeholder="Business WhatsApp Number">
            </div>
            <div class="form-group col-md-4">
              <label class="font-weight-bold">User Id</label>
              <input type="text" class="form-control" name="whatsapp_userid" placeholder="WhatsApp Userid">
            </div>
            <div class="form-group col-md-4">
              <label class="font-weight-bold">Whatsapp Key</label>
              <input type="text" class="form-control" name="whatsapp_key" placeholder="WhatsApp Key" />
            </div>
          </div>
          <div class="form-row ">
            <div class="form-group col-md-6">
              <label class="font-weight-bold">Bill Header Message</label>
              <textarea class="form-control" rows="2" placeholder="Bill Header Message" name="business_outlet_bill_header_msg"></textarea>
            </div>
            <div class="form-group col-md-6">
              <label class="font-weight-bold">Bill Footer Message</label>
              <textarea class="form-control" rows="2" placeholder="Bill footer Message" name="business_outlet_bill_footer_msg"></textarea>
            </div>
          </div>
          <div class="form-row ">
            <div class="form-group col-md-4">
              <label class="font-weight-bold">Latitude</label>
              <input type="text" class="form-control" name="business_outlet_latitude" placeholder="Outlet Latitude">
            </div>
            <div class="form-group col-md-4">
              <label class="font-weight-bold">Longitude</label>
              <input type="text" class="form-control" name="business_outlet_longitude" placeholder="Outlet Longitude">
            </div>
            <div class="form-group col-md-4">
              <label class="font-weight-bold">Outlet Location</label>
              <input type="text" class="form-control" name="business_outlet_location" placeholder="Outlet Location" required>
            </div>
          </div>
          <div class="form-row">
            <div class="form-group col-md-12">
              <input type="hidden" name="business_outlet_business_admin" value="<?= $admin_id ?>">
              <button type="submit" class="btn btn-primary">Submit</button>
            </div>
          </div>
        </form>
        <div class="alert alert-dismissible feedback" role="alert" style="margin:0px;">
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
          <div class="alert-message">
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal" id="defaultModalSuccess" tabindex="-1" role="dialog" aria-hidden="true">
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

<div class="modal" id="ModalEditOutlet" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title text-white">Add Outlet</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

    </div>
  </div>

  <!-- end -->
</div>
</div>
<div class="row">
  <div class="col-md-12">
    <div class="card" style="width:100%;">
      <div class="card-header">
        <div class="row">
          <div class="col-md-6">
            <h1 class="h3 mb-3">Business Admin Module</h1>
          </div>
          <div class="col-md-6">
            <!--<h1 class="h3 mb-3">Opted</h1>-->
          </div>
        </div>
      </div>
      <div class="card-body">
        <table class="table table-hover table-striped" style="width: 100%;">
          <thead>
            <tr>
              <th>Module Name</th>
              <th>Purchased</th>
            </tr>
          </thead>
          <tbody>
            <?php
            foreach ($module as $mod) {
            ?>
              <tr>
                <td><?= $mod['package_name'] ?></td>
                <?php
                foreach ($admin_module as $admin) {
                  if ($mod['package_id'] == $admin['package_id']) {
                ?>
                    <td><input type="checkbox" class="adminModule" value="<?= $admin['package_id'] ?>" business_admin_id="<?= $admin['business_admin_id'] ?>" checked="checked"></td>
              </tr>
        <?php
                  }
                }
              }
        ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<div class="modal" id="ModalAddRule" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title text-white">Add Rule</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form method="POST" action="#" id="AddMoreRule">
          <table id="ModalMultipleRuleTable" class="table table-hover">
            <input type="text" readonly id="AddRuleExistingOffer" name="business_outlet_rule" value="">
            <input type="text" readonly id="AddRuleExistingOfferValid" name="business_outlet_rule_validity" value="" hidden>
            <tbody>
              <th>Amount(Rs)</th>
              <th></th>
              <th>Amount(Rs)</th>
              <th></th>
              <th>Points(%)</th>
              <tr>
                <td>
                  <div class="form-group">
                    <input type="number" placeholder="Enter Amount" name="amount1[]" class="form-control" id="om_amount1" required>
                  </div>
                </td>
                <td>to</td>
                <td>
                  <div class="form-group">
                    <input type="number" placeholder="Enter Amount" name="amount2[]" id="om_amount2" onkeyup="amountValidate()" class="form-control">
                  </div>
                </td>
                <td>
                  <label>=</label>
                </td>
                <td>
                  <div class="form-group">
                    <input type="number" placeholder="Enter Points" name="points[]" class="form-control" required>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
          <div class="form-row">
            <div class="form-group col-md-12">
              <input type="hidden" name="loyalty_business_outlet_id" readonly="true">
              <input type="hidden" name="business_outlet_business_admin" value="<?= $admin_id ?>">
              <button type="submit" class="btn btn-primary .outlet_addloyalty_btn">Submit</button>
            </div>
          </div>
        </form>
      </div>

    </div>
  </div>
</div>
<div class="modal" id="ModalEditRule" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title text-white">Edit Rule</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="EditLoyaltyRule" method="POST" action="#">
          <div class="form-row">
            <div class="form-group col-md-6">
              <label>Select Rule</label>
              <select id="edit_business_outlet_rule" name="edit_business_outlet_rule" class="form-control" onchange="EdittoggleRule()" required>
                <option value="Select Rule" selected>Select rule</option>
                <option value="Offers Single Rule">Offers Single Rule</option>
                <option value="Offers Multiple Rule">Offers Multiple Rule</option>
                <option value="Cashback Single Rule">Cashback Single Rule</option>
                <option value="Cashback Multiple Rule">Cashback Multiple Rule</option>
                <option value="Offers LTV Rule">Offers LTV Rule</option>
                <option value="Cashback LTV Rule">Cashback LTV Rule</option>
                <option value="Cashback Visits">Cashback Visits</option>
              </select>
            </div>
            <div class="form-group col-md-6 ">
              <label>Rule Validity</label>
              <select class="form-control" name="edit_business_outlet_rule_validity" class="edit_business_outlet_rule_validity">
                <option selected disabled> -- Select Validity -- </option>
                <option value="1 month">1 month</option>
                <option value="2 months">2 months</option>
                <option value="3 months">3 months</option>
                <option value="4 months">4 months</option>
                <option value="5 months">5 months</option>
                <option value="6 months">6 months</option>
                <option value="9 months">9 months</option>
                <option value="12 months">12 months</option>
                <option value="18 months">18 months</option>
                <option value="24 months">24 months</option>
                <option value="36 months">36 months</option>
                <option value="48 months">48 months</option>
                <option value="60 months">60 months</option>
                <option value="Lifetime">Lifetime</option>
              </select>
            </div>
          </div>
          <div id="edit_rules">
          </div>
          <div class="form-row">
            <div class="form-group col-md-12">
              <input type="hidden" name="business_outlet_id" readonly="true">
              <input type="hidden" name="business_outlet_business_admin" value="<?= $admin_id ?>">
              <button type="submit" class="btn btn-primary .outlet-loyalty-btn">Submit</button>
            </div>
          </div>
        </form>
        <div class="alert alert-dismissible feedback" role="alert" style="margin:0px;">
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
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
</main>
<?php
$this->load->view('superAdmin/sa_footer_view');
?>
<script type="text/javascript">
  $(document).ready(function() {
    $('input[type="checkbox"]').click(function() {
      if ($(this).prop("checked") == true) {
        var parameters = {
          package_id: $(this).val(),
          business_admin_id: $(this).attr('business_admin_id'),
          package_exoiry_date: '2030-12-31'
        };
        $.ajax({
          url: "<?= base_url() ?>SuperAdmin/UpdateModule",
          data: parameters,
          type: "POST",
          // crossDomain: true,
          cache: false,
          // dataType: "json",
          success: function(data) {
            if (data.success == 'true') {
              alert("Module Successully Assigned To Admin")
            } else if (data.success == 'false') {
              alert("Error in Assigning Module");
            }
          },
          error: function(data) {
            $('.feedback').addClass('alert-danger');
            $('.alert-message').html("").html(data.message);
          }
        });
      } else if ($(this).prop("checked") == false) {
        var parameters = {
          package_id: $(this).val(),
          business_admin_id: $(this).attr('business_admin_id'),
          package_expiry_date: '2018-01-10'
        };
        $.ajax({
          url: "<?= base_url() ?>SuperAdmin/UpdateModule",
          data: parameters,
          type: "POST",
          // crossDomain: true,
          cache: false,
          // dataType: "json",
          success: function(data) {
            if (data.success == 'true') {
              alert("You Revoked Module From Admin")
            } else if (data.success == 'false') {
              alert("Error in Assigning Module");
            }
          },
          error: function(data) {
            alert(" Something Error!")
          }
        });
      }
    });
    $(document).ajaxStart(function() {
      $("#load_screen").show();
    });

    $(document).ajaxStop(function() {
      $("#load_screen").hide();
    });

    $("#AddOutlet").validate({
      errorElement: "div",
      rules: {
        "business_outlet_name": {
          required: true,
          maxlength: 100
        },
        "business_outlet_address": {
          required: true
        },
        "business_outlet_pincode": {
          required: true,
          maxlength: 10,
        },
        "business_outlet_state": {
          required: true,
          maxlength: 100,
        },
        "business_outlet_city": {
          required: true,
          maxlength: 100,
        },
        "business_outlet_country": {
          required: true,
          maxlength: 100,
        },
        "business_outlet_email": {
          email: true
        },
        "business_outlet_facebook_url": {
          url: true
        },
        "business_outlet_instagram_url": {
          url: true
        },
        "business_outlet_mobile": {
          maxlength: 10,
          minlength: 10
        },
        "business_outlet_landline": {
          maxlength: 15
        },
        "business_outlet_gst_in": {
          maxlength: 15,
          minlength: 15
        }
      },
      submitHandler: function(form) {
        var formData = $("#AddOutlet").serialize();
        $.ajax({
          url: "<?= base_url() ?>SuperAdmin/AddOutlet",
          data: formData,
          type: "POST",
          // crossDomain: true,
          cache: false,
          // dataType: "json",
          success: function(data) {
            if (data.success == 'true') {
              $("#ModalAddOutlet").modal('hide');
              $('#defaultModalSuccess').modal('show').on('shown.bs.modal', function(e) {
                $("#SuccessModalMessage").html("").html(data.message);
              }).on('hidden.bs.modal', function(e) {
                window.location.reload();
              });
            } else if (data.success == 'false') {
              if ($('.feedback').hasClass('alert-success')) {
                $('.feedback').removeClass('alert-success').addClass('alert-danger');
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

    $("#EditOutlet").validate({
      errorElement: "div",
      rules: {
        "business_outlet_name": {
          required: true,
          maxlength: 100
        },
        "business_outlet_address": {
          required: true
        },
        "business_outlet_pincode": {
          required: true,
          maxlength: 10,
        },
        "business_outlet_state": {
          required: true,
          maxlength: 100,
        },
        "business_outlet_city": {
          required: true,
          maxlength: 100,
        },
        "business_outlet_country": {
          required: true,
          maxlength: 100,
        },
        "business_outlet_email": {
          email: true
        },
        "business_outlet_facebook_url": {
          url: true
        },
        "business_outlet_instagram_url": {
          url: true
        },
        "business_outlet_mobile": {
          maxlength: 10,
          maxlength: 10
        },
        "business_outlet_landline": {
          maxlength: 15
        },
        "business_outlet_gst_in": {
          maxlength: 15,
          minlength: 15
        },
        "business_outlet_expiry_date":{
            required:true
        }
      },
      submitHandler: function(form) {
        var formData = $("#EditOutlet").serialize();
        $.ajax({
          url: "<?= base_url() ?>SuperAdmin/EditOutlet",
          data: formData,
          type: "POST",
          // crossDomain: true,
          cache: false,
          // dataType: "json",
          success: function(data) {
            if (data.success == 'true') {
              $("#ModalEditOutlet").modal('hide');
            //   alert(data.message);
              toastr["success"](data.message,"", {
                positionClass: "toast-top-right",
                progressBar: "toastr-progress-bar",
                newestOnTop: "toastr-newest-on-top",
                rtl: $("body").attr("dir") === "rtl" || $("html").attr("dir") === "rtl",
                timeOut: 1000   
            });
            setTimeout(function () { location.reload(1); }, 1000);
            //   });
            } else if (data.success == 'false') {
              if ($('.feedback').hasClass('alert-success')) {
                $('.feedback').removeClass('alert-success').addClass('alert-danger');
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
  });
  //Loyalty Form
  function toggleRule() {

    var selectedElement = $('#business_outlet_rule option:selected').val();

    if (selectedElement == "Offers Single Rule") {
      // document.getElementById('rules')[0].reset();
      document.getElementById('rules').innerHTML = '';
      var target = document.getElementById('rules');
      var p = document.createElement('div');
      p.innerHTML = '<table id="SingleRuleTable" class="table table-hover"><label>Define Offers Single Rule</label><tbody><th>Amount(Rs)</th><th></th><th>Points(%)</th><tr><td><div class="form-group"><input type="number" placeholder="Enter Amount" name="amount1[]" class="form-control" required></div></td><td><label>=</label></td><td><div class="form-group"><input type="number" placeholder="Enter Points" name="points[]" class="form-control" required></div></td></tr></tbody></table>';
      target.appendChild(p);
    }

    if (selectedElement == "Offers Multiple Rule") {
      document.getElementById('rules').innerHTML = '';
      var target = document.getElementById('rules');
      var p = document.createElement('div');
      p.innerHTML = '<table id="MultipleRuleTable" class="table table-hover"><label>Define Offers Multiple Rule</label><tbody><th>Amount(Rs)</th><th></th><th>Amount(Rs)</th><th></th><th>Points(%)</th><tr><td><div class="form-group"><input type="number" placeholder="Enter Amount" name="amount1[]" class="form-control" id="om_amount1"  required></div></td><td>to</td><td><div class="form-group"><input type="number" placeholder="Enter Amount" name="amount2[]" id="om_amount2" onkeyup="amountValidate()" class="form-control" required></div></td><td><label>=</label></td><td><div class="form-group"><input type="number" placeholder="Enter Points" name="points[]" class="form-control" required></div></td></tr></tbody></table><button type="button" class="btn btn-success" id="AddMultipleRule" onclick="AddOfferMultipleRule()">Add <i class="fa fa-plus" aria-hidden="true"></i></button>&ensp;<button type="button" class="btn btn-danger" onclick="DeleteOfferMultipleRule()" id="DeleteMultipleRule">Delete <i class="fa fa-trash" aria-hidden="true"></i></button>';
      target.append(p);
      // document.getElementById("rules").append('<table id="MultipleRuleTable" class="table table-hover"><label>Define Offers Multiple Rule</label><tbody><th>Amount(Rs)</th><th></th><th>Amount(Rs)</th><th></th><th>Points(%)</th><tr><td><div class="form-group"><input type="number" placeholder="Enter Amount" name="amount1[]" class="form-control" id="om_amount1"  required></div></td><td>to</td><td><div class="form-group"><input type="number" placeholder="Enter Amount" name="amount2[]" id="om_amount2" onkeyup="amountValidate()" class="form-control" required></div></td><td><label>=</label></td><td><div class="form-group"><input type="number" placeholder="Enter Points" name="points[]" class="form-control" required></div></td></tr></tbody></table><button type="button" class="btn btn-success" id="AddMultipleRule" onclick="AddOfferMultipleRule()">Add <i class="fa fa-plus" aria-hidden="true"></i></button>&ensp;<button type="button" class="btn btn-danger" onclick="DeleteOfferMultipleRule()" id="DeleteMultipleRule">Delete <i class="fa fa-trash" aria-hidden="true"></i></button>');
      //target.append("document.createElement('table').attr({id:'MultipleRuleTable',class:'table table-hover'})");
    }

    if (selectedElement == "Cashback Single Rule") {
      document.getElementById('rules').innerHTML = '';
      var target = document.getElementById('rules');
      var p = document.createElement('div');
      p.innerHTML = '<table id="CashbackSingleRuleTable" class="table table-hover"><label>Define Cashback Single Rule</label><tbody><th>Amount(Rs)</th><th></th><th>Points(Cashback value in Rs)</th><tr><td><div class="form-group"><input type="number" placeholder="Enter Amount" name="amount1[]" class="form-control" required></div></td><td><label>=</label></td><td><div class="form-group"><input type="number" placeholder="Enter Cashback Amount" name="cashback[]" class="form-control" required></div></td></tr></tbody></table>';
      target.appendChild(p);
    }

    if (selectedElement == "Cashback Multiple Rule") {
      document.getElementById('rules').innerHTML = '';
      var target = document.getElementById('rules');
      var p = document.createElement('div');
      p.innerHTML = '<table id="CashbackMultipleRuleTable" class="table table-hover"><label>Define Cashback Multiple Rule</label><tbody><th>Amount(Rs)</th><th></th><th>Amount(Rs)</th><th></th><th>Points(Cashback value in Rs)</th><tr><td><div class="form-group"><input type="number" placeholder="Enter Amount" name="amount1[]" class="form-control" required></div></td><td>to</td><td><div class="form-group"><input type="number" placeholder="Enter Amount" name="amount2[]" class="form-control" required></div></td><td><label>=</label></td><td><div class="form-group"><input type="number" placeholder="Enter Points" name="cashback[]" class="form-control" required></div></td></tr></tbody></table><button type="button" class="btn btn-success" onclick="AddCashbackMultipleRule()" id="NewAddCashbackMultipleRule">Add <i class="fa fa-plus" aria-hidden="true"></i></button>&ensp;<button type="button" class="btn btn-danger" onclick="DeleteCashbackMultipleRule()" id="NewDeleteCashbackMultipleRule">Delete <i class="fa fa-trash" aria-hidden="true"></i></button>';
      target.appendChild(p);
    }

    if (selectedElement == "Offers LTV Rule") {
      document.getElementById('rules').innerHTML = '';
      var target = document.getElementById('rules');
      var p = document.createElement('div');
      p.innerHTML = ' <table id="OffersLTVRuleTable" class="table table-hover"><label>Define Offers LTV Rule</label><tbody><th>Amount(Rs)</th><th></th><th>Amount(Rs)</th><th></th><th>Points(%)</th><tr><td><div class="form-group"><input type="number" placeholder="Enter Amount" name="amount1[]" class="form-control" required></div></td><td>to</td><td><div class="form-group"><input type="number" placeholder="Enter Amount" name="amount2[]" class="form-control" required></div></td><td><label>=</label></td><td><div class="form-group"><input type="number" placeholder="Enter Points" name="points[]" class="form-control" required></div></td><td></tr></tbody></table><button type="button" class="btn btn-success" name="AddLTVOfferRule()" id="NewAddLTVOfferRule" onclick="AddLTVOfferRule()">Add <i class="fa fa-plus" aria-hidden="true"></i></button>&ensp;<button type="button" class="btn btn-danger" onclick="DeleteLTVOfferRule()" id="NewDeleteLTVOfferRule">Delete <i class="fa fa-trash" aria-hidden="true"></i></button>'
      target.appendChild(p);
    }

    if (selectedElement == "Cashback LTV Rule") {
      document.getElementById('rules').innerHTML = '';
      var target = document.getElementById('rules');
      var p = document.createElement('div');
      p.innerHTML = '<table id="CashbackLTVRuleTable" class="table table-hover"><label>Define Cashback LTV Rule</label><tbody><th>Amount(Rs)</th><th></th><th>Amount(Rs)</th><th></th><th>Points(Cashback Value in Rs)</th><tr><td><div class="form-group"><input type="number" placeholder="Enter Amount" name="amount1[]" class="form-control" required></div></td><td>to</td><td><div class="form-group"><input type="number" placeholder="Enter Amount" name="amount2[]" class="form-control" required></div></td><td><label>=</label></td><td><div class="form-group"><input type="number" placeholder="Enter Points" name="cashback[]" class="form-control" required></div></td></tr></tbody></table><button type="button" class="btn btn-success" onclick="AddCashbackLTVRule()" id="NewAddCashbackLTVRule">Add <i class="fa fa-plus" aria-hidden="true"></i></button>&ensp;<button type="button" class="btn btn-danger" onclick="DeleteCashbackLTVRule()" id="NewDeleteCashbackLTVRule">Delete <i class="fa fa-trash" aria-hidden="true"></i></button>'
      target.appendChild(p);
    }

    if (selectedElement == "Cashback Visits") {
      document.getElementById('rules').innerHTML = '';
    }

  }
  //Add Offer Multiple Rule Button
  function AddOfferMultipleRule() {
    event.preventDefault();
    this.blur();
    // alert($('#MultipleRuleTable tr').length);
    var rowno = $("#MultipleRuleTable tr").length;

    rowno = rowno + 1;

    $('#MultipleRuleTable tr:last').after('<tr><td><div class="form-group"><input type="number" placeholder="Enter Amount" name="amount1[]" class="form-control" required></div></td><td>to</td><td><div class="form-group"><input type="number" placeholder="Enter Amount" name="amount2[]"  class="form-control" required></div></td><td><label>=</label></td><td><div class="form-group"><input type="number" placeholder="Enter Points"  name="points[]" class="form-control" required></div></td></tr>');

  }

  //Delete Offer Multiple Rule Button
  function DeleteOfferMultipleRule() {
    event.preventDefault();
    this.blur();
    var rowno = $("#MultipleRuleTable tr").length;
    if (rowno > 2) {
      $('#MultipleRuleTable tr:last').remove();
    }
  }
  //Add Cashback Multiple Rule Button
  function AddCashbackMultipleRule() {
    event.preventDefault();
    this.blur();
    var rowno = $("#CashbackMultipleRuleTable tr").length;

    rowno = rowno + 1;

    $('#CashbackMultipleRuleTable tr:last').after('<tr><td><div class="form-group"><input type="number" placeholder="Enter Amount" name="amount1[]" class="form-control" required></div></td><td>to</td><td><div class="form-group"><input type="number" placeholder="Enter Amount" name="amount2[]" class="form-control" required></div></td><td><label>=</label></td><td><div class="form-group"><input type="number" placeholder="Enter Points" name="cashback[]" class="form-control" required></div></td></tr></tbody></table>');
  }
  //Delete Cashback Multiple Rule Button
  function DeleteCashbackMultipleRule() {
    event.preventDefault();
    this.blur();
    var rowno = $("#CashbackMultipleRuleTable tr").length;
    if (rowno > 2) {
      $('#CashbackMultipleRuleTable tr:last').remove();
    }
  }
  //Add Offer LTV Rule Button
  function AddLTVOfferRule() {
    event.preventDefault();
    this.blur();
    var rowno = $("#OffersLTVRuleTable tr").length;

    rowno = rowno + 1;

    $('#OffersLTVRuleTable tr:last').after('<tr><td><div class="form-group"><input type="number" placeholder="Enter Amount" name="amount1[]"  class="form-control" required></div></td><td>to</td><td><div class="form-group"><input type="number" placeholder="Enter Amount" name="amount2[]"  class="form-control" required></div></td><td><label>=</label></td><td><div class="form-group"><input type="number" placeholder="Enter Points"  name="points[]" class="form-control" required></div></td></tr>')
  }
  //Delete Offer LTV Rule Button
  function DeleteLTVOfferRule() {
    event.preventDefault();
    this.blur();
    var rowno = $("#OffersLTVRuleTable tr").length;
    if (rowno > 2) {
      $('#OffersLTVRuleTable tr:last').remove();
    }
  }
  //Add Cashback LTV Rule Table
  function AddCashbackLTVRule() {
    event.preventDefault();
    this.blur();
    var rowno = 1;

    rowno = $("#CashbackLTVRuleTable tr").length;

    rowno = rowno + 1;

    $('#CashbackLTVRuleTable tr:last').after('<tr><td><div class="form-group"><input type="number" placeholder="Enter Amount" name="amount1[]"  class="form-control" required></div></td><td>to</td><td><div class="form-group"><input type="number" placeholder="Enter Amount" name="amount2[]" class="form-control" required></div></td><td><label>=</label></td><td><div class="form-group"><input type="text" placeholder="Enter Points" id="cashback[]" class="form-control" required></div></td></tr>');
  }
  //Delete Cashback LTV Rule Button
  function DeleteCashbackLTVRule() {
    event.preventDefault();
    this.blur();
    var rowno = $("#CashbackLTVRuleTable tr").length;
    if (rowno > 2) {
      $('#CashbackLTVRuleTable tr:last').remove();
    }
  }

  //Amount Comparision
  function amountValidate() {
    // alert(document.getElementById('amount1').val());
    // if(p>document.getElementsByName('amount2[]')){
    //   print("Amount 2 cannot be less");
    // }
  }
  // $(document).on('click','.outlet-loyalty-btn',function(){
  // 	$('#LoyaltyRule input[name=business_outlet_id]').val($(this).attr('business_outlet_id'));

  $('#LoyaltyRule').validate({
    errorElement: "div",
    rules: {
      "business_outlet_rule": {
        required: true,

      },
      "business_outlet_rule_validity": {
        required: true
      }
    },
    submitHandler: function(form) {
      var formData = $("#LoyaltyRule").serialize();
      $.ajax({
        url: "<?= base_url() ?>SuperAdmin/AddLoyaltyRule",
        data: formData,
        type: "POST",
        // crossDomain: true,
        cache: false,
        // dataType: "json",
        success: function(data) {
          if (data.success == 'true') {
            $("#ModalEditRule").modal('hide');
              $('#defaultModalSuccess').modal('show').on('shown.bs.modal', function(e) {
                $("#SuccessModalMessage").html("").html(data.message);
              }).on('hidden.bs.modal', function(e) {
                window.location.reload();
              });
          } else if (data.success == 'false') {
            if ($('.feedback').hasClass('alert-success')) {
              $('.feedback').removeClass('alert-success').addClass('alert-danger');
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
  // });
  //End of Loyalty Button
  $(document).on('click', '.outlet-edit-btn', function(event) {
    event.preventDefault();
    this.blur(); // Manually remove focus from clicked link.
    var parameters = {
      business_outlet_id: $(this).attr('business_outlet_id')
    };
    $.getJSON("<?= base_url() ?>SuperAdmin/GetBusinessOutlet", parameters)
      .done(function(data, textStatus, jqXHR) {

        $("#EditOutlet input[name=business_outlet_name]").attr('value', data.business_outlet_name);
        $("#EditOutlet input[name=business_outlet_gst_in]").attr('value', data.business_outlet_gst_in);
        $("#EditOutlet input[name=business_outlet_address]").attr('value', data.business_outlet_address);
        $("#EditOutlet input[name=business_outlet_location]").attr('value', data.business_outlet_location);
        $("#EditOutlet input[name=business_outlet_email]").attr('value', data.business_outlet_email);
        $("#EditOutlet input[name=business_outlet_mobile]").attr('value', data.business_outlet_mobile);
        $("#EditOutlet input[name=business_outlet_landline]").attr('value', data.business_outlet_landline);
        $("#EditOutlet input[name=business_outlet_city]").attr('value', data.business_outlet_city);
        $("#EditOutlet select[name=business_outlet_state]").val(data.business_outlet_state);
        $("#EditOutlet input[name=business_outlet_pincode]").attr('value', data.business_outlet_pincode);
        $("#EditOutlet textarea[name=business_outlet_bill_header_msg]").val(data.business_outlet_bill_header_msg);
        $("#EditOutlet textarea[name=business_outlet_bill_footer_msg]").val(data.business_outlet_bill_footer_msg);
        $("#EditOutlet input[name=business_outlet_facebook_url]").attr('value', data.business_outlet_facebook_url);
        $("#EditOutlet input[name=business_outlet_instagram_url]").attr('value', data.business_outlet_instagram_url);
        $("#EditOutlet input[name=business_outlet_sender_id]").attr('value', data.business_outlet_sender_id);
        $("#EditOutlet input[name=business_outlet_google_my_business_url]").attr('value', data.business_outlet_google_my_business_url);
        $("#EditOutlet input[name=api_key]").attr('value', data.api_key);
				$("#EditOutlet input[name=business_whatsapp_number]").attr('value', data.business_whatsapp_number);
				$("#EditOutlet input[name=whatsapp_userid]").attr('value', data.whatsapp_userid);
				$("#EditOutlet input[name=whatsapp_key]").attr('value', data.whatsapp_key);
        $("#EditOutlet input[name=business_outlet_latitude]").attr('value', data.business_outlet_latitude);
        $("#EditOutlet input[name=business_outlet_longitude]").attr('value', data.business_outlet_longitude);
        $("#EditOutlet input[name=business_outlet_id]").attr('value', data.business_outlet_id);
        $("#EditOutlet input[name=business_outlet_creation_date]").attr('value', data.business_outlet_creation_date);
        $("#EditOutlet input[name=business_outlet_expiry_date]").attr('value', data.business_outlet_expiry_date);
        $("#LoyaltyRule input[name=loyalty_business_outlet_id]").attr('value', data.business_outlet_id);
        $("#ModalEditOutlet").modal('show');

      })
      .fail(function(jqXHR, textStatus, errorThrown) {
        console.log(errorThrown.toString());
      });
  });

  //Delete / Suspend Outlet
  $(document).on('click', '.outlet-delete', function(event) {
    event.preventDefault();
    this.blur(); // Manually remove focus from clicked link.

    var parameters = {
      business_outlet_id: $(this).attr('business_outlet_id'),

      business_outlet_status: $(this).attr('business_outlet_status')
    };
    $.ajax({
      url: "<?= base_url() ?>SuperAdmin/DeleteOutlet",
      data: parameters,
      type: "POST",
      // crossDomain: true,
      cache: false,
      // dataType: "json",
      success: function(data) {
        if (data.success == 'true') {
          $("#ModalEditAdmin").modal('hide');
          $('#defaultModalSuccess').modal('show').on('shown.bs.modal', function(e) {
            $("#SuccessModalMessage").html("").html(data.message);
          }).on('hidden.bs.modal', function(e) {
            window.location.reload();
          });
        } else if (data.success == 'false') {
          if ($('.feedback').hasClass('alert-success')) {
            $('.feedback').removeClass('alert-success').addClass('alert-danger');
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
  });
  //sendmaill
  $("#sendmail").validate({
    errorElement: "div",
    rules: {
      "message": {
        required: true
      },
      "from": {
        required: true
      }
    },
    submitHandler: function(form) {
      var formData = $("#sendmail").serialize();
      $.ajax({
        url: "<?= base_url()?>iSuperAdmin/SendMail",
        data: formData,
        type: "POST",
        // crossDomain: true,
        cache: false,
        // dataType: "json",
        success: function(data) {
          if (data.success == 'true') {
            alert("Mail Send Successfully.")
          } else if (data.success == 'false') {
            alert("Error in sending mail.");
          }
        },
        error: function(data) {
          alert("Error");
        }
      });
    },
  });
</script>
<!-- Jitesh script to fetch outlet details -->
<script>
  $(document).on('click', '.outlet-edit-btn', function(event) {
    event.preventDefault();
    this.blur(); // Manually remove focus from clicked link.
    var parameters = {
      business_outlet_id: $(this).attr('business_outlet_id')
    };
    $.getJSON("<?= base_url() ?>SuperAdmin/GetBusinessOutletLoyaltyRule", parameters)
      .done(function(data, textStatus, jqXHR) {
        if (data.success == 'true') {
          //  alert(data.res_arr.length);
          $('#ExistingLoyaltyRule').trigger('reset');

          var res = data.res_arr;
              if (res[0].rule_type == 'Offers Single Rule') {
                var temp_str = "<tr><th>Sr.no<th><th>Rule Configured</th><th>Points Validity</th><th>Amount</th><th>Points</th><th>Actions</th></tr>"
                for (var i = 0; i < res.length; i++) {
                  temp_str += "<tr id=" + (i + 1) + ">";
                  temp_str += "<td>" + (i + 1) + "</td>";
                  temp_str += "<td hidden>" + res[i].rule_id + "</td>";
                  temp_str += "<td></td>";
                  temp_str += "<td>" + res[i].rule_type + "</td>";
                  temp_str += "<td>" + res[i].rule_validity + "</td>";
                  temp_str += "<td>" + res[i].amount1 + "</td>";
                  temp_str += "<td>" + res[i].points + "</td>";
                  temp_str += "<td><button type='button' class='btn btn-danger rule_delete_btn' id=" + (i + 1) + " rule_id=" + res[i].rule_id + " business_outlet_id=" + res[0].business_outlet_id + " ><i class='fa fa-trash' aria-hidden='true'></i></button></td></tr>";
                }
                $('#LoyaltyRule').attr('hidden', true);
                $('#business_rule_selected').val(res[0].rule_type);
                $('#business_outlet_rule_validity_selected').val(res[0].rule_validity);
                $('#ExistingRule').html("").html(temp_str);
              } 
              else if (res[0].rule_type == 'Offers Multiple Rule' || res[0].rule_type == 'Offers LTV Rule') {
                var temp_str = "<tr><th>Sr.no<th><th>Rule Configured</th><th>Points Validity</th><th>Amount</th><th>Amount</th><th>Points</th><th>Actions</th></tr>"
                for (var i = 0; i < res.length; i++) {
                  temp_str += "<tr id=" + (i + 1) + ">";
                  temp_str += "<td>" + (i + 1) + "</td>";
                  temp_str += "<td hidden>" + res[i].rule_id + "</td>";
                  temp_str += "<td></td>";
                  temp_str += "<td>" + res[i].rule_type + "</td>";
                  temp_str += "<td>" + res[i].rule_validity + "</td>";
                  temp_str += "<td>" + res[i].amount1 + "</td>";
                  temp_str += "<td>" + res[i].amount2 + "</td>"
                  temp_str += "<td>" + res[i].points + "</td>";
                  temp_str += "<td><button type='button' class='btn btn-danger rule_delete_btn' id=" + (i + 1) + " rule_id=" + res[i].rule_id + " business_outlet_id=" + res[0].business_outlet_id +"><i class='fa fa-trash' aria-hidden='true'></i></button></td></tr>";
                }
                temp_str += "<td><button type='button' class='btn btn-primary rule_add_btn' id=" + (i + 1) + " business_outlet_id=" + res[0].business_outlet_id + " rule_defined='" + res[0].rule_type + "' rule_valid='" + res[0].rule_validity + "' ><i class='fa fa-plus' aria-hidden='true'></i> Add</button></td>";
                $('#LoyaltyRule').attr('hidden', true);
                $('#business_rule_selected').val(res[0].rule_type);
                $('#business_outlet_rule_validity_selected').val(res[0].rule_validity);
                $('#ExistingRule').html("").html(temp_str);
              } 
              else if (res[0].rule_type == 'Cashback Single Rule') {
                var temp_str = "<tr><th>Sr.no<th><th>Rule Configured</th><th>Points Validity</th><th>Amount</th><th>Cashback</th><th>Actions</th></tr>"
                for (var i = 0; i < res.length; i++) {
                  temp_str += "<tr id=" + (i + 1) + ">";
                  temp_str += "<td>" + (i + 1) + "</td>";
                  temp_str += "<td hidden>" + res[i].rule_id + "</td>";
                  temp_str += "<td></td>";
                  temp_str += "<td>" + res[i].rule_type + "</td>";
                  temp_str += "<td>" + res[i].rule_validity + "</td>";
                  temp_str += "<td>" + res[i].amount1 + "</td>";
                  temp_str += "<td>" + res[i].cashback + "</td>";
                  temp_str += "<td><button type='button' class='btn btn-danger rule_delete_btn' id=" + (i + 1) + " rule_id=" + res[i].rule_id + " business_outlet_id=" + res[0].business_outlet_id + "><i class='fa fa-trash' aria-hidden='true'></i></button></td></tr>";
                }
                $('#LoyaltyRule').attr('hidden', true);
                $('#business_rule_selected').val(res[0].rule_type);
                $('#business_outlet_rule_validity_selected').val(res[0].rule_validity);
                $('#ExistingRule').html("").html(temp_str);
              } 
              else if (res[0].rule_type == 'Cashback Multiple Rule' || res[0].rule_type == 'Cashback LTV Rule') {
                var temp_str = "<tr><th>Sr.no<th><th>Rule Configured</th><th>Points Validity</th><th>Amount</th><th>Amount</th><th>Cashback</th><th>Actions</th></tr>"
                for (var i = 0; i < res.length; i++) {
                  temp_str += "<tr id=" + (i + 1) + ">";
                  temp_str += "<td>" + (i + 1) + "</td>";
                  temp_str += "<td hidden>" + res[i].rule_id + "</td>";
                  temp_str += "<td></td>";
                  temp_str += "<td>" + res[i].rule_type + "</td>";
                  temp_str += "<td>" + res[i].rule_validity + "</td>";
                  temp_str += "<td>" + res[i].amount1 + "</td>";
                  temp_str += "<td>" + res[i].amount2 + "</td>";
                  temp_str += "<td>" + res[i].cashback+ "</td>";
                  temp_str += "<td><button type='button' class='btn btn-danger rule_delete_btn' id=" + (i + 1) + " rule_id=" + res[i].rule_id + " business_outlet_id=" + res[0].business_outlet_id +"><i class='fa fa-trash' aria-hidden='true'></i></button></td></tr>";
                }
                temp_str += "<tr><td><button type='button' class='btn btn-primary rule_add_btn' id=" + (i + 1) + " business_outlet_id=" + res[0].business_outlet_id + " rule_defined=" + res[0].rule_type + " rule_valid = "+res[0].rule_validity+"><i class='fa fa-plus' aria-hidden='true'></i></button></td><tr>";
                $('#LoyaltyRule').attr('hidden', true);
                $('#business_rule_selected').val(res[0].rule_type);
                $('#business_outlet_rule_validity_selected').val(res[0].rule_validity);
                $('#ExistingRule').html("").html(temp_str);
              } 
             else if (res[0].rule_type == 'Cashback Visits') {
                var temp_str = "<h5>" + res[0].rule_type + " is Configured</h5>"
                var temp_str = "<tr><th>Sr.no<th><th>Rule Configured</th><th>Points Validity</th><th>Actions</th></tr>"
                for (var i = 0; i < res.length; i++) {
                  temp_str += "<tr id=" + (i + 1) + ">";
                  temp_str += "<td>" + (i + 1) + "</td>";
                  temp_str += "<td hidden>" + res[i].rule_id + "</td>";
                  temp_str += "<td></td>";
                  temp_str += "<td>" + res[i].rule_type + "</td>";
                  temp_str += "<td>" + res[i].rule_validity + "</td>";
                  temp_str += "<td><button type='button' class='btn btn-danger rule_delete_btn' id=" + (i + 1) + " rule_id=" + res[i].rule_id + " business_outlet_id=" + res[0].business_outlet_id + "><i class='fa fa-trash' aria-hidden='true'></i></button></td></tr>";
                }
                $('#LoyaltyRule').attr('hidden', true);
                $('#business_rule_selected').val(res[0].rule_type);
                $('#business_outlet_rule_validity_selected').val(res[0].rule_validity);
                $('#ExistingRule').html("").html(temp_str);
              } 
              else if (data.success == 'false') {
                $('#ExistingLoyaltyRule').remove();
                $('#LoyaltyRule').attr('hidden', false);
              }

        } 
        else {
          $('#ExistingLoyaltyRule').remove();
          $('#LoyaltyRule').attr('hidden', false);
        }

      })
      .fail(function(jqXHR, textStatus, errorThrown) {
        console.log(errorThrown.toString());
      });
  });
</script>
<!-- Delete  and Add  -->
<script>
  $(document).on('click', '.rule_delete_btn', function(event) {
    event.preventDefault();
    $(this).blur(); // Manually remove focus from clicked link.

    var parameters = {
      rule_id: $(this).attr('rule_id'),

      business_outlet_id: $(this).attr('business_outlet_id')
    };
    $.ajax({
      url: "<?= base_url() ?>SuperAdmin/DeleteOuletRule",
      data: parameters,
      type: "POST",
      // crossDomain: true,
      cache: false,
      // dataType: "json",
      success: function(data) {
        if (data.success == 'true') {
          $("#ModalEditOutlet").modal('hide');
          $('#defaultModalSuccess').modal('show').on('shown.bs.modal', function(e) {
            $("#SuccessModalMessage").html("").html(data.message);
          }).on('hidden.bs.modal', function(e) {
            window.location.reload();
          });
        } else if (data.success == 'false') {
          if ($('.feedback').hasClass('alert-success')) {
            $('.feedback').removeClass('alert-success').addClass('alert-danger');
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
  });
  // Add rule
  $(document).on('click', '.rule_add_btn', function(event) {
    $(this).blur();
    $('#ModalEditOutlet').modal('hide');
    $('#ModalAddRule').modal('show');
    // alert($('.rule_add_btn').attr('rule_defined')); 
    $('#AddMoreRule input[name=loyalty_business_outlet_id]').val($('.rule_add_btn').attr('business_outlet_id'));        
    $('#AddRuleExistingOffer').val($('.rule_add_btn').attr('rule_defined'));
    $('#AddRuleExistingOfferValid').val($('.rule_add_btn').attr('rule_valid'))
    $('#AddMoreRule').validate({
      errorElement: "div",
      rules: {
        
      },
      submitHandler: function(form) {
        var formData = $("#AddMoreRule").serialize();
        $.ajax({
          url: "<?= base_url() ?>SuperAdmin/AddLoyaltyRule",
          data: formData,
          type: "POST",
          // crossDomain: true,
          cache: false,
          // dataType: "json",
          success: function(data) {
            if (data.success == 'true') {
              $("#ModalEditOutlet").modal('hide');
              $('#defaultModalSuccess').modal('show').on('shown.bs.modal', function(e) {
                $("#SuccessModalMessage").html("").html(data.message);
              }).on('hidden.bs.modal', function(e) {
                window.location.reload();
              });
            } else if (data.success == 'false') {
              if ($('.feedback').hasClass('alert-success')) {
                $('.feedback').removeClass('alert-success').addClass('alert-danger');
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
  });
</script>
<!-- End of delete and Add -->
<!-- Edit Outlet Offer Rule -->
<script>
  $(document).on('click', '.edit_outlet_rule', function(event) {
    event.preventDefault();
    $(this).blur();
    $('#ModalEditOutlet').hide();
    $('#EditLoyaltyRule input[name=business_outlet_id]').val($('#EditOutlet input[name=business_outlet_id]').val())
    $('#ModalEditRule').show();

    // $('#EditRule').append($('#LoyaltyRule').html());
  });
</script>
<!-- End of Edit Outlet Rule -->
<!-- Edit Form dynamic script -->
<script>
  function EdittoggleRule() {

    var selectedElement = $('#edit_business_outlet_rule option:selected').val();

    if (selectedElement == "Offers Single Rule") {
      // document.getElementById('rules')[0].reset();
      document.getElementById('edit_rules').innerHTML = '';
      var target = document.getElementById('edit_rules');
      var p = document.createElement('div');
      p.innerHTML = '<table id="SingleRuleTable" class="table table-hover"><label>Define Offers Single Rule</label><tbody><th>Amount(Rs)</th><th></th><th>Points(%)</th><tr><td><div class="form-group"><input type="number" placeholder="Enter Amount" name="amount1[]" class="form-control" required></div></td><td><label>=</label></td><td><div class="form-group"><input type="number" placeholder="Enter Points" name="points[]" class="form-control" required></div></td></tr></tbody></table>';
      target.appendChild(p);
    }

    if (selectedElement == "Offers Multiple Rule") {
      document.getElementById('edit_rules').innerHTML = '';
      var target = document.getElementById('edit_rules');
      var p = document.createElement('div');
      p.innerHTML = '<table id="MultipleRuleTable" class="table table-hover"><label>Define Offers Multiple Rule</label><tbody><th>Amount(Rs)</th><th></th><th>Amount(Rs)</th><th></th><th>Points(%)</th><tr><td><div class="form-group"><input type="number" placeholder="Enter Amount" name="amount1[]" class="form-control" id="om_amount1"  required></div></td><td>to</td><td><div class="form-group"><input type="number" placeholder="Enter Amount" name="amount2[]" id="om_amount2" onkeyup="amountValidate()" class="form-control" required></div></td><td><label>=</label></td><td><div class="form-group"><input type="number" placeholder="Enter Points" name="points[]" class="form-control" required></div></td></tr></tbody></table><button type="button" class="btn btn-success" id="AddMultipleRule" onclick="AddOfferMultipleRule()">Add <i class="fa fa-plus" aria-hidden="true"></i></button>&ensp;<button type="button" class="btn btn-danger" onclick="DeleteOfferMultipleRule()" id="DeleteMultipleRule">Delete <i class="fa fa-trash" aria-hidden="true"></i></button>';
      target.append(p);
    }

    if (selectedElement == "Cashback Single Rule") {
      document.getElementById('edit_rules').innerHTML = '';
      var target = document.getElementById('edit_rules');
      var p = document.createElement('div');
      p.innerHTML = '<table id="CashbackSingleRuleTable" class="table table-hover"><label>Define Cashback Single Rule</label><tbody><th>Amount(Rs)</th><th></th><th>Points(Cashback value in Rs)</th><tr><td><div class="form-group"><input type="number" placeholder="Enter Amount" name="amount1[]" class="form-control" required></div></td><td><label>=</label></td><td><div class="form-group"><input type="number" placeholder="Enter Cashback Amount" name="cashback[]" class="form-control" required></div></td></tr></tbody></table>';
      target.appendChild(p);
    }

    if (selectedElement == "Cashback Multiple Rule") {
      document.getElementById('edit_rules').innerHTML = '';
      var target = document.getElementById('edit_rules');
      var p = document.createElement('div');
      p.innerHTML = '<table id="CashbackMultipleRuleTable" class="table table-hover"><label>Define Cashback Multiple Rule</label><tbody><th>Amount(Rs)</th><th></th><th>Amount(Rs)</th><th></th><th>Points(Cashback value in Rs)</th><tr><td><div class="form-group"><input type="number" placeholder="Enter Amount" name="amount1[]" class="form-control" required></div></td><td>to</td><td><div class="form-group"><input type="number" placeholder="Enter Amount" name="amount2[]" class="form-control" required></div></td><td><label>=</label></td><td><div class="form-group"><input type="number" placeholder="Enter Points" name="cashback[]" class="form-control" required></div></td></tr></tbody></table><button type="button" class="btn btn-success" onclick="AddCashbackMultipleRule()" id="EditAddCashbackMultipleRule">Add <i class="fa fa-plus" aria-hidden="true"></i></button>&ensp;<button type="button" class="btn btn-danger" onclick="DeleteCashbackMultipleRule()" id="EditDeleteCashbackMultipleRule">Delete <i class="fa fa-trash" aria-hidden="true"></i></button>';
      target.appendChild(p);
    }

    if (selectedElement == "Offers LTV Rule") {
      document.getElementById('edit_rules').innerHTML = '';
      var target = document.getElementById('edit_rules');
      var p = document.createElement('div');
      p.innerHTML = ' <table id="OffersLTVRuleTable" class="table table-hover"><label>Define Offers LTV Rule</label><tbody><th>Amount(Rs)</th><th></th><th>Amount(Rs)</th><th></th><th>Points(%)</th><tr><td><div class="form-group"><input type="number" placeholder="Enter Amount" name="amount1[]" class="form-control" required></div></td><td>to</td><td><div class="form-group"><input type="number" placeholder="Enter Amount" name="amount2[]" class="form-control" required></div></td><td><label>=</label></td><td><div class="form-group"><input type="number" placeholder="Enter Points" name="points[]" class="form-control" required></div></td><td></tr></tbody></table><button type="button" class="btn btn-success" name="AddLTVOfferRule()" id="EditAddLTVOfferRule" onclick="AddLTVOfferRule()">Add <i class="fa fa-plus" aria-hidden="true"></i></button>&ensp;<button type="button" class="btn btn-danger" onclick="DeleteLTVOfferRule()" id="EditDeleteLTVOfferRule">Delete <i class="fa fa-trash" aria-hidden="true"></i></button>'
      target.appendChild(p);
    }

    if (selectedElement == "Cashback LTV Rule") {
      document.getElementById('edit_rules').innerHTML = '';
      var target = document.getElementById('edit_rules');
      var p = document.createElement('div');
      p.innerHTML = '<table id="CashbackLTVRuleTable" class="table table-hover"><label>Define Cashback LTV Rule</label><tbody><th>Amount(Rs)</th><th></th><th>Amount(Rs)</th><th></th><th>Points(Cashback Value in Rs)</th><tr><td><div class="form-group"><input type="number" placeholder="Enter Amount" name="amount1[]" class="form-control" required></div></td><td>to</td><td><div class="form-group"><input type="number" placeholder="Enter Amount" name="amount2[]" class="form-control" required></div></td><td><label>=</label></td><td><div class="form-group"><input type="number" placeholder="Enter Points" name="cashback[]" class="form-control" required></div></td></tr></tbody></table><button type="button" class="btn btn-success" onclick="AddCashbackLTVRule()" id="EditAddCashbackLTVRule">Add <i class="fa fa-plus" aria-hidden="true"></i></button>&ensp;<button type="button" class="btn btn-danger" onclick="DeleteCashbackLTVRule()" id="EditDeleteCashbackLTVRule">Delete <i class="fa fa-trash" aria-hidden="true"></i></button>'
      target.appendChild(p);
    }

    if (selectedElement == "Cashback Visits") {
      document.getElementById('edit_rules').innerHTML = '';
    }

  }
  $('#EditLoyaltyRule').validate({
    errorElement: "div",
    rules: {
      "edit_business_outlet_rule": {
        required: true,
      },
      "edit_business_outlet_rule_validity": {
        required: true
      }
    },
    submitHandler: function(form) {
      var formData = $("#EditLoyaltyRule").serialize();
      $.ajax({
        url: "<?= base_url() ?>SuperAdmin/UpdateLoyaltyRule",
        data: formData,
        type: "POST",
        // crossDomain: true,
        cache: false,
        // dataType: "json",
        success: function(data) {
          if (data.success == 'true') {
            $("#ModalEditRule").modal('hide');
              $('#defaultModalSuccess').modal('show').on('shown.bs.modal', function(e) {
                $("#SuccessModalMessage").html("").html(data.message);
              }).on('hidden.bs.modal', function(e) {
                window.location.reload();
              });
          } else if (data.success == 'false') {
            if ($('.feedback').hasClass('alert-success')) {
              $('.feedback').removeClass('alert-success').addClass('alert-danger');
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
</script>
<!-- End of edit form dynamic script -->
