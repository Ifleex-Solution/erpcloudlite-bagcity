<div class="row">
    <div class="col-sm-12">
        <?php echo form_open('', 'class="" id="supplier_form2"') ?>
        <input type="hidden" name="supplier_id" id="supplier_id" value="<?php echo $supplier->supplier_id ?>">

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
                            <label for="supplier_name" class="col-sm-4 col-form-label">
                                <?php echo display('supplier_name') ?> <i class="text-danger">*</i>
                            </label>
                            <div class="col-sm-8">
                                <input type="text" name="supplier_name" class="form-control" id="supplier_name"
                                    placeholder="<?php echo display('supplier_name') ?>"
                                    value="<?php echo $supplier->supplier_name ?>">
                                <input type="hidden" name="old_name" value="<?php echo $supplier->supplier_name ?>">
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group row">
                            <label for="supplier_calling_name" class="col-sm-4 col-form-label">
                                Supplier Calling Name
                            </label>
                            <div class="col-sm-8">
                                <input type="text" name="supplier_calling_name" id="supplier_calling_name"
                                    class="form-control" placeholder="Supplier Calling Name"
                                    value="<?php echo $supplier->supplier_calling_name ?>">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group row">
                            <label for="supplier_billing_name" class="col-sm-4 col-form-label">
                                Supplier Printing Name <i class="text-danger">*</i>
                            </label>
                            <div class="col-sm-8">
                                <input type="text" name="supplier_billing_name" id="supplier_billing_name"
                                    class="form-control" placeholder="Supplier Printing Name"
                                    value="<?php echo $supplier->supplier_billing_name ?>">
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
                                    <option value="1" <?php echo ($supplier->status_label == "Active") ? 'selected' : ''; ?>>Active</option>
                                    <option value="0" <?php echo ($supplier->status_label == "Inactive") ? 'selected' : ''; ?>>Inactive</option>
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
                            <label for="supplier_mobile" class="col-sm-4 col-form-label">Mobile No</label>
                            <div class="col-sm-8">
                                <input type="text" name="supplier_mobile" class="form-control input-mask-trigger text-left"
                                    id="supplier_mobile" placeholder="Mobile No"
                                    value="<?php echo $supplier->mobile ?>"
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
                                    im-insert="true" value="<?php echo $supplier->phone ?>">
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
                                <input type="email" class="form-control input-mask-trigger" name="supplier_email"
                                    id="email" data-inputmask="'alias': 'email'" im-insert="true"
                                    placeholder="<?php echo display('email') ?>"
                                    value="<?php echo $supplier->emailnumber ?>">
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group row">
                            <label for="nic_no" class="col-sm-4 col-form-label">NIC No</label>
                            <div class="col-sm-8">
                                <input type="text" name="nic_no" id="nic_no" class="form-control"
                                    placeholder="NIC No" value="<?php echo $supplier->nic_no ?>">
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
                                    value="<?php echo $supplier->country ?>" id="country">
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group row">
                            <label for="state" class="col-sm-4 col-form-label">State / Province</label>
                            <div class="col-sm-8">
                                <input type="text" name="state" class="form-control" id="state"
                                    placeholder="State / Province" value="<?php echo $supplier->state ?>">
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
                                    placeholder="<?php echo display('city') ?>" value="<?php echo $supplier->city ?>">
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
                                    placeholder="<?php echo display('zip') ?>" value="<?php echo $supplier->zip ?>">
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
                            <label for="supplier_address" class="col-sm-4 col-form-label">Primary Address</label>
                            <div class="col-sm-8">
                                <textarea name="supplier_address" id="supplier_address" class="form-control" rows="3"
                                    placeholder="Primary Address"><?php echo $supplier->address ?></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group row">
                            <label for="address2" class="col-sm-4 col-form-label">Secondary Address</label>
                            <div class="col-sm-8">
                                <textarea name="address2" id="address2" class="form-control" rows="3"
                                    placeholder="Secondary Address"><?php echo $supplier->address2 ?></textarea>
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
                                    placeholder="BR No" value="<?php echo $supplier->contact ?>">
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group row">
                            <label for="email_address" class="col-sm-4 col-form-label">VAT No</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="email_address" id="email_address"
                                    placeholder="VAT No" value="<?php echo $supplier->email_address ?>">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12 text-right">
                        <button type="button" onclick="supplier_form2()" class="btn btn-success">
                            <?php echo (empty($supplier->supplier_id) ? display('save') : display('update')) ?>
                        </button>
                    </div>
                </div>

            </div>
        </div>

        <?php echo form_close(); ?>
    </div>
</div>
 <script>
     function supplier_form2() {
        var form = $("#supplier_form2");
        var supplier_id = $("#supplier_id").val();
        var supplier_name = $("#supplier_name").val();
        var base_url = $("#base_url").val();
        var status = $("#status").val();
        var supplier_billing_name = $("#supplier_billing_name").val();


        if (supplier_id !== '') {
            var form_url = base_url + 'edit_supplier/' + supplier_id;
        } else {
            var form_url = base_url + 'add_supplier';
        }


        if (supplier_name == '') {
            $("#supplier_name").focus();
            alert("supplier name must be required");
            setTimeout(function() {}, 500);
            return false;
        }

        if (status == '') {
            $("#status").focus();
            alert("Status must be required");
            setTimeout(function() {}, 500);
            return false;
        }

        if (supplier_billing_name == '') {
            $("#supplier_billing_name").focus();
            alert("Supplier Printing Name must be required");
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
                    if (supplier_id == '') {
                        $('#supplier_form2').trigger("reset");
                    } else {
                        setTimeout(function() {}, 1000);
                        location.reload();
                    }
                    alert(r.msg);
                    location.reload();
                } else {
                    alert(r.msg);
                }
            },
            error: function(xhr) {
                alert('failed!');
            }
        });
    }
 </script>