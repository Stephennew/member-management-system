<?php


namespace Application\Controller\Admin;
use Application\Model\AdminModel;
use Application\Model\GroupModel;
use Framework\Controller;

class AdminController extends PlatformController
{
    public function index(){
        $group= new GroupModel();
        $groupData=$group->getData();
        $this->assign("group",$groupData);
        $this->display('index');
    }

     public function getList()
    {
        $admin = new AdminModel();
        $group= new GroupModel();
        $offset=$_GET['offset'];
        $limit=$_GET['limit'];
        $conditonStr="";
        if($_GET['group_id'] !=-1){
            $conditonStr.="group_id=".$_GET['group_id']." and ";
        }

        if($_GET['sex'] !=-1){
            $conditonStr.="sex=".$_GET['sex']." and ";
        }

        if($_GET['keyword'] !=''){
            $conditonStr.=
            "user_name like '%{$_GET['keyword']}%' or ".
            "realname like  '%{$_GET['keyword']}%' or ".
            "telphone like  '%{$_GET['keyword']}%' and ";
        }

        $conditonStr.="1=1";
       
        $rows = $admin->getList($conditonStr,$offset,$limit);
        foreach($rows['rows'] as &$v){
            if($v["group_id"]){
                $groupData=$group->getOne($v["group_id"]);
                $v["group_name"]=$groupData['name'];
            }else{
                $v["group_name"]="未分配部门";
            }
        }

        $data=['total'=>$rows['total'],'rows'=>$rows['rows']];
        echo json_encode($data);
    }

    public function getFindData(){
        $admin_id=intval($_POST['admin_id']);
        $admin = new AdminModel();
        $find= $admin->getFind($admin_id);
        echo json_encode($find);
    }


    public function addAdmin(){

    	$admin = new AdminModel();
        $file=$_FILES["storeimgfile"];
        if($file['error'] ==0){
           $upload=$this->upload($file);
            if($upload['status']==1){
               $_POST["icon"]=$upload['path'];
            } 
        }else{
            $_POST["icon"]= './Uploads/a9.jpg';
        }

        if($_POST["password"] && $_POST["qrpwd"]){
            $_POST["password"]=md5($_POST["password"]);
            $_POST["qrpwd"]=md5($_POST["qrpwd"]);
            if($_POST["password"] != $_POST["qrpwd"]){
                $res["code"]=0;
                $res["message"]="密码和确认密码不一样";
                echo json_encode($res);exit;
            }
        }else{
            $res["code"]=0;
            $res["message"]="密码和确认密码必填";
            echo json_encode($res);exit;
        }
       
        $_POST['add_time']=time();
        $rows = $admin->addAdmin($_POST);
        $res["code"]=$rows;
        echo json_encode($res);
    }

    public function editAdmin(){

        $admin = new AdminModel();

        $file=$_FILES["storeimgfile"];
        $admin_id=$_POST["admin_id"];
        $adminFind= $admin->getFind($admin_id);

        if($_POST["oldpassword"] && $_POST["password"]){

	        if($adminFind["password"] != md5($_POST['oldpassword'])){
	        	$res["code"]=0;
	            $res["message"]="原始密码错误";
	            echo json_encode($res);exit;
	        }

	        $_POST["password"]=md5($_POST["password"]);
            $_POST["qrpwd"]=md5($_POST["qrpwd"]);
            if($_POST["password"] != $_POST["qrpwd"]){
                $res["code"]=0;
                $res["message"]="密码和确认密码不一样";
                echo json_encode($res);exit;
            }
        }else{
        	$_POST["password"]=$adminFind["password"];
        }
       
        $_POST["icon"]=$adminFind['icon'];

        if($file['error'] ==0){
            $upload=$this->upload($file);
            if($upload['status']==1){

               $_POST["icon"]=$upload['path'];
            }
        }
        $rows = $admin->editAdmin($_POST,$admin_id);
        $res["code"]=$rows;
        echo json_encode($res);
    }

    public function del(){
        $admin_id=intval($_POST["admin_id"]);
        $admin = new adminModel();
        $rows = $admin->del($admin_id);
        if($rows==404){
            $res['status']=0;
            $res['message']="服务记录的员工,不能删除";
        }else{
            $res['status']=1;
            $res['message']="删除成功";
        }
        echo json_encode($res);
    }
}