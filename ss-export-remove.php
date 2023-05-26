<?php
global $wpdb;

/* For clear all data */

if (isset($_POST) && count($_POST) > 0 && isset($_POST['clear'])) {

    $deleQuery = "DELETE FROM " . SS_TABLE . "";
    $wpdb->query($deleQuery);
    $msg = true;
}

if(isset($_POST['ss_search_submit_export']) && $_POST['ss_search_submit_export']!='')
{
    $filename='search_keyword_stats_'.strtotime(date('Y-m-d h:i')).'.csv';
    header( 'Content-Type: application/csv' );
    header( 'Content-Disposition: attachment; filename="' . $filename . '";' );
    global $wpdb;
    $rows = $wpdb->get_results("SELECT * FROM " . SS_TABLE . " ORDER BY repeat_count DESC");
    $BrowserSSKeyword = new forocoSS\BrowserDetection();
    // clean output buffer
    ob_end_clean();
    
    $handle = fopen( 'php://output', 'w' );

    // use keys as column titles
    $str='Sl. No,Keyword,User,Browser,OS,Repeat,No. of Results';
    fwrite( $handle, $str );
    fwrite( $handle, "\r\n");

    for ($i = 0; $i < count($rows); $i++) {
        if (is_numeric($rows[$i]->user)) {
            $user_info = get_userdata($rows[$i]->user);
            $user = $user_info->user_login;
        } else
            $user = 'Non-Registered';
            if($rows[$i]->agent!='')
            $browserDetails = $BrowserSSKeyword->getAll($rows[$i]->agent);
            else
            $browserDetails = "";
            $bname="";
                        if($browserDetails['browser_name']=='Chrome')
                        $bname="chrome";

                        if($browserDetails['browser_name']=='Opera')
                        $bname="opera";

                        if($browserDetails['browser_name']=='unknown' || $browserDetails['browser_name']=='' || $browserDetails['browser_name']==NULL)
                        $bname="browser";

                        if($browserDetails['browser_name']=='Safari')
                        $bname="safari";

                        if($browserDetails['browser_name']=='Firefox')
                        $bname="firefox";

                        if($browserDetails['browser_name']=='Safari Mobile')
                        $bname="safari";

                        if($browserDetails['browser_name']=='Yandex Browser')
                        $bname="yandex";

                        if($browserDetails['browser_name']=='Edge')
                        $bname="microsoft";

                        if($browserDetails['browser_name']=='UC Browser')
                        $bname="uc-browser";

            $str=($i + 1).','.stripslashes($rows[$i]->keywords).','.$user.','.$bname.','.(isset($browserDetails['os_name'])?$browserDetails['os_name']:"").','.$rows[$i]->repeat_count.','.$rows[$i]->search_count;
        fwrite( $handle, $str );
        fwrite( $handle, "\r\n");
    }

    fclose( $handle );

    // flush buffer
    ob_flush();
    
    // use exit to get rid of unexpected output afterward
    exit();
}

?>
<div class="wrap ss_keyword_wrapper">
    <h3> Search Keyword Statistics <span style="color: gray;">3.1</span> </h3>


    <div class="all_data">

        <div class="section group">
            <div class="col span_1_of_2">
                <?php
                if (isset($msg) && $msg == true) {
                    echo '<span style="color:green;font-size:12px;font-weight:bold; margin-left:20px;"> Delete record successfully. </span>';
                }
                ?>
                <div style="margin: 5px 0 25px 0;">
                    <h3> Clear all data (Please keep it note you will not get it back) </h3>

                    <form name="frmClear" id="frmClear" action="" method="post"
                        onsubmit="javascript:return clearDta();">

                        <input type="checkbox" name="clear" id="clear" /> To clear all data. &nbsp; <input type="submit"
                            name="ss_search_submit_clear" value="Clear" class="ss_search_submit" />
                    </form>

                    <script type="text/javascript">
                    function clearDta() {
                        if (confirm('Are you sure to delete data?')) {
                            return true;
                        } else
                            return false;
                    }
                    </script>

                </div>
            </div>
            <div class="col span_1_of_2">
                <div style="margin: 5px 0 25px 0;">
                    <h3> Export all data </h3>

                    <form name="frmExport" id="frmExport" action="" method="post">

                        <input type="submit" name="ss_search_submit_export" value="Export Data"
                            class="ss_search_submit" />
                    </form>

                </div>
            </div>
        </div>

    </div>
</div>