<?php


namespace Application\Controller\Admin;


use Application\Model\OrderFormModel;
use Framework\Controller;

class OrderFormController extends Controller
{
    private $orderForm;
    public function __construct()
    {
        $this->orderForm = new OrderFormModel();
    }

    public function index()
    {
        $this->display('index');
    }
    public function getList()
    {   $offset = $_GET['offset'];
        $limit = $_GET['limit'];
        $where = '';
        if($_GET['keyword'] != ''){
            $where =
            "order_number    like '%{$_GET['keyword']}%' or ".
            "submission_time like '%{$_GET['keyword']}%' or ".
            "member_id       like '%{$_GET['keyword']}%' or ".
            "admin_id        like '%{$_GET['keyword']}%' and ";
        }
        $where .= " 1=1";
        $orderForms = $this->orderForm->getList($where,$offset,$limit);
        foreach ($orderForms['rows'] as &$v){
            $v['submission_time'] =date('Y-m-d H:i:s',$v['submission_time']);
        }
        $orderFormList = ['total'=>$orderForms['total'],'rows'=>$orderForms['rows']];
        echo json_encode($orderFormList);
    }
    public function editOrderForm()
    {
        echo $this->orderForm->update();
    }
    public function getOne()
    {
        $row = $this->orderForm->getOne($_POST['orderform_id']);
        echo json_encode($row);
    }
    public function del()
    {
        echo $this->orderForm->delete($_POST['orderform_id']);
    }
}