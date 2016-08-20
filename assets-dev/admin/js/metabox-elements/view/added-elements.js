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
            
            if ( ! elem.hasClass( this.config.element.breal_line_container_class ) ) {
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
            } else {
                var model = new Wizzaro.Plugins.Partners.v1.MetaboxElements.Entity.BreakLine();
                
                this.collection.add( model );
                
                var view = new Wizzaro.Plugins.Partners.v1.MetaboxElements.View.BreakLine({
                    el: elem,
                    model: model,
                    config: this.config.element,
                    use_template: false
                });
                
                this.listenTo( model, 'change:select', this.toggleDeleteElemsButtonVisible );
            }
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
        
        var add_break_line_button = this.$el.find( this.config.add_break_line_button );
        if ( add_break_line_button.length > 0 ) {
            add_break_line_button.on( 'click', this.addBreakLine.bind( this ) );
        }
        
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
    
    addBreakLine: function() {
        if ( this.$elements_list.find( '> ' + this.config.element.container ).length <= 0 ) {
            this.$elements_list.html( '' );
        }
        
        var model = new Wizzaro.Plugins.Partners.v1.MetaboxElements.Entity.BreakLine();
                
        this.collection.add( model );
        
        var view = new Wizzaro.Plugins.Partners.v1.MetaboxElements.View.BreakLine({
            model: model,
            config: this.config.element,
        });
        
        this.listenTo( model, 'change:select', this.toggleDeleteElemsButtonVisible );
        
        this.$elements_list.append( view.$el );
        
        this.$elements_list.sortable( 'refresh' );
        this.checkEmptyInfo();
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