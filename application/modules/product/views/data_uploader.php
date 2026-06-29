<script src="https://cdn.jsdelivr.net/npm/papaparse@5.3.2/papaparse.min.js"></script>

<!-- ── Main Upload Panel ──────────────────────────────────────── -->
<div class="row">
    <div class="col-sm-12">
        <div class="panel panel-bd lobidrag">
            <div class="panel-heading">
                <div class="panel-title"><h4>DUPL - Data Uploader</h4></div>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label>Function</label>
                            <select class="form-control" id="functionSelect" onchange="onFunctionChange()">
                                <option value="">Select option</option>
                                <option value="branch">Branch Upload</option>
                                <option value="store">Store Upload</option>
                                <option value="paymentmethod">Payment Method Upload</option>
                                <option value="brand">Brand Upload</option>
                                <option value="category">Category Upload</option>
                                <option value="subcategory">Subcategory Upload</option>
                                <option value="unit">Unit Upload</option>
                                <option value="service">Service Upload</option>
                                <option value="product">Product Upload</option>
                                <option value="conversionratio">Conversion Ratio Upload</option>
                                <option value="productgroup">Product Group Upload</option>
                                <option value="stockbatch">Stock Batch Upload</option>
                                <option value="openingstock">Opening Stock Upload</option>
                                <option value="customer">Customer Upload</option>
                                <option value="supplier">Supplier Upload</option>
                                <option value="designation">Designation Upload</option>
                                <option value="employee">Employee Upload</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label>Upload CSV File <i class="text-danger">*</i></label>
                            <input class="form-control" type="file" id="csvFile" accept=".csv" disabled>
                        </div>
                    </div>
                    <div class="col-sm-4" style="padding-top:23px;">
                        <button type="button" onclick="validateCSV()" id="btn_validate" class="btn btn-primary" disabled>
                            <i class="fa fa-search"></i> Validate
                        </button>
                        <button type="button" onclick="confirmSave()" id="btn_save" class="btn btn-success" style="display:none; margin-left:8px;">
                            <i class="fa fa-save"></i> Confirm &amp; Save
                        </button>
                        <button type="button" onclick="clearAll()" id="btn_clear" class="btn btn-default" style="display:none; margin-left:8px;">
                            <i class="fa fa-times"></i> Clear
                        </button>
                        <span id="upload_status" style="margin-left:10px; font-weight:600; display:block; margin-top:6px;"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- ── General Upload Instructions ────────────────────────────── -->
<div class="row" id="info_general">
    <div class="col-sm-12">
        <div class="panel panel-bd lobidrag">
            <div class="panel-heading" style="display:flex; align-items:center; justify-content:space-between;">
                <div class="panel-title">
                    <h4 style="color:#c0392b; font-weight:bold; margin:0;">
                        <i class="fa fa-exclamation-circle" style="margin-right:6px;"></i>Data Upload Instructions
                    </h4>
                </div>
                <div id="sample_csv_btn_wrap" style="display:none;">
                    <a id="sample_csv_link" href="#" download class="btn btn-sm btn-default" style="border:1px solid #333; color:#333; font-weight:600;">
                        <i class="fa fa-download" style="margin-right:4px;"></i><span id="sample_csv_label">Download Sample CSV</span>
                    </a>
                </div>
            </div>
            <div class="panel-body" style="padding-top:10px;">
                <p style="margin:4px 0; line-height:2;">⚠️ Do not modify the first row (header row) in the downloaded CSV file. The column names and their order must remain exactly as provided.</p>
                <p style="margin:4px 0; line-height:2;">⚠️ You may rename the CSV file before uploading. The file name does not affect the upload process.</p>
                <p style="margin:4px 0; line-height:2;">⚠️ Enter data exactly as it appears in the system, including uppercase and lowercase letters where applicable.</p>
                <p style="margin:4px 0; line-height:2;">⚠️ For fields marked as (Yes/No), only enter <strong>Yes</strong> or <strong>No</strong>. Any other value will be considered invalid and may cause upload errors.</p>
                <p style="margin:4px 0; line-height:2;">⚠️ Do not add, remove, or rearrange columns in the CSV file.</p>
                <p style="margin:4px 0; line-height:2;">⚠️ Do not leave mandatory fields blank. Ensure all required information is provided before uploading.</p>
                <p style="margin:4px 0; line-height:2;">⚠️ Avoid extra spaces before or after values, as they may affect data validation.</p>
                <p style="margin:4px 0; line-height:2;">⚠️ Use the correct data format for dates, numbers, and codes as specified in the template.</p>
                <p style="margin:4px 0; line-height:2;">⚠️ Do not enter special characters unless they are specifically allowed for that field.</p>
                <p style="margin:4px 0; line-height:2;">⚠️ Review the file carefully before uploading to ensure all data is accurate and complete.</p>
            </div>
        </div>
    </div>
</div>

<!-- ── Validation Preview ─────────────────────────────────────── -->
<div class="row" id="preview_panel" style="display:none;">
    <div class="col-sm-12">
        <div class="panel panel-bd">
            <div class="panel-heading">
                <div class="panel-title"><h4>Validation Preview</h4></div>
            </div>
            <div class="panel-body">
                <table class="table table-bordered table-condensed" id="preview_table_product" style="display:none;">
                    <thead><tr><th>#</th><th>Barcode</th><th>Product Name</th><th>Category</th><th>Sub-Cat</th><th>Brand</th><th>Store</th><th>Master Unit</th><th>Cost Price</th><th>Sale Price</th><th>VAT%</th><th>Status</th><th>Supplier</th><th>Stock</th><th>Max Stock</th><th>Min Stock</th><th>Reorder</th><th>Reserve</th><th>Subunits</th><th>Validation</th></tr></thead>
                    <tbody></tbody>
                </table>
                <table class="table table-bordered table-condensed" id="preview_table_cr" style="display:none;">
                    <thead><tr><th>#</th><th>Product</th><th>Master Stock Unit</th><th>Substock Unit</th><th>Conversion Ratio</th><th>Status</th></tr></thead>
                    <tbody></tbody>
                </table>
                <table class="table table-bordered table-condensed" id="preview_table_brand" style="display:none;">
                    <thead><tr><th>#</th><th>Brand Name</th><th>Status</th><th>Validation</th></tr></thead>
                    <tbody></tbody>
                </table>
                <table class="table table-bordered table-condensed" id="preview_table_category" style="display:none;">
                    <thead><tr><th>#</th><th>Category Name</th><th>Status</th><th>Validation</th></tr></thead>
                    <tbody></tbody>
                </table>
                <table class="table table-bordered table-condensed" id="preview_table_subcategory" style="display:none;">
                    <thead><tr><th>#</th><th>Subcategory Name</th><th>Category</th><th>Status</th><th>Validation</th></tr></thead>
                    <tbody></tbody>
                </table>
                <table class="table table-bordered table-condensed" id="preview_table_unit" style="display:none;">
                    <thead><tr><th>#</th><th>Unit Name</th><th>Print Name</th><th>Status</th><th>Validation</th></tr></thead>
                    <tbody></tbody>
                </table>
                <table class="table table-bordered table-condensed" id="preview_table_paymentmethod" style="display:none;">
                    <thead><tr><th>#</th><th>Payment Method Name</th><th>Status</th><th>Validation</th></tr></thead>
                    <tbody></tbody>
                </table>
                <table class="table table-bordered table-condensed" id="preview_table_branch" style="display:none;">
                    <thead><tr><th>#</th><th>Code</th><th>Branch Name</th><th>Nature</th><th>Status</th><th>Validation</th></tr></thead>
                    <tbody></tbody>
                </table>
                <table class="table table-bordered table-condensed" id="preview_table_store" style="display:none;">
                    <thead><tr><th>#</th><th>Code</th><th>Store Name</th><th>Nature</th><th>GRN</th><th>GDN</th><th>Default Stock</th><th>Status</th><th>Validation</th></tr></thead>
                    <tbody></tbody>
                </table>
                <table class="table table-bordered table-condensed" id="preview_table_customer" style="display:none;">
                    <thead><tr><th>#</th><th>Customer Name</th><th>Mobile</th><th>Email</th><th>Status</th><th>Validation</th></tr></thead>
                    <tbody></tbody>
                </table>
                <table class="table table-bordered table-condensed" id="preview_table_supplier" style="display:none;">
                    <thead><tr><th>#</th><th>Supplier Name</th><th>Mobile</th><th>Email</th><th>Status</th><th>Validation</th></tr></thead>
                    <tbody></tbody>
                </table>
                <table class="table table-bordered table-condensed" id="preview_table_service" style="display:none;">
                    <thead><tr><th>#</th><th>Service Name</th><th>Charge</th><th>VAT</th><th>Status</th><th>Validation</th></tr></thead>
                    <tbody></tbody>
                </table>
                <table class="table table-bordered table-condensed" id="preview_table_productgroup" style="display:none;">
                    <thead><tr><th>#</th><th>Group Code</th><th>Group Name</th><th>Items</th><th>Status</th><th>Validation</th></tr></thead>
                    <tbody></tbody>
                </table>
                <table class="table table-bordered table-condensed" id="preview_table_openingstock" style="display:none;">
                    <thead><tr><th>#</th><th>Transaction ID</th><th>Date</th><th>Reason</th><th>Lines</th><th>Products</th><th>Validation</th></tr></thead>
                    <tbody></tbody>
                </table>
                <table class="table table-bordered table-condensed" id="preview_table_stockbatch" style="display:none;">
                    <thead><tr><th>#</th><th>Batch ID</th><th>Usage Type</th><th>Product</th><th>Status</th><th>Validation</th></tr></thead>
                    <tbody></tbody>
                </table>
                <table class="table table-bordered table-condensed" id="preview_table_designation" style="display:none;">
                    <thead><tr><th>#</th><th>Designation</th><th>Details</th><th>Status</th><th>Validation</th></tr></thead>
                    <tbody></tbody>
                </table>
                <table class="table table-bordered table-condensed" id="preview_table_employee" style="display:none;">
                    <thead><tr><th>#</th><th>Employee ID</th><th>Employee Name</th><th>Designation</th><th>Pay Type</th><th>Status</th><th>Validation</th></tr></thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include(dirname(__FILE__) . '/dupl_product.php'); ?>
<?php include(dirname(__FILE__) . '/dupl_conversionratio.php'); ?>
<?php include(dirname(__FILE__) . '/dupl_brand.php'); ?>
<?php include(dirname(__FILE__) . '/dupl_category.php'); ?>
<?php include(dirname(__FILE__) . '/dupl_subcategory.php'); ?>
<?php include(dirname(__FILE__) . '/dupl_unit.php'); ?>
<?php include(dirname(__FILE__) . '/dupl_paymentmethod.php'); ?>
<?php include(dirname(__FILE__) . '/dupl_branch.php'); ?>
<?php include(dirname(__FILE__) . '/dupl_store.php'); ?>
<?php include(dirname(__FILE__) . '/dupl_customer.php'); ?>
<?php include(dirname(__FILE__) . '/dupl_supplier.php'); ?>
<?php include(dirname(__FILE__) . '/dupl_service.php'); ?>
<?php include(dirname(__FILE__) . '/dupl_productgroup.php'); ?>
<?php include(dirname(__FILE__) . '/dupl_openingstock.php'); ?>
<?php include(dirname(__FILE__) . '/dupl_stockbatch.php'); ?>
<?php include(dirname(__FILE__) . '/dupl_designation.php'); ?>
<?php include(dirname(__FILE__) . '/dupl_employee.php'); ?>

<!-- ── Subunits Modal ──────────────────────────────────────────── -->
<div class="modal fade" id="subunitsModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" id="subunitsModalTitle">Subunits</h4>
            </div>
            <div class="modal-body" id="subunitsModalBody"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- ── Bulk Details Modal ──────────────────────────────────────── -->
<div class="modal fade" id="bulkDetailsModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" id="detailsModalTitle">Upload Details</h4>
            </div>
            <div class="modal-body">
                <table class="table table-bordered table-condensed" id="bulkDetailsTable">
                    <thead id="detailsTableHead"></thead>
                    <tbody id="bulkDetailsBody"></tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<?php
/* ── PHP → JS data injection ─────────────────────────────────── */
echo "<script>";
echo "let csv_categories="    . json_encode($categories    ?: []) . ";";
echo "let csv_subcategories=" . json_encode($subcategories ?: []) . ";";
echo "let csv_brands="        . json_encode($brands        ?: []) . ";";
echo "let csv_oops="          . json_encode($oops          ?: []) . ";";
echo "let csv_stores="        . json_encode($stores        ?: []) . ";";
echo "let csv_units="         . json_encode($units         ?: []) . ";";
echo "let csv_suppliers="     . json_encode($suppliers     ?: []) . ";";
echo "let csv_products="                   . json_encode($products                  ?: []) . ";";
echo "let csv_all_product_names="          . json_encode($all_product_names         ?: []) . ";";
echo "let csv_product_subunits="           . json_encode($product_subunits          ?: []) . ";";
echo "let csv_existing_conversionratios="  . json_encode($existing_conversionratios ?: []) . ";";
$_flat = function($arr, $key){ return array_values(array_filter(array_map(function($r) use($key){ return strtolower(trim($r[$key] ?? '')); }, $arr ?: []), function($v){ return $v !== ''; })); };
echo "let db_brand_names=new Set("          . json_encode($_flat($all_brand_names          ?? [], 'brand_name'))    . ");";
echo "let db_category_names=new Set("       . json_encode($_flat($all_category_names        ?? [], 'category_name')) . ");";
echo "let db_unit_names=new Set("           . json_encode($_flat($all_unit_names             ?? [], 'unit_name'))     . ");";
echo "let db_payment_method_names=new Set(" . json_encode($_flat($all_payment_method_names  ?? [], 'name'))           . ");";
$_scPairs = array_map(function($r){ return strtolower(trim($r['subcategory_name'] ?? '')) . '||' . ($r['category_id'] ?? ''); }, $all_subcategory_names ?? []);
echo "let db_subcategory_pairs=new Set("    . json_encode(array_values($_scPairs))                                    . ");";
echo "let db_branch_codes=new Set("         . json_encode($_flat($all_branch_data         ?? [], 'code'))             . ");";
echo "let db_branch_names=new Set("         . json_encode($_flat($all_branch_data         ?? [], 'name'))             . ");";
echo "let db_store_codes=new Set("          . json_encode($_flat($all_store_data          ?? [], 'code'))             . ");";
echo "let db_store_names=new Set("          . json_encode($_flat($all_store_data          ?? [], 'name'))             . ");";
echo "let db_customer_names=new Set("       . json_encode($_flat($all_customer_names      ?? [], 'customer_name'))    . ");";
echo "let db_supplier_names=new Set("       . json_encode($_flat($all_supplier_names      ?? [], 'supplier_name'))    . ");";
echo "let db_service_names=new Set("        . json_encode($_flat($all_service_names       ?? [], 'service_name'))     . ");";
echo "let db_productgroup_codes=new Set("   . json_encode($_flat($all_productgroup_codes  ?? [], 'groupcode'))        . ");";
echo "let db_all_stockbatches="             . json_encode($all_stockbatches         ?? [])                             . ";";
echo "let db_all_stockbatch_batchids="      . json_encode($all_stockbatch_batchids   ?? [])                            . ";";
echo "let db_conversion_ratios="            . json_encode($all_conversion_ratios     ?? [])                            . ";";
echo "let csv_designations="               . json_encode($all_designations           ?? [])                            . ";";
echo "let db_designation_names=new Set("   . json_encode($_flat($all_designation_names ?? [], 'designation'))          . ");";
echo "let db_employee_ids=new Set("        . json_encode($_flat($all_employee_ids      ?? [], 'last_name'))            . ");";
echo "_initOpeningStockMaps();";
echo "</script>";
?>

<script>
/* ── Constants ──────────────────────────────────────────────── */
const SALE_PRICE_MAP = { 'fixed price': 'fixedprice', 'mrp': 'mrp', 'custom': 'custom' };
const BATCH_TYPE_MAP = { 'single': 1, 'multiple': 2, 'both': 3 };
const ALL_PREVIEW_TABLES = '#preview_table_product,#preview_table_cr,#preview_table_brand,#preview_table_category,#preview_table_subcategory,#preview_table_unit,#preview_table_paymentmethod,#preview_table_branch,#preview_table_store,#preview_table_customer,#preview_table_supplier,#preview_table_service,#preview_table_productgroup,#preview_table_openingstock,#preview_table_stockbatch,#preview_table_designation,#preview_table_employee';
const ALL_INFO_PANELS = '';

/* ── Shared state ───────────────────────────────────────────── */
let validatedData    = [];
let previewTableData = [];
let previewDT        = null;
let productDT        = null;
let crDT             = null;
let brandDT          = null;
let categoryDT       = null;
let subcategoryDT    = null;
let unitDT           = null;
let paymentmethodDT  = null;
let branchDT         = null;
let storeDT          = null;
let customerDT       = null;
let supplierDT       = null;
let serviceDT        = null;
let productgroupDT   = null;
let openingstockDT   = null;
let stockbatchDT     = null;
let designationDT    = null;
let employeeDT       = null;
let productDTInited       = false;
let crDTInited            = false;
let brandDTInited         = false;
let categoryDTInited      = false;
let subcategoryDTInited   = false;
let unitDTInited          = false;
let paymentmethodDTInited = false;
let branchDTInited        = false;
let storeDTInited         = false;
let customerDTInited      = false;
let supplierDTInited      = false;
let serviceDTInited       = false;
let productgroupDTInited  = false;
let openingstockDTInited  = false;
let stockbatchDTInited    = false;
let designationDTInited   = false;
let employeeDTInited      = false;

/* ── Utility helpers ────────────────────────────────────────── */
function norm(val)   { return (val || '').toLowerCase().trim(); }
function findByName(arr, nameField, val) {
    if (!val || !arr || !arr.length) return null;
    const v = norm(val);
    return arr.find(r => norm(r[nameField]) === v) || null;
}
function yesNo(val)  { return norm(val) === 'yes' ? 1 : 0; }
function num(val)    { return parseFloat(val) || 0; }
function esc(str)    { return $('<div>').text(str || '').html(); }
function setStatus(msg, color) { $('#upload_status').css('color', color || '#888').text(msg); }
function currentFn() { return $('#functionSelect').val(); }

/* ── Sample CSV file map ────────────────────────────────────── */
const SAMPLE_CSV_MAP = {
    branch:          { file: 'assets/data/csv/dupl_branch_sample.csv',          label: 'Download DUPL Sample - Branch CSV' },
    store:           { file: 'assets/data/csv/dupl_store_sample.csv',           label: 'Download DUPL Sample - Store CSV' },
    paymentmethod:   { file: 'assets/data/csv/dupl_paymentmethod_sample.csv',   label: 'Download DUPL Sample - Payment Method CSV' },
    brand:           { file: 'assets/data/csv/dupl_brand_sample.csv',           label: 'Download DUPL Sample - Brand CSV' },
    category:        { file: 'assets/data/csv/dupl_category_sample.csv',        label: 'Download DUPL Sample - Category CSV' },
    subcategory:     { file: 'assets/data/csv/dupl_subcategory_sample.csv',     label: 'Download DUPL Sample - Subcategory CSV' },
    unit:            { file: 'assets/data/csv/dupl_unit_sample.csv',            label: 'Download DUPL Sample - Unit CSV' },
    service:         { file: 'assets/data/csv/dupl_service_sample.csv',         label: 'Download DUPL Sample - Service CSV' },
    product:         { file: 'assets/data/csv/sample_product.csv',              label: 'Download DUPL Sample - Product CSV' },
    conversionratio: { file: 'assets/data/csv/dupl_conversionratio_sample.csv', label: 'Download DUPL Sample - Conversion Ratio CSV' },
    productgroup:    { file: 'assets/data/csv/dupl_productgroup_sample.csv',    label: 'Download DUPL Sample - Product Group CSV' },
    stockbatch:      { file: 'assets/data/csv/dupl_stockbatch_sample.csv',      label: 'Download DUPL Sample - Stock Batch CSV' },
    openingstock:    { file: 'assets/data/DUPL_Opening_Stock.csv',              label: 'Download DUPL Sample - Opening Stock CSV' },
    customer:        { file: 'assets/data/csv/dupl_customer_sample.csv',        label: 'Download DUPL Sample - Customer CSV' },
    supplier:        { file: 'assets/data/csv/dupl_supplier_sample.csv',        label: 'Download DUPL Sample - Supplier CSV' },
    designation:     { file: 'assets/data/csv/dupl_designation_sample.csv',     label: 'Download DUPL Sample - Designation CSV' },
    employee:        { file: 'assets/data/csv/dupl_employee_sample.csv',        label: 'Download DUPL Sample - Employee CSV' },
};

/* ── Function selector ──────────────────────────────────────── */
function onFunctionChange() {
    const fn = currentFn();
    $('#csvFile').val('').prop('disabled', !fn);
    $('#btn_validate').prop('disabled', !fn);
    $('#btn_save').hide();
    $('#btn_clear').hide();
    $('#preview_panel').hide();
    setStatus('');
    validatedData    = [];
    previewTableData = [];
    if (previewDT) { previewDT.destroy(); previewDT = null; }
    $(ALL_PREVIEW_TABLES).hide();
    $('tbody', ALL_PREVIEW_TABLES).empty();
    $('#history_product,#history_cr,#history_brand,#history_category,#history_subcategory,#history_unit,#history_paymentmethod,#history_branch,#history_store,#history_customer,#history_supplier,#history_service,#history_productgroup,#history_openingstock,#history_stockbatch,#history_designation,#history_employee').hide();

    /* sample CSV download button */
    if (fn && SAMPLE_CSV_MAP[fn]) {
        var base = $('#base_url').val();
        $('#sample_csv_link').attr('href', base + SAMPLE_CSV_MAP[fn].file);
        $('#sample_csv_label').text(SAMPLE_CSV_MAP[fn].label);
        $('#sample_csv_btn_wrap').show();
    } else {
        $('#sample_csv_btn_wrap').hide();
    }

    if      (fn === 'product')        { $('#history_product').show();        if (!productDTInited)        { initProductDT();        productDTInited        = true; } }
    else if (fn === 'conversionratio') { $('#history_cr').show();             if (!crDTInited)             { initCRDT();             crDTInited             = true; } }
    else if (fn === 'brand')          { $('#history_brand').show();          if (!brandDTInited)          { initBrandDT();          brandDTInited          = true; } }
    else if (fn === 'category')       { $('#history_category').show();       if (!categoryDTInited)       { initCategoryDT();       categoryDTInited       = true; } }
    else if (fn === 'subcategory')    { $('#history_subcategory').show();    if (!subcategoryDTInited)    { initSubcategoryDT();    subcategoryDTInited    = true; } }
    else if (fn === 'unit')           { $('#history_unit').show();           if (!unitDTInited)           { initUnitDT();           unitDTInited           = true; } }
    else if (fn === 'paymentmethod')  { $('#history_paymentmethod').show();  if (!paymentmethodDTInited)  { initPaymentMethodDT();  paymentmethodDTInited  = true; } }
    else if (fn === 'branch')         { $('#history_branch').show();         if (!branchDTInited)         { initBranchDT();         branchDTInited         = true; } }
    else if (fn === 'store')          { $('#history_store').show();          if (!storeDTInited)          { initStoreDT();          storeDTInited          = true; } }
    else if (fn === 'customer')       { $('#history_customer').show();       if (!customerDTInited)       { initCustomerDT();       customerDTInited       = true; } }
    else if (fn === 'supplier')       { $('#history_supplier').show();       if (!supplierDTInited)       { initSupplierDT();       supplierDTInited       = true; } }
    else if (fn === 'service')        { $('#history_service').show();        if (!serviceDTInited)        { initServiceDT();        serviceDTInited        = true; } }
    else if (fn === 'productgroup')   { $('#history_productgroup').show();   if (!productgroupDTInited)   { initProductGroupDT();   productgroupDTInited   = true; } }
    else if (fn === 'openingstock')   { $('#history_openingstock').show();   if (!openingstockDTInited)   { initOpeningStockDT();   openingstockDTInited   = true; } }
    else if (fn === 'stockbatch')     { $('#history_stockbatch').show();     if (!stockbatchDTInited)     { initStockBatchDT();     stockbatchDTInited     = true; } }
    else if (fn === 'designation')    { $('#history_designation').show();    if (!designationDTInited)    { initDesignationDT();    designationDTInited    = true; } }
    else if (fn === 'employee')       { $('#history_employee').show();       if (!employeeDTInited)       { initEmployeeDT();       employeeDTInited       = true; } }
}

/* ── Validate dispatcher ────────────────────────────────────── */
async function validateCSV() {
    const fn = currentFn();
    if (!fn) { alert('Please select a Function first.'); return; }
    const file = document.getElementById('csvFile').files[0];
    if (!file) { alert('Please select a CSV file.'); return; }

    $('#btn_validate').prop('disabled', true);
    $('#btn_save').hide();
    $('#btn_clear').hide();
    validatedData    = [];
    previewTableData = [];
    if (previewDT) { previewDT.destroy(); previewDT = null; }
    $(ALL_PREVIEW_TABLES).hide();
    $('tbody', ALL_PREVIEW_TABLES).empty();
    $('#preview_panel').show();
    setStatus('Parsing CSV…');

    const text = await file.text();
    const cleanText = text.replace(/^﻿/, '');
    let rows;
    try {
        rows = Papa.parse(cleanText, { header: true, skipEmptyLines: true, transformHeader: h => h.trim() }).data;
    } catch(e) { alert('Failed to parse CSV: ' + e.message); $('#btn_validate').prop('disabled', false); return; }
    if (!rows.length) { alert('CSV file is empty.'); $('#btn_validate').prop('disabled', false); return; }

    if      (fn === 'product')        await validateProductRows(rows);
    else if (fn === 'conversionratio') await validateCRRows(rows);
    else if (fn === 'brand')          validateSimpleRows(rows, 'Brand Name',          'brand_name', db_brand_names,         '#preview_table_brand',         ['idx','name','statusLabel','badge'], ['#','Brand Name','Status','Validation']);
    else if (fn === 'category')       validateSimpleRows(rows, 'Category Name',       'category_name', db_category_names,   '#preview_table_category',      ['idx','name','statusLabel','badge'], ['#','Category Name','Status','Validation']);
    else if (fn === 'subcategory')    validateSubcategoryRows(rows);
    else if (fn === 'unit')           validateUnitRows(rows);
    else if (fn === 'paymentmethod')  validateSimpleRows(rows, 'Payment Method Name', 'name', db_payment_method_names,      '#preview_table_paymentmethod', ['idx','name','statusLabel','badge'], ['#','Payment Method Name','Status','Validation']);
    else if (fn === 'branch')         validateBranchRows(rows);
    else if (fn === 'store')          validateStoreRows(rows);
    else if (fn === 'customer')       validateCustomerRows(rows);
    else if (fn === 'supplier')       validateSupplierRows(rows);
    else if (fn === 'service')        validateServiceRows(rows);
    else if (fn === 'productgroup')   validateProductGroupRows(rows);
    else if (fn === 'openingstock')   validateOpeningStockRows(rows);
    else if (fn === 'stockbatch')     validateStockBatchRows(rows);
    else if (fn === 'designation')    validateDesignationRows(rows);
    else if (fn === 'employee')       validateEmployeeRows(rows);

    $('#btn_validate').prop('disabled', false);
}

/* ── Generic simple-entity validator ───────────────────────── */
function validateSimpleRows(rows, csvNameCol, payloadKey, dbNameSet, tableId, fields, headers) {
    $(tableId).show();
    let hasError = false;
    const seenInCsv = new Set();

    for (let i = 0; i < rows.length; i++) {
        const r    = rows[i];
        const name = (r[csvNameCol] || '').trim();
        const statusRaw = (r['Status (Yes/No)'] || '').trim().toLowerCase();
        let error = null;

        if (!name)                               error = 'Name is required';
        else if (seenInCsv.has(name.toLowerCase())) error = 'Duplicate in CSV: "' + name + '"';
        else if (dbNameSet.has(name.toLowerCase())) error = 'Already exists in DB: "' + name + '"';
        else if (!['yes','no'].includes(statusRaw)) error = 'Status must be Yes or No';
        if (!error) seenInCsv.add(name.toLowerCase());

        const statusInt   = statusRaw === 'yes' ? 1 : 0;
        const statusLabel = statusInt ? '<span class="label label-success">Active</span>' : '<span class="label label-default">Inactive</span>';
        const badge = error
            ? '<span class="label label-danger" title="' + esc(error) + '">' + esc(error) + '</span>'
            : '<span class="label label-success">OK</span>';

        if (error) {
            hasError = true;
            previewTableData.push({ idx: i+1, name: esc(name), statusLabel, badge, rowColor: '#fff5f5', hasError: true, _key: name });
        } else {
            const payload = { status: statusInt };
            payload[payloadKey] = name;
            validatedData.push({ _key: name, payload });
            previewTableData.push({ idx: i+1, name: esc(name), statusLabel, badge, rowColor: '', hasError: false, _key: name });
        }
    }

    buildPreviewDT(headers, fields, tableId);
    finishValidation(rows.length);
}

/* ── Preview DataTable builder ──────────────────────────────── */
function buildPreviewDT(headers, fields, tableId) {
    if (previewDT) { previewDT.destroy(); previewDT = null; }
    const errorRows = previewTableData.filter(function(r) { return r.hasError; });
    previewDT = $(tableId).DataTable({
        data: errorRows,
        pageLength: 10, lengthMenu: [[10,25,50],[10,25,50]],
        ordering: false, autoWidth: false, scrollX: true,
        columns: fields.map(function(f, i) { return { data: f, title: headers[i] }; }),
        createdRow: function(row, data) { $(row).css('background', data.rowColor); },
        language: { emptyTable: 'No validation errors — all rows are valid.' }
    });
}

/* ── Common post-validation status ─────────────────────────── */
function finishValidation(totalRows) {
    const errCount = totalRows - validatedData.length;
    if (validatedData.length > 0 && errCount > 0) {
        $('#btn_save').show();
        setStatus(validatedData.length + ' valid, ' + errCount + ' error(s) shown below — fix and re-upload.', '#e6a817');
    } else if (errCount === totalRows) {
        setStatus('All ' + totalRows + ' rows have errors — see below.', '#c0392b');
    } else {
        setStatus('All ' + validatedData.length + ' rows valid — no errors. Click Confirm & Save.', '#27ae60');
        $('#btn_save').show();
    }
    $('#btn_clear').show();
}

/* ── Save dispatcher ────────────────────────────────────────── */
async function confirmSave() {
    if (!validatedData.length) { alert('No valid rows to save.'); return; }
    const fn = currentFn();
    if (!confirm('Save ' + validatedData.length + ' record(s)?')) return;

    $('#btn_save').prop('disabled', true);
    $('#btn_validate').prop('disabled', true);
    setStatus('Saving 0 / ' + validatedData.length + '…', '#e6a817');

    const base = $('#base_url').val();
    const saveUrlMap = {
        product:         base + 'product/product/save_product',
        conversionratio: base + 'save_conversionratio_from_csv',
        brand:           base + 'save_brand_from_csv',
        category:        base + 'save_category_from_csv',
        subcategory:     base + 'save_subcategory_from_csv',
        unit:            base + 'save_unit_from_csv',
        paymentmethod:   base + 'save_paymentmethod_from_csv',
        branch:          base + 'save_branch_from_csv',
        store:           base + 'save_store_from_csv',
        customer:        base + 'save_customer_from_csv',
        supplier:        base + 'save_supplier_from_csv',
        service:         base + 'save_service_from_csv',
        productgroup:    base + 'save_productgroup_from_csv',
        openingstock:    base + 'save_openingstock_from_csv',
        stockbatch:      base + 'save_stockbatch_from_csv',
        designation:     base + 'save_designation_from_csv',
        employee:        base + 'save_employee_from_csv'
    };
    const logUrlMap = {
        product:         base + 'save_product_bulk_log',
        conversionratio: base + 'save_conversionratio_bulk_log',
        brand:           base + 'save_brand_bulk_log',
        category:        base + 'save_category_bulk_log',
        subcategory:     base + 'save_subcategory_bulk_log',
        unit:            base + 'save_unit_bulk_log',
        paymentmethod:   base + 'save_paymentmethod_bulk_log',
        branch:          base + 'save_branch_bulk_log',
        store:           base + 'save_store_bulk_log',
        customer:        base + 'save_customer_bulk_log',
        supplier:        base + 'save_supplier_bulk_log',
        service:         base + 'save_service_bulk_log',
        productgroup:    base + 'save_productgroup_bulk_log',
        openingstock:    base + 'save_openingstock_bulk_log',
        stockbatch:      base + 'save_stockbatch_bulk_log',
        designation:     base + 'save_designation_bulk_log',
        employee:        base + 'save_employee_bulk_log'
    };
    const logKeyMap = {
        product: 'product_ids', conversionratio: 'conversionratio_ids',
        brand: 'brand_ids', category: 'category_ids', subcategory: 'subcategory_ids',
        unit: 'unit_ids', paymentmethod: 'paymentmethod_ids',
        branch: 'branch_ids', store: 'store_ids', customer: 'customer_ids',
        supplier: 'supplier_ids', service: 'service_ids', productgroup: 'productgroup_ids',
        openingstock: 'openingstock_ids',
        stockbatch:   'stockbatch_ids',
        designation:  'designation_ids',
        employee:     'employee_ids'
    };
    const saveUrl = saveUrlMap[fn];
    const logUrl  = logUrlMap[fn] || null;
    const logKey  = logKeyMap[fn] || 'ids';

    let savedIds = [], failCount = 0;
    for (let idx = 0; idx < validatedData.length; idx++) {
        const item = validatedData[idx];
        const row  = previewTableData.find(d => !d.hasError && d._key === item._key);
        try {
            const resp   = await $.ajax({ url: saveUrl, type: 'POST', data: item.payload });
            const result = JSON.parse(resp);
            if (result && result.status === 'Success') {
                savedIds.push(result.id);
                if (row) { row.badge = '<span class="label label-success">Saved</span>'; row.rowColor = '#f0fff4'; }
            } else {
                failCount++;
                const msg = result && result.message ? result.message : JSON.stringify(result);
                if (row) { row.badge = '<span class="label label-danger">' + esc(msg) + '</span>'; row.rowColor = '#fff5f5'; }
            }
        } catch(e) {
            failCount++;
            if (row) { row.badge = '<span class="label label-danger">Request failed</span>'; row.rowColor = '#fff5f5'; }
        }
        setStatus('Saving ' + (idx+1) + ' / ' + validatedData.length + '…', '#e6a817');
        if (previewDT) previewDT.clear().rows.add(previewTableData).draw(false);
    }

    if (savedIds.length > 0 && logUrl) {
        const logData = {};
        logData[logKey] = savedIds;
        await $.ajax({ url: logUrl, type: 'POST', data: logData });
        if (fn === 'product'        && productDT)       productDT.ajax.reload();
        if (fn === 'conversionratio' && crDT)           crDT.ajax.reload();
        if (fn === 'brand'          && brandDT)         brandDT.ajax.reload();
        if (fn === 'category'       && categoryDT)      categoryDT.ajax.reload();
        if (fn === 'subcategory'    && subcategoryDT)   subcategoryDT.ajax.reload();
        if (fn === 'unit'           && unitDT)          unitDT.ajax.reload();
        if (fn === 'paymentmethod'  && paymentmethodDT) paymentmethodDT.ajax.reload();
        if (fn === 'branch'         && branchDT)        branchDT.ajax.reload();
        if (fn === 'store'          && storeDT)         storeDT.ajax.reload();
        if (fn === 'customer'       && customerDT)      customerDT.ajax.reload();
        if (fn === 'supplier'       && supplierDT)      supplierDT.ajax.reload();
        if (fn === 'service'        && serviceDT)       serviceDT.ajax.reload();
        if (fn === 'productgroup'   && productgroupDT)   productgroupDT.ajax.reload();
        if (fn === 'openingstock'   && openingstockDT)   openingstockDT.ajax.reload();
        if (fn === 'stockbatch'     && stockbatchDT)     stockbatchDT.ajax.reload();
        if (fn === 'designation'    && designationDT)    designationDT.ajax.reload();
        if (fn === 'employee'       && employeeDT)       employeeDT.ajax.reload();
    }

    $('#btn_save').prop('disabled', false).hide();
    $('#btn_validate').prop('disabled', false);
    validatedData = [];
    setStatus(savedIds.length + ' saved, ' + failCount + ' failed.', failCount > 0 ? '#c0392b' : '#27ae60');
}

/* ── Generic bulk-details helper ────────────────────────────── */
function showGenericBulkDetails(id, urlPath, headers, responseKeys, title) {
    if ($.fn.DataTable.isDataTable('#bulkDetailsTable')) {
        $('#bulkDetailsTable').DataTable().destroy();
        $('#bulkDetailsTable tbody').empty();
    }
    if (typeof bulkDetailsDT !== 'undefined') bulkDetailsDT = null;
    if (typeof bulkDetailsPending !== 'undefined') bulkDetailsPending = null;
    window._genericDetailsDT = null;

    $('#detailsModalTitle').text(title);
    $('#detailsTableHead').html('<tr>' + headers.map(function(h){ return '<th>' + h + '</th>'; }).join('') + '</tr>');

    $.get($('#base_url').val() + urlPath + id, function(raw) {
        var items; try { items = JSON.parse(raw); } catch(e){ items = []; }
        var mapped = items.map(function(item, i) {
            var row = { sl: i + 1 };
            responseKeys.forEach(function(k, j){ row['c'+j] = (item[k] != null) ? item[k] : ''; });
            return row;
        });
        var cols = [{ data:'sl', width:'40px' }].concat(
            responseKeys.map(function(k, j){ return { data:'c'+j }; })
        );
        window._genericDetailsDT = $('#bulkDetailsTable').DataTable({
            data: mapped, columns: cols, pageLength: 10,
            lengthMenu: [[10,25,50],[10,25,50]], ordering: false, autoWidth: false
        });
    });
    $('#bulkDetailsModal').modal('show');
}
$('#bulkDetailsModal').on('hidden.bs.modal.generic', function() {
    if (window._genericDetailsDT) {
        try { window._genericDetailsDT.destroy(); } catch(e) {}
        window._genericDetailsDT = null;
    }
});


function clearAll() {
    $('#csvFile').val('');
    $('#btn_save, #btn_clear').hide();
    $('#preview_panel').hide();
    setStatus('');
    validatedData    = [];
    previewTableData = [];
    if (previewDT) { previewDT.destroy(); previewDT = null; }
    $(ALL_PREVIEW_TABLES).hide();
    $('tbody', ALL_PREVIEW_TABLES).empty();
}
</script>
