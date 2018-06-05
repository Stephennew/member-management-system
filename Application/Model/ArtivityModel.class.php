<?php


namespace Application\Model;


use Framework\Model;

class ArtivityModel extends Model
{
    public function getList($condition,$offset,$limit)
    {
        $sql = "select * from artivity";
        if($condition != ''){
            $sql .= " WHERE ".$condition;
        }

        $sql .= " limit {$offset},{$limit}";
        $sql_count = "select count(*) from artivity WHERE {$condition}";
        $count = $this->db->fetchColumn($sql_count);
        $rows = $this->db->fetchAll($sql);
        return ['total'=>$count,'rows'=>$rows];
    }
    public function getOne($artivity_id)
    {
        $sql = "select * from artivity WHERE artivity_id = '{$artivity_id}'";
        return $row = $this->db->fetchRow($sql);
    }
    public function insert($data)
    {
        $data['content'] = strip_tags($data['content']);
        $data['start'] = strtotime($data['start']); //活动开始时间
        $data['end'] = strtotime($data['end']);//活动结束时间
        $data['time'] = time(); //活动发布时间
        $sql = "insert into artivity(`title`,`des`,`content`,`start`,`end`,`time`) VALUES (
            '{$data['title']}',
            '{$data['des']}',
            '{$data['content']}',
            '{$data['start']}',
            '{$data['end']}',
            '{$data['time']}'
            )";
        return $this->db->execute($sql);
    }
    public function update($data)
    {
        $data['content'] = strip_tags($data['content']);
        $data['start'] = strtotime($data['start']); //活动开始时间
        $data['end'] = strtotime($data['end']);//活动结束时间
        //$data['time'] = time(); //活动发布时间
        $sql = "update artivity set 
            title = '{$data['title']}',
            des = '{$data['des']}',
            content = '{$data['content']}',
            start = '{$data['start']}',
            `end` = '{$data['end']}'
            WHERE artivity_id = '{$data['artivity_id']}'";
        return $this->db->execute($sql);
    }
    public function delete($artivity_id)
    {
        $sql = "delete from artivity WHERE artivity_id = '{$artivity_id}'";
        return $this->db->execute($sql);
    }
    public function artivity()
    {
        $sql = "select * from artivity";
        return $row = $this->db->fetchAll($sql);
    }
}