<?php
class Users {
    private $_table = 'users';
    private $_requestMethod;
    private $_id;
    public function __construct($IsLoadFromLoadEndPointResource = false) {
        global $_id;
        $this->_id            = $_id;
        $this->_requestMethod = $_SERVER['REQUEST_METHOD'];
        if (isEndPointRequest()) {
            if (is_callable(array(
                $this,
                $this->_id
            ))) {
                json(call_user_func_array(array(
                    $this,
                    $this->_id
                ), array(
                    route(5)
                )));
            } else {
                if($IsLoadFromLoadEndPointResource === false) {
                    if (!empty($this->_id) && !is_numeric($this->_id) && $this->_id <= 0) {
                        json(array(
                            'message' => ucfirst($this->_table) . ' ' . __('ID cannot be empty, or character. only numbers allowed, or you have call undefined method'),
                            'code' => 400
                        ), 400);
                    } else {
                        switch ($this->_requestMethod) {
                            case 'GET':
                                break;
                            case 'POST':
                                break;
                            case 'PUT':
                                break;
                            case 'DELETE':
                                break;
                            case 'PATCH':
                                break;
                        }
                    }
                }
            }
        }
    }
    /*API*/
    public function login($username = '', $password = '') {
        global $db;
        if (isEndPointRequest()) {
            if( !isset($_POST['username']) || empty($_POST['username']) ){
                return json(array(
                    'message' => __('User name cannot be empty'),
                    'code' => 400,
                    'errors'         => array(
                        'error_id'   => '1',
                        'error_text' => 'User name cannot be empty'
                    )
                ), 400);
            }
            if( !isset($_POST['password']) || empty($_POST['password']) ){
                return json(array(
                    'message' => __('Password cannot be empty'),
                    'code' => 400,
                    'errors'         => array(
                        'error_id'   => '1',
                        'error_text' => __('Password cannot be empty')
                    )
                ), 400);
            }
            $username = Secure($_POST['username']);
            $password = Secure($_POST['password']);
        }
        if ($this->isPasswordVerifyed($username, $password)) {
            $user = $db->where('username', $username)->orWhere('email', $username)->getOne('users');
            if ($user) {
                //$db->where('id', $user['id'])->update('users',array('web_token'=>null,'web_token_created_at'=>null,'web_device'=>null));

                $data = $this->createSession($user['id'], $user);
                if ($data) {
                    $profile =  $this->get_user_profile($user['id'],array('web_token','start_up','active','web_token_created_at','verified','admin'));
                    $response = array(
                        'message' => __('Login successfully, Please wait..'),
                        'code' => 200,
                        'userProfile' => $profile,
                        'data' => array(
                            'user_id' => $user['id'],
                            'access_token' => $profile->web_token
                        )
                    );
                    if (isEndPointRequest()) {
                        unset($response['userProfile']);
                        $response['data']['user_info'] = $this->get_user_profile($user['id']);
                        unset($response['data']['user_info']->id);
                        unset($response['data']['user_info']->web_token);
                        unset($response['data']['user_info']->password);
                        unset($response['data']['user_info']->web_device);
                        $avatar = $profile->avater->avater;
                        unset($response['data']['user_info']->avater);
                        $response['data']['user_info']->avater = $avatar;
                    }
                    return json($response, 200);
                } else {
                    return json(array(
                        'message' => __('Could not save session'),
                        'code' => 400,
                        'errors'         => array(
                            'error_id'   => '3',
                            'error_text' => __('Could not save session')
                        )
                    ), 400);
                }
            } else {
                return json(array(
                    'message' => __('User Not Exist'),
                    'code' => 400,
                    'errors'         => array(
                        'error_id'   => '4',
                        'error_text' => __('User Not Exist')
                    )
                ), 400);
            }
        } else {
            return json(array(
                'message' => __('Wrong password'),
                'code' => 400,
                'errors'         => array(
                    'error_id'   => '5',
                    'error_text' => __('Wrong password')
                )
            ), 400);
        }
    }
    /*API*/
    public function register($data = array()) {
        global $config,$db;
        if (isEndPointRequest()) {
            $data = $_POST;
        }
        if (!is_array($data) && empty($data)) {
            json(array(
                'message' => __('User data unset'),
                'code' => 400,
                'errors'         => array(
                    'error_id'   => '6',
                    'error_text' => __('User data unset')
                )
            ), 400);
        }
        if (empty($data['username'])) {
            json(array(
                'message' => __('User name cannot be empty'),
                'code' => 400,
                'errors'         => array(
                    'error_id'   => '7',
                    'error_text' => __('User name cannot be empty')
                )
            ), 400);
        }
        if (strlen($data['username']) < 5 OR strlen($data['username']) > 32) {
            json(array(
                'message' => __('Username must be between 5/32'),
                'code' => 400,
                'errors'         => array(
                    'error_id'   => '8',
                    'error_text' => __('Username must be between 5/32')
                )
            ), 400);
        }
        if (!preg_match('/^[\w]+$/', $data['username'])) {
            json(array(
                'message' => __('Invalid username characters'),
                'code' => 400,
                'errors'         => array(
                    'error_id'   => '9',
                    'error_text' => __('Invalid username characters')
                )
            ), 400);
        }
        if ($this->isUsernameExists($data['username'])) {
            json(array(
                'message' => __('User Name Exists'),
                'code' => 400,
                'errors'         => array(
                    'error_id'   => '14',
                    'error_text' => __('User Name Exists')
                )
            ), 400);
            exit();
        }
        if (empty($data['email'])) {
            json(array(
                'message' => __('Email cannot be empty'),
                'code' => 400,
                'errors'         => array(
                    'error_id'   => '10',
                    'error_text' => __('Email cannot be empty')
                )
            ), 400);
        }
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            json(array(
                'message' => __('This e-mail is invalid.'),
                'code' => 400,
                'errors'         => array(
                    'error_id'   => '11',
                    'error_text' => __('This e-mail is invalid.')
                )
            ), 400);
        }
        if ($this->isEmailExists($data['email'])) {
            json(array(
                'message' => __('Email Already Exists'),
                'code' => 400,
                'errors'         => array(
                    'error_id'   => '15',
                    'error_text' => __('Email Already Exists')
                )
            ), 400);
            exit();
        }
        if (empty($data['password'])) {
            json(array(
                'message' => __('Password cannot be empty'),
                'code' => 400,
                'errors'         => array(
                    'error_id'   => '12',
                    'error_text' => __('Password cannot be empty')
                )
            ), 400);
        }
        if (strlen($data['password']) < 6) {
            json(array(
                'message' => __('Password is too short.'),
                'code' => 400,
                'errors'         => array(
                    'error_id'   => '13',
                    'error_text' => __('Password is too short.')
                )
            ), 400);
        }
        $password                     = password_hash($data['password'], PASSWORD_DEFAULT, array('cost' => 11));
        $user                         = array();
        $user['username']             = Secure($data['username']);
        $user['email']                = Secure($data['email']);
        $user['password']             = $password;
        $user['first_name']           = (ISSET($data['first_name']) ? Secure($data['first_name']) : '');
        $user['last_name']            = (ISSET($data['last_name']) ? Secure($data['last_name']) : '');
        $user['avater']               = (ISSET($data['avater']) ? Secure($data['avater']) : $config->userDefaultAvatar);
        $user['about']                = (ISSET($data['about']) ? Secure($data['about']) : '');
        $user['gender']               = (ISSET($data['gender']) ? Secure($data['gender']) : '0');
        $user['birthday']             = (ISSET($data['birthday']) ? Secure($data['birthday']) : '0000-00-00');
        $user['country']              = (ISSET($data['country_id']) ? Secure($data['country_id']) : '');
        $user['facebook']             = (ISSET($data['facebook']) ? Secure($data['facebook']) : '');
        $user['google']               = (ISSET($data['google']) ? Secure($data['google']) : '');
        $user['twitter']              = (ISSET($data['twitter']) ? Secure($data['twitter']) : '');
        $user['linkedin']             = (ISSET($data['linkedin']) ? Secure($data['linkedin']) : '');
        $user['website']              = (ISSET($data['website']) ? Secure($data['website']) : '');
        $user['instagram']            = (ISSET($data['instagram']) ? Secure($data['instagram']) : '');
        $user['language']             = (ISSET($data['language']) ? Secure($data['language']) : $config->default_language);
        $user['email_code']           = Secure(rand(1111, 9999));
        $user['src']                  = (ISSET($data['src']) ? Secure($data['src']) : 'site');
        $user['ip_address']           = (ISSET($data['ip_address']) ? Secure($data['ip_address']) : GetIpAddress());
        $user['verified']             = (ISSET($data['verified']) ? Secure($data['verified']) : (($config->emailValidation == 1) ? '0' : '1' ));
        $user['lastseen']             = time();
        $user['status']               = (ISSET($data['status']) ? Secure($data['status']) : '0');
        $user['active']               = (ISSET($data['active']) ? Secure($data['active']) : (($config->emailValidation == 1) ? '0' : ( ( $config->smtp_or_mail == 'mail' ) ? '0' : '1' ) ) );
        $user['admin']                = (ISSET($data['admin']) ? Secure($data['admin']) : '0');
        $user['type']                 = (ISSET($data['type']) ? Secure($data['type']) : 'user');
        $user['registered']           = time();
        $user['start_up']             = (ISSET($data['start_up']) ? Secure($data['start_up']) : '0');
        $user['phone_number']         = (ISSET($data['phone_number']) ? Secure($data['phone_number']) : '');
        $user['smscode']              = (ISSET($data['sms_code']) ? Secure($data['sms_code']) : Secure(rand(1111, 9999)));
        $user['is_pro']               = (ISSET($data['is_pro']) ? Secure($data['is_pro']) : '0');
        $user['pro_time']             = (ISSET($data['pro_time']) ? Secure($data['pro_time']) : '0');
        $user['pro_type']             = (ISSET($data['pro_type']) ? Secure($data['pro_type']) : '0');
        $user['timezone']             = (ISSET($data['timezone']) ? Secure($data['timezone']) : 'UTC');
        $user['balance']              = (ISSET($data['balance']) ? Secure($data['balance']) : '0');
        $user['social_login']         = (ISSET($data['social_login']) ? Secure($data['social_login']) : '0');
        $user['lat']                  = (ISSET($data['lat']) ? Secure($data['lat']) : '0');
        $user['lng']                  = (ISSET($data['lng']) ? Secure($data['lng']) : '0');
        $user['last_location_update'] = (ISSET($data['last_location_update']) ? Secure($data['last_location_update']) : '0');
        $user['online']               = '1';
        $user['privacy_show_profile_random_users'] = '1';
        $user['privacy_show_profile_match_profiles'] = '1';
        $user['created_at']           = date('Y-m-d H:i:s');
        $saved                        = $db->insert('users',$user);
        if (!$saved) {
            return json(array(
                'message' => __('Registration Failed'),
                'code' => 400,
                'errors'         => array(
                    'error_id'   => '16',
                    'error_text' => __('Registration Failed')
                )
            ), 400);
        } else {
            if (isEndPointRequest()) {
                $active           = ($config->emailValidation == 1) ? 0 : 1;
                if ($active == 0) {

                    $message_body = Emails::parse('auth/activate', array(
                        'first_name' => ($user['first_name'] !== '' ? $user['first_name'] : $user['username']),
                        'email_code' => $user['email_code']
                    ));
                    $send         = SendEmail($user['email'], __('Confirm your account'), $message_body);
                    if ($send) {
                        $response = array(
                            'code'   => 200,
                            'success_type' => 'confirm_account',
                            'message'      => __('Successfully joined.'),
                            'data'         => array(
                                'user_id'  => $saved,
                                'email' => $user['email']
                            )
                        );
                        $response['data']['user_info'] = $this->get_user_profile($saved);
                        unset($response['data']['email']);
                        $response['data']['access_token'] = $response['data']['user_info']->web_token;
                        unset($response['data']['user_info']->id);
                        unset($response['data']['user_info']->web_token);
                        unset($response['data']['user_info']->password);
                        unset($response['data']['user_info']->web_device);
                        return json($response, 200);
                    }else{
                        return json(array(
                            'message' => __('Could not send verification email'),
                            'code' => 400,
                            'errors'         => array(
                                'error_id'   => '17',
                                'error_text' => __('Could not send verification email')
                            )
                        ), 400);
                    }
                }else{
                    $jwt    = CreateLoginSession($saved);
                    if (!empty($jwt)) {

                        $response = array(
                            'code'   => 200,
                            'success_type' => 'registered',
                            'message'      => __('Successfully joined, Please wait..'),
                            'data'         => array(
                                'user_id'  => $saved,
                                'access_token' => $jwt
                            )
                        );
                        $response['data']['user_info'] = $this->get_user_profile($saved);
                        unset($response['data']['email']);
                        unset($response['data']['user_info']->id);
                        unset($response['data']['user_info']->web_token);
                        unset($response['data']['user_info']->password);
                        unset($response['data']['user_info']->web_device);
                        return json($response, 200);
                    } else {
                        return json(array(
                            'code'     => 400,
                            'errors'         => array(
                                'error_id'   => '18',
                                'error_text' => __('Error: an unknown error occurred. Please try again later')
                            )
                        ),400);
                    }
                }
            }else {
                return json(array(
                    'message' => __('Registration Success'),
                    'code' => 200,
                    'userId' => $saved,
                    'userData' => $user
                ), 200);
            }
        }
    }
    /*API*/
    public function logout(){
        global $db;
        if ( empty($_POST['access_token'])) {
            return json(array(
                'code'     => 400,
                'errors'         => array(
                    'error_id'   => '19',
                    'error_text' => __('Bad Request, Invalid or missing parameter')
                )
            ),400);
        } else {
            $s = Secure($_POST['access_token']);
            $user_id = GetUserFromSessionID($s);
            $db->where('session_id',$s);
            $_session  = $db->getValue('sessions','count(`id`)');
            if (empty($_session)) {
                return json(array(
                    'code'           => 400,
                    'errors'         => array(
                        'error_id'   => '20',
                        'error_text' => __('Error 400 - Session does not exist')
                    )
                ),400);
            } else {
                $db->where('user_id',$user_id)->where('session_id',$s)->delete('sessions');
                return json(array(
                    'code'           => 200,
                    'message'    => __('Successfully logged out')
                ),200);
            }
        }
    }
    /*API*/
    public function reset_password(){
        global $db;
        if (empty($_POST['email'])) {
            return json(array(
                'code'     => 400,
                'message' => __('No user email sent'),
                'errors'         => array(
                    'error_id'   => '21',
                    'error_text' => __('No user email sent')
                )
            ),400);
        } else{
            if (!$this->isEmailExists(Secure($_POST['email']))) {
                return json(array(
                    'code'           => 400,
                    'errors'         => array(
                        'error_id'   => '22',
                        'error_text' => __('E-mail is not exists')
                    )
                ),400);
            } else {
                $_email = $_POST['email'];
                $_email_code = Secure(rand(1111, 9999));
                $db->where('email',$_email)->update('users',array('email_code'=>$_email_code));
                $message_body = Emails::parse('auth/forget', array(
                    'first_name' => $_email,
                    'email_code' => $_email_code
                ));
                $send         = SendEmail($_email, __('Reset Password'), $message_body);
                if ($send) {
                    return json(array(
                        'code'        => 200,
                        'message' => __('A reset password link has been sent to your e-mail address')
                    ),200);
                }
            }
        }
    }
    /*API*/
    public function delete_account(){
        global $db;
        $profile = array();
        if (empty($_POST['access_token']) || empty($_POST['password']) ) {
            return json(array(
                'code'     => 400,
                'errors'         => array(
                    'error_id'   => '23',
                    'error_text' => __('Bad Request, Invalid or missing parameter')
                )
            ),400);
        } else {
            $user_id = GetUserFromSessionID(Secure($_POST['access_token']));
            $user_password = $this->get_user_profile($user_id,array('password'));
            $password_result = password_verify($_POST['password'], $user_password->password);
            if($password_result){
                $du = $this->delete_user($user_id);
                if( $du['code'] == 200 ){
                    return json(array(
                        'message' => $du['message'],
                        'code' => 200
                    ), 200);
                }else{
                    return json(array(
                        'code'     => 400,
                        'errors'         => array(
                            'error_id'   => '35',
                            'error_text' => $du['message']
                        )
                    ),400);
                }
            }else{
                return json(array(
                    'code'     => 400,
                    'errors'         => array(
                        'error_id'   => '36',
                        'error_text' => __('You enter wrong password')
                    )
                ),400);
            }
        }
    }
    /*API*/
    public function profile(){
        global $db,$config;
        $profile = array();
        if (empty($_POST['access_token']) || empty($_POST['fetch']) || empty($_POST['user_id'])) {
            return json(array(
                'code'     => 400,
                'errors'         => array(
                    'error_id'   => '23',
                    'error_text' => __('Bad Request, Invalid or missing parameter')
                )
            ),400);
        } else {
            $_owner = GetUserFromSessionID(Secure($_POST['access_token']));

            $uname = $db->where('id',$_owner)->getOne( 'users' , array('username') );
            $user_id = (int)Secure($_POST['user_id']);
            $_fetchs = Secure($_POST['fetch']);
            $_fetch = explode(',', $_fetchs);

            $profile[$user_id] = array();

            foreach ($_fetch as $key){
                if($key == 'data') {
                    if (isEndPointRequest()) {
                        $profile[$user_id] = (array)$this->get_user_profile($user_id,array('*'),true);
                    } else {
                        $result = $db->objectBuilder()->where('id', $user_id)->getOne('users');
                        unset($result->password);
                        unset($result->email_code);
                        unset($result->smscode);

                        $result->verified_final = verifiedUser($result);
                        $result->fullname = FullName($result);

                        if ($result->birthday !== '0000-00-00') {
                            $result->age = floor((time() - strtotime($result->birthday)) / 31556926);
                        } else {
                            $result->age = 0;
                        }
                        $result->lastseen_txt = get_time_ago($result->lastseen);
                        $result->lastseen_date = date('c', $result->lastseen);
                        $result->avater = GetMedia($result->avater, false);

                        if ($_owner == $user_id) {
                            $result->is_owner = true;
                            $result->is_liked = false;
                            $result->is_blocked = false;
                        } else {
                            $result->is_owner = false;
                            $result->is_liked = (bool)$db->objectBuilder()->where('user_id', $_owner)->where('like_userid', $user_id)->getValue('likes', 'count(*)');
                            $result->is_blocked = (bool)$db->objectBuilder()->where('user_id', $_owner)->where('block_userid', $user_id)->getValue('blocks', 'count(*)');
                            //Record visit
                            $saved = $db->insert('views', array('user_id' => $_owner, 'view_userid' => $user_id, 'created_at' => date('Y-m-d H:i:s')));
                            if ($saved) {
                                CreateNotification('', $_owner, $user_id, 'visit', '', '/@' . $uname['username']);
                            }
                            //Set gift seen
                            $db->where('`to`', $user_id)->update('user_gifts', array('time' => time()));
                        }
                        $profile[$user_id] = (array)$result;
                    }
                }
                if($key == 'media'){
                    $mediafiles = $db->where('user_id', $user_id)->orderBy('id', 'desc')->get('mediafiles', null, array('id','file'));
                    if ($mediafiles) {
                        $mediafilesid = 0;
                        foreach ($mediafiles as $mediafile) {
                            if($mediafile['file']) {
                                $profile[$user_id]['mediafiles'][$mediafilesid] = array();
                                $profile[$user_id]['mediafiles'][$mediafilesid]['id'] = $mediafile['id'];
                                $profile[$user_id]['mediafiles'][$mediafilesid]['full'] = GetMedia($mediafile['file'], false);
                                $profile[$user_id]['mediafiles'][$mediafilesid]['avater'] = GetMedia(str_replace('_full.', '_avater.', $mediafile['file']), false);
                                $mediafilesid++;
                            }
                        }
                    }
                }
                if($key == 'likes'){
                    $likes = $db->where('user_id', $user_id)->orderBy('id', 'desc')->get('likes', 24, array('id','like_userid','is_like','is_dislike'));
                    if ($likes) {
                        $likesid = 0;
                        foreach ($likes as $like) {
                            $profile[$user_id]['likes'][$likesid] = $like;
                            $profile[$user_id]['likes'][$likesid]['data'] = $this->get_user_profile($like['like_userid'],array('*'),true);
                            $likesid++;
                        }
                    }else{
                        $profile[$user_id]['likes'] = array();
                    }
                }
                if($key == 'blocks'){
                    $blocks = $db->where('user_id', $user_id)->orderBy('id', 'desc')->get('blocks', 24, array('id','block_userid','created_at'));
                    if ($blocks) {
                        $blocksid = 0;
                        foreach ($blocks as $block) {
                            $profile[$user_id]['blocks'][$blocksid] = $block;
                            $profile[$user_id]['blocks'][$blocksid]['data'] = $this->get_user_profile($block['block_userid'],array('*'),true);
                            $blocksid++;
                        }
                    }else{
                        $profile[$user_id]['blocks'] = array();
                    }
                }
                if($key == 'payments'){
                    $payments = $db->where('user_id', $user_id)->orderBy('id', 'desc')->get('payments', 24, array('id','amount','type','date','via','pro_plan','credit_amount'));
                    if ($payments) {
                        $paymentsid = 0;
                        foreach ($payments as $payment) {
                            $profile[$user_id]['payments'][$paymentsid] = $payment;
                            $paymentsid++;
                        }
                    }else{
                        $profile[$user_id]['payments'] = array();
                    }
                }
                if($key == 'reports'){
                    $reports = $db->where('user_id', $user_id)->orderBy('id', 'desc')->get('reports', 24, array('id','report_userid','created_at'));
                    if ($reports) {
                        $reportsid = 0;
                        foreach ($reports as $report) {
                            $profile[$user_id]['reports'][$reportsid] = $report;
                            $profile[$user_id]['reports'][$reportsid]['data'] = $this->get_user_profile($report['report_userid'],array('*'),true);
                            $reportsid++;
                        }
                    }else{
                        $profile[$user_id]['reports'] = array();
                    }
                }
                if($key == 'visits'){
                    $visits = $db->where('user_id', $user_id)->orderBy('id', 'desc')->get('views', 24, array('id','view_userid','created_at'));
                    if ($visits) {
                        $visitsid = 0;
                        foreach ($visits as $visit) {
                            $profile[$user_id]['visits'][$visitsid] = $visit;
                            $profile[$user_id]['visits'][$visitsid]['data'] = $this->get_user_profile($visit['view_userid'],array('*'),true);
                            $visitsid++;
                        }
                    }else{
                        $profile[$user_id]['visits'] = array();
                    }
                }
            }

            if( !isset( $profile[$user_id]['mediafiles'] ) ) {
                $profile[$user_id]['mediafiles'] = array();
            }
            if( !isset( $profile[$user_id]['likes'] ) ) {
                $profile[$user_id]['likes'] = array();
            }
            if( !isset( $profile[$user_id]['blocks'] ) ) {
                $profile[$user_id]['blocks'] = array();
            }
            if( !isset( $profile[$user_id]['payments'] ) ) {
                $profile[$user_id]['payments'] = array();
            }
            if( !isset( $profile[$user_id]['reports'] ) ) {
                $profile[$user_id]['reports'] = array();
            }
            if( !isset( $profile[$user_id]['visits'] ) ) {
                $profile[$user_id]['visits'] = array();
            }

            if(isEndPointRequest()){
                unset($profile[$user_id]->web_device);

                if( $profile[$user_id]->is_pro === "1" ) {
                    $lastTime = $db->objectBuilder()
                        ->where('user_id', $_owner)
                        ->where('view_userid', $user_id)
                        ->orderBy('created_at', 'DESC')
                        ->getOne('views', array('TIMESTAMPDIFF(MINUTE,views.created_at,NOW())%60 as lastTime'));
                    $can_insert = false;
                    if (isset($lastTime->lastTime) && $lastTime->lastTime > $config->profile_record_views_minute) {
                        $can_insert = true;
                    }
                    if ($lastTime === null) {
                        $can_insert = true;
                    }
                    if ($can_insert === true) {
                        if ($_owner !== $user_id) {
                            if ($_owner !== '' && $user_id !== '') {
                                $db->where('user_id', $_owner)->where('view_userid', $user_id)->delete('views');
                                $db->where('notifier_id', $_owner)->where('recipient_id', $user_id)->where('type', 'visit')->delete('notifications');
                                $saved = $db->insert('views', array('user_id' => $_owner, 'view_userid' => $user_id, 'created_at' => date('Y-m-d H:i:s')));
                                if ($saved) {
                                    $Notification = LoadEndPointResource('Notifications');
                                    if ($Notification) {
                                        $owner = $db->objectBuilder()->where('id', $_owner)->getOne('users');
                                        $Notification->createNotification($profile[$user_id]->web_device_id, $_owner, $user_id, 'visit', '', '/@' . $owner->username);
                                    }
                                }
                            }
                        }
                    }
                }

            }
            return json(array(
                'data' => $profile[$user_id],
                'message' => __('Profile fetch successfully'),
                'code' => 200
            ), 200);
        }
    }
    public function isPhoneExists($phone_number) {
        global $db;
        if (empty($phone_number)) {
            json(array(
                'message' => __('Phone number cannot be empty'),
	                'code' => 400
	            ), 400);
	        }
	        $user = $db->where('phone_number', Secure($phone_number))->getOne('users');
	        return $user;
    }
    public function isPasswordVerifyed($username, $password) {
        global $db;
        $password_result = false;
        if (empty($username)) {
            json(array(
                'message' => __('Empty username'),
                'code' => 400
            ), 400);
        }
        if (empty($password)) {
            json(array(
                'message' => __('Empty password'),
                'code' => 400
            ), 400);
        }
        $user = $db->objectBuilder()->where('username', Secure($username))->orWhere('email', Secure($username))->getOne('users');
        if (!empty($user->password)) {
            $password_result = password_verify($password, $user->password);
        }
        return $password_result;
    }
    public function createSession($user_id, $userData = array()) {
        global $db;
        $result = array();
        if (!empty($user_id) && !is_numeric($user_id) && $user_id <= 0) {
            $result = array(
                'message' => __('ID cannot be empty, or character. only numbers allowed'),
                'code' => 400
            );
        }
        $device = GetDeviceToken();
        $platform = 'web';
        if( isset($_POST['platform']) ){
            $platform = Secure($_POST['platform']);
        }
        $jwt    = CreateLoginSession($user_id,$platform);
        $_SESSION['CreateLoginSessionjwt'] = $jwt;
        try {
            if (!$this->isSessionExists($jwt, $device)) {
                $data  = array(
                    'lastseen' => time(),
                    'web_token' => $jwt,
                    'web_device' => $device,
                    'web_token_created_at' => time()
                );
                $saved = $db->where('id', Secure($user_id))->update('users', $data);
                if (!$saved) {
                    $_SESSION['userEdited'] = true;
                    return json(array(
                        'message' => __('Session add failed'),
                        'code' => 400
                    ), 400);
                } else {
                    return true;
                }
            } else {
                return true;
            }
        }
        catch (Exception $e) {
            return json(array(
                'message' => $e->getMessage(),
                'code' => 400
            ), 400);
        }
    }
    public function isEmailExists($email) {
        global $db;
        if (empty($email)) {
            json(array(
                'message' => __('Email cannot be empty'),
                'code' => 400
            ), 400);
        }
        $user = $db->where('email', Secure($email))->getOne('users');
        return $user;
    }
    public function isUsernameExists($username) {
        global $db;
        if (empty($username)) {
            json(array(
                'message' => __('Username cannot be empty'),
                'code' => 400
            ), 400);
        }
        $user = $db->where('username', Secure($username))->getOne('users',array('id'));
        return $user;
    }
    public function isSessionExists($token, $device) {
        global $db;
        if (empty($token)) {
            json(array(
                'message' => __('Token cannot be empty'),
                'code' => 400
            ), 400);
        }
        $device = GetDeviceToken();
        $user   = $db->where('web_token', Secure($token))->where('web_device', $device)->getOne('users');
        return $user;
    }
    public function GetUserByEmail($email){
        global $db;
        if(empty($email)){
            json( array( 'message' => 'Email cannot be empty', 'code' => 400 ) , 400 );
        }
        $result = $db->where('email',$email)->getOne('users');
        return $result;//get_user_profile($result->id);//
    }
    public function SetLoginWithSession($email){
        global $db;
        if (empty($email)) {
            return false;
        }
        $email          = Secure($email);
        $userData = $this->GetUserByEmail($email);
        if( !empty( $userData ) && is_array( $userData ) ){
            $user = $this->createSession($userData['id'],$userData);
            if ($user) {
                SessionStart();
                $userProfile = $this->get_user_profile($user['id'],array('web_token','start_up','active','web_token_created_at','verified'));
                $JWT = $_SESSION['CreateLoginSessionjwt'];
                if(isset($userProfile->web_token)) {
                    $JWT = $userProfile->web_token;
                }
                $_SESSION['JWT']  = $userProfile;
                $_SESSION['user_id'] = $JWT;
                setcookie( "JWT", $JWT, time() + (10 * 365 * 24 * 60 * 60));
            } else {
                return json(array(
                    'message' => __('Could not ave session'),
                    'code' => 400
                ), 400);
            }
        }else{
            return array( 'message' => __('User not found'), 'code' => 301 );
        }
    }
    public function get_user_profile($username, $cols = array(),$only_token = false) {
        global $db;
        $columns = array('*');
        if(!empty($cols)){
            $columns = $cols;
        }
        $profile_completion_fields       = array(
            'email',
            'first_name',
            'last_name',
            'avater',
            'facebook',
            'google',
            'twitter',
            'linkedin',
            'instagram',
            'phone_number',
            'birthday',
            'interest',
            'location',
            'relationship',
            'work_status',
            'education',
            'ethnicity',
            'body',
            'character',
            'children',
            'friends',
            'pets',
            'live_with',
            'car',
            'religion',
            'smoke',
            'drink',
            'travel',
            'music',
            'dish',
            'song',
            'hobby',
            'city',
            'sport',
            'book',
            'movie',
            'colour',
            'tv'
        );
        $profile_completion_fields_count = count($profile_completion_fields);
        $profile_completion_field        = 0;
        $profile_completion_value        = 0;
        $profile_completion_missing      = array();
        $profile                         = new stdClass();

        if( $only_token == true ){
            $db->where('id', $username);
        }else{
            $db->where('web_token', $username);
            $db->orWhere('username', $username);
            $db->orWhere('id', $username);
            $db->orWhere('email', $username);
        }
        $result = $db->objectBuilder()->getOne('users',$columns);
        if ($db->count > 0) {
            if( $columns[0] == "*" ) {
                foreach ($result as $key => $value) {
                    $profile->$key = trim($value);
                    $profile->verified_final = verifiedUser($result);
                    $profile->fullname = FullName($result);

                    if (in_array($key, $profile_completion_fields)) {
                        if (!empty($value)) {
                            $profile_completion_field++;
                        } else {
                            $profile_completion_missing[] = $key;
                        }
                    }
                    $data = Dataset::load($key);
                    if (isset($data) && !empty($data)) {
                        if (isset($data[$value])) {
                            $profile->{$key . '_txt'} = __($data[$value]);
                        } else {
                            $profile->{$key . '_txt'} = '';
                        }
                    }
                    if ($result->country !== '') {
                        $countries = Dataset::load('countries');
                        if (isset($countries[$result->country])) {
                            $profile->country_txt = $countries[$result->country]['name'];
                            if ($result->phone_number !== '') {
                                $profile->full_phone_number = '+' . $countries[$result->country]['isd'] . $result->phone_number;
                            }
                        }
                    } else {
                        $profile->country_txt = '-';
                    }

                    if ($result->phone_verified == 1) {
                        $profile->full_phone_number = '+' . $result->phone_number;
                    }
                    if ($result->web_token !== '') {
                        $profile->web_token = strtolower($result->web_token);
                    }
                    $profile->password = '**********************';
                    if ($result->birthday !== '0000-00-00') {
                        $profile->age = floor((time() - strtotime($result->birthday)) / 31556926);
                    } else {
                        $profile->age = 0;
                    }
                    if ($result->web_device !== '') {
                        $profile->web_device = unserialize($result->web_device);
                    }
                    $profile_completion_value = ((100 * $profile_completion_field) / $profile_completion_fields_count);
                    $profile->profile_completion = (int)$profile_completion_value;
                    $profile->profile_completion_missing = $profile_completion_missing;

                    if (isEndPointRequest()) {
                        $profile->avater = GetMedia($result->avater, false);
                    }else{
                        $profile->avater = new stdClass();
                        $profile->avater->full = GetMedia(str_replace('_avater.', '_full.', $result->avater), false);
                        $profile->avater->avater = GetMedia($result->avater, false);
                    }

                    $full_name = ucfirst(trim($result->first_name . ' ' . $result->last_name));
                    $profile->full_name = ($full_name == '') ? ucfirst(trim($result->username)) : $full_name;
                }
                $profile->lastseen_txt = get_time_ago($result->lastseen);
                $profile->lastseen_date = date('c', $result->lastseen);
                $profile->mediafiles = array();
                $mediafiles = $db->where('user_id', trim($profile->id))->orderBy('id', 'desc')->get('mediafiles', null, array('id','file','is_private','private_file'));
                if ($mediafiles) {
                    $mediafilesid = 0;
                    foreach ($mediafiles as $mediafile) {
                        $mf = array(
                            'id' => $mediafile['id'],
                            'full' => GetMedia($mediafile['file'], false),
                            'avater' => GetMedia(str_replace('_full.', '_avater.', $mediafile['file']), false),
                            'is_private' => $mediafile['is_private'],
                            'private_file_full' => GetMedia( $mediafile['private_file'], false),
                            'private_file_avater' => GetMedia(str_replace('_full.', '_avatar.', $mediafile['private_file']), false)
                        );
                        $profile->mediafiles[] = $mf;
                    }
                }
            }else{
                foreach ($result as $key => $value) {
                    if (in_array($key, $columns)) {
                        $profile->$key = trim($value);
                        $profile->verified_final = verifiedUser($result);
                        $profile->fullname = FullName($result);
                        $data = Dataset::load($key);
                        if (isset($data) && !empty($data)) {

                            if (isset($data[$value])) {
                                $profile->{$key} = $data[$value];
                            }

                            if ($result->country !== '') {
                                $countries = Dataset::load('countries');
                                if (isset($countries[$result->country])) {
                                    $profile->country = $countries[$result->country]['name'];
                                }
                            } else {
                                $profile->country = '-';
                            }

                        }
                    }

                    if( isset( $result->id ) ) {
                        $profile->mediafiles = array();
                        $mediafiles = $db->where('user_id', trim($result->id))->orderBy('id', 'desc')->get('mediafiles', null, array('id','file','is_private','private_file'));
                        if ($mediafiles) {
                            $mediafilesid = 0;
                            foreach ($mediafiles as $mediafile) {
                                $profile->mediafiles[$mediafilesid] = array();
                                $profile->mediafiles[$mediafilesid]['id'] = $mediafile['id'];
                                $profile->mediafiles[$mediafilesid]['full'] = GetMedia($mediafile['file'], false);
                                $profile->mediafiles[$mediafilesid]['avater'] = GetMedia(str_replace('_full.', '_avater.', $mediafile['file']), false);
                                $profile->mediafiles[$mediafilesid]['is_private'] = $mediafile['is_private'];
                                $profile->mediafiles[$mediafilesid]['private_full'] = GetMedia($mediafile['private_file'], false);
                                $profile->mediafiles[$mediafilesid]['private_avater'] = GetMedia(str_replace('_full.', '_avatar.', $mediafile['private_file']), false);

                                $mediafilesid++;
                            }
                        }
                    }
                }
            }

            if(isEndPointRequest()){
                unset($profile->web_device);
            }

            return $profile;
        } else {
            return false;
        }
    }
    public function ImportImageFromLogin($media, $amazon = 0) {
        global $config,$_UPLOAD,$_DS;
        if (!file_exists($_UPLOAD . 'photos' . $_DS . date('Y'))) {
            mkdir(UPLOAD . 'photos' . DIRECTORY_SEPARATOR . date('Y'), 0777, true);
        }
        if (!file_exists($_UPLOAD . 'photos' . $_DS . date('Y') . $_DS . date('m'))) {
            mkdir($_UPLOAD . '/photos' . DIRECTORY_SEPARATOR . date('Y') . DIRECTORY_SEPARATOR . date('m'), 0777, true);
        }
        $dir      = $_UPLOAD . '/photos' . $_DS . date('Y') . $_DS . date('m');
        $key      = GenerateKey();
        $file_dir = $dir . $_DS . $key . '_avatar.jpg';
        $safe_dir = 'upload/photos/' . date('Y') . '/' . date('m') . '/' . $key . '_avatar.jpg';
        $getImage = fetchDataFromURL($media);
        if (!empty($getImage)) {
            $importImage = file_put_contents($file_dir, $getImage);
            if ($importImage) {
                Resize_Crop_Image($config->profile_picture_width_crop, $config->profile_picture_height_crop, $file_dir, $file_dir, 100);
            }
        }
        if (file_exists($file_dir)) {
            UploadToS3($safe_dir, array(
                'amazon' => 0
            ));
            return $safe_dir;
        } else {
            return false;
        }
    }
    public function delete_user($user_id){
        global $db;
        $result = array();
        $img_deleted = false;
        $deleted = false;
        $error     = '';

        if (!empty($user_id) && !is_numeric($user_id) && $user_id <= 0) {
            $error .= '<p>• '.__('ID cannot be empty, or character. only numbers allowed.').'</p>';
        }
        $media_uploaded_files = array();
        $_media_files = $db->objectBuilder()->where('user_id', $user_id)->get('mediafiles',null,array('file'));
        $_chat_media_files = $db->objectBuilder()->where('`from`', $user_id)->where('media', NULL, 'IS NOT')->get('messages',null,array('media'));
        foreach ($_media_files as $key => $value){
            $media_uploaded_files[] = $value->file;
            $media_uploaded_files[] = str_replace('_full.','_avater.',$value->file);
        }
        foreach ($_chat_media_files as $key => $value){
            $media_uploaded_files[] = $value->media;
        }
        foreach ($media_uploaded_files as $key => $value){
            $img_deleted = DeleteFromToS3( $value );
        }
        $blocks_deleted = $db->where('user_id', $user_id)->orWhere('block_userid', $user_id)->delete('blocks');
        if ($blocks_deleted) {
            $deleted = true;
        } else {
            $error .= '<p>• '.__('Error while deleting "Blocks" data.').'</p>';
        }
        $conversations_deleted = $db->where('sender_id', $user_id)->orWhere('receiver_id', $user_id)->delete('conversations');
        if ($conversations_deleted) {
            $deleted = true;
        } else {
            $error .= '<p>• '.__('Error while deleting "Conversations" data.').'</p>';
        }
        $likes_deleted = $db->where('user_id', $user_id)->orWhere('like_userid', $user_id)->delete('likes');
        if ($likes_deleted) {
            $deleted = true;
        } else {
            $error .= '<p>• '.__('Error while deleting "Likes" data.').'</p>';
        }
        $mediafiles_deleted = $db->where('user_id', $user_id)->delete('mediafiles');
        if ($mediafiles_deleted) {
            $deleted = true;
        } else {
            $error .= '<p>• '.__('Error while deleting "Media files" data.').'</p>';
        }
        $messages_deleted = $db->where('`from`', $user_id)->orWhere('`to`', $user_id)->delete('messages');
        if ($messages_deleted) {
            $deleted = true;
        } else {
            $error .= '<p>• '.__('Error while deleting "Messages" data.').'</p>';
        }
        $notifications_deleted = $db->where('notifier_id', $user_id)->orWhere('recipient_id', $user_id)->delete('notifications');
        if ($notifications_deleted) {
            $deleted = true;
        } else {
            $error .= '<p>• '.__('Error while deleting "Notifications" data.').'</p>';
        }
        $reports_deleted = $db->where('user_id', $user_id)->orWhere('report_userid', $user_id)->delete('reports');
        if ($reports_deleted) {
            $deleted = true;
        } else {
            $error .= '<p>• '.__('Error while deleting "Reports" data.').'</p>';
        }
        $user_gifts_deleted = $db->where('`from`', $user_id)->orWhere('`to`', $user_id)->delete('user_gifts');
        if ($user_gifts_deleted) {
            $deleted = true;
        } else {
            $error .= '<p>• '.__('Error while deleting "Gifts" data.').'</p>';
        }
        $views_deleted = $db->where('user_id', $user_id)->orWhere('view_userid', $user_id)->delete('views');
        if ($views_deleted) {
            $deleted = true;
        } else {
            $error .= '<p>• '.__('Error while deleting "Visits" data.').'</p>';
        }
        $users_deleted = $db->where('id', $user_id)->delete('users');
        if ($users_deleted) {
            $deleted = true;
        } else {
            $error .= '<p>• '.__('Error while deleting "User" data.').'</p>';
        }
        $sessions_deleted = $db->where('user_id', $user_id)->delete('sessions');
        if ($sessions_deleted) {
            $deleted = true;
        } else {
            $error .= '<p>• '.__('Error while deleting "Sessions" data.').'</p>';
        }
        $payments_deleted = $db->where('user_id', $user_id)->delete('payments');
        if ($payments_deleted) {
            $deleted = true;
        } else {
            $error .= '<p>• '.__('Error while deleting "Payments" data.').'</p>';
        }

        if( $deleted ){
            $result = array(
                'message' => __('Your account deleted successfully.'),
                'is_delete' => $deleted,
                'img_deleted' => $img_deleted,
                'media_uploaded_files' => $media_uploaded_files,
                'code' => 200
            );
        }else {
            $result = array(
                'message' => $error,
                'is_delete' => $deleted,
                'img_deleted' => $img_deleted,
                'media_uploaded_files' => $media_uploaded_files,
                'code' => 400
            );
        }

        return $result;
    }
    /*API*/
    public function add_likes(){
        global $db;
        if (empty($_POST['likes']) || empty($_POST['access_token'])) {
            return json(array(
                'code'     => 400,
                'errors'         => array(
                    'error_id'   => '19',
                    'error_text' => __('Bad Request, Invalid or missing parameter')
                )
            ),400);
        } else {
            $user_id = (int)GetUserFromSessionID(Secure($_POST['access_token']));
            $likes = explode(',', Secure($_POST['likes']));
            $dislikes = explode(',', Secure($_POST['dislikes']));
            $inserted = false;
            if( !empty($likes) ) {
                foreach ($likes as $likekey) {
                    if(!empty($likekey) && is_numeric($likekey)){
                        $userlike = $db->objectBuilder()->where('user_id', $user_id )->where('like_userid', (int)$likekey )->where('is_like', '1' )->getValue('likes','count(*)');
                        if((int)$userlike === 0) {
                            $inserted = $db->insert('likes', array('user_id' => $user_id, 'like_userid' => (int)$likekey, 'is_like' => 1, 'is_dislike' => 0, 'created_at' => date('Y-m-d H:i:s')));
                            if ($inserted) {
                                $user_data = userData($user_id);
                                $user_data2 = userData((int)$likekey);
                                $Notification = LoadEndPointResource('Notifications');
                                if ($user_data->is_pro === "1") {
                                    $db->where('notifier_id', $user_id)->where('recipient_id', (int)$likekey)->where('type', 'like')->delete('notifications');
                                    $Notification->createNotification($user_data->web_device_id, $user_data->id, (int)$likekey, 'like', '', '/@' . $user_data->username);
                                }
                                $is_user_matches = $db->where('user_id', $user_id)->where('like_userid', (int)$likekey)->getOne('likes', array('id'));
                                if ($is_user_matches > 0) {
                                    $Notification->createNotification($user_data->web_device_id, $user_data->id, (int)$likekey, 'got_new_match', '', '/@' . $user_data->username);
                                    $Notification->createNotification($user_data->web_device_id, (int)$likekey, $user_data->id, 'got_new_match', '', '/@' . $user_data2->username);
                                }
                            }
                        }

                    }
                }
            }
            if( !empty($dislikes) ) {
                foreach ($dislikes as $dislikekey) {
                    if(!empty($dislikekey) && is_numeric($dislikekey)){
                        $userdislike = $db->objectBuilder()->where('user_id', $user_id )->where('like_userid', (int)$dislikekey )->where('is_dislike', '1' )->getValue('likes','count(*)');
                        if ((int)$userdislike === 0) {
                            $db->insert('likes', array('user_id' => $user_id, 'like_userid' => (int)$dislikekey, 'is_like' => 0, 'is_dislike' => 1, 'created_at' => date('Y-m-d H:i:s')));
                        }
                    }
                }
            }
            return json(array(
                'message' => __('Like add successfully.'),
                'code' => 200
            ), 200);
        }
    }
    /*API*/
    public function delete_like(){
        global $db;
        if (empty($_POST['user_likeid']) || empty($_POST['access_token'])) {
            return json(array(
                'code'     => 400,
                'errors'         => array(
                    'error_id'   => '19',
                    'error_text' => __('Bad Request, Invalid or missing parameter')
                )
            ),400);
        } else {
            $user_id = GetUserFromSessionID(Secure($_POST['access_token']));
            if( !is_numeric( Secure($_POST['user_likeid']) ) ){
                return json(array(
                    'code' => 400,
                    'errors' => array(
                        'error_id' => '19',
                        'error_text' => __('Bad Request, Invalid or missing parameter')
                    )
                ), 400);
            }
            $db->where('user_id', $user_id)->where('like_userid', (int)Secure($_POST['user_likeid']))->where('is_like', '1')->delete('likes');
            return json(array(
                'message' => __('Like delete successfully.'),
                'code' => 200
            ), 200);
        }
    }
    /*API*/
    public function delete_dislike(){
        global $db;
        if (empty($_POST['user_dislike']) || empty($_POST['access_token'])) {
            return json(array(
                'code'     => 400,
                'errors'         => array(
                    'error_id'   => '19',
                    'error_text' => __('Bad Request, Invalid or missing parameter')
                )
            ),400);
        } else {
            $user_id = GetUserFromSessionID(Secure($_POST['access_token']));
            if( !is_numeric( Secure($_POST['user_dislike']) ) ){
                return json(array(
                    'code' => 400,
                    'errors' => array(
                        'error_id' => '19',
                        'error_text' => __('Bad Request, Invalid or missing parameter')
                    )
                ), 400);
            }
            $db->where('user_id', $user_id)->where('like_userid', (int)Secure($_POST['user_dislike']))->where('is_dislike', '1')->delete('likes');
            return json(array(
                'message' => __('Dislike delete successfully.'),
                'code' => 200
            ), 200);
        }
    }
    /*API*/
    public function block(){
        global $db;
        if (empty($_POST['block_userid']) || empty($_POST['access_token'])) {
            return json(array(
                'code' => 400,
                'errors' => array(
                    'error_id' => '19',
                    'error_text' => __('Bad Request, Invalid or missing parameter')
                )
            ), 400);
        } else {
            if( !is_numeric( Secure($_POST['block_userid']) ) ){
                return json(array(
                    'code' => 400,
                    'errors' => array(
                        'error_id' => '19',
                        'error_text' => __('Bad Request, Invalid or missing parameter')
                    )
                ), 400);
            }
            $user_id = GetUserFromSessionID(Secure($_POST['access_token']));
            $is_exist = (bool)$db->where('user_id',$user_id)->where('block_userid', (int)Secure($_POST['block_userid']) )->getOne('blocks',array('count(*) as ct'))['ct'];
            $message = '';
            $data = array();
            if( $is_exist ){
                $db->where('user_id', $user_id)->where('block_userid', (int)Secure($_POST['block_userid']))->delete('blocks');
                $message = __('User unblocked successfully.');
            }else{

                $block_user = $db->objectBuilder()->where('user_id', $user_id )->where('block_userid', (int)Secure($_POST['block_userid']) )->getValue('blocks','count(*)');
                if((int)$block_user === 0) {
                    $saved = $db->insert('blocks', array('user_id' => $user_id, 'block_userid' => (int)Secure($_POST['block_userid']), 'created_at' => date('Y-m-d H:i:s')));
                    $message = __('User blocked successfully.');
                    $data['id'] = $saved;
                    $data['user_id'] = (int)$user_id;
                    $data['block_userid'] = (int)Secure($_POST['block_userid']);
                    $data['created_at'] = date('Y-m-d H:i:s');
                }
            }
            return json(array(
                'message' => $message,
                'code' => 200,
                'data' => $data
            ), 200);
        }
    }
    public function unblock(){
        global $db;
        if (empty($_POST['block_userid']) || empty($_POST['access_token'])) {
            return json(array(
                'code' => 400,
                'errors' => array(
                    'error_id' => '19',
                    'error_text' => __('Bad Request, Invalid or missing parameter')
                )
            ), 400);
        } else {
            if( !is_numeric( Secure($_POST['block_userid']) ) ){
                return json(array(
                    'code' => 400,
                    'errors' => array(
                        'error_id' => '19',
                        'error_text' => __('Bad Request, Invalid or missing parameter')
                    )
                ), 400);
            }
            $user_id = GetUserFromSessionID(Secure($_POST['access_token']));
            $db->where('user_id', $user_id)->where('block_userid', (int)Secure($_POST['block_userid']))->delete('blocks');
            return json(array(
                'message' => __('User unblocked successfully.'),
                'code' => 200
            ), 200);
        }
    }
    /*API*/
    public function report(){
        global $db;
        if (empty($_POST['report_userid']) || empty($_POST['access_token'])) {
            return json(array(
                'code' => 400,
                'errors' => array(
                    'error_id' => '19',
                    'error_text' => __('Bad Request, Invalid or missing parameter')
                )
            ), 400);
        } else {
            if( !is_numeric( Secure($_POST['report_userid']) ) ){
                return json(array(
                    'code' => 400,
                    'errors' => array(
                        'error_id' => '19',
                        'error_text' => __('Bad Request, Invalid or missing parameter')
                    )
                ), 400);
            }
            $user_id = GetUserFromSessionID(Secure($_POST['access_token']));
            $is_exist = (bool)$db->where('user_id',$user_id)->where('report_userid', (int)Secure($_POST['report_userid']) )->getOne('reports',array('count(*) as ct'))['ct'];
            $message = '';
            $data = array();
            if( $is_exist ){
                $db->where('user_id', $user_id)->where('report_userid', (int)Secure($_POST['report_userid']))->delete('reports');
                $message = __('User unreported successfully.');
            }else{

                $report_user = $db->objectBuilder()->where('user_id', $user_id )->where('report_userid', (int)Secure($_POST['report_userid']) )->getValue('reports','count(*)');
                if((int)$report_user === 0) {
                    $saved = $db->insert('reports', array('user_id' => $user_id, 'report_userid' => (int)Secure($_POST['report_userid']), 'created_at' => date('Y-m-d H:i:s')));
                    $message = __('User reported successfully.');
                    $data['id'] = $saved;
                    $data['user_id'] = (int)$user_id;
                    $data['report_userid'] = (int)Secure($_POST['report_userid']);
                    $data['seen'] = 0;
                    $data['created_at'] = date('Y-m-d H:i:s');
                }
            }
            return json(array(
                'message' => $message,
                'code' => 200,
                'data' => $data
            ), 200);
        }
    }
    public function unreport(){
        global $db;
        if (empty($_POST['report_userid']) || empty($_POST['access_token'])) {
            return json(array(
                'code' => 400,
                'errors' => array(
                    'error_id' => '19',
                    'error_text' => __('Bad Request, Invalid or missing parameter')
                )
            ), 400);
        } else {
            if( !is_numeric( Secure($_POST['report_userid']) ) ){
                return json(array(
                    'code' => 400,
                    'errors' => array(
                        'error_id' => '19',
                        'error_text' => __('Bad Request, Invalid or missing parameter')
                    )
                ), 400);
            }
            $user_id = GetUserFromSessionID(Secure($_POST['access_token']));
            $db->where('user_id', $user_id)->where('report_userid', (int)Secure($_POST['report_userid']))->delete('reports');
            return json(array(
                'message' => __('User unreported successfully.'),
                'code' => 200
            ), 200);
        }
    }
    public function visit(){
        global $db;
        if (empty($_POST['visit_userid']) || empty($_POST['access_token'])) {
            return json(array(
                'code' => 400,
                'errors' => array(
                    'error_id' => '19',
                    'error_text' => __('Bad Request, Invalid or missing parameter')
                )
            ), 400);
        } else {
            if( !is_numeric( Secure($_POST['visit_userid']) ) ){
                return json(array(
                    'code' => 400,
                    'errors' => array(
                        'error_id' => '19',
                        'error_text' => __('Bad Request, Invalid or missing parameter')
                    )
                ), 400);
            }
            $user_id = GetUserFromSessionID(Secure($_POST['access_token']));
            $user = $this->get_user_profile($user_id,array('username'),false);
            $saved = $db->insert('views', array('user_id' => $user_id, 'view_userid' => (int)Secure($_POST['visit_userid']), 'created_at' => date('Y-m-d H:i:s')));
            if( $saved ) {
                if( CreateNotification('',$user_id, (int)Secure($_POST['visit_userid']), 'visit', '', '/@' . $user->username) ) {
                    return json(array(
                        'message' => __('User visited successfully.'),
                        'code' => 200
                    ), 200);
                }else{
                    return json(array(
                        'code' => 400,
                        'errors' => array(
                            'error_id' => '27',
                            'error_text' => __('Could not save user visit')
                        )
                    ), 400);
                }
            }else{
                return json(array(
                    'code' => 400,
                    'errors' => array(
                        'error_id' => '27',
                        'error_text' => __('Could not save user visit')
                    )
                ), 400);
            }
        }
    }
    /*API*/
    public function list_visits(){
        global $db;
        if ( empty($_POST['access_token'])) {
            return json(array(
                'code' => 400,
                'errors' => array(
                    'error_id' => '19',
                    'error_text' => __('Bad Request, Invalid or missing parameter')
                )
            ), 400);
        }
        $limit = ( isset($_POST['limit']) && is_numeric($_POST['limit']) && (int)$_POST['limit'] > 0 ) ? (int)$_POST['limit'] : 20;
        $offset  = ( isset($_POST['offset']) && is_numeric($_POST['offset']) && (int)$_POST['offset'] > 0 ) ? (int)$_POST['offset'] : 0;
        $user_id = GetUserFromSessionID(Secure($_POST['access_token']));

        $blocked_user_array = ( array_keys(BlokedUsers($user_id)) ) ? array_keys(BlokedUsers($user_id)) : array('');
        $visits = $db->objectBuilder()
            ->join('users u', 'v.view_userid=u.id', 'LEFT')
            ->where('v.user_id', $user_id)
            ->where( 'u.verified', '1' )
            ->where( 'v.view_userid', $user_id, '<>' )
            ->where( 'v.view_userid', $blocked_user_array, 'NOT IN' )
            ->where( 'v.view_userid', $offset, '>' )
            ->groupBy('v.view_userid')
            ->orderBy('v.created_at', 'DESC')
            ->get('views v', $limit, array('u.id','u.username','u.avater','u.country','u.first_name','u.last_name','u.location','u.birthday','u.language','u.relationship','u.height','u.body','u.smoke','u.ethnicity','u.pets','v.created_at'));
        foreach ($visits as $key => $value){
            $visits[$key]->avater = GetMedia(trim($visits[$key]->avater));
        }
        return json(array(
            'data' => $visits,
            'code' => 200
        ), 200);
    }
    /*API*/
    public function send_gift(){
        global $db;
        if (empty($_POST['gift_id']) || empty($_POST['to_userid']) || empty($_POST['access_token'])) {
            return json(array(
                'code' => 400,
                'errors' => array(
                    'error_id' => '19',
                    'error_text' => __('Bad Request, Invalid or missing parameter')
                )
            ), 400);
        } else {
            if( !is_numeric( Secure($_POST['gift_id']) ) ){
                return json(array(
                    'code' => 400,
                    'errors' => array(
                        'error_id' => '19',
                        'error_text' => __('Bad Request, Invalid or missing parameter')
                    )
                ), 400);
            }
            if( !is_numeric( Secure($_POST['to_userid']) ) ){
                return json(array(
                    'code' => 400,
                    'errors' => array(
                        'error_id' => '19',
                        'error_text' => __('Bad Request, Invalid or missing parameter')
                    )
                ), 400);
            }
            $user_id = GetUserFromSessionID(Secure($_POST['access_token']));
            $user = $this->get_user_profile($user_id,array('username'),false);
            $saved = $db->insert('user_gifts', array('from' => $user_id, 'to' => (int)Secure($_POST['to_userid']),'gift_id'=> (int)Secure($_POST['gift_id']),'time'=>0));
            if( $saved ) {
                if( CreateNotification('',$user_id, (int)Secure($_POST['to_userid']), 'send_gift', '', '/@' . $user->username . '/opengift/'.$saved ) ) {
                    $userc = $db->objectBuilder()->where('id', $user_id)->getOne('users', array('balance'));
                    return json(array(
                        'credit_amount' => (int)$userc->balance,
                        'message' => __('Gift sent successfully.'),
                        'code' => 200
                    ), 200);
                }else{
                    return json(array(
                        'code' => 400,
                        'errors' => array(
                            'error_id' => '28',
                            'error_text' => __('Could not save user gift')
                        )
                    ), 400);
                }
            }else{
                return json(array(
                    'code' => 400,
                    'errors' => array(
                        'error_id' => '28',
                        'error_text' => __('Could not save user gift')
                    )
                ), 400);
            }
        }
    }
    /*API*/
    public function matches(){
        global $db;
        $match_users_data = array();
        if (empty($_POST['access_token'])) {
            return json(array(
                'code' => 400,
                'errors' => array(
                    'error_id' => '23',
                    'error_text' => __('Bad Request, Invalid or missing parameter')
                )
            ), 400);
        } else {
            $_userid = GetUserFromSessionID(Secure($_POST['access_token']));
            $limit = ( isset($_POST['limit']) && is_numeric($_POST['limit']) && (int)$_POST['limit'] > 0 ) ? (int)$_POST['limit'] : 20;
            $offset  = ( isset($_POST['offset']) && is_numeric($_POST['offset']) && (int)$_POST['offset'] > 0 ) ? (int)$_POST['offset'] : 0;

            $blocked_user_array = ( array_keys(BlokedUsers($_userid)) ) ? array_keys(BlokedUsers($_userid)) : array();
            $liked_user_array = ( array_keys(LikedUsers($_userid)) ) ? array_keys(LikedUsers($_userid)) : array();
            $disliked_user_array = ( array_keys(DisLikedUsers($_userid)) ) ? array_keys(DisLikedUsers($_userid)) : array();
            $exclude = array_merge($blocked_user_array,$liked_user_array,$disliked_user_array);
            $exclude_text = implode(',',$exclude);
            $sql = "SELECT * FROM `users` WHERE `id` > {$offset} AND (`verified` = '1' AND `privacy_show_profile_match_profiles` = '1' AND `id` NOT IN ({$exclude_text}) ) ORDER BY xlikes_created_at DESC,boosted_time DESC,is_boosted DESC,is_pro DESC LIMIT {$limit};";
            $match_users = $db->objectBuilder()->rawQuery($sql);
            foreach ($match_users as $key => $value){
                unset($match_users[$key]->access_token);
                unset($match_users[$key]->password);
                unset($match_users[$key]->web_device_id);
                unset($match_users[$key]->email_code);
                unset($match_users[$key]->src);
                unset($match_users[$key]->smscode);
                unset($match_users[$key]->pro_time);
                unset($match_users[$key]->verified);
                unset($match_users[$key]->status);
                unset($match_users[$key]->active);
                unset($match_users[$key]->admin);
                unset($match_users[$key]->start_up);
                unset($match_users[$key]->is_pro);
                unset($match_users[$key]->pro_type);

                $match_users[$key]->avater = GetMedia($match_users[$key]->avater);
                $mediafiles = $db->where('user_id', $match_users[$key]->id)->orderBy('id', 'desc')->get('mediafiles', null, array('id','file'));
                if ($mediafiles) {
                    $mediafilesid = 0;
                    foreach ($mediafiles as $mediafile) {
                        if($mediafile['file']) {
                            $match_users[$key]->mediafiles[$mediafilesid] = array();
                            $match_users[$key]->mediafiles[$mediafilesid]['id'] = $mediafile['id'];
                            $match_users[$key]->mediafiles[$mediafilesid]['full'] = GetMedia($mediafile['file'], false);
                            $match_users[$key]->mediafiles[$mediafilesid]['avater'] = GetMedia(str_replace('_full.', '_avater.', $mediafile['file']), false);
                            $mediafilesid++;
                        }
                    }
                }else{
                    $match_users[$key]->mediafiles = array();
                }
            }
            return json(array(
                'data' => $match_users,
                'code' => 200
            ), 200);
        }
    }
    /*API*/
    public function search(){
        global $db;
        if (empty($_POST['access_token'])) {
            return json(array(
                'code' => 400,
                'errors' => array(
                    'error_id' => '23',
                    'error_text' => __('Bad Request, Invalid or missing parameter')
                )
            ), 400);
        } else {
            $_userid = GetUserFromSessionID(Secure($_POST['access_token']));
            $limit = (isset($_POST['limit']) && is_numeric($_POST['limit']) && (int)$_POST['limit'] > 0) ? (int)$_POST['limit'] : 20;
            $offset = (isset($_POST['offset']) && is_numeric($_POST['offset']) && (int)$_POST['offset'] > 0) ? (int)$_POST['offset'] : 0;
            $query = GetFindMatcheQuery($_userid, $limit, $offset);
            $data = $db->objectBuilder()->rawQuery($query);
            foreach ($data as $key => $result){

                unset($result->password);
                $result->avater = GetMedia($result->avater, false);
	                $result->verified_final = verifiedUser($result);
	                $result->fullname = FullName($result);
	                if( isset( $result->id ) ) {
                    	                    $result->mediafiles = array();
	                    $mediafiles = $db->where('user_id', trim($result->id))->orderBy('id', 'desc')->get('mediafiles', 5, array('file'));
	                    if ($mediafiles) {
                        	                        $mediafilesid = 0;
	                        foreach ($mediafiles as $mediafile) {
                            	                            $result->mediafiles[$mediafilesid] = array();
	                            $result->mediafiles[$mediafilesid]['full'] = GetMedia($mediafile['file'], false);
	                            $result->mediafiles[$mediafilesid]['avater'] = GetMedia(str_replace('_full.', '_avater.', $mediafile['file']), false);
	                            $mediafilesid++;
	                        }
	                    }
	                }

            }
            return json(array(
                'data' => $data,
                'message' => __('Search fetch successfully'),
                'code' => 200
            ), 200);
        }
    }
    public function change_password(){
        global $db;
        if (empty($_POST['access_token'])) {
            return json(array(
                'code' => 400,
                'errors' => array(
                    'error_id' => '23',
                    'error_text' => __('Bad Request, Invalid or missing parameter')
                )
            ), 400);
            exit();
        }
        if (isset($_POST) && !empty($_POST)) {
            if(!isset($_POST[ 'n_pass' ]) || !isset($_POST[ 'cn_pass' ]) || !isset($_POST['c_pass'])){
                return json(array(
                    'code' => 400,
                    'errors' => array(
                        'error_id' => '23',
                        'error_text' => __('Missing New password.')
                    )
                ), 400);
                exit();
            }
            if ($_POST[ 'n_pass' ] !== $_POST[ 'cn_pass' ]) {
                return json(array(
                    'code' => 400,
                    'errors' => array(
                        'error_id' => '23',
                        'error_text' => __('Passwords Don\'t Match.')
                    )
                ), 400);
                exit();
            }
            if (isset($_POST[ 'n_pass' ]) && empty($_POST[ 'n_pass' ])) {
                return json(array(
                    'code' => 400,
                    'errors' => array(
                        'error_id' => '23',
                        'error_text' => __('Missing New password.')
                    )
                ), 400);
                exit();
            }
            if (isset($_POST[ 'c_pass' ]) && empty($_POST[ 'c_pass' ])) {
                return json(array(
                    'code' => 400,
                    'errors' => array(
                        'error_id' => '23',
                        'error_text' => __('Missing New password.')
                    )
                ), 400);
                exit();
            }
            if (!empty($_POST[ 'n_pass' ]) && strlen($_POST[ 'n_pass' ]) < 6) {
                return json(array(
                    'code' => 400,
                    'errors' => array(
                        'error_id' => '23',
                        'error_text' => __('Password is too short.')
                    )
                ), 400);
                exit();
            }

            $_userid = GetUserFromSessionID(Secure($_POST['access_token']));
            $currentpass     = $db->where('id', $_userid)->getValue("users", "password");
            $password_result = password_verify(Secure($_POST[ 'c_pass' ]), $currentpass);
            if ($password_result == false) {
                if (!empty($_POST[ 'c_pass' ])) {
                    return json(array(
                        'code' => 400,
                        'errors' => array(
                            'error_id' => '23',
                            'error_text' => __('Current password is wrong, please check again.')
                        )
                    ), 400);
                }
            }

            $_new_password = password_hash(Secure($_POST[ 'n_pass' ]), PASSWORD_DEFAULT, array(
                'cost' => 11
            ));
            $updated       = $db->where('id', $_userid)->update('users', array(
                'password' => $_new_password
            ));
            if ($updated) {
                return json(array(
                    'data' => __('Password updated successfully.'),
                    'code' => 200
                ), 200);
            }else{
                return json(array(
                    'code' => 400,
                    'errors' => array(
                        'error_id' => '23',
                        'error_text' => __('Bad Request, Invalid or missing parameter')
                    )
                ), 400);
            }


        }


    }
    /*API*/
    public function update_profile(){
        global $db;
        if (empty($_POST['access_token'])) {
            return json(array(
                'code' => 400,
                'errors' => array(
                    'error_id' => '23',
                    'error_text' => __('Bad Request, Invalid or missing parameter')
                )
            ), 400);
        } else {
            $_userid = GetUserFromSessionID(Secure($_POST['access_token']));

            unset($_POST['access_token']);
            unset($_POST['username']);
            unset($_POST['email']);
            unset($_POST['password']);
            unset($_POST['avater']);
            unset($_POST['web_device_id']);
            unset($_POST['email_code']);
            unset($_POST['src']);
            unset($_POST['smscode']);
            unset($_POST['pro_time']);
            unset($_POST['balance']);
            //unset($_POST['verified']);
            unset($_POST['status']);
            unset($_POST['active']);
            unset($_POST['admin']);
            unset($_POST['start_up']);
            unset($_POST['is_pro']);
            unset($_POST['pro_type']);

            $_Secure_Post = array();
            foreach ($_POST as $key => $value){
                $_Secure_Post[$key] = Secure($_POST[$key]);
            }

            if( !empty($_Secure_Post) ) {
                $saved = $db->where('id', $_userid)->update('users', $_Secure_Post);
                if ($saved) {
                    return json(array(
                        'data' => __('Profile updated successfully.'),
                        'code' => 200
                    ), 200);
                } else {
                    return json(array(
                        'code' => 400,
                        'errors' => array(
                            'error_id' => '31',
                            'error_text' => __('Can not update profile.')
                        )
                    ), 400);
                }
            }else{
                return json(array(
                    'code' => 400,
                    'errors' => array(
                        'error_id' => '23',
                        'error_text' => __('Bad Request, Invalid or missing parameter')
                    )
                ), 400);
            }
        }
    }
    /*API*/
    public function update_avater(){
        global $db,$_UPLOAD,$_DS,$config;
        if (empty($_FILES['avater']) || empty($_POST['access_token'])) {
            return json(array(
                'code' => 400,
                'errors' => array(
                    'error_id' => '23',
                    'error_text' => __('Bad Request, Invalid or missing parameter')
                )
            ), 400);
        } else {
            if (!isset($_FILES) && empty($_FILES)) {
                return json(array(
                    'code' => 400,
                    'errors' => array(
                        'error_id' => '23',
                        'error_text' => __('Bad Request, Invalid or missing parameter')
                    )
                ), 400);
            }
            $_userid = GetUserFromSessionID(Secure($_POST['access_token']));
            $file  = '';
            if (!file_exists($_UPLOAD . 'photos' . $_DS . date('Y'))) {
                mkdir($_UPLOAD . 'photos' . $_DS . date('Y'), 0777, true);
            }
            if (!file_exists($_UPLOAD . 'photos' . $_DS . date('Y') . $_DS . date('m'))) {
                mkdir($_UPLOAD . 'photos' . $_DS . date('Y') . $_DS . date('m'), 0777, true);
            }
            $dir = $_UPLOAD . 'photos' . $_DS . date('Y') . $_DS . date('m');
            $ext = pathinfo($_FILES['avater']['name'], PATHINFO_EXTENSION);
            $key = GenerateKey();
            $filename = $dir . $_DS . $key . '.' . $ext;
            if (move_uploaded_file($_FILES['avater']['tmp_name'], $filename)) {
                $thumbfile = 'upload' . $_DS . 'photos' . $_DS .  date('Y') . $_DS . date('m') . $_DS . $key . '_avater.' . $ext;
                $thumbnail = new ImageThumbnail($filename);
                $thumbnail->setSize($config->profile_picture_width_crop, $config->profile_picture_height_crop);
                $thumbnail->save($thumbfile);
                @unlink($filename);
                if (is_file($thumbfile)) {
                    $upload_s3 = UploadToS3($thumbfile, array(
                        'amazon' => 0
                    ));
                }
                $files_name = 'upload/photos/' . date('Y') . '/' . date('m') . '/' . $key . '_avater.' . $ext;
                $saved = $db->where('id', $_userid)->update('users',array('avater'=>$files_name));
                if($saved){
                    return json(array(
                        'data' => __('Profile avatar updated successfully.'),
                        'code' => 200
                    ), 200);
                }else{
                    return json(array(
                        'code' => 400,
                        'errors' => array(
                            'error_id' => '23',
                            'error_text' => __('Bad Request, Invalid or missing parameter')
                        )
                    ), 400);
                }
            }else{
                return json(array(
                    'code' => 400,
                    'errors' => array(
                        'error_id' => '33',
                        'error_text' => __('Can not upload avatar file.')
                    )
                ), 400);
            }
        }
    }
    /*API*/
    public function social_login(){
        global $db,$con;
        if (empty($_POST['access_token']) || empty($_POST['provider']) ) {
            return json(array(
                'code'     => 400,
                'errors'         => array(
                    'error_id'   => '23',
                    'error_text' => __('Bad Request, Invalid or missing parameter')
                )
            ),400);
        } else {
            $social_id          = 0;
            $access_token       = Secure($_POST['access_token']);
            $provider           = Secure($_POST['provider']);
            if ($provider == 'facebook') {
                $get_user_details = fetchDataFromURL("https://graph.facebook.com/me?fields=email,id,name,age_range&access_token={$access_token}");
                $json_data = json_decode($get_user_details);
                if (!empty($json_data->error)) {
                    $error_code    = 4;
                    $error_message = $json_data->error->message;
                } else if (!empty($json_data->id)) {
                    $social_id = $json_data->id;
                    $social_email = $json_data->email;
                    $social_name = $json_data->name;
                    if (empty($social_email)) {
                        $social_email = 'fb_' . $social_id . '@facebook.com';
                    }
                }

            }
            else if ($provider == 'google') {
                if (empty($_POST['google_key'])) {
                    $error_code    = 5;
                    $error_message = __('google_key (POST) is missing');
                } else {
                    $app_key = $_POST['google_key'];
                    $get_user_details = fetchDataFromURL("https://www.googleapis.com/plus/v1/people/me?access_token={$access_token}&key={$app_key}");
                    $json_data = json_decode($get_user_details);
                    if (!empty($json_data->error)) {
                        $error_code    = 4;
                        $error_message = $json_data->error;
                    } else if (!empty($json_data->id)) {
                        $social_id = $json_data->id;
                        $social_email = $json_data->emails[0]->value;
                        $social_name = $json_data->displayName;
                        if (empty($social_email)) {
                            $social_email = 'go_' . $social_id . '@google.com';
                        }
                    }
                }
            }


            if (!empty($social_id)) {
                $create_session = false;
                if (isset($this->isEmailExists($social_email)['email'])) {
                    $create_session = true;
                } else {
                    $str          = md5(microtime());
                    $id           = substr($str, 0, 9);
                    $user_uniq_id = ($this->isUsernameExists($id) === false) ? $id : 'u_' . $id;
                    $password = rand(1111, 9999);
                    $re_data      = array(
                        'username' => Secure($user_uniq_id, 0),
                        'email' => Secure($social_email, 0),
                        'password' => Secure(md5($password), 0),
                        'email_code' => Secure(md5(time()), 0),
                        'first_name' => Secure($social_name),
                        'src' => Secure($provider),
                        'lastseen' => time(),
                        'social_login' => 1,
                        'active' => '1'
                    );
                    $_POST['username'] = $re_data['username'];
                    $_POST['password'] = $re_data['password'];
                    $_POST['email'] = $re_data['email'];
                    if ($this->register($re_data) === true) {
                        $create_session = true;
                    }
                }



                if ($create_session == true) {
                    $user_id        = $this->GetUserByEmail($social_email);
                    $jwt    = CreateLoginSession($user_id);

                    return json(array(
                        'message' => __('Login Success'),
                        'code' => 200,

                        'data' => array(
                            'user_id' => (int)$user_id['id'],
                            'access_token' => $jwt,
                            'user_info' => $this->get_user_profile($user_id['id'])
                        )
                    ), 200);
                }
            }else{
                return json(array(
                    'code'     => 400,
                    'errors'         => array(
                        'error_id'   => '89',
                        'error_text' => __('Empty social id')
                    )
                ),400);
            }
        }
    }
    /*API*/
    public function pay_stripe(){
        global $db, $config;
        $stripe = array(
            'secret_key' => $config->stripe_secret,
            'publishable_key' => $config->stripe_id
        );
        \Stripe\Stripe::setApiKey($stripe['secret_key']);
        if (empty($_POST['access_token']) || empty($_POST['stripe_token']) || empty($_POST['pay_type']) || empty($_POST['description']) || empty($_POST['price']) ) {
            return json(array(
                'code' => 400,
                'errors' => array(
                    'error_id' => '23',
                    'error_text' => __('Bad Request, Invalid or missing parameter')
                )
            ), 400);
        } else {
            $_userid        = GetUserFromSessionID(Secure($_POST['access_token']));
            $product        = Secure($_POST['description']);
            $realprice      = Secure($_POST['price']);
            $price          = Secure($_POST['price']) * 100;
            $amount         = 0;
            $currency       = strtolower($config->currency);
            $payType        = Secure($_POST['pay_type']);
            $membershipType = 0;
            $token          = $_POST['stripe_token'];
            if ($payType == 'credits') {
                if ($realprice == $config->bag_of_credits_price) {
                    $amount = $config->bag_of_credits_amount;
                } else if ($realprice == $config->box_of_credits_price) {
                    $amount = $config->box_of_credits_amount;
                } else if ($realprice == $config->chest_of_credits_price) {
                    $amount = $config->chest_of_credits_amount;
                }
            } else if ($payType == 'membership') {
                if ($realprice == $config->weekly_pro_plan) {
                    $membershipType = 1;
                } else if ($realprice == $config->monthly_pro_plan) {
                    $membershipType = 2;
                } else if ($realprice == $config->yearly_pro_plan) {
                    $membershipType = 3;
                } else if ($realprice == $config->lifetime_pro_plan) {
                    $membershipType = 4;
                }
            }
            try {
                $customer = \Stripe\Customer::create(array(
                    'source' => $token
                ));
                $charge   = \Stripe\Charge::create(array(
                    'customer' => $customer->id,
                    'amount' => $price,
                    'currency' => $currency
                ));
                if ($charge) {
                    $user = $db->objectBuilder()
                        ->where('id', $_userid)
                        ->getOne('users',array('balance'));
                    $data['status']   = 200;
                    $data['message']  = __('Payment successfully');
                    if ($payType == 'credits') {
                        $newbalance = intval($user->balance) + intval($amount);
                        $updated    = $db->where('id',$_userid)->update('users',array('balance'=>$newbalance));
                        if ($updated) {
                            $db->insert('payments',array('user_id'=>$_userid,'amount'=>$price/100,'type'=>'CREDITS','date'=>date('Y-m-d H:i:s'),'pro_plan'=>'0','Credit_amount'=>$amount,'via'=>'Stripe'));
                            return json(array(
                                'code' => 200,
                                'message' => __('Payment processed successfully'),
                                'credit_amount' => intval($newbalance)
                            ), 200);
                        } else {
                            return json(array(
                                'code' => 400,
                                'errors' => array(
                                    'error_id' => '231',
                                    'error_text' => __('Error While update balance after charging')
                                )
                            ), 400);
                        }
                    } else if ($payType == 'membership') {
                        $protime = time();
                        $is_pro = "1";
                        $pro_type = $membershipType;
                        $updated        = $db->where('id',$_userid)->update('users',array('pro_time'=>$protime,'is_pro'=>$is_pro,'pro_type'=>$pro_type));
                        if ($updated) {
                            $db->insert('payments',array('user_id'=>$_userid,'amount'=>$price/100,'type'=>'PRO','date'=>date('Y-m-d H:i:s'),'pro_plan'=>$membershipType,'Credit_amount'=>'0','via'=>'Stripe'));
                            SuperCache::cache('pro_users')->destroy();
                            return json(array(
                                'code' => 200,
                                'message' => __('Payment processed successfully')
                            ), 200);
                        } else {
                            return json(array(
                                'code' => 400,
                                'errors' => array(
                                    'error_id' => '231',
                                    'error_text' => __('Error While update balance after charging')
                                )
                            ), 400);
                        }
                    }
                    return $data;
                } else {
                    return json(array(
                        'code' => 400,
                        'errors' => array(
                            'error_id' => '230',
                            'error_text' => __('Error While Payment process')
                        )
                    ), 400);
                }
            }
            catch (Exception $e) {
                return json(array(
                    'code' => 400,
                    'errors' => array(
                        'error_id' => '230',
                        'error_text' => $e->getMessage()
                    )
                ), 400);
            }
        }
    }
    /*API*/
    public function delete_media_file(){
        global $db, $_UPLOAD, $_DS, $config;
        if (empty($_POST['id']) || empty($_POST['access_token'])) {
            return json(array(
                'code' => 400,
                'errors' => array(
                    'error_id' => '23',
                    'error_text' => __('Bad Request, Invalid or missing parameter')
                )
            ), 400);
        } else {

            $_userid = GetUserFromSessionID(Secure($_POST['access_token']));
            $avatarid = Secure($_POST['id']);

            $url = $db->where('user_id', $_userid)->where('id', $avatarid)->getValue('mediafiles', 'file');
            $db->where('id', $avatarid)->where('user_id', $_userid)->delete('mediafiles');
            $avater_file = str_replace('_full.', '_avater.', $url);
            DeleteFromToS3($url);
            DeleteFromToS3($avater_file);
            return array(
                'status' => 200,
                'message' => __('File deleted successfully')
            );

        }
    }
    /*API*/
    public function upload_media_file() {
        global $db,$_UPLOAD,$_DS,$config;
        if (empty($_FILES['avater']) || empty($_POST['access_token'])) {
            return json(array(
                'code' => 400,
                'errors' => array(
                    'error_id' => '23',
                    'error_text' => __('Bad Request, Invalid or missing parameter')
                )
            ), 400);
        } else {

            $_userid = GetUserFromSessionID(Secure($_POST['access_token']));
            $file  = '';

            if (!file_exists($_UPLOAD . 'photos' . $_DS . date('Y'))) {
                mkdir($_UPLOAD . 'photos' . $_DS . date('Y'), 0777, true);
            }
            if (!file_exists($_UPLOAD . 'photos' . $_DS . date('Y') . $_DS . date('m'))) {
                mkdir($_UPLOAD . 'photos' . $_DS . date('Y') . $_DS . date('m'), 0777, true);
            }
            $dir = $_UPLOAD . 'photos' . $_DS . date('Y') . $_DS . date('m');
            $ext = pathinfo($_FILES['avater']['name'], PATHINFO_EXTENSION);
            $key = GenerateKey();
            $filename = $dir . $_DS . $key . '.' . $ext;
            if (move_uploaded_file($_FILES['avater']['tmp_name'], $filename)) {
                $thumbfile = 'upload/photos/' . date('Y') . '/' . date('m') . '/' . $key . '_avater.' . $ext;
                $org_file  = 'upload/photos/' . date('Y') . '/' . date('m') . '/' . $key . '_full.' . $ext;
                $oreginal  = new ImageThumbnail($filename);
                $oreginal->setResize(false);
                $oreginal->save($org_file);
                $thumbnail = new ImageThumbnail($filename);
                $thumbnail->setSize($config->profile_picture_width_crop, $config->profile_picture_height_crop);
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
                $media[ 'user_id' ]    = $_userid;
                $media[ 'file' ]       = 'upload/photos/' . date('Y') . '/' . date('m') . '/' . $key . '_full.' . $ext;
                $media[ 'created_at' ] = date('Y-m-d H:i:s');
                $saved                 = $db->insert('mediafiles', $media);

                if($saved){
                    return json(array(
                        'data' => __('Profile avatar uploaded successfully.'),
                        'id' => $saved,
                        'code' => 200
                    ), 200);
                }else{
                    return json(array(
                        'code' => 400,
                        'errors' => array(
                            'error_id' => '23',
                            'error_text' => __('Bad Request, Invalid or missing parameter')
                        )
                    ), 400);
                }
            }else{
                return json(array(
                    'code' => 400,
                    'errors' => array(
                        'error_id' => '33',
                        'error_text' => __('Can not upload avatar file.')
                    )
                ), 400);
            }
        }
    }
    /*API*/
    public function manage_popularity() {
        global $db,$config;
        $data = array();
        if (empty($_POST['access_token']) || empty($_POST['type']) ) {
            return json(array(
                'code'     => 400,
                'errors'         => array(
                    'error_id'   => '23',
                    'error_text' => __('Bad Request, Invalid or missing parameter')
                )
            ),400);
        } else {
            $type = Secure($_POST['type']);
            $user_id = GetUserFromSessionID(Secure($_POST['access_token']));
            $balance = intval($db->where('id',$user_id)->getValue('users','balance'));
            $ispro = (bool)($db->where('id',$user_id)->getValue('users','is_pro'));
            $amount = 0;
            if( $type == 'visits' ){
                $amount = intval($config->cost_per_xvisits);
                $data = array(
                    'balance' => $db->dec($amount),
                    'user_buy_xvisits' => '1',
                    'xvisits_created_at' => time()
                );
            }
            if( $type == 'likes' ){
                $amount = intval($config->cost_per_xlike);
                $data = array(
                    'balance' => $db->dec($amount),
                    'user_buy_xlikes' => '1',
                    'xlikes_created_at' => time()
                );
            }
            if( $type == 'boost' || $type == 'matches' ){
                if( $ispro == 1 ){
                    $amount = intval($config->pro_boost_me_credits_price);
                }else{
                    $amount = intval($config->normal_boost_me_credits_price);
                }
                $data = array(
                    'is_boosted' => '1',
                    'boosted_time' => time(),
                    'balance' => $db->dec($amount)
                );
            }

            if( $amount > $balance ){
                return json(array(
                    'code'     => 400,
                    'errors'         => array(
                        'error_id'   => '23',
                        'error_text' => __('No credit available.')
                    )
                ),400);
            }

            $saved = $db->where('id', $user_id)->update('users', $data);
            if ($saved) {
                return array(
                    'status' => 200,
                    'credit_amount' => $balance - $amount,
                    'message' => __('User boosted successfully.')
                );
            } else {
                return json(array(
                    'code'     => 400,
                    'errors'         => array(
                        'error_id'   => '23',
                        'error_text' => __('Error while boost user.')
                    )
                ),400);
            }

        }
    }
    /*API*/
    public function set_credit(){
        global $db;
        if (empty($_POST['access_token']) || empty($_POST['credits']) || empty($_POST['price']) || empty($_POST['via'])) {
            return json(array(
                'code' => 400,
                'errors' => array(
                    'error_id' => '23',
                    'error_text' => __('Bad Request, Invalid or missing parameter')
                )
            ), 400);
        } else {
            $via = Secure($_POST['via']);
            $credits = intval(Secure($_POST['credits']));
            $price = intval(Secure($_POST['price']));
            $_owner = GetUserFromSessionID(Secure($_POST['access_token']));
            $updated    = $db->where('id', $_owner)->update('users', array(
                'balance' => $db->inc($credits)
            ));
            if ($updated) {
                $db->insert('payments', array(
                    'user_id' => $_owner,
                    'amount' => $price,
                    'type' => 'CREDITS',
                    'pro_plan' => '0',
                    'credit_amount' => $credits,
                    'via' => $via
                ));
                $user = $db->where('id',$_owner)->getOne( 'users' , array('balance') );
                return json(array(
                    'message' => 'success',
                    'code' => 200,
                    'balance' => $user['balance']
                ), 200);
            } else {
                return json(array(
                    'code' => 400,
                    'errors' => array(
                        'error_id' => '23',
                        'error_text' => __('Bad Request, Invalid or missing parameter')
                    )
                ), 400);
            }
        }
    }
    /*API*/
    public function set_pro(){
        global $db;
        if (empty($_POST['access_token']) || empty($_POST['type']) || empty($_POST['price']) || empty($_POST['via'])) {
            return json(array(
                'code' => 400,
                'errors' => array(
                    'error_id' => '23',
                    'error_text' => __('Bad Request, Invalid or missing parameter')
                )
            ), 400);
        } else {
            $via = Secure($_POST['via']);
            $membershipType = intval(Secure($_POST['type']));
            $price = intval(Secure($_POST['price']));
            $_owner = GetUserFromSessionID(Secure($_POST['access_token']));
            $updated  = $db->where('id', $_owner)->update('users', array(
                'pro_time' => time(),
                'is_pro' => "1",
                'pro_type' => $membershipType
            ));
            if ($updated) {
                $db->insert('payments', array(
                    'user_id' => $_owner,
                    'amount' => $price,
                    'type' => 'PRO',
                    'pro_plan' => $membershipType,
                    'credit_amount' => '0',
                    'via' => $via
                ));
                return json(array(
                    'message' => 'success',
                    'code' => 200
                ), 200);
            } else {
                return json(array(
                    'code' => 400,
                    'errors' => array(
                        'error_id' => '23',
                        'error_text' => __('Bad Request, Invalid or missing parameter')
                    )
                ), 400);
            }
        }
    }
    /*API*/
    public function get_pro(){
        global $db;
        if (empty($_POST['access_token']) || empty($_POST['limit'])) {
            return json(array(
                'code' => 400,
                'errors' => array(
                    'error_id' => '23',
                    'error_text' => __('Bad Request, Invalid or missing parameter')
                )
            ), 400);
        } else {
            $data = [];
            $limit = intval($_POST['limit']);
            $pro_users = $db->objectBuilder()
                ->where( 'verified', '1' )
                ->where( 'is_pro', '1' )
                ->orWhere( 'is_boosted', '1' )
                ->orderBy('boosted_time','DESC')
                ->orderBy('is_pro','DESC')
                ->orderBy( 'pro_time', 'desc' )
                ->get('users',$limit,array('id','username','avater','active','is_pro'));
            foreach ($pro_users as $key => $value ){
                if( !isUserInDisLikeList($value->username) ){
                    $data[] = $this->get_user_profile($value->id,array('*'),true);
                }
            }
            return json(array(
                'data' => $data,
                'message' => __('Search fetch successfully'),
                'code' => 200
            ), 200);
        }
    }
}
