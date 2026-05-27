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
                <div class="form-group">
                    <label for="store">Store</label>
                    <div class="input-group mr-4" style="width: 250px;">
                        <select class="form-control" id="store" name="store" style="width: 250px;" tabindex="1">
                            <option value="">All Stores</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="category">Category</label>
                    <div class="input-group mr-4" style="width: 250px;">
                        <select class="form-control" id="category" name="category" style="width: 250px;" tabindex="2">
                            <option value="">All Categories</option>
                            <?php if (!empty($category_list)) { ?>
                                <?php foreach ($category_list as $category) { ?>
                                    <option value="<?php echo $category['category_id']; ?>">
                                        <?php echo $category['category_name']; ?></option>
                                <?php } ?>
                            <?php } ?>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="product">Product</label>
                    <div class="input-group mr-4" style="width: 250px;">
                        <select class="form-control" id="product" name="product" style="width: 250px;" tabindex="3">
                            <option value="">All Products</option>
                            <?php if (!empty($product_list)) { ?>
                                <?php foreach ($product_list as $product) { ?>
                                    <option value="<?php echo $product['id']; ?>"><?php echo $product['product_name']; ?>
                                    </option>
                                <?php } ?>
                            <?php } ?>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="supplier">Supplier</label>
                    <div class="input-group mr-4" style="width: 250px;">
                        <select class="form-control" id="supplier" name="supplier" style="width: 250px;" tabindex="4">
                            <option value="">All Suppliers</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="batch_type">Batch Type</label>
                    <div class="input-group mr-4" style="width: 250px;">
                        <select class="form-control" id="batch_type" name="batch_type" style="width: 250px;"
                            tabindex="5">
                            <option value="">All Batch Types</option>
                            <option value="1">Single</option>
                            <option value="2">Multiple</option>
                            <option value="3">Both</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="status">Status</label>
                    <div class="input-group mr-4" style="width: 250px;">
                        <select class="form-control" id="status" name="status" style="width: 250px;" tabindex="6">
                            <option value="">All Status</option>
                            <option value="not_expired">Not Expired</option>
                            <option value="to_be_expired">To be Expired</option>
                            <option value="expired">Expired</option>
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

<script>
    $(document).ready(function() {
        getStoreDropdown();
        getSupplierDropdown();
    });

    function getStoreDropdown() {
        var base_url = $('#base_url').val();
        $.ajax({
            type: "post",
            url: base_url + "store/store/getstorebyuserid",
            success: function(data) {
                var stores = JSON.parse(data);
                var $storeDropdown = $('#store');
                $.each(stores, function(index, store) {
                    $storeDropdown.append('<option value="' + store.id + '">' + store.name +
                        '</option>');
                });
            }
        });
    }

    function getSupplierDropdown() {
        var base_url = $('#base_url').val();
        $.ajax({
            type: "get",
            url: base_url + "report/report/getSuppliersForReport",
            success: function(data) {
                var suppliers = JSON.parse(data);
                var $supplierDropdown = $('#supplier');
                $.each(suppliers, function(index, supplier) {
                    $supplierDropdown.append('<option value="' + supplier.supplier_id + '">' + supplier
                        .supplier_name + '</option>');
                });
            }
        });
    }

    function onFilterButtonClick() {
        $.ajax({
            type: "post",
            url: $('#baseUrl2').val() + 'report/report/product_batch_summary_report_data',
            data: {
                store: $('#store').val(),
                category: $('#category').val(),
                product: $('#product').val(),
                supplier: $('#supplier').val(),
                batch_type: $('#batch_type').val(),
                status: $('#status').val()
            },
            success: function(data1) {
                var datas = JSON.parse(data1);
                console.log(datas)
                if (datas.length > 0) {
                    datas.forEach(data => {
                        data.avqty = convertmasterstock(data.master_stock_qty, data.conversion_ratio, data.master, data.sub)
                    });
                    console.log(datas)

                    $.ajax({
                        type: "post",
                        url: $('#baseUrl2').val() + 'report/report/set_stock_session2',
                        data: {
                            datas: datas,
                        },
                        success: function(data1) {
                            window.open(`generate_product_batch_summary_report`, '_blank');
                        }
                    });
                }  else {
                    alert("There is no data available for the selected parameters.");
                }

            }
        });
    }

    function convertmasterstock(avstock, conversion_ratio, mastername, subname) {

        if (!subname && !conversion_ratio) {

            return ((avstock ? avstock : 0) + mastername);



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

        let sub2 = Math.floor((sub).toLocaleString());
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
</script>