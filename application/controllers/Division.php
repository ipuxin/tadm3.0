<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Division extends MY_Controller {

	public function __construct(){
		parent::__construct();
		$this->checkQuanxian('division');
		$this->load->model('divisions_model');
	}

	public function getCityList($code){
		$this->checkPurview('cityList');
		$res = $this->divisions_model->getCityList($code);
		$this->returnJson($res);
	}

	public function getDistrictList($code){
		$this->checkPurview('districtList');
		$res = $this->divisions_model->getDistrictList($code);
		$this->returnJson($res);
	}

	public function getDivisionsList($type='min'){
		$this->checkPurview('divisionsList');
		$res = $this->divisions_model->getAll($type);
		unset($res['Result'][1]['Cities'][1]);
		unset($res['Result'][8]['Cities'][1]);
		unset($res['Result'][21]['Cities'][1]);
		unset($res['Result'][31]);
		unset($res['Result'][32]);
		unset($res['Result'][33]);
		$this->returnJson($res);
	}

	public function setCitysHot(){
		extract($this->input->post());
		$name = str_replace('市辖区','',$name);
		$res = $this->divisions_model->setHot($code,$name,$pcode,$pname,$hot);
		$this->returnJson($res);
	}

	public function getHotCitys(){
		$list = $this->divisions_model->getHot();
		$res['List'] = $list;
		$this->returnJson($res);
	}

}