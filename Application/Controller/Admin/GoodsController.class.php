<?php


namespace Application\Controller\Admin;


use Application\Model\GoodsModel;
use Framework\Controller;

class GoodsController extends Controller
{
    private $good;
    public function index()
    {
        $this->display('index');

    }
    public function __construct()
    {
        $this->good = new GoodsModel();
    }

    public function getList()
    {
        $goods = $this->good->getList();
        $goodsList = ['total'=>$goods['total'],'rows'=>$goods['rows']];
        echo json_encode($goodsList);
    }
    public function addGoods()
    {
        $file=$_FILES["photo"];
        if($file['error'] ==0){
            $upload=$this->upload($file);
            if($upload['status']==1){
                $_POST["photo"]=$upload['path'];
            }
        }else{
            $_POST["photo"]= './Uploads/a9.jpg';
        }
        echo $this->good->insert($_POST);
    }
    public function edit()
    {
        echo $this->good->update($_POST);
    }
    public function getOne()
    {
        $row = $this->good->getOne($_POST['goods_id']);
        echo json_encode($row);
    }
    public function del()
    {
        echo $this->good->delete($_POST['goods_id']);

    }
}