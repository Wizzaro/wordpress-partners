<script type="text/template" id="wizzaro-partners-metabox-noadded-elements">
    <div class="wizzaro-modal">
        <button type="button" class="wizzaro-modal-close button-link">
            <span class="dashicons dashicons-no"></span>
        </button>
        <div class="wizzaro-modal-wrapper">
            <div class="wizzaro-modal-title">
                <h1><?php _e( 'Add', $view_data['languages_domain'] ); ?></h1>
            </div>
            <div class="wizzaro-modal-content">
                <div class="spinner wizzaro-spiner"></div>
                <div class="wizzaro-modal-menu">
                    <button class="button button-large wpme-refresh">
                        <?php _e( 'Refresh', $view_data['languages_domain'] ); ?>
                    </button>
                    &nbsp;
                    <a href="#" class="wpme-select-all">
                        <?php _e( 'Select all', $view_data['languages_domain'] ); ?>
                    </a>
                    &nbsp;|&nbsp;
                    <a href="#" class="wpme-unselect-all">
                        <?php _e( 'Unselect all', $view_data['languages_domain'] ); ?>
                    </a>
                </div>
                <div class="wizzaro-partners-metabox-elements">
                    <div class="wpme-list">
                    </div>
                </div>
            </div>
            <div class="wizzaro-modal-toolbar">
                <button type="button" class="button button-primary button-large wpme-add-elements" disabled="disabled"><?php _e( 'Add', $view_data['languages_domain'] ); ?></button>
            </div>
        </div>
    </div>
    <div class="wizzaro-modal-backdrop"></div>
</script>
<?php 
    //----------------------------------------------------------------------------------------------------
?>
<script type="text/template" id="wizzaro-partners-metabox-noadded-element">
    <div class="wpme-l-elem-wrapper">
        <div class="wpme-l-e-select">
            <a href="#">
                <span class="dashicons dashicons-yes"></span>
            </a>
        </div>
        <% if ( typeof( image_src ) == 'string' ) { %>
            <div class="wpme-l-e-logo">
                <img src="<%= image_src %>">
            </div>
        <% } %>
        <div class="wpme-l-e-name">
            <%= name %>
        </div>
    </div>
    <input type="hidden" name="wizzaro_partner_elems_list[]" value="<%= id %>">
</script>
<?php 
    //----------------------------------------------------------------------------------------------------
?>
<script type="text/template" id="wizzaro-partners-metabox-added-element">
    <div class="wpme-l-elem-wrapper">
        <div class="wpme-l-e-select">
            <a href="#">
                <span class="dashicons dashicons-yes"></span>
            </a>
        </div>
        <% if ( typeof( image_src ) == 'string' ) { %>
            <div class="wpme-l-e-logo">
                <img src="<%= image_src %>">
            </div>
        <% } %>
        <div class="wpme-l-e-name">
            <%= name %>
        </div>
        <div class="wpme-l-e-del">
            <a href="#">
                <span class="dashicons dashicons-trash"></span>
            </a>
        </div>
    </div>
    <input class="wpme-l-e-id" type="hidden" name="wizzaro_partner_elems_list[]" value="<%= id %>">
</script>
<?php 
    //----------------------------------------------------------------------------------------------------
?>
<script type="text/template" id="wizzaro-partners-metabox-no-elements">
    <div class="wpme-no-elements">
        <?php _e( 'No elements to display', $view_data['languages_domain'] ); ?>
    </div>
</script>
<?php 
    //----------------------------------------------------------------------------------------------------
?>
<script type="text/template" id="wizzaro-partners-metabox-elements-sync-error">
    <div class="wpme-error">
        <?php _e( 'Error during download elements', $view_data['languages_domain'] ); ?>
    </div>
</script>               