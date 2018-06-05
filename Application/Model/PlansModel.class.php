<?php


namespace Application\Model;


use Framework\Model;

class PlansModel extends Model
{
    public function getList($where,$offset,$limit){
        $sql = "select * from plans";
        if(!empty($where)){
            $sql .= " WHERE ".$where;
        }
        $sql .= " limit {$offset},{$limit}";
        $sql_count = "select count(*) from plans";
        $rows = $this->db->fetchAll($sql);
        $count = $this->db->fetchColumn($sql_count);
        return ['total'=>$count,'rows'=>$rows];

    }
    public function insert($data)
    {
        $sql = "insert into plans(`name`,des,money,status) VALUES (
        '{$data['name']}',
        '{$data['des']}',
        '{$data['money']}',
        '{$data['status']}')";
        return $this->db->execute($sql);
    }
    public function getOne($plan_id)
    {
        $sql = "select * from plans WHERE plan_id = '{$plan_id}'";
        return $ros = $this->db->fetchRow($sql);
    }
    public function update($data)
    {
        $sql = "update plans set 
        `name`='{$data['name']}',
        des = '{$data['des']}',
        money = '{$data['money']}',
        status = '{$data['status']}'
        WHERE plan_id = '{$data['plan_id']}'
        ";
        return $this->db->execute($sql);
    }
    public function delete($plan_id)
    {
        $sql = "delete from plans WHERE plan_id = '{$plan_id}'";
        return $this->db->execute($sql);
    }
    public function getData(){
        $sql = "select * from plans";
        $rows = $this->db->fetchAll($sql);
        return $rows;
    }
}