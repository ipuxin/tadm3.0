<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*** 2015-12-11 刘深远 ***

通用控制器基类
加载通用辅助类（页面跳转，session，加密等）
加载接口model类 （ajax_model）
检查用户登陆状态
配置模板方法通用属性，页面标准头、尾

*** ***/

class MY_Controller extends CI_Controller {

	public $_UserId;
	public $_UserName;
	public $_PassWord;
	public $_Account;
	public $_AgentId;
	public $_UserType;
	public $_CityName;
	public $_CityCode;
	public $_ShopId;
	public $_ShopName;
	public $_ShopRealId;
	public $_InvitationCode;
	public $_page_title; //页面名称*/
	
	public function __construct(){
		header("Content-Type: text/html; charset=UTF-8");
		parent::__construct();
		
		$this->load->model('admin_model');
		$this->init();

		if($this->router->class!='admin' && $this->router->class!='main'){
			$this->checkLogin();
		}
	}

	function init(){
		$this->_UserId = $this->session->userdata('UserId');
		$this->_UserName = $this->session->userdata('Username');
		$this->_PassWord = $this->session->userdata('Password');
		$this->_Account = $this->session->userdata('Account');
		$this->_AgentId = $this->session->userdata('AgentId');
		$this->_UserType = $this->session->userdata('UserType');
		$this->_CityName = $this->session->userdata('CityName');
		$this->_CityCode = $this->session->userdata('CityCode');
		$this->_ShopId = $this->session->userdata('ShopId');
		$this->_ShopName = $this->session->userdata('ShopName');
		$this->_ShopRealId = $this->session->userdata('ShopRealId');
		$this->_InvitationCode = $this->session->userdata('InvitationCode');
	}

	function checkLogin(){
		if($this->uri->segment(1)=='board' && $this->uri->segment(2)=='insertBoard'){
			return;
		}
		if(!$this->_UserId || !$this->_UserName || !$this->_PassWord || !$this->_UserType){
			redirect('/');
		}
	}

	function getAgentCitys(){
		$cityCode = explode(',',$this->_CitysCode);
		$cityName = explode(',',$this->_CitysName);
		return array('Code'=>$cityCode,'Name'=>$cityName,'Data'=>array_combine($cityCode,$cityName));
	}

	function sendMoban($type,$user,$info){
		if($type=='returnMoney'){$type='sendMobanReturnMoney';}
		if($type=='teamFinish'){$type='sendMobanTeamFinish';}
		if($type=='productSend'){$type='sendMobanProductSend';}
		$url = $this->config->item('web_url').'api/'.$type;
		$data = array(
			'token' => 'dwerdwer-pyx',
			'user' => $user,
			'info' => $info
		);
		$data = json_encode($data);
		$res = $this->getcurl($url,$data,1);
	}
	
	function view($page,$data,$tem=1){
		if($this->config->item('pg_version_open')){
			$data['version'] = $this->config->item('pg_version');
		}
		$data['resUrl'] = $this->config->item('res_url');
		$data['staticPath'] = $this->config->item('static_file_path');
		$data['apiHost'] = $this->config->item('db_api_base');
		$data['title'] = $this->_page_title;

		$data['username'] = $this->_UserName;
		$data['account'] = $this->_Account;
		$data['userType'] = $this->_UserType;

		if($tem)$this->load->view('templates/header',$data);
		$this->load->view($page,$data);
		if($tem)$this->load->view('templates/footer',$data);
	}

	function checkAdminType($k){
		if($k=='admin'){
			if($this->_UserType!=1){exit();}
		}elseif($k=='hehuo'){
			if($this->_UserType!=2){exit();}
		}elseif($k=='dianpu'){
			if($this->_UserType!=3){exit();}
		}elseif($k=='mendian'){
			if($this->_UserType!=4){exit();}
		}else{
			exit();
		}
	}

	function checkQuanxian($k){
		$qxList = $this->config->item('PyxQuanxian'.$this->_UserType);
		if(!$qxList || !in_array($k,$qxList)){
			exit();
		}
	}

	function checkPurview($k){
		$pvList = $this->config->item('PyxPurview'.$this->_UserType);
		if(!$pvList || !in_array($k,$pvList)){
			exit();
		}
	}

	function returnJson($data){
		if(!$data['ErrorCode'])$data['ErrorCode'] = 0;
		$ErrorMsg = $this->config->item('ErrorMsg');
		if(!$data['ErrorMsg'])$data['ErrorMsg'] = $ErrorMsg[$data['ErrorCode']];
		echo json_encode($data);
	}

	function getRandStr($length = 8) {  
		$chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';  
		$password = '';  
		for($i = 0;$i<$length;$i++){  
			$password .= $chars[ mt_rand(0, strlen($chars) - 1) ];  
		}  
		return $password;
	}

	function getcurl($url,$data=array(),$log=0){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT,5);
		curl_setopt($ch, CURLOPT_FAILONERROR, 1);
		curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
		// 这一句是最主要的
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		$response = curl_exec($ch);
		//$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		if($log){
		$fileName = $this->config->item('log_path').date('Y-m-d').'.txt';
		$word = 'URL:'.$url."\r\n";
		if($data)$word .= 'Date:'.json_encode($data)."\r\n";
		$word .= 'Respond:'.$response."\r\n";
		$fp = fopen($fileName,"a");
		flock($fp, LOCK_EX);
		fwrite($fp,"执行日期：".strftime("%Y%m%d%H%M%S",time())."\r\n".$word."\r\n");
		flock($fp, LOCK_UN);
		fclose($fp);
		}

		curl_close($ch);
		return json_decode($response,TRUE);
	}

}