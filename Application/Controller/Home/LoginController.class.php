<?php


namespace Application\Controller\Home;


use Framework\Controller;
use Application\Model\MemberModel;

class LoginController extends Controller
{
    public function index()
    {
        $this->display('login');
    }
    public function check(){
        // var_dump($_POST);die;
        //接收数据
        //处理数据
        $manager = new MemberModel();
        $res = $manager->check($_POST);
        if($res === false) {
            $this->redirect('index.php?p=home&c=Login&a=index', $manager->getError(), 1);
        }
        //验证失败,处理错误信息

        //验证通过后,返回用户信息,将用户信息保存到session中
        $_SESSION['user'] = $res;
        //判断用户是否勾选保存此次登录信息,勾选即保存登录信息
        $password = $res['password'];
        if(isset($_POST['remember'])){
            setcookie('ids',$res['manager_id'],time()+7*24*3600,'/');
            setcookie('passwords',$password,time()+7*24*3600,'/');
        }

        $this->redirect('index.php?p=home&c=Index&a=index');
    }
    public function logout()
    {
        //删除session
        unset($_SESSION['user']['user_name']);
        //删除cookie
        setcookie('ids',null,-1,'/');
        setcookie('passwords',null,-1,'/');
        $this->redirect('index.php?p=home&c=index&a=index');
    }
}