<?php


namespace Application\Controller\Admin;


use Application\Model\HistoriesModel;
use Framework\Controller;

class HistoriesController extends Controller
{
    public function index()
    {
        $this->display('index');
    }
    public function getList()
    {
        $history = new HistoriesModel();
        $historys = $history->getList();
        foreach($historys['rows'] as &$v){
            $v['time'] =date('Y-m-d H:i:s',$v['time']);
        }
        $historiesList = ['total'=>$historys['total'],'rows'=>$historys['rows']];
        echo json_encode($historiesList);
    }
}