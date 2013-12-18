<?php

View::instance()->regFun(array('insert_video'));
View::instance()->regFun(array('url'));
View::instance()->_view->register_block('urlRewrite','dourlRewrite');

function url($params)
{
     //extract($params);


     switch (Config::instance()->router['route']) {
         case 'get':
             return '?'.http_build_query($params);
             break;
         case 'regex':
             foreach (Config::instance()->router['urls'] as $alias => $value) {
                 if ($alias == $act) {
                     $url = '/'.$alias;
                     if (!empty($value['params'])) {
                         $url .= '/';
                         preg_match_all("/<([^>]+)>/",  $value['params'],  $param);
                         preg_match_all("/\)([^>]+)\(/",  $value['params'],  $paramexp);
                         foreach ($param[1] as $key => $value) {
                             if (!empty($params[$value])) {
                                 $url .= $params[$value];
                                 if (!empty($paramexp[1][$key])) {
                                     $url .= $paramexp[1][$key];
                                 }
                             }

                         }
                     }

                    return $url.Config::instance()->router['ext'];

                };
            }



         default:
             ;
         break;
     }
     return basename($_SERVER['SCRIPT_NAME']).'?'.http_build_query($params);

}

/**
 * 取得文章
 * @param string cid
 * @param int limit
 * @return array
 * @since 1.0
 */
function insert_video()
{
	$args = @func_get_arg(0);
	if ( empty( $args ) )
	{
		return false;
	}
	extract($args);
    if (empty($limit))
	{
		$limit = 10;
	}
	$article = Base::instance()->model('video')->lists($cid, FALSE, $limit);
	View::instance()->assign($assign, $article);
	return  ;
}

function dourlRewrite($params, $url)
{

    $urls = array();
    $urls['type'] = Request::instance()->type;
    $urls['area'] = Request::instance()->area;
    $urls['year'] = Request::instance()->year;

    list($key, $val) = explode('=', $url);    //$urls = array_merge_recursive($urls, $querys);
    $urls[$key] = $val;
    foreach ($urls as $key => $value) {
        if (empty($value)) {
            unset($urls[$key]);
        }
    }
    if (!empty($urls)) {
        return str_replace(array('=','&'), '-', http_build_query($urls));
    }
    return '';


}

?>