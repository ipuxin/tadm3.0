<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Team extends MY_Controller {

	public function __construct(){
		parent::__construct();
		$this->checkQuanxian('team');
		$this->load->model('team_model');
	}

	public function getTeamList(){
		$this->checkPurview('teamList');
		extract($this->input->post());

		if($TeamId)$arr['TeamId'] = $TeamId;
		if($TeamStatus)$arr['TeamStatus'] = $TeamStatus;
		if($ProductId)$arr['ProductId'] = $ProductId;
		if($ProductName)$arr['ProductInfo.ProductName'] = "%*".$ProductName."*%";
		if($CityName)$arr['CityName'] = "%*".$CityName."*%";
		if($ShopName)$arr['ShopName'] = "%*".$ShopName."*%";
		if($ProductType>0)$arr['ProductType'] = $ProductType;
		
		$sel = array('TeamId','CreatTime','EndTime','Members','MaxOrderCount','ProductId','ProductInfo','CityName','CityCode','TeamStatus','LotteryCount');

		$teamList = $this->team_model->getTeamList($arr,array('CreatTime','DESC'),array($perpage,($page-1)*$perpage),$sel);
		$res['TeamList'] = $teamList['List'];
		$res['Count'] = $teamList['Count'];
		$res['PerPage'] = $teamList['Limit'];
		$this->returnJson($res);
	}

}