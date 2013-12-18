<?php return array (
  'error' => 
  array (
    'level' => 6135,
    'display' => false,
    'log' => true,
    'path' => 'E:\\work\\ooh\\ooh\\tmp\\log\\',
  ),
  'db' => 
  array (
    'driver' => 'Mysql',
    'host' => 'localhost:3306',
    'dbname' => 'app_movie',
    'username' => 'root',
    'password' => '',
    'pconnect' => false,
    'charset' => 'utf8',
  ),
  'router' => 
  array (
    'control_action' => 'index.index',
    'route' => 'regex',
    'protocol' => 'PATH_INFO',
    'ext' => '.html',
    'urls' => 
    array (
      'dianying' => 
      array (
        'act' => 'index.dianying',
        'params' => '(?P<name>(.*)+)',
      ),
      'dianshi' => 
      array (
        'act' => 'index.dianshi',
        'params' => '(?P<name>(.*)+)',
      ),
      'zongyi' => 
      array (
        'act' => 'index.zongyi',
        'params' => '(?P<name>(.*)+)',
      ),
      'dongman' => 
      array (
        'act' => 'index.dongman',
        'params' => '(?P<name>(.*)+)',
      ),
      'v' => 
      array (
        'act' => 'index.view',
        'params' => '(?P<name>(.*)+)',
      ),
      'yun_full' => 
      array (
        'act' => 'yun.full',
        'params' => '',
      ),
      'search' => 
      array (
        'act' => 'yun.search',
        'params' => '',
      ),
      'push' => 
      array (
        'act' => 'yun.push',
        'params' => '',
      ),
    ),
  ),
  'cache' => 
  array (
    'driver' => 'file_cache',
    'exp' => 3600,
    'server' => '127.0.0.1:11211',
  ),
  'queue' => 
  array (
    'driver' => 'redis_queue',
    'pconnect' => false,
    'exp' => 3600,
    'server' => '127.0.0.1:6379',
  ),
  'pager' => 
  array (
    'driver' => 'pager_pager',
  ),
  'session' => 
  array (
    'driver' => 'file_session',
    'name' => 'SESSANDSKY',
    'expire' => 7200,
  ),
  'cookie' => 
  array (
    'expire' => 7200,
    'domain' => '',
    'path' => '/',
  ),
  'view' => 
  array (
    'driver' => 'Phplite',
    'output' => 'html',
    'ext' => '.html',
    'charset' => 'utf-8',
    'compile_check' => true,
    'debugging' => false,
    'use_sub_dirs' => false,
    'left_delimiter' => '{%',
    'right_delimiter' => '%}',
    'template_dir' => 'E:\\work\\ooh\\ooh\\app\\views/templates',
    'compile_dir' => 'E:\\work\\ooh\\ooh\\app\\views/templates_c',
    'cache_dir' => 'E:\\work\\ooh\\ooh\\app\\views/cache',
  ),
  'conf' => 
  array (
    'site_name' => 'hdv123高清影院',
    'site_url' => 'http://www.test.com/hdv123/web',
    'pic_url' => 'http://p1.hdv123.com/v/',
    'static_url' => 'http://www.test.com/hdv123/web',
    'site_mail' => 'support@andsky.com',
    'site_icp' => '冀ICP备05013665号 ',
    'site_open' => '0',
    'closedreason' => '',
    'site_keyword' => '天下站长网,站长网,PHP网,提供LINUX，Apache Web服务器,MYSQL数据库,PHP4,PHP5程序开发相关的技术文档,学习教程,编程技巧，教程源码, 实例下载,软件下载,问题讨论,www.andsky.com',
    'site_description' => '天下站长网,站长网,PHP网,cms,技术论坛,PHP论坛,专栏 文章,教程,下载,手册,域名,空间,网站推广,php,php3,php4,php5,linux, apache2,mysql,LAMP,  xml, code, scripts,classes,forums, bbs, templates,download,developers, dns, domain, hosting,promoting, zend studio, ultraedit',
    'time_offset' => '8',
    'cache_time' => '',
    'ckdomain' => NULL,
    'SendMethod' => 'SMTP',
    'SMTPHost' => '',
    'SMTPAuth' => NULL,
    'SMTPUsername' => '',
    'SMTPPassword' => '',
  ),
  'dinshi_act' => 
  array (
    0 => 
    array (
      'id' => '201',
      'type_id' => '4',
      'tagname' => '李小璐',
      'count' => '16',
    ),
    1 => 
    array (
      'id' => '204',
      'type_id' => '4',
      'tagname' => '张嘉译',
      'count' => '32',
    ),
    2 => 
    array (
      'id' => '646',
      'type_id' => '4',
      'tagname' => '文章',
      'count' => '17',
    ),
    3 => 
    array (
      'id' => '671',
      'type_id' => '4',
      'tagname' => '杨幂',
      'count' => '37',
    ),
    4 => 
    array (
      'id' => '675',
      'type_id' => '4',
      'tagname' => '吴秀波',
      'count' => '21',
    ),
    5 => 
    array (
      'id' => '1484',
      'type_id' => '4',
      'tagname' => '陈浩民',
      'count' => '26',
    ),
    6 => 
    array (
      'id' => '1567',
      'type_id' => '4',
      'tagname' => '海清',
      'count' => '13',
    ),
    7 => 
    array (
      'id' => '1787',
      'type_id' => '4',
      'tagname' => '林心如',
      'count' => '33',
    ),
    8 => 
    array (
      'id' => '3943',
      'type_id' => '4',
      'tagname' => '陈思成',
      'count' => '14',
    ),
    9 => 
    array (
      'id' => '4838',
      'type_id' => '4',
      'tagname' => '蔡少芬',
      'count' => '35',
    ),
    10 => 
    array (
      'id' => '4863',
      'type_id' => '4',
      'tagname' => '冯绍峰',
      'count' => '23',
    ),
    11 => 
    array (
      'id' => '5296',
      'type_id' => '4',
      'tagname' => '孙俪',
      'count' => '16',
    ),
    12 => 
    array (
      'id' => '8979',
      'type_id' => '4',
      'tagname' => '黄磊',
      'count' => '13',
    ),
    13 => 
    array (
      'id' => '9165',
      'type_id' => '4',
      'tagname' => '贾乃亮',
      'count' => '12',
    ),
    14 => 
    array (
      'id' => '9909',
      'type_id' => '4',
      'tagname' => '陈楚河',
      'count' => '6',
    ),
  ),
  'dinshi_type' => 
  array (
    27 => 
    array (
      'id' => '148',
      'type_id' => '1',
      'tagname' => '爱情',
      'count' => '2255',
    ),
    11 => 
    array (
      'id' => '149',
      'type_id' => '1',
      'tagname' => '古装',
      'count' => '196',
    ),
    13 => 
    array (
      'id' => '54',
      'type_id' => '1',
      'tagname' => '喜剧',
      'count' => '2935',
    ),
    18 => 
    array (
      'id' => '163',
      'type_id' => '1',
      'tagname' => '悬疑',
      'count' => '847',
    ),
    3 => 
    array (
      'id' => '371',
      'type_id' => '1',
      'tagname' => '传记',
      'count' => '184',
    ),
    6 => 
    array (
      'id' => '89',
      'type_id' => '1',
      'tagname' => '冒险',
      'count' => '812',
    ),
    7 => 
    array (
      'id' => '42',
      'type_id' => '1',
      'tagname' => '剧情',
      'count' => '5301',
    ),
    8 => 
    array (
      'id' => '1',
      'type_id' => '1',
      'tagname' => '动作',
      'count' => '2268',
    ),
    9 => 
    array (
      'id' => '313',
      'type_id' => '1',
      'tagname' => '动画',
      'count' => '777',
    ),
    10 => 
    array (
      'id' => '101',
      'type_id' => '1',
      'tagname' => '历史',
      'count' => '389',
    ),
    12 => 
    array (
      'id' => '1078',
      'type_id' => '1',
      'tagname' => '同性',
      'count' => '96',
    ),
    14 => 
    array (
      'id' => '322',
      'type_id' => '1',
      'tagname' => '奇幻',
      'count' => '646',
    ),
    15 => 
    array (
      'id' => '314',
      'type_id' => '1',
      'tagname' => '家庭',
      'count' => '673',
    ),
    16 => 
    array (
      'id' => '336',
      'type_id' => '1',
      'tagname' => '恐怖',
      'count' => '1444',
    ),
    20 => 
    array (
      'id' => '3',
      'type_id' => '1',
      'tagname' => '惊悚',
      'count' => '1632',
    ),
    23 => 
    array (
      'id' => '424',
      'type_id' => '1',
      'tagname' => '战争',
      'count' => '543',
    ),
    24 => 
    array (
      'id' => '490',
      'type_id' => '1',
      'tagname' => '歌舞',
      'count' => '100',
    ),
    25 => 
    array (
      'id' => '702',
      'type_id' => '1',
      'tagname' => '武侠',
      'count' => '80',
    ),
    28 => 
    array (
      'id' => '2',
      'type_id' => '1',
      'tagname' => '犯罪',
      'count' => '1121',
    ),
    30 => 
    array (
      'id' => '302',
      'type_id' => '1',
      'tagname' => '科幻',
      'count' => '816',
    ),
    31 => 
    array (
      'id' => '881',
      'type_id' => '1',
      'tagname' => '纪录',
      'count' => '181',
    ),
    34 => 
    array (
      'id' => '464',
      'type_id' => '1',
      'tagname' => '西部',
      'count' => '66',
    ),
    5 => 
    array (
      'id' => '4',
      'type_id' => '1',
      'tagname' => '其他',
      'count' => '10615',
    ),
  ),
  'dinying_act' => 
  array (
    0 => 
    array (
      'id' => '30',
      'type_id' => '4',
      'tagname' => '成龙',
      'count' => '60',
    ),
    1 => 
    array (
      'id' => '135',
      'type_id' => '4',
      'tagname' => '李连杰',
      'count' => '26',
    ),
    2 => 
    array (
      'id' => '402',
      'type_id' => '4',
      'tagname' => '范冰冰',
      'count' => '41',
    ),
    3 => 
    array (
      'id' => '439',
      'type_id' => '4',
      'tagname' => '刘德华',
      'count' => '88',
    ),
    4 => 
    array (
      'id' => '669',
      'type_id' => '4',
      'tagname' => '刘亦菲',
      'count' => '12',
    ),
    5 => 
    array (
      'id' => '793',
      'type_id' => '4',
      'tagname' => '孙红雷',
      'count' => '30',
    ),
    6 => 
    array (
      'id' => '878',
      'type_id' => '4',
      'tagname' => '周星驰',
      'count' => '39',
    ),
    7 => 
    array (
      'id' => '945',
      'type_id' => '4',
      'tagname' => '任达华',
      'count' => '74',
    ),
    8 => 
    array (
      'id' => '999',
      'type_id' => '4',
      'tagname' => '舒淇',
      'count' => '41',
    ),
    9 => 
    array (
      'id' => '1248',
      'type_id' => '4',
      'tagname' => '郑伊健',
      'count' => '47',
    ),
    10 => 
    array (
      'id' => '1856',
      'type_id' => '4',
      'tagname' => '吴君如',
      'count' => '56',
    ),
    11 => 
    array (
      'id' => '5164',
      'type_id' => '4',
      'tagname' => '甄子丹',
      'count' => '38',
    ),
    12 => 
    array (
      'id' => '5197',
      'type_id' => '4',
      'tagname' => '周润发',
      'count' => '35',
    ),
    13 => 
    array (
      'id' => '5516',
      'type_id' => '4',
      'tagname' => '黄渤',
      'count' => '21',
    ),
    14 => 
    array (
      'id' => '13362',
      'type_id' => '4',
      'tagname' => '林正英',
      'count' => '29',
    ),
  ),
  'dinying_area' => 
  array (
    35 => 
    array (
      'id' => '12',
      'type_id' => '2',
      'tagname' => '大陆',
      'count' => '2574',
    ),
    83 => 
    array (
      'id' => '55',
      'type_id' => '2',
      'tagname' => '美国',
      'count' => '2731',
    ),
    104 => 
    array (
      'id' => '28',
      'type_id' => '2',
      'tagname' => '香港',
      'count' => '1560',
    ),
    29 => 
    array (
      'id' => '273',
      'type_id' => '2',
      'tagname' => '台湾',
      'count' => '361',
    ),
    103 => 
    array (
      'id' => '5',
      'type_id' => '2',
      'tagname' => '韩国',
      'count' => '790',
    ),
    17 => 
    array (
      'id' => '224',
      'type_id' => '2',
      'tagname' => '加拿大',
      'count' => '220',
    ),
    25 => 
    array (
      'id' => '181',
      'type_id' => '2',
      'tagname' => '印度',
      'count' => '89',
    ),
    44 => 
    array (
      'id' => '82',
      'type_id' => '2',
      'tagname' => '德国',
      'count' => '223',
    ),
    47 => 
    array (
      'id' => '278',
      'type_id' => '2',
      'tagname' => '意大利',
      'count' => '88',
    ),
    54 => 
    array (
      'id' => '4175',
      'type_id' => '2',
      'tagname' => '新加坡',
      'count' => '66',
    ),
    57 => 
    array (
      'id' => '102',
      'type_id' => '2',
      'tagname' => '日本',
      'count' => '1605',
    ),
    61 => 
    array (
      'id' => '246',
      'type_id' => '2',
      'tagname' => '欧美',
      'count' => '500',
    ),
    63 => 
    array (
      'id' => '194',
      'type_id' => '2',
      'tagname' => '法国',
      'count' => '302',
    ),
    67 => 
    array (
      'id' => '532',
      'type_id' => '2',
      'tagname' => '泰国',
      'count' => '224',
    ),
    71 => 
    array (
      'id' => '125',
      'type_id' => '2',
      'tagname' => '澳大利亚',
      'count' => '91',
    ),
    90 => 
    array (
      'id' => '97',
      'type_id' => '2',
      'tagname' => '英国',
      'count' => '449',
    ),
  ),
  'dinying_type' => 
  array (
    13 => 
    array (
      'id' => '54',
      'type_id' => '1',
      'tagname' => '喜剧',
      'count' => '2935',
    ),
    8 => 
    array (
      'id' => '1',
      'type_id' => '1',
      'tagname' => '动作',
      'count' => '2268',
    ),
    27 => 
    array (
      'id' => '148',
      'type_id' => '1',
      'tagname' => '爱情',
      'count' => '2255',
    ),
    16 => 
    array (
      'id' => '336',
      'type_id' => '1',
      'tagname' => '恐怖',
      'count' => '1444',
    ),
    20 => 
    array (
      'id' => '3',
      'type_id' => '1',
      'tagname' => '惊悚',
      'count' => '1632',
    ),
    3 => 
    array (
      'id' => '371',
      'type_id' => '1',
      'tagname' => '传记',
      'count' => '184',
    ),
    6 => 
    array (
      'id' => '89',
      'type_id' => '1',
      'tagname' => '冒险',
      'count' => '812',
    ),
    7 => 
    array (
      'id' => '42',
      'type_id' => '1',
      'tagname' => '剧情',
      'count' => '5301',
    ),
    9 => 
    array (
      'id' => '313',
      'type_id' => '1',
      'tagname' => '动画',
      'count' => '777',
    ),
    10 => 
    array (
      'id' => '101',
      'type_id' => '1',
      'tagname' => '历史',
      'count' => '389',
    ),
    11 => 
    array (
      'id' => '149',
      'type_id' => '1',
      'tagname' => '古装',
      'count' => '196',
    ),
    14 => 
    array (
      'id' => '322',
      'type_id' => '1',
      'tagname' => '奇幻',
      'count' => '646',
    ),
    18 => 
    array (
      'id' => '163',
      'type_id' => '1',
      'tagname' => '悬疑',
      'count' => '847',
    ),
    19 => 
    array (
      'id' => '943',
      'type_id' => '1',
      'tagname' => '情色',
      'count' => '45',
    ),
    23 => 
    array (
      'id' => '424',
      'type_id' => '1',
      'tagname' => '战争',
      'count' => '543',
    ),
    24 => 
    array (
      'id' => '490',
      'type_id' => '1',
      'tagname' => '歌舞',
      'count' => '100',
    ),
    26 => 
    array (
      'id' => '1471',
      'type_id' => '1',
      'tagname' => '灾难',
      'count' => '21',
    ),
    28 => 
    array (
      'id' => '2',
      'type_id' => '1',
      'tagname' => '犯罪',
      'count' => '1121',
    ),
    30 => 
    array (
      'id' => '302',
      'type_id' => '1',
      'tagname' => '科幻',
      'count' => '816',
    ),
    31 => 
    array (
      'id' => '881',
      'type_id' => '1',
      'tagname' => '纪录',
      'count' => '181',
    ),
    5 => 
    array (
      'id' => '4',
      'type_id' => '1',
      'tagname' => '其他',
      'count' => '10615',
    ),
  ),
  'dinying_year' => 
  array (
    3 => 
    array (
      'id' => '696',
      'type_id' => '3',
      'tagname' => '2012',
      'count' => '506',
    ),
    5 => 
    array (
      'id' => '247',
      'type_id' => '3',
      'tagname' => '2011',
      'count' => '2434',
    ),
    6 => 
    array (
      'id' => '13',
      'type_id' => '3',
      'tagname' => '2010',
      'count' => '1517',
    ),
    7 => 
    array (
      'id' => '111',
      'type_id' => '3',
      'tagname' => '2009',
      'count' => '1071',
    ),
    8 => 
    array (
      'id' => '116',
      'type_id' => '3',
      'tagname' => '2008',
      'count' => '908',
    ),
    9 => 
    array (
      'id' => '103',
      'type_id' => '3',
      'tagname' => '2007',
      'count' => '856',
    ),
    10 => 
    array (
      'id' => '6',
      'type_id' => '3',
      'tagname' => '2006',
      'count' => '779',
    ),
    11 => 
    array (
      'id' => '90',
      'type_id' => '3',
      'tagname' => '2005',
      'count' => '608',
    ),
    12 => 
    array (
      'id' => '43',
      'type_id' => '3',
      'tagname' => '2004',
      'count' => '473',
    ),
    13 => 
    array (
      'id' => '303',
      'type_id' => '3',
      'tagname' => '2003',
      'count' => '354',
    ),
    14 => 
    array (
      'id' => '444',
      'type_id' => '3',
      'tagname' => '2002',
      'count' => '293',
    ),
    15 => 
    array (
      'id' => '26281',
      'type_id' => '3',
      'tagname' => '20011',
      'count' => '2',
    ),
    16 => 
    array (
      'id' => '457',
      'type_id' => '3',
      'tagname' => '2001',
      'count' => '251',
    ),
    17 => 
    array (
      'id' => '68',
      'type_id' => '3',
      'tagname' => '2000',
      'count' => '197',
    ),
    18 => 
    array (
      'id' => '1607',
      'type_id' => '3',
      'tagname' => '1999',
      'count' => '181',
    ),
    19 => 
    array (
      'id' => '337',
      'type_id' => '3',
      'tagname' => '1998',
      'count' => '149',
    ),
    20 => 
    array (
      'id' => '521',
      'type_id' => '3',
      'tagname' => '1997',
      'count' => '128',
    ),
    21 => 
    array (
      'id' => '98',
      'type_id' => '3',
      'tagname' => '1996',
      'count' => '125',
    ),
    22 => 
    array (
      'id' => '29',
      'type_id' => '3',
      'tagname' => '1995',
      'count' => '117',
    ),
    23 => 
    array (
      'id' => '72',
      'type_id' => '3',
      'tagname' => '1994',
      'count' => '114',
    ),
    24 => 
    array (
      'id' => '3045',
      'type_id' => '3',
      'tagname' => '1993',
      'count' => '102',
    ),
  ),
  'yun' => 
  array (
    'cloudsearch_clientid' => 208,
    'cloudsearch_charset' => 'utf-8',
    'cloudsearch_siteurl' => 'www.cn0314.com',
    'cloudsearch_key' => 'b2da8afe25303cea036058234ca0eca2',
  ),
);