=== Author Bio Box ===
Contributors: claudiosanches
Donate link: http://claudiosmweb.com/doacoes/
Tags: author, bio, social
Requires at least: 3.8
Tested up to: 4.0
Stable tag: 3.3.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Display a box with the author's biography in your WordPress

== Description ==

Display a box with the author's biography and also social icons in bottom of the post.

= Contribute =

You can contribute to the source code in our [GitHub](https://github.com/claudiosmweb/author-bio-box) page.

= Credits =

* Initial idea by [Gustavo Freitas](http://gfsolucoes.net/).

== Installation ==

* Upload plugin files to your plugins folder, or install using WordPress built-in Add New Plugin installer;
* Activate the plugin;
* Navigate to Settings -> Author Bio Box and fill the options;
* Fill the **Biographical Info** and **Contact Info** in Users -> Your Profile.

= Add the box directly =

Use this function:

	<?php 
		if ( function_exists( 'get_author_bio_box' ) ) {
			echo get_author_bio_box();
		}
	?>

== Frequently Asked Questions ==

= What is the plugin license? =

* This plugin is released under a GPL license.

== Screenshots ==

1. Settings page.
2. New fields in "Your Profile" page.
3. Plugin in action.

== Changelog ==

= 3.3.0 - 2014/02/26 =

* Added Instagram and Pinterest icons (Thanks Jeremy Caris).

= 3.2.0 - 2014/02/26 =

* Added the `authorbiobox_display` filter.

= 3.2.0 - 2014/02/26 =

* Added Flickr, Tumblr, Vimeo and YouTube icons (thanks [@rafaelfunchal](https://github.com/rafaelfunchal)).
* Created the `authorbiobox_social_data` filter for custom icons ordering.

= 3.1.1 - 2014/01/08 =

* Fixed the Facebook and Twitter icons.

= 3.1.0 - 2014/01/04 =

* Added website icon.

= 3.0.0 - 2013/12/13 =

* Improved all code.
* Add support to WordPress 3.8.

= 2.0.0 - 2013/06/21 =

* Source code reformulation.
* Improved performance with fewer options in the database.
* Added Brazilian Portuguese and English languages.
* Added `Text Color` option.
* Added `Title Color` option.

= 1.7.1 =

* Fixed the jQuery in options page.
* Fixed the styles.
* Fixed the wp_nonce in options page.

= 1.7.0 =

* Fix a bug with display options.

= 1.6.0 =

* Improved the performance.
* Added a function to insert the box in the theme.

= 1.5.0 =

* Added `strip_tags` in options page.

= 1.4.0 =

* Added `wp_nonce_field` and `check_admin_referer` in options page.

= 1.3.0 =

* Added a option to shows the box in homepage.

= 1.2.0 =

* Insert `target="_blank"` in author description.

= 1.1.0 =

* Improved the code.
* Removed obsolete WP tags.
* Insert `target="_blank"` in social icons.

= 1.0.0 =

* Initial version.

== License ==

Author Bio Box is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

Author Bio Box is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with Author Bio Box. If not, see <http://www.gnu.org/licenses/>.
