<?php
namespace Wizzaro\Partners\Controller;

use Wizzaro\WPFramework\v1\Controller\AbstractPluginController;

use Wizzaro\Partners\Collections\PostTypes as PostTypesCollection;
use Wizzaro\Partners\Collections\ListsPostTypes;
use Wizzaro\Partners\Entity\ListPostType;

use Wizzaro\Partners\Component\Metabox\ListSettings;
use Wizzaro\Partners\Component\Metabox\ListShortcode;
use Wizzaro\Partners\Component\Metabox\ListElements;

use Wizzaro\Partners\Entity\PostMeta\ListSettings as ListSettingsEntity;
use Wizzaro\Partners\Entity\PostMeta\ListElements as ListElementsEntity;

use Wizzaro\Partners\Widget\Lists as ListWidget;

class Lists extends AbstractPluginController {
    
    public function init() {
        add_action( 'wizzaro_partners_after_register_post_types', array( $this, 'action_regiset_lists_post_types' ) );
        add_action( 'widgets_init', array( $this, 'action_register_widgets' ) );
    }
    
    public function init_front() {
        add_action( 'wizzaro_partners_lists_after_register_post_type', array( $this, 'action_init_shordcode' ), 10, 2 );
        add_action( 'wp_enqueue_scripts', array( $this, 'action_enqueue_style' ) );
    }
    
    public function init_admin() {
        add_action( 'wizzaro_partners_lists_after_register_post_type', array( $this, 'action_init_columns_actions' ) );
        add_action( 'wizzaro_partners_lists_after_register_post_types', array( $this, 'action_set_metaboxs_screens' ) );
        
        add_action( 'save_post', array( $this, 'reset_shordcode_view_cache' ), 10, 2 );
    }
    
    public function action_regiset_lists_post_types() {
        
        $languages_domain = $this->_config->get( 'languages', 'domain' );
        
        $args = array(
            'labels'=> array(
                'name'                  => __( 'Lists ', $languages_domain ),
                'singular_name'         => __( 'List ', $languages_domain ),
                'menu_name'             => __( 'Lists ', $languages_domain ),
                'add_new'               => __( 'Add List', $languages_domain ),
                'add_new_item'          => __( 'Add New List', $languages_domain ),
                'edit'                  => __( 'Edit List', $languages_domain ),
                'edit_item'             => __( 'Edit List', $languages_domain ),
                'new_item'              => __( 'New List', $languages_domain ),
                'not_found'             => __( 'No Lists found', $languages_domain ),
                'not_found_in_trash'    => __( 'No Lists found in trash', $languages_domain ),
            ),
            'public'              => false,
            'has_archive'         => false,
            'hierarchical'        => false,
            'supports'            => array( 'title' ),
            'show_ui'             => true,
            'show_in_menu'        => true,
            'show_in_nav_menus'   => false,
            'can_export'          => true,
            'exclude_from_search' => false,
            'publicly_queryable'  => false,
            'capability_type'     => 'post',
            'rewrite'             => false,
            'query_var'           => false,
        );
        
        $lists_post_types_keys = array();
        
        $lists_post_types_collection = ListsPostTypes::get_instance();
        
        foreach( PostTypesCollection::get_instance()->get_post_types() as $post_type_key => $post_type ) {
            if ( $post_type->get_setting( 'use_lists', false ) ) {
                
                $lists_post_type = new ListPostType( array_merge( $post_type->get_setting( 'lists_settings' ), array( 'elements_post_type' => $post_type_key ) ) );
                $lists_post_type_name = $lists_post_type->get_setting( 'post_type' );
                
                register_post_type( $lists_post_type_name, array_merge( $args, array(
                    'show_in_menu' => 'edit.php?post_type=' . $post_type_key,
                ) ) );
                
                array_push( $lists_post_types_keys, $lists_post_type_name );

                $lists_post_types_collection->add_post_type( $lists_post_type_name, $lists_post_type ); 
                
                do_action( 'wizzaro_partners_lists_after_register_post_type', $lists_post_type_name, $lists_post_type );
            }
        }
        
        do_action( 'wizzaro_partners_lists_after_register_post_types', $lists_post_types_keys );
    }

    public function action_register_widgets() {
        foreach ( ListsPostTypes::get_instance()->get_post_types() as $post_type ) {
            $widget_settings = $post_type->get_setting( 'widget', false );
            if ( $widget_settings ) {
                register_widget( new ListWidget( $widget_settings['id'], $widget_settings['name'], $post_type ) );
            }
        }
    }

    //----------------------------------------------------------------------------------------------------
    // Functions for front
    
    public function action_enqueue_style() {
        if ( apply_filters( 'wizzaro_partners_list_enqueue_style', true ) ) {
            wp_enqueue_style( 'wizzaro-partners-list-css', $this->_config->get_css_url() . 'list.css', array(), '1.0.0' );
        }
    }
    
    public function action_init_shordcode( $post_type, $post_type_obj ) {
        add_shortcode( $post_type_obj->get_setting( 'shordcode' ), array( $this, 'render_shordcode') );
    }

    public function render_shordcode( $attrs ) {
        $view = '';
        
        if ( isset( $attrs['id'] ) ) {
            $post = get_post( $attrs['id'] );
            
            if ( $post ) {
                $view = wp_cache_get( 'wizzaro_partners_list_shordcode', $post->post_type . '-' . $post->ID );
            
                if ( ! $view ) {
                    $elements = new ListElementsEntity( $post->ID );
                    
                    $view_data = array(
                        'post' => $post,
                        'settings' => new ListSettingsEntity( $post->ID ),
                        'elements' => $elements->getElements()
                    );
                    
                    if ( $this->is_themes_view_exist( 'shordcode-list-' . $post->post_type ) ) {
                        $view = $this->get_themes_view( 'shordcode-list-' . $post->post_type, $view_data );
                    } elseif ( $this->is_themes_view_exist( 'shordcode-list' ) ) {
                        $view = $this->get_themes_view( 'shordcode-list', $view_data );
                    } else {
                        $view = $this->get_view( 'shordcode-list', $view_data );
                    }
                         
                    wp_cache_set( 'wizzaro_partners_list_shordcode', $view , $post->post_type . '-' . $post->ID );
                }
            }
        }
        
        return $view;
    }

    //----------------------------------------------------------------------------------------------------
    // Functions for admin
    
     public function reset_shordcode_view_cache( $post_id, $post ) {
        if ( wp_is_post_revision( $post_id ) ) {
            return;
        }
        
        $post_type = ListsPostTypes::get_instance()->get_post_type( $post->post_type );
        
        if ( $post_type ) {
            
            $elements = new ListElementsEntity( $post->ID );
                    
            $view_data = array(
                'post' => $post,
                'settings' => new ListSettingsEntity( $post->ID ),
                'elements' => $elements->getElements()
            );
            
            if ( $this->is_themes_view_exist( 'shordcode-list' ) ) {
                $view = $this->get_themes_view( 'shordcode-list', $view_data );
            } else {
                $view = $this->get_view( 'shordcode-list', $view_data );
            }
                 
            wp_cache_set( 'wizzaro_partners_list_shordcode', $view , $post->post_type . '-' . $post->ID );
        }
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
            <code>[<?php echo ListsPostTypes::get_instance()->get_post_type( $post->post_type )->get_setting( 'shordcode' ); ?> id="<?php echo $post->ID ?>"]</code>
            <?php
        }
    }

    public function action_set_metaboxs_screens( $post_types ) {
        if ( count ( $post_types ) > 0 ) {
            ListElements::create()->set_config( array( 'screen' => $post_types ) );
            ListShortcode::create()->set_config( array( 'screen' => $post_types ) );
            ListSettings::create()->set_config( array( 'screen' => $post_types ) );
        }
    }
}