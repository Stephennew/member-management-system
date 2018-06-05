<?php


namespace Application\Model;


use Framework\Model;

class HistoriesModel extends Model
{
    public function getList()
    {
        $sql = "select * from histories order by history_id desc";
        $sql_count = "select count(*) from histories";
        $count = $this->db->fetchColumn($sql_count);
        $rows = $this->db->fetchAll($sql);
        return ['total'=>$count,'rows'=>$rows];
    }

    public function getchong()
    {
       $sql = "SELECT sum(money) as sum from histories WHERE type=1 GROUP BY member_id ORDER BY sum desc limit 3";
       $sql1 = "select * from histories";
        $rows = $this->db->fetchAll($sql);
       //$sql = SELECT sum(amount) as sum,user_id from histories WHERE type=0 GROUP BY user_id  HAVING sum=10011499.99;
        $chong = $this->db->fetchAll($sql);
        return [$rows,$chong];
    }
    public function getxiao()
    {
        $sql = "SELECT sum(money) as sum from histories WHERE type=2 GROUP BY member_id ORDER BY sum desc limit 3";
        return $this->db->fetchAll($sql);
    }
}