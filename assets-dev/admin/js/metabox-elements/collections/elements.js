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