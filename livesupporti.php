<?php
/*
	Plugin Name: LiveSupporti
	Plugin URI: http://livesupporti.com
	Description: A plugin that allows to add <strong>live support chat</strong> on a WordPress website. To get started just click <strong>Activate</strong>.
	Version: 1.0.0
	Author: LiveSupporti
	Author URI: http://livesupporti.com
	License: GPL2
  
  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License, version 2, as 
  published by the Free Software Foundation.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program; if not, write to the Free Software
  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

add_action('init', 'do_output_buffer');

add_action('wp_footer', 'livesupporti_init');

add_action('admin_menu', 'getLiveSupportiAdminMenu');

register_activation_hook( __FILE__, 'livesupporti_activate_plugin' );

add_action('admin_init', 'redirectToLiveSupportiAdminPage');

function do_output_buffer() {
        ob_start();
}

function livesupporti_init() {
	$license = get_option('txtLicense');
	addLiveSupportiScript($license);
}

function addLiveSupportiScript($license) {
	echo '
			<!-- Live support chat by LiveSupporti - http://livesupporti.com -->
			<script src="https://livesupporti.com/Scripts/client.js?acc='.$license.'"></script>
	';
}

function getLiveSupportiAdminMenu() {
	$icon = "http://livesupporti.com/Images/favicon.png";
	add_menu_page('LiveSupporti', 'LiveSupporti', 10, dirname( __FILE__ ) . '/livesupporti.php', '', $icon);
	add_submenu_page(dirname( __FILE__ ) . '/livesupporti.php', 'Settings', 'Settings', 'manage_options', dirname( __FILE__ ) . '/livesupporti.php', 'livesupporti_settings');
}

function livesupporti_settings() {
	if (!current_user_can('manage_options')) {
		wp_die(__('You do not have sufficient permissions to access this page.'));
	}
	
	$hidLiveSupporti = 'hidLiveSupporti';

	if(isset($_POST[$hidLiveSupporti]) && $_POST[$hidLiveSupporti] == 'IsPostBack') {
		update_option('txtLicense', $_POST['txtLicense']);
	}
?>
<form name="form1" method="post" action="">
	<input type="hidden" name="<?php echo $hidLiveSupporti; ?>" value="IsPostBack">
	<h1 style="color:#1FB9F2">Getting Started with LiveSupporti</h1>
	<h3>Step 1</h3>
    <p>To add the live chat plugin on your website you need a LiveSupporti account. If you don't have an account you can <a href="http://livesupporti.com/signup" target="_blank" title="Get free live support chat">sign up here</a>.</p>
    <h3>Step 2</h3>
	<p>Copy your <a href="http://livesupporti.com/pk" target="_blank">product key from here</a>, paste it below and click 'Save'.</p>
	<br>
	<input type="text" name="txtLicense" size="50" value="<?php echo get_option('txtLicense') ?>">
	<p class="submit">
		<input type="submit" name="Submit" class="button-primary" value="<?php esc_attr_e('Save') ?>" />
	</p>
</form>
<?php
}
?>
<?php
function livesupporti_activate_plugin() {
    add_option('redirectToLiveSupportiAdminPage', true);
}

function redirectToLiveSupportiAdminPage() {
    if (get_option('redirectToLiveSupportiAdminPage', false)) {
        delete_option('redirectToLiveSupportiAdminPage');
    	wp_redirect(admin_url('admin.php?page=livesupporti/livesupporti.php'));
    }
}
?>