<?php
namespace Wizzaro\Partners\Setting\OptionFormTab;

use Wizzaro\WPFramework\v1\Setting\OptionFormTab\AbstractOptionFormTab;
use Wizzaro\WPFramework\v1\Helper\Filter;

use Wizzaro\Partners\Config\PluginConfig;


class PartnerData extends AbstractOptionFormTab {
    
    private $_tab_config = array();
    
    public function __construct( &$setting_page_instance, &$option_instance, array $_tab_config ) {
        $this->_tab_config = $_tab_config;
        parent::__construct( $setting_page_instance, $option_instance );
    }
    
    protected function _get_tab_config() {
        return $this->_tab_config;
    }
    
    protected function _get_settings_config() {
        $language_domain = PluginConfig::get_instance()->get( 'languages', 'domain' );
        
        return array(
            'multiple' => true,
            'settings' => array (
                $this->get_option_instacne()->get_prefix() . '-partner-data' => array(
                    'callback' => array( $this, 'validate_options' ),
                    'sections' => array(
                        'display' => array(
                            'title' => __( 'Display', $language_domain ),
                            'callback' => '',
                            'fields' => array(
                                'display_place' => array(
                                    'title' => __( 'Place for display data', $language_domain ) . ':',
                                    'type' => 'select',
                                    'args' => array(
                                        'select_options' => array(
                                            'before' => __( 'Before content', $language_domain ),
                                            'after' => __( 'After content', $language_domain ),
                                            'shordcode' => __( 'Use only shordcode', $language_domain ) . ' [' . $this->get_option_instacne()->get_prefix() . '-data]'
                                        ),
                                        'description' => __( 'A place in which to display data', $language_domain )
                                    )
                                ),
                            )
                        )
                    )
                )
            )
        );
    }
    
    public function validate_options( $input ) {
        //die();
        //var_dump($input); die();
        return $input;
    }
}