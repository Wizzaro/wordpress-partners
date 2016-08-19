Wizzaro.namespace('Plugins.Partners.v1.MetaboxElements.View');
Wizzaro.Plugins.Partners.v1.MetaboxElements.View.Element = Backbone.View.extend({
    config: null,
    template: null,
    
    initialize: function( options ) {
        this.config = options.config;
        this.template = _.template( jQuery( this.config.view_template ).html() );
        
        this.render( options.use_template );
        
        this.$el.attr( this.config.view_id_attr_key, this.model.get( 'id' ) );

        this.listenTo( this.model, 'change:select', this.markSelected );
        this.listenTo( this.model, 'change:show', this.changeShow );
        this.listenTo( this.model, 'change:id', this.render );
        this.listenTo( this.model, 'change:name', this.render );
        this.listenTo( this.model, 'change:image_src', this.render );
        this.listenTo( this.model, 'destroy', this.destroyModel );
    },
    
    render: function( render_template ) {
        if ( render_template !== false ) {
            this.$el.addClass( this.config.container_class );
            this.$el.html( this.template( this.model.toJSON() ) );
        }
        
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