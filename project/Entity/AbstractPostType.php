<?php
namespace Wizzaro\Partners\Entity;

abstract class AbstractPostType {
    
    private $_settings = array();
    
    public function __construct( array $settings ) {
        $this->_settings = $settings;
    }
    
    public function get_setting( $key, $default = '' ) {
        if ( array_key_exists( $key, $this->_settings ) ) {
            return $this->_settings[$key];
        }
        
        return $default;
    }
}
