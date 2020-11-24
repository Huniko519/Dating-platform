    <?php if(IS_LOGGED == true){ ?>
		<!-- Boost Modal -->
		<div id="modal_boost" class="modal modal-sm">
			<div class="modal-content">
				<?php
					$_cost = 0;
					if( $profile->is_pro == "1" ){
						$_cost = $config->pro_boost_me_credits_price;
					}else{
						$_cost = $config->normal_boost_me_credits_price;
					}
				?>
				<h6 class="bold"><?php echo __('Boost me!');?></h6>
				<p><?php echo __('Get seen more by people around you in find match.');?></p>
				<p><?php echo __( 'This service costs you' ) . ' ' . $_cost. ' ' . __( 'credits and will last for' ) . ' ' . $config->boost_expire_time . ' ' . __( 'miuntes') . '.';?></p>
				<div class="modal-footer">
					<button type="button" class="btn-flat waves-effect modal-close"><?php echo __( 'Cancel' );?></button>
					<?php if($profile->balance >= $_cost ){?>
						<button data-userid="<?php echo $profile->id;?>" id="btn_boostme" class="modal-close waves-effect waves-light btn-flat btn_primary white-text"><?php echo __( 'Boost Now' );?></button>
					<?php }else{ ?>
						<a href="<?php echo $site_url;?>/credit" data-ajax="/credit" class="modal-close waves-effect waves-light btn-flat btn_primary white-text"><?php echo __( 'Buy Credits' );?></a>
					<?php } ?>
				</div>
			</div>
		</div>
		<!-- End Boost Modal -->
		
		<div class="sidenav_overlay" onclick="javascript:$('body').removeClass('side_open');"></div>
	<?php } ?>
		
	<!-- Scroll to top  -->
    <a href="javascript:void(0);" class="btn-floating btn-large waves-effect waves-light dt_to_top bg_gradient"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path fill="currentColor" d="M7.41,15.41L12,10.83L16.59,15.41L18,14L12,8L6,14L7.41,15.41Z"></path></svg></a>
    <!-- End Scroll to top  -->
    <?php require( $theme_path . 'main' . $_DS . 'script.php' );?>
	<?php require( $theme_path . 'main' . $_DS . 'geolocation.php' );?>
    <?php require( $theme_path . 'main' . $_DS . 'custom-footer-js.php' );?>
    <?php //write_console();?>
	
	<script type="text/javascript">
		$(document).on('click', '.find_matches_cont > .row > .col.l3 .dt_sections [data-ajax]', function() {
			$('body').removeClass('side_open');
		});
		</script>

    <?php if( IS_LOGGED == true ){ ?>
        <div style="display:none">
            <audio id="notification-sound" class="sound-controls" preload="auto" style="visibility: hidden;">
                <source src="<?php echo $theme_url; ?>assets/mp3/New-notification.mp3" type="audio/mpeg">
            </audio>
            <audio id="message-sound" class="sound-controls" preload="auto" style="visibility: hidden;">
                <source src="<?php echo $theme_url; ?>assets/mp3/New-message.mp3" type="audio/mpeg">
            </audio>
            <audio id="calling-sound" class="sound-controls" preload="auto">
                <source src="<?php echo $theme_url; ?>assets/mp3/calling.mp3" type="audio/mpeg">
            </audio>
            <audio id="video-calling-sound" class="sound-controls" preload="auto">
                <source src="<?php echo $theme_url; ?>assets/mp3/video_call.mp3" type="audio/mpeg">
            </audio>
        </div>
    <?php } ?>

    <?php if(route( 1 ) !== 'find-matches'){ ?>
    <script>
        window.addEventListener("load", function(){
            window.cookieconsent.initialise({
                "palette": {
                    "popup": {
                        "background": "#ffffff",
                        "text": "#afafaf"
                    },
                    "button": {
                        "background": "#c649b8"
                    }
                },
                "theme": "edgeless",
                "position": "bottom-left",
                "content": {
                    "message": "<?php echo __('This website uses cookies to ensure you get the best experience on our website.');?>",
                    "dismiss": "<?php echo __('Got It!');?>",
                    "link": "<?php echo __('Learn More');?>",
                    "href": "<?php echo $site_url;?>/privacy"
                }
            });
        });
    </script>
    <?php } ?>


</body>
</html>