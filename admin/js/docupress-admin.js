(function (blocks, element, components, editor) {
    var el = wp.element.createElement;
    var InnerBlocks = editor.InnerBlocks;
    var PanelBody = components.PanelBody;
    var SelectControl = components.SelectControl;
    var RangeControl = components.RangeControl;
    var CheckboxControl = components.CheckboxControl;
    var useState = wp.element.useState;
    var useEffect = wp.element.useEffect;
    var { useSelect, dispatch } = wp.data;

    function fetchPosts(postCount, setPreviewContent, showFeaturedImage, showExcerpt, collection, setAttributes) {
        var apiUrl = '/wp-json/wp/v2/docupress?per_page=' + postCount;

        if (collection) {
            fetch('/wp-json/wp/v2/docupress_collections?slug=' + collection)
                .then(function(response) {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(function(collections) {
                    var termId = collections.length > 0 ? '&docupress_collections=' + collections[0].id : '';

                    apiUrl += termId;

                    // Proceed with fetching posts using the updated apiUrl
                    fetchPostsFromApi(apiUrl);
                })
                .catch(function(error) {
                    console.error('Error fetching collections:', error);
                });
        } else {
            // Fetch posts without specifying the collection
            fetchPostsFromApi(apiUrl);
        }

        function fetchPostsFromApi(apiUrl) {
            fetch(apiUrl)
                .then(function (response) {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(function (posts) {
                    var postPromises = posts.map(function (post) {
                        var strippedExcerpt = post.excerpt.rendered.replace(/<\/?[^>]+(>|$)/g, '');

                        if (showFeaturedImage && post.featured_media) {
                            // Retrieve the media item by ID
                            return fetch('/wp-json/wp/v2/media/' + post.featured_media)
                                .then(function(response) {
                                    if (!response.ok) {
                                        throw new Error('Network response was not ok');
                                    }
                                    return response.json();
                                })
                                .then(function(media) {
                                    var imageSrc = media.source_url; // Get the source URL of the media item

                                    return el('img', { src: imageSrc, alt: post.title.rendered });
                                })
                                .catch(function(error) {
                                    console.error('Error fetching media item:', error);
                                    return null;
                                });
                        }

                        return null;
                    });

                    Promise.all(postPromises).then(function(images) {
                        var previewContent = posts.map(function (post, index) {
                            var content = [];
                            var parser = new DOMParser();
                            var decodedExcerpt = parser.parseFromString(post.excerpt.rendered, 'text/html').body.textContent;
                            decodedExcerpt = decodedExcerpt.replace('[&hellip;]', ' - ');
                            
                            if (images[index]) {
                                content.push(images[index]);
                            }

                            content.push(
                                el('div', { className: 'list-content' },
                                  el('h3', {},
                                    el('a', { href: post.link }, post.title.rendered)
                                  ),
                                  showExcerpt && el('p', {}, decodedExcerpt)
                                )
                            );      
                            
                            console.log(content);

                            return content;
                        });

                        setPreviewContent(previewContent);
                        setAttributes({ previewContent: previewContent });
                    });
                })
                .catch(function (error) {
                    console.error('Error fetching posts:', error);
                    setPreviewContent([]);
                });
        }
    }

    blocks.registerBlockType('docupress/articles', {
        title: 'DocuPress Articles',
        icon: 'format-aside',
        category: 'common',
        attributes: {
            postCount: {
                type: 'number',
                default: 6
            },
            displayStyle: {
                type: 'string',
                default: 'grid'
            },
            showFeaturedImage: {
                type: 'boolean',
                default: true
            },
            showExcerpt: {
                type: 'boolean',
                default: true
            },
            collection: {
                type: 'string',
                default: ''
            },
            previewContent: {
                type: 'array',
                default: []
            }
        },
        edit: function (props) {
            var attributes = props.attributes;
            var setAttributes = props.setAttributes;

            var [previewContent, setPreviewContent] = useState(attributes.previewContent);
            var [collection, setCollection] = useState('');

            useEffect(() => {
                fetchPosts(attributes.postCount, setPreviewContent, attributes.showFeaturedImage, attributes.showExcerpt, collection, setAttributes);
            }, [collection, attributes.postCount, attributes.showFeaturedImage, attributes.showExcerpt]);

            function fetchCollections() {
                var apiUrl = '/wp-json/wp/v2/docupress_collections';

                fetch(apiUrl)
                    .then(function (response) {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(function (collections) {
                        var collectionOptions = collections.map(function (collection) {
                            return { value: collection.slug, label: collection.name };
                        });

                        setCollectionOptions(collectionOptions);
                    })
                    .catch(function (error) {
                        console.error('Error fetching collections:', error);
                        setCollectionOptions([]);
                    });
            }

            var [collectionOptions, setCollectionOptions] = useState([]);
            useEffect(fetchCollections, []);

            function onChangePostCount(value) {
                setAttributes({ postCount: value });
                fetchPosts(value, setPreviewContent, attributes.showFeaturedImage, attributes.showExcerpt, collection);
            }

            function onChangeDisplayStyle(value) {
                setAttributes({ displayStyle: value });
            }

            function onChangeShowFeaturedImage(value) {
                setAttributes({ showFeaturedImage: value });
                fetchPosts(attributes.postCount, setPreviewContent, value, attributes.showExcerpt, collection);
            }

            function onChangeShowExcerpt(value) {
                setAttributes({ showExcerpt: value });
                fetchPosts(attributes.postCount, setPreviewContent, attributes.showFeaturedImage, value, collection);
            }

            function onChangeCollection(value) {
                setAttributes({ collection: value });
                setCollection(value);
            }

            var blockClassName = 'docupress-block-' + attributes.displayStyle;

            return el('div', { className: props.className },
                el(wp.blockEditor.InspectorControls, null,
                    el(PanelBody, { title: 'DocuPress Block Settings', initialOpen: true },
                        el(RangeControl, {
                            label: 'Post Count',
                            value: attributes.postCount,
                            onChange: onChangePostCount,
                            min: 1,
                            max: 24
                        }),
                        el(SelectControl, {
                            label: 'Display Style',
                            value: attributes.displayStyle,
                            onChange: onChangeDisplayStyle,
                            options: [
                                { value: 'list', label: 'List' },
                                { value: 'grid', label: 'Grid' }
                            ]
                        }),
                        el(SelectControl, {
                            label: 'Collection',
                            value: attributes.collection,
                            onChange: onChangeCollection,
                            options: [
                                { value: '', label: 'All' }, // Option to fetch all posts without specifying a collection
                                ...collectionOptions
                            ]
                        }),
                        el(CheckboxControl, {
                            label: 'Show Featured Image',
                            checked: attributes.showFeaturedImage,
                            onChange: onChangeShowFeaturedImage
                        }),
                        el(CheckboxControl, {
                            label: 'Show Excerpt',
                            checked: attributes.showExcerpt,
                            onChange: onChangeShowExcerpt
                        })
                    )
                ),
                el('div', { className: blockClassName },
                    previewContent.map(function (content, index) {
                        return el('div', { className: 'docupress-block-post' }, content);
                    })
                )

            );
        },
        save: function (props) {
            var attributes = props.attributes;
            var blockClassName = 'docupress-block-' + attributes.displayStyle;
            console.log(attributes);

            return el('div', { className: props.className },
                el('div', { className: blockClassName },
                    attributes.previewContent.map(function (content, index) {
                        console.log(attributes.previewContent);
                        return el('div', { className: 'docupress-block-post', key: index }, content);
                    })
                )
            );
        }        
    });
})(window.wp.blocks, window.wp.element, window.wp.components, window.wp.editor);
