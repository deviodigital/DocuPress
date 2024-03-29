function DocuPressVote(ID, type) {
	// For the LocalStorage.
	var itemName = "articlerating" + ID;
	var container = "#article-rating-" + ID;

	// Check if the LocalStorage value exist.
	if ( ! localStorage.getItem(itemName) ) {

		// Set HTML5 LocalStorage so the user can not vote again unless it's manually cleared.
		localStorage.setItem(itemName, true);

		// Set the localStorage type as well.
		var typeItemName = "articlerating" + ID + "-" + type;
		localStorage.setItem(typeItemName, true);

		// Check type.
		if ( 1 === type ) {
			jQuery( ".article-rating-smile" ).addClass( "selected" );
			jQuery( ".article-rating-frown" ).addClass( "faded" );
		} else if ( 2 === type ) {
			jQuery( ".article-rating-frown" ).addClass( "selected" );
			jQuery( ".article-rating-smile" ).addClass( "faded" );
		}
	
		// Data for the Ajax Request.
		var data = {
			action: "docupress_article_rating_add_vote",
			postid: ID,
			type: type,
			nonce: DocuPressRatingAjax.nonce
		};

		jQuery.post(DocuPressRatingAjax.ajax_url, data, function(response) {

			var object = jQuery(container);

			jQuery(container).html("");

			jQuery(container).append(response);

			// Remove the class and ID so we don't have 2 DIVs with the same ID.
			jQuery(object).removeClass("article-rating-container");
			jQuery(object).attr("id", "");

			// Add the class to the clicked element.
			var newContainer = "#article-rating-" + ID;

			// Check the type.			
			if ( 1 === type ) {
				var articleRatingClass = ".article-rating-smile";
			} else {
				var articleRatingClass = ".article-rating-frown";
			}
			// Add class.
			jQuery(newContainer + articleRatingClass ).addClass("article-rating-voted");
		});
	} else {
		// Do nothing.
	}
}

// Run custom jQuery.
jQuery(document).ready(function() {
	// Get all article containers.
	jQuery( ".article-rating-container" ).each(function( index ) {
		// Get data attribute.
		var contentID = jQuery(this).data("content-id");
		// Set item name.
		var itemName = "articlerating"+contentID;
		// Check if this content has localstorage.
		if (localStorage.getItem(itemName)) {
			// Check if it's a Smile or Frown vote.
			if ( localStorage.getItem("articlerating" + contentID + "-1") ) {
				jQuery(this).find(".article-rating-smile").addClass("article-rating-voted");
				jQuery(this).find(".article-rating-frown").addClass("faded");
			}
			if ( localStorage.getItem("articlerating" + contentID + "-2") ) {
				jQuery(this).find(".article-rating-frown").addClass("article-rating-voted");
				jQuery(this).find(".article-rating-smile").addClass("faded");
			}
		}
	});
});
