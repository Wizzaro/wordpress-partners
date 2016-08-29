<p>
    <select class="widefat"  id="<?php echo $view_data['field_id'] ?>" name="<?php echo $view_data['field_name'] ?>">
        <?php
        foreach( $view_data['slides'] as $slide ) {
            ?>
            <option value="<?php echo $slide->ID; ?>" <?php selected( $view_data['selected'], $slide->ID ); ?>><?php echo esc_attr( $slide->post_title ); ?></option>
            <?php
        }
        ?>
    </select>
</p>