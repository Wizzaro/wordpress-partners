<?php
wp_nonce_field('wizzaro_partners_partner_data_edit_nounce', 'wizzaro_partners_partner_data_edit');
?>
<div class="panel-wrap">
    <div class="panel">
        <?php
        $display_place = $view_data['post_type_option_instance']->get_option( $view_data['post_type_option_instance']->get_prefix() . '-partner-data', 'display_place' );
        
        if ( $view_data['supports']['content'] === true && in_array( $display_place, array( 'before', 'after' ) ) ) {
            ?>
            <ul>
                <li>
                    <strong><?php _e( 'Info', $view_data['languages_domain'] ); ?>:</strong>
                </li>
                <?php
                switch ( $display_place ) {
                    case 'before':
                        echo '<li>' . __( 'Data will be automatically added before displaying the content', $view_data['languages_domain'] ) . '.</li>';
                        break;
                    case 'after':
                        echo '<li>' . __( 'Data will be automatically added after displaying the content', $view_data['languages_domain'] ) . '.</li>';
                        break;
                }
                if ( $view_data['supports']['content'] === true ) {
                    ?>
                    <li>
                        <?php printf( __( 'For display data you can add shordcode: %s in the content', $view_data['languages_domain'] ), '<code>[' . $view_data['post_type_option_instance']->get_prefix() . '-data]</code>' ); ?>
                    </li>
                    <?php
                }
                ?>
            </ul>
            <?php
        }
        ?>
        <ul>
            <li>
                <strong><?php _e( 'Data', $view_data['languages_domain'] ); ?>:</strong>
            </li>
            <?php
            foreach ( $view_data['partner_data_attributes'] as $attribute_key => $attribute_settings ) {
                if ( $attribute_settings['use'] === true ) {
                    ?>
                    <li>
                        <label><?php echo $attribute_settings['title'] ?></label>
                    </li>
                    <?php
                    
                    $name = 'wizzaro_partners_parner[' . $attribute_key . ']';
                    
                    if ( array_key_exists( 'multiple', $attribute_settings) && $attribute_settings['multiple'] === true ) {
                        ?>
                        <li class="wppd-multiple-attrs">
                            <div class="wppd-ma-items">
                                <?php
                                $name .= '[]';
                                
                                if ( is_array( $view_data['partner_data']->$attribute_key ) && count( $view_data['partner_data']->$attribute_key ) > 0 ) {
                                    foreach ( $view_data['partner_data']->$attribute_key as $value ) {
                                        ?>
                                        <div class="wppd-ma-item">
                                            <input class="large-text" name="<?php echo $name; ?>" type="text" value="<?php echo esc_attr( $value ); ?>" placeholder="<?php echo $attribute_settings['placeholder']; ?>">
                                        </div>
                                        <?php
                                    }
                                } else {
                                    ?>
                                    <div class="wppd-ma-item">
                                        <input class="large-text" name="<?php echo $name; ?>" type="text" value="" placeholder="<?php echo $attribute_settings['placeholder']; ?>">
                                    </div>
                                    <?php
                                }
                                ?>
                            </div>
                            <div class="wppd-ma-add-item">
                                <a href="#">
                                    <?php _e( 'Add new', $view_data['languages_domain'] ); ?>
                                </a>
                            </div>
                        </li>
                        <?php
                    } else {
                        ?>
                        <li>
                            <input class="large-text" name="<?php echo $name; ?>" type="text" value="<?php echo esc_attr( $view_data['partner_data']->$attribute_key ); ?>" placeholder="<?php echo $attribute_settings['placeholder']; ?>">
                        </li>
                        <?php
                    }
                }
            }
            ?>
        </ul>
    </div>
</div>