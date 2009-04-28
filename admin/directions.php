<div class="wrap">
	<h2>Dynamic Headers - Directions For Use</h2>
	<h3>Please read all directions prior to use!</h3>
	<?php include("header.php"); ?>
	<p><strong>Description</strong>: This plugin will allow you to upload various media type (jpg's, gif's, png's and Flash files) to use as headers throughout your site. You can assign different headers of different filetypes to be used on different posts/pages. You can also set a default header to use on posts/pages that do not have a set header.</p>
		<strong>1.</strong> After installing the plugin you will need to make sure /wp-content/plugins/dynamic-headers/header-images/ is writable. If you are unsure on how to do this, consult your hosting company, your help files for your hosting, your FTP client documentation, etc. This process can vary from server to server.<br /><br />
If the directory is not writable you should be seeing an error message at the top of the plugin admin pages.<br /><br />
		<strong>2.</strong> Upload media (using the filtypes listed above) on the <a href="/wp-admin/admin.php?page=dhnd_add_menu">Add New File Page</a>.<br /><br />
		<strong>3.</strong> Go to the <a href="/wp-admin/admin.php?page=dhnd_options">Settings Page</a> and set your default header image. If you do not set a default header image, no media will be shown for posts and pages that do not have a header image associated with it.<br /><br />
		Alternatively, you can also add images to the /wp-content/plugins/dynamic-headers/header-images/ directory using your FTP client or other file manager.<br /><br />
		<strong>4.</strong> Create or edit a post or page and at the bottom of the page you will see a new box called "Dynamic Header by Nicasio Design". Select from the drop down one of your uploaded media files. This media will be shown only on the page or posts you set it to appear on.<br /><br />
		<strong>5.</strong> Now you will need to add the template tag created by the plugin to your theme file where you want your dynamic header to appear (this will usually be in wp-content/themes/your-theme-name/header.php).<br /><br />
		You have 2 options for adding your dynamic header:<br /><br />
		<strong>Option 1</strong> (Recommended): Simply drop the this line of code into your theme file <br /><br /><code>&lt;?php show_media_header(); ?&gt;</code><br /><br />This will automatically determine what type of media you are using and generate the appropriate code to insert it. No other coding is required on your part.<br /><br />
		<strong>Option 2:</strong> You can use this line of code to simply get the URL of the media for a particular post or page. This will allow you to do some more advanced things and embed the media yourself if you know what you are doing. <br /><br />
<code>&lt;?php $dynamic_header_url = get_media_header_url(); ?&gt;</code><br /><br />
		
You can then use the variable <code>$dynamic_header_url</code> however you see fit. It will contain the full path to your media file. <br />It is advised that most users simply use Option 1 as it is significantly more simple.<br /><strong>Note:</strong> This function can return NULL or the string 'None' if there are no headers for the current page. 
<br /><br />
<strong>Important Notes:</strong> On archive pages, the header media is controlled by the first post in the list. We plan to add control for archives pages separately in a future release, but for now, be aware that the first post on an archives page controls that page's header.
<br /><br />
</div>