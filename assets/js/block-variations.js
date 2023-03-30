const MY_VARIATION_NAME = 'jetpack/projects-list';


wp.blocks.registerBlockVariation( 'core/query', {
    name: MY_VARIATION_NAME,
    title: 'Projects List',
    description: 'Displays a list of projects',
    isActive: ( { namespace, query } ) => {
        return (
            namespace === MY_VARIATION_NAME
            && query.postType === 'jetpack-portfolio'
        );
    },
    icon: 'portfolio'/** An SVG icon can go here*/,
    attributes: {
        namespace: MY_VARIATION_NAME,
        query: {
            perPage: 6,
            pages: 0,
            offset: 0,
            postType: 'jetpack-portfolio',
            order: 'desc',
            orderBy: 'date',
            author: '',
            search: '',
            exclude: [],
            sticky: '',
            inherit: false,
        },
    },
    scope: [ 'inserter' ],
    innerBlocks: [
        [
          'core/post-template',
          {},
          [
            [ 'core/post-title' ],
						[ 'core/post-featured-image' ],
          ],
        ]
      ]
    }
);
