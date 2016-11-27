<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*** 用户类 ***

创建 2016-01-30 刘深远 

*** ***/

class User_model extends MY_Model {

	private $_model;
	
	public function __construct(){
		parent::__construct();
		$this->load->library('encrypt');
		$this->load->library('session');
		$this->init();
	}

	function init(){
		parent::init();
		$this->setTable('User');

		$Subscribe = array(
			0 => '未关注',
			1 => '已关注'
		);

		$this->_model = array(
			'OpenId' => 'str',
			'Subscribe' => $Subscribe, //是否关注
			'NickName' => 'str',
			'UserInfo' => 'array',
			'Thumbnail' => 'url', //头像
			'Favorites' => 'array',
			'Addresses' => 'array'
		);
	}

	function resetUser($user){

		$userinfo = array(
			'Sex' => $user['sex'],
			'City' => $user['city'],
			'Province' => $user['province'],
			'Country' => $user['country'],
			'Subscribe_time' => $user['subscribe_time']
		);
		$arr = array(
			'OpenId' => $user['openid'],
			'Subscribe' => $user['subscribe'],
			'NickName' => $user['nickname'],
			'Thumbnail' => $user['headimgurl'],
			'UserInfo' => $userinfo
		);

		if($userId = $this->checkUser($user['openid'])){
			if($this->update($userId,$arr)){
				$arr['id'] = $userId;
				$this->putSession($arr);
			}
		}else{
			if($user = $this->add($arr)){
				$this->putSession($user);
			}
		}
	}

	function getUserList($arr,$order=array(),$limit=array(),$sel=array()){
		if(!$order)$order = array('CreatTime','DESC');
		$list = $this->getList($arr,$order,$limit,$sel);
		$list = $this->setDateUserList($list,$usertype);
		$data['List'] = $list;
		$data['Count'] = $this->_return_Count;
		$data['Limit'] = $this->_return_Limit;
		$data['Skip'] = $this->_return_Skip;
		return $data;
	}

	function setDateUserList($list,$usertype){
		foreach($list as $v){
			$arr[] = $this->setDateUser($v,$usertype);
		}
		return $arr;
	}

	function setDateUser($arr){

		$sex = array(1=>'男',2=>'女');

		$arr['SubscribeShow'] = $this->_model['Subscribe'][$arr['Subscribe']];
		if($arr['Subscribe'])$arr['SubscribeDate'] = date('Y-m-d H:i:s',$arr['UserInfo']['Subscribe_time']);
		$arr['SexShow'] = $sex[$arr['UserInfo']['Sex']];
		$arr['RealName'] = $arr['Addresses'][0]['RealName'];
		$arr['Mobile'] = $arr['Addresses'][0]['Mobile'];
		$arr['ProviceName'] = $arr['Addresses'][0]['ProviceName'];
		$arr['CityName'] = $arr['Addresses'][0]['CityName'];
		$arr['DistrictName'] = $arr['Addresses'][0]['DistrictName'];
		$arr['Address'] = $arr['Addresses'][0]['Address'];
		$arr['AppLoginedShow'] = '未登录';
		if($arr['AppLogined'])$arr['AppLoginedShow'] = '已登录';
		return $arr;
	}

	function loginUser($user){
		if(!$this->checkUser($user['openid'])){
			$userinfo = array(
				'Sex' => $user['sex'],
				'City' => $user['city'],
				'Province' => $user['province'],
				'Country' => $user['country'],
				'Subscribe_time' => $user['subscribe_time']
			);
			$arr = array(
				'OpenId' => $user['openid'],
				'Subscribe' => $user['subscribe'],
				'NickName' => $user['nickname'],
				'Thumbnail' => $user['headimgurl'],
				'UserInfo' => $userinfo
			);
			if($user = $this->add($arr)){
				$this->putSession($user);
			}
		}
	}

	function addUser($arr){
		$arr = $this->setModel($arr);
		if(is_numeric($arr)){$Data['ErrorCode'] = $arr;return $Data;}
		if($user = $this->add($arr)){
			return  $user;
		}
		return false;
	}

	function getUserInfo($arr){
		return $this->getRow($arr);
	}

	function addAddress($userid,$arr,$first=0){
		$Address = $this->session->Addresses;
		if($first && $Address){
			array_unshift($Address,$arr);
		}else{
			$Address[] = $arr;
		}
		if($this->update($userid,array('Addresses'=>$Address))){
			$this->session->set_userdata('Addresses',$Address);
			return $arr;
		}
		return false;
	}

	function addFavorite($userid,$productId){
		$Favorite = $this->session->Favorites;
		$Favorite[] = $productId;
		$Favorite = array_unique($Favorite); //去重
		if($this->update($userid,array('Favorites'=>$Favorite))){
			$this->session->set_userdata('Favorites',$Favorite);
			return $Favorite;
		}
		return false;
	}

	function delFavorite($userid,$productId){
		$Favorite = $this->session->Favorites;
		if($Favorite){
			foreach($Favorite as $k=>$v){
				if($v==$productId){
					unset($Favorite[$k]);
				}
			}
			if($this->update($userid,array('Favorites'=>$Favorite))){
				$this->session->set_userdata('Favorites',$Favorite);
				return $Favorite;
			}
		}
		return false;
	}

	function updAddress($userid,$arr,$addressId){
		$Address = $this->session->Addresses;
		foreach($Address as $k=>$v){
			if($v['AddressId']==$addressId){
				$Address[$k] = $arr;break;
			}
		}
		if($this->update($userid,array('Addresses'=>$Address))){
			$this->session->set_userdata('Addresses',$Address);
			return $arr;
		}
		return false;
	}

	function getAddress($addressId){
		$Address = $this->session->Addresses;
		foreach($Address as $v){
			if($v['AddressId']==$addressId)return $v;
		}
		return false;
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
		if(!$arr['OpenId']){
			return 104;
		}else{
			if($this->checkHas('OpenId',$arr['OpenId'])){
				return 105;
			}
		}
		//$arr['Account'] = $arr['NickName'];
		//$arr['Password'] = md5($arr['Password']);
		return $arr;
	}

	//将登陆信息保存到session
	function putSession($user){
		$userInfo = array(
			'UserId'    => $user['id'],
			'OpenId'    => $user['OpenId'],
			'Subscribe' => $user['Subscribe'],
			'NickName'  => $user['NickName'],
			'Thumbnail' => $user['Thumbnail'],
			'UserInfo'  => $user['UserInfo']
		);

		if($user['Favorites']){$userInfo['Favorites'] = $user['Favorites'];}
		if($user['Addresses']){$userInfo['Addresses'] = $user['Addresses'];}
		$this->session->set_userdata($userInfo);
	}
	
	//检查登陆信息
	function checkUser($openId){
		$arr = array(
			'OpenId'  => $openId
		);
		$user = $this->getRow($arr);
		if($user){
			$this->putSession($user);
			return $user['id'];
		}
		return false;
	}

}