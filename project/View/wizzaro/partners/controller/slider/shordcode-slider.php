<?php
use Wizzaro\Partners\Entity\PostMeta\PartnerData as PartnerDataEntity;
use Wizzaro\Partners\Collections\PostTypes;

//$container_width = '';
$element_width = '';
if ( apply_filters( 'wizzaro_partners_slider_use_amount', true, $view_data['post'] ) ) {
    $elems_count = count( $view_data['elements'] );
    
    $container_width = ( 100 * count( $view_data['elements'] ) ) / $view_data['settings']->line_amount;
    $container_width = $container_width < 100 ? 100 : $container_width;
    
    $divider = $elems_count > $view_data['settings']->line_amount ? $elems_count : $view_data['settings']->line_amount;
    $element_width = $container_width / $divider;
    
    //$container_width = ' style="width:' . $container_width . '%"';
    $element_width = ' style="width:' . $element_width . '%"';
}
?>
<div class="wizzaro-partners-slider" data-transition-speed="<?php echo esc_attr( $view_data['settings']->transition_speed ); ?>" data-pause-betwen-transition="<?php echo esc_attr( $view_data['settings']->pause_betwen_transition ); ?>" data-pause-on-hover="<?php echo esc_attr( $view_data['settings']->pause_on_hover ); ?>">
    <?php 
    if ( $view_data['settings']->header ) {
        ?>
        <h2 class="wps-header">
            <?php echo esc_attr( $view_data['settings']->header ); ?>
        </h2>
        <?php
    }
    ?>
    <div class="wps-elems">
        <?php
        foreach ( $view_data['elements'] as $element ) {
            $element_data = new PartnerDataEntity( $element->ID );
            ?>
                <div class="wps-elem"<?php echo $element_width; ?>>
                    <div class="wps-e-thumbnail">
                        <?php
                        if ( has_post_thumbnail( $element ) ) {
                            echo get_the_post_thumbnail( $element );
                        }
                        ?>
                    </div>
                    <div class="wps-e-title">
                        <?php echo esc_attr( $element->post_title ); ?>
                    </div>
                    <?php
                    $element_post_type = PostTypes::get_instance()->get_post_type( $element->post_type );
                    
                    if ( $element_post_type ) {
                        foreach ( $element_post_type->get_setting( 'partner_data_attributes' ) as $attribute_key => $attribute_settings ) {
                            if ( $attribute_settings['use'] === true && mb_strlen( $element_data->$attribute_key ) > 0 ) {
                                ?>
                                <div class="wps-e-<?php echo $attribute_key ?>">
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
                    ?>
                </div>
            <?php
        }
        ?>
    </div>
</div>