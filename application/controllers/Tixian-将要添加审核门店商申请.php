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

        /*{"id":"5837ac11a090420e4c7813cf","Amount":"1","Account":"zhifubaoaccount0019","AccountType":"\u652f\u4ed8\u5b9d","AmountReal":"1","OrderId":""}
                $this->logResultmy('---this->input->post()---');
                $this->logResultmy(json_encode($this->input->post()));

                5837ac11a090420e4c7813cf
                $this->logResultmy('---id---');
                $this->logResultmy(json_encode($id));

                $this->logResultmy('---arr---');
                $this->logResultmy(json_encode($arr));*/

        /*$this->logResultmy('---here---');
        $this->logResultmy(json_encode($arr));*/

//处理向shop,admin中添加实际到账金额
        //取得admin表中的id
        $yueTX = $this->yue_tixian_model->getTxRow($id);
        /*{"id":"5837ac11a090420e4c7813cf","UserId":"58354d73a0904243c86938f7","Type":"\u7533\u8bf7\u63d0\u73b0","Account":"zhifubaoaccount0019","AccountType":"\u652f\u4ed8\u5b9d","Amount":1,"AmountReal":1,"OrderId":"","CreatTime":1480043537,"UpdateTime":1480044880,"UserType":"\u5e97\u94fa","UserMobile":"18039208926","UserRealName":"test999","UserShowName":"shopname9999","AccountName":""}
        $this->logResultmy('---yueTX---');
        $this->logResultmy(json_encode($yueTX));*/

        //admin表中的UserId
        $UserId = $yueTX['UserId'];
        $admin = $this->admin_model->getAdmin($UserId);

        //检测是否在admin表中
        if (!$admin['Admin']) {
            return;
        }
        $admin = $admin['Admin'];

        //取得ShopRealId
        $ShopRealId = $admin['ShopRealId'];
        //取得UserType
        $UserType = $admin['UserType'];

        /*//58354d73a0904243c86938f7
        $this->logResultmy('---id---');
        $this->logResultmy(json_encode($id));

        $this->logResultmy('---admin---');
        $this->logResultmy(json_encode($admin));

        //58354d73a0904243c86938f6
        $this->logResultmy('---ShopRealId---');
        $this->logResultmy(json_encode($ShopRealId));

        //3
        $this->logResultmy('---UserType---');
        $this->logResultmy(json_encode($UserType));*/
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
        /*        $this->logResultmy('---here-Admin---');
                $this->logResultmy(json_encode($admin));
                //null
                $this->logResultmy(json_encode($admin['Admin']));
                //1
                $this->logResultmy(json_encode($Amount));
                //0.04
                $this->logResultmy(json_encode($Balance));*/

        $this->logResultmy('---Amount>Balance-begin---');
        $this->logResultmy(json_encode($Amount));
        $this->logResultmy(json_encode($Balance));

        /*//如果提现金额大于可提现余额，则不成功$shop['BalanceReal'] - $shop['totalBalanceApply']
        if ($Amount > $Balance) {
            return;
        }*/

        //{"AmountReal":"1","OrderId":"","UpdateTime":1480054335}

        $this->logResultmy('---Amount>Balance-after---');
        $this->logResultmy(json_encode($Amount));
        $this->logResultmy(json_encode($Balance));

        //为明细表准备基础通用数据
        $arr = array(
            'UserId' => $UserId, //用户编号
            'Type' => '申请提现', //提现状态
            'Account' => $ZhifubaoAccount, //提现账号
            'AccountType' => '支付宝', //账号类型
            'Amount' => $Amount,//提现金额
            'AmountReal' => 0, //实际转账金额
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
        }elseif ($UserType == 4) {
            $arr['UserType'] = '门店商';
//            $arr['UserTypeNum'] = 4;
            $arr['UserMobile'] = $admin['Mobile'];
            $arr['UserRealName'] = $admin['RealName'];
            $arr['UserShowName'] = $admin['Username'];
            $arr['AccountName'] = $admin['ZhifubaoKaihu'];
        }

        //{"UserId":"58354d73a0904243c86938f7","Type":"\u7533\u8bf7\u63d0\u73b0","Account":"zhifubaoaccount0019","AccountType":"\u652f\u4ed8\u5b9d","Amount":1,"AmountReal":0,"OrderId":"","CreatTime":1480042715,"UpdateTime":"","UserType":"\u5e97\u94fa","UserMobile":"18039208926","UserRealName":"test999","UserShowName":"shopname9999","AccountName":null}
//        $this->logResultmy('---arr---');
//        $this->logResultmy(json_encode($arr));
////{"Tixian":{"id":"5837a8dba090420e4c7813c7","UserId":"58354d73a0904243c86938f7","Type":"\u7533\u8bf7\u63d0\u73b0","Account":"zhifubaoaccount0019","AccountType":"\u652f\u4ed8\u5b9d","Amount":1,"AmountReal":0,"OrderId":"","CreatTime":1480042715,"UpdateTime":"","UserType":"\u5e97\u94fa","UserMobile":"18039208926","UserRealName":"test999","UserShowName":"shopname9999","AccountName":""}}
//        $this->logResultmy('---this->yue_tixian_model->addTixian---');
//        $this->logResultmy(json_encode($res));

        /**
         * {
         * "Tixian": {
         * "id": "5836b0b1a09042d0ccbd1de2",
         * "UserId": "58354d73a0904243c86938f7",
         * "Type": "申请提现",
         * "Account": "zhifubaoaccount0019",
         * "AccountType": "支付宝",
         * "Amount": 1,
         * "AmountReal": 0,
         * "OrderId": "",
         * "CreatTime": 1479979185,
         * "UpdateTime": "",
         * "totalBalanceReal": 1,
         * "UserType": "店铺",
         * "UserMobile": "18039208926",
         * "UserRealName": "test999",
         * "UserShowName": "shopname9999",
         * "AccountName": ""
         * }
         * }
         */
        if ($UserType == 3) {
            /*$arr = array(
                'Balance-' => $Amount,
                'BalanceReal-' =>  $Amount
            );
            $this->shop_model->updShop($arr,$this->_ShopRealId);*/

            //更新商铺的shop表
            $arr = array('totalBalanceReal+' => $Amount * (1 - $this->config->item('shop_charge_now')));
            $this->logResultmy('---shop-out-begin---');
            $this->logResultmy(json_encode($arr));
            $res = $this->shop_model->updShop($arr, $ShopRealId);

            $this->logResultmy('---shop-update---');
            $this->logResultmy(json_encode($arr));
            $this->logResultmy(json_encode($res));

//                $this->load->model('shop_balance_model');
//                $this->shop_balance_model->Tixianmy($shop, $Amount);
//                $this->shop_balance_model->Tixianmy($shop, $Amount, 1);
        } elseif ($UserType == 2) {

            //更新合伙人的表
            $arr = array('totalBalanceReal+' => $Amount);
            $res = $this->admin_model->updUser($arr, $Id);
        }

        $this->logResultmy('---begin-res---');
        $this->logResultmy(json_encode($res));

        $arrOld = array(
            'AmountReal' => $AmountReal,
            'OrderId' => $OrderId,
            'AmountCharge' => round($AmountReal * $this->config->item('shop_charge_now'), 2),   //手续费
            'UpdateTime' => time()
        );

        //更新提现明细表状态
        $res = $this->yue_tixian_model->updTixian($arrOld, $id);

        $this->returnJson($res);

        $this->logResultmy('---end-res---');
        $this->logResultmy(json_encode($res));
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
        $this->logResultmy('---admin_UserType---');

        //{"Amount":"1"}
        extract($this->input->post());

        $this->logResultmy('---Amount---');
        $this->logResultmy(json_encode($Amount));

        $this->logResultmy('---this->_UserId---');
        $this->logResultmy(json_encode($this->_UserId));

        if (!is_numeric($Amount)) {
            return;
        }
        if (!$this->_UserId) {
            return;
        }
        $Amount = intval($Amount);
        /**
         * $admin{
         * "id": "58354d73a0904243c86938f7",
         * "Account": "18039208926",
         * "Address": "dzzhifubaoaccount00199",
         * "CityCode": 110100,
         * "CityName": "北京市",
         * "IsDisable": 0,
         * "Mobile": "18039208926",
         * "Password": "698d51a19d8a121ce581499d7b701668",
         * "RealName": "test999",
         * "ShopId": 310,
         * "ShopName": "shopname9999",
         * "ShopRealId": "58354d73a0904243c86938f6",
         * "UserType": 3,
         * "Username": "shopname9999",
         * "ZhifubaoAccount": "",
         * "CreatDate": 1479888243,
         * "IsDisableShow": "启用"
         * }
         */
        $admin = $this->admin_model->getAdmin($this->_UserId);

        $this->logResultmy('---admin---');
        $this->logResultmy(json_encode($admin));

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

        $this->logResultmy('---Amount---');
        $this->logResultmy(json_encode($Amount));

        $this->logResultmy('---Balance---');
        $this->logResultmy(json_encode($Balance));

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

        //{"UserId":"58354d73a0904243c86938f7","Type":"\u7533\u8bf7\u63d0\u73b0","Account":"zhifubaoaccount0019","AccountType":"\u652f\u4ed8\u5b9d","Amount":1,"AmountReal":0,"OrderId":"","CreatTime":1480042715,"UpdateTime":"","UserType":"\u5e97\u94fa","UserMobile":"18039208926","UserRealName":"test999","UserShowName":"shopname9999","AccountName":null}
        $this->logResultmy('---arr---');
        $this->logResultmy(json_encode($arr));
////{"Tixian":{"id":"5837a8dba090420e4c7813c7","UserId":"58354d73a0904243c86938f7","Type":"\u7533\u8bf7\u63d0\u73b0","Account":"zhifubaoaccount0019","AccountType":"\u652f\u4ed8\u5b9d","Amount":1,"AmountReal":0,"OrderId":"","CreatTime":1480042715,"UpdateTime":"","UserType":"\u5e97\u94fa","UserMobile":"18039208926","UserRealName":"test999","UserShowName":"shopname9999","AccountName":""}}
//        $this->logResultmy('---this->yue_tixian_model->addTixian---');
//        $this->logResultmy(json_encode($res));

        $this->logResultmy('---shop-out---');
        $this->logResultmy(json_encode($this->session->userdata('UserType')));
        $this->logResultmy(json_encode($res));

        //提交成功
        if (!$res['ErrorCode']) {
            /**
             * {
             * "Tixian": {
             * "id": "5836b0b1a09042d0ccbd1de2",
             * "UserId": "58354d73a0904243c86938f7",
             * "Type": "申请提现",
             * "Account": "zhifubaoaccount0019",
             * "AccountType": "支付宝",
             * "Amount": 1,
             * "AmountReal": 0,
             * "OrderId": "",
             * "CreatTime": 1479979185,
             * "UpdateTime": "",
             * "totalBalanceReal": 1,
             * "UserType": "店铺",
             * "UserMobile": "18039208926",
             * "UserRealName": "test999",
             * "UserShowName": "shopname9999",
             * "AccountName": ""
             * }
             * }
             */
            if ($this->session->userdata('UserType') == 3) {
                /*$arr = array(
                    'Balance-' => $Amount,
                    'BalanceReal-' =>  $Amount
                );
                $this->shop_model->updShop($arr,$this->_ShopRealId);*/

                //更新商铺的shop表
                $arr = array('totalBalanceApply+' => $Amount);
                $this->logResultmy('---shop-out-begin---');
                $this->logResultmy(json_encode($arr));
                $shop = $this->shop_model->updShop($arr, $this->_ShopRealId);

                $this->logResultmy('---shop-in---');
                $this->logResultmy(json_encode($arr));
                $this->logResultmy(json_encode($shop));

//                $this->load->model('shop_balance_model');
//                $this->shop_balance_model->Tixianmy($shop, $Amount);
//                $this->shop_balance_model->Tixianmy($shop, $Amount, 1);
            } elseif ($this->session->userdata('UserType') == 2) {

                //更新合伙人的表
                $arr = array('totalBalanceApply+' => $Amount);
                $this->admin_model->updUser($arr, $this->_UserId);
            } elseif ($this->session->userdata('UserType') == 4) {
                /*$arr = array(
                    'Balance-' => $Amount,
                    'BalanceReal-' =>  $Amount
                );
                $this->shop_model->updShop($arr,$this->_ShopRealId);*/

                //更新商铺的shop表
                $arr = array('totalBalanceApply+' => $Amount);
                $this->logResultmy('---shop-admin_model-begin---');
                $this->logResultmy(json_encode($arr));
                $shop = $this->admin_model->updUser($arr, $this->_UserId);

                $this->logResultmy('---admin_model-in---');
                $this->logResultmy(json_encode($arr));
                $this->logResultmy(json_encode($shop));

//                $this->load->model('shop_balance_model');
//                $this->shop_balance_model->Tixianmy($shop, $Amount);
//                $this->shop_balance_model->Tixianmy($shop, $Amount, 1);
            }
        }
    }

}