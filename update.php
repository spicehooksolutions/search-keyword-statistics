<?php
global $wpdb;
if(get_option('SS_BG_VERSION_UPDATE_31')==null || get_option('SS_BG_VERSION_UPDATE_31')==false)
{
    

            $query =  "ALTER TABLE " . SS_TABLE . " MODIFY  COLUMN `user` varchar(255), MODIFY COLUMN `agent` TEXT NULL,MODIFY COLUMN source VARCHAR(255) NULL";
            $wpdb->query( $query );        
            
            $query =  "ALTER TABLE " . SS_TABLE . " ADD  COLUMN `devicetype` varchar(255) NULL";
            $wpdb->query( $query );
            
            update_option('SS_BG_VERSION_UPDATE_31',true);
}
?>