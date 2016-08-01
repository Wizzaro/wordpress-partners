<div class="wizzaro-partner-data">
    <?php
    if ( $view_data['partner_data_attributes']['address'] === true ) {
        ?>
        <div class="wpd-item">
            <strong><?php _e( 'Address', $view_data['languages_domain'] ); ?></strong>:
            <?php 
            echo esc_attr( $view_data['partner_data']->street ) . ', ' .  esc_attr( $view_data['partner_data']->zip_code ) . ' ' . esc_attr( $view_data['partner_data']->city );
            ?>
        </div>
        <?php
    }

    //====================================================================================================

    if ( $view_data['partner_data_attributes']['phone'] === true ) {
        ?>
        <div class="wpd-item">
            <strong><?php _e( 'Phone', $view_data['languages_domain'] ); ?></strong>
            <a href="tel:<?php echo esc_attr( $view_data['partner_data']->phone ); ?>"><?php echo esc_attr( $view_data['partner_data']->phone ); ?></a>
        </div>
        <?php
    }
    
    //====================================================================================================

    if ( $view_data['partner_data_attributes']['email'] === true ) {
        ?>
        <div class="wpd-item">
            <strong><?php _e( 'E-mail', $view_data['languages_domain'] ); ?></strong>
            <a href="mailto:<?php echo esc_attr( $view_data['partner_data']->email ); ?>"><?php echo esc_attr( $view_data['partner_data']->email ); ?></a>
        </div>
        <?php
    }
    
    //====================================================================================================

    if ( $view_data['partner_data_attributes']['website_url'] === true ) {
        ?>
        <div class="wpd-item">
            <strong><?php _e( 'Website URL', $view_data['languages_domain'] ); ?></strong>
            <a href="<?php echo esc_url( $view_data['partner_data']->website_url ); ?>" target="__blank"><?php echo preg_replace( '/^(http|https){1}(:)?(\/\/)?/i', '', esc_attr( $view_data['partner_data']->website_url ) ); ?></a>
        </div>
        <?php
    }
    ?>
</div>