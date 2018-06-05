<?php


namespace Application\Controller\Admin;


class CaptchaController
{
    public function captcha(){
        //1. 准备字符串
        $string = "023456789ABCDEFGHKLMNOPQRSTUVWXYZ";
        //2.打乱字符串
        $string_shu = str_shuffle($string);
        //3.截取字符串
        $random_code = substr($string_shu,0,4);
        // 将字符串保存到session,登录验证的时候需要使用
        $_SESSION['randmo_code'] = $random_code;
        //4.准备图片资源
        $image_path ="./Public/captcha/captcha_bg".mt_rand(1,5).'.jpg';
        //'./Public/captcha/captcha_bj2.jpg';
        $size = getimagesize($image_path);
        list($width,$height)=$size;
        //创建画布
        $img = imagecreatefromjpeg($image_path);
        //混淆验证码
        //画点,随机化很多的点

        for ($i = 0;$i<=200;$i++){
            //随机颜色
            $rand_color = imagecolorallocate($img,mt_rand(0,255),mt_rand(0,255),mt_rand(0,255));
            //随机点
            imagesetpixel($img,mt_rand(1,$width-2),mt_rand(1,$height-2),$rand_color);
        }
        //画线
        for ($i=1;$i<=5;$i++){
            //随机颜色
            $rand_color = imagecolorallocate($img,mt_rand(0,255),mt_rand(0,255),mt_rand(0,255));
            //划线
            imageline($img,mt_rand(1,$width-2),mt_rand(1,$height-2),mt_rand(1,$width-2),mt_rand(1,$height-2),$rand_color);
        }

        //准备字体颜色
        $blank = imagecolorallocate($img,0,0,0);
        $white = imagecolorallocate($img,255,255,255);
        $color = [$blank,$white];
        //图片上写字
        imagestring($img,5,$width/2.8,$height/8,$random_code,$color[mt_rand(0,1)]);
        //验证图片优化,边框
        imagerectangle($img,0,0,$width-1,$height-1,$white);
        //输出到浏览器
        header("Content-Type:image/jpeg");
        imagejpeg($img);
        //销毁图片
        imagedestroy($img);
    }

}