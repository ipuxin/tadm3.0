<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Jiameng extends MY_Controller {

	public function __construct(){
		parent::__construct();
		$this->checkQuanxian('jiameng');
		$this->load->model('admin_model');
	}

	public function getJiamengList(){
		$this->checkPurview('jiamengList');
		extract($this->input->post());

		$arr['UserType'] = 2;
		if($Username)$arr['Username'] = "%*".$Username."*%";
		if($IsDisable>=0)$arr['IsDisable'] = $IsDisable;

		$adminList = $this->admin_model->getAdminList($arr,'',array($perpage,($page-1)*$perpage),$sel=array());
		$res['AdminList'] = $adminList['List'];
		$res['Count'] = $adminList['Count'];
		$res['PerPage'] = $adminList['Limit'];
		$this->returnJson($res);
	}
	
	public function addJiameng(){
		$this->checkPurview('jiamengAdd');
		extract($this->input->post());

		$arr = array(
			'UserType' => 2,
			'Username' => $Username,
			'Account' => $Account,
			'Password' => $Password,
			'CityName' => $CityName,
			'CityCode' => $CityCode,
			'IsDisable' => $IsDisable
		);

		$res = $this->admin_model->addUser($arr);
		$this->returnJson($res);
	}
	
	public function updJiameng(){
		$this->checkPurview('jiamengUpd');
		extract($this->input->post());

		$arr = array(
			'id' => $id,
			'Username' => $Username,
			'Account' => $Account,
			'CityName' => $CityName,
			'CityCode' => $CityCode,
			'IsDisable' => $IsDisable
		);
		if($Password)$arr['Password'] = $Password;

		$res = $this->admin_model->updUser($arr);
		$this->returnJson($res);
	}

}