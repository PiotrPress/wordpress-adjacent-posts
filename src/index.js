import { __ } from '@wordpress/i18n';
import { registerBlockVariation } from '@wordpress/blocks';
import { addFilter } from '@wordpress/hooks';

registerBlockVariation( 'core/query', {
    name: 'piotrpress/prev-post-query',
    title: __( 'Previous Post Query', 'piotrpress-adjacent-posts' ),
    icon: 'arrow-left-alt',
    isActive: [ 'namespace' ],
    attributes: {
        namespace: 'piotrpress/prev-post-query',
        query: {
            perPage: 1
        },
    },
    scope: [ 'inserter' ],
    allowedControls: []
} );

registerBlockVariation( 'core/query', {
    name: 'piotrpress/next-post-query',
    title: __( 'Next Post Query', 'piotrpress-adjacent-posts' ),
    icon: 'arrow-right-alt',
    isActive: [ 'namespace' ],
    attributes: {
        namespace: 'piotrpress/next-post-query',
        query: {
            perPage: 1
        },
    },
    scope: [ 'inserter' ],
    allowedControls: []
} );

addFilter( 'editor.BlockEdit', 'core/query', ( BlockEdit ) => ( props ) => {
    switch( props.attributes?.namespace ) {
        case 'piotrpress/prev-post-query': props.attributes.query.include = [ prevPostID ]; break;
        case 'piotrpress/next-post-query': props.attributes.query.include = [ nextPostID ]; break;
    }
    return <BlockEdit key="edit" { ...props } />;
} );