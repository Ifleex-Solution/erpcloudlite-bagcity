<?php
defined('BASEPATH') OR exit('No direct script access allowed');


//stockbatch part
$route['stockbatch_form']         = "stock/stock/bdtask_stockbatch_form";
$route['stockbatch_form/(:any)']  = "stock/stock/bdtask_stockbatch_form/$1";
$route['stockbatchlist']         = "stock/stock/bdtask_stockbatchlist";

//stock adjustment part
$route['newstockadjustment_form']         = "stock/stock/bdtask_newstockadjustment_form";
$route['newstockadjustment_form/(:any)']  = "stock/stock/bdtask_newstockadjustment_form/$1";
$route['manage_stock_adjustment']         = "stock/stock/bdtask_manage_stock_adjustment";

//new inventory transection part
$route['new_inventory_transection']         = "stock/stock/bdtask_new_stock_form";
$route['new_inventory_transection/(:any)']  = "stock/stock/bdtask_new_stock_form/$1";

//manage inventory transection part
$route['manage_inventory_transection']      = "stock/stock/bdtask_manage_inventory_transection";

$route['save_openingstock_from_csv']   = "stock/stock/save_openingstock_from_csv";

//GRN
$route['new_grn']         = "stock/stock/bdtask_newgrn_form";
$route['new_grn/(:any)']  = "stock/stock/bdtask_newgrn_form/$1";
$route['manage_grn']         = "stock/stock/bdtask_manage_grn";


//GDN
$route['new_gdn']         = "stock/stock/bdtask_newgdn_form";
$route['new_gdn/(:any)']  = "stock/stock/bdtask_newgdn_form/$1";
$route['manage_gdn']         = "stock/stock/bdtask_manage_gdn";






