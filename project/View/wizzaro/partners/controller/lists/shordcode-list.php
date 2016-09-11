<?php
use Wizzaro\Partners\Entity\PostMeta\PartnerData as PartnerDataEntity;
use Wizzaro\Partners\Collections\PostTypes;

//$container_width = '';
$element_width = '';
if ( apply_filters( 'wizzaro_partners_list_use_amount', true, $view_data['post'] ) ) {
    $element_width = 100 / $view_data['settings']->line_amount;
    $element_width = ' style="width:' . $element_width . '%"';
}
?>
<div class="wizzaro-partners-list">
    <?php 
    if ( $view_data['settings']->header ) {
        ?>
        <h2 class="wpl-header">
            <?php echo esc_attr( $view_data['settings']->header ); ?>
        </h2>
        <?php
    }
    ?>
    <div class="wpl-elems">
        <div class="wpl-elems-wrapper">
            <?php
            foreach ( $view_data['elements'] as $element ) {
                if ( is_string( $element) && ! strcasecmp( $element, 'break' ) ) {
                    ?>
                    </div>
                    <div class="wpl-elems-wrapper">
                    <?php
                } else {
                    $element_data = new PartnerDataEntity( $element->ID );
                    ?>
                    <div class="wpl-elem"<?php echo $element_width; ?>>
                        <div class="wpl-e-thumbnail">
                            <?php
                            if ( has_post_thumbnail( $element ) ) {
                                echo get_the_post_thumbnail( $element );
                            }
                            ?>
                        </div>
                        <div class="wpl-e-title">
                            <?php echo esc_attr( $element->post_title ); ?>
                        </div>
                        <?php
                        $element_post_type = PostTypes::get_instance()->get_post_type( $element->post_type );
                        
                        if ( $element_post_type ) {
                            foreach ( $element_post_type->get_setting( 'partner_data_attributes' ) as $attribute_key => $attribute_settings ) {
                                if ( $attribute_settings['use'] === true ) {
                                    if ( array_key_exists( 'multiple', $attribute_settings) && $attribute_settings['multiple'] === true ) {
                                        if ( is_array( $element_data->$attribute_key ) && count( $element_data->$attribute_key ) > 0 ) {
                                            ?>
                                            <div class="wpl-e-<?php echo $attribute_key ?>">
                                                <strong><?php echo $attribute_settings['title'] ?>:</strong>
                                                <?php
                                                $value_view = '';
                                                
                                                foreach( $element_data->$attribute_key as $attr ) {
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
                                    } elseif( is_string( $element_data->$attribute_key ) && mb_strlen( $element_data->$attribute_key ) > 0 ) {
                                        ?>
                                        <div class="wpl-e-<?php echo $attribute_key ?>">
                                            <strong><?php echo $attribute_settings['title'] ?>:</strong>
                                            <?php
                                            switch ( $attribute_settings['type'] ) {
                                                case 'phone':
                                                    ?>
                                                    <a href="tel:<?php echo preg_replace( '/\s+/', '', esc_attr( $element_data->$attribute_key ) ); ?>"><?php echo esc_attr( $element_data->$attribute_key ); ?></a>
                                                    <?php
                                                    break;
                                                case 'email':
                                                    ?>
                                                    <a href="mailto:<?php echo esc_attr( $element_data->$attribute_key ); ?>"><?php echo esc_attr( $element_data->$attribute_key ); ?></a>
                                                    <?php
                                                    break;
                                                case 'url':
                                                    ?>
                                                    <a href="<?php echo esc_url( $element_data->$attribute_key ); ?>" target="__blank"><?php echo preg_replace( '/^(http|https){1}(:)?(\/\/)?/i', '', esc_attr( $element_data->$attribute_key ) ); ?></a>
                                                    <?php
                                                    break;
                                                default:
                                                    echo esc_attr( $element_data->$attribute_key );
                                                    break;
                                            }
                                            ?>
                                        </div>
                                        <?php
                                    }
                                }
                            }
                        }
                        ?>
                    </div>
                    <?php
                }
            }
            ?>
        </div>
    </div>
</div>