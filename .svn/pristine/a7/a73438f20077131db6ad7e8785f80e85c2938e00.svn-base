<?php

class AdminBackstageAction extends BaseAction{
    
    public function mediaSetup(){
        import("ORG.Util.Page");
        if($_POST['medianame']){
            $medianame = $_POST['medianame'];
            $and .=" and medianame = '".$medianame."'";
        }
        $totalRows = M()->table('s_media')->where ("id is not null".$and)->count();
        $birthdaypage = new Page($totalRows,5);
        $this->medialist=M()->table('s_media')->where ("id is not null".$and)->limit($birthdaypage->firstRow,$birthdaypage->listRows)->select();
        $this->birthdaypage= $birthdaypage->show();
        
        
        
        $this->medianamelist=M()->table('s_media')->select();
        
        
        if($_POST['shopperinquire']){
            $shopperinquire= $_POST['shopperinquire'];
            $andshopper .=" and (b.shoppername ='".$shopperinquire."' or b.shopperid = '".$shopperinquire."')";
        }
        $shopper= M()->table('s_media a,s_shopper b')->where ("b.mid = a.id".$andshopper)->field("b.*,a.medianame")->count();
        $shopperpage = new Page($shopper,5);
        $this->shopperlist=M()->table('s_media a,s_shopper b')->where ("b.mid = a.id".$andshopper)->field("b.*,a.medianame")->limit($shopperpage->firstRow,$shopperpage->listRows)->select();
        $this->shopperpage= $shopperpage->show();
        
        
        if($_POST['adinquire']){
            $adinquire= $_POST['adinquire'];
            $andad .=" and (b.adname ='".$adinquire."' or b.adnameid = '".$adinquire."')";
        }
        $ad= M()->table('s_shopper a,s_ad b')->where ("b.sid = a.id".$andad)->field("b.*,a.shoppername")->count();
        $adpage = new Page($ad,5);
        $this->adlist=M()->table('s_shopper a,s_ad b')->where ("b.sid = a.id".$andad)->field("b.*,a.shoppername")->limit($adpage->firstRow,$adpage->listRows)->select();
        $this->adpage= $adpage->show();
        
        $this->shoppernamelist=M()->table('s_shopper')->select();
        
        $this->display();
        
    }
    
    public function addmediaSetup(){
        if($_POST['addmedianame']){
            $data['medianame'] = $_POST['addmedianame'];
            $data['createtime'] = date('Y-m-d');
            $media = M('media')->where("medianame = '".$_POST['addmedianame']."'")->find();
            if(!$media){
            	$is = M('media')->add($data);
            	if($is){
            		$data['data'] = 0;
            	}else{
            		$data['data'] = 1;
            	}
            }else{
            	$data['data'] = 3;
            }
            
        }else{
            $data['data'] = 2;
        }
        $this->ajaxReturn($data);
    }
    
    
    public function addshopper(){
        if($_POST['shoppername']){
            $data['shopperid'] = $_POST['shopperid'];
            $data['shoppername'] = $_POST['shoppername'];
            $shoppername = $_POST['shoppername'];
            $data['mid'] = $_POST['mid'];
            $mid = $_POST['mid'];
            $data['createtime'] = date('Y-m-d');
            $s = M()->table('s_media')->where ("id = '".$mid."'")->select();
            $account = $s[0]['medianame'];
            $data['pingjie'] = "mm_".$account."_".$shopperid."";
            $shopperid = M('shopper')->where("shopperid = '".$_POST['shopperid']."'")->find();
            if(!$shopperid){
            	if($s){
            		$is = M('shopper')->add($data);
            		if($is){
            			$data['data'] = 0;
            		}else{
            			$data['data'] = 1;
            		}
            	}else{
            		$data['data'] = 2;
            	}
            }else{
            	$data['data'] = 4;
            }
            
        }else{
            $data['data'] = 3;
        }
        $this->ajaxReturn($data);
    }
    
    public function modifyshopper(){
    	if($_POST['shoppername']){
    		$data['shoppername'] = $_POST['shoppername'];
    		$s = M('shopper')->where("id = '".$_POST['id']."'")->save($data);
    		if($s){
    			$data['data'] = '修改成功';
    		}else{
    			$data['data'] = '修改失败';
    		}
    	}
    	$this->ajaxReturn($data);
    }
    
    public function modifyadd(){
    	if($_POST['adname']){
    		$data['adname'] = $_POST['adname'];
    		$s = M('ad')->where("id = '".$_POST['id']."'")->save($data);
    		if($s){
    			$data['data'] = '修改成功';
    		}else{
    			$data['data'] = '修改失败';
    		}
    	}
    	$this->ajaxReturn($data);
    }
    
    public function addad(){
        if($_POST['adname']){
            $data['adnameid'] = $_POST['adnameid'];
            $data['adname'] = $_POST['adname'];
            $adnameid= $_POST['adnameid'];
            $data['sid'] = $_POST['sid'];
            $sid = $_POST['sid'];
            $data['createtime'] = date('Y-m-d');
            $s = M()->table('s_shopper')->where ("id = '".$sid."'")->order('id DESC')->select();
            $account = $s[0]['pingjie'];
            $data['pingjie'] = "".$account."_".$adnameid."";
            $adddate = M('ad')->where("adnameid = '".$_POST['adnameid']."'")->find();
            if(!$adddate){
            	if($s){
            		$is = M('ad')->add($data);
            		if($is){
            			$data['data'] = 0;
            		}else{
            			$data['data'] = 1;
            		}
            	}else{
            		$data['data'] = 2;
            	}
            }else{
            	$data['data'] = 4;
            }
            
        }else{
            $data['data'] = 3;
        }
        $this->ajaxReturn($data);
    }
    
    
    
//     public function addexcel(){
//         $id = $_POST['excelshopperid'];
//         $slist = M("shopper")->where("id = ".$id)->select();
//         if ($slist) {
//             import("ORG.Util.PHPExcel");
//             $filePath = $_FILES['adexcel']['tmp_name'];
//             $filePath = iconv("UTF-8", "gb2312", $filePath);
//             $PHPExcel = new PHPExcel();
//             /**默认用excel2007读取excel，若格式不对，则用之前的版本进行读取*/
//             $PHPReader = new PHPExcel_Reader_Excel2007();
//             if(!$PHPReader->canRead($filePath)){
//                 $PHPReader = new PHPExcel_Reader_Excel5();
//                 if(!$PHPReader->canRead($filePath)){
//                     echo 'no Excel';
//                     return ;
//                 }
//             }
//             $PHPExcel = $PHPReader->load($filePath);
//             /**读取excel文件中的第一个工作表*/
//             $currentSheet = $PHPExcel->getSheet(0);
//             /**取得最大的列号*/
//             $allColumn = $currentSheet->getHighestColumn();
//             /**取得一共有多少行*/
//             $allRow = $currentSheet->getHighestRow();
//             $arr=array(
//                     0=>'A'
//             );
//             //print_r($arr);exit;
//             //括号里（3，2）代表着3代表列，这里的列要减1,2代表那一行
//             //$val = $currentSheet->getCellByColumnAndRow(2,2)->getValue();
//             //echo $val;exit;
//             $newArr=array();
//             $adid="";
//             $zhid="";
//             $sql = "insert into s_ad(adname,sid,createtime,pingjie,status,dailisatus) values";
//             $createtime = date('Y-m-d');
//             $status = 0;
//             $dailisatus = 0;
//             $userid = $_SESSION['user'][0]['id'];
//             $sid = $slist[0]['id'];
//             $pingjie = $slist[0]['pingjie'];
//             $pingjie = $slist[0]['pingjie'];
//             /**从第二行开始输出，因为excel表中第一行为列名*/
//             for($currentRow = 2;$currentRow <= $allRow;$currentRow++){
//                 $lastping = "";
//                 /**从第A列开始输出*/
//                 $adname = $currentSheet->getCellByColumnAndRow(0,$currentRow)->getValue();
//                 $parts=split("_",$adname);
//                 $data['141'] = $parts[3];
//                 $lastping = $pingjie."_".$adname;
//                 $sql .= "('".$adname."',".$sid.",'".$createtime."','".$lastping."',".$status.",".$dailisatus."),";
//             }
//             $sql = substr($sql,0,-1);//$parts=split($pattern,$expression,$limit);
//             $is = M('adid')->query($sql);
//             if (!$is) {
//                 $_SESSION['msg'] = '导入成功';
//             } else {
//                 $_SESSION['msg'] = '导入失误';
//             }
//         } else {
//             $_SESSION['msg'] = '导购位有误';
//         }
//         $this->redirect("AdminBackstage/mediaSetup");
//     }
    
    public function updatezhaopin(){
        $data['id'] = $_POST['id'];
        if ($_POST['findstatus'] == 0) {
            $data['findstatus'] = 1;
        } else if ($_POST['findstatus'] == 1) {
            $data['findstatus'] = 0;
        }
        $is = M("shopper")->save($data);
        if ($is) {
            $data2['msg'] = 1;
        } else {
            $data2['msg'] = 0;
        }
        echo json_encode($data2);
    }
    
    
}

?>