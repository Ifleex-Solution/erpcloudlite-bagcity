<div class="row">
    <div class="col-sm-12">
        <?php echo form_open('', 'class="" id="customer_form2"') ?>
        <input type="hidden" name="customer_id" id="customer_id" value="<?php echo $customer->customer_id ?>">

        <!-- ===================== Section 1: Basic Information ===================== -->
        <div class="panel panel-bd lobidrag">
            <div class="panel-heading">
                <div class="panel-title">
                    <h4><?php echo $title ?></h4>
                </div>
            </div>
            <div class="panel-body">

                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group row">
                            <label for="customer_name" class="col-sm-4 col-form-label">
                                <?php echo display('customer_name') ?> <i class="text-danger">*</i>
                            </label>
                            <div class="col-sm-8">
                                <input type="text" name="customer_name" class="form-control" id="customer_name"
                                    placeholder="<?php echo display('customer_name') ?>"
                                    value="<?php echo $customer->customer_name ?>">
                                <input type="hidden" name="old_name" value="<?php echo $customer->customer_name ?>">
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group row">
                            <label for="customer_calling_name" class="col-sm-4 col-form-label">
                                Customer Calling Name
                            </label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="customer_calling_name"
                                    id="customer_calling_name" placeholder="Customer Calling Name"
                                    value="<?php echo $customer->customer_calling_name ?>">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group row">
                            <label for="customer_billing_name" class="col-sm-4 col-form-label">
                                Customer Printing Name<i class="text-danger">*</i>
                            </label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="customer_billing_name"
                                    id="customer_billing_name" placeholder="Customer Printing Name"
                                    value="<?php echo $customer->customer_billing_name ?>">
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group row">
                            <label for="status" class="col-sm-4 col-form-label">
                                Status <i class="text-danger">*</i>
                            </label>
                            <div class="col-sm-8">
                                <select class="form-control" id="status" name="status" tabindex="-1" aria-hidden="true" required>
                                    <option value="">Select One</option>
                                    <option value="1" <?php echo ($customer->status_label == "Active") ? 'selected' : ''; ?>>Active</option>
                                    <option value="0" <?php echo ($customer->status_label == "Inactive") ? 'selected' : ''; ?>>Inactive</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- ===================== Section 2: Contact Details ===================== -->
        <div class="panel panel-bd lobidrag">
            <div class="panel-heading">
                <div class="panel-title">
                    <h4>Contact Details</h4>
                </div>
            </div>
            <div class="panel-body">

                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group row">
                            <label for="customer_mobile" class="col-sm-4 col-form-label">Mobile No</label>
                            <div class="col-sm-8">
                                <input type="text" name="customer_mobile"
                                    class="form-control input-mask-trigger text-left" id="customer_mobile"
                                    placeholder="Mobile No"
                                    value="<?php echo $customer->customer_mobile ?>"
                                    data-inputmask="'alias': 'decimal', 'groupSeparator': '', 'autoGroup': true"
                                    im-insert="true">
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group row">
                            <label for="phone" class="col-sm-4 col-form-label">TP Number</label>
                            <div class="col-sm-8">
                                <input class="form-control input-mask-trigger text-left" id="phone" type="text"
                                    name="phone" placeholder="TP Number"
                                    data-inputmask="'alias': 'decimal', 'groupSeparator': '', 'autoGroup': true"
                                    im-insert="true" value="<?php echo $customer->phone ?>">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group row">
                            <label for="email" class="col-sm-4 col-form-label">
                                <?php echo display('email_address') ?>
                            </label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control input-mask-trigger" name="customer_email"
                                    id="email" data-inputmask="'alias': 'email'" im-insert="true"
                                    placeholder="<?php echo display('email') ?>"
                                    value="<?php echo $customer->customer_email ?>">
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group row">
                            <label for="nic_no" class="col-sm-4 col-form-label">NIC No</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="nic_no" id="nic_no"
                                    placeholder="NIC No" value="<?php echo $customer->nic_no ?>">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group row">
                            <label for="country" class="col-sm-4 col-form-label">
                                <?php echo display('country') ?>
                            </label>
                            <div class="col-sm-8">
                                <input name="country" type="text" class="form-control"
                                    placeholder="<?php echo display('country') ?>"
                                    value="<?php echo $customer->country ?>" id="country">
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group row">
                            <label for="state" class="col-sm-4 col-form-label">State / Province</label>
                            <div class="col-sm-8">
                                <input type="text" name="state" class="form-control" id="state"
                                    placeholder="State / Province" value="<?php echo $customer->state ?>">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group row">
                            <label for="city" class="col-sm-4 col-form-label">
                                <?php echo display('city') ?>
                            </label>
                            <div class="col-sm-8">
                                <input type="text" name="city" class="form-control" id="city"
                                    placeholder="<?php echo display('city') ?>" value="<?php echo $customer->city ?>">
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group row">
                            <label for="zip" class="col-sm-4 col-form-label">
                                <?php echo display('zip') ?>
                            </label>
                            <div class="col-sm-8">
                                <input name="zip" type="text" class="form-control" id="zip"
                                    placeholder="<?php echo display('zip') ?>" value="<?php echo $customer->zip ?>">
                            </div>
                        </div>
                    </div>
                </div>



            </div>
        </div>

        <!-- ===================== Section 3: Address & Registration ===================== -->
        <div class="panel panel-bd lobidrag">
            <div class="panel-heading">
                <div class="panel-title">
                    <h4>Address &amp; Registration</h4>
                </div>
            </div>
            <div class="panel-body">

                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group row">
                            <label for="customer_address" class="col-sm-4 col-form-label">Primary Address</label>
                            <div class="col-sm-8">
                                <textarea name="customer_address" id="customer_address" class="form-control" rows="3"
                                    placeholder="Primary Address"><?php echo $customer->customer_address ?></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group row">
                            <label for="address2" class="col-sm-4 col-form-label">Secondary Address</label>
                            <div class="col-sm-8">
                                <textarea name="address2" id="address2" class="form-control" rows="3"
                                    placeholder="Secondary Address"><?php echo $customer->address2 ?></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group row">
                            <label for="contact" class="col-sm-4 col-form-label">BR No</label>
                            <div class="col-sm-8">
                                <input class="form-control" id="contact" type="text" name="contact"
                                    placeholder="BR No" value="<?php echo $customer->contact ?>">
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group row">
                            <label for="email_address" class="col-sm-4 col-form-label">VAT No</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="email_address" id="email_address"
                                    placeholder="VAT No" value="<?php echo $customer->email_address ?>">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12 text-right">
                        <button type="button" onclick="customer_form2()" class="btn btn-success">
                            <?php echo (empty($customer->customer_id) ? display('save') : display('update')) ?>
                        </button>
                    </div>
                </div>

            </div>
        </div>

        <?php echo form_close(); ?>
    </div>
</div>

 <script>
     function customer_form2() {

         var form = $("#customer_form2");
         var custome_id = $("#customer_id").val();
         var customer_name = $("#customer_name").val();
         var status = $("#status").val();
         var customer_billing_name = $("#customer_billing_name").val();


         var base_url = $("#base_url").val();
         if (custome_id !== '') {
             var form_url = base_url + 'edit_customer/' + custome_id;
         } else {
             var form_url = base_url + 'add_customer';
         }
         if (customer_name == '') {
             $("#customer_name").focus();
             alert("Customer name must be required")
             setTimeout(function() {}, 500);
             return false;
         }
         if (status == '') {
             $("#status").focus();
             alert("Status must be required")
             setTimeout(function() {}, 500);
             return false;
         }

         if (customer_billing_name == '') {
             $("#customer_billing_name").focus();
             alert("Customer Printing Name must be required")
             setTimeout(function() {}, 500);
             return false;
         }
         $.ajax({
             url: form_url,
             method: 'POST',
             dataType: 'json',
             data: form.serialize(),
             success: function(r) {
                 if (r.status == 1) {
                     if (custome_id == '') {
                         $('#customer_form2').trigger("reset");
                     } else {
                         setTimeout(function() {}, 1000);
                         location.reload();
                     }
                     alert(r.msg)
                     location.reload();

                     
                 } else {
                    alert(r.msg)
                }
             },
             error: function(xhr) {
                 alert('failed!');
             }
         });
     }
 </script>