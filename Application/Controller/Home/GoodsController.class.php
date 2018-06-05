<?php


namespace Application\Controller\Home;


use Application\Model\GoodsModel;
use Framework\Controller;

class GoodsController extends Controller
{
    private $goods;
    public function index()
    {
        $rows = $this->goods->getList();
        $this->assign('rows',$rows['rows']);
        $this->display('index');
    }
    public function __construct()
    {
        $this->goods = new GoodsModel();
    }
}