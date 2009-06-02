=== Dynamic Headers ===
Contributors: k0pper,css_wizard
Donate link: http://nicasiodesign.com/blog/
Tags: header, images, dynamic, Post, posts, plugin, page, image, aesthetic, small, fast, custom
Requires at least: 2.3
Tested up to: 2.7.1
Stable tag: 3.1

Dynamic Headers does just what you think it would based on the name - it allows you to create highly dynamic header space on your WordPress site.

== Description ==

Dynamic headers fills a void that has been present for a while in WordPress. It is a small, easy to use plugin that allows you to manage what header media is shown on each page/post. Unlike some other plugins however, this plugin allows you to use any image file OR a .swf Flash file. So you are no longer limited to a certain media type for your headers and you are no longer limited to one site-wide header. Enjoy.

**Important Upgrade Note:** After version 2.7 the image folder has moved. You will need to create the directory `/wp-content/header-images/` and make it writable. You will need to backup `/wp-content/plugins/dynamic-headers/header-images/` and move your header files to the new directory before upgrading.

**Change Log for Version 3.1:**

-  Fixed bug causing the link target to be printed when using image links.
-  Made plugin compatible with WordPress MU and WordPress 2.8 Beta 2
-  This upgrade is advised for all using the plugin

**Features:**

-  Set different headers for each page and post.
-  Use different media types on each page (image files, flash files).
-  Cross browser compliant embed code automatically generated.
-  Can set default header for pages/posts without set header image.
-  Fails gracefully if no header media present for current page.
-  Random media for individual pages/posts and default media.
-  Alt / Title tag management for images.
-  Supports both built in browser uploader or FTP.
-  Quick and lightweight.
-  Simply add template tag to theme to pull dynamic media.
-  Tested and validated to work on WordPress version 2.3+
-  Ability to add links to your header images.
-  WordPress MU Compatible

== Installation ==

These are the directions for the install. Be sure to read Directions for Use before using.

1. Upload 'the custom-header' directory to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Create the directory `/wp-content/header-images/` and make it writable (777).
1. Read the Other Notes page for Directions for Use. These directions are also contained in the plugin.

== Frequently Asked Questions ==

= Does this plugin automatically resize my files? =

No. To keep the process simple for both us and you (and to insure you have a large amount of flexibility in the use of our template tags) we do not resize your media files. This could be an option on future release.

= Why am I seeing an image I didn't set on an archive page or the home page? =

Due to the way WordPress handles post ID's on an archive page, or the blog homepage, the first post is what will determine what dynamic media is pulled. You can overwrite the image for the homepage on the plugin's Settings page. We plan to add override support for the other archive pages on a future release.

= Does the template tag have to go in header.php? =

Absolutely not. You are limited only by your imagination (and theme editing abilities) on how/where you want to drop the dynamic media tag in your theme.

= Can I upload media via FTP instead of using the browser based uploader? =

Sure, just upload your media to `/wp-content/header-images/`.

== Directions for Use ==

1. After installing the plugin you will need to create `/wp-content/header-images/` and make it writable. If you are unsure on how to do this, consult your hosting company, your help files for your hosting, your FTP client documentation, etc. This process can vary from server to server. If the directory is not writable you should be seeing an error message at the top of the plugin admin pages.
1. Upload media (using the filtypes listed above) on the Add New File Page.
1. Go to the Settings Page (under the Headers menu created by the plugin) and set your default header image. If you do not set a default header image, no media will be shown for posts and pages that do not have a header image associated with it.Alternatively, you can also add images to the `/wp-content/header-images/` directory using your FTP client or other file manager.
1. Create or edit a post or page and at the bottom of the page you will see a new box called "Dynamic Header by Nicasio Design". Select from the drop down one of your uploaded media files. This media will be shown only on the page or posts you set it to appear on.
1. Now you will need to add the template tag created by the plugin to your theme file where you want your dynamic header to appear (this will usually be in `/wp-content/themes/your-theme-name/header.php`).

You have 2 options for adding your dynamic header:

**Option 1 (Recommended)**: Simply drop the this line of code into your theme file

`<?php show_media_header(); ?>`

This will automatically determine what type of media you are using and generate the appropriate code to insert it. No other coding is required on your part.

**Option 2**: You can use this line of code to simply get the URL of the media for a particular post or page. This will allow you to do some more advanced things and embed the media yourself if you know what you are doing.

`<?php $dynamic_header_url = get_media_header_url(); ?>`

You can then use the variable `$dynamic_header_url` however you see fit. It will contain the full path to your media file.

It is advised that most users simply use Option 1 as it is significantly more simple.

**Note:** This function can return NULL or the string "None" if there are no headers for the current page.

**Important Notes**: On archive pages, the header media is controlled by the first post in the list. We plan to add control for archives pages separately in a future release, but for now, be aware that the first post on an archives page controls that page's header.

== Screenshots ==

1. Add new media to be used as a dynamic header.
2. Settings page for the plugin.
3. The Dynamic Header box on the post/page editing page.