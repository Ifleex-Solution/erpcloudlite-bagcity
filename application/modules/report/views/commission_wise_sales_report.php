<!-- Commission Wise Sales Report -->
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
                    <h4><?php echo $title; ?></h4>
                </div>
            </div>
            <br />
            <div class="panel-body" style="margin-left: 120px;">

                <?php
                date_default_timezone_set('Asia/Colombo');
                $today = date('Y-m-d');
                ?>

                <div class="form-group">
                    <label for="branch">Branch</label>
                    <div class="input-group mr-4" style="width: 250px;">
                        <select class="form-control" id="branch" name="branch" style="width: 250px;" tabindex="1">
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="employee">Employee</label>
                    <div class="input-group mr-4" style="width: 250px;">
                        <select name="employee" class="form-control" id="employee">
                            <option value="">-- All Employees --</option>
                            <?php foreach ($user_list as $user) { ?>
                                <option value="<?php echo $user['id']; ?>">
                                    <?php echo $user['first_name'] . ' ' . $user['last_name']; ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                </div>

                <div class="form-group" style="margin-bottom: 10px;">
                    <input type="checkbox" id="single_date_checkbox" name="single_date_checkbox">
                    <label for="single_date_checkbox">Single Date</label>
                </div>

                <div class="form-group" style="display: flex; gap: 20px;">
                    <div>
                        <label for="from_date">From Date:</label>
                        <input type="text" name="from_date" class="form-control datepicker" id="from_date"
                            placeholder="Start Date" value="<?php echo $today ?>" style="width: 200px;">
                    </div>
                    <div id="to_date_container">
                        <label for="to_date">To Date:</label>
                        <input type="text" name="to_date" class="form-control datepicker" id="to_date"
                            placeholder="End Date" value="<?php echo $today ?>" style="width: 200px;">
                    </div>
                </div>

                <div class="form-group">
                    <label for="password" class="mr-2 mb-0">Password</label>
                    <input type="password" tabindex="4" class="form-control" name="password" id="password"
                        value="" style="width: 200px;" autocomplete="off">
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

<script>
    let type2 = '';
    let type  = '';

    $(document).ready(function () {
        getBranchDropdown(0);
        type2 = (usertype == 3) ? 'B' : 'A';
    });

    function getBranchDropdown(branchId) {
        $.ajax({
            type: 'post',
            url: $('#base_url').val() + 'store/store/getbranchbyuserid',
            success: function (data) {
                var branches     = JSON.parse(data);
                var $branchDropdown = $('#branch');
                $branchDropdown.empty();
                $branchDropdown.append('<option value="" disabled selected>Select Branch</option>');
                $.each(branches, function (index, branch) {
                    $branchDropdown.append('<option value="' + branch.id + '">' + branch.name + '</option>');
                    if (branch.default != 0) {
                        $branchDropdown.val(branch.id);
                    }
                });
                if (branchId > 0) {
                    $branchDropdown.val(branchId);
                }
            }
        });
    }

    function onFilterButtonClick() {
        type = type2;

        if (password_enable == '1') {
            if (document.getElementById('password').value == '') {
                alert("Password shouldn't be empty");
                return;
            }
            $.ajax({
                url: $('#base_url').val() + 'dashboard/setting/checkpasswordReport',
                type: 'POST',
                data: { password: document.getElementById('password').value },
                success: function (response) {
                    if (JSON.parse(response) == 'wrong password') {
                        alert('Wrong Password');
                        return;
                    }
                    if (type == 'A' && JSON.parse(response) != 'A') {
                        alert('Wrong Password');
                        return;
                    }
                    type = JSON.parse(response);
                    generateReport();
                },
                error: function (error) { console.log(error); }
            });
        } else {
            generateReport();
        }
    }

    function generateReport() {
        $.ajax({
            type: 'post',
            url: $('#baseUrl2').val() + 'report/report/sales_reportcommissionwise',
            data: {
                from_date:  $('#from_date').val(),
                to_date:    document.getElementById('single_date_checkbox').checked
                                ? $('#from_date').val()
                                : $('#to_date').val(),
                empid:      type,
                istype:     document.getElementById('single_date_checkbox').checked,
                employee:   $('#employee').val(),
                branch:     $('#branch').val()
            },
            success: function (data1) {
                var datas = JSON.parse(data1);
                if (datas.length > 0) {
                    window.open('generate_salesreportcommissionwise', '_blank');
                } else {
                    alert('There is no data available for the selected parameters.');
                }
            }
        });
    }

    document.getElementById('single_date_checkbox').addEventListener('change', function () {
        var fromDate        = document.getElementById('from_date');
        var toDate          = document.getElementById('to_date');
        var toDateContainer = document.getElementById('to_date_container');
        if (this.checked) {
            toDate.value             = fromDate.value;
            toDateContainer.style.display = 'none';
        } else {
            toDateContainer.style.display = 'block';
        }
    });
</script>
