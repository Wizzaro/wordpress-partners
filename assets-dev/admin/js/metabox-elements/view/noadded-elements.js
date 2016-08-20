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
        if ( added_model.has( 'id' ) ) {
            var model = this.collection.findWhere( { id: added_model.get( 'id' ) } );
            
            if ( ! _.isUndefined( model ) ) {
                model.show();
            }
        }
    },
});