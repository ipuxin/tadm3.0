<?php
defined('BASEPATH') OR exit('No direct script access allowed');

//管理员菜单
$config['PyxMenu1_Main'] = '/admin/product/product_list';
$config['PyxMenu1']['我的']['提现申请'] = array('MenuUrl'=>'/admin/tixian/tixian_list');
$config['PyxMenu1']['我的']['我的资料'] = array('MenuUrl'=>'/admin/user/user_self_upd');

$config['PyxMenu1']['商品']['商品列表'] = array('MenuUrl'=>'/admin/product/product_list');
$config['PyxMenu1']['商品']['商品审核'] = array('MenuUrl'=>'/admin/product/product_list_check');

$config['PyxMenu1']['活动']['新增优惠券'] = array('MenuUrl'=>'/admin/coupon/coupon_add');
$config['PyxMenu1']['活动']['优惠券列表'] = array('MenuUrl'=>'/admin/coupon/coupon_list');
$config['PyxMenu1']['活动']['已发优惠券'] = array('MenuUrl'=>'/admin/coupon/coupon_user_list');

$config['PyxMenu1']['会员']['全部会员'] = array('MenuUrl'=>'/admin/user/user_list');

$config['PyxMenu1']['订单']['全部订单'] = array('MenuUrl'=>'/admin/order/order_list');
$config['PyxMenu1']['订单']['待付款订单'] = array('MenuUrl'=>'/admin/order/order_list/1');
$config['PyxMenu1']['订单']['已付款订单'] = array('MenuUrl'=>'/admin/order/order_list/2');
$config['PyxMenu1']['订单']['待发货订单'] = array('MenuUrl'=>'/admin/order/order_list/3');
$config['PyxMenu1']['订单']['已发货订单'] = array('MenuUrl'=>'/admin/order/order_list/4');
$config['PyxMenu1']['订单']['已签收订单'] = array('MenuUrl'=>'/admin/order/order_list/5');
$config['PyxMenu1']['订单']['已取消订单'] = array('MenuUrl'=>'/admin/order/order_list/6');
$config['PyxMenu1']['订单']['已退款订单'] = array('MenuUrl'=>'/admin/order/order_list/7');
$config['PyxMenu1']['订单']['订单批量处理'] = array('MenuUrl'=>'/admin/order/order_piliang');

$config['PyxMenu1']['团购']['全部团购'] = array('MenuUrl'=>'/admin/team/team_list');
$config['PyxMenu1']['团购']['待支付团购'] = array('MenuUrl'=>'/admin/team/team_list/1');
$config['PyxMenu1']['团购']['正在拼团'] = array('MenuUrl'=>'/admin/team/team_list/2');
$config['PyxMenu1']['团购']['拼团完成'] = array('MenuUrl'=>'/admin/team/team_list/3');
$config['PyxMenu1']['团购']['拼团失败'] = array('MenuUrl'=>'/admin/team/team_list/4');
$config['PyxMenu1']['团购']['取消拼团'] = array('MenuUrl'=>'/admin/team/team_list/5');

$config['PyxMenu1']['店铺']['全部店铺'] = array('MenuUrl'=>'/admin/shop/shop_list');
$config['PyxMenu1']['店铺']['店铺审核'] = array('MenuUrl'=>'/admin/shop/shop_check');
$config['PyxMenu1']['店铺']['已审核店铺'] = array('MenuUrl'=>'/admin/shop/shop_check2');
$config['PyxMenu1']['店铺']['禁用店铺'] = array('MenuUrl'=>'/admin/shop/shop_disable');

$config['PyxMenu1']['加盟商']['加盟商管理'] = array('MenuUrl'=>'/admin/jiameng/jiameng_list');
$config['PyxMenu1']['加盟商']['添加加盟商'] = array('MenuUrl'=>'/admin/jiameng/jiameng_add');
$config['PyxMenu1']['加盟商']['门店商管理'] = array('MenuUrl'=>'/admin/mendian/mendian_list');
$config['PyxMenu1']['加盟商']['添加门店商'] = array('MenuUrl'=>'/admin/mendian/mendian_add');

$config['PyxMenu1']['设置']['分类设置'] = array('MenuUrl'=>'/admin/setting/category');
$config['PyxMenu1']['设置']['热门城市'] = array('MenuUrl'=>'/admin/setting/setting_hotcitys');
$config['PyxMenu1']['设置']['焦点图'] = array('MenuUrl'=>'/admin/setting/setting_banner');

//加盟商菜单
$config['PyxMenu2_Main'] = '/admin2/product/product_list';
$config['PyxMenu2']['我的']['我的资料'] = array('MenuUrl'=>'/admin2/user/user_self_upd');
$config['PyxMenu2']['我的']['提现明细'] = array('MenuUrl'=>'/admin2/tixian/tixian_list');
$config['PyxMenu2']['我的']['申请提现'] = array('MenuUrl'=>'/admin2/tixian/tixian_add');

$config['PyxMenu2']['商品']['商品列表'] = array('MenuUrl'=>'/admin2/product/product_list');
$config['PyxMenu2']['商品']['商品审核'] = array('MenuUrl'=>'/admin2/product/product_list_check');

$config['PyxMenu2']['会员']['全部会员'] = array('MenuUrl'=>'/admin2/user/user_list');

$config['PyxMenu2']['订单']['全部订单'] = array('MenuUrl'=>'/admin2/order/order_list');
$config['PyxMenu2']['订单']['待付款订单'] = array('MenuUrl'=>'/admin2/order/order_list/1');
$config['PyxMenu2']['订单']['已付款订单'] = array('MenuUrl'=>'/admin2/order/order_list/2');
$config['PyxMenu2']['订单']['待发货订单'] = array('MenuUrl'=>'/admin2/order/order_list/3');
$config['PyxMenu2']['订单']['已发货订单'] = array('MenuUrl'=>'/admin2/order/order_list/4');
$config['PyxMenu2']['订单']['已签收订单'] = array('MenuUrl'=>'/admin2/order/order_list/5');
$config['PyxMenu2']['订单']['已取消订单'] = array('MenuUrl'=>'/admin2/order/order_list/6');
$config['PyxMenu2']['订单']['已退款订单'] = array('MenuUrl'=>'/admin2/order/order_list/7');
$config['PyxMenu2']['订单']['订单批量处理'] = array('MenuUrl'=>'/admin2/order/order_piliang');

$config['PyxMenu2']['团购']['全部团购'] = array('MenuUrl'=>'/admin2/team/team_list');
$config['PyxMenu2']['团购']['待支付团购'] = array('MenuUrl'=>'/admin2/team/team_list/1');
$config['PyxMenu2']['团购']['正在拼团'] = array('MenuUrl'=>'/admin2/team/team_list/2');
$config['PyxMenu2']['团购']['拼团完成'] = array('MenuUrl'=>'/admin2/team/team_list/3');
$config['PyxMenu2']['团购']['拼团失败'] = array('MenuUrl'=>'/admin2/team/team_list/4');
$config['PyxMenu2']['团购']['取消拼团'] = array('MenuUrl'=>'/admin2/team/team_list/5');

$config['PyxMenu2']['店铺']['全部店铺'] = array('MenuUrl'=>'/admin2/shop/shop_list');
$config['PyxMenu2']['店铺']['店铺审核'] = array('MenuUrl'=>'/admin2/shop/shop_check');
$config['PyxMenu2']['店铺']['禁用店铺'] = array('MenuUrl'=>'/admin2/shop/shop_disable');

//店铺菜单
$config['PyxMenu3']['我的']['我的资料'] = array('MenuUrl'=>'/admin3/user/user_self_upd');
$config['PyxMenu3']['我的']['我的店铺'] = array('MenuUrl'=>'/admin3/shop/shop_self_upd');
$config['PyxMenu3']['我的']['交易明细'] = array('MenuUrl'=>'/admin3/product/product_sale_list');
$config['PyxMenu3']['我的']['资金明细'] = array('MenuUrl'=>'/admin3/product/shop_balance_list');
$config['PyxMenu3']['我的']['提现明细'] = array('MenuUrl'=>'/admin3/tixian/tixian_list');
$config['PyxMenu3']['我的']['申请提现'] = array('MenuUrl'=>'/admin3/tixian/tixian_add');

$config['PyxMenu3']['商品']['商品列表'] = array('MenuUrl'=>'/admin3/product/product_list');
$config['PyxMenu3']['商品']['商品审核'] = array('MenuUrl'=>'/admin3/product/product_list_check');
$config['PyxMenu3']['商品']['发布商品'] = array('MenuUrl'=>'/admin3/product/product_add');

$config['PyxMenu3']['活动']['新增优惠券'] = array('MenuUrl'=>'/admin3/coupon/coupon_add');
$config['PyxMenu3']['活动']['优惠券列表'] = array('MenuUrl'=>'/admin3/coupon/coupon_list');
$config['PyxMenu3']['活动']['已发优惠券'] = array('MenuUrl'=>'/admin3/coupon/coupon_user_list');

//$config['PyxMenu3']['会员']['全部会员'] = array('MenuUrl'=>'/admin3/user/user_list');

$config['PyxMenu3']['订单']['全部订单'] = array('MenuUrl'=>'/admin3/order/order_list');
$config['PyxMenu3']['订单']['待付款订单'] = array('MenuUrl'=>'/admin3/order/order_list/1');
$config['PyxMenu3']['订单']['已付款订单'] = array('MenuUrl'=>'/admin3/order/order_list/2');
$config['PyxMenu3']['订单']['待发货订单'] = array('MenuUrl'=>'/admin3/order/order_list/3');
$config['PyxMenu3']['订单']['已发货订单'] = array('MenuUrl'=>'/admin3/order/order_list/4');
$config['PyxMenu3']['订单']['已签收订单'] = array('MenuUrl'=>'/admin3/order/order_list/5');
$config['PyxMenu3']['订单']['已取消订单'] = array('MenuUrl'=>'/admin3/order/order_list/6');
$config['PyxMenu3']['订单']['已退款订单'] = array('MenuUrl'=>'/admin3/order/order_list/7');
$config['PyxMenu3']['订单']['订单批量处理'] = array('MenuUrl'=>'/admin3/order/order_piliang');

$config['PyxMenu3']['团购']['全部团购'] = array('MenuUrl'=>'/admin3/team/team_list');
$config['PyxMenu3']['团购']['待支付团购'] = array('MenuUrl'=>'/admin3/team/team_list/1');
$config['PyxMenu3']['团购']['正在拼团'] = array('MenuUrl'=>'/admin3/team/team_list/2');
$config['PyxMenu3']['团购']['拼团完成'] = array('MenuUrl'=>'/admin3/team/team_list/3');
$config['PyxMenu3']['团购']['拼团失败'] = array('MenuUrl'=>'/admin3/team/team_list/4');
$config['PyxMenu3']['团购']['取消拼团'] = array('MenuUrl'=>'/admin3/team/team_list/5');

$config['PyxMenu3']['设置']['快递商设置'] = array('MenuUrl'=>'/admin3/kuaidi/setkuaidi');

//门店商菜单
$config['PyxMenu4_Main'] = '/admin2/product/product_list';
$config['PyxMenu4']['我的']['我的资料'] = array('MenuUrl'=>'/admin4/user/user_self_upd');
//$config['PyxMenu4']['我的']['提现明细'] = array('MenuUrl'=>'/admin4/tixian/tixian_list');
$config['PyxMenu4']['我的']['申请提现'] = array('MenuUrl'=>'/admin4/tixian/tixian_add');

$config['PyxMenu4']['商品']['商品列表'] = array('MenuUrl'=>'/admin4/product/product_list');
//$config['PyxMenu4']['商品']['商品审核'] = array('MenuUrl'=>'/admin4/product/product_list_check');

//$config['PyxMenu4']['会员']['全部会员'] = array('MenuUrl'=>'/admin4/user/user_list');

$config['PyxMenu4']['订单']['全部订单'] = array('MenuUrl'=>'/admin4/order/order_list');
$config['PyxMenu4']['订单']['待付款订单'] = array('MenuUrl'=>'/admin4/order/order_list/1');
$config['PyxMenu4']['订单']['已付款订单'] = array('MenuUrl'=>'/admin4/order/order_list/2');
$config['PyxMenu4']['订单']['待发货订单'] = array('MenuUrl'=>'/admin4/order/order_list/3');
$config['PyxMenu4']['订单']['已发货订单'] = array('MenuUrl'=>'/admin4/order/order_list/4');
$config['PyxMenu4']['订单']['已签收订单'] = array('MenuUrl'=>'/admin4/order/order_list/5');
$config['PyxMenu4']['订单']['已取消订单'] = array('MenuUrl'=>'/admin4/order/order_list/6');
$config['PyxMenu4']['订单']['已退款订单'] = array('MenuUrl'=>'/admin4/order/order_list/7');
//$config['PyxMenu4']['订单']['订单批量处理'] = array('MenuUrl'=>'/admin4/order/order_piliang');

$config['PyxMenu4']['团购']['全部团购'] = array('MenuUrl'=>'/admin4/team/team_list');
$config['PyxMenu4']['团购']['待支付团购'] = array('MenuUrl'=>'/admin4/team/team_list/1');
$config['PyxMenu4']['团购']['正在拼团'] = array('MenuUrl'=>'/admin4/team/team_list/2');
$config['PyxMenu4']['团购']['拼团完成'] = array('MenuUrl'=>'/admin4/team/team_list/3');
$config['PyxMenu4']['团购']['拼团失败'] = array('MenuUrl'=>'/admin4/team/team_list/4');
$config['PyxMenu4']['团购']['取消拼团'] = array('MenuUrl'=>'/admin4/team/team_list/5');

$config['PyxMenu4']['店铺']['全部店铺'] = array('MenuUrl'=>'/admin4/shop/shop_list');
//$config['PyxMenu4']['店铺']['店铺审核'] = array('MenuUrl'=>'/admin4/shop/shop_check');
//$config['PyxMenu4']['店铺']['禁用店铺'] = array('MenuUrl'=>'/admin4/shop/shop_disable');
?>