jQuery( document ).ready( function( $ ) {
    $ ( '#wizzaro-partners-partner-data.postbox .wppd-ma-add-item a' ).on( 'click', function( event ) {
        event.preventDefault();
        event.stopPropagation();
        
        var container = $( this ).parents( '.wppd-multiple-attrs' ).first();
        
        if ( container.length > 0 ) {
            var item = container.find( '.wppd-ma-item' ).first();
            
            if ( item.length > 0 ) {
                item = item.clone();
                item.find( 'input' ).val( '' );
                container.find( '.wppd-ma-items' ).append( item );
            }
        }
    } );
} );
