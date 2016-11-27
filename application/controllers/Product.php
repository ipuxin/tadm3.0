<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Product extends MY_Controller {

	public function __construct(){
		parent::__construct();
		$this->checkQuanxian('product');
		$this->load->model('product_model');
	}

	public function getProductSaleList(){
		$this->checkPurview('productSale');
		extract($this->input->post());
		$this->load->model('product_sale_model');
		
		if($this->_UserType==3)$arr['ShopRealId'] = $this->_ShopRealId;
		if($CreateTimeStart)$arr['CreatTime>'] = strtotime($CreateTimeStart);
		if($CreateTimeEnd)$arr['CreatTime<'] = strtotime($CreateTimeEnd)+59;

		$list = $this->product_sale_model->getSaleList($arr,array('CreatTime','DESC'),array($perpage,($page-1)*$perpage));
		$res['ProductSaleList'] = $list['List'];
		$res['Count'] = $list['Count'];
		$res['PerPage'] = $list['Limit'];
		$this->returnJson($res);
	}

	//资金明细表
	public function getShopBalanceList(){
		$this->checkPurview('shopBalance');
		extract($this->input->post());
		$this->load->model('shop_balance_model');
		
		if($this->_UserType==3)$arr['ShopRealId'] = $this->_ShopRealId;
		if($CreateTimeStart)$arr['CreatTime>'] = strtotime($CreateTimeStart);
		if($CreateTimeEnd)$arr['CreatTime<'] = strtotime($CreateTimeEnd)+59;

		$list = $this->shop_balance_model->getBalanceList($arr,array('CreatTime','DESC'),array($perpage,($page-1)*$perpage));
		$res['ProductSaleList'] = $list['List'];
		$res['Count'] = $list['Count'];
		$res['PerPage'] = $list['Limit'];
		$this->returnJson($res);
	}

	public function getProductList(){
		$this->checkPurview('productList');
		extract($this->input->post());
		
		if($ProductName)$arr['ProductName'] = "%*".$ProductName."*%";
		if($ShopName)$arr['ShopName'] = "%*".$ShopName."*%";
		if($CityName)$arr['CityName'] = "%*".$CityName."*%";
		if($ProductType)$arr['ProductType'] = $ProductType;
		if(isset($IsAllCity) && $IsAllCity>=0)$arr['IsAllCity'] = $IsAllCity;
		$arr['IsChecked'] = 1;

		$sel = array('ProductId','ImageMin','ProductName','CityName','Prices','SalesCount','IsForSale','IsDisable','ShopName','ShopId','ProductType');

		$productList = $this->product_model->getProductList($arr,'',array($perpage,($page-1)*$perpage),$sel);
		$res['ProductList'] = $productList['List'];
		$res['Count'] = $productList['Count'];
		$res['PerPage'] = $productList['Limit'];
		$this->returnJson($res);
	}

	public function getProductListCheck(){
		$this->checkPurview('productList');
		extract($this->input->post());
		
		if($ProductName)$arr['ProductName'] = "%*".$ProductName."*%";
		if($ShopName)$arr['ShopName'] = "%*".$ShopName."*%";
		if($CityName)$arr['CityName'] = "%*".$CityName."*%";
		if($ProductType)$arr['ProductType'] = $ProductType;
		if($IsChecked>=0){$arr['IsChecked'] = $IsChecked;}else{$arr['IsChecked!'] = 1;}

		$sel = array('ProductId','ImageMin','ProductName','CityName','Prices','SalesCount','IsForSale','IsDisable','ShopName','ShopId','ProductType','IsChecked');

		$productList = $this->product_model->getProductList($arr,'',array($perpage,($page-1)*$perpage),$sel);
		$res['ProductList'] = $productList['List'];
		$res['Count'] = $productList['Count'];
		$res['PerPage'] = $productList['Limit'];
		$this->returnJson($res);
	}

	public function addProduct(){
		$this->checkPurview('productAdd');
		$this->load->model('product_model');
		extract($this->input->post());

		if($ProductType>1){
			$Prices['Team'] = $Prices['Normal'];
			$Prices['Normal'] = $Prices['Market'];
			$freightAmout = 0;
		}

		$arr = array(
			'ProductType' => $ProductType,
			'ImageMin' => $ImageMin,
			'ImageBig' => $ImageBig,
			'ImageList' => $ImageList,
			'ProductName' => $ProductName,
			'Prices' => $Prices,
			'StorageCount' => $StorageCount,
			'freightAmout' => $freightAmout,
			'DeliverAddress' => $DeliverAddress,
			'Description' => $Description,
			'Category1' => $Category1,
			'Category2' => $Category2,
			'Content' => $Content,
			'IsForSale' => intval($IsForSale),
			'ShopId' => $this->_ShopRealId,
			'ShopName' => $this->_ShopName,
			'CityCode' => $this->_CityCode,
			'CityName' => $this->_CityName,
			//16-09-17 ������Ʒ����Ҫ���
			'IsChecked' => 1,
		);

		if($ProductType>1){
			$arr['TeamMemberLimit'] = $TeamMemberLimit;
			$arr['Alive'] = $Alive;
			$arr['IsTeam'] = 1;
		}
		if($ProductType>2){
			$arr['LotteryCount'] = $LotteryCount;
			$arr['IsLottery'] = 1;
		}

		$res = $this->product_model->addProduct($arr);

		$this->returnJson($res);
	}

	public function updProductCitys(){
		$this->checkPurview('productUpd');
		$this->load->model('product_model');
		$this->load->model('shop_model');
		extract($this->input->post());

		$productInfo = $this->product_model->getProduct($id);
		$ShopId = $productInfo['ShopId'];
		$shopInfo = $this->shop_model->getShop($ShopId);
		$CityCode = $shopInfo['CityCode'];
		
		$Code[] = $CityCode;
		foreach( $Code as $k=>$v){
			if( !$v ) unset( $Code[$k] );
		}
		$Code = implode(',',$Code);

		if($this->session->userdata('UserType')==1 || $this->session->userdata('UserType')==3){
			$arr = array('CityCode'=>'['.$Code.']');
			$where = array('id' => $id);
		}

		$res = $this->product_model->updProduct($arr,$where);
		$this->returnJson($res);
	}

	public function updProduct(){
		$this->checkPurview('productUpd');
		$this->load->model('product_model');
		extract($this->input->post());

		if($ProductType>1){
			$Prices['Team'] = $Prices['Normal'];
			//$Prices['Normal'] = $Prices['Market'];
			$freightAmout = 0;
		}
		
		if($this->session->userdata('UserType')==3){
			$arr = array(
				'ProductType' => $ProductType,
				'ImageMin' => $ImageMin,
				'ImageBig' => $ImageBig,
				'ImageList' => $ImageList,
				'ProductName' => $ProductName,
				'Prices' => $Prices,
				'StorageCount' => $StorageCount,
				'freightAmout' => $freightAmout,
				'DeliverAddress' => $DeliverAddress,
				'Description' => $Description,
				'Category1' => $Category1,
				'Category2' => $Category2,
				'Content' => $Content,
				'IsForSale' => intval($IsForSale),

				//ƴ�Ų���
				'TeamMemberLimit' => $TeamMemberLimit,
				'Alive' => $Alive,
				'LotteryCount' => $LotteryCount
			);
			$where = array(
				'ShopId' => $this->_ShopRealId,
				'id' => $id
			);
			
			if($ProductType==3 || $ProductType==4){
				$arr['IsChecked'] = 0;
			}else{
				$arr['IsChecked'] = 1;
			}

		}elseif($this->session->userdata('UserType')==2){
			if(is_numeric($IsDisable))$arr['IsDisable'] = $IsDisable;
			if(is_numeric($IsChecked))$arr['IsChecked'] = $IsChecked;
			$where = array(
				'CityCode' => $this->session->userdata('CityCode'),
				'id' => $id
			);
		}elseif($this->session->userdata('UserType')==1){
			if(is_numeric($IsDisable))$arr['IsDisable'] = $IsDisable;
			if(is_numeric($IsChecked))$arr['IsChecked'] = $IsChecked;
			if(is_numeric($IsAllCity))$arr['IsAllCity'] = $IsAllCity;
			$where = array(
				'id' => $id
			);
		}

		$res = $this->product_model->updProduct($arr,$where);

		$this->returnJson($res);
	}

	public function setProductHide(){
		$this->checkPurview('productHide');
		$this->load->model('product_model');
		extract($this->input->post());
		$arr = array('IsHide'=>1);
		$res = $this->product_model->updProduct($arr,$ProductId);

		$this->returnJson($res);
	}

	public function setProductIsForSale(){
		$this->checkPurview('productUpd');
		$this->load->model('product_model');
		extract($this->input->post());
		$arr = array('IsForSale'=>$IsForSale);
		$res = $this->product_model->updProduct($arr,$ProductId);

		$this->returnJson($res);
	}

}