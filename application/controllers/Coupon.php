<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*** 优惠券控制器 ***

创建 2016-01-29 刘深远 

*** ***/

class Coupon extends MY_Controller {

	public function __construct(){
		parent::__construct();
		$this->checkQuanxian('coupon');
		$this->load->model('coupon_model');
		$this->load->model('usercoupon_model');
	}

	public function couponHide(){
		extract($this->input->post());
		$res = $this->coupon_model->hideCoupon($id);
		$this->returnJson($res);
	}

	public function send($userId){
		$arr['IsActive'] = 'null';
		$couponList = $this->coupon_model->getCouponList($arr,array('CreatDate','DESC'));
		$data['List'] = $couponList['List'];
		$data['UserId'] = $userId;
		$this->view('coupon_send',$data);
	}

	public function couponSend(){
		extract($this->input->post());
		$CouponNum = intval($CouponNum);
		$res = $this->coupon_model->couponSend($UserId,$CouponId,$CouponNum);
		$this->returnJson($res);
	}

	public function couponActive(){
		$couponList = $this->coupon_model->getCouponList(array('IsActive'=>1),array('CreatDate','DESC'));
		$data['couponList'] = $couponList['List'];
		$groupList = $this->groupcoupon_model->getCouponList();
		$data['groupList'] = $groupList['List'];

		$this->view('coupon_active',$data);
	}
	
	public function Addcoupon(){
		$this->checkPurview('couponAdd');
		$arr = $this->input->post();
		if($this->_UserType==3){
			$arr['ShopId'] = $this->_ShopRealId;
			$arr['ShopName'] = $this->_ShopName;
		}elseif($this->_UserType==1){
			$arr['IsAll'] = 1;
		}
		if($ProviceName && $ProviceCode && $CityName && $CityCode){
			$arr['ProviceName'] = $ProviceName;
			$arr['ProviceCode'] = $ProviceCode;
			$arr['CityName'] = $CityName;
			$arr['CityCode'] = $CityCode;
		}
		$res = $this->coupon_model->addCoupon($arr);
		$this->returnJson($res);
	}

	public function Updcoupon(){
		$this->checkPurview('couponUpd');
		$arr = $this->input->post();
		if($arr['ProviceName'] && $arr['ProviceCode'] && $arr['CityName'] && $arr['CityCode']){

		}else{
			$arr['ProviceName'] = '';
			$arr['ProviceCode'] = '';
			$arr['CityName'] = '';
			$arr['CityCode'] = '';
		}
		$res = $this->coupon_model->updCoupon($arr);
		$this->returnJson($res);
	}

	public function getCouponListActive(){
		$this->checkPurview('couponList');
		$couponList = $this->coupon_model->getCouponList(array('IsActive'=>1),array('CreatDate','DESC'));
		$res['CouponList'] = $couponList['List'];
		$res['Count'] = $couponList['Count'];
		$res['PerPage'] = $couponList['Limit'];
		$this->returnJson($res);
	}

	public function getCouponList(){
		$this->checkPurview('couponList');
		extract($this->input->post());
		if($CouponName)$arr['CouponName'] = "%*".$CouponName."*%";
		if($CityName)$arr['CityName'] = "%*".$CityName."*%";
		if($this->_UserType==3){
			$arr['ShopId'] = $this->_ShopRealId;
		}elseif($this->_UserType==1){
			$arr['IsAll'] = 1;
		}
		$couponList = $this->coupon_model->getCouponList($arr,array('CreatDate','DESC'),array($perpage,($page-1)*$perpage));
		$res['CouponList'] = $couponList['List'];
		$res['Count'] = $couponList['Count'];
		$res['PerPage'] = $couponList['Limit'];
		$this->returnJson($res);
	}

	public function getUserCouponList(){
		$this->checkPurview('couponList');
		extract($this->input->post());
		if($CouponName)$arr['CouponName'] = "%*".$CouponName."*%";
		if($IsUsed>=0)$arr['IsUsed'] = $IsUsed;
		$arr['ShopId'] = $this->_ShopRealId;

		$couponList = $this->usercoupon_model->getCouponList($arr,array('CreatDate','DESC'),array($perpage,($page-1)*$perpage));
		$res['CouponList'] = $couponList['List'];
		$res['Count'] = $couponList['Count'];
		$res['PerPage'] = $couponList['Limit'];
		$this->returnJson($res);
	}
}