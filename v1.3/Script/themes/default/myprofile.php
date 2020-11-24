<?php
    global $db;
    $views_count = 0;
    $views = $db->objectBuilder()
                ->where('v.view_userid', $profile->id)
                ->groupBy('v.user_id')
                ->orderBy('v.created_at', 'DESC')
                ->get('views v', null, array('COUNT(DISTINCT v.user_id) AS views'));
    if( $views !== null ){
        $views_count = COUNT($views);
    }
    $likes_count = $db->where('like_userid',$profile->id)->getOne('likes','count(id) as likes')['likes'];

?>
<!-- Profile  -->
<style>
@media (max-width: 1024px){
.dt_slide_menu {
	display: none;
}
nav .header_user {
	display: block;
}
}
</style>
<div class="container dt_user_profile_parent">
    <!-- display gps not enable message - see header js -->
    <div class="alert alert-warning hide" role="alert" id="gps_not_enabled">
        <p><?php echo __( 'Please Enable Location Services on your browser.' );?></p>
    </div>
    <script>
        var gps_not_enabled = document.querySelector('#gps_not_enabled');
        if( window.gps_is_not_enabled == true ){
            gps_not_enabled.classList.remove('hide');
        }
    </script>

    <div class="row r_margin">
        <div class="col s12 m3 custom_fixed_element">
            <!-- Left Sidebar -->
            <div class="dt_user_profile">
                <div class="dt_user_info">
                    <div class="avatar">
                        <a class="inline" href="<?php echo $profile->avater->full;?>" id="avater_profile_img">
                            <img src="<?php echo $profile->avater->avater;?>" alt="<?php echo $profile->full_name;?>" class="responsive-img" />
                            <?php if((int)abs(((strtotime(date('Y-m-d H:i:s')) - $profile->lastseen))) < 60 && (int)$profile->online == 1) { echo '<div class="useronline" style="top: 10px;left: 10px;"></div>'; }?>
                        </a>
                        <div class="dt_chng_avtr">
							<span class="btn-upload-image" onclick="document.getElementById('profileavatar_img').click(); return false">
								<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M21,17H7V3H21M21,1H7A2,2 0 0,0 5,3V17A2,2 0 0,0 7,19H21A2,2 0 0,0 23,17V3A2,2 0 0,0 21,1M3,5H1V21A2,2 0 0,0 3,23H19V21H3M15.96,10.29L13.21,13.83L11.25,11.47L8.5,15H19.5L15.96,10.29Z" /></svg> <?php echo __( 'Change Photo' );?>
							</span>
                            <input type="file" id="profileavatar_img" class="hide" accept="image/x-png, image/gif, image/jpeg" name="avatar">
                        </div>
						<div class="dt_avatar_progress hide">
							<div class="avatar_imgprogress progress">
								<div class="avatar_imgdeterminate determinate" style="width: 0%"></div >
							</div>
						</div>
                    </div>
                </div>
                <div class="dt_user_pro_info">
                    <ul>
                        <li>
                            <a href="<?php echo $site_url;?>/popularity" data-ajax="/popularity">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path fill="#ff5722" d="M12,16A3,3 0 0,1 9,13C9,11.88 9.61,10.9 10.5,10.39L20.21,4.77L14.68,14.35C14.18,15.33 13.17,16 12,16M12,3C13.81,3 15.5,3.5 16.97,4.32L14.87,5.53C14,5.19 13,5 12,5A8,8 0 0,0 4,13C4,15.21 4.89,17.21 6.34,18.65H6.35C6.74,19.04 6.74,19.67 6.35,20.06C5.96,20.45 5.32,20.45 4.93,20.07V20.07C3.12,18.26 2,15.76 2,13A10,10 0 0,1 12,3M22,13C22,15.76 20.88,18.26 19.07,20.07V20.07C18.68,20.45 18.05,20.45 17.66,20.06C17.27,19.67 17.27,19.04 17.66,18.65V18.65C19.11,17.2 20,15.21 20,13C20,12 19.81,11 19.46,10.1L20.67,8C21.5,9.5 22,11.18 22,13Z"></path></svg>
                                <span style="display: inline-block;"><?php echo __( 'Popularity' );?> </span><span class="bold" style="margin: 0px 5px;color: #a33596;display: inline-block;"><?php echo GetUserPopularity($profile->id);?></span>
                                <span class="bold"><?php echo __( 'Increase' );?></span>
                            </a>
                        </li>
                        <?php if( $profile->is_pro == 0 && $config->pro_system == 1 ){?>
                        <li>
                            <a href="<?php echo $site_url;?>/pro" data-ajax="/pro">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path fill="#2196f3" d="M16,9H19L14,16M10,9H14L12,17M5,9H8L10,16M15,4H17L19,7H16M11,4H13L14,7H10M7,4H9L8,7H5M6,2L2,8L12,22L22,8L18,2H6Z"></path></svg>
                                <span><?php echo __( 'Premium' );?></span>
                                <span class="bold"><?php echo __( 'Upgrade' );?></span>
                            </a>
                        </li>
                        <?php } ?>
                    </ul>
                </div>
                <div class="dt_user_social">
                    <?php if( !empty( $profile->facebook ) || !empty( $profile->twitter ) || !empty( $profile->google ) || !empty( $profile->instagram ) || !empty( $profile->linkedin ) || !empty( $profile->website ) ) {?>
                        <div class="valign-wrapper">
                            <h5><?php echo __( 'Social accounts' );?></h5>
                        </div>
                    <?php } ?>
                    <ul>
                        <?php if( !empty( $profile->facebook ) ) {?>
                            <li class="fb">
                                <a href="https://www.facebook.com/<?php echo $profile->facebook;?>" target="_blank">
                                    <div class="soc_icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M13.397,20.997v-8.196h2.765l0.411-3.209h-3.176V7.548c0-0.926,0.258-1.56,1.587-1.56h1.684V3.127	C15.849,3.039,15.025,2.997,14.201,3c-2.444,0-4.122,1.492-4.122,4.231v2.355H7.332v3.209h2.753v8.202H13.397z"/></svg>
                                    </div>
                                    <div class="soc_info">
                                        <p><?php echo __( 'Facebook' );?></p>
                                        <span>@<?php echo $profile->facebook;?></span>
                                    </div>
                                </a>
                            </li>
                        <?php } ?>
                        <?php if( !empty( $profile->twitter ) ) {?>
                            <li class="twit">
                                <a href="https://twitter.com/<?php echo $profile->twitter;?>" target="_blank">
                                    <div class="soc_icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M22.46,6C21.69,6.35 20.86,6.58 20,6.69C20.88,6.16 21.56,5.32 21.88,4.31C21.05,4.81 20.13,5.16 19.16,5.36C18.37,4.5 17.26,4 16,4C13.65,4 11.73,5.92 11.73,8.29C11.73,8.63 11.77,8.96 11.84,9.27C8.28,9.09 5.11,7.38 3,4.79C2.63,5.42 2.42,6.16 2.42,6.94C2.42,8.43 3.17,9.75 4.33,10.5C3.62,10.5 2.96,10.3 2.38,10C2.38,10 2.38,10 2.38,10.03C2.38,12.11 3.86,13.85 5.82,14.24C5.46,14.34 5.08,14.39 4.69,14.39C4.42,14.39 4.15,14.36 3.89,14.31C4.43,16 6,17.26 7.89,17.29C6.43,18.45 4.58,19.13 2.56,19.13C2.22,19.13 1.88,19.11 1.54,19.07C3.44,20.29 5.7,21 8.12,21C16,21 20.33,14.46 20.33,8.79C20.33,8.6 20.33,8.42 20.32,8.23C21.16,7.63 21.88,6.87 22.46,6Z"/></svg>
                                    </div>
                                    <div class="soc_info">
                                        <p><?php echo __( 'Twitter' );?></p>
                                        <span>@<?php echo $profile->twitter;?></span>
                                    </div>
                                </a>
                            </li>
                        <?php } ?>
                        <?php if( !empty( $profile->google ) ) {?>
                            <li class="gplus">
                                <a href="https://plus.google.com/<?php echo $profile->google;?>" target="_blank">
                                    <div class="soc_icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M23,11H21V9H19V11H17V13H19V15H21V13H23M8,11V13.4H12C11.8,14.4 10.8,16.4 8,16.4C5.6,16.4 3.7,14.4 3.7,12C3.7,9.6 5.6,7.6 8,7.6C9.4,7.6 10.3,8.2 10.8,8.7L12.7,6.9C11.5,5.7 9.9,5 8,5C4.1,5 1,8.1 1,12C1,15.9 4.1,19 8,19C12,19 14.7,16.2 14.7,12.2C14.7,11.7 14.7,11.4 14.6,11H8Z"/></svg>
                                    </div>
                                    <div class="soc_info">
                                        <p><?php echo __( 'Google Plus' );?></p>
                                        <span>@<?php echo $profile->google;?></span>
                                    </div>
                                </a>
                            </li>
                        <?php } ?>
                        <?php if( !empty( $profile->instagram ) ) {?>
                            <li class="insta">
                                <a href="https://www.instagram.com/<?php echo $profile->instagram;?>" target="_blank">
                                    <div class="soc_icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M7.8,2H16.2C19.4,2 22,4.6 22,7.8V16.2A5.8,5.8 0 0,1 16.2,22H7.8C4.6,22 2,19.4 2,16.2V7.8A5.8,5.8 0 0,1 7.8,2M7.6,4A3.6,3.6 0 0,0 4,7.6V16.4C4,18.39 5.61,20 7.6,20H16.4A3.6,3.6 0 0,0 20,16.4V7.6C20,5.61 18.39,4 16.4,4H7.6M17.25,5.5A1.25,1.25 0 0,1 18.5,6.75A1.25,1.25 0 0,1 17.25,8A1.25,1.25 0 0,1 16,6.75A1.25,1.25 0 0,1 17.25,5.5M12,7A5,5 0 0,1 17,12A5,5 0 0,1 12,17A5,5 0 0,1 7,12A5,5 0 0,1 12,7M12,9A3,3 0 0,0 9,12A3,3 0 0,0 12,15A3,3 0 0,0 15,12A3,3 0 0,0 12,9Z"/></svg>
                                    </div>
                                    <div class="soc_info">
                                        <p><?php echo __( 'instagram' );?></p>
                                        <span>@<?php echo $profile->instagram;?></span>
                                    </div>
                                </a>
                            </li>
                        <?php } ?>
                        <?php if( !empty( $profile->linkedin ) ) {?>
                            <li class="lin">
                                <a href="https://www.linkedin.com/in/<?php echo $profile->linkedin;?>" target="_blank">
                                    <div class="soc_icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M21,21H17V14.25C17,13.19 15.81,12.31 14.75,12.31C13.69,12.31 13,13.19 13,14.25V21H9V9H13V11C13.66,9.93 15.36,9.24 16.5,9.24C19,9.24 21,11.28 21,13.75V21M7,21H3V9H7V21M5,3A2,2 0 0,1 7,5A2,2 0 0,1 5,7A2,2 0 0,1 3,5A2,2 0 0,1 5,3Z"/></svg>
                                    </div>
                                    <div class="soc_info">
                                        <p><?php echo __( 'LinkedIn' );?></p>
                                        <span>@<?php echo $profile->linkedin;?></span>
                                    </div>
                                </a>
                            </li>
                        <?php } ?>
                        <?php if( !empty( $profile->website ) ) {?>
                            <li>
                                <a href="<?php echo $profile->website;?>" target="_blank">
                                    <div class="soc_icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M12 2C6.486 2 2 6.486 2 12s4.486 10 10 10 10-4.486 10-10S17.514 2 12 2zM4.069 13h2.974c.136 2.379.665 4.478 1.556 6.23A8.01 8.01 0 0 1 4.069 13zm2.961-2H4.069a8.012 8.012 0 0 1 4.618-6.273C7.704 6.618 7.136 8.762 7.03 11zm5.522 8.972c-.183.012-.365.028-.552.028-.186 0-.367-.016-.55-.027-1.401-1.698-2.228-4.077-2.409-6.973h6.113c-.208 2.773-1.117 5.196-2.602 6.972zM9.03 11c.139-2.596.994-5.028 2.451-6.974.172-.01.344-.026.519-.026.179 0 .354.016.53.027 1.035 1.364 2.427 3.78 2.627 6.973H9.03zm6.431 8.201c.955-1.794 1.538-3.901 1.691-6.201h2.778a8.005 8.005 0 0 1-4.469 6.201zM17.167 11a14.67 14.67 0 0 0-1.792-6.243A8.014 8.014 0 0 1 19.931 11h-2.764z"/></svg>
                                    </div>
                                    <div class="soc_info">
                                        <p><?php echo __( 'Website' );?></p>
                                        <span><?php echo $profile->website;?></span>
                                    </div>
                                </a>
                            </li>
                        <?php } ?>

                        <?php
                        $social_fields = GetProfileFields('social');
                        $social_custom_data = UserFieldsData($profile->id);
                        if (count($social_fields) > 0) {
                            foreach ($social_fields as $key => $field) {
                                if($field['profile_page'] == 1) {
                                    if( isset($social_custom_data[$field['fid']]) && $social_custom_data[$field['fid']] !== null ) {
                                        echo '<li>';
                                        echo '    <a href="' . $social_custom_data[$field['fid']] . '" target="_blank">';
                                        echo '    <div class="soc_icon"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M12 2C6.486 2 2 6.486 2 12s4.486 10 10 10 10-4.486 10-10S17.514 2 12 2zM4.069 13h2.974c.136 2.379.665 4.478 1.556 6.23A8.01 8.01 0 0 1 4.069 13zm2.961-2H4.069a8.012 8.012 0 0 1 4.618-6.273C7.704 6.618 7.136 8.762 7.03 11zm5.522 8.972c-.183.012-.365.028-.552.028-.186 0-.367-.016-.55-.027-1.401-1.698-2.228-4.077-2.409-6.973h6.113c-.208 2.773-1.117 5.196-2.602 6.972zM9.03 11c.139-2.596.994-5.028 2.451-6.974.172-.01.344-.026.519-.026.179 0 .354.016.53.027 1.035 1.364 2.427 3.78 2.627 6.973H9.03zm6.431 8.201c.955-1.794 1.538-3.901 1.691-6.201h2.778a8.005 8.005 0 0 1-4.469 6.201zM17.167 11a14.67 14.67 0 0 0-1.792-6.243A8.014 8.014 0 0 1 19.931 11h-2.764z"/></svg></div>';
                                        echo '    <div class="soc_info"><p>' . $field['name'] . '</p><span>' . $social_custom_data[$field['fid']] . '</span></div></a>';
                                        echo '</li>';
                                    }
                                }
                            }
                        }
                        ?>

                    </ul>
                </div>
            </div> <!-- End Left Sidebar -->
			
			<div class="dt_user_profile">
                <div class="dt_user_prof_complt">
                    <h5 class="valign-wrapper"><?php echo __( 'Profile Completion' );?><span><?php echo $profile->profile_completion;?>%</span></h5>
					<div class="progress">
						<div class="determinate" style="width: <?php echo $profile->profile_completion;?>%"></div>
					</div>
				</div>
			</div>

            <?php echo GetAd('profile_side_bar');?>

        </div>
		
        <div class="col s12 m9">
            <!-- Right Main Area -->

            <?php if( $config->image_verification == 0 ){ ?>

				<?php if( verifiedUser($profile) == false ){ ?>
                    <div class="dt_user_profile dt_how_to_verfy_alrt">
                        <span>
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="#2196F3" d="M10,17L6,13L7.41,11.59L10,14.17L16.59,7.58L18,9M12,1L3,5V11C3,16.55 6.84,21.74 12,23C17.16,21.74 21,16.55 21,11V5L12,1Z" /></svg> <?php echo __( 'To get your profile verified you have to verify these.');?>
                        </span>
						<ul class="browser-default dt_prof_vrfy">
                            <?php if($config->emailValidation == "1"){?>
                                <?php if( $config->sms_or_email === 'mail' ){?>
                                    <?php if( $profile->active === "0" ){?>
                                        <li><?php echo __( 'Please verify your email address' );?> <a href="<?php echo $site_url;?>/verifymail" data-ajax="/verifymail"><?php echo __( 'Verify Now' );?></a>.</li>
                                    <?php } ?>
                                <?php } ?>
                                <?php if( $config->sms_or_email == 'sms' ){?>
                                    <?php if( !empty( $profile->phone_number ) && $profile->phone_verified == "0" ){?>
                                        <li><?php echo __( 'Please verify your phone number' );?> <a href="<?php echo $site_url;?>/verifyphone" data-ajax="/verifyphone"><?php echo __( 'Verify Now' );?></a>.</li>
                                    <?php } ?>
                                <?php } ?>
                            <?php } ?>
                            <?php if(count($profile->mediafiles) < 5){ ?>
							<li><?php echo __( 'Upload at least 5 image.');?></li>
                            <?php }?>
						</ul>
                    </div>
                <?php } ?>

            <?php } ?>

			<div class="dt_user_profile dt_user_info">
				<div class="info">
					<div class="combo valign-wrapper">
						<h2>
							<?php echo $profile->full_name;?><?php echo ( $profile->age  > 0 ) ? ", ". $profile->age : "";?>
							<?php if( verifiedUser($profile) ){ ?>
								<span tooltip="<?php
                                if( $config->image_verification == 1 && $profile->approved_at > 0 ){
                                    echo __( 'This profile is Verified by photos' );
                                }else{
                                    echo __( 'This profile is Verified by phone' );
                                }


                                ?>" flow="down">
									<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="#2196F3" d="M10,17L6,13L7.41,11.59L10,14.17L16.59,7.58L18,9M12,1L3,5V11C3,16.55 6.84,21.74 12,23C17.16,21.74 21,16.55 21,11V5L12,1Z" /></svg>
								</span>
							<?php }else{ ?>
								<span tooltip="<?php echo __( 'This profile is Not verified' );?>" flow="down">
									<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="#e18805" d="M12,1L3,5V11C3,16.55 6.84,21.74 12,23C17.16,21.74 21,16.55 21,11V5L12,1M17,15.59L15.59,17L12,13.41L8.41,17L7,15.59L10.59,12L7,8.41L8.41,7L12,10.59L15.59,7L17,8.41L13.41,12L17,15.59Z" /></svg>
								</span>
							<?php } ?>
						</h2>
						<a class="user_btn" href="<?php echo $site_url;?>/settings/<?php echo $profile->username;?>" data-ajax="/settings/<?php echo $profile->username;?>"><?php echo __( 'Edit' );?></a>
					</div>
					<?php if( $profile->country !== '' ){?>
						<p class="valign-wrapper"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="#8BC34A" d="M12,11.5A2.5,2.5 0 0,1 9.5,9A2.5,2.5 0 0,1 12,6.5A2.5,2.5 0 0,1 14.5,9A2.5,2.5 0 0,1 12,11.5M12,2A7,7 0 0,0 5,9C5,14.25 12,22 12,22C12,22 19,14.25 19,9A7,7 0 0,0 12,2Z" /></svg> <?php echo $profile->country_txt;?></p>
					<?php } ?>
					<div class="dt_user_likes">
						<ul>
							<li><a href="<?php echo $site_url;?>/likes" data-ajax="/likes" id="mylikesx" class="waves-effect"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="#f25e4e" d="M12,21.35L10.55,20.03C5.4,15.36 2,12.27 2,8.5C2,5.41 4.42,3 7.5,3C9.24,3 10.91,3.81 12,5.08C13.09,3.81 14.76,3 16.5,3C19.58,3 22,5.41 22,8.5C22,12.27 18.6,15.36 13.45,20.03L12,21.35Z" /></svg> <span class="bold"><?php echo $likes_count;?></span> <?php echo __( 'Likes' );?></a></li>
							<li><a href="<?php echo $site_url;?>/visits" data-ajax="/visits" id="myViewsx" class="waves-effect"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="#673ab7" d="M12,9A3,3 0 0,0 9,12A3,3 0 0,0 12,15A3,3 0 0,0 15,12A3,3 0 0,0 12,9M12,17A5,5 0 0,1 7,12A5,5 0 0,1 12,7A5,5 0 0,1 17,12A5,5 0 0,1 12,17M12,4.5C7,4.5 2.73,7.61 1,12C2.73,16.39 7,19.5 12,19.5C17,19.5 21.27,16.39 23,12C21.27,7.61 17,4.5 12,4.5Z" /></svg> <span class="bold"><?php echo $views_count;?></span> <?php echo __( 'Views' );?></a></li>
						</ul>
					</div>
				</div>
			</div>
			
			<div class="dt_user_profile">
				<!-- Cover -->
				<figure class="dt_cover_photos">
					<div class="dt_cp_photos_list">
						<?php
						$i = 0;
						$media_count = count( (array)$profile->mediafiles );
						$gallery = array();
						$gallery['visable'][0] = null;
						$gallery['visable'][1] = null;
						$gallery['visable'][2] = null;
						$gallery['visable'][3] = null;

						for( $i == 0 ; $i < $media_count ; $i++ ){
							$gallery['visable'][$i] = $profile->mediafiles[$i];
						}

						foreach ($gallery['visable'] as $key => $value) {
							if( !empty( $value ) ){
                                $private = 'false';
                                $img_path = $value['avater'];
                                if( $value['is_private'] == 1 ){
                                    $private = 'true';
                                    $img_path = $value['private_file_avater'];
                                }
                                $is_avater = 'false';
                                if($value['avater'] == $profile->avater->avater){
                                    $is_avater = 'true';
                                }
								echo '<div class="dt_cp_l_photos">';
								echo '<a class="inline" href="'. $value['full'] .'" data-fancybox="gallery" data-private="'.$private.'" data-avater="'.$is_avater.'"><img src="' . $img_path .'" alt="' . $profile->username .'"></a>';
								echo '</div>';
							}else{
								echo '<div class="dt_cp_l_photos">';
								echo '<div class="inline"></div>';
								echo '</div>';
							}
						}
						?>
						<div class="dt_cp_bar_add_photos" onclick="document.getElementById('avatar_profileimg').click(); return false"> <!-- Add Photo -->
							<div class="inline">
								<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M5,3A2,2 0 0,0 3,5V19A2,2 0 0,0 5,21H14.09C14.03,20.67 14,20.34 14,20C14,19.32 14.12,18.64 14.35,18H5L8.5,13.5L11,16.5L14.5,12L16.73,14.97C17.7,14.34 18.84,14 20,14C20.34,14 20.67,14.03 21,14.09V5C21,3.89 20.1,3 19,3H5M19,16V19H16V21H19V24H21V21H24V19H21V16H19Z" /></svg>
								<b><?php echo __( 'Add Photos' );?></b>
							</div>
						</div>
						<input type="file" id="avatar_profileimg" class="hide" accept="image/x-png, image/gif, image/jpeg" name="profile_images" multiple="multiple">
					</div>
				</figure> <!-- End Cover -->
			</div>
			
            <div class="dt_user_profile">
                <div class="dt_user_about">
                    <?php if( !empty( $profile->about ) ) {?>
                        <div class="about_block"> <!-- About You -->
                            <h4>
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M12,4A4,4 0 0,1 16,8A4,4 0 0,1 12,12A4,4 0 0,1 8,8A4,4 0 0,1 12,4M12,14C16.42,14 20,15.79 20,18V20H4V18C4,15.79 7.58,14 12,14Z" /></svg>
                                <?php echo __( 'About You' );?>
                                <span><a class="edit_link" href="<?php echo $site_url;?>/settings-profile/<?php echo $profile->username;?>" data-ajax="/settings-profile/<?php echo $profile->username;?>"><?php echo __( 'Edit' );?></a></span>
                            </h4>
                            <p class="description"><?php echo nl2br($profile->about);?></p>
                        </div>
                    <?php } ?>

                    <?php if( !empty( $profile->location ) ) {?>
                        <div class="about_block"> <!-- Location -->
                            <h4>
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M12,11.5A2.5,2.5 0 0,1 9.5,9A2.5,2.5 0 0,1 12,6.5A2.5,2.5 0 0,1 14.5,9A2.5,2.5 0 0,1 12,11.5M12,2A7,7 0 0,0 5,9C5,14.25 12,22 12,22C12,22 19,14.25 19,9A7,7 0 0,0 12,2Z" /></svg>
                                <?php echo __( 'Location' );?>
                                <span><a class="edit_link" href="<?php echo $site_url;?>/settings-profile/<?php echo $profile->username;?>" data-ajax="/settings-profile/<?php echo $profile->username;?>"><?php echo __( 'Edit' );?></a></span>
                            </h4>
                            <p class="description"><?php echo $profile->location;?></p>
                            <div class="location_map">
                                <img src="https://maps.googleapis.com/maps/api/staticmap?center=<?php echo urlencode($profile->location);?>&zoom=13&size=600x205&maptype=roadmap&key=AIzaSyBFZHfyVXQ0H1Fh30rrZEOUgAi55_zYbZE" alt="<?php echo __( 'Location' );?>" />
                            </div>
                        </div>
                    <?php } ?>

                    <?php if( !empty( $profile->interest ) ) {?>
                        <div class="about_block"> <!-- Interests -->
                            <h4>
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M14,10H2V12H14V10M14,6H2V8H14V6M2,16H10V14H2V16M21.5,11.5L23,13L16,20L11.5,15.5L13,14L16,17L21.5,11.5Z" /></svg>
                                <?php echo __( 'Interests' );?>
                                <span><a class="edit_link" href="<?php echo $site_url;?>/settings-profile/<?php echo $profile->username;?>" data-ajax="/settings-profile/<?php echo $profile->username;?>"><?php echo __( 'Edit' );?></a></span>
                            </h4>
                            <?php
                            $chips = explode( "," , $profile->interest );
                            if( !empty( $chips ) ) {
                                foreach ($chips as $key => $value) {
                                    $interest = trim(  $value  );
                                    if( $interest !== "" ){
                                        echo '<a href="'.$site_url.'/interest/'.strtolower($interest).'" data-ajax="/interest/'.strtolower($interest).'"><div class="chip">'.$interest.'</div></a>';
                                    }
                                }
                            }
                            ?>
                        </div>
                    <?php } ?>

                    <div class="about_block"> <!-- Profile Info -->
                        <h4><?php echo __( 'Profile Info ' );?>
                            <span><a class="edit_link" href="<?php echo $site_url;?>/settings-profile/<?php echo $profile->username;?>" data-ajax="/settings-profile/<?php echo $profile->username;?>"><?php echo __( 'Edit' );?></a></span>
                        </h4>
                        <div class="dt_profile_info">
							<?php if( !empty( $profile->language ) || !empty( $profile->relationship ) || !empty( $profile->work_status ) || !empty( $profile->education ) ) {?>
                                <h5><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="#ff9800" d="M5,9.5L7.5,14H2.5L5,9.5M3,4H7V8H3V4M5,20A2,2 0 0,0 7,18A2,2 0 0,0 5,16A2,2 0 0,0 3,18A2,2 0 0,0 5,20M9,5V7H21V5H9M9,19H21V17H9V19M9,13H21V11H9V13Z"></path></svg> <?php echo __( 'Basic' );?></h5>
                            <?php } ?>
                            <?php if( !empty( $profile->gender ) ) {?>
                                <div class="row">
                                    <div class="col s6">
                                        <p class="info_title"><?php echo __( 'Gender' );?></p>
                                    </div>
                                    <div class="col s6">
                                        <p><?php echo __($profile->gender);?></p>
                                    </div>
                                </div>
                            <?php } ?>
                            <?php if( !empty( $profile->language ) ) {?>
                                <div class="row">
                                    <div class="col s6">
                                        <p class="info_title"><?php echo __( 'Preferred Language' );?></p>
                                    </div>
                                    <div class="col s6">
                                        <p><?php echo $profile->language;?></p>
                                    </div>
                                </div>
                            <?php } ?>
                            <?php if( !empty( $profile->relationship ) ) {?>
                                <div class="row">
                                    <div class="col s6">
                                        <p class="info_title"><?php echo __( 'Relationship status' );?></p>
                                    </div>
                                    <div class="col s6">
                                        <p><?php echo $profile->relationship_txt;?></p>
                                    </div>
                                </div>
                            <?php } ?>
                            <?php if( !empty( $profile->work_status ) ) {?>
                                <div class="row">
                                    <div class="col s6">
                                        <p class="info_title"><?php echo __( 'Work status' );?></p>
                                    </div>
                                    <div class="col s6">
                                        <p><?php echo $profile->work_status_txt;?></p>
                                    </div>
                                </div>
                            <?php } ?>
                            <?php if( !empty( $profile->education ) ) {?>
                                <div class="row">
                                    <div class="col s6">
                                        <p class="info_title"><?php echo __( 'Education Level' );?></p>
                                    </div>
                                    <div class="col s6">
                                        <p><?php echo $profile->education_txt;?></p>
                                    </div>
                                </div>
                            <?php } ?>

                            <?php
                            $general_fields = GetProfileFields('general');
                            $general_custom_data = UserFieldsData($profile->id);
                            if (count($general_fields) > 0) {
                                foreach ($general_fields as $key => $field) {
                                    if($field['profile_page'] == 1) {
                                        if( isset($general_custom_data[$field['fid']]) && $general_custom_data[$field['fid']] !== null ) {
                                            echo '<div class="row">';
                                            echo '    <div class="col s6"><p class="info_title">' . $field['name'] . '</p></div>';
                                            if ($field['select_type'] == 'yes') {
                                                $options = @explode(',', $field['type']);
                                                array_unshift($options,"");
                                                unset($options[0]);
                                                if (isset($options[$general_custom_data[$field['fid']]])) {
                                                    echo '    <div class="col s6"><p>' . $options[$general_custom_data[$field['fid']]] . '</p></div>';
                                                } else {
                                                    echo '    <div class="col s6"><p>' . $general_custom_data[$field['fid']] . '</p></div>';
                                                }
                                            } else {
                                                echo '    <div class="col s6"><p>' . $general_custom_data[$field['fid']] . '</p></div>';
                                            }
                                            echo '</div>';
                                        }
                                    }
                                }
                            }
                            ?>

                        </div>
                        <div class="dt_profile_info">
                            <?php if( !empty( $profile->ethnicity ) || !empty( $profile->body ) || !empty( $profile->height ) || !empty( $profile->hair_color ) ) {?>
                                <h5><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="#4caf50" d="M9,11.75A1.25,1.25 0 0,0 7.75,13A1.25,1.25 0 0,0 9,14.25A1.25,1.25 0 0,0 10.25,13A1.25,1.25 0 0,0 9,11.75M15,11.75A1.25,1.25 0 0,0 13.75,13A1.25,1.25 0 0,0 15,14.25A1.25,1.25 0 0,0 16.25,13A1.25,1.25 0 0,0 15,11.75M12,2A10,10 0 0,0 2,12A10,10 0 0,0 12,22A10,10 0 0,0 22,12A10,10 0 0,0 12,2M12,20C7.59,20 4,16.41 4,12C4,11.71 4,11.42 4.05,11.14C6.41,10.09 8.28,8.16 9.26,5.77C11.07,8.33 14.05,10 17.42,10C18.2,10 18.95,9.91 19.67,9.74C19.88,10.45 20,11.21 20,12C20,16.41 16.41,20 12,20Z"></path></svg> <?php echo __( 'Looks' );?></h5>
                            <?php } ?>

                            <?php if( !empty( $profile->ethnicity ) ) {?>
                                <div class="row">
                                    <div class="col s6">
                                        <p class="info_title"><?php echo __( 'Ethnicity' );?></p>
                                    </div>
                                    <div class="col s6">
                                        <p><?php echo $profile->ethnicity_txt;?></p>
                                    </div>
                                </div>
                            <?php } ?>
                            <?php if( !empty( $profile->body ) ) {?>
                                <div class="row">
                                    <div class="col s6">
                                        <p class="info_title"><?php echo __( 'Body Type' );?></p>
                                    </div>
                                    <div class="col s6">
                                        <p><?php echo $profile->body_txt;?></p>
                                    </div>
                                </div>
                            <?php } ?>
                            <?php if( !empty( $profile->height ) ) {?>
                                <div class="row">
                                    <div class="col s6">
                                        <p class="info_title"><?php echo __( 'Height' );?></p>
                                    </div>
                                    <div class="col s6">
                                        <p><?php echo $profile->height;?>cm</p>
                                    </div>
                                </div>
                            <?php } ?>
                            <?php if( !empty( $profile->hair_color ) ) {?>
                                <div class="row">
                                    <div class="col s6">
                                        <p class="info_title"><?php echo __( 'Hair color' );?></p>
                                    </div>
                                    <div class="col s6">
                                        <p><?php echo $profile->hair_color_txt;?></p>
                                    </div>
                                </div>
                            <?php } ?>

                        </div>
                        <div class="dt_profile_info">
                            <?php if( !empty( $profile->character ) || !empty( $profile->children ) || !empty( $profile->friends ) || !empty( $profile->pets ) ) {?>
                                <h5><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="#795548" d="M17.81,4.47C17.73,4.47 17.65,4.45 17.58,4.41C15.66,3.42 14,3 12,3C10.03,3 8.15,3.47 6.44,4.41C6.2,4.54 5.9,4.45 5.76,4.21C5.63,3.97 5.72,3.66 5.96,3.53C7.82,2.5 9.86,2 12,2C14.14,2 16,2.47 18.04,3.5C18.29,3.65 18.38,3.95 18.25,4.19C18.16,4.37 18,4.47 17.81,4.47M3.5,9.72C3.4,9.72 3.3,9.69 3.21,9.63C3,9.47 2.93,9.16 3.09,8.93C4.08,7.53 5.34,6.43 6.84,5.66C10,4.04 14,4.03 17.15,5.65C18.65,6.42 19.91,7.5 20.9,8.9C21.06,9.12 21,9.44 20.78,9.6C20.55,9.76 20.24,9.71 20.08,9.5C19.18,8.22 18.04,7.23 16.69,6.54C13.82,5.07 10.15,5.07 7.29,6.55C5.93,7.25 4.79,8.25 3.89,9.5C3.81,9.65 3.66,9.72 3.5,9.72M9.75,21.79C9.62,21.79 9.5,21.74 9.4,21.64C8.53,20.77 8.06,20.21 7.39,19C6.7,17.77 6.34,16.27 6.34,14.66C6.34,11.69 8.88,9.27 12,9.27C15.12,9.27 17.66,11.69 17.66,14.66A0.5,0.5 0 0,1 17.16,15.16A0.5,0.5 0 0,1 16.66,14.66C16.66,12.24 14.57,10.27 12,10.27C9.43,10.27 7.34,12.24 7.34,14.66C7.34,16.1 7.66,17.43 8.27,18.5C8.91,19.66 9.35,20.15 10.12,20.93C10.31,21.13 10.31,21.44 10.12,21.64C10,21.74 9.88,21.79 9.75,21.79M16.92,19.94C15.73,19.94 14.68,19.64 13.82,19.05C12.33,18.04 11.44,16.4 11.44,14.66A0.5,0.5 0 0,1 11.94,14.16A0.5,0.5 0 0,1 12.44,14.66C12.44,16.07 13.16,17.4 14.38,18.22C15.09,18.7 15.92,18.93 16.92,18.93C17.16,18.93 17.56,18.9 17.96,18.83C18.23,18.78 18.5,18.96 18.54,19.24C18.59,19.5 18.41,19.77 18.13,19.82C17.56,19.93 17.06,19.94 16.92,19.94M14.91,22C14.87,22 14.82,22 14.78,22C13.19,21.54 12.15,20.95 11.06,19.88C9.66,18.5 8.89,16.64 8.89,14.66C8.89,13.04 10.27,11.72 11.97,11.72C13.67,11.72 15.05,13.04 15.05,14.66C15.05,15.73 16,16.6 17.13,16.6C18.28,16.6 19.21,15.73 19.21,14.66C19.21,10.89 15.96,7.83 11.96,7.83C9.12,7.83 6.5,9.41 5.35,11.86C4.96,12.67 4.76,13.62 4.76,14.66C4.76,15.44 4.83,16.67 5.43,18.27C5.53,18.53 5.4,18.82 5.14,18.91C4.88,19 4.59,18.87 4.5,18.62C4,17.31 3.77,16 3.77,14.66C3.77,13.46 4,12.37 4.45,11.42C5.78,8.63 8.73,6.82 11.96,6.82C16.5,6.82 20.21,10.33 20.21,14.65C20.21,16.27 18.83,17.59 17.13,17.59C15.43,17.59 14.05,16.27 14.05,14.65C14.05,13.58 13.12,12.71 11.97,12.71C10.82,12.71 9.89,13.58 9.89,14.65C9.89,16.36 10.55,17.96 11.76,19.16C12.71,20.1 13.62,20.62 15.03,21C15.3,21.08 15.45,21.36 15.38,21.62C15.33,21.85 15.12,22 14.91,22Z"></path></svg> <?php echo __( 'Personality' );?></h5>
                            <?php } ?>

                            <?php if( !empty( $profile->character ) ) {?>
                                <div class="row">
                                    <div class="col s6">
                                        <p class="info_title"><?php echo __( 'Character' );?></p>
                                    </div>
                                    <div class="col s6">
                                        <p><?php echo $profile->character_txt;?></p>
                                    </div>
                                </div>
                            <?php } ?>
                            <?php if( !empty( $profile->children ) ) {?>
                                <div class="row">
                                    <div class="col s6">
                                        <p class="info_title"><?php echo __( 'Children' );?></p>
                                    </div>
                                    <div class="col s6">
                                        <p><?php echo $profile->children_txt;?></p>
                                    </div>
                                </div>
                            <?php } ?>
                            <?php if( !empty( $profile->friends ) ) {?>
                                <div class="row">
                                    <div class="col s6">
                                        <p class="info_title"><?php echo __( 'Friends' );?></p>
                                    </div>
                                    <div class="col s6">
                                        <p><?php echo $profile->friends_txt;?></p>
                                    </div>
                                </div>
                            <?php } ?>
                            <?php if( !empty( $profile->pets ) ) {?>
                                <div class="row">
                                    <div class="col s6">
                                        <p class="info_title"><?php echo __( 'Pets' );?></p>
                                    </div>
                                    <div class="col s6">
                                        <p><?php echo $profile->pets_txt;?></p>
                                    </div>
                                </div>
                            <?php } ?>

                        </div>
                        <div class="dt_profile_info">
                            <?php if( !empty( $profile->live_with ) || !empty( $profile->car ) || !empty( $profile->religion ) || !empty( $profile->smoke ) || !empty( $profile->drink ) || !empty( $profile->travel ) ) {?>
                                <h5><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="#2196f3" d="M15,18.54C17.13,18.21 19.5,18 22,18V22H5C5,21.35 8.2,19.86 13,18.9V12.4C12.16,12.65 11.45,13.21 11,13.95C10.39,12.93 9.27,12.25 8,12.25C6.73,12.25 5.61,12.93 5,13.95C5.03,10.37 8.5,7.43 13,7.04V7A1,1 0 0,1 14,6A1,1 0 0,1 15,7V7.04C19.5,7.43 22.96,10.37 23,13.95C22.39,12.93 21.27,12.25 20,12.25C18.73,12.25 17.61,12.93 17,13.95C16.55,13.21 15.84,12.65 15,12.39V18.54M7,2A5,5 0 0,1 2,7V2H7Z"></path></svg> <?php echo __( 'Lifestyle' );?></h5>
                            <?php } ?>

                            <?php if( !empty( $profile->live_with ) ) {?>
                                <div class="row">
                                    <div class="col s6">
                                        <p class="info_title"><?php echo __( 'I live with' );?></p>
                                    </div>
                                    <div class="col s6">
                                        <p><?php echo $profile->live_with_txt;?></p>
                                    </div>
                                </div>
                            <?php } ?>
                            <?php if( !empty( $profile->car ) ) {?>
                                <div class="row">
                                    <div class="col s6">
                                        <p class="info_title"><?php echo __( 'Car' );?></p>
                                    </div>
                                    <div class="col s6">
                                        <p><?php echo $profile->car_txt;?></p>
                                    </div>
                                </div>
                            <?php } ?>
                            <?php if( !empty( $profile->religion ) ) {?>
                                <div class="row">
                                    <div class="col s6">
                                        <p class="info_title"><?php echo __( 'Religion' );?></p>
                                    </div>
                                    <div class="col s6">
                                        <p><?php echo $profile->religion_txt;?></p>
                                    </div>
                                </div>
                            <?php } ?>
                            <?php if( !empty( $profile->smoke ) ) {?>
                                <div class="row">
                                    <div class="col s6">
                                        <p class="info_title"><?php echo __( 'Smoke' );?></p>
                                    </div>
                                    <div class="col s6">
                                        <p><?php echo $profile->smoke_txt;?></p>
                                    </div>
                                </div>
                            <?php } ?>
                            <?php if( !empty( $profile->drink ) ) {?>
                                <div class="row">
                                    <div class="col s6">
                                        <p class="info_title"><?php echo __( 'Drink' );?></p>
                                    </div>
                                    <div class="col s6">
                                        <p><?php echo $profile->drink_txt;?></p>
                                    </div>
                                </div>
                            <?php } ?>
                            <?php if( !empty( $profile->travel ) ) {?>
                                <div class="row">
                                    <div class="col s6">
                                        <p class="info_title"><?php echo __( 'Travel' );?></p>
                                    </div>
                                    <div class="col s6">
                                        <p><?php echo $profile->travel_txt;?></p>
                                    </div>
                                </div>
                            <?php } ?>

                        </div>
                        <div class="dt_profile_info">
                            <?php if( !empty( $profile->music ) || !empty( $profile->dish ) || !empty( $profile->song ) || !empty( $profile->hobby ) || !empty( $profile->city ) || !empty( $profile->sport ) || !empty( $profile->book ) || !empty( $profile->movie ) || !empty( $profile->colour ) || !empty( $profile->tv ) ) {?>
                                <h5><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="#e91e63" d="M23,10C23,8.89 22.1,8 21,8H14.68L15.64,3.43C15.66,3.33 15.67,3.22 15.67,3.11C15.67,2.7 15.5,2.32 15.23,2.05L14.17,1L7.59,7.58C7.22,7.95 7,8.45 7,9V19A2,2 0 0,0 9,21H18C18.83,21 19.54,20.5 19.84,19.78L22.86,12.73C22.95,12.5 23,12.26 23,12V10M1,21H5V9H1V21Z"></path></svg> <?php echo __( 'Favourites' );?></h5>
                            <?php } ?>

                            <?php if( !empty( $profile->music ) ) {?>
                                <div class="row">
                                    <div class="col s6">
                                        <p class="info_title"><?php echo __( 'Music Genre' );?></p>
                                    </div>
                                    <div class="col s6">
                                        <p><?php echo $profile->music;?></p>
                                    </div>
                                </div>
                            <?php } ?>
                            <?php if( !empty( $profile->dish ) ) {?>
                                <div class="row">
                                    <div class="col s6">
                                        <p class="info_title"><?php echo __( 'Dish' );?></p>
                                    </div>
                                    <div class="col s6">
                                        <p><?php echo $profile->dish;?></p>
                                    </div>
                                </div>
                            <?php } ?>
                            <?php if( !empty( $profile->song ) ) {?>
                                <div class="row">
                                    <div class="col s6">
                                        <p class="info_title"><?php echo __( 'Song' );?></p>
                                    </div>
                                    <div class="col s6">
                                        <p><?php echo $profile->song;?></p>
                                    </div>
                                </div>
                            <?php } ?>
                            <?php if( !empty( $profile->hobby ) ) {?>
                                <div class="row">
                                    <div class="col s6">
                                        <p class="info_title"><?php echo __( 'Hobby' );?></p>
                                    </div>
                                    <div class="col s6">
                                        <p><?php echo $profile->hobby;?></p>
                                    </div>
                                </div>
                            <?php } ?>
                            <?php if( !empty( $profile->city ) ) {?>
                                <div class="row">
                                    <div class="col s6">
                                        <p class="info_title"><?php echo __( 'City' );?></p>
                                    </div>
                                    <div class="col s6">
                                        <p><?php echo $profile->city;?></p>
                                    </div>
                                </div>
                            <?php } ?>
                            <?php if( !empty( $profile->sport ) ) {?>
                                <div class="row">
                                    <div class="col s6">
                                        <p class="info_title"><?php echo __( 'Sport' );?></p>
                                    </div>
                                    <div class="col s6">
                                        <p><?php echo $profile->sport;?></p>
                                    </div>
                                </div>
                            <?php } ?>
                            <?php if( !empty( $profile->book ) ) {?>
                                <div class="row">
                                    <div class="col s6">
                                        <p class="info_title"><?php echo __( 'Book' );?></p>
                                    </div>
                                    <div class="col s6">
                                        <p><?php echo $profile->book;?></p>
                                    </div>
                                </div>
                            <?php } ?>
                            <?php if( !empty( $profile->movie ) ) {?>
                                <div class="row">
                                    <div class="col s6">
                                        <p class="info_title"><?php echo __( 'Movie' );?></p>
                                    </div>
                                    <div class="col s6">
                                        <p><?php echo $profile->movie;?></p>
                                    </div>
                                </div>
                            <?php } ?>
                            <?php if( !empty( $profile->colour ) ) {?>
                                <div class="row">
                                    <div class="col s6">
                                        <p class="info_title"><?php echo __( 'Color' );?></p>
                                    </div>
                                    <div class="col s6">
                                        <p><?php echo $profile->colour;?></p>
                                    </div>
                                </div>
                            <?php } ?>
                            <?php if( !empty( $profile->tv ) ) {?>
                                <div class="row">
                                    <div class="col s6">
                                        <p class="info_title"><?php echo __( 'TV Show' );?></p>
                                    </div>
                                    <div class="col s6">
                                        <p><?php echo $profile->tv;?></p>
                                    </div>
                                </div>
                            <?php } ?>

                        </div>

                        <div class="dt_profile_info">
                            <?php
                            $is_show_title = false;
                            $_profile_custom_data = '';
                            $profile_fields = GetProfileFields('profile');
                            $profile_custom_data = UserFieldsData($profile->id);
                            if (count($profile_fields) > 0) {
                                foreach ($profile_fields as $key => $field) {
                                    if($field['profile_page'] == 1) {
                                        if( isset($profile_custom_data[$field['fid']]) && $profile_custom_data[$field['fid']] !== null ) {
                                            $is_show_title = true;
                                            $_profile_custom_data .= '<div class="row">';
                                            $_profile_custom_data .= '    <div class="col s6"><p class="info_title">' . $field['name'] . '</p></div>';
                                            if ($field['select_type'] == 'yes') {
                                                $profile_options = @explode(',', $field['type']);
                                                array_unshift($profile_options,"");
                                                unset($profile_options[0]);
                                                if (isset($profile_options[$profile_custom_data[$field['fid']]])) {
                                                    $_profile_custom_data .= '    <div class="col s6"><p>' . $profile_options[$profile_custom_data[$field['fid']]] . '</p></div>';
                                                } else {
                                                    $_profile_custom_data .= '    <div class="col s6"><p>' . $profile_custom_data[$field['fid']] . '</p></div>';
                                                }
                                            } else {
                                                $_profile_custom_data .= '    <div class="col s6"><p>' . $profile_custom_data[$field['fid']] . '</p></div>';
                                            }
                                            $_profile_custom_data .= '</div>';
                                        }
                                    }
                                }
                            }

                            if($is_show_title == true){
                                echo '<h5><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="#ff9800" d="M5,9.5L7.5,14H2.5L5,9.5M3,4H7V8H3V4M5,20A2,2 0 0,0 7,18A2,2 0 0,0 5,16A2,2 0 0,0 3,18A2,2 0 0,0 5,20M9,5V7H21V5H9M9,19H21V17H9V19M9,13H21V11H9V13Z"></path></svg> '. __( 'Other' ) .'</h5>';
                            }
                            echo $_profile_custom_data;
                            ?>
                        </div>


                    </div>
                </div>
            </div> <!-- End Right Main Area -->
        </div>
    </div>
</div>
<!-- End Profile  -->

<div id="upload_images" class="modal" style="width: 30%;">
    <div class="modal-content">
        <div class="dt_user_prof_complt">
            <h5 class="valign-wrapper"><?php echo __( 'Upload Completion' );?><span id="c_perc">0%</span></h5>
            <div class="progress" id="c_prog">
                <div class="determinate" id="c_det" style="width: 0%"></div>
            </div>
        </div>
    </div>
</div>


<a href="javascript:void(0);" class="btn-floating btn-large waves-effect waves-light bg_gradient only-mobile" style="bottom: 100px;position: fixed;right: 32px;z-index: 99999999999999999999999;" onclick="document.getElementById('avatar_profileimg').click(); return false">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="-7 -6 40 40"><path fill="currentColor" d="M5,3A2,2 0 0,0 3,5V19A2,2 0 0,0 5,21H14.09C14.03,20.67 14,20.34 14,20C14,19.32 14.12,18.64 14.35,18H5L8.5,13.5L11,16.5L14.5,12L16.73,14.97C17.7,14.34 18.84,14 20,14C20.34,14 20.67,14.03 21,14.09V5C21,3.89 20.1,3 19,3H5M19,16V19H16V21H19V24H21V21H24V19H21V16H19Z"></path></svg>
</a>
