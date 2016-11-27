<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Banner extends MY_Controller {

	public function __construct(){
		parent::__construct();
		$this->checkQuanxian('setting');
		$this->load->model('banner_model');
	}

	public function addBanner(){
		$this->checkPurview('setbanner');
		extract($this->input->post());
		
		$arr = array(
			'Url' => $Url,
			'Name' => $Name,
			'Paixu' => $Paixu,
			'IsDisable' => $IsDisable
		);

		$res = $this->banner_model->addBanner($arr);
		$this->returnJson($res);
	}

	public function updBanner(){
		$this->checkPurview('setbanner');
		extract($this->input->post());
		
		$arr = array(
			'Url' => $Url,
			'Name' => $Name,
			'Paixu' => $Paixu,
			'IsDisable' => $IsDisable
		);

		$res = $this->banner_model->updBanner($arr,$id);
		$this->returnJson($res);
	}

}