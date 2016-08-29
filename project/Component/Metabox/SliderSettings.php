<?php
namespace Wizzaro\Partners\Component\Metabox;

use Wizzaro\WPFramework\v1\Component\Metabox\AbstractMetabox;
use Wizzaro\WPFramework\v1\Helper\Filter;
use Wizzaro\WPFramework\v1\Helper\Validator;
use Wizzaro\WPFramework\v1\Helper\View;

use Wizzaro\Partners\Config\PluginConfig;

use Wizzaro\Partners\Entity\PostMeta\SliderSettings as SliderSettingsEntity;
use Wizzaro\Partners\Collections\SlidersPostTypes;

class SliderSettings extends AbstractMetabox {
    
    private $_config;
    
    public function set_config( array $config ) {
        $this->_config = array_merge( $this->_get_metabox_config(), $config );
    }
    
    protected function _get_metabox_config() {
        
        if ( ! $this->_config ) {
            $this->_config = array(
                'id' => 'wizzaro-partners-slider-settings',
                'title' => __( 'Settings', PluginConfig::get_instance()->get( 'languages', 'domain' ) ),
                'screen' => array(),
                'context' => 'side',
                //'priority' => 'normal'
            );
        }
        
        return $this->_config;
    }
    
    public function render( $post ) {

        View::get_instance()->render_view_for_instance( PluginConfig::get_instance()->get_view_templates_path(), $this, 'metabox', array(
            'post' => $post,
            'languages_domain' => PluginConfig::get_instance()->get( 'languages', 'domain' ),
            'slider_settings' => new SliderSettingsEntity( $post->ID ) 
        ) );
    }
    
    public function save( $post_id, $post ) {

        if( 
            ! is_admin()
            || wp_is_post_revision( $post_id )
            || ! isset ( $_POST['wizzaro_partners_slider_settings'] )  
            || ! isset ( $_POST['wizzaro_partners_slider_settings_edit'] ) 
            || ! wp_verify_nonce( $_POST['wizzaro_partners_slider_settings_edit'], 'wizzaro_partners_slider_settings_edit_nounce' ) ) {
            return;
        }
        
        if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) {
            return $post->ID;   
        }
        
        $settings = new SliderSettingsEntity( $post->ID );
        $new_settings = $_POST['wizzaro_partners_slider_settings'];
        
        if ( is_array( $new_settings ) ) {
            $filter_instance = Filter::get_instance();

            if ( array_key_exists( 'header', $new_settings ) ) {
                $settings->header = $filter_instance->filter_text( $new_settings['header'] );
            }
            
            if ( array_key_exists( 'line_amount', $new_settings ) ) {
                $line_amount = $filter_instance->filter_int( $new_settings['line_amount'] );
                
                if ( is_int( $line_amount ) && $line_amount >= 1 && $line_amount <= 100 ) {
                    $settings->line_amount = $line_amount;
                } else {
                    $settings->line_amount = 6;
                }
            }
            
            if ( array_key_exists( 'transition_speed', $new_settings ) ) {
                $transition_speed = $filter_instance->filter_int( $new_settings['transition_speed'] );

                if ( is_int( $transition_speed ) && $transition_speed >= 0 ) {
                    $settings->transition_speed = $transition_speed;
                } else {
                    $settings->transition_speed = 2000;
                }
            }
            
            if ( array_key_exists( 'pause_betwen_transition', $new_settings ) ) {
                $pause_betwen_transition = $filter_instance->filter_int( $new_settings['pause_betwen_transition'] );
                
                if (is_int( $pause_betwen_transition ) && $pause_betwen_transition >= 0 ) {
                    $settings->pause_betwen_transition = $pause_betwen_transition;
                } else {
                    $settings->pause_betwen_transition = 1000;
                }
            }
            
            if ( array_key_exists( 'pause_on_hover', $new_settings ) ) {
                if ( ! strcasecmp( $new_settings['pause_on_hover'], '1' ) ) {
                    $settings->pause_on_hover = 1;
                } else {
                    $settings->pause_on_hover = 0;
                }
            }
        }

        $settings->save();
    }
}
