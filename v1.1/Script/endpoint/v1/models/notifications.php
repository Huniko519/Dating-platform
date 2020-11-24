<?php
class Notifications {
    private $_table = 'messages';
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
                if ($IsLoadFromLoadEndPointResource === false) {
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
    public function createNotification($token, $notifier_id, $recipient_id, $type, $text, $url) {
        global $db, $config;
        $is_exist = $db->where('notifier_id',$notifier_id)->where('recipient_id',$recipient_id)->where('type',$type)->getOne('notifications');
        if($is_exist['id'] !== NULL){
            $db->where('notifier_id',$notifier_id)->where('recipient_id',$recipient_id)->where('type',$type)->delete('notifications');
        }
        $notification                 = array();
        $notification['notifier_id']  = $notifier_id;
        $notification['recipient_id'] = $recipient_id;
        $notification['type']         = $type;
        $notification['text']         = $text;
        $notification['url']          = $url;
        $notification['full_url']     = '';
        $notification['created_at']   = strtotime(date('Y-m-d H:i:s'));
        $saved                        = $db->insert('notifications', $notification);
        if ($saved) {
            if ($token !== '') {
                $notification_object               = array();
                $notification_object['id']         = $saved;
                $notification_object['player_ids'] = array(
                    $token
                );
                $notification_object['type']       = $type;
                $notification_object['url']        = $config->uri . $url;
                $notification_text                 = Dataset::load('notification');
                if (isset($notification_text[$type])) {
                    $notification_object['contents'] = $notification_text[$type];
                }
                sendOneSignalPush($notification_object);
            }
            return array(
                'message' => __('Notification saved successfully'),
                'code' => 200
            );
        } else {
            return array(
                'message' => __('Error While saving notification'),
                'code' => 400
            );
        }
    }
    public function htmlNotification($notification) {
        global $db, $config, $_BASEPATH, $_DS;
        $site_url   = $config->uri;
        $theme_url  = $config->uri . '/themes/' . $config->theme . '/';
        $theme_path = $_BASEPATH . 'themes' . $_DS . $config->theme . $_DS;
        $username   = '';
        $avater     = '';
        $user       = $db->objectBuilder()->where('id', $notification->notifier_id)->getOne('users', array(
            'username',
            'avater',
            'first_name',
            'last_name'
        ));
        if ($user) {
            if($user->first_name == '' && $user->last_name == '') {
                $username = $user->username;
            }else if($user->first_name !== '' && $user->last_name == '') {
                $username = $user->first_name;
            }else if($user->first_name == '' && $user->last_name !== '') {
                $username = $user->last_name;
            }else{
                $username = $user->first_name . ' ' . $user->last_name;
            }
            $avater   = GetMedia($user->avater);
        }
        $text              = '';
        $notification_text = Dataset::load('notification');
        if (isset($notification_text[$notification->type])) {
            $text = __($notification_text[$notification->type]);
        }
        $text = str_replace('%d',$notification->text, $text);
        $style = '';
        if ($notification->type == 'got_new_match') {
            $style = 'style="display:none;"';
        }
        $html = '';
        if (file_exists($theme_path . 'main' . $_DS . 'notification.php')) {
            ob_start();
            require($theme_path . 'main' . $_DS . 'notification.php');
            $html = ob_get_contents();
            ob_end_clean();
        }
        return $html;
    }
    public function setNotificationSeen($id, $userid) {
        global $db;
        if (empty($id) || !is_numeric($id)) {
            return false;
        }
        $notification = $db->where('id', $id)->where('recipient_id', $userid)->update('notifications', array(
            'seen' => time()
        ));
        if ($notification) {
            return true;
        } else {
            return false;
        }
    }
    public function getUnreadNotifications() {
        global $db;
        $blocked_user_array = array();
        $blocked_user_array = (array_keys(BlokedUsers())) ? array_keys(BlokedUsers()) : array(
            ''
        );
        $notifications      = $db->objectBuilder()->where('recipient_id', auth()->id)->where('seen', 0)->where('notifier_id', $blocked_user_array, 'NOT IN')->getValue('notifications', 'COUNT( (SELECT IF(`notifications`.`type` = \'like\', (SELECT IF((SELECT is_pro from users where users.id = `notifications`.`recipient_id`) = 1, 1, 0)), 1)) )');
        return $notifications;
    }
    /*API*/
    public function get_notifications() {
        global $db;
        $notifications_data = array();
        if (empty($_POST['access_token'])) {
            return json(array(
                'code' => 400,
                'errors' => array(
                    'error_id' => '19',
                    'error_text' => __('Bad Request, Invalid or missing parameter')
                )
            ), 400);
        } else {
            $user_id            = GetUserFromSessionID(Secure($_POST['access_token']));
            $limit              = (isset($_POST['limit']) && is_numeric($_POST['limit']) && (int) $_POST['limit'] > 0) ? (int) $_POST['limit'] : 20;
            $offset             = (isset($_POST['offset']) && is_numeric($_POST['offset']) && (int) $_POST['offset'] > 0) ? (int) $_POST['offset'] : 0;
            $blocked_user_array = array();
            $blocked_user_array = (array_keys(BlokedUsers($user_id))) ? array_keys(BlokedUsers($user_id)) : array(
                ''
            );
            $notifications      = $db->objectBuilder()->where('recipient_id', $user_id)->where('id', $offset, '>')->orderBy('id', 'DESC')->orderBy('created_at', 'DESC')->where('notifier_id', $blocked_user_array, 'NOT IN')->get('notifications', $limit, array(
                'id',
                'notifier_id',
                'recipient_id',
                'type',
                'seen',
                'text',
                'url',
                'created_at'
            ));
            foreach ($notifications as $key => $value) {
                $notifications_data[$key] = $value;
                foreach ($value as $k => $v) {
                    if ($k == 'notifier_id') {
                        $result = $db->objectBuilder()->where('id', $notifications[$key]->notifier_id)->getOne('users');
                        unset($result->password);
                        unset($result->email_code);
                        unset($result->smscode);
                        if ($result->birthday !== '0000-00-00') {
                            $result->age = floor((time() - strtotime($result->birthday)) / 31556926);
                        } else {
                            $result->age = 0;
                        }
                        $result->lastseen_txt               = get_time_ago($result->lastseen);
                        $result->lastseen_date              = date('c', $result->lastseen);
                        $result->avater                     = GetMedia($result->avater, false);
                        $notifications_data[$key]->notifier = $result;
                    }
                }
            }

            $new_notifications = $db->where('recipient_id',$user_id)->where('seen',0)->where('notifier_id', $blocked_user_array, 'NOT IN')->getOne('notifications','count(*) as ct');
            $new_messages = $db->where('`to`',$user_id)->where('seen',0)->getOne('messages','count(*) as ct');

            $db->where('recipient_id',$user_id)->where('seen',0)->update('notifications',array('seen'=>time()));

            return array(
                'code' => 200,
                'data' => $notifications_data,
                'new_notification_count' => $new_notifications['ct'],
                'new_messages_count' => $new_messages['ct']
            );
        }
    }
}