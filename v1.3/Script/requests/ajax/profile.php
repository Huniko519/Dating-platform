<?php
Class Profile extends Aj {
    public function approve_story(){
        global $db;
        if (self::ActiveUser() == NULL) {
            return array(
                'status' => 403,
                'message' => __('Forbidden')
            );
        }
        $storyid            = Secure($_POST['storyid']);
        $story_to_userid    = Secure($_POST['storyuserid']);
        $storyuserid        = Secure($_POST['story_to_userid']);

        if( self::ActiveUser()->id == $story_to_userid ) {
            $story = $db->where('id', $storyid)->getOne('success_stories', array('quote'));
            $saved = $db->where('id', $storyid)->where('user_id', $storyuserid)->where('story_user_id', $story_to_userid)->update('success_stories', array(
                'status' => 1
            ));
            if ($saved) {
                $Notification = LoadEndPointResource('Notifications');
                if ($Notification) {
                    $Notification->createNotification(self::ActiveUser()->web_device_id, self::ActiveUser()->id, $storyuserid, 'approve_story', '', '/story/' . $storyid. '_'. url_slug($story['quote']));
                }
                return array(
                    'status' => 200,
                    'message' => __('Story approved successfully.'),
                    'url' => self::Config()->uri . '/story/' . $storyid. '_'. url_slug($story['quote'])
                );
            } else {
                return array(
                    'status' => 400,
                    'message' => __('Forbidden')
                );
            }
        }else{
            return array(
                'status' => 400,
                'message' => __('Forbidden')
            );
        }
    }
    public function disapprove_story(){
        global $db;
        if (self::ActiveUser() == NULL) {
            return array(
                'status' => 403,
                'message' => __('Forbidden')
            );
        }
        $storyid            = Secure($_POST['storyid']);
        $story_to_userid    = Secure($_POST['storyuserid']);
        $storyuserid        = Secure($_POST['story_to_userid']);

        if( self::ActiveUser()->id == $story_to_userid ) {
            $story = $db->where('id', $storyid)->getOne('success_stories', array('quote'));
            $saved = $db->where('id', $storyid)->where('user_id', $storyuserid)->where('story_user_id', $story_to_userid)->update('success_stories', array(
                'status' => 0
            ));
            if ($saved) {
                $Notification = LoadEndPointResource('Notifications');
                if ($Notification) {
                    $Notification->createNotification(self::ActiveUser()->web_device_id, self::ActiveUser()->id, $storyuserid, 'disapprove_story', '', '/story/' . $storyid. '_'. url_slug($story['quote']));
                }
                return array(
                    'status' => 200,
                    'message' => __('Story disapproved successfully.')
                );
            } else {
                return array(
                    'status' => 400,
                    'message' => __('Forbidden')
                );
            }
        }else{
            return array(
                'status' => 400,
                'message' => __('Forbidden')
            );
        }
    }
    public function add_new_story(){
        global $db;
        if (self::ActiveUser() == NULL) {
            return array(
                'status' => 403,
                'message' => __('Forbidden')
            );
        }
        $selected_user  = Secure($_POST['selected_user']);
        $story_date     = Secure($_POST['story_date']);
        $quote          = Secure($_POST['quote']);
        $story          = Secure(base64_decode($_POST['story']));

        $is_user_exist  = $db->where('username', $selected_user)->getOne('users','id');

        if( $is_user_exist['id'] !== null && $is_user_exist['id'] > 0 ){

            $exist = $db->where('user_id',self::ActiveUser()->id)->where('story_user_id',$is_user_exist['id'])->getOne('success_stories','id');
            if( $exist['id'] !== null && $exist['id'] > 0 ){
                return array(
                    'status' => 400,
                    'message' => __('You have previous story with this user')
                );
            }
            $saved = $db->insert('success_stories',array(
                'user_id' => self::ActiveUser()->id,
                'story_user_id' => $is_user_exist['id'],
                'quote' => $quote,
                'description' => $story,
                'story_date' => $story_date,
                'status' => 0,
                'created_at' => time()
            ));
            if($saved) {
                $Notification = LoadEndPointResource('Notifications');
                if ($Notification) {
                    $Notification->createNotification(self::ActiveUser()->web_device_id, self::ActiveUser()->id, $is_user_exist['id'], 'create_story', '', '/@' . self::ActiveUser()->username . '/story/' . $saved);
                }
                return array(
                    'status' => 200,
                    'message' => str_replace('{0}', $selected_user, __("Thank you for your story, we have sent the story to {0}, once approved your story will be published."))
                );
            }else{
                return array(
                    'status' => 400,
                    'message' => __('Forbidden')
                );
            }
        }else{
            return array(
                'status' => 400,
                'message' => __('No user found with this name')
            );
        }

    }
    public function unprivate_avater(){
        global $db, $_UPLOAD, $_DS;
        if (self::ActiveUser() == NULL) {
            return array(
                'status' => 403,
                'message' => __('Forbidden')
            );
        }
        $error = '';
        $url = '';
        if (isset($_POST)) {
            if (isset($_POST['url']) && !empty($_POST['url'])) {
                $url = Secure($_POST['url']);
                $url = str_replace(GetMedia('', false), '', $url);
            } else {
                $error .= '<p>• ' . __('Missing `url` parameter.') . '</p>';
            }
        }
        if ($error == '') {
            if ($_POST[ 'url' ] == self::ActiveUser()->avater->full) {
                return array(
                    'status' => 400,
                    'message' => __('You can not delete your profile image, but you can change it first.')
                );
            }
            $id = $db->where('user_id', self::ActiveUser()->id)->where('file', $url)->get('mediafiles', 1, array('*'));
            if ($id[0]['id'] > 0) {
                $db->where('id', $id[0]['id'])->update('mediafiles', array('is_private' => 0, 'private_file' => ''));
                $avater_file = str_replace('_full.', '_avatar.', $id[0]['private_file']);
                DeleteFromToS3($id[0]['private_file']);
                DeleteFromToS3($avater_file);
            }
            return array(
                'status' => 200,
                'message' => __('File deleted successfully')
            );
        } else {
            return array(
                'status' => 400,
                'message' => $error
            );
        }
    }
    public function private_avater() {
        global $db, $_UPLOAD, $_DS;
        if (self::ActiveUser() == NULL) {
            return array(
                'status' => 403,
                'message' => __('Forbidden')
            );
        }
        $error = '';
        $url   = '';
        if (isset($_POST)) {
            if (isset($_POST[ 'url' ]) && !empty($_POST[ 'url' ])) {
                $url = Secure($_POST[ 'url' ]);
                $url = str_replace(GetMedia('', false), '', $url);
            } else {
                $error .= '<p>• ' . __('Missing `url` parameter.') . '</p>';
            }
        }
        if ($error == '') {
            if ($_POST[ 'url' ] == self::ActiveUser()->avater->full) {
                return array(
                    'status' => 400,
                    'message' => __('You can not delete your profile image, but you can change it first.')
                );
            }
            $id = $db->where('user_id', self::ActiveUser()->id)->where('file', $url)->get('mediafiles', 1, array('*'));
            if ($id[0]['id'] > 0) {
                $avater_file = str_replace('_full.', '_avater.', $url);

                if (!file_exists($_UPLOAD . 'photos' . $_DS . 'private' . $_DS . date('Y'))) {
                    mkdir($_UPLOAD . 'photos' . $_DS . 'private' . $_DS . date('Y'), 0777, true);
                }
                if (!file_exists($_UPLOAD . 'photos' . $_DS . 'private' . $_DS . date('Y') . $_DS . date('m'))) {
                    mkdir($_UPLOAD . 'photos' . $_DS . 'private' . $_DS . date('Y') . $_DS . date('m'), 0777, true);
                }
                $dir = $_UPLOAD . 'photos' . $_DS . 'private' . $_DS . date('Y') . $_DS . date('m');
                $key      = GenerateKey();
                $file_extension    = pathinfo($url, PATHINFO_EXTENSION);

                $dest = $dir . $_DS . $key . '_private_full.'.$file_extension;
                $safe_dest = 'upload/photos/private/' . date('Y') . '/' . date('m') . '/' . $key . '_private_full.'.$file_extension;
                $safe_dest_thumb = 'upload/photos/private/' . date('Y') . '/' . date('m') . '/' . $key . '_private_avatar.'.$file_extension;

                if($id[0]['private_file'] == null) {
                    CompressImage($url, $dest, self::Config()->profile_picture_image_quality, true);
                }
                if(file_exists($dest)){

                    if($id[0]['private_file'] !== '') {
                        $safe_dest = $id[0]['private_file'];
                    }

                    $db->where('id', $id[0]['id'])->update('mediafiles', array('is_private' => 1, 'private_file' => $safe_dest));

                    $thumbnail = new ImageThumbnail($dest);
                    $thumbnail->setSize(self::Config()->profile_picture_width_crop, self::Config()->profile_picture_height_crop);
                    $thumbnail->save($safe_dest_thumb);

                    if (is_file($safe_dest_thumb)) {
                        $upload_s3 = UploadToS3($safe_dest_thumb, array(
                            'amazon' => 0
                        ));
                    }
                    if (is_file($safe_dest)) {
                        $upload_s3 .= UploadToS3($safe_dest, array(
                            'amazon' => 0
                        ));
                    }
                }


            }
            return array(
                'status' => 200,
                'message' => __('This image now is private.')
            );
        } else {
            return array(
                'status' => 400,
                'message' => $error
            );
        }
    }
    public function delete_avater() {
        global $db, $_UPLOAD, $_DS;
        if (self::ActiveUser() == NULL) {
            return array(
                'status' => 403,
                'message' => __('Forbidden')
            );
        }
        $error = '';
        $url   = '';
        if (isset($_POST)) {
            if (isset($_POST[ 'url' ]) && !empty($_POST[ 'url' ])) {
                $url = Secure($_POST[ 'url' ]);
                $url = str_replace(GetMedia('', false), '', $url);
            } else {
                $error .= '<p>• ' . __('Missing `url` parameter.') . '</p>';
            }
        }
        if ($error == '') {
            if ($_POST[ 'url' ] == self::ActiveUser()->avater->full) {
                return array(
                    'status' => 400,
                    'message' => __('You can not delete your profile image, but you can change it first.')
                );
            }
            $id = $db->where('user_id', self::ActiveUser()->id)->where('file', $url)->getValue('mediafiles', 'id');
            if ($id > 0) {

                $avater_file = str_replace('_full.', '_avater.', $url);
                DeleteFromToS3($url);
                DeleteFromToS3($avater_file);

                $private_file = $db->where('id', $id)->getValue('mediafiles', 'private_file');
                if($private_file !== ''){
                    $avater_file = str_replace('_full.', '_avatar.', $private_file);
                    DeleteFromToS3($avater_file);
                    DeleteFromToS3($private_file);
                }

                $db->where('id', $id)->delete('mediafiles');
            }
            return array(
                'status' => 200,
                'message' => __('File deleted successfully')
            );
        } else {
            return array(
                'status' => 400,
                'message' => $error
            );
        }
    }
    public function delete_avater_admin() {
        global $db, $_UPLOAD, $_DS;

        if (self::ActiveUser() == NULL || self::ActiveUser()->admin !== "1") {
            return array(
                'status' => 403,
                'message' => __('Forbidden')
            );
        }
        $error = '';
        $url   = '';
        if (isset($_POST)) {
            if (isset($_POST[ 'url' ]) && !empty($_POST[ 'url' ])) {
                $url = Secure($_POST[ 'url' ]);
                $url = str_replace(GetMedia('', false), '', $url);
            } else {
                $error .= '<p>• ' . __('Missing `url` parameter.') . '</p>';
            }
        }
        if ($error == '') {

            $media = $db->where('file', $url)->getOne('mediafiles', array(
                'user_id'
            ));

            $uid = $media['user_id'];
            $userData = userData($uid);

            $id = $db->where('user_id', $uid)->where('file', $url)->getValue('mediafiles', 'id');
            if ($id > 0) {

                $avater_file = str_replace('_full.', '_avater.', $url);
                DeleteFromToS3($url);
                DeleteFromToS3($avater_file);

                $private_file = $db->where('id', $id)->getValue('mediafiles', 'private_file');
                if($private_file !== ''){
                    $avater_file = str_replace('_full.', '_avatar.', $private_file);
                    DeleteFromToS3($avater_file);
                    DeleteFromToS3($private_file);
                }

                $db->where('id', $id)->delete('mediafiles');
                if ($_POST[ 'url' ] == $userData->avater->full) {
                    $last_img = $db->where('user_id', $uid)->orderBy('id','DESC')->getValue('mediafiles', 'file');
                    if($last_img){
                        $db->where('id', $uid)->update('users',array( 'avater' => str_replace('_full.', '_avater.', $last_img) ));
                    }else{
                        $db->where('id', $uid)->update('users',array( 'avater' => self::Config()->userDefaultAvatar ));
                    }
                }

            }

            return array(
                'status' => 200,
                'message' => __('File deleted successfully'),
                'userData' => $userData
            );

        } else {
            return array(
                'status' => 400,
                'message' => $error
            );
        }
    }
    public function upload_avater() {
        global $db, $_UPLOAD, $_DS;
        if (self::ActiveUser() == NULL) {
            return array(
                'status' => 403,
                'message' => __('Forbidden')
            );
        }
        if (!isset($_FILES) && empty($_FILES)) {
            return array(
                'status' => 403,
                'message' => __('Forbidden')
            );
        }
        $error = false;
        $files = array();
        if (!file_exists($_UPLOAD . 'photos' . $_DS . date('Y'))) {
            mkdir($_UPLOAD . 'photos' . $_DS . date('Y'), 0777, true);
        }
        if (!file_exists($_UPLOAD . 'photos' . $_DS . date('Y') . $_DS . date('m'))) {
            mkdir($_UPLOAD . 'photos' . $_DS . date('Y') . $_DS . date('m'), 0777, true);
        }
        $dir = $_UPLOAD . 'photos' . $_DS . date('Y') . $_DS . date('m');
        foreach ($_FILES as $file) {
            $ext      = pathinfo($file[ 'name' ], PATHINFO_EXTENSION);
            $key      = GenerateKey();
            $filename = $dir . $_DS . $key . '.' . $ext;
            if (move_uploaded_file($file[ 'tmp_name' ], $filename)) {
                $thumbfile = 'upload'. $_DS . 'photos' . $_DS . date('Y') . $_DS . date('m') . $_DS . $key . '_avater.' . $ext;
                $org_file  = 'upload'. $_DS . 'photos' . $_DS . date('Y') . $_DS . date('m') . $_DS . $key . '_full.' . $ext;
                $oreginal  = new ImageThumbnail($filename);
                $oreginal->setResize(false);
                $oreginal->save($org_file);
                $thumbnail = new ImageThumbnail($filename);
                $thumbnail->setSize(self::Config()->profile_picture_width_crop, self::Config()->profile_picture_height_crop);
                $thumbnail->save($thumbfile);
                @unlink($filename);
                if (is_file($org_file)) {
                    $upload_s3 = UploadToS3($org_file, array(
                        'amazon' => 0
                    ));
                } 
                if (is_file($thumbfile)) {
                    $upload_s3 .= UploadToS3($thumbfile, array(
                        'amazon' => 0
                    ));
                }
                $media                 = array();
                $media[ 'user_id' ]    = self::ActiveUser()->id;
                $media[ 'file' ]       = 'upload/photos/' . date('Y') . '/' . date('m') . '/' . $key . '_full.' . $ext;
                $media[ 'created_at' ] = date('Y-m-d H:i:s');
                $saved                 = $db->insert('mediafiles', $media);
                if ($saved) {
                    $_SESSION[ 'userEdited' ] = true;
                    $files[]                  = 'upload/photos/' . date('Y') . '/' . date('m') . '/' . $key . '_avater.' . $ext;
                }
            } else {
                $error = true;
            }
        }
        if ($error) {
            return array(
                'status' => 503
            );
        } else {
            return array(
                'status' => 200,
                'files' => $files
            );
        }
    }
    public function set_avater() {
        global $db;
        if (self::ActiveUser() == NULL) {
            return array(
                'status' => 403,
                'message' => __('Forbidden')
            );
        }
        if (empty($_GET[ 'id' ])) {
            return array(
                'status' => 403,
                'message' => __('Forbidden')
            );
        } else {
            $id = Secure($_GET[ 'id' ]);
            if ($id != self::ActiveUser()->avater->avater) {
                $updated = $db->where('id', self::ActiveUser()->id)->update('users', array(
                    'avater' => $id
                ));
                if ($updated) {
                    $_SESSION[ 'userEdited' ] = true;
                    return array(
                        'status' => 200
                    );
                } else {
                    return array(
                        'status' => 204
                    );
                }
            }
        }
    }
    public function set_user_avater() {
        global $db;
        if (self::ActiveUser() == NULL) {
            return array(
                'status' => 403,
                'message' => __('Forbidden')
            );
        }
        if (empty($_GET[ 'id' ]) && empty($_GET[ 'userid' ])) {
            return array(
                'status' => 403,
                'message' => __('Forbidden')
            );
        } else {
            $id       = Secure($_GET[ 'id' ]);
            $userid   = Secure($_GET[ 'userid' ]);
            $new_user = $db->where('id', $userid)->getOne('users', array(
                'id'
            ));
            if ($new_user) {
                $updated = $db->where('id', $userid)->update('users', array(
                    'avater' => $id
                ));
                if ($updated) {
                    $media = $db->where('file', str_replace('_avater.', '_full.', $id))->getOne('mediafiles', array(
                        'id'
                    ));
                    if ($media) {
                        $saved = $db->where('id', $media['id'])->update('mediafiles', array('user_id' => $userid));
                        if ($saved) {
                            $_SESSION[ 'userEdited' ] = true;
                            return array(
                                'status' => 200
                            );
                        }
                    }
                } else {
                    return array(
                        'status' => 204
                    );
                }
            }
        }
    }
    public function set() {
        global $db;
        if (self::ActiveUser() == NULL) {
            return array(
                'status' => 403,
                'message' => __('Forbidden')
            );
        }
        if (empty($_GET[ 'key' ])) {
            return array(
                'status' => 403,
                'message' => __('Forbidden')
            );
        }
        if (empty($_GET[ 'value' ])) {
            return array(
                'status' => 403,
                'message' => __('Forbidden')
            );
        }
        if ($_GET[ 'key' ] == 'username' || $_GET[ 'key' ] == 'password' || $_GET[ 'key' ] == 'admin' || $_GET[ 'key' ] == 'id' || $_GET[ 'key' ] == 'active' || $_GET[ 'key' ] == 'verified') {
            return array(
                'status' => 403,
                'message' => __('Forbidden')
            );
        }
        $key     = Secure($_GET[ 'key' ]);
        $value   = Secure($_GET[ 'value' ]);
        $updated = $db->where('id', self::ActiveUser()->id)->update('users', array(
            $key => $value
        ));
        if ($updated) {
            $_SESSION[ 'userEdited' ] = true;
            return array(
                'status' => 200
            );
        } else {
            return array(
                'status' => 204
            );
        }
    }
    public function set_data() {
        global $db;
        if (self::ActiveUser() == NULL) {
            return array(
                'status' => 403,
                'message' => __('Forbidden')
            );
        }
        if (isset($_GET[ 'path' ])) {
            unset($_GET[ 'path' ]);
        }
        $data = array();
        foreach ($_GET as $key => $value) {
            if ($key == 'username' || $key == 'password' || $key == 'admin' || $key == 'id') {
                return array(
                    'status' => 403,
                    'message' => __('Forbidden')
                );
            } else {
                $data[ $key ] = Secure($value);
            }
        }
        if(isset($data['start_up']) && $data['start_up'] == "2"){
            if(self::Config()->emailValidation == "0"){
                $data['start_up'] = "3";
            }
        }
        $updated = $db->where('id', self::ActiveUser()->id)->update('users', $data);
        if ($updated) {
            $_SESSION[ 'userEdited' ] = true;
            return array(
                'status' => 200
            );
        } else {
            return array(
                'status' => 204
            );
        }
    }
    public function set_snapshotdata(){
        global $db, $_UPLOAD, $_DS;
        if (self::ActiveUser() == NULL) {
            return array(
                'status' => 403,
                'message' => __('Forbidden')
            );
        }
        if( !isset( $_POST['snapshot'] ) ){
            return array(
                'status' => 204
            );
        }else{
            $img = $_POST['snapshot'];
            $img = str_replace('data:image/png;base64,', '', $img);
            $img = str_replace(' ', '+', $img);
            $fileData = base64_decode($img);
            if (!file_exists($_UPLOAD . 'snapshots' . $_DS . date('Y'))) {
                mkdir($_UPLOAD . 'snapshots' . $_DS . date('Y'), 0777, true);
            }
            if (!file_exists($_UPLOAD . 'snapshots' . $_DS . date('Y') . $_DS . date('m'))) {
                mkdir($_UPLOAD . 'snapshots' . $_DS . date('Y') . $_DS . date('m'), 0777, true);
            }
            $dir = $_UPLOAD . 'snapshots' . $_DS . date('Y') . $_DS . date('m');
            $key      = GenerateKey();
            $fileName = $dir . $_DS . $key . '.jpg';
            file_put_contents($fileName, $fileData);
            if (is_file($fileName)) {
                $upload_s3 = UploadToS3($fileName, array(
                    'amazon' => 0
                ));
            }

            $data = array();
            $data['status'] = "2";
            $data['start_up'] = "1";
            if( self::Config()->pending_verification == "1" ) {
                $data['verified'] = "0";
            }
            $data['snapshot'] = 'upload/snapshots/' . date('Y') . '/' . date('m') . '/' . $key . '.jpg';

            $updated = $db->where('id', self::ActiveUser()->id)->update('users', $data);
            if ($updated) {
                $_SESSION[ 'userEdited' ] = true;
                return array(
                    'status' => 200
                );
            } else {
                return array(
                    'status' => 204
                );
            }
        }


    }
    public function save_general_setting() {
        global $db, $config;
        if (self::ActiveUser() == NULL) {
            return array(
                'status' => 403,
                'message' => __('Forbidden')
            );
        }
        $error       = "";
        $set_admin   = false;
        $set_pro     = false;
        $set_balance = false;
        $user        = array();
        $users       = LoadEndPointResource('users');
        $target_id   = self::ActiveUser()->id;
        $change_phone = false;
        if (isset($_POST) && !empty($_POST)) {
            if (isset($_POST[ 'targetuid' ]) && $_POST[ 'targetuid' ] !== '') {
                $targetuid = base64_decode(strrev(Secure($_POST[ 'targetuid' ])));
                if (is_numeric($targetuid) && $targetuid > 0) {
                    $target_id = (int) $targetuid;
                }
            }
            if (isset($_POST[ 'first_name' ]) && strlen($_POST[ 'first_name' ]) > 30) {
                $error .= '<p>• ' . __('you can not use more than 30 character for first name.') . '</p>';
            }
            if (isset($_POST[ 'last_name' ]) && strlen($_POST[ 'last_name' ]) > 30) {
                $error .= '<p>• ' . __('you can not use more than 30 character for last name.') . '</p>';
            }
            if (isset($_POST[ 'username' ]) && $target_id == self::ActiveUser()->id) {
                if (Secure($_POST[ 'username' ]) !== self::ActiveUser()->username) {
                    if ($users) {
                        if ($users->isUsernameExists(Secure($_POST[ 'username' ]))) {
                            $error .= '<p>• ' . __('This User name is Already exist.') . '</p>';
                        }
                    }
                    $user_name_list = array(
                        'home',
                        'register',
                        'login',
                        'reset_password',
                        'social_login',
                        'find-matches',
                        'pro',
                        'credit',
                        'settings',
                        'settings-profile',
                        'settings-privacy',
                        'settings-password',
                        'settings-social',
                        'settings-blocked',
                        'settings-delete',
                        'settings-delete',
                        'visits',
                        'likes',
                        'liked',
                        'disliked',
                        'transactions',
                        'admin',
                        'about',
                        'contact',
                        'forgot',
                        'mail-otp',
                        'privacy',
                        'terms',
                        'reset',
                        'profile',
                        'ajax'
                    );
                    if (in_array(Secure($_POST[ 'username' ]), $user_name_list)) {
                        $error .= '<p>• ' . __('This User name is reserved word. please choose anther username.') . '</p>';
                    }
                }
            }
            if (isset($_POST[ 'username' ]) && empty($_POST[ 'username' ])) {
                $error .= '<p>• ' . __('empty user name.') . '</p>';
            }
            if (isset($_POST[ 'email' ]) && $target_id == self::ActiveUser()->id) {
                if (!filter_var($_POST[ 'email' ], FILTER_VALIDATE_EMAIL)) {
                    $error .= '<p>• ' . __('This e-mail is invalid.') . '</p>';
                } else {
                    if ($users) {
                        if (Secure($_POST[ 'email' ]) !== self::ActiveUser()->email) {
                            if ($users->isEmailExists(Secure($_POST[ 'email' ]))) {
                                $error .= '<p>• ' . __('This email is Already exist.') . '</p>';
                            }
                        }
                    }
                }
            }
            if (self::ActiveUser()->admin == 1) {
                if (isset($_POST[ 'admin' ]) && ( $_POST[ 'admin' ] == 'on' || $_POST[ 'admin' ] == 'off' ) ) {
                    $set_admin = true;
                }
                if (isset($_POST[ 'is_pro' ]) && $_POST[ 'is_pro' ] == 'on') {
                    $set_pro = true;
                }
                if (isset($_POST[ 'balance' ]) && $_POST[ 'balance' ] > 0) {
                    $set_balance = true;
                }
            }
            if ($error == '') {
                if (isset($_POST[ 'admin' ])) {
                    if ($set_admin) {
                        if ($_POST['admin'] == 'on') {
                            $user['admin'] = '1';
                        } elseif ($_POST['admin'] == 'off') {
                            $user['admin'] = '0';
                        }
                    }
                }
                if ($set_pro) {
                    if (self::ActiveUser()->admin == 1) {
                        $user['is_pro'] = '1';
                        $user['pro_type'] = '4';
                        $user['pro_time'] = time();
                    }
                } else {
                    if (self::ActiveUser()->admin == 1) {
                        $user['is_pro'] = '0';
                        $user['pro_type'] = '0';
                        $user['pro_time'] = '0';
                    }
                }
                if ($set_balance) {
                    $user[ 'balance' ] = Secure($_POST[ 'balance' ]);
                }
                if (isset($_POST[ 'first_name' ])) {
                    $user[ 'first_name' ] = Secure($_POST[ 'first_name' ]);
                }
                if (isset($_POST[ 'last_name' ])) {
                    $user[ 'last_name' ] = Secure($_POST[ 'last_name' ]);
                }
                if (isset($_POST[ 'email' ])) {
                    $user[ 'email' ] = Secure($_POST[ 'email' ]);
                }
                if (isset($_POST[ 'username' ])) {
                    $user[ 'username' ] = Secure($_POST[ 'username' ]);
                }
                if (isset($_POST[ 'country' ])) {
                    $user[ 'country' ] = Secure($_POST[ 'country' ]);
                }
                if (isset($_POST[ 'phone_number' ])) {
                    $user[ 'phone_number' ] = Secure($_POST[ 'phone_number' ]);
                    if (self::ActiveUser()->phone_number !== $user[ 'phone_number' ]) {
                        if (self::ActiveUser()->phone_verified == 1) {
                            $user[ 'verified' ] = '0';
                            $user[ 'phone_verified' ] = '0';
                            $user[ 'start_up' ] = '2';
                            $change_phone = true;
                        }
                    }
                }
                if (isset($_POST[ 'gender' ])) {
                    $user[ 'gender' ] = Secure($_POST[ 'gender' ]);
                }
                if (isset($_POST[ 'birthday' ])) {
                    $user[ 'birthday' ] = Secure($_POST[ 'birthday' ]);
                }

                $saved = $db->where('id', $target_id)->update('users', $user);
                if ($saved) {

                    $field_data = array();
                    if (!empty($_POST['custom_fields'])) {
                        $fields = GetProfileFields('general');
                        foreach ($fields as $key => $field) {
                            $name = $field['fid'];
                            if (isset($_POST[$name])) {
                                if (mb_strlen($_POST[$name]) > $field['length']) {
                                    $errors[] = $field['name'] . ' field max characters is ' . $field['length'];
                                }
                                $field_data[] = array(
                                    $name => $_POST[$name]
                                );
                            }
                        }
                    }
                    if (!empty($field_data)) {
                        $insert = UpdateUserCustomData($target_id, $field_data);
                    }

                    if ($target_id == self::ActiveUser()->id) {
                        $_SESSION[ 'userEdited' ] = true;

                        if( $change_phone === true ){
                            return array(
                                'status' => 200,
                                'message' => '<p> ' . __('Profile general data saved successfully.') . '</p>',
                                'url' => $config->uri
                            );

                        }else{
                            return array(
                                'status' => 200,
                                'message' => '<p> ' . __('Profile general data saved successfully.') . '</p>',
                                'updateDom' => array(
                                    'selector' => '#profile_link',
                                    'attributes' => array(
                                        'href' => $config->uri . '/@' . Secure($_POST[ 'username' ]),
                                        'data-ajax' => '/@' . Secure($_POST[ 'username' ])
                                    )
                                )
                            );
                        }

                    } else {
                        return array(
                            'status' => 200,
                            'message' => '<p> ' . __('Profile general data saved successfully.') . '</p>'
                        );
                    }
                } else {
                    $error .= '<p>• ' . __('Error while saving general profile settings.') . '</p>';
                }
            }
        }
        if ($error !== '') {
            return array(
                'status' => 401,
                'message' => $error
            );
        }
    }
    public function save_profile_setting() {
        global $db;
        if (self::ActiveUser() == NULL) {
            return array(
                'status' => 403,
                'message' => __('Forbidden')
            );
        }
        $error     = "";
        $msg       = "";
        $user      = array();
        $target_id = self::ActiveUser()->id;
        if (isset($_POST) && !empty($_POST)) {
            if (isset($_POST[ 'targetuid' ]) && $_POST[ 'targetuid' ] !== '') {
                $targetuid = base64_decode(strrev(Secure($_POST[ 'targetuid' ])));
                if (is_numeric($targetuid) && $targetuid > 0) {
                    $target_id = (int) $targetuid;
                }
            }
            if (isset($_POST[ 'about' ])) {
                $user[ 'about' ] = Secure($_POST[ 'about' ]);
            }
            if (isset($_POST[ 'interest' ])) {
                $user[ 'interest' ] = Secure($_POST[ 'interest' ]);
            }
            if (isset($_POST[ 'location' ])) {
                $user[ 'location' ] = Secure($_POST[ 'location' ]);
            }
            if (isset($_POST[ 'relationship' ])) {
                $user[ 'relationship' ] = Secure($_POST[ 'relationship' ]);
            }
            if (isset($_POST[ 'language' ])) {
                $user[ 'language' ] = Secure($_POST[ 'language' ]);
            }
            if (isset($_POST[ 'work_status' ])) {
                $user[ 'work_status' ] = Secure($_POST[ 'work_status' ]);
            }
            if (isset($_POST[ 'education' ])) {
                $user[ 'education' ] = Secure($_POST[ 'education' ]);
            }
            if (isset($_POST[ 'ethnicity' ])) {
                $user[ 'ethnicity' ] = Secure($_POST[ 'ethnicity' ]);
            }
            if (isset($_POST[ 'body' ])) {
                $user[ 'body' ] = Secure($_POST[ 'body' ]);
            }
            if (isset($_POST[ 'height' ])) {
                $user[ 'height' ] = Secure($_POST[ 'height' ]);
            }
            if (isset($_POST[ 'hair_color' ])) {
                $user[ 'hair_color' ] = Secure($_POST[ 'hair_color' ]);
            }
            if (isset($_POST[ 'character' ])) {
                $user[ 'character' ] = Secure($_POST[ 'character' ]);
            }
            if (isset($_POST[ 'children' ])) {
                $user[ 'children' ] = Secure($_POST[ 'children' ]);
            }
            if (isset($_POST[ 'friends' ])) {
                $user[ 'friends' ] = Secure($_POST[ 'friends' ]);
            }
            if (isset($_POST[ 'pets' ])) {
                $user[ 'pets' ] = Secure($_POST[ 'pets' ]);
            }
            if (isset($_POST[ 'live_with' ])) {
                $user[ 'live_with' ] = Secure($_POST[ 'live_with' ]);
            }
            if (isset($_POST[ 'car' ])) {
                $user[ 'car' ] = Secure($_POST[ 'car' ]);
            }
            if (isset($_POST[ 'religion' ])) {
                $user[ 'religion' ] = Secure($_POST[ 'religion' ]);
            }
            if (isset($_POST[ 'smoke' ])) {
                $user[ 'smoke' ] = Secure($_POST[ 'smoke' ]);
            }
            if (isset($_POST[ 'drink' ])) {
                $user[ 'drink' ] = Secure($_POST[ 'drink' ]);
            }
            if (isset($_POST[ 'travel' ])) {
                $user[ 'travel' ] = Secure($_POST[ 'travel' ]);
            }
            if (isset($_POST[ 'music' ])) {
                $user[ 'music' ] = Secure($_POST[ 'music' ]);
            }
            if (isset($_POST[ 'dish' ])) {
                $user[ 'dish' ] = Secure($_POST[ 'dish' ]);
            }
            if (isset($_POST[ 'song' ])) {
                $user[ 'song' ] = Secure($_POST[ 'song' ]);
            }
            if (isset($_POST[ 'hobby' ])) {
                $user[ 'hobby' ] = Secure($_POST[ 'hobby' ]);
            }
            if (isset($_POST[ 'city' ])) {
                $user[ 'city' ] = Secure($_POST[ 'city' ]);
            }
            if (isset($_POST[ 'sport' ])) {
                $user[ 'sport' ] = Secure($_POST[ 'sport' ]);
            }
            if (isset($_POST[ 'book' ])) {
                $user[ 'book' ] = Secure($_POST[ 'book' ]);
            }
            if (isset($_POST[ 'movie' ])) {
                $user[ 'movie' ] = Secure($_POST[ 'movie' ]);
            }
            if (isset($_POST[ 'colour' ])) {
                $user[ 'colour' ] = Secure($_POST[ 'colour' ]);
            }
            if (isset($_POST[ 'tv' ])) {
                $user[ 'tv' ] = Secure($_POST[ 'tv' ]);
            }
            if (!empty($user)) {
                $saved = $db->where('id', $target_id)->update('users', $user);
                if ($saved) {

                    $field_data = array();
                    if (!empty($_POST['custom_fields'])) {
                        $fields = GetProfileFields('profile');
                        foreach ($fields as $key => $field) {
                            $name = $field['fid'];
                            if (isset($_POST[$name])) {
                                if (mb_strlen($_POST[$name]) > $field['length']) {
                                    $errors[] = $field['name'] . ' field max characters is ' . $field['length'];
                                }
                                $field_data[] = array(
                                    $name => $_POST[$name]
                                );
                            }
                        }
                    }
                    if (!empty($field_data)) {
                        $insert = UpdateUserCustomData($target_id, $field_data);
                    }

                    if ($target_id == self::ActiveUser()->id) {
                        $_SESSION[ 'userEdited' ] = true;
                    }
                    return array(
                        'status' => 200,
                        'message' => '<p> ' . __('Profile data saved successfully.') . '</p>'
                    );
                } else {
                    $error .= '<p>• ' . __('Error while saving profile settings.') . '</p>';
                }
            } else {
                return array(
                    'status' => 200,
                    'message' => '<p> ' . __('Profile data saved successfully.') . '</p>'
                );
            }
        }
        if ($error !== '') {
            return array(
                'status' => 401,
                'message' => $error
            );
        }
    }
    public function save_privacy_setting() {
        global $db;
        if (self::ActiveUser() == NULL) {
            return array(
                'status' => 403,
                'message' => __('Forbidden')
            );
        }
        $error     = "";
        $msg       = "";
        $user      = array();
        $target_id = self::ActiveUser()->id;
        if (isset($_POST)) {
            if (isset($_POST[ 'targetuid' ]) && $_POST[ 'targetuid' ] !== '') {
                $targetuid = base64_decode(strrev(Secure($_POST[ 'targetuid' ])));
                if (is_numeric($targetuid) && $targetuid > 0) {
                    $target_id = (int) $targetuid;
                }
            }
            if (isset($_POST[ 'privacy_show_profile_on_google' ]) && !empty($_POST[ 'privacy_show_profile_on_google' ])) {
                $user[ 'privacy_show_profile_on_google' ] = (Secure($_POST[ 'privacy_show_profile_on_google' ]) == "on") ? 1 : 0;
            } else {
                $user[ 'privacy_show_profile_on_google' ] = 0;
            }
            if (isset($_POST[ 'privacy_show_profile_random_users' ]) && !empty($_POST[ 'privacy_show_profile_random_users' ])) {
                $user[ 'privacy_show_profile_random_users' ] = (Secure($_POST[ 'privacy_show_profile_random_users' ]) == "on") ? 1 : 0;
            } else {
                $user[ 'privacy_show_profile_random_users' ] = 0;
            }
            if (isset($_POST[ 'privacy_show_profile_match_profiles' ]) && !empty($_POST[ 'privacy_show_profile_match_profiles' ])) {
                $user[ 'privacy_show_profile_match_profiles' ] = (Secure($_POST[ 'privacy_show_profile_match_profiles' ]) == "on") ? 1 : 0;
            } else {
                $user[ 'privacy_show_profile_match_profiles' ] = 0;
            }
            if (!empty($user)) {
                $saved = $db->where('id', $target_id)->update('users', $user);
                if ($saved) {
                    if ($target_id == self::ActiveUser()->id) {
                        $_SESSION[ 'userEdited' ] = true;
                    }
                    return array(
                        'status' => 200,
                        'message' => '<p> ' . __('Profile privacy data saved successfully.') . '</p>'
                    );
                } else {
                    $error .= '<p>• ' . __('Error while saving privacy setting.') . '</p>';
                }
            } else {
                return array(
                    'status' => 200,
                    'message' => '<p> ' . __('Profile privacy data saved successfully.') . '</p>'
                );
            }
        }
        if ($error !== '') {
            return array(
                'status' => 401,
                'message' => $error
            );
        }
    }
    public function save_password_setting() {
        global $db;
        if (self::ActiveUser() == NULL) {
            return array(
                'status' => 403,
                'message' => __('Forbidden')
            );
        }
        $error            = "";
        $msg              = "";
        $set_new_password = false;
        $target_id        = self::ActiveUser()->id;
        if (isset($_POST) && !empty($_POST)) {
            if (isset($_POST[ 'targetuid' ]) && $_POST[ 'targetuid' ] !== '') {
                $targetuid = base64_decode(strrev(Secure($_POST[ 'targetuid' ])));
                if (is_numeric($targetuid) && $targetuid > 0) {
                    $target_id = (int) $targetuid;
                }
            }
            if ($_POST[ 'n_pass' ] !== $_POST[ 'cn_pass' ]) {
                $error .= "<p>• " . __("Passwords Don't Match.") . "</p>";
            }
            if (isset($_POST[ 'n_pass' ]) && empty($_POST[ 'n_pass' ])) {
                $error .= '<p>• ' . __('Missing New password.') . '</p>';
            }
            if (!empty($_POST[ 'n_pass' ]) && strlen($_POST[ 'n_pass' ]) < 6) {
                $error .= '<p>• ' . __('Password is too short.') . '</p>';
            }
            if (self::ActiveUser()->admin == "1") {
                $set_new_password = true;
            } else {
                if (isset($_POST[ 'c_pass' ]) && empty($_POST[ 'c_pass' ])) {
                    $error .= '<p>• ' . __('Current password missing .') . '</p>';
                } else {
                    $currentpass     = $db->where('id', $target_id)->getValue("users", "password");
                    $password_result = password_verify(Secure($_POST[ 'c_pass' ]), $currentpass);
                    if ($password_result == true) {
                        $set_new_password = true;
                    } else {
                        if (!empty($_POST[ 'c_pass' ])) {
                            $error .= '<p>• ' . __('Current password is wrong, please check again.') . '</p>';
                        }
                    }
                }
            }
            if ($error == '') {
                if ($set_new_password) {
                    $_new_password = password_hash(Secure($_POST[ 'n_pass' ]), PASSWORD_DEFAULT, array(
                        'cost' => 11
                    ));
                    $updated       = $db->where('id', $target_id)->update('users', array(
                        'password' => $_new_password
                    ));
                    if ($updated) {
                        if ($target_id == self::ActiveUser()->id) {
                            $_SESSION[ 'userEdited' ] = true;
                        }
                        return array(
                            'status' => 200,
                            'message' => '<p> ' . __('Password updated successfully.') . '</p>'
                        );
                    } else {
                        $error .= '<p>• ' . __('Error while update your password, please check again.') . '</p>';
                    }
                }
            }
        }
        if ($error !== '') {
            return array(
                'status' => 401,
                'message' => $error
            );
        }
    }
    public function save_social_setting() {
        global $db;
        if (self::ActiveUser() == NULL) {
            return array(
                'status' => 403,
                'message' => __('Forbidden')
            );
        }
        $error     = '';
        $msg       = '';
        $user      = array();
        $target_id = self::ActiveUser()->id;
        if (isset($_POST) && !empty($_POST)) {
            if (isset($_POST[ 'targetuid' ]) && $_POST[ 'targetuid' ] !== '') {
                $targetuid = base64_decode(strrev(Secure($_POST[ 'targetuid' ])));
                if (is_numeric($targetuid) && $targetuid > 0) {
                    $target_id = (int) $targetuid;
                }
            }
            if (isset($_POST[ 'facebook' ])) {
                if (filter_var($_POST[ 'facebook' ], FILTER_VALIDATE_URL)) {
                    $error .= '<p>• ' . __('Please enter just facebook profile user.') . '</p>';
                } else {
                    $user[ 'facebook' ] = Secure($_POST[ 'facebook' ]);
                }
            }
            if (isset($_POST[ 'twitter' ])) {
                if (filter_var($_POST[ 'twitter' ], FILTER_VALIDATE_URL)) {
                    $error .= '<p>• ' . __('Please enter just twitter profile user.') . '</p>';
                } else {
                    $user[ 'twitter' ] = Secure($_POST[ 'twitter' ]);
                }
            }
            if (isset($_POST[ 'google' ])) {
                if (filter_var($_POST[ 'google' ], FILTER_VALIDATE_URL)) {
                    $error .= '<p>• ' . __('Please enter just google profile user.') . '</p>';
                } else {
                    $user[ 'google' ] = Secure($_POST[ 'google' ]);
                }
            }
            if (isset($_POST[ 'instagram' ])) {
                if (filter_var($_POST[ 'instagram' ], FILTER_VALIDATE_URL)) {
                    $error .= '<p>• ' . __('Please enter just instagram profile user.') . '</p>';
                } else {
                    $user[ 'instagram' ] = Secure($_POST[ 'instagram' ]);
                }
            }
            if (isset($_POST[ 'linkedin' ])) {
                if (filter_var($_POST[ 'linkedin' ], FILTER_VALIDATE_URL)) {
                    $error .= '<p>• ' . __('Please enter just linkedin profile user.') . '</p>';
                } else {
                    $user[ 'linkedin' ] = Secure($_POST[ 'linkedin' ]);
                }
            }
            if (isset($_POST[ 'website' ])) {
                if (!empty($_POST[ 'website' ])) {
                    if (filter_var($_POST[ 'website' ], FILTER_VALIDATE_URL)) {
                        $user[ 'website' ] = Secure($_POST[ 'website' ]);
                    } else {
                        $error .= '<p>• ' . __('Please enter valid domain name.') . '</p>';
                    }
                } else {
                    $user[ 'website' ] = '';
                }
            }
            if ($error == '') {
                if (!empty($user)) {
                    $saved = $db->where('id', $target_id)->update('users', $user);
                    if ($saved) {

                        $field_data = array();
                        if (!empty($_POST['custom_fields'])) {
                            $fields = GetProfileFields('social');
                            foreach ($fields as $key => $field) {
                                $name = $field['fid'];
                                if (isset($_POST[$name])) {
                                    if (mb_strlen($_POST[$name]) > $field['length']) {
                                        $errors[] = $field['name'] . ' field max characters is ' . $field['length'];
                                    }
                                    $field_data[] = array(
                                        $name => $_POST[$name]
                                    );
                                }
                            }
                        }
                        if (!empty($field_data)) {
                            $insert = UpdateUserCustomData($target_id, $field_data);
                        }

                        if ($target_id == self::ActiveUser()->id) {
                            $_SESSION[ 'userEdited' ] = true;
                        }
                        return array(
                            'status' => 200,
                            'message' => '<p> ' . __('Social setting updated successfully.') . '</p>'
                        );
                    } else {
                        $error .= '<p>• ' . __('Error while saving social setting.') . '</p>';
                    }
                } else {
                    return array(
                        'status' => 200,
                        'message' => '<p> ' . __('Social setting updated successfully.') . '</p>'
                    );
                }
            }
        }
        if ($error !== '') {
            return array(
                'status' => 401,
                'message' => $error
            );
        }
    }
    public function save_email_setting() {
        global $db;
        if (self::ActiveUser() == NULL) {
            return array(
                'status' => 403,
                'message' => __('Forbidden')
            );
        }
        $error = '';
        $msg   = '';
        $user  = array();
        if (isset($_POST)) {
            if (isset($_POST[ 'email_on_profile_view' ]) && !empty($_POST[ 'email_on_profile_view' ]) && $_POST[ 'email_on_profile_view' ] !== self::ActiveUser()->email_on_profile_view) {
                $user[ 'email_on_profile_view' ] = (Secure($_POST[ 'email_on_profile_view' ]) == "on") ? 1 : 0;
            } else {
                $user[ 'email_on_profile_view' ] = 0;
            }
            if (isset($_POST[ 'email_on_new_message' ]) && !empty($_POST[ 'email_on_new_message' ]) && $_POST[ 'email_on_new_message' ] !== self::ActiveUser()->email_on_new_message) {
                $user[ 'email_on_new_message' ] = (Secure($_POST[ 'email_on_new_message' ]) == "on") ? 1 : 0;
            } else {
                $user[ 'email_on_new_message' ] = 0;
            }
            if (isset($_POST[ 'email_on_profile_like' ]) && !empty($_POST[ 'email_on_profile_like' ]) && $_POST[ 'email_on_profile_like' ] !== self::ActiveUser()->email_on_profile_like) {
                $user[ 'email_on_profile_like' ] = (Secure($_POST[ 'email_on_profile_like' ]) == "on") ? 1 : 0;
            } else {
                $user[ 'email_on_profile_like' ] = 0;
            }
            if (isset($_POST[ 'email_on_purchase_notifications' ]) && !empty($_POST[ 'email_on_purchase_notifications' ]) && $_POST[ 'email_on_purchase_notifications' ] !== self::ActiveUser()->email_on_purchase_notifications) {
                $user[ 'email_on_purchase_notifications' ] = (Secure($_POST[ 'email_on_purchase_notifications' ]) == "on") ? 1 : 0;
            } else {
                $user[ 'email_on_purchase_notifications' ] = 0;
            }
            if (isset($_POST[ 'email_on_special_offers' ]) && !empty($_POST[ 'email_on_special_offers' ]) && $_POST[ 'email_on_special_offers' ] !== self::ActiveUser()->email_on_special_offers) {
                $user[ 'email_on_special_offers' ] = (Secure($_POST[ 'email_on_special_offers' ]) == "on") ? 1 : 0;
            } else {
                $user[ 'email_on_special_offers' ] = 0;
            }
            if (isset($_POST[ 'email_on_announcements' ]) && !empty($_POST[ 'email_on_announcements' ]) && $_POST[ 'email_on_announcements' ] !== self::ActiveUser()->email_on_announcements) {
                $user[ 'email_on_announcements' ] = (Secure($_POST[ 'email_on_announcements' ]) == "on") ? 1 : 0;
            } else {
                $user[ 'email_on_announcements' ] = 0;
            }

            if (isset($_POST[ 'email_on_get_gift' ]) && !empty($_POST[ 'email_on_get_gift' ]) && $_POST[ 'email_on_get_gift' ] !== self::ActiveUser()->email_on_get_gift) {
                $user[ 'email_on_get_gift' ] = (Secure($_POST[ 'email_on_get_gift' ]) == "on") ? 1 : 0;
            } else {
                $user[ 'email_on_get_gift' ] = 0;
            }
            if (isset($_POST[ 'email_on_got_new_match' ]) && !empty($_POST[ 'email_on_got_new_match' ]) && $_POST[ 'email_on_got_new_match' ] !== self::ActiveUser()->email_on_got_new_match) {
                $user[ 'email_on_got_new_match' ] = (Secure($_POST[ 'email_on_got_new_match' ]) == "on") ? 1 : 0;
            } else {
                $user[ 'email_on_got_new_match' ] = 0;
            }
            if (isset($_POST[ 'email_on_chat_request' ]) && !empty($_POST[ 'email_on_chat_request' ]) && $_POST[ 'email_on_chat_request' ] !== self::ActiveUser()->email_on_chat_request) {
                $user[ 'email_on_chat_request' ] = (Secure($_POST[ 'email_on_chat_request' ]) == "on") ? 1 : 0;
            } else {
                $user[ 'email_on_chat_request' ] = 0;
            }

            if (!empty($user)) {
                $saved = $db->where('id', self::ActiveUser()->id)->update('users', $user);
                if ($saved) {
                    $_SESSION[ 'userEdited' ] = true;
                    return array(
                        'status' => 200,
                        'message' => '<p> ' . __('Emails setting saved successfully.') . '</p>'
                    );
                } else {
                    $error .= '<p>• ' . __('Error while saving email setting.') . '</p>';
                }
            } else {
                return array(
                    'status' => 200,
                    'message' => '<p> ' . __('Emails setting saved successfully.') . '</p>'
                );
            }
        }
        if ($error !== '') {
            return array(
                'status' => 401,
                'message' => $error
            );
        }
    }
    public function get_profile_likes() {
        global $db, $config;
        if (self::ActiveUser() == NULL) {
            return array(
                'status' => 403,
                'message' => __('Forbidden')
            );
        }
        $likes_html = '';
        $likes      = $db->objectBuilder()->join('users u', 'l.user_id=u.id', 'LEFT')->where('l.like_userid', self::ActiveUser()->id)->where('l.is_like', "1")->groupBy('l.user_id')->orderBy('l.created_at', 'DESC')->get('likes l', null, array(
            'DISTINCT(l.user_id)',
            'u.username',
            'u.avater',
            'l.created_at'
        ));
        foreach ($likes as $key => $value) {
            $likes_html .= '<li><a href="' . $config->uri . '/@' . $value->username . '" data-ajax="/@' . $value->username . '" class="valign-wrapper"><img src="' . GetMedia($value->avater) . '" class="margin_right_ten" /><span class="bold">' . $value->username . '</span></a><a href="' . $config->uri . '/@' . $value->username . '" class="valign-wrapper time ajax-time " title="' . date('c', strtotime($value->created_at)) . '">' . Time_Elapsed_String(strtotime($value->created_at)) . '</a></li>';
        }
        return array(
            'status' => 200,
            'likes' => $likes_html
        );
    }
    public function get_profile_views() {
        global $db, $config;
        if (self::ActiveUser() == NULL) {
            return array(
                'status' => 403,
                'message' => __('Forbidden')
            );
        }
        $views_html = '';
        $views      = $db->objectBuilder()->join('users u', 'v.user_id=u.id', 'LEFT')->where('v.view_userid', self::ActiveUser()->id)->groupBy('v.user_id')->orderBy('v.created_at', 'DESC')->get('views v', null, array(
            'DISTINCT(v.user_id)',
            'u.username',
            'u.avater',
            'max(v.created_at) as created_at'
        ));
        foreach ($views as $key => $value) {
            $views_html .= '<li><a href="' . $config->uri . '/@' . $value->username . '" data-ajax="/@' . $value->username . '" class="valign-wrapper"><img src="' . GetMedia($value->avater) . '" class="margin_right_ten" /><span class="bold">' . $value->username . '</span></a><a href="' . $config->uri . '/@' . $value->username . '" class="valign-wrapper ajax-time" title="' . date('c', strtotime($value->created_at)) . '">' . Time_Elapsed_String(strtotime($value->created_at)) . '</a></li>';
        }
        return array(
            'status' => 200,
            'views' => $views_html,
            'd' => $views
        );
    }
    public function get_notifications() {
        global $_BASEPATH,$_DS,$config;
        $data          = array(
            'status' => 200
        );

        $html = '';
        $theme_path = $_BASEPATH . 'themes' . $_DS . $config->theme . $_DS;

        $notifications = LoadEndPointResource('notifications');
        if ($notifications) {
            $data[ 'notifications' ] = (int) $notifications->getUnreadNotifications();
        }
        $messages = LoadEndPointResource('messages');
        if ($messages) {
            $data[ 'chatnotifications' ] = (int) $messages->getUnreadMessages();
        }

        $data['calls']    = 0;
        $data['is_call']  = 0;
        $check_calles     = CheckFroInCalls();
        if ($check_calles !== false && is_array($check_calles)) {
            $wo['incall']                 = $check_calles;
            $wo['incall']['in_call_user'] = userData($check_calles['from_id']);
            $data['calls']                = 200;
            $data['is_call']              = 1;


            $template            = $theme_path . 'partails' . $_DS . 'modals' . $_DS . 'in_call.php';
            if (file_exists($template)) {
                ob_start();
                require($template);
                $html .= ob_get_contents();
                ob_end_clean();
            }


            $data['calls_html']           = $html;
        }

        $data['audio_calls']   = 0;
        $data['is_audio_call'] = 0;
        $check_calles          = CheckFroInCalls('audio');
        if ($check_calles !== false && is_array($check_calles)) {
            $wo['incall']                 = $check_calles;
            $wo['incall']['in_call_user'] = userData($check_calles['from_id']);
            $data['audio_calls']          = 200;
            $data['is_audio_call']        = 1;

            $template            = $theme_path . 'partails' . $_DS . 'modals' . $_DS . 'in_audio_call.php';
            if (file_exists($template)) {
                ob_start();
                require($template);
                $html .= ob_get_contents();
                ob_end_clean();
            }

            $data['audio_calls_html']     = $html;
        }

        return $data;
    }
    public function open_gift_model() {
        global $db;
        if (self::ActiveUser() == NULL) {
            return array(
                'status' => 403,
                'message' => __('Forbidden')
            );
        }
        $gifts_html = '';
        $gifts      = $db->objectBuilder()->orderBy('id', 'desc')->get('gifts', null, array(
            'id',
            'media_file'
        ));
        foreach ($gifts as $key => $value) {
            $gifts_html .= '<div class="gift-data"><img src="' . GetMedia($value->media_file) . '" data-id="' . $value->id . '" class="gift"></div>';
        }
        return array(
            'status' => 200,
            'gifts' => $gifts_html
        );
    }
    public function send_gift() {
        global $db, $config;
        if (self::ActiveUser() == NULL) {
            return array(
                'status' => 403,
                'message' => __('Forbidden')
            );
        }
        $error   = '';
        $to      = 0;
        $gift_id = 0;
        if (isset($_POST)) {
            if (isset($_POST[ 'to' ]) && is_numeric($_POST[ 'to' ])) {
                $to = (int) Secure($_POST[ 'to' ]);
            } else {
                $error .= '<p>• ' . __('Missing `to` parameter.') . '</p>';
            }
            if (isset($_POST[ 'gift_id' ]) && is_numeric($_POST[ 'gift_id' ])) {
                $gift_id = (int) Secure($_POST[ 'gift_id' ]);
            } else {
                $error .= '<p>• ' . __('Missing `gift_id` parameter.') . '</p>';
            }
        }
        if ($error == '') {
            $save = $db->insert('user_gifts', array(
                'from' => self::ActiveUser()->id,
                'to' => $to,
                'gift_id' => $gift_id,
                'time' => 0
            ));
            if ($save) {
                $db->where('id', self::ActiveUser()->id)->update('users', array(
                    'balance' => $db->dec((int) $config->cost_per_gift)
                ), 1);
                $Notification = LoadEndPointResource('Notifications');
                if ($Notification) {
                    $Notification->createNotification(self::ActiveUser()->web_device_id, self::ActiveUser()->id, $to, 'send_gift', '', '/@' . self::ActiveUser()->username . '/opengift/' . $save);
                }
                return array(
                    'status' => 200,
                    'current_credit' => self::ActiveUser()->balance - (int) $config->cost_per_gift,
                    'cost_per_gift' => (int) $config->cost_per_gift,
                    'message' => __('Gift send successfully.')
                );
            } else {
                return array(
                    'status' => 400,
                    'message' => __('Gift send failed.')
                );
            }
        } else {
            return array(
                'status' => 400,
                'message' => $error
            );
        }
    }
    public function record_gift_seen() {
        global $db;
        if (self::ActiveUser() == NULL) {
            return array(
                'status' => 403,
                'message' => __('Forbidden')
            );
        }
        $gift_id = 0;
        $error   = '';
        if (isset($_POST)) {
            if (isset($_POST[ 'id' ]) && is_numeric($_POST[ 'id' ])) {
                $gift_id = (int) Secure($_POST[ 'id' ]);
            } else {
                $error .= '<p>• ' . __('Missing `id` parameter.') . '</p>';
            }
        }
        if ($error == '') {
            $updated = $db->where('id', $gift_id)->update('user_gifts', array(
                'time' => time()
            ));
            if ($updated) {
                return array(
                    'status' => 200
                );
            } else {
                return array(
                    'status' => 400
                );
            }
        } else {
            return array(
                'status' => 400,
                'message' => $error
            );
        }
    }
    public function buymore_xvisits() {
        global $db, $config;
        if (self::ActiveUser() == NULL) {
            return array(
                'status' => 403,
                'message' => __('Forbidden')
            );
        }
        $_cost  = 0;
        $userid = 0;
        $error  = '';
        if (isset($_POST[ 'uid' ]) && !empty($_POST[ 'uid' ])) {
            $userid = Secure($_POST[ 'uid' ]);
        }
        if ($userid == 0) {
            $error = '<p>• ' . __('No user ID found.') . '</p>';
        }
        $_cost = (int) $config->cost_per_xvisits;
        if( isGenderFree(self::ActiveUser()->gender) === true ){
            $_cost = 0;
        }
        if (self::ActiveUser()->balance >= $_cost) {
        } else {
            $error = '<p>• ' . __('No credit available.') . '</p>';
        }
        if ($error == '') {
            $saved = $db->where('id', $userid)->update('users', array(
                'balance' => $db->dec($_cost),
                'user_buy_xvisits' => '1',
                'xvisits_created_at' => time()
            ));
            if ($saved) {
                $_SESSION[ 'userEdited' ] = true;
                return array(
                    'status' => 200,
                    'current_credit' => self::ActiveUser()->balance - $_cost,
                    'message' => __('User buy more visits successfully.')
                );
            } else {
                $error = '<p>• ' . __('Error while buy more visits.') . '</p>';
            }
        }
        if ($error !== '') {
            return array(
                'status' => 400,
                'message' => __('Error while buy more visits.')
            );
        }
    }
    public function buymore_xmatches() {
        global $db, $config;
        if (self::ActiveUser() == NULL) {
            return array(
                'status' => 403,
                'message' => __('Forbidden')
            );
        }
        $_cost  = 0;
        $userid = 0;
        $error  = '';
        if (isset($_POST[ 'uid' ]) && !empty($_POST[ 'uid' ])) {
            $userid = Secure($_POST[ 'uid' ]);
        }
        if ($userid == 0) {
            $error = '<p>• ' . __('No user ID found.') . '</p>';
        }
        $_cost = (int) $config->cost_per_xmatche;
        if( isGenderFree(self::ActiveUser()->gender) === true ){
            $_cost = 0;
        }
        if (self::ActiveUser()->balance >= $_cost) {
        } else {
            $error = '<p>• ' . __('No credit available.') . '</p>';
        }
        if ($error == '') {
            $saved = $db->where('id', $userid)->update('users', array(
                'balance' => $db->dec($_cost),
                'user_buy_xmatches' => '1',
                'xmatches_created_at' => time()
            ));
            if ($saved) {
                $_SESSION[ 'userEdited' ] = true;
                return array(
                    'status' => 200,
                    'current_credit' => self::ActiveUser()->balance - $_cost,
                    'message' => __('User buy more matches successfully.')
                );
            } else {
                $error = '<p>• ' . __('Error while buy more matches.') . '</p>';
            }
        }
        if ($error !== '') {
            return array(
                'status' => 400,
                'message' => __('Error while buy more matches.')
            );
        }
    }
    public function buymore_xlikes() {
        global $db, $config;
        if (self::ActiveUser() == NULL) {
            return array(
                'status' => 403,
                'message' => __('Forbidden')
            );
        }
        $_cost  = 0;
        $userid = 0;
        $error  = '';
        if (isset($_POST[ 'uid' ]) && !empty($_POST[ 'uid' ])) {
            $userid = Secure($_POST[ 'uid' ]);
        }
        if ($userid == 0) {
            $error = '<p>• ' . __('No user ID found.') . '</p>';
        }
        $_cost = (int) $config->cost_per_xlike;
        if( isGenderFree(self::ActiveUser()->gender) === true ){
            $_cost = 0;
        }
        if (self::ActiveUser()->balance >= $_cost) {
        } else {
            $error = '<p>• ' . __('No credit available.') . '</p>';
        }
        if ($error == '') {
            $saved = $db->where('id', $userid)->update('users', array(
                'balance' => $db->dec($_cost),
                'user_buy_xlikes' => '1',
                'xlikes_created_at' => time()
            ));
            if ($saved) {
                $_SESSION[ 'userEdited' ] = true;
                return array(
                    'status' => 200,
                    'current_credit' => self::ActiveUser()->balance - $_cost,
                    'message' => __('User buy more likes successfully.')
                );
            } else {
                $error = '<p>• ' . __('Error while buy more likes.') . '</p>';
            }
        }
        if ($error !== '') {
            return array(
                'status' => 400,
                'message' => __('Error while buy more likes.')
            );
        }
    }
    public function delete_account() {
        global $db, $config;
        if (self::ActiveUser() == NULL) {
            return array(
                'status' => 403,
                'message' => __('Forbidden')
            );
        }
        $error                = "";
        $deleted              = false;
        $allow_delete_account = false;
        if (isset($_POST) && !empty($_POST)) {
            if (isset($_POST[ 'c_pass' ]) && empty($_POST[ 'c_pass' ])) {
                $error .= '<p>• ' . __('Current password missing.') . '</p>';
            } else {
                $currentpass     = $db->where('id', self::ActiveUser()->id)->getValue("users", "password");
                $password_result = password_verify(Secure($_POST[ 'c_pass' ]), $currentpass);
                if ($password_result == true) {
                    $allow_delete_account = true;
                } else {
                    if (!empty($_POST[ 'c_pass' ])) {
                        $error .= '<p>• ' . __('Current password is wrong, please check again.') . '</p>';
                    }
                }
            }
        }
        if ($error == '') {
            if ($allow_delete_account) {
                $d_user = LoadEndPointResource('users');
                if ($d_user) {
                    $deleted = $d_user->delete_user(self::ActiveUser()->id);
                }
                if ($deleted[ 'message' ] !== '' && $deleted[ 'is_delete' ] === false) {
                    return array(
                        'status' => 401,
                        'message' => $deleted[ 'message' ]
                    );
                } else {
                    logout(false);
                    return array(
                        'status' => 200,
                        'url' => $config->uri,
                        'message' => __('Your account deleted successfully.')
                    );
                }
            }
        }
        if ($error !== '') {
            return array(
                'status' => 401,
                'message' => $error
            );
        }
    }
    public function upload_receipt() {
        global $db, $_UPLOAD, $_DS;
        if (self::ActiveUser() == NULL) {
            return array(
                'status' => 403,
                'message' => __('Forbidden')
            );
        }
        if (!isset($_FILES) && empty($_FILES)) {
            return array(
                'status' => 403,
                'message' => __('Forbidden')
            );
        }
        $error = false;
        $files = array();
        if (!file_exists($_UPLOAD . 'photos' . $_DS . date('Y'))) {
            mkdir($_UPLOAD . 'photos' . $_DS . date('Y'), 0777, true);
        }
        if (!file_exists($_UPLOAD . 'photos' . $_DS . date('Y') . $_DS . date('m'))) {
            mkdir($_UPLOAD . 'photos' . $_DS . date('Y') . $_DS . date('m'), 0777, true);
        }
        $dir = $_UPLOAD . 'photos' . $_DS . date('Y') . $_DS . date('m');
        foreach ($_FILES as $file) {
            $ext      = pathinfo($file[ 'name' ], PATHINFO_EXTENSION);
            $key      = GenerateKey();
            $filename = $dir . $_DS . $key . '.' . $ext;
            if (move_uploaded_file($file[ 'tmp_name' ], $filename)) {
                $org_file  = $dir . $_DS . $key . '_full.' . $ext;
                $org_file  = 'upload' . $_DS . 'photos' . $_DS . date('Y') . $_DS . date('m') . $_DS . $key . '_avater.' . $ext;
                $oreginal  = new ImageThumbnail($filename);
                $oreginal->setResize(false);
                $oreginal->save($org_file);
                @unlink($filename);
                if (is_file($org_file)) {
                    $upload_s3 = UploadToS3($org_file, array(
                        'amazon' => 0
                    ));
                }
                $info                  = array();
                $info[ 'user_id' ]     = self::ActiveUser()->id;
                $info[ 'receipt_file' ]= 'upload/photos/' . date('Y') . '/' . date('m') . '/' . $key . '_avater.' . $ext;
                $info[ 'created_at' ]  = date('Y-m-d H:i:s');
                $info[ 'description' ] = (isset($_POST['description'])) ? Secure($_POST['description']) : '';
                $info[ 'price' ]       = (isset($_POST['price'])) ? Secure($_POST['price']) : '0';
                $info[ 'mode' ]        = (isset($_POST['mode'])) ? Secure($_POST['mode']) : '';
                $info[ 'approved' ]    = 0;
                $saved                 = $db->insert('bank_receipts', $info);
            } else {
                $error = true;
            }
        }
        if ($error) {
            return array(
                'status' => 503
            );
        } else {
            return array(
                'status' => 200,
                'info' => $info
            );
        }
    }
}
