<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Order extends MY_Controller {

	public function __construct(){
		parent::__construct();
		$this->checkQuanxian('order');
		$this->load->model('order_model');
	}

//打印日志函数--ci
    function logResultmy($word = '')
    {
        $dir = $this->config->item('data_log_path') . 'MyOrder_Updorder_tadm';
        if (!file_exists($dir)) {
            mkdir($dir, '0777', true);
        }
        $fileName = $dir . '/' . 'MyOrder_Updorder_tadm' . '.txt';
        $fp = fopen($fileName, "a");
        flock($fp, LOCK_EX);
        fwrite($fp, "执行日期：" . strftime("%Y-%m-%d~%H:%M:%S", time()) . "\r\n" . $word . "\r\n");
        flock($fp, LOCK_UN);
        fclose($fp);
    }


    public function getOrderList(){
        $this->logResultmy(json_encode('getOrderList'));
        $this->logResultmy(json_encode($this->input->post()));

		$this->checkPurview('orderList');
		extract($this->input->post());

        $this->logResultmy(json_encode('---arr-Begin---'));
        $this->logResultmy(json_encode($arr));

		if($OrderStatus)$arr['OrderStatus'] = $OrderStatus;
		if($OrderStatus==1){$arr['OrderStatus'] = '[0,1]';}
		//if($id)$arr['OrderId'] = "%*".$id."*%";
		if($id)$arr['OrderId'] = $id;
		if($ProductName)$arr['ProductInfo.ProductName'] = "%*".$ProductName."*%";
		if($ProductShowId)$arr['ProductShowId'] = $ProductShowId;
		if($RealName)$arr['DeliveryInfo.RealName'] = "%*".$RealName."*%";
		if($Mobile)$arr['DeliveryInfo.Mobile'] = "%*".$Mobile."*%";
		//if($ProductFee==0)$arr['ProductFee'] = 0;
		//if($ProductFee==1)$arr['ProductFee!'] = 0;
		if($CreateTimeStart)$arr['CreateTime>'] = strtotime($CreateTimeStart);
		if($CreateTimeEnd)$arr['CreateTime<'] = strtotime($CreateTimeEnd)+59;
		if($CityName)$arr['CityName'] = "%*".$CityName."*%";
		if($ShopName)$arr['ShopName'] = "%*".$ShopName."*%";
		if($ProductType>0)$arr['ProductInfo.ProductType'] = $ProductType;

        $this->logResultmy(json_encode('---CityName---'));
        $this->logResultmy(json_encode($CityName));

        $this->logResultmy(json_encode('---arr-Begin2---'));
        $this->logResultmy(json_encode($arr));

		$sel = array('OrderId','OrderType','Type','OrderStatus','ProductInfo.ProductName','DeliveryInfo','ProductCount','ProductFee','Remark','CreatTime','UserId','CityName','CityCode','TeamId','ProductList','ShopName');

		$orderList = $this->order_model->getOrderList($arr,array('OrderId','DESC'),array($perpage,($page-1)*$perpage),$sel);

        $this->logResultmy(json_encode('---orderList---'));
        $this->logResultmy(json_encode($orderList));

        $res['OrderList'] = $orderList['List'];
		$res['Count'] = $orderList['Count'];
		$res['PerPage'] = $orderList['Limit'];

        $this->logResultmy(json_encode('---res---'));
        $this->logResultmy(json_encode($res));

		$this->returnJson($res);
	}

	public function Updorder(){
        $this->logResultmy(json_encode('---Updorder---'));

		$this->checkPurview('orderUpd');
		extract($this->input->post());

        $this->logResultmy(json_encode('---Updorder---\n'.$this->input->post()));

		$order = $this->order_model->getOrderInfo($id);

		$arr = array(
			'DeliveryInfo' => $DeliveryInfo,
			'Address' => $Address
		);

		if($Logistics['LogisticsNum'] && $Logistics['LogisticsCode'] && $Logistics['LogisticsName']){
			$arr['Logistics'] = $Logistics;
			if(!$order['Logistics']['LogisticsCode']){
				$arr['FahuoTime'] = time();
			}
		}

		if($OrderStatus<=4){
			$arr['OrderStatus'] = $OrderStatus;
		}

		if($OrderStatus==5 && $this->_UserType==1){
			$arr['OrderStatus'] = $OrderStatus;
            //订单已完成的时间(确认收货时间)
            $arr['RealReceiptTime'] = time();
            $this->logResultmy(json_encode('---Updorder-RealReceiptTime---'));
		}

		$where = array(
			'id' => $id
		);

		if($this->_UserType==3){
			$where['ShopId'] = $this->_ShopRealId;
		}
        $this->logResultmy(json_encode('---Updorder-arr---'));
        $this->logResultmy(json_encode($arr));

        $this->logResultmy(json_encode('---Updorder-where---'));
        $this->logResultmy(json_encode($where));

		$this->order_model->updOrder($where,$arr);
		$this->returnJson($res);
	}

	//退款
	public function Refundorder(){
		$this->checkPurview('orderRefund');
		extract($this->input->post());

		$this->load->model('order_model');
		$res = $this->order_model->refundOrder($id,$refundFee);

		$this->returnJson($res);
	}

	public function excelRead(){
		$this->checkPurview('orderListInput');
		require_once FCPATH."data/phpexcel/Classes/PHPExcel.php";
		require_once FCPATH."data/phpexcel/Classes/PHPExcel/IOFactory.php";
		require_once FCPATH."data/phpexcel/Classes/PHPExcel/Writer/Excel5.php";

		if($_POST['leadExcel'] == "true"){
			$file = $_FILES['inputExcel']['name'];
			$filetempname = $_FILES['inputExcel']['tmp_name'];
			$filePath = 'data/excel/';
			$str = "";
			$time=date("y-m-d-H-i-s");//去当前上传的时间 
			$extend=strrchr($file,'.');
			$name=$time.$extend;
			$uploadfile=$filePath.$name;//上传后的文件名地址 
			$result=move_uploaded_file($filetempname,$uploadfile);//假如上传到当前目录下
			if($result){
				$objReader = PHPExcel_IOFactory::createReader('Excel5');//use excel2007 for 2007 format 
				$objPHPExcel = $objReader->load($uploadfile); 
				$sheet = $objPHPExcel->getSheet(0); 
				$highestRow = $sheet->getHighestRow();           //取得总行数 
				$highestColumn = $sheet->getHighestColumn(); //取得总列数
				for($j=2;$j<=$highestRow;$j++){
					$arr = '';
					for($k='A';$k<=$highestColumn;$k++){
						$arr[] = (string)$objPHPExcel->getActiveSheet()->getCell("$k$j")->getValue();
					}
					$list[] = $arr;
				}

				$updNum = 0;
				$this->load->model('user_model');
				foreach($list as $v){
					$orderId = $v[0];
					$code = $v[1];
					$name = $v[2];
					$num = $v[3];
					if($orderId && $code && $name && $num){

						$where = array(
							'ShopId' => $this->_ShopRealId,
							'OrderId' => $orderId,
							'OrderStatus' => 3
						);

						$arr = array(
							'OrderStatus' => 4,
							'Logistics' => array(
								'LogisticsCode' => $code,
								'LogisticsName' => $name,
								'LogisticsNum' => $num,
							),
							'FahuoTime' => time()
						);

						$upd = $this->order_model->updOrder($where,$arr);
						if($upd['Num']){$updNum += 1;}
					}
				}
			}else{
				//上传失败
			}
		}

		$data['updNum'] = $updNum;
		$this->view('admin3/order/order_excel_input',$data);
	}

}