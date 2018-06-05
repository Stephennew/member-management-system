<?php


namespace Application\Model;


use Framework\Model;

class GoodsModel extends Model
{
    public function getList()
    {
        $sql  = "select * from goods";
        $sql_count = "select count(*) from goods";
        $rows = $this->db->fetchAll($sql);
        $count = $this->db->fetchColumn($sql_count);
        return ['total'=>$count,'rows'=>$rows];
    }
    public function insert($data)
    {
        $sql = "insert into goods(`name`,des,price,status,inventory,photo) VALUES (
              '{$data['name']}',
              '{$data['des']}',
              '{$data['price']}',
              '{$data['status']}',
              '{$data['inventory']}',
              '{$data['photo']}')";
        return $this->db->execute($sql);
    }
    public function update($data)
    {
        $sql = "update goods set 
              `name` = '{$data['name']}',
              des = '{$data['des']}',
              price = '{$data['price']}',
              status = '{$data['status']}',
              inventory '{$data['inventory']}',
              photo = '{$data['photo']}')";
        return $this->db->execute($sql);
    }
    public function getOne($goods_id)
    {
        $sql = "select * from goods WHERE goods_id = '{$goods_id}'";
        return $row = $this->db->fetchRow($sql);
    }
    public function delete($goods_id)
    {
        $sql = "delete from goods WHERE goods_id = {$goods_id}";
        return $this->db->execute($sql);
    }
}