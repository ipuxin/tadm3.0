<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//菜单配置

$config['FactoryMenu']['商品']['商品列表'] = array('MenuUrl'=>'/product/productList');
$config['FactoryMenu']['商品']['发布全国商品'] = array('MenuUrl'=>'/product/productAdd');
$config['FactoryMenu']['商品']['置顶全国商品'] = array('MenuUrl'=>'/product/productIndex');
$config['FactoryMenu']['商品']['商品评价'] = array('MenuUrl'=>'/product/pinjia');

$config['FactoryMenu']['活动']['新增优惠券'] = array('MenuUrl'=>'/coupon/couponadd');
$config['FactoryMenu']['活动']['优惠券列表'] = array('MenuUrl'=>'/coupon/couponlist');
$config['FactoryMenu']['活动']['已发优惠券'] = array('MenuUrl'=>'/coupon/usercouponlist');
//$config['FactoryMenu']['活动']['活动优惠券'] = array('MenuUrl'=>'/coupon/couponActive');

//$config['FactoryMenu']['统计'][''] = array('MenuUrl'=>'/product/muban');

$config['FactoryMenu']['会员']['全部会员'] = array('MenuUrl'=>'/user');

$config['FactoryMenu']['订单']['全部订单'] = array('MenuUrl'=>'/order');
$config['FactoryMenu']['订单']['待付款订单'] = array('MenuUrl'=>'/order/index/1');
$config['FactoryMenu']['订单']['已付款订单'] = array('MenuUrl'=>'/order/index/2');
$config['FactoryMenu']['订单']['待发货订单'] = array('MenuUrl'=>'/order/index/3');
$config['FactoryMenu']['订单']['已发货订单'] = array('MenuUrl'=>'/order/index/4');
$config['FactoryMenu']['订单']['已签收订单'] = array('MenuUrl'=>'/order/index/5');
$config['FactoryMenu']['订单']['已取消订单'] = array('MenuUrl'=>'/order/index/6');
$config['FactoryMenu']['订单']['已退款订单'] = array('MenuUrl'=>'/order/index/7');
$config['FactoryMenu']['订单']['订单批量处理'] = array('MenuUrl'=>'/order/excelpage');

$config['FactoryMenu']['团购']['全部团购'] = array('MenuUrl'=>'/team');
$config['FactoryMenu']['团购']['待支付团购'] = array('MenuUrl'=>'/team/index/1');
$config['FactoryMenu']['团购']['正在拼团'] = array('MenuUrl'=>'/team/index/2');
$config['FactoryMenu']['团购']['拼团完成'] = array('MenuUrl'=>'/team/index/3');
$config['FactoryMenu']['团购']['拼团失败'] = array('MenuUrl'=>'/team/index/4');
$config['FactoryMenu']['团购']['取消拼团'] = array('MenuUrl'=>'/team/index/5');

$config['FactoryMenu']['设置']['文章设置'] = array('MenuUrl'=>'/setting/newsList');
//$config['FactoryMenu']['设置']['分类设置'] = array('MenuUrl'=>'/setting/productTags');
$config['FactoryMenu']['设置']['评价标签'] = array('MenuUrl'=>'/setting/pinjiaTags');
$config['FactoryMenu']['设置']['焦点图设置'] = array('MenuUrl'=>'/banner/banners');
$config['FactoryMenu']['设置']['热门城市'] = array('MenuUrl'=>'/setting/hotCitys');

$config['FactoryMenu']['系统']['物流商管理'] = array('MenuUrl'=>'/admin/kuaidiList');
$config['FactoryMenu']['系统']['加盟商管理'] = array('MenuUrl'=>'/admin/adminList');
$config['FactoryMenu']['系统']['添加加盟商'] = array('MenuUrl'=>'/admin/adminAdd');
$config['FactoryMenu']['系统']['加盟商申请'] = array('MenuUrl'=>'/board');

$config['FactoryMenu']['代理商']['代理商管理'] = array('MenuUrl'=>'/admin/agentList');
$config['FactoryMenu']['代理商']['添加代理商'] = array('MenuUrl'=>'/admin/agentAdd');
$config['FactoryMenu']['代理商']['产品管理'] = array('MenuUrl'=>'/agent/agentProduct');
$config['FactoryMenu']['代理商']['添加产品'] = array('MenuUrl'=>'/agent/agentProductAdd');
$config['FactoryMenu']['代理商']['待发货订单'] = array('MenuUrl'=>'/agent/agentOrder/3');
$config['FactoryMenu']['代理商']['已发货订单'] = array('MenuUrl'=>'/agent/agentOrder/4');
$config['FactoryMenu']['代理商']['订单批量处理'] = array('MenuUrl'=>'/agent/agentOrderExcel');

$config['FactoryMenu']['货源共享']['货源列表'] = array('MenuUrl'=>'/goods/goodsList');
$config['FactoryMenu']['货源共享']['货源审核'] = array('MenuUrl'=>'/goods/goodsCheck');
$config['FactoryMenu']['货源共享']['我的货源'] = array('MenuUrl'=>'/goods/goodsMine');
$config['FactoryMenu']['货源共享']['发布货源'] = array('MenuUrl'=>'/goods/goodsAdd');

$config['FactoryMenu2']['会员']['全部会员'] = array('MenuUrl'=>'/user');

$config['FactoryMenu2']['商品']['商品列表'] = array('MenuUrl'=>'/product2/productList');
$config['FactoryMenu2']['商品']['发布商品'] = array('MenuUrl'=>'/product2/productAdd');
$config['FactoryMenu2']['商品']['首页商品'] = array('MenuUrl'=>'/product2/productIndex');

$config['FactoryMenu2']['活动']['新增优惠券'] = array('MenuUrl'=>'/coupon2/couponadd');
$config['FactoryMenu2']['活动']['优惠券列表'] = array('MenuUrl'=>'/coupon2/couponlist');
$config['FactoryMenu2']['活动']['已发优惠券'] = array('MenuUrl'=>'/coupon2/usercouponlist');
$config['FactoryMenu2']['活动']['活动优惠券'] = array('MenuUrl'=>'/coupon2/couponActive');

$config['FactoryMenu2']['订单']['全部订单'] = array('MenuUrl'=>'/order');
$config['FactoryMenu2']['订单']['待付款订单'] = array('MenuUrl'=>'/order/index/1');
$config['FactoryMenu2']['订单']['已付款订单'] = array('MenuUrl'=>'/order/index/2');
$config['FactoryMenu2']['订单']['待发货订单'] = array('MenuUrl'=>'/order/index/3');
$config['FactoryMenu2']['订单']['已发货订单'] = array('MenuUrl'=>'/order/index/4');
$config['FactoryMenu2']['订单']['已签收订单'] = array('MenuUrl'=>'/order/index/5');
$config['FactoryMenu2']['订单']['已取消订单'] = array('MenuUrl'=>'/order/index/6');
$config['FactoryMenu2']['订单']['已退款订单'] = array('MenuUrl'=>'/order/index/7');
$config['FactoryMenu2']['订单']['订单批量处理'] = array('MenuUrl'=>'/order/excelpage');

$config['FactoryMenu2']['团购']['全部团购'] = array('MenuUrl'=>'/team2');
$config['FactoryMenu2']['团购']['待支付团购'] = array('MenuUrl'=>'/team2/index/1');
$config['FactoryMenu2']['团购']['正在拼团'] = array('MenuUrl'=>'/team2/index/2');
$config['FactoryMenu2']['团购']['拼团完成'] = array('MenuUrl'=>'/team2/index/3');
$config['FactoryMenu2']['团购']['拼团失败'] = array('MenuUrl'=>'/team2/index/4');
$config['FactoryMenu2']['团购']['取消拼团'] = array('MenuUrl'=>'/team2/index/5');

$config['FactoryMenu2']['设置']['分类设置'] = array('MenuUrl'=>'/setting2/productTags');

$config['FactoryMenu2']['系统']['下级账号管理'] = array('MenuUrl'=>'/admin2/adminList');
$config['FactoryMenu2']['系统']['添加下级账号'] = array('MenuUrl'=>'/admin2/adminAdd');

$config['FactoryMenu2']['货源共享']['货源列表'] = array('MenuUrl'=>'/goods/goodsList');
$config['FactoryMenu2']['货源共享']['我的货源'] = array('MenuUrl'=>'/goods/goodsMine');
$config['FactoryMenu2']['货源共享']['发布货源'] = array('MenuUrl'=>'/goods/goodsAdd');

$config['FactoryMenu3']['订单']['创建订单'] = array('MenuUrl'=>'/agent/orderAdd');
$config['FactoryMenu3']['订单']['待发货订单'] = array('MenuUrl'=>'/agent/agentOrder/3');
$config['FactoryMenu3']['订单']['已发货订单'] = array('MenuUrl'=>'/agent/agentOrder/4');

$config['FactoryMenu4']['订单']['全部订单'] = array('MenuUrl'=>'/order');
$config['FactoryMenu4']['订单']['待付款订单'] = array('MenuUrl'=>'/order/index/1');
$config['FactoryMenu4']['订单']['已付款订单'] = array('MenuUrl'=>'/order/index/2');
$config['FactoryMenu4']['订单']['待发货订单'] = array('MenuUrl'=>'/order/index/3');
$config['FactoryMenu4']['订单']['已发货订单'] = array('MenuUrl'=>'/order/index/4');
$config['FactoryMenu4']['订单']['已签收订单'] = array('MenuUrl'=>'/order/index/5');
$config['FactoryMenu4']['订单']['已取消订单'] = array('MenuUrl'=>'/order/index/6');
$config['FactoryMenu4']['订单']['已退款订单'] = array('MenuUrl'=>'/order/index/7');
?>