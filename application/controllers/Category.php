<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Category extends MY_Controller {

	public function __construct(){
		parent::__construct();
		$this->checkQuanxian('setting');
		$this->load->model('category_model');
	}

	public function addCate(){
		$this->checkPurview('setcategory');
		extract($this->input->post());
		
		$arr = array(
			'CateParent' => $CateParent,
			'CateName' => $CateName,
			'CateSorting' => $CateSorting,
			'CateLevel' => '2',
			'IsDisable' => $IsDisable,
			'ImgUrl' => $ImgUrl
		);

		$res = $this->category_model->addCategory($arr);
		$this->returnJson($res);
	}

	public function updCate(){
		$this->checkPurview('setcategory');
		extract($this->input->post());
		
		$arr = array(
			'CateParent' => $CateParent,
			'CateName' => $CateName,
			'CateSorting' => $CateSorting,
			'IsDisable' => $IsDisable,
			'ImgUrl' => $ImgUrl
		);

		$res = $this->category_model->updCategory($arr,$id);
		$this->returnJson($res);
	}

}