=== DocuPress ===
Contributors: deviodigital
Donate link: https://deviodigital.com
Tags: documentation, document, knowledgebase, help, support, notes
Requires at least: 3.0.1
Tested up to: 5.6
Stable tag: 2.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Documentation Simplified.

== Description ==

Create your own online help documents from your WordPress dashboard with DocuPress.

Separate your documentation into collections, making it easy for users to browse only the content they're looking for.

DocuPress also comes with 2 custom widgets:

*   Collections - output's a list of every collection you create with DocuPress.
*   Recent Articles - select how many articles to display, randomize the output order and also choose which collection you'd like to display articles from.
*   Related Articles - select how many articles to display and randomize the output order in this widget that will only display when viewing a single article.

== Installation ==

1. Go to `Plugins - Add New` in your WordPress admin panel and search for "DocuPress"
2. Install and activate the plugin directly in your admin panel
3. Pat yourself on the back for a job well done :)

== Screenshots ==

1. DocuPress adds a `Documentation` tab to your admin dashboard
2. DocuPress widget, available via `Appearance - Widgets`
3. DocuPress shortcode display
4. DocuPress single article display

== Changelog ==

= 2.1 =
*   Added new Spanish translation in `languages/docupress-es_ES.pot`
*   Updated text strings for localization in `languages/docupress.pot`

= 2.0 =
*   Added "Was this article helpful?" section to the bottom of single articles in `admin/docupress-article-ratings.php`
*   Added an estimated reading time to the beginning of single articles in `admin/docupress-article-ratings.php`
*   Added 2 filters to the `[docupress]` shortcode in `admin/docupress-shortcodes.php `
*   Updated shortcode list style in `public/css/docupress-public.css`
*   Updated activator and deactivator class names in `docupress.php`
*   Updated metabox with checkbox to hide estimated reading time in `admin/docupress-metaboxes.php`
*   Updated `.pot` file for translation in `languages/docupress.pot`
*   General code cleanup throughout multiple files in the plugin

= 1.3 =
*   Added new `[docupress]` shortcode in `admin/docupress-shortcodes.php`
*   Updated `.pot` file for translation in `languages/docupress.pot`
*   General code cleanup throughout multiple files in the plugin

= 1.2.2 =
*   Bugfix removed rest_base and rest_controller_class from custom post type in `admin/docupress-cpt.php`
*   Updated code to escape input data for path and url in `admin/docupress-metaboxes.php`
*   Updated check to hide undefined variable name notice in `admin/docupress-metaboxes.php`
*   Updated code to Change names based on custom permalink based in `admin/docupress-cpt.php`
*   General code cleanup in `public/class-docupress-public.php`

= 1.2.1 =
*   Bugfix misspelled ID for noncename verification in `admin/docupress-metaboxes.php`
*   Updated function names with all lowercase letters in `admin/docupress-cpt.php`
*   Updated function names with all lowercase letters in `admin/docupress-taxonomies.php`
*   Updated cpt and tax function names in activator in `includes/class-docupress-activator.php`
*   Updated text strings for localization in `admin/docupress-metaboxes.php`
*   Updated text strings for localization in `admin/docupress-widgets.php`
*   Updated `.pot` file for translation in `languages/docupress.pot`
*   General doc clean up throughout various files

= 1.2 =
*   Added new Collections widget
*   Added new Additional Details metabox for articles
*   Added custom permalink settings to change article base

= 1.1.0 =
*   Removed comment capabilities
*   Updated call constructor method for the DocuPress widget
*   Updated `get_bloginfo()` instance to URL instead of HOME
*   Added `global $post;` and `wp_reset_postdata();` to the widget query

= 1.0 =
*   Initial release