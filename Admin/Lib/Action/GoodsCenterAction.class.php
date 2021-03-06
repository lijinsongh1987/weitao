<?php
class GoodsCenterAction extends BaseAction {
	public function goodsmanager(){
		import("ORG.Util.Page");
		if($_GET['gid']){
			$and .=" and a.gid = '".$_GET['gid']."'";
		}
		if($_GET['starttime']){
			$starttime= $_GET['starttime'];
			$and .= " and a.update_time >= '".$starttime." 00:00:00'";
		}
		if($_GET['endtime']){
			$endtime= $_GET['endtime'];
			$and .= " and a.update_time <= '".$endtime." 23:59:59'";
		}
		if($_GET['pricegt']){
			$and .=" and a.Price >= ".$_GET['pricegt'];
		}
		if($_GET['pricelt']){
			$and .=" and a.Price <= ".$_GET['pricelt'];
		}
		if ($_GET['shaixuanid']) {
			if ($_GET['type']) {
				$and .= " and a.Cid = ".$_GET['type'];
			}
			if ($_GET['status']) {
				$status = $_GET['status'] - 1;
				$and .= " and a.status = ".$status;
			}
			if($_GET['tstatus']){
				$tstatus = $_GET['tstatus'] - 1;
				$and .=" and a.tuistatus >= ".$tstatus;
			}
			if($_GET['tehuistatus']){
				$tehuistatus = 0;
				if ($_GET['tehuistatus'] == 1) {
					$tehuistatus = 1;
				} else {
					$tehuistatus = 0;
				}
				$and .=" and a.tehuistatus = ".$tehuistatus;
			}
			if($_GET['salergt']){
				$and .=" and a.Sales_num >= ".$_GET['salergt'];
			}
			if($_GET['salerlt']){
				$and .=" and a.Sales_num <= ".$_GET['salerlt'];
			}
		}
		$totalRows = M()->table("wt_goods a,wt_goodstype b")->where("a.Cid = b.tkid ".$and)->count();
		$page= new Page($totalRows,20);
		$this->list=M()->table("wt_goods a,wt_goodstype b")->where("a.Cid = b.tkid ".$and)->limit($page->firstRow,$page->listRows)->order("update_time desc")->field("a.*,b.typename")->select();
		$this->page= $page->show();
		$this->shaixuanid = $_GET['shaixuanid'];
		$this->typelist = M("goodstype")->select();
		$urllist = M("url")->find();
		$this->jumpid = $urllist['id'];
		$this->jumpstatus = $urllist['status'];
		$this->display();
	}
	
	public function tuijiantrue(){
		$data['id'] = $_POST['id'];
		$data['status'] = $_POST['status'];
		if ($_POST['status'] == 1) {
			$data['xiajiatime'] = date("Y-m-d H:i:s");
		} else {
			$data['xiajiatime'] = date("Y-m-d H:i:s");
		}
		$is = M("goods")->save($data);
		if ($is) {
			$data2['msg'] = 1;
		} else {
			$data2['msg'] = 0;
		}
		echo json_encode($data2);
	}
	
	public function batheditgoods(){
		$ids = $_POST['ids'];
		$data['status'] = $_POST['status'];
		if ($_POST['status'] == 1) {
			$data['xiajiatime'] = date("Y-m-d H:i:s");
		} else {
			$data['xiajiatime'] = date("Y-m-d H:i:s");
		}
		$is = M("goods")->where("id in (".$ids.")")->save($data);
		if ($is) {
			$data2['msg'] = 1;
		} else {
			$data2['msg'] = 0;
		}
		echo json_encode($data2);
	}
	
	public function updategoodsinfo(){
		$data['id'] = $_POST['id'];
		$data['D_title'] = $_POST['dtitle'];
		$data['Pic'] = $_POST['pic'];
		$data['Cid'] = $_POST['typeid'];
		$data['Org_Price'] = $_POST['orgprice'];
		$data['Sales_num'] = $_POST['salesnum'];
		$data['SellerID'] = $_POST['sellerid'];
		$data['isTmall'] = $_POST['isTmall'];
		$data['status'] = $_POST['saleFlag'];
		$data['Quan_id'] = $_POST['quanid'];
		$data['Quan_price'] = $_POST['quanprice'];
		$data['Commission_jihua'] = $_POST['Commission_jihua'];
		$data['Commission_queqiao'] = $_POST['Commission_queqiao'];
		$is = M("goods")->save($data);
		if ($is) {
			$data2['msg'] = 1;
		} else {
			$data2['msg'] = 0;
		}
// 		$data2['sql'] = M()->getLastSql();
		echo json_encode($data2);
	}
	
	public function editurl(){
		$data['id'] = $_POST['id'];
		$data['status'] = $_POST['status'];
		$data['updatetime'] = date("Y-m-d H:i:s");
		$is = M("url")->save($data);
		if ($is) {
			$data['msg'] = 1;
		} else {
			$data['msg'] = 0;
		}
		echo json_encode($data);
	}
	public function tuijiangoods(){
		$data['id'] = $_POST['id'];
		$data['tuistatus'] = $_POST['status'];
		$is = M("goods")->save($data);
		if ($is) {
			$data2['msg'] = 1;
		} else {
			$data2['msg'] = 0;
		}
		echo json_encode($data2);
	}
	
	public function bathedittuijian(){
		$ids = $_POST['ids'];
		$data['tuistatus'] = $_POST['status'];
		$is = M("goods")->where("id in (".$ids.")")->save($data);
		if ($is) {
			$data2['msg'] = 1;
		} else {
			$data2['msg'] = 0;
		}
		echo json_encode($data2);
	}
	
	public function edittehuistatus(){
		$data['id'] = $_POST['id'];
		$data['tehuistatus'] = $_POST['status'];
		if ($_POST['status'] == 1) {
			$data3['tehuistatus'] = 0;
			M("goods")->where("id is not null")->save($data3);
		}
		$is = M("goods")->save($data);
		if ($is) {
			$data2['msg'] = 1;
		} else {
			$data2['msg'] = 0;
		}
		echo json_encode($data2);
	}
}
?>