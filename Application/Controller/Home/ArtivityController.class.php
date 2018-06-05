<?php


namespace Application\Controller\Home;


use Application\Model\ArtivityModel;
use Framework\Controller;

class ArtivityController extends Controller
{
    public function index()
    {   $artivity = new ArtivityModel();
        $rows = $artivity->artivity();
        $this->assign('rows',$rows);
        $this->display('index');
    }
}