<?php

class DetailsAction extends Action{
    public function details(){
        $goodsid = $_GET['id'];
        $goodslist=M("goods")->where("id = '".$goodsid."'")->find();
        $this-> goodsimg = $goodslist['Pic'];
        $this-> IsTmall= $goodslist['IsTmall'];
        $this-> D_title= $goodslist['D_title'];
        $this-> GoodsID= $goodslist['GoodsID'];
        $this-> id= $goodslist['id'];
        $this-> Quan_link= $goodslist['Quan_link'];
        $this-> Org_Price= $goodslist['Org_Price'];
        $this-> Quan_receive= $goodslist['Quan_receive'];
        $this-> Price= $goodslist['Price'];
        $this-> Quan_price= $goodslist['Quan_price'];
        $this->url = urlencode ($goodslist['url']);
        $this->Introduce= $goodslist['Introduce'];
        $this->list=M("goods")->where("id is not null ".$and)->order("update_time desc")->limit(3)->select();
        //echo M()->getLastSql();
        $this->display();      
    }
   
}

?>