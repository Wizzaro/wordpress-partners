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
