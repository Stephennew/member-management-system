<?php


namespace Application\Model;


use Framework\Model;

class CodesModel extends Model
{
    public function getList($where,$offset,$limit)
    {
        $sql = "select * from codes where '{$where}' limit {$offset}, {$limit}" ;
        $sql_count = "select count(*) from codes where '{$where}' limit {$offset},{$limit}";
        $rows = $this->db->fetchAll($sql);
        $count = $this->db->fetchColumn($sql_count);
        return ['total'=>$count,'rows'=>$rows];
    }
    public function insert($data)
    {   $data['user_id'] = 1;
        $data['status'] = 1;
        for ($i=1;$i<=$data['num'];++$i){
            $data['code']=uniqid();
            $sql = "insert into codes(code,user_id,money,status) VALUES (
              '{$data['code']}',
              '{$data['user_id']}',
              '{$data['money']}',
              '{$data['status']}')";
             $this->db->execute($sql);
        }
          return true;
    }
    public function update($data)
    {
        $sql = "update codes set 
                    money = '{$data['money']}'
                    WHERE code_id = '{$data['code_id']}'";
    }
    public function getOne($code_id)
    {
        $sql  = "select * from codes WHERE code_id = '{$code_id}'";
        return $row = $this->db->fetchRow($sql);
    }
    public function delete($code_id)
    {
        $sql = "delete from codes WHERE code_id='{$code_id}'";
        return $this->db->execute($sql);
    }



}