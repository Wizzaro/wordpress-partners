<?php
wp_nonce_field('wizzaro_partners_elements_edit_nounce', 'wizzaro_partners_elements_edit');
?>
<div class="panel-wrap">
    <div class="panel">
        <div id="wizzaro-partners-metabox-added-elements" class="wizzaro-partners-metabox-elements">
            <div class="spinner wizzaro-spiner"></div>
            <div>
                <button type="button" class="wpme-add-bt button button-primary button-large"><?php _e( 'Add elements', $view_data['languages_domain'] ); ?></button>
                <button type="button" class="wpme-add-break-line-bt button button-large"><?php _e( 'Add break line', $view_data['languages_domain'] ); ?></button>
                <button type="button" class="wpme-remove-selected-bt button button-delete button-large" disabled="disabled"><?php _e( 'Remove selected elements', $view_data['languages_domain'] ); ?></button>
            </div>
            <div class="wpme-menu">
                <a href="#" class="wpme-select-all">
                    <?php _e( 'Select all', $view_data['languages_domain'] ); ?>
                </a>
                &nbsp;|&nbsp;
                <a href="#" class="wpme-unselect-all">
                    <?php _e( 'Unselect all', $view_data['languages_domain'] ); ?>
                </a>
            </div>
            <div class="wpme-list">
                <?php
                if ( count( $view_data['elemnts'] ) <= 0 ) {
                    ?>
                    <div class="wpme-no-elements">
                        <?php _e( 'No elements to display', $view_data['languages_domain'] ); ?>
                    </div>
                    <?php
                } else {
                    foreach( $view_data['elemnts'] as $element ) {
                        if ( is_string( $element) && ! strcasecmp( $element, 'break' ) ) {
                            ?>
                            <div class="wpme-l-elem wpme-l-elem-break-line">
                                <div class="wpme-l-elem-wrapper">
                                    <div class="wpme-l-e-select">
                                        <a href="#">
                                            <span class="dashicons dashicons-yes"></span>
                                        </a>
                                    </div>
                                    <div class="wpme-l-e-break-line">
                                    </div>
                                    <div class="wpme-l-e-del">
                                        <a href="#">
                                            <span class="dashicons dashicons-trash"></span>
                                        </a>
                                    </div>
                                </div>
                                <input class="wpme-l-e-id" type="hidden" name="<?php echo $view_data['input_name']; ?>[]" value="break">
                            </div>
                            <?php
                        } else {
                            ?>
                            <div class="wpme-l-elem">
                                <div class="wpme-l-elem-wrapper">
                                    <div class="wpme-l-e-select">
                                        <a href="#">
                                            <span class="dashicons dashicons-yes"></span>
                                        </a>
                                    </div>
                                    <?php
                                    if ( has_post_thumbnail( $element ) ) {
                                        ?>
                                        <div class="wpme-l-e-logo">
                                            <?php echo get_the_post_thumbnail( $element ); ?>
                                        </div>
                                        <?php
                                    }
                                    ?>
                                    <div class="wpme-l-e-name">
                                        <?php echo $element->post_title; ?>
                                    </div>
                                    <div class="wpme-l-e-del">
                                        <a href="#">
                                            <span class="dashicons dashicons-trash"></span>
                                        </a>
                                    </div>
                                </div>
                                <input class="wpme-l-e-id" type="hidden" name="<?php echo $view_data['input_name']; ?>[]" value="<?php echo $element->ID; ?>">
                            </div>
                            <?php
                        }
                    }
                }
                ?>
            </div>
            <div class="wpme-menu">
                <a href="#" class="wpme-select-all">
                    <?php _e( 'Select all', $view_data['languages_domain'] ); ?>
                </a>
                &nbsp;|&nbsp;
                <a href="#" class="wpme-unselect-all">
                    <?php _e( 'Unselect all', $view_data['languages_domain'] ); ?>
                </a>
            </div>
            <div>
                <button type="button" class="wpme-add-bt button button-primary button-large"><?php _e( 'Add elements', $view_data['languages_domain'] ); ?></button>
                <button type="button" class="wpme-add-break-line-bt button button-large"><?php _e( 'Add break line', $view_data['languages_domain'] ); ?></button>
                <button type="button" class="wpme-remove-selected-bt button button-delete button-large" disabled="disabled"><?php _e( 'Remove selected elements', $view_data['languages_domain'] ); ?></button>
            </div>
        </div>
    </div>
</div>