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
    let type2 = '';
    let type = "";

    $(document).ready(function() {
        if (usertype == 3) {
            type2 = "B"

        } else {
            type2 = "A"

        }
    });



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
            url: $('#baseUrl2').val() + 'report/report/gross_profit_report',
            data: {
                from_date: $('#from_date').val(),
                to_date: document.getElementById('single_date_checkbox').checked ? $('#from_date').val() : $('#to_date').val(),
                empid: type,
                istype: document.getElementById('single_date_checkbox').checked,

            },
            success: function(data1) {
                datas = JSON.parse(data1);
                console.log(datas)
                window.open(`generate_gross_profit_report`, '_blank');
                // if (datas.length>0) {
                //     window.open(`generate_gross_profit_report`, '_blank');

                // } else {
                //     alert("There is no data available for the selected parameters.")
                // }


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