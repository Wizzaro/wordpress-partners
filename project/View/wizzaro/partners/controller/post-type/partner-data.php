<div class="wizzaro-partner-data">
    <?php
    foreach ( $view_data['partner_data_attributes'] as $attribute_key => $attribute_settings ) {
        if ( $attribute_settings['use'] === true && $view_data['partner_data']->$attribute_key ) {
            ?>
            <div class="wpd-item">
                <strong><?php echo $attribute_settings['title'] ?>:</strong>
                <?php
                switch ( $attribute_settings['type'] ) {
                    case 'phone':
                        ?>
                        <a href="tel:<?php echo preg_replace( '/\s+/', '', esc_attr( $view_data['partner_data']->$attribute_key ) ); ?>"><?php echo esc_attr( $view_data['partner_data']->$attribute_key ); ?></a>
                        <?php
                        break;
                    case 'email':
                        ?>
                        <a href="mailto:<?php echo esc_attr( $view_data['partner_data']->$attribute_key ); ?>"><?php echo esc_attr( $view_data['partner_data']->$attribute_key ); ?></a>
                        <?php
                        break;
                    case 'url':
                        ?>
                        <a href="<?php echo esc_url( $view_data['partner_data']->$attribute_key ); ?>" target="__blank"><?php echo preg_replace( '/^(http|https){1}(:)?(\/\/)?/i', '', esc_attr( $view_data['partner_data']->$attribute_key ) ); ?></a>
                        <?php
                        break;
                    default:
                        echo esc_attr( $view_data['partner_data']->$attribute_key );
                        break;
                }
                ?>
            </div>
            <?php
        }
    }
    ?>
</div>