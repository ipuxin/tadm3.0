<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tixian extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        //$this->checkQuanxian('order');
        $this->load->model('yue_tixian_model');
    }

    //提现列表
    public function getList()
    {
        //$this->checkPurview('orderList');
        extract($this->input->post());

        //if($CreateTimeStart)$arr['CreateTime>'] = strtotime($CreateTimeStart);
        //if($CreateTimeEnd)$arr['CreateTime<'] = strtotime($CreateTimeEnd)+59;
        if ($CityName) $arr['CityName'] = "%*" . $CityName . "*%";
        if ($ShopName) $arr['ShopName'] = "%*" . $ShopName . "*%";
        if ($UserType) $arr['UserType'] = $UserType;

        $orderList = $this->yue_tixian_model->getTxList($arr, array('CreatTime', 'DESC'), array($perpage, ($page - 1) * $perpage));
        $res['TXList'] = $orderList['List'];
        $res['Count'] = $orderList['Count'];
        $res['PerPage'] = $orderList['Limit'];
        $this->returnJson($res);
    }

    //审核提现

    public function updTixian()
    {
        extract($this->input->post());

        $this->checkPurview('tixianUpd');
        $this->load->model('yue_tixian_model');

        //根据订单编号，找到UserId
        $yueTX = $this->yue_tixian_model->getTxRow($id);
        $UserId = $yueTX['UserId'];

        //通过UserId获取当前Admin表中的一条数据
        $admin = $this->admin_model->getAdmin($UserId);

        //检测是否在admin表中
        if (!$admin['Admin']) {
            return;
        }
        $admin = $admin['Admin'];

        //取得Shop表中的ShopRealId
        $ShopRealId = $admin['ShopRealId'];
        //取得UserType
        $UserType = $admin['UserType'];

        if ($UserType == 3) {
            $this->load->model('shop_model');
            $shop = $this->shop_model->getShop($ShopRealId);
            if (!$shop['ZhifubaoAccount']) {
                return;
            }
            $Balance = $shop['BalanceReal'] - $shop['totalBalanceApply'];
            $ZhifubaoAccount = $shop['ZhifubaoAccount'];

        } elseif ($UserType == 2 || $UserType == 4) {
            if (!$admin['ZhifubaoAccount']) {
                return;
            }
            $Balance = $admin['BalanceReal'] - $admin['totalBalanceApply'];
            $ZhifubaoAccount = $admin['ZhifubaoAccount'];
        }

        //为明细表准备基础通用数据
        $arr = array(
            'UserId' => $UserId, //用户编号
            'Type' => '申请提现', //提现状态
            'Account' => $ZhifubaoAccount, //提现账号
            'AccountType' => '支付宝', //账号类型
            'Amount' => $Amount,//提现金额
            'AmountReal' => $AmountReal, //实际转账金额
            'OrderId' => '', //转帐订单号
            'CreatTime' => time(), //申请时间
            'UpdateTime' => '', //交易时间
        );

        //为明细表准备精细数据
        if ($UserType == 3) {
            $arr['UserType'] = '店铺';
//            $arr['UserTypeNum'] = 3;
            $arr['UserMobile'] = $admin['Mobile'];
            $arr['UserRealName'] = $admin['RealName'];
            $arr['UserShowName'] = $admin['ShopName'];
            $arr['AccountName'] = $shop['ZhifubaoKaihu'];
        } elseif ($UserType == 2) {
            $arr['UserType'] = '城市合伙人';
//            $arr['UserTypeNum'] = 2;
            $arr['UserMobile'] = $admin['Mobile'];
            $arr['UserRealName'] = $admin['RealName'];
            $arr['UserShowName'] = $admin['Username'];
            $arr['AccountName'] = $admin['ZhifubaoKaihu'];
        } elseif ($UserType == 4) {
            $arr['UserType'] = '门店商';
//            $arr['UserTypeNum'] = 4;
            $arr['UserMobile'] = $admin['Mobile'];
            $arr['UserRealName'] = $admin['RealName'];
            $arr['UserShowName'] = $admin['Username'];
            $arr['AccountName'] = $admin['ZhifubaoKaihu'];
        }

        //列表显示的手续费
        $AmountCharge = 0;

        if ($UserType == 3) {
            //店铺提现收取一定比例的手续费
            $AmountCharge = round($Amount * $this->config->item('shop_charge_now'), 2);
            //更新商铺的shop表
            $arr = array('totalBalanceReal+' => $AmountReal);
            $res = $this->shop_model->updShop($arr, $ShopRealId);

        } elseif ($UserType == 2) {
            //更新合伙人的表
            $arr = array('totalBalanceReal+' => $AmountReal);
            $res = $this->admin_model->updUser($arr, $UserId);
        } elseif ($UserType == 4) {
            //更新门店商
            $arr = array('totalBalanceReal+' => $AmountReal);
            $res = $this->admin_model->updUser($arr, $UserId);
        }

        $arrOld = array(
            'AmountReal' => $AmountReal,
            'OrderId' => $OrderId,
            'AmountCharge' => $AmountCharge,   //手续费
            'UpdateTime' => time()
        );

        if ($res['ErrorCode']) {
            return;
        }
        //更新提现明细表状态
        $res = $this->yue_tixian_model->updTixian($arrOld, $id);

        $this->returnJson($res);
    }

//打印日志函数--ci
    function logResultmy($word = '')
    {
        $dir = $this->config->item('data_log_path') . 'TixianMy';
        if (!file_exists($dir)) {
            mkdir($dir, '0777', true);
        }
        $fileName = $dir . '/' . 'Tixian-updTixian' . '.txt';
        $fp = fopen($fileName, "a");
        flock($fp, LOCK_EX);
        fwrite($fp, "执行日期：" . strftime("%Y-%m-%d %H:%M:%S", time()) . "\r\n" . $word . "\r\n");
        flock($fp, LOCK_UN);
        fclose($fp);
    }

    //接受用户提交的提现申请-表单
    public function addTixian()
    {
        extract($this->input->post());

        if (!is_numeric($Amount)) {
            return;
        }
        if (!$this->_UserId) {
            return;
        }
        $Amount = round($Amount, 4);

        $admin = $this->admin_model->getAdmin($this->_UserId);

        //检测是否在admin表中
        if (!$admin['Admin']) {
            return;
        }
        $admin = $admin['Admin'];

        if ($this->session->userdata('UserType') == 3) {
            $this->load->model('shop_model');
            $shop = $this->shop_model->getShop($this->_ShopRealId);
            if (!$shop['ZhifubaoAccount']) {
                return;
            }
            $Balance = $shop['BalanceReal'] - $shop['totalBalanceApply'];
            $ZhifubaoAccount = $shop['ZhifubaoAccount'];

        } elseif ($this->session->userdata('UserType') == 2) {
            if (!$admin['ZhifubaoAccount']) {
                return;
            }
            $Balance = $admin['BalanceReal'] - $admin['totalBalanceApply'];
            $ZhifubaoAccount = $admin['ZhifubaoAccount'];
        } elseif ($this->session->userdata('UserType') == 4) {
            if (!$admin['ZhifubaoAccount']) {
                return;
            }
            $Balance = $admin['BalanceReal'] - $admin['totalBalanceApply'];
            $ZhifubaoAccount = $admin['ZhifubaoAccount'];
        }

        if ($Amount > $Balance) {
            return;
        }

        $arr = array(
            'UserId' => $this->_UserId, //用户编号
            'Type' => '申请提现', //提现状态
            'Account' => $ZhifubaoAccount, //提现账号
            'AccountType' => '支付宝', //账号类型
            'Amount' => $Amount,//提现金额
            'AmountReal' => 0, //实际转账金额
            'OrderId' => '', //转帐订单号
            'CreatTime' => time(), //申请时间
            'UpdateTime' => '', //交易时间
        );

        if ($this->session->userdata('UserType') == 3) {
            $arr['UserType'] = '店铺';
            $arr['UserMobile'] = $admin['Mobile'];
            $arr['UserRealName'] = $admin['RealName'];
            $arr['UserShowName'] = $admin['ShopName'];
            $arr['AccountName'] = $shop['ZhifubaoKaihu'];
        } elseif ($this->session->userdata('UserType') == 2) {
            $arr['UserType'] = '城市合伙人';
            $arr['UserMobile'] = $admin['Mobile'];
            $arr['UserRealName'] = $admin['RealName'];
            $arr['UserShowName'] = $admin['Username'];
            $arr['AccountName'] = $admin['ZhifubaoKaihu'];
        } elseif ($this->session->userdata('UserType') == 4) {
            $arr['UserType'] = '门店商';
            $arr['UserMobile'] = $admin['Mobile'];
            $arr['UserRealName'] = $admin['RealName'];
            $arr['UserShowName'] = $admin['Username'];
            $arr['AccountName'] = $admin['ZhifubaoKaihu'];
        }

        //提交提现申请
        $res = $this->yue_tixian_model->addTixian($arr);
        $this->returnJson($res);

        //提交成功
        if (!$res['ErrorCode']) {
            if ($this->session->userdata('UserType') == 3) {

                //更新商铺的shop表
                $arr = array('totalBalanceApply+' => $Amount);
                $this->shop_model->updShop($arr, $this->_ShopRealId);

            } elseif ($this->session->userdata('UserType') == 2) {

                //更新城市合伙人的Admin表
                $arr = array('totalBalanceApply+' => $Amount);
                $this->admin_model->updUser($arr, $this->_UserId);
            } elseif ($this->session->userdata('UserType') == 4) {

                //更新门店商的Admin表
                $arr = array('totalBalanceApply+' => $Amount);
                $this->admin_model->updUser($arr, $this->_UserId);
            }
        }
    }

}