<?php


namespace Application\Model;


use Framework\Model;

class OrderModel extends Model
{
    public function insert($data)
    {
        $data['status']=1;
        $data['date'] = strtotime($data['date']);
        $sql = "insert into `order`(telphone,realname,barber,content,`date`,`status`)
                VALUES (
                '{$data['telphone']}',
                '{$data['realname']}',
                '{$data['barber']}',
                '{$data['content']}',
                '{$data['date']}',
                '{$data['status']}')";
        return $this->db->execute($sql);
    }
    public function getList()
    {
        $sql = "select * from `order`";
        $sql_count = "select count(*) from `order`";
        $rows = $this->db->fetchAll($sql);
        $count = $this->db->fetchColumn($sql_count);
        return ['total'=>$count,'rows'=>$rows];
    }
    public function delete($order_id)
    {
        $sql = "delete from `order` WHERE order_id = '{$order_id}'";
        return $this->db->execute($sql);
    }
    public function getOne($order_id)
    {
        $sql = "select * from `order` WHERE order_id = '{$order_id}'";
        return $row = $this->db->fetchRow($sql);
    }
    public function update($data)
    {

        $sql = "update `order`  set reply = '{$data['reply']}' WHERE order_id = '{$data['order_id']}'";
        var_dump($sql);
        return $this->db->execute($sql);
    }
}