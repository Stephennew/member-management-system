<?php


namespace Application\Controller\Admin;

use Application\Model\MemberModel;
use Application\Model\LevelModel;
use Application\Model\RechargeModel;
use Application\Model\PlansModel;
use Application\Model\AdminModel;
use Framework\Controller;


class MemberController extends Controller
{
    public function index()
    {
        $level = new LevelModel();
        $plans=new PlansModel();
        $admin=new AdminModel();
        $levelData = $level->getList();
        $plansData=$plans->getData();
        $adminData=$admin->getData();
        $this->assign("plans",$plansData);
        $this->assign("adminData",$adminData);
        $this->assign("level",$levelData["rows"]);
        $this->display('index');
    }

    public function getList()
    {
        $member = new MemberModel();
        $level = new LevelModel();
        $conditonStr="";
        if($_GET['level_id'] !=-1){
            $conditonStr.="level_id=".$_GET['level_id']." and ";
        }

        if($_GET['sex'] !=-1){
            $conditonStr.="sex=".$_GET['sex']." and ";
        }

        if($_GET['keyword'] !=''){
            $conditonStr.=
            "user_name like '%{$_GET['keyword']}%' or ".
            "realname like  '%{$_GET['keyword']}%' or ".
            "telphone like  '%{$_GET['keyword']}%' and ";
        }

        $conditonStr.="1=1";
        $rows = $member->getList($conditonStr);
        foreach ($rows["rows"] as &$value) {
            if($value['level_id']) {
                $levelData = $level->getOne($value['level_id']);
                $value["level_name"] = $levelData["caste"];
            }else{
                $value["level_name"] = 'vip0';
            }
        }
        $test=['total'=>$rows['total'],'rows'=>$rows['rows']];
        echo json_encode($test);
    }

    public function getFindData(){
        $member_id=intval($_POST['member_id']);
        $member = new MemberModel();
        $level = new LevelModel();
        $find= $member->getFind($member_id);
        $levelData=$level->getOne($find['level_id']);
        $find["level_name"]=$levelData["caste"];
        echo json_encode($find);
    }

    //添加会员
    public function addMember(){

        $member = new MemberModel();
        $file=$_FILES["storeimgfile"];
        if($file['error'] ==0){
           $upload=$this->upload($file);
            if($upload['status']==1){
               $_POST["photo"]=$upload['path'];
            } 
        }else{
            $_POST["photo"]= './Uploads/a9.jpg';
        }

        if($_POST["password"] && $_POST["qrpwd"]){
            $_POST["password"]=md5($_POST["password"]);
            $_POST["qrpwd"]=md5($_POST["qrpwd"]);
            if($_POST["password"] != $_POST["qrpwd"]){
                $res["code"]=0;
                $res["message"]="密码和确认密码不一样";
                echo json_encode($res);exit;
            }
        }else{
             $res["code"]=0;
            $res["message"]="密码和确认密码必填";
            echo json_encode($res);exit;
        }
       
        
        $rows = $member->addMember($_POST);
        $res["code"]=$rows;
        echo json_encode($res);


    }

    public function editMember(){

        $member = new MemberModel();
        $file=$_FILES["storeimgfile"];
        $member_id=$_POST["member_id"];
        $memberFind= $member->getFind($member_id);
        $_POST["photo"]=$memberFind['photo'];
        $password=$memberFind['password'];
        if($file['error'] ==0){
            $upload=$this->upload($file);
            if($upload['status']==1){

               $_POST["photo"]=$upload['path'];
            }
        }
        $rows = $member->editMember($_POST,$member_id);
        $res["code"]=$rows;
        echo json_encode($res);
    }

    public function del(){
        $member_id=intval($_POST["member_id"]);
        $member = new MemberModel();
        $rows = $member->del($member_id);
        echo $rows;
    }

    public function recharge(){
        // $recharge = new RechargeModel();
        // $rows = $recharge->addRecharge($_POST);
        $member = new MemberModel();
        $rows = $member->recharge($_POST);
        echo $rows;
    }
    //消费
    public function expend(){
        $member = new MemberModel();
        $res= $member->expend($_POST);
        echo json_encode($res);
    }

    //到处excel
    public function daochu(){
        $where = '1=1';
        $m = new MemberModel();
        $ms=$m->getList($where);
        $mss = $ms['rows']; //需要导出的数据
        //创建excel对象
        //require "./Public/PHPExcel/Classes/PHPExcel.php";
        $objPHPExcel = new \PHPExcel();

            //添加一个表单
        $objPHPExcel->setActiveSheetIndex(0);

        //设置表单名称
        $objPHPExcel->getActiveSheet()->setTitle("用户信息表");

        /**
         * 向表单中添加数据
         *
         * 1.表头
         * 2.数据
         */

        /**
         * 准备表头的名称
         */
        $xlsHeader = [
            'ID',
            '用户名',
            '邮箱',
            '真实姓名',
            '性别',
            '电话',
            '会员级别',
        ];

        /**
         * 准备表格列名
         */
        $cellName = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O',
            'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 'AA', 'AB', 'AC', 'AD', 'AE',
            'AF', 'AG', 'AH', 'AI', 'AJ', 'AK', 'AL', 'AM', 'AN', 'AO', 'AP', 'AQ', 'AR', 'AS',
            'AT', 'AU', 'AV', 'AW', 'AX', 'AY', 'AZ'];
        /**
         * 将表格第一行作为表格的简介行，需要合并
         */
        //>>1.获取需要合并多少列
        $column_count = count($xlsHeader);
        //>>2.合并第一行的三列
        $objPHPExcel->getActiveSheet()->mergeCells("A1:" . $cellName[$column_count - 1] . "1");
        //>>3.设置合并后的内容
        $objPHPExcel->getActiveSheet()->setCellValue("A1", "用户信息统计  创建时间：" . date("Y-m-d"));

        /**
         * 表格第二行开始设置表头
         */
        foreach ($xlsHeader as $k => $v) {
            $objPHPExcel->getActiveSheet()->setCellValue($cellName[$k] . "2", $v);
        }

        /**
         * 表格第三行开始添加表格数据
         */
        foreach ($mss as $k => $v) {
            //获取当前多少行
            $line = 3 + $k;
            $i = 0;
            foreach ($v as $key => $value) {
                $objPHPExcel->getActiveSheet()->setCellValue($cellName[$i] . $line, $value);
                ++$i;
            }
        }
        //导出excel
        $xlsname = iconv("utf-8", "gb2312", "用户信息表");

        // Redirect output to a client’s web browser (Excel5)
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $xlsname . '.xls"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');

        // If you're serving to IE over SSL, then the following may be needed
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0

        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
        exit;

    }

    


}