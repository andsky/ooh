<?php
/**
 *
 * js class
 *
 * @author andsky<andsky888@gmail.com>
 * @version 1.0 2013-9-10
 */
class JS
{
    /**
     * 返回上一页
     * @param unknown_type $step
     * @author andsky 669811@qq.com
     */
    static public function back($step = -1)
    {
        $js = 'history.go('.$step.');';
        JS::_write($js);

    }
    /**
     * alert
     * @param unknown_type $msg
     * @author andsky 669811@qq.com
     */
    static public function alert($msg, $url = '')
    {
        $js = 'alert(\''.$msg.'\');';
        if (!empty($url)) {
            if ($url == 'back') {
                $js .= 'history.go(-1);';
            }else {
                $js .= 'window.location.href="'.$url.'";';
            }

        }
        JS::_write($js);
    }
    /**
     * 完成输出 js
     * @param unknown_type $msg
     * @author andsky 669811@qq.com
     */
    static public function _write($js)
    {
        header("Content-type: text/html; charset=utf-8");
        echo '<script language="javascript">';
        echo $js;
        echo '</script>';
        exit;
    }

    static public function reload()
    {
        $js = 'location.reload();';
        JS::_write($js);

    }

    static public function reloadOpener()
    {
        $js = 'if (opener)opener.location.reload();';
        JS::_write($js);
    }

    static public function go($url)
    {
        $js = 'if(self!=top)
			  {
				  parent.location.href="'.$url.'";
		      }
			  else
			  {
			 	 window.location.href="'.$url.'";
			  }';
        JS::_write($js);

    }

    static function msg($msg='', $url='', $fun='')
    {
        $alert = '';
        if ( $msg ){
            $alert .= "parent.Msg.alert('{$msg}', '{$url}');";
        }
        if ( $url ){
            //$alert .= "setTimeout(\"top.location.href='$url'\",1000);";
        }
        if ( $fun ){
            $alert .= "parent.".$fun."();";
        }
        JS::_write($alert);
    }

    private function _top($parent)
    {
        $js = 'if(self!=top)
			  {
				  parent.'.$parent.';
		      }
			  else
			  {
			 	 '.$parent.';
			  }';
        return $js;
    }

    static public function close()
    {
        $js = 'window.close()';
        JS::_write($js);

    }

    static public function submit($form)
    {
        $js = $form.'.submit();';
        JS::_write($js);
    }

    static public function valid($field)
    {
        $js = 'if(self!=top)
			  {
				  parent.Valid.display("'.$field.'");
		      }
			  else
			  {
			 	 Valid.display("'.$field.'");
			  }';
        JS::_write($js);
    }
}