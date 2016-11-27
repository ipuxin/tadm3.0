<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Order extends MY_Controller {

	public function __construct(){
		parent::__construct();
		$this->checkQuanxian('order');
		$this->load->model('order_model');
	}

	public function getOrderList(){
		$this->checkPurview('orderList');
		extract($this->input->post());

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

		$sel = array('OrderId','OrderType','Type','OrderStatus','ProductInfo.ProductName','DeliveryInfo','ProductCount','ProductFee','Remark','CreatTime','UserId','CityName','CityCode','TeamId','ProductList','ShopName');

		$orderList = $this->order_model->getOrderList($arr,array('OrderId','DESC'),array($perpage,($page-1)*$perpage),$sel);
		$res['OrderList'] = $orderList['List'];
		$res['Count'] = $orderList['Count'];
		$res['PerPage'] = $orderList['Limit'];
		$this->returnJson($res);
	}

	public function Updorder(){
		$this->checkPurview('orderUpd');
		extract($this->input->post());

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
		}

		$where = array(
			'id' => $id
		);

		if($this->_UserType==3){
			$where['ShopId'] = $this->_ShopRealId;
		}
		
		$this->order_model->updOrder($where,$arr);
		$this->returnJson($res);
	}

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