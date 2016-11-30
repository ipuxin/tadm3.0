<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*** 申请提现页 ***
 *
 * 创建 2016-08-28 刘深远
 *** ***/
?>
<style>
    div.btn {
        font-size: 12px;
        border-radius: 0px;
        padding: 8px 16px;
        height: 32px;
        line-height: 14px;
        border: 1px solid #ddd;
        background-color: #f7f7f7;
        box-sizing: border-box;
        cursor: pointer;
        color: #666;
        float: left;
        margin-top: 14px;
    }

    div.btn:hover {
        background-color: #fff;
    }

    span {
        position: relative;
    }

    span img {
        height: 60px;
        display: inline-block;
    }

    span a {
        display: inline-block;
        float: left;
        margin-right: 10px;
    }

    span i {
        display: block;
        height: 24px;
        width: 24px;
        position: absolute;
        line-height: 24px;
        border-radius: 50%;
        border: 1px solid #888;
        background-color: rgba(0, 0, 0, 0.5);
        color: #fff;
        font-size: 18px;
        text-align: center;
        right: -2px;
        top: -12px;
        cursor: pointer;
    }

    line textarea {
        float: left;
    }

    tabarea {
        display: block;
        margin-bottom: 12px;
    }

    areaList line > span.title {
        width: 140px;
    }

    areaList btns {
        display: block;
        padding-left: 148px;
    }

    areaList div.btn {
        display: inline-block;
        float: left;
        height: 30px;
        line-height: 30px;
        border: 1px solid #ccc;
        background-color: #f8f8f8;
        padding: 0 6px;
        margin-right: 4px;
        margin-bottom: 4px;
        margin-top: 0;
    }

    areaList div.btn.sel {
        border-color: #09c;
        background-color: #0099cc;
        color: #fff;
    }

    .tixianNum {
        width: 200px;
        color: #555555;
        vertical-align: middle;
        background-color: #ffffff;
        background-image: none;
        border: 1px solid #cccccc;
        -webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
        box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
        -webkit-transition: border-color ease-in-out 0.15s, box-shadow ease-in-out 0.15s;
        transition: border-color ease-in-out 0.15s, box-shadow ease-in-out 0.15s;
        padding: 6px 12px;
        height: 32px;
        box-sizing: border-box;
    }
</style>
<body>
<section>
    <div class="title"><span>申请提现</span></div>
    <form action="/tixian/addTixian" style="margin-top:16px;">
        <line>
            <span class="title">累计销售额度：</span>
            <span><?= round($shop['Balance'],2) ?></span>
        </line>
        <line>
            <span class="title">累计可提现额度：</span>
            <span><?= round($shop['BalanceReal'],2) ?></span>
        </line>
        <line>
            <span class="title">累计申请提现：</span>
            <span><?= round($shop['totalBalanceApply'],2) ?></span>
        </line>
        <line>
            <span class="title">累计实际到账：</span>
            <span><?= round($shop['totalBalanceReal'],2) ?></span>
        </line>
        <line>
            <!--累计销售额度-已提现-->
            <span class="title">余额：</span>
            <span><?= round($shop['Balance'] - $shop['totalBalanceApply'],2) ?></span>
        </line>
        <line>
            <!--累计可提现额度-已提现-->
            <span class="title">可提现余额：</span>
            <span><?= round($shop['BalanceReal'] - $shop['totalBalanceApply'],2) ?></span>
        </line>
        <line>
            <span class="title">支付宝账号：</span>
            <span><?= $shop['ZhifubaoAccount'] ?></span>
        </line>
        <line>
            <span class="title">提现金额：</span>
            <?php
            //规定最大提现的正整数值
            $maxTX = intval($shop['BalanceReal'] - $shop['totalBalanceApply']);
            ?>
            <input class="tixianNum" type="number" name="Amount" min="1" max="<?= $maxTX ?>" step="1" value="">
        </line>
        <line>
            <span class="title"></span>
            <input type="reset">
            <button>保存</button>
        </line>
    </form>
</section>
<script src="<?= $staticPath ?>js/init.js?v=<?= $version ?>"></script>
<script src="<?= $staticPath ?>js/ext/form.js?v=<?= $version ?>"></script>
<script>

    $('form').FormAjax({
        success: function (data) {
            if (data.ErrorCode > 0) {
                $.tip(data.ErrorMsg, 2);
            } else {
                location.reload();
            }
        }
    });

</script>
</body>