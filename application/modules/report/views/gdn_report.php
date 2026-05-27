<!-- GDN Report -->
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
                    <label for="store">Store</label>
                    <div class="input-group mr-4" style="width: 250px;">
                        <select class="form-control" id="store" name="store" style="width: 250px;" tabindex="1">
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="incident_type">Incident Type</label>
                    <div class="input-group mr-4" style="width: 250px;">
                        <select class="form-control" id="incident_type" name="incident_type" style="width: 250px;" tabindex="2">
                            <option value="">All Types</option>
                            <option value="sale">Retail</option>
                            <option value="wholesale">Wholesale</option>
                            <option value="purchasereturn">Purchase Return</option>
                            <option value="storetransfer">Store Transfer</option>
                            <option value="stockdisposal">Stock Disposal</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="customer_id">Customer</label>
                    <div class="input-group mr-4" style="width: 250px;">
                        <select class="form-control" id="customer_id" name="customer_id" style="width: 250px;" tabindex="3">
                            <option value="">All Customers</option>
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

                <button type="button" id="btn-filter" class="btn btn-success" onclick="onGdnFilterButtonClick()">
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
    let type = "";
    $(document).ready(function() {
        $(".datepicker").datepicker({
            dateFormat: 'yy-mm-dd'
        });
        getStoreDropdown();
        getCustomerDropdown();
        if (usertype == 3) {
            type2 = "B"

        } else {
            type2 = "A"

        }
    });

    function getStoreDropdown() {
        var base_url = $('#base_url').val();
        $.ajax({
            type: "post",
            url: base_url + "stock/stock/active_storegdndrop",
            success: function(data) {
                var stores = JSON.parse(data);
                var $storeDropdown = $('#store');
                $storeDropdown.empty();
                $storeDropdown.append('<option value="">All Stores</option>');
                $.each(stores, function(index, store) {
                    $storeDropdown.append('<option value="' + store.id + '">' + store.name + '</option>');
                    if (store.default != 0) {
                        $storeDropdown.val(store.id);
                    }
                });
            }
        });
    }

    function getCustomerDropdown() {
        var base_url = $('#base_url').val();
        $.ajax({
            type: "get",
            url: base_url + "report/report/getCustomersForReport",
            success: function(data) {
                var customers = JSON.parse(data);
                var $customerDropdown = $('#customer_id');
                $.each(customers, function(index, customer) {
                    $customerDropdown.append('<option value="' + customer.customer_id + '">' + customer.customer_name + '</option>');
                });
            }
        });
    }

    function onGdnFilterButtonClick() {
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
            url: $('#baseUrl2').val() + 'report/report/gdn_reportinvoicewise',
            data: {
                from_date: $('#from_date').val(),
                to_date: document.getElementById('single_date_checkbox').checked ? $('#from_date').val() : $('#to_date').val(),
                empid: type,
                istype: document.getElementById('single_date_checkbox').checked,
                store: $('#store').val(),
                incident_type: $('#incident_type').val(),
                customer_id: $('#customer_id').val(),
            },
            success: function(data1) {
                var datas = JSON.parse(data1);
                if (datas && datas.error) {
                    console.log('Debug info:', datas);
                    alert("Debug: " + JSON.stringify(datas, null, 2));
                } else if (datas && datas.length > 0) {
                    window.open('generate_gdnreportinvoice', '_blank');
                } else {
                    alert("There is no data available for the selected parameters.");
                }
            },
            error: function(xhr) {
                alert("Request failed. Please try again.");
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