<?php


namespace Application\Controller\Home;


use Framework\Controller;

class IndexController extends Controller
{
    public function index()
    {
        $this->display('index');
    }
    /*public function yuyue()
    {
        require './View/Home/Index/yuyue.html';
    }*/
    public function goods()
    {
        require './View/Home/Goods/index.html';
    }
}