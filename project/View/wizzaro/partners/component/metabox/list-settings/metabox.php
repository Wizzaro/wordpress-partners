<?php
wp_nonce_field('wizzaro_partners_list_settings_edit_nounce', 'wizzaro_partners_list_settings_edit');
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
                <input class="large-text" name="wizzaro_partners_list_settings[header]" type="text" value="<?php echo esc_attr( $view_data['list_settings']->header ); ?>" placeholder="<?php _e( 'Add header', $view_data['languages_domain'] ); ?>">
            </li>
        </ul>
        <?php
        //--------------------------------------------------------------------------------------------------------------
        if ( apply_filters( 'wizzaro_partners_list_use_amount', true, $view_data['post'] ) ) {
            ?>
            <ul>
                <li>
                    <strong><?php _e( 'Amount in line', $view_data['languages_domain'] ); ?>:</strong>
                </li>
                <li>
                    <input class="large-text" name="wizzaro_partners_list_settings[line_amount]" type="number" min="1" max="100" value="<?php echo esc_attr( $view_data['list_settings']->line_amount ); ?>" placeholder="6">
                </li>
            </ul>
            <?php
        }
        ?>
    </div>
</div>