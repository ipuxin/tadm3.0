<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*** 拼团类 ***

创建 2016-01-30 刘深远 

*** ***/

class Team_model extends MY_Model {

	private $_model;
	
	public function __construct(){
		parent::__construct();
		$this->init();
	}

	function init(){
		parent::init();
		$this->setTable('Team');

		$TypeArr = array(
			1 => '等待团长支付',
			2 => '正在拼团',
			3 => '拼团完成',
			4 => '拼团失败',
			5 => '取消拼团'
		);

		$this->_model = array(
			'TeamStatus' => $TypeArr,
			'TeamId' => 'orderId',
			'ShopId' => 'id',
			'CityCode' => 'str',
			'CityName' => '',
			'ProductId' => 'id',
			'ProductRealId' => '',
			'ProductInfo' => array('ProductName','Prices','Description','freightAmout','DeliverAddress'),
			'LotteryCount' => 'num',
			'Members' => array(
				array('OrderId','OrderCreateDate','IsNewMember','UserId','NickName','Thnumbail')
			),
			'MaxOrderCount' => 'num',
			
			'TeamHeaderDeliveryInfo' => array(), //团长收货信息
			//'AgentId' => 'num',
			//'ActiveMsg' => 'array', //活动产品提示信息
			//'ProductRelation' => 'array', //关联产品
			//'CouponGroup' => 'id', //优惠券包id
			'CreatTime' => 'time',
			'EndTime' => 'time'
		);
	}

	function getTeamList($arr,$order=array(),$limit,$sel){
		if(!$order)$order = array('CreatTime','DESC');
		if($this->session->userdata('UserType')==2){
			$CityCode = $this->session->userdata('CityCode');
			$CityCodeNum = intval($CityCode);
			$arr['CityCode'] = $CityCodeNum;
		}

		if($this->session->userdata('UserType')==3){
			$ShopId = $this->session->userdata('ShopRealId');
			$arr['ShopId'] = $ShopId;
		}

		if($this->session->userdata('UserType')==4){
			$this->load->model('shop_model');
			$shopIds = $this->shop_model->InvitationCodeShopIds();
			$arr['ShopId'] = $shopIds;
		}

		$list = $this->getList($arr,$order,$limit,$sel);
		$list = $this->resetTeamList($list);
		$data['List'] = $list;
		$data['Count'] = $this->_return_Count;
		$data['Limit'] = $this->_return_Limit;
		$data['Skip'] = $this->_return_Skip;
		return $data;
	}
	
	//设置拼团抽奖
	function lotteryTeam($team){

		//$team = $this->getTeamInfo($teamInfo);

		$num = $team['LotteryCount'];
		$all = $team['MaxOrderCount'];
		$order = $team['Orders'];
		if($num && $num>0){
			$arr = array(); 
			if($num>=$all)$num = $all - 1;
			while(count($arr)<$num)$arr[rand(0,$all-1)]=null;
			$arr = array_keys($arr);
			for($i=0;$i<$all;$i++){
				if(in_array($i,$arr)){
					$order[$i]['Lottery'] = 1;
				}else{
					$arrTuikuan[] = $order[$i];
				}
			}
		}
		$this->updTeam($team['id'],array('Orders'=>$order));
		return $arrTuikuan;
	}

	function updTeam($where,$arr){
		//$arr = $this->setModel($arr);
		if($updnum = $this->update($where,$arr)){
			$Data['Num'] = $updnum;
		}else{
			$Data['ErrorCode'] = 3;
		}
		return $Data;
	}

	function getTeamInfo($teamId){
		$team = $this->getRow(array('TeamId'=>$teamId));
		$team = $this->resetTeam($team);
		return $team;
	}

	function getTeamUserInfo($members){
		foreach($members as $v){
			$orderIds[] = $v['OrderId'];
		}
		$orderIds = implode(',',$orderIds);
		$orderSelArr['OrderId'] = '['.$orderIds.']'; 

		$this->load->model('order_model');
		$orderSel = array('OrderStatus','DeliveryInfo','OrderId');
		$order = $this->order_model->getOrderList($orderSelArr,'','',$orderSel);
		$orders = $order['List'];
		foreach($orders as $v){
			$orderList[$v['OrderId']] = $v;
		}
		foreach($members as $k=>$v){
			$order = $orderList[$v['OrderId']];
			$members[$k]['OrderStatusMsg'] = $order['OrderStatusMsg'];
			$members[$k]['RealName'] = $order['DeliveryInfo']['RealName'];
			$members[$k]['Mobile'] = $order['DeliveryInfo']['Mobile'];
			$members[$k]['OrderRealId'] = $order['id'];
			$members[$k]['CreatDate'] = date('Y-m-d H:i:s',$v['OrderCreatTime']);
		}
		return $members;
	}

	function resetTeamList($list){
		if($list)foreach($list as $v){
			$listReturn[] = $this->resetTeam($v);
		}
		return $listReturn;
	}

	function resetTeam($team){
		$team['LastMemberNum'] = $team['MaxOrderCount'] - count($team['Members']);
		$team['ProductName'] = $team['ProductInfo']['ProductName'];
		$team['TeamStatusMsg'] = $this->_model['TeamStatus'][$team['TeamStatus']];
		$team['CreatDate'] = date('Y-m-d H:i:s',$team['CreatTime']);
		$team['EndDate'] = date('Y-m-d H:i:s',$team['EndTime']);
		return $team;
	}

	function getTeamFromId($id){
		$team = $this->getRow($id);
		return $team;
	}

	function setModel($arr){
		if(!$arr['ProductName'])return 301;
		if(!$arr['AgentId']){$arr['AgentId']=$this->_AgentId;}
		if(!$arr['AgentId'])return 5;
		if(!$arr['ProductId'])$arr['ProductId'] = $this->getMax('ProductId')+1;
		return $arr;
	}
	
}