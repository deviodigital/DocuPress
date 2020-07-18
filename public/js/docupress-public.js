function article_rating_vote(ID, type) {
	// For the LocalStorage.
	var itemName = "articlerating" + ID;

	var container = '#article-rating-' + ID;

	// Check if the LocalStorage value exist. If do nothing.
	if (!localStorage.getItem(itemName)) {

		// Set HTML5 LocalStorage so the user can not vote again unless it's manually cleared.
        localStorage.setItem(itemName, true);

        // Set the localStorage type as well.
		var typeItemName = "articlerating" + ID + "-" + type;
		localStorage.setItem(typeItemName, true);
	
		// Data for the Ajax Request.
		var data = {
			action: 'article_rating_add_vote',
			postid: ID,
			type: type,
			nonce: article_rating_ajax.nonce
		};

		jQuery.post(article_rating_ajax.ajax_url, data, function(response) {

			var object = jQuery(container);

			jQuery(container).html('');

			jQuery(container).append(response);

			// Remove the class and ID so we don't have 2 DIVs with the same ID.
			jQuery(object).removeClass('article-rating-container');
			jQuery(object).attr('id', '');

			// Add the class to the clicked element.
			var new_container = '#article-rating-' + ID;

			// Check the type.			
			if( type == 1){ article_rating_class = ".article-rating-smile"; }
			else{ article_rating_class = ".article-rating-frown";  }

			jQuery(new_container +  article_rating_class ).addClass('article-rating-voted');

		});
	} else {
		// Display message if we detect LocalStorage.
		jQuery('#article-rating-' + ID + ' .article-rating-already-voted').fadeIn().css('display', 'block');
	}
}

jQuery(document).ready(function() {
	// Get all article containers.
	jQuery( ".article-rating-container" ).each(function( index ) {
		// Get data attribute.
		var content_id = jQuery(this).data('content-id');
		// Set item name.
		var itemName = "articlerating"+content_id;
		// Check if this content has localstorage.
		if (localStorage.getItem(itemName)){
			// Check if it's a Smile or Frown vote.
			if ( localStorage.getItem("articlerating" + content_id + "-1") ){
				jQuery(this).find('.article-rating-smile').addClass('article-rating-voted');
			}
			if ( localStorage.getItem("articlerating" + content_id + "-0") ){
				jQuery(this).find('.article-rating-frown').addClass('article-rating-voted');
			}
		}
	});
});
