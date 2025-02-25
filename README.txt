=== DocuPress ===
Contributors: deviodigital
Donate link: https://deviodigital.com
Tags: documentation, docs, kb, knowledgebase, help, support, documents, notes
Requires at least: 3.0.1
Tested up to: 6.7.2
Stable tag: 3.1.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Documentation Simplified.

== Description ==

**DocuPress** helps you create your own documentation directly within your WordPress dashboard.

Separate your articles into collections, making it easy for users to browse only the content they're looking for.

### Includes new Gutenberg block, widgets, and a shortcode

**DocuPress** includes a Gutenberg block to display your articles within the new WordPress editor.

Included block options:

* Article Count
* Display style (grid/list)
* Display by Collection
* Display or hide featured image
* Display or hide excerpt

See the screenshots for various examples of the block in action

**DocuPress** also includes the `[docupress]` shortcode, making it easy to include a list of articles anywhere on your site.

Full list of shortcode options: `[docupress limit="99" collections="" order="" viewall="on"]`

**DocuPress** also includes 3 custom widgets:

*   **Collections** - output's a list of every collection you create with DocuPress.
*   **Recent Articles** - select how many articles to display, randomize the output order and also choose which collection you'd like to display articles from.
*   **Related Articles** - select how many articles to display and randomize the output order in this widget that will only display when viewing a single article.

### Translations

Currently, **DocuPress** comes prepackaged with the following translations:

*   English
*   Spanish
*   French
*   Italian
*   Hindi

Please [let us know](https://www.deviodigital.com/contact/) if there's a language you'd like to see **DocuPress** translated to next.

== Installation ==

1. Go to `Plugins - Add New` in your WordPress admin panel and search for `DocuPress`
2. Install and activate the plugin directly in your admin panel
3. Pat yourself on the back for a job well done :)

== Screenshots ==

1. DocuPress single article display
2. DocuPress "Documentation" tab in the admin dashboard
3. Gutenberg Block - Grid display (back-end)
4. Gutenberg Block - Grid display (front-end)
5. Gutenberg Block - List display (back-end)
6. Gutenberg Block - List display (front-end)

== Changelog ==

= 3.1.0 =

* [🐛 BUG: Fixed docupress_article_rating_display function name usage in the add_vote ajax](https://github.com/deviodigital/DocuPress/commit/759badbeb443bbac6f9538fec998ef789667856e)
* [🐛 BUG: Fixed fatal error in block when viewing editor after saving the content](https://github.com/deviodigital/DocuPress/commit/c0df2e34f7b5132a24c7e36af0ad958fecf1567d)
* [📦 NEW: Added WPCom Check to restrict plugin usage on wordpress.com](https://github.com/deviodigital/DocuPress/commit/bed961023dd760711c7ea451c7005c794c918cfa)
* [📦 NEW: Added PluginUpdateChecker to serve plugin updates from GitHub](https://github.com/deviodigital/DocuPress/commit/cf8c99efbd30f65c3596ce525fae31ce8d1d0e8e)
* [👌 IMPROVE: Updated array to shorthand syntax](https://github.com/deviodigital/DocuPress/commit/6654c1fe948f565b8ab45a34065848c2942936fa)
* [👌 IMPROVE: Updated content being added to the_content](https://github.com/deviodigital/DocuPress/commit/8f31f691373613cf84e85c1901a1e5a6baa21517)
* [👌 IMPROVE: Updated JS enqueues to only run when DocuPress elements are present](https://github.com/deviodigital/DocuPress/commit/cc865a1dea1dfac97f033906d2733619ab762acf)
* [👌 IMPROVE: Updated variable names to fix warnings](https://github.com/deviodigital/DocuPress/commit/c428c81a22ed2511e2ea7b4d41d76868a97a65f2)
* [👌 IMPROVE: Updated details metabox for better visual display](https://github.com/deviodigital/DocuPress/commit/d6e3bb8c6bd7c57fff702676dca9d7673f1fb50f)
* [👌 IMPROVE: Updated text strings for localization](https://github.com/deviodigital/DocuPress/commit/59e82de19fdeb83c73d8b236a4cd687f218b66e3)
* [👌 IMPROVE: General code cleanup](https://github.com/deviodigital/DocuPress/commit/a4b4182e3723e05d10fc0992ce4c6460b59d151b)

= 3.0.0 =

* [📦 NEW: Articles block for Gutenberg](https://github.com/deviodigital/DocuPress/commit/6400cb4cffde724787eaafd71c38fa7b3b5094b5)
* [📖 DOC: Added 'Hindi' to language list](https://github.com/deviodigital/DocuPress/commit/2665ca7128b5b8a0c5dcd185ed07f6453e1c3e08)
* [👌 IMPROVE: Updated screenshots](https://github.com/deviodigital/DocuPress/commit/697f3e4b8eaa9a427ef5a280fac78365389c15ea)
* [👌 IMPROVE: Updated metabox with global](https://github.com/deviodigital/DocuPress/commit/5dbdec7b35472338fe879c404ae6f24534d6e2cd)
* [👌 IMPROVE: Updated for PHP 8 compatibility](https://github.com/deviodigital/DocuPress/commit/2dfd0a6b5321bdd1016cbd52924ac2f16f2a8e4f)
* [👌 IMPROVE: Updated to remove escaping redundancy](https://github.com/deviodigital/DocuPress/commit/fd1fdf16fc0a5fe3bc72459e96dd7fd7fdcacf08)
* [👌 IMPROVE: General code cleanup](https://github.com/deviodigital/DocuPress/commit/345757647bb6e3db1fafea0fe1b1c633fe32ad23)
*  [👌 IMPROVE: General code cleanup](https://github.com/deviodigital/DocuPress/commit/50deaf7a3a4109be0b60bb6d711b2f9df20a0274)
* [👌 IMPROVE: Updated languages for translation](https://github.com/deviodigital/DocuPress/commit/6fbf3b47401fe60eb78b35638c04a001992bcd8d)

= 2.3.0 =
*   Added new Hindi translation in `languages/docupress-hi_IN.pot`
*   Updated various security related issues found with [Codacy](https://codacy.com) throughout multiple files in the plugin
*   General code cleanup throughout multiple files in the plugin

= 2.2.1 =
*   Actually added the new French translation in `languages/docupress-fr_FR.pot`
*   Actually added the new Italian translation in `languages/docupress-it_IT.pot`
*   Updated text strings for localization in `languages/docupress.pot`
*   Updated various styles in `public/css/docupress-public.css`
*   General code cleanup throughout multiple files in the plugin

= 2.2 =
*   Added new Spanish translation in `languages/docupress-es_ES.pot`
*   Added new French translation in `languages/docupress-fr_FR.pot`
*   Added new Italian translation in `languages/docupress-it_IT.pot`
*   Updated text strings for localization in `languages/docupress.pot`

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