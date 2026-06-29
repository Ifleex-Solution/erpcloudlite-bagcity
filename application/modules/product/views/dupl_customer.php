<!-- ── Customer: Upload History ─────────────────────────────── -->
<div class="row" id="history_customer" style="display:none;">
    <div class="col-sm-12">
        <div class="panel panel-bd">
            <div class="panel-heading"><div class="panel-title"><h4>DUPL History - Customer</h4></div></div>
            <div class="panel-body">
                <table class="table table-striped table-bordered" id="bulkCustomerTable">
                    <thead><tr>
                        <th><?php echo display('sl') ?></th>
                        <th>Upload ID</th><th>Date</th><th>Uploaded By</th>
                        <th><?php echo display('action') ?></th>
                    </tr></thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
function validateCustomerRows(rows) {
    $('#preview_table_customer').show();
    const seenNames = new Set();
    for (let i = 0; i < rows.length; i++) {
        const r          = rows[i];
        const name       = (r['Customer Name']          || '').trim();
        const calName    = (r['Calling Name']           || '').trim();
        const bilName    = (r['Billing Name']           || '').trim();
        const mobile     = (r['Mobile']                 || '').trim();
        const phone      = (r['Phone']                  || '').trim();
        const email      = (r['Email']                  || '').trim();
        const nic        = (r['NIC']                    || '').trim();
        const country    = (r['Country']                || '').trim();
        const state      = (r['State']                  || '').trim();
        const city       = (r['City']                   || '').trim();
        const zip        = (r['Zip']                    || '').trim();
        const address    = (r['Address']                || '').trim();
        const address2   = (r['Address 2']              || '').trim();
        const stRaw      = (r['Status (Yes/No)']        || '').trim().toLowerCase();
        // BR No and VAT No columns are intentionally ignored
        let error = null;

        if (!name)                                      error = 'Customer Name is required';
        else if (seenNames.has(name.toLowerCase()))     error = 'Duplicate name in CSV: "' + name + '"';
        else if (db_customer_names.has(name.toLowerCase())) error = 'Customer already exists: "' + name + '"';
        else if (!['yes','no'].includes(stRaw))         error = 'Status must be Yes or No';
        if (!error) seenNames.add(name.toLowerCase());

        const statusLabel = stRaw==='yes' ? '<span class="label label-success">Active</span>' : '<span class="label label-default">Inactive</span>';
        const badge = error ? '<span class="label label-danger" title="'+esc(error)+'">'+esc(error)+'</span>' : '<span class="label label-success">OK</span>';
        if (error) {
            previewTableData.push({ idx:i+1, name:esc(name), mobile:esc(mobile), email:esc(email), statusLabel, badge, rowColor:'#fff5f5', hasError:true, _key:name });
        } else {
            validatedData.push({ _key:name, payload:{ customer_name:name, customer_calling_name:calName, customer_billing_name:bilName, customer_mobile:mobile, phone, email_address:email, nic_no:nic, country, state, city, zip, customer_address:address, address2, status:stRaw==='yes'?1:0 } });
            previewTableData.push({ idx:i+1, name:esc(name), mobile:esc(mobile), email:esc(email), statusLabel, badge, rowColor:'', hasError:false, _key:name });
        }
    }
    buildPreviewDT(['#','Customer Name','Mobile','Email','Status','Validation'],
                   ['idx','name','mobile','email','statusLabel','badge'], '#preview_table_customer');
    finishValidation(rows.length);
}
function initCustomerDT() {
    customerDT = $('#bulkCustomerTable').DataTable({
        responsive:true, processing:true, serverSide:true, order:[[1,'desc']],
        dom:'lfrtip', serverMethod:'post',
        ajax:{ url:$('#base_url').val()+'checkBulkCustomerUpload', data:{ csrf_test_name:$('#CSRF_TOKEN').val() } },
        columns:[{data:'sl'},{data:'uploaded_id'},{data:'date'},{data:'name'},{data:'button',orderable:false}]
    });
}
function showBulkCustomerDetails(id) { showGenericBulkDetails(id,'get_bulk_customer_details/',['#','Customer Name','Mobile','Email'],['customer_name','mobile','email_address'],'Customer Upload Details'); }
function deleteBulkCustomer(id) {
    if (!confirm('Delete this batch and all its customers?')) return;
    $.post($('#base_url').val()+'delete_bulk_customer/'+id, function(){ if(customerDT) customerDT.ajax.reload(); });
}
</script>
