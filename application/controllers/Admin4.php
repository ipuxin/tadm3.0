<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*** 门店商主控制器 ***

创建 2016-10-19 刘深远 

*** ***/

class Admin4 extends MY_Controller {

	public $_page_pre;

	public function __construct(){
		parent::__construct();
		$this->checkAdminType('mendian');
		$this->_page_pre = 'admin4';
	}

	public function product($page='',$v1=''){
		$this->checkQuanxian('product');
		if($page=='product_upd'){
			$this->checkPurview('productUpd');
			$this->load->model('product_model');
			$data['info'] = $this->product_model->getProduct($v1);
		}
		if($page=='product_info'){
			$this->checkPurview('productInfo');
			$this->load->model('product_model');
			$data['info'] = $this->product_model->getProduct($v1);
		}
		if($page=='product_check'){
			$this->checkPurview('productInfo');
			$this->load->model('product_model');
			$data['info'] = $this->product_model->getProduct($v1);
		}

		$this->view($this->_page_pre.'/product/'.$page,$data);
	}

	public function user($page=''){
		$this->checkQuanxian('user');

		if($page=='user_self_upd'){
			$user = $this->admin_model->getAdmin($this->_UserId);
			$data['user'] = $user['Admin'];
		}

		$this->view($this->_page_pre.'/user/'.$page,$data);
	}

	public function order($page='',$v1=''){
		$this->checkQuanxian('order');

		if($page=='order_list'){
			$data['OrderStatus'] = $v1;
		}
		if($page=='order_info'){
			$this->checkPurview('orderInfo');
			$this->load->model('order_model');
			$this->load->model('shop_model');
			$order = $this->order_model->getOrderInfo($v1);
			//$data['kuaidi'] = $this->kuaidi_model->getKuaidiList(array('Show'=>1));
			$shop = $this->shop_model->getShop($order['ShopId']);
			$data['status'] = $this->order_model->getStatusArr();
			$data['order'] = $order;
			$data['shop'] = $shop;
		}

		$this->view($this->_page_pre.'/order/'.$page,$data);
	}

	public function team($page='',$v1=''){
		$this->checkQuanxian('team');

		if($page=='team_list'){
			$data['TeamStatus'] = $v1;
		}
		if($page=='team_info'){
			$this->checkPurview('teamInfo');
			$this->load->model('team_model');
			$team = $this->team_model->getTeamInfo($v1);
			$team['Members'] = $this->team_model->getTeamUserInfo($team['Members']);
			$data['team'] = $team;
		}

		$this->view($this->_page_pre.'/team/'.$page,$data);
	}

	public function shop($page='',$v1=''){
		$this->checkQuanxian('shop');

		if($page=='shop_info'){
			$this->checkPurview('shopInfo');
			$this->load->model('shop_model');
			$shop = $this->shop_model->getShop($v1);
			$data['shop'] = $shop;
		}

		if($page=='shopshenhe_info'){
			$this->checkPurview('shopInfo');
			$this->load->model('shop_shenhe_model');
			$shop = $this->shop_shenhe_model->getShop($v1);
			$data['shop'] = $shop;
		}

		$this->view($this->_page_pre.'/shop/'.$page,$data);
	}

	public function tixian($page=''){
		//$this->checkQuanxian('user');

		if($page=='tixian_add'){
			//$this->checkPurview('shopSelfUpd');
			$admin = $this->admin_model->getAdmin($this->_UserId);
			$data['shop'] = $admin['Admin'];
		}

		$this->view($this->_page_pre.'/tixian/'.$page,$data);
	}

}