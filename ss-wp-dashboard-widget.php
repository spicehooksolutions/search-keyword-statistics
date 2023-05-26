<?php
// Function that outputs the contents of the dashboard widget
function ss_dashboard_widget_function($post, $callback_args) {

    global $wpdb;
    $rows = $wpdb->get_results("SELECT * FROM " . SS_TABLE . " WHERE repeat_count>0 ORDER BY repeat_count DESC LIMIT 0,10");
    ?>
    <div>
        <h3> Search Keyword Statistics <span style="color: gray;">3.1</span> - Most repeated result</h3>

        <div>
    <?php
    if (isset($error) && $error != '') {
        echo '<span style="color:red;font-size:12px;font-weight:bold;">' . $error . '</span>';
    }
    ?>



        </div>

        <div class="ss_dasboard_table">
            <form action="" method="post" name="frmKeyword" >
                <table cellpadding="0" cellspacing="0" border="0" class="display wp-list-table widefat fixed table-view-list ss__keyword_search" id="example" width="100%">
                    <tr>
                        <td width="10%">Sl No</td>
                        <td width="50%">Keywords</td>
                        <td width="15%">Repeat</td>
                        <td width="25%">No. of Result</td>
                    </tr>
    <?php
    for ($i = 0; $i < count($rows); $i++) {
        if (is_numeric($rows[$i]->user)) {
            $user_info = get_userdata($rows[$i]->user);
            $user = $user_info->user_login;
        } else
            $user = 'Non-Registered';
        ?>
                        <tr class="odd gradeX">
                            <td><?php echo $i + 1; ?></td>
                            <td><?php echo $rows[$i]->keywords; ?></td>

                            <td class="center"><?php echo $rows[$i]->repeat_count; ?></td>
                            <td class="center"><?php echo $rows[$i]->search_count; ?></td>

                        </tr>
                        <?php
                    }
                    ?>


                </table>

            </form>
        </div>
				
			
				<p class="vcwb-rss-widget-bottom search-keywords-footer">
        
				<a href="<?php echo admin_url( 'admin.php?page=ss-menu' ); ?>"  >
            View Details            <span aria-hidden="true" class="dashicons dashicons-external"></span>
        </a>
                   
            </p>

    </div>

    <?php
}
?>