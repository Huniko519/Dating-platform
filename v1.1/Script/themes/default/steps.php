<?php
    $error = "";
    if( isset( $_SESSION['JWT'] ) ){
        $profile = auth();
    }else{
        exit();
    }
    $current_step = "";
    if( $profile->start_up == 0 ){
        $current_step = "slider-one-active";
    }else if( $profile->start_up == 1 ){
        $current_step = "center slider-two-active";
    }else if( $profile->start_up == 2 ){
        $current_step = "full slider-three-active";
    }

    global $db;
    if($config->emailValidation == '0'){
        if( $profile->start_up == 2 ){
            $db->where('id',$profile->id)->update('users',array('start_up'=>'3'));
            ?>
            <a href="javascript:void(0);" id="btnProSuccessRedirect" data-ajax="/find-matches" style="visibility: hidden;display: none;"></a>
            <script>
                setTimeout(() => {
                    $("#btnProSuccessRedirect").click();
                }, 1500);
            </script>
            <?php
        }
    }
?>
    <!-- Step One  -->
    <div class="container slider_container <?php echo $current_step;?>">
        <div class="row">
            <div>
                <div class="dt_signup_steps">
                    <div class="steps_header">
                        <div class="steps">
                            <div class="step step-one">
                                <div class="liner"></div>
                                <span><?php echo __( 'Avatar' );?></span>
                            </div>
                            <div class="step step-two">
                                <div class="liner"></div>
                                <span><?php echo __( 'Info' );?></span>
                            </div>

                            <div class="step step-three">
                                <div class="liner"></div>
                                <span>
                                    <?php if( $config->emailValidation == "0" ) {?>
                                        <?php echo __( 'Finish' );?>
                                    <?php }else{ ?>
                                        <?php echo __( 'Verification' );?>
                                    <?php } ?>
                                </span>
                            </div>

                        </div>
                        <div class="line">
                            <div class="dot-move"></div>
                            <div class="dot zero"></div>
                            <div class="dot center"></div>
                            <div class="dot full"></div>
                        </div>
                    </div>
                    <div class="slider-ctr">
                        <div class="slider">
                            <!-- Step 1  -->
                            <form class="slider-form slider-one">
                                <div class="choose_photo">
                                    <h6 class="bold"><?php echo ( $profile->full_name !== "" ? $profile->full_name : $profile->username ) ;?>, <?php echo __( 'people want to see what you look like!' );?></h6>
                                    <p><?php echo __( 'Upload Images to set your Profile Picture Image.' );?></p>
                                    <span class="dt_selct_avatar" onclick="document.getElementById('avatar_img').click(); return false">
                                        <span class="svg-empty"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M5,3A2,2 0 0,0 3,5V19A2,2 0 0,0 5,21H14.09C14.03,20.67 14,20.34 14,20C14,19.32 14.12,18.64 14.35,18H5L8.5,13.5L11,16.5L14.5,12L16.73,14.97C17.7,14.34 18.84,14 20,14C20.34,14 20.67,14.03 21,14.09V5C21,3.89 20.1,3 19,3H5M19,16V19H16V21H19V24H21V21H24V19H21V16H19Z"></svg></span>
                                    </span>
                                    <input type="file" id="avatar_img" class="hide" accept="image/x-png, image/gif, image/jpeg" name="avatar">
                                    <div class="progress hide" style="width: 180px;margin: auto;margin-top: 25px;padding-top: 4px;">
                                        <div class="determinate" style="width: 0%"></div >
                                    </div>
                                </div>
                                <div class="step_footer">
                                    <button class="waves-effect waves-light btn btn_primary bold first next" id="btn-upload-images" disabled data-selected=""><?php echo __( 'Next' );?> <svg viewBox="0 0 19 14" xmlns="http://www.w3.org/2000/svg" width="18" height="18"><path fill="currentColor" d="M18.6 6.9v-.5l-6-6c-.3-.3-.9-.3-1.2 0-.3.3-.3.9 0 1.2l5 5H1c-.5 0-.9.4-.9.9s.4.8.9.8h14.4l-4 4.1c-.3.3-.3.9 0 1.2.2.2.4.2.6.2.2 0 .4-.1.6-.2l5.2-5.2h.2c.5 0 .8-.4.8-.8 0-.3 0-.5-.2-.7z"></path></svg></button>
                                </div>
                            </form>
                            <!-- Step 2  -->
                            <form class="slider-form slider-two">
                                <div class="row">
                                    <div class="input-field col s6">
                                        <select id="height" name="height">
                                            <?php echo DatasetGetSelect( null, "height", __("Height") );?>
                                        </select>
                                        <label for="height"><?php echo __( 'Height' );?></label>
                                    </div>
                                    <div class="input-field col s6">
                                        <select id="hair" name="hair">
                                            <?php echo DatasetGetSelect( null, "hair_color", __("Choose your Hair Color") );?>
                                        </select>
                                        <label for="hair"><?php echo __( 'Hair Color' );?></label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="input-field col s6">
                                        <input id="mobile" type="text" data-errmsg="<?php echo __( 'Your phone number is required.');?>" class="validate" title="Field must be a number." placeholder="<?php echo __('Phone number, e.g +90..');?>" <?php if($config->sms_or_email == 'sms'){?> data-validation-type="sms" required<?php }else{?> data-validation-type="mail" <?php } ?> >
                                        <label for="mobile"><?php echo __( 'Mobile Number' );?></label>
                                    </div>
                                    <div class="input-field col s6">
                                        <select id="country" data-errmsg="<?php echo __( 'Select your country.');?>" required>
                                            <option value="" disabled selected><?php echo __( 'Choose your country' );?></option>
                                            <?php
                                            foreach( Dataset::load('countries') as $key => $val ){
                                                echo '<option value="'. $key .'" data-code="'. $val['isd'] .'">'. $val['name'] .'</option>';
                                            }
                                            ?>
                                        </select>
                                        <label for="country"><?php echo __( 'Country' );?></label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="input-field col s6">
                                        <select id="gender" name="gender" data-errmsg="<?php echo __( 'Choose your Gender');?>" required>
                                            <?php echo DatasetGetSelect( null, "gender", __("Choose your Gender") );?>
                                        </select>
                                        <label for="gender"><?php echo __( 'Gender' );?></label>
                                    </div>
                                    <div class="input-field col s6">
                                        <input id="birthdate" data-errmsg="<?php echo __( 'Select your Birth date.');?>" type="text" class="datepicker user_bday" required>
                                        <label for="birthdate"><?php echo __( 'Birthdate' );?></label>
                                    </div>
                                </div>
                                <div class="step_footer">
                                    <button class="waves-effect waves-light btn btn_primary bold second next" data-emailvalidation="<?php echo $config->emailValidation;?>"><?php echo __( 'Next' );?> <svg viewBox="0 0 19 14" xmlns="http://www.w3.org/2000/svg" width="18" height="18"><path fill="currentColor" d="M18.6 6.9v-.5l-6-6c-.3-.3-.9-.3-1.2 0-.3.3-.3.9 0 1.2l5 5H1c-.5 0-.9.4-.9.9s.4.8.9.8h14.4l-4 4.1c-.3.3-.3.9 0 1.2.2.2.4.2.6.2.2 0 .4-.1.6-.2l5.2-5.2h.2c.5 0 .8-.4.8-.8 0-.3 0-.5-.2-.7z"></path></svg></button>
                                </div>
                            </form>
                            <!-- Step 3  -->
                            <form class="slider-form slider-three" <?php if( $config->emailValidation == "0" ) {?>style="padding:0px;"<?php } ?>>
                                <?php if( $config->emailValidation == "1" ) {?>

                                    <?php if ( $config->sms_or_email == "sms" ) {?>
                                    <!-- Mobile -->
                                    <div class="otp_head">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M16,18H7V4H16M11.5,22A1.5,1.5 0 0,1 10,20.5A1.5,1.5 0 0,1 11.5,19A1.5,1.5 0 0,1 13,20.5A1.5,1.5 0 0,1 11.5,22M15.5,1H7.5A2.5,2.5 0 0,0 5,3.5V20.5A2.5,2.5 0 0,0 7.5,23H15.5A2.5,2.5 0 0,0 18,20.5V3.5A2.5,2.5 0 0,0 15.5,1Z" /></svg>
                                        <p><?php echo __( 'Phone Verification Needed' );?></p>
                                        <div class="row">
                                            <div class="col s12 m2"></div>
                                            <div class="col s12 m8">
                                                <div class="input-field inline">
                                                    <input id="mobile_validate" type="text" style="width: 200px;" value="<?php echo $profile->phone_number;?>">
                                                </div>
                                                <button class="btn waves-effect waves-light" style="margin-left: -5px;" id="send_otp"><?php echo __( 'Send OTP' );?></button>
                                            </div>
                                            <div class="col s12 m2"></div>
                                        </div>
                                    </div>
                                    <div class="enter_otp">
                                        <p><?php echo __( 'Please enter the verification code sent to your Phone' );?></p>
                                        <div id="otp_outer">
                                            <div id="otp_inner">
                                                <input id="otp_check_phone" type="text" maxlength="4" value="" pattern="\d*" title="Field must be a number." onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')" required />
                                                <a href="javascript:void(0);" data-ajax="/steps"><?php echo __( 'Resend' );?></a>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- End Mobile -->
                                    <?php } ?>
                                    <?php if ( $config->sms_or_email == "mail" ) {?>
                                    <!-- Email -->
                                    <div class="otp_head">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M20,8L12,13L4,8V6L12,11L20,6M20,4H4C2.89,4 2,4.89 2,6V18A2,2 0 0,0 4,20H20A2,2 0 0,0 22,18V6C22,4.89 21.1,4 20,4Z" /></svg>
                                        <p><?php echo __( 'Email Verification Needed' );?></p>
                                        <div class="row">
                                            <div class="col s12 m2"></div>
                                            <div class="col s12 m8">
                                                <div class="input-field inline">
                                                    <input id="email" type="email" value="<?php echo strtolower($profile->email);?>" data-email="<?php echo strtolower($profile->email);?>">
                                                </div>
                                                <button class="btn waves-effect waves-light" id="send_otp_email"><?php echo __( 'Send OTP' );?></button>
                                            </div>
                                            <div class="col s12 m2"></div>
                                        </div>
                                    </div>
                                    <div class="enter_otp_email">
                                        <p><?php echo __( 'Please enter the verification code sent to your Email' );?></p>
                                        <div id="otp_outer">
                                            <div id="otp_inner">
                                                <input id="otp_check_email" type="text" maxlength="4" value="" pattern="\d*" title="Field must be a number." onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')" required/>
                                                <a href="<?php echo $site_url;?>/steps" data-ajax="/steps"><?php echo __( 'Resend' );?></a>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- End Email -->
                                    <?php } ?>
                                <?php }else{ ?>

                                            <div class="dt_p_head center pro_success">
                                                <svg class="checkmark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52"><circle class="checkmark__circle" cx="26" cy="26" r="25" fill="none"></circle><path class="checkmark__check" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8"></path></svg>
                                                <h2 class="light"><?php echo __( 'Congratulations!' );?></h2>
                                                <p class="bold"><?php echo __('You have successfully registered.');?></p>
                                            </div>

                                <?php } ?>
                                <div class="step_footer">
                                    <button class="waves-effect waves-light btn btn_primary bold reset" disabled><?php echo __( 'Finish' );?> <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" width="18" height="18"><path fill="currentColor" d="M21,7L9,19L3.5,13.5L4.91,12.09L9,16.17L19.59,5.59L21,7Z"></path></svg></button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Step One  -->

    <!-- Images Modal -->
    <div id="modal_imgs" class="modal modal-fixed-footer">
        <div class="modal-content">
            <h6 class="bold"><span class="count_imgs"></span> <?php echo __( 'Images Uploaded' );?></h6>
            <p class="select_profile_image" style="display:none;"><?php echo __( 'Now, select any one image that you want to set as your Profile Picture.' );?></p>
            <div id="image_holder"></div>

            <div class="progress">
                <div class="determinate" style="width: 0%"></div >
            </div>

            <div id="status"></div>

        </div>
        <div class="modal-footer">
            <div id="modal_imgs_info"></div><button class="modal-close waves-effect waves-green btn-flat bold" disabled  data-selected=""><?php echo __( 'Apply' );?></button>
        </div>
    </div>
    <!-- End Images Modal -->
