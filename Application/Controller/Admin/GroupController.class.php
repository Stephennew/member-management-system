<?php


namespace Application\Controller\Admin;


use Application\Model\GroupModel;
use Framework\Controller;

class GroupController extends Controller
{
    private $group;
    public function __construct()
    {
        $this->group = new GroupModel();
    }

    public function index(){
        $this->display('index');
    }

    public function getList(){

        $offset=$_GET['offset'];
        $limit=$_GET['limit'];

        $where ='';
        if($_GET['name'] !=''){
            $where = " name like '%{$_GET['name']}%'  and ";
        }
        $where.=" 1=1";
        $groups = $this->group->getList($where,$offset,$limit);
        $groupList=['total'=>$groups['total'],'rows'=>$groups['rows']];
        echo json_encode($groupList);
    }

    public function addGroup()
    {
       echo $this->group->insert($_POST);
    }

    public function editGroup()
    {
        echo $this->group->update($_POST);
    }

    public function getOne(){
        $row = $this->group->getOne($_POST['group_id']);
        echo json_encode($row);
    }

    public function del()
    {
        echo $this->group->delete($_POST['group_id']);
    }
}