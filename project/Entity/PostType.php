<?php
namespace Wizzaro\Partners\Entity;

use  Wizzaro\WPFramework\v1\Setting\AbstractSettingsPage;

use Wizzaro\Partners\Option\PartnerData;

class PostType {
    
    private $_settings = array();
    
    private $_options = false;
    
    public function __construct( array $settings ) {
        $this->_settings = $settings;
    }
    
    public function get_setting( $key, $default = '' ) {
        if ( array_key_exists( $key, $this->_settings ) ) {
            return $this->_settings[$key];
        }
        
        return $default;
    }
    
    public function get_option_instance() {
        if ( ! $this->_options ) {
            $this->_options = new PartnerData( $this->get_setting( 'post_type' ) );
        }   
        
        return $this->_options;     
    }
}
