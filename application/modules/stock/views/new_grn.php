<style>
    .product_field {
        width: 300px;
    }

    .field {
        width: 170px;
    }

    .unit {
        width: 120px;
    }

    .qty {
        width: 120px;
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
            <input type="hidden" name="baseUrl2" id="baseUrl2" class="baseUrl" value="<?php echo base_url(); ?>" />
            <div class="panel-body" style="margin: 20px;">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group row">
                            <label for="supplier_sss" class="col-sm-4 col-form-label">Store
                                <i class="text-danger">*</i>
                            </label>
                            <div class="col-sm-6">
                                <select class="form-control" id="store" required name="store[]" tabindex="3" onchange="get_type('store')">
                                    <option value=""></option>
                                </select>
                            </div>

                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group row">
                            <label for="date" class="col-sm-4 col-form-label">Date
                                <i class="text-danger">*</i></label>
                            <div class="col-sm-8">
                                <?php
                                date_default_timezone_set('Asia/Colombo');

                                $date = date('Y-m-d');
                                ?>

                                <?php if (!empty($id)) { ?>
                                    <input class="datepicker form-control" type="text" size="50" name="invoice_date" id="date" required value="<?php echo html_escape($date); ?>" tabindex="4" />
                                <?php } else { ?>
                                    <input class="datepicker form-control" type="text" size="50" name="invoice_date" id="date" required value="<?php echo html_escape($date); ?>" tabindex="4" />

                                <?php } ?>
                            </div>
                        </div>

                    </div>
                    <div class="col-sm-6">
                        <div class="form-group row">
                            <label for="supplier_sss" class="col-sm-4 col-form-label">Incident Type
                                <i class="text-danger">*</i>
                            </label>
                            <div class="col-sm-6">
                                <select class="form-control" id="type" required name="type" tabindex="3" onchange="get_type('type')">
                                    <option value=""></option>
                                    <option value="purchase">Purchase</option>
                                    <option value="salesreturn">Sales Return</option>
                                    <option value="storetransfer">Store Transfer</option>

                                </select>
                            </div>

                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group row">
                            <label for="address" class="col-sm-4 col-form-label">Vehicle No
                            </label>
                            <div class="col-sm-8">
                                <input tabindex="" class="form-control" id="vehicleno" name="vehicleno" placeholder="Vehicle No" />

                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="form-group row">
                            <label for="supplier_sss" class="col-sm-4 col-form-label">Voucher No
                            </label>
                            <div class="col-sm-6">
                                <select class="form-control" id="voucherno" required name="voucherno" tabindex="3" onchange="get_type('voucherno')">
                                    <option value=""></option>
                                </select>
                            </div>

                        </div>
                        <div class="form-group row">
                            <label for="supplier_sss" class="col-sm-4 col-form-label"><?php echo display('supplier') ?>
                            </label>
                            <div class="col-sm-6">
                                <select name="supplier_id" id="supplier_id" class="form-control " tabindex="1">
                                    <option value="">Select an option</option>

                                    <?php foreach ($all_supplier as $suppliers) { ?>
                                        <option value="<?php echo $suppliers['supplier_id'] ?>">
                                            <?php echo $suppliers['supplier_name'] ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <?php if ($this->permission1->method('add_supplier', 'create')->access()) { ?>
                                <div class="col-sm-2">
                                    <a class="btn btn-success" title="Add New Supplier" href="<?php echo base_url('add_supplier'); ?>"><i class="fa fa-user"></i></a>
                                </div>
                            <?php } ?>
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="form-group row">
                            <label for="address" class="col-sm-4 col-form-label">Details
                            </label>
                            <div class="col-sm-8">
                                <textarea class="form-control" id="detail" name="detail" placeholder="Details" rows="4"></textarea>

                            </div>
                        </div>
                    </div>





                </div>


            </div>

            <div style="margin: 20px;">
                <table class="table table-bordered table-hover" id="normalinvoice">
                    <thead>
                        <tr>
                            <th class="text-center product_field">Product<i
                                    class="text-danger">*</i></th>
                            <th class="text-center ">Batch <i class="text-danger">*</i> </th>
                            <th class="text-center ">Unit <i class="text-danger">*</i></th>
                            <th class="text-center ">Available Qty</th>
                            <th class="text-center "><span id="typehead"></span></th>
                            <th class="text-center ">Arrived Qty</th>
                            <th class="text-center ">Pending Qty</th>
                            <th class="text-center ">Qty<i
                                    class="text-danger">*</i></th>

                            <th class="text-center"><?php echo display('action') ?></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody id="addinvoiceItem">
                        <tr id="myRow1">
                            <td class="product_field">

                                <select name="product[]" class="form-control" id="product1" tabindex="1" onchange="quantity_calculate(1,'product')" required>
                                    <option value="">Select Product</option>
                                    <?php foreach ($products as $services) {
                                        echo $services['id']; ?>
                                        <option value="<?php echo $services['id']; ?>"><?php echo $services['product_name']; ?></option>

                                    <?php  }   ?>
                                </select>
                                <input type="hidden" id="mconversion_ratio1" />
                                <input type="hidden" id="bd1" />
                                <input type="hidden" id="ad1" />


                            </td>
                            <td class="qty">
                                <select class="form-control" id="batch1" required name="batch[]" tabindex="3" onchange="quantity_calculate(1,'batch')">
                                    <option value=""></option>
                                </select>
                            </td>


                            <td class="qty">
                                <select class="form-control" id="unit1" required name="unit1" onchange="quantity_calculate(1,'unit')" tabindex="3">
                                    <option value=""></option>
                                </select>
                                <input type="hidden" id="conversionid1" />
                                <input type="hidden" id="conversiontype1" />
                                <input type="hidden" id="conversion_ratio1" />
                                <input type="hidden" id="purchasedetailid1" />



                            </td>
                            <td class="qty">
                                <input type="hidden" name="code[]" required onkeyup="quantity_calculate(1,'code');"
                                    class="total_qntt_1 form-control text-right"
                                    id="code1" placeholder="0.00" min="0" readonly />
                                <span id='codetype1' style="margin-left:5px"></span>
                            </td>
                            <td class="qty">
                                <input type="hidden" name="puqty[]" required onkeyup="quantity_calculate(1,'puqty');"
                                    class="total_qntt_1 form-control text-right"
                                    id="puqty1" placeholder="0.00" min="0" tabindex="5" readonly />
                                <input type="hidden" name="codepuqty[]" required onkeyup="quantity_calculate(1,'codepuqty');"
                                    class="total_qntt_1 form-control text-right"
                                    id="codepuqty1" placeholder="0.00" min="0" tabindex="5" readonly />

                                <span id='codeputype1' style="margin-left:5px"></span>

                            </td>
                            <td class="qty">
                                <input type="hidden" name="arqty[]" required onkeyup="quantity_calculate(1,'puqty');"
                                    class="total_qntt_1 form-control text-right"
                                    id="arqty1" placeholder="0.00" min="0" tabindex="5" readonly />
                                <input type="hidden" name="codearqty[]" required onkeyup="quantity_calculate(1,'codearqty');"
                                    class="total_qntt_1 form-control text-right"
                                    id="codearqty1" placeholder="0.00" min="0" tabindex="5" readonly />

                                <span id='codeartype1' style="margin-left:5px"></span>
                            </td>
                            <td class="qty">
                                <input type="hidden" name="penqty[]" required onkeyup="quantity_calculate(1,'penqty');"
                                    class="total_qntt_1 form-control text-right"
                                    id="penqty1" placeholder="0.00" min="0" tabindex="5" readonly />
                                <input type="hidden" name="codepenqty[]" required onkeyup="quantity_calculate(1,'codepenqty');"
                                    class="total_qntt_1 form-control text-right"
                                    id="codepenqty1" placeholder="0.00" min="0" tabindex="5" readonly />

                                <span id='codepentype1' style="margin-left:5px"></span>
                            </td>
                            <td class="qty">
                                <input type="number" name="qty[]" required onkeyup="quantity_calculate(1,'qty');"
                                    class="total_qntt_1 form-control text-right"
                                    id="qty1" placeholder="0.00" min="0" tabindex="5" />
                            </td>


                            <td>
                            </td>

                        </tr>

                        <?php
                        // Assuming you want to generate 5 rows dynamically
                        for ($i = 2; $i <= 70; $i++) {
                        ?>
                            <tr id="myRow<?php echo $i; ?>">
                                <td class="product_field">
                                    <select name="product[]" class="form-control" id="product<?php echo $i; ?>" tabindex="1" onchange="quantity_calculate(<?php echo $i; ?>, 'product')" required>
                                        <option value="">Select Product</option>
                                    </select>
                                    <input type="hidden" id="mconversion_ratio<?php echo $i; ?>" />
                                    <input type="hidden" id="bd<?php echo $i; ?>" />
                                    <input type="hidden" id="ad<?php echo $i; ?>" />
                                </td>


                                <td class="qty">
                                    <select class="form-control" id="batch<?php echo $i; ?>" required name="batch[]" tabindex="3" onchange="quantity_calculate(<?php echo $i; ?>,'batch')">
                                        <option value=""></option>
                                    </select>
                                </td>


                                <td class="qty">
                                    <select class="form-control" id="unit<?php echo $i; ?>" required name="unit<?php echo $i; ?>" onchange="quantity_calculate(<?php echo $i; ?>,'unit')" tabindex="3">
                                        <option value=""></option>
                                    </select>
                                    <input type="hidden" id="conversionid<?php echo $i; ?>" />
                                    <input type="hidden" id="conversiontype<?php echo $i; ?>" />
                                    <input type="hidden" id="conversion_ratio<?php echo $i; ?>" />
                                    <input type="hidden" id="purchasedetailid<?php echo $i; ?>" />

                                </td>

                                <td class="qty">
                                    <input type="hidden" name="code[]" required onkeyup="quantity_calculate(<?php echo $i; ?>,'code');"
                                        class="total_qntt_1 form-control text-right"
                                        id="code<?php echo $i; ?>" placeholder="0.00" min="0" readonly />
                                    <span id='codetype<?php echo $i; ?>' style="margin-left:5px"></span>


                                </td>
                                <td class="qty">
                                    <input type="hidden" name="puqty[]" required onkeyup="quantity_calculate(<?php echo $i; ?>,'puqty');"
                                        class="total_qntt_1 form-control text-right"
                                        id="puqty<?php echo $i; ?>" placeholder="0.00" min="0" tabindex="5" readonly />
                                    <input type="hidden" name="codepuqty[]" required onkeyup="quantity_calculate(<?php echo $i; ?>,'codepuqty');"
                                        class="total_qntt_1 form-control text-right"
                                        id="codepuqty<?php echo $i; ?>" placeholder="0.00" min="0" tabindex="5" readonly />

                                    <span id='codeputype<?php echo $i; ?>' style="margin-left:5px"></span>

                                </td>
                                <td class="qty">
                                    <input type="hidden" name="arqty[]" required onkeyup="quantity_calculate(<?php echo $i; ?>,'puqty');"
                                        class="total_qntt_1 form-control text-right"
                                        id="arqty<?php echo $i; ?>" placeholder="0.00" min="0" tabindex="5" readonly />
                                    <input type="hidden" name="codearqty[]" required onkeyup="quantity_calculate(<?php echo $i; ?>,'codepuqty');"
                                        class="total_qntt_1 form-control text-right"
                                        id="codearqty<?php echo $i; ?>" placeholder="0.00" min="0" tabindex="5" readonly />

                                    <span id='codeartype<?php echo $i; ?>' style="margin-left:5px"></span>
                                </td>
                                <td class="qty">
                                    <input type="hidden" name="penqty[]" required onkeyup="quantity_calculate(<?php echo $i; ?>,'penqty');"
                                        class="total_qntt_1 form-control text-right"
                                        id="penqty<?php echo $i; ?>" placeholder="0.00" min="0" tabindex="5" readonly />

                                    <input type="hidden" name="codepenqty[]" required onkeyup="quantity_calculate(<?php echo $i; ?>,'codepuqty');"
                                        class="total_qntt_1 form-control text-right"
                                        id="codepenqty<?php echo $i; ?>" placeholder="0.00" min="0" tabindex="5" readonly />

                                    <span id='codepentype<?php echo $i; ?>' style="margin-left:5px"></span>
                                </td>

                                <td class="qty">
                                    <input type="number" name="qty[]" onkeyup="quantity_calculate(<?php echo $i; ?>, 'qty');"
                                        onchange="quantity_calculate(1);" class="total_qntt_1 form-control text-right"
                                        id="qty<?php echo $i; ?>" placeholder="0.00" min="0" tabindex="5" />
                                </td>



                                <td style="display: flex; justify-content: center; align-items: center;">
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
                            <td colspan="9" rowspan="2">
                                <button type="button" id="add_invoice_item" class="btn btn-info"
                                    name="add-invoice-item" onClick="addInputField('addinvoiceItem');"><i
                                        class='fa fa-plus'></i> Add New Item</button>
                                <input type="hidden" name="" id="discount_type" value="<?php echo $discount_type ?>">
                            </td>


                        </tr>
                    </tfoot>
                </table>



                <input type="hidden" name="finyear" value="<?php echo financial_year(); ?>">
                <p hidden id="old-amount"><?php echo 0; ?></p>
                <p hidden id="pay-amount"></p>
                <p hidden id="change-amount"></p>
            </div>
            <div class="form-group row text-right" style="margin-right: 5px;">
                <div class="col-sm-12 p-20">
                    <!-- <input type="button" id="add_invoice" class="btn btn-success" name="add-invoice"
                        value="Save" tabindex="17"  /> -->

                    <button id="save_add" class="btn btn-success" name="add-invoice" onclick="save()">
                        <?php echo (empty($id) ? display('save') : display('update')) ?></button>




                </div>
            </div>
        </div>
    </div>
</div>


<?php
echo "<script>";
echo "var id = " . json_encode($id) . ";";
echo "var type = " . json_encode($type) . ";";
echo "let products=" . json_encode($products) . ";";
echo "let stores=" . json_encode($store_list) . ";";
echo "let batches=" . json_encode($batches) . ";";
echo "let units=" . json_encode($units) . ";";
echo "let suppliers=" . json_encode($all_supplier) . ";";
echo "let usertype=" . json_encode($this->session->userdata('user_level2')) . ";";
echo "</script>";
?>

<script>
    let count = 2

    let type2 = ""
    $(document).ready(function() {
        document.getElementById("typehead").innerHTML='Purchased Qty'
        if (usertype == 3) {
            document.getElementById('style12').style.backgroundColor = '#E0E0E0';
            const title = document.getElementById('title');
            title.style.color = 'blue';
            type2 = "B"

        } else {
            type2 = "A"

        }
        getActiveStore(0);
        for (let i = 2; i <= 70; i++) {
            document.getElementById('myRow' + i).style.display = 'none';

        }
        if (id != null) {
            $.ajax({
                url: $('#baseUrl2').val() + 'stock/stock/getgrnStockById',
                type: 'POST',
                data: {
                    pid: id,
                    type2: type2,
                    type: type.type
                },
                success: function(response) {
                    var grnStocks = JSON.parse(response);
                    console.log(grnStocks)
                    // count = 1;
                    for (let i = 0; i < grnStocks.length; i++) {
                        let a = i + 1;
                        document.getElementById('myRow' + a).style.display = 'table-row';

                        // Call other functions based on data
                        getActiveProduct(grnStocks[i].product, a, true);
                        getActiveStore(grnStocks[i].store, true);

                        if(grnStocks[i].type=="salesreturn"){
                            document.getElementById('typehead').innerHTML = "Returned Qty";


                        }


                        getType(grnStocks[i].type, grnStocks[i].voucherno);

                        var $supplierDropdown = $('#supplier_id');
                        $supplierDropdown.empty();
                        $supplierDropdown.append('<option value="" disabled selected>Select Supplier</option>'); // Add default option
                        $.each(suppliers, function(index, supplier) {
                            $supplierDropdown.append('<option value="' + supplier.supplier_id + '">' + supplier.supplier_name + '</option>');
                        });
                        $supplierDropdown.val(grnStocks[i].supplier_id)

                        $supplierDropdown.prop('disabled', true);


                        getBatchDropdown(batches, a, grnStocks[i].batch, grnStocks[i].product, grnStocks[i].batchtype, true)


                        //  getAdjDropdown(adjStocks[i].actualstock > 0 ? "increase" : "decrease", a)
                        // Set form values
                        // document.getElementById('code' + a).value = grnStocks[i].avstock;
                        document.getElementById('date').value = grnStocks[i].date;
                        document.getElementById('detail').value = grnStocks[i].details;
                        //  document.getElementById('receivedfrom').value = grnStocks[i].supplier_name;
                        document.getElementById('vehicleno').value = grnStocks[i].vehicleno;

                        document.getElementById('code' + a).value = grnStocks[i].avstock;

                        if (grnStocks[i].type == "storetransfer") {
                            getActiveSubUnitEdit(grnStocks[i].product, a, grnStocks[i].unit, grnStocks[i].conversion_id, grnStocks[i].conversion_ratio, grnStocks[i].convertiontype, grnStocks[i].avstock,
                                0, 0, grnStocks[i].purchasedetailid)

                            if (grnStocks[i].conversion_ratio) {
                                document.getElementById('qty' + a).value = grnStocks[i].actualstock * grnStocks[i].conversion_ratio;

                            } else {
                                document.getElementById('qty' + a).value = grnStocks[i].actualstock
                            }

                        }


                        if (!grnStocks[i].purchasedetailid) {

                            getActiveSubUnitEdit(grnStocks[i].product, a, grnStocks[i].unit, grnStocks[i].conversion_id, grnStocks[i].conversion_ratio, grnStocks[i].convertiontype, grnStocks[i].avstock,
                                0, 0, grnStocks[i].purchasedetailid)

                            if (grnStocks[i].conversion_ratio) {
                                document.getElementById('qty' + a).value = grnStocks[i].actualstock * grnStocks[i].conversion_ratio;

                            } else {
                                document.getElementById('qty' + a).value = grnStocks[i].actualstock
                            }
                        }





                        if (grnStocks[i].purchasedetailid) {
                            $.ajax({
                                url: $('#baseUrl2').val() + 'stock/stock/getPurchaseByVoucherNoAndProductId',
                                type: 'POST',
                                data: {
                                    store: grnStocks[i].store,
                                    type2: type2,
                                    voucherno: grnStocks[i].voucherno,
                                    product: grnStocks[i].product,
                                    batch: grnStocks[i].batch,
                                    purchasedetailid: grnStocks[i].purchasedetailid,
                                    type: type.type,
                                    invoicetype: type.type === "purchase" ? 1 : type.type === "salesreturn" ? 2 : 0
                                },
                                success: function(response) {

                                    let items = JSON.parse(response);
                                    document.getElementById('code' + a).value = items[0].avstock;
                                    document.getElementById('puqty' + a).value = items[0].quantity;

                                    document.getElementById('arqty' + a).value = items[0].arquatity;

                                    let arqty = items[0].arquatity - parseFloat(grnStocks[i].actualstock);

                                    // document.getElementById('arqty' + a).value = arqty;

                                    let penqty = items[0].quantity - items[0].arquatity;

                                    document.getElementById('penqty' + a).value = penqty;
                                    if (grnStocks[i].conversion_ratio) {
                                        document.getElementById('qty' + a).value = grnStocks[i].actualstock * grnStocks[i].conversion_ratio;

                                    } else {
                                        document.getElementById('qty' + a).value = grnStocks[i].actualstock
                                    }
                                    document.getElementById('purchasedetailid' + a).value = grnStocks[i].purchasedetailid;

                                    getActiveSubUnitEdit(grnStocks[i].product, a, grnStocks[i].unit, grnStocks[i].conversion_id, grnStocks[i].conversion_ratio, grnStocks[i].convertiontype, items[0].avstock,
                                        items[0].quantity, items[0].arquatity, grnStocks[i].purchasedetailid)
                                },
                                error: function(error) {
                                    console.log(error)
                                }
                            });
                        }

                        count = count + 1;





                    }
                },
                error: function(error) {
                    console.log(error);
                }
            }); // 2000 milliseconds = 2 seconds delay
        }


    });


    function addInputField(t) {
        document.getElementById('myRow' + count).style.display = 'table-row';
        getActiveProduct(0, count)
        count = count + 1;

    }

    function save() {
        arrItem2 = [];
        let settype = invoicetype();

        for (let i = 1; i <= count; i++) {
            if (document.getElementById('myRow' + i).style.display != "none") {

                if (document.getElementById('store').value == "") {
                    alert("Store shouldn't be empty")
                    return
                } else if (document.getElementById('type').value == "") {
                    alert("Type shouldn't be empty")
                    return
                } else if (document.getElementById('product' + i).value == "") {
                    alert("Product shouldn't be empty")
                    return
                } else if (document.getElementById('qty' + i).value == "" || document.getElementById('qty' + i).value < 0) {
                    alert("Qty shouldn't be empty or quantity greater than 0")
                    return
                } else {
                    var dropdown = document.getElementById('product' + i);
                    var storedropdown = document.getElementById('store');
                    var typedropdown = document.getElementById('type');
                    var vouchernodropdown = "";

                    if (document.getElementById('voucherno').value == "") {
                        vouchernodropdown = "";
                    } else {
                        vouchernodropdown = document.getElementById('voucherno');
                        vouchernodropdown = vouchernodropdown.options[vouchernodropdown.selectedIndex].text.split("-")[0]
                    }

                    let qty = 0;
                    if (document.getElementById('conversiontype' + i).value == "+") {
                        qty = document.getElementById('qty' + i).value - document.getElementById('conversion_ratio' + i).value
                    } else
                    if (document.getElementById('conversiontype' + i).value == "-") {
                        qty = document.getElementById('qty' + i).value + document.getElementById('conversion_ratio' + i).value
                    } else
                    if (document.getElementById('conversiontype' + i).value == "*") {
                        qty = document.getElementById('qty' + i).value / document.getElementById('conversion_ratio' + i).value
                    } else
                    if (document.getElementById('conversiontype' + i).value == "/") {
                        qty = document.getElementById('qty' + i).value * document.getElementById('conversion_ratio' + i).value
                    } else {
                        qty = document.getElementById('qty' + i).value
                    }


                    arrItem2.push({
                        product: document.getElementById('product' + i).value,
                        store: document.getElementById('store').value,
                        supplier_id: document.getElementById('supplier_id').value,
                        quantity: qty,
                        date: document.getElementById('date').value,
                        detail: document.getElementById('detail').value,
                        vehicleno: document.getElementById('vehicleno').value,
                        type: document.getElementById('type').value,
                        voucherno: document.getElementById('voucherno').value,
                        voucher_no: vouchernodropdown,
                        type2: type2,
                        invoicetype: settype,
                        product_name: dropdown.options[dropdown.selectedIndex].text,
                        store_name: storedropdown.options[storedropdown.selectedIndex].text,
                        type_name: typedropdown.options[typedropdown.selectedIndex].text,
                        unit: document.getElementById('unit' + i).value,
                        conversionid: document.getElementById('conversionid' + i).value,
                        batch: document.getElementById('batch' + i).value,
                        purchasedetailid: document.getElementById('purchasedetailid' + i).value,
                        aqty:  document.getElementById('qty' + i).value +" "+units.find(unit => unit.unit_id == document.getElementById('unit' + i).value).unit_name
                    });
                }
            }

        }

        // console.log(arrItem2)
        // let check2 = valcheck();

        // if (!check2) {
        //     alert("You can't use  same (product,store)  in multiple rows")
        //     return
        // }

        $("#save_add").hide();




        if (id > 0) {
            $.ajax({
                url: $('#baseUrl2').val() + 'stock/stock/update_grn',
                type: 'POST',
                data: {
                    items: arrItem2,
                    id: id
                },
                success: function(response) {
                    alert("Good Received Note Updated Successfully")
                    datas = JSON.parse(response);

                    $("#save_add").show();

                    printRawHtml(datas.details);

                },
                error: function(error) {
                    console.log(error)
                }
            });


        } else {
            $.ajax({
                url: $('#baseUrl2').val() + 'stock/stock/save_grn',
                type: 'POST',
                data: {
                    items: arrItem2
                },
                success: function(response) {
                    alert("Good Received Note saved Successfully")
                    // window.location.href = $('#baseUrl2').val() + 'manage_grn';

                    datas = JSON.parse(response);
                    $("#save_add").show();


                    printRawHtml(datas.details);



                },
                error: function(error) {
                    console.log(error)
                }
            });

        }
    }

    function printRawHtml(view) {


        $(view).print({

            deferred: $.Deferred().done(function() {
                window.location.href = $('#baseUrl2').val() + 'manage_grn';
            })
        });
    }

    function deleteRow(num) {
        document.getElementById('myRow' + num).style.display = 'none';
    }

    function valcheck() {
        arrItem = [];

        if (count > 2) {
            for (let i = 1; i < count; i++) {
                if (document.getElementById('myRow' + i).style.display != "none") {
                    let check = arrItem.find(item => item.product == document.getElementById('product' + i).value &&
                        item.store == document.getElementById('store' + i).value);

                    if (check != undefined) {
                        if (check.product != '') {
                            return false
                        } else {
                            arrItem.push({
                                product: document.getElementById('product' + i).value,
                                store: document.getElementById('store' + i).value

                            });
                        }

                    } else {
                        arrItem.push({
                            product: document.getElementById('product' + i).value,
                            store: document.getElementById('store' + i).value

                        });
                    }
                }

            }

        }
        return true;

    }

    function get_type(name) {

        if (name === "type" || name === "store") {
            var $voucherDropdown = $('#voucherno');
            $voucherDropdown.empty();
            document.getElementById("typehead").innerHTML='Purchased Qty'

            clearTable();
            if (document.getElementById('type').value === "purchase" &&
                document.getElementById('store').value !== "") {


                $.ajax({
                    url: $('#baseUrl2').val() + 'stock/stock/getVoucherNo',
                    type: 'POST',
                    data: {
                        store: document.getElementById('store').value,
                        type2: type2,
                        type: 'purchase'
                    },
                    success: function(response) {
                        if (response != "") {
                            let vouchers = JSON.parse(response);
                            getVoucher(vouchers, 0, 'purchase')
                        }

                    },
                    error: function(error) {
                        console.log(error)
                    }
                });

            }

            if (document.getElementById('type').value === "salesreturn" &&
                document.getElementById('store').value !== "") {


                $.ajax({
                    url: $('#baseUrl2').val() + 'stock/stock/getVoucherNo',
                    type: 'POST',
                    data: {
                        store: document.getElementById('store').value,
                        type2: type2,
                        type: 'salesreturn'

                    },
                    success: function(response) {
                        document.getElementById("typehead").innerHTML='Returned Qty'
                        if (response != "") {
                            let vouchers = JSON.parse(response);
                            getVoucher(vouchers, 0, 'salesreturn')
                        }

                    },
                    error: function(error) {
                        console.log(error)
                    }
                });

            }
        }

        if (name === "voucherno") {
            clearTable();
            let settype = invoicetype();

            if (document.getElementById('voucherno').value != "") {
                $.ajax({
                    url: $('#baseUrl2').val() + 'stock/stock/getPurchaseByVoucherNo',
                    type: 'POST',
                    data: {
                        store: document.getElementById('store').value,
                        invoicetype: settype,
                        voucherno: document.getElementById('voucherno').value,

                    },
                    success: function(response) {
                        let items = JSON.parse(response);
                        console.log(items)

                        // document.getElementById('receivedfrom').value = items[0].supplier_name
                        for (let i = 2; i <= 70; i++) {
                            document.getElementById('myRow' + i).style.display = 'none';

                        }
                        for (let i = 0; i < items.length; i++) {

                            let a = i + 1;
                            document.getElementById('myRow' + a).style.display = 'table-row';
                            getActiveProduct(items[i].product_id, a, true);
                            getBatchDropdown(batches, a, items[i].batch, items[i].product_id, items[i].batchtype, true);

                            // document.getElementById('unit' + a).value = items[i].unit;


                            document.getElementById('code' + a).value = items[i].avstock;
                            document.getElementById('puqty' + a).value = items[i].quantity;
                            document.getElementById('arqty' + a).value = items[i].arquatity;
                            document.getElementById('purchasedetailid' + a).value = items[i].purchasedetailid;


                            let penqty = items[i].quantity - items[i].arquatity;

                            document.getElementById('penqty' + a).value = penqty;


                            getActiveSubUnitEdit(items[i].product_id, a, items[i].unit, items[i].conversion_id, items[i].conversion_ratio, items[i].convertiontype, items[i].avstock,
                                items[i].quantity, items[i].arquatity, items[i].purchasedetailid)



                            count = count + 1;
                        }

                        var $supplierDropdown = $('#supplier_id');
                        $supplierDropdown.empty();
                        $supplierDropdown.append('<option value="" disabled selected>Select Supplier</option>'); // Add default option
                        $.each(suppliers, function(index, supplier) {
                            $supplierDropdown.append('<option value="' + supplier.supplier_id + '">' + supplier.supplier_name + '</option>');
                        });
                        $supplierDropdown.val(items[0].supplier_id)
                        $supplierDropdown.prop('disabled', true);




                        //console.log(items);
                    },
                    error: function(error) {
                        console.log(error)
                    }
                });
            }



        }



    }

    function clearTable() {
        //document.getElementById('receivedfrom').value = ""

        for (let i = 2; i <= 70; i++) {
            document.getElementById('myRow' + i).style.display = 'none';
        }
        getActiveProduct(0, 1, false);
        document.getElementById('qty' + 1).value = "";
        document.getElementById('unit' + 1).value = "";
        document.getElementById('code' + 1).value = "";
    }

    function getVoucher(vouchers, voucherId, type) {
        var $voucherDropdown = $('#voucherno');
        $voucherDropdown.empty();
        $voucherDropdown.append('<option value="" disabled selected>Select Voucher No</option>'); // Add default option

        $.each(vouchers, function(index, voucher) {
            if (type == "purchase") {
                $voucherDropdown.append('<option value="' + voucher.id + '">' + voucher.voucherno + " - " + voucher.supplier_name + '</option>');

            } else {
                $voucherDropdown.append('<option value="' + voucher.id + '">' + voucher.voucherno + '</option>');

            }
        });

        if (voucherId > 0) {
            {
                $voucherDropdown.val(voucherId)
            }
        }
    }



    function quantity_calculate(item, name) {
        if (name === "product") {
            document.getElementById('code' + item).value = "";
            document.getElementById('qty' + item).value = "";
            document.getElementById('puqty' + item).value = "";
            document.getElementById('arqty' + item).value = "";
            document.getElementById('penqty' + item).value = "";
            if (!document.getElementById('store').value) {
                alert("Please select the store")
                return
            }
            $.ajax({
                url: $('#baseUrl2').val() + 'stock/stock/getproduct',
                type: 'POST',
                data: {
                    prodid: document.getElementById('product' + item).value.toString(),
                },
                success: function(response) {

                    let product = JSON.parse(response);
                    document.getElementById('unit' + item).value = product[0].unit;
                    setTimeout(
                        $.ajax({
                            url: $('#baseUrl2').val() + 'stock/stock/getproductSubUnitPrimary',
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

                                avStock(item, document.getElementById('product' + item).value, 1, "", "")


                                //   document.getElementById('unit' + item).value = product[0].unit;
                            },
                            error: function(error) {
                                console.log(error)
                            }
                        }), 1000);
                    getBatchDropdown(batches, item, 1, document.getElementById('product' + item).value, product[0].batchtype, false);
                    getActiveSubUnit(document.getElementById('product' + item).value, item)
                },
                error: function(error) {
                    console.log(error)
                }
            });
        }
        if (name === "qty") {
            // if (parseInt(document.getElementById("qty" + item).value) > parseInt(document.getElementById("codepenqty" + item).value)) {
            //     document.getElementById("qty" + item).value = "";
            //     alert("Entered qty more than pending qty")
            // }

        }

        if (name === "unit") {
            let select = document.getElementById('unit' + item);
            let selectedText = select.options[select.selectedIndex].text;
            convertion(item, document.getElementById('product' + item).value, document.getElementById('unit' + item).value, selectedText)

        }



    }

    function avStock(item, product, batch, convertiontype, conversion_ratio, bd, ad, addigit) {
        document.getElementById('code' + item).value = "";
        document.getElementById('qty' + item).value = "";
        // getAdjDropdown(0, item)
        $.ajax({
            url: $('#baseUrl2').val() + 'stock/stock/avg_phystock',
            type: 'POST',
            data: {
                prodid: product,
                storeid: document.getElementById('store').value.toString(),
                batch: batch

            },
            success: function(response) {
                let stock = JSON.parse(response);
                let el = document.getElementById('codetype' + item);
                el.style.color = 'black';
                el.style.fontWeight = 'bold';
                el.innerHTML = ""
                let select = document.getElementById('unit' + item);
                let selectedText = select.options[select.selectedIndex].text;
                let el2 = document.getElementById('codeputype' + item);
                el2.style.color = 'blue';
                el2.style.fontWeight = 'bold';

                let el3 = document.getElementById('codeartype' + item);
                el3.style.color = 'green';
                el3.style.fontWeight = 'bold';

                let el4 = document.getElementById('codepentype' + item);
                el4.style.color = 'red';
                el4.style.fontWeight = 'bold';

                if (convertiontype == "*") {
                    document.getElementById('code' + item).value = (stock[0].avgqty * conversion_ratio).toFixed(2)
                    let sub = stock[0].avgqty * conversion_ratio;
                    let sub2 = Math.floor((sub).toLocaleString());
                    if (isNaN(sub2)) {
                        sub = Number(sub).toFixed(6);
                        el.innerHTML = (Math.floor(sub)).toLocaleString() + " " + selectedText
                    } else {
                        el.innerHTML = sub2 + " " + selectedText

                    }
                    if (document.getElementById('voucherno').value) {

                        sub = parseFloat(document.getElementById('puqty' + item).value) * conversion_ratio;
                        sub2 = Math.floor((sub).toLocaleString());
                        if (isNaN(sub2)) {
                            sub = Number(sub).toFixed(6);
                            el2.innerHTML = (Math.floor(sub)).toLocaleString() + " " + selectedText
                        } else {
                            el2.innerHTML = sub2 + " " + selectedText

                        }



                        sub = parseFloat(document.getElementById('arqty' + item).value) * conversion_ratio;

                        sub2 = Math.floor((sub).toLocaleString());
                        if (isNaN(sub2)) {
                            sub = Number(sub).toFixed(6);
                            el3.innerHTML = (Math.floor(sub)).toLocaleString() + " " + selectedText
                        } else {
                            el3.innerHTML = sub2 + " " + selectedText

                        }

                        sub = document.getElementById('penqty' + item).value * conversion_ratio;
                        sub2 = Math.floor((sub).toLocaleString());
                        if (isNaN(sub2)) {
                            sub = Number(sub).toFixed(6);
                            el4.innerHTML = (Math.floor(sub)).toLocaleString() + " " + selectedText
                        } else {
                            el4.innerHTML = sub2 + " " + selectedText

                        }

                        // el3.innerHTML = (Math.floor()).toLocaleString() + " " + selectedText
                        // el4.innerHTML = (Math.floor(document.getElementById('penqty' + item).value * conversion_ratio)).toLocaleString() + " " + selectedText



                        document.getElementById('codepuqty' + item).value = Math.floor(document.getElementById('puqty' + item).value * conversion_ratio)
                        document.getElementById('codearqty' + item).value = Math.floor(document.getElementById('arqty' + item).value * conversion_ratio)
                        document.getElementById('codepenqty' + item).value = Math.floor(document.getElementById('penqty' + item).value * conversion_ratio)
                    }
                } else if (convertiontype == "/") {
                    document.getElementById('code' + item).value = (stock[0].avgqty / conversion_ratio).toFixed(2)
                    el.innerHTML = (Math.floor(stock[0].avgqty / conversion_ratio)).toLocaleString() + " " + selectedText
                    if (document.getElementById('voucherno').value) {


                    }

                } else if (convertiontype == "+") {
                    document.getElementById('code' + item).value = (stock[0].avgqty + conversion_ratio).toFixed(2)
                    el.innerHTML = (Math.floor(stock[0].avgqty + conversion_ratio)).toLocaleString() + " " + selectedText
                    if (document.getElementById('voucherno').value) {


                    }

                } else if (convertiontype == "-") {
                    document.getElementById('code' + item).value = (stock[0].avgqty - conversion_ratio).toFixed(2)
                    el.innerHTML = (Math.floor(stock[0].avgqty - conversion_ratio)).toLocaleString() + " " + selectedText
                    if (document.getElementById('voucherno').value) {


                    }

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
                        if (document.getElementById('voucherno').value) {

                            let totalcountpu = 0;
                            mas = document.getElementById('mconversion_ratio' + item).value * document.getElementById('puqty' + item).value / document.getElementById('mconversion_ratio' + item).value;
                            let subcountpu = 0;
                            sub = document.getElementById('mconversion_ratio' + item).value * document.getElementById('puqty' + item).value % document.getElementById('mconversion_ratio' + item).value


                            mas2 = Math.floor((mas).toLocaleString());
                            if (isNaN(mas2)) {
                                mas = Number(mas).toFixed(6);
                                totalcountpu = (Math.floor(mas)).toLocaleString()
                            } else {
                                totalcountpu = mas2

                            }

                            sub2 = Math.floor((sub).toLocaleString());
                            if (isNaN(sub2)) {
                                sub = Number(sub).toFixed(6);
                                subcountpu = (Math.floor(sub)).toLocaleString()
                            } else {
                                subcountpu = sub2

                            }
                            el2.innerHTML = (totalcountpu + document.getElementById('bd' + item).value + " " + subcountpu + document.getElementById('ad' + item).value).toLocaleString();



                            let totalcountar = 0;
                            mas = document.getElementById('mconversion_ratio' + item).value * document.getElementById('arqty' + item).value / document.getElementById('mconversion_ratio' + item).value;
                            let subcountar = 0;
                            sub = document.getElementById('mconversion_ratio' + item).value * document.getElementById('arqty' + item).value % document.getElementById('mconversion_ratio' + item).value;


                            mas2 = Math.floor((mas).toLocaleString());
                            if (isNaN(mas2)) {
                                mas = Number(mas).toFixed(6);
                                totalcountar = (Math.floor(mas)).toLocaleString()
                            } else {
                                totalcountar = mas2

                            }

                            sub2 = Math.floor((sub).toLocaleString());
                            if (isNaN(sub2)) {
                                sub = Number(sub).toFixed(6);
                                subcountar = (Math.floor(sub)).toLocaleString()
                            } else {
                                subcountar = sub2

                            }
                            el3.innerHTML = (totalcountar + document.getElementById('bd' + item).value + " " + subcountar + document.getElementById('ad' + item).value).toLocaleString();



                            let totalcountpe = 0;
                            mas = document.getElementById('mconversion_ratio' + item).value * document.getElementById('penqty' + item).value / document.getElementById('mconversion_ratio' + item).value;
                            let subcountpe = 0;
                            sub = document.getElementById('mconversion_ratio' + item).value * document.getElementById('penqty' + item).value % document.getElementById('mconversion_ratio' + item).value;


                            mas2 = Math.floor((mas).toLocaleString());
                            if (isNaN(mas2)) {
                                mas = Number(mas).toFixed(6);
                                totalcountpe = (Math.floor(mas)).toLocaleString()
                            } else {
                                totalcountpe = mas2

                            }

                            sub2 = Math.floor((sub).toLocaleString());
                            if (isNaN(sub2)) {
                                sub = Number(sub).toFixed(6);
                                subcountpe = (Math.floor(sub)).toLocaleString()
                            } else {
                                subcountpe = sub2

                            }
                            el4.innerHTML = (totalcountpe + document.getElementById('bd' + item).value + " " + subcountpe + document.getElementById('ad' + item).value).toLocaleString();



                            // let totalcountpu = Math.floor();
                            // let subcountpu = (Math.floor(document.getElementById('mconversion_ratio' + item).value * document.getElementById('puqty' + item).value % document.getElementById('mconversion_ratio' + item).value)).toLocaleString();

                            // // let totalcountar = Math.floor(document.getElementById('mconversion_ratio' + item).value * document.getElementById('arqty' + item).value / document.getElementById('mconversion_ratio' + item).value);
                            // let subcountar = (Math.floor(document.getElementById('mconversion_ratio' + item).value * document.getElementById('arqty' + item).value % document.getElementById('mconversion_ratio' + item).value)).toLocaleString();

                            // let totalcountpe = Math.floor(document.getElementById('mconversion_ratio' + item).value * document.getElementById('penqty' + item).value / document.getElementById('mconversion_ratio' + item).value);
                            // let subcountpe = (Math.floor(document.getElementById('mconversion_ratio' + item).value * document.getElementById('penqty' + item).value % document.getElementById('mconversion_ratio' + item).value)).toLocaleString();


                            document.getElementById('codepuqty' + item).value = totalcountpu
                            document.getElementById('codearqty' + item).value = totalcountar
                            document.getElementById('codepenqty' + item).value = totalcountpe
                        }


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

    function getActiveProduct(productId, item, needToFreeeze) {
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

        if (needToFreeeze) {
            $productDropdown.prop('disabled', true);

        } else {
            $productDropdown.prop('disabled', false);

        }
    }




    function getActiveStore(storeId) {
        var $storeDropdown = $('#store');
        $storeDropdown.empty();
        $storeDropdown.append('<option value="" disabled selected>Select store</option>'); // Add default option

        $.each(stores, function(index, store) {
            $storeDropdown.append('<option value="' + store.id + '">' + store.name + '</option>');
            if (store.default == 1) {
                $storeDropdown.val(store.id)

            }
        });

        if (storeId > 0) {
            {
                $storeDropdown.val(storeId)
            }
        }
    }

    function getType(typeId, voucherno) {
        var $typeDropdown = $('#type');
        $typeDropdown.empty();
        $typeDropdown.append('<option value="" disabled selected>Select Type</option>'); // Add default option
        $typeDropdown.append('<option value="purchase">Purchase</option>');
        $typeDropdown.append('<option value="salesreturn">Sales Return</option>');
        $typeDropdown.append('<option value="storetransfer">Store Transfer</option>');
        if (typeId != "" && type.type != "") {
            {
                $typeDropdown.val(typeId)

                $.ajax({
                    url: $('#baseUrl2').val() + 'stock/stock/getVoucherNo',
                    type: 'POST',
                    data: {
                        store: document.getElementById('store').value,
                        type2: type2,
                        type: type.type
                    },
                    success: function(response) {

                        if (response != "") {
                            let vouchers = JSON.parse(response);
                            getVoucher(vouchers, voucherno, type.type)
                        }

                    },
                    error: function(error) {
                        console.log(error)
                    }
                });
            }
        }
    }

    // function getBatchDropdown(batches, item, value, needToFreeeze) {
    //     var $batchDropdown = $('#batch' + item);
    //     $batchDropdown.empty();
    //     $batchDropdown.append('<option value="" disabled selected>Select Batch</option>'); // Add default option
    //     $batchDropdown.append('<option value="0">Default</option>');



    //     $.each(batches, function(index, batch) {
    //         $batchDropdown.append('<option value="' + batch.id + '">' + batch.batchid + '</option>');


    //         // let opening = stockbatchopening ? stockbatchopening.find(ope => ope.batch == batch.id && ope.product == $('#product' + item).val()) : undefined;
    //         // if (document.getElementById('type').value === "openingstock") {
    //         //     if (opening == undefined) {
    //         //         $batchDropdown.append('<option value="' + batch.id + '">' + batch.batchid + '</option>');
    //         //     }

    //         // } else {
    //         //     if (opening != undefined) {
    //         //         $batchDropdown.append('<option value="' + batch.id + '">' + batch.batchid + '</option>');
    //         //     }
    //         // }
    //     });
    //     $batchDropdown.val(value)

    //     if (needToFreeeze) {
    //         $batchDropdown.prop('disabled', true);

    //     } else {
    //         $batchDropdown.prop('disabled', false);

    //     }
    // }

    function getBatchDropdown(batches, item, value, product, batchtype, needToFreeeze) {


        $.ajax({
            url: $('#base_url').val() + 'stock/stock/getBatchbyProductAndBatchtype2',
            type: 'POST',
            data: {
                product: product,
                batchtype: batchtype,
                 id:id
            },
            success: function(response2) {
                var $batchDropdown = $('#batch' + item);
                $batchDropdown.empty();
                $batchDropdown.append('<option value="" disabled selected>Select Batch</option>'); // Add default option
                if (response2 != "not") {
                    let batches2 = JSON.parse(response2);
                    $.each(batches2, function(index, batch) {
                        $batchDropdown.append('<option value="' + batch.id + '">' + batch.batchid + '</option>');
                    });
                }
                $batchDropdown.val(value)
                if (needToFreeeze) {
                    $batchDropdown.prop('disabled', true);

                } else {
                    $batchDropdown.prop('disabled', false);

                }



            },
            error: function(error) {
                console.log(error)
            }
        });




    }

    function getActiveSubUnit(productId, item) {
        $.ajax({
            url: $('#baseUrl2').val() + 'product/product/active_subunitsbyproductId',
            type: 'POST',
            data: {
                product_id: productId
            },
            success: function(response) {
                // alert("Invoice Details Updated Successfully")
                // window.location.href = $('#base_url').val() + 'invoice_list';
                datas = JSON.parse(response);
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

    function getActiveSubUnitEdit(productId, item, value, conversion_id, conversion_ratio, cconvertiontype, avstock, quantity, arquatity, purchasedetailid) {
        $.ajax({
            url: $('#baseUrl2').val() + 'product/product/active_subunitsbyproductId',
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



                $subunitDropdown.empty();
                $subunitDropdown.append('<option value="" disabled selected>Select unit</option>'); // Add default option
                $subunitDropdown.append('<option value="' + datas[0].unit + '">' + datas[0].name2 + '</option>');

                $.each(datas, function(index, store) {
                    if (store.unit_id) {
                        $subunitDropdown.append('<option value="' + store.unit_id + '">' + store.unit_name + '</option>');
                    }
                });

                $subunitDropdown.val(value)


                let select = document.getElementById('unit' + item);
                let selectedText = select.options[select.selectedIndex].text;
                let el = document.getElementById('codetype' + item);
                el.style.color = 'black';
                el.style.fontWeight = 'bold';


                if (value == datas[0].unit) {
                    document.getElementById('bd' + item).value = datas[0].name2
                    const found = datas.find(data => data.first == 1);

                    document.getElementById('ad' + item).value =
                        found ? found.unit_name : selectedText;
                } else {
                    document.getElementById('bd' + item).value = datas[0].name2
                    document.getElementById('ad' + item).value = selectedText

                }





                if (conversion_ratio != null) {
                    avstock = avstock * conversion_ratio
                    el.innerHTML = (Math.floor(avstock)).toLocaleString() + " " + selectedText
                } else {
                    let sub2 = Math.floor((parseFloat(avstock)).toLocaleString());
                    if (isNaN(sub2)) {
                        avstock = Number(avstock).toFixed(6);
                        el.innerHTML = (Math.floor(avstock)).toLocaleString() + " " + selectedText
                    } else {
                        el.innerHTML = sub2 + " " + selectedText

                    }
                }
                let el2 = document.getElementById('codeputype' + item);
                el2.style.color = 'blue';
                el2.style.fontWeight = 'bold';

                let el3 = document.getElementById('codeartype' + item);
                el3.style.color = 'green';
                el3.style.fontWeight = 'bold';

                let el4 = document.getElementById('codepentype' + item);
                el4.style.color = 'red';
                el4.style.fontWeight = 'bold';
                arquatity = arquatity == null ? 0 : arquatity;
                let peqty = 0;

                if (conversion_ratio != null) {
                    quantity = conversion_ratio * quantity
                    arquatity = conversion_ratio * arquatity
                    peqty = quantity - arquatity;
                } else {
                    peqty = quantity - arquatity;
                }

                if (purchasedetailid) {
                    document.getElementById('codepuqty' + item).value = quantity
                    document.getElementById('codearqty' + item).value = arquatity
                    document.getElementById('codepenqty' + item).value = peqty



                    sub2 = Math.floor((parseFloat(quantity)).toLocaleString());
                    if (isNaN(sub2)) {
                        quantity = Number(quantity).toFixed(6);
                        el2.innerHTML = (Math.floor(quantity)).toLocaleString() + " " + selectedText
                    } else {
                        el2.innerHTML = sub2 + " " + selectedText

                    }

                    sub2 = Math.floor((parseFloat(arquatity)).toLocaleString());
                    if (isNaN(sub2)) {
                        quantity = Number(arquatity).toFixed(6);
                        el3.innerHTML = (Math.floor(arquatity)).toLocaleString() + " " + selectedText
                    } else {
                        el3.innerHTML = sub2 + " " + selectedText

                    }

                    sub2 = Math.floor((parseFloat(peqty)).toLocaleString());
                    if (isNaN(sub2)) {
                        quantity = Number(peqty).toFixed(6);
                        el4.innerHTML = (Math.floor(peqty)).toLocaleString() + " " + selectedText
                    } else {
                        el4.innerHTML = sub2 + " " + selectedText

                    }

                }


                // el2.innerHTML = Math.floor(quantity) + " " + selectedText
                // el3.innerHTML = Math.floor(arquatity) + " " + selectedText
                // el4.innerHTML = Math.floor(peqty) + " " + selectedText





                if (value == datas[0].unit) {

                    $.ajax({
                        url: $('#baseUrl2').val() + 'stock/stock/getproductSubUnitPrimary',
                        type: 'POST',
                        data: {
                            prodid: productId,
                        },
                        success: function(response2) {
                            if (response2 != "null") {

                                let product2 = JSON.parse(response2); //console.log(adjStocks[i].actualstock*product2[0].conversion_ratio)
                                // document.getElementById('code' + item).value = avstock * product2[0].conversion_ratio;
                                document.getElementById('mconversion_ratio' + item).value = product2[0].conversion_ratio

                                // document.getElementById('bd' + item).value = selectedText
                                // document.getElementById('ad' + item).value = product2[0].unit_name
                                let el = document.getElementById('codetype' + item);
                                el.style.color = 'black';
                                el.style.fontWeight = 'bold';
                                el.innerHTML = ""
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

                                document.getElementById('code' + item).value = avstock == null ? 0 : totalcount;
                                el.innerHTML = (totalcount + document.getElementById('bd' + item).value + " " + subcount + document.getElementById('ad' + item).value).toLocaleString();


                                


                                if (purchasedetailid) {
                                    let totalcountpu = 0;
                                    mas = document.getElementById('mconversion_ratio' + item).value * quantity / document.getElementById('mconversion_ratio' + item).value;
                                    let subcountpu = 0;
                                    sub = document.getElementById('mconversion_ratio' + item).value * quantity % document.getElementById('mconversion_ratio' + item).value


                                    mas2 = Math.floor((mas).toLocaleString());
                                    if (isNaN(mas2)) {
                                        mas = Number(mas).toFixed(6);
                                        totalcountpu = (Math.floor(mas)).toLocaleString()
                                    } else {
                                        totalcountpu = mas2

                                    }

                                    sub2 = Math.floor((sub).toLocaleString());
                                    if (isNaN(sub2)) {
                                        sub = Number(sub).toFixed(6);
                                        subcountpu = (Math.floor(sub)).toLocaleString()
                                    } else {
                                        subcountpu = sub2

                                    }
                                    el2.innerHTML = (totalcountpu + document.getElementById('bd' + item).value + " " + subcountpu + document.getElementById('ad' + item).value).toLocaleString();


                                    let totalcountar = 0;
                                    mas = document.getElementById('mconversion_ratio' + item).value * arquatity / document.getElementById('mconversion_ratio' + item).value;
                                    let subcountar = 0;
                                    sub = document.getElementById('mconversion_ratio' + item).value * arquatity % document.getElementById('mconversion_ratio' + item).value;


                                    mas2 = Math.floor((mas).toLocaleString());
                                    if (isNaN(mas2)) {
                                        mas = Number(mas).toFixed(6);
                                        totalcountar = (Math.floor(mas)).toLocaleString()
                                    } else {
                                        totalcountar = mas2

                                    }

                                    sub2 = Math.floor((sub).toLocaleString());
                                    if (isNaN(sub2)) {
                                        sub = Number(sub).toFixed(6);
                                        subcountar = (Math.floor(sub)).toLocaleString()
                                    } else {
                                        subcountar = sub2

                                    }
                                    el3.innerHTML = (totalcountar + document.getElementById('bd' + item).value + " " + subcountar + document.getElementById('ad' + item).value).toLocaleString();


                                    let totalcountpe = 0;
                                    mas = document.getElementById('mconversion_ratio' + item).value * peqty / document.getElementById('mconversion_ratio' + item).value;
                                    let subcountpe = 0;
                                    sub = document.getElementById('mconversion_ratio' + item).value * peqty % document.getElementById('mconversion_ratio' + item).value;


                                    mas2 = Math.floor((mas).toLocaleString());
                                    if (isNaN(mas2)) {
                                        mas = Number(mas).toFixed(6);
                                        totalcountpe = (Math.floor(mas)).toLocaleString()
                                    } else {
                                        totalcountpe = mas2

                                    }

                                    sub2 = Math.floor((sub).toLocaleString());
                                    if (isNaN(sub2)) {
                                        sub = Number(sub).toFixed(6);
                                        subcountpe = (Math.floor(sub)).toLocaleString()
                                    } else {
                                        subcountpe = sub2

                                    }
                                    el4.innerHTML = (totalcountpe + document.getElementById('bd' + item).value + " " + subcountpe + document.getElementById('ad' + item).value).toLocaleString();


                                }



                                // let totalcount = Math.floor(document.getElementById('mconversion_ratio' + item).value * avstock / document.getElementById('mconversion_ratio' + item).value);
                                // let subcount = (Math.floor(document.getElementById('mconversion_ratio' + item).value * avstock % document.getElementById('mconversion_ratio' + item).value)).toLocaleString();

                                // let totalcountpu = Math.floor(document.getElementById('mconversion_ratio' + item).value * quantity / document.getElementById('mconversion_ratio' + item).value);
                                // let subcountpu = (Math.floor(document.getElementById('mconversion_ratio' + item).value * quantity % document.getElementById('mconversion_ratio' + item).value)).toLocaleString();

                                // let totalcountar = Math.floor(document.getElementById('mconversion_ratio' + item).value * arquatity / document.getElementById('mconversion_ratio' + item).value);
                                // let subcountar = (Math.floor(document.getElementById('mconversion_ratio' + item).value * arquatity % document.getElementById('mconversion_ratio' + item).value)).toLocaleString();

                                // let totalcountpe = Math.floor(document.getElementById('mconversion_ratio' + item).value * peqty / document.getElementById('mconversion_ratio' + item).value);
                                // let subcountpe = (Math.floor(document.getElementById('mconversion_ratio' + item).value * peqty % document.getElementById('mconversion_ratio' + item).value)).toLocaleString();

                                // document.getElementById('code' + item).value = avstock == null ? 0 : totalcount;
                                // el2.innerHTML = (totalcountpu + document.getElementById('bd' + item).value + " " + subcountpu + document.getElementById('ad' + item).value).toLocaleString();
                                // el3.innerHTML = (totalcountar + document.getElementById('bd' + item).value + " " + subcountar + document.getElementById('ad' + item).value).toLocaleString();
                                // el4.innerHTML = (totalcountpe + document.getElementById('bd' + item).value + " " + subcountpe + document.getElementById('ad' + item).value).toLocaleString();


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
                        url: $('#baseUrl2').val() + 'stock/stock/getproductSubUnitPrimary',
                        type: 'POST',
                        data: {
                            prodid: productId,
                        },
                        success: function(response2) {
                            if (response2 != "null") {

                                let product2 = JSON.parse(response2); //console.log(adjStocks[i].actualstock*product2[0].conversion_ratio)
                                // document.getElementById('code' + item).value = avstock * product2[0].conversion_ratio;
                                document.getElementById('mconversion_ratio' + item).value = product2[0].conversion_ratio
                                // document.getElementById('bd' + item).value = datas[0].name2
                                // document.getElementById('ad' + item).value = product2[0].unit_name
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
        // if (unitname == "S") {
        $.ajax({
            url: $('#baseUrl2').val() + 'stock/stock/conversion',
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

                    avStock(item, document.getElementById('product' + item).value, document.getElementById('batch' + item).value,
                        datas[0].convertiontype, datas[0].conversion_ratio)
                } else {
                    // alert("Conversion not found")
                    getActiveSubUnit(document.getElementById('product' + item).value, item)
                    avStock(item, document.getElementById('product' + item).value, document.getElementById('batch' + item).value, "", "")

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

    function invoicetype() {
        if (document.getElementById('type').value === "purchase") {
            return 1;
        } else if (document.getElementById('type').value === "salesreturn") {
            return 2;
        } else {
            return 0;
        }
    }
</script>