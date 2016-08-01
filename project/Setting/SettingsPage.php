<?php
namespace Wizzaro\Partners\Setting;

use Wizzaro\WPFramework\v1\Setting\AbstractSettingsPage;

class SettingsPage extends AbstractSettingsPage {
    
    protected function get_page_config() {
        return array(
            'page_title' => '',
            'menu_title' => '',
            'capability' => 'manage_options',
            'menu_slug' => ''
        );
    }
}