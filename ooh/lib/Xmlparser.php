<?php
/**
 *
 * xmlparser class
 *
 * @author andsky<andsky888@gmail.com>
 * @version 1.0 2013-9-10
 */

class Xmlparser {

    private static $_instance;

    var $xml      = NULL;
    var $data     = NULL;
    var $error    = NULL;
    var $mapped   = NULL;



    /**
     * Xmlparser instance
     * @return Xmlparser instance
     */
    public static function instance()
    {
        if (self::$_instance == null) {
            self::$_instance = new self;
        }
        return self::$_instance;
    }

    public function __construct()
    {
        $this->xml = xml_parser_create();
        xml_set_object($this->xml, $this);
        xml_set_element_handler($this->xml, 'startHandler', 'endHandler');
        xml_set_character_data_handler($this->xml, 'dataHandler');
    }

    function parser($data)
    {

        $this->data = NULL;
        $parse = xml_parse($this->xml, $data);
        if (!$parse) {
            throw new Http500Exceptions(sprintf("XML error: %s at line %d",
                        xml_error_string(xml_get_error_code($this->xml)),
                        xml_get_current_line_number($this->xml)));
            xml_parser_free($this->xml);
        }


        return true;
    }

    function startHandler($parser, $name, $attributes)
    {
        $data['name'] = $name;
        if ($attributes) { $data['attributes'] = $attributes; }
        $this->data[] = $data;
    }

    function dataHandler($parser, $data) {
        //Trims everything except for spaces
        if($data = trim($data, "\t\n\r\0\x0B")) {
            $test = str_replace(' ', '', $data);
            if (empty($test)) {
                $data = null;
            }
            $index = count($this->data) - 1;
            if(isset($this->data[$index]['content'])) {
                $this->data[$index]['content'] .= $data;
            } else {
                $this->data[$index]['content'] = $data;
            }
        }
    }

    function endHandler($parser, $name)
    {
        if (count($this->data) > 1) {
            $data = array_pop($this->data);
            $index = count($this->data) - 1;
            $this->data[$index]['child'][] = $data;
        }
    }

    function format()
    {
        return $this->subformat($this->data[0]);
    }


    function subformat($foo, $hold=null)
    {
        if (isset($foo['child'])) {
            $content = array();
            foreach ($foo['child'] as $bar) {
                $result = $this->subformat($bar);
                if (isset($bar['child'])) {
                    list($key,$value) = each($result);
                    if (count($value) > 1) {
                        $content[$key][] = $value;
                    } else {
                        $content[$key] = $value;
                    }
                } else {
                    $content = $content + $result;
                }
            }

            return array($foo['name']=>$content);
        } else {
            return array($foo['name']=>$foo['content']);
        }
    }

}