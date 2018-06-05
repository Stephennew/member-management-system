<?php


namespace Application\Controller\Admin;


use Application\Model\LevelModel;
use Framework\Controller;

class LevelController extends Controller
{
    private $level;
    public function __construct()
    {
        $this->level = new LevelModel();
    }

    public function index()
    {
        $this->display('index');
    }

    public function getList()
    {
        $level = $this->level->getList();
        $levelList = ['total'=>$level['total'],'rows'=>$level['rows']];
        echo json_encode($levelList);
    }
    public function addLevel()
    {
        echo $this->level->insert($_POST);
    }
    public function editLevel()
    {
        echo $this->level->update($_POST);
    }
    public function getOne()
    {
        $row = $this->level->getOne($_POST['level_id']);
        echo json_encode($row);
    }
    public function del()
    {
        echo $this->level->delete($_POST['level_id']);
    }
}