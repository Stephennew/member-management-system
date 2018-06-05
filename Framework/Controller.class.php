<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/5/23
 * Time: 17:54
 */

namespace Framework;



class Controller
{
    private $datas = []; //存放数据容器. 该容器中的数据需要在页面中使用到.

    /**
     * 加载当前控制器对应的视图文件夹下的模板
     * @param $template 模板的名字
     */
    public function display($template){
        extract($this->datas); //将datas中的数据解析成变量.  变量名就是键的名字
        require CURRENT_VIEW_PATH.$template.'.html';
    }

    /**
     * 将数据放到$data中
     * @param $name
     * @param $value
     */
    public function assign($name,$value=''){
        if(is_array($name)){
            //如果name是数组,将$name的数据直接合并到$datas中
            $this->datas = array_merge($this->datas,$name);  //$name = array('key1'=>value1,'key2'=>value2);
        }else{
            $this->datas[$name] = $value;
        }
    }



    /**
     * 跳转
     * @param $url  跳转的url
     * @param $msg   提示的信息
     * @param $time  等待时间,秒
     */
    protected  function redirect($url,$msg='',$time=0){
        if(!headers_sent()){  //headers_sent检测header是否发送给浏览器
            //header没有发送,使用header跳转
            if($time==0){ //立即跳转
                header("Location: $url");
            }else{  //延迟跳转
                echo '<h1>'.$msg.'</h1>';  //跳转之前输出提示信息
                header("Refresh: $time;url=$url");
            }
        }else{
            if($time!=0){   //延时跳转
                echo '<h1>'.$msg.'</h1>';  //提示信息
                $time = $time * 1000;
            }
            //使用js跳转
            echo <<<JS
            <script type='text/javascript'>
                window.setTimeout(function(){
                  location.href = '{$url}';
                },{$time});
            </script>
JS;
        }
        exit;  //跳转之后没有必要再执行其他的代码.
    }
    //判断文件是否上传成功
    public function upload($data)
    {
        $image_type = ['image/jpg','image/jpeg','image/png','image/bmp','image/gif'];
        $image_size = 2*1024*1024;
        $res=[];
        if ($data['error'] != 0) {

            $res["message"] = '上传失败!';
            $res["status"]=0;
            return $res;
        }
        if(!in_array($data['type'],$image_type)){

            $res["message"] =  '该类型不允许,请重新上传!';
            $res["status"]=0;
            return $res;
        }
        if($data['size'] > $image_size){
            $res["message"] =  '该类型不允许,请重新上传!';
            $res["status"]=0;
            return $res;
        }
        if(!is_uploaded_file($data['tmp_name'])){
            $res["message"] =  '不是通过HTTP POST 上传';
            $res["status"]=0;
            return $res;
        }
        //后缀名
        $suffix = strrchr($data['name'],'.');
        //名称
        $filename = uniqid('image_').$suffix;

        //路径 创建文件夹
        $new_path = './Uploads/'.date('Ymd').'/';
        //自动创建目录
        if(!is_dir($new_path)){
            mkdir($new_path,0777,true);
        }
        //完整的文件路径
        $fullname = $new_path.$filename;
        //判断文件移动是否成功
        if(!move_uploaded_file($data['tmp_name'],$fullname)){
            $res["message"] =  '移动文件失败';
            $res["status"]=0;
            return $res;
        }else{
            $res["path"] =  $fullname;
            $res["status"]=1;
            return $res;
        }

    }


}