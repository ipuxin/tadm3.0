<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*** 店铺类 ***
 *
 * 创建 2016-08-01 刘深远
 *** ***/
class Shop_model extends MY_Model
{

    private $_model;
    private $_admin_prefix;
    private $_admin_password;

    public function __construct()
    {
        parent::__construct();
        $this->init();
    }

    function init()
    {

        parent::init();
        $this->_admin_prefix = $this->config->item('shop_admin_prefix');
        $this->_admin_password = $this->config->item('shop_admin_password');
        $this->setTable('Shop_Balance');

        $IsDisableArr = array(
            0 => '启用',
            1 => '禁用'
        );

        $TypeArr = array(
            1 => '普通店铺'
        );

        $this->_model = array(
            'ShopId' => 'num', //商铺编号
            'UserId' => '', //拥有者id
            'ShopType' => $TypeArr,
            'ShopName' => 'str',
            'ShopLogo' => 'url',
            'ShopImages' => array(),
            'ShopAddress' => 'str',
            'ShopOwnerName' => 'str',
            'ShopOwnerMobile' => 'str',
            'ShopDescription' => 'str',
            'BondMoney' => 'str', //保证金
            'DeliverAddress' => 'str', //发货地址
            'ReturnAddress' => 'str', //退货地址

            'totalBalanceReal'=>'num',//累计已提现余额

            'ShopTemplate' => 'str', //模板名称

            'CateArea' => '', //经营范围

            'CityCode' => '', //城市Code
            'CityName' => '', //城市Name

            'Fans' => 'num', //粉丝数量

            'CreatTime' => 'time', //创建时间
            'OpenTime' => 'time', //开通时间

            'IsDisable' => $IsDisableArr,
        );
    }

    function getShopList($arr, $order = array(), $limit = array(), $sel = array())
    {
        if (!$order) $order = array('CreatTime', 'DESC');

        if ($this->session->userdata('UserType') == 2) {
            $CityCode = $this->session->userdata('CityCode');
            $CityCodeNum = intval($CityCode);
            $arr['CityCode'] = $CityCodeNum;
        }

        if ($this->session->userdata('UserType') == 4) {
            $shopIds = $this->InvitationCodeShopIdsNum();
            $arr['ShopId'] = $shopIds;
        }

        $list = $this->getList($arr, $order, $limit, $sel);
        if ($list) $list = $this->resetShopList($list);
        $data['List'] = $list;
        $data['Count'] = $this->_return_Count;
        $data['Limit'] = $this->_return_Limit;
        $data['Skip'] = $this->_return_Skip;
        return $data;
    }

    function resetShopList($list)
    {
        foreach ($list as $k => $v) {
            $list[$k] = $this->resetShop($v);
        }
        return $list;
    }

    function resetShop($arr)
    {
        if ($arr['ShopType']) $arr['ShopTypeShow'] = $this->_model['ShopType'][$arr['ShopType']];
        if ($arr['CreatTime']) $arr['CreatTimeDate'] = date('Y-m-d H:i:s', $arr['CreatTime']);
        if ($arr['OpenTime']) $arr['OpenTimeDate'] = date('Y-m-d H:i:s', $arr['OpenTime']);
        return $arr;
    }

    function getShop($arr, $sel = array())
    {
        $shop = $this->getRow($arr, $sel);
        if ($shop) {
            $shop = $this->resetShop($shop);
            return $shop;
        } else {
            return false;
        }
    }

    function checkUserHasShop($userId)
    {
        $has = $this->getShop(array('UserId' => $userId), array('ShopId', 'id'));
        return $has;
    }

    function creatShopAdmin($shopId = '')
    {
        $shop = $this->getShop($shopId);
        $this->load->model('admin_model');

        $arr = array(
            'Account' => $this->_admin_prefix . $shop['ShopId'],
            'Password' => $this->_admin_password,
            'Address' => $shop['ShopAddress'],
            'CityCode' => $shop['CityCode'],
            'CityName' => $shop['CityName'],
            'IsDisable' => 0,
            'Mobile' => $shop['ShopOwnerMobile'],
            'RealName' => $shop['ShopOwnerName'],
            'ShopId' => $shop['ShopId'],
            'ShopName' => $shop['ShopName'],
            'ShopRealId' => $shop['id'],
            'UserType' => 3,
            'Username' => $shop['ShopName'],
            'totalBalanceReal'=>0,//累计实际到账
            'totalBalanceApply'=>0,//累计申请提现
        );

        $res = $this->admin_model->addUser($arr);
        if ($res['User']) {
            return $res['User'];
        } else {
            return false;
        }
    }

    function addShop($arr)
    {
        $arr = $this->setModel($arr);
        if (is_numeric($arr)) {
            $Data['ErrorCode'] = $arr;
            if ($arr == 201) $Data['ErrorMessage'] = '店铺ID创建失败';
            if ($arr == 202) $Data['ErrorMessage'] = '所属城市参数缺失';
            if ($arr == 203) $Data['ErrorMessage'] = '用户ID参数缺失';
            if ($arr == 205) $Data['ErrorMessage'] = '用户已经开通过店铺';
            return $Data;
        }
        if ($shop = $this->add($arr)) {
            $Data['Shop'] = $shop;
        } else {
            $Data['ErrorCode'] = 4;
        }
        return $Data;
    }

    function updShop($arr, $where = array())
    {
        $arr = $this->setModel($arr, 'upd');
        if (is_numeric($arr)) {
            $Data['ErrorCode'] = $arr;
            if ($arr == 204) $Data['ErrorMessage'] = '用户ID参数不可修改';
            return $Data;
        }
        if ($updnum = $this->update($where, $arr)) {
            $Data['Num'] = $updnum;
        } else {
            $Data['ErrorCode'] = 3;
        }
        return $Data;
    }

    function InvitationCodeShopIds()
    {
        $InvitationCode = $this->_InvitationCode;
        $List = $this->getList(array('InvitationCode' => $InvitationCode));
        if (count($List)) {
            foreach ($List as $v) {
                $shopIds[] = $v['id'];
            }
            return $shopIds;
        } else {
            return false;
        }
    }

    function InvitationCodeShopIdsNum()
    {
        $InvitationCode = $this->_InvitationCode;
        $List = $this->getList(array('InvitationCode' => $InvitationCode));
        if (count($List)) {
            foreach ($List as $v) {
                $shopIds[] = $v['ShopId'];
            }
            return $shopIds;
        } else {
            return false;
        }
    }

    function setModel($arr, $type = 'add')
    {
        if ($type == 'add') {
            //if(!$arr['UserId']){return 203;}
            //if($this->checkUserHasShop($arr['UserId'])){return 205;}
            if (!$arr['ShopId']) {
                $arr['ShopId'] = $this->getMax('ShopId');
            }
            if ($arr['ShopId'] !== false) {
                $arr['ShopId'] = $arr['ShopId'] + rand(10, 49);
            } else {
                return 201;
            }
            if (!$arr['CreatTime']) $arr['CreatTime'] = time();
            if (!$arr['ShopType']) $arr['ShopType'] = 1;
            if (!$arr['IsDisable']) $arr['IsDisable'] = 0;
            if (!$arr['CityCode'] || !$arr['CityName']) {
                return 202;
            }

            if (!$arr['Fans']) $arr['Fans'] = 0;
        }

        if ($type == 'upd') {
            //if($arr['UserId']){return 204;}
        }
        return $arr;
    }

}