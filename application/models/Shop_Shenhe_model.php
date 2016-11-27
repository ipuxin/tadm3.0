<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*** 店铺申请类 ***

创建 2016-08-01 刘深远 

*** ***/

class Shop_Shenhe_model extends MY_Model {

	private $_model;
	private $_order_choucheng;
	
	public function __construct(){
		parent::__construct();
		$this->init();
	}

	function init(){
		parent::init();
		$this->setTable('Shop_Shenhe');
	}

	function getShopList($arr,$order=array(),$limit=array(),$sel=array()){
		if(!$order)$order = array('CreatTime','DESC');

		if($this->session->userdata('UserType')==2){
			$CityCode = $this->session->userdata('CityCode');
			$CityCodeNum = intval($CityCode);
			$arr['CityCode'] = $CityCodeNum;
		}

		$list = $this->getList($arr,$order,$limit,$sel);
		if($list)$list = $this->resetShopList($list);
		$data['List'] = $list;
		$data['Count'] = $this->_return_Count;
		$data['Limit'] = $this->_return_Limit;
		$data['Skip'] = $this->_return_Skip;
		return $data;
	}

	function resetShopList($list){
		foreach($list as $k=>$v){
			$list[$k] = $this->resetShop($v);
		}
		return $list;
	}

	function resetShop($arr){
		if($arr['CreatTime'])$arr['CreatTimeDate'] = date('Y-m-d H:i:s',$arr['CreatTime']);
		return $arr;	
	}
	
	function getShop($arr,$sel = array()){
		$shop = $this->getRow($arr,$sel);
		if($shop){
			$shop = $this->resetShop($shop);
			return $shop;
		}else{
			return false;
		}
	}
}