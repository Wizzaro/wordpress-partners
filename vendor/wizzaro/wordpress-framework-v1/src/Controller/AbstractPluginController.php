<?php
namespace Wizzaro\WPFramework\v1\Controller;

use Wizzaro\WPFramework\v1\Controller\AbstractController;

abstract class AbstractPluginController extends AbstractController {
    /*
    private $_main_file_patch;
    
    public function __construct( &$bootstrap ) {
        parent::__construct( $bootstrap );
        
        if ( is_admin() ) {
            $main_file_patch = $bootstrap->get_main_file_path();
            
            register_activation_hook( $main_file_patch , array( $this, 'plugin_activation' ) );
            register_deactivation_hook( $main_file_patch , array( $this, 'plugin_deactivation' ) );
            register_uninstall_hook( $main_file_patch , array( $this, 'plugin_delete' ) );
        }
    }
    
    public function plugin_activation() {
    }
    
    public function plugin_deactivation() {
    }
    
    public function plugin_delete() {
    }*/
}