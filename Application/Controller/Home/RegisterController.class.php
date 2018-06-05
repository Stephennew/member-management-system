<?php


namespace Application\Controller\Home;


use Application\Model\MemberModel;
use Framework\Controller;

class RegisterController extends Controller
{
    public function index()
    {
        $this->display('register');
    }
    public function addMember()
    {
        $member = new MemberModel();
        $file = $_FILES['photo'];
        if($file['error'] ==0){
            $upload=$this->upload($file);
            if($upload['status']==1){
                $_POST["photo"]=$upload['path'];
            }
        }else{
            $_POST["photo"]= './Uploads/a9.jpg';
        }
        $res = $member->registerMember($_POST);
        if(!$res){
            $this->redirect('index.php?p=home&c=register&a=index',$member->getError(),'1');
        }
        $this->redirect('index.php?p=home&c=login&a=index','注册成功!','1');
    }
}