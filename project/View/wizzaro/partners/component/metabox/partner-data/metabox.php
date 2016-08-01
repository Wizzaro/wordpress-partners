<?php
use Wizzaro\Partners\Texts\PluginTexts;

wp_nonce_field('wizzaro_partners_partner_data_edit_nounce', 'wizzaro_partners_partner_data_edit');
?>
<div class="panel-wrap">
    <div class="panel">
        <ul>
            <?php
            foreach ( $view_data['partner_data_attributes'] as $attribute_key => $attribute_settings ) {
                if ( $attribute_settings['use'] === true ) {
                    ?>
                    <li>
                        <label><?php echo $attribute_settings['title'] ?></label>
                    </li>
                    <li>
                        <input class="large-text" name="wizzaro_partners_parner[<?php echo $attribute_key ?>]" type="text" value="<?php echo esc_attr( $view_data['partner_data']->$attribute_key ); ?>" placeholder="<?php echo $attribute_settings['placeholder']; ?>">
                    </li>
                    <?php
                }
            }
            ?>
        </ul>
    </div>
</div>