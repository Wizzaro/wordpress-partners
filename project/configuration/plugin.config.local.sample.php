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
        'slug' => 'partners',
        'labels'=> array(
            'name' => __('Partners', 'wizzaro-partners'),
            'singular_name' => __('Partner', 'wizzaro-partners'),
            'rewrite' => array('slug' => 'partners'),
            'menu_name' => __('Partners', 'wizzaro-partners'),
            'add_new'            => __( 'Add Partner', 'wizzaro-partners' ),
            'add_new_item'       => __( 'Add New Partner', 'wizzaro-partners' ),
            'edit'               => __( 'Edit Partner', 'wizzaro-partners' ),
            'edit_item'          => __( 'Edit Partner', 'wizzaro-partners' ),
            'new_item'           => __( 'New Partner', 'wizzaro-partners' ),
            'not_found'          => __( 'No Partners found', 'wizzaro-partners' ),
            'not_found_in_trash' => __( 'No Partners found in trash', 'wizzaro-partners' ),
        ),
        'public' => true,
        'use_sliders' => true,
        'use_lists' => true,
        'admin_menu_icon' => 'dashicons-groups'
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
            'slug' => 'custom_post_type',
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
             * Defines whether you want to use sliders on page. 
             * If yes then add slider settings in admin panel, support shordcodes and widgets
             * Required: yes
             */
            'use_sliders' => true,
            /*
             * Defines whether you want to use partners list (not a posts list but custom list when you define partners order, position etc. ) on page. 
             * If yes then add list settings in admin panel, support shordcodes and widgets
             * Required: yes
             */
            'use_lists' => true,
            /*
             * Inform with icon must be show in admin menu
             * Required: no
             */
            'admin_menu_icon' => 'dashicons-groups'
        ),
        // ... - second post type
    )
);