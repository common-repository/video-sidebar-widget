<?php

function delete_file() {
    $id = $_GET['id'];
    
    ?>
    <form name="delete_new" method="post" action="admin.php?page=video_url" >
        <table class="form-table" cellspacing="0">
            <tr>
                <td>Are you Sure you want to delete? </td>

            <input type="hidden" name="video_delete_id" value="<?php echo $id; ?>" />
            </tr>

            <tr>
                <td colspan="2"><input class="button-primary" type="submit" name="video_delete" value="Delete"   /></td>
                <td colspan="0"><input class="button-primary" type="button" value="Cancel" onclick="window.history.back()"></td>
            </tr>

        </table>    
    </form>
<?php
}
