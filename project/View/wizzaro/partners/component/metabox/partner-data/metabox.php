<?php
use Wizzaro\Partners\Texts\PluginTexts;

wp_nonce_field('wizzaro_partners_partner_data_edit_nounce', 'wizzaro_partners_partner_data_edit');
?>
<div class="panel-wrap">
    <div class="panel">
        <ul>
            <?php
            if ( $view_data['partner_data_attributes']['address'] === true ) {
                ?>
                <li>
                    <label><?php _e( 'Street', $view_data['languages_domain'] ); ?></label>
                </li>
                <li>
                    <input class="large-text" name="wizzaro_partners_parner[street]" type="text" value="<?php echo esc_attr( $view_data['partner_data']->street ); ?>" placeholder="<?php _e( 'Add Street', $view_data['languages_domain'] ); ?>">
                </li>
                <?php
                //--------------------------------------------------------------------------------------------------------------
                ?>
                <li>
                    <label><?php _e( 'Zip code', $view_data['languages_domain'] ); ?></label>
                </li>
                <li>
                    <input class="large-text" name="wizzaro_partners_parner[zip_code]" type="text" value="<?php echo esc_attr( $view_data['partner_data']->zip_code ); ?>" placeholder="<?php _e( 'Add zip code', $view_data['languages_domain'] ); ?>">
                </li>
                <?php
                //--------------------------------------------------------------------------------------------------------------
                ?>
                <li>
                    <label><?php _e( 'City', $view_data['languages_domain'] ); ?></label>
                </li>
                <li>
                    <input class="large-text" name="wizzaro_partners_parner[city]" type="text" value="<?php echo esc_attr( $view_data['partner_data']->city ); ?>" placeholder="<?php _e( 'Add City', $view_data['languages_domain'] ); ?>">
                </li>
                <?php
            }

            //====================================================================================================

            if ( $view_data['partner_data_attributes']['phone'] === true ) {
                ?>
                <li>
                    <label><?php _e( 'Phone', $view_data['languages_domain'] ); ?></label>
                </li>
                <li>
                    <input class="large-text" name="wizzaro_partners_parner[phone]" type="text" value="<?php echo esc_attr( $view_data['partner_data']->phone ); ?>" placeholder="<?php _e( 'Add Phone', $view_data['languages_domain'] ); ?>">
                </li>
                <?php
            }
            
            //====================================================================================================

            if ( $view_data['partner_data_attributes']['email'] === true ) {
                ?>
                <li>
                    <label><?php _e( 'E-mail', $view_data['languages_domain'] ); ?></label>
                </li>
                <li>
                    <input class="large-text" name="wizzaro_partners_parner[email]" type="text" value="<?php echo esc_attr( $view_data['partner_data']->email ); ?>" placeholder="<?php _e( 'Add e-mail', $view_data['languages_domain'] ); ?>">
                </li>
                <?php
            }
            
            //====================================================================================================

            if ( $view_data['partner_data_attributes']['phone'] === true ) {
                ?>
                <li>
                    <label><?php _e( 'Website URL' ); ?></label>
                </li>
                <li>
                    <input class="large-text" name="wizzaro_partners_parner[website_url]" type="text" value="<?php echo esc_attr( $view_data['partner_data']->website_url ); ?>" placeholder="<?php _e( 'Add website URL', $view_data['languages_domain'] ); ?>">
                </li>
                <?php
            }
            ?>
        </ul>
    </div>
</div>