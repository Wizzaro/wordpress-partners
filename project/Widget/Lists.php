<?php
namespace Wizzaro\Partners\Widget;

use Wizzaro\WPFramework\v1\Helper\View;

use \WP_Widget;

use Wizzaro\Partners\Config\PluginConfig;
use Wizzaro\Partners\Entity\ListPostType;

class Lists extends WP_Widget {
    
    private $_post_type;
    
    public function __construct( $id_base, $name, ListPostType $post_type ) {
        $this->_post_type = $post_type;
        parent::__construct( $id_base, $name );
    }
    
    // This is where the action happens
    public function widget( $args, $instance ) {
        
        if ( isset ( $instance['list_id'] ) ) {
            echo $args['before_widget'];
            echo do_shortcode( '[' . $this->_post_type->get_setting( 'shordcode' ) . ' id="' . $instance['list_id'] . '"]' );
            echo $args['after_widget'];
        }
    }
    
    public function update( $new_instance, $instance ) {
        if ( isset( $new_instance['list_id'] ) ) {
            $list = get_post( absint( $new_instance['list_id'] ) );
            
            if ( $list && ! strcasecmp( $list->post_type, $this->_post_type->get_setting( 'post_type' ) ) ) {
                $instance['list_id'] = $list->ID;                
            }
        }
        
        return $instance;
    }
    
    public function form( $instance ) {
        $instance = wp_parse_args( (array) $instance, array( 'list_id' => '' ) );
        
        $args = array(
            'posts_per_page' => -1,
            'post_type' => $this->_post_type->get_setting( 'post_type' ),
            'orderby' => 'title',
            'order' => 'ASC'
        );
        
        View::get_instance()->render_view_for_instance( PluginConfig::get_instance()->get_view_templates_path(), $this, 'form', array (
            'field_id' => $this->get_field_id( 'list_id' ),
            'field_name' => $this->get_field_name( 'list_id' ),
            'selected' => $instance['list_id'],
            'slides' => get_posts( $args )
        ) );
    }
}