<?php


namespace Application\Controller\Home;


use Application\Model\OrderModel;
use Framework\Controller;

class OrderController extends Controller
{
    public function index()
    {
        $this->display('index');
    }
    public function addOrder()
    {
        $order = new OrderModel();
        $res = $order->insert($_POST);
        if(!$res){
            $this->redirect('index.php?p=home&c=index&a=index','预约失败',1);
        }
        $this->redirect('index.php?p=home&c=index&a=index','预约成功',1);
    }
}