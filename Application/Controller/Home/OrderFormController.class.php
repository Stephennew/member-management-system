<?php


namespace Application\Controller\Home;


use Application\Model\OrderFormModel;
use Framework\Controller;

class OrderFormController extends Controller
{
    private $orderform;
   /* public function index()
    {

        $this->display('orderform');
    }*/
    public function __construct()
    {
        $this->orderform = new OrderFormModel();
    }
    public function getMeOrder()
    {
        $rows = $this->orderform->getMeOrder($_REQUEST['member_id']);
        if($rows == false){
            $this->redirect('index.php?p=home&c=Login&a=index',$this->orderform->getError(),1);
        }
        //$this->redirect('index.php?p=home&c=orderform&a=index');
        $this->assign('rows',$rows);
        $this->display('orderform');
    }
    public function addorderform()
    {
        $res = $this->orderform->insert($_POST);
        if(!$res){
            $this->redirect('index.php?p=home&c=Goods&a=index',$this->orderform->getError(),1);
        }
        $this->redirect('index.php?p=home&c=Goods&a=index','已下单,正在处理订单',1);

    }
    public function cancel()
    {
        $this->orderform->cancel($_GET['orderform_id']);
        $this->redirect("index.php?p=home&c=orderform&a=getMeOrder&member_id=<?={$_GET['member_id']}?>");
    }
    public function show()
    {
        $row = $this->orderform->show($_GET['orderform_id']);
        //var_dump($row);
        $this->assign($row);
        $this->display('show');
    }
}