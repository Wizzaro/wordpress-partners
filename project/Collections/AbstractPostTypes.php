<?php
namespace Wizzaro\Partners\Collections;

use Wizzaro\WPFramework\v1\AbstractSingleton; 

abstract class AbstractPostTypes extends AbstractSingleton {
    
    private $_post_types = array();
    
    public function add_post_type( $key, &$post_type ) {
        $this->_post_types[$key] = $post_type;
    }
    
    public function get_post_type( $key ) {
        if ( array_key_exists( $key, $this->_post_types ) ) {
            return $this->_post_types[$key];
        }
        
        return false;
    }
    
    public function get_post_types() {
        return $this->_post_types;
    }
    
    public function get_post_types_keys() {
        return array_keys( $this->_post_types );
    }
}