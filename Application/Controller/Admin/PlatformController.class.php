<?php


namespace Application\Controller\Admin;
use Application\Model\AdminModel;
use  Application\Model\ManagerModel;
use Framework\Controller;
/*平台统一验证控制器,所有需要登录才可访问的页面都需要验证*/
class PlatformController extends Controller
{
   public function __construct()
   {
       if($this->checkLogin() === false) {

           $this->redirect('index.php?p=admin&c=Login&a=index','您没有登录,请先登录!',1);
       }
   }
    private function checkLogin()
    {

        //验证$_SESSION中是否有登录信息
        if (!isset($_SESSION['user_info']) || $_SESSION['user_info']['admin_id'] <= 0) {
            if (isset($_COOKIE['id']) && isset($_COOKIE['password'])) {
                $id = $_COOKIE['id'];
                $password = $_COOKIE['password'];
                $manger = new AdminModel();
                $res = $manger->checkIdPassword($id, $password);
                //var_dump($res);die;
                //验证id password 通过 将信息保存到 $_SESSION 中
                if($res !== false){
                    $_SESSION['user_info'] = $res;
                }
                return true;
            }
            return false;
        }

    }
}