<?php


namespace Application\Controller\Admin;


use Application\Model\OrderModel;
use Framework\Controller;

class OrderController extends Controller
{
    private $order;
    public function index()
    {
        $this->display('index');
    }
    public function __construct()
    {
        $this->order = new OrderModel();
    }

    public function getList()
    {
        $orders = $this->order->getList();
        foreach ($orders['rows'] as &$v){
            $v['date'] = date('Y-m-d H:i:s',$v['date']);
        }
        $orderList = ['total'=>$orders['total'],'rows'=>$orders['rows']];

        echo json_encode($orderList);
    }
    public function del()
    {
        echo $this->order->delete($_POST['order_id']);
    }
    public function getOne()
    {
        $row = $this->order->getOne($_POST['order_id']);
        echo json_encode($row);
    }
    public function editOrder()
    {
        echo $this->order->update($_POST);
    }
}