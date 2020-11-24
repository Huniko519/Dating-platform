<?php
$admin_mode = false;
if( $profile->admin == '1' ){
    $target_user = route(2);
    $_user = LoadEndPointResource('users');
    if( $_user ){
        if( $target_user !== '' ){
            $profile = $_user->get_user_profile(Secure($target_user));
            if( !$profile ){
                echo '<script>window.location = window.site_url;</script>';
                exit();
            }else{
                $admin_mode = true;
            }
        }
    }
}
?>
<style>
.dt_settings_header {margin-top: -3px;display: inline-block;}
@media (max-width: 1024px){
.dt_slide_menu {
	display: none;
}
nav .header_user {
	display: block;
}
}
</style>
<!-- Settings  -->
<div class="dt_settings_header bg_gradient">
	<div class="dt_settings_circle-1"></div>
	<div class="dt_settings_circle-2"></div>
	<div class="dt_settings_circle-3"></div>
    <div class="container">
        <div class="sett_active_svg">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M12 2C6.486 2 2 6.486 2 12s4.486 10 10 10 10-4.486 10-10S17.514 2 12 2zm0 18c-4.411 0-8-3.589-8-8 0-1.846.634-3.542 1.688-4.897l11.209 11.209A7.946 7.946 0 0 1 12 20zm6.312-3.103L7.103 5.688A7.948 7.948 0 0 1 12 4c4.411 0 8 3.589 8 8a7.954 7.954 0 0 1-1.688 4.897z" /></svg>
        </div>
        <div class="sett_navbar valign-wrapper">
            <ul class="tabs">
                <li class="tab col s3"><a href="<?php echo $site_url;?>/settings/<?php echo $profile->username;?>" data-ajax="/settings/<?php echo $profile->username;?>" target="_self"><?php echo __( 'General' );?></a></li>
                <li class="tab col s3"><a href="<?php echo $site_url;?>/settings-profile/<?php echo $profile->username;?>" data-ajax="/settings-profile/<?php echo $profile->username;?>" target="_self"><?php echo __( 'Profile' );?></a></li>
                <li class="tab col s3"><a href="<?php echo $site_url;?>/settings-privacy/<?php echo $profile->username;?>" data-ajax="/settings-privacy/<?php echo $profile->username;?>" target="_self"><?php echo __( 'Privacy' );?></a></li>
                <li class="tab col s3"><a href="<?php echo $site_url;?>/settings-password/<?php echo $profile->username;?>" data-ajax="/settings-password/<?php echo $profile->username;?>" target="_self"><?php echo __( 'Password' );?></a></li>
                <li class="tab col s3"><a href="<?php echo $site_url;?>/settings-social/<?php echo $profile->username;?>" data-ajax="/settings-social/<?php echo $profile->username;?>" target="_self"><?php echo __( 'Social Links' );?></a></li>
                <li class="tab col s3"><a class="active" href="<?php echo $site_url;?>/settings-blocked/<?php echo $profile->username;?>" data-ajax="/settings-blocked/<?php echo $profile->username;?>" target="_self"><?php echo __( 'Blocked Users' );?></a></li>
<!--                <li class="tab col s3"><a href="javascript:void(0);" data-ajax="/settings-email" target="_self">--><?php //echo __( 'Emails' );?><!--</a></li>-->
                <?php if( $admin_mode == false && $config->deleteAccount == '1' ) {?><li class="tab col s3"><a href="<?php echo $site_url;?>/settings-delete/<?php echo $profile->username;?>" data-ajax="/settings-delete/<?php echo $profile->username;?>" target="_self"><?php echo __( 'Delete Account' );?></a></li><?php } ?>
            </ul>
        </div>
    </div>
</div>
<div class="container">
    <div class="dt_settings row">
        <div class="col s12 m3"></div>
        <form method="post" action="" class="col s12 m6">
            <div class="row blocked_users">
                <?php
					global $db;
					$blocked = $db->objectBuilder()
								  ->where('user_id', $profile->id)
								  ->get('blocks',null,array('block_userid'));

                    if( count( $blocked ) == 0 ){
                        echo '<h5 class="empty_state"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M9,4A4,4 0 0,1 13,8A4,4 0 0,1 9,12A4,4 0 0,1 5,8A4,4 0 0,1 9,4M9,6A2,2 0 0,0 7,8A2,2 0 0,0 9,10A2,2 0 0,0 11,8A2,2 0 0,0 9,6M9,13C11.67,13 17,14.34 17,17V20H1V17C1,14.34 6.33,13 9,13M9,14.9C6.03,14.9 2.9,16.36 2.9,17V18.1H15.1V17C15.1,16.36 11.97,14.9 9,14.9M15,4A4,4 0 0,1 19,8A4,4 0 0,1 15,12C14.53,12 14.08,11.92 13.67,11.77C14.5,10.74 15,9.43 15,8C15,6.57 14.5,5.26 13.67,4.23C14.08,4.08 14.53,4 15,4M23,17V20H19V16.5C19,15.25 18.24,14.1 16.97,13.18C19.68,13.62 23,14.9 23,17Z" /></svg>' . __( 'There is no blocked user yet.' ) . '</h5>';
                    }else{
                        foreach ($blocked as $key => $user) {

                            $blocked_user_data = $db->objectBuilder()
													  ->where('id', $user->block_userid)
													  ->getOne('users',array('id','username','first_name','last_name','avater'));

							$_full_name = ucfirst(trim($blocked_user_data->first_name . ' ' . $blocked_user_data->last_name));
							$full_name = ($_full_name == '') ? ucfirst(trim($blocked_user_data->username)) : $_full_name;
                    ?>

                        <div class="col s6 m4 xs12" id="blocked_user_<?php echo $blocked_user_data->id;?>">
                            <div class="unblock_card">
                                <span href="javascript:void(0);">
                                    <div class="avatar">
                                        <img src="<?php echo GetMedia($blocked_user_data->avater);?>" alt="<?php echo $full_name;?>" class="circle">
                                    </div>
                                </span>
                                <div class="info">
                                    <span href="javascript:void(0);">
                                        <span class="black-text truncate bold"><?php echo $full_name;?></span>
                                    </span>
                                    <a class="btn waves-effect btn_primary unblock" data-ajax-post="/useractions/unblock" data-ajax-params="userid=<?php echo $blocked_user_data->id;?><?php echo ( $admin_mode ? '&targetuid=' . strrev( str_replace( '==', '', base64_encode($profile->id) ) ) : '' );?>" data-ajax-callback="callback_unblock_hide" class="block_text"><?php echo __( 'Unblock' );?></a>
                                </div>
                            </div>
                        </div>

                    <?php
                        }
                    }
                ?>
            </div>
            <?php if( $admin_mode == true ){?>
                <input type="hidden" name="targetuid" value="<?php echo strrev( str_replace( '==', '', base64_encode($profile->id) ) );?>">
            <?php }?>
        </form>
        <div class="col s12 m3"></div>
    </div>
</div>
<!-- End Settings  -->
