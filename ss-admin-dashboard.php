<?php
global $wpdb;

/* For clear individual data */

if (isset($_POST) && count($_POST) > 0 && isset($_POST['keywords'])) {
    foreach ($_POST['keywords'] as $kw) {
        $deleQuery = "DELETE FROM " . SS_TABLE . " WHERE id='" . $kw . "'";
        $wpdb->query($deleQuery);
        $msg = true;
    }
}


if (isset($_POST) && count($_POST) > 0 && isset($_POST['ss_search'])) {
    $search = false;
    if (trim($_POST['ss_search_frm']) == '' || trim($_POST['ss_search_to']) == '') {
        $error = 'Select both dates';
        $rows = $wpdb->get_results("SELECT * FROM " . SS_TABLE . " ORDER BY repeat_count DESC");
    } else {
        $search = true;

        $frm = date('Y-m-d', strtotime($_POST['ss_search_frm']));
        $to = date('Y-m-d', strtotime($_POST['ss_search_to']));
        $rows = $wpdb->get_results("SELECT * FROM " . SS_TABLE . " WHERE  STR_TO_DATE(`query_date`,'%Y%m%d') BETWEEN '" . $frm . "' AND '" . $to . "' ORDER BY repeat_count DESC");
    }
} else
    $rows = $wpdb->get_results("SELECT * FROM " . SS_TABLE . " ORDER BY repeat_count DESC");
?>


<script>
jQuery(function() {
    jQuery("#ss_search_frm").datepicker();
    jQuery("#ss_search_to").datepicker();
});

function form_submit() {

    if (jQuery('ss_search_frm').val() == '' || jQuery('ss_search_to').val() == '' || jQuery('ss_search_frm').val() ==
        undefined || jQuery('ss_search_to').val() == undefined) {
        alert('Please select both dates');
        return false;
    } else
        return true;
}
</script>
<div class="wrap ss_keyword_wrapper">
    <h3> Search Keyword Statistics <span style="color: gray;">3.1</span> </h3>

    <?php
    if (isset($error) && $error != '') {
        echo '<span style="color:red;font-size:12px;font-weight:bold;">' . $error . '</span>';
    }
    ?>

    <div class="all_data">
        <h2>Search Keyword Statistics ( All records)</h2>

        <form action="" method="post" enctype="multipart/formdata" name="frmSortSS" class="BFDfrmSortSS">
            <input type="hidden" name="ss_search" value="<?php echo rand(88, 8888); ?>" />

            From : <input type="text" name="ss_search_frm" id="ss_search_frm"
                value="<?php echo (isset($_POST['ss_search_frm'])?$_POST['ss_search_frm']:'');?>" size="10"
                class="BFDsearchKeyWordtextBox" readonly />
            To : <input type="text" name="ss_search_to" id="ss_search_to"
                value="<?php echo (isset($_POST['ss_search_to'])?$_POST['ss_search_to']:'');?>" size="10"
                class="BFDsearchKeyWordtextBox" readonly />

            <input type="submit" name="ss_search_submit" value="Search" class="ss_search_submit" />

        </form>
        <br />
        <?php
            if (isset($search) && $search == true) {
                echo '<div style="color:black;font-size:14px;font-weight:500; margin:20px 0; background-color:#f3f9fae6;padding: 5px 20px;"> Result between ' . wp_date('M d Y',strtotime($frm)) . ' To ' . wp_date('M d Y',strtotime($to)) . ' &nbsp;  <input type="button" name="clear_result" value="Clear" class="ss_search_submit" onclick="javascript:location.href=location.href;" /> </div>';
            }

            
            ?>
    </div>

    <form action="" method="post" name="frmKeyword">
        <table cellpadding="0" cellspacing="0" border="0"
            class="display wp-list-table widefat fixed striped table-view-list ss__keyword_search"
            id="ss__keyword_search" width="100%">
            <thead>
                <tr>

                    <th>Sl No</th>
                    <th>keywords</th>
                    <th>User</th>
                    <th>Browser</th>
                    <th>OS</th>
                    <th>Mobile/Desktop</th>
                    <th>Repeat</th>
                    <th>No. of Result</th>
                    <th>Searched on</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $BrowserSSKeyword = new forocoSS\BrowserDetection();
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
                            

                        if(isset($browserDetails['device_type']) && $browserDetails['device_type']!='')
                        {
                            $wpdb->query("UPDATE " . SS_TABLE . " SET devicetype='".$browserDetails['device_type']."' WHERE id='".$rows[$i]->id."'");
                        }

                            $mySearch =new WP_Query("s=".stripslashes($rows[$i]->keywords)." & showposts=-1");
                            $NumResults = $mySearch->post_count;

                            if( $NumResults>0)
                            {
                                
                                if( $mySearch->have_posts()): 
                                    $posttypes=array();
                                    $posts=$mySearch->posts;
                                    foreach ($posts as $key => $value) {
                                        if(get_post_type( $value->ID )!='')
                                        {
                                            $postType=get_post_type_object(get_post_type( $value->ID ));
                                            $posttypes[]=$postType->labels->singular_name;
                                        }
                                    }
                                    else:
                                    endif;

                                    //var_dump(array_unique($posttypes));
                            }

                        ?>
                <tr class="odd gradeX">

                    <td> <input type="checkbox" name="keywords[]" id="keywords<?php echo $i + 1; ?>"
                            value="<?php echo $rows[$i]->id; ?>" /> <?php echo $i + 1; ?></td>
                    <td><?php echo stripslashes($rows[$i]->keywords); ?></td>
                    <td><?php echo $user; ?></td>
                    <?php
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


                        ?>
                    <td><img src="<?php echo plugin_dir_url(__FILE__).'browsers/'.strtolower($bname);?>.png"
                            class="flag-16" /> <span><?php echo $browserDetails['browser_name'];?></span></td>

                    <td><?php echo ucfirst((isset($browserDetails['os_name'])?$browserDetails['os_name']:""));?></td>
                    <td><?php echo ucfirst((isset($browserDetails['device_type'])?$browserDetails['device_type']:""));?>
                    </td>

                    <td class="center"><?php echo $rows[$i]->repeat_count; ?></td>
                    <td class="center"><?php echo $rows[$i]->search_count; ?></td>
                    <td class="center"><?php echo wp_date('M d Y, h:i:s a',strtotime($rows[$i]->query_date)); ?></td>
                </tr>
                <?php
                    }
                    ?>

            </tbody>
            <tfoot>
                <tr>
                    <th>Sl No</th>
                    <th>keywords</th>
                    <th>User</th>
                    <th>Browser</th>
                    <th>OS</th>
                    <th>Mobile/Desktop</th>
                    <th>Repeat</th>
                    <th>No. of Result</th>
                    <th>Searched on</th>
                </tr>
            </tfoot>
        </table>
        <div
            style="margin:30px 10px; text-align:right; font-size:18px; font-family:Georgia, 'Times New Roman', Times, serif; padding:5px 5px;">
            <input type="submit" name="keywordSubmit" value="Delete" class="ss_search_submit" />
        </div>
    </form>

    <script type="text/javascript">
    jQuery(document).ready(function() {
        jQuery('#ss__keyword_search').dataTable();
    });
    </script>
</div>
<div class="wrap ss_keyword_wrapper">
    <div class="section group">
        <div class="col span_1_of_2">
            <h2>Search Keyword Statistics ( Mobile devices)</h2>

            <?php
            $rows = $wpdb->get_results("SELECT * FROM " . SS_TABLE . " WHERE devicetype='mobile' ORDER BY repeat_count DESC");
            ?>
            <table cellpadding="0" cellspacing="0" border="0"
                class="display wp-list-table widefat fixed striped table-view-list ss__keyword_search"
                id="ss__keyword_search_mobile" width="100%">
                <thead>
                    <tr>
                        <th>keywords</th>
                        <th>Browser</th>
                        <th>OS</th>
                        <th>Repeat</th>
                        <th>No. of Result</th>
                        <th>Searched on</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    
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
                          
                            

                        ?>
                    <tr class="odd gradeX">

                       
                        <td><?php echo stripslashes($rows[$i]->keywords); ?></td>
                       
                        <?php
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


                        ?>
                        <td><img src="<?php echo plugin_dir_url(__FILE__).'browsers/'.strtolower($bname);?>.png"
                                class="flag-16" /> <span><?php echo $browserDetails['browser_name'];?></span></td>

                        <td><?php echo ucfirst((isset($browserDetails['os_name'])?$browserDetails['os_name']:""));?>
                        </td> 
                        <td class="center"><?php echo $rows[$i]->repeat_count; ?></td>
                        <td class="center"><?php echo $rows[$i]->search_count; ?></td>
                        <td class="center"><?php echo wp_date('M d Y, h:i:s a',strtotime($rows[$i]->query_date)); ?>
                        </td>
                    </tr>
                    <?php
                    }
                    ?>

                </tbody>
                <tfoot>
                    <tr>
                        <th>keywords</th>
                        <th>Browser</th>
                        <th>OS</th>
                        <th>Repeat</th>
                        <th>No. of Result</th>
                        <th>Searched on</th>
                    </tr>
                </tfoot>
            </table>

            <script type="text/javascript">
            jQuery(document).ready(function() {
                jQuery('#ss__keyword_search_mobile').dataTable();
            });
            </script>
        </div>
        <div class="col span_1_of_2">
        <h2>Search Keyword Statistics ( Desktop devices)</h2>

        <?php
        $rows = $wpdb->get_results("SELECT * FROM " . SS_TABLE . " WHERE devicetype!='mobile' ORDER BY repeat_count DESC");
        ?>
        <table cellpadding="0" cellspacing="0" border="0"
            class="display wp-list-table widefat fixed striped table-view-list ss__keyword_search"
            id="ss__keyword_search_desktop" width="100%">
            <thead>
                <tr>
                    <th>keywords</th>
                    <th>Browser</th>
                    <th>OS</th>
                    <th>Repeat</th>
                    <th>No. of Result</th>
                    <th>Searched on</th>
                </tr>
            </thead>
            <tbody>
                <?php
                
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
                    
                        

                    ?>
                <tr class="odd gradeX">

                
                    <td><?php echo stripslashes($rows[$i]->keywords); ?></td>
                
                    <?php
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


                    ?>
                    <td><img src="<?php echo plugin_dir_url(__FILE__).'browsers/'.strtolower($bname);?>.png"
                            class="flag-16" /> <span><?php echo $browserDetails['browser_name'];?></span></td>

                    <td><?php echo ucfirst((isset($browserDetails['os_name'])?$browserDetails['os_name']:""));?>
                    </td> 
                    <td class="center"><?php echo $rows[$i]->repeat_count; ?></td>
                    <td class="center"><?php echo $rows[$i]->search_count; ?></td>
                    <td class="center"><?php echo wp_date('M d Y, h:i:s a',strtotime($rows[$i]->query_date)); ?>
                    </td>
                </tr>
                <?php
                }
                ?>

            </tbody>
            <tfoot>
                <tr>
                    <th>keywords</th>
                    <th>Browser</th>
                    <th>OS</th>
                    <th>Repeat</th>
                    <th>No. of Result</th>
                    <th>Searched on</th>
                </tr>
            </tfoot>
        </table>

        <script type="text/javascript">
        jQuery(document).ready(function() {
            jQuery('#ss__keyword_search_desktop').dataTable();
        });
        </script>
        </div>
    </div>
</div>