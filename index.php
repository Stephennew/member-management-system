<?php
//设置header头
header("Content-Type: text/html;charset=UTF-8");

defined('DS') or define('DS',DIRECTORY_SEPARATOR);   //将该常量起一个别名
defined('ROOT_PATH') or define('ROOT_PATH',dirname($_SERVER['SCRIPT_FILENAME']).DS); //项目的根目录
defined('APP_PATH') or define('APP_PATH',ROOT_PATH.'Application'.DS);  //Application的目录
defined('FRAM_PATH') or define('FRAM_PATH',ROOT_PATH.'Framework'.DS);  //Framework的目录
defined('TOOLS_PATH') or define('TOOLS_PATH',FRAM_PATH.'tools'.DS);  //tools的目录
defined('CONFIG_PATH') or define('CONFIG_PATH',APP_PATH.'Config'.DS);  //Config的目录
defined('CONTROLLER_PATH') or define('CONTROLLER_PATH',APP_PATH.'Controller'.DS);  //Controller的目录
defined('MODEL_PATH') or define('MODEL_PATH',APP_PATH.'Model'.DS);  //Model的目录
defined('VIEW_PATH') or define('VIEW_PATH',APP_PATH.'VIEW'.DS);  //VIEW的目录
defined('PUBLIC_PATH') or define('PUBLIC_PATH',ROOT_PATH.'Public'.DS);  //Public的目录
defined('UPLOADS_PATH') or define('UPLOADS_PATH',ROOT_PATH.'Uploads'.DS);  //Uploads的目录

//加载配置文件
$GLOBALS['config'] = require CONFIG_PATH."application.config.php";
//通过GET方式获取一个参数,表示需要使用的某个功能
$p = $_GET['p']??$GLOBALS['config']['default']['platform'];//控制器的类名
$c = $_GET['c']??$GLOBALS['config']['default']['controller'];//控制器的类名
$a = $_GET['a']??$GLOBALS['config']['default']['action'];//控制器的方法名,

//以下常量的值会随着用户访问的平台和控制器来确定.
defined('CURRENT_CONTROLLER_PATH') or define('CURRENT_CONTROLLER_PATH',CONTROLLER_PATH.$p.DS); //正在访问当前控制在哪个平台的路径.
defined('CURRENT_VIEW_PATH') or define('CURRENT_VIEW_PATH',VIEW_PATH.$p.DS.$c.DS); //正在访问当前控制在哪个平台的路径.

//准备一个变量 拼接控制器类名
$class_name = "\\Application\\Controller\\{$p}\\{$c}Controller";
//创建控制器类对象 创建多个控制类对象
$controller = new $class_name();
$controller->$a();

/**
 * 类的自动加载
 */
function __autoload($class_name){
    //根据传入的类名加载对应的类文件
    $class_name = str_replace("\\","/",$class_name);
    //加载类文件
    if(file_exists(ROOT_PATH."{$class_name}.class.php")){
        require ROOT_PATH."{$class_name}.class.php";
    }else{
        require PUBLIC_PATH.'PHPExcel/Classes/PHPExcel.php';
    }

}
