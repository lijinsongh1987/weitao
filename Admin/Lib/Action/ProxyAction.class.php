<?php 
class ProxyAction extends BaseAction{
	
	public function proxySetup(){
	    import("ORG.Util.Page");
	    if($_GET['phonenumber']){
	    	$search= $_GET['search'];
	    	$and .=" and phonenum = '".$_GET['phonenumber']."'";
	    }
	    if($_GET['starttime']){
	    	$starttime= $_GET['starttime'];
	    	$and .= " and createtime >= '".$starttime."'";
	    }
	    if($_GET['endtime']){
	    	$endtime= $_GET['endtime'];
	    	$and .= " and createtime <= '".$endtime."'";
	    }
    	$totalRows = M("applyproxy")->where("status = 0 ".$and)->count();
    	$page= new Page($totalRows,20);
    	$this->daililist=M("applyproxy")->where("status = 0 ".$and)->limit($page->firstRow,$page->listRows)->order("createtime desc")->select();
    	$this->page= $page->show();
    	$this->partlist = M("partment")->select();
    	$this->adlist = M("ad")->select();
	    $this->display();
	}
	
	public function adddaili(){
		$data3['id'] = $_POST["id"];
		$data3['status'] = 1;
		$is = M("applyproxy")->save($data3);
		$data['phonenum'] = $_POST['phonenum'];
		$data['password'] = $_POST['password'];
		$data['partid'] = $_POST['partid'];
		$data['adid'] = $_POST['adid'];
		$data['status'] = 0;
		$data['adid'] = date("Y-m-d ");
		$is = M("proxy")->add($data);
		if ($is) {
			$data2['msg'] = 1;
		} else {
			$data2['msg'] = 0;
		}
		echo json_encode($data2);
	}
	
	public function invalidproxy(){
		$data['status'] = 2;
		$data['id'] = $_POST['id'];
		$is = M("applyproxy")->save($data);
		if ($is) {
			$data2['msg'] = 1;
		} else {
			$data2['msg'] = 0;
		}
		echo json_encode($data2);
	}
}
?>