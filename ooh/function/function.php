<?php

/**
 * 内部调用的函数集
 */



/**
 * 自动加载类
 *
 * @param string $class 类
 * @since 1.0
 */
function __autoload($class)
{
    static $objects = array();

    if (isset($objects[$class])){
        return $objects[$class];
    }
    $class_file = '';
    foreach (array(LIB_PATH, APP_LIB_PATH, SHARED_LIB_PATH) as $path) {
        $class_file = $path.$class.'.php';
        if (file_exists($class_file)) {
            break;
        }
    }
    if ( empty($class_file))
    {
        throw new HttpExceptions('not fount lib: '.$class);
    }
    require( $class_file);
    $objects[$class] =  $class;
    return TRUE;

}

/**
 * 导入类
 *
 * @param string $class 类 支持 , . *
 * @param string $subdir 是否导入子目录 默认false
 */
function import($class, $subdir = FALSE, $is_dir = FALSE)
{
    static $objects = array();

    if (isset($objects[$class])){
        return TRUE;
    }
    if (class_exists($class, FALSE)){
        return TRUE;
    }
    if (strstr($class,',')){
        $classes = explode( ',', $class );
        foreach ( $classes as $v )
        {
            import($v);
        }
    }
    if (is_file($class)){
        require($class);
        $objects[$class] = TRUE;
        return TRUE;
    }
    if (strstr($class,'.')){
        $class = str_replace( '.', '/', $class);
    }
    $search_dir = array(LIB_PATH, PLU_PATH, APP_LIB_PATH, APP_PLU_PATH, SHARED_LIB_PATH, SHARED_PLU_PATH);
    //已经是路径
    foreach ($search_dir as $dir)
    {
        $ClassName = $dir.$class.'.php';
        if ( is_file( $ClassName ) ){
            require( $ClassName );
            $objects[$class] = TRUE;
            return TRUE;
        }
    }
    //*载入
    if (strstr(OOH_PATH.$class, '*')){
        $array = Fso::instance()->fileList(OOH_PATH.str_replace('*', '', $class ), TRUE, '*.php');
        foreach ($array as $path) {
            if (is_file($path)) {
                import($path);
            }
        }

    }
    return TRUE;
}

