<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*** 店铺资金类 ***
 *
 * 创建 2016-08-28 刘深远
 *** ***/
class Shop_Balance_model extends MY_Model
{

    private $_model;

    public function __construct()
    {
        parent::__construct();
        $this->init();
    }

    function init()
    {
        parent::init();
        $this->setTable('Shop_Mingxi');

        $TypeArr = array(
            1 => '订单支付',
            2 => '拼团完成',
            3 => '提现',
            4 => '订单退款',
        );

        $ProductTypeArr = array(
            1 => '普通商品',
            2 => '拼团商品',
            3 => '免费试用',
            4 => '1元夺宝',
            5 => '幸运抽奖'
        );

        $IsRealArr = array(
            0 => '总余额',
            1 => '可提现余额'
        );

        $this->_model = array(
            'IsReal' => $IsRealArr,
            'ShopId' => 'num', //商铺编号
            'ShopRealId' => 'id', //商铺真实ID
            'CityCode' => 'num',
            'CityName' => 'str',
            'ProductId' => 'num',
            'ProductRealId' => 'id',
            'ProductType' => $ProductTypeArr,
            'OrderId' => 'num',
            'OrderRealId' => 'id',
            'TeamId' => 'num',
            'BalanceType' => $TypeArr,
            'Amount' => 'num',//变动金额
            'CreatTime' => 'time', //生成时间
        );
    }

    function getBalanceList($arr, $order = array('CreatTime', 'DESC'), $limit = array(), $sel = array())
    {
        $list = $this->getList($arr, $order, $limit, $sel);
        if ($list) $list = $this->resetBalanceList($list);
        $data['List'] = $list;
        $data['Count'] = $this->_return_Count;
        $data['Limit'] = $this->_return_Limit;
        $data['Skip'] = $this->_return_Skip;
        return $data;
    }

    function resetBalanceList($list)
    {
        foreach ($list as $k => $v) {
            $rlist[] = $this->resetBalance($v);
        }
        return $rlist;
    }

    function resetBalance($arr)
    {
        if ($arr['CreatTime']) $arr['CreatTimeDate'] = date('Y-m-d H:i:s', $arr['CreatTime']);
        if ($arr['ProductType']) $arr['ProductTypeDate'] = $this->_model['ProductType'][$arr['ProductType']];
        if ($arr['BalanceType']) $arr['BalanceTypeDate'] = $this->_model['BalanceType'][$arr['BalanceType']];
        if ($arr['IsReal'] == 1) {
            $arr['IsRealShow'] = '可提现余额';
        } elseif ($arr['IsReal'] == 0) {
            $arr['IsRealShow'] = '总余额';
        }
        return $arr;
    }

    function addOrderBalance($order, $Amount = 0, $isReal = 0)
    {
        $arr = array(
            'IsReal' => $isReal,
            'BalanceType' => 1,
            'ShopRealId' => $order['ShopId'],
            'CityCode' => $order['CityCode'],
            'CityName' => $order['CityName'],
            'ProductId' => $order['ProductId'],
            'ProductRealId' => $order['ProductRealId'],
            'ProductType' => $order['ProductInfo']['ProductType'],
            'OrderId' => $order['OrderId'],
            'OrderRealId' => $order['id'],
            'TeamId' => $order['TeamId'],
            'Amount' => $Amount
        );
        $this->addBalance($arr);
    }

    function OrderRefund($order, $Amount = 0, $isReal = 0)
    {
        $Amount = $Amount / 100 * -1;
        $arr = array(
            'IsReal' => $isReal,
            'BalanceType' => 4,
            'ShopRealId' => $order['ShopId'],
            'CityCode' => $order['CityCode'],
            'CityName' => $order['CityName'],
            'ProductId' => $order['ProductId'],
            'ProductRealId' => $order['ProductRealId'],
            'ProductType' => $order['ProductInfo']['ProductType'],
            'OrderId' => $order['OrderId'],
            'OrderRealId' => $order['id'],
            'TeamId' => $order['TeamId'],
            'Amount' => $Amount
        );
        $this->addBalance($arr);
    }

//不用了
    function Tixianmy($shop, $Amount = 0)
    {
        $this->logResultmy('---Tixianmy-shop---');
        $this->logResultmy(json_encode($shop));

        $Amount = $Amount * 1;
        $ShopId = $shop['id'];

        $this->load->model('shop_model');
        //查询条件
        $getShopArr = array(
            'id' => $ShopId,
        );

        //增加累计申请提现金额
        $arr = array(
            'totalBalanceApply' => $shop['totalBalanceApply'] + $Amount,
        );

//        $this->logResultmy('---shop-arr---');
//        $this->logResultmy(json_encode($arr));

        if ($shop = $this->shop_model->updShop($arr, $getShopArr)) {
            $Data['Balance'] = $shop;
            /**
             * {
             * "Balance": {
             * "id": "5836ba40a09042d0ccbd1df8",
             * "totalBalanceReal": 1,
             * "CreatTime": 1479981632
             * }
             * }
             */
//            $this->logResultmy('---shop-in---');
//            $this->logResultmy(json_encode($shop));
        } else {
            $Data['ErrorCode'] = 4;
        }

        return $Data;
    }

    function Tixian($shop, $Amount = 0, $isReal = 0)
    {
        $Amount = $Amount * -1;
        $arr = array(
            'IsReal' => $isReal,
            'BalanceType' => 3,
            'ShopRealId' => $shop['id'],
            'CityCode' => $shop['CityCode'],
            'CityName' => $shop['CityName'],
            //'ProductId' => $order['ProductId'],
            //'ProductRealId' => $order['ProductRealId'],
            //'ProductType' => $order['ProductInfo']['ProductType'],
            //'OrderId' => $order['OrderId'],
            //'OrderRealId' => $order['id'],
            //'TeamId' => $order['TeamId'],
            'Amount' => $Amount
        );
        $this->addBalancemy($arr);
    }

//打印日志函数--ci
    function logResultmy($word = '')
    {
        $dir = $this->config->item('data_log_path') . 'TixianMy';
        if (!file_exists($dir)) {
            mkdir($dir, '0777', true);
        }
        $fileName = $dir . '/' . 'TixianMy' . '.txt';
        $fp = fopen($fileName, "a");
        flock($fp, LOCK_EX);
        fwrite($fp, "执行日期：" . strftime("%Y-%m-%d%H:%M:%S", time()) . "\r\n" . $word . "\r\n");
        flock($fp, LOCK_UN);
        fclose($fp);
    }

    //申请提现
    function addBalancemy($ShopRealId, $arr)
    {
        $arr = $this->setModel($arr);
        if (is_numeric($arr)) {
            $Data['ErrorCode'] = $arr;
            if ($arr == 601) $Data['ErrorMessage'] = '参数缺失';
            return $Data;
        }
        $this->load->model('shop_model');
        $where = array('ShopRealId' => $ShopRealId);
        if ($shop = $this->shop_model->updShop($arr, $where)) {
            $Data['Balance'] = $shop;
            /**
             * {
             * "Balance": {
             * "id": "5836ba40a09042d0ccbd1df8",
             * "totalBalanceReal": 1,
             * "CreatTime": 1479981632
             * }
             * }
             */
            $this->logResultmy('---Data---');
            $this->logResultmy(json_encode($Data));
        } else {
            $Data['ErrorCode'] = 4;
        }
        return $Data;
    }

    function addBalance($arr)
    {
        $arr = $this->setModel($arr);
        if (is_numeric($arr)) {
            $Data['ErrorCode'] = $arr;
            if ($arr == 601) $Data['ErrorMessage'] = '参数缺失';
            return $Data;
        }
        if ($shop = $this->add($arr)) {
            $Data['Balance'] = $shop;
        } else {
            $Data['ErrorCode'] = 4;
        }
        return $Data;
    }

    function setModel($arr, $type = 'add')
    {
        if ($type == 'add') {
            $arr['CreatTime'] = time();
        }
        return $arr;
    }

}