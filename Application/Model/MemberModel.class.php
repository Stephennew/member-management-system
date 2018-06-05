<?php


namespace Application\Model;


use Framework\Model;

class MemberModel extends Model
{
    public function getList($conditonStr)
    {
        $sql = "select * from member where ".$conditonStr;
        $sql_count = "select count(*) from member";
        $count = $this->db->fetchColumn($sql_count);
        $rows = $this->db->fetchAll($sql);
        return ['total'=>$count,'rows'=>$rows];
    }

    public function getFind($member_id){
    	$sql = "select * from member where member_id=".$member_id;
    	$row= $this->db->fetchRow($sql);
    	return $row;
    }

    public function addMember($data){
        $sql = "insert into member(user_name,
                password,
                realname,
                sex,
                telphone,
                photo,
                remarks) 
                value ('{$data['user_name']}','{$data['password']}',
                '{$data['realname']}',
                '{$data['sex']}',
                '{$data['telphone']}',
                '{$data['photo']}',
                '{$data['remarks']}')";
            
        return $this->db->execute($sql);
    }

    public function editMember($data,$member_id){
        $sql = "update  member set 
            user_name='{$data['user_name']}',
            realname='{$data['realname']}',
            sex='{$data['sex']}',
            telphone='{$data['telphone']}',
            photo='{$data['photo']}',
            remarks='{$data['remarks']}' 
            where member_id={$member_id}";

        return $this->db->execute($sql);
    }

    public function del($member_id){
        $sql="delete from member where member_id={$member_id}";
        return $this->db->execute($sql);
    }

    public function recharge($data){

        //查询充值规则
        $sql = "select * from `recharge_rule`";
        $rows = $this->db->fetchAll($sql);
        //查询等级规则
        $sqllevel="select * from level";
        $levelRows = $this->db->fetchAll($sqllevel);
        $rule=[];
        $level=[];
        //获取小于当前充值金额的充值优惠规则数据
        foreach($rows as $v){
            if($data["money"] >= $v["recharge_money"]){
                $rule[]=$v;
            }
        }
        //获取小于当前充值金额的充值优惠规则数据降序
       $ruleData=$this->list_sort_by($rule,"recharge_money",'desc');
        //获取充值优惠规则
       if($ruleData){
            $data["send"]=$ruleData[0]["recharge_send"];
            $data["rule_id"]=$ruleData[0]["recharge_rule_id"];
        }else{
            $data["send"]=0;
            $data["rule_id"]=0;
        }
        $data["time"]=time();
        $admin_id=$_SESSION['user_info']['admin_id'];
        //写入充值金额表
        $sql1="insert into recharge(money,send,time,remarks,rule_id,admin_id,member_id) 
                value('{$data['money']}','{$data['send']}','{$data['time']}','{$data['remarks']}','{$data['rule_id']}',$admin_id,'{$data['member_id']}')";
        $this->db->execute($sql1);

        //获取当前会员充值金额累计
        $countSql="select sum(money) from recharge where member_id=".$data['member_id'];
        $rechargeCount=$this->db->fetchColumn($countSql);
        $data["level_id"]=0;
        $data["caste"]="vip0";
        foreach($levelRows as $v){
            //vip10 最高等级30000
            if($rechargeCount > 30000){
                $data["level_id"]=10;
            //获取小于累计金额的vip规则数据
            }else if($rechargeCount >= $v["condition"]){
                $level[]=$v;
            }
        }
        //获取小于累计金额的vip规则数据降序
        if($level){
           $levelData=$this->list_sort_by($level,"condition",'desc');
            //获取vip规则
            if($levelData){
                $data["level_id"]=$levelData[0]["level_id"];
                $data["caste"]=$levelData[0]["caste"];
            } 
        }
        //当前会员数据
        $memberData=$this->getFind($data["member_id"]);
        //余额 会员余额+充值金额+赠送金额
        $balance=$memberData["balance"]+$data["money"]+$data["send"];
        //更新会员余额信息 和 vip等级信息
        if($memberData["level_id"]==$data["level_id"]){
            $sql2="update member set balance ='{$balance}' where member_id = {$data['member_id']}";
            $cont="";

        }else{
            $sql2="update member set 
        balance ={$balance},
        level_id={$data["level_id"]}
         where member_id = {$data['member_id']}";
            $cont="变更";
        }
        //写入日志表
        $type=1;
        $remarks="充值".$data['money']."送".$data["send"]."vip等级".$cont.$data["caste"];
        $sql3="insert into histories(admin_id,member_id,type,money,balance,remarks,time)
                value('{$admin_id}','{$data['member_id']}','{$type}','{$data['money']}','{$balance}','{$remarks}','{$data['time']}')";

        $this->db->execute($sql2);
        $this->db->execute($sql3);

        return true;
    }
    public function registerMember($data)
    {
        if($data['password'] != $data['repassword']){
            $this->error = '确认密码与密码不一致';
            return false;
        }
        $sql = "insert into member(
                user_name,
                password,
                realname,
                sex,
                telphone,
                photo) 
                value (
                '{$data['username']}',
                '{$data['password']}',
                '{$data['realname']}',
                '{$data['sex']}',
                '{$data['telphone']}',
                '{$data['photo']}')";

        return $this->db->execute($sql);
    }
    public function check($data){
       /* //验证用户输入的验证码是否与sesion中的一致,由于验证不需要查询数据,所以先验证
        if(!isset($data['captcha']) || strtolower($_SESSION['randmo_code']) != strtolower($data['captcha'])){
            $this->error = '验证码错误!';
            return false;
        }*/
        //准备sql
        //根据用户名查找一条用户数据
        $sql = "select * from member WHERE user_name='{$data['username']}'";
        //执行sql
        $res = $this->db->fetchRow($sql);
        //判断是否有用户信息
        if(empty($res)){
            $this->error = "用户名不存在!";
            return false;
        }
        //如果有用户数据,将传入的密码与数据库里面的面进行比对
        if($data['password'] != $res['password']){
            $this->error = "密码错误";
            return false;
        }
        return $res;
    }

    public  function checkIdPassword($id,$password){
        //根据$id去查询数据
        $sql = "select * from member WHERE member_id = '{$id}'";
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
    //消费
    public function expend($data){
        $expendmoney = 0;
        $member_id=$data["member_id"];
        $plan_id=$data["plan_id"];
        $memberData=$this->getFind($member_id);
        $level=new LevelModel();
        $plan=new PlansModel();
        if(isset($memberData['level_id'])){
            $levelData=$level->getOne($memberData['level_id']);
        }else{
            $levelData=0;
        }
        $planData=$plan->getOne($plan_id);
        $balance=$memberData["balance"];
        if($data["code"]){
            $code=$data["code"];
            $sql="select * from codes where code='{$code}' and user_id={$member_id} and status=0";
            $codes= $this->db->fetchRow($sql);
            if($codes){
                $money=$planData['money']-$codes['money'];
                if($money >0){
                    $expendmoney=$money;
                    if($levelData){
                        $expendmoney=$money*$levelData['discount'];
                    }

                    if( $memberData["balance"] > $expendmoney ){

                        $balance=$balance- $expendmoney;

                    }else{
                        $res['status']=0;
                        $res["message"]="你当前余额不足".$expendmoney;
                    }
                }else{

                    $money=abs($money);
                    $login_admin = $_SESSION['user_info']['admin_id'];
                    $codesUpdate="update codes set status=1 where code_id=".$codes['code_id'];
                    $codesAdd="insert into codes (code,user_id,money,parent_id,admin_id) 
                                value ('{$code}','{$member_id}','{$money}','{$codes['code_id']}','{$login_admin}') ";
                    //var_dump($sql);die;
                    $this->db->execute($codesUpdate);
                    $this->db->execute($codesAdd);
                }
            }else{
                $res['status']=0;
                $res["message"]="你输入的代金券号不存在或者不可用".$code;
            }
        }else{

            $expendmoney=$planData['money'];
            if($levelData){
                $expendmoney=$planData['money']*$levelData['discount'];
            }

            $balance=$balance -$expendmoney;

        }

        $time=time();
        $points=$memberData['points']+$expendmoney;

        $sql1="insert into expend (plan_id,admin_id,code,time,member_id)
            value ('{$data['plan_id']}','{$data['admin_id']}','{$data['code']}','{$time}','{$member_id}')";
        $this->db->execute($sql1);
        $expend_id=$this->db->mysqli_insert_id();
        $sql2="insert into points (member_id,points,expend_id,time)
                value('{$member_id}','{$expendmoney}','{$expend_id}','{$time}')";

        $sql3="update member set balance =".$balance." ,points=". $points." WHERE member_id = {$member_id}";

        $type=2;
        $remarks="消费".$expendmoney."增送".$expendmoney."积分";
        $sql4="insert into histories(admin_id,member_id,type,money,balance,remarks,time)
                value('{$data['admin_id']}','{$member_id}','{$type}','{$expendmoney}','{$balance}','{$remarks}','{$time}')";

        $this->db->execute($sql2);
        $this->db->execute($sql3);
        $this->db->execute($sql4);

        $res['status']=1;
        $res["message"]="成功";
        return $res;
    }

    public function getData(){
        $sql = "select * from member";
        $rows = $this->db->fetchAll($sql);
        return $rows;

    }


}