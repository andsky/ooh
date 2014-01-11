<?php
/**
 *
 * base class
 *
 * @author andsky<andsky888@gmail.com>
 * @version 1.0 2008-9-10
 */


class Base{

    private static $_instance;
    private static $_plugins = array();
	private static $_models = array();


    public static function instance()
    {
        if (self::$_instance == NULL) {
            self::$_instance = new self;
        }
        return self::$_instance;
    }

    /**
     *
     * 加载model类
     * @param string $module 类名
     * @return Model
     */
    public static function model($model)
    {
		if (!empty(self::$_models[$model]))
        {
            return self::$_models[$model];
        }
        $model_class = BASE_PATH.APP_MODEL.DIRECTORY_SEPARATOR.'models'.DIRECTORY_SEPARATOR.$model.'.php';

        if ( is_file( $model_class ) )
        {
            require($model_class);
        }else{
            throw new Http503Exceptions("can't load model {$model},Please check!!");
        }
        self::$_models[$model] = new $model();
        return self::$_models[$model];
    }

    /**
     * 装载插件
     * @param string $plugin
     * @author andsky 669811@qq.com
     */
    public static function plugin($plugin)
    {
        if(isset(self::$_plugins[$plugin])) {
            return self::$_plugins[$plugin];
        }
        $class_file = '';
        foreach (array(PLU_PATH, APP_PLU_PATH) as $path) {
            $class_file = $path.$plugin.'.php';
            if (file_exists($class_file)) {
                break;
            }
        }
        if ( empty($class_file))
        {
            throw new HttpExceptions('not fount plugin: '.$plugin);
        }
        require($class_file);
        if (class_exists($plugin)) {
            self::$_plugins[$plugin] =  new $plugin();
             return self::$_plugins[$plugin];
        }else {
            self::$_plugins[$plugin] = TRUE;
            return TRUE;
        }

    }
}