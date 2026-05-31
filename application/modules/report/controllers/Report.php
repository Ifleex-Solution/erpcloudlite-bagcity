<?php
defined('BASEPATH') or exit('No direct script access allowed');
#------------------------------------    
# Author: Bdtask Ltd
# Author link: https://www.bdtask.com/
# Dynamic style php file
# Developed by :Isahaq
#------------------------------------    
require_once("./vendor/Config.php");
require_once(__DIR__ . '/TCPDF-main/tcpdf.php');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;


class SalesReportInvoicewise extends TCPDF
{
    // Override the Header() method to remove the line
    public $pageTotal = 0;
    public $showPageTotal = true;

    public function Header()
    {
        $this->pageTotal = 0;
    }

    // Page footer
    public function Footer()
    {
        if (!$this->showPageTotal) {
            return;
        }
        // Position at 15 mm from bottom
        $this->SetY(-22);
        $this->SetFont('', 'B', 12);
        $this->Cell(50, 10, "Page Total:", 'TB', 0, 'L');
        $this->Cell(108, 10, "", 'TB', 0, 'L');
        $this->Cell(35, 10, number_format($this->pageTotal, 2), 'TB', 1, 'R');
        $this->Ln(10);
    }

    public function updatePageTotal($amount)
    {
        $this->pageTotal += $amount;
    }
}

class StockReport extends TCPDF
{
    // Override the Header() method to remove the line
    public $pageTotal = 0;

    public function Header()
    {
        $this->pageTotal = 0;
    }

    public function updatePageTotal($amount)
    {
        $this->pageTotal += $amount;
    }
}

class Report extends MX_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->model(array(
            'report_model',
            'service/service_model'

        ));
        if (! $this->session->userdata('isLogIn'))
            redirect('login');
    }

    /*product stock part*/
    function bdtask_livestock_report()
    {
        $data['title']         = display('live_stock_report');
        $data['product_list']  = $this->report_model->product_list_stock();
        $data['category_list'] = $this->report_model->category_list_product();
        $data['store_list']    = $this->report_model->active_store_stock();
        $data['product_group_list'] = $this->db
            ->select('id, name')
            ->from('product_group')
            ->where('status', 1)
            ->get()
            ->result_array();

        $data['module'] = "report";
        $data['page']   = "live_stock_report";
        echo modules::run('template/layout', $data);
    }

    function bdtask_stock_report()
    {
        $data['title']         = display('stock_report');
        $data['product_list']  = $this->report_model->product_list_stock();
        $data['category_list'] = $this->report_model->category_list_product();
        $data['store_list']    = $this->report_model->active_store_stock();
        $data['product_group_list'] = $this->db
            ->select('id, name')
            ->from('product_group')
            ->where('status', 1)
            ->get()
            ->result_array();

        $data['module'] = "report";
        $data['page']   = "stock_report";
        echo modules::run('template/layout', $data);
    }

    public function getProductsByGroup()
    {
        $group_id = (int)$this->input->post('group_id');
        $products = $this->db
            ->select('pi.id, pi.product_name')
            ->from('product_group_details pgd')
            ->join('product_information pi', 'pi.id = pgd.product', 'inner')
            ->where('pgd.pid', $group_id)
            ->where('pi.status', 1)
            ->get()
            ->result_array();
        echo json_encode($products);
    }

    public function bdtask_checkStocklist()
    {
        // GET data
        $postData = $this->input->post();
        $data = $this->report_model->bdtask_getStock($postData);
        echo json_encode($data);
    }


    public function bdtask_cash_closing()
    {
        $data['title']      = "Reports | Daily Closing";
        $data['info']       = $this->report_model->accounts_closing_data();
        $data['pay_methods'] = $this->report_model->payment_methods();
        $data['module']     = "report";
        $data['page']       = "closing_form";
        echo modules::run('template/layout', $data);
    }

    public function add_daily_closing()
    {

        $closedata = $this->db->select('*')->from('daily_closing')->where('date', date('Y-m-d'))->get()->num_rows();
        if ($closedata > 0) {
            $this->session->set_flashdata(array('exception' => 'Already Closed Today'));
            redirect(base_url('closing_form'));
        }
        $todays_date = date("Y-m-d");
        $data = array(
            'last_day_closing'  =>  str_replace(',', '', $this->input->post('last_day_closing', TRUE)),
            'cash_in'           =>  str_replace(',', '', $this->input->post('cash_in', TRUE)),
            'cash_out'          =>  str_replace(',', '', $this->input->post('cash_out', TRUE)),
            'date'              =>  $todays_date,
            'amount'            =>  str_replace(',', '', $this->input->post('cash_in_hand', TRUE)),
            'status'            =>      1
        );
        $invoice_id = $this->report_model->daily_closing_entry($data);


        $this->session->set_flashdata(array('message' => display('successfully_added')));
        redirect(base_url('closing_report'));
    }


    public function bdtask_closing_report()
    {
        $daily_closing_data = $this->report_model->get_closing_report();
        $i = 0;

        if (!empty($daily_closing_data)) {
            foreach ($daily_closing_data as $k => $v) {
                $daily_closing_data[$k]['final_date'] = $this->occational->dateConvert(date("Y-m-d", strtotime($daily_closing_data[$k]['datetime'])));
            }
        }
        $data = array(
            'title'              => display('closing_report'),
            'daily_closing_data' => $daily_closing_data,
        );
        $data['module']   = "report";
        $data['page']     = "closing_report";
        echo modules::run('template/layout', $data);
    }


    public function bdtask_closing_report_search()
    {
        $from_date = $this->input->get('from_date');
        $to_date = $this->input->get('to_date');
        $daily_closing_data = $this->report_model->get_date_wise_closing_report($from_date, $to_date);

        $i = 0;
        if (!empty($daily_closing_data)) {
            foreach ($daily_closing_data as $k => $v) {
                $daily_closing_data[$k]['final_date'] = $this->occational->dateConvert(date("Y-m-d", strtotime($daily_closing_data[$k]['datetime'])));
            }
            foreach ($daily_closing_data as $k => $v) {
                $i++;
                $daily_closing_data[$k]['sl'] = $i;
            }
        }

        $data = array(
            'title'              => display('closing_report'),
            'daily_closing_data' => $daily_closing_data,
            'from_date'          => $from_date,
            'to_date'            => $to_date,

        );

        $data['module']   = "report";
        $data['page']     = "closing_report";
        echo modules::run('template/layout', $data);
    }


    public function bdtask_todays_report()
    {
        $sales_report = $this->report_model->todays_sales_report();
        $sales_amount = 0;
        if (!empty($sales_report)) {
            $i = 0;
            foreach ($sales_report as $k => $v) {
                $i++;
                $sales_report[$k]['sl'] = $i;
                $sales_report[$k]['sales_date'] = $this->occational->dateConvert($sales_report[$k]['date']);
                $sales_amount = $sales_amount + $sales_report[$k]['total_amount'];
            }
        }

        $purchase_report = $this->report_model->todays_purchase_report();
        $purchase_amount = 0;
        if (!empty($purchase_report)) {
            $i = 0;
            foreach ($purchase_report as $k => $v) {
                $i++;
                $purchase_report[$k]['sl'] = $i;
                $purchase_report[$k]['prchse_date'] = $this->occational->dateConvert($purchase_report[$k]['purchase_date']);
                $purchase_amount = $purchase_amount + $purchase_report[$k]['grand_total_amount'];
            }
        }

        $data = array(
            'title'           => display('todays_report'),
            'sales_report'    => $sales_report,
            'sales_amount'    => number_format($sales_amount, 2, '.', ','),
            'purchase_amount' => number_format($purchase_amount, 2, '.', ','),
            'purchase_report' => $purchase_report,
            'date'            => $today = date('Y-m-d'),
        );

        $data['module']   = "report";
        $data['page']     = "todays_report";
        echo modules::run('template/layout', $data);
    }


    //    ============ its for todays_customer_receipt =============
    public function bdtask_todays_customer_received()
    {
        date_default_timezone_set('Asia/Colombo');

        $today = date('Y-m-d');
        $all_customer = $this->db->select('*')->from('customer_information')->get()->result();
        $todays_customer_receipt = $this->report_model->todays_customer_receipt($today);
        $data = array(
            'title'                   => "Received From Customer",
            'all_customer'            => $all_customer,
            'todays_customer_receipt' => $todays_customer_receipt,
            'today'                   => $today,
            'customer_id'             => '',
        );
        $data['module']   = "report";
        $data['page']     = "todays_customer_receipt";
        echo modules::run('template/layout', $data);
    }


    //    ============ its for todays_customer_receipt =============
    public function bdtask_customerwise_received()
    {
        date_default_timezone_set('Asia/Colombo');

        $customer_id = $this->input->post('customer_id', TRUE);
        $from_date   = $this->input->post('from_date', TRUE);
        $today       = date('Y-m-d');
        $all_customer = $this->db->select('*')->from('customer_information')->get()->result();
        $filter_customer_wise_receipt = $this->report_model->filter_customer_wise_receipt($customer_id, $from_date);
        $data = array(
            'title'                   => "Received From Customer",
            'all_customer'            => $all_customer,
            'todays_customer_receipt' => $filter_customer_wise_receipt,
            'today'                   => $from_date,
            'customer_info'           => $this->report_model->customerinfo_rpt($customer_id),
            'customer_id'            => $customer_id,
        );

        $data['module']   = "report";
        $data['page']     = "todays_customer_receipt";
        echo modules::run('template/layout', $data);
    }

    public function bdtask_todays_sales_report()
    {
        // $sales_report = $this->report_model->todays_sales_report();
        $sales_amount = 0;
        if (!$this->permission1->method('todays_sales_report', 'read')->access()) {
            $previous_url = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : base_url();
            redirect($previous_url);
        }
        $data = array(
            'title'        => display('sales_report'),
            'sales_amount' => number_format($sales_amount, 2, '.', ','),
        );
        $data['module']   = "report";
        $data['page']     = "sales_report";
        echo modules::run('template/layout', $data);
    }

    public function bdtask_datewise_sales_report()
    {
        $from_date = $this->input->get('from_date');
        $to_date  = $this->input->get('to_date');
        $sales_report = $this->report_model->retrieve_dateWise_SalesReports($from_date, $to_date);
        $sales_amount = 0;
        if (!empty($sales_report)) {
            $i = 0;
            foreach ($sales_report as $k => $v) {
                $i++;
                $sales_report[$k]['sl'] = $i;
                $sales_report[$k]['sales_date'] = $this->occational->dateConvert($sales_report[$k]['date']);
                $sales_amount = $sales_amount + $sales_report[$k]['total_amount'];
            }
        }
        $data = array(
            'title'        => display('sales_report'),
            'sales_amount' => $sales_amount,
            'sales_report' => $sales_report,
            'from_date'    => $from_date,
            'to_date'      => $to_date,
        );
        $data['module']   = "report";
        $data['page']     = "sales_report";
        echo modules::run('template/layout', $data);
    }

    public function bdtask_userwise_sales_report()
    {
        $user_id = (!empty($this->input->get('user_id')) ? $this->input->get('user_id') : '');
        $star_date = (!empty($this->input->get('from_date')) ? $this->input->get('from_date') : date('Y-m-d'));
        $end_date = (!empty($this->input->get('to_date')) ? $this->input->get('to_date') : date('Y-m-d'));
        if (!$this->permission1->method('user_wise_sales_report', 'create')->access()) {
            $previous_url = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : base_url();
            redirect($previous_url);
        }

        // $sales_report = $this->report_model->user_sales_report($star_date, $end_date, $user_id);
        // $sales_amount = 0;
        // if (!empty($sales_report)) {
        //     $i = 0;
        //     foreach ($sales_report as $k => $v) {
        //         $i++;
        //         $sales_report[$k]['sl'] = $i;

        //         $sales_amount += $sales_report[$k]['amount'];
        //     }
        // }
        $user_list = $this->report_model->userList();
        $data = array(
            'title'         => display('user_wise_sales_report'),
            // 'sales_amount'  => number_format($sales_amount, 2, '.', ','),
            // 'sales_report'  => $sales_report,
            'from'          => $this->occational->dateConvert($star_date),
            'to'            => $this->occational->dateConvert($end_date),
            'user_list'     => $user_list,
            'user_id'       => $user_id,
        );
        $data['module']   = "report";
        $data['page']     = "user_wise_sales_report";
        echo modules::run('template/layout', $data);
    }


    public function bdtask_invoice_wise_due_report()
    {
        $from_date = (!empty($this->input->get('from_date')) ? $this->input->get('from_date') : date('Y-m-d'));
        $to_date = (!empty($this->input->get('to_date')) ? $this->input->get('to_date') : date('Y-m-d'));

        $data = array(
            'title'        => display('due_report'),
            'from_date'    => $from_date,
            'to_date'      => $to_date,

        );

        $data['module']   = "report";
        $data['page']     = "due_report";
        echo modules::run('template/layout', $data);
    }


    public function bdtask_shippingcost_report()
    {
        $from_date = (!empty($this->input->get('from_date')) ? $this->input->get('from_date') : date('Y-m-d'));
        $to_date = (!empty($this->input->get('to_date')) ? $this->input->get('to_date') : date('Y-m-d'));
        $sales_report = $this->report_model->retrieve_dateWise_Shippingcost($from_date, $to_date);
        $sales_amount = 0;
        if (!empty($sales_report)) {
            $i = 0;
            foreach ($sales_report as $k => $v) {
                $i++;
                $sales_report[$k]['sl'] = $i;
                $sales_report[$k]['sales_date'] = $this->occational->dateConvert($sales_report[$k]['date']);
                $sales_amount = $sales_amount + $sales_report[$k]['total_amount'];
            }
        }
        $data = array(
            'title'        => display('shipping_cost_report'),
            'sales_amount' => $sales_amount,
            'sales_report' => $sales_report,
            'from_date'    => $from_date,
            'to_date'      => $to_date,
        );
        $data['module']   = "report";
        $data['page']     = "shippincost_report";
        echo modules::run('template/layout', $data);
    }

    public function bdtask_purchase_report()
    {
        $from_date = (!empty($this->input->get('from_date')) ? $this->input->get('from_date') : date('Y-m-d'));
        $to_date = (!empty($this->input->get('to_date')) ? $this->input->get('to_date') : date('Y-m-d'));

        if (!$this->permission1->method('todays_purchase_report', 'read')->access()) {
            $previous_url = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : base_url();
            redirect($previous_url);
        }
        $data['title']   = display('purchase_report');
        $data['from']   = $from_date;
        $data['to']   = $to_date;
        $data['module']   = "report";
        $data['page']     = "purchase_report";
        echo modules::run('template/layout', $data);
    }

    public function bdtask_purchase_report_category_wise()
    {
        $from_date = (!empty($this->input->get('from_date')) ? $this->input->get('from_date') : date('Y-m-d'));
        $to_date   = (!empty($this->input->get('to_date')) ? $this->input->get('to_date') : date('Y-m-d'));
        $category  = (!empty($this->input->get('category')) ? $this->input->get('category') : '');
        $category_list = $this->report_model->category_list_product();
        $product_list = $this->report_model->product_list();

        // $purchase_report_category_wise = $this->report_model->purchase_report_category_wise($from_date, $to_date, $category);
        $data = array(
            'title'         => "Purchase Report (Category Wise)",
            'category_list' => $category_list,
            'from'          => $from_date,
            'to'            => $to_date,
            'category_id'   => $category,
            'product_list'   => $product_list,

            // 'purchase_report_category_wise' => $purchase_report_category_wise,
        );
        if (!$this->permission1->method('purchase_report_category_wise', 'read')->access()) {
            $previous_url = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : base_url();
            redirect($previous_url);
        }
        $data['module']   = "report";
        $data['page']     = "purchase_report_category_wise";
        echo modules::run('template/layout', $data);
    }


    public function bdtask_sale_report_productwise()
    {
        // $from_date      = (!empty($this->input->get('from_date')) ? $this->input->get('from_date') : date('Y-m-d'));
        // $to_date        = (!empty($this->input->get('to_date')) ? $this->input->get('to_date') : date('Y-m-d'));
        // $product_id     = (!empty($this->input->get('product_id')) ? $this->input->get('product_id') : '');

        // $product_report = $this->report_model->retrieve_product_sales_report($from_date, $to_date, $product_id);
        if (!$this->permission1->method('product_wise_sales_report', 'read')->access()) {
            $previous_url = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : base_url();
            redirect($previous_url);
        }
        $product_list = $this->report_model->product_list();
        // if (!empty($product_report)) {
        //     $i = 0;
        //     foreach ($product_report as $k => $v) {
        //         $i++;
        //         $product_report[$k]['sl'] = $i;
        //     }
        // }
        // $sub_total = 0;
        // if (!empty($product_report)) {
        //     foreach ($product_report as $k => $v) {
        //         $product_report[$k]['sales_date'] = $this->occational->dateConvert($product_report[$k]['date']);
        //         $sub_total = $sub_total + $product_report[$k]['total_amount'];
        //     }
        // }
        $data = array(
            'title'          => display('sales_report_product_wise'),
            // 'sub_total'      => number_format($sub_total, 2, '.', ','),
            // 'product_report' => $product_report,
            'product_list'   => $product_list,
            // 'product_id'     => $product_id,
            // 'from'           => $from_date,
            // 'to'             => $to_date,
        );
        $data['module']   = "report";
        $data['page']     = "product_report";
        echo modules::run('template/layout', $data);
    }


    public function bdtask_categorywise_sales_report()
    {
        $from_date = (!empty($this->input->get('from_date')) ? $this->input->get('from_date') : date('Y-m-d'));
        $to_date = (!empty($this->input->get('to_date')) ? $this->input->get('to_date') : date('Y-m-d'));
        $category = (!empty($this->input->get('category')) ? $this->input->get('category') : '');
        $category_list = $this->report_model->category_list_product();
        $product_list = $this->report_model->product_list();
        if (!$this->permission1->method('sales_report_category_wise', 'read')->access()) {
            $previous_url = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : base_url();
            redirect($previous_url);
        }
        // $sales_report_category_wise = $this->report_model->sales_report_category_wise($from_date, $to_date, $category);
        $data = array(
            'title'                      => display('sales_report_category_wise'),
            'category_list'              => $category_list,
            'product_list'   => $product_list,
            // 'sales_report_category_wise' => $sales_report_category_wise,
            'from'                       => $from_date,
            'to'                         => $to_date,
            'category_id'                => $category,
        );
        $data['module']   = "report";
        $data['page']     = "sales_report_category_wise";
        echo modules::run('template/layout', $data);
    }



    public function bdtask_sales_return()
    {
        $from_date = $this->input->post('from_date', TRUE);
        $to_date   = $this->input->post('to_date', TRUE);
        $start     = (!empty($from_date) ? $from_date : date('Y-m-d'));
        $end       = (!empty($to_date) ? $to_date : date('Y-m-d'));
        $return_list = $this->report_model->sales_return_list($start, $end);
        if (!empty($return_list)) {
            foreach ($return_list as $k => $v) {
                $return_list[$k]['final_date'] = $this->occational->dateConvert($return_list[$k]['date_return']);
            }
        }

        $data = array(
            'title'      => display('invoice_return'),
            'return_list' => $return_list,
            'from_date'  => $start,
            'to_date'    => $end,
        );

        $data['module']   = "report";
        $data['page']     = "sales_return";
        echo modules::run('template/layout', $data);
    }


    public function bdtask_supplier_return()
    {
        $from_date = $this->input->post('from_date', TRUE);
        $to_date   = $this->input->post('to_date', TRUE);
        $start     = (!empty($from_date) ? $from_date : date('Y-m-d'));
        $end       = (!empty($to_date) ? $to_date : date('Y-m-d'));
        $return_list = $this->report_model->supplier_return($start, $end);
        if (!empty($return_list)) {
            foreach ($return_list as $k => $v) {
                $return_list[$k]['final_date'] = $this->occational->dateConvert($return_list[$k]['date_return']);
            }
        }

        $data = array(
            'title'       => display('supplier_return'),
            'return_list' => $return_list,
            'start_date'  => $start,
            'end_date'    => $end,
        );

        $data['module']   = "report";
        $data['page']     = "supplier_return";
        echo modules::run('template/layout', $data);
    }

    public function bdtask_tax_report()
    {
        $from_date = (!empty($this->input->get('from_date')) ? $this->input->get('from_date') : date('Y-m-d'));
        $to_date = (!empty($this->input->get('to_date')) ? $this->input->get('to_date') : date('Y-m-d'));
        $sales_report = $this->report_model->retrieve_dateWise_tax($from_date, $to_date);
        $sales_amount = 0;
        if (!empty($sales_report)) {
            $i = 0;
            foreach ($sales_report as $k => $v) {

                $sales_report[$k]['sl']         = $i;
                $sales_report[$k]['sales_date'] = $this->occational->dateConvert($sales_report[$k]['date']);
                $sales_amount = $sales_amount + $sales_report[$k]['total_amount'];
                $i++;
            }
        }
        $data = array(
            'title'        => display('tax_report'),
            'sales_amount' => $sales_amount,
            'sales_report' => $sales_report,
            'from_date'    => $from_date,
            'to_date'      => $to_date,
        );

        $data['module']   = "report";
        $data['page']     = "tax_report";
        echo modules::run('template/layout', $data);
    }


    public function bdtask_profit_report()
    {
        $start_date = (!empty($this->input->get('from_date')) ? $this->input->get('from_date') : date('Y-m-d'));
        $end_date   = (!empty($this->input->get('to_date')) ? $this->input->get('to_date') : date('Y-m-d'));
        $total_profit_report = $this->report_model->total_profit_report($start_date, $end_date);
        $profit_ammount   = 0;
        $SubTotalSupAmnt  = 0;
        $SubTotalSaleAmnt = 0;
        if (!empty($total_profit_report)) {
            $i = 0;
            foreach ($total_profit_report as $k => $v) {
                $total_profit_report[$k]['sl'] = $i;
                $total_profit_report[$k]['prchse_date'] = $this->occational->dateConvert($total_profit_report[$k]['date']);
                $profit_ammount = $profit_ammount + $total_profit_report[$k]['total_profit'];
                $SubTotalSupAmnt = $SubTotalSupAmnt + $total_profit_report[$k]['total_supplier_rate'];
                $SubTotalSaleAmnt = $SubTotalSaleAmnt + $total_profit_report[$k]['total_sale'];
            }
        }

        $data = array(
            'title'               => display('profit_report'),
            'profit_ammount'      => number_format($profit_ammount, 2, '.', ','),
            'total_profit_report' => $total_profit_report,
            'from'                => $start_date,
            'to'                  => $end_date,
            'SubTotalSupAmnt'     => number_format($SubTotalSupAmnt, 2, '.', ','),
            'SubTotalSaleAmnt'    => number_format($SubTotalSaleAmnt, 2, '.', ','),
        );
        $data['module']   = "report";
        $data['page']     = "profit_report";
        echo modules::run('template/layout', $data);
    }


    public function bdtask_add_closing()
    {

        $this->form_validation->set_rules('opening_bal', display('opening_balance'), 'max_length[100]|required');
        if ($this->form_validation->run()) {
            $createby    = $this->session->userdata('id');
            $check_exist = $this->db->select('')->from('closing_records')->where('user_id', $createby)->where('DATE(datetime)', date('Y-m-d'))->where('head_code', $this->input->post('head_code', true))->get()->num_rows();
            if ($check_exist > 0) {
                $data['status'] = 0;
                $data['message'] = 'Already Closed Today';
                echo json_encode($data);
                exit;
            }
            $createdate = date('Y-m-d H:i:s');
            $postData = array(
                'head_code'       => $this->input->post('head_code', true),
                'opening_balance' => $this->input->post('opening_bal', true),
                'amount_in'       => $this->input->post('total_received', true),
                'amount_out'      => $this->input->post('total_paid', true),
                'closign_balance' => $this->input->post('closing', true),
                'user_id'         => $createby,
                'status'          => 1
            );
            if ($this->report_model->create_opening($postData)) {
                $data['status'] = 1;
                $data['message'] = display('successfully_saved');
            } else {
                $data['status'] = 0;
                $data['message'] = display('please_try_again');
            }
        } else {
            $data['status'] = 0;
            $data['message'] = validation_errors();
        }
        echo json_encode($data);
        exit;
    }

    public function CheckReportList()
    {
        // echo "bb";
        // exit;
        $postData = $this->input->post();
        $data = $this->report_model->getReportList($postData);
        // dd($data);
        // exit;
        echo json_encode($data);
    }
    public function getSalesReportList()
    {
        // echo "bb";
        // exit;
        $postData = $this->input->post();
        $data = $this->report_model->getSalesReportList($postData);
        // dd($data);
        // exit;
        echo json_encode($data);
    }
    public function get_retrieve_dateWise_DueReports()
    {
        // echo "bb";
        // exit;
        $postData = $this->input->post();
        $data = $this->report_model->get_retrieve_dateWise_DueReports($postData);
        // dd($data);
        // exit;
        echo json_encode($data);
    }

    public function sales_reportinvoicewise()
    {
        $from_date = $this->input->post('from_date');
        $to_date  = $this->input->post('to_date');
        $empid = $this->input->post('empid');
        $branch = $this->input->post('branch');
        $customer_id = $this->input->post('customer_id');

        $report_data = $this->report_model->sales_reportinvoicewise($from_date, $to_date, $empid, $branch, $customer_id);
        $_SESSION['sale_reportsri'] =  $report_data;
        $_SESSION['sri_istype'] =   $this->input->post('istype');
        $_SESSION['srifrom_date'] = $from_date;
        $_SESSION['srito_date'] =  $to_date;


        echo json_encode($_SESSION['sale_reportsri']);
    }

    public function purchase_reportinvoicewise()
    {
        $from_date = $this->input->post('from_date');
        $to_date  = $this->input->post('to_date');
        $empid = $this->input->post('empid');
        $branch = $this->input->post('branch');
        $supplier_id = $this->input->post('supplier_id');

        $report_data = $this->report_model->bdtask_purchase_report($from_date, $to_date, $empid, $branch, $supplier_id);
        $_SESSION['purchase_reportpri'] =  $report_data;
        $_SESSION['pri_istype'] =   $this->input->post('istype');
        $_SESSION['prifrom_date'] = $from_date;
        $_SESSION['prito_date'] =  $to_date;


        echo json_encode($_SESSION['purchase_reportpri']);
    }

    public function sales_reportproductwise()
    {
        $from_date = $this->input->post('from_date');
        $to_date  = $this->input->post('to_date');
        $empid = $this->input->post('empid');
        $productid = $this->input->post('productid');
        $branch = $this->input->post('branch');

        $report_data = $this->report_model->retrieve_product_sales_report($from_date, $to_date, $productid, $empid, $branch);
        $_SESSION['sale_reportsrp'] =  $report_data;
        $_SESSION['srp_istype'] =   $this->input->post('istype');
        $_SESSION['srpfrom_date'] = $from_date;
        $_SESSION['srpto_date'] =  $to_date;
        echo json_encode($_SESSION['sale_reportsrp']);
    }



    public function sales_reportcategorywise()
    {
        $from_date = $this->input->post('from_date');
        $to_date  = $this->input->post('to_date');
        $empid = $this->input->post('empid');
        $category = $this->input->post('category');
        $product = $this->input->post('product');
        $branch = $this->input->post('branch');

        $report_data = $this->report_model->sales_report_category_wise($from_date, $to_date, $category, $product, $empid, $branch);
        $_SESSION['sale_reportsrc'] =  $report_data;
        $_SESSION['src_istype'] =   $this->input->post('istype');
        $_SESSION['srcfrom_date'] = $from_date;
        $_SESSION['srcto_date'] =  $to_date;
        echo json_encode($_SESSION['sale_reportsrc']);
    }

    public function sales_reportemployeewise()
    {
        $from_date = $this->input->post('from_date');
        $to_date  = $this->input->post('to_date');
        $empid = $this->input->post('empid');
        $employee = $this->input->post('employee');
        $branch = $this->input->post('branch');

        $report_data = $this->report_model->user_sales_report($from_date, $to_date, $employee, $empid, $branch);
        $_SESSION['sale_reportsre'] =  $report_data;
        $_SESSION['sre_istype'] =   $this->input->post('istype');
        $_SESSION['srefrom_date'] = $from_date;
        $_SESSION['sreto_date'] =  $to_date;


        echo json_encode($_SESSION['sale_reportsre']);
    }


    public function purchase_reportcategorywise()
    {
        $from_date = $this->input->post('from_date');
        $to_date  = $this->input->post('to_date');
        $empid = $this->input->post('empid');
        $category = $this->input->post('category');
        $product = $this->input->post('product');
        $branch = $this->input->post('branch');


        $report_data = $this->report_model->purchase_report_category_wise($from_date, $to_date, $category, $product, $empid, $branch);
        $_SESSION['purchase_reportprc'] =  $report_data;
        $_SESSION['prc_istype'] =   $this->input->post('istype');
        $_SESSION['prcfrom_date'] = $from_date;
        $_SESSION['prcto_date'] =  $to_date;


        echo json_encode($_SESSION['purchase_reportprc']);
    }


    public function generate_salesreportinvoice()
    {
        $page = 1;
        $pdf = new SalesReportInvoicewise('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Your Name');
        $pdf->SetTitle('Sales Report(Invoice Wise)');
        $pdf->SetSubject('TCPDF Tutorial');
        $pdf->SetKeywords('TCPDF, PDF, columns, example');
        $top_margin = 5;
        $pdf->SetMargins(15, $top_margin, 10);
        $pdf->SetAutoPageBreak(TRUE, 20);
        $pdf->AddPage();
        $pdf->SetFont('helvetica', '', 10);

        $this->header($pdf, $page, "Sales Report (Invoice Wise)", $_SESSION['sri_istype'], $_SESSION['srifrom_date'], $_SESSION['srito_date']);


        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->Cell(45, 10, 'Sale Date', 'TB', 0, 'L', 0, '', 1);
        $pdf->Cell(33, 10, 'Invoice No', 'TB', 0, 'L', 0, '', 1);
        $pdf->Cell(40, 10, 'Incident Type', 'TB', 0, 'L', 0, '', 1);
        $pdf->Cell(35, 10, 'Customer Name', 'TB', 0, 'L', 0, '', 1);
        $pdf->Cell(40, 10, 'Amount', 'TB', 0, 'R', 0, '', 1);
        $pdf->Ln(10);

        $data = isset($_SESSION['sale_reportsri']) ? $_SESSION['sale_reportsri'] : [];
        $lineHeight = 10;
        $maxY = 270;

        $patotal = 0;
        $total = 0;
        foreach ($data as $row) {

            if ($pdf->GetY() + $lineHeight > $maxY) {
                $pdf->updatePageTotal($patotal);
                $patotal = 0;
                $pdf->AddPage();
                $page = $page + 1;
                $this->header($pdf, $page, "Sales Report (Invoice Wise)", $_SESSION['sri_istype'], $_SESSION['srifrom_date'], $_SESSION['srito_date']);
                $pdf->SetFont('helvetica', 'B', 12);
                $pdf->Cell(45, 10, 'Sale Date', 'TB', 0, 'L', 0, '', 1);
                $pdf->Cell(33, 10, 'Invoice No', 'TB', 0, 'L', 0, '', 1);
                $pdf->Cell(40, 10, 'Incident Type', 'TB', 0, 'L', 0, '', 1);
                $pdf->Cell(35, 10, 'Customer Name', 'TB', 0, 'L', 0, '', 1);
                $pdf->Cell(40, 10, 'Amount', 'TB', 0, 'R', 0, '', 1);
                $pdf->Ln(10);
            }
            $total = $total + $row['total'];
            $patotal = $patotal + $row['total'];

            $pdf->SetFont('', '', 10);
            $pdf->Cell(45, 8, $row['date'], 0, 0, 'L');
            $pdf->Cell(33, 8,  $row['invoiceno'], 0, 0, 'L');
            $pdf->Cell(40, 8,  $row['incidenttype'], 0, 0, 'L');
            $pdf->Cell(35, 8,  $row['customer_name'], 0, 0, 'L');
            $pdf->Cell(40, 8, number_format($row['total'], 2), 0, 1, 'R');
        }
        $pdf->SetFont('', 'B', 12);
        $pdf->Cell(50, 10, "Total Amount:", 'TB', 0, 'L');
        $pdf->Cell(100, 10, "", 'TB', 0, 'L');
        $pdf->Cell(45, 10, number_format($total, 2), 'TB', 1, 'R');
        $pdf->updatePageTotal($patotal);

        $date = date('Y-m-d');
        $filename = "Sales Report(Invoice Wise)_$date.pdf";
        $pdf->Output($filename, 'I');
    }

    public function generate_purchasereportinvoice()
    {
        $page = 1;
        $pdf = new SalesReportInvoicewise('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Your Name');
        $pdf->SetTitle('Purchase Report(Invoice Wise)');
        $pdf->SetSubject('TCPDF Tutorial');
        $pdf->SetKeywords('TCPDF, PDF, columns, example');
        $top_margin = 5;
        $pdf->SetMargins(15, $top_margin, 10);
        $pdf->SetAutoPageBreak(TRUE, 20);
        $pdf->AddPage();
        $pdf->SetFont('helvetica', '', 10);

        $this->header($pdf, $page, "Purchase Report (Invoice Wise)", $_SESSION['pri_istype'], $_SESSION['prifrom_date'], $_SESSION['prito_date']);


        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->Cell(45, 10, 'Purchase Date', 'TB', 0, 'L', 0, '', 1);
        $pdf->Cell(33, 10, 'Invoice No', 'TB', 0, 'L', 0, '', 1);
        $pdf->Cell(40, 10, 'Incident Type', 'TB', 0, 'L', 0, '', 1);
        $pdf->Cell(35, 10, 'Supplier Name', 'TB', 0, 'L', 0, '', 1);
        $pdf->Cell(40, 10, 'Amount', 'TB', 0, 'R', 0, '', 1);
        $pdf->Ln(10);

        $data = isset($_SESSION['purchase_reportpri']) ? $_SESSION['purchase_reportpri'] : [];
        $lineHeight = 10;
        $maxY = 270;

        $patotal = 0;
        $total = 0;

        foreach ($data as $row) {

            if ($pdf->GetY() + $lineHeight > $maxY) {
                $pdf->updatePageTotal($patotal);
                $patotal = 0;
                $pdf->AddPage();
                $page = $page + 1;
                $this->header($pdf, $page, "Sales Report (Invoice Wise)", $_SESSION['pri_istype'], $_SESSION['prifrom_date'], $_SESSION['prito_date']);
                $pdf->SetFont('helvetica', 'B', 12);
                $pdf->Cell(45, 10, 'Purchase Date', 'TB', 0, 'L', 0, '', 1);
                $pdf->Cell(33, 10, 'Invoice No', 'TB', 0, 'L', 0, '', 1);
                $pdf->Cell(40, 10, 'Incident Type', 'TB', 0, 'L', 0, '', 1);
                $pdf->Cell(35, 10, 'Supplier Name', 'TB', 0, 'L', 0, '', 1);
                $pdf->Cell(40, 10, 'Amount', 'TB', 0, 'R', 0, '', 1);
                $pdf->Ln(10);
            }
            $patotal = $patotal + $row['total'];
            $total = $total + $row['total'];

            $pdf->SetFont('', '', 10);
            $pdf->Cell(45, 8, $row['date'], 0, 0, 'L');
            $pdf->Cell(33, 8,  $row['invoiceno'], 0, 0, 'L');
            $pdf->Cell(40, 8,  $row['incidenttype'], 0, 0, 'L');
            $pdf->Cell(35, 8,  $row['supplier_name'], 0, 0, 'L');
            $pdf->Cell(40, 8, number_format($row['total'], 2), 0, 1, 'R');
        }
        $pdf->SetFont('', 'B', 12);
        $pdf->Cell(50, 10, "Total Amount:", 'TB', 0, 'L');
        $pdf->Cell(100, 10, "", 'TB', 0, 'L');
        $pdf->Cell(45, 10, number_format($total, 2), 'TB', 1, 'R');
        $pdf->updatePageTotal($patotal);




        $date = date('Y-m-d');
        $filename = "Purchase Report (Invoice Wise)_$date.pdf";
        $pdf->Output($filename, 'I');
    }

    public function generate_salesreportproduct()
    {
        $page = 1;
        $pdf = new SalesReportInvoicewise('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Your Name');
        $pdf->SetTitle('Sales Report(Product Wise)');
        $pdf->SetSubject('TCPDF Tutorial');
        $pdf->SetKeywords('TCPDF, PDF, columns, example');
        $top_margin = 5;
        $pdf->SetMargins(15, $top_margin, 10);
        $pdf->SetAutoPageBreak(TRUE, 20);
        $pdf->AddPage();
        $pdf->SetFont('helvetica', '', 10);

        $this->header($pdf, $page, "Sales Report (Product Wise)", $_SESSION['srp_istype'], $_SESSION['srpfrom_date'], $_SESSION['srpto_date']);


        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->Cell(24, 10, 'Sale Date', 'TB', 0, 'L', 0, '', 1);
        $pdf->Cell(36, 10, 'Product Name', 'TB', 0, 'L', 0, '', 1);
        $pdf->Cell(23, 10, 'Invoice No', 'TB', 0, 'L', 0, '', 1);
        $pdf->Cell(22, 10, 'Invoice Type', 'TB', 0, 'L', 0, '', 1);
        $pdf->Cell(15, 10, 'Customer', 'TB', 0, 'L', 0, '', 1);
        $pdf->Cell(27, 10, 'Rate', 'TB', 0, 'R', 0, '', 1);
        $pdf->Cell(15, 10, 'Qty', 'TB', 0, 'R', 0, '', 1);
        $pdf->Cell(27, 10, 'Total', 'TB', 0, 'R', 0, '', 1);


        $pdf->Ln(10);

        $data = isset($_SESSION['sale_reportsrp']) ? $_SESSION['sale_reportsrp'] : [];
        $lineHeight = 10;
        $maxY = 270;



        $patotal = 0;
        $total = 0;

        foreach ($data as $row) {

            if ($pdf->GetY() + $lineHeight > $maxY) {
                $pdf->updatePageTotal($patotal);
                $patotal = 0;
                $pdf->AddPage();
                $page = $page + 1;
                $this->header($pdf, $page, "Sales Report (Product Wise)", $_SESSION['srp_istype'], $_SESSION['srpfrom_date'], $_SESSION['srpto_date']);
                $pdf->SetFont('helvetica', 'B', 12);
                $pdf->Cell(24, 10, 'Sale Date', 'TB', 0, 'L', 0, '', 1);
                $pdf->Cell(36, 10, 'Product Name', 'TB', 0, 'L', 0, '', 1);
                $pdf->Cell(23, 10, 'Invoice No', 'TB', 0, 'L', 0, '', 1);
                $pdf->Cell(22, 10, 'Invoice Type', 'TB', 0, 'L', 0, '', 1);
                $pdf->Cell(15, 10, 'Customer', 'TB', 0, 'L', 0, '', 1);
                $pdf->Cell(27, 10, 'Rate', 'TB', 0, 'R', 0, '', 1);
                $pdf->Cell(15, 10, 'Qty', 'TB', 0, 'R', 0, '', 1);
                $pdf->Cell(27, 10, 'Total', 'TB', 0, 'R', 0, '', 1);

                $pdf->Ln(10);
            }
            $patotal = $patotal + $row['total'];
            $total = $total + $row['total'];

            $pdf->SetFont('', '', 10);
            $pdf->MultiCell(24, 8, $row['date'],'', 'L', 0, 0);
            $pdf->MultiCell(36, 8,  $row['product_name'], '', 'L', 0, 0);
            $pdf->MultiCell(23, 8,  $row['sale_id'], '', 'L', 0, 0);
            $pdf->MultiCell(22, 8,  $row['incidenttype'], '', 'L', 0, 0);
            $pdf->MultiCell(15, 8,  $row['customer_name'], '', 'L', 0, 0);
            $pdf->MultiCell(27, 8, number_format($row['product_rate'], 2), '', 'R', 0, 0);
            $pdf->MultiCell(15, 8,  $row['quantity']." ". $row['unit_name'], '', 'R', 0, 0);
            $pdf->MultiCell(27, 8, number_format($row['total'], 2), '', 'R', 0, 0);
            $pdf->Ln(10);



            // $pdf->Cell(40, 8, number_format($row['total'], 2), 0, 1, 'R');
        }
        $pdf->SetFont('', 'B', 12);
        $pdf->Cell(50, 10, "Total Amount:", 'TB', 0, 'L');
        $pdf->Cell(108, 10, "", 'TB', 0, 'L');
        $pdf->Cell(35, 10, number_format($total, 2), 'TB', 1, 'R');

        $pdf->updatePageTotal($patotal);

        $date = date('Y-m-d');
        $filename = "Sales Report (Product Wise)_$date.pdf";
        $pdf->Output($filename, 'I');
    }

    public function generate_salesreportcategory()
    {
        $page = 1;
        $pdf = new SalesReportInvoicewise('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Your Name');
        $pdf->SetTitle('Sales Report(Category Wise)');
        $pdf->SetSubject('TCPDF Tutorial');
        $pdf->SetKeywords('TCPDF, PDF, columns, example');
        $top_margin = 5;
        $pdf->SetMargins(15, $top_margin, 10);
        $pdf->SetAutoPageBreak(TRUE, 20);
        $pdf->AddPage();
        $pdf->SetFont('helvetica', '', 10);

        $this->header($pdf, $page, "Sales Report (Category Wise)", $_SESSION['src_istype'], $_SESSION['srcfrom_date'], $_SESSION['srcto_date']);


        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->Cell(75, 10, 'Category Name', 'TB', 0, 'L', 0, '', 1);
        $pdf->Cell(60, 10, 'Product Name', 'TB', 0, 'L', 0, '', 1);
        $pdf->Cell(20, 10, 'Qty', 'TB', 0, 'C', 0, '', 1);
        $pdf->Cell(30, 10, 'Amount', 'TB', 0, 'R', 0, '', 1);




        $pdf->Ln(10);

        $data =  $_SESSION['sale_reportsrc'];
        $lineHeight = 10;
        $maxY = 270;



        $patotal = 0;
        $total = 0;

        foreach ($data as $row) {



            if ($pdf->GetY() + $lineHeight > $maxY) {
                $pdf->updatePageTotal($patotal);
                $patotal = 0;
                $pdf->AddPage();
                $page = $page + 1;
                $this->header($pdf, $page, "Sales Report (Category Wise)", $_SESSION['src_istype'], $_SESSION['srcfrom_date'], $_SESSION['srcto_date']);
                $pdf->SetFont('helvetica', 'B', 12);
                $pdf->Cell(75, 10, 'Category Name', 'TB', 0, 'L', 0, '', 1);
                $pdf->Cell(60, 10, 'Product Name', 'TB', 0, 'L', 0, '', 1);
                $pdf->Cell(20, 10, 'Qty', 'TB', 0, 'C', 0, '', 1);
                $pdf->Cell(30, 10, 'Amount', 'TB', 0, 'R', 0, '', 1);
                $pdf->Ln(10);
            }
            $patotal = $patotal + $row['total_price'];
            $total = $total + $row['total_price'];

            // $pdf->SetFont('', '', 10);
            // $pdf->Cell(45, 8, $row['category_name'], 0, 0, 'L');
            // $pdf->Cell(50, 8,  $row['product_name'], 0, 0, 'L');
            // $pdf->Cell(30, 8,  $row['date'], 0, 0, 'L');
            // $pdf->Cell(20, 8,  $row['quantity'], 0, 0, 'R');
            // $pdf->Cell(40, 8, number_format($row['total_price'], 2), 0, 0, 'R');

            $pdf->SetFont('', '', 10);
            $pdf->MultiCell(75, 8, $row['category_name'], '', 'L', 0, 0);
            $pdf->MultiCell(60, 8, $row['product_name'], '', 'L', 0, 0);
            $pdf->MultiCell(20, 8, $row['quantity'], '', 'C', 0, 0);
            $pdf->MultiCell(30, 8,  number_format($row['total_price'], 2), '', 'R', 0, 0);
            $pdf->Ln(10);



            // $pdf->Cell(40, 8, number_format($row['total'], 2), 0, 1, 'R');
        }
        $pdf->SetFont('', 'B', 12);
        $pdf->Cell(50, 10, "Total Amount:", 'TB', 0, 'L');
        $pdf->Cell(100, 10, "", 'TB', 0, 'L');
        $pdf->Cell(35, 10, number_format($total, 2), 'TB', 1, 'R');

        $pdf->updatePageTotal($patotal);



        $date = date('Y-m-d');
        $filename = "Sales Report (Category Wise)_$date.pdf";
        ob_end_clean();

        $pdf->Output($filename, 'I');
    }


    public function generate_salesreportemployee()
    {
        $page = 1;
        $pdf = new SalesReportInvoicewise('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Your Name');
        $pdf->SetTitle('Sales Report(Employee Wise)');
        $pdf->SetSubject('TCPDF Tutorial');
        $pdf->SetKeywords('TCPDF, PDF, columns, example');
        $top_margin = 5;
        $pdf->SetMargins(15, $top_margin, 10);
        $pdf->SetAutoPageBreak(TRUE, 20);
        $pdf->AddPage();
        $pdf->SetFont('helvetica', '', 10);

        $this->header($pdf, $page, "Sales Report (Employee Wise)", $_SESSION['sre_istype'], $_SESSION['srefrom_date'], $_SESSION['sreto_date']);


        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->Cell(50, 10, 'Employee ID', 'TB', 0, 'L', 0, '', 1);
        $pdf->Cell(50, 10, 'Employee Name', 'TB', 0, 'L', 0, '', 1);
        $pdf->Cell(40, 10, 'Total Sale', 'TB', 0, 'C', 0, '', 1);
        $pdf->Cell(45, 10, 'Total Amount', 'TB', 0, 'R', 0, '', 1);




        $pdf->Ln(10);

        $data =  $_SESSION['sale_reportsre'];
        $lineHeight = 10;
        $maxY = 270;



        $patotal = 0;
        $total = 0;

        foreach ($data as $row) {



            if ($pdf->GetY() + $lineHeight > $maxY) {
                $pdf->updatePageTotal($patotal);
                $patotal = 0;
                $pdf->AddPage();
                $page = $page + 1;
                $this->header($pdf, $page, "Sales Report (Employee Wise)", $_SESSION['sre_istype'], $_SESSION['srefrom_date'], $_SESSION['sreto_date']);
                $pdf->SetFont('helvetica', 'B', 12);
                $pdf->Cell(50, 10, 'First Name', 'TB', 0, 'L', 0, '', 1);
                $pdf->Cell(50, 10, 'Last Name', 'TB', 0, 'L', 0, '', 1);
                $pdf->Cell(40, 10, 'Total Sale', 'TB', 0, 'C', 0, '', 1);
                $pdf->Cell(45, 10, 'Total Amount', 'TB', 0, 'R', 0, '', 1);
                $pdf->Ln(10);
            }
            $patotal = $patotal + $row['amount'];
            $total = $total + $row['amount'];

            $pdf->SetFont('', '', 10);
            $pdf->Cell(50, 8, $row['first_name'], 0, 0, 'L');
            $pdf->Cell(50, 8,  $row['last_name'], 0, 0, 'L');
            $pdf->Cell(40, 8,  $row['toal_invoice'], 0, 0, 'C');
            $pdf->Cell(45, 8, number_format($row['amount'], 2), 0, 0, 'R');
            $pdf->Ln(8);



            // $pdf->Cell(40, 8, number_format($row['total'], 2), 0, 1, 'R');
        }
        $pdf->SetFont('', 'B', 12);
        $pdf->Cell(50, 10, "Total Amount:", 'TB', 0, 'L');
        $pdf->Cell(100, 10, "", 'TB', 0, 'L');
        $pdf->Cell(35, 10, number_format($total, 2), 'TB', 1, 'R');

        $pdf->updatePageTotal($patotal);




        $date = date('Y-m-d');
        $filename = "Sales Report (Employee Wise)_$date.pdf";
        $pdf->Output($filename, 'I');
    }

    public function generate_purchasereportcategory()
    {
        $page = 1;
        $pdf = new SalesReportInvoicewise('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Your Name');
        $pdf->SetTitle('Purchase Report(Category Wise)');
        $pdf->SetSubject('TCPDF Tutorial');
        $pdf->SetKeywords('TCPDF, PDF, columns, example');
        $top_margin = 5;
        $pdf->SetMargins(15, $top_margin, 10);
        $pdf->SetAutoPageBreak(TRUE, 20);
        $pdf->AddPage();
        $pdf->SetFont('helvetica', '', 10);

        $this->header($pdf, $page, "Purchase Report (Category Wise)", $_SESSION['prc_istype'], $_SESSION['prcfrom_date'], $_SESSION['prcto_date']);


        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->Cell(75, 10, 'Category Name', 'TB', 0, 'L', 0, '', 1);
        $pdf->Cell(60, 10, 'Product Name', 'TB', 0, 'L', 0, '', 1);
        $pdf->Cell(20, 10, 'Qty', 'TB', 0, 'C', 0, '', 1);
        $pdf->Cell(30, 10, 'Amount', 'TB', 0, 'R', 0, '', 1);




        $pdf->Ln(10);

        $data =  $_SESSION['purchase_reportprc'];
        $lineHeight = 10;
        $maxY = 270;



        $patotal = 0;
        $total = 0;

        foreach ($data as $row) {



            if ($pdf->GetY() + $lineHeight > $maxY) {
                $pdf->updatePageTotal($patotal);
                $patotal = 0;
                $pdf->AddPage();
                $page = $page + 1;
                $this->header($pdf, $page, "Purchase Report (Category Wise)", $_SESSION['src_istype'], $_SESSION['srcfrom_date'], $_SESSION['srcto_date']);
                $pdf->SetFont('helvetica', 'B', 12);
                $pdf->Cell(75, 10, 'Category Name', 'TB', 0, 'L', 0, '', 1);
                $pdf->Cell(60, 10, 'Product Name', 'TB', 0, 'L', 0, '', 1);
                $pdf->Cell(20, 10, 'Qty', 'TB', 0, 'C', 0, '', 1);
                $pdf->Cell(30, 10, 'Amount', 'TB', 0, 'R', 0, '', 1);
                $pdf->Ln(10);
            }
            $patotal = $patotal + $row['total_price'];
            $total = $total + $row['total_price'];



            $pdf->SetFont('', '', 10);
            $pdf->MultiCell(75, 8, $row['category_name'], '', 'L', 0, 0);
            $pdf->MultiCell(60, 8, $row['product_name'], '', 'L', 0, 0);
            $pdf->MultiCell(20, 8, $row['quantity'], '', 'C', 0, 0);
            $pdf->MultiCell(30, 8,  number_format($row['total_price'], 2), '', 'R', 0, 0);

            // $pdf->Cell(40, 8, number_format($row['total_price'], 2), 0, 0, 'R');






            // $pdf->Cell(45, 8, $row['category_name'], 0, 0, 'L');
            // $pdf->Cell(50, 8,  $row['product_name'], 0, 0, 'L');
            // $pdf->Cell(30, 8,  $row['date'], 0, 0, 'L');
            // $pdf->Cell(20, 8,  $row['quantity'], 0, 0, 'R');
            // $pdf->Cell(40, 8, number_format($row['total_price'], 2), 0, 0, 'R');
            $pdf->Ln(10);



            // $pdf->Cell(40, 8, number_format($row['total'], 2), 0, 1, 'R');
        }
        $pdf->SetFont('', 'B', 12);
        $pdf->Cell(50, 10, "Total Amount:", 'TB', 0, 'L');
        $pdf->Cell(100, 10, "", 'TB', 0, 'L');
        $pdf->Cell(35, 10, number_format($total, 2), 'TB', 1, 'R');

        $pdf->updatePageTotal($patotal);



        $date = date('Y-m-d');
        $filename = "Sales Report (Category Wise)_$date.pdf";
        ob_end_clean();

        $pdf->Output($filename, 'I');
    }


    public function header($pdf, $page, $head, $type, $from, $to)
    {
        $pdf->Ln(5);
        $company_info     = $this->service_model->company_info();
        $currency_details = $this->service_model->web_setting();
        $pdf->SetFont('helvetica', 'B', 100);
        $x = $pdf->GetX();
        $y = $pdf->GetY();
        $pdf->Image(base_url() . $currency_details[0]['invoice_logo'], $x, 13, 50, 20, '', '', '', true, 300, '', false, false, 0, false, false, false);
        $pdf->SetX($x + 65);
        $pdf->SetFont('helvetica', 'B', 15);
        $pageWidth = $pdf->GetPageWidth() + 10;
        $pdf->SetFont('helvetica', 'B', 20);
        $company =  $company_info[0]['company_name'];
        $companyWidth = $pdf->GetStringWidth($company);
        $pdf->SetX(($pageWidth - $companyWidth) / 2);
        $pdf->Cell($companyWidth, 10, $company, 0, 1, 'C');
        $pdf->SetFont('helvetica', '', 10);
        $address = $company_info[0]['address'];
        $addressWidth = $pdf->GetStringWidth($address);
        $pdf->SetX(($pageWidth - $addressWidth) / 2);
        $pdf->Cell($addressWidth, 5, $address, 0, 1, 'C');
        $email = $company_info[0]['email'];
        $emailWidth = $pdf->GetStringWidth($email);
        $pdf->SetX(($pageWidth - $emailWidth) / 2);
        $pdf->Cell($emailWidth, 5, $email, 0, 1, 'C');
        $mobile = $company_info[0]['mobile'];
        $mobileWidth = $pdf->GetStringWidth($mobile);
        $pdf->SetX(($pageWidth - $mobileWidth) / 2);
        $pdf->Cell($mobileWidth, 5, $mobile, 0, 1, 'C');
        $pdf->SetFont('helvetica', 'B', 12);
        $headWidth = $pdf->GetStringWidth($head);
        $pdf->SetX(($pageWidth - $headWidth) / 2);
        $pdf->Cell($headWidth, 5, $head, 0, 1, 'C');

        $pdf->SetFont('helvetica', '', 11);
        $dateWidth = $pdf->GetStringWidth($mobile);
        $pdf->SetX(($pageWidth - $dateWidth) / 2);

        if (isset($type) && $type  === "false") {
            $pdf->Cell($dateWidth, 10, "From: " . $from . "  " . "To: " . $to, 0, 1, 'C');
        } else if ($from != "") {
            $pdf->Cell($dateWidth, 10, "Date: " . $from, 0, 1, 'C');
        }

        $margins = $pdf->getMargins();
        $pageWidth = $pdf->GetPageWidth();
        $rightMargin = $margins['right'];
        $pdf->SetFont('helvetica', 'B', 60);
        $rightText =  $page;
        $pdf->SetXY($pageWidth - $rightMargin - 60, 13);
        $pdf->Cell(50, 10, "", 0, 0, 'R');
        $pdf->Ln(40);
    }


    public function stock_reportdata()
    {
        $from_date = $this->input->post('from_date');
        $to_date  = $this->input->post('to_date');

        $product       = $this->input->post('product');
        $product_group = (int)$this->input->post('product_group');
        $category      = $this->input->post('category');
        $store         = $this->input->post('store');
        $stocktype     = $this->input->post('stocktype');
        $batch         = $this->input->post('batch');

        $storeResult = $this->db->select("store.id")
            ->from('sec_store')
            ->join('store', 'store.id=sec_store.storeid')
            ->where('sec_store.userid', $this->session->userdata('id'))
            ->group_by('sec_store.storeid')
            ->get()
            ->result();

        $storeids = [];

        if (isset($storeResult)) {
            $storeids = array_column($storeResult, 'id');
        }

        $sqljoin = "";
        if ($category) {
            $sqljoin .= " And pi.category_id=" . $category;
        }

        if ($product) {
            $sqljoin .= " And pi.id=" . (int)$product;
        } elseif ($product_group) {
            $sqljoin .= " And pi.id IN (SELECT product FROM product_group_details WHERE pid = " . $product_group . ")";
        }

        if ($store) {
            $sqljoin .= " And sd.store=" . $store;
        } else {
            if ($this->session->userdata('user_level2') != 1 && !empty($storeids)) {
                $inClause = implode(',', array_map('intval', $storeids));
                $sqljoin .= " AND sd.store IN ($inClause) ";
            }
        }

        if ($batch==0) {
            $sqljoin .= " And sd.batch=" . $batch;
        } 

        if ($batch) {
            $sqljoin .= " And sd.batch=" . $batch;
        } 


        $encryption_key = Config::$encryption_key;


        if ($stocktype == "all" || $stocktype == "") {
            $sql = "SELECT  id,product_name,
            unit,
            category_name,
            SUM(inqty) AS inqty,
            SUM(outqty) AS outqty,
            SUM(avqty) AS avqty,
            SUM(pinqty) AS pinqty,
            SUM(poutqty) AS poutqty,
            SUM(pavqty) AS pavqty,purchase_price, sale_price,sub,master,conversion_ratio,subpurchase_price,subsale_price from (SELECT pi.id,pi.product_name,pi.unit,pc.category_name,
        SUM(CASE  WHEN AES_DECRYPT(sd.stock, '" . $encryption_key . "')  > 0  THEN AES_DECRYPT(sd.stock, '" . $encryption_key . "')  ELSE 0 END) AS inqty,
        ABS(SUM(CASE WHEN AES_DECRYPT(sd.stock, '" . $encryption_key . "')  < 0 THEN AES_DECRYPT(sd.stock, '" . $encryption_key . "')  ELSE 0  END)) AS outqty,
        SUM(CASE 
            WHEN AES_DECRYPT(sd.stock, '" . $encryption_key . "') > 0 
            THEN AES_DECRYPT(sd.stock, '" . $encryption_key . "') 
            ELSE 0 
        END) 
    - 
    ABS(SUM(CASE 
                WHEN AES_DECRYPT(sd.stock, '" . $encryption_key . "') < 0 
                THEN AES_DECRYPT(sd.stock, '" . $encryption_key . "') 
                ELSE 0  
            END)) AS avqty, NULL AS pinqty,NULL AS poutqty,NULL AS pavqty,AES_DECRYPT(pi.cost_price, '" . $encryption_key . "') as purchase_price,AES_DECRYPT(pi.price, '" . $encryption_key . "') as sale_price,pi.category_id,
        u1.unit_name as sub,u2.unit_name as master,cr.conversion_ratio,AES_DECRYPT(sp.subcost_price, '" . $encryption_key . "') as subpurchase_price,AES_DECRYPT(sp.subsell_price, '" . $encryption_key . "') as subsale_price
        from product_information pi
        INNER JOIN product_category pc on pc.category_id=pi.category_id
        INNER JOIN stock_details sd on sd.product=pi.id
        left JOIN subunit_product sp ON sp.product_id = pi.id && sp.first=1
        left JOIN units u1  ON u1.unit_id = sp.unit_id 
        INNER JOIN units u2 ON u2.unit_id = pi.unit 
        left JOIN conversion_ratio cr ON cr.product = pi.id && u1.unit_id=cr.subunit
        WHERE pi.status=1 and sd.date BETWEEN '$from_date' AND '$to_date'" . $sqljoin . "
        GROUP By pi.id
        UNION 
        SELECT pi.id,pi.product_name,pi.unit,pc.category_name,
         NULL AS inqty,
            NULL AS outqty,
            NULL AS avqty,
        SUM(CASE  WHEN AES_DECRYPT(sd.stock, '" . $encryption_key . "')  > 0  THEN AES_DECRYPT(sd.stock, '" . $encryption_key . "')  ELSE 0 END) AS pinqty,
        ABS(SUM(CASE WHEN AES_DECRYPT(sd.stock, '" . $encryption_key . "')  < 0 THEN AES_DECRYPT(sd.stock, '" . $encryption_key . "')  ELSE 0  END)) AS poutqty,
        SUM(CASE 
            WHEN AES_DECRYPT(sd.stock, '" . $encryption_key . "') > 0 
            THEN AES_DECRYPT(sd.stock, '" . $encryption_key . "') 
            ELSE 0 
        END) 
    - 
    ABS(SUM(CASE 
                WHEN AES_DECRYPT(sd.stock, '" . $encryption_key . "') < 0 
                THEN AES_DECRYPT(sd.stock, '" . $encryption_key . "') 
                ELSE 0  
            END)) AS pavqty,AES_DECRYPT(pi.cost_price, '" . $encryption_key . "') as purchase_price,AES_DECRYPT(pi.price, '" . $encryption_key . "') as sale_price,pi.category_id,
        u1.unit_name as sub,u2.unit_name as master,cr.conversion_ratio,AES_DECRYPT(sp.subcost_price, '" . $encryption_key . "') as subpurchase_price,AES_DECRYPT(sp.subsell_price, '" . $encryption_key . "') as subsale_price
        from product_information pi
        INNER JOIN product_category pc on pc.category_id=pi.category_id
        INNER JOIN phystock_details sd on sd.product=pi.id
        left JOIN subunit_product sp ON sp.product_id = pi.id && sp.first=1
        left JOIN units u1  ON u1.unit_id = sp.unit_id 
        INNER JOIN units u2 ON u2.unit_id = pi.unit 
        left JOIN conversion_ratio cr ON cr.product = pi.id && u1.unit_id=cr.subunit
        WHERE pi.status=1 and sd.date BETWEEN '$from_date' AND '$to_date'" . $sqljoin . "
        GROUP By pi.id) AS stock_data
        GROUP BY id;";


            $query = $this->db->query($sql);
            $data  = $query->result_array();
        }

        if ($stocktype == "actualstock") {

            $sql = "SELECT pi.id,pi.product_name,pi.unit,pc.category_name,
SUM(CASE  WHEN AES_DECRYPT(sd.stock, '" . $encryption_key . "')  > 0  THEN AES_DECRYPT(sd.stock, '" . $encryption_key . "')  ELSE 0 END) AS inqty,
SUM(CASE WHEN AES_DECRYPT(sd.stock, '" . $encryption_key . "')  < 0 THEN AES_DECRYPT(sd.stock, '" . $encryption_key . "')  ELSE 0  END) AS outqty,
 SUM(CASE 
            WHEN AES_DECRYPT(sd.stock, '" . $encryption_key . "') > 0 
            THEN AES_DECRYPT(sd.stock, '" . $encryption_key . "') 
            ELSE 0 
        END) 
    - 
    ABS(SUM(CASE 
                WHEN AES_DECRYPT(sd.stock, '" . $encryption_key . "') < 0 
                THEN AES_DECRYPT(sd.stock, '" . $encryption_key . "') 
                ELSE 0  
            END)) AS avqty,AES_DECRYPT(pi.cost_price, '" . $encryption_key . "') as purchase_price,AES_DECRYPT(pi.price, '" . $encryption_key . "') as sale_price,pi.category_id,
u1.unit_name as sub,u2.unit_name as master,cr.conversion_ratio,AES_DECRYPT(sp.subcost_price, '" . $encryption_key . "') as subpurchase_price,AES_DECRYPT(sp.subsell_price, '" . $encryption_key . "') as subsale_price
from product_information pi
INNER JOIN product_category pc on pc.category_id=pi.category_id
INNER JOIN stock_details sd on sd.product=pi.id
left JOIN subunit_product sp ON sp.product_id = pi.id && sp.first=1
left JOIN units u1  ON u1.unit_id = sp.unit_id 
INNER JOIN units u2 ON u2.unit_id = pi.unit 
left JOIN conversion_ratio cr ON cr.product = pi.id && u1.unit_id=cr.subunit
WHERE sd.date BETWEEN '$from_date' AND '$to_date'" . $sqljoin . "
GROUP By pi.id";
            $query = $this->db->query($sql);
            $data  = $query->result_array();
        }

        if ($stocktype == "physicalstock") {

            $sql = "SELECT pi.id,pi.product_name,pi.unit,pc.category_name,
SUM(CASE  WHEN AES_DECRYPT(sd.stock, '" . $encryption_key . "')  > 0  THEN AES_DECRYPT(sd.stock, '" . $encryption_key . "')  ELSE 0 END) AS pinqty,
SUM(CASE WHEN AES_DECRYPT(sd.stock, '" . $encryption_key . "')  < 0 THEN AES_DECRYPT(sd.stock, '" . $encryption_key . "')  ELSE 0  END) AS poutqty,
SUM(CASE 
            WHEN AES_DECRYPT(sd.stock, '" . $encryption_key . "') > 0 
            THEN AES_DECRYPT(sd.stock, '" . $encryption_key . "') 
            ELSE 0 
        END) 
    - 
    ABS(SUM(CASE 
                WHEN AES_DECRYPT(sd.stock, '" . $encryption_key . "') < 0 
                THEN AES_DECRYPT(sd.stock, '" . $encryption_key . "') 
                ELSE 0  
            END)) AS pavqty,AES_DECRYPT(pi.cost_price, '" . $encryption_key . "') as purchase_price,AES_DECRYPT(pi.price, '" . $encryption_key . "') as sale_price,pi.category_id,
u1.unit_name as sub,u2.unit_name as master,cr.conversion_ratio,AES_DECRYPT(sp.subcost_price, '" . $encryption_key . "') as subpurchase_price,AES_DECRYPT(sp.subsell_price, '" . $encryption_key . "') as subsale_price
from product_information pi
INNER JOIN product_category pc on pc.category_id=pi.category_id
INNER JOIN phystock_details sd on sd.product=pi.id
left JOIN subunit_product sp ON sp.product_id = pi.id && sp.first=1
left JOIN units u1  ON u1.unit_id = sp.unit_id 
INNER JOIN units u2 ON u2.unit_id = pi.unit 
left JOIN conversion_ratio cr ON cr.product = pi.id && u1.unit_id=cr.subunit
WHERE sd.date BETWEEN '$from_date' AND '$to_date'" . $sqljoin . "
GROUP By pi.id";
            $query = $this->db->query($sql);
            $data  = $query->result_array();
        }

        $empid = $this->input->post('empid');
        $_SESSION['sr_istype'] =   $this->input->post('istype');
        $_SESSION['srfrom_date'] = $from_date;
        $_SESSION['srto_date'] =  $to_date;
        $_SESSION['sr_istype2'] =  $stocktype;
        $_SESSION['header'] = "Comprehensive Stock Report";




        echo json_encode($data);
    }

    public function set_stock_session()
    {
        $stock_report = $this->input->post('datas');
        $_SESSION['stock_report'] =  $stock_report;
        echo json_encode("");
    }
    public function set_stock_session2()
    {
        $stock_report = $this->input->post('datas');
        $_SESSION['product_batch_summary_report_data'] = $stock_report;
        echo json_encode("");
    }


    public function livestock_reportdata()
    {

        $product       = $this->input->post('product');
        $product_group = (int)$this->input->post('product_group');
        $category      = $this->input->post('category');
        $store         = $this->input->post('store');
        $stocktype     = $this->input->post('stocktype');
        $batch         = $this->input->post('batch');

        $storeResult = $this->db->select("store.id")
            ->from('sec_store')
            ->join('store', 'store.id=sec_store.storeid')
            ->where('sec_store.userid', $this->session->userdata('id'))
            ->group_by('sec_store.storeid')
            ->get()
            ->result();

        $storeids = [];

        if (isset($storeResult)) {
            $storeids = array_column($storeResult, 'id');
        }

        $sqljoin = "";
        if ($category) {
            $sqljoin .= " And pi.category_id=" . $category;
        }

        if ($product) {
            $sqljoin .= " And pi.id=" . (int)$product;
        } elseif ($product_group) {
            $sqljoin .= " And pi.id IN (SELECT product FROM product_group_details WHERE pid = " . $product_group . ")";
        }

        if ($store) {
            $sqljoin .= " And sd.store=" . $store;
        } else {
            if ($this->session->userdata('user_level2') != 1 && !empty($storeids)) {
                $inClause = implode(',', array_map('intval', $storeids));
                $sqljoin .= " AND sd.store IN ($inClause) ";
            }
        }

        if ($batch==0) {
            $sqljoin .= " And sd.batch=" . $batch;
        } 

        if ($batch) {
            $sqljoin .= " And sd.batch=" . $batch;
        } 


        $encryption_key = Config::$encryption_key;


        if ($stocktype == "all" || $stocktype == "") {
            $sql = "SELECT  id,product_name,
            unit,
            category_name,
            SUM(inqty) AS inqty,
            SUM(outqty) AS outqty,
            SUM(avqty) AS avqty,
            SUM(pinqty) AS pinqty,
            SUM(poutqty) AS poutqty,
            SUM(pavqty) AS pavqty,purchase_price, sale_price,sub,master,conversion_ratio,
            MAX(max_stock_level) AS max_stock_level,
            MAX(min_stock_level) AS min_stock_level,
            MAX(reorder_stock_level) AS reorder_stock_level,
            MAX(reserve_stock_level) AS reserve_stock_level,subpurchase_price,subsale_price
            from (SELECT pi.id,pi.product_name,pi.unit,pc.category_name,
        SUM(CASE  WHEN AES_DECRYPT(sd.stock, '" . $encryption_key . "')  > 0  THEN AES_DECRYPT(sd.stock, '" . $encryption_key . "')  ELSE 0 END) AS inqty,
        SUM(CASE WHEN AES_DECRYPT(sd.stock, '" . $encryption_key . "')  < 0 THEN AES_DECRYPT(sd.stock, '" . $encryption_key . "')  ELSE 0  END) AS outqty,
         SUM(CASE 
            WHEN AES_DECRYPT(sd.stock, '" . $encryption_key . "') > 0 
            THEN AES_DECRYPT(sd.stock, '" . $encryption_key . "') 
            ELSE 0 
        END) 
    - 
    ABS(SUM(CASE 
                WHEN AES_DECRYPT(sd.stock, '" . $encryption_key . "') < 0 
                THEN AES_DECRYPT(sd.stock, '" . $encryption_key . "') 
                ELSE 0  
            END)) AS avqty, NULL AS pinqty,NULL AS poutqty,NULL AS pavqty,AES_DECRYPT(pi.cost_price, '" . $encryption_key . "') as purchase_price,AES_DECRYPT(pi.price, '" . $encryption_key . "') as sale_price,pi.category_id,
            u1.unit_name as sub,u2.unit_name as master,cr.conversion_ratio,
            pi.max_stock_level,pi.min_stock_level,pi.reorder_stock_level,pi.reserve_stock_level,AES_DECRYPT(sp.subcost_price, '" . $encryption_key . "') as subpurchase_price,AES_DECRYPT(sp.subsell_price, '" . $encryption_key . "') as subsale_price
        from product_information pi
        INNER JOIN product_category pc on pc.category_id=pi.category_id
        INNER JOIN stock_details sd on sd.product=pi.id
        left JOIN subunit_product sp ON sp.product_id = pi.id && sp.first=1
        left JOIN units u1  ON u1.unit_id = sp.unit_id 
        INNER JOIN units u2 ON u2.unit_id = pi.unit 
        left JOIN conversion_ratio cr ON cr.product = pi.id && u1.unit_id=cr.subunit
        WHERE pi.status=1 " . $sqljoin . "
        GROUP By pi.id
        UNION 
        SELECT pi.id,pi.product_name,pi.unit,pc.category_name,
         NULL AS inqty,
            NULL AS outqty,
            NULL AS avqty,
        SUM(CASE  WHEN AES_DECRYPT(sd.stock, '" . $encryption_key . "')  > 0  THEN AES_DECRYPT(sd.stock, '" . $encryption_key . "')  ELSE 0 END) AS pinqty,
        SUM(CASE WHEN AES_DECRYPT(sd.stock, '" . $encryption_key . "')  < 0 THEN AES_DECRYPT(sd.stock, '" . $encryption_key . "')  ELSE 0  END) AS poutqty,
        SUM(CASE 
            WHEN AES_DECRYPT(sd.stock, '" . $encryption_key . "') > 0 
            THEN AES_DECRYPT(sd.stock, '" . $encryption_key . "') 
            ELSE 0 
        END) 
    - 
    ABS(SUM(CASE 
                WHEN AES_DECRYPT(sd.stock, '" . $encryption_key . "') < 0 
                THEN AES_DECRYPT(sd.stock, '" . $encryption_key . "') 
                ELSE 0  
            END)) AS pavqty,AES_DECRYPT(pi.cost_price, '" . $encryption_key . "') as purchase_price,AES_DECRYPT(pi.price, '" . $encryption_key . "') as sale_price,pi.category_id,
            u1.unit_name as sub,u2.unit_name as master,cr.conversion_ratio,
            pi.max_stock_level,pi.min_stock_level,pi.reorder_stock_level,pi.reserve_stock_level,AES_DECRYPT(sp.subcost_price, '" . $encryption_key . "') as subpurchase_price,AES_DECRYPT(sp.subsell_price, '" . $encryption_key . "') as subsale_price
        from product_information pi
        INNER JOIN product_category pc on pc.category_id=pi.category_id
        INNER JOIN phystock_details sd on sd.product=pi.id
        left JOIN subunit_product sp ON sp.product_id = pi.id && sp.first=1
        left JOIN units u1  ON u1.unit_id = sp.unit_id 
        INNER JOIN units u2 ON u2.unit_id = pi.unit 
        left JOIN conversion_ratio cr ON cr.product = pi.id && u1.unit_id=cr.subunit
        WHERE pi.status=1 " . $sqljoin . "
        GROUP By pi.id) AS stock_data
        GROUP BY id;";


            $query = $this->db->query($sql);
            $data  = $query->result_array();
        }

        if ($stocktype == "actualstock") {

            $sql = "SELECT pi.id,pi.product_name,pi.unit,pc.category_name,
SUM(CASE  WHEN AES_DECRYPT(sd.stock, '" . $encryption_key . "')  > 0  THEN AES_DECRYPT(sd.stock, '" . $encryption_key . "')  ELSE 0 END) AS inqty,
SUM(CASE WHEN AES_DECRYPT(sd.stock, '" . $encryption_key . "')  < 0 THEN AES_DECRYPT(sd.stock, '" . $encryption_key . "')  ELSE 0  END) AS outqty,
   SUM(CASE 
            WHEN AES_DECRYPT(sd.stock, '" . $encryption_key . "') > 0 
            THEN AES_DECRYPT(sd.stock, '" . $encryption_key . "') 
            ELSE 0 
        END) 
    - 
    ABS(SUM(CASE 
                WHEN AES_DECRYPT(sd.stock, '" . $encryption_key . "') < 0 
                THEN AES_DECRYPT(sd.stock, '" . $encryption_key . "') 
                ELSE 0  
                END)) AS avqty,AES_DECRYPT(pi.cost_price, '" . $encryption_key . "') as purchase_price,AES_DECRYPT(pi.price, '" . $encryption_key . "') as sale_price,pi.category_id,
    u1.unit_name as sub,u2.unit_name as master,cr.conversion_ratio,
    pi.max_stock_level,pi.min_stock_level,pi.reorder_stock_level,pi.reserve_stock_level,AES_DECRYPT(sp.subcost_price, '" . $encryption_key . "') as subpurchase_price,AES_DECRYPT(sp.subsell_price, '" . $encryption_key . "') as subsale_price
from product_information pi
INNER JOIN product_category pc on pc.category_id=pi.category_id
INNER JOIN stock_details sd on sd.product=pi.id
left JOIN subunit_product sp ON sp.product_id = pi.id && sp.first=1
left JOIN units u1  ON u1.unit_id = sp.unit_id 
INNER JOIN units u2 ON u2.unit_id = pi.unit 
left JOIN conversion_ratio cr ON cr.product = pi.id && u1.unit_id=cr.subunit
WHERE " . $sqljoin . "
GROUP By pi.id";
            $query = $this->db->query($sql);
            $data  = $query->result_array();
        }

        if ($stocktype == "physicalstock") {

            $sql = "SELECT pi.id,pi.product_name,pi.unit,pc.category_name,
SUM(CASE  WHEN AES_DECRYPT(sd.stock, '" . $encryption_key . "')  > 0  THEN AES_DECRYPT(sd.stock, '" . $encryption_key . "')  ELSE 0 END) AS pinqty,
SUM(CASE WHEN AES_DECRYPT(sd.stock, '" . $encryption_key . "')  < 0 THEN AES_DECRYPT(sd.stock, '" . $encryption_key . "')  ELSE 0  END) AS poutqty,
   SUM(CASE 
            WHEN AES_DECRYPT(sd.stock, '" . $encryption_key . "') > 0 
            THEN AES_DECRYPT(sd.stock, '" . $encryption_key . "') 
            ELSE 0 
        END) 
    - 
    ABS(SUM(CASE 
                WHEN AES_DECRYPT(sd.stock, '" . $encryption_key . "') < 0 
                THEN AES_DECRYPT(sd.stock, '" . $encryption_key . "') 
                ELSE 0  
            END)) AS pavqty
,AES_DECRYPT(pi.cost_price, '" . $encryption_key . "') as purchase_price,AES_DECRYPT(pi.price, '" . $encryption_key . "') as sale_price,pi.category_id,
    u1.unit_name as sub,u2.unit_name as master,cr.conversion_ratio,
    pi.max_stock_level,pi.min_stock_level,pi.reorder_stock_level,pi.reserve_stock_level,AES_DECRYPT(sp.subcost_price, '" . $encryption_key . "') as subpurchase_price,AES_DECRYPT(sp.subsell_price, '" . $encryption_key . "') as subsale_price
from product_information pi
INNER JOIN product_category pc on pc.category_id=pi.category_id
INNER JOIN phystock_details sd on sd.product=pi.id
left JOIN subunit_product sp ON sp.product_id = pi.id && sp.first=1
left JOIN units u1  ON u1.unit_id = sp.unit_id 
INNER JOIN units u2 ON u2.unit_id = pi.unit 
left JOIN conversion_ratio cr ON cr.product = pi.id && u1.unit_id=cr.subunit
WHERE sd.date " . $sqljoin . "
GROUP By pi.id";
            $query = $this->db->query($sql);
            $data  = $query->result_array();
        }
        $empid = $this->input->post('empid');
       // $_SESSION['stock_report'] =  $data;
        $_SESSION['sr_istype'] =   $this->input->post('istype');
        $_SESSION['srfrom_date'] = '';
        $_SESSION['srto_date'] =  '';
        $_SESSION['sr_istype2'] =  $stocktype;
        $_SESSION['header'] = "Live Stock Report";





        echo json_encode($data);
    }



    public function cashbook_reportdata()
    {
        $encryption_key = Config::$encryption_key;

        $from_date = $this->input->post('from_date');
        $to_date  = $this->input->post('to_date');
        $empid = $this->input->post('empid');
        $payment = $this->input->post('payment');
        $branch = $this->input->post('branch');

        $branchResult = $this->db->select("branch.id")
            ->from('sec_branch')
            ->join('branch', 'branch.id=sec_branch.branchid')
            ->where('sec_branch.userid', $this->session->userdata('id'))
            ->group_by('sec_branch.branchid')
            ->get()
            ->result();

        $branchids = [];

        if (isset($branchResult)) {
            $branchids = array_column($branchResult, 'id');
        }




        $sqljoin = "";

        if ($empid != "All") {
            $sqljoin .= " And type2= '" . $empid . "'";
        }

        if ($payment) {
            $sqljoin .= " And payment_type= " . $payment . "";
        }

        // if ($branch) {
        //     $this->db->where("a.branch", $branch);
        // } else {
        //     if ($this->session->userdata('user_level2') != 1) {

        //         $this->db->where_in('a.branch', $branchids);
        //     }
        // }

        if ($branch) {
            $sqljoin .= " AND branch = " . (int)$branch . " ";
        } else {
            if ($this->session->userdata('user_level2') != 1 && !empty($branchids)) {
                // Convert branch ID array to comma-separated string
                $inClause = implode(',', array_map('intval', $branchids));
                $sqljoin .= " AND branch IN ($inClause) ";
            }
        }



       $sql = "
SELECT 
    date,
    incidenttype,
    payment_type,
    payment_method,
    invoice_no,
    grandTotal,
    type2,
    createddate,
    branch
FROM
(
    SELECT 
        V.date,
        'Payments' AS incidenttype,
        V.from AS payment_type,
        pt.name AS payment_method,
        AES_DECRYPT(V.voucher_id, '{$encryption_key}') AS invoice_no,
        -CAST(AES_DECRYPT(V.total, '{$encryption_key}') AS DECIMAL(18,2)) AS grandTotal,
        AES_DECRYPT(V.type2, '{$encryption_key}') AS type2,
        V.date as createddate,
        V.branch
    FROM voucher V
    INNER JOIN payment_type pt ON pt.id = V.from
    WHERE V.type = 1

    UNION ALL

    SELECT 
        V.date,
        'Receipts' AS incidenttype,
        V.from AS payment_type,
        pt.name AS payment_method,
        AES_DECRYPT(V.voucher_id, '{$encryption_key}') AS invoice_no,
        CAST(AES_DECRYPT(V.total, '{$encryption_key}') AS DECIMAL(18,2)) AS grandTotal,
        AES_DECRYPT(V.type2, '{$encryption_key}') AS type2,
        V.date as createddate,
        V.branch
    FROM voucher V
    INNER JOIN payment_type pt ON pt.id = V.from
    WHERE V.type = 2

    UNION ALL
      SELECT 
        V.date,
        'Transfer' AS incidenttype,
        V.from AS payment_type,
        pt.name AS payment_method,
        AES_DECRYPT(V.voucher_id, '{$encryption_key}') AS invoice_no,
        -CAST(AES_DECRYPT(V.total, '{$encryption_key}') AS DECIMAL(18,2)) AS grandTotal,
        AES_DECRYPT(V.type2, '{$encryption_key}') AS type2,
        V.date as createddate,
        V.branch
    FROM voucher V
    INNER JOIN payment_type pt ON pt.id = V.from
    WHERE V.type = 3

    
    UNION ALL
      SELECT 
        V.date,
        'Transfer' AS incidenttype,
        Vd.to AS payment_type,
        pt.name AS payment_method,
        AES_DECRYPT(V.voucher_id, '{$encryption_key}') AS invoice_no,
        CAST(AES_DECRYPT(Vd.amount, '{$encryption_key}') AS DECIMAL(18,2)) AS grandTotal,
        AES_DECRYPT(V.type2, '{$encryption_key}') AS type2,
        V.date as createddate,
        V.branch
    FROM voucher_details Vd
    INNER JOIN voucher V ON vd.pid = V.id
    INNER JOIN payment_type pt ON pt.id = Vd.to
    WHERE V.type = 3


    UNION ALL

    SELECT 
        s.date,
        'Sale' AS incidenttype,
        s.payment_type,
        pt.name AS payment_method,
        AES_DECRYPT(s.sale_id, '{$encryption_key}') AS invoice_no,
        CAST(AES_DECRYPT(s.grandTotal, '{$encryption_key}') AS DECIMAL(18,2)) AS grandTotal,
        AES_DECRYPT(s.type2, '{$encryption_key}') AS type2,
        s.createddate,
        s.branch
    FROM sale s
    INNER JOIN payment_type pt ON pt.id = s.payment_type

    UNION ALL

    SELECT 
        p.date,
        'Purchase' AS incidenttype,
        p.payment_type,
        pt.name AS payment_method,
        AES_DECRYPT(p.chalan_no, '{$encryption_key}') AS invoice_no,
        -CAST(AES_DECRYPT(p.grandTotal, '{$encryption_key}') AS DECIMAL(18,2)) AS grandTotal,
        AES_DECRYPT(p.type2, '{$encryption_key}') AS type2,
        p.createddate,
        p.branch
    FROM purchase p
    INNER JOIN payment_type pt ON pt.id = p.payment_type

    UNION ALL

    SELECT 
        pr.rdate AS date,
        'Purchase Return' AS incidenttype,
        pr.payment_type,
        pt.name AS payment_method,
        AES_DECRYPT(pr.purchase_return_id, '{$encryption_key}') AS invoice_no,
        CAST(AES_DECRYPT(pr.grandTotal, '{$encryption_key}') AS DECIMAL(18,2)) AS grandTotal,
        AES_DECRYPT(pr.type2, '{$encryption_key}') AS type2,
        pr.createddate,
        pr.branch
    FROM purchase_return pr
    INNER JOIN payment_type pt ON pt.id = pr.payment_type

    UNION ALL

    SELECT 
        sr.rdate AS date,
        'Sales Return' AS incidenttype,
        sr.payment_type,
        pt.name AS payment_method,
        AES_DECRYPT(sr.sales_return_id, '{$encryption_key}') AS invoice_no,
        -CAST(AES_DECRYPT(sr.grandTotal, '{$encryption_key}') AS DECIMAL(18,2)) AS grandTotal,
        AES_DECRYPT(sr.type2, '{$encryption_key}') AS type2,
        sr.createddate,
        sr.branch
    FROM sales_return sr
    INNER JOIN payment_type pt ON pt.id = sr.payment_type

) AS cashbook

WHERE date BETWEEN '$from_date' AND '$to_date'
$sqljoin

ORDER BY createddate DESC
";
        $query = $this->db->query($sql);
        $data  = $query->result_array();

        $_SESSION['cashbook'] =  $data;
        $_SESSION['cb_istype'] =   $this->input->post('istype');
        $_SESSION['cbfrom_date'] = $from_date;
        $_SESSION['cbto_date'] =  $to_date;
        // $_SESSION['cb_istype2'] =  $stocktype;




        echo json_encode($data);
    }

    public function generate_stockreport()
    {
        $page = 1;
        $pdf = new StockReport('L', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Your Name');
        $pdf->SetTitle($_SESSION['header']);
        $pdf->SetSubject('TCPDF Tutorial');
        $pdf->SetKeywords('TCPDF, PDF, columns, example');
        $top_margin = 5;
        $pdf->SetMargins(7, $top_margin, 10);
        $pdf->SetAutoPageBreak(TRUE, 20);
        $pdf->AddPage();
        $pdf->SetFont('helvetica', '', 10);

        $this->header($pdf, $page,  $_SESSION['header'], $_SESSION['sr_istype'], $_SESSION['srfrom_date'], $_SESSION['srto_date']);

        if ($_SESSION['sr_istype2'] == "actualstock") {
            $pdf->Cell(20, 10, '', '', 0, 'C', 0, '', 1);
        }

        if ($_SESSION['sr_istype2'] == "physicalstock") {
            $pdf->Cell(40, 10, '', '', 0, 'C', 0, '', 1);
        }

        $pdf->SetFont('helvetica', 'B', 10);
        $pdf->Cell(35, 10, '', 'TL', 0, 'L', 0, '', 1);
        $pdf->Cell(20, 10, 'Product Infomation', 'T', 0, 'L', 0, '', 1);
        $pdf->Cell(10, 10, '', 'T', 0, 'L', 0, '', 1);
        $pdf->Cell(15, 10, '', 'T', 0, 'R', 0, '', 1);

        if ($_SESSION['sr_istype2'] != "physicalstock") {
            $pdf->Cell(60, 10, 'Actual Stock', 'TL', 0, 'C', 0, '', 1);
        }

        if ($_SESSION['sr_istype2'] != "actualstock") {
            $pdf->Cell(60, 10, 'Physical Stock', 'TL', 0, 'C', 0, '', 1);
        }

        $pdf->Cell(12, 10, '', 'TL', 0, 'L', 0, '', 1);

        $pdf->Cell(30, 10, 'Unit Price', 'TR', 0, 'L', 0, '', 1);

        if ($_SESSION['sr_istype2'] != "physicalstock") {
            $pdf->Cell(12, 10, '', 'TL', 0, 'L', 0, '', 1);

            $pdf->Cell(30, 10, 'Stock Value', 'TR', 0, 'L', 0, '', 1);
        }


        $pdf->Ln(10);
        $pdf->SetFont('helvetica', 'B', 8);

        if ($_SESSION['sr_istype2'] == "actualstock") {
            $pdf->Cell(20, 10, '', '', 0, 'C', 0, '', 1);
        }

        if ($_SESSION['sr_istype2'] == "physicalstock") {
            $pdf->Cell(40, 10, '', '', 0, 'C', 0, '', 1);
        }

        $pdf->MultiCell(15, 10, 'Sl', 'TLB', 'C', 0, 0);
        $pdf->MultiCell(35, 10, 'Product Name', 'TLB', 'C', 0, 0);
        $pdf->MultiCell(30, 10, 'Category', 'TLB', 'C', 0, 0);
        // $pdf->MultiCell(15, 10, 'Unit', 'TLB', 'C', 0, 0);

        if ($_SESSION['sr_istype2'] != "physicalstock") {
            $pdf->MultiCell(20, 10, "In.Qty", 'TLB', 'C', 0, 0);
            $pdf->MultiCell(20, 10, "Out.Qty", 'TLB', 'C', 0, 0);
            $pdf->MultiCell(20, 10, "Avl.Qty", 'TLB', 'C', 0, 0);
        }

        if ($_SESSION['sr_istype2'] != "actualstock") {
            $pdf->MultiCell(20, 10, "In.Qty", 'TLB', 'C', 0, 0);
            $pdf->MultiCell(20, 10, "Out.Qty", 'TLB', 'C', 0, 0);
            $pdf->MultiCell(20, 10, "Avl.Qty", 'TLB', 'C', 0, 0);
        }

        $pdf->MultiCell(21, 10, "Purchase Price", 'TLB', 'C', 0, 0);
        $pdf->MultiCell(21, 10, "Sale Price", 'TLBR', 'C', 0, 0);

        if ($_SESSION['sr_istype2'] != "physicalstock") {
            $pdf->MultiCell(21, 10, "Act.Stock Purchase Val", 'TLB', 'C', 0, 0);
            $pdf->MultiCell(21, 10, "Act.Stock Sale Val", 'TLBR', 'C', 0, 0);
        }

        $data =  $_SESSION['stock_report'];
        $lineHeight = 10;
        $maxY = 270;



        $patotal = 0;
        $total = 0;
        $pdf->SetFont('helvetica', '', 8);
        $i = 1;

        foreach ($data as $row) {
            $pdf->Ln(10);

            if ($_SESSION['sr_istype2'] == "actualstock") {
                $pdf->Cell(20, 10, '', '', 0, 'C', 0, '', 1);
            }

            if ($_SESSION['sr_istype2'] == "physicalstock") {
                $pdf->Cell(40, 10, '', '', 0, 'C', 0, '', 1);
            }

            $pdf->Cell(15, 10, $i, 'TLB', 0, 'C', 0, '', 1);
            $pdf->Cell(35, 10, $row['product_name'], 'TLB', 0, 'C', 0, '', 1);
            $pdf->Cell(30, 10, $row['category_name'], 'TLB', 0, 'C', 0, '', 1);
            // $pdf->Cell(15, 10, $row['unit'], 'TLB', 0, 'C', 0, '', 1);



            if ($_SESSION['sr_istype2'] != "physicalstock") {
                // $pdf->Cell(20, 10, $row['inqty'], 'TLB', 0, 'C', 0, '', 1);
                // $pdf->Cell(20, 10, $row['outqty'], 'TLB', 0, 'C', 0, '', 1);
                // $pdf->Cell(20, 10, $row['avqty'], 'TLB', 0, 'C', 0, '', 1);
                $pdf->MultiCell(20, 10, $row['inqty'], 'TLB', 'C', 0, 0);
                $pdf->MultiCell(20, 10, $row['outqty'], 'TLB', 'C', 0, 0);
                $pdf->MultiCell(20, 10, $row['avqty'], 'TLB', 'C', 0, 0);
            }

            if ($_SESSION['sr_istype2'] != "actualstock") {
                // $pdf->Cell(20, 10, $row['pinqty'], 'TLB', 0, 'C', 0, '', 1);
                // $pdf->Cell(20, 10, $row['poutqty'], 'TLB', 0, 'C', 0, '', 1);
                // $pdf->Cell(20, 10, $row['pavqty'], 'TLB', 0, 'C', 0, '', 1);

                $pdf->MultiCell(20, 10, $row['pinqty'], 'TLB', 'C', 0, 0);
                $pdf->MultiCell(20, 10, $row['poutqty'], 'TLB', 'C', 0, 0);
                $pdf->MultiCell(20, 10, $row['pavqty'], 'TLB', 'C', 0, 0);
            }




            $purchase_price = $row['stockunittype']=="master"?(is_numeric($row['purchase_price']) ? $row['purchase_price'] : 0):(is_numeric($row['subpurchase_price']) ? $row['subpurchase_price'] : 0);
           $sale_price   =$row['stockunittype']=="master"?(is_numeric($row['sale_price']) ? $row['sale_price'] : 0):(is_numeric($row['subsale_price']) ? $row['subsale_price'] : 0);
            $avqty   = is_numeric($row['avqtymain']) ? $row['avqtymain'] : 0;

            $total_purchase = $purchase_price * $avqty;
            $total_sale     = $sale_price * $avqty;
            
            $pdf->Cell(21, 10, number_format($purchase_price, 2), 'TLB', 0, 'C');
            $pdf->Cell(21, 10, number_format($sale_price, 2), 'TLBR', 0, 'C');
            
            if ($_SESSION['sr_istype2'] != "physicalstock") {
                $pdf->Cell(21, 10, number_format($total_purchase, 2), 'TLB', 0, 'C');
                $pdf->Cell(21, 10, number_format($total_sale, 2), 'TLBR', 0, 'C');
            }
            $i = $i + 1;
        }




        $date = date('Y-m-d');
        $filename =  $_SESSION['header'] . "_$date.pdf";
        $pdf->Output($filename, 'I');
    }



    public function generate_livestockreport()
    {
        $page = 1;
        $pdf = new StockReport('L', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Your Name');
        $pdf->SetTitle($_SESSION['header']);
        $pdf->SetSubject('TCPDF Tutorial');
        $pdf->SetKeywords('TCPDF, PDF, columns, example');
        $top_margin = 5;
        $pdf->SetMargins(15, $top_margin, 10);
        $pdf->SetAutoPageBreak(TRUE, 20);
        $pdf->AddPage();
        $pdf->SetFont('helvetica', '', 10);

        $this->header($pdf, $page,  $_SESSION['header'], $_SESSION['sr_istype'], $_SESSION['srfrom_date'], $_SESSION['srto_date']);

        if ($_SESSION['sr_istype2'] == "actualstock") {
            $pdf->Cell(20, 10, '', '', 0, 'C', 0, '', 1);
        }

        if ($_SESSION['sr_istype2'] == "physicalstock") {
            $pdf->Cell(40, 10, '', '', 0, 'C', 0, '', 1);
        }

        $pdf->SetFont('helvetica', 'B', 10);
        $pdf->Cell(50, 10, '', 'TL', 0, 'L', 0, '', 1);
        $pdf->Cell(35, 10, 'Product Infomation', 'T', 0, 'L', 0, '', 1);
        $pdf->Cell(20, 10, '', 'T', 0, 'L', 0, '', 1);
        $pdf->Cell(15, 10, '', 'T', 0, 'R', 0, '', 1);

        if ($_SESSION['sr_istype2'] != "physicalstock") {
            $pdf->Cell(24, 10, 'Actual Stock', 'TL', 0, 'C', 0, '', 1);
        }

        if ($_SESSION['sr_istype2'] != "actualstock") {
            $pdf->Cell(24, 10, 'Physical Stock', 'TL', 0, 'C', 0, '', 1);
        }


        $pdf->Cell(15, 10, '', 'TL', 0, 'L', 0, '', 1);

        $pdf->Cell(35, 10, 'Unit Price', 'TR', 0, 'L', 0, '', 1);

        if ($_SESSION['sr_istype2'] != "physicalstock") {
            $pdf->Cell(15, 10, '', 'TL', 0, 'L', 0, '', 1);

            $pdf->Cell(35, 10, 'Stock Value', 'TR', 0, 'L', 0, '', 1);
        }



        $pdf->Ln(10);
        $pdf->SetFont('helvetica', 'B', 8);

        if ($_SESSION['sr_istype2'] == "actualstock") {
            $pdf->Cell(20, 10, '', '', 0, 'C', 0, '', 1);
        }

        if ($_SESSION['sr_istype2'] == "physicalstock") {
            $pdf->Cell(40, 10, '', '', 0, 'C', 0, '', 1);
        }

        $pdf->MultiCell(15, 10, 'Sl', 'TLB', 'C', 0, 0);
        $pdf->MultiCell(50, 10, 'Product Name', 'TLB', 'C', 0, 0);
        $pdf->MultiCell(40, 10, 'Category', 'TLB', 'C', 0, 0);
        $pdf->MultiCell(15, 10, 'Unit', 'TLB', 'C', 0, 0);

        if ($_SESSION['sr_istype2'] != "physicalstock") {
            $pdf->MultiCell(24, 10, "Avl.Qty", 'TLB', 'C', 0, 0);
        }

        if ($_SESSION['sr_istype2'] != "actualstock") {
            $pdf->MultiCell(24, 10, "Avl.Qty", 'TLB', 'C', 0, 0);
        }

        $pdf->MultiCell(25, 10, "Purchase Price", 'TLB', 'C', 0, 0);
        $pdf->MultiCell(25, 10, "Sale Price", 'TLBR', 'C', 0, 0);

        if ($_SESSION['sr_istype2'] != "physicalstock") {
            $pdf->MultiCell(25, 10, "Act.Stock Purchase Val", 'TLB', 'C', 0, 0);
            $pdf->MultiCell(25, 10, "Act.Stock Sale Val", 'TLBR', 'C', 0, 0);
        }

        $data =  $_SESSION['stock_report'];
        $lineHeight = 10;
        $maxY = 270;



        $patotal = 0;
        $total = 0;
        $pdf->SetFont('helvetica', '', 8);
        $i = 1;

        foreach ($data as $row) {
            $pdf->Ln(10);

            if ($_SESSION['sr_istype2'] == "actualstock") {
                $pdf->Cell(20, 10, '', '', 0, 'C', 0, '', 1);
            }

            if ($_SESSION['sr_istype2'] == "physicalstock") {
                $pdf->Cell(40, 10, '', '', 0, 'C', 0, '', 1);
            }

            $pdf->Cell(15, 10, $i, 'TLB', 0, 'C', 0, '', 1);
            $pdf->Cell(50, 10, $row['product_name'], 'TLB', 0, 'C', 0, '', 1);
            $pdf->Cell(40, 10, $row['category_name'], 'TLB', 0, 'C', 0, '', 1);
            $pdf->Cell(15, 10, $row['unit'], 'TLB', 0, 'C', 0, '', 1);



            if ($_SESSION['sr_istype2'] != "physicalstock") {
                $pdf->Cell(24, 10, $row['avqty'], 'TLB', 0, 'C', 0, '', 1);
            }

            if ($_SESSION['sr_istype2'] != "actualstock") {
                $pdf->Cell(24, 10, $row['pavqty'], 'TLB', 0, 'C', 0, '', 1);
            }




            // $pdf->Cell(25, 10, number_format($row['purchase_price'], 2), 'TLB', 0, 'C', 0, '', 1);
            // $pdf->Cell(25, 10, number_format($row['sale_price'], 2), 'TLBR', 0, 'C', 0, '', 1);
            // $total_purchase =  $row['purchase_price'] * $row['avqty'];
            // $total_sale =  $row['sale_price'] * $row['avqty'];

            // if ($_SESSION['sr_istype2'] != "physicalstock") {


            //     $pdf->Cell(25, 10, number_format($total_purchase, 2), 'TLB', 0, 'C', 0, '', 1);
            //     $pdf->Cell(25, 10, number_format($total_sale, 2), 'TLBR', 0, 'C', 0, '', 1);
            // }
            $i = $i + 1;
        }




        $date = date('Y-m-d');
        $filename =  $_SESSION['header'] . "_$date.pdf";
        $pdf->Output($filename, 'I');
    }








    public function generate_cashbook()
    {
        $page = 1;
        $pdf = new SalesReportInvoicewise('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Your Name');
        $pdf->SetTitle('Cash Book');
        $pdf->SetSubject('TCPDF Tutorial');
        $pdf->SetKeywords('TCPDF, PDF, columns, example');
        $top_margin = 5;
        $pdf->SetMargins(15, $top_margin, 10);
        $pdf->SetAutoPageBreak(TRUE, 20);
        $pdf->AddPage();
        $pdf->SetFont('helvetica', '', 10);

        $this->header($pdf, $page, "Cash Book", $_SESSION['cb_istype'], $_SESSION['cbfrom_date'], $_SESSION['cbto_date']);


        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->Cell(37, 10, 'Date', 'TB', 0, 'L', 0, '', 1);
        $pdf->Cell(37, 10, 'Incident', 'TB', 0, 'L', 0, '', 1);
        $pdf->Cell(37, 10, 'Payment Method', 'TB', 0, 'C', 0, '', 1);
        $pdf->Cell(45, 10, 'Invoice No', 'TB', 0, 'C', 0, '', 1);
        $pdf->Cell(37, 10, 'Amount', 'TB', 0, 'R', 0, '', 1);




        $pdf->Ln(10);

        $data =  $_SESSION['cashbook'];
        $lineHeight = 10;
        $maxY = 270;



        $patotal = 0;
        $total = 0;

        foreach ($data as $row) {



            if ($pdf->GetY() + $lineHeight > $maxY) {
                $pdf->updatePageTotal($patotal);
                $patotal = 0;
                $pdf->AddPage();
                $page = $page + 1;
                $this->header($pdf, $page, "Cash Book", $_SESSION['cb_istype'], $_SESSION['cbfrom_date'], $_SESSION['cbto_date']);
                $pdf->SetFont('helvetica', 'B', 12);
                $pdf->Cell(37, 10, 'Date', 'TB', 0, 'L', 0, '', 1);
                $pdf->Cell(37, 10, 'Incident', 'TB', 0, 'L', 0, '', 1);
                $pdf->Cell(37, 10, 'Payment Method', 'TB', 0, 'L', 0, '', 1);
                $pdf->Cell(45, 10, 'Invoice No', 'TB', 0, 'L', 0, '', 1);
                $pdf->Cell(37, 10, 'Amount', 'TB', 0, 'R', 0, '', 1);
                $pdf->Ln(10);
            }
            $patotal = $patotal + $row['grandTotal'];
            $total = $total + $row['grandTotal'];


            $pdf->SetFont('', '', 10);
            $pdf->Cell(37, 8, $row['date'], 0, 0, 'L');
            $pdf->Cell(37, 8,  $row['incidenttype'], 0, 0, 'L');
            $pdf->Cell(37, 8,  $row['payment_method'], 0, 0, 'C');
            $pdf->Cell(45, 8,  $row['invoice_no'], 0, 0, 'C');

            $grandtotal = number_format($row['grandTotal'], 2);

            $pdf->Cell(37, 8,  $grandtotal, 0, 0, 'R');

            $pdf->Ln(8);



            // $pdf->Cell(40, 8, number_format($row['total'], 2), 0, 1, 'R');
        }
        $pdf->SetFont('', 'B', 12);
        $pdf->Cell(50, 10, "Total Amount:", 'TB', 0, 'L');
        $pdf->Cell(108, 10, "", 'TB', 0, 'L');
        $pdf->Cell(35, 10, number_format($total, 2), 'TB', 1, 'R');

        $pdf->updatePageTotal($patotal);



        $date = date('Y-m-d');
        $filename = "Cash Book_$date.pdf";
        $pdf->Output($filename, 'I');
    }

    public function bdtask_stock_audit_report()
    {
        $encryption_key = Config::$encryption_key;

        if (!$this->permission1->method('stock_audit_report', 'read')->access()) {
            $previous_url = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : base_url();
            redirect($previous_url);
        }
        $product_list =$this->report_model->product_list_stock();

        $store_list = $this->report_model->store_list();


        $data = array(
            'title'          => display('stock_audit_report'),
            'product_list'   => $product_list,
            'store_list'     => $store_list,


        );
        $data['module']   = "report";
        $data['page']     = "stock_audit_report";
        echo modules::run('template/layout', $data);
    }


    public function audit_stock_report()
    {
        $from_date = $this->input->post('from_date');
        $to_date  = $this->input->post('to_date');
        $productid = $this->input->post('productid');
        $encryption_key = Config::$encryption_key;
        $storeid  = $this->input->post('storeid');
        $incident = $this->input->post('incident');
        $scenario = $this->input->post('scenario');

        $this->db->select("
                as.product,
                as.date,
                as.scenario,
                as.incident,
                AES_DECRYPT(as.pvoucher, '$encryption_key') AS pvoucher,
                AES_DECRYPT(as.voucher, '$encryption_key') AS voucher,
                as.pid,
                as.store,
                pi.product_name,
                AES_DECRYPT(as.astockstr, '$encryption_key') AS astockstr,
                AES_DECRYPT(as.pstockstr, '$encryption_key') AS pstockstr,
                AES_DECRYPT(as.astock, '$encryption_key') AS astock,
                AES_DECRYPT(as.pstock, '$encryption_key') AS pstock,
                s.name as storename,
                as.lastupdateddate,cr.conversion_ratio,u1.unit_name as sub,u2.unit_name as master
        ", false);
        $this->db->from('audit_stock as');
        $this->db->join('product_information pi', 'pi.id = as.product', 'inner');
        $this->db->join('store s', 's.id = as.store', 'inner');
        $this->db->join('subunit_product sp', 'sp.product_id = pi.id AND sp.first = 1', 'left');
        $this->db->join('units u1', 'u1.unit_id = sp.unit_id', 'left');
        $this->db->join('units u2', 'u2.unit_id = pi.unit', 'inner');
        $this->db->join('conversion_ratio cr', 'cr.product = pi.id AND u1.unit_id = cr.subunit', 'left');

        if (!empty($from_date)) {
            $this->db->where('as.date >=', $from_date);
        }
        
        if (!empty($to_date)) {
            $this->db->where('as.date <=', $to_date);
        }
        
        if (!empty($productid)) {
            $this->db->where('as.product', $productid);
        }
        
        if (!empty($storeid)) {
            $this->db->where('as.store', $storeid);
        }
        
        if (!empty($incident)) {
            $this->db->where('as.incident', $incident);
        }
        
        if (!empty($scenario)) {
            $sce="";
            if($scenario=="purchaseinvoice"){
                $sce="Purchase Invoice";
            }else if($scenario=="saleinvoice"){
                $sce="Sale Invoice";
            }else if($scenario=="saleinvoice"){
                $sce="Stock";
            }else if($scenario=="GRN"){
                $sce="GRN";
            }else if($scenario=="GDN"){
                $sce="GDN";
            }else if($scenario=="purchasereturn"){
                $sce="Purchase Return";
            }
            $this->db->where('as.scenario', $sce);
        }
        $this->db->order_by("as.lastupdateddate", "asc");



        $query = $this->db->get();
        $result1 = $query->result_array();


        $this->db->select("
               u.unit_name,u.unit_id
        ", false);
        $this->db->from('product_information pi');
        $this->db->join('units u', 'u.unit_id = pi.unit', 'inner');
        $this->db->where('pi.id', $productid);

        $query = $this->db->get();
        $result2 = $query->result_array();

        $this->db->select("
                u.unit_name,u.unit_id,cr.conversion_ratio
        ", false);
        $this->db->from('subunit_product sp');
        $this->db->join('units u', 'u.unit_id = sp.unit_id', 'inner');
        $this->db->join('conversion_ratio cr', 'cr.product = sp.product_id AND u.unit_id = cr.subunit', 'left');
        $this->db->where('sp.product_id', $productid);
        $query = $this->db->get();
        $result3 = $query->result_array();


        echo json_encode([
            'audit_stock' => $result1,
            'masterunit'     => $result2,
            'subunit'     => $result3
        ]);
    }

    public function audit_stock_report_sync()
    {
        $encryption_key = Config::$encryption_key;

        $this->db->query("CALL sp_insert_stock_audit(?)", [
            $encryption_key
        ]);
        echo json_encode("Success");
    }

    public function generate_auditreport()
    {
        $data = json_decode($_POST['aulist'], true);
        $masterunit = json_decode($_POST['masterunit'], true);
        $subunit = json_decode($_POST['subunit'], true);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'Stock Audit Report  ' . $_POST['fdate'] . " To " . $_POST['tdate']);
        $sheet->mergeCells("A1:J1");
        $sheet->getStyle("A1:J1")->getFont()->setBold(true);
        $sheet->getStyle("A1:J1")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle("A1:J1")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);


        $sheet->setCellValue('A2', 'Product Name  ' . $data[0]['product_name']);
        $sheet->mergeCells("A2:J2");
        $sheet->getStyle("A2:J2")->getFont()->setBold(true);
        $sheet->getStyle("A2:J2")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle("A2:J2")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

        $startRow = 3;


        $sheet->setCellValue('A' . $startRow, "Master Stock Unit");
        $sheet->setCellValue('B' . $startRow, $masterunit[0]['unit_name']);


        $startRow++;

        foreach ($subunit as $row) {
            $sheet->setCellValue('A' . $startRow, "Substock Unit = ". $row['unit_name']);
            $sheet->setCellValue('B' . $startRow, "Conversion Ratio = ".$row['conversion_ratio']);
            $sheet->getStyle('A' . $startRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT)->setVertical(Alignment::VERTICAL_TOP)->setWrapText(true);
            $sheet->getStyle('B' . $startRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT)->setVertical(Alignment::VERTICAL_TOP)->setWrapText(true);

            $startRow++;
        }

        $startRow++;

        // === HEADER ROWS ===
        $headers = ['Date', 'Scenario', 'Incident','Parent Voucher', 'Voucher No', 'Store', 'Actual Stock', 'Physical Stock','Actual Stock (Std.)','Physical Stock (Std.)'];
        $columnWidths = [23, 35, 30, 17, 17, 14, 20, 20,19, 19];

        //header
        $sheet->fromArray($headers, NULL, 'A'.$startRow);

        foreach ($headers as $index => $header) {
            $colLetter = Coordinate::stringFromColumnIndex($index + 1);
            $sheet->getColumnDimension($colLetter)->setWidth($columnWidths[$index]);
        }

        $range = "A{$startRow}:J{$startRow}";
         $sheet->getStyle($range)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        $sheet->getStyle($range)->getFont()->setBold(true);


        $scenario = '';
        $voucher_id = '';
        $stockktotal = 0;
        $phystockktotal = 0;

        $startRow++;


        foreach ($data as $row) {
            // if ($scenario == $row['scenario'] && $voucher_id == $row['voucher']) {
            //     $startRow = $startRow - 1;

            //     $storecell = $sheet->getCell('F' . $startRow)->getValue() . "\n" . $row['storename'];
            //     $stockcell = $sheet->getCell('G' . $startRow)->getValue() . "\n" . $row['astock'];
            //     $phystockcell = $sheet->getCell('H' . $startRow)->getValue() . "\n" . $row['pstock'];
            //     $astockstr = $sheet->getCell('I' . $startRow)->getValue() . "\n" . $row['astockstr'];
            //     $pstockstr = $sheet->getCell('J' . $startRow)->getValue() . "\n" . $row['pstockstr'];


            //     $sheet->setCellValue('F' . $startRow, $storecell);
            //     $sheet->setCellValue('G' . $startRow, $stockcell);
            //     $sheet->setCellValue('H' . $startRow, $phystockcell);
            //     $sheet->setCellValue('I' . $startRow, $astockstr);
            //     $sheet->setCellValue('J' . $startRow, $pstockstr);

            //     $sheet->getStyle('F' . $startRow)->getAlignment()->setWrapText(true);
            // } else {
                $sheet->setCellValue('A' . $startRow, $row['date']);
                $sheet->setCellValue('B' . $startRow,$row['scenario']);

                if ($row['scenario'] == "purchase") {
                    $sheet->setCellValue('B' . $startRow, "Purchase Invoice");
                }

                if ($row['scenario'] == "sale") {
                    $sheet->setCellValue('B' . $startRow, "Sale Invoice");
                }

                // if ($row['scenario'] == "stock") {
                //     $sheet->setCellValue('C' . $startRow, "Stock");
                // }

                // if ($row['scenario'] == "GRN") {
                //     $sheet->setCellValue('C' . $startRow, "GRN");
                // }

                // if ($row['scenario'] == "GDN") {
                //     $sheet->setCellValue('C' . $startRow, "GDN");
                // }
                $sheet->setCellValue('C' . $startRow, $row['incident']);

                if ($row['incident'] == "localpurchase") {
                    $sheet->setCellValue('C' . $startRow, "Local Purchase");
                }

                if ($row['incident'] == "internationalpurchase") {
                    $sheet->setCellValue('C' . $startRow, "International Purchase");
                }

                if ($row['incident'] == "sale") {
                    $sheet->setCellValue('C' . $startRow, "Sale");
                }

                if ($row['incident'] == "wholesale") {
                    $sheet->setCellValue('C' . $startRow, "Whole Sale");
                }

                if ($row['incident'] == "openingstock") {
                    $sheet->setCellValue('C' . $startRow, "Opening Stock");
                }

                if ($row['incident'] == "opening_stock") {
                    $sheet->setCellValue('C' . $startRow, "Opening Stock");
                }

                if ($row['incident'] == "storetransfer") {
                    $sheet->setCellValue('C' . $startRow, "Store Transfer");
                }

                if ($row['incident'] == "stockdisposal") {
                    $sheet->setCellValue('C' . $startRow, "Stock Disposal");
                }
                if ($row['incident'] == "stockadjustment") {
                    $sheet->setCellValue('C' . $startRow, "Stock Adjustment");
                }

                if ($row['incident'] == "purchase") {
                    $sheet->setCellValue('C' . $startRow, "Purchase");
                }

                if ($row['incident'] == "salesreturn") {
                    $sheet->setCellValue('C' . $startRow, "Sales Return");
                }

                if ($row['incident'] == "Sales Return") {
                    $sheet->setCellValue('C' . $startRow, "Sales Return");
                }

                if ($row['incident'] == "storetransfer") {
                    $sheet->setCellValue('C' . $startRow, "Store Transfer");
                }
                if ($row['incident'] == "purchasereturn") {
                    $sheet->setCellValue('C' . $startRow, "Purchase Return");
                }

                if ($row['incident'] == "stockdisposal") {
                    $sheet->setCellValue('C' . $startRow, "Stock Disposal");
                }


                $sheet->setCellValue('D' . $startRow, $row['pvoucher']);
                $sheet->setCellValue('E' . $startRow, $row['voucher']);
                $sheet->setCellValue('F' . $startRow, $row['storename']);
                $sheet->setCellValue('G' . $startRow, $row['astockstr']);
                $sheet->setCellValue('H' . $startRow, $row['pstockstr']);
                $sheet->setCellValue('I' . $startRow, $row['astock']);
                $sheet->setCellValue('J' . $startRow, $row['pstock']);
            // }
            // $sheet->getStyle('J' . $startRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_TOP)->setWrapText(true);
            // $sheet->getStyle('J' . $startRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_TOP)->setWrapText(true);

            $range = "A$startRow:J$startRow";
            $sheet->getStyle($range)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

            $scenario = $row['scenario'];
            $voucher_id = $row['voucher'];

            $phystockktotal += (float)($row['pstock'] ?? 0);
            $stockktotal    += (float)($row['astock'] ?? 0);

            $startRow++;
        }


        $sheet->setCellValue('A' . $startRow, 'Available Quantity');
        $startCol = 1;
        $endCol = 6;
        $row = $startRow;
        $startLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($startCol);
        $endLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($endCol);
        $mergeRange = "{$startLetter}{$row}:{$endLetter}{$row}";
        $sheet->mergeCells($mergeRange);

    
        $sheet->setCellValue('G' . $startRow, $_POST['astock_totalstr']);
        $sheet->setCellValue('H' . $startRow, $_POST['pstock_totalstr']);
        $sheet->setCellValue('I' . $startRow, $stockktotal);
        $sheet->setCellValue('J' . $startRow, $phystockktotal);
        $sheet->getStyle('G' . $startRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_TOP)->setWrapText(true);
        $sheet->getStyle('H' . $startRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_TOP)->setWrapText(true);
        $sheet->getStyle('I' . $startRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_TOP)->setWrapText(true);
        $sheet->getStyle('J' . $startRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_TOP)->setWrapText(true);


        $range = "A$startRow:J$startRow";
        $sheet->getStyle($range)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        $sheet->getStyle($range)->getFont()->setBold(true);


        // $sheet->getStyle($mergeRange)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);



        // === OUTPUT DOWNLOAD ===
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="OT_Reportcheck.xlsx"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }




    public function bdtask_sales_order_report()
    { 
        $data['title']      = display('sales_order_report');
        $data['module']     = "report";
        $data['page']       = "sales_order_report";
        echo modules::run('template/layout', $data);
    }

    public function bdtask_sales_return_report()
    { 
        $data['title']      = display('sales_return_report');
        $data['module']     = "report";
        $data['page']       = "sales_return_report";
        echo modules::run('template/layout', $data);
    }

    public function bdtask_purchase_order_report()
    { 
        $data['title']      = display('purchase_order_report');
        $data['module']     = "report";
        $data['page']       = "purchase_order_report";
        echo modules::run('template/layout', $data);
    }

    public function bdtask_purchase_return_report()
    { 
        $data['title']      = display('purchase_return_report');
        $data['module']     = "report";
        $data['page']       = "purchase_return_report";
        echo modules::run('template/layout', $data);
    }

    public function bdtask_grn_report()
    { 
        $data['title']      = display('grn_report');
        $data['module']     = "report";
        $data['page']       = "grn_report";
        echo modules::run('template/layout', $data);
    }

    public function bdtask_gdn_report()
    { 
        $data['title']      = display('gdn_report');
        $data['module']     = "report";
        $data['page']       = "gdn_report";
        echo modules::run('template/layout', $data);
    }

    public function bdtask_gross_profit_report()
    { 
        $data['title']      = display('gross_profit_report');
        $data['module']     = "report";
        $data['page']       = "gross_profit_report";
        echo modules::run('template/layout', $data);
    }

    public function bdtask_gross_profit_category_report()
    { 
        $data['title']      = display('gross_profit_category_report');
        $data['product_list']  = $this->report_model->product_list();
        $data['category_list']  = $this->report_model->category_list_product();
        $data['module']     = "report";
        $data['page']       = "gross_profit_category_report";
        echo modules::run('template/layout', $data);
    }

    public function getCustomersForReport()
    {
        $encryption_key = Config::$encryption_key;
        $this->db->select("customer_id, AES_DECRYPT(customer_name, '{$encryption_key}') AS customer_name");
        $this->db->from('customer_information');
        $this->db->order_by('customer_name', 'ASC');
        $query = $this->db->get();
        $result = $query->result_array();
        echo json_encode($result);
    }

    public function getSuppliersForReport()
    {
        $encryption_key = Config::$encryption_key;
        $this->db->select("supplier_id, AES_DECRYPT(supplier_name, '{$encryption_key}') AS supplier_name");
        $this->db->from('supplier_information');
        $this->db->order_by('supplier_name', 'ASC');
        $query = $this->db->get();
        $result = $query->result_array();
        echo json_encode($result);
    }

    public function purchase_return_reportinvoicewise()
    {
        $from_date   = $this->input->post('from_date');
        $to_date     = $this->input->post('to_date');
        $empid       = $this->input->post('empid');
        $branch      = $this->input->post('branch');
        $supplier_id = $this->input->post('supplier_id');

        $report_data = $this->report_model->purchase_return_reportinvoicewise($from_date, $to_date, $empid, $branch, $supplier_id);
        $_SESSION['purchase_return_reportspri'] = $report_data;
        $_SESSION['prri_istype']                = $this->input->post('istype');
        $_SESSION['prrifrom_date']              = $from_date;
        $_SESSION['prrito_date']                = $to_date;

        echo json_encode($_SESSION['purchase_return_reportspri']);
    }

    public function purchase_order_reportinvoicewise()
    {
        $from_date   = $this->input->post('from_date');
        $to_date     = $this->input->post('to_date');
        $empid       = $this->input->post('empid');
        $branch      = $this->input->post('branch');
        $supplier_id   = $this->input->post('supplier_id');
        $status        = $this->input->post('status');
        $incident_type = $this->input->post('incident_type');

        $report_data = $this->report_model->purchase_order_reportinvoicewise($from_date, $to_date, $empid, $branch, $supplier_id, $status, $incident_type);
        $_SESSION['purchase_order_reportspori'] = $report_data;
        $_SESSION['pori_istype']                = $this->input->post('istype');
        $_SESSION['porifrom_date']              = $from_date;
        $_SESSION['porito_date']                = $to_date;
        $_SESSION['pori_status']                = $status;
        $_SESSION['pori_incident_type']         = $incident_type;

        echo json_encode($_SESSION['purchase_order_reportspori']);
    }

    public function sales_order_reportinvoicewise()
    {
        $from_date   = $this->input->post('from_date');
        $to_date     = $this->input->post('to_date');
        $empid       = $this->input->post('empid');
        $branch      = $this->input->post('branch');
        $customer_id = $this->input->post('customer_id');

        $report_data = $this->report_model->sales_order_reportinvoicewise($from_date, $to_date, $empid, $branch, $customer_id);
        $_SESSION['sale_order_reportsori'] = $report_data;
        $_SESSION['sori_istype']           = $this->input->post('istype');
        $_SESSION['sorifrom_date']         = $from_date;
        $_SESSION['sorito_date']           = $to_date;

        echo json_encode($_SESSION['sale_order_reportsori']);
    }

    public function sales_return_reportinvoicewise()
    {
        try {
            $from_date   = $this->input->post('from_date');
            $to_date     = $this->input->post('to_date');
            $empid       = $this->input->post('empid');
            $branch      = $this->input->post('branch');
            $customer_id = $this->input->post('customer_id');

            $report_data = $this->report_model->sales_return_reportinvoicewise($from_date, $to_date, $empid, $branch, $customer_id);
            $_SESSION['sale_return_reportsrri'] = $report_data;
            $_SESSION['srri_istype']            = $this->input->post('istype');
            $_SESSION['srrifrom_date']          = $from_date;
            $_SESSION['srrito_date']            = $to_date;

            echo json_encode($_SESSION['sale_return_reportsrri']);
        } catch (Throwable $e) {
            http_response_code(200);
            echo json_encode(['error' => $e->getMessage(), 'file' => $e->getFile(), 'line' => $e->getLine()]);
        }
    }

    public function generate_salesreturnreportinvoice()
    {
        $page = 1;
        $pdf = new SalesReportInvoicewise('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Your Name');
        $pdf->SetTitle('Sales Return Report');
        $pdf->SetSubject('TCPDF Tutorial');
        $pdf->SetKeywords('TCPDF, PDF, columns, example');
        $top_margin = 5;
        $pdf->SetMargins(15, $top_margin, 10);
        $pdf->SetAutoPageBreak(TRUE, 20);
        $pdf->AddPage();
        $pdf->SetFont('helvetica', '', 10);

        $this->header($pdf, $page, "Sales Return Report", $_SESSION['srri_istype'], $_SESSION['srrifrom_date'], $_SESSION['srrito_date']);

        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->Cell(35, 10, 'Return Date',   'TB', 0, 'L', 0, '', 1);
        $pdf->Cell(35, 10, 'Return ID',     'TB', 0, 'L', 0, '', 1);
        $pdf->Cell(35, 10, 'Invoice ID',    'TB', 0, 'L', 0, '', 1);
        $pdf->Cell(55, 10, 'Customer Name', 'TB', 0, 'L', 0, '', 1);
        $pdf->Cell(25, 10, 'Amount',        'TB', 0, 'R', 0, '', 1);
        $pdf->Ln(10);

        $data       = isset($_SESSION['sale_return_reportsrri']) ? $_SESSION['sale_return_reportsrri'] : [];
        $lineHeight = 10;
        $maxY       = 270;
        $patotal    = 0;
        $total      = 0;

        foreach ($data as $row) {
            if ($pdf->GetY() + $lineHeight > $maxY) {
                $pdf->updatePageTotal($patotal);
                $patotal = 0;
                $pdf->AddPage();
                $page = $page + 1;
                $this->header($pdf, $page, "Sales Return Report", $_SESSION['srri_istype'], $_SESSION['srrifrom_date'], $_SESSION['srrito_date']);
                $pdf->SetFont('helvetica', 'B', 12);
                $pdf->Cell(35, 10, 'Return Date',   'TB', 0, 'L', 0, '', 1);
                $pdf->Cell(35, 10, 'Return ID',     'TB', 0, 'L', 0, '', 1);
                $pdf->Cell(35, 10, 'Invoice ID',    'TB', 0, 'L', 0, '', 1);
                $pdf->Cell(55, 10, 'Customer Name', 'TB', 0, 'L', 0, '', 1);
                $pdf->Cell(25, 10, 'Amount',        'TB', 0, 'R', 0, '', 1);
                $pdf->Ln(10);
            }
            $total   += (float)$row['total'];
            $patotal += (float)$row['total'];

            $pdf->SetFont('', '', 10);
            $pdf->Cell(35, 8, $row['date'],          0, 0, 'L');
            $pdf->Cell(35, 8, $row['return_id'],     0, 0, 'L');
            $pdf->Cell(35, 8, $row['invoiceno'],     0, 0, 'L');
            $pdf->Cell(55, 8, $row['customer_name'], 0, 0, 'L');
            $pdf->Cell(25, 8, number_format((float)$row['total'], 2), 0, 1, 'R');
        }

        $pdf->SetFont('', 'B', 12);
        $pdf->Cell(50,  10, "Total Amount:", 'TB', 0, 'L');
        $pdf->Cell(100, 10, "",              'TB', 0, 'L');
        $pdf->Cell(35,  10, number_format($total, 2), 'TB', 1, 'R');
        $pdf->updatePageTotal($patotal);

        $date     = date('Y-m-d');
        $filename = "Sales Return Report_$date.pdf";
        $pdf->Output($filename, 'I');
    }

    public function generate_salesorderreportinvoice()
    {
        $page = 1;
        $pdf = new SalesReportInvoicewise('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Your Name');
        $pdf->SetTitle('Sales Order Report');
        $pdf->SetSubject('TCPDF Tutorial');
        $pdf->SetKeywords('TCPDF, PDF, columns, example');
        $top_margin = 5;
        $pdf->SetMargins(15, $top_margin, 10);
        $pdf->SetAutoPageBreak(TRUE, 20);
        $pdf->AddPage();
        $pdf->SetFont('helvetica', '', 10);

        $this->header($pdf, $page, "Sales Order Report", $_SESSION['sori_istype'], $_SESSION['sorifrom_date'], $_SESSION['sorito_date']);

        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->Cell(45, 10, 'Order Date',     'TB', 0, 'L', 0, '', 1);
        $pdf->Cell(40, 10, 'Order No',       'TB', 0, 'L', 0, '', 1);
        $pdf->Cell(60, 10, 'Customer Name',  'TB', 0, 'L', 0, '', 1);
        $pdf->Cell(40, 10, 'Amount',         'TB', 0, 'R', 0, '', 1);
        $pdf->Ln(10);

        $data       = isset($_SESSION['sale_order_reportsori']) ? $_SESSION['sale_order_reportsori'] : [];
        $lineHeight = 10;
        $maxY       = 270;
        $patotal    = 0;
        $total      = 0;

        foreach ($data as $row) {
            if ($pdf->GetY() + $lineHeight > $maxY) {
                $pdf->updatePageTotal($patotal);
                $patotal = 0;
                $pdf->AddPage();
                $page = $page + 1;
                $this->header($pdf, $page, "Sales Order Report", $_SESSION['sori_istype'], $_SESSION['sorifrom_date'], $_SESSION['sorito_date']);
                $pdf->SetFont('helvetica', 'B', 12);
                $pdf->Cell(45, 10, 'Order Date',    'TB', 0, 'L', 0, '', 1);
                $pdf->Cell(40, 10, 'Order No',      'TB', 0, 'L', 0, '', 1);
                $pdf->Cell(60, 10, 'Customer Name', 'TB', 0, 'L', 0, '', 1);
                $pdf->Cell(40, 10, 'Amount',        'TB', 0, 'R', 0, '', 1);
                $pdf->Ln(10);
            }
            $total   += $row['total'];
            $patotal += $row['total'];

            $pdf->SetFont('', '', 10);
            $pdf->Cell(45, 8, $row['date'],          0, 0, 'L');
            $pdf->Cell(40, 8, $row['invoiceno'],     0, 0, 'L');
            $pdf->Cell(60, 8, $row['customer_name'], 0, 0, 'L');
            $pdf->Cell(40, 8, number_format($row['total'], 2), 0, 1, 'R');
        }

        $pdf->SetFont('', 'B', 12);
        $pdf->Cell(50,  10, "Total Amount:", 'TB', 0, 'L');
        $pdf->Cell(100, 10, "",              'TB', 0, 'L');
        $pdf->Cell(35,  10, number_format($total, 2), 'TB', 1, 'R');
        $pdf->updatePageTotal($patotal);

        $date     = date('Y-m-d');
        $filename = "Sales Order Report_$date.pdf";
        $pdf->Output($filename, 'I');
    }

    public function gdn_reportinvoicewise()
    {
        $from_date     = $this->input->post('from_date');
        $to_date       = $this->input->post('to_date');
        $empid         = $this->input->post('empid');
        $store         = $this->input->post('store');
        $incident_type = $this->input->post('incident_type');
        $customer_id   = $this->input->post('customer_id');

        $report_data = $this->report_model->gdn_reportinvoicewise($from_date, $to_date, $empid, $store, $incident_type, $customer_id);
        
        // Debug: Return query and params if no data
        if (!$report_data) {
            $debug = [
                'error' => 'No data found',
                'params' => [
                    'from_date' => $from_date,
                    'to_date' => $to_date,
                    'empid' => $empid,
                    'store' => $store,
                    'incident_type' => $incident_type,
                    'customer_id' => $customer_id
                ],
                'last_query' => $this->db->last_query()
            ];
            echo json_encode($debug);
            return;
        }
        
        $_SESSION['gdn_reportgri']  = $report_data;
        $_SESSION['gri_istype']     = $this->input->post('istype');
        $_SESSION['grifrom_date']   = $from_date;
        $_SESSION['grito_date']     = $to_date;

        echo json_encode($_SESSION['gdn_reportgri']);
    }

    public function grn_reportinvoicewise()
    {
        $from_date     = $this->input->post('from_date');
        $to_date       = $this->input->post('to_date');
        $empid         = $this->input->post('empid');
        $store         = $this->input->post('store');
        $incident_type = $this->input->post('incident_type');
        $supplier_id   = $this->input->post('supplier_id');

        $report_data = $this->report_model->grn_reportinvoicewise($from_date, $to_date, $empid, $store, $incident_type, $supplier_id);

        $_SESSION['grn_reportgrri'] = $report_data;
        $_SESSION['grri_istype']    = $this->input->post('istype');
        $_SESSION['grrifrom_date']  = $from_date;
        $_SESSION['grrito_date']    = $to_date;

        echo json_encode($_SESSION['grn_reportgrri']);
    }

    public function generate_grnreportinvoice()
    {
        $page = 1;
        $pdf = new SalesReportInvoicewise('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->showPageTotal = false;
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Your Name');
        $pdf->SetTitle('GRN Report');
        $pdf->SetSubject('TCPDF Tutorial');
        $pdf->SetKeywords('TCPDF, PDF, columns, example');
        $top_margin = 5;
        $pdf->SetMargins(15, $top_margin, 10);
        $pdf->SetAutoPageBreak(TRUE, 20);
        $pdf->AddPage();
        $pdf->SetFont('helvetica', '', 10);

        $this->header($pdf, $page, "GRN Report", $_SESSION['grri_istype'], $_SESSION['grrifrom_date'], $_SESSION['grrito_date']);

        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->Cell(28, 10, 'GRN Date',       'TB', 0, 'L', 0, '', 1);
        $pdf->Cell(25, 10, 'GRN ID',         'TB', 0, 'L', 0, '', 1);
        $pdf->Cell(30, 10, 'Voucher No',     'TB', 0, 'L', 0, '', 1);
        $pdf->Cell(35, 10, 'Store',          'TB', 0, 'L', 0, '', 1);
        $pdf->Cell(32, 10, 'Incident Type',  'TB', 0, 'L', 0, '', 1);
        $pdf->Cell(35, 10, 'Supplier',       'TB', 0, 'L', 0, '', 1);
        $pdf->Ln(10);

        $data       = isset($_SESSION['grn_reportgrri']) ? $_SESSION['grn_reportgrri'] : [];
        $lineHeight = 10;
        $maxY       = 270;

        foreach ($data as $row) {
            if ($pdf->GetY() + $lineHeight > $maxY) {
                $pdf->AddPage();
                $page = $page + 1;
                $this->header($pdf, $page, "GRN Report", $_SESSION['grri_istype'], $_SESSION['grrifrom_date'], $_SESSION['grrito_date']);
                $pdf->SetFont('helvetica', 'B', 12);
                $pdf->Cell(28, 10, 'GRN Date',       'TB', 0, 'L', 0, '', 1);
                $pdf->Cell(25, 10, 'GRN ID',         'TB', 0, 'L', 0, '', 1);
                $pdf->Cell(30, 10, 'Voucher No',     'TB', 0, 'L', 0, '', 1);
                $pdf->Cell(35, 10, 'Store',          'TB', 0, 'L', 0, '', 1);
                $pdf->Cell(32, 10, 'Incident Type',  'TB', 0, 'L', 0, '', 1);
                $pdf->Cell(35, 10, 'Supplier',       'TB', 0, 'L', 0, '', 1);
                $pdf->Ln(10);
            }

            $pdf->SetFont('', '', 10);
            $pdf->Cell(28, 8, $row['date'],          0, 0, 'L');
            $pdf->Cell(25, 8, $row['grn_id'],        0, 0, 'L');
            $pdf->Cell(30, 8, $row['voucherno'],     0, 0, 'L');
            $pdf->Cell(35, 8, $row['store'],         0, 0, 'L');
            $pdf->Cell(32, 8, $row['type'],          0, 0, 'L');
            $pdf->Cell(35, 8, $row['supplier_name'], 0, 1, 'L');
        }

        $date     = date('Y-m-d');
        $filename = "GRN Report_$date.pdf";
        $pdf->Output($filename, 'I');
    }

    public function generate_gdnreportinvoice()
    {
        $page = 1;
        $pdf = new SalesReportInvoicewise('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->showPageTotal = false;
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Your Name');
        $pdf->SetTitle('GDN Report');
        $pdf->SetSubject('TCPDF Tutorial');
        $pdf->SetKeywords('TCPDF, PDF, columns, example');
        $top_margin = 5;
        $pdf->SetMargins(15, $top_margin, 10);
        $pdf->SetAutoPageBreak(TRUE, 20);
        $pdf->AddPage();
        $pdf->SetFont('helvetica', '', 10);

        $this->header($pdf, $page, "GDN Report", $_SESSION['gri_istype'], $_SESSION['grifrom_date'], $_SESSION['grito_date']);

        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->Cell(28, 10, 'GDN Date',       'TB', 0, 'L', 0, '', 1);
        $pdf->Cell(25, 10, 'GDN ID',         'TB', 0, 'L', 0, '', 1);
        $pdf->Cell(30, 10, 'Voucher No',     'TB', 0, 'L', 0, '', 1);
        $pdf->Cell(35, 10, 'Store',          'TB', 0, 'L', 0, '', 1);
        $pdf->Cell(32, 10, 'Incident Type',  'TB', 0, 'L', 0, '', 1);
        $pdf->Cell(35, 10, 'Customer',       'TB', 0, 'L', 0, '', 1);
        $pdf->Ln(10);

        $data       = isset($_SESSION['gdn_reportgri']) ? $_SESSION['gdn_reportgri'] : [];
        $lineHeight = 10;
        $maxY       = 270;

        foreach ($data as $row) {
            if ($pdf->GetY() + $lineHeight > $maxY) {
                $pdf->AddPage();
                $page = $page + 1;
                $this->header($pdf, $page, "GDN Report", $_SESSION['gri_istype'], $_SESSION['grifrom_date'], $_SESSION['grito_date']);
                $pdf->SetFont('helvetica', 'B', 12);
                $pdf->Cell(28, 10, 'GDN Date',       'TB', 0, 'L', 0, '', 1);
                $pdf->Cell(25, 10, 'GDN ID',         'TB', 0, 'L', 0, '', 1);
                $pdf->Cell(30, 10, 'Voucher No',     'TB', 0, 'L', 0, '', 1);
                $pdf->Cell(35, 10, 'Store',          'TB', 0, 'L', 0, '', 1);
                $pdf->Cell(32, 10, 'Incident Type',  'TB', 0, 'L', 0, '', 1);
                $pdf->Cell(35, 10, 'Customer',       'TB', 0, 'L', 0, '', 1);
                $pdf->Ln(10);
            }

            $pdf->SetFont('', '', 10);
            $pdf->Cell(28, 8, $row['date'],          0, 0, 'L');
            $pdf->Cell(25, 8, $row['gdn_id'],        0, 0, 'L');
            $pdf->Cell(30, 8, $row['voucherno'],     0, 0, 'L');
            $pdf->Cell(35, 8, $row['store'],         0, 0, 'L');
            $pdf->Cell(32, 8, $row['type'],          0, 0, 'L');
            $pdf->Cell(35, 8, $row['customer_name'], 0, 1, 'L');
        }

        $date     = date('Y-m-d');
        $filename = "GDN Report_$date.pdf";
        $pdf->Output($filename, 'I');
    }

     public function generate_purchaseorderreportinvoice()
    {
        $page = 1;
        $pdf = new SalesReportInvoicewise('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Your Name');
        $pdf->SetTitle('Purchase Order Report');
        $pdf->SetSubject('TCPDF Tutorial');
        $pdf->SetKeywords('TCPDF, PDF, columns, example');
        $top_margin = 5;
        $pdf->SetMargins(15, $top_margin, 10);
        $pdf->SetAutoPageBreak(TRUE, 20);
        $pdf->AddPage();
        $pdf->SetFont('helvetica', '', 10);

        $this->header($pdf, $page, "Purchase Order Report", $_SESSION['pori_istype'], $_SESSION['porifrom_date'], $_SESSION['porito_date']);

        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->Cell(35, 10, 'Purchase Date', 'TB', 0, 'L', 0, '', 1);
        $pdf->Cell(35, 10, 'Order No', 'TB', 0, 'L', 0, '', 1);
        $pdf->Cell(50, 10, 'Supplier Name', 'TB', 0, 'L', 0, '', 1);
        $pdf->Cell(30, 10, 'Status', 'TB', 0, 'L', 0, '', 1);
        $pdf->Cell(35, 10, 'Amount', 'TB', 0, 'R', 0, '', 1);
        $pdf->Ln(10);

        $data = isset($_SESSION['purchase_order_reportspori']) ? $_SESSION['purchase_order_reportspori'] : [];
        $lineHeight = 10;
        $maxY = 270;

        $patotal = 0;
        $total = 0;

        foreach ($data as $row) {

            if ($pdf->GetY() + $lineHeight > $maxY) {
                $pdf->updatePageTotal($patotal);
                $patotal = 0;
                $pdf->AddPage();
                $page = $page + 1;
                $this->header($pdf, $page, "Purchase Order Report", $_SESSION['pori_istype'], $_SESSION['porifrom_date'], $_SESSION['porito_date']);
                $pdf->SetFont('helvetica', 'B', 12);
                $pdf->Cell(35, 10, 'Purchase Date', 'TB', 0, 'L', 0, '', 1);
                $pdf->Cell(35, 10, 'Order No', 'TB', 0, 'L', 0, '', 1);
                $pdf->Cell(50, 10, 'Supplier Name', 'TB', 0, 'L', 0, '', 1);
                $pdf->Cell(30, 10, 'Status', 'TB', 0, 'L', 0, '', 1);
                $pdf->Cell(35, 10, 'Amount', 'TB', 0, 'R', 0, '', 1);
                $pdf->Ln(10);
            }
            $patotal = $patotal + $row['total'];
            $total = $total + $row['total'];

            $pdf->SetFont('', '', 10);
            $pdf->Cell(35, 8, $row['date'], 0, 0, 'L');
            $pdf->Cell(35, 8,  $row['invoiceno'], 0, 0, 'L');
            $pdf->Cell(50, 8,  $row['supplier_name'], 0, 0, 'L');
            $pdf->Cell(30, 8,  $row['status_label'], 0, 0, 'L');
            $pdf->Cell(35, 8, number_format($row['total'], 2), 0, 1, 'R');
        }
        $pdf->SetFont('', 'B', 12);
        $pdf->Cell(50, 10, "Total Amount:", 'TB', 0, 'L');
        $pdf->Cell(100, 10, "", 'TB', 0, 'L');
        $pdf->Cell(35, 10, number_format($total, 2), 'TB', 1, 'R');
        $pdf->updatePageTotal($patotal);

        $date = date('Y-m-d');
        $filename = "Purchase Order Report_$date.pdf";
        $pdf->Output($filename, 'I');
    }

    private function renderPurchaseReturnReportHeaderRow($pdf)
    {
        $pdf->SetFont('helvetica', 'B', 11);
        $pdf->Cell(32, 10, 'Return Date', 'TB', 0, 'L', 0, '', 1);
        $pdf->Cell(33, 10, 'Return ID',   'TB', 0, 'L', 0, '', 1);
        $pdf->Cell(33, 10, 'Invoice ID',  'TB', 0, 'L', 0, '', 1);
        $pdf->Cell(57, 10, 'Supplier',    'TB', 0, 'L', 0, '', 1);
        $pdf->Cell(30, 10, 'Amount',      'TB', 0, 'R', 0, '', 1);
        $pdf->Ln(10);
    }

    private function fitPdfText($pdf, $text, $width)
    {
        $value = (string) $text;
        if ($pdf->GetStringWidth($value) <= $width) {
            return $value;
        }

        $suffix = '...';
        while ($value !== '' && $pdf->GetStringWidth($value . $suffix) > $width) {
            $value = substr($value, 0, -1);
        }

        return rtrim($value) . $suffix;
    }

    public function generate_purchasereturnreportinvoice()
    {
        $page = 1;
        $pdf = new SalesReportInvoicewise('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Your Name');
        $pdf->SetTitle('Purchase Return Report');
        $pdf->SetSubject('TCPDF Tutorial');
        $pdf->SetKeywords('TCPDF, PDF, columns, example');
        $top_margin = 5;
        $pdf->SetMargins(15, $top_margin, 10);
        $pdf->SetAutoPageBreak(TRUE, 20);
        $pdf->AddPage();
        $pdf->SetFont('helvetica', '', 10);

        $this->header($pdf, $page, "Purchase Return Report", $_SESSION['prri_istype'], $_SESSION['prrifrom_date'], $_SESSION['prrito_date']);

        $this->renderPurchaseReturnReportHeaderRow($pdf);

        $data       = isset($_SESSION['purchase_return_reportspri']) ? $_SESSION['purchase_return_reportspri'] : [];
        $lineHeight = 8;
        $maxY       = 270;
        $patotal    = 0;
        $total      = 0;

        foreach ($data as $row) {
            if ($pdf->GetY() + $lineHeight > $maxY) {
                $pdf->updatePageTotal($patotal);
                $patotal = 0;
                $pdf->AddPage();
                $page = $page + 1;
                $this->header($pdf, $page, "Purchase Return Report", $_SESSION['prri_istype'], $_SESSION['prrifrom_date'], $_SESSION['prrito_date']);
                $this->renderPurchaseReturnReportHeaderRow($pdf);
            }

            $amount   = (float) $row['amount'];
            $total   += $amount;
            $patotal += $amount;

            $pdf->SetFont('', '', 10);
            $pdf->Cell(32, 8, $this->fitPdfText($pdf, $row['return_date'], 30), 0, 0, 'L');
            $pdf->Cell(33, 8, $this->fitPdfText($pdf, $row['return_id'], 31), 0, 0, 'L');
            $pdf->Cell(33, 8, $this->fitPdfText($pdf, $row['invoice_id'], 31), 0, 0, 'L');
            $pdf->Cell(57, 8, $this->fitPdfText($pdf, $row['supplier_name'], 55), 0, 0, 'L');
            $pdf->Cell(30, 8, number_format($amount, 2), 0, 1, 'R');
        }

        $pdf->SetFont('', 'B', 12);
        $pdf->Cell(155, 10, "Total Amount:", 'TB', 0, 'L');
        $pdf->Cell(30, 10, number_format($total, 2), 'TB', 1, 'R');
        $pdf->updatePageTotal($patotal);

        $date     = date('Y-m-d');
        $filename = "Purchase Return Report_$date.pdf";
        $pdf->Output($filename, 'I');
    }

    public function bdtask_todays_service_report()
    {
        // $sales_report = $this->report_model->todays_sales_report();
        $sales_amount = 0;
        if (!$this->permission1->method('service_report', 'read')->access()) {
            $previous_url = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : base_url();
            redirect($previous_url);
        }
        $data = array(
            'title'        => display('service_report'),
            // 'sales_amount' => number_format($sales_amount, 2, '.', ','),
        );
        $data['module']   = "report";
        $data['page']     = "servicereport_invoicewise";
        echo modules::run('template/layout', $data);
    }

    public function service_reportinvoicewise()
    {
        $from_date = $this->input->post('from_date');
        $to_date  = $this->input->post('to_date');
        $empid = $this->input->post('empid');
        $branch = $this->input->post('branch');
        $customer_id = $this->input->post('customer_id');

        $report_data = $this->report_model->service_reportinvoicewise($from_date, $to_date, $empid, $branch, $customer_id);
        $_SESSION['service_reportsri'] =  $report_data;
        $_SESSION['seri_istype'] =   $this->input->post('istype');
        $_SESSION['serifrom_date'] = $from_date;
        $_SESSION['serito_date'] =  $to_date;


        echo json_encode($_SESSION['service_reportsri']);
    }
    public function generate_servicereportinvoice()
    {
        $page = 1;
        $pdf = new SalesReportInvoicewise('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Your Name');
        $pdf->SetTitle('Service Report');
        $pdf->SetSubject('TCPDF Tutorial');
        $pdf->SetKeywords('TCPDF, PDF, columns, example');
        $top_margin = 5;
        $pdf->SetMargins(15, $top_margin, 10);
        $pdf->SetAutoPageBreak(TRUE, 20);
        $pdf->AddPage();
        $pdf->SetFont('helvetica', '', 10);

        $this->header($pdf, $page, "Service Report", $_SESSION['seri_istype'], $_SESSION['serifrom_date'], $_SESSION['serito_date']);


        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->Cell(30, 10, 'Service Date', 'TB', 0, 'L', 0, '', 1);
        $pdf->Cell(30, 10, 'EOD Date',    'TB', 0, 'L', 0, '', 1);
        $pdf->Cell(40, 10, 'Invoice No', 'TB', 0, 'L', 0, '', 1);
        $pdf->Cell(50, 10, 'Customer Name', 'TB', 0, 'L', 0, '', 1);
        $pdf->Cell(40, 10, 'Amount', 'TB', 0, 'R', 0, '', 1);
        $pdf->Ln(10);

        $data = isset($_SESSION['service_reportsri']) ? $_SESSION['service_reportsri'] : [];
        $lineHeight = 10;
        $maxY = 270;

        $patotal = 0;
        $total = 0;
        foreach ($data as $row) {

            if ($pdf->GetY() + $lineHeight > $maxY) {
                $pdf->updatePageTotal($patotal);
                $patotal = 0;
                $pdf->AddPage();
                $page = $page + 1;
                $this->header($pdf, $page, "Service Report", $_SESSION['sri_istype'], $_SESSION['srifrom_date'], $_SESSION['srito_date']);
                $pdf->SetFont('helvetica', 'B', 12);
                $pdf->Cell(30, 10, 'Service Date', 'TB', 0, 'L', 0, '', 1);
                $pdf->Cell(30, 10, 'EOD Date',    'TB', 0, 'L', 0, '', 1);
                $pdf->Cell(40, 10, 'Invoice No', 'TB', 0, 'L', 0, '', 1);
                $pdf->Cell(50, 10, 'Customer Name', 'TB', 0, 'L', 0, '', 1);
                $pdf->Cell(40, 10, 'Amount', 'TB', 0, 'R', 0, '', 1);
                $pdf->Ln(10);
            }
            $total = $total + $row['total'];
            $patotal = $patotal + $row['total'];

            $pdf->SetFont('', '', 10);
            $pdf->Cell(30, 8, $row['date'], 0, 0, 'L');
             $pdf->Cell(30, 8, $row['eod_date'], 0, 0, 'L');
            $pdf->Cell(40, 8,  $row['invoiceno'], 0, 0, 'L');
            $pdf->Cell(50, 8,  $row['customer_name'], 0, 0, 'L');
            $pdf->Cell(40, 8, number_format($row['total'], 2), 0, 1, 'R');
        }
        $pdf->SetFont('', 'B', 12);
        $pdf->Cell(40, 10, "Total Amount:", 'TB', 0, 'L');
        $pdf->Cell(110, 10, "", 'TB', 0, 'L');
        $pdf->Cell(40, 10, number_format($total, 2), 'TB', 1, 'R');
        $pdf->updatePageTotal($patotal);

        $date = date('Y-m-d');
        $filename = "Service Report_$date.pdf";
        $pdf->Output($filename, 'I');
    }

    public function gross_profit_report()
    {
        $from_date = $this->input->post('from_date');
        $to_date  = $this->input->post('to_date');
        $empid = $this->input->post('empid');
        $encryption_key = Config::$encryption_key;

        $empid   = ($empid === '' || $empid === 'null') ? null : $empid;

        // $report_data = $this->report_model->sales_reportinvoicewise($from_date, $to_date, $empid, $encryption_key);
        $query = $this->db->query("CALL GetGrossProfitReport(?, ?, ?, ?)", [
            $from_date,
            $to_date,
            $encryption_key,
            $empid
        ]);

        if ($query->num_rows() > 0) {
            $report_data = $query->result_array();
        } else {
            $report_data = [];
        }
        $_SESSION['gross_profit_report_data'] =  $report_data;
        $_SESSION['gpr_istype'] =   $this->input->post('istype');
        $_SESSION['gprfrom_date'] = $from_date;
        $_SESSION['gprto_date'] =  $to_date;


        echo json_encode($_SESSION['gross_profit_report_data']);
    }

  
    public function generate_gross_profit_report()
    {
        $page = 1;
        $pdf = new StockReport('P', 'mm', 'A4', true, 'UTF-8', false);

        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Your Name');
        $pdf->SetTitle('Gross Profit Report');
        $pdf->SetSubject('TCPDF Tutorial');
        $pdf->SetKeywords('TCPDF, PDF, columns, example');
        $top_margin = 5;
        $pdf->SetMargins(15, $top_margin, 10);
        $pdf->SetAutoPageBreak(TRUE, 20);
        $pdf->AddPage();
        $pdf->SetFont('helvetica', '', 10);
        $this->header($pdf, $page, "Gross Profit Report", $_SESSION['gpr_istype'], $_SESSION['gprfrom_date'], $_SESSION['gprto_date']);



        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->Cell(100, 10, "Detail", 'LRTB', 0, 'L', 0, '', 1);
        $pdf->Cell(40, 10, 'Amount', 'LRTB', 0, 'R', 0, '', 1);
        $pdf->Cell(40, 10, 'Amount', 'LRTB', 0, 'R', 0, '', 1);
        $pdf->Ln(10);

        $data = isset($_SESSION['gross_profit_report_data']) ? $_SESSION['gross_profit_report_data'] : [];
        $sale = array_filter($data, function($row) {
            return $row['type'] === 'sale';
        });
        
       $sop= $data[0]['grandtotal']- $data[1]['grandtotal'];
        
       $pdf->SetFont('helvetica', '', 12);
        $pdf->Cell(100, 10, "Sale of Product", 'LR', 0, 'L', 0, '', 1);
        $pdf->Cell(40, 10, '', 'LR', 0, 'L', 0, '', 1);
        $pdf->Cell(40, 10,number_format($sop, 2) , 'LR', 0, 'R', 0,'' , 1);
        $pdf->Ln(10);



        $pdf->Cell(100, 10, "Sale of Service", 'LR', 0, 'L', 0, '', 1);
        $pdf->Cell(40, 10, '', 'LR', 0, 'R', 0, '', 1);
        $pdf->Cell(40, 10,number_format($data[2]['grandtotal'], 2) , 'LR', 0, 'R', 0,'' , 1);
        $pdf->Ln(10);


       $revenue= $sop+$data[2]['grandtotal'];

        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->Cell(100, 10, "Total Revenue", 'LR', 0, 'L', 0, '', 1);
        $pdf->Cell(40, 10, '', 'LR', 0, 'L', 0, '', 1);
        $pdf->Cell(40, 10,number_format($revenue, 2) , 'LR', 0, 'R', 0,'' , 1);
        $pdf->Ln(10);



        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->Cell(100, 10, "Cost Of Sale", 'LR', 0, 'L', 0, '', 1);
        $pdf->Cell(40, 10, '', 'LR', 0, 'L', 0, '', 1);
        $pdf->Cell(40, 10,'' , 'LR', 0, 'R', 0,'' , 1);

        $pdf->Ln(10);
        $pdf->SetFont('helvetica', '', 12);
        $pdf->Cell(10, 10, '', 'L', 0, 'L', 0, '', 1);
        $pdf->Cell(90, 10, 'Opening Stock', 'R', 0, 'L', 0, '', 1);
        $pdf->Cell(40, 10,number_format($data[5]['grandtotal'], 2) , 'LR', 0, 'R', 0,'' , 1);
        $pdf->Cell(40, 10, '', 'LR', 0, 'L', 0, '', 1);

        
        $pdf->Ln(10);
        $pop= $data[3]['grandtotal']- $data[4]['grandtotal'];

        $cos=$data[5]['grandtotal']+$pop-$data[6]['grandtotal'];

        $pdf->Cell(10, 10, '', 'L', 0, 'L', 0, '', 1);
        $pdf->Cell(90, 10, 'Purchase of Product', 'R', 0, 'L', 0, '', 1);
        $pdf->Cell(40, 10,number_format($pop, 2) , 'LR', 0, 'R', 0,'' , 1);
        $pdf->Cell(40, 10, '', 'LR', 0, 'L', 0, '', 1);

        $pdf->Ln(10);
        $pdf->Cell(10, 10, '', 'L', 0, 'L', 0, '', 1);
        $pdf->Cell(90, 10, '(-) Closing Stock', 'R', 0, 'L', 0, '', 1);
        $pdf->Cell(40, 10,"(".number_format($data[6]['grandtotal'], 2).")"  , 'LR', 0, 'R', 0,'' , 1);
        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->Cell(40, 10,"(" .number_format($cos, 2).")", 'LR', 0, 'R', 0, '', 1);

        $final=$revenue-$cos;
        $pdf->Ln(10);
        $pdf->Cell(100, 10, 'Gross Profit', 'LRBT', 0, 'L', 0, '', 1);
        $pdf->Cell(40, 10, "" , 'LRTB', 0, 'R', 0,'' , 1);
        $pdf->Cell(40, 10,number_format($final, 2), 'LRTB', 0, 'R', 0, '', 1);



     

        $date = date('Y-m-d'); 
        $filename = "gross_profit Report_$date.pdf";
        $pdf->Output($filename, 'I');
    }
    public function gross_profit_categorywise_report()
    {
        $from_date = $this->input->post('from_date');
        $to_date   = $this->input->post('to_date');
        $empid     = $this->input->post('empid');
        $category  = $this->input->post('category');
        $product   = $this->input->post('product');
        $istype    = $this->input->post('istype');
    
        $encryption_key = Config::$encryption_key;
    
        $empid   = ($empid === '' || $empid === 'null') ? null : $empid;
        $category = ($category === '' || $category === 'null') ? null : $category;
        $product  = ($product === '' || $product === 'null') ? null : $product;
    
        $query = $this->db->query("CALL GrossProfitReportCategorywise(?,?,?,?,?,?)", [
            $from_date,
            $to_date,
            $empid,
            $encryption_key,
            $product,
            $category
        ]);
    
        $report_data = [];
    
        if ($query) {
            $report_data = $query->result_array();
        }
    
        $_SESSION['gross_profit_report_category_data'] = $report_data;
        $_SESSION['gprc_istype']   = $istype;
        $_SESSION['gprcfrom_date'] = $from_date;
        $_SESSION['gprcto_date']   = $to_date;
    
        echo json_encode($report_data);
    }

    public function generate_grossprofitreportcategorywise()
    {
        $page = 1;
        $pdf = new StockReport('L', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Your Name');
        $pdf->SetTitle('Gross Profit Report(Categorywise)');
        $pdf->SetSubject('TCPDF Tutorial');
        $pdf->SetKeywords('TCPDF, PDF, columns, example');
    
        $top_margin = 5;
        $pdf->SetMargins(15, $top_margin, 10);
        $pdf->SetAutoPageBreak(TRUE, 20);
        $pdf->AddPage();
        $pdf->SetFont('helvetica', '', 10);
    
        $this->header($pdf, $page, "Gross Profit Report(Categorywise)", $_SESSION['gprc_istype'], $_SESSION['gprcfrom_date'], $_SESSION['gprcto_date']);
    
        $pdf->SetFont('helvetica', 'B', 12);
        // First row
        $pdf->Cell(40, 10, 'Category', 'TLR', 0, 'L', 0);
        $pdf->Cell(40, 10, 'Products', 'TLR', 0, 'L', 0);
        $pdf->Cell(33, 10, 'Total Sales', 'TLR', 0, 'C', 0);
        $pdf->Cell(132, 10, 'Cost Of Sales', 'TLR', 0, 'C', 0);
        $pdf->Cell(33, 10, 'Gross Profit', 'TLR', 0, 'C', 0);
        $pdf->Ln(10);
    
        // Second row (sub-columns under Cost Of Sales)
        $pdf->Cell(40, 10, '', 'BLR', 0, 'L', 0);
        $pdf->Cell(40, 10, '', 'BLR', 0, 'L', 0);
        $pdf->Cell(33, 10, '', 'BLR', 0, 'C', 0);
        $pdf->Cell(33, 10, 'Opening Stock', 'TBLR', 0, 'C', 0);
        $pdf->Cell(33, 10, 'Total Purchase', 'TBLR', 0, 'C', 0);
        $pdf->Cell(33, 10, 'Closing Stock', 'TBLR', 0, 'C', 0);
        $pdf->Cell(33, 10, 'COGS', 'TBLR', 0, 'C', 0);
        $pdf->Cell(33, 10, '', 'BLR', 0, 'C', 0);
        $pdf->Ln(10);
    
        $data = $_SESSION['gross_profit_report_category_data'] ?? [];
    
        // Initialize totals
        $total_sales = 0;
        $total_opening = 0;
        $total_purchase = 0;
        $total_closing = 0;
        $total_cogs = 0;
        $total_gross_profit = 0;
        $category="";
    
        foreach ($data as $row) {
    
            $pdf->SetFont('', '', 10);
            if($category==$row['category_name']){
                $pdf->Cell(40, 10,"", 'LR', 0, 'L', 0);
            }else{
                $pdf->Cell(40, 10, $row['category_name'], 'LR', 0, 'L', 0);
                $category= $row['category_name'];
            }
            $pdf->Cell(40, 10, $row['product_name'], 'LR', 0, 'L', 0);
            $pdf->Cell(33, 10,  number_format($row['total_sale'], 2), 'LR', 0, 'R', 0);
            $pdf->Cell(33, 10,  number_format($row['opening_stock'], 2), 'LR', 0, 'R', 0);
            $pdf->Cell(33, 10,  number_format($row['total_purchase'], 2), 'LR', 0, 'R', 0);
            $pdf->Cell(33, 10, number_format($row['closing_stock'], 2), 'LR', 0, 'R', 0);
            $pdf->Cell(33, 10, number_format($row['cogs'], 2), 'LR', 0, 'R', 0);
            $pdf->Cell(33, 10, number_format($row['gross_profit'], 2), 'LR', 0, 'R', 0);
            $pdf->Ln(10);
    
            // Sum totals
            $total_sales += $row['total_sale'];
            $total_opening += $row['opening_stock'];
            $total_purchase += $row['total_purchase'];
            $total_closing += $row['closing_stock'];
            $total_cogs += $row['cogs'];
            $total_gross_profit += $row['gross_profit'];
        }
    
        // Print totals row
        $pdf->SetFont('', 'B', 12);
        $pdf->Cell(80, 10, "Total Amount:", 'TBLR', 0, 'L', 0); // 40+40
        $pdf->Cell(33, 10, number_format($total_sales, 2), 'TBLR', 0, 'R', 0);
        $pdf->Cell(33, 10, number_format($total_opening, 2), 'TBLR', 0, 'R', 0);
        $pdf->Cell(33, 10, number_format($total_purchase, 2), 'TBLR', 0, 'R', 0);
        $pdf->Cell(33, 10, number_format($total_closing, 2), 'TBLR', 0, 'R', 0);
        $pdf->Cell(33, 10, number_format($total_cogs, 2), 'TBLR', 0, 'R', 0);
        $pdf->Cell(33, 10, number_format($total_gross_profit, 2), 'TBLR', 0, 'R', 0);
        $pdf->Ln();
    
        $date = date('Y-m-d');
        $filename = "Service Report_$date.pdf";
        $pdf->Output($filename, 'I');
    }

    public function bdtask_service_order_report()
    {
        // $sales_report = $this->report_model->todays_sales_report();
        $sales_amount = 0;
        if (!$this->permission1->method('service_order_report', 'read')->access()) {
            $previous_url = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : base_url();
            redirect($previous_url);
        }
        $data = array(
            'title'        => display('service_order_report'),
            // 'sales_amount' => number_format($sales_amount, 2, '.', ','),
        );
        $data['module']   = "report";
        $data['page']     = "service_order_report";
        echo modules::run('template/layout', $data);
    }

    public function service_order_reportinvoicewise()
    {
        $from_date   = $this->input->post('from_date');
        $to_date     = $this->input->post('to_date');
        $empid       = $this->input->post('empid');
        $branch      = $this->input->post('branch');
        $customer_id = $this->input->post('customer_id');

        $report_data = $this->report_model->service_order_reportinvoicewise($from_date, $to_date, $empid, $branch, $customer_id);
        if (!$report_data) {
            $report_data = [];
        }
        $_SESSION['service_order_reportsori'] = $report_data;
        $_SESSION['ssori_istype']              = $this->input->post('istype');
        $_SESSION['ssorifrom_date']            = $from_date;
        $_SESSION['ssorito_date']              = $to_date;

        echo json_encode($_SESSION['service_order_reportsori']);
    }

    public function generate_serviceorderreportinvoice()
    {
        $page = 1;
        $pdf = new SalesReportInvoicewise('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Your Name');
        $pdf->SetTitle('Job Order Report');
        $pdf->SetSubject('TCPDF Tutorial');
        $pdf->SetKeywords('TCPDF, PDF, columns, example');
        $top_margin = 5;
        $pdf->SetMargins(15, $top_margin, 10);
        $pdf->SetAutoPageBreak(TRUE, 20);
        $pdf->AddPage();
        $pdf->SetFont('helvetica', '', 10);

        $this->header($pdf, $page, "Job Order Report", $_SESSION['ssori_istype'], $_SESSION['ssorifrom_date'], $_SESSION['ssorito_date']);

        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->Cell(30, 10, 'Order Date',    'TB', 0, 'L', 0, '', 1);
        $pdf->Cell(30, 10, 'EOD Date',    'TB', 0, 'L', 0, '', 1);
        $pdf->Cell(40, 10, 'Order No',      'TB', 0, 'L', 0, '', 1);
        $pdf->Cell(50, 10, 'Customer Name', 'TB', 0, 'L', 0, '', 1);
        $pdf->Cell(40, 10, 'Amount',        'TB', 0, 'R', 0, '', 1);
        $pdf->Ln(10);

        $data       = isset($_SESSION['service_order_reportsori']) ? $_SESSION['service_order_reportsori'] : [];
        $lineHeight = 10;
        $maxY       = 270;
        $patotal    = 0;
        $total      = 0;

        foreach ($data as $row) {
            if ($pdf->GetY() + $lineHeight > $maxY) {
                $pdf->updatePageTotal($patotal);
                $patotal = 0;
                $pdf->AddPage();
                $page = $page + 1;
                $this->header($pdf, $page, "Job Order Report", $_SESSION['ssori_istype'], $_SESSION['ssorifrom_date'], $_SESSION['ssorito_date']);
                $pdf->SetFont('helvetica', 'B', 12);
                $pdf->Cell(30, 10, 'Order Date',    'TB', 0, 'L', 0, '', 1);
                 $pdf->Cell(30, 10, 'EOD Date',    'TB', 0, 'L', 0, '', 1);
                $pdf->Cell(40, 10, 'Order No',      'TB', 0, 'L', 0, '', 1);
                $pdf->Cell(50, 10, 'Customer Name', 'TB', 0, 'L', 0, '', 1);
                $pdf->Cell(40, 10, 'Amount',        'TB', 0, 'R', 0, '', 1);
                $pdf->Ln(10);
            }

            $amount = (float) $row['total'];
            $total += $amount;
            $patotal += $amount;

            $pdf->SetFont('', '', 10);
            $pdf->Cell(30, 8, $row['date'], 0, 0, 'L');
             $pdf->Cell(30, 8, $row['eod_date'], 0, 0, 'L');
            $pdf->Cell(40, 8, $row['invoiceno'], 0, 0, 'L');
            $pdf->Cell(50, 8, $row['customer_name'], 0, 0, 'L');
            $pdf->Cell(40, 8, number_format($amount, 2), 0, 1, 'R');
        }

        $pdf->SetFont('', 'B', 12);
        $pdf->Cell(40,  10, "Total Amount:", 'TB', 0, 'L');
        $pdf->Cell(110, 10, "",              'TB', 0, 'L');
        $pdf->Cell(40,  10, number_format($total, 2), 'TB', 1, 'R');
        $pdf->updatePageTotal($patotal);

        $date = date('Y-m-d');
        $filename = "Job Order Report_$date.pdf";
        $pdf->Output($filename, 'I');
    }

    public function bdtask_product_batch_summary_report()
    {
        if (!$this->permission1->method('product_batch_summary_report', 'read')->access()) {
            $previous_url = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : base_url();
            redirect($previous_url);
        }
        $data = array(
            'title'         => display('product_batch_summary_report'),
            'category_list' => $this->report_model->category_list_product(),
             'product_list' => $this->report_model->product_list_stock()
        );
        $data['module']   = "report";
        $data['page']     = "product_batch_summary_report";
        echo modules::run('template/layout', $data);
    }

    public function product_batch_summary_report_data()
    {
        $store = $this->input->post('store', TRUE);
        $category = $this->input->post('category', TRUE);
        $product = $this->input->post('product', TRUE);
        $supplier = $this->input->post('supplier', TRUE);
        $batch_type = $this->input->post('batch_type', TRUE);
        $status = $this->input->post('status', TRUE);

        $report_data = $this->report_model->product_batch_summary_report($store, $category, $product, $supplier, $batch_type, $status);

        $_SESSION['product_batch_summary_report_data'] = $report_data;
        $_SESSION['pbsr_store'] = $store;
        $_SESSION['pbsr_category'] = $category;
        $_SESSION['pbsr_product'] = $product;
        $_SESSION['pbsr_supplier'] = $supplier;
        $_SESSION['pbsr_batch_type'] = $batch_type;
        $_SESSION['pbsr_status'] = $status;

        echo json_encode($report_data);
    }

    public function generate_product_batch_summary_report()
    {
        $page = 1;
        $pdf = new StockReport('L', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Your Name');
        $pdf->SetTitle('Product Batch Summary Report');
        $pdf->SetSubject('TCPDF Tutorial');
        $pdf->SetKeywords('TCPDF, PDF, columns, example');
        $pdf->SetMargins(12, 5, 12);
        $pdf->setHeaderMargin(0);
        $pdf->setFooterMargin(10);
        $pdf->SetAutoPageBreak(TRUE, 20);
        $pdf->AddPage('L', 'A4');
        $pdf->SetFont('helvetica', '', 10);
        $pdf->SetY(5);

        $this->header($pdf, $page, 'Product Batch Summary Report', '', '', '');

        $this->renderProductBatchSummaryHeaderRow($pdf);

        $data = isset($_SESSION['product_batch_summary_report_data']) ? $_SESSION['product_batch_summary_report_data'] : [];
        $lineHeight = 8;
        $maxY = 185;

        $pdf->SetFont('helvetica', '', 8.5);

        foreach ($data as $row) {
            if ($pdf->GetY() + $lineHeight > $maxY) {
                $pdf->AddPage('L', 'A4');
                $page = $page + 1;
                $pdf->SetY(5);
                $this->header($pdf, $page, 'Product Batch Summary Report', '', '', '');
                $this->renderProductBatchSummaryHeaderRow($pdf);
                $pdf->SetFont('helvetica', '', 8.5);
            }

            $supplier = isset($row['supplier']) && trim((string)$row['supplier']) !== '' ? $row['supplier'] : 'n/a';

            $pdf->Cell(8, 8, $row['sl'], 'LRB', 0, 'C');
            $pdf->Cell(28, 8, $this->fitPdfText($pdf, $row['category'], 28), 'LRB', 0, 'L');
            $pdf->Cell(46, 8, $this->fitPdfText($pdf, $row['product_name'], 46), 'LRB', 0, 'L');
            $pdf->Cell(34, 8, $this->fitPdfText($pdf, $supplier, 34), 'LRB', 0, 'L');
            $pdf->Cell(28, 8, $this->fitPdfText($pdf, $row['batch_id'], 28), 'LRB', 0, 'L');
            $pdf->Cell(22, 8, $row['manufacture_date'], 'LRB', 0, 'C');
            $pdf->Cell(22, 8, $row['packing_date'], 'LRB', 0, 'C');
            $pdf->Cell(22, 8, $row['expiry_date'], 'LRB', 0, 'C');
            $pdf->Cell(18, 8, number_format((float)$row['mrp'], 2), 'LRB', 0, 'R');
            $pdf->Cell(24, 8, $row['avqty'], 'LRB', 0, 'R');
            $pdf->Cell(20, 8, $row['status'], 'LRB', 1, 'C');
        }

        if (empty($data)) {
            $pdf->SetFont('helvetica', '', 10);
            $pdf->Cell(272, 10, 'No data available for selected filters.', 'LRB', 1, 'C');
        }

        $date = date('Y-m-d');
        $filename = "Product_Batch_Summary_Report_$date.pdf";
        $pdf->Output($filename, 'I');
    }

    private function renderProductBatchSummaryHeaderRow($pdf)
    {
        $pdf->SetFont('helvetica', 'B', 8.5);
        $pdf->Cell(8, 10, 'SL', 'LTRB', 0, 'C');
        $pdf->Cell(28, 10, 'Category', 'LTRB', 0, 'C');
        $pdf->Cell(46, 10, 'Product Name', 'LTRB', 0, 'C');
        $pdf->Cell(34, 10, 'Supplier', 'LTRB', 0, 'C');
        $pdf->Cell(28, 10, 'Batch ID', 'LTRB', 0, 'C');
        $pdf->Cell(22, 10, 'MFG Date', 'LTRB', 0, 'C');
        $pdf->Cell(22, 10, 'Packing Date', 'LTRB', 0, 'C');
        $pdf->Cell(22, 10, 'Expiry Date', 'LTRB', 0, 'C');
        $pdf->Cell(18, 10, 'MRP', 'LTRB', 0, 'C');
        $pdf->Cell(24, 10, 'Master Stock Qty', 'LTRB', 0, 'C');
        $pdf->Cell(20, 10, 'Status', 'LTRB', 1, 'C');
    }

    public function bdtask_purchase_report_product_wise()
    {
        // $from_date      = (!empty($this->input->get('from_date')) ? $this->input->get('from_date') : date('Y-m-d'));
        // $to_date        = (!empty($this->input->get('to_date')) ? $this->input->get('to_date') : date('Y-m-d'));
        // $product_id     = (!empty($this->input->get('product_id')) ? $this->input->get('product_id') : '');

        // $product_report = $this->report_model->retrieve_product_sales_report($from_date, $to_date, $product_id);
        if (!$this->permission1->method('purchase_report_productwise', 'read')->access()) {
            $previous_url = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : base_url();
            redirect($previous_url);
        }
        $product_list = $this->report_model->product_list();
        // if (!empty($product_report)) {
        //     $i = 0;
        //     foreach ($product_report as $k => $v) {
        //         $i++;
        //         $product_report[$k]['sl'] = $i;
        //     }
        // }
        // $sub_total = 0;
        // if (!empty($product_report)) {
        //     foreach ($product_report as $k => $v) {
        //         $product_report[$k]['sales_date'] = $this->occational->dateConvert($product_report[$k]['date']);
        //         $sub_total = $sub_total + $product_report[$k]['total_amount'];
        //     }
        // }
        $data = array(
            'title'          => display('purchase_report_productwise'),
            // 'sub_total'      => number_format($sub_total, 2, '.', ','),
            // 'product_report' => $product_report,
            'product_list'   => $product_list,
            // 'product_id'     => $product_id,
            // 'from'           => $from_date,
            // 'to'             => $to_date,
        );
        $data['module']   = "report";
        $data['page']     = "purchasereport_productwise";
        echo modules::run('template/layout', $data);
    }

    public function purchase_reportproductwise()
    {
        $from_date = $this->input->post('from_date');
        $to_date  = $this->input->post('to_date');
        $empid = $this->input->post('empid');
        $productid = $this->input->post('productid');
        $branch = $this->input->post('branch');

        $report_data = $this->report_model->retrieve_product_purchase_report($from_date, $to_date, $productid, $empid, $branch);
        $_SESSION['purchase_reportsrp'] =  $report_data;
        $_SESSION['prp_istype'] =   $this->input->post('istype');
        $_SESSION['prpfrom_date'] = $from_date;
        $_SESSION['prpto_date'] =  $to_date;
        echo json_encode($_SESSION['purchase_reportsrp']);
    }

    public function generate_purchasereportproduct()
    {
        $page = 1;
        $pdf = new SalesReportInvoicewise('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Your Name');
        $pdf->SetTitle('Purchase Report(Product Wise)');
        $pdf->SetSubject('TCPDF Tutorial');
        $pdf->SetKeywords('TCPDF, PDF, columns, example');
        $top_margin = 5;
        $pdf->SetMargins(15, $top_margin, 10);
        $pdf->SetAutoPageBreak(TRUE, 20);
        $pdf->AddPage();
        $pdf->SetFont('helvetica', '', 10);

        $this->header($pdf, $page, "Purchase Report (Product Wise)", $_SESSION['prp_istype'], $_SESSION['prpfrom_date'], $_SESSION['prpto_date']);


        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->Cell(24, 10, 'Purchase Date', 'TB', 0, 'L', 0, '', 1);
        $pdf->Cell(36, 10, 'Product Name', 'TB', 0, 'L', 0, '', 1);
        $pdf->Cell(23, 10, 'Invoice No', 'TB', 0, 'L', 0, '', 1);
        $pdf->Cell(22, 10, 'Invoice Type', 'TB', 0, 'L', 0, '', 1);
        $pdf->Cell(15, 10, 'Supplier Name', 'TB', 0, 'L', 0, '', 1);
        $pdf->Cell(27, 10, 'Rate', 'TB', 0, 'R', 0, '', 1);
        $pdf->Cell(15, 10, 'Qty', 'TB', 0, 'R', 0, '', 1);
        $pdf->Cell(27, 10, 'Total', 'TB', 0, 'R', 0, '', 1);


        $pdf->Ln(10);

        $data = isset($_SESSION['purchase_reportsrp']) ? $_SESSION['purchase_reportsrp'] : [];
        $lineHeight = 10;
        $maxY = 270;



        $patotal = 0;
        $total = 0;

        foreach ($data as $row) {

            if ($pdf->GetY() + $lineHeight > $maxY) {
                $pdf->updatePageTotal($patotal);
                $patotal = 0;
                $pdf->AddPage();
                $page = $page + 1;
                $this->header($pdf, $page, "Purchase Report (Product Wise)", $_SESSION['prp_istype'], $_SESSION['prpfrom_date'], $_SESSION['prpto_date']);
                $pdf->SetFont('helvetica', 'B', 12);
                $pdf->Cell(24, 10, 'Purchase Date', 'TB', 0, 'L', 0, '', 1);
                $pdf->Cell(36, 10, 'Product Name', 'TB', 0, 'L', 0, '', 1);
                $pdf->Cell(23, 10, 'Invoice No', 'TB', 0, 'L', 0, '', 1);
                $pdf->Cell(22, 10, 'Invoice Type', 'TB', 0, 'L', 0, '', 1);
                $pdf->Cell(15, 10, 'Supplier Name', 'TB', 0, 'L', 0, '', 1);
                $pdf->Cell(27, 10, 'Rate', 'TB', 0, 'R', 0, '', 1);
                $pdf->Cell(15, 10, 'Qty', 'TB', 0, 'R', 0, '', 1);
                $pdf->Cell(27, 10, 'Total', 'TB', 0, 'R', 0, '', 1);
        
                $pdf->Ln(10);
            }
            $patotal = $patotal + $row['total'];
            $total = $total + $row['total'];

            $pdf->SetFont('', '', 10);
            $pdf->MultiCell(24, 8, $row['date'],'', 'L', 0, 0);
            $pdf->MultiCell(36, 8,  $row['product_name'], '', 'L', 0, 0);
            $pdf->MultiCell(23, 8,  $row['chalan_no'], '', 'L', 0, 0);
            $pdf->MultiCell(22, 8,  $row['incidenttype'], '', 'L', 0, 0);
            $pdf->MultiCell(15, 8,  $row['supplier_name'], '', 'L', 0, 0);
            $pdf->MultiCell(27, 8, number_format($row['product_rate'], 2), '', 'R', 0, 0);
            $pdf->MultiCell(15, 8,  $row['quantity']." ". $row['unit_name'], '', 'R', 0, 0);
            $pdf->MultiCell(27, 8, number_format($row['total'], 2), '', 'R', 0, 0);
            $pdf->Ln(8);



            // $pdf->Cell(40, 8, number_format($row['total'], 2), 0, 1, 'R');
        }
        $pdf->SetFont('', 'B', 12);
        $pdf->Cell(50, 10, "Total Amount:", 'TB', 0, 'L');
        $pdf->Cell(100, 10, "", 'TB', 0, 'L');
        $pdf->Cell(35, 10, number_format($total, 2), 'TB', 1, 'R');

        $pdf->updatePageTotal($patotal);

        $date = date('Y-m-d');
        $filename = "Purchase Report (Product Wise)_$date.pdf";
        $pdf->Output($filename, 'I');
    }

    public function bdtask_payment_report()
    {
        $sales_amount = 0;
        if (!$this->permission1->method('payment_report', 'read')->access()) {
            $previous_url = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : base_url();
            redirect($previous_url);
        }
        $data = array(
            'title'        => display('payment_report'),
            // 'sales_amount' => number_format($sales_amount, 2, '.', ','),
        );
        $data['acc'] = $this->paymenttype_dropdown();
        $data['to'] = "Payments";
    


        $data['module']   = "report";
        $data['type'] = 1;
        $data['page']     = "voucher_report";
        echo modules::run('template/layout', $data);

    }

    public function bdtask_receipt_report()
    {
        $sales_amount = 0;
        if (!$this->permission1->method('receipt_report', 'read')->access()) {
            $previous_url = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : base_url();
            redirect($previous_url);
        }
        $data = array(
            'title'        => display('receipt_report'),
            // 'sales_amount' => number_format($sales_amount, 2, '.', ','),
        );
        $data['acc'] = $this->receipttype_dropdown();
        $data['to'] = "Receipt";

        $data['module']   = "report";
        $data['type'] = 2;
        $data['page']     = "voucher_report";
        echo modules::run('template/layout', $data);

    }

      public function bdtask_contra_voucher_report()
    {
        $sales_amount = 0;
        if (!$this->permission1->method('contra_voucher_report', 'read')->access()) {
            $previous_url = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : base_url();
            redirect($previous_url);
        }
        $data = array(
            'title'        => display('contra_voucher_report'),
            // 'sales_amount' => number_format($sales_amount, 2, '.', ','),
        );
        $data['acc'] = $this->contatype_dropdown();
        $data['from'] = "Transferred From";
        $data['to'] = "Transferred To";

        $data['module']   = "report";
        $data['type'] = 3;
        $data['page']     = "voucher_report";
        echo modules::run('template/layout', $data);

    }


    public function paymenttype_dropdown()
    {
        $this->db->select('id as HeadCode,name as HeadName')
            ->from('payment_receipt_type')
            ->where_in('type', ['Payment', 'Common'])
            ->where('status', '1');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }

     public function receipttype_dropdown()
  {
    $this->db->select('id as HeadCode,name as HeadName')
      ->from('payment_receipt_type')
      ->where_in('type', ['Receipt','Common'])
      ->where('status', '1');
    $query = $this->db->get();
    if ($query->num_rows() > 0) {
      return $query->result_array();
    }
    return false;
  }

   public function contatype_dropdown()
  {
    $this->db->select('id as HeadCode,name as HeadName')
      ->from('payment_type')
      ->where('status', '1');
    $query = $this->db->get();
    if ($query->num_rows() > 0) {
      return $query->result_array();
    }
    return false;
  }

  

    public function payment_rep()
    {
        $from_date = $this->input->post('from_date');
        $to_date  = $this->input->post('to_date');
        $empid = $this->input->post('empid');
        $branch = $this->input->post('branch');
        $type = $this->input->post('type');
        $from = $this->input->post('from');
        $to = $this->input->post('to');



        $report_data = $this->report_model->voucher_report($from_date, $to_date, $empid, $branch, $type, $from ,$to);
        $_SESSION['voucher_report'] =  $report_data;
        $_SESSION['v_istype'] =   $this->input->post('istype');
        $_SESSION['vfrom_date'] = $from_date;
        $_SESSION['vto_date'] =  $to_date;
        
        if($type==1){
            $_SESSION['head'] = "Payment Report";
            $_SESSION['head1'] = "Payment Method";
            $_SESSION['head2'] = "Payments";

        }else if($type==2){
            $_SESSION['head'] = "Receipt Report";
            $_SESSION['head1'] = "Payment Method";
            $_SESSION['head2'] = "Receipt";
            
        }else{
            $_SESSION['head'] = "Contra Voucher Report";
            $_SESSION['head1'] = "Transferred From";
            $_SESSION['head2'] = "Transferred To";

        }



        echo json_encode($_SESSION['voucher_report']);
    }

       public function generate_voucherReport()
    {
        $page = 1;
        $pdf = new SalesReportInvoicewise('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Your Name');
        $pdf->SetTitle($_SESSION['head']);
        $pdf->SetSubject('TCPDF Tutorial');
        $pdf->SetKeywords('TCPDF, PDF, columns, example');
        $top_margin = 5;
        $pdf->SetMargins(15, $top_margin, 10);
        $pdf->SetAutoPageBreak(TRUE, 20);
        $pdf->AddPage();
        $pdf->SetFont('helvetica', '', 10);

        $this->header($pdf, $page, $_SESSION['head'], $_SESSION['v_istype'], $_SESSION['vfrom_date'], $_SESSION['vto_date']);


        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->Cell(45, 10, 'Date', 'TB', 0, 'L', 0, '', 1);
        $pdf->Cell(33, 10, 'Voucher Id', 'TB', 0, 'L', 0, '', 1);
        $pdf->Cell(40, 10, $_SESSION['head1'] , 'TB', 0, 'L', 0, '', 1);
        $pdf->Cell(35, 10, $_SESSION['head2'], 'TB', 0, 'L', 0, '', 1);
        $pdf->Cell(40, 10, 'Amount', 'TB', 0, 'R', 0, '', 1);
        $pdf->Ln(10);

        $data = isset($_SESSION['voucher_report']) ? $_SESSION['voucher_report'] : [];
        $lineHeight = 10;
        $maxY = 270;

        $patotal = 0;
        $total = 0;

        foreach ($data as $row) {

            if ($pdf->GetY() + $lineHeight > $maxY) {
                $pdf->updatePageTotal($patotal);
                $patotal = 0;
                $pdf->AddPage();
                $page = $page + 1;
                $this->header($pdf, $page, $_SESSION['head'], $_SESSION['v_istype'], $_SESSION['vfrom_date'], $_SESSION['vto_date']);
                $pdf->SetFont('helvetica', 'B', 12);
                $pdf->Cell(45, 10, 'Date', 'TB', 0, 'L', 0, '', 1);
                $pdf->Cell(33, 10, 'Voucher Id', 'TB', 0, 'L', 0, '', 1);
                $pdf->Cell(40, 10, $_SESSION['head1'], 'TB', 0, 'L', 0, '', 1);
                $pdf->Cell(35, 10, $_SESSION['head2'], 'TB', 0, 'L', 0, '', 1);
                $pdf->Cell(40, 10, 'Amount', 'TB', 0, 'R', 0, '', 1);
                $pdf->Ln(10);
                $pdf->Ln(10);
            }
            $patotal = $patotal + $row['amount'];
            $total = $total + $row['amount'];

            $pdf->SetFont('', '', 10);
            $pdf->Cell(45, 8, $row['date'], 0, 0, 'L');
            if( $_SESSION['head']=="Payment Report"){
               $pdf->Cell(33, 8,"PV-" .$row['voucher_id'], 0, 0, 'L');
            }else if( $_SESSION['head']=="Receipt Report"){
               $pdf->Cell(33, 8,"RV-" . $row['voucher_id'], 0, 0, 'L');
            }else{
               $pdf->Cell(33, 8, "CV-" .$row['voucher_id'], 0, 0, 'L');
            }
            $pdf->Cell(40, 8,  $row['from_name'], 0, 0, 'L');
            $pdf->Cell(35, 8,  $row['to_name'], 0, 0, 'L');
            $pdf->Cell(40, 8, number_format($row['amount'], 2), 0, 1, 'R');
        }
        $pdf->SetFont('', 'B', 12);
        $pdf->Cell(50, 10, "Total Amount:", 'TB', 0, 'L');
        $pdf->Cell(100, 10, "", 'TB', 0, 'L');
        $pdf->Cell(45, 10, number_format($total, 2), 'TB', 1, 'R');
        $pdf->updatePageTotal($patotal);




        $date = date('Y-m-d');
        $filename = "voucher_report.pdf";
        $pdf->Output($filename, 'I');
    }


    // ---------------------------------------------------------------
    // Commission Wise Sales Report
    // ---------------------------------------------------------------

    public function bdtask_commission_wise_sales_report()
    {
        if (!$this->permission1->method('commission_wise_sales_report', 'read')->access()) {
            $previous_url = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : base_url();
            redirect($previous_url);
        }

        $user_list = $this->report_model->userList();
        $data = [
            'title'     => 'Sales Report (Commission Wise)',
            'user_list' => $user_list,
        ];
        $data['module'] = 'report';
        $data['page']   = 'commission_wise_sales_report';
        echo modules::run('template/layout', $data);
    }

    public function sales_reportcommissionwise()
    {
        $from_date = $this->input->post('from_date');
        $to_date   = $this->input->post('to_date');
        $empid     = $this->input->post('empid');
        $employee  = $this->input->post('employee');
        $branch    = $this->input->post('branch');

        $report_data = $this->report_model->commission_wise_sales_report($from_date, $to_date, $employee, $empid, $branch);

        $_SESSION['sale_reportscw'] = $report_data;
        $_SESSION['scw_istype']     = $this->input->post('istype');
        $_SESSION['scwfrom_date']   = $from_date;
        $_SESSION['scwto_date']     = $to_date;

        echo json_encode($_SESSION['sale_reportscw']);
    }

    public function generate_salesreportcommissionwise()
    {
        $page = 1;
        $pdf  = new SalesReportInvoicewise('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('System');
        $pdf->SetTitle('Sales Report (Commission Wise)');
        $pdf->SetSubject('Commission Wise Sales Report');
        $pdf->SetKeywords('PDF, commission, sales');
        $pdf->SetMargins(7, 5, 7);
        $pdf->SetAutoPageBreak(TRUE, 20);
        $pdf->AddPage();
        $pdf->SetFont('helvetica', '', 9);

        $this->header($pdf, $page, 'Sales Report (Commission Wise)', $_SESSION['scw_istype'], $_SESSION['scwfrom_date'], $_SESSION['scwto_date']);

        // Column widths — total 196mm (A4 210mm - 7mm left - 7mm right)
        $pdf->SetFont('helvetica', 'B', 9);
        $pdf->Cell(22, 10, 'Date',            'TB', 0, 'L', 0, '', 1);
        $pdf->Cell(20, 10, 'Invoice No',       'TB', 0, 'L', 0, '', 1);
        $pdf->Cell(20, 10, 'Employee',         'TB', 0, 'L', 0, '', 1);
        $pdf->Cell(26, 10, 'Grand Total',      'TB', 0, 'R', 0, '', 1);
        $pdf->Cell(23, 10, 'Cost',             'TB', 0, 'R', 0, '', 1);
        $pdf->Cell(23, 10, 'Profit',           'TB', 0, 'R', 0, '', 1);
        $pdf->Cell(30, 10, 'Guide Commmi.',      'TB', 0, 'R', 0, '', 1);
        $pdf->Cell(30, 10, 'Salesman Commi.',       'TB', 1, 'R', 0, '', 1);

        $data       = isset($_SESSION['sale_reportscw']) ? $_SESSION['sale_reportscw'] : [];
        $lineHeight = 8;
        $maxY       = 270;

        $total_grand      = 0;
        $total_cost       = 0;
        $total_profit     = 0;
        $total_guide      = 0;
        $total_commission = 0;
        $patotal          = 0;

        foreach ($data as $row) {
            if ($pdf->GetY() + $lineHeight > $maxY) {
                $pdf->updatePageTotal($patotal);
                $patotal = 0;
                $pdf->AddPage();
                $page++;
                $this->header($pdf, $page, 'Sales Report (Commission Wise)', $_SESSION['scw_istype'], $_SESSION['scwfrom_date'], $_SESSION['scwto_date']);
                $pdf->SetFont('helvetica', 'B', 9);
                $pdf->Cell(22, 10, 'Date',            'TB', 0, 'L', 0, '', 1);
                $pdf->Cell(20, 10, 'Invoice No',       'TB', 0, 'L', 0, '', 1);
                $pdf->Cell(20, 10, 'Employee',         'TB', 0, 'L', 0, '', 1);
                $pdf->Cell(26, 10, 'Grand Total',      'TB', 0, 'R', 0, '', 1);
                $pdf->Cell(23, 10, 'Cost',             'TB', 0, 'R', 0, '', 1);
                $pdf->Cell(23, 10, 'Profit',           'TB', 0, 'R', 0, '', 1);
                $pdf->Cell(24, 10, 'Guide Commmi.',      'TB', 0, 'R', 0, '', 1);
                $pdf->Cell(23, 10, 'Salesman Commi.',       'TB', 1, 'R', 0, '', 1);
            }

            $total_grand      += $row['grand_total'];
            $total_cost       += $row['cost'];
            $total_profit     += $row['profit'];
            $total_guide      += $row['guide_commission'];
            $total_commission += $row['sale_commission'];
            $patotal          += $row['sale_commission'];

            $pdf->SetFont('', '', 8);
            $pdf->Cell(22, $lineHeight, $row['sale_date'],                          0, 0, 'L');
            $pdf->Cell(20, $lineHeight, $row['sale_id'],                            0, 0, 'L');
            $pdf->Cell(20, $lineHeight, $row['employee_name'],                      0, 0, 'L');
            $pdf->Cell(26, $lineHeight, number_format($row['grand_total'],    2),   0, 0, 'R');
            $pdf->Cell(23, $lineHeight, number_format($row['cost'],           2),   0, 0, 'R');
            $pdf->Cell(23, $lineHeight, number_format($row['profit'],         2),   0, 0, 'R');
            $pdf->Cell(30, $lineHeight, number_format($row['guide_commission'], 2), 0, 0, 'R');
            $pdf->Cell(30, $lineHeight, number_format($row['sale_commission'], 2),  0, 1, 'R');
        }

        $pdf->SetFont('', 'B', 9);
        $pdf->Cell(62, 10, 'Total',                              'TB', 0, 'L');
        $pdf->Cell(26, 10, number_format($total_grand,      2),  'TB', 0, 'R');
        $pdf->Cell(23, 10, number_format($total_cost,       2),  'TB', 0, 'R');
        $pdf->Cell(23, 10, number_format($total_profit,     2),  'TB', 0, 'R');
        $pdf->Cell(30, 10, number_format($total_guide,      2),  'TB', 0, 'R');
        $pdf->Cell(30, 10, number_format($total_commission, 2),  'TB', 1, 'R');

        $pdf->updatePageTotal($patotal);

        $filename = 'Sales_Report_Commission_Wise_' . date('Y-m-d') . '.pdf';
        $pdf->Output($filename, 'I');
    }
}
