<?php

add_action( 'admin_notices', function() {
    
    // Check if the "ss-ald-r" cookie is present and has a value of current user id
    
    $ss_asking_review=true;
    
        if (isset($_COOKIE["ss-ald-r"]) && $_COOKIE["ss-ald-r"] == get_current_user_id()) {
            $ss_asking_review=false;
        }
    
        if($ss_asking_review==true)
        {
        ?>
        <style>
            #ss-keyword-review-asking
{
	padding: 15px 5px;
}
            </style>
        <div class="notice notice-success is-dismissible" id="ss-keyword-review-asking">
            <div class="row">
                <div class="col-1">
                <img src="<?php echo plugin_dir_url(__FILE__).'images/';?>SpiceHookSolutions.png" alt="SpiceHook Solutions" width="90" height="auto" style="margin-top: 20px;">
                </div>

               
                <div class="col-11">

            <p>
                <?php _e( 'Would you mind sparing just two minutes of your time? Your kind gesture of giving <strong>Search Keyword Statistics</strong> a 5-star rating on WordPress.org would be greatly appreciated. Your support will enable us to create even better free products in the future by sharing the love!', 'sh-language' ); ?>
            </p>
            <p>
                <?php _e( '<button class="button button-primary"><a style="color:#FFFFFF;" href="https://wordpress.org/support/plugin/search-keyword-statistics/reviews/?filter=5" target="_blank">Sure, Leave a review! </a></button> 
              <button onclick="javascript:ss_hide_user();" class="button btn-default" style="">I Already Did !</button>
              <button onclick="javascript:ss_instant_hide();" class="button btn-default" style="">Maybe Later !</button>'); ?>
              </p>
        </div>

        </div>
        </div>
        
        
        <script>
       function ss_instant_hide() {
          var v = document.getElementById("ss-keyword-review-asking");      
           v.style.display = "none";
       }
    </script>
    
    <script>
    function ss_hide_user(){
           // Get the current user's ID from WordPress
            var currentUserId = <?php echo get_current_user_id(); ?>;
    
            // Set the cookie with the user's ID and expiration date
            var expirationDate = new Date();
            expirationDate.setTime(expirationDate.getTime() + (60 * 24 * 60 * 60 * 1000)); // 60 days in milliseconds
            document.cookie = "ss-ald-r=" + currentUserId + ";expires=" + expirationDate.toUTCString() + ";path=/";
    
            var v = document.getElementById("ss-keyword-review-asking");      
            v.style.display = "none";
        }
    </script>
    
        <?php
    
        }// cookie check condition
    });
    
?>