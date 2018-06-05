<?php


namespace Application\Controller\Admin;


use Application\Model\PlansModel;
use Framework\Controller;

class PlansController extends Controller
{
    private $plan;
    public function __construct()
    {
        $this->plan = new PlansModel();
    }

    public function index(){
        $this->display('index');
    }

    public function getList(){
        $offset = $_GET['offset'];
        $limit = $_GET['limit'];
        $where = '';
        if($_GET['name'] != ''){
            $where = " name like '%{$_GET['name']}%' and ";
        }
        if($_GET['money'] != ''){
            $where .= " money ='{$_GET['money']}' and ";
        }

//        if($_GET['name'] != '' && $_GET['money'] != ''){
//            $where = '';
//            $where .= " name like '%{$_GET['name']}%' and money like '%{$_GET['money']}%";
//        }
        $where.="1=1";
        $plans = $this->plan->getList($where,$offset,$limit);

        $plansList = ['total'=>$plans['total'],'rows'=>$plans['rows']];
        echo json_encode($plansList);
    }
    public function addPlans()
    {
        echo $this->plan->insert($_POST);
    }
    public function editPlans()
    {
        echo $this->plan->update($_POST);
    }
    public function getOne()
    {
        $row = $this->plan->getOne($_POST['plan_id']);
        echo json_encode($row);
    }
    public function del()
    {
        var_dump($_POST);
        echo $this->plan->delete($_POST['plan_id']);
    }
}