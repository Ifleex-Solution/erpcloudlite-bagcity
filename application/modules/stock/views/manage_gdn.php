<div class="row">
    <div class="col-sm-12">
        <div class="panel panel-bd lobidrag">
            <div class="panel-heading" id="style12">
                <div class="panel-title">
                    <span id="title"><?php echo display('manage_gdn') ?></span>
                    <span class="padding-lefttitle">
                        <table>
                            <tr>


                                <td style="padding-left: 20px;">
                                    <button class="btn btn-success m-b-5 m-r-2" data-toggle="modal" data-target="#filterModal">
                                        <i class="ti-filter"></i> Filter
                                    </button>
                                </td>
                                <td style="padding-left: 5px;">
                                    <?php if ($this->permission1->method('new_gdn', 'create')->access()) { ?>
                                        <a href="<?php echo base_url('new_gdn') ?>" class="btn btn-primary m-b-5 m-r-2"><i class="ti-plus"> </i> <?php echo display('new_gdn') ?> </a>
                                    <?php } ?>
                                </td>
                            </tr>
                        </table>
                    </span>

                </div>
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered" cellspacing="0" width="100%" id="stockdisposalnote">
                        <thead>
                            <tr>
                                <th><?php echo display('sl') ?></th>
                                <th>Date</th>
                                <th>GDN ID</th>
                                <th>Customer</th>
                                <th>Store</th>
                                <th>Voucher No</th>
                                <th>Incident Type</th>
                                <th>Details</th>
                                <th><?php echo display('action') ?>
                                </th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>

                </div>
            </div>
        </div>
        <input type="hidden" id="total_product" value="<?php echo $total_product; ?>" name="">
    </div>
</div>

<div id="filterModal" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Filter Options</h4>
            </div>

            <div class="modal-body">

                <?php
                date_default_timezone_set('Asia/Colombo');

                $date = date('Y-m-d'); ?>
                <div class="form-group">
                    <label>Store</label>
                    <select class="form-control " id="store" name="store" tabindex="3" style="width: 400px;">
                        <!-- options go here -->
                    </select>

                </div>


                <div class="form-group">
                    <label>From Date</label>
                    <input type="text" required tabindex="2" class="form-control datepicker" name="fdate" value="<?php echo $date; ?>" id="fdate" />
                </div>

                <div class="form-group">
                    <label>To Date</label>
                    <input type="text" required tabindex="2" class="form-control datepicker" name="tdate" value="<?php echo $date; ?>" id="tdate" />
                </div>

                <div class="form-group">
                        <label>Password</label>
                        <input type="password" class="form-control" id="password">
                </div>



            </div>


            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="filter()">Apply Filter</button>
            </div>

        </div>

    </div>
</div>
<?php
echo "<script>";
echo "let usertype=" . json_encode($this->session->userdata('user_level2')) . ";";
echo "let password_enable=" . json_encode($this->session->userdata('password_enable')) . ";";
echo "</script>";
?>
<script>
    let type2 = ""
    $(document).ready(function() {

        if (usertype == 3) {
            type2 = "B"
            const title = document.getElementById('title');
            title.style.color = 'blue';
            document.getElementById('style12').style.backgroundColor = '#E0E0E0';


        } else {
            type2 = "A"

        }

        // getStoreDropdown(0)

        getWholeData();


    });
    let storeId = 0;

    function filter() {
        $('#stockdisposalnote').DataTable().destroy();

        if (document.getElementById('fdate').value == '') {
            alert("From date shouldn't be empty")
            return

        }

        if (document.getElementById('store').value == '' | !document.getElementById('store').value) {
            alert("Store shouldn't be empty")
            return

        }
        if (document.getElementById('tdate').value == '') {
            alert("To date shouldn't be empty")
            return

        }

        if (password_enable == "1") {
            if (document.getElementById('password').value == '') {
                alert("Password shouldn't be empty")
                return

            }

            $.ajax({
                url: $('#base_url').val() + 'dashboard/setting/checkpassword',
                type: 'POST',
                data: {
                    password: document.getElementById('password').value,
                },
                success: function(response) {
                    var count = JSON.parse(response);
                    if (count == 1) {
                        document.getElementById('password').value = ''
                        getInvoiceData(document.getElementById('fdate').value, document.getElementById('tdate').value);
                        $('#filterModal').modal('hide');
                    } else {
                        alert("Wrong password")
                        return
                    }
                },
                error: function(error) {
                    console.log(error);
                }
            });


        } else {
            getInvoiceData(document.getElementById('fdate').value, document.getElementById('tdate').value);
            $('#filterModal').modal('hide');
            return

        }



    }


    function getWholeData() {

        if ($.fn.DataTable.isDataTable('#stockdisposalnote')) {
            $('#stockdisposalnote').DataTable().clear().destroy();
        }

        var base_url = $('#base_url').val();

        $.ajax({
            type: "post",
            url: base_url + "stock/stock/active_storegdndrop",
            data: {
                // is_credit_edit: is_credit_edit,
                // csrf_test_name: csrf_test_name
            },
            success: function(data) {
                var storees = JSON.parse(data);
                var $storeDropdown = $('#store');
                $storeDropdown.empty();
                $storeDropdown.append('<option value="" disabled selected>Select store</option>'); // Add default option

                $.each(storees, function(index, store) {
                    $storeDropdown.append('<option value="' + store.id + '">' + store.name + '</option>');
                    if (storeId == 0) {
                        if (store.default != 0) {
                            $storeDropdown.val(store.id)
                            storeId = store.id;
                        }
                    }

                });

                if (storeId > 0) {
                    {
                        $storeDropdown.val(storeId)
                    }
                }

				if (password_enable == "0"){
                    getInvoiceData(null, null)
                }
            }
        });


    }

    function getInvoiceData(fdate, tdate) {
        "use strict";
        var csrf_test_name = $('#CSRF_TOKEN').val();
        var base_url = $('#base_url').val();
        var total_product = $("#total_product").val();
        $('#stockdisposalnote').DataTable({
            responsive: true,

            "aaSorting": [
                [2, "desc"]
            ],
            "columnDefs": [{
                    "bSortable": false,
                    "aTargets": [0, 1, 2, 3, 4]
                },

            ],
            'processing': true,
            'serverSide': true,


            'lengthMenu': [
                [10, 25, 50, 100, 250, 500, 1000],
                [10, 25, 50, 100, 250, 500, 1000]
            ],

            dom: "'<'col-sm-4'l><'col-sm-4 text-center'><'col-sm-4'>Bfrtip",
            buttons: [{
                extend: "copy",
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5] //Your Colume value those you want
                },
                className: "btn-sm prints"
            }, {
                extend: "csv",
                title: "Manage GRN",
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5] //Your Colume value those you want print
                },
                className: "btn-sm prints"
            }, {
                extend: "excel",
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5] //Your Colume value those you want print
                },
                title: "Manage GRN",
                className: "btn-sm prints"
            }, {
                extend: "pdf",
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5] //Your Colume value those you want print
                },
                title: "Manage GRN",
                className: "btn-sm prints"
            }, {
                extend: "print",
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5] //Your Colume value those you want print
                },
                title: "<center>Manage GRN</center>",
                className: "btn-sm prints"
            }],

            'serverMethod': 'post',
            'ajax': {
                'url': base_url + 'stock/stock/checkgdnstock',
                data: {
                    csrf_test_name: csrf_test_name,
                    type2: type2,
                    storeid: document.getElementById("store").value,
                    fdate: fdate,
                    tdate: tdate

                }
            },
            'columns': [{
                    data: 'sl'
                },
                {
                    data: 'date'
                },
                {
                    data: 'gdn_id'
                },
                {
                    data: 'customer_name'
                },
                {
                    data: 'store'
                },
                {
                    data: 'voucherno'
                },
                {
                    data: 'type'
                },
                {
                    data: 'details'
                },
                {
                    data: 'button'
                },
            ],

        });

    }



    function reprintInvoice(invoiceId) {
        if (confirm("Do you want to reprint this record")) {
            $.ajax({
                type: "post",
                url: $('#base_url').val() + 'stock/stock/getgdnStockForPos',
                data: {
                    id: invoiceId,

                },
                success: function(data1) {
                    datas = JSON.parse(data1);
                    printRawHtml(datas.details, invoiceId);

                }
            });
        }

    }

    function printRawHtml(view, invoiceId) {
        $(view).print({

            deferred: $.Deferred().done(function() {
                window.location.reload(true);
            })
        });
    }
</script>