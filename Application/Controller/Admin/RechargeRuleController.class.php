<?php


namespace Application\Controller\Admin;


use Application\Model\RechargeRuleModel;
use Framework\Controller;

class RechargeRuleController extends Controller
{
    private $RechargeRule;

    public function __construct()
    {
        $this->RechargeRule = new RechargeRuleModel();
    }

    public function index(){
        $this->display('index');
    }

    public function getList(){

        $offset=$_GET['offset'];
        $limit=$_GET['limit'];
        $where ='';
        $rechargeData = $this->RechargeRule->getList($offset,$limit);
        $rechargelist=['total'=>$rechargeData['total'],'rows'=>$rechargeData['rows']];
        echo json_encode($rechargelist);
    }

    public function addRechargeRule()
    {
        $_POST['add_time']=time();
       echo $this->RechargeRule->insert($_POST);
    }

    public function editRechargeRule()
    {
        echo$this->RechargeRule->update($_POST);
    }

    public function getOne(){
        $row = $this->RechargeRule->getOne($_POST['recharge_rule_id']);
        //var_dump($row);
        echo json_encode($row);
    }

    public function del()
    {
        echo $this->RechargeRule->delete($_POST['recharge_rule_id']);
    }
}