<script src="<?php echo base_url() ?>my-assets/js/admin_js/json/product.js" type="text/javascript"></script>
<input type="hidden" name="baseUrl2" id="baseUrl2" class="baseUrl" value="<?php echo base_url(); ?>" />
<div class="row">
    <div class="col-sm-12">
        <div class="panel panel-bd lobidrag">
            <div class="panel-heading">
                <div class="panel-title">
                    <h4><?php echo $title; ?></h4>
                </div>
            </div>

            <div class="panel-body">

                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group row">
                            <label for="barcode_or_qrcode"
                                class="col-sm-2 col-form-label"><?php echo display('barcode_or_qrcode') ?> <i
                                    class="text-danger"></i></label>
                            <div class="col-sm-10">
                                <?php if (!empty($id)) { ?>
                                    <input class="form-control" name="product_id" type="text" id="product_id"
                                        placeholder="<?php echo display('barcode_or_qrcode') ?>" tabindex="1"
                                        value="<?php echo !empty($product_id) ? $product_id : (!empty($product->product_id) ? $product->product_id : ''); ?>"
                                        readonly>
                                <?php } else { ?>
                                    <input class="form-control" name="product_id" type="text" id="product_id" placeholder="<?php echo display('barcode_or_qrcode') ?>" tabindex="1" value="<?php echo $productId ?>">
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
                <input type="hidden" name="button" id="button" value="akakak" />


                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group row">
                            <label for="product_name"
                                class="col-sm-4 col-form-label"><?php echo display('product_name') ?> <i
                                    class="text-danger">*</i></label>
                            <div class="col-sm-8">
                                <input class="form-control" name="product_name" type="text" id="product_name"
                                    placeholder="<?php echo display('product_name') ?>"
                                    value="<?php echo $product->product_name ?>" required tabindex="1">
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group row">
                            <label for="serial_no" class="col-sm-4 col-form-label"><?php echo display('serial_no') ?>
                            </label>
                            <div class="col-sm-8">
                                <input type="text" tabindex="" class="form-control " id="serial_no" name="serial_no"
                                    placeholder="111,abc,XYz" value="<?php echo $product->serial_no ?>" />
                            </div>
                        </div>
                    </div>

                </div>

                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group row">
                            <label for="category_id" class="col-sm-4 col-form-label"><?php echo display('category') ?>
                                <i class="text-danger">*</i></label>
                            <div class="col-sm-8">
                                <select class="form-control" id="category_id" required name="category_id" tabindex="3"
                                    onchange="changecategory()">
                                    <option value=""></option>
                                    <?php if ($category_list) { ?>
                                        <?php foreach ($category_list as $categories) { ?>
                                            <option value="<?php echo $categories['category_id'] ?>" <?php if ($product->category_id == $categories['category_id']) {
                                                                                                            echo 'selected';
                                                                                                        } ?>>
                                                <?php echo $categories['category_name'] ?></option>

                                    <?php }
                                    } ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group row">
                            <label for="category_id"
                                class="col-sm-4 col-form-label">Sub<?php echo display('category') ?>
                            </label>
                            <div class="col-sm-8">
                                <select class="form-control" id="subcategory_id" required name="subcategory_id"
                                    tabindex="3">
                                    <option value=""></option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group row">
                            <label for="brand_id" class="col-sm-4 col-form-label">Brand
                            </label>
                            <div class="col-sm-8">
                                <select class="form-control" id="brand_id" required name="brand_id" tabindex="3">
                                    <option value=""></option>
                                    <?php if ($brand_list) { ?>
                                        <?php foreach ($brand_list as $categories) { ?>
                                            <option value="<?php echo $categories['brand_id'] ?>" <?php if ($product->brand_id == $categories['brand_id']) {
                                                                                                        echo 'selected';
                                                                                                    } ?>>
                                                <?php echo $categories['brand_name'] ?></option>

                                    <?php }
                                    } ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="form-group row">
                            <label for="brand_id" class="col-sm-4 col-form-label text-nowrap">Origin of Product
                            </label>
                            <div class="col-sm-8">
                                <select class="form-control" id="oop_id" required name="oop_id" tabindex="3">
                                    <option value=""></option>
                                    <?php if ($oop_list) { ?>
                                        <?php foreach ($oop_list as $categories) { ?>
                                            <option value="<?php echo $categories['oop_id'] ?>" <?php if ($product->oop_id == $categories['oop_id']) {
                                                                                                    echo 'selected';
                                                                                                } ?>>
                                                <?php echo $categories['oop_name'] ?></option>

                                    <?php }
                                    } ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="form-group row">
                            <label for="product_type" class="col-sm-4 col-form-label">Product Type
                                <i class="text-danger">*</i></label>
                            <div class="col-sm-8">
                                <select class="form-control" id="product_type" name="product_type" required
                                    tabindex="3">
                                    <option value=""></option>
                                    <option value="N/A" <?php if ($product->product_type == "N/A" || (empty($product->id) && empty($product->product_type))) echo 'selected'; ?>>N/A</option>
                                    <option value="Retail Good"
                                        <?php if ($product->product_type == "Retail Good") echo 'selected'; ?>>Retail
                                        Good</option>
                                    <option value="Finished Good"
                                        <?php if ($product->product_type == "Finished Good") echo 'selected'; ?>>
                                        Finished Good</option>
                                    <option value="Ingredients"
                                        <?php if ($product->product_type == "Ingredients") echo 'selected'; ?>>
                                        Ingredients</option>
                                    <option value="Raw Material"
                                        <?php if ($product->product_type == "Raw Material") echo 'selected'; ?>>Raw
                                        Material</option>
                                    <option value="Packing Material"
                                        <?php if ($product->product_type == "Packing Material") echo 'selected'; ?>>
                                        Packing Material</option>
                                    <option value="MRO" <?php if ($product->product_type == "MRO") echo 'selected'; ?>>
                                        MRO</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="form-group row">
                            <label for="store" class="col-sm-4 col-form-label">Default Store
                                <i class="text-danger">*</i></label>
                            <div class="col-sm-8">
                                <select class="form-control" id="store" name="store" tabindex="3">
                                    <option value=""></option>
                                    <option value="1" <?php if ($product->store == 1) {
                                                                                                echo 'selected';
                                                                                            } ?>>N/A</option>
                                    <?php if ($store_list) { ?>
                                        <?php foreach ($store_list as $categories) { ?>
                                            <option value="<?php echo $categories['id'] ?>" <?php if ($product->store == $categories['id']) {
                                                                                                echo 'selected';
                                                                                            } ?>>
                                                <?php echo $categories['name'] ?></option>
                                    <?php }
                                    } ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <?php if ($vtinfo->ischecked == 1) { ?>
                        <div class="col-sm-6">
                            <div class="form-group row">
                                <label for="cost_price" class="col-sm-4 col-form-label">Product VAT %
                                </label>
                                <div class="col-sm-8">
                                    <input class="form-control text-right" id="vat" name="vat" type="number"
                                        placeholder="0.00" tabindex="5" min="0" value="<?php echo $product->product_vat ?>">
                                </div>
                            </div>
                        </div>
                    <?php } else { ?>
                        <input type="hidden" class="form-control" name="vat" id="vat" value="0.0">
                    <?php } ?>



                </div>

                <div class="row">

                    <div class="col-sm-6">
                        <div class="form-group row">
                            <label for="store" class="col-sm-4 col-form-label">Default Sales Price
                                <i class="text-danger">*</i></label>
                            <div class="col-sm-8">
                                <select class="form-control" id="defaultsaleprice" required name="defaultsaleprice"
                                    tabindex="3">
                                    <option value=""></option>

                                    <option value="fixedprice"
                                        <?php if ($product->defaultsaleprice == "fixedprice") echo 'selected'; ?>>Fixed
                                        Price</option>
                                    <option value="mrp"
                                        <?php if ($product->defaultsaleprice == "mrp") echo 'selected'; ?>>MRP</option>
                                    <option value="custom"
                                        <?php if ($product->defaultsaleprice == "custom" || (empty($product->id) && empty($product->defaultsaleprice))) echo 'selected'; ?>>Custom
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>



                    <div class="col-sm-6">
                        <div class="form-group row">
                            <label for="product_model" class="col-sm-4 col-form-label"><?php echo display('model') ?>
                            </label>
                            <div class="col-sm-8">
                                <input type="text" tabindex="" class="form-control" id="product_model" name="model"
                                    placeholder="<?php echo display('model') ?>"
                                    value="<?php echo $product->product_model ?>" />
                            </div>
                        </div>
                    </div>





                    <div class="col-sm-6">
                        <div class="form-group row">
                            <label for="serial_no"
                                class="col-sm-4 col-form-label"><?php echo display('product_details') ?> </label>
                            <div class="col-sm-8">
                                <textarea class="form-control" name="description" id="description" rows="1"
                                    placeholder="<?php echo display('product_details') ?>"
                                    tabindex="2"><?php echo $product->product_details ?></textarea>
                            </div>
                        </div>
                    </div>


                    <div class="col-sm-6">
                        <div class="form-group row">
                            <label for="status" class="col-sm-4 col-form-label">Batch Type <i
                                    class="text-danger">*</i></label>
                            <div class="col-sm-8">
                                <select class="form-control" id="batchtype" name="batchtype" required tabindex="-1"
                                    aria-hidden="true">
                                    <option value="">Select One</option>
                                    <option value="1" <?php echo ($product->batchtype == 1) ? 'selected' : ''; ?>>Single
                                    </option>
                                    <option value="2" <?php echo ($product->batchtype == 2) ? 'selected' : ''; ?>>
                                        Multiple</option>
                                    <option value="3" <?php echo ($product->batchtype == 3 || (empty($product->id) && empty($product->batchtype))) ? 'selected' : ''; ?>>Both
                                    </option>

                                </select>

                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="form-group row">
                            <label for="printname" class="col-sm-4 col-form-label">Print Name </label>
                            <div class="col-sm-8">
                                <input type="text" tabindex="" class="form-control" id="printname" name="model"
                                    placeholder="Printname" value="<?php echo $product->printname ?>" />
                            </div>
                        </div>
                    </div>





                    <div class="col-sm-6">
                        <div class="form-group row">

                            <label for="status" class="col-sm-4 col-form-label">Status <i
                                    class="text-danger">*</i></label>
                            <div class="col-sm-8">
                                <select class="form-control" id="status" name="status" required tabindex="-1"
                                    aria-hidden="true">
                                    <option value="">Select One</option>
                                    <option value="1"
                                        <?php echo ($product->status_label == "Active" || (empty($product->id) && empty($product->status_label))) ? 'selected' : ''; ?>>Active
                                    </option>
                                    <option value="0"
                                        <?php echo ($product->status_label == "Inactive") ? 'selected' : ''; ?>>Inactive
                                    </option>
                                </select>

                            </div>
                        </div>
                    </div>






                </div>


            </div>

        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-12">
        <div class="panel panel-bd lobidrag">
            <div class="panel-heading">
                <div class="panel-title">
                    <h4>Master Stock And Substock</h4>
                </div>
            </div>
            <div class="panel-body">
                <div class="form-group row">
                    <div class="col-sm-4">
                        <div class="form-group row">
                            <label for="unit" class="col-sm-4 col-form-label">Master Stock <?php echo display('unit') ?> <i class="text-danger">*</i></label>
                            <div class="col-sm-8">
                                <select class="form-control" id="unit" name="unit" required tabindex="-1"
                                    aria-hidden="true">
                                    <option value="">Select One</option>
                                    <?php if ($unit_list) { ?>
                                        <?php foreach ($unit_list as $units) { ?>
                                            <option value="<?php echo $units['unit_id'] ?>" <?php if ($product->unit == $units['unit_id']) {
                                                                                                echo 'selected';
                                                                                            } ?>>
                                                <?php echo $units['unit_name'] ?></option>

                                    <?php }
                                    } ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group row">
                            <label for="cost_price" class="col-sm-4 col-form-label">Fixed Purchase Price
                            </label>
                            <div class="col-sm-8">
                                <input class="form-control text-right" id="cost_price" name="cost_price" type="number"
                                    placeholder="0.00" tabindex="5" min="0" value="<?php echo $product->cost_price ?>">
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group row">
                            <label for="sell_price" class="col-sm-4 col-form-label">Fixed Sale Price
                            </label>
                            <div class="col-sm-8">
                                <input class="form-control text-right" id="sell_price" name="sell_price" type="number"
                                    placeholder="0.00" tabindex="5" min="0" value="<?php echo $product->price ?>">
                            </div>
                        </div>
                    </div>






                </div>



                <button class="btn btn-primary" data-toggle="modal" data-target="#entryModal">Add Substock</button>
                <br />
                <br />

                <table class="table table-bordered table-striped table-condensed" id="dataTable">
                    <thead>
                        <tr>
                            <th>Substock Unit</th>
                            <th>Substock Purchase Price</th>
                            <th>Substock Sale Price</th>
                            <th>Primary <i class="text-danger">*</i></th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- New rows will be added here -->
                    </tbody>
                </table>

            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-12">
        <div class="panel panel-bd lobidrag">
            <div class="panel-heading">
                <div class="panel-title">
                    <h4>Stock Level And Supply Settings</h4>
                </div>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group row">
                            <label for="supplier_id" class="col-sm-4 col-form-label">Supplier
                            </label>
                            <div class="col-sm-8">
                                <select class="form-control" id="supplier_id" name="supplier_id" tabindex="3">
                                    <option value=""></option>
                                    <?php if ($supplier) { ?>
                                        <?php foreach ($supplier as $sup) { ?>
                                            <option value="<?php echo $sup['supplier_id'] ?>" <?php if ($product->supplier_id == $sup['supplier_id']) {
                                                                                                    echo 'selected';
                                                                                                } ?>>
                                                <?php echo $sup['supplier_name'] ?></option>

                                    <?php }
                                    } ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="form-group row">
                            <label for="stock" class="col-sm-4 col-form-label">Stock <i
                                    class="text-danger">*</i></label>
                            <div class="col-sm-8">
                                <select class="form-control" id="stock" name="stock" required tabindex="-1"
                                    aria-hidden="true">
                                    <option value="1" <?php echo ($product->stock == "1" || (empty($product->id) && $product->stock !== "0")) ? 'selected' : ''; ?>>Enable
                                    </option>
                                    <option value="0" <?php echo ($product->stock == "0") ? 'selected' : ''; ?>>
                                        Disable
                                    </option>
                                </select>

                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="form-group row">
                            <label for="max_stock_level" class="col-sm-4 col-form-label">Max. Stock Level
                            </label>
                            <div class="col-sm-8">
                                <input class="form-control text-right" id="max_stock_level" name="max_stock_level"
                                    type="number" step="any" min="0" placeholder="0.00" tabindex="5"
                                    value="<?php echo isset($product->max_stock_level) ? $product->max_stock_level : ''; ?>">
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="form-group row">
                            <label for="min_stock_level" class="col-sm-4 col-form-label">Min. Stock Level
                            </label>
                            <div class="col-sm-8">
                                <input class="form-control text-right" id="min_stock_level" name="min_stock_level"
                                    type="number" step="any" min="0" placeholder="0.00" tabindex="5"
                                    value="<?php echo isset($product->min_stock_level) ? $product->min_stock_level : ''; ?>">
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="form-group row">
                            <label for="reorder_stock_level" class="col-sm-4 col-form-label">Reorder Stock Level
                            </label>
                            <div class="col-sm-8">
                                <input class="form-control text-right" id="reorder_stock_level" name="reorder_stock_level"
                                    type="number" step="any" min="0" placeholder="0.00" tabindex="5"
                                    value="<?php echo isset($product->reorder_stock_level) ? $product->reorder_stock_level : ''; ?>">
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="form-group row">
                            <label for="reserve_stock_level" class="col-sm-4 col-form-label">Reserve Stock Level
                            </label>
                            <div class="col-sm-8">
                                <input class="form-control text-right" id="reserve_stock_level" name="reserve_stock_level"
                                    type="number" step="any" min="0" placeholder="0.00" tabindex="5"
                                    value="<?php echo isset($product->reserve_stock_level) ? $product->reserve_stock_level : ''; ?>">
                            </div>
                        </div>
                    </div>

                </div>

                <div class="form-group row">

                    <div class="col-sm-12 text-right">

                        <button type="submit" class="btn btn-success " name="save" onclick="save('save')">
                            <?php echo (empty($id) ? display('save') : display('update')) ?></button>

                        <?php if (empty($id)) { ?>
                            <button type="submit" class="btn btn-success" name="add-another" onclick="save('save_add')">
                                <?php echo display('save_and_add_another'); ?>
                            </button>

                        <?php } ?>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="entryModal" tabindex="-1" role="dialog" aria-labelledby="entryModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h4 class="modal-title" id="entryModalLabel">Add Sub stock</h4>
            </div>

            <div class="modal-body">
                <div class="form-group">
                    <label for="nameInput">Substock Unit</label>
                    <select class="form-control" id="subunit" name="subunit" required tabindex="-1" aria-hidden="true">
                        <option value="">Select One</option>
                        <?php if ($unit_list) { ?>
                            <?php foreach ($unit_list as $units) { ?>
                                <option value="<?php echo $units['unit_id'] ?>" <?php if ($product->subunit == $units['unit_name']) {
                                                                                    echo 'selected';
                                                                                } ?>>
                                    <?php echo $units['unit_name'] ?></option>

                        <?php }
                        } ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="emailInput">Sub Purchase Price</label>
                    <input class="form-control text-right" id="subcost_price" name="subcost_price" type="number"
                        placeholder="0.00" tabindex="5" min="0" value="<?php echo $product->subcost_price ?>">
                </div>
                <div class="form-group">
                    <label for="ageInput">Sub Sale Price</label>
                    <input class="form-control text-right" id="subsell_price" name="subsell_price" type="number"
                        placeholder="0.00" tabindex="5" min="0" value="<?php echo $product->subsell_price ?>">
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-success" onclick="addEntry()">Add</button>
            </div>

        </div>
    </div>
</div>

<div class="modal fade" id="updateModal" tabindex="-1" role="dialog" aria-labelledby="entryModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h4 class="modal-title" id="entryModalLabel">Update Sub stock</h4>
            </div>

            <div class="modal-body">
                <div class="form-group">
                    <label for="nameInput">Substock Unit</label>
                    <select class="form-control" id="up_subunit" name="subunit" required tabindex="-1"
                        aria-hidden="true" disabled>
                        <option value="">Select One</option>
                        <?php if ($unit_list) { ?>
                            <?php foreach ($unit_list as $units) { ?>
                                <option value="<?php echo $units['unit_id'] ?>" <?php if ($product->subunit == $units['unit_name']) {
                                                                                    echo 'selected';
                                                                                } ?>>
                                    <?php echo $units['unit_name'] ?></option>

                        <?php }
                        } ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="emailInput">Sub Purchase Price</label>
                    <input class="form-control text-right" id="up_id" name="subcost_price" type="hidden">

                    <input class="form-control text-right" id="up_subcost_price" name="subcost_price" type="text"
                        placeholder="0.00" tabindex="5" min="0" value="<?php echo $product->subcost_price ?>">
                </div>
                <div class="form-group">
                    <label for="ageInput">Sub Sale Price</label>
                    <input class="form-control text-right" id="up_subsell_price" name="subsell_price" type="text"
                        placeholder="0.00" tabindex="5" min="0" value="<?php echo $product->subsell_price ?>">
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-success" onclick="updateEntry()">Update</button>
            </div>




        </div>
    </div>
</div>

<?php
echo "<script>";
echo "var id = " . json_encode($id) . ";";
echo "var floorId = " . json_encode($product->floor) . ";";
echo "var storeId = " . json_encode($product->store) . ";";
echo "var unit_list = " . json_encode($unit_list) . ";";
echo "var subcategory_list = " . json_encode($subcategory_list) . ";";
echo "var subcategory_id = " . json_encode($product->subcategory_id) . ";";
echo "var category_id = " . json_encode($product->category_id) . ";";
echo "var subunit_product = " . json_encode($subunit_product) . ";";
echo "var subunit_conversions = " . json_encode($subunit_conversions) . ";";


echo "</script>";
?>
<script>
    var entries = [];
    var deletedentries = [];



    $(document).ready(function() {
        if (id > 0) {
            let subcat = subcategory_list.filter(sub => sub.category_id == category_id)

            var $subunitDropdown = $('#subcategory_id');
            $subunitDropdown.empty();
            $subunitDropdown.append(
                '<option value="" disabled selected>Select subcategory</option>'); // Add default option

            $.each(subcat, function(index, store) {
                $subunitDropdown.append('<option value="' + store.subcategory_id + '">' + store
                    .subcategory_name + '</option>');
            });
            $subunitDropdown.val(subcategory_id)
            console.log(subunit_conversions)
            console.log(subunit_product)

            subunit_product.forEach(en => {
                var entry = {
                    id: en.id,
                    subunitid: en.unit_id,
                    subunit: en.unit_name,
                    subcost_price: en.subcost_price,
                    subsell_price: en.subsell_price,
                    selected: en.first == 1 ? true : false,
                    selectedInt: en.first
                };

                entries.push(entry);
                var index = entries.length - 1;

                var checkedAttr = entry.selected ? 'checked' : '';
                const exists = subunit_conversions.some(
                    item => item.subunit === en.unit_id
                );

                var newRow = '<tr data-index="' + index + '">' +
                    '<td>' + entry.subunit + '</td>' +
                    '<td>' + entry.subcost_price + '</td>' +
                    '<td>' + entry.subsell_price + '</td>' +
                    '<td class="text-center" style="vertical-align: middle;">' +
                    '<input type="checkbox" class="row-checkbox" ' + checkedAttr +
                    ' style="margin-right: 6px; transform: scale(1.5); vertical-align: middle;">' +
                    '</td>' +
                    '<td class="text-center" style="vertical-align: middle;">' +
                    '<button class="btn btn-info btn-xs edit-btn" ' +
                    'data-entry=\'' + JSON.stringify(entry) + '\'>' +
                    '<i class="fa fa-pencil" aria-hidden="true"></i></button>';

                if (!exists) {
                    newRow = newRow +
                        '<button class="btn btn-danger btn-xs delete-btn" style="vertical-align: middle;">' +
                        '<i class="fa fa-trash-o" aria-hidden="true"></i></button>';
                }
                newRow = newRow + '</td>' + '</tr>';


                $('#dataTable tbody').append(newRow);
            });

        } else {
            $('#sell_price').val("")

        }

    });

    function editRow(entry) {
        console.log(entry)

        $('#updateModal').modal('show');

        var $subunitDropdown = $('#up_subunit');
        $subunitDropdown.empty();

        $.each(unit_list, function(index, store) {
            $subunitDropdown.append('<option value="' + store.unit_id + '">' + store.unit_name + '</option>');
        });

        $subunitDropdown.val(entry.subunitid)
        $('#up_id').val(entry.id)
        $('#up_subcost_price').val(entry.subcost_price)
        $('#up_subsell_price').val(entry.subsell_price)



    }

    function updateEntry() {

        $.ajax({
            url: $('#baseUrl2').val() + 'product/product/update_subunit',
            type: 'POST',
            data: {
                id: $('#up_id').val(),
                subcost_price: document.getElementById('up_subcost_price').value ? document.getElementById(
                    'up_subcost_price').value : 0,
                subsell_price: document.getElementById('up_subsell_price').value ? document.getElementById(
                    'up_subsell_price').value : 0,
            },
            success: function(response) {
                // alert("Invoice Details Updated Successfully")
                // window.location.href = $('#base_url').val() + 'invoice_list';

                // datas = JSON.parse(response);
                // clearDetails()
                // $("#save_add").show();
                alert("Subunit Updated Successfully")
                window.location.reload();


            },
            error: function(error) {
                console.log(error)
            }
        });

    }


    function changecategory() {
        let subcat = subcategory_list.filter(sub => sub.category_id == $('#category_id').val())

        var $subunitDropdown = $('#subcategory_id');
        $subunitDropdown.empty();
        $subunitDropdown.append('<option value="" disabled selected>Select subcategory</option>'); // Add default option

        $.each(subcat, function(index, store) {
            $subunitDropdown.append('<option value="' + store.subcategory_id + '">' + store.subcategory_name +
                '</option>');
        });
    }

    function addEntry() {
        var subunitid = $('#subunit').val().trim();
        var subunit = $('#subunit option:selected').text();

        var subcost_price = $('#subcost_price').val().trim();
        var subsell_price = $('#subsell_price').val().trim();

        let entry1 = entries.find(entry => entry.subunitid == subunitid);

        if (entry1) {
            alert("Substock Unit already exsist")
            return
        }
        if (subunit) {
            var entry = {
                id: 0,
                subunitid: subunitid,
                subunit: subunit,
                subcost_price: subcost_price ? subcost_price : 0,
                subsell_price: subsell_price ? subsell_price : 0,
                selected: false,
                selectedInt: 0

            };

            entries.push(entry);
            var index = entries.length - 1;

            var newRow = '<tr data-index="' + index + '">' +
                '<td>' + entry.subunit + '</td>' +
                '<td>' + entry.subcost_price + '</td>' +
                '<td>' + entry.subsell_price + '</td>' +
                '<td class="text-center" style="vertical-align: middle;"> <input type="checkbox" class="row-checkbox" style="margin-right: 6px; transform: scale(1.5); vertical-align: middle;"></td>' +

                '<td class="text-center" style="vertical-align: middle;"><button class="btn btn-danger btn-xs delete-btn" style="vertical-align: middle;"> <i class="fa fa-trash-o" aria-hidden="true"></i></button></td>' +
                '</tr>';

            $('#dataTable tbody').append(newRow);

        } else {
            alert("Substock Unit shouldn't be empty");
        }
        var $subunitDropdown = $('#subunit');
        $subunitDropdown.empty();
        $subunitDropdown.append('<option value="" disabled selected>Select substock unit</option>'); // Add default option
        $.each(unit_list, function(index, store) {
            $subunitDropdown.append('<option value="' + store.unit_id + '">' + store.unit_name + '</option>');
        });
        $('#subcost_price').val("")
        $('#subsell_price').val("")
    }
    $(document).on('click', '.delete-btn', function() {
        var row = $(this).closest('tr');
        var index = row.data('index');
        if (entries[index].id != 0) {
            if (confirm("Are you sure you want to delete this subunit?")) {
                $.ajax({
                    url: $('#baseUrl2').val() + 'product/product/delete_subunit',
                    type: 'POST',
                    data: {
                        id: entries[index].id,
                    },
                    success: function(response) {
                        alert("Subunit Deleted Successfully")
                        window.location.reload();


                    },
                    error: function(error) {
                        console.log(error)
                    }
                });
                entries.splice(index, 1);
                row.remove();
                $('#dataTable tbody tr').each(function(i) {
                    $(this).attr('data-index', i);
                });


            }



        } else {
            if (confirm("Are you sure you want to delete this subunit?")) {
                entries.splice(index, 1);
                row.remove();
                $('#dataTable tbody tr').each(function(i) {
                    $(this).attr('data-index', i);
                });
            }

        }

    });

    $(document).on('click', '.edit-btn', function() {
        const entry = $(this).data('entry');
        editRow(entry);

        var $subunitDropdown = $('#unit' + item);
    });


    $(document).on('change', '.row-checkbox', function() {
        $('.row-checkbox').not(this).prop('checked', false);
        entries.forEach(function(entry) {
            entry.selected = false;
            entry.selectedInt = 0;
        });
        if ($(this).is(':checked')) {
            var row = $(this).closest('tr');
            var index = row.data('index');
            entries[index].selected = true;
            entries[index].selectedInt = 1;
        } else {
            console.log("All checkboxes are now unchecked.");
        }
    });

    function save(value) {

        if ($('#product_name').val() == "" || $('#product_name').val() == null) {
            alert("Product Name shouldn't be empty");
            return;
        }

        if ($('#category_id').val() == "" || $('#category_id').val() == null) {
            alert("Category shouldn't be empty");
            return;
        }
        if ($('#store').val() == "" || $('#store').val() == null) {
            alert("Store shouldn't be empty");
            return;
        }
        if ($('#defaultsaleprice').val() == "" || $('#defaultsaleprice').val() == null) {
            alert("Default Sale Price shouldn't be empty");
            return;
        }
        if ($('#status').val() == "" || $('#status').val() == null) {
            alert("Status shouldn't be empty");
            return;
        }

        if ($('#unit').val() == "" || $('#unit').val() == null) {
            alert("Master Stock Unit shouldn't be empty");
            return;
        }
        if ($('#batchtype').val() == "" || $('#batchtype').val() == null) {
            alert("Batch Type shouldn't be empty");
            return;
        }
        if ($('#product_type').val() == "" || $('#product_type').val() == null) {
            alert("Product Type shouldn't be empty");
            return;
        }
        if ($('#stock').val() == "" || $('#stock').val() == null) {
            alert("Stock shouldn't be empty");
            return;
        }

        let subcat = entries.filter(ent => ent.subunitid == $('#unit').val())

        if (subcat.length > 0) {
            alert("Substock Unit is not shouldn't be same as Master Stock Unit.");
            return;
        }


        if (entries.length > 0) {
            const count = entries.filter(
                ent => ent.selected == true
            ).length;

            if (count == 0) {
                alert("Any primary subunit should be there");
                return;
            }
        }


        if (id > 0) {
            $.ajax({
                url: $('#baseUrl2').val() + 'product/product/update_product',
                type: 'POST',
                data: {
                    id: id,
                    product_id: document.getElementById('product_id').value,
                    product_name: document.getElementById('product_name').value,
                    serial_no: document.getElementById('serial_no').value,
                    category_id: document.getElementById('category_id').value,
                    subcategory_id: document.getElementById('subcategory_id').value,
                    brand_id: document.getElementById('brand_id').value,
                    oop_id: document.getElementById('oop_id').value,
                    supplier_id: document.getElementById('supplier_id').value,
                    product_type: document.getElementById('product_type').value,
                    store: document.getElementById('store').value,
                    vat: document.getElementById('vat').value,
                    defaultsaleprice: document.getElementById('defaultsaleprice').value,
                    product_model: document.getElementById('product_model').value,
                    description: document.getElementById('description').value,
                    unit: document.getElementById('unit').value,
                    status: document.getElementById('status').value,
                    stock: document.getElementById('stock').value,
                    max_stock_level: document.getElementById('max_stock_level').value,
                    min_stock_level: document.getElementById('min_stock_level').value,
                    reorder_stock_level: document.getElementById('reorder_stock_level').value,
                    reserve_stock_level: document.getElementById('reserve_stock_level').value,
                    cost_price: document.getElementById('cost_price').value ? document.getElementById('cost_price')
                        .value : 0,
                    sell_price: document.getElementById('sell_price').value ? document.getElementById('sell_price')
                        .value : 0,
                    batchtype: document.getElementById('batchtype').value,
                    printname: document.getElementById('printname').value,
                    ad: "",
                    bd: "",
                    entries: entries
                },
                success: function(response) {
                    let result = JSON.parse(response);

                    if (result === "Success") {
                        alert("Product Details Updated Successfully");
                        window.location.href = $('#base_url').val() + 'product_list';
                    } else {
                        alert("Error: " + result);
                    }
                },
                error: function(error) {
                    console.log(error);
                    alert("An error occurred while updating the product. Check console for details.");
                }
            });
        } else {
            $.ajax({
                url: $('#baseUrl2').val() + 'product/product/save_product',
                type: 'POST',
                data: {
                    product_id: document.getElementById('product_id').value,
                    product_name: document.getElementById('product_name').value,
                    serial_no: document.getElementById('serial_no').value,
                    category_id: document.getElementById('category_id').value,
                    subcategory_id: document.getElementById('subcategory_id').value,
                    brand_id: document.getElementById('brand_id').value,
                    oop_id: document.getElementById('oop_id').value,
                    supplier_id: document.getElementById('supplier_id').value,
                    product_type: document.getElementById('product_type').value,
                    store: document.getElementById('store').value,
                    vat: document.getElementById('vat').value,
                    defaultsaleprice: document.getElementById('defaultsaleprice').value,
                    product_model: document.getElementById('product_model').value,
                    description: document.getElementById('description').value,
                    unit: document.getElementById('unit').value,
                    status: document.getElementById('status').value,
                    stock: document.getElementById('stock').value,
                    max_stock_level: document.getElementById('max_stock_level').value,
                    min_stock_level: document.getElementById('min_stock_level').value,
                    reorder_stock_level: document.getElementById('reorder_stock_level').value,
                    reserve_stock_level: document.getElementById('reserve_stock_level').value,
                    cost_price: document.getElementById('cost_price').value,
                    sell_price: document.getElementById('sell_price').value,
                    batchtype: document.getElementById('batchtype').value,
                    printname: document.getElementById('printname').value,
                    ad: "",
                    bd: "",
                    entries: entries
                },
                success: function(response) {
                    let result = JSON.parse(response);

                    if (result === "Success") {
                        alert("Product Details saved Successfully");
                        if (value == "save_add") {
                            window.location.href = $('#base_url').val() + 'product_form';
                        } else {
                            window.location.href = $('#base_url').val() + 'product_list';
                        }
                    } else {
                        alert("Error: " + result);
                    }
                },
                error: function(error) {
                    console.log(error);
                    alert("An error occurred while saving the product. Check console for details.");
                }
            });
        }
    }
</script>


<script>
    if (floorId > 0) {
        onChangeStore(storeId, floorId);

    }
    let code = 0;

    if (id != null) {
        code = document.getElementById("product_id").value.toString();
    }

    // function validateForm(event) {
    //     // Prevent default form submission
    //     event.preventDefault();

    //     // Identify which button was clicked
    //     const buttonName = event.submitter.name; // Get the name of the button that was clicked
    //     async function checkProduct() {
    //         try {
    //             let response = await $.ajax({
    //                 type: "POST",
    //                 url: $('#baseUrl2').val() + 'product/product/getProductById',
    //                 data: {
    //                     code: document.getElementById('product_id').value.toString().padStart(6, '0'),
    //                 }
    //             });

    //             let data = JSON.parse(response);
    //             if (data === "success") {
    //                 return true;
    //             } else {
    //                 if (code == document.getElementById('product_id').value.toString().padStart(6, '0')) {
    //                     return true;
    //                 } else {
    //                     alert("Product code already exists");
    //                     return false;
    //                 }
    //             }

    //         } catch (error) {
    //             alert("An error occurred: " + error);
    //             return false;
    //         }
    //     }

    //     checkProduct().then((isValid) => {
    //         if (isValid) {
    //             if (buttonName === 'save') {
    //                 document.getElementById('button').value = "save";
    //                 document.getElementById('insert_product').submit();
    //             } else if (buttonName === 'add-another') {
    //                 document.getElementById('button').value = "add-another";
    //                 document.getElementById('insert_product').submit();
    //             }
    //         } else {
    //             return false;
    //         }
    //     });
    // }
</script>