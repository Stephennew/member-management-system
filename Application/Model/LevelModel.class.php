<?php


namespace Application\Model;


use Framework\Model;

class LevelModel extends Model
{
    public function getList()
    {
        $sql  = "select * from `level`";
        $sql_count = "select count(*) from `level`";
        $rows = $this->db->fetchAll($sql);
        $count = $this->db->fetchColumn($sql_count);
        return ['total'=>$count,'rows'=>$rows];
    }
    public function insert($data)
    {
        $sql = "insert into `level`(caste,discount,`condition`) VALUES (
            '{$data['caste']}',
            '{$data['discount']}',
            '{$data['condition']}'
            )";
        return $this->db->execute($sql);
    }
    public function update($data)
    {
        $sql = "update `level` set 
              caste = '{$data['caste']}',
              discount = '{$data['discount']}',
              `condition` = '{$data['condition']}'
              WHERE level_id = '{$data['level_id']}'
              ";
        return $this->db->execute($sql);
    }
    public function delete($level_id)
    {
        $sql = "delete  from `level` WHERE level_id = '{$level_id}'";
        return $this->db->execute($sql);
    }
    public function getOne($level_id)
    {
        $sql  = "select * from `level` WHERE level_id = '{$level_id}'";
        return $row = $this->db->fetchRow($sql);
    }
}