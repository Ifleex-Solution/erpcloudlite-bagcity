<input type="hidden" name="baseUrl2" id="baseUrl2" class="baseUrl" value="<?php echo base_url(); ?>" />
<style>
input[readonly],
select[disabled] {
    background-color: #f5f5f5 !important;
    cursor: not-allowed;
    opacity: 0.8;
}

/* Additional protection for readonly datepicker fields */
input.datepicker[readonly] {
    pointer-events: none;
}
</style>

<div class="row">
    <div class="col-sm-12">
        <div class="panel panel-bd lobidrag">
            <div class="panel-heading">
                <div class="panel-title">
                    <h4><?php echo $title; ?></h4>
                </div>
            </div>
            <?php echo form_open_multipart('stockbatch_form/' . $stockbatch->id, array('class' => 'form-vertical', 'id' => 'insert_stockbatch', 'name' => 'insert_stockbatch', 'onsubmit' => 'return validateForm(event)')) ?>
            <div class="panel-body">
                <!-- Batch ID Field -->
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group row">
                            <label for="batchid" class="col-sm-4 col-form-label">Batch Id<i
                                    class="text-danger">*</i></label>
                            <div class="col-sm-8">
                                <?php if (!empty($stockbatch->id)) { ?>
                                <input class="form-control" name="batchid" type="text" id="batchid"
                                    placeholder="Batch Id" tabindex="1" autocomplete='off'
                                    value="<?php echo $stockbatch->batchid ?>" required>
                                <?php } else { ?>
                                <input class="form-control" name="batchid" type="text" id="batchid"
                                    placeholder="Batch Id" autocomplete='off' tabindex="1"
                                    value="<?php echo $stockbatch->batchid ?>" required>
                                <?php } ?>
                                <input type="hidden" name="button" id="button" />
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Details Field -->
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group row">
                            <label for="name" class="col-sm-4 col-form-label">Details</label>
                            <div class="col-sm-8">
                                <input type="text" tabindex="" class="form-control" id="details" name="details"
                                    placeholder="Details" autocomplete='off'
                                    value="<?php echo $stockbatch->details ?>" />
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Batch Usage Type Field -->
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group row">
                            <label for="status" class="col-sm-4 col-form-label">Batch Usage Type<i
                                    class="text-danger">*</i></label>
                            <div class="col-sm-8">
                                <?php if (!empty($stockbatch->id) && isset($has_transactions) && $has_transactions) { ?>
                                <!-- Frozen if transactions exist -->
                                <select class="form-control" id="busage_display" tabindex="-1" aria-hidden="true"
                                    disabled>
                                    <option value="">Select One</option>
                                    <option value="single"
                                        <?php echo ($stockbatch->busage == "single") ? 'selected' : ''; ?>>Single Usage
                                    </option>
                                    <option value="multiple"
                                        <?php echo ($stockbatch->busage == "multiple") ? 'selected' : ''; ?>>Multiple
                                        Usage</option>
                                </select>
                                <input type="hidden" name="busage" id="busage"
                                    value="<?php echo $stockbatch->busage; ?>" />
                                <small class="text-warning"><i class="fa fa-info-circle"></i> Batch Usage Type is locked
                                    because transactions have been made with this batch.</small>
                                <?php } else { ?>
                                <!-- Editable if no transactions or new record -->
                                <select class="form-control" id="busage" name="busage" tabindex="-1" aria-hidden="true"
                                    required onchange="changeBatchtype()">
                                    <option value="">Select One</option>
                                    <option value="single"
                                        <?php echo ($stockbatch->busage == "single") ? 'selected' : ''; ?>>Single Usage
                                    </option>
                                    <option value="multiple"
                                        <?php echo ($stockbatch->busage == "multiple") ? 'selected' : ''; ?>>Multiple
                                        Usage</option>
                                </select>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Product Field (Single Usage Only) -->
                <div class="row" id="singleshow" style="display:none;">
                    <div class="col-sm-6">
                        <div class="form-group row">
                            <label for="status" class="col-sm-4 col-form-label">Product<i
                                    class="text-danger">*</i></label>
                            <div class="col-sm-8">
                                <?php if (!empty($stockbatch->id) && isset($has_transactions) && $has_transactions && $stockbatch->busage == "single") { ?>
                                <!-- Frozen if transactions exist and batch usage is single -->
                                <select class="form-control" id="product_display" tabindex="-1" disabled>
                                    <option value=""></option>
                                    <?php if ($products) { ?>
                                    <?php foreach ($products as $categories) { ?>
                                    <option value="<?php echo $categories['id'] ?>"
                                        <?php if ($stockbatch->product == $categories['id']) { echo 'selected'; } ?>>
                                        <?php echo $categories['product_name'] ?></option>
                                    <?php } } ?>
                                </select>
                                <input type="hidden" name="product" value="<?php echo $stockbatch->product; ?>" />
                                <small class="text-warning"><i class="fa fa-info-circle"></i> Product is locked because
                                    transactions have been made with this batch.</small>
                                <?php } else { ?>
                                <!-- Editable if no transactions or new record -->
                                <select class="form-control" id="product" name="product" tabindex="3">
                                    <option value=""></option>
                                    <?php if ($products) { ?>
                                    <?php foreach ($products as $categories) { ?>
                                    <option value="<?php echo $categories['id'] ?>"
                                        <?php if ($stockbatch->product == $categories['id']) { echo 'selected'; } ?>>
                                        <?php echo $categories['product_name'] ?></option>
                                    <?php } } ?>
                                </select>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Manufacturing Date Field (Single Usage Only) -->
                <div class="row" id="singleshow1" style="display:none;">
                    <div class="col-sm-6">
                        <div class="form-group row">
                            <label for="name" class="col-sm-4 col-form-label">Manufacturing Date</label>
                            <div class="col-sm-8">
                                <input class="datepicker form-control" type="text" id="mdate" name="mdate"
                                    value="<?php echo !empty($stockbatch->mdate) ? $stockbatch->mdate : ''; ?>"
                                    placeholder="YYYY-MM-DD" autocomplete='off'>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Expiry Date Field (Single Usage Only) -->
                <div class="row" id="singleshow2" style="display:none;">
                    <div class="col-sm-6">
                        <div class="form-group row">
                            <label for="edate_toggle" class="col-sm-4 col-form-label">Expiry Date </label>
                            <div class="col-sm-8">
                                <select class="form-control" id="edate_toggle" name="edate_toggle" onchange="toggleEdateField()">
                                    <option value="no" <?php echo (isset($stockbatch->edate_enabled) && $stockbatch->edate_enabled == 1) ? '' : 'selected'; ?>>Disable</option>
                                    <option value="yes" <?php echo (isset($stockbatch->edate_enabled) && $stockbatch->edate_enabled == 1) ? 'selected' : ''; ?>>Enable</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row" id="edate_row" style="display:<?php echo (isset($stockbatch->edate_enabled) && $stockbatch->edate_enabled == 1) ? 'flex' : 'none'; ?>;">
                    <div class="col-sm-6">
                        <div class="form-group row">
                            <label for="edate" class="col-sm-4 col-form-label">Select Expiry Date</label>
                            <div class="col-sm-8">
                                <input class="datepicker form-control" type="text" id="edate" name="edate"
                                    value="<?php echo !empty($stockbatch->edate) ? $stockbatch->edate : ''; ?>"
                                    placeholder="YYYY-MM-DD" autocomplete='off'>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Packing Date Field (Single Usage Only) -->
                <div class="row" id="singleshow3" style="display:none;">
                    <div class="col-sm-6">
                        <div class="form-group row">
                            <label for="name" class="col-sm-4 col-form-label">Packing Date</label>
                            <div class="col-sm-8">
                                <input class="datepicker form-control" type="text" id="pdate" name="pdate"
                                    value="<?php echo !empty($stockbatch->pdate) ? $stockbatch->pdate : ''; ?>"
                                    placeholder="YYYY-MM-DD" autocomplete='off'>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- MRP Field (Single Usage Only) -->
                <div class="row" id="singleshow4" style="display:none;">
                    <div class="col-sm-6">
                        <div class="form-group row">
                            <label for="name" class="col-sm-4 col-form-label">MRP</label>
                            <div class="col-sm-8">
                                <input class="form-control" type="number" id="mrp" name="mrp"
                                    value="<?php echo $stockbatch->mrp ?>" placeholder="0.00" min="0"
                                    step="0.01" autocomplete='off'>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Hidden fields for Multiple Usage Type -->
                <div id="multipleshow" style="display:none;">
                    <input class="form-control" type="hidden" id="product1" name="product1" autocomplete='off'
                        value="0">
                    <input class="form-control" type="hidden" id="mdate1" name="mdate1" autocomplete='off'
                        value="<?php echo !empty($stockbatch->mdate) ? $stockbatch->mdate : ''; ?>">
                    <input class="form-control" type="hidden" id="pdate1" name="pdate1" autocomplete='off'
                        value="<?php echo !empty($stockbatch->pdate) ? $stockbatch->pdate : ''; ?>">
                    <input class="form-control" type="hidden" id="edate1" name="edate1" autocomplete='off'
                        value="<?php echo !empty($stockbatch->edate) ? $stockbatch->edate : ''; ?>">
                    <input class="form-control" type="hidden" id="mrp1" name="mrp1" autocomplete='off' value="0">
                </div>

                <!-- Status Field -->
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group row">
                            <label for="status" class="col-sm-4 col-form-label">Status<i
                                    class="text-danger">*</i></label>
                            <div class="col-sm-8">
                                <select class="form-control" id="status" name="status" tabindex="-1" aria-hidden="true"
                                    required>
                                    <option value="">Select One</option>
                                    <option value="1"
                                        <?php echo ($stockbatch->status_label == "Active") ? 'selected' : ''; ?>>Active
                                    </option>
                                    <option value="0"
                                        <?php echo ($stockbatch->status_label == "Inactive") ? 'selected' : ''; ?>>
                                        Inactive</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="row">
                    <div class="form-group row">
                        <div class="col-sm-6 text-right">
                            <button type="submit" class="btn btn-success" name="save">
                                <?php echo (empty($stockbatch->id) ? display('save') : display('update')) ?></button>

                            <?php if (empty($stockbatch->id)) { ?>
                            <button type="submit" class="btn btn-success" name="add-another">
                                <?php echo display('save_and_add_another'); ?>
                            </button>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php echo form_close() ?>
        </div>
    </div>
</div>

<?php
echo "<script>";
echo "var id = " . json_encode($stockbatch->id) . ";";
echo "var busage = " . json_encode($stockbatch->busage) . ";";
echo "var has_transactions = " . json_encode(isset($has_transactions) ? $has_transactions : false) . ";";
echo "</script>";
?>

<script>
let code = 0;

$(document).ready(function() {
    $('body').addClass("sidebar-mini sidebar-collapse");

    // Initialize display based on busage value
    if (busage === "single") {
        $("#singleshow").show();
        $("#singleshow1").show();
        $("#singleshow2").show();
        $("#singleshow3").show();
        $("#singleshow4").show();
        $("#multipleshow").hide();
    } else if (busage === "multiple") {
        $("#singleshow").hide();
        $("#singleshow1").hide();
        $("#singleshow2").hide();
        $("#singleshow3").hide();
        $("#singleshow4").hide();
        $("#multipleshow").show();
    } else {
        // For new records, hide all until selection
        $("#singleshow").hide();
        $("#singleshow1").hide();
        $("#singleshow2").hide();
        $("#singleshow3").hide();
        $("#singleshow4").hide();
        $("#multipleshow").hide();
    }
});

if (id != null) {
    code = document.getElementById("batchid").value.toString();
    console.log(code)
}

function changeBatchtype() {
    // Only prevent changes if transactions exist
    if (id != null && has_transactions) {
        alert('Cannot change Batch Usage Type as transactions have been made with this batch.');
        return false;
    }

    let busageValue = document.getElementById('busage') ? document.getElementById('busage').value : '';

    if (busageValue == "single") {
        $("#singleshow").show();
        $("#singleshow1").show();
        $("#singleshow2").show();
        $("#singleshow3").show();
        $("#singleshow4").show();
        $("#multipleshow").hide();
    } else if (busageValue == "multiple") {
        $("#singleshow").hide();
        $("#singleshow1").hide();
        $("#singleshow2").hide();
        $("#singleshow3").hide();
        $("#singleshow4").hide();
        $("#multipleshow").show();
    }
}

function toggleEdateField() {
    const toggle = document.getElementById('edate_toggle');
    const edateRow = document.getElementById('edate_row');
    const edateInput = document.getElementById('edate');
    if (toggle.value === 'yes') {
        edateRow.style.display = 'flex';
    } else {
        edateRow.style.display = 'none';
        edateInput.value = '';
    }
}

function validateForm(event) {
    event.preventDefault();

    const buttonName = event.submitter.name;

    async function checkstockbatch() {
        // Skip batch ID check if editing and batch ID hasn't changed
        if (id != null && code == document.getElementById('batchid').value.toString()) {
            return true;
        }

        try {
            let response = await $.ajax({
                type: "POST",
                url: $('#baseUrl2').val() + 'stock/stock/getStockBatchById',
                data: {
                    batchid: document.getElementById('batchid').value.toString(),
                }
            });

            let data = JSON.parse(response);
            console.log(data)
            if (data === "success") {
                return true;
            } else {
                if (code == document.getElementById('batchid').value.toString()) {
                    return true;
                } else {
                    alert("Batch Id already exists");
                    return false;
                }
            }

        } catch (error) {
            alert("An error occurred: " + error);
            return false;
        }
    }

    checkstockbatch().then((isValid) => {
        if (isValid) {
            if (buttonName === 'save') {
                document.getElementById('button').value = "save";
                document.getElementById('insert_stockbatch').submit();
            } else if (buttonName === 'add-another') {
                document.getElementById('button').value = "add-another";
                document.getElementById('insert_stockbatch').submit();
            }
        } else {
            return false;
        }
    });
}
</script>