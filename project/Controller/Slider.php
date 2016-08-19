<?php
namespace Wizzaro\Partners\Controller;

use Wizzaro\WPFramework\v1\Controller\AbstractPluginController;

use Wizzaro\Partners\Collections\PostTypes as PostTypesCollection;
use Wizzaro\Partners\Collections\SlidersPostTypes;
use Wizzaro\Partners\Entity\SliderPostType;

use Wizzaro\Partners\Component\Metabox\SliderSettings;
use Wizzaro\Partners\Component\Metabox\SliderShortcode;
use Wizzaro\Partners\Component\Metabox\SliderElements;

class Slider extends AbstractPluginController {
    
    public function init() {
        add_action( 'wizzaro_partners_after_register_post_types', array( $this, 'action_regiset_sliders_post_types' ) );
    }
    
    public function init_front() {
        //add_action( 'wizzaro_partners_sliders_after_register_post_type', array( $this, 'action_init_shordcode' ) );
        //add_shortcode( 'partners-slider', array( $this, 'render_shordcode_partners_slider' ) );
    }
    
    public function init_admin() {
        SliderElements::create();
        SliderShortcode::create();
        SliderSettings::create();
        
        add_action( 'wizzaro_partners_sliders_after_register_post_type', array( $this, 'action_init_columns_actions' ) );
        add_action( 'wizzaro_partners_sliders_after_register_post_types', array( $this, 'action_set_metaboxs_screens' ) );
    }
    
    public function action_regiset_sliders_post_types() {
        
        $languages_domain = $this->_config->get( 'languages', 'domain' );
        
        $args = array(
            'labels'=> array(
                'name'                  => __( 'Sliders ', $languages_domain ),
                'singular_name'         => __( 'Slider ', $languages_domain ),
                'menu_name'             => __( 'Sliders ', $languages_domain ),
                'add_new'               => __( 'Add Slider', $languages_domain ),
                'add_new_item'          => __( 'Add New Slider', $languages_domain ),
                'edit'                  => __( 'Edit Slider', $languages_domain ),
                'edit_item'             => __( 'Edit Slider', $languages_domain ),
                'new_item'              => __( 'New Slider', $languages_domain ),
                'not_found'             => __( 'No Sliders found', $languages_domain ),
                'not_found_in_trash'    => __( 'No Sliders found in trash', $languages_domain ),
            ),
            'public'              => false,
            'has_archive'         => false,
            'hierarchical'        => false,
            'supports'            => array( 'title' ),
            'show_ui'             => true,
            'show_in_menu'        => true,
            'show_in_nav_menus'   => false,
            'show_in_admin_bar'   => true,
            'can_export'          => true,
            'exclude_from_search' => false,
            'publicly_queryable'  => false,
            'capability_type'     => 'post',
            'rewrite'             => false,
            'query_var'           => false,
        );
        
        $sliders_post_types_keys = array();
        
        $slider_post_types_collection = SlidersPostTypes::get_instance();
        
        foreach( PostTypesCollection::get_instance()->get_post_types() as $post_type_key => $post_type ) {
            if ( $post_type->get_setting('use_sliders', false) ) {
                
                $sliders_post_type = new SliderPostType( array_merge( $post_type->get_setting( 'sliders_settings' ), array( 'elements_post_type' => $post_type_key ) ) );
                $slider_post_type_name = $sliders_post_type->get_setting( 'post_type' );
                
                register_post_type( $slider_post_type_name, array_merge( $args, array(
                    'show_in_menu' => 'edit.php?post_type=' . $post_type_key,
                ) ) );
                
                array_push( $sliders_post_types_keys, $slider_post_type_name );

                $slider_post_types_collection->add_post_type( $slider_post_type_name, $sliders_post_type ); 
                
                do_action( 'wizzaro_partners_sliders_after_register_post_type', $slider_post_type_name );
            }
        }
        
        do_action( 'wizzaro_partners_sliders_after_register_post_types', $sliders_post_types_keys );
    }

    public function action_init_columns_actions( $post_type ) {
        add_filter( 'manage_' . $post_type . '_posts_columns', array( $this, 'filter_reservation_data_columns' ) );
        add_action( 'manage_' . $post_type . '_posts_custom_column', array( $this, 'action_render_columns' ), 2 );
    }

    public function filter_reservation_data_columns( $existing_columns ) {
        
        $columns = array();
        $columns['cb'] = $existing_columns['cb'];
        $columns['title'] = $existing_columns['title'];
        $columns['shortcode'] = __( 'Shortcode', $this->_config->get( 'languages', 'domain' ) );
        $columns['date'] = $existing_columns['date'];

        return $columns;
    }

    public function action_render_columns( $column ) {
        global $post;

        if ( ! strcasecmp( $column, 'shortcode' ) && ! wp_is_post_revision( $post->ID ) ) {
            ?>
            <code>[<?php echo SlidersPostTypes::get_instance()->get_post_type( $post->post_type )->get_setting( 'shordcode' ); ?> id="<?php echo $post->ID ?>"]</code>
            <?php
        }
    }

    public function action_set_metaboxs_screens( $post_types ) {
        SliderElements::get_instance()->set_config( array( 'screen' => $post_types ) );
        SliderShortcode::get_instance()->set_config( array( 'screen' => $post_types ) );
        SliderSettings::get_instance()->set_config( array( 'screen' => $post_types ) );
    }
}