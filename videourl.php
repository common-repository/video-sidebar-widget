<?php
/*
  Plugin Name:Video Url
  Plugin URI:  http://www.itobuz.com/
  Description: Plugin for show video in widget and page.
  Author: Itobuz Technologies Pvt Ltd
  Version: 1.0.0.3
  Author URI: http://www.itobuz.com/
 */
global $wpdb;
$pro_table_prefix = $wpdb->prefix;
define('PRO_TABLE_PREFIX', $pro_table_prefix);
register_activation_hook(__FILE__, 'install');
register_deactivation_hook(__FILE__, 'uninstall');

function install() {
//    require_once(ABSPATH . '/wp-blog-header.php');
    global $wpdb;
    $table = PRO_TABLE_PREFIX . "videourl";
    $new_table = PRO_TABLE_PREFIX . "newfield";

    $structure = "CREATE TABLE " . $table . " (id INT(9),name VARCHAR(220),url TEXT)";
    $structure_new = "CREATE TABLE " . $new_table . " (eid INT(9) NOT NULL AUTO_INCREMENT, PRIMARY KEY(eid))";
    $wpdb->query($structure_new);
    $wpdb->query($structure);
}

function uninstall() {
    global $wpdb;
    $table = PRO_TABLE_PREFIX . "videourl";
    $new_table = PRO_TABLE_PREFIX . "newfield";
    $structure = "drop table if exists " . $table;
    $structure_new = "drop table if exists " . $new_table;
    $wpdb->query($structure_new);
    $wpdb->query($structure);
}

// Add option page in admin menu
add_action('admin_menu', 'videourl');

function videourl() {
    add_menu_page('Video Url', 'Video Url', 'administrator', 'video_url');
    add_submenu_page('video_url', 'Video URL', 'Video URL', 'administrator', 'video_url', 'video_url_setting');
    add_submenu_page(NULL, 'Edit Page', 'Edit Page', 'administrator', 'edit-page', 'display_edit_file');
    add_submenu_page(NULL, 'Delete Page', 'Delete Page', 'administrator', 'delete-page', 'delete_file');
}

require_once('video_edit.php');
require_once('delete.php');

//Admin Setting page

function video_url_setting() {
    ?>

    <form name="add_new" method="post" action="" >
        <table class="widefat page fixed" cellspacing="0">
            <tr>
                <td colspan="2"><input class="button-primary" type="submit" name="add_new_submit" value="Add New Url"   /></td>
            </tr>
        </table>    
    </form>
    <?php
    global $wpdb;
    $table = PRO_TABLE_PREFIX . "videourl";
    $new_table = PRO_TABLE_PREFIX . "newfield";

    /* Update Code */

    if (isset($_REQUEST['video_update'])) {
        $urlname = isset($_REQUEST['urlname']) ? $_REQUEST['urlname'] : '';
        $urlpath = isset($_REQUEST['urlpath']) ? $_REQUEST['urlpath'] : '';
        $video_id = isset($_REQUEST['video_id']) ? $_REQUEST['video_id'] : '';

        $strQuery = "UPDATE '.$table. SET name = %s, url = %s WHERE id = %d";
        $wpdb->query($wpdb->prepare("UPDATE $table SET name = %s, url = %s WHERE id = %d", $urlname, stripslashes_deep($urlpath), $video_id));
    }

    /* Delete Code */

    if (isset($_REQUEST['video_delete'])) {
        $video_delete_id = isset($_REQUEST['video_delete_id']) ? $_REQUEST['video_delete_id'] : '';
        $wpdb->query($wpdb->prepare(
                        "DELETE FROM $table
		 WHERE id = %d
		"
                        , $video_delete_id));
        $wpdb->query($wpdb->prepare(
                        "DELETE FROM $new_table
		 WHERE eid = %d
		"
                        , $video_delete_id));
    }

    /* Submit Code */

    if (isset($_REQUEST['video_submit'])) {
        $sql = "SELECT *FROM " . $new_table;
        $results = $wpdb->get_results($sql);
        foreach ($results as $result) {
            $id = $result->eid;
        }
        $urlname = isset($_REQUEST['urlname']) ? $_REQUEST['urlname'] : '';
        $urlpath = isset($_REQUEST['urlpath']) ? $_REQUEST['urlpath'] : '';
        $wpdb->insert($table, array('id' => $id, 'name' => $urlname, 'url' => stripslashes_deep($urlpath)));
    }

    /* Form Create */

    if (isset($_REQUEST['add_new_submit'])) {
        $wpdb->insert($new_table, array('eid' => NULL));
        ?>
        <form name="add_new" method="post" action="" >
            <table class="form-table" cellspacing="0">
                <tr>
                    <td>Name</td>
                    <td colspan="2"><input class="regular-text ltr" type="text" name="urlname" size="60"  /></td>
                </tr>

                <tr>
                    <td>URl Path</td>
                    <td colspan="2"><textarea name="urlpath" rows="8" cols="60"></textarea></td>
                </tr>

                <tr>
                    <td colspan="0"><input class="button-primary" type="submit" name="video_submit" value="Add"   /></td>
                    <td colspan="0"><input class="button-primary" type="button" value="Cancel" onclick="window.history.back()"></td>
                </tr>
            </table>    
        </form>
        <?php
    }
    $column = array('name' => "Name", 'edit' => "Edit", 'delete' => "Delete");
    register_column_headers('data_field', $column);
    ?>
    <table class="widefat page fixed" cellspacing="0">
        <thead>
            <tr><?php print_column_headers('data_field'); ?></tr>
        </thead>
        <tbody>
            <?php
            $sql = "SELECT *FROM " . $table;
            $results = $wpdb->get_results($sql);
            foreach ($results as $result) {
                ?>
                <tr>
                    <td><?php echo $result->name; ?></td>
                    <td><a href="<?php bloginfo('url') ?>/wp-admin/admin.php?page=edit-page&id=<?php echo $result->id; ?>"><input class="button-primary" type="button" value="EDIT" /></a></td>
                    <td><a href="<?php bloginfo('url') ?>/wp-admin/admin.php?page=delete-page&id=<?php echo $result->id; ?>"><input class="button-primary" type="button" value="DELETE" /></a></td>

                </tr> 
            <?php } ?>
        </tbody>  
    </table>
    <?php
}

function video_scripts_method() {
    wp_deregister_script('jquery');
    wp_register_script('jquery', 'http://code.jquery.com/jquery-latest.min.js');
    wp_enqueue_script('jquery');
}

add_action('wp_enqueue_scripts', 'video_scripts_method');

function videourl_view() {
    ?><script>
        $(document).ready(function () {
            var x = $(".video a").attr('href');
            $('#ii').attr('src', x)
            $(".video a").click(function (event) {
                var href = $(this).attr('href');
                event.preventDefault();
                $('#ii').attr('src', href)
            });

        })
    </script>
    <iframe id="ii" width="auto" height="157" frameborder="0" allowfullscreen="" src=""></iframe>

    <?php
    global $wpdb;
    $table = PRO_TABLE_PREFIX . "videourl";
    $sql = "SELECT * FROM " . $table;
    $results = $wpdb->get_results($sql);
    foreach ($results as $result) {
        $youtubeurl = $result->url;
        $youtubeurl = str_replace('\\', '', $youtubeurl);
        preg_match('/src="(.*?)"/', $youtubeurl, $match);
        ?>
        <div><img width="12" height="18" border="0" align="left" src="<?php echo plugins_url("more-videos-bullet.gif", __FILE__); ?>"></div>
        <div class="video" style="margin-bottom:7px;"><a href="<?php echo $match[1]; ?>"><?php echo $result->name; ?> </a></div>
        <?php
    }
}

include('widget_url.php');
