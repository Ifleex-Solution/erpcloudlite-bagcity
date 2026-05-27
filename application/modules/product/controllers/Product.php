<?php
defined('BASEPATH') or exit('No direct script access allowed');
#------------------------------------    
# Author: Bdtask Ltd
# Author link: https://www.bdtask.com/
# Dynamic style php file
# Developed by :Isahaq
#------------------------------------    
require_once("./vendor/Config.php");

class Product extends MX_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->model(array(
            'product_model',
            'supplier/supplier_model'
        ));
        $this->load->library('ciqrcode');
        if (!$this->session->userdata('isLogIn'))
            redirect('login');
    }

    // category part
    function bdtask_category_list()
    {
        $data['title']      = "Category List";
        $data['module']     = "product";
        $data['page']       = "category_list";
        $data["category_list"] = $this->product_model->category_list();
        if (!$this->permission1->method('manage_category', 'read')->access()) {
            $previous_url = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : base_url();
            redirect($previous_url);
        }
        echo modules::run('template/layout', $data);
    }


    public function bdtask_category_form($id = null)
    {
        $data['title'] = display('add_category');
        #-------------------------------#
        $this->form_validation->set_rules('category_name', display('category_name'), 'required|max_length[200]');
        $this->form_validation->set_rules('status', display('status'), 'max_length[2]');
        #-------------------------------#
        $data['category'] = (object)$postData = [
            'category_id'      => $id,
            'category_name'    => $this->input->post('category_name', true),
            'status'           => $this->input->post('status', true),
        ];

        if (!$this->permission1->method('manage_category', 'create')->access()) {
            $previous_url = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : base_url();
            redirect($previous_url);
        }

        $base_url = base_url();

        #-------------------------------#
        if ($this->form_validation->run() === true) {

            #if empty $id then insert data
            if (empty($id)) {
                if ($this->product_model->create_category($postData)) {
                    if (isset($_POST['add-another'])) {
                        echo '
                        <script type="text/javascript">
                        alert("Category Details Saved successfully");
                        window.location.href = "' . $base_url . 'category_form";
                       </script>';
                        exit;
                    } else {

                        echo '<script type="text/javascript">
                        alert("Category Details Saved successfully");
                        window.location.href = "' . $base_url . 'category_list";
                       </script>';
                    }
                } else {
                    $message = display('please_try_again');

                    if (isset($_POST['add-another'])) {
                        echo '
                        <script type="text/javascript">
                        alert("' . $message . '");
                        window.location.href = "' . $base_url . 'category_form";
                       </script>';
                        exit;
                    } else {

                        echo '<script type="text/javascript">
                        alert("' . $message . '");
                        window.location.href = "' . $base_url . 'category_list";
                       </script>';
                    }
                }
            } else {
                if ($this->product_model->update_category($postData)) {
                    if (isset($_POST['add-another'])) {
                        echo '
                        <script type="text/javascript">
                        alert("Category Details Updated successfully");
                        window.location.href = "' . $base_url . 'category_form";
                       </script>';
                        exit;
                    } else {

                        echo '<script type="text/javascript">
                        alert("Category Details Updated successfully");
                        window.location.href = "' . $base_url . 'category_list";
                       </script>';
                    }
                } else {
                    $message = display('please_try_again');

                    if (isset($_POST['add-another'])) {
                        echo '
                        <script type="text/javascript">
                        alert("' . $message . '");
                        window.location.href = "' . $base_url . 'category_form";
                       </script>';
                        exit;
                    } else {

                        echo '<script type="text/javascript">
                        alert("' . $message . '");
                        window.location.href = "' . $base_url . 'category_list";
                       </script>';
                    }
                }
            }
        } else {
            if (!empty($id)) {
                $data['title']    = display('edit_category');
                $data['category'] = $this->product_model->single_category_data($id);
            }
            $data['module']   = "product";
            $data['page']     = "category_form";
            echo Modules::run('template/layout', $data);
        }
    }



    public function bdtask_deletecategory($id = null)
    {
        $base_url = base_url();

        if ($this->product_model->delete_category($id)) {
            echo '<script type="text/javascript">
            alert("Category Details Deleted successfully");
            window.location.href = "' . $base_url . 'category_list";
           </script>';
        } else {
            echo '<script type="text/javascript">
            alert("Cannot delete this category because products are linked to it or something went wrong");
            window.location.href = "' . $base_url . 'category_list";
           </script>';
        }
    }

    // unit part
    function bdtask_unit_list()
    {
        $data['title']      = "Unit List";
        $data['module']     = "product";
        $data['page']       = "unit_list";
        $data["unit_list"] = $this->product_model->unit_list();
        if (!$this->permission1->method('manage_unit', 'read')->access()) {
            $previous_url = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : base_url();
            redirect($previous_url);
        }
        echo modules::run('template/layout', $data);
    }


    public function bdtask_unit_form($id = null)
    {
        $data['title'] = display('add_unit');
        #-------------------------------#
        $this->form_validation->set_rules('unit_name', display('unit_name'), 'required|max_length[200]');
        $this->form_validation->set_rules('status', display('status'), 'max_length[2]');
        #-------------------------------#
        $data['unit'] = (object)$postData = [
            'unit_id'      => $id,
            'unit_name'    => $this->input->post('unit_name', true),
            'unit_display_name'    => $this->input->post('unit_display_name', true),

            'status'       => $this->input->post('status', true),
        ];

        if (!$this->permission1->method('manage_unit', 'create')->access()) {
            $previous_url = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : base_url();
            redirect($previous_url);
        }

        $base_url = base_url();
        #-------------------------------#
        if ($this->form_validation->run() === true) {

            #if empty $id then insert data
            if (empty($id)) {
                if ($this->product_model->create_unit($postData)) {
                    if (isset($_POST['add-another'])) {
                        echo '
                        <script type="text/javascript">
                        alert("Unit Details Saved successfully");
                        window.location.href = "' . $base_url . 'unit_form";
                       </script>';
                        exit;
                    } else {

                        echo '<script type="text/javascript">
                        alert("Unit Details Saved successfully");
                        window.location.href = "' . $base_url . 'unit_list";
                       </script>';
                    }
                } else {
                    $message = display('please_try_again');

                    if (isset($_POST['add-another'])) {
                        echo '
                        <script type="text/javascript">
                        alert("' . $message . '");
                        window.location.href = "' . $base_url . 'unit_form";
                       </script>';
                        exit;
                    } else {

                        echo '<script type="text/javascript">
                        alert("' . $message . '");
                        window.location.href = "' . $base_url . 'unit_list";
                       </script>';
                    }
                }
            } else {


                if ($this->product_model->update_unit($postData)) {
                    if (isset($_POST['add-another'])) {
                        echo '
                        <script type="text/javascript">
                        alert("Unit Details Updated successfully");
                        window.location.href = "' . $base_url . 'unit_form";
                       </script>';
                        exit;
                    } else {

                        echo '<script type="text/javascript">
                        alert("Unit Details Updated successfully");
                        window.location.href = "' . $base_url . 'unit_list";
                       </script>';
                    }
                } else {
                    $message = display('please_try_again');

                    if (isset($_POST['add-another'])) {
                        echo '
                        <script type="text/javascript">
                        alert("' . $message . '");
                        window.location.href = "' . $base_url . 'unit_form";
                       </script>';
                        exit;
                    } else {

                        echo '<script type="text/javascript">
                        alert("' . $message . '");
                        window.location.href = "' . $base_url . 'unit_list";
                       </script>';
                    }
                }
            }
        } else {
            if (!empty($id)) {
                $data['title']    = display('edit_unit');
                $data['unit'] = $this->product_model->single_unit_data($id);
            }
            $data['module']   = "product";
            $data['page']     = "unit_form";
            echo Modules::run('template/layout', $data);
        }
    }



    public function bdtask_deleteunit($id = null)
{
    $base_url = base_url();

    if ($this->product_model->delete_unit($id)) {
        echo '<script type="text/javascript">
        alert("Unit Details Deleted successfully");
        window.location.href = "' . $base_url . 'unit_list";
       </script>';
    } else {
        echo '<script type="text/javascript">
        alert("Cannot delete this Unit because it is being used in product information, stock transactions, subunit products, or conversion ratios. Please remove all references before deleting.");
        window.location.href = "' . $base_url . 'unit_list";
       </script>';
    }
}

    public function getProductById()
    {
        $this->db->select('*');
        $this->db->from('product_information a');
        $this->db->where('a.product_id', $this->input->post('code'));
        $query = $this->db->get();
        $num_rows = $query->num_rows();
        if ($num_rows > 0) {
            echo json_encode("not success");
        } else {
            echo json_encode("success");
        }
    }

    public function getActivefloorBystoreId()
    {
        $data = $this->product_model->active_floorByStore($this->input->post('id', TRUE));
        echo json_encode($data);
    }


    // product part
    public function bdtask_product_form($id = null)
    {
        $data['title'] = display('add_product');
        $data['product_open']   = null;
        #-------------------------------#
        $this->form_validation->set_rules('product_name', display('product_name'), 'required|max_length[200]');
        $this->form_validation->set_rules('subcategory_id', 'Subcategory', 'required|max_length[20]');
        $this->form_validation->set_rules('unit', display('unit'), 'required');
        $this->form_validation->set_rules('status', "Status", 'required');
        $this->form_validation->set_rules('store', "Store", 'required');
        $this->form_validation->set_rules('product_type', 'Product Type', 'required');

        if (!$this->permission1->method('manage_product', 'create')->access()) {
            $previous_url = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : base_url();
            redirect($previous_url);
        }


        $product_id = (!empty($this->input->post('product_id', TRUE)) ? $this->input->post('product_id', TRUE) : $this->generator(8));
        $sup_price = $this->input->post('supplier_price', TRUE);
        // $s_id      = $this->input->post('supplier_id',TRUE);
        $product_model = $this->input->post('model', TRUE);
        $taxfield = $this->db->select('tax_name,default_value')
            ->from('tax_settings')
            ->get()
            ->result_array();




        #-------------------------------#
        $data['product'] = (object)$postData = [
            'product_id'     => $this->input->post('product_id', TRUE),
            'product_name'   => $this->input->post('product_name', TRUE),
            'category_id'    => $this->input->post('category_id', TRUE),
            'unit'           => $this->input->post('unit', TRUE),
            'product_vat'            => $this->input->post('vat', TRUE),
            'serial_no'      => $this->input->post('serial_no', TRUE),
            'price'          => $this->input->post('price', TRUE) > 0 ? $this->input->post('price', TRUE) : 0.0,
            'product_model'  => $this->input->post('model', TRUE),
            'cost_price'  => $this->input->post('cost_price', TRUE),

            'product_details' => $this->input->post('description', TRUE),
            'store'  => $this->input->post('store', TRUE),
            // 'floor' => $this->input->post('floor', TRUE),
            // 'product_vat'    => $this->input->post('product_vat', TRUE),
            // 'image'          => (!empty($image) ? $image : 'my-assets/image/product.png'),
            'status'         => $this->input->post('status', TRUE),
            'supplier_id'    => $this->input->post('supplier_id', TRUE),
            'product_type'   => $this->input->post('product_type', TRUE),
            'stock'          => $this->input->post('stock', TRUE),
            'max_stock_level' => $this->input->post('max_stock_level', TRUE),
            'min_stock_level' => $this->input->post('min_stock_level', TRUE),
            'reorder_stock_level' => $this->input->post('reorder_stock_level', TRUE),
            'reserve_stock_level' => $this->input->post('reserve_stock_level', TRUE),
        ];

        $tablecolumn = $this->db->list_fields('tax_collection');
        $num_column = count($tablecolumn) - 4;
        if ($num_column > 0) {
            $txf = [];
            for ($i = 0; $i < $num_column; $i++) {
                $txf[$i] = 'tax' . $i;
            }
            foreach ($txf as $key => $value) {
                $postData[$value] = (!empty($this->input->post($value)) ? $this->input->post($value) : 0) / 100;
            }
        }
        $encryption_key = Config::$encryption_key;



        $base_url = base_url();

        #-------------------------------#
        if ($this->form_validation->run() === true) {

            #if empty $id then insert data
            if (empty($id)) {
                $query = "
    INSERT INTO product_information 
    (product_id, product_name, category_id, unit, product_vat, serial_no, price, product_model, cost_price, product_details, 
    store, status,sprice,subunit,scost_price,subcategory_id,brand_id,oop_id,printname,supplier_id,product_type,stock,max_stock_level,min_stock_level,reorder_stock_level,reserve_stock_level) 
    VALUES 
    ('{$this->input->post('product_id', TRUE)}',
     '{$this->input->post('product_name', TRUE)}',
     '{$this->input->post('category_id', TRUE)}',
     '{$this->input->post('unit', TRUE)}',
     '{$this->input->post('vat', TRUE)}',
     '{$this->input->post('serial_no', TRUE)}',
     AES_ENCRYPT('{$this->input->post('price', TRUE)}', '{$encryption_key}'),
     '{$this->input->post('model', TRUE)}',
     AES_ENCRYPT('{$this->input->post('cost_price', TRUE)}', '{$encryption_key}'),
     '{$this->input->post('description', TRUE)}',
     '{$this->input->post('store', TRUE)}',
     '{$this->input->post('status', TRUE)}',
     AES_ENCRYPT('{$this->input->post('sprice', TRUE)}', '{$encryption_key}'),
          '{$this->input->post('subunit', TRUE)}',
     AES_ENCRYPT('{$this->input->post('scost_price', TRUE)}', '{$encryption_key}'),
          '{$this->input->post('subcategory_id', TRUE)}',
     '{$this->input->post('brand_id', TRUE)}',
     '{$this->input->post('oop_id', TRUE)}',
        '{$this->input->post('printname', TRUE)}',
    '{$this->input->post('supplier_id', TRUE)}',
    '{$this->input->post('product_type', TRUE)}',
    '{$this->input->post('stock', TRUE)}',
    '{$this->input->post('max_stock_level', TRUE)}',
    '{$this->input->post('min_stock_level', TRUE)}',
    '{$this->input->post('reorder_stock_level', TRUE)}',
    '{$this->input->post('reserve_stock_level', TRUE)}'

    );";


                if ($this->product_model->create_product($query)) {
                    if ($this->input->post('button', true) == "add-another") {
                        echo '
                        <script type="text/javascript">
                        alert("Product Details Saved successfully");
                        window.location.href = "' . $base_url . 'product_form";
                       </script>';
                        exit;
                    } else {

                        echo '<script type="text/javascript">
                        alert("Product Details Saved successfully");
                        window.location.href = "' . $base_url . 'product_list";
                       </script>';
                    }
                } else {
                    $message = display('please_try_again');

                    if ($this->input->post('button', true) == "add-another") {
                        echo '
                        <script type="text/javascript">
                        alert("' . $message . '");
                        window.location.href = "' . $base_url . 'product_form";
                       </script>';
                        exit;
                    } else {

                        echo '<script type="text/javascript">
                        alert("' . $message . '");
                        window.location.href = "' . $base_url . 'product_list";
                       </script>';
                    }
                }
            } else {
                $query = "
    UPDATE product_information 
    SET 
        product_name = '{$this->input->post('product_name', TRUE)}',
        category_id = '{$this->input->post('category_id', TRUE)}',
        unit = '{$this->input->post('unit', TRUE)}',
        product_vat = '{$this->input->post('vat', TRUE)}',
        serial_no = '{$this->input->post('serial_no', TRUE)}',
        price = AES_ENCRYPT('{$this->input->post('price', TRUE)}', '{$encryption_key}'),
        product_model = '{$this->input->post('model', TRUE)}',
        cost_price = AES_ENCRYPT('{$this->input->post('cost_price', TRUE)}', '{$encryption_key}'),
        product_details = '{$this->input->post('description', TRUE)}',
        store = '{$this->input->post('store', TRUE)}',
        status = '{$this->input->post('status', TRUE)}',
        sprice= AES_ENCRYPT('{$this->input->post('sprice', TRUE)}', '{$encryption_key}'),
        subunit=  '{$this->input->post('subunit', TRUE)}',
                printname=  '{$this->input->post('printname', TRUE)}',
        scost_price= AES_ENCRYPT('{$this->input->post('scost_price', TRUE)}', '{$encryption_key}'),
        supplier_id = '{$this->input->post('supplier_id', TRUE)}',
        product_type = '{$this->input->post('product_type', TRUE)}',
        stock = '{$this->input->post('stock', TRUE)}',
        max_stock_level = '{$this->input->post('max_stock_level', TRUE)}',
        min_stock_level = '{$this->input->post('min_stock_level', TRUE)}',
        reorder_stock_level = '{$this->input->post('reorder_stock_level', TRUE)}',
        reserve_stock_level = '{$this->input->post('reserve_stock_level', TRUE)}'
    WHERE id = '{$id}';
";
                if ($this->product_model->update_product($query)) {
                    if ($this->input->post('button', true) == "add-another") {
                        echo '
        <script type="text/javascript">
        alert("Product Details Updated successfully");
        window.location.href = "' . $base_url . 'product_form";
       </script>';
                        exit;
                    } else {

                        echo '<script type="text/javascript">
        alert("Product Details Updated successfully");
        window.location.href = "' . $base_url . 'product_list";
       </script>';
                    }
                } else {
                    $message = display('please_try_again');

                    if ($this->input->post('button', true) == "add-another") {
                        echo '
        <script type="text/javascript">
        alert("' . $message . '");
        window.location.href = "' . $base_url . 'product_form";
       </script>';
                        exit;
                    } else {

                        echo '<script type="text/javascript">
        alert("' . $message . '");
        window.location.href = "' . $base_url . 'product_list";
       </script>';
                    }
                }
            }
        } else {
            if (!empty($id)) {
                $data['title']         = display('edit_product');
                $data['product']       = $this->product_model->single_product_data($id);
                $data['subunit_product']       = $this->product_model->single_subunit_product($id);
                $data['subunit_conversions']       = $this->product_model->get_conversionrations($id);
                $data['product_id']    = !empty($data['product']->product_id) ? $data['product']->product_id : '';
            } else {
                $sql3 = "SELECT MAX(product_id)+1 AS highest_product_id FROM product_information;";
                $query3 = $this->db->query($sql3);
                $result3 = $query3->row();
                $data['productId'] = !empty($result3->highest_product_id) ? str_pad($result3->highest_product_id, 6, '0', STR_PAD_LEFT) : "000001";
            }
            $data['supplier']      = $this->product_model->supplier_list();
            $data['vattaxinfo']    = $this->product_model->vat_tax_setting();
            $data['id']            =  $id;
            $data['subcategory_list'] = $this->product_model->active_subcategory();
            $data['category_list'] = $this->product_model->active_category();

            $data['brand_list'] = $this->product_model->active_brand();
            $data['oop_list'] = $this->product_model->active_oop();


            $data['store_list'] = $this->product_model->active_store();
            $data['unit_list']     = $this->product_model->active_unit();
            $data['supplier_pr']   = $this->product_model->supplier_product_list($id);
            $data['product_open']   = $this->product_model->product_opening($id);
            $data['vtinfo']   = $this->db->select('*')->from('vat_tax_setting')->get()->row();
            $data['taxfield']      = $taxfield;
            $data['module']        = "product";
            $data['page']          = "product_form";

            echo Modules::run('template/layout', $data);
        }
    }




    public function bdtask_product_list()
    {
        $data['title']         = display('manage_product');
        $data['total_product'] = $this->db->count_all("product_information");
        $data['module']        = "product";
        $data['page']          = "product_list";
        if (!$this->permission1->method('manage_product', 'read')->access()) {
            $previous_url = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : base_url();
            redirect($previous_url);
        }
        echo modules::run('template/layout', $data);
    }

    public function CheckProductList()
    {
        $postData = $this->input->post();
        $data = $this->product_model->getProductList($postData);
        echo json_encode($data);
    }

    public function CheckProductGroupList()
    {
        $postData = $this->input->post();
        $data = $this->product_model->getProductGroupList($postData);
        echo json_encode($data);
    }

    public function bdtask_deleteproduct($id = null)
    {
        $base_url = base_url();
        if ($this->product_model->delete_product($id)) {
            echo '<script type="text/javascript">
            alert("Product Details Deleted successfully");
            window.location.href = "' . $base_url . 'product_list ";
           </script>';
        } else {
            echo '<script type="text/javascript">
            alert("Cannot delete this Product  because this product Group is linked to it or something went wrong");
            window.location.href = "' . $base_url . 'product_list";
           </script>';
        }
    }

     public function bdtask_deleteproductgroup($id = null)
    {
        $base_url = base_url();
        if ($this->product_model->delete_product_group($id)) {
            echo '<script type="text/javascript">
            alert("Product Group Details Deleted successfully");
            window.location.href = "' . $base_url . 'product_grouplist";
           </script>';
        } else {
            echo '<script type="text/javascript">
            alert("Cannot delete this Product Group because this product is linked to it or something went wrong");
            window.location.href = "' . $base_url . 'product_grouplist";
           </script>';
        }
    }

    public function bdtask_csv_product()
    {
        $data['title']         = display('add_product_csv');
        $data['module']        = "product";
        $data['page']          = "add_product_csv";
        if (!$this->permission1->method('manage_product', 'create')->access()) {
            $previous_url = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : base_url();
            redirect($previous_url);
        }
        echo modules::run('template/layout', $data);
    }


    function uploadCsv()
    {
        $filename = $_FILES['upload_csv_file']['name'];
        $basenameAndExtension = explode('.', $filename);
        $ext = end($basenameAndExtension);
        if ($ext == 'csv') {
            $count = 0;
            $fp = fopen($_FILES['upload_csv_file']['tmp_name'], 'r') or die("can't open file");

            if (($handle = fopen($_FILES['upload_csv_file']['tmp_name'], 'r')) !== FALSE) {

                while ($csv_line = fgetcsv($fp, 1024)) {
                    //keep this if condition if you want to remove the first row
                    for ($i = 0, $j = count($csv_line); $i < $j; $i++) {
                        $product_id = $this->generator(10);
                        $insert_csv = array();
                        $insert_csv['supplier_id'] = (!empty($csv_line[1]) ? $csv_line[1] : null);
                        $insert_csv['product_name'] = (!empty($csv_line[2]) ? $csv_line[2] : null);
                        $insert_csv['product_model'] = (!empty($csv_line[3]) ? $csv_line[3] : null);
                        $insert_csv['category_id'] = (!empty($csv_line[4]) ? $csv_line[4] : null);
                        $insert_csv['price'] = (!empty($csv_line[5]) ? $csv_line[5] : null);
                        $insert_csv['supplier_price'] = (!empty($csv_line[6]) ? $csv_line[6] : null);
                        $insert_csv['opening_stock'] = (!empty($csv_line[7]) ? $csv_line[7] : null);
                        $insert_csv['opening_batch'] = (!empty($csv_line[8]) ? $csv_line[8] : null);
                    }
                    // $check_supplier = $this -> db -> select('*') -> from('supplier_information') -> where('supplier_name', $insert_csv['supplier_id']) -> get() -> row();
                    // if (!empty($check_supplier)) {
                    //     $supplier_id = $check_supplier -> supplier_id;
                    // } else {
                    //     $supplierinfo = array(
                    //         'supplier_name' => $insert_csv['supplier_id'],
                    //         'address'           => '',
                    //         'mobile'            => '',
                    //         'details'           => '',
                    //         'status'            => 1
                    //     );
                    //     if ($count > 0) {
                    //         $this -> db -> insert('supplier_information', $supplierinfo);
                    //     }
                    //     $supplier_id = $this -> db -> insert_id();
                    //     $coa = $this -> supplier_model -> headcode();
                    //     if ($coa -> HeadCode != NULL) {
                    //         $headcode = $coa -> HeadCode + 1;
                    //     }
                    //     else {
                    //         $headcode = "21110000001";
                    //     }
                    //     $c_acc = $supplier_id.'-'.$insert_csv['supplier_id'];
                    //     $createby = $this -> session -> userdata('id');
                    //     $createdate = date('Y-m-d H:i:s');


                    //     $supplier_coa = [
                    //         'HeadCode'         => $headcode,
                    //         'HeadName'         => $c_acc,
                    //         'PHeadName'        => 'Suppliers',
                    //         'HeadLevel'        => '3',
                    //         'IsActive'         => '1',
                    //         'IsTransaction'    => '1',
                    //         'IsGL'             => '0',
                    //         'HeadType'         => 'L',
                    //         'IsBudget'         => '0',
                    //         'IsDepreciation'   => '0',
                    //         'supplier_id'      => $supplier_id,
                    //         'DepreciationRate' => '0',
                    //         'CreateBy'         => $createby,
                    //         'CreateDate'       => $createdate,
                    //     ];

                    //     if ($count > 0) {
                    //         $this -> db -> insert('acc_coa', $supplier_coa);
                    //     }
                    // }

                    $check_category = $this->db->select('*')->from('product_category')->where('category_name', $insert_csv['category_id'])->get()->row();
                    if (!empty($check_category)) {
                        $category_id = $check_category->category_id;
                    } else {
                        $categorydata = array(
                            'category_name'         => $insert_csv['category_id'],
                            'status'                => 1
                        );
                        if ($count > 0) {
                            $this->db->insert('product_category', $categorydata);
                            $category_id = $this->db->insert_id();
                        }
                    }
                    $data = array(
                        'product_id'    => $product_id,
                        'category_id'   => $category_id,
                        'product_name'  => $insert_csv['product_name'],
                        'product_model' => $insert_csv['product_model'],
                        'price'         => $insert_csv['price'],
                        'unit'          => '',
                        'tax'           => '',
                        'product_details' => 'Csv Product',
                        'image'         => 'my-assets/image/product.png',
                        'status'        => 1
                    );

                    if ($count > 0) {

                        $result = $this->db->select('*')
                            ->from('product_information')
                            ->where('product_name', $data['product_name'])
                            ->where('product_model', $data['product_model'])
                            ->where('category_id', $category_id)
                            ->get()
                            ->row();
                        if (empty($result)) {
                            $this->db->insert('product_information', $data);
                            $product_id = $product_id;
                        } else {
                            $product_id = $result->product_id;
                            $udata = array(
                                'product_id'     => $result->product_id,
                                'category_id'    => $category_id,
                                'product_name'   => $result->product_name,
                                'product_model'  => $insert_csv['product_model'],
                                'price'          => $insert_csv['price'],
                                'unit'           => '',
                                'tax'            => '',
                                'product_details' => 'Csv Uploaded Product',
                                'image'         => 'my-assets/image/product.png',
                                'status'        => 1
                            );
                            $this->db->where('product_id', $result->product_id);
                            $this->db->update('product_information', $udata);
                        }

                        $supp_prd = array(
                            'product_id'     => $product_id,
                            'supplier_id'    => 1,
                            'supplier_price' => $insert_csv['supplier_price'],
                            'products_model' => $insert_csv['product_model'],
                        );

                        $splprd = $this->db->select('*')
                            ->from('supplier_product')
                            ->where('supplier_id', 1)
                            ->where('product_id', $product_id)
                            ->get()
                            ->num_rows();

                        if ($splprd == 0) {
                            $this->db->insert('supplier_product', $supp_prd);
                        } else {
                            $supp_prd = array(
                                'supplier_id'    => 1,
                                'supplier_price' => $insert_csv['supplier_price'],
                                'products_model' => $insert_csv['product_model']
                            );
                            $this->db->where('product_id', $product_id);
                            $this->db->where('supplier_id', 1);
                            $this->db->update('supplier_product', $supp_prd);
                        }



                        $data1 = array(
                            'product_id'         => $product_id,
                            'quantity'           => $insert_csv['opening_stock'],
                            'batch_id'           =>  $insert_csv['opening_batch'],
                            'status'             => 1
                        );
                        $this->db->insert('product_purchase_details', $data1);
                    }
                    $count++;
                }
            }

            $this->session->set_flashdata(array('message' => display('successfully_added')));
            redirect(base_url('product_list'));
        } else {
            $this->session->set_flashdata(array('error_message' => 'Please Import Only Csv File'));
            redirect(base_url('bulk_products'));
        }
    }




    public function qrgenerator($product_id)
    {
        $config['cacheable'] = true; //boolean, the default is true
        $config['cachedir'] = ''; //string, the default is application/cache/
        $config['errorlog'] = ''; //string, the default is application/logs/
        $config['quality'] = true; //boolean, the default is true
        $config['size'] = '1024'; //interger, the default is 1024
        $config['black'] = array(224, 255, 255); // array, default is 
        $config['white'] = array(70, 130, 180); // array, default is array(0,0,0)
        $this->ciqrcode->initialize($config);
        $params['data'] = $product_id;
        $params['level'] = 'H';
        $params['size'] = 10;
        $image_name = $product_id . '.png';
        $params['savename'] = FCPATH . 'my-assets/image/qr/' . $image_name;
        $this->ciqrcode->generate($params);
        $product_info = $this->product_model->bdtask_barcode_productdata($product_id);
        $data = array(
            'title'           => display('qr_code'),
            'product_name'    => $product_info[0]['product_name'],
            'product_model'   => $product_info[0]['product_model'],
            'price'           => $product_info[0]['price'],
            'product_details' => $product_info[0]['product_details'],
            'qr_image'        => $image_name,
        );
        $data['module']        = "product";
        $data['page']          = "barcode_print_page";
        echo modules::run('template/layout', $data);
    }


    // bar code part
    // public function barcode_print($product_id)
    // {
    //     $product_info = $this->product_model->bdtask_barcode_productdata($product_id);

    //     $data = array(
    //         'title'           => display('barcode'),
    //         'product_id'      => $product_id,
    //         'product_name'    => $product_info[0]['product_name'],
    //         'product_model'   => $product_info[0]['product_model'],
    //         'price'           => $product_info[0]['price'],
    //         'product_details' => $product_info[0]['product_details'],

    //     );

    //     $data['module']        = "product";
    //     $data['page']          = "barcode_print_page";
    //     echo modules::run('template/layout', $data);
    // }


    public function bdtask_product_details($product_id = null)
    {
        $details_info = $this->product_model->bdtask_barcode_productdata($product_id);
        $purchaseData = $this->product_model->product_purchase_info($product_id);
        $totalPurchase = 0;
        $totalPrcsAmnt = 0;

        if (!empty($purchaseData)) {
            foreach ($purchaseData as $k => $v) {
                $purchaseData[$k]['final_date'] = $purchaseData[$k]['date'];
                $totalPrcsAmnt = ($totalPrcsAmnt + $purchaseData[$k]['total_amount']);
                $totalPurchase = ($totalPurchase + $purchaseData[$k]['quantity']);
            }
        }

        $salesData = $this->product_model->invoice_data($product_id);

        $totalSales = 0;
        $totaSalesAmt = 0;
        if (!empty($salesData)) {
            foreach ($salesData as $k => $v) {
                $salesData[$k]['final_date'] = $salesData[$k]['date'];
                $totalSales = ($totalSales + $salesData[$k]['quantity']);
                $totaSalesAmt = ($totaSalesAmt + $salesData[$k]['total_amount']);
            }
        }

        $stock = ($totalPurchase - $totalSales);
        $data = array(
            'title'               => display('product_details'),
            'product_name'        => $details_info[0]['product_name'],
            'product_model'       => $details_info[0]['product_model'],
            'price'               => $details_info[0]['price'],
            'purchaseTotalAmount' => number_format($totalPrcsAmnt, 2, '.', ','),
            'salesTotalAmount'    => number_format($totaSalesAmt, 2, '.', ','),
            'img'                 => $details_info[0]['image'],
            'total_purchase'      => $totalPurchase,
            'total_sales'         => $totalSales,
            'purchaseData'        => $purchaseData,
            'salesData'           => $salesData,
            'stock'               => $stock,
        );

        $data['module']        = "product";
        $data['page']          = "product_details";
        echo modules::run('template/layout', $data);
    }


    public function generator($lenth)
    {
        $number = array("1", "2", "3", "4", "5", "6", "7", "8", "9", "0");

        for ($i = 0; $i < $lenth; $i++) {
            $rand_value = rand(0, 9);
            $rand_number = $number["$rand_value"];

            if (empty($con)) {
                $con = $rand_number;
            } else {
                $con = "$con" . "$rand_number";
            }
        }
        return $con;
    }














    // Brand part
    function bdtask_brand_list()
    {
        $data['title']      = "Brand List";
        $data['module']     = "product";
        $data['page']       = "brand_list";
        $data["brand_list"] = $this->product_model->brand_list();
        if (!$this->permission1->method('brand_list', 'read')->access()) {
            $previous_url = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : base_url();
            redirect($previous_url);
        }
        echo modules::run('template/layout', $data);
    }


    public function bdtask_brand_form($id = null)
    {
        $data['title'] = display('add_brand');
        #-------------------------------#
        $this->form_validation->set_rules('brand_name', "Brand Name", 'required|max_length[200]');
        $this->form_validation->set_rules('status', display('status'), 'max_length[2]');
        #-------------------------------#
        $data['brand'] = (object)$postData = [
            'brand_id'      => $id,
            'brand_name'    => $this->input->post('brand_name', true),
            'status'           => $this->input->post('status', true),
        ];

        if (!$this->permission1->method('brand_list', 'create')->access()) {
            $previous_url = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : base_url();
            redirect($previous_url);
        }

        $base_url = base_url();

        #-------------------------------#
        if ($this->form_validation->run() === true) {

            #if empty $id then insert data
            if (empty($id)) {
                if ($this->product_model->create_brand($postData)) {
                    if (isset($_POST['add-another'])) {
                        echo '
                        <script type="text/javascript">
                        alert("Brand Details Saved successfully");
                        window.location.href = "' . $base_url . 'brand_form";
                       </script>';
                        exit;
                    } else {

                        echo '<script type="text/javascript">
                        alert("Brand Details Saved successfully");
                        window.location.href = "' . $base_url . 'brand_list";
                       </script>';
                    }
                } else {
                    $message = display('please_try_again');

                    if (isset($_POST['add-another'])) {
                        echo '
                        <script type="text/javascript">
                        alert("' . $message . '");
                        window.location.href = "' . $base_url . 'brand_form";
                       </script>';
                        exit;
                    } else {

                        echo '<script type="text/javascript">
                        alert("' . $message . '");
                        window.location.href = "' . $base_url . 'brand_list";
                       </script>';
                    }
                }
            } else {
                if ($this->product_model->update_brand($postData)) {
                    if (isset($_POST['add-another'])) {
                        echo '
                        <script type="text/javascript">
                        alert("Brand Details Updated successfully");
                        window.location.href = "' . $base_url . 'brand_form";
                       </script>';
                        exit;
                    } else {

                        echo '<script type="text/javascript">
                        alert("Brand Details Updated successfully");
                        window.location.href = "' . $base_url . 'brand_list";
                       </script>';
                    }
                } else {
                    $message = display('please_try_again');

                    if (isset($_POST['add-another'])) {
                        echo '
                        <script type="text/javascript">
                        alert("' . $message . '");
                        window.location.href = "' . $base_url . 'brand_form";
                       </script>';
                        exit;
                    } else {

                        echo '<script type="text/javascript">
                        alert("' . $message . '");
                        window.location.href = "' . $base_url . 'brand_list";
                       </script>';
                    }
                }
            }
        } else {
            if (!empty($id)) {
                $data['title']    = "Edit Brand";
                $data['brand'] = $this->product_model->single_brand_data($id);
            }
            $data['module']   = "product";
            $data['page']     = "brand_form";
            echo Modules::run('template/layout', $data);
        }
    }



    public function bdtask_deletebrand($id = null)
    {
        $base_url = base_url();

        if ($this->product_model->delete_brand($id)) {
             echo '<script type="text/javascript">
             alert("Brand Details Deleted successfully");
             window.location.href = "' . $base_url . 'brand_list";
            </script>';
        } else {
             echo '<script type="text/javascript">
             alert("Cannot delete this brand because products are linked to it or something went wrong");
             window.location.href = "' . $base_url . 'brand_list";
       </script>';
        }
    }









    // OOP part
    function bdtask_oop_list()
    {
        $data['title']      = "Origin Of Product List";
        $data['module']     = "product";
        $data['page']       = "oop_list";
        $data["oop_list"] = $this->product_model->oop_list();
        if (!$this->permission1->method('oop_list', 'read')->access()) {
            $previous_url = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : base_url();
            redirect($previous_url);
        }
        echo modules::run('template/layout', $data);
    }


    public function bdtask_oop_form($id = null)
    {
        $data['title'] = display('add_oop');
        #-------------------------------#
        $this->form_validation->set_rules('oop_name', "Origin Of Product", 'required|max_length[200]');
        $this->form_validation->set_rules('status', display('status'), 'max_length[2]');
        #-------------------------------#
        $data['oop'] = (object)$postData = [
            'oop_id'      => $id,
            'oop_name'    => $this->input->post('oop_name', true),
            'status'           => $this->input->post('status', true),
        ];

        if (!$this->permission1->method('oop_list', 'create')->access()) {
            $previous_url = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : base_url();
            redirect($previous_url);
        }

        $base_url = base_url();

        #-------------------------------#
        if ($this->form_validation->run() === true) {

            #if empty $id then insert data
            if (empty($id)) {
                if ($this->product_model->create_oop($postData)) {
                    if (isset($_POST['add-another'])) {
                        echo '
                        <script type="text/javascript">
                        alert("Origin Of Product Details Saved successfully");
                        window.location.href = "' . $base_url . 'oop_form";
                       </script>';
                        exit;
                    } else {

                        echo '<script type="text/javascript">
                        alert("Origin Of Product Details Saved successfully");
                        window.location.href = "' . $base_url . 'oop_list";
                       </script>';
                    }
                } else {
                    $message = display('please_try_again');

                    if (isset($_POST['add-another'])) {
                        echo '
                        <script type="text/javascript">
                        alert("' . $message . '");
                        window.location.href = "' . $base_url . 'oop_form";
                       </script>';
                        exit;
                    } else {

                        echo '<script type="text/javascript">
                        alert("' . $message . '");
                        window.location.href = "' . $base_url . 'oop_list";
                       </script>';
                    }
                }
            } else {
                if ($this->product_model->update_oop($postData)) {
                    if (isset($_POST['add-another'])) {
                        echo '
                        <script type="text/javascript">
                        alert("Origin Of Product Details Updated successfully");
                        window.location.href = "' . $base_url . 'oop_form";
                       </script>';
                        exit;
                    } else {

                        echo '<script type="text/javascript">
                        alert("Origin Of Product Details Updated successfully");
                        window.location.href = "' . $base_url . 'oop_list";
                       </script>';
                    }
                } else {
                    $message = display('please_try_again');

                    if (isset($_POST['add-another'])) {
                        echo '
                        <script type="text/javascript">
                        alert("' . $message . '");
                        window.location.href = "' . $base_url . 'oop_form";
                       </script>';
                        exit;
                    } else {

                        echo '<script type="text/javascript">
                        alert("' . $message . '");
                        window.location.href = "' . $base_url . 'oop_list";
                       </script>';
                    }
                }
            }
        } else {
            if (!empty($id)) {
                $data['title']    = "Edit Origin Of Product";
                $data['oop'] = $this->product_model->single_oop_data($id);
            }
            $data['module']   = "product";
            $data['page']     = "oop_form";
            echo Modules::run('template/layout', $data);
        }
    }



    public function bdtask_deleteoop($id = null)
    {
        $base_url = base_url();

        if ($this->product_model->delete_oop($id)) {
            echo '<script type="text/javascript">
            alert("Origin Of Product Details Deleted successfully");
            window.location.href = "' . $base_url . 'oop_list";
           </script>';
        } else {
            echo '<script type="text/javascript">
            alert("Cannot delete this Origin Of Product because products are linked to it or something went wrong");
            window.location.href = "' . $base_url . 'oop_list";
           </script>';
        }
    }






    // Sub Stock part
    function bdtask_subcategory_list()
    {
        $data['title']      = "Subcategory List";
        $data['module']     = "product";
        $data['page']       = "subcategory_list";
        $data["subcategory_list"] = $this->product_model->subcategory_list();
        if (!$this->permission1->method('subcategory_list', 'read')->access()) {
            $previous_url = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : base_url();
            redirect($previous_url);
        }
        echo modules::run('template/layout', $data);
    }


    public function bdtask_subcategory_form($id = null)
    {
        $data['title'] = display('add_subcategory');
        #-------------------------------#
        $this->form_validation->set_rules('subcategory_name', "Subcategory", 'required|max_length[200]');
        $this->form_validation->set_rules('status', display('status'), 'max_length[2]');
        #-------------------------------#
        $data['oop'] = (object)$postData = [
            'subcategory_id'      => $id,
            'subcategory_name'    => $this->input->post('subcategory_name', true),
            'category_id'    => $this->input->post('category_id', true),
            'status'           => $this->input->post('status', true),
        ];

        if (!$this->permission1->method('subcategory_list', 'create')->access()) {
            $previous_url = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : base_url();
            redirect($previous_url);
        }

        $base_url = base_url();

        #-------------------------------#
        if ($this->form_validation->run() === true) {

            #if empty $id then insert data
            if (empty($id)) {
                if ($this->product_model->create_subcategory($postData)) {
                    if (isset($_POST['add-another'])) {
                        echo '
                        <script type="text/javascript">
                        alert("Subcategory Details Saved successfully");
                        window.location.href = "' . $base_url . 'subcategory_form";
                       </script>';
                        exit;
                    } else {

                        echo '<script type="text/javascript">
                        alert("Subcategory Details Saved successfully");
                        window.location.href = "' . $base_url . 'subcategory_list";
                       </script>';
                    }
                } else {
                    $message = display('please_try_again');

                    if (isset($_POST['add-another'])) {
                        echo '
                        <script type="text/javascript">
                        alert("' . $message . '");
                        window.location.href = "' . $base_url . 'subcategory_form";
                       </script>';
                        exit;
                    } else {

                        echo '<script type="text/javascript">
                        alert("' . $message . '");
                        window.location.href = "' . $base_url . 'subcategory_list";
                       </script>';
                    }
                }
            } else {
                if ($this->product_model->update_subcategory($postData)) {
                    if (isset($_POST['add-another'])) {
                        echo '
                        <script type="text/javascript">
                        alert("Subcategory Details Updated successfully");
                        window.location.href = "' . $base_url . 'subcategory_form";
                       </script>';
                        exit;
                    } else {

                        echo '<script type="text/javascript">
                        alert("Subcategory Details Updated successfully");
                        window.location.href = "' . $base_url . 'subcategory_list";
                       </script>';
                    }
                } else {
                    $message = display('please_try_again');

                    if (isset($_POST['add-another'])) {
                        echo '
                        <script type="text/javascript">
                        alert("' . $message . '");
                        window.location.href = "' . $base_url . 'subcategory_form";
                       </script>';
                        exit;
                    } else {

                        echo '<script type="text/javascript">
                        alert("' . $message . '");
                        window.location.href = "' . $base_url . 'subcategory_list";
                       </script>';
                    }
                }
            }
        } else {
            if (!empty($id)) {
                $data['title']    = "Edit Subcategory";
                $data['subcategory'] = $this->product_model->single_subcategory_data($id);
            }
            $data['category_list'] = $this->product_model->active_category();
            $data['module']   = "product";
            $data['page']     = "subcategory_form";
            echo Modules::run('template/layout', $data);
        }
    }



    public function bdtask_deletesubcategory($id = null)
    {
        $base_url = base_url();

        if ($this->product_model->delete_subcategory($id)) {
            echo '<script type="text/javascript">
            alert("Subcatgory Details Deleted successfully");
            window.location.href = "' . $base_url . 'subcategory_list";
           </script>';
        } else {
            echo '<script type="text/javascript">
            alert("Cannot delete this Subcatgory because products are linked to it or something went wrong");
            window.location.href = "' . $base_url . 'subcategory_list";
           </script>';
        }
    }


    // conversion ratio part

    function bdtask_conversionratio_list()
    {
        $data['title']      = display('conversionratio_list');
        $data['module']     = "product";
        $data['page']       = "conversionratio_list";
        $data["conversionration_list"] = $this->product_model->conversionration_list();
        if (!$this->permission1->method('conversionratio_list', 'read')->access()) {
            $previous_url = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : base_url();
            redirect($previous_url);
        }
        echo modules::run('template/layout', $data);
    }


    public function bdtask_conversionratio_form($id = null)
    {
        $data['title'] = display('conversionratio_form');
        
        // Check if editing and if transactions exist
        $data['has_transactions'] = false;
        if (!empty($id)) {
            $data['has_transactions'] = $this->product_model->check_conversionratio_transactions($id);
        }
        
        #-------------------------------#
        // $this->form_validation->set_rules('date', "Date", 'required|max_length[200]');
        $this->form_validation->set_rules('product_id', "Product", 'required');
        $this->form_validation->set_rules('subunit', "Subunit", 'required');
        $this->form_validation->set_rules('conversion_ratio', "Conversion Ratio", 'required');
       // $this->form_validation->set_rules('convertiontype', "Conversion Type", 'required');
        #-------------------------------#
        $data['conversion_ratio'] = (object)$postData = [
            'conversionratio_id'      => $id,
            // 'date'       => $this->input->post('date', true),
            'product'    => $this->input->post('product_id', true),
            'convertiontype'    => '*',
            'subunit'    => $this->input->post('subunit', true),
            'conversion_ratio'    => $this->input->post('conversion_ratio', true),
            'status'     => 1,
        ];

        if (!$this->permission1->method('conversionratio_list', 'create')->access()) {
            $previous_url = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : base_url();
            redirect($previous_url);
        }


        $base_url = base_url();

        #-------------------------------#
        if ($this->form_validation->run() === true) {
            
            // Prevent editing if transactions exist
            if (!empty($id) && $data['has_transactions']) {
                echo '<script type="text/javascript">
                alert("Cannot update this Conversion Ratio because it has been used in transactions (purchases, sales, stock adjustments, etc.). Please create a new conversion ratio instead.");
                window.location.href = "' . $base_url . 'conversionratio_list";
               </script>';
                return;
            }

            #if empty $id then insert data
            if (empty($id)) {
                // Block duplicate: same product + subunit combination must be unique
                if ($this->product_model->check_duplicate_conversionratio($postData['product'], $postData['subunit'])) {
                    echo '<script type="text/javascript">
                    alert("A conversion ratio for this subunit already exists for the selected product. Each subunit can only have one conversion ratio per product.");
                    window.location.href = "' . $base_url . 'conversionratio_form";
                   </script>';
                    return;
                }

                if ($this->product_model->create_conversionratio($postData)) {
                    if (isset($_POST['add-another'])) {
                        echo '
                        <script type="text/javascript">
                        alert("Conversion Ratio Details Saved successfully");
                        window.location.href = "' . $base_url . 'conversionratio_form";
                       </script>';
                        exit;
                    } else {

                        echo '<script type="text/javascript">
                        alert("Conversion Ratio Details Saved successfully");
                        window.location.href = "' . $base_url . 'conversionratio_list";
                       </script>';
                    }
                } else {
                    $message = display('please_try_again');

                    if (isset($_POST['add-another'])) {
                        echo '
                        <script type="text/javascript">
                        alert("' . $message . '");
                        window.location.href = "' . $base_url . 'conversionratio_form";
                       </script>';
                        exit;
                    } else {

                        echo '<script type="text/javascript">
                        alert("' . $message . '");
                        window.location.href = "' . $base_url . 'conversionratio_list";
                       </script>';
                    }
                }
            } else {
                // Block duplicate: check if another active conversion ratio exists for same product + subunit
                if ($this->product_model->check_duplicate_conversionratio($postData['product'], $postData['subunit'], $id)) {
                    echo '<script type="text/javascript">
                    alert("A conversion ratio for this subunit already exists for the selected product. Each subunit can only have one conversion ratio per product.");
                    window.location.href = "' . $base_url . 'conversionratio_list";
                   </script>';
                    return;
                }

                if ($this->product_model->update_conversionratio($postData)) {
                    if (isset($_POST['add-another'])) {
                        echo '
                        <script type="text/javascript">
                        alert("Conversion Ratio Details Updated successfully");
                        window.location.href = "' . $base_url . 'conversionratio_form";
                       </script>';
                        exit;
                    } else {

                        echo '<script type="text/javascript">
                        alert("Conversion Ratio Details Updated successfully");
                        window.location.href = "' . $base_url . 'conversionratio_list";
                       </script>';
                    }
                } else {
                    $message = display('please_try_again');

                    if (isset($_POST['add-another'])) {
                        echo '
                        <script type="text/javascript">
                        alert("' . $message . '");
                        window.location.href = "' . $base_url . 'conversionratio_form";
                       </script>';
                        exit;
                    } else {

                        echo '<script type="text/javascript">
                        alert("' . $message . '");
                        window.location.href = "' . $base_url . 'conversionratio_list";
                       </script>';
                    }
                }
            }
        } else {
            if (!empty($id)) {
                $data['title']    = "Edit Conversion Ratio";
                $data['conversionratio'] = $this->product_model->single_conversionratio_data($id);
            }
            $data['products'] = $this->product_model->active_product();

            $data['module']   = "product";
            $data['page']     = "conversionratio_form";
            echo Modules::run('template/layout', $data);
        }
    }



    public function bdtask_deleteconversionratio($id = null)
    {
        $base_url = base_url();

        if ($this->product_model->delete_conversionratio($id)) {
            echo '<script type="text/javascript">
            alert("Conversion Ratio Details Deleted successfully");
            window.location.href = "' . $base_url . 'conversionratio_list";
           </script>';
        } else {
            echo '<script type="text/javascript">
            alert("Cannot delete this Conversion Ratio because it has been used in transactions (purchases, sales, stock adjustments, etc.). Please deactivate it instead or create a new one.");
            window.location.href = "' . $base_url . 'conversionratio_list";
           </script>';
        }
    }

    public function save_product()
    {
        $encryption_key = Config::$encryption_key;
        $entries = $this->input->post('entries', TRUE);

        $product_id = trim((string)$this->input->post('product_id', TRUE));
        $generate_product_id = ($product_id === '');
        if ($generate_product_id) {
            // Temporary placeholder value, replaced right after insert with ID-based code.
            $product_id = '0';
        }

        $supplier_id = $this->input->post('supplier_id', TRUE);
        $supplier_id = ($supplier_id === '' || $supplier_id === null) ? 0 : $supplier_id;

        $vat = $this->input->post('vat', TRUE);
        $vat = ($vat === '' || $vat === null) ? 0 : $vat;

        $defaultsaleprice = $this->input->post('defaultsaleprice', TRUE);
        $defaultsaleprice = ($defaultsaleprice === '' || $defaultsaleprice === null) ? 'fixedprice' : $defaultsaleprice;

        $batchtype = $this->input->post('batchtype', TRUE);
        $batchtype = ($batchtype === '' || $batchtype === null) ? 1 : $batchtype;

        $ad = $this->input->post('ad', TRUE);
        $ad = ($ad === null) ? '' : $ad;

        $bd = $this->input->post('bd', TRUE);
        $bd = ($bd === null) ? '' : $bd;

        $stock = $this->input->post('stock', TRUE);
        $stock = ($stock === '' || $stock === null) ? 0 : $stock;

        $max_stock_level = $this->input->post('max_stock_level', TRUE);
        $max_stock_level = is_numeric($max_stock_level) ? $max_stock_level : 0;

        $min_stock_level = $this->input->post('min_stock_level', TRUE);
        $min_stock_level = is_numeric($min_stock_level) ? $min_stock_level : 0;

        $reorder_stock_level = $this->input->post('reorder_stock_level', TRUE);
        $reorder_stock_level = is_numeric($reorder_stock_level) ? $reorder_stock_level : 0;

        $reserve_stock_level = $this->input->post('reserve_stock_level', TRUE);
        $reserve_stock_level = is_numeric($reserve_stock_level) ? $reserve_stock_level : 0;

        $query = "INSERT INTO product_information 
        (product_id, product_name, category_id, unit, product_vat, serial_no, price, product_model, cost_price, product_details, store, status, subcategory_id, brand_id, oop_id, defaultsaleprice, ad, bd, batchtype, printname, supplier_id, product_type, stock, max_stock_level, min_stock_level, reorder_stock_level, reserve_stock_level) 
        VALUES 
        ('{$product_id}',
         '{$this->input->post('product_name', TRUE)}',
         '{$this->input->post('category_id', TRUE)}',
         '{$this->input->post('unit', TRUE)}',
         '{$vat}',
         '{$this->input->post('serial_no', TRUE)}',
         AES_ENCRYPT('{$this->input->post('sell_price', TRUE)}', '{$encryption_key}'),
         '{$this->input->post('product_model', TRUE)}',
         AES_ENCRYPT('{$this->input->post('cost_price', TRUE)}', '{$encryption_key}'),
         '{$this->input->post('description', TRUE)}',
         '{$this->input->post('store', TRUE)}',
         '{$this->input->post('status', TRUE)}',
         '{$this->input->post('subcategory_id', TRUE)}',
         '{$this->input->post('brand_id', TRUE)}',
         '{$this->input->post('oop_id', TRUE)}',
         '{$defaultsaleprice}',
         '{$ad}',
         '{$bd}',
         '{$batchtype}',
         '{$this->input->post('printname', TRUE)}',
         '{$supplier_id}',
         '{$this->input->post('product_type', TRUE)}',
         '{$stock}',
         '{$max_stock_level}',
         '{$min_stock_level}',
         '{$reorder_stock_level}',
         '{$reserve_stock_level}')";

        if ($this->db->query($query)) {
            $inserted_id = $this->db->insert_id();

            if ($generate_product_id) {
                $generated_product_id = str_pad($inserted_id, 6, '0', STR_PAD_LEFT);
                $this->db->query("UPDATE product_information SET product_id = '{$generated_product_id}' WHERE id = '{$inserted_id}'");
            }

            if (!empty($entries) && is_array($entries)) {
                foreach ($entries as $item) {
                    $subunit_query = "INSERT INTO subunit_product (product_id, unit_id, subsell_price, subcost_price, first) VALUES ('{$inserted_id}', '{$item['subunitid']}', AES_ENCRYPT('{$item['subsell_price']}', '{$encryption_key}'), AES_ENCRYPT('{$item['subcost_price']}', '{$encryption_key}'), '{$item['selectedInt']}')";
                    $this->db->query($subunit_query);
                }
            }

            echo json_encode("Success");
        } else {
            $db_error = $this->db->error();
            $error_message = !empty($db_error['message']) ? $db_error['message'] : 'Database query failed';
            echo json_encode("Error: " . $error_message . " | Query: " . $query);
        }
    }




    public function update_product()
    {
        $encryption_key = Config::$encryption_key;
        $entries = $this->input->post('entries', TRUE);

        $supplier_id = $this->input->post('supplier_id', TRUE);
        $supplier_id = ($supplier_id === '' || $supplier_id === null) ? 0 : $supplier_id;

        $vat = $this->input->post('vat', TRUE);
        $vat = ($vat === '' || $vat === null) ? 0 : $vat;

        $defaultsaleprice = $this->input->post('defaultsaleprice', TRUE);
        $defaultsaleprice = ($defaultsaleprice === '' || $defaultsaleprice === null) ? 'fixedprice' : $defaultsaleprice;

        $batchtype = $this->input->post('batchtype', TRUE);
        $batchtype = ($batchtype === '' || $batchtype === null) ? 1 : $batchtype;

        $ad = $this->input->post('ad', TRUE);
        $ad = ($ad === null) ? '' : $ad;

        $bd = $this->input->post('bd', TRUE);
        $bd = ($bd === null) ? '' : $bd;

        $stock = $this->input->post('stock', TRUE);
        $stock = ($stock === '' || $stock === null) ? 0 : $stock;

        $max_stock_level = $this->input->post('max_stock_level', TRUE);
        $max_stock_level = is_numeric($max_stock_level) ? $max_stock_level : 0;

        $min_stock_level = $this->input->post('min_stock_level', TRUE);
        $min_stock_level = is_numeric($min_stock_level) ? $min_stock_level : 0;

        $reorder_stock_level = $this->input->post('reorder_stock_level', TRUE);
        $reorder_stock_level = is_numeric($reorder_stock_level) ? $reorder_stock_level : 0;

        $reserve_stock_level = $this->input->post('reserve_stock_level', TRUE);
        $reserve_stock_level = is_numeric($reserve_stock_level) ? $reserve_stock_level : 0;

        $query = "UPDATE product_information SET
            product_name = '{$this->input->post('product_name', TRUE)}',
            category_id = '{$this->input->post('category_id', TRUE)}',
            unit = '{$this->input->post('unit', TRUE)}',
            product_vat = '{$vat}',
            serial_no = '{$this->input->post('serial_no', TRUE)}',
            price = AES_ENCRYPT('{$this->input->post('sell_price', TRUE)}', '{$encryption_key}'),
            product_model = '{$this->input->post('product_model', TRUE)}',
            cost_price = AES_ENCRYPT('{$this->input->post('cost_price', TRUE)}', '{$encryption_key}'),
            product_details = '{$this->input->post('description', TRUE)}',
            store = '{$this->input->post('store', TRUE)}',
            status = '{$this->input->post('status', TRUE)}',
            subcategory_id = '{$this->input->post('subcategory_id', TRUE)}',
            brand_id = '{$this->input->post('brand_id', TRUE)}',
            oop_id = '{$this->input->post('oop_id', TRUE)}',
            defaultsaleprice = '{$defaultsaleprice}',
            ad = '{$ad}',
            bd = '{$bd}',
            batchtype = '{$batchtype}',
            printname = '{$this->input->post('printname', TRUE)}',
            supplier_id = '{$supplier_id}',
            product_type = '{$this->input->post('product_type', TRUE)}',
            stock = '{$stock}',
            max_stock_level = '{$max_stock_level}',
            min_stock_level = '{$min_stock_level}',
            reorder_stock_level = '{$reorder_stock_level}',
            reserve_stock_level = '{$reserve_stock_level}'
        WHERE id = '{$this->input->post('id', TRUE)}'";

        if ($this->db->query($query)) {
            if (!empty($entries) && is_array($entries)) {
                foreach ($entries as $item) {
                    if($item['id']==0){
                        $subunit_query = "INSERT INTO subunit_product (product_id, unit_id, subsell_price, subcost_price, first) VALUES ('{$this->input->post('id', TRUE)}', '{$item['subunitid']}', AES_ENCRYPT('{$item['subsell_price']}', '{$encryption_key}'), AES_ENCRYPT('{$item['subcost_price']}', '{$encryption_key}'), '{$item['selectedInt']}')";
                        $this->db->query($subunit_query);
                    }else{
                        $subunit_query = "UPDATE subunit_product SET subsell_price = AES_ENCRYPT('{$item['subsell_price']}', '{$encryption_key}'), subcost_price = AES_ENCRYPT('{$item['subcost_price']}', '{$encryption_key}'), first = '{$item['selectedInt']}', product_id = '{$this->input->post('id', TRUE)}', unit_id = '{$item['subunitid']}' WHERE id = '{$item['id']}'";
                        $this->db->query($subunit_query);
                    }
                }
            }

            echo json_encode("Success");
        } else {
            $db_error = $this->db->error();
            $error_message = !empty($db_error['message']) ? $db_error['message'] : 'Database query failed';
            echo json_encode("Error: " . $error_message . " | Query: " . $query);
        }
    }
    public function update_subunit()
    {
        $encryption_key = Config::$encryption_key;
        $query = "
        UPDATE subunit_product
        SET
            subsell_price = AES_ENCRYPT('{$this->input->post('subsell_price', TRUE)}', '{$encryption_key}'),
        subcost_price = AES_ENCRYPT('{$this->input->post('subcost_price', TRUE)}', '{$encryption_key}')
        WHERE
            id = '{$this->input->post('id', TRUE)}'
           
    ";
                    $this->db->query($query);
                    echo json_encode("Success");

    

    }

    public function delete_subunit()
    {
        $query = "DELETE FROM subunit_product WHERE id = '{$this->input->post('id', TRUE)}'";
        $this->db->query($query);
         echo json_encode("Success");
    }

    public function active_subunitsbyproductId()
    {

        $this->db->select('sp.unit_id, u.unit_name, p.unit, u2.unit_name as name2,p.stock,sp.first');
        $this->db->from('subunit_product sp');
        $this->db->join('units u', 'u.unit_id = sp.unit_id','left');
        $this->db->join('product_information p', 'p.id = sp.product_id','left');
        $this->db->join('units u2', 'u2.unit_id = p.unit','left');
        $this->db->where('p.id', $this->input->post('product_id', TRUE));
        $this->db->where('u.status', 1);
        
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            echo json_encode($query->result_array());
        }else{
            $this->db->select('p.unit, u2.unit_name as name2, 0 as unit_id, "" as unit_name, p.stock', FALSE);
            $this->db->from('product_information p');
            $this->db->join('units u2', 'u2.unit_id = p.unit','left');
            $this->db->where('p.id', $this->input->post('product_id', TRUE)); 
            $this->db->where('u2.status', 1);

            $query1 = $this->db->get();
            if ($query1->num_rows() > 0) {
                echo json_encode($query1->result_array());
            }
        }
    }


    function bdtask_productgroup_form($id = null)
    {
        $data['title']       = display('add_product_group');
        $data['products'] = $this->active_product();
        $data['store_list'] = $this->product_model->active_store();
        $data['module']      = "product";
        $data['page']        = "productgroup_form";

        if ($this->permission1->method('product_grouplist', 'create')->access()) {
            if ($id != null) {

                $data['title'] = "Edit Product Group";
                $data['id'] = $id;

            }else{
                $sql3 = "SELECT MAX(id)+1 AS highest_product_id FROM product_group;";
                $query3 = $this->db->query($sql3);
                $result3 = $query3->row();
                $data['product_group_id'] = !empty($result3->highest_product_id) ? str_pad($result3->highest_product_id, 6, '0', STR_PAD_LEFT) : "000001";
            }
            // echo modules::run('template/layout', $data);
            echo modules::run('template/layout', $data);
        } else {
            $previous_url = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : base_url();
            redirect($previous_url);
        }
    }

    public function active_product()
    {
        $this->db->select('id,product_name');
        $this->db->from('product_information');
        $this->db->where('status', 1);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }


    public function save_productgroup()
    {
        $items = $this->input->post('items', TRUE);

        $encryption_key = Config::$encryption_key;

        $check = $this->db->query("
    SELECT id 
    FROM product_group 
    WHERE groupcode = '" . $this->input->post('product_group_id', TRUE) . "' 
");
        if ($check->num_rows() > 0) {

            echo json_encode("already");
        } else {
            $query = "
    INSERT INTO product_group 
    (id,groupcode, name,status,invoice_group) 
    VALUES 
    (0,
     '{$this->input->post('product_group_id', TRUE)}',
     '{$this->input->post('product_group_name', TRUE)}',  
     '{$this->input->post('status', TRUE)}',
      '{$this->input->post('invoicegroup', TRUE)}'
    );";

            $this->db->query($query);


            $inserted_id = $this->db->insert_id();
            foreach ($items as $item) {


                $query = "
            INSERT INTO product_group_details 
            (id, pid, product,qty,unit,parent) 
            VALUES 
            (0, 
             '{$inserted_id}', 
             '{$item['product']}', 
             AES_ENCRYPT('{$item['quantity']}', '{$encryption_key}'), 
                 '{$item['unit']}',
                  '{$item['parent']}'
            );";

                $this->db->query($query);
            }



            echo json_encode("Success");
        }
    }

    public function bdtask_product_grouplist()
    {
        $data['title']         = display('product_grouplist');
        $data['total_product'] = $this->db->count_all("product_information");
        $data['module']        = "product";
        $data['page']          = "productgroup_list";
        if (!$this->permission1->method('product_grouplist', 'read')->access()) {
            $previous_url = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : base_url();
            redirect($previous_url);
        }
        echo modules::run('template/layout', $data);
    }

    public function getProductGroupById()
    {

        $encryption_key = Config::$encryption_key;

        $this->db->select("
         po.id, 
         pod.product,
         pod.unit,
         po.groupcode,
         po.name,
          AES_DECRYPT(pod.qty, '{$encryption_key}') AS qty,
         po.status,pod.parent,po.invoice_group
     ");
        $this->db->from('product_group po');
        $this->db->join('product_group_details pod', 'pod.pid = po.id', 'inner');
        $this->db->where('po.id', $this->input->post('id'));

        $query = $this->db->get();


        if ($query->num_rows() > 0) {
            echo json_encode($query->result_array());
        }
    }


    public function update_productgroup()
    {
        $encryption_key = Config::$encryption_key;

        $items = $this->input->post('items', TRUE);

        $check = $this->db->query("
        SELECT id 
        FROM product_group 
        WHERE groupcode = '" . $this->input->post('product_group_id', TRUE) . "' 
        AND id != '" . $this->input->post('id', TRUE) . "'
    ");
        if ($check->num_rows() > 0) {

            echo json_encode("already");
        } else {
            $query = "
    UPDATE product_group
    SET 
        groupcode =  '{$this->input->post('product_group_id', TRUE)}',
        name = '{$this->input->post('product_group_name', TRUE)}',
        status = '{$this->input->post('status', TRUE)}',
        invoice_group='{$this->input->post('invoicegroup', TRUE)}'
    WHERE id = '{$this->input->post('id', TRUE)}';";

            $this->db->query($query);

            $this->db->where('pid', $this->input->post('id', TRUE))
                ->delete('product_group_details');
            foreach ($items as $item) {
                $query = "
            INSERT INTO product_group_details 
            (id, pid, product,qty,unit,parent) 
            VALUES 
            (0, 
             '{$this->input->post('id', TRUE)}', 
             '{$item['product']}', 
             AES_ENCRYPT('{$item['quantity']}', '{$encryption_key}'), 
                 '{$item['unit']}',
                  '{$item['parent']}'
            );";

                $this->db->query($query);
            }
            echo json_encode("Success");
        }
    }


    public function CheckProductListForLabelPrint()
    {
        $postData = $this->input->post();
        $data = $this->product_model->getProductListForLabelPrint($postData, $this->input->post('category'));
        echo json_encode($data);
    }

    public function label_print()
    {
        $data = array(
            'title'           => display('labelprint'),
        );
        //$data['category_list'] = $this->product_model->active_category();

        $data['module']        = "product";
        $data['page']          = "label_print";
        echo modules::run('template/layout', $data);
    }

    public function insertLabelPrint()
    {
        $supp_prd = array(
            'id' => $this->input->post('id'),
            'no_of_labels'    => $this->input->post('noOfLabel'),
            'product_code' => $this->input->post('productId'),
            'product' => $this->input->post('productName'),
            'category' => $this->input->post('categoryName'),
            'price' =>  number_format((float)$this->input->post('price'), 2, '.', ''),
        );

        $this->db->insert('labelprint', $supp_prd);

        echo json_encode("Success");
    }



    public function deleteLabelPrint()
    {
        $this->db->where('id', $this->input->post('id'))
            ->delete("labelprint");

        if ($this->db->affected_rows()) {
            echo json_encode("Success");
        } else {
            echo json_encode("");
        }
    }

    public function deletewholeLabelPrint()
    {
        if ($this->db->query('TRUNCATE TABLE labelprint')) {
            echo json_encode("Success");
        } else {
            echo json_encode("unsuccess");
        }
    }

    public function getLabelPrintData()
    {
        $query = $this->db->select('id, no_of_labels as noOfLabel, product_code as productId, 
                                product as productName, category as categoryName, 
                               price as price')
            ->from('labelprint')
            ->get();

        if ($query->num_rows() > 0) {
            echo json_encode($query->result_array());
        } else {
            echo  json_encode([]);
        }
    }

    public function printsticker()
    {
        $_SESSION['barcodelabels'] =   $this->input->post('labels');
        $_SESSION['cqty'] =   1;
        $this->db->query('TRUNCATE TABLE barcode_sticker');
        foreach ($_SESSION['barcodelabels'] as $label) {
            $noOfLabel = (int)$label['noOfLabel'];
            for ($i = 0; $i < $noOfLabel; $i++) {
                $supp_prd = array(
                    'product_id'    => $label["productId"],
                    'product_name' => $label["productName"],
                    'price' => number_format((float)$label['price'], 2, '.', ''),
                );

                $this->db->insert('barcode_sticker', $supp_prd);
            }
        }
        echo json_encode($_SESSION['barcodelabels']);
    }

    public function barcode_print()
    {
        $data = array(
            'title'           => "Barcode Sticker",
        );
        $data['module']        = "product";
        $data['page']          = "barcode_print_page";
        echo modules::run('template/layout', $data);
    }

}