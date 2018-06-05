<?php


namespace Application\Model;


use Framework\Model;

class GroupModel extends Model
{
    public function getList($where,$offset,$limit)
    {
        
        $sql = "select * from `group` WHERE {$where} limit {$offset},{$limit}";
        $sql_count = "select count(*) from `group` where {$where}";
        $count = $this->db->fetchColumn($sql_count);
        $rows = $this->db->fetchAll($sql);
        return ['total' => $count, 'rows' => $rows];
    }

    public function getData(){
        $sql = "select * from `group`";
        $rows = $this->db->fetchAll($sql);
        return $rows;
    }

    public function insert($data)
    {
        $sql = "insert into `group` SET `name` = '{$data['name']}'";

        return $this->db->execute($sql);
    }

    public function getOne($group_id)
    {
        $sql = "select * from `group` WHERE group_id = '{$group_id}'";
        return $this->db->fetchRow($sql);
    }

    public function update($data)
    {
        $sql = "update `group` set `name` = '{$data['name']}' WHERE group_id = '{$data['group_id']}'";
        return $this->db->execute($sql);
    }

    public function delete($group_id)
    {
        $sql = "delete from `group` WHERE group_id = '{$group_id}'";
        return $this->db->execute($sql);
    }
}