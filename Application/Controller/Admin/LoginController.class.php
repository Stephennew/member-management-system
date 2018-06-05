<?php


namespace Application\Controller\Admin;

use Application\Model\AdminModel;
use Application\Model\ManagerModel;
use Framework\Controller;

class LoginController extends Controller
{


    public function index(){

    $this->display('login_v2');
    }

    public function check(){
       // var_dump($_POST);die;
       //接收数据
        //处理数据
            $manager = new AdminModel();
            $res = $manager->check($_POST);
            if($res === false) {
               $this->redirect('index.php?p=admin&c=Login&a=index', $manager->getError(), 1);
            }
        //验证失败,处理错误信息

        //验证通过后,返回用户信息,将用户信息保存到session中
        $_SESSION['user_info'] = $res;
        //判断用户是否勾选保存此次登录信息,勾选即保存登录信息
        $password = $res['password'];
        if(isset($_POST['remember'])){
            setcookie('id',$res['manager_id'],time()+7*24*3600,'/');
            setcookie('password',$password,time()+7*24*3600,'/');
        }

        $this->redirect('index.php?p=admin&c=Index&a=index');
    }
    public function logout()
    {
        //删除session
        unset($_SESSION['user_info']);
        //删除cookie
        setcookie('id',null,-1,'/');
        setcookie('password',null,-1,'/');
       $this->redirect('index.php?p=admin&c=Login&a=index');
    }

}