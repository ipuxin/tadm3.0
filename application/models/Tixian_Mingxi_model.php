<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*** 提现明细现类 ***/

class Tixian_Mingxi_model extends MY_Model {

	private $_model;
	
	public function __construct(){
		parent::__construct();
		$this->init();
	}

	function init(){
		parent::init();
		$this->setTable('Tixian_Mingxi');

		$this->_model = array(
			'UserId' => 'num', //用户编号
			'UserType' => 'str', //用户状态 店铺 合伙人
            'Account'=>'str',//提现账号
			'AccountType' => 'str', //账号类型(支付宝)
            'AmountApply' => 'num', //申请转账金额
			'AmountReal' => 'num', //实际转账金额
            'Charge'=>'num',    //手续费
            'beforAmount' => 'num',//提现前金额
            'afterAmount' => 'num',//提现后金额
			'IP' => 'str', //转帐时的IP
            'result' => '商户提现', //转帐原因
			'CreatTime' => 'time', //申请时间
		);
	}

	function getTxMXList($arr,$order=array('CreatTime','DESC'),$limit=array(),$sel=array()){
		if($this->session->userdata('UserType')==2 || $this->session->userdata('UserType')==3){
			$arr['UserId'] = $this->session->userdata('UserId');
		}
		$list = $this->getList($arr,$order,$limit,$sel);
		if($list)$list = $this->resetTxList($list);
		$data['List'] = $list;
		$data['Count'] = $this->_return_Count;
		$data['Limit'] = $this->_return_Limit;
		$data['Skip'] = $this->_return_Skip;
		return $data;
	}

	function resetTxMXList($list){
		foreach($list as $k=>$v){
			$rlist[] = $this->resetTx($v);
		}
		return $rlist;
	}

	function resetTxMX($arr){
		if($arr['CreatTime'])$arr['CreatTimeDate'] = date('Y-m-d H:i:s',$arr['CreatTime']);
		if($arr['UpdateTime'])$arr['UpdateTimeDate'] = date('Y-m-d H:i:s',$arr['UpdateTime']);
		return $arr;	
	}

	function addTixianMX($arr){
		if($Tixian = $this->add($arr)){
			$Data['Tixian'] = $Tixian;
		}else{
			$Data['ErrorCode'] = 4;
		}
		return $Data;
	}

	function updTixianMX($arr,$where= array()){
		if($updnum = $this->update($where,$arr)){
			$Data['Num'] = $updnum;
		}else{
			$Data['ErrorCode'] = 3;
		}
		return $Data;
	}

	function setModelMX($arr,$type='add'){
		if($type=='add'){
			$arr['CreatTime'] = time();
		}
		return $arr;
	}
	
}