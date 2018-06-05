<?php


namespace Application\Model;


use Framework\Model;

class AdminModel extends Model
{
	 public function getList($where,$offset,$limit)
    {
        $sql = "select * from admin  WHERE {$where} limit {$offset},{$limit}";
        $sql_count = "select count(*) from admin WHERE {$where}";
        $count = $this->db->fetchColumn($sql_count);
        $rows = $this->db->fetchAll($sql);
        return ['total'=>$count,'rows'=>$rows];
    }

    public function getFind($admin_id){

    	$sql = "select * from admin where admin_id=".$admin_id;
    	$row= $this->db->fetchRow($sql);
    	return $row;
    }
    public function getData(){
        $sql = "select * from admin";
        $rows = $this->db->fetchAll($sql);
        return $rows;
    }

	public function addAdmin($data){
        $sql = "insert into admin(
        		user_name,
                password,
                realname,
                sex,
                telphone,
                icon,
                group_id,
                is_admin,
                add_time
                ) 
                VALUES (
                '{$data['user_name']}',
                '{$data['password']}',
                '{$data['realname']}',
                '{$data['sex']}',
                '{$data['telphone']}',
                '{$data['icon']}',
                '{$data['group_id']}',
                '{$data['is_admin']}',
                '{$data['add_time']}'
            )";
            
        return $this->db->execute($sql);
    }

    public function editAdmin($data,$admin_id){
        $sql = "update admin set 
            user_name='{$data['user_name']}',
            realname='{$data['realname']}',
            sex='{$data['sex']}',
            telphone='{$data['telphone']}',
            icon='{$data['icon']}',
            password='{$data['password']}',
            group_id='{$data['group_id']}',
            is_admin='{$data['is_admin']}'
            where admin_id={$admin_id}";

        return $this->db->execute($sql);
    }

    public function del($admin_id){
       /* $sql1 = "select  * from expend WHERE admin_id = {$admin_id}";
        $res = $this->db->execute($sql1);
        if($res){
            echo 404;
        } else{*/
            $sql="delete from admin where admin_id={$admin_id}";
            return $this->db->execute($sql);
       /* }*/

    }

    public function check($data){
        //验证用户输入的验证码是否与sesion中的一致,由于验证不需要查询数据,所以先验证
        if(!isset($data['captcha']) || strtolower($_SESSION['randmo_code']) != strtolower($data['captcha'])){
            $this->error = '验证码错误!';
            return false;
        }
        //准备sql
        //根据用户名查找一条用户数据
        $sql = "select * from admin WHERE user_name='{$data['username']}'";
        //执行sql
        $res = $this->db->fetchRow($sql);
        //判断是否有用户信息
        if(empty($res)){
            $this->error = "用户名不存在!";
            return false;
        }
        /*if($res){

        }*/
        //如果有用户数据,将传入的密码与数据库里面的面进行比对
        if(md5($data['password']) != $res['password']){
            $this->error = "密码错误";
            return false;
        }
        return $res;
    }

    public  function checkIdPassword($id,$password){
        //根据$id去查询数据
        $sql = "select * from admin WHERE admin_id = '{$id}'";
        $res = $this->db->fetchRow($sql);

        //判断是否有数据
        if(empty($res)){
            //$this->error = '该用户不存在!';
            return false;
        }

        //var_dump($res);die;
        //cookie中的数据进行多次加密,取出数据库里面也要多次加密,与cookie的密码进行比较
        //$db_passwrod = md5($res['password'].md5('wang'));
        if($res['password'] !== $password){
            //$this->error = '密码不正确!';
            return false;
        }
        //var_dump($res);die;
        //id  password 都通过验证,返回用户信息
        return $res;

    }


}