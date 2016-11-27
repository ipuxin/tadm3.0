<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*** 余额提现类 ***
 *
 * 创建 2016-09-20 刘深远
 *** ***/
class Yue_Tixian_model extends MY_Model
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
        $this->setTable('Yue_Tixian');

        $this->_model = array(
            'UserId' => 'num', //用户编号
            'UserType' => 'str', //用户状态 店铺 合伙人
            'UserRealName' => '', //用户真实姓名
            'UserShowName' => '', //用户展示名称 店铺名/账号名
            'UserMobile' => '', //用户账号
            'Type' => 'str', //提现状态
            'Account' => '', //提现账号
            'AccountType' => 'str', //账号类型
            'Amount' => 'num',//提现金额
            'AmountReal' => 'num', //实际转账金额
            'OrderId' => 'num', //转帐订单号
            'CreatTime' => 'time', //申请时间
            'UpdateTime' => 'time', //交易时间
        );
    }

    function getTxList($arr, $order = array('CreatTime', 'DESC'), $limit = array(), $sel = array())
    {
        if ($this->session->userdata('UserType') == 2 || $this->session->userdata('UserType') == 3) {
            $arr['UserId'] = $this->session->userdata('UserId');
        }
        $list = $this->getList($arr, $order, $limit, $sel);
        if ($list) $list = $this->resetTxList($list);
        $data['List'] = $list;
        $data['Count'] = $this->_return_Count;
        $data['Limit'] = $this->_return_Limit;
        $data['Skip'] = $this->_return_Skip;
        return $data;
    }

    function getTxRow($arr, $sel = array()){
        $yueTx = $this->getRow($arr, $sel);
        if ($yueTx) {
            return $yueTx;
        } else {
            return false;
        }
    }

    function resetTxList($list)
    {
        foreach ($list as $k => $v) {
            $rlist[] = $this->resetTx($v);
        }
        return $rlist;
    }

    function resetTx($arr)
    {
        if ($arr['CreatTime']) $arr['CreatTimeDate'] = date('Y-m-d H:i:s', $arr['CreatTime']);
        if ($arr['UpdateTime']) $arr['UpdateTimeDate'] = date('Y-m-d H:i:s', $arr['UpdateTime']);
        return $arr;
    }

    function addTixian($arr)
    {
        if ($Tixian = $this->add($arr)) {
            $Data['Tixian'] = $Tixian;
        } else {
            $Data['ErrorCode'] = 4;
        }
        return $Data;
    }

    //最终审核提现到账
    function updTixian($arr,$where= array()){

        $this->logResultmy('---Yue-updTixian-arr---');
        $this->logResultmy(json_encode($arr));

        $this->logResultmy('---Yue-where---');
        $this->logResultmy(json_encode($where));

        if($updnum = $this->update($where,$arr)){
            $Data['Num'] = $updnum;
        }else{
            $Data['ErrorCode'] = 3;
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
//打印日志函数--ci
    function logResultmy($word = '')
    {
        $dir = $this->config->item('data_log_path') . 'TixianMy';
        if (!file_exists($dir)) {
            mkdir($dir, '0777', true);
        }
        $fileName = $dir . '/' . 'Yue_Tixian_modelMy' . '.txt';
        $fp = fopen($fileName, "a");
        flock($fp, LOCK_EX);
        fwrite($fp, "执行日期：" . strftime("%Y-%m-%d%H:%M:%S", time()) . "\r\n" . $word . "\r\n");
        flock($fp, LOCK_UN);
        fclose($fp);
    }

}