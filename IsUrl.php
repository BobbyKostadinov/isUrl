<?php
/**
 * Validate query strings
 * @author Bobby Kostadinov <b.a.kostadinov@gmail.com>
 * 
 * Usage: Just place this class in your /library/Validate and take advantage of this custom validator
 *
 */
class Validate_IsUrl extends Zend_Validate_Abstract
{

    const INVALID_URL = 'invalidUrl';
    const EXCEPTION = 'Exception';

    protected $_messageTemplates = array(
        self::EXCEPTION => "'%value%' is not a valid URL",
        self::INVALID_URL => "'%value%' is not a valid URL. Invalid string provided.",
    );

    public function isValid($value)
    {
        if (!is_string($value)) {
            $this->_error(self::INVALID_URL);
             return false;
        }

        $this->_setValue($value);
        
        try {
            //Zend_Uri will result in exception if schema is invalid
            $uriHttp = Zend_Uri_Http::fromString($value);
        } catch (Zend_Uri_Exception $e) {
            $this->_error(self::EXCEPTION);
            return false;
        }
        
        //Perform validation on all parts of the URL - host, query string etc
        if (false === $uriHttp->valid()) {
            $this->_error(self::INVALID_URL);
            return false;
        }
       
        return true;
    }
}
