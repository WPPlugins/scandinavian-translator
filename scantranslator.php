<?php

// Scandinavian Translator
//
// Copyright (c) 2007 - 2008 Søren Storm Hansen
// http://www.dseneste.dk
//
// Released under the GPL license
// http://www.opensource.org/licenses/gpl-license.php
//
// This is a plugin for WordPress
// http://wordpress.org/
//
// **********************************************************************
// This program is distributed in the hope that it will be useful, but
// WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
// *****************************************************************
//
// Flag icons by http://www.jacorre.com/

/*
Plugin Name: Scandinavian Translator
Plugin URI: http://www.dseneste.dk/index.php/scandinavian-translator/
Description: Translates posts and pages from Danish, Swedish and English to Danish, English and Norwegian (options depends on base language) by redirecting to <a href="http://www.gramtrans.com/">gramtrans.com</a>.
Version: 1.1
Author: S&oslash;ren Storm Hansen
Author URI: http://www.dseneste.dk/

Installation:

1. Copy the folder with this file to your wp-content/plugins directory;
2. Access the WordPress administrator interface;
3. Click the Plugins tab;
4. Activate the Scandinavian Translator plugin;
5. Click the Options tab;
6. Click the Scandinavian Translator subtab;
7. Choose your settings and click Save;

*/

$data = array(
	'display_text' 			=> '',
	'link_type' 			=> '',
	'original_language' 	=> '',
	'display_pages'			=> '',
	'open_window'			=> '',
	'translate_dan_eng'		=> '',
	'translate_dan_nor'		=> '',
	'translate_swe_eng'		=> '',
	'translate_swe_dan'		=> '',
	'translate_swe_nor'		=> '',
	'translate_eng_dan'		=> '',
	'translate_eng_nor'		=> ''
);

$imgs = array(
	'uk'	=> get_bloginfo('url') . '/wp-content/plugins/scandinavian-translator/unitedkingdom.gif',
	'dk'	=> get_bloginfo('url') . '/wp-content/plugins/scandinavian-translator/denmark.gif',
	'se'	=> get_bloginfo('url') . '/wp-content/plugins/scandinavian-translator/sweden.gif',
	'no'	=> get_bloginfo('url') . '/wp-content/plugins/scandinavian-translator/norway.gif'
);

$ds_flash = '';

function ds_is_authorized() {
	global $user_level;
	if (function_exists("current_user_can")) {
		return current_user_can('activate_plugins');
	} else {
		return $user_level > 5;
	}
}

add_option('scantrans_settings',$data,'Scandinavian Translator Settings');

$scantrans_settings = get_option('scantrans_settings');

function ds_add_scantrans_options_page() {
	if (function_exists('add_options_page')) {
		add_options_page('Scandinavian Translator', 'Scandinavian Translator', 8, basename(__FILE__), 'ds_scantrans_options_subpanel');
	}
}

function ds_defaults($my_settings){
//	if( $my_settings['display_text'] == '' ) $my_settings['display_text'] = 'Translate:';
	if( $my_settings['link_type'] == '' ) $my_settings['link_type'] = 'image';
	if( $my_settings['original_language'] == '' ) $my_settings['original_language'] = 'dan';
	if( $my_settings['display_pages'] == '' ) $my_settings['display_pages'] = 'single';
	if( $my_settings['open_window'] == '' ) $my_settings['open_window'] = 'new';
	return $my_settings;
}

function ds_scantrans_options_subpanel() {

	global $ds_flash, $scantrans_settings, $_POST, $wp_rewrite;

	if (ds_is_authorized()) {
		if (isset($_POST['display_text'])) {
			$scantrans_settings['display_text'] = $_POST['display_text'];
			update_option('scantrans_settings',$scantrans_settings);
			$ds_flash = "Your settings have been saved.";
		}
		if (isset($_POST['link_type'])) {
			$scantrans_settings['link_type'] = $_POST['link_type'];
			update_option('scantrans_settings',$scantrans_settings);
			$ds_flash = "Your settings have been saved.";
		}
		if (isset($_POST['original_language'])) {
			$scantrans_settings['original_language'] = $_POST['original_language'];
			update_option('scantrans_settings',$scantrans_settings);
			$ds_flash = "Your settings have been saved.";
		}
		if (isset($_POST['display_pages'])) {
			$scantrans_settings['display_pages'] = $_POST['display_pages'];
			update_option('scantrans_settings',$scantrans_settings);
			$ds_flash = "Your settings have been saved.";
		}
		if (isset($_POST['open_window'])) {
			$scantrans_settings['open_window'] = $_POST['open_window'];
			update_option('scantrans_settings',$scantrans_settings);
			$ds_flash = "Your settings have been saved.";
		}
		if (isset($_POST['submit'])) {
			if (isset($_POST['translate_dan_eng'])) {
				$scantrans_settings['translate_dan_eng'] = 1;
			} else {
				$scantrans_settings['translate_dan_eng'] = 0;
			}
			if (isset($_POST['translate_dan_nor'])) {
				$scantrans_settings['translate_dan_nor'] = 1;
			} else {
				$scantrans_settings['translate_dan_nor'] = 0;
			}
			if (isset($_POST['translate_swe_eng'])) {
				$scantrans_settings['translate_swe_eng'] = 1;
			} else {
				$scantrans_settings['translate_swe_eng'] = 0;
			}
			if (isset($_POST['translate_swe_dan'])) {
				$scantrans_settings['translate_swe_dan'] = 1;
			} else {
				$scantrans_settings['translate_swe_dan'] = 0;
			}
			if (isset($_POST['translate_swe_nor'])) {
				$scantrans_settings['translate_swe_nor'] = 1;
			} else {
				$scantrans_settings['translate_swe_nor'] = 0;
			}
			if (isset($_POST['translate_eng_dan'])) {
				$scantrans_settings['translate_eng_dan'] = 1;
			} else {
				$scantrans_settings['translate_eng_dan'] = 0;
			}
			if (isset($_POST['translate_eng_nor'])) {
				$scantrans_settings['translate_eng_nor'] = 1;
			} else {
				$scantrans_settings['translate_eng_nor'] = 0;
			}
			update_option('scantrans_settings',$scantrans_settings);
			$ds_flash = "Your settings have been saved.";
		}
	}	else {
		$ds_flash = "You don't have enough access rights.";
	}

	if ($ds_flash != '') echo '<div id="message"class="updated fade"><p>' . $ds_flash . '</p></div>';

	if (ds_is_authorized()) {

		$scantrans_settings = ds_defaults($scantrans_settings);
		$org_lan = $scantrans_settings['original_language'];
		$link_type = $scantrans_settings['link_type'];

		echo '<div class="wrap">';
		echo '<h2>Scandinavian Translator 1.1</h2>';
		echo '<p>
			Thank you for installing Scandinavian Translator. This plugin offers your visitors a one-click translation of your posts. The options for translation depends on the base language of your blog.
		</p>
		<p>
			The links for translation is inserted in a new line after the post or in the sidebar. The links are encapsulated in &lt;div class="scantrans"&gt;&lt;/div&gt;. You can edit the layout of the links by making a style for the scantrans class in your style sheet.
		</p>
		<h3>
			Settings:
		</h3>
		<form action="" method="post">
			<ol>
				<li>
					<strong>Text to display before links:</strong><br /><input type="text" name="display_text" value="' . $scantrans_settings['display_text'] . '" size="45" />
				</li>
				<li>
					<strong>Display links as:</strong><br />
				</li>
					<input type="radio" name="link_type" value="text"';
					if($link_type == 'text') echo ' checked="checked"';
					echo '/> Text<br />
					<input type="radio" name="link_type" value="image"';
					if($link_type == 'image') echo ' checked="checked"';
					echo '/> Images: ';
					global $imgs;
					echo '<img alt="English" width="27" height="17" src="' . $imgs['uk'] . '"> ';
					echo '<img alt="Norsk" width="27" height="17" src="' . $imgs['no'] . '"> ';
					echo '<img alt="Dansk" width="27" height="17" src="' . $imgs['dk'] . '"> ';
					echo '<img alt="Svensk" width="27" height="17" src="' . $imgs['se'] . '"> ';
				echo '<li>
					<strong>Languages:</strong><br />Choose base language of your posts and one or more languages to offer translations to.<br />
					<input type="radio" name="original_language" value="dan"';
					if($org_lan == 'dan') echo ' checked="checked"';
					echo '/> Danish to:
					<ul style="list-style:none;">
						<li>
							<input type="checkbox" id="translate_dan_eng" name="translate_dan_eng" value="1"';
							if($scantrans_settings['translate_dan_eng'] == 1) echo ' checked="checked"';
							echo '/> English
						</li>
						<li>
							<input type="checkbox" id="translate_dan_nor" name="translate_dan_nor" value="1"';
							if($scantrans_settings['translate_dan_nor'] == 1) echo ' checked="checked"';
							echo '/> Norwegian
						</li>
					</ul>
					<input type="radio" name="original_language" value="swe"';
					if($org_lan == 'swe') echo ' checked="checked"';
					echo '/> Swedish to:
					<ul style="list-style:none;">
						<li>
							<input type="checkbox" id="translate_swe_eng" name="translate_swe_eng" value="1"';
							if($scantrans_settings['translate_swe_eng'] == 1) echo ' checked="checked"';
							echo '/> English
						</li>
						<li>
							<input type="checkbox" id="translate_swe_dan" name="translate_swe_dan"  value="1"';
							if($scantrans_settings['translate_swe_dan'] == 1) echo ' checked="checked"';
							echo '/> Danish
						</li>
						<li>
							<input type="checkbox" id="translate_swe_nor" name="translate_swe_nor" value="1"';
							if($scantrans_settings['translate_swe_nor'] == 1) echo ' checked="checked"';
							echo '/> Norwegian
						</li>
					</ul>
					<input type="radio" name="original_language" value="eng"';
					if($org_lan == 'eng') echo ' checked="checked"';
					echo '/> English to:
					<ul style="list-style:none;">
						<li>
							<input type="checkbox" id="translate_eng_dan" name="translate_eng_dan" value="1"';
							if($scantrans_settings['translate_eng_dan'] == 1) echo ' checked="checked"';
							echo '/> Danish
						</li>
						<li>
							<input type="checkbox" id="translate_eng_nor" name="translate_eng_nor" value="1"';
							if($scantrans_settings['translate_eng_nor'] == 1) echo ' checked="checked"';
							echo '/> Norwegian
						</li>
					</ul>
				</li>
				<li>
					<strong>Show on pages:</strong><br /><input type="radio" name="display_pages" value="single"';
					if( $scantrans_settings['display_pages'] == 'single' ) echo ' checked="checked"';
					echo '/> Only single posts<br /><input type="radio" name="display_pages" value="all"';
					if( $scantrans_settings['display_pages'] == 'all' ) echo ' checked="checked"';
					echo '/> All pages<br /><input type="radio" name="display_pages" value="sidebar"';
					if( $scantrans_settings['display_pages'] == 'sidebar' ) echo ' checked="checked"';
					echo '/> Sidebar (only on single posts and static pages)
					<ul style="list-style:none;">
						<li>
							Paste this code into the file sidebar.php where you want the translation links:<br />
							<input type="text" name="sidebarcode" value="&lt;?php if(function_exists(&quot;st_translate&quot;)) echo st_translate(); ?&gt;" size="70" />
						</li>
					</ul>
				</li>
				<li>
					<strong>Open translation in:</strong><br /><input type="radio" name="open_window" value="new"';
					if( $scantrans_settings['open_window'] == 'new' ) echo ' checked="checked"';
					echo '/> New window<br /><input type="radio" name="open_window" value="same"';
					if( $scantrans_settings['open_window'] == 'same' ) echo ' checked="checked"';
					echo '/> Same window
				</li>
			</ol>
			<p>
				<input type="submit" name="submit" id="submit" value="Save" />
			</p>
			<p>
				Home of Scandinavian Translator: <a href="http://www.dseneste.dk/index.php/scandinavian-translator">dSeneste.dk</a>. Flag icons by <a href="http://www.jacorre.com/">jacorre.com</a>.
			</p>
		</form>';
		echo '</div>';
	} else {
		echo '<div class="wrap"><p>Sorry, you are not allowed to access this page.</p></div>';
	}
}

function ds_add_link($my_settings, $content){
	global $imgs;
	$OK = false;
	$url = urlencode(get_permalink($post->ID));
	$GramURL = '<a href="http://gramtrans.com/gt/url/?url=' . $url . '&pair=';
	$GramURL2 = '&x-form-id=translate_url" ';
	if($my_settings['open_window'] == 'new') $GramURL2 .= 'target="_new"';
	$gtText = '<div class="scantrans">';
	$gtText .= $my_settings['display_text'];
	$gtText .= ' ';
	if ( $my_settings['original_language'] == 'dan' ) {
		if ( $my_settings['translate_dan_eng'] == 1 ) {
			$gtText .= $GramURL;
			$gtText .= $my_settings['original_language'];
			$gtText .= '2';
			$gtText .= 'eng';
			$gtText .= $GramURL2 . 'title="Translate to English">';
			if ( $my_settings['link_type'] == 'text' ) {
				$gtText .= 'English';
			} else {
				$gtText .= '<img alt="Translate to English" width="27" height="17" src="' . $imgs['uk'] . '">';
			}
			$gtText .= '</a>';
			$OK = true;
		}
		if ( $my_settings['translate_dan_nor'] == 1 ) {
			if( $OK == true && $my_settings['link_type'] == 'text' ) $gtText .= ', ';
			if( $OK == true && $my_settings['link_type'] == 'image' ) $gtText .= ' ';
			$gtText .= $GramURL;
			$gtText .= $my_settings['original_language'];
			$gtText .= '2';
			$gtText .= 'nor';
			$gtText .= $GramURL2 . 'title="Overs&aelig;t til norsk">';
			if ( $my_settings['link_type'] == 'text' ) {
				$gtText .= 'norsk';
			} else {
				$gtText .= '<img alt="Overs&aelig;t til norsk" width="27" height="17" src="' . $imgs['no'] . '">';
			}
			$gtText .= '</a>';
			$OK = true;
		}
	} elseif ( $my_settings['original_language'] == 'swe' ) {
		if ( $my_settings['translate_swe_eng'] == 1 ) {
			$gtText .= $GramURL;
			$gtText .= $my_settings['original_language'];
			$gtText .= '2';
			$gtText .= 'eng';
			$gtText .= $GramURL2 . 'title="Translate to English">';
			if ( $my_settings['link_type'] == 'text' ) {
				$gtText .= 'English';
			} else {
				$gtText .= '<img alt="Translate to English" width="27" height="17" src="' . $imgs['uk'] . '">';
			}
			$gtText .= '</a>';
			$OK = true;
		}
		if ( $my_settings['translate_swe_dan'] == 1 ) {
			if( $OK == true && $my_settings['link_type'] == 'text' ) $gtText .= ', ';
			if( $OK == true && $my_settings['link_type'] == 'image' ) $gtText .= ' ';
			$gtText .= $GramURL;
			$gtText .= $my_settings['original_language'];
			$gtText .= '2';
			$gtText .= 'dan';
			$gtText .= $GramURL2 . 'title="Overs&aelig;t til dansk">';
			if ( $my_settings['link_type'] == 'text' ) {
				$gtText .= 'dansk';
			} else {
				$gtText .= '<img alt="Overs&aelig;t til dansk" width="27" height="17" src="' . $imgs['dk'] . '">';
			}
			$gtText .= '</a>';
			$OK = true;
		}
		if ( $my_settings['translate_swe_nor'] == 1 ) {
			if( $OK == true && $my_settings['link_type'] == 'text' ) $gtText .= ', ';
			if( $OK == true && $my_settings['link_type'] == 'image' ) $gtText .= ' ';
			$gtText .= $GramURL;
			$gtText .= $my_settings['original_language'];
			$gtText .= '2';
			$gtText .= 'nor';
			$gtText .= $GramURL2 . 'title="Overs&aelig;t til norsk">';
			if ( $my_settings['link_type'] == 'text' ) {
				$gtText .= 'norsk';
			} else {
				$gtText .= '<img alt="Overs&aelig;t til norsk" width="27" height="17" src="' . $imgs['no'] . '">';
			}
			$gtText .= '</a>';
			$OK = true;
		}
	} else { //eng
		if ( $my_settings['translate_eng_dan'] == 1 ) {
			$gtText .= $GramURL;
			$gtText .= $my_settings['original_language'];
			$gtText .= '2';
			$gtText .= 'dan';
			$gtText .= $GramURL2 . 'title="Overs&aelig;t til dansk">';
			if ( $my_settings['link_type'] == 'text' ) {
				$gtText .= 'dansk';
			} else {
				$gtText .= '<img alt="Overs&aelig;t til dansk" width="27" height="17" src="' . $imgs['dk'] . '">';
			}
			$gtText .= '</a>';
			$OK = true;
		}
		if ( $my_settings['translate_eng_nor'] == 1 ) {
			if( $OK == true && $my_settings['link_type'] == 'text' ) $gtText .= ', ';
			if( $OK == true && $my_settings['link_type'] == 'image' ) $gtText .= ' ';
			$gtText .= $GramURL;
			$gtText .= $my_settings['original_language'];
			$gtText .= '2';
			$gtText .= 'nor';
			$gtText .= $GramURL2 . 'title="Overs&aelig;t til norsk">';
			if ( $my_settings['link_type'] == 'text' ) {
				$gtText .= 'norsk';
			} else {
				$gtText .= '<img alt="Overs&aelig;t til norsk" width="27" height="17" src="' . $imgs['no'] . '">';
			}
			$gtText .= '</a>';
			$OK = true;
		}
	}
	$gtText .= '</div>';

	if( $OK == true ) $content .= $gtText;
	return $content;
}

function st_translate(){
	$content = '';
	global $scantrans_settings;
	if ( $scantrans_settings['display_pages'] == 'sidebar' && (is_single() || is_page() ) ) {
		$content .= ds_add_link($scantrans_settings, '');
	}
	return $content;
}

function st_add_link($content) {
	global $scantrans_settings;
	$scantrans_settings = ds_defaults($scantrans_settings);

	if ( $scantrans_settings['display_pages'] == 'all' ) {
		$content = ds_add_link($scantrans_settings, $content);
	} elseif ( $scantrans_settings['display_pages'] == 'single' && is_single() ) {
		$content = ds_add_link($scantrans_settings, $content);
	}
	return $content;
}

add_filter('the_content', 'st_add_link');
add_action('admin_menu', 'ds_add_scantrans_options_page');

?>