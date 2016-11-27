<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*** 店铺-商家控制器 ***
 *
 * 创建 2016-08-13 刘深远
 *** ***/
class Admin3 extends MY_Controller
{

    public $_page_pre;

    public function __construct()
    {
        parent::__construct();
        $this->checkAdminType('dianpu');
        $this->_page_pre = 'admin3';
    }

    public function product($page = '', $v1 = '')
    {
        $this->checkQuanxian('product');
        if ($page == 'product_upd') {
            $this->checkPurview('productUpd');
            $this->load->model('product_model');
            $data['info'] = $this->product_model->getProduct($v1);
        }
        if ($page == 'product_add') {
            $this->load->model('shop_model');
            $shop = $this->shop_model->getShop($this->_ShopRealId);
            $data['Shop'] = $shop;
            $this->checkPurview('productAdd');
        }
        if ($page == 'product_info') {
            $this->checkPurview('productInfo');
            $this->load->model('product_model');
            $data['info'] = $this->product_model->getProduct($v1);
        }
        if ($page == 'product_sale_list') {
            $this->checkPurview('productSale');
        }
        //资金明细表：product/getShopBalanceList
        if ($page == 'shop_balance_list') {
            $this->checkPurview('shopBalance');
        }

        if ($page == 'product_info' || $page == 'product_upd' || $page == 'product_add') {
            $this->load->model('category_model');
            $catelist = $this->category_model->getCategoryList(array('CateLevel' => 1));
            $data['cate'] = $catelist['List'];
            $catelist = $this->category_model->getCategoryList(array('CateLevel' => 2));
            $data['cate2'] = $catelist['List'];
        }
        $this->view($this->_page_pre . '/product/' . $page, $data);
    }

    public function user($page = '')
    {
//        $this->session->all_userdata();
        /**
         * Array
         * (
         * [__ci_last_regenerate] => 1479889316
         * [UserId] => 58354d73a0904243c86938f7
         * [Account] => 18039208926
         * [Username] => shopname9999
         * [Password] => 698d51a19d8a121ce581499d7b701668
         * [UserType] => 3
         * [CityName] => 北京市
         * [CityCode] => 110100
         * [DistrictCode] =>
         * [DistrictName] =>
         * [ShopId] => 310
         * [ShopName] => shopname9999
         * [ShopRealId] => 58354d73a0904243c86938f6
         * )
         */
//        echo '<pre>';
//        print_r($this->session->all_userdata());
//        echo '</pre>';
//        exit('<hr>');

        $this->checkQuanxian('user');

        if ($page == 'user_self_upd') {
            $user = $this->admin_model->getAdmin($this->_UserId);
            $data['user'] = $user['Admin'];
        }

        $this->view($this->_page_pre . '/user/' . $page, $data);
    }

    public function order($page = '', $v1 = '')
    {
        $this->checkQuanxian('order');

        if ($page == 'order_list') {
            $data['OrderStatus'] = $v1;
        }
        if ($page == 'order_info') {
            $this->checkPurview('orderInfo');
            $this->load->model('order_model');
            $this->load->model('shop_model');
            $this->load->model('kuaidi_model');
            $order = $this->order_model->getOrderInfo($v1);
            $data['kuaidi'] = $this->kuaidi_model->getShopKuaidiList($this->_ShopRealId);
            $shop = $this->shop_model->getShop($order['ShopId']);
            $data['status'] = $this->order_model->getStatusArr();
            $data['order'] = $order;
            $data['shop'] = $shop;
        }
        if ($page == 'order_piliang') {
            $this->load->model('kuaidi_model');
            $data['kuaidi'] = $this->kuaidi_model->getShopKuaidiList($this->_ShopRealId);
        }

        $this->view($this->_page_pre . '/order/' . $page, $data);
    }

    public function team($page = '', $v1 = '')
    {
        $this->checkQuanxian('team');

        if ($page == 'team_list') {
            $data['TeamStatus'] = $v1;
        }
        if ($page == 'team_info') {
            $this->checkPurview('teamInfo');
            $this->load->model('team_model');
            $team = $this->team_model->getTeamInfo($v1);
            $team['Members'] = $this->team_model->getTeamUserInfo($team['Members']);
            $data['team'] = $team;
        }

        $this->view($this->_page_pre . '/team/' . $page, $data);
    }

    public function shop($page = '', $v1 = '')
    {
        $this->checkQuanxian('shop');

        if ($page == 'shop_self_upd') {
            $this->checkPurview('shopSelfUpd');
            $this->load->model('shop_model');
            $shop = $this->shop_model->getShop($this->_ShopRealId);
            $data['shop'] = $shop;
        }

        $this->view($this->_page_pre . '/shop/' . $page, $data);
    }

    public function kuaidi($page = '', $v1 = '')
    {
        $this->checkQuanxian('kuaidi');

        if ($page == 'setkuaidi') {
            $this->load->model('kuaidi_model');
            $list = $this->kuaidi_model->getKuaidiList();
            $shopList = $this->kuaidi_model->getShopKuaidiList($this->_ShopRealId);
            foreach ($shopList as $v) {
                $shopList[$v['Code']] = $v;
            }
            $data['list'] = $list;
            $data['shopKuaidi'] = $shopList;
        }

        $this->view($this->_page_pre . '/kuaidi/' . $page, $data);
    }

    public function tixian($page = '')
    {
        //$this->checkQuanxian('user');

        if ($page == 'tixian_add') {
            $this->checkPurview('shopSelfUpd');
            $this->load->model('shop_model');
            $shop = $this->shop_model->getShop($this->_ShopRealId);
            $data['shop'] = $shop;
        }

        /**
         * [id] => 58354d73a0904243c86938f6
         * [Balance] => 1.12
         * [CityCode] => 110100
         * [CityName] => 北京市
         * [CreatTime] => 1479888243
         * [DeliverAddress] => dzzhifubaoaccount00199
         * [ReturnAddress] => dzzhifubaoaccount00199
         * [ShopAddress] => dzzhifubaoaccount00199
         * [ShopDescription] =>
         * [IsDisable] => 0
         * [IsHide] => 0
         * [IsOpenAdminAccount] => 1
         * [ShopLogo] => http://twww.pingoing.cn/upload/image/20161123/1479887655111323.jpg
         * [ShopName] => shopname9999
         * [ShopOwnerMobile] => 18039208926
         * [ShopOwnerName] => test999
         * [ShopType] => 个人
         * [ZhifubaoAccount] => zhifubaoaccount0019
         * [InvitationCode] => yaoqingma0019
         * [ShopId] => 310
         * [Fans] => 0
         * [BalanceReal] => 1.04
         * [ShopTypeShow] =>
         * [CreatTimeDate] => 2016-11-23 16:04:03
         */

        $this->view($this->_page_pre . '/tixian/' . $page, $data);
    }

    public function coupon($page = '', $v1 = '')
    {
        $this->checkQuanxian('coupon');

        if ($page == 'coupon_upd') {
            $this->checkPurview('couponUpd');
            $this->load->model('coupon_model');
            $Coupon = $this->coupon_model->getCoupon($v1);
            $data['coupon'] = $Coupon;
        }
        if ($page == 'coupon_send') {
            $this->checkPurview('couponSend');
            $this->load->model('coupon_model');
            $arr['ShopId'] = $this->_ShopRealId;
            $couponList = $this->coupon_model->getCouponList($arr, array('CreatDate', 'DESC'));
            $data['List'] = $couponList['List'];
            $data['UserId'] = $v1;
        }

        $this->view($this->_page_pre . '/coupon/' . $page, $data);
    }

}