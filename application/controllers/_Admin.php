<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*** 后台主控制器 ***

创建 2016-01-25 刘深远 

*** ***/

class Admin extends MY_Controller {

	public function __construct(){
		parent::__construct();
		$this->checkQuanxian('admin');
		$this->load->model('admin_model');
	}

	public function kuaidiList(){
		$this->load->model('kuaidi_model');
		$res = $this->kuaidi_model->getKuaidiList();
		$data['kuaidi'] = $res;
		$this->view('admin_kuaidi',$data);
	}

	public function setKuaidi(){
		$this->load->model('kuaidi_model');
		extract($this->input->post());
		foreach($id as $k=>$v){
			if($Show[$k]!=$ShowReset[$k]){
				$arr[] = array(
					'id' => $v,
					'Show' => $ShowReset[$k]
				);
			}
		}
		if($arr)$res = $this->kuaidi_model->updKuaidiList($arr);
		$this->returnJson($res);
	}

	public function adminAdd(){
		$data['usedCode'] = $this->admin_model->getAllCode();
		$this->view('admin_add',$data);
	}

	public function agentAdd(){
		$this->view('agent_add',$data);
	}

	public function adminUpd($id){
		$admin = $this->admin_model->getAdmin($id);
		$data['admin'] = $admin['Admin'];
		$data['usedCode'] = $this->admin_model->getAllCode();
		$this->view('admin_upd',$data);
	}

	public function agentUpd($id){
		$admin = $this->admin_model->getAdmin($id);
		$data['admin'] = $admin['Admin'];
		$this->view('agent_upd',$data);
	}

	public function adminList(){
		//$res = $this->admin_model->addUser($arr);
		$this->view('admin_list',$data);
	}

	public function agentList(){
		$this->view('agent_list',$data);
	}

	public function getAdminList(){
		extract($this->input->post());
		$arr['UserType'] = 2;
		if($Username)$arr['Username'] = "%*".$Username."*%";
		if($IsDisable>=0)$arr['IsDisable'] = $IsDisable;
		$adminList = $this->admin_model->getAdminList($arr,'',array($perpage,($page-1)*$perpage));
		$res['AdminList'] = $adminList['List'];
		$res['Count'] = $adminList['Count'];
		$res['PerPage'] = $adminList['Limit'];
		$this->returnJson($res);
	}

	public function getAgentList(){
		extract($this->input->post());
		$arr['UserType'] = 3;
		if($Username)$arr['Username'] = "%*".$Username."*%";
		if($IsDisable>=0)$arr['IsDisable'] = $IsDisable;
		$adminList = $this->admin_model->getAdminList($arr,'',array($perpage,($page-1)*$perpage));
		$res['AdminList'] = $adminList['List'];
		$res['Count'] = $adminList['Count'];
		$res['PerPage'] = $adminList['Limit'];
		$this->returnJson($res);
	}

	public function getDivisionsList($type){
		$this->load->model('divisions_model');
		$res = $this->divisions_model->getAll($type);
		$this->returnJson($res);
	}

	public function addAdmin(){
		extract($this->input->post());
		$CityNames = array_filter($CityNames);
		$CityCodes = array_filter($CityCodes);
		foreach($CityNames as $k=>$v){
			$Citys[] = array($CityCodes[$k],$v);
		}
		$arr = array(
			'UserType' => 2,
			'Username' => $Username,
			'Account' => $Account,
			'Password' => $Password,
			'Citys' => $Citys,
			'IsDisable' => $IsDisable
		);
		$res = $this->admin_model->addUser($arr);
		$this->returnJson($res);
	}

	public function updAdmin(){
		extract($this->input->post());
		$CityNames = array_filter($CityNames);
		$CityCodes = array_filter($CityCodes);
		foreach($CityNames as $k=>$v){
			$Citys[] = array($CityCodes[$k],$v);
		}
		$arr = array(
			'id' => $id,
			'Username' => $Username,
			'Account' => $Account,
			'Citys' => $Citys,
			'IsDisable' => $IsDisable
		);
		if($Password)$arr['Password'] = $Password;
		$res = $this->admin_model->updUser($arr);
		$this->returnJson($res);
	}

	public function addAgent(){
		extract($this->input->post());
		$arr = array(
			'UserType' => 3,
			'Username' => $Username,
			'Account' => $Account,
			'Password' => $Password,
			'IsDisable' => $IsDisable
		);
		$res = $this->admin_model->addUser($arr);
		$this->returnJson($res);
	}

	public function updAgent(){
		extract($this->input->post());
		$arr = array(
			'id' => $id,
			'Username' => $Username,
			'Account' => $Account,
			'IsDisable' => $IsDisable
		);
		if($Password)$arr['Password'] = $Password;
		$res = $this->admin_model->updUser($arr);
		$this->returnJson($res);
	}

}