<?php
namespace Wizzaro\Partners\Collections;

use Wizzaro\WPFramework\v1\AbstractSingleton; 
use Wizzaro\Partners\Entity\PostType;

class PostTypes extends AbstractSingleton {
    
    private $_post_types = array();
    
    public function add_post_type( $key, PostType &$post_type ) {
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
}