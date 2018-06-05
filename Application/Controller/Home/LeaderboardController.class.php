<?php


namespace Application\Controller\Home;


use Framework\Controller;

class LeaderboardController extends Controller
{
   /* public function index()
    {
        $this->display('pai');
    }*/
    public function get()
    {

       $history = new \Application\Model\HistoriesModel();
       $rows  = $history->getchong();
       $xiao = $history->getxiao();


       $this->assign('chong',$rows[1]);
       $this->assign('xiao',$xiao);
       $this->display('pai');
    }
}