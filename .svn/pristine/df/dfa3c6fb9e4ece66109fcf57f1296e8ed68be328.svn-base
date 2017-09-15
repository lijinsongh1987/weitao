<?php
include "wechat/wechat.class.php";
include "wechat/WxMchPayHelper.php";

// 本类由系统自动生成，仅供测试用途
class BaseAction extends Action
{
	//权限控制和登陆验证
	public function _initialize()
	{
		if (!isset($_SESSION['username'])) {
			$this->redirect("Login/login");
		}
		if (!isset($_SESSION['user'][0]['id'])) {
		    $this->redirect("Login/login");
		}
		
		//------------------查询菜单----------------
		$menus = "";
		if (!isset($_SESSION['menu'])) {
			$parent = array();
			if ($_SESSION['user'][0]['username'] != 'admin') {
				$mid = M()->table("s_user a,s_role b,s_access c")->where("a.roleid = b.id and b.id = c.roleid and a.username != 'admin' and a.id = ".$_SESSION['user'][0]['id'])->field("c.mid")->order("b.id asc")->select();
			    $ids = "";
			    for ($i = 0; $i < count($mid);$i++) {
			        if ($i < (count($mid) - 1)) {
			            $ids .= $mid[$i]['mid'].",";
			        } else if ($i == (count($mid) - 1)) {
			            $ids .= $mid[$i]['mid'];
			        }
			    }
			    $menus = M('menu')->where("id in (".$ids.")")->order('id asc')->select();
			} else {
				$menus = M('menu')->order('id asc')->select();
			}
			for ($i=0;$i<count($menus);$i++){
				if($menus[$i]['parentid'] == 0){
					array_push($parent,$menus[$i]);
				}
			}
			for ($i=0;$i<count($parent);$i++){
				$arr= array();
				for ($j=0;$j<count($menus);$j++){
					if(($menus[$j]['parentid'] == $parent[$i]['id']) && $menus[$j]['parentid'] !=0){
						array_push($arr,$menus[$j]);
					}
				}
				$parent[$i]['sub'] = $arr;
			}
			$_SESSION['menu'] = $parent;
		}
		
		
		$this->menus = $_SESSION['menu'];
		$this->curl = $_SERVER['QUERY_STRING'];
		
		//------------------查询菜单----------------
		
	}
	
	
	public function toastr($type, $msg, $showtime = 1000)
	{
		$tostr['type'] = $type;
		$tostr['msg'] = $msg;
		$tostr['showtime'] = $showtime;
		$_SESSION['toastr'] = $tostr;
	}
	
	
	/**
	 * 生成带userid的永久二维码
	 * @param unknown $userid
	 */
	public function getQrCode($posterPath,$userid){
		
		$options = array(
				'token'=>C('token'),
				'encodingaeskey'=>C('encodingaeskey'),
				'appid'=>C('appid'),
				'appsecret'=>C('appsecret')
		);
		
		$weObj = new Wechat($options);
		$tickArr = $weObj->getQRCode("u_".$userid, 2, 2592000);
		//获取到二维码的http地址
		$qrUrl = 'http://mp.weixin.qq.com/cgi-bin/showqrcode?ticket='.urlencode($tickArr['ticket']);
		$qrName = 'u_'.$userid.'_'.$this->msectime();
		$qrPath = './poster/qr/'.$qrName.'.jpg';
		
		$content = file_get_contents($qrUrl);
		file_put_contents($qrPath, $content);
		
		//等比例压缩图片
		list($width, $height) = getimagesize($qrPath);
		$new = imagecreatetruecolor(180, 180);
		$img = imagecreatefromjpeg($qrPath);
		imagecopyresized($new, $img,0, 0,0, 0,180, 180, $width, $height);//copy部分图像并调整
		imagejpeg($new, $qrPath);//图像输出新图片、另存为
		imagedestroy($new);
		imagedestroy($img);
		
		//合并图片
		return $this->merge($posterPath, $qrPath,$qrName);
	}
	
	
	/**
	 * 生成带userid的永久二维码 没有海报
	 * @param unknown $userid
	 */
	public function getNoBgQrCode($userid){
		
		$options = array(
				'token'=>C('token'),
				'encodingaeskey'=>C('encodingaeskey'),
				'appid'=>C('appid'),
				'appsecret'=>C('appsecret')
		);
		
		$weObj = new Wechat($options);
		$tickArr = $weObj->getQRCode("u_".$userid, 2, 2592000);
		//获取到二维码的http地址
		$qrUrl = 'http://mp.weixin.qq.com/cgi-bin/showqrcode?ticket='.urlencode($tickArr['ticket']);
		$qrName = 'u_'.$userid.'_'.$this->msectime();
		$qrPath = './poster/qr/'.$qrName.'.jpg';
		
		$content = file_get_contents($qrUrl);
		file_put_contents($qrPath, $content);
		
		return $qrPath;
	}
	
	
	/**
	 * 合并图片
	 * @param  $bigImgPath
	 * @param  $qCodePath
	 * @return string
	 */
	private function merge($bigImgPath,$qCodePath,$qrName){
		
		$bigImg = imagecreatefromstring(file_get_contents($bigImgPath));
		$qCodeImg = imagecreatefromstring(file_get_contents($qCodePath));
		//文字
		/*
		 $font = './msyhl.ttc';//字体
		 $black = imagecolorallocate($bigImg, 45, 152, 62);//字体颜色 RGB
		 $fontSize = 20;   //字体大小
		 $circleSize = 0; //旋转角度
		 $left = 50;      //左边距
		 $top = 150;       //顶边距
		 imagefttext($bigImg, $fontSize, $circleSize, $left, $top, $black, $font, '我饿哦饿哦饿哦放到我我我我我防守打法');
		 */
		list($qCodeWidth, $qCodeHight, $qCodeType) = getimagesize($qCodePath);
		
		imagecopymerge($bigImg, $qCodeImg, 545,873, 0, 0, 180, 180, 100);//234, 234  495,830
		list($bigWidth, $bigHight, $bigType) = getimagesize($bigImgPath);
		
		$qrPath = './poster/'.$qrName;
		$path = '/poster/'.$qrName;
		switch ($bigType) {
			case 1:
				imagegif($bigImg, $qrPath.".gif");
				$path= $path.".gif";
				break;
			case 2:
				imagejpeg($bigImg, $qrPath.".jpg");
				$path= $path.".jpg";
				break;
			case 3:
				imagepng($bigImg, $qrPath.".png");
				$path= $path.".png";
				break;
			default:
				break;
		}
		
		imagedestroy($bigImg);
		imagedestroy($qcodeImg);
		return $path;
		
	}
	
	/**
	 * 生成微信菜单
	 * @param  $data  = '{"button":[{"type":"view","name":"音乐","url":"http://music.163.com"}]}';
	 * @return string
	 *
	 *  $data =  array(
	 *   		"button"=>
	 *   			array(
	 *   				array('type'=>'click','name'=>'最新消息','key'=>'MENU_KEY_NEWS'),
	 *   				array('type'=>'view','name'=>'我要搜索','url'=>'http://www.baidu.com'),
	 *   				)
	 *  		);
	 */
	public function weixin_menu($data){
		
		$options = array(
				'token'=>C('token'),
				'encodingaeskey'=>C('encodingaeskey'),
				'appid'=>C('appid'),
				'appsecret'=>C('appsecret')
		);
		
		$weObj = new Wechat($options);
		return $weObj->createMenu($data);
		
	}
	
	
	/**
	 * 获取Unix毫秒时间戳
	 * @return float Unix毫秒时间戳
	 */
	function msectime(){
		list($msec, $sec) = explode(' ', microtime());
		return (float)sprintf('%.0f', (floatval($msec) + floatval($sec)) * 1000);
	}
	
	/**
	 * 下载图片（二维码）
	 * @param unknown $remote
	 * @param unknown $local
	 */
	private function curlDownload($remote,$local) {
		
		$cp = curl_init($remote);
		$fp = fopen($local,"wb");
		curl_setopt($cp, CURLOPT_FILE, $fp);
		curl_setopt($cp, CURLOPT_HEADER, 0);
		curl_exec($cp);
		curl_close($cp);
		fclose($fp);
	}
	
	
	public function pay($openId,$money,$note){
		// 企业转账给个人
		$param = array(
				"nonce_str" => $this->randCode(30),//随机字符串，不长于32位
				"mchid" => C("mchid"),//商户号
				"mch_appid" =>  C("appid"),
				"partner_trade_no" => $this->orderNum(),//商户订单号，需保持唯一性(只能是字母或者数字，不能包含有符号)
				"openid" => $openId,
				"amount" => $money,//付款金额，单位分
				"check_name" => 'NO_CHECK',	//校验用户姓名选项  NO_CHECK：不校验真实姓名FORCE_CHECK：强校验真实姓名
				"re_user_name" => '',	//收款用户姓名(可选) 如果check_name设置为FORCE_CHECK，则必填用户真实姓名
				"spbill_create_ip" => '127.0.0.1',//调用接口的机器 Ip 地址
				"desc" => $note,//备注信息
		);
		$wxMchPayHelper = new WxMchPayHelper($param);
		return $wxMchPayHelper->transfers();
	}
	
	
	/**
	 +----------------------------------------------------------
	 * 生成随机字符串
	 +----------------------------------------------------------
	 * @param int       $length  要生成的随机字符串长度
	 * @param string    $type    随机码类型：0，数字+大小写字母；1，数字；2，小写字母；3，大写字母；4，特殊字符；-1，数字+大小写字母+特殊字符
	 +----------------------------------------------------------
	 * @return string
	 +----------------------------------------------------------
	 */
	function randCode($length = 5, $type = 0) {
		$arr = array(1 => "0123456789", 2 => "abcdefghijklmnopqrstuvwxyz", 3 => "ABCDEFGHIJKLMNOPQRSTUVWXYZ", 4 => "~@#$%^&*(){}[]|");
		if ($type == 0) {
			array_pop($arr);
			$string = implode("", $arr);
		} elseif ($type == "-1") {
			$string = implode("", $arr);
		} else {
			$string = $arr[$type];
		}
		$count = strlen($string) - 1;
		$code = '';
		for ($i = 0; $i < $length; $i++) {
			$code .= $string[rand(0, $count)];
		}
		return $code;
	}
	
	/**
	 * 生成订单号
	 */
	public function orderNum(){
		list($s1, $s2) = explode(' ', microtime());
		return (float)sprintf('%.0f', (floatval($s1) + floatval($s2)) * 1000).$this->randCode(10,1);
	}
	
	/**
	 * 模板消息
	 * @param unknown $openId
	 * @param unknown $url
	 * @param unknown $title
	 * @param unknown $goodsInfo
	 * @param unknown $date
	 * @param unknown $kuaidi
	 * @param unknown $number
	 * @param unknown $address
	 */
	public function sendTempMsg($openId,$url,$title,$goodsInfo,$date,$kuaidi,$number,$address){
		
		$touser = array();
		$touser['touser'] = $openId;
		$touser['template_id'] = "jP8rHHAF_9BI-Q0C7cMOa65dyiXrykiXJVZoA31Vr40";
		$touser['url'] = $url;
		$touser['topcolor'] = "#111111";
		
		$data = array();
		
		$dataValue['value'] = $title;
		$dataValue['color'] = "#111111";
		$data['first'] = $dataValue;
		
		$dataValue['value'] = $goodsInfo;
		$dataValue['color'] = "#111111";
		$data['keyword1'] = $dataValue;
		
		$dataValue['value'] = $date;
		$dataValue['color'] = "#111111";
		$data['keyword2'] = $dataValue;
		
		$dataValue['value'] = $kuaidi;
		$dataValue['color'] = "#111111";
		$data['keyword3'] = $dataValue;
		
		$dataValue['value'] = $number;
		$dataValue['color'] = "#111111";
		$data['keyword4'] = $dataValue;
		
		
		$dataValue['value'] = $address;
		$dataValue['color'] = "#111111";
		$data['keyword5'] = $dataValue;
		
		$touser['data'] = $data;
		
		$options = array(
				'token'=>C('token'),
				'encodingaeskey'=>C('encodingaeskey'),
				'appid'=>C('appid'),
				'appsecret'=>C('appsecret')
		);
		
		$we_obj = new Wechat($options);
		
		$json = $we_obj->sendTemplateMessage($touser);
		return $json;
	}
	
	
	/**
	 * 订单消息模板
	 * @param unknown $openId
	 * @param unknown $title
	 * @param unknown $messageType
	 * @param unknown $date
	 * @param unknown $remark
	 */
	public function sendMsgTemp($openId,$url,$title,$messageType,$date,$remark){
		
		$touser = array();
		$touser['touser'] = $openId;
		$touser['template_id'] = "8fss7iPkvEjtm8tosxd6hlGXTq3LwzWam4FwQzP4LY4";
		$touser['url'] = $url;
		$touser['topcolor'] = "#111111";
		
		$data = array();
		
		$dataValue['value'] = $title;
		$dataValue['color'] = "#111111";
		$data['first'] = $dataValue;
		
		$dataValue['value'] = $messageType;
		$dataValue['color'] = "#111111";
		$data['keyword1'] = $dataValue;
		
		$dataValue['value'] = $date;
		$dataValue['color'] = "#111111";
		$data['keyword2'] = $dataValue;
		
		
		$dataValue['value'] = $remark;
		$dataValue['color'] = "#111111";
		$data['remark'] = $dataValue;
		
		$touser['data'] = $data;
		
		$options = array(
				'token'=>C('token'),
				'encodingaeskey'=>C('encodingaeskey'),
				'appid'=>C('appid'),
				'appsecret'=>C('appsecret')
		);
		
		$we_obj = new Wechat($options);
		
		$json = $we_obj->sendTemplateMessage($touser);
	}
	
	
	/**
	 * 账号开通模板消息
	 * @param unknown $openId
	 * @param unknown $title
	 * @param unknown $messageType
	 * @param unknown $date
	 * @param unknown $remark
	 */
	public function sendAccountMsgTemp($openId,$url,$title,$account,$date,$remark){
		
		$touser = array();
		$touser['touser'] = $openId;
		$touser['template_id'] = "lgCz8dqT0Z7FHw8TXEJSvBVco5FAS-tVasofMuIDIUg";
		$touser['url'] = $url;
		$touser['topcolor'] = "#111111";
		
		$data = array();
		
		$dataValue['value'] = $title;
		$dataValue['color'] = "#111111";
		$data['first'] = $dataValue;
		
		$dataValue['value'] = $account;
		$dataValue['color'] = "#111111";
		$data['keyword1'] = $dataValue;
		
		$dataValue['value'] = $date;
		$dataValue['color'] = "#111111";
		$data['keyword2'] = $dataValue;
		
		
		$dataValue['value'] = $remark;
		$dataValue['color'] = "#111111";
		$data['remark'] = $dataValue;
		
		$touser['data'] = $data;
		
		$options = array(
				'token'=>C('token'),
				'encodingaeskey'=>C('encodingaeskey'),
				'appid'=>C('appid'),
				'appsecret'=>C('appsecret')
		);
		
		$we_obj = new Wechat($options);
		
		$json = $we_obj->sendTemplateMessage($touser);
	}
	
	
}