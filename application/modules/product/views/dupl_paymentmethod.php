<!-- ── Payment Method: Upload History ────────────────────────── -->
<div class="row" id="history_paymentmethod" style="display:none;">
    <div class="col-sm-12">
        <div class="panel panel-bd">
            <div class="panel-heading">
                <div class="panel-title"><h4>DUPL History - Payment Method</h4></div>
            </div>
            <div class="panel-body">
                <table class="table table-striped table-bordered" id="bulkPaymentMethodTable">
                    <thead>
                        <tr>
                            <th><?php echo display('sl') ?></th>
                            <th>Upload ID</th><th>Date</th><th>Uploaded By</th>
                            <th><?php echo display('action') ?></th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
function initPaymentMethodDT() {
    paymentmethodDT = $('#bulkPaymentMethodTable').DataTable({
        responsive: true, processing: true, serverSide: true,
        order: [[1,'desc']], lengthMenu: [[10,25,50,100],[10,25,50,100]],
        dom: 'lfrtip', serverMethod: 'post',
        ajax: { url: $('#base_url').val() + 'checkBulkPaymentMethodUpload', data: { csrf_test_name: $('#CSRF_TOKEN').val() } },
        columns: [{ data:'sl' },{ data:'uploaded_id' },{ data:'date' },{ data:'name' },{ data:'button', orderable:false }]
    });
}
function showBulkPaymentMethodDetails(id) { showGenericBulkDetails(id, 'get_bulk_paymentmethod_details/', ['#','Payment Method'], ['name'], 'Payment Method Upload Details'); }
function deleteBulkPaymentMethod(id) {
    if (!confirm('Delete this batch and all its payment methods?')) return;
    $.post($('#base_url').val() + 'delete_bulk_paymentmethod/' + id, function() { if (paymentmethodDT) paymentmethodDT.ajax.reload(); });
}
</script>
