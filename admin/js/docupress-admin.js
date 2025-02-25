( function( blocks, element, components, blockEditor, data, i18n ) {
	'use strict';

	const el = element.createElement;
	const { useState, useEffect } = element;
	const { InspectorControls } = blockEditor;
	const { PanelBody, SelectControl, RangeControl, CheckboxControl } = components;
	const __ = i18n.__;

	/**
	 * Fetch posts and update preview state.
	 *
	 * @param {number} postCount Number of posts to fetch.
	 * @param {Function} setPreviewContent Callback to set preview content.
	 * @param {boolean} showFeaturedImage Whether to display featured images.
	 * @param {boolean} showExcerpt Whether to display excerpts.
	 * @param {string} collection The collection slug.
	 * @param {string} displayStyle Display style ('grid' or 'list').
	 */
	function fetchPosts( postCount, setPreviewContent, showFeaturedImage, showExcerpt, collection, displayStyle ) {
		let apiUrl = '/wp-json/wp/v2/docupress?per_page=' + postCount;

		if ( collection ) {
			fetch( '/wp-json/wp/v2/docupress_collections?slug=' + collection )
				.then( ( response ) => {
					if ( ! response.ok ) {
						throw new Error( 'Network response was not ok' );
					}
					return response.json();
				} )
				.then( ( collections ) => {
					const termId = collections.length > 0 ? '&docupress_collections=' + collections[0].id : '';
					apiUrl += termId;
					fetchPostsFromApi( apiUrl );
				} )
				.catch( ( error ) => {
					console.error( 'Error fetching collections:', error );
				} );
		} else {
			fetchPostsFromApi( apiUrl );
		}

		function fetchPostsFromApi( apiUrl ) {
			fetch( apiUrl )
				.then( ( response ) => {
					if ( ! response.ok ) {
						throw new Error( 'Network response was not ok' );
					}
					return response.json();
				} )
				.then( ( posts ) => {
					const postPromises = posts.map( ( post ) => {
						if ( showFeaturedImage && post.featured_media ) {
							return fetch( '/wp-json/wp/v2/media/' + post.featured_media )
								.then( ( response ) => {
									if ( ! response.ok ) {
										throw new Error( 'Network response was not ok' );
									}
									return response.json();
								} )
								.then( ( media ) => {
									let imageSrc = media.source_url;
									// Use our custom size for grid format if available.
									if (
										displayStyle === 'grid' &&
										media.media_details &&
										media.media_details.sizes &&
										media.media_details.sizes['docupress-grid']
									) {
										imageSrc = media.media_details.sizes['docupress-grid'].source_url;
									} else if (
										media.media_details &&
										media.media_details.sizes &&
										media.media_details.sizes.thumbnail
									) {
										imageSrc = media.media_details.sizes.thumbnail.source_url;
									}
									return el( 'img', { src: imageSrc, alt: post.title.rendered } );
								} )
								.catch( ( error ) => {
									console.error( 'Error fetching media item:', error );
									return null;
								} );
						}
						return Promise.resolve( null );
					} );

					Promise.all( postPromises ).then( ( images ) => {
						const postsContent = posts.map( ( post, index ) => {
							const parser = new DOMParser();
							let decodedExcerpt = parser
								.parseFromString( post.excerpt.rendered, 'text/html' )
								.body.textContent;
							decodedExcerpt = decodedExcerpt.replace( '[&hellip;]', ' - ' );
							return el(
								'div',
								{ className: 'docupress-post-wrapper' },
								images[ index ] ? images[ index ] : null,
								el(
									'div',
									{ className: 'list-content' },
									el(
										'h3',
										{},
										el(
											'a',
											{ href: post.link },
											post.title.rendered
										)
									),
									showExcerpt && el( 'p', {}, decodedExcerpt )
								)
							);
						} );
						setPreviewContent( postsContent );
					} );
				} )
				.catch( ( error ) => {
					console.error( 'Error fetching posts:', error );
					setPreviewContent( [] );
				} );
		}
	}

	/**
	 * The edit component for the DocuPress Articles block.
	 *
	 * @param {Object} props Block properties.
	 */
	function DocuPressArticlesEdit( props ) {
		const { attributes, setAttributes, className } = props;
		const { postCount, displayStyle, showFeaturedImage, showExcerpt, collection } = attributes;
		const [ previewContent, setPreviewContent ] = useState( [] );
		const [ currentCollection, setCurrentCollection ] = useState( collection );
		const [ collectionOptions, setCollectionOptions ] = useState( [] );

		useEffect( () => {
			fetchPosts( postCount, setPreviewContent, showFeaturedImage, showExcerpt, currentCollection, displayStyle );
		}, [ currentCollection, postCount, showFeaturedImage, showExcerpt, displayStyle ] );

		useEffect( () => {
			fetch( '/wp-json/wp/v2/docupress_collections' )
				.then( ( response ) => {
					if ( ! response.ok ) {
						throw new Error( 'Network response was not ok' );
					}
					return response.json();
				} )
				.then( ( collections ) => {
					const options = collections.map( ( coll ) => {
						return { value: coll.slug, label: coll.name };
					} );
					setCollectionOptions( options );
				} )
				.catch( ( error ) => {
					console.error( 'Error fetching collections:', error );
					setCollectionOptions( [] );
				} );
		}, [] );

		function onChangePostCount( value ) {
			setAttributes( { postCount: value } );
		}

		function onChangeDisplayStyle( value ) {
			setAttributes( { displayStyle: value } );
		}

		function onChangeShowFeaturedImage( value ) {
			setAttributes( { showFeaturedImage: value } );
		}

		function onChangeShowExcerpt( value ) {
			setAttributes( { showExcerpt: value } );
		}

		function onChangeCollection( value ) {
			setAttributes( { collection: value } );
			setCurrentCollection( value );
		}

		const blockClassName = 'docupress-block-' + displayStyle;

		return el(
			'div',
			{ className },
			el(
				InspectorControls,
				null,
				el(
					PanelBody,
					{ title: __( 'DocuPress Block Settings', 'docupress' ), initialOpen: true },
					el( RangeControl, {
						label: __( 'Post Count', 'docupress' ),
						value: postCount,
						onChange: onChangePostCount,
						min: 1,
						max: 24,
					} ),
					el( SelectControl, {
						label: __( 'Display Style', 'docupress' ),
						value: displayStyle,
						onChange: onChangeDisplayStyle,
						options: [
							{ value: 'list', label: __( 'List', 'docupress' ) },
							{ value: 'grid', label: __( 'Grid', 'docupress' ) },
						],
					} ),
					el( SelectControl, {
						label: __( 'Collection', 'docupress' ),
						value: collection,
						onChange: onChangeCollection,
						options: [
							{ value: '', label: __( 'All', 'docupress' ) },
							...collectionOptions,
						],
					} ),
					el( CheckboxControl, {
						label: __( 'Show Featured Image', 'docupress' ),
						checked: showFeaturedImage,
						onChange: onChangeShowFeaturedImage,
					} ),
					el( CheckboxControl, {
						label: __( 'Show Excerpt', 'docupress' ),
						checked: showExcerpt,
						onChange: onChangeShowExcerpt,
					} )
				)
			),
			el(
				'div',
				{ className: blockClassName },
				previewContent.map( ( content, index ) =>
					el( 'div', { className: 'docupress-block-post', key: index }, content )
				)
			)
		);
	}

	blocks.registerBlockType( 'docupress/articles', {
		title: __( 'DocuPress Articles', 'docupress' ),
		icon: 'format-aside',
		category: 'common',
		attributes: {
			postCount: {
				type: 'number',
				default: 6,
			},
			displayStyle: {
				type: 'string',
				default: 'grid',
			},
			showFeaturedImage: {
				type: 'boolean',
				default: true,
			},
			showExcerpt: {
				type: 'boolean',
				default: true,
			},
			collection: {
				type: 'string',
				default: '',
			},
		},
		edit: DocuPressArticlesEdit,
		save: function() {
			return null;
		},
	} );
} )( window.wp.blocks, window.wp.element, window.wp.components, window.wp.blockEditor, window.wp.data, window.wp.i18n );
