<script src="<?php echo base_url() ?>my-assets/js/admin_js/purchase.js" type="text/javascript"></script>


<style>
    .col-big {
        width: 20% !important;
    }

    .col-total {
        width: 20% !important;
    }

    .col-medium {
        width: 11% !important;
    }

    .col-medium2 {
        width: 15% !important;
    }

    .vathidden {
        width: 11% !important;
    }

    .vatshow {
        width: 15% !important;
    }


    .col-small {
        width: 7% !important;
    }
</style>
<div class="row">
    <div class="col-sm-12">
        <div class="panel panel-bd lobidrag">
            <div class="panel-heading" id="style12">
                <div class="panel-title">
                    <span id="title"><?php echo $title; ?></span>
                     <span class="padding-lefttitle">
                        <table>
                            <tr>
                                <td style="padding-left: 20px;">
                                    <button class="btn btn-success m-b-5 m-r-2" data-toggle="modal" data-target="#customerModel">
                                        <i class="fa fa-user-plus"></i> Add Customer
                                    </button>
                                </td>
                            </tr>
                        </table>



                    </span>
                </div>

            </div>

            <div class="panel-body">


                <div class="row">



                    <div class="col-sm-6">
                        <div class="form-group row">
                            <label for="date" class="col-sm-4 col-form-label">Service Date
                                <i class="text-danger">*</i>
                            </label>
                            <div class="col-sm-6">
                                <?php
                                date_default_timezone_set('Asia/Colombo');

                                $date = date('Y-m-d'); ?>
                                <input type="text" required tabindex="2" class="form-control datepicker" name="sale_date" value="<?php echo $date; ?>" id="date" />
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="form-group row">
                            <label for="eod_date" class="col-sm-4 col-form-label">EOD Date
                            </label>
                            <div class="col-sm-6">
                                <input type="text" tabindex="2" class="form-control datepicker" name="eod_date" value="<?php echo $date; ?>" id="eod_date" />
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="form-group row">
                            <label for="supplier_sss" class="col-sm-4 col-form-label">Branch
                                <i class="text-danger">*</i>
                            </label>
                            <div class="col-sm-6">
                                <select class="form-control" id="branch" required name="branch" tabindex="3">


                                </select>
                            </div>

                        </div>

                    </div>



                    <div class="col-sm-6">
                        <div class="form-group row">
                            <label for="invoicetype" class="col-sm-4 col-form-label">Invoice Type
                                <i class="text-danger">*</i>
                            </label>
                            <div class="col-sm-6">
                                <select class="form-control" id="invoicetype" required name="invoicetype" tabindex="3" onchange="incidetTypechange()">
                                    <option value=""></option>
                                    <option value="cash">Cash</option>
                                    <option value="credit">Credit</option>
                                    <option value="cash_vat">Cash VAT</option>
                                    <option value="credit_vat">Credit VAT</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="form-group row">
                            <label for="supplier_sss" class="col-sm-4 col-form-label">Customer
                                <i class="text-danger">*</i>
                            </label>
                            <div class="col-sm-6">
                                <select name="customer_id" id="customer_id" class="form-control " required="" tabindex="1">
                                    <option value="">Select an option</option>
                                    <?php foreach ($all_customer as $customer) { ?>
                                        <option value="<?php echo $customer['customer_id'] ?>">
                                            <?php echo $customer['customer_name'] ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                         
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="form-group row">
                            <label for="supplier_sss" class="col-sm-4 col-form-label">Employee
                            </label>
                            <div class="col-sm-6">
                                <select name="employee_id" id="employee_id" class="form-control " tabindex="1">
                                    <option value="">Select an option</option>
                                    <?php foreach ($all_employee as $employee) { ?>
                                        <option value="<?php echo $employee['id'] ?>">
                                            <?php echo $employee['first_name'] . " " . $employee['last_name'] ?></option>
                                    <?php } ?>
                                </select>
                            </div>

                        </div>
                    </div>





                </div>

                <div class="row">


                </div>


                <br>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="saleTable">
                        <thead>
                            <tr>
                                <th class="text-center  col-big">Service<i
                                        class="text-danger">*</i></th>

                                <th class="text-center col-medium vathidden">Qty</th>
                                <th class="text-center col-medium2 vatshow">Qty</th>

                                <th class="text-center col-medium vathidden">Price val</th>
                                <th class="text-center col-medium2 vatshow">Price val</th>



                                <th class="text-center col-medium vathidden">Discount</th>
                                <th class="text-center col-medium vathidden">Dis.val</th>

                                <th class="text-center col-medium2 vatshow">Discount</th>
                                <th class="text-center col-medium2 vatshow">Dis.val</th>

                                <th class="text-center col-medium vathidden">VAT </th>
                                <th class="text-center col-medium vathidden">VAT.val</th>

                                <th class="text-center col-medium vathidden">Total</th>
                                <th class="text-center col-medium2 vatshow">Total</th>

                                <th class="text-center col-medium"><?php echo display('action') ?></th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody id="addinvoiceItem">
                            <tr id="myRow1">
                                <td class="product_field">

                                    <select name="product[]" class="form-control" id="product1" tabindex="1" onchange="product_search(1, 'product')">
                                        <option value="">Select Product</option>
                                        <?php foreach ($services as $service) { ?>
                                            <option value="<?php echo $service['service_id']; ?>"><?php echo $service['service_name']; ?></option>
                                        <?php } ?>
                                    </select>


                                </td>




                                <td class="qty">
                                    <input type="text" name="product_quantity[]" id="qty1" min="0" class="form-control text-right store_cal_1" onkeyup="calculate_sum(1);" onchange="calculate_sum(1);" placeholder="0.00" value="" tabindex="6" />
                                </td>
                                <td class="rate">
                                    <input type="text" name="product_rate[]" onkeyup="calculate_sum(1);" onchange="calculate_sum(1);" id="product_rate1" class="form-control product_rate_1 text-right" placeholder="0.00" value="" min="0" tabindex="7" />
                                </td>

                                <td class="qty">
                                    <input type="text" name="discount_per[]" onkeyup="calculate_sum(1);" onchange="calculate_sum(1);" id="discount1" class="form-control discount_1 text-right" min="0" tabindex="11" placeholder="0.00" />
                                    <input type="hidden" value="<?php echo $discount_type ?>" name="discount_type" id="discount_type">

                                </td>
                                <td class="rate">
                                    <input type="text" name="discountvalue[]" id="discount_value1" class="form-control text-right discount_value_1 total_discount_val" min="0" tabindex="12" placeholder="0.00" readonly />
                                </td>

                                <!-- VAT  start-->
                                <td class="qty vathidden">
                                    <input type="text" name="vatpercent[]" onkeyup="calculate_sum(1);" onchange="calculate_sum(1);" id="vat_percent1" class="form-control vat_percent_1 text-right" min="0" tabindex="13" placeholder="0.00" />
                                </td>
                                <td class="rate vathidden">
                                    <input type="text" name="vatvalue[]" id="vat_value1" class="form-control vat_value1 text-right total_vatamnt" min="0" tabindex="14" placeholder="0.00" readonly />
                                </td>
                                <!-- VAT  end-->
                                <td class="product_field">
                                    <input class="form-control total_price text-right total_price_1" type="text" name="total_price[]" id="total_price1" value="0.00" readonly="readonly" />

                                    <input type="hidden" id="total_discount1" class="" />
                                    <input type="hidden" id="all_discount1" class="total_discount dppr" name="discount_amount[]" />
                                </td>

                                <td>
                                </td>

                            </tr>

                            <?php
                            for ($i = 2; $i <= 20; $i++) {
                            ?>
                                <tr id="myRow<?php echo $i; ?>">
                                    <td class="product_field">
                                        <select name="product[]" class="form-control" id="product<?php echo $i; ?>" tabindex="1" onchange="product_search(<?php echo $i; ?>, 'product')">
                                            <option value="">Select Product</option>
                                            <?php foreach ($services as $service) { ?>
                                                <option value="<?php echo $service['service_id']; ?>"><?php echo $service['service_name']; ?></option>
                                            <?php } ?>
                                        </select>
                                    </td>





                                    <td class="qty">
                                        <input type="text" name="product_quantity[]" id="qty<?php echo $i; ?>" min="0" class="form-control text-right store_cal_1" onkeyup="calculate_sum(<?php echo $i; ?>);" onchange="calculate_sum(<?php echo $i; ?>);" placeholder="0.00" value="" tabindex="6" />
                                    </td>

                                    <td class="rate">
                                        <input type="text" name="product_rate[]" onkeyup="calculate_sum(<?php echo $i; ?>);" onchange="calculate_sum(<?php echo $i; ?>);" id="product_rate<?php echo $i; ?>" class="form-control product_rate_1 text-right" placeholder="0.00" value="" min="0" tabindex="7" />
                                    </td>

                                    <td class="qty">
                                        <input type="text" name="discount_per[]" onkeyup="calculate_sum(<?php echo $i; ?>);" onchange="calculate_sum(<?php echo $i; ?>);" id="discount<?php echo $i; ?>" class="form-control discount_1 text-right" min="0" tabindex="11" placeholder="0.00" />
                                        <input type="hidden" value="<?php echo $discount_type ?>" name="discount_type" id="discount_type">
                                    </td>

                                    <td class="rate">
                                        <input type="text" name="discountvalue[]" id="discount_value<?php echo $i; ?>" class="form-control text-right discount_value_1 total_discount_val" min="0" tabindex="12" placeholder="0.00" readonly />
                                    </td>

                                    <!-- VAT start -->
                                    <td class="qty vathidden">
                                        <input type="text" name="vatpercent[]" onkeyup="calculate_sum(<?php echo $i; ?>);" onchange="calculate_sum(<?php echo $i; ?>);" id="vat_percent<?php echo $i; ?>" class="form-control vat_percent_1 text-right" min="0" tabindex="13" placeholder="0.00" />
                                    </td>
                                    <td class="rate vathidden">
                                        <input type="text" name="vatvalue[]" id="vat_value<?php echo $i; ?>" class="form-control vat_value1 text-right total_vatamnt" min="0" tabindex="14" placeholder="0.00" readonly />
                                    </td>
                                    <!-- VAT end -->

                                    <td class="product_field">
                                        <input class="form-control total_price text-right total_price_1" type="text" name="total_price[]" id="total_price<?php echo $i; ?>" value="0.00" readonly="readonly" />
                                        <input type="hidden" id="total_discount<?php echo $i; ?>" class="" />
                                        <input type="hidden" id="all_discount<?php echo $i; ?>" class="total_discount dppr" name="discount_amount[]" />
                                    </td>

                                    <td>
                                        <button class='btn btn-danger' type='button' value='Delete' onclick='deleteRow(<?php echo $i; ?>)'>
                                            <i class='fa fa-trash'></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php
                            }
                            ?>

                        </tbody>
                        <tfoot>
                            <tr>
                                <td class="text-right vathidden" colspan="7"><b><?php echo display('total') ?>:</b></td>
                                <td class="text-right vatshow" colspan="5"><b><?php echo display('total') ?>:</b></td>
                                <td class="text-right">
                                    <input type="text" id="Total" class="text-right form-control" name="total" value="0.00" readonly="readonly" />
                                </td>
                                <td>
                                    <button type="button" id="add_invoice_item" class="btn btn-info" name="add-invoice-item"
                                        onClick="addInputField('addinvoiceItem');" tabindex="9"><i class="fa fa-plus"></i></button>
                                    <input type="hidden" name="baseUrl" class="baseUrl" value="<?php echo base_url(); ?>" />
                                </td>
                            </tr>
                            <tr>
                                <td class="text-right vathidden" colspan="7"><b>Service Discount:</b></td>
                                <td class="text-right vatshow" colspan="5"><b>Service Discount:</b></td>
                                <td class="text-right">
                                    <input type="text" id="discount" class="text-right form-control discount total_discount_val" onkeyup="calculate_sum(1)" name="discount" placeholder="0.00" value="" />
                                </td>
                                <td></td>
                            </tr>
                            <tr>
                                <td class="text-right vathidden" colspan="7"><b><?php echo display('total_discount') ?>:</b></td>
                                <td class="text-right vatshow" colspan="5"><b><?php echo display('total_discount') ?>:</b></td>
                                <td class="text-right">
                                    <input type="text" id="total_discount_ammount" class="form-control text-right" name="total_discount" value="0.00" readonly="readonly" />
                                </td>
                                <td> </td>
                            </tr>
                            <tr>
                                <td class="text-right vathidden" colspan="7"><b><?php echo display('ttl_val') ?>:</b></td>
                                <td class="text-right vathidden">
                                    <input type="text" id="total_vat_amnt" class="form-control text-right" name="total_vat_amnt" value="0.00" readonly="readonly" />
                                </td>
                                <td class="vathidden"> </td>
                            </tr>
                            <tr>
                                <td class="text-right vathidden" colspan="7"><b><?php echo display('grand_total') ?>:</b></td>
                                <td class="text-right vatshow" colspan="5"><b><?php echo display('grand_total') ?>:</b></td>
                                <td class="text-right">
                                    <input type="text" id="grandTotal" class="text-right form-control grandTotalamnt" name="grand_total_price" placeholder="0.00" value="00" readonly />
                                </td>
                                <td> </td>
                            </tr>
                        </tfoot>
                    </table>
                    <input type="hidden" name="finyear" value="<?php echo financial_year(); ?>">
                    <p hidden id="pay-amount"></p>
                    <p hidden id="change-amount"></p>
                </div>

                <div class="col-sm-3 table-bordered p-20" style="margin-top: 10px;">
                    <div class="form-group">
                        <label for="details" class="col-form-label"><?php echo display('details'); ?></label>
                        <textarea class="form-control" tabindex="4" id="details" name="sale_details" placeholder="<?php echo display('details'); ?>" rows="3"></textarea>
                    </div>
                </div>

                <div class="form-group row text-right">
                    <div class="col-sm-12 p-20">
                        <button id="save_add" class="btn btn-success" name="add-invoice" onclick="save()">
                            <?php echo (empty($id) ? display('save') : display('update')) ?></button>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<?php
echo "<script>";
echo "let id = " . json_encode($id) . ";";
echo "let services=" . json_encode($service_list) . ";";
echo "let customers=" . json_encode($all_customer) . ";";
echo "let employees=" . json_encode($all_employee) . ";";
echo "let usertype=" . json_encode($this->session->userdata('user_level2')) . ";";

echo "let pmethods=" . json_encode($all_pmethod) . ";";
echo "let vtinfo=" . json_encode($vtinfo) . ";";
echo "</script>";
?>
<script>
    $('body').addClass("sidebar-mini sidebar-collapse");

    let type2 = ""
    if (usertype == 3) {
        document.getElementById('style12').style.backgroundColor = '#E0E0E0';
        const title = document.getElementById('title');
        title.style.color = 'blue';
        type2 = "B"

    } else {
        type2 = "A"

    }
    let count = 2
    getActiveProduct(0, 1)

    function clearDetails2() {
        for (let i = 1; i < 20; i++) {
            var $productDropdown = $('#product' + i);
            $productDropdown.empty();
            $productDropdown.append('<option value="" disabled selected>Select Product</option>');
            $.each(services, function(index, s) {
                $productDropdown.append('<option value="' + s.service_id + '">' + s.service_name + '</option>');
            });
            document.getElementById('myRow' + i).style.display = 'none';
            document.getElementById('qty' + i).value = "";
            document.getElementById('product_rate' + i).value = "";
            document.getElementById('discount' + i).value = "";
            document.getElementById('discount_value' + i).value = "";
            document.getElementById('vat_percent' + i).value = "";
            document.getElementById('vat_value' + i).value = "";
            document.getElementById('total_price' + i).value = "";
            document.getElementById('total_discount' + i).value = "";
            document.getElementById('all_discount' + i).value = "";
        }
        document.getElementById('myRow1').style.display = 'table-row';
        document.getElementById('discount').value = "";
        document.getElementById('total_discount_ammount').value = "";
        document.getElementById('total_vat_amnt').value = "";
        document.getElementById('grandTotal').value = "";
        document.getElementById('Total').value = "";
    }

    function incidetTypechange() {
        clearDetails2();
        if (document.getElementById('invoicetype').value === 'cash_vat' ||
            document.getElementById('invoicetype').value === 'credit_vat') {
            document.querySelectorAll('.vathidden').forEach(el => {
                el.style.display = 'table-cell';
            });
            document.querySelectorAll('.vatshow').forEach(el => {
                el.style.display = 'none';
            });
        } else {
            document.querySelectorAll('.vathidden').forEach(el => {
                el.style.display = 'none';
            });
            document.querySelectorAll('.vatshow').forEach(el => {
                el.style.display = 'table-cell';
            });
        }
    }

    $(document).ready(function() {
        document.querySelectorAll('.vathidden').forEach(el => {
            el.style.display = 'none';
        });

        if (id == null) {
            document.getElementById('invoicetype').value = 'cash';
        }

        for (let j = 2; j <= 20; j++) {
            document.getElementById('myRow' + j).style.display = 'none';
        }

        var $customerDropdown = $('#customer_id');
        $customerDropdown.empty();
        $customerDropdown.append('<option value="" disabled selected>Select Customer</option>'); // Add default option
        $.each(customers, function(index, customer) {
            $customerDropdown.append('<option value="' + customer.customer_id + '">' + customer.customer_name + '</option>');
        });
        $customerDropdown.val(1)



        if (id != null) {
            $.ajax({
                url: $('#base_url').val() + 'service/service/getServiceOrderById',
                type: 'POST',
                data: {
                    id: id,
                    type2: type2
                },
                success: function(response) {
                    var sales = JSON.parse(response);
                    document.getElementById('date').value = sales[0].date;
                    document.getElementById('eod_date').value = sales[0].eod_date;
                    document.getElementById('details').value = sales[0].details;

                    getBranchDropdown(sales[0].branch);






                    var $customerDropdown = $('#customer_id');
                    $customerDropdown.empty();
                    $customerDropdown.append('<option value="" disabled selected>Select Customer</option>'); // Add default option
                    $.each(customers, function(index, customer) {
                        $customerDropdown.append('<option value="' + customer.customer_id + '">' + customer.customer_name + '</option>');
                    });
                    $customerDropdown.val(sales[0].customer_id)

                    var $employeeDropdown = $('#employee_id');
                    $employeeDropdown.empty();
                    $employeeDropdown.append('<option value="" disabled selected>Select Employee</option>');
                    $.each(employees, function(index, employee) {
                        $employeeDropdown.append('<option value="' + employee.id + '">' + employee.first_name + " " + employee.last_name + '</option>');
                    });
                    $employeeDropdown.val(sales[0].employee_id);

                    var $invoiceTypeDropdown = $('#invoicetype');
                    $invoiceTypeDropdown.empty();
                    $invoiceTypeDropdown.append('<option value="" disabled selected>Select Invoice Type</option>');
                    $invoiceTypeDropdown.append('<option value="cash">Cash</option>');
                    $invoiceTypeDropdown.append('<option value="credit">Credit</option>');
                    $invoiceTypeDropdown.append('<option value="cash_vat">Cash VAT</option>');
                    $invoiceTypeDropdown.append('<option value="credit_vat">Credit VAT</option>');
                    $invoiceTypeDropdown.val(sales[0].invoicetype);
                    $invoiceTypeDropdown.prop('disabled', true);
                    incidetTypechange();

                    document.getElementById('total_discount_ammount').value = sales[0].total_discount_ammount;
                    document.getElementById('total_vat_amnt').value = sales[0].total_vat_amnt;
                    document.getElementById('grandTotal').value = sales[0].grandTotal;
                    document.getElementById('Total').value = sales[0].total;
                    document.getElementById('discount').value = sales[0].discount;

                    // count = 1;
                    for (let i = 0; i < sales.length; i++) {
                        let a = i + 1;
                        document.getElementById('myRow' + a).style.display = 'table-row';
                        getActiveProduct(sales[i].service, a);

                        document.getElementById('qty' + a).value = sales[i].quantity;
                        document.getElementById('product_rate' + a).value = sales[i].product_rate;
                        document.getElementById('discount' + a).value = sales[i].discount2;
                        document.getElementById('discount_value' + a).value = sales[i].discount_value;

                        if (vtinfo.ischecked == 1) {
                            document.getElementById('vat_percent' + a).value = sales[i].vat_percent;
                        }
                        document.getElementById('vat_value' + a).value = sales[i].vat_value;
                        document.getElementById('total_price' + a).value = sales[i].total_price;
                        document.getElementById('total_discount' + a).value = sales[i].total_discount;
                        document.getElementById('all_discount' + a).value = sales[i].all_discount;



                        count = count + 1;
                    }
                },
                error: function(error) {
                    console.log(error);
                }
            });
        } else {
            getBranchDropdown(0);
            getSalesOrderDropdown();

        }
    });





    function addInputField(t) {
        // if (count < 11) {
        document.getElementById('myRow' + count).style.display = 'table-row';
        getActiveProduct(0, count)
        count = count + 1;

    }

    function product_search(item, name) {

        if (name === "product") {
            document.getElementById('qty' + item).value = "";
            document.getElementById('product_rate' + item).value = "";
            document.getElementById('discount' + item).value = "";
            document.getElementById('discount_value' + item).value = "";
            document.getElementById('vat_percent' + item).value = "";
            document.getElementById('vat_value' + item).value = "";
            document.getElementById('total_price' + item).value = "";
            document.getElementById('total_discount' + item).value = "";
            document.getElementById('all_discount' + item).value = "";
            document.getElementById('qty' + item).value = "";
            document.getElementById('product_rate' + item).value = services.find(s => s.service_id === document.getElementById('product' + item).value).charge;

            if (vtinfo.ischecked == 1) {
                document.getElementById('vat_percent' + item).value = services.find(s => s.service_id === document.getElementById('product' + item).value).service_vat;
            }
        }
    }



    function deleteRow(num) {
        document.getElementById('myRow' + num).style.display = 'none';

        document.getElementById('qty' + num).value = 0;
        document.getElementById('product_rate' + num).value = 0;
        document.getElementById('discount' + num).value = 0;
        document.getElementById('discount_value' + num).value = 0;
        document.getElementById('vat_percent' + num).value = 0;
        document.getElementById('vat_value' + num).value = 0;
        document.getElementById('total_price' + num).value = 0;
        document.getElementById('total_discount' + num).value = 0;
        document.getElementById('all_discount' + num).value = 0;
        calculate_sum(num)
    }



    function calculate_sum(sl) {

        var p = 0;
        var v = 0;
        var gr_tot = 0;
        var dis = 0;
        var item_ctn_qty = $("#qty" + sl).val();
        var vendor_rate = $("#product_rate" + sl).val();

        var total_price = item_ctn_qty * vendor_rate;
        $("#total_price" + sl).val(total_price.toFixed(2));

        var quantity = $("#qty" + sl).val();
        var discount = $("#discount" + sl).val();
        var dis_type = $("#discount_type").val();
        var price_item = $("#product_rate" + sl).val();
        var invoicetype = document.getElementById('invoicetype').value;
        var isVatType = (invoicetype === 'cash_vat' || invoicetype === 'credit_vat');
        var vat_percent = isVatType ? (parseFloat($("#vat_percent" + sl).val()) || 0) : 0;
        if (!isVatType) {
            $("#vat_value" + sl).val(0);
        }
        var avqty = $("#avqty" + sl).val();


        // if (parseInt(quantity) > parseInt(avqty)) {
        //     $("#qty" + sl).val("");
        //     alert("Quantity shouldn't be greater than available quantity");
        //     return;
        // }

        if (quantity > 0 || discount > 0 || vat_percent > 0) {
            if (dis_type == 1) {
                var price = quantity * price_item;
                var disc = +(price * discount / 100);
                $("#discount_value" + sl).val(disc);
                $("#all_discount" + sl).val(disc);
                //Total price calculate per product
                var temp = price - disc;
                // product wise vat start
                var vat = +(temp * vat_percent / 100);
                $("#vat_value" + sl).val(vat);
                // product wise vat end
                var ttletax = 0;
                $("#total_price" + sl).val(temp);



            } else if (dis_type == 2) {
                var price = quantity * price_item;

                // Discount cal per product
                var disc = (discount * quantity);
                $("#discount_value" + sl).val(disc);
                $("#all_discount" + sl).val(disc);

                //Total price calculate per product
                var temp = price - disc;
                $("#total_price" + sl).val(temp);
                // product wise vat start
                var vat = +(temp * vat_percent / 100);
                $("#vat_value" + sl).val(vat);
                // product wise vat end

                var ttletax = 0;

            } else if (dis_type == 3) {
                var total_price = quantity * price_item;
                var disc = discount;
                // Discount cal per product
                $("#discount_value" + sl).val(disc);
                $("#all_discount" + sl).val(disc);
                //Total price calculate per product
                var price = total_price - disc;
                $("#total_price" + sl).val(price);
                // product wise vat start
                var vat = +(price * vat_percent / 100);
                $("#vat_value" + sl).val(vat);
                // product wise vat end

                $("#total_price" + sl).val(price);


                var ttletax = 0;

            }
        }

        //Total Price
        $(".total_price").each(function() {
            isNaN(this.value) || 0 == this.value.length || (gr_tot += parseFloat(this.value))
        });
        $(".discount").each(function() {
            isNaN(this.value) || 0 == this.value.length || (dis += parseFloat(this.value))
        });
        //Total Discount
        $(".total_discount_val").each(function() {
                isNaN(this.value) || 0 == this.value.length || (p += parseFloat(this.value))
            }),
            $("#total_discount_ammount").val(p.toFixed(2, 2)),

            $(".total_vatamnt").each(function() {
                isNaN(this.value) || 0 == this.value.length || (v += parseFloat(this.value))
            }),
            $("#total_vat_amnt").val(v.toFixed(2, 2)),

            $("#Total").val(gr_tot.toFixed(2, 2));
        var vatamnt = $("#total_vat_amnt").val();

        var gttl = gr_tot - dis;
        var grandtotal = parseFloat(gttl) + parseFloat(vatamnt);
        $("#grandTotal").val(grandtotal.toFixed(2, 2));
        // $("#pamount_by_method").val(grandtotal.toFixed(2, 2));

        // $('#paidAmount').val(grandtotal.toFixed(2, 2));

        var purchase_edit_page = $("#purchase_edit_page").val();
        $("#add_new_payment").empty();

        $("#pay-amount").text('0');
        //   $("#dueAmmount").val(0);

        if (purchase_edit_page == 1) {

            var base_url = $('#base_url').val();
            var is_credit_edit = $('#is_credit_edit').val();

            var csrf_test_name = $('[name="csrf_test_name"]').val();
            var gtotal = $(".grandTotalamnt").val();
            var url = base_url + "purchase/purchase/bdtask_showpaymentmodal";
            $.ajax({
                type: "post",
                url: url,
                data: {
                    is_credit_edit: is_credit_edit,
                    csrf_test_name: csrf_test_name
                },
                success: function(data) {


                    $('#add_new_payment').append(data);

                    //  $("#pamount_by_method").val(gtotal);
                    $("#add_new_payment_type").prop('disabled', false);
                    var card_typesl = $('.card_typesl').val();

                    if (card_typesl == 0) {
                        $("#add_new_payment_type").prop('disabled', true);
                    }

                }
            });

        }

    }

    function getActiveProduct(productId, item) {
        var $productDropdown = $('#product' + item);
        $productDropdown.empty();
        $productDropdown.append('<option value="" disabled selected>Select Product</option>'); // Add default option

        $.each(services, function(index, product) {
            $productDropdown.append('<option value="' + product.service_id + '">' + product.service_name + '</option>');
        });

        if (productId > 0) {
            {
                $productDropdown.val(productId)
            }
        }
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


    function save() {
        arrItem = [];
        for (let i = 1; i < count; i++) {
            if (document.getElementById('myRow' + i).style.display != "none") {
                if (document.getElementById('customer_id').value == "" || document.getElementById('customer_id').value === " ") {
                    alert("Customer shouldn't be empty")
                    return
                } else if (document.getElementById('invoicetype').value == "") {
                    alert("Invoice Type shouldn't be empty")
                    return
                } else if (document.getElementById('branch').value == "") {
                    alert("Branch shouldn't be empty")
                    return
                } else if (document.getElementById('product' + i).value == "") {
                    alert("Service shouldn't be empty")
                    return
                } else if (document.getElementById('qty' + i).value == "") {
                    alert("Quantity shouldn't be empty")
                    return

                } else
                if (document.getElementById('product_rate' + i).value == "") {
                    alert("Price shouldn't be empty")
                    return
                } else {
                    var dropdown = document.getElementById('product' + i);


                    arrItem.push({
                        service: document.getElementById('product' + i).value,
                        product_name: dropdown.options[dropdown.selectedIndex].text,
                        quantity: document.getElementById('qty' + i).value,
                        product_rate: document.getElementById('product_rate' + i).value ? document.getElementById('product_rate' + i).value : "0",
                        discount: document.getElementById('discount' + i).value ? document.getElementById('discount' + i).value : "0",
                        discount_value: document.getElementById('discount_value' + i).value ? document.getElementById('discount_value' + i).value : "0",
                        vat_percent: document.getElementById('vat_percent' + i).value ? document.getElementById('vat_percent' + i).value : "0",
                        vat_value: document.getElementById('vat_value' + i).value ? document.getElementById('vat_value' + i).value : "0",
                        total_price: document.getElementById('total_price' + i).value ? document.getElementById('total_price' + i).value : "0",
                        total_discount: document.getElementById('total_discount' + i).value ? document.getElementById('total_discount' + i).value : "0",
                        all_discount: document.getElementById('all_discount' + i).value ? document.getElementById('all_discount' + i).value : "0",
                    });
                }
            }

        }

        $("#save_add").hide();

        if (id > 0) {
            $.ajax({
                url: $('#base_url').val() + 'service/service/update_serviceorder',
                type: 'POST',
                data: {
                    id: id,
                    items: arrItem,
                    discount: document.getElementById('discount').value,
                    type2: "C",
                    invoicetype: document.getElementById('invoicetype').value,
                    total_discount_ammount: document.getElementById('total_discount_ammount').value,
                    total_vat_amnt: document.getElementById('total_vat_amnt').value,
                    grandTotal: document.getElementById('grandTotal').value,
                    date: document.getElementById('date').value,
                    eod_date: document.getElementById('eod_date').value,
                    details: document.getElementById('details').value,
                    total: document.getElementById('Total').value,
                    customer_id: document.getElementById('customer_id').value,
                    employee_id: document.getElementById('employee_id').value,
                    branch: document.getElementById('branch').value
                },
                success: function(response) {
                    // alert("Invoice Details Updated Successfully")
                    // window.location.href = $('#base_url').val() + 'invoice_list';

                    datas = JSON.parse(response);
                    clearDetails()
                    $("#save_add").show();

                    alert("Job order Details Updated Successfully")
                    printRawHtml(datas.details);


                },
                error: function(error) {
                    console.log(error)
                }
            });


        } else {
            // console.log(arrItem)

            $.ajax({
                url: $('#base_url').val() + 'service/service/save_service_order',
                type: 'POST',
                data: {
                    items: arrItem,
                    type2: "C",
                    invoicetype: document.getElementById('invoicetype').value,
                    discount: document.getElementById('discount').value,
                    total_discount_ammount: document.getElementById('total_discount_ammount').value,
                    total_vat_amnt: document.getElementById('total_vat_amnt').value,
                    grandTotal: document.getElementById('grandTotal').value,
                    date: document.getElementById('date').value,
                    eod_date: document.getElementById('eod_date').value,
                    details: document.getElementById('details').value,
                    total: document.getElementById('Total').value,
                    customer_id: document.getElementById('customer_id').value,
                    employee_id: document.getElementById('employee_id').value,
                    branch: document.getElementById('branch').value,



                },
                success: function(response) {
                    datas = JSON.parse(response);
                    clearDetails()
                    $("#save_add").show();

                    alert("Job order Details saved Successfully")
                    printRawHtml(datas.details);
                },
                error: function(error) {
                    console.log(error)
                }
            });

        }







    }

    function clearDetails() {
        for (let i = 1; i < 20; i++) {
            var $productDropdown = $('#product' + i);
            $productDropdown.empty();
            $productDropdown.append('<option value="" disabled selected>Select Product</option>'); // Add default option

            $.each(services, function(index, product) {
                $productDropdown.append('<option value="' + product.service_id + '">' + product.service_name + '</option>');
            });


            document.getElementById('myRow' + i).style.display = 'none';

            document.getElementById('product_rate' + i).value = "";
            document.getElementById('discount' + i).value = "";
            document.getElementById('discount_value' + i).value = "";
            document.getElementById('vat_percent' + i).value = "";
            document.getElementById('vat_value' + i).value = "";
            document.getElementById('total_price' + i).value = "";
            document.getElementById('total_discount' + i).value = "";
            document.getElementById('all_discount' + i).value = "";

        }
        document.getElementById('myRow1').style.display = 'table-row';

        document.getElementById('discount').value = ""
        document.getElementById('total_discount_ammount').value = ""
        document.getElementById('total_vat_amnt').value = ""
        document.getElementById('grandTotal').value = ""
        document.getElementById('date').value = ""
        document.getElementById('details').value = ""
        document.getElementById('Total').value = ""
        document.getElementById('customer_id').value = ""

        var $customerDropdown = $('#customer_id');
        $customerDropdown.empty();
        $customerDropdown.append('<option value="" disabled selected>Select Customer</option>'); // Add default option
        $.each(customers, function(index, customer) {
            $customerDropdown.append('<option value="' + customer.customer_id + '">' + customer.customer_name + '</option>');
        });
    }

    function printRawHtml(view) {


        $(view).print({

            deferred: $.Deferred().done(function() {
                window.location.reload();
            })
        });
    }

    function save_customer() {
        var name  = document.getElementById('mc_customer_name').value.trim();
        var phone = document.getElementById('mc_customer_phone').value.trim();

        if (!name) {
            alert("Customer name shouldn't be empty");
            return;
        }

        $.ajax({
            url: $('#base_url').val() + 'invoice/invoice/save_customer',
            type: 'POST',
            data: { customer_name: name, customer_phone: phone },
            success: function(response) {
                var result = JSON.parse(response);
                customers = result.all_customer;

                var $customerDropdown = $('#customer_id');
                $customerDropdown.empty();
                $customerDropdown.append('<option value="" disabled selected>Select Customer</option>');
                $.each(customers, function(index, customer) {
                    $customerDropdown.append('<option value="' + customer.customer_id + '">' + customer.customer_name + '</option>');
                });
                $customerDropdown.val(result.inserted_id);

                alert("Customer saved successfully");
                $('#customerModel').modal('hide');
                document.getElementById('mc_customer_name').value  = '';
                document.getElementById('mc_customer_phone').value = '';
            },
            error: function(error) {
                console.log(error);
            }
        });
    }
</script>

<!-- Add Customer Modal -->
<div id="customerModel" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Add New Customer</h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Customer Name <i class="text-danger">*</i></label>
                    <input type="text" class="form-control" id="mc_customer_name" placeholder="Customer Name" />
                </div>
                <div class="form-group">
                    <label>Phone Number</label>
                    <input type="text" class="form-control" id="mc_customer_phone" placeholder="Phone Number" />
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="save_customer()">Save</button>
            </div>
        </div>
    </div>
</div>