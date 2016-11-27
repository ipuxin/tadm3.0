<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Main extends MY_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model('admin_model');
	}

	public function index(){
		$this->_page_title = '拼一下后台管理系统 V 1.10605';
		if($this->_Account && $this->_PassWord && $this->_UserType){
			if($this->admin_model->checkUser($this->_Account,$this->_PassWord)){
				$PyxMenu = $this->config->item('PyxMenu'.$this->_UserType);
				$PyxMenuMain = $this->config->item('PyxMenu'.$this->_UserType.'_Main');
				$data['PyxMenu'] = $PyxMenu;
				$data['PyxMenuMain'] = $PyxMenuMain;
				$this->view('main',$data);
			}else{
				$this->view('login',$data);
			}
		}else{
			$this->view('login',$data);
		}
	}

	//用户登陆
	public function login(){
		$arr = $this->input->post();
		$return = $this->admin_model->login($arr);
		$this->returnJson($return);
	}

	public function loginOut(){
		session_destroy();
		redirect('/');
	}

}