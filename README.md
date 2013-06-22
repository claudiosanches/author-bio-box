# Author Bio Box #
**Contributors:** claudiosanches  
**Tags:** author, bio, social  
**Requires at least:** 3.5  
**Tested up to:** 3.5.2  
**Stable tag:** 2.0.0  
**License:** GPLv2 or later  
**License URI:** http://www.gnu.org/licenses/gpl-2.0.html  

Display a box with the author's biography in your WordPress

## Description ##

Display a box with the author's biography and also social icons in bottom of the post.

### Contribute ###

You can contribute to the source code in our [GitHub](https://github.com/claudiosmweb/social-count-plus) page.

### Credits ###

* Initial idea by [Gustavo Freitas](http://gfsolucoes.net/).

## Installation ##

* Upload plugin files to your plugins folder, or install using WordPress built-in Add New Plugin installer;
* Activate the plugin;
* Navigate to Settings -> Author Bio Box and fill the options.

## Add the box directly ##

Use this function:

    <?php if ( function_exists( 'get_author_bio_box' ) ) echo get_author_bio_box(); ?>

## Frequently Asked Questions ##

### What is the plugin license? ###

* This plugin is released under a GPL license.

## Screenshots ##

### 1. Settings page. ###
![1. Settings page.](http://s.wordpress.org/extend/plugins/author-bio-box/screenshot-1.png)

### 2. Plugin in action. ###
![2. Plugin in action.](http://s.wordpress.org/extend/plugins/author-bio-box/screenshot-2.png)


## Changelog ##

### 2.0.0 - 21/06/2013 ###

* Source code reformulation.
* Improved performance with fewer options in the database.
* Added Brazilian Portuguese and English languages.
* Added `Text Color` option.
* Added `Title Color` option.

### 1.7.1 ###

* Fixed the jQuery in options page.
* Fixed the styles.
* Fixed the wp_nonce in options page.

### 1.7.0 ###

* Fix a bug with display options.

### 1.6.0 ###

* Improved the performance.
* Added a function to insert the box in the theme.

### 1.5.0 ###

* Added `strip_tags` in options page.

### 1.4.0 ###

* Added `wp_nonce_field` and `check_admin_referer` in options page.

### 1.3.0 ###

* Added a option to shows the box in homepage.

### 1.2.0 ###

* Insert `target="_blank"` in author description.

### 1.1.0 ###

* Improved the code.
* Removed obsolete WP tags.
* Insert `target="_blank"` in social icons.

### 1.0.0 ###

* Initial version.

## License ##

Author Bio Box is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

Author Bio Box is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with Author Bio Box. If not, see <http://www.gnu.org/licenses/>.
