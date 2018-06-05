<?php


namespace Application\Controller\Admin;


use Application\Model\ArtivityModel;
use Framework\Controller;

class ArtivityController extends Controller
{
    private $artivity;
    public function __construct()
    {
        $this->artivity = new ArtivityModel();
    }

    public function index()
    {
        $this->display('index');
    }

    public function getList()
    {
        $offset = $_GET['offset'];
        $limit = $_GET['limit'];

        $condition = '';
        if($_GET['title'] != ''){
            $condition = "title like '%{$_GET['title']}%' and ";
        }
        if($_GET['des'] != ''){
            $condition .= "des like '%{$_GET['des']}%' and ";
        }

        $time=time();
        $condition .= "'{$time}' between start and end ";
//        if($_GET['des'] != '' && $_GET['title'] != ''){
//            $condition = '';
//            $condition .= "title like '%{$_GET['title']}%' and des like '%{$_GET['des']}%' ";
//        }
        $artivitys = $this->artivity->getList($condition,$offset,$limit);

        foreach( $artivitys['rows'] as &$v){
            $v['time']=date("Y-m-d", $v['time']);
            $v['start']=date("Y-m-d", $v['start']);
            $v['end']=date("Y-m-d", $v['end']);
        }

        $artivitysList = ['total'=>$artivitys['total'],'rows'=>$artivitys['rows']];

        echo json_encode($artivitysList);

    }

    public function addArtivity()
    {
        echo $this->artivity->insert($_POST);
    }
    public function editArtivity()
    {
        echo $this->artivity->update($_POST);
    }

    public function getOne()
    {
        $row = $this->artivity->getOne($_POST['artivity_id']);

        $row['start']=date("Y-m-d H:i:s", $row['start']);
        $row['end']=date("Y-m-d H:i:s", $row['end']);

        echo json_encode($row);
    }

    public function del()
    {
        echo $this->artivity->delete($_POST['artivity_id']);
    }
}