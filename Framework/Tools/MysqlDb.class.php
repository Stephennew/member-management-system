<?php
namespace Framework\Tools;

//>>3.设计一个类
class MysqlDb
{
    //确定属性
    private $host;//主机
    private $user;//用户名
    private $password;//密码
    private $database;//数据库名
    private $port;//端口号
    private $charset;//字符集

    //保存数据库的连接对象
    private $link;

    //私有的静态成员属性,用于保存创建好的对象
    private static $instance = null;
    //确定方法

    /**
     * 构造 初始化操作
     * MysqlDb constructor.
     * @param $databaseInfo 数据库配置信息,一维数组
     */
    private function __construct($databaseInfo)
    {
        //初始化属性
        $this->host = $databaseInfo['host']??'127.0.0.1';
        $this->user = $databaseInfo['user']??'root';
        $this->password = $databaseInfo['password'];
        $this->database = $databaseInfo['database'];
        $this->port = $databaseInfo['port']??3306;
        $this->charset = $databaseInfo['charset']??'utf8';
        //>>1.连接数据
        $this->connectDb();
        //>>2.设置字符集
        $this->setcharset();
    }
    //私有克隆方法
    private function __clone()
    {
    }
    /**
     * 公有的静态的创建对象的方法\
     * @param $databaseInfo 数据库的连接信息,一维数组
     */
    public static function getInstance($databaseInfo){
        //判断如果曾经没有创建才创建
        if(self::$instance == null){
            //创建对象
            self::$instance = new self($databaseInfo);
        }
        //返回对象
        return self::$instance;
    }
    //连接数据库的方法
    private function connectDb()
    {
        $this->link = mysqli_connect($this->host, $this->user, $this->password, $this->database, $this->port);
//判断连接是否成功
        if ($this->link === false) {//连接失败
            //输出错误的信息
            die(
                "连接数据库失败!<br/>" .
                "错误代码:" . mysqli_connect_errno() . "<br/>" .
                "错误信息:" . mysqli_connect_error()
            );
        }
    }

    //设置字符集的方法
    private function setcharset()
    {
        $result = mysqli_set_charset($this->link, $this->charset);
        //判断设置字符集是否错误
        if ($result === false) {//设置错误
            //输出错误的信息
            die(
                "设置字符集失败!<br/>" .
                "错误代码:" . mysqli_errno($this->link) . "<br/>" .
                "错误信息:" . mysqli_error($this->link)
            );
        }
    }

    /**
     * 专业用于执行sql语句
     * @param $sql 需要执行的sql语句
     * @return $result 返回结果集
     */
    private function query($sql)
    {
        //>>3.执行sql语句
        $result = mysqli_query($this->link, $sql);
        //判断执行sql是否错误
        if ($result === false) {
            //输出错误的信息
            die(
                "执行sql语句失败!<br/>" .
                "错误代码:" . mysqli_errno($this->link) . "<br/>" .
                "错误信息:" . mysqli_error($this->link) . "<br/>" .
                "SQL语句:" . $sql
            );
        }
        //>>4.返回执行后的结果集
        return $result;
    }


    /**
     * 用于执行  执行类的sql(delete , insert , update)
     * @param $sql
     * @return bool
     */
    public function execute($sql)
    {
        return $this->query($sql);
    }

    /**
     * 执行并解析sql,返回一维数组
     * @param $sql
     * @return [一维数组]
     */
    public function fetchRow($sql)
    {
        //>>1.执行sql
        $result = $this->query($sql);
        //>>2.解析sql 并 返回
        $row = mysqli_fetch_assoc($result);
        return $row??[];

//        $rows = $this->fetchAll($sql);
////        var_dump($rows);
//        return $rows[0];
    }

    /**
     * 执行sql并解析sql,返回二维数组
     * @param $sql
     * @return [二维数组]
     */
    public function fetchAll($sql)
    {
        //1.执行sql
        $result = $this->query($sql);
        //2.解析sql 并 返回
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }

    /**
     * 执行sql 并解析sql 返回之后的第一行第一列数据的值
     * @param $sql
     * @return  返回第一行第一列的值
     */
    public function fetchColumn($sql)
    {
        //>>1.执行sql
//            $result = $this->query($sql);
//            $rows = $this->fetchAll($sql);
        $row = $this->fetchRow($sql);
        //>>2.将第一行第一列的值取出来
        $value = array_shift($row);
//            var_dump(array_values($row));
        //>>3.返回对应的值
        return $value;
    }

    //析构方法
    public function __destruct()
    {
        //p("析构方法");
        //关闭数据库连接
        mysqli_close($this->link);
    }

    /**
     * 对象序列化的时候自动调用执行,返回需要被序列化的属性组成的数组
     */
    public function __sleep(){
        return ['host','user','password','database','port','charset'];
    }
    //对象被反序列化的时候自动调用执行
    public function __wakeup()
    {
        // 重新初始化
        //>>1.连接数据
        $this->connectDb();
        //>>2.设置字符集
        $this->setcharset();
    }
    public function  mysqli_insert_id(){
        return mysqli_insert_id($this->link);
    }
}