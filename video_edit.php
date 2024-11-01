<?php
function display_edit_file() {
    $id = $_GET['id'];
    global $wpdb;
    $pro_table_prefix = $wpdb->prefix;
    define('PRO_TABLE_PREFIX', $pro_table_prefix);
    $table = PRO_TABLE_PREFIX . "videourl";
    $new_table = PRO_TABLE_PREFIX . "newfield";

    $sql = "SELECT *FROM " . $table . " WHERE id=" . $id;

    $results = $wpdb->get_results($sql);
    ?>
    <form name="update_new" method="post" action="admin.php?page=video_url" >
        <table class="form-table" cellspacing="0">
<?php
            foreach ($results as $result) {
                ?>
                <tr>
                    <td>Name</td>
                    <td colspan="2"> 
                        <input class="regular-text ltr" type="hidden" name="video_id" size="60" value= "<?php echo $id; ?>"/>
                        <input class="regular-text ltr" type="text" name="urlname" size="60" value= "<?php echo $result->name; ?>"/></td>
                </tr>

                <tr>
                    <td>URl Path</td>
                    <td colspan="2"><textarea name="urlpath" rows="8" cols="60"><?php echo $result->url; ?></textarea></td>
                </tr>

                <tr>
                    <td colspan="2"><input class="button-primary" type="submit" name="video_update" value="Update"   /></td>
                    <td colspan="0"><input class="button-primary" type="button" value="Cancel" onclick="window.history.back()"></td>
                </tr>
<?php } ?>
        </table>    
    </form>
<?php
}