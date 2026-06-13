<!-- Sales report -->
<style>
    input[type="password"]::-ms-reveal,
    input[type="password"]::-ms-clear {
        display: none;
    }
</style>
<div class="row">
    <div class="col-sm-12">
        <div class="panel panel-bd lobidrag">
            <div class="panel-heading">
                <div class="panel-title">
                    <h4><?php
                        echo $title;
                        ?></h4>
                </div>
            </div>
            <br />
            <div class="panel-body" style="margin-left: 120px;">


                <?php
                date_default_timezone_set('Asia/Colombo');

                $today = date('Y-m-d');
                ?>
                <div class="form-group">
                    <label for="product">Branch</label>
                    <div class="input-group mr-4" style="width: 250px;">

                        <select class="form-control" id="branch" required name="branch" style="width: 250px;" tabindex="3">


                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="supplier_id">Supplier</label>
                    <div class="input-group mr-4" style="width: 250px;">
                        <select class="form-control" id="supplier_id" name="supplier_id" style="width: 250px;" tabindex="4">
                            <option value="">All Suppliers</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="incident_type">Incident Type</label>
                    <div class="input-group mr-4" style="width: 250px;">
                        <select class="form-control" id="incident_type" name="incident_type" style="width:250px;">
                            <option value="">All Types</option>
                            <option value="1">International Purchase</option>
                            <option value="2">Local Purchase</option>
                            <option value="3">Purchase Return</option>
                        </select>
                    </div>
                </div>

                <div class="form-group" style="margin-bottom: 10px;">
                    <input type="checkbox" id="single_date_checkbox" name="single_date_checkbox">
                    <label for="single_date_checkbox">Single Date</label>
                </div>
                <div class="form-group" style="display: flex; gap: 20px;">
                    <div>
                        <label for="from_date">From Date: </label>
                        <input type="text" name="from_date" class="form-control datepicker" id="from_date"
                            placeholder="<?php echo display('start_date') ?>" value="<?php echo $today ?>" style="width: 200px;">
                    </div>
                    <div id="to_date_container">
                        <div>
                            <label for="to_date">To Date:</label>
                            <input type="text" name="to_date" class="form-control datepicker" id="to_date"
                                placeholder="<?php echo display('end_date') ?>" value="<?php echo $today ?>" style="width: 200px;">
                        </div>
                    </div>
                </div>


                <div class="form-group">

                    <label for="empid" class="mr-2 mb-0">Password</label>

                    <input type="password" tabindex="4" class="form-control" name="password" id="password" value="" style="width: 200px;" autocomplete="off">

                </div>

                <button type="button" id="btn-filter" class="btn btn-success" onclick="onFilterButtonClick()">
                    Generate Report
                </button>
            </div>
        </div>
    </div>
</div>

<input type="hidden" name="baseUrl2" id="baseUrl2" class="baseUrl" value="<?php echo base_url(); ?>" />
<?php
echo "<script>";
echo "let password_enable=" . json_encode($this->session->userdata('password_enable')) . ";";
echo "let usertype=" . json_encode($this->session->userdata('user_level2')) . ";";
echo "</script>";
?>
<script src="<?php echo base_url('my-assets/js/admin_js/sales_report.js') ?>" type="text/javascript"></script>
<script>
    $(document).ready(function() {
        getBranchDropdown(0);
        getSupplierDropdown();
        if (usertype == 3) {
            type2 = "B"

        } else {
            type2 = "A"

        }
    });

    function getSupplierDropdown() {
        var base_url = $('#base_url').val();

        $.ajax({
            type: "get",
            url: base_url + "report/report/getSuppliersForReport",
            success: function(data) {
                var suppliers = JSON.parse(data);
                var $supplierDropdown = $('#supplier_id');

                $.each(suppliers, function(index, supplier) {
                    $supplierDropdown.append('<option value="' + supplier.supplier_id + '">' + supplier.supplier_name + '</option>');
                });
            }
        });
    }

    function onFilterButtonClick() {

        type = type2;
        if (password_enable == "1") {

            if (document.getElementById('password').value == '') {
                alert("Password shouldn't be empty")
                return

            }

            $.ajax({
                url: $('#base_url').val() + 'dashboard/setting/checkpasswordReport',
                type: 'POST',
                data: {
                    password: document.getElementById('password').value,
                },
                success: function(response) {
                    if (JSON.parse(response) == "wrong password") {
                        alert("Wrong Password")
                        return
                    }

                    if (type == "A") {
                        if (JSON.parse(response) != "A") {
                            alert("Wrong Password")
                            return

                        }
                    }

                    var passwordtype = JSON.parse(response);
                    type = passwordtype;

                    generateReport()





                },
                error: function(error) {
                    console.log(error);
                }
            });

        } else {
            generateReport()
        }



    }

    function generateReport() {
        $.ajax({
            type: "post",
            url: $('#baseUrl2').val() + 'report/report/purchase_reportinvoicewise',
            data: {
                from_date: $('#from_date').val(),
                to_date: document.getElementById('single_date_checkbox').checked ? $('#from_date').val() : $('#to_date').val(),
                empid: type,
                istype: document.getElementById('single_date_checkbox').checked,
                branch: $('#branch').val(),
                supplier_id: $('#supplier_id').val(),
                incident_type: $('#incident_type').val(),


            },
            success: function(data1) {
                datas = JSON.parse(data1);
                // console.log(datas)
                if (datas.length > 0) {
                    window.open(`generate_purchasereportinvoice`, '_blank');

                } else {
                    alert("There is no data available for the selected parameters.")
                }



            }
        });
    }

    function getBranchDropdown(branchId) {

        var base_url = $('#base_url').val();

        $.ajax({
            type: "post",
            url: base_url + "store/store/getbranchbyuserid",
            data: {
                // is_credit_edit: is_credit_edit,
                // csrf_test_name: csrf_test_name
            },
            success: function(data) {
                var branches = JSON.parse(data);
                console.log(branches)
                var $branchDropdown = $('#branch');
                $branchDropdown.empty();
                $branchDropdown.append('<option value="" disabled selected>Select Branch</option>'); // Add default option

                $.each(branches, function(index, branch) {
                    $branchDropdown.append('<option value="' + branch.id + '">' + branch.name + '</option>');
                    if (branch.default != 0) {
                        $branchDropdown.val(branch.id)
                    }
                });

                if (branchId > 0) {
                    {
                        $branchDropdown.val(branchId)
                    }
                }



            }
        });
    }
</script>
<script>
    document.getElementById('single_date_checkbox').addEventListener('change', function() {
        let fromDate = document.getElementById('from_date');
        let toDate = document.getElementById('to_date');
        let toDateContainer = document.getElementById('to_date_container');
        if (this.checked) {
            toDate.value = fromDate.value;
            toDateContainer.style.display = 'none';
        } else {
            toDateContainer.style.display = 'block';
        }
    });
</script>