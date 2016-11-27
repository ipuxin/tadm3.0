<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*** 提现编辑页 ***
 *
 * 创建 2016-09-12 刘深远
 *** ***/
?>
<style>
    tit {
        margin-bottom: 20px;
    }

    tit button {
        margin-left: 12px;
    }

    itemList item.green {
        background-color: #009a61;
        color: #fff
    }

    #Image {
        width: 320px;
        height: 160px;
    }

    div.btn {
        font-size: 12px;
        border-radius: 0px;
        padding: 8px 16px;
        height: 32px;
        line-height: 14px;
        border: 1px solid #ddd;
        background-color: #f7f7f7;
        box-sizing: border-box;
    }

    div.btn:hover {
        background-color: #fff;
    }
</style>
<body class="bg-grey">
<warpbox>
    <tit><span>编辑</span> 提现</tit>

    <form class="tab" action="/tixian/updTixian">
        <input type="hidden" name="id" value="<?= $info['id'] ?>">
        <line>
            <span class="title">提现类型：</span>
            <span><?= $info['UserType'] ?></span>
            <input type="hidden" name="Amount" value="<?= $info['UserType'] ?>">
        </line>
        <line>
            <span class="title">申请时间：</span>
            <span><?= date('Y-m-d H:i:s', $info['CreatTime']) ?></span>
        </line>
        <line>
            <span class="title">提现账号：</span>
            <span><?= $info['Account'] ?> （<?= $info['AccountType'] ?>）</span>
            <input type="hidden" name="Account" value="<?= $info['Account'] ?>">
            <input type="hidden" name="AccountType" value="<?= $info['AccountType'] ?>">
        </line>
        <line>
            <span class="title">开户名称：</span>
            <span><?= $info['AccountName'] ?> （<?= $info['UserMobile'] ?>）</span>
        </line>
        <line>
            <span class="title">申请金额：</span>
            <input type="hidden" name="Amount" value="<?= $info['Amount'] ?>">
            <span><?= $info['Amount'] ?></span>
        </line>
        <line>
            <span class="title">已转账金额：</span>
            <input type="text" name="AmountReal" disabled style="color:#999;"
                   value="<?php
                   //除类型为：店铺，外，城市合伙人（加盟商）、门店商，提现均不收取手续费
                   if ($info['UserType'] == '店铺') {
                       echo $info['Amount'] * (1 - $this->config->item('shop_charge_now'));
                   }else{
                       echo $info['Amount'];
                   }
                   ?>">
            <?php
            if ($info['UserType'] == '店铺'){
                echo $info['UserType'].'<span style="color:#999;">（当前手续费' . ($this->config->item('shop_charge_now') * 100) . '%）</span>';
            }
            ?>
        </line>
        <line>
            <span class="title">转账单号：</span>
            <input type="text" name="OrderId" value="<?= $info['OrderId'] ?>">
        </line>
        <line>
            <span class="title"></span>
            <input type="reset">
            <button>保存</button>
        </line>
    </form>
</warpbox>
<script src="<?= $staticPath ?>js/init.js?v=<?= $version ?>"></script>
<script src="<?= $staticPath ?>js/ext/form.js?v=<?= $version ?>"></script>
<script>

    $('form.tab').FormAjax({
        success: function (data) {
            if (data.ErrorCode > 0) {
                //self.location= '/admin/setting/setting_banner';
            } else {
                self.location = '/admin/tixian/tixian_list';
            }
        }
    });

</script>
</body>