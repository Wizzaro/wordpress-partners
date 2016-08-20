Wizzaro.namespace('Plugins.Partners.v1.MetaboxElements');
Wizzaro.Plugins.Partners.v1.MetaboxElements.Config = Wizzaro.Plugins.Partners.v1.MetaboxElements.Config || {
    added_elements_list: {
        view_no_elements_template: '#wizzaro-partners-metabox-no-elements',
        container: '#wizzaro-partners-metabox-added-elements',
        elems_list: '.wpme-list',
        add_new_elem_button: '.wpme-add-bt',
        add_break_line_button: '.wpme-add-break-line-bt',
        remove_elems_button: '.wpme-remove-selected-bt',
        select_all_elems_button: '.wpme-select-all',
        unselect_all_elems_button: '.wpme-unselect-all',
        loader_elem: '.wizzaro-spiner',
        element: {
            view_template: '#wizzaro-partners-metabox-added-element',
            view_break_line_template: '#wizzaro-partners-metabox-break-line-element',
            view_id_attr_key: 'data-model-view',
            container: '.wpme-l-elem',
            container_class: 'wpme-l-elem',
            breal_line_container: 'wpme-l-elem-break-line',
            breal_line_container_class: 'wpme-l-elem-break-line',
            id_elem: '.wpme-l-e-id',
            logo_elem: '.wpme-l-e-logo img',
            name_elem: '.wpme-l-e-name',
            select_elem_button: '.wpme-l-e-select a',
            delete_elem_button: '.wpme-l-e-del a',
            selected_class: 'wpme-l-elem-selected',
            placeholder_class: 'wpme-l-elem-placeholder',
            sortable_opatity: 0.7
        }
    },
    no_added_elements_list: {
        view_template: '#wizzaro-partners-metabox-noadded-elements',
        view_no_elements_template: '#wizzaro-partners-metabox-no-elements',
        view_sync_elements_template: '#wizzaro-partners-metabox-elements-sync-error',
        add_elements_button: '.wpme-add-elements',
        close_button: '.wizzaro-modal-close',
        refresh_button: '.wpme-refresh',
        select_all_elems_button: '.wpme-select-all',
        unselect_all_elems_button: '.wpme-unselect-all',
        elems_list: '.wpme-list',
        loader_elem: '.wizzaro-spiner',
        element: {
            view_template: '#wizzaro-partners-metabox-noadded-element',
            view_id_attr_key: 'data-model-view',
            container_class: 'wpme-l-elem',
            container: '.wpme-l-elem',
            id_elem: '.wpme-l-e-id',
            logo_elem: '.wpme-l-e-logo img',
            name_elem: '.wpme-l-e-name',
            select_elem_button: '.wpme-l-e-select a',
            selected_class: 'wpme-l-elem-selected'
        }
    },
};