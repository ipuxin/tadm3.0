<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*** 管理员类 ***

创建 2016-01-25 刘深远 

*** ***/

class Admin_model extends MY_Model {

	private $_model;
	
	public function __construct(){
		parent::__construct();
		$this->init();
	}

	function init(){
		parent::init();
		$this->setTable('Admin');

		$IsDisable = array(
			0 => '启用',
			1 => '禁用'
		);

		$TypeArr = array(
			1 => '系统管理员',
			2 => '加盟商',
			3 => '店铺',
			4 => '品牌商'
		);

		$this->_model = array(
			'Account' => 'str',
			'Username' => 'str',
			'Password' => 'md5',
			'IsDisable' => $IsDisable,
			'CityCode' => 'code',
			'CityName' => 'str',
			'UserType' => $TypeArr,
			'CreatDate' => 'date'
		);
	}

	function getAdminList($arr=array(),$order=array(),$limit=array()){
		if(!$order)$order=array('CreatDate','DESC');
		$list = $this->getList($arr,$order,$limit);
		if($list)$list = $this->resetAdminList($list);
		$data['List'] = $list;
		$data['Count'] = $this->_return_Count;
		$data['Limit'] = $this->_return_Limit;
		$data['Skip'] = $this->_return_Skip;
		return $data;
	}

	function resetAdminList($list){
		$data = $this->_model;
		foreach($list as $k=>$v){
			$list[$k]['IsDisableShow'] = $data['IsDisable'][$v['IsDisable']];
		}
		return $list;
	}

	function resetAdmin($arr){
		$data = $this->_model;
		$arr['IsDisableShow'] = $data['IsDisable'][$arr['IsDisable']];
		return $arr;
	}

	function getAdmin($arr){
		$admin = $this->getRow($arr);
		if($admin){
			$Data['Admin'] = $this->resetAdmin($admin);
		}else{
			$Data['ErrorCode'] = 102;
		}
		return $Data;
	}

	function login($arr){
		$arr['Password'] = md5($arr['Password']);
		$user = $this->getRow($arr);
		if($user)$user = $this->resetAdmin($user);
		if($user && $user['IsDisable']==0){
			$this->putSession($user);
		}else{
			$Data['ErrorCode'] = 102;
		}
		return $Data;
	}
	
	//获取所有已经设置过的城市Code
	function getAllCode(){
		$list = $this->getAdminList(array('UserType'=>2));
		if($list['List']){
			foreach($list['List'] as $v){
				if($v['CityCode'])$codes[] = $v['CityCode'];
			}
		}

		if($codes){
			return implode(',',$codes);
		}else{
			return false;
		}
	}

	//获取当前城市已经设置过的区域Code
	function getAllCodeForCity($cityCode){
		$list = $this->getAdminList(array('UserType'=>4,'ParentCityCode'=>$cityCode));
		if($list['List']){
			foreach($list['List'] as $v){
				if($v['DistrictCode'])$codes[] = $v['DistrictCode'];
			}
		}
		return implode(',',$codes);
	}

	function addUser($arr){
		$arr['CreatDate'] = time();
		$arr = $this->setModel($arr);
		if(is_numeric($arr)){$Data['ErrorCode'] = $arr;return $Data;}
		if($user = $this->add($arr)){
			$Data['User'] = $user;
		}else{
			$Data['ErrorCode'] = 101;
		}
		return $Data;
	}

	function updUser($arr=array(),$where=array()){
		if(!$where)$where = $arr['id'];
		if($arr['Password'])$arr['Password'] = md5($arr['Password']);
		if($updnum = $this->update($where,$arr)){
			$Data['Updnum'] = $updnum;
		}else{
			$Data['ErrorCode'] = 101;
		}
		return $Data;
	}

	function delUser($id){
		if(is_array($id)){$Data['ErrorCode'] = 103;return $Data;}
		if($delnum = $this->del($id)){
			$Data['Num'] = $delnum;
		}else{
			$Data['ErrorCode'] = 103;
		}
		return $Data;
	}

	function setModel($arr){
		if(!$arr['Account']){
			return 104;
		}else{
			if($this->checkHas('Account',$arr['Account'])){
				return 105;
			}
		}
		$arr['Password'] = md5($arr['Password']);
		return $arr;
	}

	//将登陆信息保存到session
	function putSession($user){
		$userInfo = array(
			'UserId'   => $user['id'],
			'Account'  => $user['Account'],
			'Username' => $user['Username'],
			'Password' => $user['Password'],
			'UserType' => $user['UserType'],
			'CityName'  => $user['CityName'],
			'CityCode'  => $user['CityCode'],
			'DistrictCode' => $user['DistrictCode'],
			'DistrictName' => $user['DistrictName']
		);

		if($user['UserType']==3){
			$userInfo['ShopId'] = $user['ShopId'];
			$userInfo['ShopName'] = $user['ShopName'];
			$userInfo['ShopRealId'] = $user['ShopRealId'];
		}
		
		if($user['UserType']==4){
			$userInfo['InvitationCode'] = $user['InvitationCode'];
		}

		$this->session->set_userdata($userInfo);
	}
	
	//检查登陆信息
	function checkUser($account,$password){
		$arr = array(
			'Account'  => $account,
			'Password' => $password
		);
		$user = $this->getRow($arr);
		if($user && $user['IsDisable']==0){
			$user = $this->resetAdmin($user);
			$this->putSession($user);
			return true;
		}
		return false;
	}

    function updAdmin($arr,$where= array()){
        if(is_numeric($arr)){
            $Data['ErrorCode'] = $arr;
            if($arr==204)$Data['ErrorMessage'] = '用户ID参数不可修改';
            return $Data;
        }
        if($updnum = $this->update($where,$arr)){
            $Data['Num'] = $updnum;
        }else{
            $Data['ErrorCode'] = 3;
        }
        return $Data;
    }
	
}