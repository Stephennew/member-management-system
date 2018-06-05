<?php


namespace Application\Controller\Admin;


use Application\Model\CodesModel;
use Application\Model\MemberModel;
use Framework\Controller;

class CodesController extends Controller
{
    private $code;
    public function __construct()
    {
        $this->code = new CodesModel();
    }
    public function index()
    {
        $member = new MemberModel();
        $members = $member->getData();
        $this->assign('member',$members);
        $this->display('index');
    }
    public function getList()
    {
        $offset = $_GET['offset'];
        $limit = $_GET['limit'];
        $where = '';
        if($_GET['code'] != ''){
            $where = " code = '{$_GET['code']}' and ";
        }
        $where.="1=1";
        $codes = $this->code->getList($where,$offset,$limit);
        $codesList = ['total'=>$codes['total'],'rows'=>$codes['rows']];
        echo json_encode($codesList);
    }
    public function editCodes()
    {
        echo $this->code->update($_POST);
    }
    public function addCodes()
    {
        echo $this->code->insert($_POST);
    }
    public function getOne()
    {
        $row = $this->code->getOne($_POST['code_id']);
        echo json_encode($row);
    }
    public function del()
    {
        echo $this->code->delete($_POST['code_id']);
    }

}
