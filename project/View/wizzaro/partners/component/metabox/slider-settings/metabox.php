<?php
wp_nonce_field('wizzaro_partners_slider_settings_edit_nounce', 'wizzaro_partners_slider_settings_edit');
?>
<div class="panel-wrap">
    <div class="panel">
        <?php
        //--------------------------------------------------------------------------------------------------------------
        ?>
        <ul>
            <li>
                <strong><?php _e( 'Header', $view_data['languages_domain'] ); ?>:</strong>
            </li>
            <li>
                <input class="large-text" name="wizzaro_partners_slider_settings[header]" type="text" value="<?php echo esc_attr( $view_data['slider_settings']->header ); ?>" placeholder="<?php _e( 'Add header', $view_data['languages_domain'] ); ?>">
            </li>
        </ul>
        <?php
        //--------------------------------------------------------------------------------------------------------------
        if ( apply_filters( 'wizzaro_partners_slider_use_amount', true, $view_data['post'] ) ) {
            ?>
            <ul>
                <li>
                    <strong><?php _e( 'Amount in line', $view_data['languages_domain'] ); ?>:</strong>
                </li>
                <li>
                    <input class="large-text" name="wizzaro_partners_slider_settings[line_amount]" type="number" min="1" max="100" value="<?php echo esc_attr( $view_data['slider_settings']->line_amount ); ?>" placeholder="6">
                </li>
            </ul>
            <?php
        }
        //--------------------------------------------------------------------------------------------------------------
        ?>
        <ul>
            <li>
                <strong><?php _e( 'Transition speed (ms)', $view_data['languages_domain'] ); ?>:</strong>
            </li>
            <li>
                <input class="large-text" name="wizzaro_partners_slider_settings[transition_speed]" type="number" min="0" value="<?php echo esc_attr( $view_data['slider_settings']->transition_speed ); ?>" placeholder="2000">
            </li>
        </ul>
        <?php
        //--------------------------------------------------------------------------------------------------------------
        ?>
        <ul>
            <li>
                <strong><?php _e( 'Pause betwen transition (ms)', $view_data['languages_domain'] ); ?>:</strong>
            </li>
            <li>
                <input class="large-text" name="wizzaro_partners_slider_settings[pause_betwen_transition]" type="number" min="0" value="<?php echo esc_attr( $view_data['slider_settings']->pause_betwen_transition ); ?>" placeholder="1000">
            </li>
        </ul>
        <?php
        //--------------------------------------------------------------------------------------------------------------
        ?>
        <ul>
            <li>
                <strong><?php _e( 'Pause on hover slider', $view_data['languages_domain'] ); ?>:</strong>
            </li>
            <li>
                <input type="radio" name="wizzaro_partners_slider_settings[pause_on_hover]" value="1" <?php checked( $view_data['slider_settings']->pause_on_hover, 1 ); ?>>
                <?php _e( 'Yes' ); ?>
            </li>
            <li>
                <input type="radio" name="wizzaro_partners_slider_settings[pause_on_hover]" value="0" <?php checked( $view_data['slider_settings']->pause_on_hover, 0 ); ?>>
                <?php _e( 'No' ); ?>
            </li>
        </ul>
    </div>
</div>