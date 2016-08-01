<?php
namespace Wizzaro\Partners\Option;

use Wizzaro\WPFramework\v1\Option\AbstractOption;

class PartnerData extends AbstractOption {
    
    private $_prefix = '';
    
    public function __construct( $prefix ) {
        
        $this->_prefix = $prefix;
        
        $this->_options = array (
            $this->_prefix . '-partner-data' => array (
                'display_place' => 'before',
            )
        );
        
        parent::__construct();
    }
    
    public function get_prefix() {
        return $this->_prefix;
    }
}