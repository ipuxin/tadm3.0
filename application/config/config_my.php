<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/* 数据接口配置 */
$config['db_api_base'] = 'http://tapi.pingoing.cn/';
$config['db_api_type'] = 'restful.';
$config['db_table_prefix'] = 'PYX3_';
$config['db_api_action'] = array('select'=>'Query','create'=>'Create','update'=>'Update','save'=>'Save','delete'=>'Delete');
$config['db_max_query_time'] = 5;
$config['db_log_query_time'] = 1;

/* 微信配置 */
/*$config['wx_appid'] = 'wx032ee768936f17d3';
$config['wx_secret'] = 'df15a117460067781749ae7d38cb90e0';
$config['wx_token'] = 'D25qFgENJxdzdpPrx2MA';
$config['wx_jsapi_path'] = 'application/data/jsapi.txt';
$config['wx_token_path'] = 'application/data/token.txt';*/

/* 页面配置 */
$config['pg_version_open'] = FALSE;
$config['pg_version'] = '201607290001';

/* 店铺配置 */
$config['shop_admin_prefix'] = 'shop_';
$config['shop_admin_password'] = '123456';

/* 手续费设置 */
$config['shop_charge_now'] = 0.04;  //商铺提现手续费
$config['hehuo_charge_now'] = 0.02;  //城市合伙人收益率
