<?php
class Matches{
    public $users = array();
    public $html = '';
    public function __construct(){
        global $db;
        $query = $db->objectBuilder()
                    ->where( 'verified', '1' )
                    ->where( 'id', auth()->id, '<>' )
                    ->where( 'privacy_show_profile_match_profiles', '1' )
                    ->where( 'id NOT IN ( SELECT like_userid FROM likes WHERE user_id = '.auth()->id.' )' )
                    ->where( 'id NOT IN ( SELECT user_id FROM blocks WHERE block_userid = '.auth()->id.' )' )
                    ->orderBy("id DESC")
                    ->get('users' ,30,array('id','username','avater','country','first_name','last_name','birthday','language','relationship','height','body','smoke','ethnicity','pets'));

        if( $query ){
            $query = ToObject($query);
            foreach ($query as $key => $user ){
                $this->users[$key] = $user;
                foreach ($user as $k => $u ){
                    $this->users[$key]->{$k} = isset( Dataset::load($k)[$u] ) ? Dataset::load($k)[$u] : $u;
                }
            }
        }
    }
    public function get(){
        $this->html = '';
        $this->GenerateAvater();
        $this->html .= '<div class="mtc_usr_details z-depth-1">';
        $show_first = false;
        if( !empty( $this->users ) ) {
            foreach ($this->users as $key => $user) {
                $this->show_user_details(  $user , $show_first );
                $show_first = true;
            }
        }
        $this->html .= '</div>';
        return $this->html;
    }
    public function GenerateAvater(){
        $this->html .= '<div class="mtc_usr_avtr">';
        if( !empty( $this->users ) ){
            $i = 0;
            foreach ($this->users as $key => $user ){
                $this->html .= '<div class="usr_thumb ';
                if( $i == 0 ) {
                    $this->html .= 'isActive';
                }
                $this->html .= '" data-id="'.$user->id.'" id="user'.$user->id.'">';
                $this->html .= '<img alt="'.$user->username.'" src="'.GetMedia($user->avater).'"></div>';
                $i++;
            }
        }
        $this->html .= '</div>';
    }
    public function show_user_details( $user, $hide = true ){
        global $db,$site_url;
        $_name = trim($user->first_name . ' ' . $user->last_name);
        $full_name = ($_name == '') ? trim($user->username) : $_name;
        $age = ( $user->birthday !== "0000-00-00" ) ? floor((time() - strtotime($user->birthday)) / 31556926) : "";
        $mediafiles          = $db->objectBuilder()->where('user_id', $user->id)->orderBy('id', 'desc')->get('mediafiles',4,array('file'));
        if ($mediafiles) {
            foreach ($mediafiles as $key => $mediafile) {
                $mediafile->full = GetMedia($mediafile->file, false);
                $mediafile->avater = GetMedia(str_replace('_full.', '_avater.', $mediafile->file), false);
            }

        }
        $this->html .= '<div class="mtc_usrd_content" data-id="'.$user->id.'" id="userdetails'.$user->id.'"';
        if( $hide ) {
            $this->html .= 'style="display:none;"';
        }
        $this->html .= '>';

        $this->html .= '<div class="mtc_usrd_top"><div class="mtc_usrd_summary left"><div class="usr_name"><a href="'.$site_url.'/@'.$user->username.'" data-ajax="/@'.$user->username.'">'.$full_name.'</a> ,</div><div class="usr_age">'.(( $age > 0 ) ? $age : '-').'</div>';
        if( $user->country !== '' ){
            $this->html .= '<div class="usr_location">'. ( isset( Dataset::load('countries')[$user->country] ) ? Dataset::load('countries')[$user->country]['name'] : '' ) .'</div>';
        }
        $this->html .= '</div>';
        $this->html .= '<div class="mtc_usrd_actions right">';
        $this->html .= '<button class="btn waves-effect like" data-id="'.$user->id.'"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M12,21.35L10.55,20.03C5.4,15.36 2,12.27 2,8.5C2,5.41 4.42,3 7.5,3C9.24,3 10.91,3.81 12,5.08C13.09,3.81 14.76,3 16.5,3C19.58,3 22,5.41 22,8.5C22,12.27 18.6,15.36 13.45,20.03L12,21.35Z"></path></svg> '. __( 'Like' ).'</button>';
        $this->html .= '<button class="btn waves-effect dislike" data-id="'.$user->id.'"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M19,15H23V3H19M15,3H6C5.17,3 4.46,3.5 4.16,4.22L1.14,11.27C1.05,11.5 1,11.74 1,12V14A2,2 0 0,0 3,16H9.31L8.36,20.57C8.34,20.67 8.33,20.77 8.33,20.88C8.33,21.3 8.5,21.67 8.77,21.94L9.83,23L16.41,16.41C16.78,16.05 17,15.55 17,15V5C17,3.89 16.1,3 15,3Z"></path></svg></button>';
        $this->html .= '</div></div>';

        $this->html .= '<div class="row">';
        $this->html .= '    <div class="col s12 m7">';
        $this->html .= '        <div class="mtc_usrd_slider">';


        $mediafiles          = $db->objectBuilder()->where('user_id', $user->id)->orderBy('id', 'desc')->get('mediafiles',4,array('file'));
        if ($mediafiles) {
            foreach ($mediafiles as $key => $mediafile) {
                $mediafile->full = GetMedia($mediafile->file, false);
                $mediafile->avater = GetMedia(str_replace('_full.', '_avater.', $mediafile->file), false);
            }

        }
        if( isset( $mediafiles[0] ) ){
            $this->html .= '<div class="slide_prnt"><a href="'.$mediafiles[0]->full.'" data-fancybox="gallery"><div class="img_slide"><img alt="'.$full_name.'" src="'.$mediafiles[0]->avater.'" class="responsive-img"></div></a></div>';
        }else{
            $this->html .= '<div class="slide_prnt"><div class="img_slide" data-fancybox="gallery"></div></div>';
        }
        if( isset( $mediafiles[1] ) ){
            $this->html .= '<div class="slide_prnt"><a href="'.$mediafiles[1]->full.'" data-fancybox="gallery"><div class="img_slide"><img alt="'.$full_name.'" src="'.$mediafiles[1]->avater.'" class="responsive-img"></div></a></div>';
        }else{
            $this->html .= '<div class="slide_prnt"><div class="img_slide" data-fancybox="gallery"></div></div>';
        }
        if( isset( $mediafiles[2] ) ){
            $this->html .= '<div class="slide_prnt"><a href="'.$mediafiles[2]->full.'" data-fancybox="gallery"><div class="img_slide"><img alt="'.$full_name.'" src="'.$mediafiles[2]->avater.'" class="responsive-img"></div></a></div>';
        }else{
            $this->html .= '<div class="slide_prnt"><div class="img_slide" data-fancybox="gallery"></div></div>';
        }
        if( isset( $mediafiles[3] ) ){
            $this->html .= '<div class="slide_prnt"><a href="'.$mediafiles[3]->full.'" data-fancybox="gallery"><div class="img_slide"><img alt="'.$full_name.'" src="'.$mediafiles[3]->avater.'" class="responsive-img"></div></a></div>';
        }else{
            $this->html .= '<div class="slide_prnt"><div class="img_slide" data-fancybox="gallery"></div></div>';
        }


        $this->html .= '        </div>';
        $this->html .= '    </div>';

        $this->html .= '    <div class="col s12 m5">';
        $this->html .= '        <div class="mtc_usrd_sidebar">';
        $this->html .= '            <div class="sidebar_usr_info">';

                                        $this->html .= '<h5>'. __( 'About' ).' ' . $full_name .'</h5>';
                                        if( !empty( $user->language ) ) {
                                            $this->html .= '<div><p class="info_title"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M12.87,15.07L10.33,12.56L10.36,12.53C12.1,10.59 13.34,8.36 14.07,6H17V4H10V2H8V4H1V6H12.17C11.5,7.92 10.44,9.75 9,11.35C8.07,10.32 7.3,9.19 6.69,8H4.69C5.42,9.63 6.42,11.17 7.67,12.56L2.58,17.58L4,19L9,14L12.11,17.11L12.87,15.07M18.5,10H16.5L12,22H14L15.12,19H19.87L21,22H23L18.5,10M15.88,17L17.5,12.67L19.12,17H15.88Z"></path></svg> '. __( 'Preferred Language' ).'</p><span>'. $user->language.'</span></div>';
                                        }
                                        if( !empty( $user->relationship ) ) {
                                            $this->html .= '<div><p class="info_title"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M7.5,2A2,2 0 0,1 9.5,4A2,2 0 0,1 7.5,6A2,2 0 0,1 5.5,4A2,2 0 0,1 7.5,2M6,7H9A2,2 0 0,1 11,9V14.5H9.5V22H5.5V14.5H4V9A2,2 0 0,1 6,7M16.5,2A2,2 0 0,1 18.5,4A2,2 0 0,1 16.5,6A2,2 0 0,1 14.5,4A2,2 0 0,1 16.5,2M15,22V16H12L14.59,8.41C14.84,7.59 15.6,7 16.5,7C17.4,7 18.16,7.59 18.41,8.41L21,16H18V22H15Z"></path></svg> '. __( 'Relationship status' ).'</p><span>'. $user->relationship.'</span></div>';
                                        }
                                        if( !empty( $user->height ) ) {
                                            $this->html .= '<div><p class="info_title"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M13,9V15H16L12,19L8,15H11V9H8L12,5L16,9H13M4,2H20V4H4V2M4,20H20V22H4V20Z"></path></svg> '. __( 'Height' ).'</p><span>'. $user->height.'cm</span></div>';
                                        }
                                        if( !empty( $user->body ) ) {
                                            $this->html .= '<div><p class="info_title"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M12,1C10.89,1 10,1.9 10,3C10,4.11 10.89,5 12,5C13.11,5 14,4.11 14,3A2,2 0 0,0 12,1M10,6C9.73,6 9.5,6.11 9.31,6.28H9.3L4,11.59L5.42,13L9,9.41V22H11V15H13V22H15V9.41L18.58,13L20,11.59L14.7,6.28C14.5,6.11 14.27,6 14,6"></path></svg> '. __( 'Body Type' ).'</p><span>'. $user->body.'</span></div>';
                                        }
                                        if( !empty( $user->smoke ) ) {
                                            $this->html .= '<div><p class="info_title"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M2,16H17V19H2V16M20.5,16H22V19H20.5V16M18,16H19.5V19H18V16M18.85,7.73C19.47,7.12 19.85,6.28 19.85,5.35C19.85,3.5 18.35,2 16.5,2V3.5C17.5,3.5 18.35,4.33 18.35,5.35C18.35,6.37 17.5,7.2 16.5,7.2V8.7C18.74,8.7 20.5,10.53 20.5,12.77V15H22V12.76C22,10.54 20.72,8.62 18.85,7.73M16.03,10.2H14.5C13.5,10.2 12.65,9.22 12.65,8.2C12.65,7.18 13.5,6.45 14.5,6.45V4.95C12.65,4.95 11.15,6.45 11.15,8.3A3.35,3.35 0 0,0 14.5,11.65H16.03C17.08,11.65 18,12.39 18,13.7V15H19.5V13.36C19.5,11.55 17.9,10.2 16.03,10.2Z"></path></svg> '. __( 'Smoke' ).'</p><span>'. $user->smoke.'</span></div>';
                                        }
                                        if( !empty( $user->ethnicity ) ) {
                                            $this->html .= '<div><p class="info_title"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M5,9.5L7.5,14H2.5L5,9.5M3,4H7V8H3V4M5,20A2,2 0 0,0 7,18A2,2 0 0,0 5,16A2,2 0 0,0 3,18A2,2 0 0,0 5,20M9,5V7H21V5H9M9,19H21V17H9V19M9,13H21V11H9V13Z"></path></svg> '. __( 'Ethnicity' ).'</p><span>'. $user->ethnicity.'</span></div>';
                                        }
                                        if( !empty( $user->pets ) ) {
                                            $this->html .= '<div><p class="info_title"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M8.35,3C9.53,2.83 10.78,4.12 11.14,5.9C11.5,7.67 10.85,9.25 9.67,9.43C8.5,9.61 7.24,8.32 6.87,6.54C6.5,4.77 7.17,3.19 8.35,3M15.5,3C16.69,3.19 17.35,4.77 17,6.54C16.62,8.32 15.37,9.61 14.19,9.43C13,9.25 12.35,7.67 12.72,5.9C13.08,4.12 14.33,2.83 15.5,3M3,7.6C4.14,7.11 5.69,8 6.5,9.55C7.26,11.13 7,12.79 5.87,13.28C4.74,13.77 3.2,12.89 2.41,11.32C1.62,9.75 1.9,8.08 3,7.6M21,7.6C22.1,8.08 22.38,9.75 21.59,11.32C20.8,12.89 19.26,13.77 18.13,13.28C17,12.79 16.74,11.13 17.5,9.55C18.31,8 19.86,7.11 21,7.6M19.33,18.38C19.37,19.32 18.65,20.36 17.79,20.75C16,21.57 13.88,19.87 11.89,19.87C9.9,19.87 7.76,21.64 6,20.75C5,20.26 4.31,18.96 4.44,17.88C4.62,16.39 6.41,15.59 7.47,14.5C8.88,13.09 9.88,10.44 11.89,10.44C13.89,10.44 14.95,13.05 16.3,14.5C17.41,15.72 19.26,16.75 19.33,18.38Z"></path></svg> '. __( 'Pets' ).'</p><span>'. $user->pets.'</span></div>';
                                        }

        $this->html .= '            </div>';
        $this->html .= '            <div class="vew_profile"><a href="'.$site_url.'/@'.$user->username.'" data-ajax="/@'. $user->username .'" class="btn waves-effect">'. __( 'View Profile' ).'</a></div>';
        $this->html .= '        </div>';
        $this->html .= '    </div>';


        $this->html .= '</div>';

        $this->html .= '</div>';
    }

}