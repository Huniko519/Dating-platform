<script>
        document_title = document.title;

        window.site_url =  "<?php echo $config->uri; ?>";
        window.ajax = "<?php echo $config->uri . "/aj/"; ?>";
        window.theme_url = "<?php echo $theme_url; ?>";
        window.worker_updateDelay = '<?php echo (int)$config->worker_updateDelay;?>';
        window.media_path = "<?php echo GetMedia('', false);?>";
        window.current_route1 = "/<?php echo route( 1 );?>";
        window.current_route2 = "/<?php echo route( 2 );?>";
        window.current_route3 = "/<?php echo route( 3 );?>";
        window.current_route4 = "/<?php echo route( 4 );?>";
        window.current_page = "<?php echo $data['name'];?>";
        <?php
        if(isset($_GET['access']) && $_GET['access'] == 'admin'){
            echo 'window.maintenance_mode = "?access=admin";' . "\n";
        }else{
            echo 'window.maintenance_mode = ""' . "\n";
        }

        if(IS_LOGGED === true){
            if (isset(auth()->username)) {
                echo 'window.loggedin_user = "@' . auth()->username . '";' . "\n";
                echo 'window.loggedInUserID = "' . strtolower(auth()->web_token) . '";' . "\n";
                echo 'window.start_up = "' . auth()->start_up . '";' . "\n";
                echo 'window.swaps = ' .GetUserTotalSwipes(auth()->id) . ';' . "\n";
                if(auth()->is_pro === "1"){
                    echo 'window.max_swaps = 999999999999999999999999;' . "\n";
                }else{
                    echo 'window.max_swaps = ' . (($config->max_swaps ) ? (int)$config->max_swaps : 0) .';' . "\n";
                }
            }
        }
        ?>
    </script>
<?php
if(IS_LOGGED === true){
    echo '<script src="//media.twiliocdn.com/sdk/js/video/v1/twilio-video.min.js"></script>';

}
?>
    <script src="<?php echo $theme_url . 'assets/js/jquery-2.1.1.min.js';?>" type="text/javascript" id="jquery-2.1.1"></script>
    <script src="<?php echo $theme_url . 'assets/js/functions.js';?>" type="text/javascript" id="functions"></script>

