var Wizzaro = Wizzaro || {};

Wizzaro.namespace = Wizzaro.namespace || function( ns_name ) {
    var parts = ns_name.split('.');
    var parent = Wizzaro;
    var i, max;
    
    if( parts[0] == 'Wizzaro' ) {
        parts = parts.slice(1);
    }
    
    for( i = 0, max = parts.length; i < max; i++ ) {
        if( jQuery.type( parent[parts[i]] ) == 'undefined' ) {
            parent[parts[i]] = {};
        }
        
        parent = parent[parts[i]];
    }
    
    return parent;
};


jQuery( document ).ready( function( $ ) {
    new Wizzaro.Plugins.Partners.v1.MetaboxElements.View.AddedElements();
});

Wizzaro.namespace('Plugins.Partners.v1.MetaboxElements');
Wizzaro.Plugins.Partners.v1.MetaboxElements.Config = Wizzaro.Plugins.Partners.v1.MetaboxElements.Config || {
    added_elements_list: {
        view_no_elements_template: '#wizzaro-partners-metabox-no-elements',
        container: '#wizzaro-partners-metabox-added-elements',
        elems_list: '.wpme-list',
        add_new_elem_button: '.wpme-add-bt',
        remove_elems_button: '.wpme-remove-selected-bt',
        select_all_elems_button: '.wpme-select-all',
        unselect_all_elems_button: '.wpme-unselect-all',
        loader_elem: '.wizzaro-spiner',
        element: {
            view_template: '#wizzaro-partners-metabox-added-element',
            view_id_attr_key: 'data-model-view',
            container: '.wpme-l-elem',
            container_class: 'wpme-l-elem',
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
Wizzaro.namespace('Plugins.Partners.v1.MetaboxElements.Entity');
Wizzaro.Plugins.Partners.v1.MetaboxElements.Entity.Element = Backbone.Model.extend({
    
    destroy: function(options) {
        options = options ? _.clone( options ) : {};
        var model = this;
        var success = options.success;
        var wait = options.wait;

        var destroy = function() {
            model.stopListening();
            model.trigger('destroy', model, model.collection, options);
        };

        options.success = function( resp ) {
            if ( wait ) destroy();
            if ( success ) success.call( options.context, model, resp, options );
            if ( ! model.isNew() ) model.trigger( 'sync', model, resp, options );
        };

        var xhr = false;
        _.defer(options.success);
        if ( ! wait ) destroy();
        return xhr;
    },
    
    defaults: function() {
        return {
            id: null,
            image_src: null,
            name: null,
            select: false,
            show: true,
        };
    },
    
    select: function() {
        this.set( 'select', true );
    },
    
    unselect: function() {
        this.set( 'select', false );
    },
    
    toggleSelect: function() {
        if ( this.get( 'select' ) === true ) {
            this.unselect();
        } else {
            this.select();
        }
    },
    
    show: function() {
        this.set( 'show', true );
    },
    
    hide: function() {
        this.set( 'show', false );
    }
});
Wizzaro.namespace('Plugins.Partners.v1.MetaboxElements.Collection');
Wizzaro.Plugins.Partners.v1.MetaboxElements.Collection.Elements = Backbone.Collection.extend({
    model: Wizzaro.Plugins.Partners.v1.MetaboxElements.Entity.Element,
    
    getSelectedElements: function() {
        return this.where( { select: true, show: true } );
    },
    
    getShowsElements: function() {
        return this.where( { show: true } );
    },
    
    getPrevShow: function( model ) {
        var index = this.indexOf( model ) - 1;

        if ( index >= 0 ) {
            for( ; index >= 0; index-- ) {
                var prev_model = this.at( index );

                if ( prev_model.get( 'show' ) === true ) {
                    return prev_model;
                }
            }    
        }
        
        return;
    },
    
    fetch: function( options ) {
        options = _.extend( {parse: true}, options );
        var success = options.success;
        var error = options.error;
        var collection = this;
        
        options.success = function( resp ) {
            if ( _.isObject( resp ) ) {
                if ( resp.status == true ) {
                    var method = options.reset ? 'reset' : 'set';
                    collection[method]( resp['elements'], options );
                    if (success) success.call( options.context, collection, resp['elements'], options );
                    collection.trigger('sync', collection, resp['elements'], options);
                } else {
                    if ( error ) error.call( options.context, collection, resp, options );
                    collection.trigger( 'error', collection, resp, options );
                }
            } else {
                if ( error ) error.call( options.context, collection, resp, options );
                collection.trigger( 'error', collection, resp, options );
            }
        };
        
        options.error = function( resp ) {
            if ( error ) error.call( options.context, collection, resp, options );
            collection.trigger( 'error', collection, resp, options );
        };

        return this.sync('read', this, options);
    },
});
Wizzaro.namespace('Plugins.Partners.v1.MetaboxElements.View');
Wizzaro.Plugins.Partners.v1.MetaboxElements.View.AddedElements = Backbone.View.extend({
    el: Wizzaro.Plugins.Partners.v1.MetaboxElements.Config.added_elements_list.container,
    config: Wizzaro.Plugins.Partners.v1.MetaboxElements.Config.added_elements_list,
    
    no_elements_template: _.template( jQuery( Wizzaro.Plugins.Partners.v1.MetaboxElements.Config.added_elements_list.view_no_elements_template ).html() ),
    
    collection: null,
    no_added_elements_modal: null,
    
    $remove_elements_button: null,
    $elements_list: null,
    $loader: null,
    
    initialize: function() {
        this.$remove_elements_button =  this.$el.find( this.config.remove_elems_button );
        this.$elements_list = this.$el.find( this.config.elems_list );
        this.$loader = this.$el.find( this.config.loader_elem );
        
        this.$elements_list.sortable( {
            items: '> ' + this.config.element.container,
            placeholder: this.config.element.placeholder_class,
            cursor: 'move',
            opacity: this.config.element.sortable_opatity,
        } ).disableSelection();
        
        this.collection = new Wizzaro.Plugins.Partners.v1.MetaboxElements.Collection.Elements();
        
        jQuery.each( this.$el.find( this.config.elems_list ).find( this.config.element.container ), function( index, value ) {
            var elem = jQuery( value );
            
            var model = new Wizzaro.Plugins.Partners.v1.MetaboxElements.Entity.Element({
                id: String( elem.find( this.config.element.id_elem ).val() ),
                image_src: elem.find( this.config.element.logo_elem ).attr( 'src' ),
                name: elem.find( this.config.element.name_elem ).text() 
            });
            
            this.collection.add( model );
            
            var view = new Wizzaro.Plugins.Partners.v1.MetaboxElements.View.Element({
                el: elem,
                model: model,
                config: this.config.element,
                use_template: false
            });
            
            this.listenTo( model, 'change:select', this.toggleDeleteElemsButtonVisible );
        }.bind( this ) );

        this.no_added_elements_modal = new Wizzaro.Plugins.Partners.v1.MetaboxElements.View.NoAddedElements();
        this.no_added_elements_modal.addFilter( 'display_element', this.filterNoAdedDisplayElement.bind( this ) );
        
        //events
        this.listenTo( this.no_added_elements_modal, 'add_elements', this.addNewElements );
        this.listenTo( this.collection, 'remove', this.checkEmptyInfo );
        this.listenTo( this.collection, 'remove', this.toggleDeleteElemsButtonVisible );
        
        this.no_added_elements_modal.listenTo( this.collection, 'remove', this.no_added_elements_modal.redisplayElement );
        
        this.$el.find( this.config.add_new_elem_button ).on( 'click', this.openNoAddedElementsModal.bind( this ) );
        
        this.$el.find( this.config.select_all_elems_button ).on( 'click', this.selectAllElems.bind( this ) );
        this.$el.find( this.config.unselect_all_elems_button ).on( 'click', this.unSelectAllElems.bind( this ) );
        
        this.$remove_elements_button.on( 'click', this.removeSelectedElements.bind( this ) );
    },

    openNoAddedElementsModal: function( event ) {
        event.preventDefault();
        event.stopPropagation();
        
        this.no_added_elements_modal.open();
    },
    
    filterNoAdedDisplayElement: function( display, model ) {
        if ( ! _.isUndefined( this.collection.findWhere( { id: model.get( 'id' ) } ) ) ) {
            return false;
        }
        
        return display;
    },
    
    checkEmptyInfo: function() {
        if ( this.collection.length <= 0 ) {
            this.$elements_list.html( this.no_elements_template() );
        }
    },
    
    addNewElements: function( elements ) {
        this.$loader.show();
        
        if ( this.$elements_list.find( '> ' + this.config.element.container ).length <= 0 ) {
            this.$elements_list.html( '' );
        }
        
        _.each( elements, function( element ) {
            if ( _.isUndefined( this.collection.findWhere( { id: element.get( 'id' ) } ) ) ) {
                var model = new Wizzaro.Plugins.Partners.v1.MetaboxElements.Entity.Element( element.toJSON() );
                
                this.collection.add( model );
                
                var view = new Wizzaro.Plugins.Partners.v1.MetaboxElements.View.Element({
                    model: model,
                    config: this.config.element,
                });
                
                this.listenTo( model, 'change:select', this.toggleDeleteElemsButtonVisible );
                
                this.$elements_list.append( view.$el );
            }
        }.bind( this ) );
        
        this.$elements_list.sortable( 'refresh' );
        this.checkEmptyInfo();
        this.$loader.hide();
    },
    
    toggleDeleteElemsButtonVisible: function() {
        if ( this.collection.getSelectedElements().length > 0 ) {
            this.$remove_elements_button.attr( 'disabled', false );
        } else {
            this.$remove_elements_button.attr( 'disabled', true );
        }
    },
    
    selectAllElems: function( event ) {
        event.preventDefault();
        event.stopPropagation();
        
        this.collection.invoke( 'select' );
    },
    
    unSelectAllElems: function( event ) {
        event.preventDefault();
        event.stopPropagation();
        
        this.collection.invoke( 'unselect' );
    },
    
    removeSelectedElements: function( event ) {
        event.preventDefault();
        event.stopPropagation();
        
        var selected_elems = this.collection.getSelectedElements();
        
        if ( selected_elems.length > 0 && confirm( wpWizzaroPartnersMetaboxElementsConfig.l10n.delete_elements ) ) {
            _.invoke( selected_elems, 'destroy' );
        }
    }
});
Wizzaro.namespace('Plugins.Partners.v1.MetaboxElements.View');
Wizzaro.Plugins.Partners.v1.MetaboxElements.View.Element = Backbone.View.extend({
    config: null,
    
    initialize: function( options ) {
        this.config = options.config;

        if ( options.use_template !== false ) {
            var template = _.template( jQuery( this.config.view_template ).html() );
            this.$el.addClass( this.config.container_class );
            this.$el.html( template( this.model.toJSON() ) );
        }
        
        this.$el.attr( this.config.view_id_attr_key, this.model.get( 'id' ) );

        this.listenTo( this.model, 'change:select', this.markSelected );
        this.listenTo( this.model, 'change:show', this.changeShow );
        this.listenTo( this.model, 'destroy', this.destroyModel );
        
        
        this.$el.find( this.config.select_elem_button ).on( 'click', this.select.bind( this ) );
        
        var delete_button = this.$el.find( this.config.delete_elem_button );
        
        if ( delete_button.length > 0 ) {
            this.$el.find( this.config.delete_elem_button ).on( 'click', this.delete.bind( this ) );
        }
    },
    
    select: function( event ) {
        event.preventDefault();
        event.stopPropagation();
        this.model.toggleSelect();
    },
    
    markSelected: function() {
        if ( this.model.get( 'select' ) === true ) {
            this.$el.addClass( this.config.selected_class );
        } else {
            this.$el.removeClass( this.config.selected_class );
        }
    },
    
    changeShow: function() {
        if ( this.model.get( 'show' ) === false ) {
            this.remove();
        }
    },
    
    delete: function( event ) {
        event.preventDefault();
        event.stopPropagation();
        
        if ( confirm( wpWizzaroPartnersMetaboxElementsConfig.l10n.delete_element ) ) {
            this.model.destroy();
        }
    },
    
    destroyModel: function() {
        this.remove();
    }
});
Wizzaro.namespace('Plugins.Partners.v1.MetaboxElements.View');
Wizzaro.Plugins.Partners.v1.MetaboxElements.View.NoAddedElements = Backbone.View.extend({
    
    collection: null,
    
    $add_elements_button: null,
    $elements_list: null,
    $loader: null,
    
    config: Wizzaro.Plugins.Partners.v1.MetaboxElements.Config.no_added_elements_list,
    
    filters: {
        'display_element': [],
        'display_elements': []  
    },
    
    template: _.template( jQuery( Wizzaro.Plugins.Partners.v1.MetaboxElements.Config.no_added_elements_list.view_template ).html() ),
    no_elements_template: _.template( jQuery( Wizzaro.Plugins.Partners.v1.MetaboxElements.Config.no_added_elements_list.view_no_elements_template ).html() ),
    sync_elements_template: _.template( jQuery( Wizzaro.Plugins.Partners.v1.MetaboxElements.Config.no_added_elements_list.view_sync_elements_template ).html() ),

    attributes: {
        class : 'wizzaro-modal-container',
    },
    
    initialize: function() {
        this.collection = new Wizzaro.Plugins.Partners.v1.MetaboxElements.Collection.Elements();
        this.collection.url = ajaxurl;
        this.render();
        jQuery( 'body' ).append( this.$el );
    },
    
    render: function() {
        this.$el.html( this.template() );
        
        this.$add_elements_button =  this.$el.find( this.config.add_elements_button );
        this.$elements_list = this.$el.find( this.config.elems_list );
        this.$loader = this.$el.find( this.config.loader_elem );
        
        jQuery( document ).on( 'keydown', this.eventKeyDown.bind( this ) );
        
        this.$add_elements_button.on( 'click', this.addElements.bind( this ) );
                
        this.$el.find( this.config.close_button ).on( 'click', this.close.bind( this ) );
        
        this.$el.find( this.config.refresh_button ).on( 'click', this.updateCollection.bind( this ) );
        this.$el.find( this.config.select_all_elems_button ).on( 'click', this.selectAllElems.bind( this ) );
        this.$el.find( this.config.unselect_all_elems_button ).on( 'click', this.unSelectAllElems.bind( this ) );
        
        this.listenTo( this.collection, 'add', this.addNewElement );
        this.listenTo( this.collection, 'remove', this.checkEmptyInfo );
        
        return this;
    },
    
    open: function() {
        jQuery( 'body' ).css( 'overflow', 'hidden' );
        
        if ( this.collection.length <= 0 ) {
            this.updateCollection();
        }
         
        this.$el.show();
    },
    
    close: function() {
        jQuery( 'body' ).css( 'overflow', '' );
        this.$el.hide();
    },
    
    eventKeyDown: function() {
         if ( event.keyCode == 27 ) {
             if ( this.$el.is(':visible') ) {
                event.preventDefault();
                event.stopPropagation();
                this.close();
            }
        }
    },
    
    addFilter: function( key, filter ) {
        if (  _.isArray( this.filters[key] ) && _.isFunction( filter ) ) {
            this.filters[key].push( filter );
        }
    },
    
    checkEmptyInfo: function() {
        if ( this.collection.getShowsElements().length <= 0 ) {
            this.$elements_list.html( this.no_elements_template() );
        }
    },
    
    updateCollection: function() {
        this.$loader.show();
        
        this.collection.fetch({
            type: 'post',
            data: {
                action: wpWizzaroPartnersMetaboxElementsConfig.sync_elements.action,
                nounce: wpWizzaroPartnersMetaboxElementsConfig.sync_elements.nonce,
                elements_post_type: wpWizzaroPartnersMetaboxElementsConfig.sync_elements.elements_post_type
            },
            error: function() {
                this.collection.reset();
                this.$elements_list.html( this.sync_elements_template() );
            }.bind( this ),
            success: function( data, status ) {
                this.checkEmptyInfo();
            }.bind( this ),
            complete: function() {
                this.$loader.hide();
            }.bind( this )
        });
    },
    
    addNewElement: function( model ) {
        this.listenTo( model, 'change:show', this.displayElement );
        this.listenTo( model, 'change:show', this.checkEmptyInfo );
        this.listenTo( model, 'change:select', this.toggleAddButtonVisible );
        this.displayElement( model );
    },
    
    displayElement: function( model ) {
        var display = true;
        
        _.each( this.filters['display_element'], function( filter ) {
            display = filter( display, model );
        });
        
        model.set( 'show', display );
        
        if ( model.get( 'show' ) === true ) {
            if ( this.$elements_list.find( '> ' + this.config.element.container ).length <= 0 ) {
                this.$elements_list.html( '' );
            }
            
            var view = new Wizzaro.Plugins.Partners.v1.MetaboxElements.View.Element({
                model: model,
                config: this.config.element
            });
            
            var prev_model = this.collection.getPrevShow( model );
            
            if ( ! _.isUndefined( prev_model ) ) {
                var $prev_view_el = this.$elements_list.find( this.config.element.container + '[' + this.config.element.view_id_attr_key + '=' + prev_model.get( 'id' ) + ']')
                
                if ( $prev_view_el.length > 0 ) {
                    $prev_view_el.after( view.$el );
                } else {
                    this.$elements_list.append( view.$el );
                }
            } else {
                this.$elements_list.append( view.$el );
            }
        }
    },
    
    selectAllElems: function( event ) {
        event.preventDefault();
        event.stopPropagation();
        
        this.collection.invoke( 'select' );
    },
    
    unSelectAllElems: function( event ) {
        event.preventDefault();
        event.stopPropagation();
        
        this.collection.invoke( 'unselect' );
    },
    
    toggleAddButtonVisible: function() {
        if ( this.collection.getSelectedElements().length > 0 ) {
            this.$add_elements_button.attr( 'disabled', false );
        } else {
            this.$add_elements_button.attr( 'disabled', true );
        }
    },
    
    addElements: function( event ) {
        event.preventDefault();
        event.stopPropagation();
        
        var selected_elems = this.collection.getSelectedElements();
        this.collection.invoke( 'unselect' );
        this.trigger( 'add_elements', selected_elems );
        this.close();
        _.invoke( selected_elems, 'hide' );
    },
    
    redisplayElement: function( added_model ) {
        var model = this.collection.findWhere( { id: added_model.get( 'id' ) } );
        
        if ( ! _.isUndefined( model ) ) {
            model.show();
        }
    },
});