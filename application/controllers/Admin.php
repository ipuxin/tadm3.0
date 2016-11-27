<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*** 管理员主控制器 ***

创建 2016-08-13 刘深远 

*** ***/

class Admin extends MY_Controller {

	public $_page_pre;

	public function __construct(){
		parent::__construct();
		$this->checkAdminType('admin');
		$this->_page_pre = 'admin';
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
		if($page=='Adjust'){
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

	public function jiameng($page='',$v1=''){
		$this->checkQuanxian('jiameng');

		if($page=='jiameng_add'){
			$this->checkPurview('jiamengAdd');
			$this->load->model('admin_model');
			$data['usedCode'] = $this->admin_model->getAllCode();
		}

		if($page=='jiameng_upd'){
			$this->checkPurview('jiamengUpd');
			$this->load->model('admin_model');
			$admin = $this->admin_model->getAdmin($v1);
			$data['admin'] = $admin['Admin'];
			$data['usedCode'] = $this->admin_model->getAllCode();
		}

		$this->view($this->_page_pre.'/jiameng/'.$page,$data);
		
	}

	public function mendian($page='',$v1=''){

        $this->checkQuanxian('jiameng');

		if($page=='mendian_add'){
			$this->checkPurview('jiamengAdd');
			$this->load->model('admin_model');
			//$data['usedCode'] = $this->admin_model->getAllCode();
		}

		if($page=='mendian_upd'){
			$this->checkPurview('jiamengUpd');
			$this->load->model('admin_model');
			$admin = $this->admin_model->getAdmin($v1);
			$data['admin'] = $admin['Admin'];
			//$data['usedCode'] = $this->admin_model->getAllCode();
		}

		$this->view($this->_page_pre.'/jiameng/'.$page,$data);
		
	}

	public function setting($page='',$v1=''){
		$this->checkQuanxian('setting');

		if($page=='category'){
			$this->checkPurview('setcategory');
			$this->load->model('category_model');
			$data['list'] = $this->category_model->getCategoryAll();
		}

		if($page=='category_add'){
			$this->checkPurview('setcategory');
			$this->load->model('category_model');
			$list = $this->category_model->getCategoryList(array('CateLevel'=>1));
			$data['list'] = $list['List'];
		}

		if($page=='category_upd'){
			$this->checkPurview('setcategory');
			$this->load->model('category_model');
			$list = $this->category_model->getCategoryList(array('CateLevel'=>1));
			$cate = $this->category_model->getRow($v1);
			$data['list'] = $list['List'];
			$data['cate'] = $cate;
		}

		if($page=='setting_hotcitys'){
			$this->checkPurview('sethotcity');
		}

		if($page=='setting_banner'){
			$this->checkPurview('setbanner');
			$this->load->model('banner_model');
			$list = $this->banner_model->getBannerList();
			$data['list'] = $list['List'];
		}

		if($page=='banner_add'){
			$this->checkPurview('setbanner');
		}

		if($page=='banner_upd'){
			$this->checkPurview('setbanner');
			$this->load->model('banner_model');
			$data['Banner'] = $this->banner_model->getRow($v1);
		}

		$this->view($this->_page_pre.'/setting/'.$page,$data);
	}

	public function tixian($page='',$v1=''){
		//$this->checkQuanxian('user');

        //提现申请（？检测权限验证流程方法）
		if($page=='tixian_upd'){
			$this->checkPurview('tixianUpd');
			$this->load->model('yue_tixian_model');
			$info = $this->yue_tixian_model->getRow($v1);
			$data['info'] = $info;
		}

		$this->view($this->_page_pre.'/tixian/'.$page,$data);
	}

	public function coupon($page='',$v1=''){
		$this->checkQuanxian('coupon');

		if($page=='coupon_upd'){
			$this->checkPurview('couponUpd');
			$this->load->model('coupon_model');
			$Coupon = $this->coupon_model->getCoupon($v1);
			$data['coupon'] = $Coupon;
		}
		if($page=='coupon_send'){
			$this->checkPurview('couponSend');
			$this->load->model('coupon_model');
			$arr['IsAll'] = 1;
			$couponList = $this->coupon_model->getCouponList($arr,array('CreatDate','DESC'));
			$data['List'] = $couponList['List'];
			$data['UserId'] = $v1;
		}

		$this->view($this->_page_pre.'/coupon/'.$page,$data);
	}

	/*总管理员设置销量*/
	public function Adjust()
	{
		$this->checkPurview('adjust');
		
		extract($this->input->post());

		$arr = array(
			'id' => $id,
			'Adjust' => $Adjust,
			'Waiting' => $Waiting,
			'Real' => $Real,
		);

		$this->load->model('product_model');
		$res = $this->product_model->Adjust($arr,$id);
		
		if($res){
			redirect('/admin/product/product_upd/'.$id);
		}
	}

}