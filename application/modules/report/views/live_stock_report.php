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
                <div class="form-group">
                    <label for="product">Batch Type</label>
                    <div class="input-group mr-4" style="width: 250px;">
                        <select name="product" class="form-control" style="width: 250px;" id="batchtype" onchange="selectBatchType()">
                            <option value=""></option>
                            <option value="1">Single</option>
                            <option value="2">Multiple</option>
                            <!-- <option value="3">Both</option> -->

                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="productgroup">Product Group</label>
                    <div class="input-group mr-4" style="width: 250px;">
                        <select name="productgroup" class="form-control" style="width: 250px;" id="productgroup" onchange="onProductGroupChange()">
                            <option value="">--select one--</option>
                            <?php foreach ($product_group_list as $group) { ?>
                                <option value="<?php echo $group['id']; ?>"><?php echo $group['name']; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="product"><?php echo display('product') ?></label>
                    <div class="input-group mr-4" style="width: 250px;">
                        <select name="product" class="form-control" style="width: 250px;" id="product" onchange="selectProduct()">
                            <option value=""></option>
                            <?php foreach ($product_list as $productss) { ?>
                                <option value="<?php echo  $productss['id'] ?>"
                                    <?php ?>>
                                    <?php echo  $productss['product_name'] ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="product">Batch Name</label>
                    <div class="input-group mr-4" style="width: 250px;">
                        <select name="batch" class="form-control" style="width: 250px;" id="batch">
                            <option value=""></option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="category"><?php echo display('category') ?></label>
                    <div class="input-group mr-4" style="width: 250px;">

                        <select name="category" class="form-control" id="category">
                            <option value="">--select one -- </option>
                            <?php
                            foreach ($category_list as $category) {
                            ?>
                                <option value="<?php echo $category['category_id']; ?>" <?php if ($category['category_id'] == $category_id) {
                                                                                            echo 'selected';
                                                                                        } ?>><?php echo $category['category_name']; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="store"><?php echo display('store') ?></label>
                    <div class="input-group mr-4" style="width: 250px;">

                        <select name="store" class="form-control" id="store">
                            <option value="">--select one -- </option>

                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="stocktype">Stock Type</label>
                    <div class="input-group mr-4" style="width: 250px;">

                        <select name="stocktype" class="form-control" id="stocktype">
                            <option value="">--select one -- </option>
                            <option value="all">All</option>
                            <option value="actualstock">Actual Stock</option>
                            <option value="physicalstock">Physical Stock</option>

                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="stockunittype">Unit Type</label>
                    <div class="input-group mr-4" style="width: 250px;">

                        <select name="stockunittype" class="form-control" id="stockunittype">
                            <option value="">--select one -- </option>
                            <option value="master" selected>Master Unit</option>
                            <option value="sub">Sub Unit</option>

                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="statusfilter">Stock Level</label>
                    <div class="input-group mr-4" style="width: 250px;">
                        <select name="statusfilter" class="form-control" id="statusfilter">
                            <option value="">--All Status--</option>
                            <option value="Out of Stock">Out of Stock</option>
                            <option value="Reorder">Reorder</option>
                            <option value="Near Order">Near Order</option>
                            <option value="Sufficient Stock">Sufficient Stock</option>
                            <option value="Overstock">Overstock</option>
                        </select>
                    </div>
                </div>


                <button type="button" id="btn-filter" class="btn btn-success" onclick="onFilterButtonClick()">
                    Generate Report
                </button>
            </div>
        </div>
    </div>
</div>

<input type="hidden" name="baseUrl2" id="baseUrl2" class="baseUrl" value="<?php echo base_url(); ?>" />

<script src="<?php echo base_url('my-assets/js/admin_js/sales_report.js') ?>" type="text/javascript"></script>
<script>
    $(document).ready(function() {
        getStoreDropdown(0);
    });

    function selectBatchType() {
        var batchtype = document.getElementById('batchtype').value;

        if (batchtype == 1) {
            document.getElementById("category").disabled = true;
        } else {
            document.getElementById("category").disabled = false;
        }

        if (batchtype) {
            document.getElementById("productgroup").value = "";
            document.getElementById("productgroup").disabled = true;
        } else {
            document.getElementById("productgroup").disabled = false;
        }

        getProductDropdown();
        getBatchDropdown();
    }

    function onProductGroupChange() {
        var groupId = document.getElementById('productgroup').value;

        if (groupId) {
            document.getElementById("batchtype").value = "";
            document.getElementById("batchtype").disabled = true;
            document.getElementById("batch").innerHTML = '<option value="">--select one--</option>';
            document.getElementById("batch").disabled = true;

            $.ajax({
                url: $('#base_url').val() + 'report/report/getProductsByGroup',
                type: 'POST',
                data: { group_id: groupId },
                success: function(response) {
                    var $productDropdown = $('#product');
                    $productDropdown.empty();
                    $productDropdown.append('<option value="">--select one--</option>');
                    var products = JSON.parse(response);
                    $.each(products, function(index, p) {
                        $productDropdown.append('<option value="' + p.id + '">' + p.product_name + '</option>');
                    });
                },
                error: function(error) {
                    console.log(error);
                }
            });
        } else {
            document.getElementById("batchtype").disabled = false;
            document.getElementById("batch").disabled = false;
        }
    }

    function getProductDropdown() {


        $.ajax({
            url: $('#base_url').val() + 'stock/stock/usage_productjson',
            type: 'POST',
            data: {
                batchtype: document.getElementById('batchtype').value
            },
            success: function(response2) {
                var $batchDropdown = $('#product');
                $batchDropdown.empty();
                $batchDropdown.append('<option value="" disabled selected>Select Batch</option>'); // Add default option
                if (response2 != "not") {
                    let batches2 = JSON.parse(response2);
                    $.each(batches2, function(index, batch) {
                        $batchDropdown.append('<option value="' + batch.id + '">' + batch.product_name + '</option>');
                    });
                }




            },
            error: function(error) {
                console.log(error)
            }
        });




    }

    function selectProduct() {

        if (document.getElementById('batchtype').value) {
            getBatchDropdown()
        }


    }



    function getBatchDropdown() {


        $.ajax({
            url: $('#base_url').val() + 'stock/stock/getBatchbyBatchtype',
            type: 'POST',
            data: {
                batchtype: document.getElementById('batchtype').value,
                product: document.getElementById('product').value
            },
            success: function(response2) {

                var $batchDropdown = $('#batch');
                $batchDropdown.empty();
                $batchDropdown.append('<option value="" disabled selected>Select Batch</option>'); // Add default option
                $batchDropdown.append('<option value="0">Default</option>');
                if (response2 != "not") {
                    let batches2 = JSON.parse(response2);
                    console.log(batches2)
                    $.each(batches2, function(index, batch) {
                        $batchDropdown.append('<option value="' + batch.id + '">' + batch.batchid + '</option>');
                    });
                }




            },
            error: function(error) {
                console.log(error)
            }
        });




    }

    function getStoreDropdown(branchId) {

        var base_url = $('#base_url').val();

        $.ajax({
            type: "post",
            url: base_url + "store/store/getstorebyuserid",
            data: {
                // is_credit_edit: is_credit_edit,
                // csrf_test_name: csrf_test_name
            },
            success: function(data3) {
                var stores = JSON.parse(data3);
                var $storeDropdown = $('#store');
                $storeDropdown.empty();
                $storeDropdown.append('<option value="" disabled selected>Select Store</option>'); // Add default option

                $.each(stores, function(index, store) {
                    $storeDropdown.append('<option value="' + store.id + '">' + store.name + '</option>');
                    if (store.default != 0) {
                        $storeDropdown.val(store.id)
                    }
                });
            }
        });
    }

    function onFilterButtonClick() {
        let type = "";

        if (!$('#stockunittype').val()) {
            alert("Please select Unit Type");
            return;
        }

        if (document.getElementById('batchtype').value == 1) {
            if (!document.getElementById('product').value) {
                alert("Please select Product");
                return;
            }
        }
        $.ajax({
            type: "post",
            url: $('#baseUrl2').val() + 'report/report/livestock_reportdata',
            data: {
                product: $('#product').val(),
                product_group: $('#productgroup').val(),
                category: $('#category').val(),
                store: $('#store').val(),
                batch: $('#batch').val(),
                stocktype: $('#stocktype').val()
            },
            success: function(data1) {
                datas = JSON.parse(data1);
                actual = JSON.parse(data1);

                var selectedStatus = $('#statusfilter').val();
                if (selectedStatus) {
                    actual = actual.filter(row => getStockStatusByLevels(row) === selectedStatus);
                    datas = datas.filter(row => getStockStatusByLevels(row) === selectedStatus);
                }

                console.log(actual)
                if (datas.length != 0) {
                    if ($('#stockunittype').val() == "master") {
                        datas.forEach(data => {
                             data.avqtymain=data.avqty,
                            data.avqty = convertmasterstock(data.avqty, data.conversion_ratio, data.master, data.sub)
                            data.inqty = convertmasterstock(data.inqty, data.conversion_ratio, data.master, data.sub)
                            data.outqty = convertmasterstock(Math.abs(data.outqty), data.conversion_ratio, data.master, data.sub)
                            data.pavqty = convertmasterstock(data.pavqty, data.conversion_ratio, data.master, data.sub)
                            data.pinqty = convertmasterstock(data.pinqty, data.conversion_ratio, data.master, data.sub)
                            data.poutqty = convertmasterstock(Math.abs(data.poutqty), data.conversion_ratio, data.master, data.sub)
                            data.stockunittype = $('#stockunittype').val()
                        });
                    } else {
                        datas.forEach(data => {
                            data.avqtymain= convertsubstock(data.avqty, data.conversion_ratio, data.sub),
                            data.avqty = convertsubstock(data.avqty, data.conversion_ratio, data.sub) + " " + data.sub
                            data.inqty = convertsubstock(data.inqty, data.conversion_ratio, data.sub)  + " " + data.sub
                            data.outqty = convertsubstock(Math.abs(data.outqty), data.conversion_ratio, data.sub) + " " + data.sub
                            data.pavqty = convertsubstock(data.pavqty, data.conversion_ratio, data.sub) + " " + data.sub
                            data.pinqty = convertsubstock(data.pinqty, data.conversion_ratio, data.sub) + " " + data.sub
                            data.poutqty = convertsubstock(Math.abs(data.poutqty), data.conversion_ratio, data.sub) + " " + data.sub
                            data.stockunittype = $('#stockunittype').val() 
                        });
                    }
                    console.log(datas)
                    $.ajax({
                        type: "post",
                        url: $('#baseUrl2').val() + 'report/report/set_stock_session',
                        data: {
                            datas: datas,
                        },
                        success: function(data1) {
                            window.open(`generate_stockreport`, '_blank');
                        }
                    });

                } else {
                    alert("There is no data available for the selected parameters.")
                }




            }
        });

    }

    function getStockStatusByLevels(row) {
        var reserve = Number(row.reserve_stock_level || 0);
        var reorder = Number(row.reorder_stock_level || 0);
        var min = Number(row.min_stock_level || 0);
        var max = Number(row.max_stock_level || 0);

        // Keep threshold order safe in case configuration was entered out of sequence.
        reorder = Math.max(reorder, reserve);
        min = Math.max(min, reorder);
        max = Math.max(max, min);

        var qty = 0;
        if (row.avqty !== null && row.avqty !== undefined && row.avqty !== '') {
            qty = Number(row.avqty || 0);
        } else if (row.pavqty !== null && row.pavqty !== undefined && row.pavqty !== '') {
            qty = Number(row.pavqty || 0);
        }

        if (qty <= reserve) {
            return "Out of Stock";
        }
        if (qty <= reorder) {
            return "Reorder";
        }
        if (qty <= min) {
            return "Near Order";
        }
        if (qty <= max) {
            return "Sufficient Stock";
        }

        return "Overstock";
    }

    function convertmasterstock(avstock, conversion_ratio, mastername, subname) {

        
        if(!subname&&!conversion_ratio){
            
                return ((avstock?avstock:0) + mastername);

            

        }
        let totalcount = 0;
        let mas = conversion_ratio * avstock / conversion_ratio;
        let subcount = 0;
        let sub = conversion_ratio * avstock % conversion_ratio;

        let mas2 = Math.floor((mas).toLocaleString());
        if (isNaN(mas2)) {
            mas = Number(mas).toFixed(6);
            totalcount = (Math.floor(mas)).toLocaleString()
        } else {
            totalcount = mas2

        }

        let sub2 = Math.floor((Number(sub).toFixed(6)).toLocaleString());
        if (isNaN(sub2)) {
            sub = Number(sub).toFixed(6);
            subcount = (Math.floor(sub)).toLocaleString()
        } else {
            subcount = sub2

        }

        if (isNaN(totalcount)) {
            totalcount = 0
        }

        if (isNaN(subcount)) {
            subcount = 0
        }

        return (totalcount + mastername + " " + subcount + subname);
    }

   function convertsubstock(avstock, conversion_ratio, subname) {
        let sub = avstock * conversion_ratio;
        let sub2 = Math.floor((sub).toLocaleString());
        if (isNaN(sub2)) {
            sub = Number(sub).toFixed(6);
            return (Math.floor(sub)).toLocaleString() 
        } else {
            return sub2 

        }
    }
</script>