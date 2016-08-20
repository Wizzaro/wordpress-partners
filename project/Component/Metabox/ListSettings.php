<?php
namespace Wizzaro\Partners\Component\Metabox;

use Wizzaro\WPFramework\v1\Component\Metabox\AbstractMetabox;
use Wizzaro\WPFramework\v1\Helper\Filter;
use Wizzaro\WPFramework\v1\Helper\View;

use Wizzaro\Partners\Config\PluginConfig;

use Wizzaro\Partners\Entity\PostMeta\ListSettings as ListSettingsEntity;

class ListSettings extends AbstractMetabox {
    
    private $_config;
    
    public function set_config( array $config ) {
        $this->_config = array_merge( $this->_get_metabox_config(), $config );
    }
    
    protected function _get_metabox_config() {
        
        if ( ! $this->_config ) {
            $this->_config = array(
                'id' => 'wizzaro-partners-list-settings',
                'title' => __( 'Settings', PluginConfig::get_instance()->get( 'languages', 'domain' ) ),
                'screen' => array(),
                'context' => 'side',
            );
        }
        
        return $this->_config;
    }
    
    public function render( $post ) {

        View::get_instance()->render_view_for_instance( PluginConfig::get_instance()->get_view_templates_path(), $this, 'metabox', array(
            'post' => $post,
            'languages_domain' => PluginConfig::get_instance()->get( 'languages', 'domain' ),
            'list_settings' => new ListSettingsEntity( $post->ID ) 
        ) );
    }
    
    public function save( $post_id, $post ) {

        if( 
            ! is_admin()
            || wp_is_post_revision( $post_id )
            || ! isset ( $_POST['wizzaro_partners_list_settings'] )  
            || ! isset ( $_POST['wizzaro_partners_list_settings_edit'] ) 
            || ! wp_verify_nonce( $_POST['wizzaro_partners_list_settings_edit'], 'wizzaro_partners_list_settings_edit_nounce' ) ) {
            return;
        }
        
        if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) {
            return $post->ID;   
        }
        
        $settings = new ListSettingsEntity( $post->ID );
        $new_settings = $_POST['wizzaro_partners_list_settings'];
        
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
        }

        $settings->save();
    }
}
