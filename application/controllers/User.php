<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends MY_Controller {

	public function __construct(){
		parent::__construct();
		$this->checkQuanxian('user');
		$this->load->model('user_model');
	}

	public function getUserList(){
		$this->checkPurview('userList');
		extract($this->input->post());
		
		if($NickName)$arr['NickName'] = "%*".$NickName."*%";
		if($RealName)$arr['Addresses.RealName'] = "%*".$RealName."*%";
		if($ProviceName)$arr['Addresses.ProviceName'] = "%*".$ProviceName."*%";
		if($CityName)$arr['Addresses.CityName'] = "%*".$CityName."*%";
		if($DistrictName)$arr['Addresses.DistrictName'] = "%*".$DistrictName."*%";
		if($Address)$arr['Addresses.Address'] = "%*".$Address."*%";
		if($AppLogined)$arr['AppLogined'] = $AppLogined;

		if($this->session->userdata('UserType')==2){
			$arr['Addresses.0.CityName'] = $this->session->userdata('CityName');
		}

		$sel = array('Addresses','NickName','Subscribe','Thumbnail','UserInfo');

		$productList = $this->user_model->getUserList($arr,array('UserInfo.Subscribe_time','DESC'),array($perpage,($page-1)*$perpage),$sel);
		$res['UserList'] = $productList['List'];
		$res['Count'] = $productList['Count'];
		$res['PerPage'] = $productList['Limit'];
		$this->returnJson($res);
	}

	public function updSelf(){
		$this->load->model('admin_model');
		extract($this->input->post());

		$arr = array(
			'RealName' => $RealName,
			'Mobile' => $Mobile,
			'Address' => $Address,
			'ZhifubaoAccount' => $ZhifubaoAccount,
			'ZhifubaoKaihu' => $ZhifubaoKaihu
		);
		if($Password)$arr['Password'] = $Password;

		$res = $this->admin_model->updUser($arr,$this->_UserId);
		$this->returnJson($res);
	}

}