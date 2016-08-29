<?php
namespace Wizzaro\Partners\Widget;

use Wizzaro\WPFramework\v1\Helper\View;

use \WP_Widget;

use Wizzaro\Partners\Config\PluginConfig;
use Wizzaro\Partners\Entity\SliderPostType;

class Slider extends WP_Widget {
    
    private $_post_type;
    
    public function __construct( $id_base, $name, SliderPostType $post_type ) {
        $this->_post_type = $post_type;
        parent::__construct( $id_base, $name );
    }
    
    // This is where the action happens
    public function widget( $args, $instance ) {
        
        if ( isset ( $instance['slider_id'] ) ) {
            echo $args['before_widget'];
            echo do_shortcode( '[' . $this->_post_type->get_setting( 'shordcode' ) . ' id="' . $instance['slider_id'] . '"]' );
            echo $args['after_widget'];
        }
    }
    
    public function update( $new_instance, $instance ) {
        if ( isset( $new_instance['slider_id'] ) ) {
            $slider = get_post( absint( $new_instance['slider_id'] ) );
            
            if ( $slider && ! strcasecmp( $slider->post_type, $this->_post_type->get_setting( 'post_type' ) ) ) {
                $instance['slider_id'] = $slider->ID;                
            }
        }
        
        return $instance;
    }
    
    public function form( $instance ) {
        $instance = wp_parse_args( (array) $instance, array( 'slider_id' => '' ) );
        
        $args = array(
            'posts_per_page' => -1,
            'post_type' => $this->_post_type->get_setting( 'post_type' ),
            'orderby' => 'title',
            'order' => 'ASC'
        );
        
        View::get_instance()->render_view_for_instance( PluginConfig::get_instance()->get_view_templates_path(), $this, 'form', array (
            'field_id' => $this->get_field_id( 'slider_id' ),
            'field_name' => $this->get_field_name( 'slider_id' ),
            'selected' => $instance['slider_id'],
            'slides' => get_posts( $args )
        ) );
    }
}