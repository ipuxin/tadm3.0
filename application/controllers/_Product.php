<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Product extends MY_Controller {

	public function __construct(){
		parent::__construct();
		$this->checkQuanxian('product');
		$this->load->model('product_model');
	}

	public function productList(){
		$this->view('product_list',$data);
	}

	public function productAdd(){
		$this->load->model('groupcoupon_model');
		$groupList = $this->groupcoupon_model->getCouponList();
		$data['groupList'] = $groupList['List'];

		$this->view('product_add',$data);
	}

	public function productUpd($id){
		if(!$id){redirect('/product/productList');}
		$this->load->model('groupcoupon_model');
		$groupList = $this->groupcoupon_model->getCouponList();
		$data['groupList'] = $groupList['List'];
		$productInfo = $this->product_model->getRow($id);
		if(!$productInfo){redirect('/product/productList');}
		$data['pro'] = $productInfo;
		$this->view('product_upd',$data);
	}

	public function pinjia(){
		$this->view('product_pinjia',$data);
	}

	public function productIndex(){
		$this->load->model('configure_model');
		$config = $this->configure_model->getConfig(1);
		$data['data'] = $config['Data'];
		$this->view('product_index',$data);
	}

	public function getPinjiaList(){
		extract($this->input->post());
		if($NickName)$arr['UserInfo.NickName'] = "%*".$NickName."*%";
		if($IsShow>=0)$arr['IsShow'] = $IsShow;
		$this->load->model('pinjia_model');
		$pinjiaList = $this->pinjia_model->getPinjiaList($arr,'',array($perpage,($page-1)*$perpage));
		$res['PinjiaList'] = $pinjiaList['List'];
		$res['Count'] = $pinjiaList['Count'];
		$res['PerPage'] = $pinjiaList['Limit'];
		$this->returnJson($res);
	}

	public function UpdPinjia(){
		extract($this->input->post());
		$this->load->model('pinjia_model');
		
		if($IsShow==1){$IsShow=2;}else{$IsShow=1;}

		$arr = array(
			'id' => $id,
			'IsShow' => $IsShow
		);

		$res = $this->pinjia_model->UpdPinjia($arr);
		$this->returnJson($res);
	}

	public function Addproduct(){
		$arr = $this->input->post();
		
		foreach($arr['CityCodes'] as $k=>$v){
			if($v){
				$showCityCodes[] = $v;
				$showCityNames[] = $arr['CityNames'][$k];
			}
		}

		unset($arr['CityCodes']);
		unset($arr['CityNames']);

		$arr['ShowCityCodes'] = $showCityCodes;
		$arr['ShowCityNames'] = $showCityNames;

		$res = $this->product_model->addProduct($arr);
		$this->returnJson($res);
	}

	public function Updproduct(){
		$arr = $this->input->post();

		foreach($arr['CityCodes'] as $k=>$v){
			if($v){
				$showCityCodes[] = $v;
				$showCityNames[] = $arr['CityNames'][$k];
			}
		}

		unset($arr['CityCodes']);
		unset($arr['CityNames']);

		$arr['ShowCityCodes'] = $showCityCodes;
		$arr['ShowCityNames'] = $showCityNames;

		$res = $this->product_model->updProduct($arr);
		$this->returnJson($res);
	}

	public function productHide(){
		extract($this->input->post());
		$res = $this->product_model->hideProduct($id);
		$this->returnJson($res);
	}

	public function getProductList(){
		extract($this->input->post());
		
		if($ProductName)$arr['ProductName'] = "%*".$ProductName."*%";
		if($Type)$arr['Type'] = $Type;
		if($IsDisable>=0)$arr['IsDisable'] = $IsDisable;
		if($ProductType==-1)$arr['ProductType!'] = 'AllCity';
		if($ProductType=='AllCity')$arr['ProductType'] = 'AllCity';

		$productList = $this->product_model->getProductList($arr,array('Priority','ASC'),array($perpage,($page-1)*$perpage));
		$res['ProductList'] = $productList['List'];
		$res['Count'] = $productList['Count'];
		$res['PerPage'] = $productList['Limit'];
		$this->returnJson($res);
	}

}