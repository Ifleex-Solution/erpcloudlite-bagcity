<!-- New Service -->
<div class="row">
    <div class="col-sm-12">
        <div class="panel panel-bd lobidrag">
            <div class="panel-heading">
                <div class="panel-title">
                    <h4><?php echo display('add_service') ?> </h4>
                </div>
            </div>
            <?php echo form_open('service/service/insert_service', array('class' => 'form-vertical', 'id' => 'insert_service', 'name' => 'insert_service', 'onsubmit' => 'return validateForm(event)')) ?>
            <div class="panel-body">


                <div class="form-group row">
                    <label for="service_code" class="col-sm-2 col-form-label">Service ID <i class="text-danger">*</i></label>
                    <div class="col-sm-4">
                        <input class="form-control" name="service_code" id="service_code" type="text" required
                            placeholder="Service ID" value="<?php echo isset($service_code)?html_escape($service_code):''; ?>">
                    </div>
                </div>
                

                <div class="form-group row">
                    <label for="service_name" class="col-sm-2 col-form-label"><?php echo display('service_name') ?> <i
                            class="text-danger">*</i></label>
                    <div class="col-sm-4">
                        <input class="form-control" name="service_name" id="service_name" type="text"
                            placeholder="<?php echo display('service_name') ?>" >
                    </div>
                    <input type="hidden" name="button" id="button" />

                </div>

                <div class="form-group row">
                    <label for="charge" class="col-sm-2 col-form-label"><?php echo display('charge') ?> <i
                            class="text-danger">*</i></label>
                    <div class="col-sm-4">
                        <input class="form-control" name="charge" id="charge" type="text"
                            placeholder="<?php echo display('charge') ?>" >
                    </div>
                </div>
                <div class="form-group row">
                    <label for="service_vat" class="col-sm-2 col-form-label"><?php echo display('service_vat') . ' %' ?> </label>
                    <div class="col-sm-4">
                        <input class="form-control" name="service_vat" id="service_vat" type="text"
                            placeholder="<?php echo display('service_vat') . ' %' ?>">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="description" class="col-sm-2 col-form-label"><?php echo display('description') ?> <i
                            class="text-danger"></i></label>
                    <div class="col-sm-4">
                        <textarea class="form-control" name="description" id="description"
                            placeholder="<?php echo display('description') ?>"></textarea>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="status" class="col-sm-2 col-form-label">Status<i class="text-danger">*</i></label>

                    <div class="col-sm-4">

                        <select class="form-control" id="status" name="status" tabindex="-1" aria-hidden="true" >
                            <option value="">Select One</option>
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select> 


                    </div>
                </div>


                <?php if ($vattaxinfo->dynamic_tax == 1) {
                    $i = 0;
                    foreach ($taxfield as $taxss) { ?>

                        <div class="form-group row">
                            <label for="tax" class="col-sm-3 col-form-label"><?php echo $taxss['tax_name']; ?> <i class="text-danger"></i></label>
                            <div class="col-sm-6">
                                <input type="text" name="tax<?php echo $i; ?>" class="form-control" value="<?php echo number_format($taxss['default_value'], 2, '.', ','); ?>">
                            </div>
                            <div class="col-sm-1"> <i class="text-success">%</i></div>
                        </div>
                <?php $i++;
                    }
                }

                ?>


                <div class="form-group row">

                    <div class="col-sm-6 text-right">


                        <button type="submit" class="btn btn-success " name="save">
                            <?php echo display('save')  ?></button>

                        <?php if (empty($branch->id)) { ?>
                            <button type="submit" class="btn btn-success" name="add-another">
                                <?php echo display('save_and_add_another'); ?>
                            </button>
                        <?php } ?>

                    </div>
                </div>
            </div>
            <?php echo form_close() ?>
        </div>
    </div>

</div>
<script>
    let code = 0;

    if (id != null) {
        code = document.getElementById("code").value.toString();
    }

    function validateForm(event) {
        // Prevent default form submission
        event.preventDefault();

        // Identify which button was clicked
        const buttonName = event.submitter.name; // Get the name of the button that was clicked

        if(document.getElementById('charge').value=='' ){
            alert("Charge is required");
            return
        }

        if(document.getElementById('service_name').value=='' ){
            alert("Service Name is required");
            return
        }

        if(document.getElementById('status').value=='' ){
            alert("Status is required");
            return
        }

        if (buttonName === 'save') {
            document.getElementById('button').value = "save";
            document.getElementById('insert_service').submit();
        } else if (buttonName === 'add-another') {
            document.getElementById('button').value = "add-another";
            document.getElementById('insert_service').submit();
        }
    }
</script>