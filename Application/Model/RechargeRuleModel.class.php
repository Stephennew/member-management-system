<?php


namespace Application\Model;


use Framework\Model;

class RechargeRuleModel extends Model
{
    public function getList($offset,$limit)
    {
       
        $sql = "select * from `recharge_rule` limit {$offset},{$limit}";
        $sql_count = "select count(*) from `recharge_rule`";
        $count = $this->db->fetchColumn($sql_count);
        $rows = $this->db->fetchAll($sql);
        return ['total' => $count, 'rows' => $rows];
    }

    public function insert($data)
    {
        $sql = "insert into `recharge_rule` (recharge_money,recharge_send,remarks,add_time,status)
                value('{$data['recharge_money']}','{$data['recharge_send']}','{$data['remarks']}','{$data['add_time']}',1) ";

        return $this->db->execute($sql);
    }

    public function getOne($id)
    {
        $sql = "select * from `recharge_rule` WHERE recharge_rule_id = " .$id;
        return $row = $this->db->fetchRow($sql);
    }

    public function update($data)
    {
        $sql = "update `recharge_rule` set `recharge_money` = '{$data['recharge_money']}',`recharge_send` = '{$data['recharge_send']}',`remarks` = '{$data['remarks']}'
            WHERE recharge_rule_id = '{$data['recharge_rule_id']}'";
        return $this->db->execute($sql);
    }

    public function delete($id)
    {
        $sql = "delete from `recharge_rule` WHERE recharge_rule_id = ".$id;
        return $this->db->execute($sql);
    }
}