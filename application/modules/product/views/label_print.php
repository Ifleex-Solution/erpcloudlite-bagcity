<input type="hidden" name="baseUrl2" id="baseUrl2" class="baseUrl" value="<?php echo base_url(); ?>" />

<div class="row">
    <div class="col-sm-12">
        <div class="panel panel-bd lobidrag">

            <div class="panel-heading" >
                <div class="panel-title">
                    <h4>Barcode Print</h4>
                    <!-- <?php echo display('labelprint') ?> -->
                </div>
            </div>
            <div class="panel-body" >

                <!-- Trigger button for modal -->
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#productModal">
                    Add Product
                </button>
                <button type="button" class="btn btn-danger" onclick="deletewholeLabelPrint()">
                    Clear Details
                </button>
                <br /><br />
                <table id="dataTable" class="table1 table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th style="width: 10%;">No. of Labels</th>
                            <th style="width: 15%;">Product Code</th>
                            <th style="width: 20%;">Product Name</th>
                            <th style="width: 20%;">Category</th>
                            <th style="width: 15%;">Price</th>
                            <th style="width: 5%;"></th>

                        </tr>
                    </thead>
                    <tbody>
                        <!-- Data will be populated here -->
                    </tbody>
                </table>

                <!-- Modal Structure -->
                <div class="modal fade" id="productModal" tabindex="-1" role="dialog" aria-labelledby="categoryBrandModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document" style="width: 1000px;">
                        <div class="modal-content">
                            <div class="modal-header">
                                <p class="modal-title" id="categoryBrandModalLabel">Add Product</p>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <!-- Your form content -->
                                <div class="row">
                                    <div class="col-sm-5">
                                        <div class="form-group row">
                                            <label for="category_id" class="col-sm-4 col-form-label"><?php echo display('category') ?>
                                                <i class="text-danger">*</i></label>
                                            <div class="col-sm-8">
                                                <select class="form-control" id="category_id" required name="category_id" tabindex="4">
                                                    <option value=""></option>
                                                    <?php if ($category_list) { ?>
                                                        <?php foreach ($category_list as $categories) { ?>
                                                            <option value="<?php echo $categories['category_id'] ?>" <?php if ($product->category_id == $categories['category_id']) {
                                                                                                                            echo 'selected';
                                                                                                                        } ?>>
                                                                <?php echo $categories['category_code'] . '-' . $categories['category_name'] ?>
                                                            </option>
                                                    <?php }
                                                    } ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                  
                                    <div class="col-sm-2">
                                        <button type="button" class="btn btn-success" data-toggle="modal" onclick="findbyBrandCodeAndLabelCode()">
                                            Find
                                        </button>
                                    </div>


                                </div>
                                <div class="table-responsive">
                                    <table class="table-bordered" cellspacing="0" width="100%" id="productList">
                                        <thead>
                                            <tr>

                                                <th>Product Code</th>
                                                <th>Product Name</th>
                                                <th>Category</th>
                                                <th><?php echo display('price') ?></th>
                                                <th></th>

                                            </tr>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                    </table>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="model2" tabindex="-1" role="dialog" aria-labelledby="model2" aria-hidden="true">
                    <div class="modal-dialog" role="document" style="width: 300px;">
                        <div class="modal-content">
                            <div class="modal-header">
                                <p class="modal-title" id="model2">Add No Of Barcodes</p>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">

                                <input type="number" name="noOfLabel" id="noOfLabel" class="form-control" value="" placeholder="No Of Label" onkeyup="noOfLabel(event)" style="width: 200px; margin-left:20px;">
                                <br />
                                <button type="button" class="btn btn-success" data-toggle="modal" onclick="closeLabelCount()" style=" margin-left:20px;">
                                    Add
                                </button>
                            </div>
                        </div>
                    </div>
                </div>


                <br /><br />


                <div class="row" style="margin-left: 20px;">
                    <div class="col-sm-12">
                        <div class="form-group row">
                            <form>

                                <div class="row">

                                    <button type="button" class="btn btn-success" onclick="generateLabels()">Generate Sticker </button>

                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    var base_url = $('#baseUrl2').val();
    $(document).ready(function() {
        $('#productList').DataTable({
            responsive: true,
            "aaSorting": [
                // [1, "asc"]
            ],
            "columnDefs": [{
                "bSortable": false,
                "aTargets": [0, 1, 2, 3, 4] // Disable sorting for specific columns
            }],
            'processing': true,
            'serverSide': true,

            'lengthMenu': [
                [10, 25, 50, 100, 250, 500, 1000],
                [10, 25, 50, 100, 250, 500, 1000]
            ],

            dom: "'<'col-sm-4'l><'col-sm-4 text-center'><'col-sm-4'>Bfrtip",
            buttons: [],

            'serverMethod': 'post',
            'ajax': {
                'url': base_url + 'product/product/CheckProductListForLabelPrint',
                data: {
                    csrf_test_name: "",
                    category: $("#category_id").val() !== "" ? $("#category_id").val() : null,
                }
            },
            'columns': [

                {
                    data: 'sl'
                },
                {
                    data: 'product_name'
                },
                {
                    data: 'category'
                },
                {
                    data: 'price',
                    className: 'text-right'

                },
                {
                    data: 'button'
                },
            ],
        });

        $.ajax({
            type: "post",
            url: $('#baseUrl2').val() + 'product/product/getLabelPrintData',
            data: {

            },
            success: function(data1) {

                arrItem = JSON.parse(data1)
                if(arrItem.length>0){
                    let lastItem = arrItem[arrItem.length - 1];
                    iD = parseInt(lastItem.id);
                    dataTable();
                }
            }
        });
    });

    let iD = 0;
    let product_ID = 0;
    let product_NAME = "";
    let catgory_NAME = "";
    let brandcode_NAME = "";
    let pricE = 0;
    let arrItem = [];

    function openLabelCount(id, productId, productName, categoryName,  price) {
        $("#model2").modal('show');
        product_ID = productId;
        product_NAME = productName;
        catgory_NAME = categoryName;
        pricE = price;
        setTimeout(function() {
            let element2 = document.getElementById("noOfLabel");
            element2.focus();
        }, 500);




    }

    function generateLabels() {
        $.ajax({
            type: "post",
            url: $('#baseUrl2').val() + 'product/product/printsticker',
            data: {
                labels: arrItem,
                cqty: $('#cqty').val()

            },
            success: function(data1) {
                datas = JSON.parse(data1);
                if (datas.length != 0) {
                    // alert("Barcode Stickers have been successfully created. Please verify them in the Crystal Report.")

                    window.open(`barcode`, '_blank');

                } else {
                    alert("There is no data available for the selected parameters.")
                }


            }
        });
    }

    function generateTag() {
        $.ajax({
            type: "post",
            url: $('#baseUrl2').val() + 'product/product/printtag',
            data: {
                labels: arrItem,
                cqty: $('#cqty').val()

            },
            success: function(data1) {
                // datas = JSON.parse(data1);
                alert("Barcode Tags have been successfully created. Please verify them in the Crystal Report.")


            }
        });
    }

    function findbyBrandCodeAndLabelCode() {
        if ($.fn.dataTable.isDataTable('#productList')) {
            $('#productList').DataTable().destroy();
        }
        $('#productList').DataTable({
            responsive: true,
            "aaSorting": [
                [1, "asc"]
            ],
            "columnDefs": [{
                "bSortable": false,
                "aTargets": [0, 2, 3, 4] // Disable sorting for specific columns
            }],
            'processing': true,
            'serverSide': true,

            'lengthMenu': [
                [10, 25],
                [10, 25]
            ],

        dom: "'<'col-sm-4'l><'col-sm-4 text-center'><'col-sm-4'>Bfrtip",
            buttons: [],

            'serverMethod': 'post',
            'ajax': {
                'url': base_url + 'product/product/CheckProductListForLabelPrint',
                data: {
                    csrf_test_name: "",
                    category: $("#category_id").val() !== "" ? $("#category_id").val() : null,
                    // brand: $("#brandcode_id").val() !== "" ? $("#brandcode_id").val() : null
                }
            },
            'columns': [

                {
                    data: 'sl'
                },
                {
                    data: 'product_name'
                },
                {
                    data: 'category'
                },
                {
                    data: 'price',
                    className: 'text-right'

                },
                {
                    data: 'button'
                },
            ],
        });
    }

    function closeLabelCount(productId) {
        $("#model2").modal('hide');
        iD = iD + 1;
        // $("#productModal").modal('hide');
        arrItem.push({
            id: iD,
            productId: product_ID,
            productName: product_NAME,
            categoryName: catgory_NAME,
            price: pricE,
            noOfLabel: $('#noOfLabel').val(),
        });

        $.ajax({
            type: "post",
            url: $('#baseUrl2').val() + 'product/product/insertLabelPrint',
            data: {
                id: iD,
                productId: product_ID,
                productName: product_NAME,
                categoryName: catgory_NAME,
                price: pricE,
                noOfLabel: $('#noOfLabel').val(),

            },
            success: function(data1) {
                dataTable();

                $('#noOfLabel').val("")
            }
        });


    }

    function noOfLabel(event) {
        if (event.key === 'Enter') {

            $("#model2").modal('hide');
            iD = iD + 1;
            // $("#productModal").modal('hide');
            arrItem.push({
                id: iD,
                productId: product_ID,
                productName: product_NAME,
                categoryName: catgory_NAME,
                price: pricE,
                noOfLabel: $('#noOfLabel').val(),
            });

            $.ajax({
                type: "post",
                url: $('#baseUrl2').val() + 'product/product/insertLabelPrint',
                data: {
                    id: iD,
                    productId: product_ID,
                    productName: product_NAME,
                    categoryName: catgory_NAME,
                    price: pricE,
                    noOfLabel: $('#noOfLabel').val(),

                },
                success: function(data1) {
                    dataTable();

                    $('#noOfLabel').val("")
                }
            });


        }
    }

    function dataTable() {
        $('#dataTable').DataTable().destroy();

        var table = $('#dataTable').DataTable({
            destroy: true, // Allow the table to be destroyed
            data: arrItem,
            columns: [{
                    data: 'noOfLabel',
                    orderable: false,
                },
                {
                    data: 'productId',
                    orderable: false,
                },
                {
                    data: 'productName',
                    orderable: false,
                },
                {
                    data: 'categoryName',
                    orderable: false,
                },
                {
                    data: 'price',
                    orderable: false,
                    className: 'text-right',
                    render: function(data, type, row) {
                        return parseFloat(data).toFixed(2); // Format the price with 2 decimal places
                    }
                },
                {
                    data: 'deleteButton', // Add a column for the delete button
                    orderable: false,
                    searchable: false,
                    className: 'text-center', // Center the button
                    render: function(data, type, row) {
                        return '<a href="#" style="margin-left: 5px;"  class="btn btn-xs btn-danger" onclick="deleteRow(' + row.id + ')"> <i class="fa fa-times" ></i> </a>'
                    }
                }
            ],
            paging: false,
            ordering: false
        });
    }

    function deleteRow(rowData) {

        $.ajax({
            type: "post",
            url: $('#baseUrl2').val() + 'product/product/deleteLabelPrint',
            data: {
                id: rowData,

            },
            success: function(data1) {

                $.ajax({
                    type: "post",
                    url: $('#baseUrl2').val() + 'product/product/getLabelPrintData',
                    data: {

                    },
                    success: function(data1) {

                        arrItem = JSON.parse(data1)
                        dataTable();

                    }
                });
            }
        });


    }

    function deletewholeLabelPrint() {

        $.ajax({
            type: "post",
            url: $('#baseUrl2').val() + 'product/product/deletewholeLabelPrint',
            data: {
                id: 1,

            },
            success: function(data1) {
                console.log(data1)
                arrItem = [];
                iD=0;
                dataTable();
            }
        });


    }
</script>