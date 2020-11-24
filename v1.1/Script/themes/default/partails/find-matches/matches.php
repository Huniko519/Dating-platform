<?php global $site_url; ?>
<div class="mtc_usrd_content" data-id="<?php echo $matche->id;?>" <?php if($matche_first === false){?> style="display: none;"<?php }?>>
    <div class="row">
        <div class="col s12 m7">
            <div class="mtc_usrd_slider">
                <div class="mtc_usrd_top">
                    <div class="mtc_usrd_summary">
                        <div class="usr_name"><a href="<?php echo $site_url;?>/@<?php echo $matche->username;?>" data-ajax="/@<?php echo $matche->username;?>" tooltip="<?php echo $matche->username;?>"><?php echo ($matche->first_name !== '' ) ? $matche->first_name . ' ' . $matche->last_name : $matche->username;?></a>,</div>
                        <div class="usr_age"><?php echo getAge($matche->birthday);?></div>
                        <div class="usr_location"><?php echo $matche->country;?></div>
                    </div>
                </div>
                <div class="carousel carousel-slider center match_usr_img_slidr">
                    <?php if(count($matche->mediafiles) > 1){?>
                        <span class="changer back" onclick="Previous_Picture();"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M15.41,16.58L10.83,12L15.41,7.41L14,6L8,12L14,18L15.41,16.58Z" /></svg></span>
                        <span class="changer next" onclick="Next_Picture();"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M8.59,16.58L13.17,12L8.59,7.41L10,6L16,12L10,18L8.59,16.58Z" /></svg></span>
                    <?php }else{
                        echo '<div class="carousel-item"><img alt="'.$matche->username.'" src="'. GetMedia('',false) . $matche->avater.'"></div>';
                    }?>

                    <?php foreach ($matche->mediafiles as $key => $mfile){?>
                        <div class="carousel-item">
                            <img alt="<?php echo $matche->username;?>" src="<?php echo $mfile['avater'];?>">
                        </div>
                    <?php } ?>

                </div>
                <div class="mtc_usrd_actions">
                    <button href="javascript:void(0);" data-userid="<?php echo $matche->id;?>" id="matches_like_btn" data-ajax-post="/useractions/like" data-ajax-params="userid=<?php echo $matche->id;?>&username=<?php echo $matche->username;?>&source=find-matches" data-ajax-callback="callback_like" class="btn waves-effect like"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M12,21.35L10.55,20.03C5.4,15.36 2,12.27 2,8.5C2,5.41 4.42,3 7.5,3C9.24,3 10.91,3.81 12,5.08C13.09,3.81 14.76,3 16.5,3C19.58,3 22,5.41 22,8.5C22,12.27 18.6,15.36 13.45,20.03L12,21.35Z"></path></svg></button>
                    <button href="javascript:void(0);" data-userid="<?php echo $matche->id;?>" id="matches_dislike_btn" data-ajax-post="/useractions/dislike" data-source="find-matches" data-ajax-params="userid=<?php echo $matche->id;?>&username=<?php echo $matche->username;?>&source=find-matches" data-ajax-callback="callback_dislike" class="btn waves-effect dislike"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M19,6.41L17.59,5L12,10.59L6.41,5L5,6.41L10.59,12L5,17.59L6.41,19L12,13.41L17.59,19L19,17.59L13.41,12L19,6.41Z"></path></svg></button>
                </div>
            </div>
        </div>
        <div class="col s12 m5">
            <div class="mtc_usrd_sidebar">
                <div class="sidebar_usr_info">
                    <h5><?php echo __('About');?> <?php echo ($matche->first_name !== '' ) ? $matche->first_name . ' ' . $matche->last_name : $matche->username;?></h5>
                    <?php if($matche->language){?>
                        <div>
                            <p class="info_title"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M12.87,15.07L10.33,12.56L10.36,12.53C12.1,10.59 13.34,8.36 14.07,6H17V4H10V2H8V4H1V6H12.17C11.5,7.92 10.44,9.75 9,11.35C8.07,10.32 7.3,9.19 6.69,8H4.69C5.42,9.63 6.42,11.17 7.67,12.56L2.58,17.58L4,19L9,14L12.11,17.11L12.87,15.07M18.5,10H16.5L12,22H14L15.12,19H19.87L21,22H23L18.5,10M15.88,17L17.5,12.67L19.12,17H15.88Z"></path></svg> <?php echo __('Preferred Language');?></p>
                            <span><?php echo $matche->language;?></span>
                        </div>
                    <?php }?>
                    <?php if($matche->relationship){?>
                        <div>
                            <p class="info_title"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M7.5,2A2,2 0 0,1 9.5,4A2,2 0 0,1 7.5,6A2,2 0 0,1 5.5,4A2,2 0 0,1 7.5,2M6,7H9A2,2 0 0,1 11,9V14.5H9.5V22H5.5V14.5H4V9A2,2 0 0,1 6,7M16.5,2A2,2 0 0,1 18.5,4A2,2 0 0,1 16.5,6A2,2 0 0,1 14.5,4A2,2 0 0,1 16.5,2M15,22V16H12L14.59,8.41C14.84,7.59 15.6,7 16.5,7C17.4,7 18.16,7.59 18.41,8.41L21,16H18V22H15Z"></path></svg> <?php echo __('Relationship status');?></p>
                            <span><?php echo $matche->relationship;?></span>
                        </div>
                    <?php }?>
                    <?php if($matche->height){?>
                        <div>
                            <p class="info_title"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M13,9V15H16L12,19L8,15H11V9H8L12,5L16,9H13M4,2H20V4H4V2M4,20H20V22H4V20Z"></path></svg> <?php echo __('Height');?></p>
                            <span><?php echo $matche->height;?></span>
                        </div>
                    <?php }?>
                    <?php if($matche->body){?>
                        <div>
                            <p class="info_title"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M12,1C10.89,1 10,1.9 10,3C10,4.11 10.89,5 12,5C13.11,5 14,4.11 14,3A2,2 0 0,0 12,1M10,6C9.73,6 9.5,6.11 9.31,6.28H9.3L4,11.59L5.42,13L9,9.41V22H11V15H13V22H15V9.41L18.58,13L20,11.59L14.7,6.28C14.5,6.11 14.27,6 14,6"></path></svg> <?php echo __('Body Type');?></p>
                            <span><?php echo $matche->body;?></span>
                        </div>
                    <?php }?>
                    <?php if($matche->smoke){?>
                        <div>
                            <p class="info_title"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M2,16H17V19H2V16M20.5,16H22V19H20.5V16M18,16H19.5V19H18V16M18.85,7.73C19.47,7.12 19.85,6.28 19.85,5.35C19.85,3.5 18.35,2 16.5,2V3.5C17.5,3.5 18.35,4.33 18.35,5.35C18.35,6.37 17.5,7.2 16.5,7.2V8.7C18.74,8.7 20.5,10.53 20.5,12.77V15H22V12.76C22,10.54 20.72,8.62 18.85,7.73M16.03,10.2H14.5C13.5,10.2 12.65,9.22 12.65,8.2C12.65,7.18 13.5,6.45 14.5,6.45V4.95C12.65,4.95 11.15,6.45 11.15,8.3A3.35,3.35 0 0,0 14.5,11.65H16.03C17.08,11.65 18,12.39 18,13.7V15H19.5V13.36C19.5,11.55 17.9,10.2 16.03,10.2Z"></path></svg> <?php echo __('Smoke');?></p>
                            <span><?php echo $matche->smoke;?></span>
                        </div>
                    <?php }?>
                    <?php if($matche->ethnicity){?>
                        <div>
                            <p class="info_title"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M5,9.5L7.5,14H2.5L5,9.5M3,4H7V8H3V4M5,20A2,2 0 0,0 7,18A2,2 0 0,0 5,16A2,2 0 0,0 3,18A2,2 0 0,0 5,20M9,5V7H21V5H9M9,19H21V17H9V19M9,13H21V11H9V13Z"></path></svg> <?php echo __('Ethnicity');?></p>
                            <span><?php echo $matche->ethnicity;?></span>
                        </div>
                    <?php }?>
                </div>
                <div class="vew_profile">
                    <a href="<?php echo $site_url;?>/@<?php echo $matche->username;?>" data-ajax="/@<?php echo $matche->username;?>" class="btn waves-effect"><?php echo __( 'View Profile' );?></a>
                </div>
            </div>
        </div>
    </div>
</div>