<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*** 产品类 ***

创建 2016-01-29 刘深远 

*** ***/

class Product_model extends MY_Model {

	private $_model;
	
	public function __construct(){
		parent::__construct();
		$this->init();
	}

	function init(){
		parent::init();
		$this->setTable('Product');
		$IsDisableArr = array(
			0 => '启用',
			1 => '禁用',
			2 => '待审核',
			3 => '审核不通过'
		);

		$IsForSaleArr = array(
			0 => '下架',
			1 => '上架'
		);

		$IsCheckedArr = array(
			0 => '待审核',
			1 => '审核通过',
			2 => '审核不通过'
		);

		$TypeArr = array(
			1 => '普通商品',
			2 => '拼团商品',
			3 => '免费试用',
			4 => '一元夺宝',
			5 => '幸运抽奖'
		);

		$TeamTypeArr = array(
			1 => '普通拼团',
			2 => '秒杀',
			3 => '办公室',
			4 => '校园',
			5 => '家庭'
		);

		$StatusArr = array(
			1 => '预告中',
			2 => '进行中',
			3 => '已完成'
		);

		$this->_model = array(
			'ProductId' => 'num',
			'ShopId' => '',
			'ProductName' => 'str',
			'ProductNameMin' => 'str',
			'Description' => 'str',
			'DescriptionMin' => 'str',
			'Content' => 'text',
			'ImageMin' => 'url', //小图，展示图
			'Images' => 'list', //相册
			'ProductType' => $TypeArr,
			'Tags' => 'array', //分类标签
			'Prices' => array('Normal','Team','Market'),
			//'ProductRelation' => 'array', //关联产品id
			
			'StorageCount' => 'num',//存货数量
			'SalesCount'=> array('Waiting','Real','Adjust','Set_sale'),
			'DisplayRange' => array('all','wechat','app'), //显示范围
			'ProductStatus' => $StatusArr,
			'DeliverAddress' => 'str', //发货地址
			'freightAmout' => 'str', //运费
			
			/* 购买限制参数 */
			'NeedAppLogined' => 'num', //需要APP登录才能购买
			'NewUserOnly' => 'num', //需要新人才能购买
			'OnceSalesLimit' => 'num',//每次最多购买数量。
			'BuyCountLimit' => 'num',//可购买次数。

			'CityCode' => 'str',
			'CityName' => 'str',
			'DisplayRange' => array('','all'), //显示方式

			'Priority' => 'num', //排序
			'IsForSale' => $IsForSaleArr, //上架，商家控制属性
			'IsDisable' => $IsDisableArr, //后台管理员控制属性
			'IsChecked' => $IsCheckedArr,
			'IsHide' => 'num', //伪删除，隐藏
			'CreatTime' => '',

			/* 拼团特有参数 */
			'TeamType' => $TeamTypeArr,
			'ProductEndDate' => 'date', //产品结束时间
			'Alive' => 'num', //拼团的存活时间，单位小时
			'TeamMemberLimit' => 'num',//团购人数上限
			'LotteryCount' => 'num', //抽奖人数
			'NewMemberCount' => 'num', //新人参与人数
			'ShowTeamList' => 'num', //展示当前参团列表
			'IsTuanzhangGet' => 'num', //是否团长代收货
			'IsCountDown' => 'num', //是否倒计时功能（秒杀）
		);
	}

	function getProductList($arr,$order=array(),$limit,$sel=array()){
		$arr['IsHide!'] = 1;
		if(!$order)$order=array('ProductId','DESC');

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
		if($list)$list = $this->resetProductList($list);
		$data['List'] = $list;
		$data['Count'] = $this->_return_Count;
		$data['Limit'] = $this->_return_Limit;
		$data['Skip'] = $this->_return_Skip;
		return $data;
	}

	function resetProductList($list){
		foreach($list as $k=>$v){
			$list[$k] = $this->resetProduct($v);
		}
		return $list;
	}

	function resetProduct($v){
		if(!$v)return;
		if($v['ProductType'])$v['ProductTypeMsg'] = $this->_model['ProductType'][$v['ProductType']];
		if($v['ProductStatus'])$v['ProductStatusMsg'] = $this->_model['ProductStatus'][$v['ProductStatus']];
		if($v['ProductStatus'])$v['IsDisable'] = $this->_model['IsDisable'][$v['IsDisable']];
		/*if($v['ImageList'] && count($v['Images'])){
			$ImagesShow = array();
			foreach($v['ImageList'] as $img){
				$ImagesShow[] = $this->config->item('res_url').$img;
			}
			$v['ImageList'] = $ImagesShow;
		}

		if($v['ImageMin']){$v['ImageMin'] = $this->config->item('res_url').$v['ImageMin'];}
		if($v['ImageBig']){$v['ImageBig'] = $this->config->item('res_url').$v['ImageBig'];}*/
		if($v['SalesCount']){
			$v['SalesCountReal'] = $v['SalesCount']['Waiting'] + $v['SalesCount']['Real'];
			if($v['StorageCount'])$v['StorageCountReal'] = $v['StorageCount'];
		}
		if($v['Category1'] || $v['Category2']){
			$this->load->model('category_model');
			if($v['Category1']){
				$Category1 = $this->category_model->getRow($v['Category1']);
				$v['Category1Name'] = $Category1['CateName'];
			}
			if($v['Category2']){
				$Category2 = $this->category_model->getRow($v['Category2']);
				$v['Category2Name'] = $Category2['CateName'];
			}
		}
		if(isset($v['IsDisable']))$v['IsDisableMsg'] = $this->_model['IsDisable'][$v['IsDisable']];
		if(isset($v['IsForSale']))$v['IsForSaleMsg'] = $this->_model['IsForSale'][$v['IsForSale']];
		if(isset($v['IsChecked']))$v['IsCheckedMsg'] = $this->_model['IsChecked'][$v['IsChecked']];
		return $v;
	}
	
	function getProduct($arr,$sel=array()){
		$product = $this->getRow($arr,$sel);
		$product = $this->resetProduct($product);
		return $product;
	}

	function addProduct($arr){
		$arr = $this->setModel($arr);
		if(is_numeric($arr)){
			$Data['ErrorCode'] = $arr;
			if($arr==301)$Data['ErrorMessage'] = '宝贝名称不能为空';
			if($arr==302)$Data['ErrorMessage'] = '宝贝ID创建失败';
			return $Data;
		}
		if($pro = $this->add($arr)){
			$Data['Pro'] = $pro;
		}else{
			$Data['ErrorCode'] = 4;
		}
		return $Data;
	}

	function updProduct($arr,$where=array()){
		if(!$where)$where = $arr['id'];
		$arr = $this->setModel($arr,'upd');
		if($updnum = $this->update($where,$arr)){
			$Data['Num'] = $updnum;
		}else{
			$Data['ErrorCode'] = 3;
		}
		return $Data;
	}

	function hideProduct($id){
		$arr = array(
			'id' => $id,
			'IsHide' => 1
		);
		if($updnum = $this->update($arr['id'],$arr)){
			$Data['Num'] = $updnum;
		}else{
			$Data['ErrorCode'] = 3;
		}
		return $Data;
	}

	function delProduct($arr){
		if($delnum = $this->del($arr)){
			$Data['Num'] = $delnum;
		}else{
			$Data['ErrorCode'] = 2;
		}
		return $Data;
	}

	function setModel($arr,$type="add"){
		if($type=='add'){
			if(!$arr['ProductName'])return 301;
			if(!$arr['ProductId']){$arr['ProductId'] = $this->getMax('ProductId');}
			if($arr['ProductId']!==false){
				$arr['ProductId'] = $arr['ProductId'] + rand(10,39);
			}else{
				return 302;
			}
			if(!$arr['SalesCount']){
				$arr['SalesCount'] = array(
					    'Waiting' => 0,
					    'Real' => 0,
					    'Adjust' => 0,
					    'Set_sale' => 0,
				);
			}
			if(!$arr['CreatTime'])$arr['CreatTime'] = time();
			if(!$arr['ProductType'])$arr['ProductType'] = 1;
			if(!$arr['Prices']['Market'])$arr['Prices']['Market'] = intval($arr['Prices']['Normal']*1.2);

			$arr['Prices']['Normal'] = round($arr['Prices']['Normal'],2);

			$arr['IsDisable'] = 0;
			$arr['IsHide'] = 0;
		}

		if($type=='upd'){
			if($arr['Prices']['Normal']){
				$arr['Prices']['Normal'] = round($arr['Prices']['Normal'],2);
				if(!$arr['Prices']['Market'])$arr['Prices']['Market'] = intval($arr['Prices']['Normal']*1.2);
			}
			if(!$arr['SalesCount']){
				$arr['SalesCount'] = array(
						'Waiting' => intval($arr['Waiting']),
						'Real' => intval($arr['Real']),
						'Set_sale' => intval($arr['Set_sale']),
						'Adjust' => intval($arr['Set_sale'] += intval($arr['Real']))
				);
			}
		}
		if($type=='Adjust'){
			if(!$arr['SalesCount']){
				$arr['SalesCount'] = array(
					'Waiting' => intval($arr['Waiting']),
					'Real' => intval($arr['Real']),
					'Adjust' => intval($arr['Adjust'])
				);
			}
		}
		return $arr;
	}
	function Adjust($arr,$where= array())
	{
		$arr = $this->setModel($arr,'Adjust');
		if($updnum = $this->update($where,$arr)){
			return true;
		}else{
			return $Data['ErrorCode'] = 3;
		}
	}
	
}