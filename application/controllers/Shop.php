<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Shop extends MY_Controller {

	public function __construct(){
		parent::__construct();
		$this->checkQuanxian('shop');
		$this->load->model('shop_model');
	}

	public function getShopList(){
		$this->checkPurview('shopList');
		extract($this->input->post());
		
		if($CityName)$arr['CityName'] = "%*".$CityName."*%";
		if($ShopName)$arr['ShopName'] = "%*".$ShopName."*%";
		if($CreateTimeStart)$arr['CreatTime>'] = strtotime($CreateTimeStart);
		if($CreateTimeEnd)$arr['CreatTime<'] = strtotime($CreateTimeEnd)+59;
		if(isset($IsOpenAdminAccount))$arr['IsOpenAdminAccount'] = $IsOpenAdminAccount;

		if(!isset($IsDisable)){$arr['IsDisable!'] = 1;}else{$arr['IsDisable'] = $IsDisable;}

		$sel = array('CityName','CityCode','ShopName','ShopLogo','ShopId','Fans','CreatTime');

		$orderList = $this->shop_model->getShopList($arr,array('CreateDate','DESC'),array($perpage,($page-1)*$perpage),$sel);
		$res['ShopList'] = $orderList['List'];
		$res['Count'] = $orderList['Count'];
		$res['PerPage'] = $orderList['Limit'];
		$this->returnJson($res);
	}

	public function getShopAccountList(){
		$this->checkPurview('shopAccountList');
		extract($this->input->post());

		$arr['UserType'] = 3;
		if($ShopName)$arr['ShopName'] = "%*".$ShopName."*%";
		$arr['IsDisable'] = 1;

		$adminList = $this->admin_model->getAdminList($arr,'',array($perpage,($page-1)*$perpage),$sel=array());
		$res['AdminList'] = $adminList['List'];
		$res['Count'] = $adminList['Count'];
		$res['PerPage'] = $adminList['Limit'];

		$this->returnJson($res);
	}

	public function setShopShenhe(){
		$this->checkPurview('shopAccountUpd');
		$this->load->model('shop_shenhe_model');
		extract($this->input->post());

		if($open==1){
			$arr['Checked'] = 1;
			$arr['Open'] = $open;
			$res = $this->shop_shenhe_model->update($ShopId,$arr);

			$this->load->model('shop_shenhe_model');
			$shenhe = $this->shop_shenhe_model->getShop($ShopId);

			$shop = array(
				'Balance' => 0,
				'CityCode' => $shenhe['CityCode'],
				'CityName' => $shenhe['CityName'],
				'CreatTime' => time(),
				'DeliverAddress' => $shenhe['address'],
				'ReturnAddress' => $shenhe['address'],
				'ShopAddress' => $shenhe['address'],
				'ShopDescription' => $shenhe['shopinfo'],
				'IsDisable' => 0,
				'IsHide' => 0,
				'IsOpenAdminAccount' => 1,
				'ShopLogo' => $shenhe['shoplogo'],
				'ShopName' => $shenhe['shopname'],
				'ShopOwnerMobile' => $shenhe['mobile'],
				'ShopOwnerName' => $shenhe['realname'],
				'ShopType' => $shenhe['ShopType'],
				'ZhifubaoAccount' => $shenhe['zhifubaoaccount'],
                'InvitationCode'=>$shenhe['invitation'],
			);

			$this->load->model('shop_model');
			$shop = $this->shop_model->addShop($shop);
			$shop = $shop['Shop'];

			$admin = array(
				'Account' => $shenhe['mobile'],
				'Address' => $shenhe['address'],
				'CityCode' => $shenhe['CityCode'],
				'CityName' => $shenhe['CityName'],
				'IsDisable' => 0,
				'Mobile' => $shenhe['mobile'],
				'Password' => $shenhe['password'],
				'RealName' => $shenhe['realname'],
				'ShopId' => $shop['ShopId'],
				'ShopName' => $shenhe['shopname'],
				'ShopRealId' => $shop['id'],
				'UserType' => 3,
				'Username' => $shenhe['shopname'],
				'ZhifubaoAccount' => $shop['zhifubaoaccount']
			);

			$this->load->model('admin_model');
			$res = $this->admin_model->addUser($admin);
			
			//���ͨ�����˺ſ�ͨ�ɹ����Ͷ���֪ͨ
			if($res['ErrorCode']==0){
				$this->load->model('duanxin_model');
				$this->duanxin_model->SendShopOpen($shenhe['mobile'],$shenhe);
			}
		}elseif($open==2){
			$arr['Checked'] = 1;
			$arr['Open'] = $open;
			$res = $this->shop_shenhe_model->update($ShopId,$arr);
		}

		$this->returnJson($res);
	}

	public function getShopShenheList(){
		$this->checkPurview('shopAccountList');
		$this->load->model('shop_shenhe_model');
		extract($this->input->post());

		$arr['IsHide!'] = 1;
		if($ShopName)$arr['shopname'] = '%'.$ShopName.'%';
		if($Checked){
			$arr['Checked'] = $Checked;
		}else{
			$arr['Checked!'] = 1;
		}
		$perpage = 15;
		$res = $this->shop_shenhe_model->getShopList($arr,'',array($perpage,($page-1)*$perpage),$sel=array());

		$this->returnJson($res);
	}

	public function setShopAccount(){
		$this->checkPurview('shopAccountUpd');
		$this->load->model('admin_model');
		extract($this->input->post());

		$arr = array('IsDisable' =>	$IsDisable);

		$where = array(
			'UserType' => 3,
			'id' => $id,
			'CityCode' => $this->_CityCode
		);

		$res = $this->admin_model->updUser($arr,$where);
		$this->returnJson($res);
	}

	public function updSelf(){
		$this->checkPurview('shopSelfUpd');
		extract($this->input->post());

		$arr = array(
			'ShopLogo' => $ShopLogo,
			//'ShopName' => $ShopName,
			'ShopOwnerName' => $ShopOwnerName,
			'ShopOwnerMobile' => $ShopOwnerMobile,
			'ShopDescription' => $ShopDescription,
			'ShopAddress' => $ShopAddress,
			'DeliverAddress' => $DeliverAddress,
			'ReturnAddress' => $ReturnAddress,
			'FreightAmout' => intval($FreightAmout),
			'FreightFreeAmout' => intval($FreightFreeAmout),
			'ZhifubaoKaihu' => $ZhifubaoKaihu,
			'ZhifubaoAccount' => trim($ZhifubaoAccount)
		);

		$res = $this->shop_model->updShop($arr,$this->_ShopRealId);
		$this->returnJson($res);

		//ͬ�����µ������Ƶ�ƴ�ţ���������Ʒ����
		if($ShopName){
			$updShopName['ShopName'] = $ShopName;
			$where['ShopId'] = $this->_ShopRealId;
			$this->load->model('product_model');
			$this->product_model->update($where,$updShopName);
			$this->load->model('order_model');
			$this->order_model->update($where,$updShopName);
			$this->load->model('team_model');
			$this->team_model->update($where,$updShopName);
		}
	}

	public function setShopDisable(){
		$this->checkPurview('shopDisable');
		extract($this->input->post());
		
		$this->load->model('product_model');
		$this->product_model->update(array('ShopId'=>$ShopId),array('IsShopDisable'=>$Disable));

		$this->load->model('admin_model');
		$this->admin_model->update(array('ShopRealId'=>$ShopId),array('IsDisable'=>$Disable));

		$res = $this->shop_model->updShop(array('IsDisable'=>$Disable),$ShopId);
		$this->returnJson($res);
	}

	public function updChecked(){
		$this->checkPurview('shopUpdCheck');
		extract($this->input->post());

		if($IsOpenAdminAccount){
			$arr['IsOpenAdminAccount'] = $IsOpenAdminAccount;
			if($IsOpenAdminAccount==1){
				$admin = $this->shop_model->creatShopAdmin($ShopId);
				if($admin){
					$this->shop_model->updShop($arr,$ShopId);
				}
			}else{
				$this->shop_model->updShop($arr,$ShopId);
			}
		}
		$this->returnJson($res);
	}

}