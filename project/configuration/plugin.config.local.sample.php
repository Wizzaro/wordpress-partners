<?php
/*
 * This is sample configuration for wizzaro partners plugin
 * In this place you can replace original configuration, add new post types etc.
 * Add this configuration to ../wp-content/wizzaro-partners/plugin.config.local.php file
 */
namespace Wizzaro\Partners;

return array(
    /*
     * This variable allows decide whether use the default post types (Partners) with custom post types which are defined in "post_types" settings variable in this file
     */
    'use_default_post_type' => true, //or false
    /*
     * This variable gives you the opportunity to replace default post type settings.
     * If you don't wont replace any settings or you set "use_default_post_type" to false - skip this setting.
     */
    'default_post_type' => array(
        'post_type' => 'wizzaro-partners',
        'slug' => 'partners',
        'labels'=> array(
            'name'                  => __( 'Partners', 'wizzaro-partners' ),
            'singular_name'         => __( 'Partner', 'wizzaro-partners' ),
            'menu_name'             => __( 'Partners', 'wizzaro-partners' ),
            'add_new'               => __( 'Add Partner', 'wizzaro-partners' ),
            'add_new_item'          => __( 'Add New Partner', 'wizzaro-partners' ),
            'edit'                  => __( 'Edit Partner', 'wizzaro-partners' ),
            'edit_item'             => __( 'Edit Partner', 'wizzaro-partners' ),
            'new_item'              => __( 'New Partner', 'wizzaro-partners' ),
            'not_found'             => __( 'No Partners found', 'wizzaro-partners' ),
            'not_found_in_trash'    => __( 'No Partners found in trash', 'wizzaro-partners' ),
            'featured_image'        => __( 'Logo', 'wizzaro-partners' ),
            'set_featured_image'    => __( 'Set logo', 'wizzaro-partners' ),
            'remove_featured_image' => __( 'Remove logo', 'wizzaro-partners' ),
            'use_featured_image'    => __( 'Use as logo', 'wizzaro-partners' ),
        ),
        'public' => true,
        'use_sliders' => true,
        'sliders_settings' => array(
            'post_type' => 'wizzaro-partners-s',
            'shordcode' => 'wizzaro-partners-slider',
            'widget' => array(
                'id' => 'wizzaro-partners-slider-widget',
                'name' => __( 'Partners Slider', 'wizzaro-partners' )
            )
        ),
        'use_lists' => true,
        'lists_settings' => array(
            'post_type' => 'wizzaro-partners-l',
            'shordcode' => 'wizzaro-partners-list',
            'widget' => array(
                'id' => 'wizzaro-partners-list-widget',
                'name' => __( 'Partners List', 'wizzaro-partners' )
            )
        ),
        'admin_menu_icon' => 'dashicons-groups',
        'setting_page_config' => array(
            'page_title' => __('Partners Settings', 'wizzaro-partners'),
            'menu_title' => __('Partners Settings', 'wizzaro-partners'),
            'menu_slug' => 'wizzaro-partners-settings'
        ),
        'settings_tabs' => array(
            'partner_data' => array(
                'name' => __( 'Partner Data', 'wizzaro-partners' ),
                'slug' => 'partner-data'
            )
        ),
        'supports' => array(
            'logo' => true,
            'content' => true,
        ),
        'partner_data_attributes' => array(
            'address' => array(
                'use' => true,
                'type' => 'address',
                'title' => __('Address', 'wizzaro-partners'),
                'placeholder' =>  __('Add address', 'wizzaro-partners')
            ),
            'phone' => array(
                'use' => true,
                'type' => 'phone',
                'title' => __('Phone', 'wizzaro-partners'),
                'placeholder' =>  __('Add phone', 'wizzaro-partners')
            ),
            'email' => array(
                'use' => true,
                'type' => 'email',
                'title' => __('E-mail', 'wizzaro-partners'),
                'placeholder' =>  __('Add e-mail', 'wizzaro-partners')
            ),
            'website_url' => array(
                'use' => true,
                'type' => 'url',
                'title' => __('Website URL', 'wizzaro-partners'),
                'placeholder' =>  __('Add website URL', 'wizzaro-partners')
            ),
        ),
    ),
    /*
     * This variable gives you the opportunity to create your own post types for this plugin.
     * If you don't wont create custom post types - skip this setting.
     * IMPORTANT: If you wont use onlu one post type and name of this in not "Partners" but "Sponsors" (for example) then better idea is replace "default_post_type" setting.
     */
    'post_types' => array(
        'custom_post_type_key' => array(
            /*
             * Customize the permalink structure slug. Default is array key ("custom_post_type_key" - in this example)
             * Required: no
             */
            'slug' => 'partners',
            /*
             * An array of labels for this post type defined by wordpress standard.
             * More info: https://codex.wordpress.org/Function_Reference/register_post_type#labels (section "Arguments" -> "labels") 
             * Required: yes
             */
            'labels'=> array(),
            /*
             * Define is this post type will be visible for readers. (posts list and post page will be visible)
             * Required: yes
             */
            'public' => true,
            /*
             * Define which supports must be use in partner post type
             * Required: yes
             */
            'supports' => array(
                'logo' => true,
                'content' => true,
            ),
            /*
             * Define partner attributes list
             * Required: yes
             */
            'partner_data_attributes' => array(
                'my_attribute' => array(
                    //Define whether the attribute is to be used (if false then this attribute will be no show in admin panel and front view) 
                    'use' => true,
                    /**
                     * Your attribute type (text|address|phone|email|url)
                     * Selected type is important when editing and display attribute data
                     */
                    'type' => 'text',
                    //Define attribute title
                    'title' => '',
                    //Define attribute placeholder for adnim panel field
                    'placeholder' => ''
                ),
            ),
            /*
             * Defines whether you want to use sliders on page. 
             * If yes then add slider settings in admin panel, support shordcodes and widgets
             * Required: yes
             */
            'use_sliders' => true,
            /*
             * Defines sliders settings. 
             * If setting "use_sliders" is true then you must define sliders settongs | If setting "use_sliders" is false then skip this setting
             * Required: no
             */
            'sliders_settings' => array(
                //define post type key for sliders posts (max. 20 characters, cannot contain capital letters or spaces)
                'post_type' => '',
                //define shordcode name for sliders which use for build shordcode string ex. [defined-shordcode-name id="slider_post_id"] 
                'shordcode' => 'defined-shordcode-name',
                //if you wond render sliders by widget, you must defined this option - if not delete this option
                'widget' => array(
                    'id' => '',
                    'name' => ''
                )
            ),
            /*
             * Defines whether you want to use partners list (not a posts list but custom list when you define partners order, position etc. ) on page. 
             * If yes then add list settings in admin panel, support shordcodes and widgets
             * Required: yes
             */
            'use_lists' => true,
            /*
             * Defines lists settings. 
             * If setting "use_lists" is true then you must define sliders settongs | If setting "use_lists" is false then skip this setting
             * Required: no
             */
            'lists_settings' => array(
                 //define post type key for lists posts (max. 20 characters, cannot contain capital letters or spaces)
                'post_type' => '',
                //define shordcode name for lists which use for build shordcode string ex. [defined-shordcode-name id="list_post_id"] 
                'shordcode' => 'defined-shordcode-name',
                //if you wond render lists by widget, you must defined this option - if not delete this option
                'widget' => array(
                    'id' => '',
                    'name' => ''
                )
            ),
            /*
             * Inform with icon must be show in admin menu
             * Required: no
             */
            'admin_menu_icon' => 'dashicons-groups',
            /*
             * Defines setting page config. 
             * If setting "public" is true then you must define setting page config | If setting "public" is false then skip this setting
             * Required: no
             */
            'setting_page_config' => array(
                'page_title' => '',
                'menu_title' => '',
                'menu_slug' => ''
            ),
            /*
             * Defines partner data setting page tab config. 
             * If setting "public" is true then you must define setting page config | If setting "public" is false then skip this setting
             * Required: no
             */
            'settings_tabs' => array(
                'partner_data' => array(
                    'name' => '',
                    'slug' => ''
                )
            )
        ),
        // ... - second post type
    )
);