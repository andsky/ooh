<?php
/**
 *
 * exception class
 *
 * @author andsky<andsky888@gmail.com>
 * @version 1.0 2013-9-10
 */
class Exceptions extends Exception{


    function __construct( $message, $code = 0, $extra = false ){
        parent::__construct($message, $code);
    }


    function __destruct(){

    }
}

class HttpExceptions extends Exceptions{
    public $httpCode;

    function __construct( $message, $code = 0, $extra = false ){
        $this->httpCode = $code;
        parent::__construct($message, $code);
    }
}

class Http500Exceptions extends HttpExceptions{

    function __construct( $message ){
        parent::__construct($message, 500);
    }
}

class Http503Exceptions extends HttpExceptions{

    function __construct( $message ){
        parent::__construct($message, 503);
    }
}

class Http404Exceptions extends HttpExceptions{

    function __construct( $message ){
        parent::__construct($message, 404);
    }
}

class Http403Exceptions extends HttpExceptions{

    function __construct( $message ){
        parent::__construct($message, 403);
    }
}