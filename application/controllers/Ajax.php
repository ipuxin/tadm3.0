<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ajax extends MY_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model('admin_model');
	}

	public function getCateList(){
		extract($this->input->post());
		$this->load->model('category_model');

		$arr = array(
			'CateLevel' => $CateLevel,
			'CateParent' => $CateParent
		);

		$list = $this->category_model->getCategoryList($arr);
		$res['list'] = $list['List'];
		$this->returnJson($res);
	}

	public function addKuaidi(){
		extract($this->input->post());
		$this->load->model('kuaidi_model');

		$arr = array(
			'Code' => $code,
			'Name' => $name,
			'ShopId' => $this->_ShopRealId
		);

		$this->kuaidi_model->addKuaidi($arr);
		$this->returnJson($res);
	}

	public function delKuaidi(){
		extract($this->input->post());
		$this->load->model('kuaidi_model');
		
		$arr = array(
			'Code' => $code,
			'ShopId' => $this->_ShopRealId
		);
		
		$this->kuaidi_model->delKuaidi($arr);
		$this->returnJson($res);
	}

}