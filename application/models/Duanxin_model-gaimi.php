<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*** 短信类 ***

创建 2016-08-01 刘深远 

*** ***/

class Duanxin_model extends MY_Model {

	private $_model;
	private $_admin_prefix;
	private $_admin_password;
	
	public function __construct(){
		parent::__construct();
		$this->init();
	}

	function init(){
		parent::init();
	}

	function Send($mobile, $text){
		$url = "http://sh2.ipyy.com/ensms.ashx";
		$userId = 2785;
		$userName = "jksc614";
		$password = "jack123456";

		$stamp = gmdate('mdHis', time() + 3600 * 8);
		$secret = $password.$stamp;
		$secret = $this->Md5Encrypt($secret);
		$jsonParam = new stdClass();
		$jsonParam->UserName = $userName;
		$jsonParam->Secret = $secret;
		$jsonParam->Stamp = $stamp;
		$jsonParam->Moblie = $mobile;
		$jsonParam->Text = $text;
		$jsonParam->Ext = "";
		$jsonParam->SendTime = "";

		$jsonString = json_encode($jsonParam);

		$key = $password;
		$key = substr($key,0,8);
		$key = str_pad($key,8,"\0",STR_PAD_RIGHT);

		$data = $this->DESEncrypt($jsonString,$key);

		$text64 = base64_encode($data);
		$params = "UserId=".$userId."&Text64=".urlencode($text64);
		$resultString = file_get_contents($url."?".$params);
		$result = json_decode($resultString);
		return $result;
	}

	function Md5Encrypt($text){
		$ret = md5($text);
		return strtoupper($ret);
	}

	function getBytes($string) {  
		$bytes = array();  
		for($i = 0; $i < strlen($string); $i++){  
			$bytes[] = ord($string[$i]);  
		}  
		return $bytes;  
	}  

	function DESEncrypt($value,$key){
		$iv = $key;
		$td = mcrypt_module_open(MCRYPT_3DES, '', MCRYPT_MODE_CBC, '');
		$value = $this->PaddingPKCS7($value);
		mcrypt_generic_init($td, $key, $iv);
		$ret = mcrypt_generic($td, $value);
		mcrypt_generic_deinit($td);
		mcrypt_module_close($td);
		return $ret;
	}

	function PaddingPKCS7($data){
		$block_size = mcrypt_get_block_size('tripledes', 'cbc');
		$padding_char = $block_size - (strlen($data) % $block_size);
		$data .= str_repeat(chr($padding_char), $padding_char);
		return $data;
	}

	function SendShopOpen($mobile,$shenhe){
		$text = '【拼一下】您申请的本地商家申请已经通过！登录账号：'.$shenhe['mobile'].'，登录密码：'.$shenhe['password'].'，登录网站：adm.pingoing.cn。请您妥善保管好您的个人账号信息。';
		$res = $this->Send($mobile, $text);
        $this->logResultmy(json_encode($res));
        $this->logResultmy(json_encode($mobile));
        $this->logResultmy(json_encode($shenhe));
    }

	function SendTest($mobile){
		//$text = "【拼一下】123456(动态验证码),请在30分钟内填写";
		//$this->Send($mobile, $text);
	}

    function logResultmy($word = '')
    {
        $dir = $this->config->item('data_log_path') . 'MyDuanxin';
        if (!file_exists($dir)) {
            mkdir($dir, '0777', true);
        }
        $fileName = $dir . '/' . 'MyDuanxin' . '.txt';
        $fp = fopen($fileName, "a");
        flock($fp, LOCK_EX);
        fwrite($fp, "执行日期：" . strftime("%Y-%m-%d~%H:%M:%S", time()) . "\r\n" . $word . "\r\n");
        flock($fp, LOCK_UN);
        fclose($fp);
    }

}