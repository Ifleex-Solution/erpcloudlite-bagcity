<script src="<?php echo base_url() ?>my-assets/js/admin_js/purchase.js" type="text/javascript"></script>

<style>
    .col-big {
        width: 25% !important;
    }

    .col-total {
        width: 20% !important;
    }

    .col-medium {
        width: 8% !important;
    }

    .vathidden {
        width: 8% !important;
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
                    <h4 id="title"><?php echo $title; ?></h4>
                </div>
            </div>

            <div class="panel-body">


                <div class="row">

                    <div class="col-sm-6">
                        <div class="form-group row">
                            <label for="date" class="col-sm-4 col-form-label">Product Group Id
                                <i class="text-danger">*</i>
                            </label>
                            <div class="col-sm-6">
                                <input type="text" tabindex="2" class="form-control" value="" id="product_group_id" value="<?php echo $product_group_id ?>" />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">

                    <div class="col-sm-6">
                        <div class="form-group row">
                            <label for="supplier_sss" class="col-sm-4 col-form-label">Product Group Name
                                <i class="text-danger">*</i>
                            </label>
                            <div class="col-sm-6">
                                <input type="text" tabindex="2" class="form-control" value="" id="product_group_name" />

                            </div>

                        </div>

                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group row">
                            <label for="invoicegroup" class="col-sm-4 col-form-label">Invoice Grouping<i class="text-danger">*</i></label>
                            <div class="col-sm-6">
                                <select class="form-control" id="invoicegroup" name="invoicegroup" required tabindex="-1" aria-hidden="true">
                                    <option value="">Select One</option>
                                     <option value="1" <?php echo ($product->status_label == "Inactive") ? 'selected' : ''; ?>>Enable</option>
                                    <option value="0" <?php echo ($product->status_label == "Active") ? 'selected' : ''; ?>>Disable</option>
                                </select>

                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group row">
                            <label for="status" class="col-sm-4 col-form-label">Status<i class="text-danger">*</i></label>
                            <div class="col-sm-6">
                                <select class="form-control" id="status" name="status" required tabindex="-1" aria-hidden="true">
                                    <option value="">Select One</option>
                                    <option value="1" <?php echo ($product->status_label == "Active") ? 'selected' : ''; ?>>Active</option>
                                    <option value="0" <?php echo ($product->status_label == "Inactive") ? 'selected' : ''; ?>>Inactive</option>
                                </select>

                            </div>
                        </div>
                    </div>
                </div>




                <br>
                <button type="button" id="add_invoice_item" class="btn btn-info" name="add-invoice-item"
                    onClick="addInputField('addinvoiceItem');" tabindex="9"><i class="fa fa-plus"></i>Add Product</button>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="saleTable">
                        <thead>
                            <tr>
                                <th class="text-center col-big">Product<i class="text-danger">*</i></th>
                                <th class="text-center col-big">Unit<i class="text-danger">*</i> </th>
                                <th class="text-center  col-big">Qty<i class="text-danger col-medium">*</i></th>
                                <th class="text-center  col-small ">Parent<i class="text-danger col-medium">*</i></th>
                                <th class="text-center"><?php echo display('action') ?></th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody id="addinvoiceItem">
                            <tr id="myRow1">
                                <td class="product_field">

                                    <select name="product[]" class="form-control" id="product1" tabindex="1" onchange="product_search(1,'product')">
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
                                    <select class="form-control" id="unit1" required name="unit1" onchange="product_search(1,'unit')" tabindex="3">
                                        <option value=""></option>
                                    </select>
                                    <input type="hidden" id="conversionid1" />
                                    <input type="hidden" id="conversiontype1" />
                                    <input type="hidden" id="conversion_ratio1" />
                                </td>




                                <td class="qty">
                                    <input type="text" name="product_quantity[]" id="qty1" min="0" class="form-control text-right store_cal_1" onkeyup="calculate_sum(1);" onchange="calculate_sum(1);" placeholder="0.00" value="" tabindex="6" />
                                </td>
                                <td class="text-center">
                                <input type="checkbox" id="primary1" onchange="primaryCheck(1)" style="transform: scale(1.5); margin-right:5px;" />

                                </td>

                                <td>

                                </td>

                            </tr>

                            <?php
                            for ($i = 2; $i <= 10; $i++) {
                            ?>
                                <tr id="myRow<?php echo $i; ?>">
                                    <td class="product_field">
                                        <select name="product[]" class="form-control" id="product<?php echo $i; ?>" tabindex="1" onchange="product_search(<?php echo $i; ?>, 'product')">
                                            <option value="">Select Product</option>
                                            <?php foreach ($products as $services) { ?>
                                                <option value="<?php echo $services['id']; ?>"><?php echo $services['product_name']; ?></option>
                                            <?php } ?>
                                        </select>
                                        <input type="hidden" id="mconversion_ratio<?php echo $i; ?>" />

                                        <input type="hidden" id="bd<?php echo $i; ?>" />
                                        <input type="hidden" id="ad<?php echo $i; ?>" />

                                    </td>




                                    <td class="qty">
                                        <select class="form-control" id="unit<?php echo $i; ?>" required name="unit<?php echo $i; ?>" onchange="product_search(<?php echo $i; ?>,'unit')" tabindex="3">
                                            <option value=""></option>
                                        </select>
                                        <input type="hidden" id="conversionid<?php echo $i; ?>" />
                                        <input type="hidden" id="conversiontype<?php echo $i; ?>" />
                                        <input type="hidden" id="conversion_ratio<?php echo $i; ?>" />
                                    </td>



                                    <td class="qty">
                                        <input type="text" name="product_quantity[]" id="qty<?php echo $i; ?>" min="0" class="form-control text-right store_cal_1" onkeyup="calculate_sum(<?php echo $i; ?>);" onchange="calculate_sum(<?php echo $i; ?>);" placeholder="0.00" value="" tabindex="6" />
                                    </td>
                                    <td class="text-center">
                                    <input type="checkbox" id="primary<?php echo $i; ?>" onclick="primaryCheck(<?php echo $i; ?>)" style="transform: scale(1.3); margin-right:5px;" />
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
                    </table>
                    <input type="hidden" name="finyear" value="<?php echo financial_year(); ?>">
                    <p hidden id="pay-amount"></p>
                    <p hidden id="change-amount"></p>
                </div>

                <div class="form-group row text-right">
                    <div class="col-sm-12 p-20">
                        <button id="save_add" class="btn btn-success" name="add-invoice" onclick="save()">
                            <?php
                            echo empty($id)
                                ? display('save')
                                : (empty($pagetype) ? display('update') : display('save'));
                            ?>
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
echo "let product_group_id=" . json_encode($product_group_id) . ";";
echo "let usertype=" . json_encode($this->session->userdata('user_level2')) . ";";
echo "</script>";
?>
<script>
    // $('body').addClass("sidebar-mini sidebar-collapse");

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

    if (!id) {
        document.getElementById('product_group_id').value = product_group_id
    }


    function addInputField(t) {
        // if (count < 11) {
        document.getElementById('myRow' + count).style.display = 'table-row';
        getActiveProduct(0, count)
        count = count + 1;

    }

    $(document).ready(function() {
        for (let j = 2; j <= 10; j++) {
            document.getElementById('myRow' + j).style.display = 'none';
        }

        document.querySelectorAll('.vathidden').forEach(el => {
            el.style.display = 'none';
        });


        
        if (id != null) {

                $.ajax({
                    url: $('#base_url').val() + 'product/product/getProductGroupById',
                    type: 'POST',
                    data: {
                        id: id
                      },
                    success: function(response) {

                        var sales = JSON.parse(response);
                        
                        var $incidenttypeDropdown = $('#status');
                        $incidenttypeDropdown.empty();
                        $incidenttypeDropdown.append('<option value="" disabled selected>Select Status</option>'); // Add default option
                        $incidenttypeDropdown.append('<option value="1">Active</option>');
                        $incidenttypeDropdown.append('<option value="0">Inactive</option>');
                        $incidenttypeDropdown.val(sales[0].status)

                        
                         var $incidenttypeDropdown = $('#invoicegroup');
                        $incidenttypeDropdown.empty();
                        $incidenttypeDropdown.append('<option value="" disabled selected>Select Invoice Group</option>'); // Add default option
                        $incidenttypeDropdown.append('<option value="1">Enable</option>');
                        $incidenttypeDropdown.append('<option value="0">Disable</option>');
                        $incidenttypeDropdown.val(sales[0].invoice_group)
                        document.getElementById('product_group_id').value = sales[0].groupcode;
                        document.getElementById('product_group_name').value = sales[0].name;



                      
                        count = 1;
                        for (let i = 0; i < sales.length; i++) {
                            let a = i + 1;
                            document.getElementById('myRow' + a).style.display = 'table-row';
                            getActiveProduct(sales[i].product, a);

                            document.getElementById('qty' + a).value = sales[i].qty;
                            document.getElementById('unit' + a).value = sales[i].unit;

                            getActiveSubUnitEdit(sales[i].product, a, sales[i].unit)
                            document.getElementById('primary' + a).checked = sales[i].parent==1;


                           
                            count = count + 1;
                        }
                    },
                    error: function(error) {
                        console.log(error);
                    }
                });
            } 
        
    });

    function primaryCheck(row) {

        
        for (let i = 1; i <= count; i++) {

            let checkbox = document.getElementById("primary" + i);

        
            if (i !== row) {
                checkbox.checked = false;
            } else {
                checkbox.checked =  checkbox.checked; 
            }

        }

    }


    function product_search(item, name) {

        if (name === "product") {
            document.getElementById('qty' + item).value = "";
            document.getElementById('unit' + item).value = "";
            $.ajax({
                url: $('#base_url').val() + 'stock/stock/getproduct',
                type: 'POST',
                data: {
                    prodid: document.getElementById('product' + item).value.toString(),
                },
                success: function(response) {
                    let product = JSON.parse(response);
                    getActiveSubUnit(document.getElementById('product' + item).value, item)
                    setTimeout(
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


                                //   document.getElementById('unit' + item).value = product[0].unit;
                            },
                            error: function(error) {
                                console.log(error)
                            }
                        }), 1000);
                    document.getElementById('unit' + item).value = product[0].unit;








                },
                error: function(error) {
                    console.log(error)
                }
            });
        }


    }





    function deleteRow(num) {
        document.getElementById('myRow' + num).style.display = 'none';

        document.getElementById('qty' + num).value = 0;

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














    function save() {
        arrItem = [];

        if(document.getElementById('invoicegroup').value==1){
            let countPrimary=0
        for (let j = 1; j < count; j++) {
            if (document.getElementById('myRow' + j).style.display != "none") {
                if(document.getElementById('primary' + j).checked){
                    countPrimary++
                }
             }

        }
         if(countPrimary==0){
             alert("Parent should be enable")
             return

        }


        }
        
       
        for (let i = 1; i < count; i++) {
            if (document.getElementById('myRow' + i).style.display != "none") {
                if (document.getElementById('product' + i).value == "") {
                    alert("Product shouldn't be empty")
                    return
                } else if (document.getElementById('qty' + i).value == "") {
                    alert("Quantity shouldn't be empty")
                    return

                } else if (document.getElementById('unit' + i).value == "") {
                    alert("Unit shouldn't be empty")
                    return

                } else if (document.getElementById('product_group_id').value == "") {
                    alert("Product Group Id shouldn't be empty")
                    return
                } else if (document.getElementById('product_group_name').value == "") {
                    alert("Product Group Name shouldn't be empty")
                    return
                } else if (document.getElementById('status').value == "") {
                    alert("Status shouldn't be empty")
                    return
                } else if (document.getElementById('invoicegroup').value == "") {
                    alert("Invoice Group shouldn't be empty")
                    return
                } 
                
                else {


                    qty = document.getElementById('qty' + i).value

                    arrItem.push({
                        product: document.getElementById('product' + i).value,
                        unit: document.getElementById('unit' + i).value,
                        parent: document.getElementById('primary' + i).checked ?1:0,
                        quantity: qty,
                    });
                }
            }

        }

        console.log(arrItem)

        $("#save_add").hide();

        if (id > 0 ) {
            $.ajax({
                url: $('#base_url').val() + 'product/product/update_productgroup',
                type: 'POST',
                data: {
                    id: id,
                    items: arrItem,
                    invoicegroup: document.getElementById('invoicegroup').value,
                    product_group_id: document.getElementById('product_group_id').value,
                    product_group_name: document.getElementById('product_group_name').value,
                    status: document.getElementById('status').value

                },
                success: function(response) {
                    // alert("Invoice Details Updated Successfully")
                    // window.location.href = $('#base_url').val() + 'invoice_list';

                    datas = JSON.parse(response);
                    $("#save_add").show();

                    if(datas=="already"){
                        alert("Product Group ID or Name already exists")


                    }else{
                        alert("Product Group Details Updated Successfully")
                        window.location.reload();
                    }
                   


                },
                error: function(error) {
                    console.log(error)
                }
            });


        } else {

            $.ajax({
                url: $('#base_url').val() + 'product/product/save_productgroup',
                type: 'POST',
                data: {
                    id: id,
                    items: arrItem,
                    invoicegroup: document.getElementById('invoicegroup').value,
                    product_group_id: document.getElementById('product_group_id').value,
                    product_group_name: document.getElementById('product_group_name').value,
                    status: document.getElementById('status').value

                },
                success: function(response) {
              

                    datas = JSON.parse(response);
                    $("#save_add").show();

                    if(datas=="already"){
                        alert("Product Group ID or Name already exists")
                    }else{
                        alert("Product Group Details saved Successfully")
                        window.location.reload();
                    }

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


            document.getElementById('myRow' + i).style.display = 'none';
            document.getElementById('qty' + i).value = "";
            document.getElementById('unit' + i).value = "";

        }
        document.getElementById('myRow1').style.display = 'table-row';
        document.getElementById('product_group_id').value = ""
        document.getElementById('product_group_name').value = ""
        document.getElementById('status').value = ""
    }

    function printRawHtml(view) {


        $(view).print({

            deferred: $.Deferred().done(function() {
                window.location.reload();
                window.location.href = $('#base_url').val() + 'add_invoice';

            })
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

    function getActiveSubUnitEdit(productId, item, value) {
        $.ajax({
            url: $('#base_url').val() + 'product/product/active_subunitsbyproductId',
            type: 'POST',
            data: {
                product_id: productId
            },
            success: function(response) {
                datas = JSON.parse(response);
                var $subunitDropdown = $('#unit' + item);
                $subunitDropdown.empty();
                $subunitDropdown.append('<option value="" disabled selected>Select unit</option>'); // Add default option
                $subunitDropdown.append('<option value="' + datas[0].unit + '">' + datas[0].name2 + '</option>');

                $.each(datas, function(index, store) {
                    if (store.unit_id) {
                        $subunitDropdown.append('<option value="' + store.unit_id + '">' + store.unit_name + '</option>');
                    }
                });

                $subunitDropdown.val(value)

            },
            error: function(error) {
                console.log(error)
            }
        });
    }
</script>