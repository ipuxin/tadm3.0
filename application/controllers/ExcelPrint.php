<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*** 2016-02-28 刘深远 ***

文件导出类

*** ***/


class ExcelPrint extends MY_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model('order_model');
	}

	//按条件导出订单
	public function outputListSel(){
		$this->checkPurview('orderListDaochu');
		extract($this->input->post());
		require_once FCPATH."data/phpexcel/Classes/PHPExcel.php";
		require_once FCPATH."data/phpexcel/Classes/PHPExcel/IOFactory.php";
		require_once FCPATH."data/phpexcel/Classes/PHPExcel/Writer/Excel5.php";

		if($OrderStatus>0)$arr['OrderStatus'] = $OrderStatus;
		if($ShopName)$arr['ShopName'] = "%*".$ShopName."*%";
		if($CityName)$arr['CityName'] = "%*".$CityName."*%";
		if($id)$arr['id'] = "%*".$id."*%";
		if($ProductName)$arr['ProductInfo.ProductName'] = "%*".$ProductName."*%";
		if($ProductId)$arr['ProductId'] = $ProductId;
		if($RealName)$arr['DeliveryInfo.RealName'] = "%*".$RealName."*%";
		if($Mobile)$arr['DeliveryInfo.Mobile'] = "%*".$Mobile."*%";
		if($ProductType)$arr['ProductType'] = $ProductType;
		if($ProductFee==0)$arr['ProductFee'] = 0;
		if($ProductFee==1)$arr['ProductFee!'] = 0;

		//if($CreateTimeStart)$arr['CreatTime>'] = strtotime($CreateTimeStart);
		//if($CreateTimeEnd)$arr['CreatTime<'] = strtotime($CreateTimeEnd)+24*3600-1;

		$orderList = $this->order_model->getOrderList($arr,array('OrderId','DESC'),'',array('OrderId','ProductInfo.ProductName','DeliveryInfo','ProductId','OrderFee','PayAmount','ProductCount','TeamId','OrderType','Remark','CreatTime','ProductList','ShopName'));

		if(empty($orderList['List'])){
			echo "没有符合条件的订单";
			exit();
		}
		$titles = array(
			'订单ID',
			'商品名称',
			'商品ID',
			'订单价格',
			'支付金额',
			'商品数量',
			'团购ID',
			'订单类型',
			'店铺',
			'省',
			'市',
			'区',
			'地址',
			'电话',
			'收货人姓名',
			'订单备注',
			'下单时间',
			'快递编号',
			'快递名称',
			'快递单号');

		foreach($orderList['List'] as $k=>$v){

			if($v['ProductList']){
				$ProductName = '';
				$ProductCount = '';
				foreach($v['ProductList'] as $pro){
					$ProductName[] = $pro['ProductName'];
					$ProductCount[] = $pro['ProductCount'];
				}
				$v['ProductName'] = implode("\r\n",$ProductName);
				$v['ProductCount'] = implode("\r\n",$ProductCount);
			}

			if(is_array($v['ProductId'])){
				$v['ProductId'] = implode("\r\n",$v['ProductId']);
			}
			
			if($v['DeliveryInfo']['CityName']=='北京市市辖区'){$v['DeliveryInfo']['CityName']='北京市';}
			if($v['DeliveryInfo']['CityName']=='天津市市辖区'){$v['DeliveryInfo']['CityName']='天津市';}
			if($v['DeliveryInfo']['CityName']=='天津市郊县'){$v['DeliveryInfo']['CityName']='天津市';}
			if($v['DeliveryInfo']['CityName']=='上海市市辖区'){$v['DeliveryInfo']['CityName']='上海市';}
			if($v['DeliveryInfo']['CityName']=='上海市郊县'){$v['DeliveryInfo']['CityName']='上海市';}
			if($v['DeliveryInfo']['CityName']=='重庆市市辖区'){$v['DeliveryInfo']['CityName']='重庆市';}
			if($v['DeliveryInfo']['CityName']=='重庆市郊县'){$v['DeliveryInfo']['CityName']='重庆市';}

			$v['Remark'] = preg_replace("/[^\x{4e00}-\x{9fa5}]/iu",'',$v['Remark']);
			$v['DeliveryInfo']['RealName'] = preg_replace("/[^\x{4e00}-\x{9fa5}]/iu",'*',$v['DeliveryInfo']['RealName']);

			$value[] = array(
				$v['OrderId'],
				$v['ProductName'],
				$v['ProductId'],
				$v['ProductCount'],
				$v['OrderFee'],
				$v['PayAmount'],
				$v['TeamId'],
				$v['OrderTypeMsg'],
				$v['ShopName'],
				$v['DeliveryInfo']['ProviceName'],
				$v['DeliveryInfo']['CityName'],
				$v['DeliveryInfo']['DistrictName'],
				$v['DeliveryInfo']['Address'],
				$v['DeliveryInfo']['Mobile'],
				$v['DeliveryInfo']['RealName'],
				$v['Remark'],
				$v['CreatDateShow'],
				$v[''],
				$v[''],
				$v['']);
		}

		//exit();

		$resultPHPExcel = new PHPExcel();

		$resultPHPExcel->getActiveSheet()->setCellValue('A1', '订单ID'); 
		$resultPHPExcel->getActiveSheet()->setCellValue('B1', '商品名称');
		$resultPHPExcel->getActiveSheet()->setCellValue('C1', '商品ID'); 
		$resultPHPExcel->getActiveSheet()->setCellValue('D1', '商品数量'); 
		$resultPHPExcel->getActiveSheet()->setCellValue('E1', '订单价格'); 
		$resultPHPExcel->getActiveSheet()->setCellValue('F1', '支付金额'); 
		$resultPHPExcel->getActiveSheet()->setCellValue('G1', '团购ID'); 
		$resultPHPExcel->getActiveSheet()->setCellValue('H1', '订单类型'); 
		$resultPHPExcel->getActiveSheet()->setCellValue('I1', '店铺'); 
		$resultPHPExcel->getActiveSheet()->setCellValue('J1', '省'); 
		$resultPHPExcel->getActiveSheet()->setCellValue('K1', '市');
		$resultPHPExcel->getActiveSheet()->setCellValue('L1', '区'); 
		$resultPHPExcel->getActiveSheet()->setCellValue('M1', '地址'); 
		$resultPHPExcel->getActiveSheet()->setCellValue('N1', '电话'); 
		$resultPHPExcel->getActiveSheet()->setCellValue('O1', '收货人姓名'); 
		$resultPHPExcel->getActiveSheet()->setCellValue('P1', '订单备注'); 
		$resultPHPExcel->getActiveSheet()->setCellValue('Q1', '下单时间'); 
		$resultPHPExcel->getActiveSheet()->setCellValue('R1', '快递编号'); 
		$resultPHPExcel->getActiveSheet()->setCellValue('S1', '快递名称'); 
		$resultPHPExcel->getActiveSheet()->setCellValue('T1', '快递单号'); 
		
		foreach($value as $k=>$v){
			$key = $k+2;
			$resultPHPExcel->getActiveSheet()->setCellValue('A'.$key, $v[0]); 
			$resultPHPExcel->getActiveSheet()->setCellValue('B'.$key, $v[1]); 
			$resultPHPExcel->getActiveSheet()->getStyle('B'.$key)->getAlignment()->setWrapText(true);
			$resultPHPExcel->getActiveSheet()->setCellValue('C'.$key, $v[2]); 
			$resultPHPExcel->getActiveSheet()->getStyle('C'.$key)->getAlignment()->setWrapText(true);
			$resultPHPExcel->getActiveSheet()->setCellValue('D'.$key, $v[3]); 
			$resultPHPExcel->getActiveSheet()->getStyle('D'.$key)->getAlignment()->setWrapText(true);
			$resultPHPExcel->getActiveSheet()->setCellValue('E'.$key, $v[4]); 
			$resultPHPExcel->getActiveSheet()->setCellValue('F'.$key, $v[5]); 
			$resultPHPExcel->getActiveSheet()->setCellValue('G'.$key, $v[6]); 
			$resultPHPExcel->getActiveSheet()->setCellValue('H'.$key, $v[7]); 
			$resultPHPExcel->getActiveSheet()->setCellValue('I'.$key, $v[8]); 
			$resultPHPExcel->getActiveSheet()->setCellValue('J'.$key, $v[9]); 
			$resultPHPExcel->getActiveSheet()->setCellValue('K'.$key, $v[10]); 
			$resultPHPExcel->getActiveSheet()->setCellValue('L'.$key, $v[11]); 
			$resultPHPExcel->getActiveSheet()->setCellValue('M'.$key, $v[12]); 
			$resultPHPExcel->getActiveSheet()->setCellValue('N'.$key, $v[13]); 
			$resultPHPExcel->getActiveSheet()->setCellValue('O'.$key, $v[14]); 
			$resultPHPExcel->getActiveSheet()->setCellValue('P'.$key, $v[15]); 
			$resultPHPExcel->getActiveSheet()->setCellValue('Q'.$key, $v[16]); 
			$resultPHPExcel->getActiveSheet()->setCellValue('R'.$key, $v[17]); 
			$resultPHPExcel->getActiveSheet()->setCellValue('S'.$key, $v[18]); 
			$resultPHPExcel->getActiveSheet()->setCellValue('T'.$key, $v[19]); 
		}
		
		$outputFileName = 'order_'.date('Y-m-d_H:i:s',time()).".xls";
		$xlsWriter = new PHPExcel_Writer_Excel5($resultPHPExcel); 
		//ob_start(); ob_flush(); 
		header("Content-Type: application/force-download"); 
		header("Content-Type: application/octet-stream"); 
		header("Content-Type: application/download"); 
		header('Content-Disposition:inline;filename="'.$outputFileName.'"'); 
		header("Content-Transfer-Encoding: binary"); 
		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); 
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); 
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0"); 
		header("Pragma: no-cache"); 
		$xlsWriter->save("php://output");
	}

}