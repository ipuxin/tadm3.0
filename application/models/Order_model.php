<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*** 订单类 ***

创建 2016-01-30 刘深远 

*** ***/

class Order_model extends MY_Model {

	private $_model;
	
	public function __construct(){
		parent::__construct();
		$this->init();
	}

	function init(){
		parent::init();
		$this->setTable('Order');
		$TypeArr = array(
			1 => '单买',
			2 => '开团',
			3 => '参团'
		);

		$DeliveryInfo = array(
			'Address' => 'str',
			'ContactName' => 'str',
			'Mobile' => 'num',
			'City' => 'str',
			'Region' => 'str',
			'Type' => 'str'
		);

		$OrderStatus = array(
			1 => '待支付',
			2 => '已支付',
			3 => '待发货',
			4 => '已发货',
			5 => '已完成',
			6 => '已取消',
			7 => '已退款'
		);

		$this->_model = array(
			'OrderId' => 'num',
			'OrderStatus' => $OrderStatus,
			'ShopId' => 'id',
			'UserId' => 'id',

			'CityCode' => '',
			'CityName' => '',

			'OrderType' => $TypeArr,
			'ProductId' => 'str',
			'ProductRealId' => 'num', //产品真实ID
			'ProductCount' => 'num',
			'ProductInfo' => array('ProductName','Description','Image','Prices'),
			'Remark' => 'str',
			'DeliveryInfo' => $DeliveryInfo, //收货信息
			'ProductFee' => 'num', //产品总价
			'freightFee' => 'num', //运费
			'OrderFee' => 'num', //实际需要支付的价格
			
			'PayAmount' => 'num', //支付金额
			'PayType' => array('wechat','alipay','app_wechat','app_alipay'),
			'PayTradeNo' => 'str', //第三方交易号
			'PayStatus' => array('未支付','已支付'),

			'Logistics' => array('LogisticsName','LogisticsCode'), //快递信息
			'CreatTime' => 'time',

			//拼团特有属性
			'TeamId' => 'id',
		);
	}

	function getStatusArr(){
		return $this->_model['OrderStatus'];
	}


//打印日志函数--ci
    function logResultMy($word = '')
    {
        $dir = $this->config->item('data_log_path') . 'MyOrderModel';
        if (!file_exists($dir)) {
            mkdir($dir, '0777', true);
        }
        $fileName = $dir . '/' . 'MyOrderModel' . '.txt';
        $fp = fopen($fileName, "a");
        flock($fp, LOCK_EX);
        fwrite($fp, "执行日期：" . strftime("%Y-%m-%d~%H:%M:%S", time()) . "\r\n" . $word . "\r\n");
        flock($fp, LOCK_UN);
        fclose($fp);
    }

    //如果确认收货超过5天,不能退款
	function refundOrder($orderId,$refund_fee = ''){
		$order = $this->getOrderInfo($orderId);

        $RealReceiptTime = $order['RealReceiptTime'];
//            $timeDelay = 5*24*3600;//5天
        $timeDelay = 5*60;
        $endTime = $RealReceiptTime + $timeDelay;

		if($order['PayType']=='微信'){
			include_once(APPPATH."controllers/WxRefund/WxPay.Api.php");
		}elseif($order['PayType']=='微信APP'){
			include_once(APPPATH."controllers/WxRefundApp/WxPay.Api.php");
		}

		$total_fee = $order['PayAmount'] * 100;
		$last_fee = ($order['PayAmount'] - $order['RefundFee']) * 100;
		$transaction_id = $order['PayTradeNo'];
		if(!$refund_fee){
			$refund_fee = $last_fee;
		}else{
			$refund_fee = $refund_fee*100;
		}

		if(!$order){$data['ErrorCode'] = 500;$data['ErrorMsg'] = '错误的订单信息';}
		if(!$total_fee || !$transaction_id){$data['ErrorCode'] = 500;$data['ErrorMsg'] = '订单未支付或支付失败';}
		if($refund_fee>$last_fee){$data['ErrorCode'] = 500;$data['ErrorMsg'] = '退款金额超标';}
		if($data)return $data;

		$input = new WxPayRefund();
		$input->SetTransaction_id($transaction_id);
		$input->SetTotal_fee($total_fee);
		$input->SetRefund_fee($refund_fee);
		$input->SetOut_refund_no(WxPayConfig::MCHID.date("YmdHis"));
		$input->SetOp_user_id(WxPayConfig::MCHID);
		$return = WxPayApi::refund($input);

		if($return['result_code']==FAIL){
			$data['ErrorCode'] = 500;$data['ErrorMsg'] = $return['err_code_des'];
			return $data;
		}

		if($return['return_code']==FAIL){
			$data['ErrorCode'] = 500;$data['ErrorMsg'] = $return['return_msg'];
			return $data;
		}

		$upd = array(
			'RefundFee' => ($total_fee-$last_fee+$refund_fee)/100,
			'RefundTime' => time(),
			'OrderStatus' => 7
		);
		$this->updOrder($order['id'],$upd);
		
		//$this->load->model('shop_model');
		//$shopArr['Balance-'] = $refund_fee/100;
		//$shopArr['BalanceReal-'] = $refund_fee/100;
		//$this->shop_model->updShop($shopArr,$order['ShopId']);

		$this->load->model('shop_balance_model');
		$this->shop_balance_model->OrderRefund($order,$refund_fee);
		$this->shop_balance_model->OrderRefund($order,$refund_fee,1);

		$data['ErrorCode'] = 0;
		$data['ErrorMsg'] = '退款成功';

		return $data;
	}

	function creatOrder($arr){
		if($order = $this->add($arr)){
			$data['Order'] = $order;
		}
		$data['ErrorCode'] = $this->_return_code;
		$data['ErrorMsg'] = $this->_return_Message;
		return $data;
	}

	function getOrderList($arr,$order=array(),$limit=array(),$sel=array()){
		if(!$order)$order = array('OrderId','DESC');
		if($this->session->userdata('UserType')==2){
			$CityCode = $this->session->userdata('CityCode');
			$CityCodeNum = intval($CityCode);
			$arr['CityCode'] = $CityCodeNum;
		}

		if($this->session->userdata('UserType')==3){
			$ShopId = $this->session->userdata('ShopRealId');
			$arr['ShopId'] = $ShopId;
		}

		if($this->session->userdata('UserType')==4){
			$this->load->model('shop_model');
			$shopIds = $this->shop_model->InvitationCodeShopIds();
			$arr['ShopId'] = $shopIds;
		}

		$list = $this->getList($arr,$order,$limit,$sel);
		if($list)$list = $this->resetOrderList($list);
		$data['List'] = $list;
		$data['Count'] = $this->_return_Count;
		$data['Limit'] = $this->_return_Limit;
		$data['Skip'] = $this->_return_Skip;
		return $data;
	}

	function orderCancel($arr){
		if($num = $this->update($arr,array('OrderStatus'=>6))){
			$data['OrderUpdates'] = $num;
			$this->load->model('product_model');
			$order = $this->getOrderInfo($arr['id']);
			if($num = $this->product_model->update($order['ProductId'],array('SalesCount.Waiting-'=>$order['ProductCount']))){
				$data['ProductUpdates'] = $num;
			}
		}
		$data['ErrorCode'] = $this->_return_code;
		$data['ErrorMsg'] = $this->_return_Message;
		return $data;
	}

	function getOrderInfo($arr){
		$order = $this->getRow($arr);
		if($order)$order = $this->resetOrder($order);
		$order['KuaidiInfo'] = $this->getKuaidiInfo('',$order);
		return $order;
	}

	function resetOrderList($list){
		foreach($list as $k=>$v){
			$list[$k] = $this->resetOrder($v);
		}
		return $list;
	}

	function getKuaidiInfo($id = '',$order = ''){
		if(!$order){$order = $this->getOrderInfo($id);}
		$Code = $order['Logistics']['LogisticsCode'];
		$Num = $order['Logistics']['LogisticsNum'];

		$url = 'http://106.75.62.247:81/api/logistics/trace?lid='.$Num.'&sid='.$Code;
		$res = $this->getcurl($url);
		return $res['Result'];
	}

	function resetOrder($arr){
		if($arr['OrderType'])$arr['OrderTypeMsg'] = $this->_model['OrderType'][$arr['OrderType']];
		if($arr['ProductType'])$arr['ProductTypeMsg'] = $this->_model['ProductType'][$arr['ProductType']];
		if($arr['OrderStatus'])$arr['OrderStatusMsg'] = $this->_model['OrderStatus'][$arr['OrderStatus']];
		if($arr['ProductInfo'])$arr['ProductName'] = $arr['ProductInfo']['ProductName'];
		if($arr['DeliveryInfo'])$arr['Mobile'] = $arr['DeliveryInfo']['Mobile'];
		if($arr['DeliveryInfo'])$arr['RealName'] = $arr['DeliveryInfo']['RealName'];
		if($arr['CreatTime'])$arr['CreatDateShow'] = date('Y-m-d H:i:s',$arr['CreatTime']);
		if($arr['RefundTime'])$arr['RefundDate'] = date('Y-m-d H:i:s',$arr['RefundTime']);
		if($arr['ProductList']){
			foreach($arr['ProductList'] as $pro){
				$ProductName[] = $pro['ProductName']." (".$pro['ProductCount']."份) ";
			}
			$arr['ProductNameShow'] = implode('<br>',$ProductName);
		}else{
			$arr['ProductNameShow'] = $arr['ProductName'];
		}
		return $arr;
	}

	function updOrder($where,$arr){
		if($updnum = $this->update($where,$arr)){
			$Data['Num'] = $updnum;
		}else{
			$Data['ErrorCode'] = 3;
		}
		return $Data;
	}

	function orderRefund($id,$fee='',$OrderStatus=''){
		$order = $this->getOrderInfo($id);
		$status = 7;
		$return = $order['ReturnAmount'] ? $order['ReturnAmount'] : 0;
		$return = (($order['PayAmount']*100)-$return*100)/100;

		if($fee){
			if($return<$fee){$res['ErrorCode'] = 256;return $res;}else{$return = $fee;}
			if($OrderStatus)$status = $OrderStatus;
		}

		if($order['PayAmount'] && $order['PayAmount']>=0 && $return>0){
			$refundid = time().rand(10000,99999);
			$url = $this->returnBase().'data.order.refund?id='.$id.'&refundid='.$refundid.'&fee='.$return.'&status='.$status;
			$res = $this->getcurl($url,1);
			if($res['Code']===0){
				$res['ReturnMoney'] = $return;
			}
			return $res;
		}else{
			$res['ErrorCode'] = 255;
			return $res;
		}
	}

	function setModel($arr){
		if(!$arr['ProductName'])return 301;
		if(!$arr['AgentId']){$arr['AgentId']=$this->_AgentId;}
		if(!$arr['AgentId'])return 5;
		if(!$arr['ProductId'])$arr['ProductId'] = $this->getMax('ProductId')+1;
		return $arr;
	}
	
}