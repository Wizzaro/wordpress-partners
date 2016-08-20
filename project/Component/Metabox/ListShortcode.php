<?php
namespace Wizzaro\Partners\Component\Metabox;

use Wizzaro\WPFramework\v1\Component\Metabox\AbstractMetabox;
use Wizzaro\WPFramework\v1\Helper\View;

use Wizzaro\Partners\Config\PluginConfig;
use Wizzaro\Partners\Collections\ListsPostTypes;

class ListShortcode extends AbstractMetabox {
    
    private $_config;
    
    public function set_config( array $config ) {
        $this->_config = array_merge( $this->_get_metabox_config(), $config );
    }
    
    protected function _get_metabox_config() {
        
        if ( ! $this->_config ) {
            $this->_config = array(
                'id' => 'wizzaro-partners-list-shortcode',
                'title' => __( 'Shortcode', PluginConfig::get_instance()->get( 'languages', 'domain' ) ),
                'screen' => array(),
                'context' => 'side',
            );
        }
        
        return $this->_config;
    }
    
    public function render( $post ) {

        View::get_instance()->render_view_for_instance( PluginConfig::get_instance()->get_view_templates_path(), $this, 'metabox', array(
            'shortcode' => ListsPostTypes::get_instance()->get_post_type( $post->post_type )->get_setting( 'shordcode' ),
            'post_id' => $post->ID
        ) );
    }
}
