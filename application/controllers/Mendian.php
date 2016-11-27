<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mendian extends MY_Controller {

	public function __construct(){
		parent::__construct();
		$this->checkQuanxian('jiameng');
		$this->load->model('admin_model');
	}

	public function getMendianList(){
		$this->checkPurview('jiamengList');
		extract($this->input->post());

		$arr['UserType'] = 4;
		if($Username)$arr['Username'] = "%*".$Username."*%";
		if($IsDisable>=0)$arr['IsDisable'] = $IsDisable;

		$adminList = $this->admin_model->getAdminList($arr,'',array($perpage,($page-1)*$perpage),$sel=array());
		$res['AdminList'] = $adminList['List'];
		$res['Count'] = $adminList['Count'];
		$res['PerPage'] = $adminList['Limit'];
		$this->returnJson($res);
	}
	
	public function addMendian(){
		$this->checkPurview('jiamengAdd');
		extract($this->input->post());

		$arr = array(
			'UserType' => 4,
			'Username' => $Username,
			'Account' => $Account,
			'Password' => $Password,
			'InvitationCode' => $this->getRandStr(20),
			'IsDisable' => $IsDisable
		);

		$res = $this->admin_model->addUser($arr);
		$this->returnJson($res);
	}
	
	public function updMendian(){
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