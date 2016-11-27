<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*** 快递类 ***

创建 2016-02-27 刘深远 

*** ***/

class Kuaidi_model extends MY_Model {

	private $_model;
	
	public function __construct(){
		parent::__construct();
		$this->init();
	}

	function init(){
		parent::init();
		$this->setTable('Kuaidi');
		$this->_model = array(
			'Code' => 'str',
			'Name' => 'str',
			'Show' => 'num'
		);
	}

	function getKuaidiList($arr = array()){
		$list = $this->getList($arr,array('Code','ASC'));
		return $list;
	}

	function getShopKuaidiList($ShopId = ''){
		$arr = array('ShopId'=>$ShopId);
		$this->setTable('ShopKuaidi');
		$list = $this->getList($arr,array('Code','ASC'));
		return $list;
	}

	function updKuaidiList($arr){
		foreach($arr as $v){
			$this->updKuaidi($v);
		}
		return;
	}

	function addKuaidi($arr){
		$this->setTable('ShopKuaidi');
		$order = $this->add($arr);
	}

	function delKuaidi($arr){
		$this->setTable('ShopKuaidi');
		$this->del($arr);
	}

	function updKuaidi($arr){
		if($updnum = $this->update($arr['id'],$arr)){
			$Data['Num'] = $updnum;
		}else{
			$Data['ErrorCode'] = 3;
		}
		return $Data;
	}

	/*function addKuaidi($arr){
		$updnum = $this->add($arr);
	}*/

}