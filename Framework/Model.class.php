<?php

namespace Framework;


/**
 * 基础模型类
 */
abstract class Model
{
    //可以让继承提交中的类都使用该属性.
    protected $db;
    //存放错误信息
    protected $error;

    public function __construct()
    {
        $this->db = \Framework\Tools\MysqlDb::getInstance($GLOBALS['config']['db']);
    }

    /**
     * 获取错误信息
     * @return mixed
     */
    public function getError()
    {
        return $this->error;
    }
    /**
     * 对查询结果集进行排序
     * @access public
     * @param array $list 查询结果
     * @param string $field 排序的字段名
     * @param string $sortby 排序类型 （asc正向排序 desc逆向排序 nat自然排序）
     * @return array
     */
    function list_sort_by($list, $field, $sortby = 'asc')
    {
        if (is_array($list))
        {
            $refer = $resultSet = array();
            foreach ($list as $i => $data)
            {
                $refer[$i] = &$data[$field];
            }
            switch ($sortby)
            {
                case 'asc': // 正向排序
                    asort($refer);
                    break;
                case 'desc': // 逆向排序
                    arsort($refer);
                    break;
                case 'nat': // 自然排序
                    natcasesort($refer);
                    break;
            }
            foreach ($refer as $key => $val)
            {
                $resultSet[] = &$list[$key];
            }
            return $resultSet;
        }
        return false;
    }

}