<script src="<?php echo base_url() ?>my-assets/js/admin_js/purchase.js" type="text/javascript"></script>

<style>
    .product_field {
        width: 200px;
    }

    .field {
        width: 30px;
    }

    .unit {
        width: 70px;
    }

    .qty {
        width: 100px;
    }

    .rate {
        width: 150px;
    }
</style>
<div class="row">
    <div class="col-sm-12">
        <div class="panel panel-bd lobidrag">
            <div class="panel-heading" id="style12">
                <div class="panel-title">
                    <h4 id="title"><?php echo $title; ?></h4>
                </div>
            </div>

            <div class="panel-body">

                <div class="row">

                    <div class="col-sm-3">
                        <div class="form-group row">
                            <label class="col-sm-5 col-form-label">Return Date <i class="text-danger">*</i></label>
                            <div class="col-sm-7">
                                <input type="text" class="form-control datepicker" name="return_date" id="rdate"
                                    value="<?php echo date('Y-m-d'); ?>" required>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-3">
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Branch <i class="text-danger">*</i></label>
                            <div class="col-sm-8">
                                <select class="form-control" id="branch" name="branch" required
                                    onchange="getSalesOrderDropdown()"></select>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-3" id="showorderno">
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Invoice Id</label>
                            <div class="col-sm-8">
                                <select class="form-control" id="invoice_id" name="invoice_id"
                                    onchange="getSalesOrderDetails()"></select>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-3" id="showorderno2">
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Invoice Id</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="invoice_id1" readonly>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-3">
                        <div class="form-group row">
                            <label class="col-sm-5 col-form-label">Invoice Date <i class="text-danger">*</i></label>
                            <div class="col-sm-7">
                                <input type="text" class="form-control datepicker" name="sale_date" id="sale_date"
                                    value="<?php echo date('Y-m-d'); ?>" readonly>
                            </div>
                        </div>
                    </div>

                </div>



                <div class="row">



                    <div class="col-sm-6">
                        <div class="form-group row">
                            <label for="supplier_sss" class="col-sm-4 col-form-label">Invoice Type
                                <i class="text-danger">*</i>
                            </label>
                            <div class="col-sm-6">
                                <select class="form-control" id="invoicetype" required name="invoicetype" tabindex="3" onchange="incidetTypechange()" disabled>
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
                            <label for="supplier_sss" class="col-sm-4 col-form-label">Incident Type
                                <i class="text-danger">*</i>
                            </label>
                            <div class="col-sm-6">
                                <select class="form-control" id="incidenttype" required name="incidenttype" tabindex="3" disabled>
                                    <option value=""></option>
                                    <option value="1">Retail</option>
                                    <option value="2">Wholesale</option>

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
                                <select name="customer_id" id="customer_id" class="form-control " required="" tabindex="1" disabled>
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
                            <label for="supplier_sss" class="col-sm-4 col-form-label">Salesman
                            </label>
                            <div class="col-sm-6">
                                <select name="employee_id" id="employee_id" class="form-control " tabindex="1" disabled>
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
                                <th class="text-center product_field">Product<i
                                        class="text-danger">*</i></th>
                                <th class="text-center">Store<i class="text-danger">*</i>
                                </th>
                                <th class="text-center">Batch<i class="text-danger">*</i>
                                </th>
                                <th class="text-center ">Unit </th>
                                <th class="text-center ">Av.Qty</th>
                                <th class="text-center ">Qty<i
                                        class="text-danger">*</i></th>
                                <th class="text-center ">Return Qty<i
                                        class="text-danger">*</i></th>
                                <th class="text-center ">Return Store<i
                                        class="text-danger">*</i></th>
                                <th class="text-center ">Deduction%<i
                                        class="text-danger">*</i></th>


                                <th class="text-center vathidden" id="vathidden">VAT.val</th>


                                <th class="text-center ">Total</th>


                            </tr>
                        </thead>
                        <tbody id="addinvoiceItem">
                            <tr id="myRow1">
                                <td class="product_field">

                                    <select name="product[]" class="form-control" id="product1" tabindex="1" onchange="product_search(1,'product')" disabled>
                                        <option value="">Select Product</option>
                                        <?php foreach ($products as $services) {
                                            echo $services['id']; ?>
                                            <option value="<?php echo $services['id']; ?>"><?php echo $services['product_name']; ?></option>

                                        <?php  }   ?>
                                    </select>
                                    <input type="hidden" id="mconversion_ratio1" />
                                    <input type="hidden" id="mastercost_price1" />
                                    <input type="hidden" id="bd1" />
                                    <input type="hidden" id="ad1" />
                                    <input type="hidden" id="isstock1" />
                                    <input type="hidden" id="invoicedetail1" />




                                </td>

                                <td class="rate">
                                    <select class="form-control" id="store1" name="store[]" tabindex="3" onchange="product_search(1,'store')" disabled>
                                        <option value=""></option>
                                    </select>
                                </td>
                                <td class="rate">
                                    <select class="form-control" id="batch1" name="batch[]" tabindex="3" onchange="product_search(1,'batch')" disabled>
                                        <option value=""></option>
                                    </select>
                                </td>

                                <td class="qty">
                                    <select class="form-control" id="unit1" required name="unit1" onchange="product_search(1,'unit')" tabindex="3" disabled>
                                        <option value=""></option>
                                    </select>
                                    <input type="hidden" id="conversionid1" />
                                    <input type="hidden" id="conversiontype1" />
                                    <input type="hidden" id="conversion_ratio1" />
                                </td>
                                <td class="qty">
                                    <input type="hidden" name="code[]" onkeyup="product_search(1,'code');"
                                        class="total_qntt_1 form-control text-right"
                                        id="code1" placeholder="0.00" min="0" readonly />
                                    <span id='codetype1' style="margin-left:5px"></span>
                                </td>



                                <td class="qty">
                                    <input type="text" name="product_quantity[]" id="qty1" min="0" class="form-control text-right store_cal_1" onkeyup="calculate_sum(1);" onchange="calculate_sum(1);" placeholder="0.00" value="" tabindex="6" disabled />
                                    <input type="hidden" name="product_rate[]" onkeyup="calculate_sum(1);" onchange="calculate_sum(1);" id="product_rate1" class="form-control product_rate_1 text-right" placeholder="0.00" value="" min="0" tabindex="7" />
                                    <input type="hidden" name="discount_per[]" onkeyup="calculate_sum(1);" onchange="calculate_sum(1);" id="discount1" class="form-control discount_1 text-right" min="0" tabindex="11" placeholder="0.00" />
                                    <input type="hidden" value="<?php echo $discount_type ?>" name="discount_type" id="discount_type">
                                    <input type="hidden" name="discountvalue[]" id="discount_value1" class="form-control text-right discount_value_1 total_discount_val" min="0" tabindex="12" placeholder="0.00" readonly />

                                </td>
                                <td class="rate">


                                    <input type="text" name="product_quantity[]" id="rqty1" min="0" class="form-control text-right store_cal_1" onkeyup="calculate_sum(1);" onchange="calculate_sum(1);" placeholder="0.00" value="" tabindex="6" />


                                </td>

                                <td class="qty">
                                    <select class="form-control" id="rstore1" name="store[]" tabindex="3">
                                        <option value=""></option>
                                    </select>

                                </td>
                                <td class="qty">

                                    <input type="text" name="product_quantity[]" id="rdeduction1" min="0" class="form-control text-right store_cal_1" placeholder="0.00" value="" tabindex="6" onkeyup="calculate_sum(1);" />


                                </td>


                                <!-- VAT  start-->

                                <td class="rate vathidden">
                                    <input type="hidden" name="vatpercent[]" onkeyup="calculate_sum(1);" onchange="calculate_sum(1);" id="vat_percent1" class="form-control vat_percent_1 text-right" min="0" tabindex="13" placeholder="0.00" />
                                    <input type="text" name="vatvalue[]" id="vat_value1" class="form-control vat_value1 text-right total_vatamnt" min="0" tabindex="14" placeholder="0.00" readonly />
                                </td>

                                <!-- VAT  end-->
                                <td class="product_field">
                                    <input class="form-control total_price text-right total_price_1" type="text" name="total_price[]" id="total_price1" value="0.00" readonly="readonly" />

                                    <input type="hidden" id="total_discount1" class="" />
                                    <input type="hidden" id="all_discount1" class="total_discount dppr" name="discount_amount[]" />
                                </td>



                            </tr>

                            <?php
                            for ($i = 2; $i <= 20; $i++) {
                            ?>
                                <tr id="myRow<?php echo $i; ?>">
                                    <td class="product_field">
                                        <select name="product[]" class="form-control" id="product<?php echo $i; ?>" tabindex="1" onchange="product_search(<?php echo $i; ?>, 'product')" disabled>
                                            <option value="">Select Product</option>
                                            <?php foreach ($products as $services) { ?>
                                                <option value="<?php echo $services['id']; ?>"><?php echo $services['product_name']; ?></option>
                                            <?php } ?>
                                        </select>
                                        <input type="hidden" id="mconversion_ratio<?php echo $i; ?>" />
                                        <input type="hidden" id="mastercost_price<?php echo $i; ?>" />

                                        <input type="hidden" id="bd<?php echo $i; ?>" />
                                        <input type="hidden" id="ad<?php echo $i; ?>" />
                                        <input type="hidden" id="isstock<?php echo $i; ?>" />
                                        <input type="hidden" id="invoicedetail<?php echo $i; ?>" />


                                    </td>



                                    <td class="rate">
                                        <select class="form-control" id="store<?php echo $i; ?>" name="store[]" tabindex="3" onchange="product_search(<?php echo $i; ?>, 'store')" disabled>
                                            <option value=""></option>
                                        </select>
                                    </td>

                                    <td class="rate">
                                        <select class="form-control" id="batch<?php echo $i; ?>" name="batch[]" tabindex="3" onchange="product_search(<?php echo $i; ?>, 'batch')" disabled>
                                            <option value=""></option>
                                        </select>
                                    </td>



                                    <td class="qty">
                                        <select class="form-control" id="unit<?php echo $i; ?>" required name="unit<?php echo $i; ?>" onchange="product_search(<?php echo $i; ?>,'unit')" tabindex="3" disabled>
                                            <option value=""></option>
                                        </select>
                                        <input type="hidden" id="conversionid<?php echo $i; ?>" />
                                        <input type="hidden" id="conversiontype<?php echo $i; ?>" />
                                        <input type="hidden" id="conversion_ratio<?php echo $i; ?>" />
                                    </td>

                                    <td class="qty">
                                        <input type="hidden" name="code[]" onkeyup="product_search(<?php echo $i; ?>, 'code');" class="total_qntt_1 form-control text-right" id="code<?php echo $i; ?>" placeholder="0.00" min="0" readonly />
                                        <span id='codetype<?php echo $i; ?>' style="margin-left:5px"></span>

                                    </td>

                                    <td class="qty">
                                        <input type="text" name="product_quantity[]" id="qty<?php echo $i; ?>" min="0" class="form-control text-right store_cal_1" onkeyup="calculate_sum(<?php echo $i; ?>);" onchange="calculate_sum(<?php echo $i; ?>);" placeholder="0.00" value="" tabindex="6" disabled />
                                        <input type="hidden" name="product_rate[]" onkeyup="calculate_sum(<?php echo $i; ?>);" onchange="calculate_sum(<?php echo $i; ?>);" id="product_rate<?php echo $i; ?>" class="form-control product_rate_1 text-right" placeholder="0.00" value="" min="0" tabindex="7" />
                                        <input type="hidden" name="discount_per[]" onkeyup="calculate_sum(<?php echo $i; ?>);" onchange="calculate_sum(<?php echo $i; ?>);" id="discount<?php echo $i; ?>" class="form-control discount_1 text-right" min="0" tabindex="11" placeholder="0.00" />
                                        <input type="hidden" name="discountvalue[]" id="discount_value<?php echo $i; ?>" class="form-control text-right discount_value_1 total_discount_val" min="0" tabindex="12" placeholder="0.00" readonly />
                                        <input type="hidden" value="<?php echo $discount_type ?>" name="discount_type" id="discount_type">
                                    </td>

                                    <td class="rate">


                                        <input type="text" name="product_quantity[]" id="rqty<?php echo $i; ?>" min="0" class="form-control text-right store_cal_1" onkeyup="calculate_sum(<?php echo $i; ?>);" onchange="calculate_sum(<?php echo $i; ?>);" placeholder="0.00" value="" tabindex="6" />


                                    </td>

                                    <td class="qty">
                                        <select class="form-control" id="rstore<?php echo $i; ?>" name="store[]" tabindex="3">
                                            <option value=""></option>
                                        </select>

                                    </td>
                                    <td class="qty">

                                        <input type="text" name="product_quantity[]" id="rdeduction<?php echo $i; ?>" min="0" class="form-control text-right store_cal_1" placeholder="0.00" value="" tabindex="6" onkeyup="calculate_sum(<?php echo $i; ?>);" />


                                    </td>

                                    <!-- VAT start -->

                                    <td class="rate vathidden">
                                        <input type="hidden" name="vatpercent[]" onkeyup="calculate_sum(<?php echo $i; ?>);" onchange="calculate_sum(<?php echo $i; ?>);" id="vat_percent<?php echo $i; ?>" class="form-control vat_percent_1 text-right" min="0" tabindex="13" placeholder="0.00" />
                                        <input type="text" name="vatvalue[]" id="vat_value<?php echo $i; ?>" class="form-control vat_value1 text-right total_vatamnt" min="0" tabindex="14" placeholder="0.00" readonly />
                                    </td>

                                    <!-- VAT end -->

                                    <td class="product_field">
                                        <input class="form-control total_price text-right total_price_1" type="text" name="total_price[]" id="total_price<?php echo $i; ?>" value="0.00" readonly="readonly" />
                                        <input type="hidden" id="total_discount<?php echo $i; ?>" class="" />
                                        <input type="hidden" id="all_discount<?php echo $i; ?>" class="total_discount dppr" name="discount_amount[]" />
                                    </td>


                                </tr>
                            <?php
                            }
                            ?>

                        </tbody>
                        <tfoot>


                            <tr>
                                <td colspan="10" class="text-right vathidden"><b><?php echo display('ttl_val') ?>:</b></td>
                                <td colspan="9" class="text-right vatshow"><b><?php echo display('ttl_val') ?>:</b></td>


                                <td class="text-right">

                                    <input type="text" id="total_vat_amnt" class="form-control text-right" name="total_vat_amnt" value="0.00" readonly="readonly" />

                                </td>
                                <td> </td>
                            </tr>

                            <tr>
                                <td colspan="10" class="text-right vathidden"><b><?php echo display('grand_total') ?>:</b></td>
                                <td colspan="9" class="text-right vatshow"><b><?php echo display('grand_total') ?>:</b></td>


                                <td class="text-right">
                                    <input type="hidden" name="baseUrl" class="baseUrl" value="<?php echo base_url(); ?>" />

                                    <input type="hidden" id="Total" class="text-right form-control" name="total" value="0.00" readonly="readonly" />

                                    <input type="hidden" id="discount" class="text-right form-control discount total_discount_val" onkeyup="calculate_store(1)" name="discount" placeholder="0.00" value="" />

                                    <input type="hidden" id="total_discount_ammount" class="form-control text-right" name="total_discount" value="0.00" readonly="readonly" />
                                    <input type="hidden" id="total_vat_amnt" class="form-control text-right" name="total_vat_amnt" value="0.00" readonly="readonly" />

                                    <input type="text" id="grandTotal" class="text-right form-control grandTotalamnt" name="grand_total_price" placeholder="0.00" value="00" readonly />
                                </td>
                                <td> </td>
                            </tr>

                        </tfoot>
                    </table>
                    <input type="hidden" name="finyear" value="<?php echo financial_year(); ?>">
                    <p hidden id="pay-amount"></p>
                    <p hidden id="change-amount"></p>
                    <div class="col-sm-6 table-bordered p-20">
                        <div id="adddiscount" class="display-none">
                            <div class="row">

                                <!-- Payment Type -->
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="payments" class="col-form-label">
                                            <?php echo display('payment_type'); ?> <i class="text-danger">*</i>
                                        </label>
                                        <select name="multipaytype[]" class="form-control" id="your_dropdown_id" tabindex="1">
                                            <option value="">Select an option</option>
                                            <?php foreach ($all_pmethod as $services) { ?>
                                                <option value="<?php echo $services['id']; ?>">
                                                    <?php echo $services['name']; ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>

                                <!-- Details -->
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="details" class="col-form-label">
                                            <?php echo display('details'); ?>
                                        </label>
                                        <textarea
                                            class="form-control"
                                            tabindex="4"
                                            id="details"
                                            name="purchase_details"
                                            placeholder="<?php echo display('details'); ?>"
                                            rows="3"></textarea>
                                    </div>
                                </div>

                            </div>
                        </div>
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
echo "let products=" . json_encode($products) . ";";
echo "let stores=" . json_encode($store_list) . ";";
echo "let customers=" . json_encode($all_customer) . ";";
echo "let employees=" . json_encode($all_employee) . ";";
echo "let usertype=" . json_encode($this->session->userdata('user_level2')) . ";";
echo "let batches=" . json_encode($batches) . ";";
echo "let units=" . json_encode($units) . ";";

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

    $(document).ready(function() {
        for (let j = 2; j <= 20; j++) {
            document.getElementById('myRow' + j).style.display = 'none';
        }

        document.querySelectorAll('.vathidden').forEach(el => {
            el.style.display = 'none';
        });

        document.getElementById("showorderno2").style.display = "none";

        if (id != null) {
            $.ajax({
                url: $('#base_url').val() + 'invoice/invoice/getSalesReturnById',
                type: 'POST',
                data: {
                    id: id,
                    type2: type2
                },
                success: function(response) {
                    debugger
                    var sales = JSON.parse(response);
                    console.log(sales)
                    document.getElementById('sale_date').value = sales[0].date;
                    document.getElementById('rdate').value = sales[0].rdate;

                    document.getElementById('details').value = sales[0].details;

                    document.getElementById("showorderno").style.display = "none";
                    document.getElementById("showorderno2").style.display = "block";
                    document.getElementById('invoice_id1').value = sales[0].sale_id;







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
                    $employeeDropdown.append('<option value="" disabled selected>Select Employee</option>'); // Add default option
                    $.each(employees, function(index, employee) {
                        $employeeDropdown.append('<option value="' + employee.id + '">' + employee.first_name + " " + employee.last_name + '</option>');
                    });
                    $employeeDropdown.val(sales[0].employee_id)

                    var $paymentDropdown = $('#your_dropdown_id');
                    $paymentDropdown.empty();
                    $paymentDropdown.append('<option value="" disabled selected>Select Supplier</option>'); // Add default option
                    $.each(pmethods, function(index, supplier) {
                        $paymentDropdown.append('<option value="' + supplier.id + '">' + supplier.name + '</option>');
                    });
                    $paymentDropdown.val(sales[0].payment_type)

                    var $incidenttypeDropdown = $('#incidenttype');
                    $incidenttypeDropdown.empty();
                    $incidenttypeDropdown.append('<option value="" disabled selected>Select Incident Type</option>'); // Add default option
                    $incidenttypeDropdown.append('<option value="1">Retail</option>');
                    $incidenttypeDropdown.append('<option value="2">Whole Sale</option>');
                    $incidenttypeDropdown.val(sales[0].incidenttype)

                    var $invoiceTypeDropdown = $('#invoicetype');
                    $invoiceTypeDropdown.empty(); // Clear existing options
                    $invoiceTypeDropdown.append('<option value="" disabled selected>Select Invoice Type</option>');
                    $invoiceTypeDropdown.append('<option value="cash">Cash</option>');
                    $invoiceTypeDropdown.append('<option value="credit">Credit</option>');
                    $invoiceTypeDropdown.append('<option value="cash_vat">Cash VAT</option>');
                    $invoiceTypeDropdown.append('<option value="credit_vat">Credit VAT</option>');
                    $invoiceTypeDropdown.val(sales[0].invoicetype);

                    document.getElementById('total_discount_ammount').value = sales[0].total_discount_ammount;
                    document.getElementById('total_vat_amnt').value = sales[0].total_vat_amnt;
                    document.getElementById('grandTotal').value = sales[0].grandTotal;
                    document.getElementById('Total').value = sales[0].total;
                    document.getElementById('discount').value = sales[0].discount;

                    // count = 1;
                    for (let i = 0; i < sales.length; i++) {
                        let a = i + 1;
                        document.getElementById('myRow' + a).style.display = 'table-row';
                        getActiveProduct(sales[i].product, a);
                        getActiveStore(sales[i].store, sales[i].rstore, a);

                        document.getElementById('qty' + a).value = sales[i].quantity;
                        document.getElementById('rqty' + a).value = sales[i].rqty;
                        document.getElementById('rdeduction' + a).value = sales[i].rdeduction;
                        document.getElementById('unit' + a).value = sales[i].unit;
                        document.getElementById('code' + a).value = sales[i].avstock;
                        document.getElementById('product_rate' + a).value = sales[i].product_rate;
                        document.getElementById('discount' + a).value = sales[i].discount2;
                        document.getElementById('discount_value' + a).value = sales[i].discount_value;
                        document.getElementById('mastercost_price' + a).value = sales[i].cost_price;
                        document.getElementById('invoicedetail' + a).value = sales[i].invoice_detail_id;

                        // if (vtinfo.ischecked == 1) {
                        //     document.getElementById('vat_percent' + a).value = sales[i].vat_percent;
                        // }
                        if (sales[0].invoicetype == 'cash_vat' ||
                            sales[0].invoicetype == 'credit_vat' ||
                            sales[0].invoicetype == 'svat') {
                            document.getElementById('vat_percent' + a).value = sales[i].vat_percent;
                            document.querySelectorAll('.vathidden').forEach(el => {
                                el.style.display = 'table-cell';
                            });
                            document.querySelectorAll('.vatshow').forEach(el => {
                                el.style.display = 'none';
                            });
                        } else {
                            document.getElementById('vat_percent' + a).value = 0;

                        }
                        document.getElementById('vat_value' + a).value = sales[i].vat_value;
                        document.getElementById('total_price' + a).value = sales[i].total_price;
                        document.getElementById('total_discount' + a).value = sales[i].total_discount;
                        document.getElementById('all_discount' + a).value = sales[i].all_discount;

                        getActiveSubUnitEdit(sales[i].product, a, sales[i].unit, sales[i].conversion_id,
                            sales[i].conversion_ratio, sales[i].convertiontype,
                            sales[i].avstock)

                        // getBatchDropdown(batches, a, sales[i].batch)
                        getBatchDropdown(batches, a, sales[i].batch, sales[i].product, sales[i].batchtype)



                        count = count + 1;
                    }
                },
                error: function(error) {
                    console.log(error);
                }
            });
        } else {
            getBranchDropdown(0);

        }
    });

    function getSalesOrderDetails() {
        $.ajax({
            url: $('#base_url').val() + 'invoice/invoice/getSaleById',
            type: 'POST',
            data: {
                id: document.getElementById("invoice_id").value,
                type2: type2
            },
            success: function(response) {
                clearDetails()
                var sales = JSON.parse(response);
                console.log(sales)
                document.getElementById('sale_date').value = sales[0].date;
                document.getElementById('details').value = sales[0].details;

                // getBranchDropdown(sales[0].branch);


                var $customerDropdown = $('#customer_id');
                $customerDropdown.empty();
                $customerDropdown.append('<option value="" disabled selected>Select Customer</option>'); // Add default option
                $.each(customers, function(index, customer) {
                    $customerDropdown.append('<option value="' + customer.customer_id + '">' + customer.customer_name + '</option>');
                });
                $customerDropdown.val(sales[0].customer_id)

                var $employeeDropdown = $('#employee_id');
                $employeeDropdown.empty();
                $employeeDropdown.append('<option value="" disabled selected>Select Employee</option>'); // Add default option
                $.each(employees, function(index, employee) {
                    $employeeDropdown.append('<option value="' + employee.id + '">' + employee.first_name + " " + employee.last_name + '</option>');
                });
                $employeeDropdown.val(sales[0].employee_id)



                var $incidenttypeDropdown = $('#incidenttype');
                $incidenttypeDropdown.empty();
                $incidenttypeDropdown.append('<option value="" disabled selected>Select Incident Type</option>'); // Add default option
                $incidenttypeDropdown.append('<option value="1">Retail</option>');
                $incidenttypeDropdown.append('<option value="2">Whole Sale</option>');
                $incidenttypeDropdown.val(sales[0].incidenttype)

                var $invoiceTypeDropdown = $('#invoicetype');
                $invoiceTypeDropdown.empty(); // Clear existing options
                $invoiceTypeDropdown.append('<option value="" disabled selected>Select Invoice Type</option>');
                $invoiceTypeDropdown.append('<option value="cash">Cash</option>');
                $invoiceTypeDropdown.append('<option value="credit">Credit</option>');
                $invoiceTypeDropdown.append('<option value="cash_vat">Cash VAT</option>');
                $invoiceTypeDropdown.append('<option value="credit_vat">Credit VAT</option>');
                $invoiceTypeDropdown.val(sales[0].invoicetype);


                var $paymentDropdown = $('#your_dropdown_id');
                $paymentDropdown.empty();
                $paymentDropdown.append('<option value="" disabled selected>Select Supplier</option>'); // Add default option
                $.each(pmethods, function(index, supplier) {
                    $paymentDropdown.append('<option value="' + supplier.id + '">' + supplier.name + '</option>');
                });
                $paymentDropdown.val(sales[0].payment_type)

                document.getElementById('total_discount_ammount').value = sales[0].total_discount_ammount;
                document.getElementById('total_vat_amnt').value = sales[0].total_vat_amnt;
                // document.getElementById('grandTotal').value = sales[0].grandTotal;
                // document.getElementById('Total').value = sales[0].total;
                document.getElementById('discount').value = sales[0].discount;

                // count = 1;
                for (let i = 0; i < sales.length; i++) {
                    let a = i + 1;
                    document.getElementById('myRow' + a).style.display = 'table-row';
                    getActiveProduct(sales[i].product, a);
                    getActiveStore(sales[i].store, sales[i].store, a);

                    document.getElementById('qty' + a).value = sales[i].quantity;
                    document.getElementById('unit' + a).value = sales[i].unit;
                    document.getElementById('code' + a).value = sales[i].avstock;
                    document.getElementById('product_rate' + a).value = sales[i].product_rate;
                    document.getElementById('discount' + a).value = sales[i].discount2;
                    document.getElementById('discount_value' + a).value = sales[i].discount_value;
                    document.getElementById('mastercost_price' + a).value = sales[i].cost_price;

                    // if (vtinfo.ischecked == 1) {
                    //     document.getElementById('vat_percent' + a).value = sales[i].vat_percent;
                    // }
                    if (sales[0].invoicetype == 'cash_vat' ||
                        sales[0].invoicetype == 'credit_vat' ||
                        sales[0].invoicetype == 'svat') {
                        document.getElementById('vat_percent' + a).value = sales[i].vat_percent;
                        document.querySelectorAll('.vathidden').forEach(el => {
                            el.style.display = 'table-cell';
                        });
                        document.querySelectorAll('.vatshow').forEach(el => {
                            el.style.display = 'none';
                        });
                    } else {
                        document.getElementById('vat_percent' + a).value = 0;
                        document.querySelectorAll('.vathidden').forEach(el => {
                            el.style.display = 'none';
                        });
                        document.querySelectorAll('.vatshow').forEach(el => {
                            el.style.display = 'table-cell';
                        });

                    }
                    document.getElementById('vat_value' + a).value = sales[i].vat_value;
                    document.getElementById('total_price' + a).value = sales[i].total_price;
                    document.getElementById('total_discount' + a).value = sales[i].total_discount;
                    document.getElementById('all_discount' + a).value = sales[i].all_discount;
                    document.getElementById('rdeduction' + a).value = 100;

                    getActiveSubUnitEdit(sales[i].product, a, sales[i].unit, sales[i].conversion_id,
                        sales[i].conversion_ratio, sales[i].convertiontype,
                        sales[i].avstock)

                    // getBatchDropdown(batches, a, sales[i].batch)
                    getBatchDropdown(batches, a, sales[i].batch, sales[i].product, sales[i].batchtype)



                    count = count + 1;
                }
            },
            error: function(error) {
                console.log(error);
            }
        });
    }

    function addInputField(t) {
        // if (count < 11) {
        document.getElementById('myRow' + count).style.display = 'table-row';
        getActiveStore(0, 0, count);
        getActiveProduct(0, count)
        count = count + 1;

    }

    function incidetTypechange() {

        if (document.getElementById('invoicetype').value == 'cash_vat' ||
            document.getElementById('invoicetype').value == 'credit_vat' ||
            document.getElementById('invoicetype').value == 'svat') {
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

    function product_search(item, name) {

        if (name === "product") {
            document.getElementById('qty' + item).value = "";
            document.getElementById('code' + item).value = "";
            document.getElementById('unit' + item).value = "";
            document.getElementById('product_rate' + item).value = "";
            document.getElementById('discount' + item).value = "";
            document.getElementById('discount_value' + item).value = "";
            document.getElementById('vat_percent' + item).value = "";
            document.getElementById('vat_value' + item).value = "";
            document.getElementById('total_price' + item).value = "";
            document.getElementById('total_discount' + item).value = "";
            document.getElementById('all_discount' + item).value = "";
            // var $storeDropdown = $('#store' + item);
            // $storeDropdown.empty();
            // document.getElementById('code' + item).value = "";
            // document.getElementById('qty' + item).value = "";
            getStoresDropdown(stores, item);
            $.ajax({
                url: $('#base_url').val() + 'stock/stock/getproduct',
                type: 'POST',
                data: {
                    prodid: document.getElementById('product' + item).value.toString(),
                },
                success: function(response) {
                    let product = JSON.parse(response);
                    $.ajax({
                        url: $('#base_url').val() + 'stock/stock/getproductSubUnitPrimary',
                        type: 'POST',
                        data: {
                            prodid: document.getElementById('product' + item).value.toString(),
                        },
                        success: function(response2) {
                            if (response2 != "null") {
                                let product2 = JSON.parse(response2);
                                document.getElementById('mconversion_ratio' + item).value = product2[0].conversion_ratio
                                document.getElementById('bd' + item).value = product[0].unit_name
                                document.getElementById('ad' + item).value = product2[0].unit_name
                            } else {
                                document.getElementById('mconversion_ratio' + item).value = ""
                                document.getElementById('bd' + item).value = ""
                                document.getElementById('ad' + item).value = ""
                            }

                            avStock(item, document.getElementById('product' + item).value, product[0].store, 0, "", "")

                            //   document.getElementById('unit' + item).value = product[0].unit;
                        },
                        error: function(error) {
                            console.log(error)
                        }
                    });
                    getActiveStore(product[0].store, 0, item);
                    getBatchDropdown(batches, item, 0, document.getElementById('product' + item).value.toString(), product[0].batchtype);
                    // avStock(item, document.getElementById('product' + item).value, product[0].store, 0, "", "")
                    getActiveSubUnit(document.getElementById('product' + item).value, item)

                    document.getElementById('unit' + item).value = product[0].unit;
                    document.getElementById('product_rate' + item).value = product[0].cost_price;
                    document.getElementById('mastercost_price' + item).value = product[0].cost_price;
                    // if (vtinfo.ischecked == 1) {
                    //     document.getElementById('vat_percent' + item).value = product[0].product_vat;
                    // }
                    if (document.getElementById('invoicetype').value == 'cash_vat' ||
                        document.getElementById('invoicetype').value == 'credit_vat' ||
                        document.getElementById('invoicetype').value == 'svat') {
                        document.getElementById('vat_percent' + item).value = product[0].product_vat;
                    } else {
                        document.getElementById('vat_percent' + item).value = 0;

                    }
                    //document.getElementById('vat_value' + item).value = 0;



                },
                error: function(error) {
                    console.log(error)
                }
            });
        }


        if (name === "batch") {
            avStock(item, document.getElementById('product' + item).value, document.getElementById('store' + item).value, document.getElementById('batch' + item).value, "", "")
            getActiveSubUnit(document.getElementById('product' + item).value, item)

        }


        if (name === "store") {
            avStock(item, document.getElementById('product' + item).value, document.getElementById('store' + item).value, document.getElementById('batch' + item).value, "", "")
            getActiveSubUnit(document.getElementById('product' + item).value, item)

        }

        if (name === "unit") {

            let select = document.getElementById('unit' + item);
            let selectedText = select.options[select.selectedIndex].text;
            convertion(item, document.getElementById('product' + item).value, document.getElementById('unit' + item).value, selectedText)
            // avStock(item,document.getElementById('product' + item).value,document.getElementById('store' + item).value,0)
            // getActiveSubUnit(document.getElementById('product' + item).value,item)

        }
    }

    function avStock(item, product, store, batch, convertiontype, conversion_ratio) {
        document.getElementById('code' + item).value = "";
        document.getElementById('qty' + item).value = "";
        $.ajax({
            url: $('#base_url').val() + 'stock/stock/avg_avstock',
            type: 'POST',
            data: {
                prodid: product,
                storeid: store,
                batch: batch
            },
            success: function(response) {
                let stock = JSON.parse(response);
                let el = document.getElementById('codetype' + item);
                el.style.color = 'green';
                el.style.fontWeight = 'bold';
                el.innerHTML = ""
                let select = document.getElementById('unit' + item);
                let selectedText = select.options[select.selectedIndex].text;


                if (convertiontype == "*") {
                    document.getElementById('code' + item).value = (stock[0].avgqty * conversion_ratio).toFixed(2)

                    let sub = (stock[0].avgqty * conversion_ratio);
                    let sub2 = Math.floor((sub).toLocaleString());
                    if (isNaN(sub2)) {
                        sub = Number(sub).toFixed(6);
                        el.innerHTML = (Math.floor(sub)).toLocaleString() + " " + selectedText
                    } else {
                        el.innerHTML = sub2 + " " + selectedText

                    }

                } else if (convertiontype == "/") {
                    document.getElementById('code' + item).value = (stock[0].avgqty / conversion_ratio).toFixed(2)
                    let sub = stock[0].avgqty / conversion_ratio;
                    el.innerHTML = (Math.floor(sub)).toLocaleString() + " " + selectedText

                } else if (convertiontype == "+") {
                    document.getElementById('code' + item).value = (stock[0].avgqty + conversion_ratio).toFixed(2)
                    let sub = stock[0].avgqty + conversion_ratio;
                    el.innerHTML = (Math.floor(sub)).toLocaleString() + " " + selectedText

                } else if (convertiontype == "-") {
                    document.getElementById('code' + item).value = (stock[0].avgqty - conversion_ratio).toFixed(2)
                    let sub = stock[0].avgqty - conversion_ratio;
                    el.innerHTML = (Math.floor(sub)).toLocaleString() + " " + selectedText

                } else {

                    if (document.getElementById('mconversion_ratio' + item).value != "") {

                        let totalcount = 0;
                        let mas = document.getElementById('mconversion_ratio' + item).value * stock[0].avgqty / document.getElementById('mconversion_ratio' + item).value;
                        let subcount = 0;
                        let sub = document.getElementById('mconversion_ratio' + item).value * stock[0].avgqty % document.getElementById('mconversion_ratio' + item).value;


                        let mas2 = Math.floor((mas).toLocaleString());
                        if (isNaN(mas2)) {
                            mas = Number(mas).toFixed(6);
                            totalcount = (Math.floor(mas)).toLocaleString()
                        } else {
                            totalcount = mas2

                        }

                        let sub2 = Math.floor((sub).toLocaleString());
                        if (isNaN(sub2)) {
                            sub = Number(sub).toFixed(6);
                            subcount = (Math.floor(sub)).toLocaleString()
                        } else {
                            subcount = sub2

                        }

                        document.getElementById('code' + item).value = stock[0].avgqty == null ? 0 : totalcount;


                        el.innerHTML = totalcount + document.getElementById('bd' + item).value + " " + subcount + document.getElementById('ad' + item).value;
                    } else {
                        document.getElementById('code' + item).value = stock[0].avgqty == null ? 0 : stock[0].avgqty;
                        el.innerHTML = (Math.floor(stock[0].avgqty)).toLocaleString() + " " + selectedText

                    }
                }
            },
            error: function(error) {
                console.log(error)
            }
        });
    }
    deletedsaleDetails = []

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

        $.ajax({
            type: "post",
            url: $('#base_url').val() + "/stock/stock/is_grnorgdnthere",
            data: {
                id: document.getElementById('invoicedetail' + num).value,
                invoicetype: 2
            },
            success: function(data) {
                var grnlength = JSON.parse(data);

                if (grnlength.length == 0) {

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
                    if (document.getElementById('invoicedetail' + num).value != 0) {
                        deletedsaleDetails.push(document.getElementById('invoicedetail' + num).value)
                    }
                    calculate_sum(num)

                } else {
                    alert("grn already added to this invoice item")
                }
            }
        });
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

        var quantity = $("#rqty" + sl).val();
        var discount = $("#discount" + sl).val();
        if (!$("#rdeduction" + sl).val()) {
            $("#rdeduction" + sl).val(100)
        }
        if ($("#rdeduction" + sl).val() > 100) {
            $("#rdeduction" + sl).val(100)
        }
        var rdeduction = $("#rdeduction" + sl).val();

        var dis_type = $("#discount_type").val();
        var price_item = $("#product_rate" + sl).val();
        var vat_percent = 0;
        if (document.getElementById('invoicetype').value == 'cash_vat' ||
            document.getElementById('invoicetype').value == 'credit_vat' ||
            document.getElementById('invoicetype').value == 'svat') {
            vat_percent = $("#vat_percent" + sl).val();



        }
        let qty = $("#qty" + sl).val();
        // let number = parseInt(value.replace(/,/g, ''), 10);


        // if (parseInt(quantity) > parseInt(qty)) {
        //     $("#rqty" + sl).val("");
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
                if (rdeduction && quantity > 0) {
                    let vat1 = $("#vat_value" + sl).val();
                    var vat1rdeductionval = +(vat1 * rdeduction / 100);
                    $("#vat_value" + sl).val(vat1rdeductionval);


                }
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


        if (rdeduction && quantity > 0) {
            let price1 = $("#total_price" + sl).val();
            var rdeductionval = +(price1 * rdeduction / 100);
            var temp = price1 - rdeductionval;
            $("#total_price" + sl).val(rdeductionval);


        }else{
            $("#total_price" + sl).val(0);

        }


        //Total Price
        $(".total_price").each(function() {
            isNaN(this.value) || 0 == this.value.length || (gr_tot += parseFloat(this.value))
        });
        // $(".discount").each(function() {
        //     isNaN(this.value) || 0 == this.value.length || (dis += parseFloat(this.value))
        // });
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

        $.each(products, function(index, product) {
            $productDropdown.append('<option value="' + product.id + '">' + product.product_name + '</option>');
        });

        if (productId > 0) {
            {
                $productDropdown.val(productId)
            }
        }
    }




    function getActiveStore(storeId, rstoreId, item) {
        var $storeDropdown = $('#store' + item);
        $storeDropdown.empty();
        $storeDropdown.append('<option value="" disabled selected>Select store</option>'); // Add default option


        var $storeDropdown1 = $('#rstore' + item);
        $storeDropdown1.empty();
        $storeDropdown1.append('<option value="" disabled selected>Select store</option>'); // Add default option

        if (storeId == 1) {
            $storeDropdown.append('<option value="1">N/A</option>');
            $storeDropdown1.append('<option value="1">N/A</option>');

        }

        $.each(stores, function(index, store) {
            $storeDropdown.append('<option value="' + store.id + '">' + store.name + '</option>');

            $storeDropdown1.append('<option value="' + store.id + '">' + store.name + '</option>');
        });

        if (storeId > 0) {
            {
                $storeDropdown.val(storeId)
            }
        }

        if (rstoreId > 0) {
            {
                $storeDropdown1.val(rstoreId)
            }
        }
    }

    function getStoresDropdown(stores, item) {
        var $storeDropdown = $('#store' + item);
        $storeDropdown.empty();
        $storeDropdown.append('<option value="" disabled selected>Select store</option>'); // Add default option

        $.each(stores, function(index, store) {
            $storeDropdown.append('<option value="' + store.id + '">' + store.name + '</option>');
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
                var $branchDropdown = $('#branch');
                $branchDropdown.empty();
                $branchDropdown.append('<option value="" disabled selected>Select Branch</option>'); // Add default option

                $.each(branches, function(index, branch) {
                    $branchDropdown.append('<option value="' + branch.id + '">' + branch.name + '</option>');
                    if (branch.default != 0) {
                        $branchDropdown.val(branch.id)
                        getSalesOrderDropdown()
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


    function getSalesOrderDropdown() {

        var base_url = $('#base_url').val();

        console.log(type2)
        console.log(document.getElementById("branch").value)

        console.log()
        $.ajax({
            type: "post",
            url: base_url + "invoice/invoice/getsalesidbybranch",
            data: {
                type2: type2,
                branch: document.getElementById("branch").value
            },
            success: function(data) {

                var salesorder = JSON.parse(data);
                console.log(salesorder)
                var $branchDropdown = $('#invoice_id');
                $branchDropdown.empty();
                $branchDropdown.append('<option value="" disabled selected>Select Sales Order</option>'); // Add default option

                $.each(salesorder, function(index, branch) {
                    $branchDropdown.append('<option value="' + branch.id + '">' + branch.sale_id + '</option>');

                });




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
                } else if (document.getElementById('your_dropdown_id').value == "") {
                    alert("Payment Type shouldn't be empty")
                    return
                } else if (document.getElementById('branch').value == "") {
                    alert("Branch shouldn't be empty")
                    return
                } else if (document.getElementById('rdate').value == "") {
                    alert("Return Date shouldn't be empty")
                    return
                } else if (document.getElementById('product' + i).value == "") {
                    alert("Product shouldn't be empty")
                    return
                } else if (document.getElementById('rstore' + i).value == "") {
                    alert("Store shouldn't be empty")
                    return

                } else if (document.getElementById('rqty' + i).value == "") {
                    alert("Quantity shouldn't be empty")
                    return

                } else
                if (document.getElementById('product_rate' + i).value == "") {
                    alert("Price shouldn't be empty")
                    return
                } else {
                    var dropdown = document.getElementById('product' + i);

                    let qty = 0;
                    let rqty = 0;

                    if (document.getElementById('conversiontype' + i).value == "+") {
                        qty = document.getElementById('qty' + i).value - document.getElementById('conversion_ratio' + i).value
                        rqty = document.getElementById('rqty' + i).value - document.getElementById('conversion_ratio' + i).value
                    } else
                    if (document.getElementById('conversiontype' + i).value == "-") {
                        qty = document.getElementById('qty' + i).value + document.getElementById('conversion_ratio' + i).value
                        rqty = document.getElementById('rqty' + i).value + document.getElementById('conversion_ratio' + i).value

                    } else
                    if (document.getElementById('conversiontype' + i).value == "*") {
                        qty = document.getElementById('qty' + i).value / document.getElementById('conversion_ratio' + i).value
                        rqty = document.getElementById('rqty' + i).value / document.getElementById('conversion_ratio' + i).value

                    } else
                    if (document.getElementById('conversiontype' + i).value == "/") {
                        qty = document.getElementById('qty' + i).value * document.getElementById('conversion_ratio' + i).value
                        rqty = document.getElementById('rqty' + i).value * document.getElementById('conversion_ratio' + i).value

                    } else {
                        qty = document.getElementById('qty' + i).value
                        rqty = document.getElementById('rqty' + i).value

                    }


                    arrItem.push({
                        product: document.getElementById('product' + i).value,
                        product_name: dropdown.options[dropdown.selectedIndex].text,
                        store: document.getElementById('store' + i).value,
                        quantity: qty,
                        product_rate: document.getElementById('product_rate' + i).value,
                        batch: document.getElementById('batch' + i).value,
                        discount: document.getElementById('discount' + i).value,
                        discount_value: document.getElementById('discount_value' + i).value,
                        vat_percent: document.getElementById('vat_percent' + i).value,
                        vat_value: document.getElementById('vat_value' + i).value,
                        total_price: document.getElementById('total_price' + i).value,
                        total_discount: document.getElementById('total_discount' + i).value,
                        all_discount: document.getElementById('all_discount' + i).value,
                        unit: document.getElementById('unit' + i).value,
                        conversionid: document.getElementById('conversionid' + i).value,
                        rqty: rqty,
                        rstore: document.getElementById('rstore' + i).value,
                        rdeduction: document.getElementById('rdeduction' + i).value,
                        invoicedetail: document.getElementById('invoicedetail' + i).value ? document.getElementById('invoicedetail' + i).value : 0,
                        isstock:  document.getElementById('isstock' + i).value,
                        aqty:  document.getElementById('rqty' + i).value +" "+units.find(unit => unit.unit_id == document.getElementById('unit' + i).value).unit_name,
                    });
                }
            }

        }
        console.log(arrItem)

        var paymentdropdown = document.getElementById('your_dropdown_id');
        $("#save_add").hide();

        if (id > 0) {
            $.ajax({
                url: $('#base_url').val() + 'invoice/invoice/update_sales_return',
                type: 'POST',
                data: {
                    id: id,
                    items: arrItem,
                    discount: document.getElementById('discount').value,
                    type2: type2,
                    total_discount_ammount: document.getElementById('total_discount_ammount').value,
                    total_vat_amnt: document.getElementById('total_vat_amnt').value,
                    grandTotal: document.getElementById('grandTotal').value,
                    rdate: document.getElementById('rdate').value,
                    date: document.getElementById('sale_date').value,
                    details: document.getElementById('details').value,
                    total: document.getElementById('Total').value,
                    customer_id: document.getElementById('customer_id').value,
                    employee_id: document.getElementById('employee_id').value,
                    payment_type: document.getElementById('your_dropdown_id').value,
                    payment: paymentdropdown.options[paymentdropdown.selectedIndex].text,
                    incidenttype: document.getElementById('incidenttype').value,
                    branch: document.getElementById('branch').value,
                    invoicetype: document.getElementById('invoicetype').value,
                    invoice_id1:document.getElementById('invoice_id1').value,


                },
                success: function(response) {
                    // alert("Invoice Details Updated Successfully")
                    // window.location.href = $('#base_url').val() + 'invoice_list';

                    datas = JSON.parse(response);
                    clearDetails()
                    $("#save_add").show();

                    alert("Sales return Details updated Successfully")
                    window.location.reload();

                    // printRawHtml(datas.details);


                },
                error: function(error) {
                    console.log(error)
                }
            });


        } else {

            $.ajax({
                url: $('#base_url').val() + 'invoice/invoice/save_sales_return',
                type: 'POST',
                data: {
                    items: arrItem,
                    type2: type2,
                    discount: document.getElementById('discount').value,
                    total_discount_ammount: document.getElementById('total_discount_ammount').value,
                    total_vat_amnt: document.getElementById('total_vat_amnt').value,
                    grandTotal: document.getElementById('grandTotal').value,
                    rdate: document.getElementById('rdate').value,
                    date: document.getElementById('sale_date').value,
                    details: document.getElementById('details').value,
                    total: document.getElementById('Total').value,
                    customer_id: document.getElementById('customer_id').value,
                    payment_type: document.getElementById('your_dropdown_id').value,
                    payment: paymentdropdown.options[paymentdropdown.selectedIndex].text,
                    employee_id: document.getElementById('employee_id').value,
                    incidenttype: document.getElementById('incidenttype').value,
                    branch: document.getElementById('branch').value,
                    invoicetype: document.getElementById('invoicetype').value,
                    invoice_id: document.getElementById('invoice_id').value,
                },
                success: function(response) {
                    datas = JSON.parse(response);
                    clearDetails()
                    $("#save_add").show();

                    alert("Sales return Details saved Successfully")
                    window.location.reload();


                    //  printRawHtml(datas.details);
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

            $.each(products, function(index, product) {
                $productDropdown.append('<option value="' + product.id + '">' + product.product_name + '</option>');
            });

            var $storeDropdown = $('#store' + i);
            $storeDropdown.empty();
            $storeDropdown.append('<option value="" disabled selected>Select store</option>'); // Add default option

            $.each(stores, function(index, store) {
                $storeDropdown.append('<option value="' + store.id + '">' + store.name + '</option>');
            });

            document.getElementById('myRow' + i).style.display = 'none';
            document.getElementById('qty' + i).value = "";
            document.getElementById('rqty' + i).value = "";
            document.getElementById('rstore' + i).value = "";
            document.getElementById('rdeduction' + i).value = "";



            document.getElementById('code' + i).value = "";
            document.getElementById('unit' + i).value = "";

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
        document.getElementById('sale_date').value = ""
        document.getElementById('details').value = ""
        document.getElementById('Total').value = ""
        document.getElementById('customer_id').value = ""
        document.getElementById('your_dropdown_id').value = ""

        var $customerDropdown = $('#customer_id');
        $customerDropdown.empty();
        $customerDropdown.append('<option value="" disabled selected>Select Customer</option>'); // Add default option
        $.each(customers, function(index, customer) {
            $customerDropdown.append('<option value="' + customer.customer_id + '">' + customer.customer_name + '</option>');
        });

        var $paymentDropdown = $('#your_dropdown_id');
        $paymentDropdown.empty();
        $paymentDropdown.append('<option value="" disabled selected>Select Supplier</option>'); // Add default option
        $.each(pmethods, function(index, supplier) {
            $paymentDropdown.append('<option value="' + supplier.id + '">' + supplier.name + '</option>');
        });
    }

    function printRawHtml(view) {


        $(view).print({

            deferred: $.Deferred().done(function() {
                window.location.reload();
            })
        });
    }

    function getBatchDropdown(batches, item, value, product, batchtype) {


        $.ajax({
            url: $('#base_url').val() + 'stock/stock/getBatchbyProductAndBatchtype',
            type: 'POST',
            data: {
                product: product,
                batchtype: batchtype
            },
            success: function(response2) {
                var $batchDropdown = $('#batch' + item);
                $batchDropdown.empty();
                $batchDropdown.append('<option value="" disabled selected>Select Batch</option>'); // Add default option
                $batchDropdown.append('<option value="0">Default</option>');
                if (response2 != "not") {
                    let batches2 = JSON.parse(response2);
                    $.each(batches2, function(index, batch) {
                        $batchDropdown.append('<option value="' + batch.id + '">' + batch.batchid + '</option>');
                    });
                }
                $batchDropdown.val(value)




            },
            error: function(error) {
                console.log(error)
            }
        });




    }

    function getActiveSubUnit(productId, item) {
        $.ajax({
            url: $('#base_url').val() + 'product/product/active_subunitsbyproductId',
            type: 'POST',
            data: {
                product_id: productId
            },
            success: function(response) {
                // alert("Invoice Details Updated Successfully")
                // window.location.href = $('#base_url').val() + 'invoice_list';
                datas = JSON.parse(response);
                console.log(datas)
                var $subunitDropdown = $('#unit' + item);
                document.getElementById('conversionid' + item).value = "";
                document.getElementById('conversiontype' + item).value = "";
                document.getElementById('conversion_ratio' + item).value = "";


                $subunitDropdown.empty();
                $subunitDropdown.append('<option value="" disabled selected>Select unit</option>'); // Add default option
                $subunitDropdown.append('<option value="' + datas[0].unit + '">' + datas[0].name2 + '</option>');
                $subunitDropdown.val(datas[0].unit)

                $.each(datas, function(index, store) {
                    if (store.unit_id) {
                        $subunitDropdown.append('<option value="' + store.unit_id + '">' + store.unit_name + '</option>');
                    }
                });



            },
            error: function(error) {
                console.log(error)
            }
        });
    }

    function getActiveSubUnitEdit(productId, item, value, conversion_id, conversion_ratio, cconvertiontype, avstock) {
        $.ajax({
            url: $('#base_url').val() + 'product/product/active_subunitsbyproductId',
            type: 'POST',
            data: {
                product_id: productId
            },
            success: function(response) {
                datas = JSON.parse(response);
                var $subunitDropdown = $('#unit' + item);
                if (conversion_id != "0") {
                    document.getElementById('conversionid' + item).value = conversion_id;
                    document.getElementById('conversiontype' + item).value = cconvertiontype;
                    document.getElementById('conversion_ratio' + item).value = conversion_ratio;
                } else {
                    document.getElementById('conversionid' + item).value = "";
                    document.getElementById('conversiontype' + item).value = "";
                    document.getElementById('conversion_ratio' + item).value = "";
                }

                document.getElementById('codetype' + item).innerHTML=""

                $subunitDropdown.empty();
                $subunitDropdown.append('<option value="" disabled selected>Select unit</option>'); // Add default option
                $subunitDropdown.append('<option value="' + datas[0].unit + '">' + datas[0].name2 + '</option>');

                $.each(datas, function(index, store) {
                    if (store.unit_id) {
                        $subunitDropdown.append('<option value="' + store.unit_id + '">' + store.unit_name + '</option>');
                    }
                });

                $subunitDropdown.val(value)

                document.getElementById('isstock' + item).value = datas[0].stock


                if (datas[0].stock == 0) {
                    return
                }


                let select = document.getElementById('unit' + item);
                let selectedText = select.options[select.selectedIndex].text;
                let el = document.getElementById('codetype' + item);
                el.style.color = 'green';
                el.style.fontWeight = 'bold';
                // el.innerHTML = (Math.floor(avstock)).toLocaleString() + " " + selectedText


                let sub2 = Math.floor((parseFloat(avstock)).toLocaleString());
                if (isNaN(sub2)) {
                    avstock = Number(avstock).toFixed(6);
                    el.innerHTML = (Math.floor(avstock)).toLocaleString() + " " + selectedText
                } else {
                    el.innerHTML = sub2 + " " + selectedText

                }



                if (value == datas[0].unit) {

                    $.ajax({
                        url: $('#base_url').val() + 'stock/stock/getproductSubUnitPrimary',
                        type: 'POST',
                        data: {
                            prodid: productId,
                        },
                        success: function(response2) {
                            if (response2 != "null") {

                                let product2 = JSON.parse(response2); //console.log(adjStocks[i].actualstock*product2[0].conversion_ratio)
                                console.log(product2)
                                // document.getElementById('code' + item).value = avstock * product2[0].conversion_ratio;
                                document.getElementById('mconversion_ratio' + item).value = product2[0].conversion_ratio
                                document.getElementById('bd' + item).value = product2[0].unit2
                                document.getElementById('ad' + item).value = product2[0].unit_name
                                let el = document.getElementById('codetype' + item);
                                el.style.color = 'green';
                                el.style.fontWeight = 'bold';
                                el.innerHTML = ""
                                // let totalcount = Math.floor(document.getElementById('mconversion_ratio' + item).value * avstock / document.getElementById('mconversion_ratio' + item).value);
                                // let subcount = (Math.floor(document.getElementById('mconversion_ratio' + item).value * avstock % document.getElementById('mconversion_ratio' + item).value)).toLocaleString();


                                let totalcount = 0;
                                let mas = document.getElementById('mconversion_ratio' + item).value * avstock / document.getElementById('mconversion_ratio' + item).value;
                                let subcount = 0;
                                let sub = document.getElementById('mconversion_ratio' + item).value * avstock % document.getElementById('mconversion_ratio' + item).value;


                                let mas2 = Math.floor((mas).toLocaleString());
                                if (isNaN(mas2)) {
                                    mas = Number(mas).toFixed(6);
                                    totalcount = (Math.floor(mas)).toLocaleString()
                                } else {
                                    totalcount = mas2

                                }

                                let sub2 = Math.floor((sub).toLocaleString());
                                if (isNaN(sub2)) {
                                    sub = Number(sub).toFixed(6);
                                    subcount = (Math.floor(sub)).toLocaleString()
                                } else {
                                    subcount = sub2

                                }
                                // if (stocktype != "both") {
                                document.getElementById('code' + item).value = avstock == null ? 0 : totalcount;
                                el.innerHTML = (totalcount + document.getElementById('bd' + item).value + " " + subcount + document.getElementById('ad' + item).value).toLocaleString();
                                // }
                            } else {
                                document.getElementById('mconversion_ratio' + item).value = ""
                                document.getElementById('bd' + item).value = ""
                                document.getElementById('ad' + item).value = ""
                            }
                            //   document.getElementById('unit' + item).value = product[0].unit;
                        },
                        error: function(error) {
                            console.log(error)
                        }
                    });
                } else {
                    $.ajax({
                        url: $('#base_url').val() + 'stock/stock/getproductSubUnitPrimary',
                        type: 'POST',
                        data: {
                            prodid: productId,
                        },
                        success: function(response2) {

                            if (response2 != "null") {

                                let product2 = JSON.parse(response2); //console.log(adjStocks[i].actualstock*product2[0].conversion_ratio)
                                console.log(product2)
                                // document.getElementById('code' + item).value = avstock * product2[0].conversion_ratio;
                                document.getElementById('mconversion_ratio' + item).value = product2[0].conversion_ratio
                                document.getElementById('bd' + item).value = product2[0].unit2
                                document.getElementById('ad' + item).value = product2[0].unit_name
                            } else {
                                document.getElementById('mconversion_ratio' + item).value = ""
                                document.getElementById('bd' + item).value = ""
                                document.getElementById('ad' + item).value = ""
                            }
                            //   document.getElementById('unit' + item).value = product[0].unit;
                        },
                        error: function(error) {
                            console.log(error)
                        }
                    });
                }






            },
            error: function(error) {
                console.log(error)
            }
        });
    }


    function convertion(item, product, unit, unitname) {

        // if (unitname.split("-")[1] == "S") {
        $.ajax({
            url: $('#base_url').val() + 'stock/stock/conversion',
            type: 'POST',
            data: {
                product_id: product,
                unit: unit
            },
            success: function(response) {
                // alert("Invoice Details Updated Successfully")
                // window.location.href = $('#base_url').val() + 'invoice_list';
                if (response != "not") {
                    datas = JSON.parse(response);
                    document.getElementById('conversiontype' + item).value = datas[0].convertiontype
                    document.getElementById('conversionid' + item).value = datas[0].conversionratio_id
                    document.getElementById('conversion_ratio' + item).value = datas[0].conversion_ratio;
                    document.getElementById('product_rate' + item).value = datas[0].subcost_price;



                    avStock(item, document.getElementById('product' + item).value, document.getElementById('store' + item).value, document.getElementById('batch' + item).value,
                        datas[0].convertiontype, datas[0].conversion_ratio)
                } else {
                    // alert("Conversion not found")
                    getActiveSubUnit(document.getElementById('product' + item).value, item)
                    avStock(item, document.getElementById('product' + item).value, document.getElementById('store' + item).value, document.getElementById('batch' + item).value, "", "")
                    document.getElementById('product_rate' + item).value = document.getElementById('mastercost_price' + item).value;
                }

            },
            error: function(error) {
                console.log(error)
            }
        });
        // } else {
        //     getActiveSubUnit(document.getElementById('product' + item).value, item)
        //     avStock(item, document.getElementById('product' + item).value, document.getElementById('store' + item).value, document.getElementById('batch' + item).value, "", "")

        // }


    }
</script>