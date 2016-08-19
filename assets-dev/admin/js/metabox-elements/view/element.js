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