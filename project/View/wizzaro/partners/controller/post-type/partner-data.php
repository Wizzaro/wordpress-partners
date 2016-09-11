<div class="wizzaro-partner-data">
    <?php
    foreach ( $view_data['partner_data_attributes'] as $attribute_key => $attribute_settings ) {
        if ( $attribute_settings['use'] === true && $view_data['partner_data']->$attribute_key ) {
            if ( array_key_exists( 'multiple', $attribute_settings) && $attribute_settings['multiple'] === true ) {
                if ( is_array( $view_data['partner_data']->$attribute_key ) && count( $view_data['partner_data']->$attribute_key ) > 0 ) {
                    ?>
                    <div class="wpd-item">
                        <strong><?php echo $attribute_settings['title'] ?>:</strong>
                        <?php
                        $value_view = '';
                        
                        foreach( $view_data['partner_data']->$attribute_key as $attr ) {
                            $value_view .= ', ';
                            
                            switch ( $attribute_settings['type'] ) {
                                case 'phone':
                                    $value_view .= '<a href="tel:' . preg_replace( '/\s+/', '', esc_attr( $attr ) ) . '">' . esc_attr( $attr ) . '</a>';
                                    break;
                                case 'email':
                                    $value_view .= '<a href="mailto:' . esc_attr( $attr ) . '">' . esc_attr( $attr ) . '</a>';
                                    break;
                                case 'url':
                                    $value_view .= '<a href="' . esc_url( $attr ) . '" target="__blank">' . preg_replace( '/^(http|https){1}(:)?(\/\/)?/i', '', esc_attr( $attr ) ) . '</a>';
                                    break;
                                default:
                                    $value_view .= esc_attr( $attr );
                                    break;
                            }
                        }

                        echo trim( ltrim( $value_view, ',' ) ); 
                        ?>
                    </div>
                    <?php
                }
            } elseif( is_string( $view_data['partner_data']->$attribute_key ) && mb_strlen( $view_data['partner_data']->$attribute_key ) > 0 ) {
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
    }
    ?>
</div>