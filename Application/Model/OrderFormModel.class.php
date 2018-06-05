<?php


namespace Application\Model;


use Framework\Model;

class OrderFormModel extends Model
{
    public function getList($where,$offset,$limit)
    {
        $admin = new AdminModel();
        $sql = "select * from order_form WHERE {$where} limit {$offset},{$limit}";
        $sql_count = "select count(*) from order_form WHERE {$where}";
        $rows =$this->db->fetchAll($sql);
        $count = $this->db->fetchColumn($sql_count);
        return ['total'=>$count,'rows'=>$rows];

    }
    public function getOne($orderform_id)
    {
        $sql = "select * from order_form WHERE orderform_id = '{$orderform_id}'";
        return $row = $this->db->fetchRow($sql);
    }
    public function delete($orderform_id)
    {
        $sql = "delete from order_form WHERE orderform_id = '{$orderform_id}'";
        return $this->db->execute($sql);
    }
    public function update($data)
    {
        var_dump($data);
    }
    //积分商城新增订单
    public function insert($data)
    {   //增加订单是判断用户是否登录
        if(!isset($_SESSION['user']) || $_SESSION['user']['member_id']<0){
            $this->error = '登录才可以兑换';
            return false;
        }
        //这里要去判断该用户的积分是否小于该商品的积分

        $order_number = uniqid();//订单号
        $user_name = $_SESSION['user']['member_id'];//会员ID
        $submission_time = time(); //提交时间
        $status = 0;//默认状态
        $sql = "insert into order_form(
            order_number,
            submission_time,
            member_id,
            status,
            goods_id,
            goods_num) VALUES (
         '{$order_number}',
         '{$submission_time}',
         '{$user_name}',
         '{$status}',
         '{$data['goods_id']}',
         '{$data['goods_num']}')";
        return $this->db->execute($sql);
    }
    //获取登录会员的订单
    public function getMeOrder($member_id)
    {
        if(!isset($_SESSION['user']) || $_SESSION['user']['member_id']<0){
            $this->error = '登录后才可以查询订单';
            return false;
        }

        $sql  = "select * from order_form WHERE member_id = '{$member_id}'";
        $rows = $this->db->fetchAll($sql);
        return $rows;

    }
    //显示一条订单
    public function show($orderform_id)
    {
        $sql = "select * from order_form WHERE orderform_id = '{$orderform_id}'";
        $row = $this->db->fetchRow($sql);
        return $row;
    }
    //取消一条订单
    public function cancel($orderform_id)
    {
        $sql = "delete from order_form WHERE orderform_id = '{$orderform_id}'";
        $this->db->execute($sql);
    }
}
