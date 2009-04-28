<?php
/*
Plugin Name: Dynamic Headers by Nicasio Design
Plugin URI: http://blog.nicasiodesign.com/category/wordpress-plugins/
Version: 2.6
Description: This plugin allows a custom header image to be displayed on each page
Author: Dan Cannon
Author URI: http://blog.nicasiodesign.com
License: GPL
	Copyright 2009  Dan Cannon of Nicasio Design and Development  (email : dcannon1@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

//Check to see if someone else has used my class name so that my plugin won't break WP
//All plugin devs should do this to avoid naming collisions!
if (!class_exists("custom_header")) {
	class custom_header {
		
		//the constructor that initializes the class
		function custom_header() {
		}
		
		//setup a db table to store file info in
		function ch_install () {
		   global $wpdb;

		   $table_name = $wpdb->prefix . "custom_headers";
		   
		   if($wpdb->get_var("show tables like '$table_name'") != $table_name) {
			  $sql = "CREATE TABLE " . $table_name . " (
			  id mediumint(9) NOT NULL AUTO_INCREMENT,
			  post_id bigint(11) DEFAULT '0' NOT NULL,
			  url VARCHAR(255) NOT NULL,
			  header_type VARCHAR(255) NOT NULL,
			  filetype VARCHAR(255) NOT NULL,
			  UNIQUE KEY id (id)
			);";

			  require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
			  dbDelta($sql);
			}
			
			//Create the max size option with a default value of 400k
			add_option('dhnd_max_size', '400');
			add_option('dhnd_default', 'None');
			add_option('dhnd_homepage', 'None');
			add_option('dhnd_footer_link', 'Yes');
		}
		
		function headerCode(){
			echo '<script language="javascript">AC_FL_RunContent = 0;</script>';
			echo '<script type=\'text/javascript\' src="'. get_bloginfo('wpurl') . '/wp-content/plugins/dynamic-headers/AC_RunActiveContent.js"></script>';
		}
		
		//Update the db and parse the data
		function save_ch($post_id) {
			//Declare the needed global stuff
			global $wpdb;
			global $_POST;
			$table_name = $wpdb->prefix . "custom_headers";
			
			//We don't want to create extra database entries for the revisions that are created with page saves
			if(!wp_is_post_revision($post_id)){
				
				/*-------- Media Header Code Block ------------- */
				//Declare the url and use it to get the filetype
				$url = $_POST['mediaHeader'];
				$file_array = explode(".", $url);
				
				//Get the filetype
				$filetype = end($file_array);

				//Get this page's media header
				$check_q = $wpdb->get_row("SELECT * FROM $table_name WHERE post_id='$post_id' LIMIT 1");
				$this_id = $check_q->id;
				
				if($check_q != NULL){
					$wpdb->query("
						UPDATE $table_name SET post_id='$post_id', url='$url', filetype ='$filetype' WHERE id='$this_id'");
				} else {
					$wpdb->query("
						INSERT INTO $table_name (post_id, url, filetype, header_type)
						VALUES ('$post_id', '$url', '$filetype', 'media')");
				}
			}
		}
	}
} //End Class custom_header

//This function will actually build the admin menu
function dhnd_menu() {
  add_menu_page('Headers', 'Headers', 8, __FILE__, 'dhnd_main_menu', '/wp-content/plugins/dynamic-headers/images/custom_header_icon.png');
  add_submenu_page(__FILE__, 'Directions', 'Directions', 8, __FILE__, 'dhnd_main_menu');
  add_submenu_page(__FILE__, 'Add New File', 'Add New File', 8, 'dhnd_add_menu', 'dhnd_add_menu');
  add_submenu_page(__FILE__, 'Manage Files', 'Manage Files', 8, 'dhnd_manage_files', 'dhnd_manage_files');
  add_submenu_page(__FILE__, 'Settings', 'Settings', 8, 'dhnd_options', 'dhnd_options');
  add_submenu_page(__FILE__, 'About', 'About', 8, 'dhnd_about', 'dhnd_about');
}

function dhnd_about() {
	include(ABSPATH."/wp-content/plugins/dynamic-headers/admin/about.php");
}

function dhnd_main_menu() {
	include(ABSPATH."/wp-content/plugins/dynamic-headers/admin/directions.php");
}

function dhnd_add_menu() {
	include(ABSPATH."/wp-content/plugins/dynamic-headers/admin/main.php");
}

function dhnd_options() {
	include(ABSPATH."/wp-content/plugins/dynamic-headers/admin/options.php");
}

function dhnd_manage_files() {
	include(ABSPATH."/wp-content/plugins/dynamic-headers/admin/manage.php");
}
 

//initialize the class to a variable
if (class_exists("custom_header")) {
	$cl_custom_header = new custom_header();
}

//This function actually creates the 
function create_ch_form() {
	/*We can use this to see errors */
	/*
	error_reporting(E_ALL);
	ini_set("display_errors", 1);
	*/
	global $post;
	global $wpdb;
	
	$this_id = $post->ID;
	
	$media_file = $wpdb->get_var("SELECT url FROM wp_custom_headers WHERE post_id='$this_id' LIMIT 1");
	
	$this_url = $check_q;

	//Print out the form
	?>
	<div id="mediaHeaderForm">
		<div style="margin-top:10px;padding:5px;">
		<label for="mediaHeader" style="display:block;font-weight:bold;margin-bottom:5px;">Header Image for This Page</label>
		<p>This drop down shows a list of all media you have uploaded through the <a href="/wp-admin/admin.php?page=dhnd_add_menu">Upload Page</a>. <br />This could be a .jpg, .jpeg, .gif, .png, .swf file.<br />If you insert a flash file the plugin will automatically generate the cross browser compatible code to embed the file.</p>
		<select name="mediaHeader" id="mediaHeader">
			<option value="None">-- No Media Header --</option>
			<?php
			//Print out the media file list
			if($media_file == 'Random'){
				echo '<option value="Random" selected>-- Random Media Header --</option>';
			} else {
				echo '<option value="Random">-- Random Media Header --</option>';
			}
			echo '';
			if($media_dir = opendir(ABSPATH."wp-content/plugins/dynamic-headers/header-images/")){
				while ($m_file = readdir($media_dir)) {
					if($m_file != "." && $m_file != ".."){
						if($media_file == $m_file){
							echo '<option value="'.$m_file.'" selected>'.$m_file.'</option>';
						} else {
							echo '<option value="'.$m_file.'">'.$m_file.'</option>';
						}
					}
				}
			}
			?>
		</select>
		<br />
		<?php
			$file_array = explode(".", $media_file);
			$filetype = end($file_array);
			
			if($filetype != 'swf'){
				echo '<img src="'.get_bloginfo('wpurl').'/wp-content/plugins/dynamic-headers/header-images/'.$media_file.'" style="width:30%;margin-top:10px;" />';
			} else {
				echo '<img src="'.get_bloginfo('wpurl').'/wp-content/plugins/dynamic-headers/images/Flash-logo.png" style="position:relative;top:8px;" /> '. $media_file;
			}
		?>
		<br />
		</div>
		<br />
	</div>
<?php
}

//Actions and Filters	
if (isset($cl_custom_header)) {
	//Actions - remember to reference the class variable for functions
	register_activation_hook(__FILE__,array(&$cl_custom_header,'ch_install'));
	add_action("save_post",array(&$cl_custom_header,'save_ch'));
	add_action("wp_head",array(&$cl_custom_header,'headerCode'));
	add_action('admin_menu', 'myplugin_add_custom_box');
	add_action('admin_menu', 'dhnd_menu');
	add_action('wp_footer', 'dhnd_footer');
	
	function myplugin_add_custom_box() {
	  if( function_exists( 'add_meta_box' )) {
			add_meta_box( 'myplugin_sectionid', 'Nicasio Dynamic Header', 'create_ch_form', 'page', 'advanced', 'high' );
			add_meta_box( 'myplugin_sectionid', 'Nicasio Dynamic Header', 'create_ch_form', 'post', 'advanced', 'high' );
	   }
	}
}

function dhnd_footer(){
	if(get_option('dhnd_footer_link') == 'Yes'){
		echo 'Proudly using <a href="http://blog.nicasiodesign.com/category/wordpress-plugins/" target="_blank">Dynamic Headers</a> by <a href="http://nicasiodesign.com" target="_blank">Nicasio Design</a>';
	}
}

function get_random_media_item(){
	//Open the media directory and add all of the images to an array.
	if($media_dir = opendir(ABSPATH."wp-content/plugins/dynamic-headers/header-images/")){
		while ($m_file = readdir($media_dir)) {
			if($m_file != "." && $m_file != ".."){
				$media_array[] = $m_file;
			}
		}
	}
	
	return $media_array[rand(0,count($media_array)-1)];
}

function show_media_header(){
	global $post;
	$post_id = $post->ID;
	$load_this_media = 'None';
	//Declare the wpdb var and table name and run the query to see if there is a media object associated with this post
	global $wpdb;
	$table_name = $wpdb->prefix . "custom_headers";
	$check_q = $wpdb->get_row("SELECT * FROM $table_name WHERE post_id='$post_id' AND header_type='media' LIMIT 1");
	
	//This large chunk of code determines what page we are on and whether to load a fixed or random header.
	if(!is_home()){
		//If there isn't one, do this stuff:
		if($check_q == NULL) {
			$initial_file_value = get_option('dhnd_default');
			
			//If the option is random we need to get a random image to use.
			if($initial_file_value == 'Random'){
				$load_this_media = get_random_media_item();
			} else {
				$load_this_media = $initial_file_value;
			}
			
		//If there is an image associated with this post then set the file URL value to the queried URL.
		} else {
			$initial_file_value = $check_q->url;
			
			//If the option is random we need to get a random image to use.
			if($initial_file_value == 'Random'){
				$load_this_media = get_random_media_item();
			} else {
				$load_this_media = $initial_file_value;
			}
		}
	} else {
		$initial_file_value = get_option('dhnd_homepage');
		
		if($initial_file_value == 'Random'){
			$load_this_media = get_random_media_item();
		} else {
			$load_this_media = $initial_file_value;
		}
	}
	
	if($load_this_media != 'None' && $load_this_media != ""){
		$file_array = explode(".", $load_this_media);
		
		//Get the filetype
		$filetype = end($file_array);
		
		if($filetype != 'swf'){
			if(get_option('dhnd_'.$load_this_media) == ""){
				$dhnd_alt_tag = $load_this_media;
			} else {
				$dhnd_alt_tag = get_option('dhnd_'.$load_this_media);
			}

			echo '<img src="'.get_bloginfo('wpurl').'/wp-content/plugins/dynamic-headers/header-images/'.$load_this_media.'" alt="'.$dhnd_alt_tag.'" title="'.$dhnd_alt_tag.'" />';
		} else {
			$swf_array = explode(".", $load_this_media);
			
			$i = 0;
			
			while($i < count($swf_array) - 1){
				$swf_name .= $swf_array[$i];
				$i++;
			}
			
			$swf_src = '/wp-content/plugins/dynamic-headers/header-images/'.$load_this_media;
			list($width, $height, $type, $attr) = getimagesize($swf_src);
		?>
			<script language="javascript">
				if (AC_FL_RunContent == 0) {
					alert("This page requires AC_RunActiveContent.js.");
				} else {
				AC_FL_RunContent(
					'codebase', 'http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,0,0',
					'width', '<?php echo $width; ?>',
					'height', '<?php echo $height; ?>',
					'src', '<?php echo get_bloginfo('wpurl').'/wp-content/plugins/dynamic-headers/header-images/'.$swf_name; ?>',
					'quality', 'high',
					'pluginspage', 'http://www.macromedia.com/go/getflashplayer',
					'align', 'middle',
					'play', 'true',
					'loop', 'true',
					'scale', 'showall',
					'wmode', 'transparent',
					'devicefont', 'false',
					'id', 'mediaHeader',
					'bgcolor', '#ffffff',
					'name', 'mediaHeader',
					'menu', 'true',
					'allowFullScreen', 'false',
					'allowScriptAccess','sameDomain',
					'movie', '<?php echo get_bloginfo('wpurl').'/wp-content/plugins/dynamic-headers/header-images/'.$swf_name; ?>',
					'salign', ''
					); //end AC code
				}
			</script>
			<noscript>
				<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,0,0" width="<?php echo $width; ?>" height="<?php echo $height; ?>" id="mediaHeader" align="middle">
				<param name="allowScriptAccess" value="sameDomain" />
				<param name="allowFullScreen" value="false" />
				<param name="wmode" value="transparent" />
				<param name="movie" value="<?php echo get_bloginfo('wpurl').'/'.$swf_src; ?>" /><param name="quality" value="high" /><param name="bgcolor" value="#ffffff" />	
				<embed src="<?php echo get_bloginfo('wpurl').'/'.$swf_src; ?>" wmode="transparent" quality="high" bgcolor="#ffffff" width="<?php echo $width; ?>" height="<?php echo $height; ?>" name="mediaHeader" align="middle" allowScriptAccess="sameDomain" allowFullScreen="false" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" />
				</object>
			</noscript>
		<?php
		}
	}
	
	unset($load_this_media);
}

function get_media_header_url() {
	//Declare the wpdb var and table name and run the query to see if there is a media object associated with this post
	global $wpdb;
	global $post;
	$post_id = $post->ID;
	$table_name = $wpdb->prefix . "custom_headers";
	$check_q = $wpdb->get_row("SELECT * FROM $table_name WHERE post_id='$post_id' AND header_type='media' LIMIT 1");
	
	//This large chunk of code determines what page we are on and whether to load a fixed or random header.
	if(!is_home()){
		//If there isn't one, do this stuff:
		if($check_q == NULL) {
			$initial_file_value = get_option('dhnd_default');
			
			//If the option is random we need to get a random image to use.
			if($initial_file_value == 'Random'){
				$load_this_media = get_random_media_item();
			} else {
				$load_this_media = $initial_file_value;
			}
			
		//If there is an image associated with this post then set the file URL value to the queried URL.
		} else {
			$initial_file_value = $check_q->url;
			
			//If the option is random we need to get a random image to use.
			if($initial_file_value == 'Random'){
				$load_this_media = get_random_media_item();
			} else {
				$load_this_media = $initial_file_value;
			}
		}
	} else {
		$initial_file_value = get_option('dhnd_homepage');
		
		if($initial_file_value == 'Random'){
			$load_this_media = get_random_media_item();
		} else {
			$load_this_media = $initial_file_value;
		}
	}
	
	return $load_this_media;
}

function is__writable($path) {
//NOTE: use a trailing slash for folders!!!
    if ($path{strlen($path)-1}=='/') // recursively return a temporary file path
        return is__writable($path.uniqid(mt_rand()).'.tmp');
    else if (is_dir($path))
        return is__writable($path.'/'.uniqid(mt_rand()).'.tmp');
    // check tmp file for read/write capabilities
    $rm = file_exists($path);
    $f = @fopen($path, 'a');
    if ($f===false)
        return false;
    fclose($f);
    if (!$rm)
        unlink($path);
    return true;
}
?>