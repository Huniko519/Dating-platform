<?php
use Twilio\Jwt\AccessToken;
use Twilio\Jwt\Grants\VideoGrant;

class Messages {
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
    public function createNewConversation($receiver_id, $userid = null) {
        global $db,$config;
        if (empty($receiver_id) || !is_numeric($receiver_id)) {
            return false;
        }
        $isnew = false;
        $uid = 0;
        if ($userid > 0) {
            $uid = $userid;
        } else {
            $uid = auth()->id;
        }
        $sid = $db->where('sender_id', $uid)->where('receiver_id', (int) $receiver_id)->getOne('conversations', array(
            'id',
            'created_at',
            'status'
        ));
        if ($sid['id'] > 0) {

            $data = [];
            $data['created_at'] = time();

            if($config->message_request_system == 'on') {
                if ((int)$sid['status'] == 2) {
                    if (intval($sid['created_at']) < strtotime("-1 day")) {
                        $data['status'] = 0;
                        $db->where('sender_id', $uid)->where('receiver_id', (int) $receiver_id)->update('conversations',array('status'=>0));
                        $db->where('sender_id', (int) $receiver_id)->where('receiver_id', $uid)->update('conversations',array('status'=>1));
                        $isnew = true;
                    }
                }
            }
            $db->where('id', $sid['id'])->update('conversations', $data);





        } else {
            $dat = array(
                'sender_id' => $uid,
                'receiver_id' => (int) $receiver_id,
                'created_at' => time()
            );
            if($config->message_request_system == 'on'){
                $dat['status'] = 0;
            }else{
                $dat['status'] = 1;
            }
            $db->insert('conversations', $dat);
            $isnew = true;
        }
        $rid = $db->where('sender_id', (int) $receiver_id)->where('receiver_id', $uid)->getOne('conversations', array(
            'id'
        ));
        if ($rid['id'] > 0) {
            $db->where('id', $rid['id'])->update('conversations', array(
                'created_at' => time()
            ));
        } else {
            $dat2 = array(
                'sender_id' => (int) $receiver_id,
                'receiver_id' => $uid,
                'created_at' => time(),
                'status' => 1
            );
            $db->insert('conversations',$dat2 );
            $isnew = true;
        }

        if($isnew  === true){
            if($config->message_request_system == 'on'){
                $Notification = LoadEndPointResource('Notifications');
                if($Notification) {
                    $Notification->createNotification(auth()->web_device_id, auth()->id, $receiver_id, 'message', '', '/@' . auth()->username . '/chat_request');
                }
            }
        }

        return true;
    }
    public function getUnreadMessages() {
        global $db;
        $blocked_user_array = array();
        $blocked_user_array = (array_keys(BlokedUsers())) ? array_keys(BlokedUsers()) : array(
            ''
        );
        $messages           = $db->objectBuilder()->where('`to`', auth()->id)->where('`from`', $blocked_user_array, 'NOT IN')->where('seen', 0)->getValue('messages', 'count(*)');
        return $messages;
    }
    public function setMessageSeen($id, $userid) {
        global $db;
        if (empty($id) || !is_numeric($id)) {
            return false;
        }
        $message = $db->where('id', $id)->where('`from`', $userid)->update('messages', array(
            'seen' => time()
        ));
        if ($message) {
            return true;
        } else {
            return false;
        }
    }
    public function userData($uid){
        global $conn;
        $result = new StdClass();
        $sql  = 'SELECT `first_name`,`last_name`,`username`,`avater`,`online`,`lastseen`,`verified`,`phone_verified`,`active`,`admin` FROM `users` WHERE `id` = '.$uid.' LIMIT 1;';
        $msg = @mysqli_query($conn, $sql);
        while ($row = @mysqli_fetch_array($msg, MYSQLI_ASSOC)) {
            $result->first_name = $row['first_name'];
            $result->last_name = $row['last_name'];
            $result->avater = GetMedia($row['avater']);
            $result->online = $row['online'];
            $result->lastseen = $row['lastseen'];
            $result->verified = $row['verified'];
            $result->phone_verified = $row['phone_verified'];
            $result->active = $row['active'];
            $result->admin = $row['admin'];
            $result->mediafiles = [];
        }
        @mysqli_free_result($msg);
        unset($msg);
        return $result;
    }
    public function getConversationList($userid = null, $limit = null, $offset = 0) {
        global $db;
        if ($userid > 0) {
            $uid = $userid;
        } else {
            $uid = auth()->id;
        }
        if ($limit == null) {
            $limit = 20;
        }
        $users = LoadEndPointResource('users');

        $conversationList   = array();
        $blocked_user_array = array();
        $blocked_user       = BlokedUsers($uid);
        $blocked_user_array = (array_keys($blocked_user)) ? array_keys($blocked_user) : array(
            '0'
        );
        $receiver           = $db->objectBuilder()->where('id  > ' . (int) $offset)->where('sender_id', $uid)
            //->where('status', array('2'), 'NOT IN')
            ->where('sender_id', $blocked_user_array, 'NOT IN')
            ->orderBy('created_at', 'DESC')
            ->get('conversations', $limit, array('id','receiver_id'));

        foreach ($receiver as $key => $value) {
            $msg = $db->objectBuilder()->Where('`from_delete`', '0')->where('`from`', $uid)->Where('`to`', $value->receiver_id)->where('`to`', $blocked_user_array, 'NOT IN')->orderBy('id', 'DESC')->get('messages', 1, array(
                '`text`',
                'from_delete',
                'to_delete',
                'seen',
                'media',
                'sticker',
                'created_at'
            ));
            if (!$msg) {
                $msg = $db->objectBuilder()->Where('to_delete', '0')->where('`to`', $uid)->Where('`from`', $value->receiver_id)->where('`from`', $blocked_user_array, 'NOT IN')->orderBy('id', 'DESC')->get('messages', 1, array(
                    '`text`',
                    'from_delete',
                    'to_delete',
                    'seen',
                    '`from`',
                    'media',
                    'sticker',
                    'created_at'
                ));
            }
            $lm = $db->objectBuilder()->rawQuery('SELECT 
                                  CASE WHEN messages.`text` IS NULL THEN \'~MedIa~\' ELSE messages.`text` END AS text,
                                  `seen`,
                                  `from`,
                                  `to`,
                                  `created_at`
                                FROM
                                  messages
                                WHERE
                                  (messages.`from` = ' . $uid . ' AND messages.`to` = ' . $value->receiver_id . ' )
                                  OR 
                                  (messages.`from` = ' . $value->receiver_id . ' AND messages.`to` = ' . $uid . ')
                                ORDER BY
                                  messages.id DESC
                                LIMIT 1');
            if (isset($lm[0])) {
                $last_message = $lm[0]->text;
            }
            if (isset($msg[0])) {
                $user = $this->userData($value->receiver_id);
                if ($user) {
                    $_name     = trim($user->first_name . ' ' . $user->last_name);
                    $full_name = ($_name == '') ? trim($user->username) : $_name;
                    if ($last_message !== '~MedIa~') {
                        $_last_message = $last_message;
                    } else {
                        if ($uid == $lm[0]->to) {
                            $_last_message = __('Sent image to you.');
                        } else {
                            $_last_message = '. . .';
                        }
                    }
                    $conv_status = CheckIfConversionAccepted($uid, $value->receiver_id);
                    if ($userid == $value->receiver_id) {
                        if(!empty($conv_status)){
                            $accepted = 1;
                        }else{
                            $accepted = 0;
                        }
                    }else{
                        if($conv_status === false){
                            $accepted = 1;
                        }else{
                            $accepted = 0;
                        }
                    }
                    $new_messages = $db->where('`from`',$value->receiver_id)->where('`to`',$lm[0]->from)->where('seen',0)->getOne('messages','count(*) as ct');
                    $dt = array(
                        'conversation_status' => (int)$conv_status['status'],
                        'conversation_created_at' => $conv_status['created_at'],
                        'id' => $value->id,
                        'owner' => $lm[0]->from,
                        'user' => array(
                            'id' => $value->receiver_id,
                            'avater' => $user->avater,
                            'online' => $user->online,
                            'lastseen' => $user->lastseen,
                            'full_name' => $full_name . (($msg[0]->to_delete == 1 || $msg[0]->from_delete == 1) ? "  <!-- - <b style='color: #9C27B0;'>" . __('Delete chat.') . '</b>-->' : ''),
                            'verified' => $user->verified,
                            'phone_verified' => $user->phone_verified,
                            'active' => $user->active,
                            'mediafiles' => $user->mediafiles,
                            'admin' => $user->admin
                        ),
                        'seen' => $lm[0]->seen,
                        'accepted' => $accepted,
                        'text' => $_last_message,
                        'media' => $msg[0]->media,
                        'sticker' => $msg[0]->sticker,
                        'time' => get_time_ago(strtotime($msg[0]->created_at)),
                        'lmtime' => Time_Elapsed_String(strtotime($lm[0]->created_at)),
                        'created_at' => date('c', strtotime($lm[0]->created_at)),
                        'new_messages' => $new_messages['ct']
                    );
                    if (isset($lm[0])) {
                        $dt['seen'] = $lm[0]->seen;
                    }
                    if (isEndPointRequest()) {
                        unset($dt['lmtime']);
                        unset($dt['user']);
                        $dt['user'] = $users->get_user_profile($value->receiver_id,array('*'),true);

                        $dt['message_type'] = 'text';
                        if( $_last_message !== '' && $msg[0]->media == '' && $msg[0]->sticker == ''){
                            $dt['message_type'] = 'text';
                        }
                        if( $_last_message == '' && $msg[0]->media !== '' && $msg[0]->sticker == ''){
                            $dt['message_type'] = 'media';
                        }
                        if( $_last_message == '' && $msg[0]->media == '' && $msg[0]->sticker !== ''){
                            $dt['message_type'] = 'sticker';
                        }

                    }
                    $conversationList[] = $dt;
                }
            }
        }

        return $conversationList;
    }
    public function _getConversationList() {
        global $db;
        $conversationList    = array();
        $blocked_user_array  = array();
        $blocked_user_array  = (array_keys(BlokedUsers())) ? implode(',', array_keys(BlokedUsers())) : '';
        $conversations_array = array();
        $blockedquery        = '';
        if ($blocked_user_array !== '') {
            $blockedquery = ' AND sender_id NOT IN (' . $blocked_user_array . ') ';
        }
        $query         = 'SELECT
                      c.sender_id,
                      c.receiver_id,
                      m.text,
                      m.media,
                      CASE WHEN m.sticker IS NULL THEN \'\' ELSE (SELECT file FROM stickers WHERE id = m.sticker) END AS sticker,
                      m.* 
                    FROM
                      conversations c 
                    LEFT JOIN
                      messages m ON c.sender_id = m.from
                    WHERE
                        m.to_delete = 0
                        ' . $blockedquery . '
                        AND
                        c.receiver_id = ' . auth()->id . '
                      
                    ORDER BY
                      m.created_at DESC
                    ';
        $conversations = $db->objectBuilder()->rawQuery($query);
        foreach ($conversations as $key => $value) {
            if (!isset($conversations_array[$value->sender_id])) {
                $conversations_array[$value->sender_id] = $value;
            }
        }
        foreach ($conversations_array as $key => $value) {
            if (isset($conversationList[$value->sender_id])) {
                continue;
            } else {
                $user = userData($value->sender_id);
                if ($user) {
                    $_name                               = trim($user->first_name . ' ' . $user->last_name);
                    $full_name                           = ($_name == '') ? trim($user->username) : $_name;
                    $conversationList[$value->sender_id] = array(
                        'user' => array(
                            'id' => $value->sender_id,
                            'avater' => $user->avater->avater,
                            'online' => $user->online,
                            'lastseen' => $user->lastseen,
                            'full_name' => $full_name . (($value->from_delete == 1) ? "  - <b style='color: #9C27B0;'>" . __('Delete chat.') . '</b>' : ''),
                            'verified' => $user->verified,
                            'phone_verified' => $user->phone_verified,
                            'active' => $user->active,
                            'mediafiles' => $user->mediafiles
                        ),
                        'seen' => $value->seen,
                        'text' => (isset($value->text)) ? $value->text : __('Sent image to you.'),
                        'media' => $value->media,
                        'sticker' => $value->sticker,
                        'time' => get_time_ago(strtotime($value->created_at)),
                        'created_at' => date('c', strtotime($value->created_at))
                    );
                }
            }
        }
        return ToObject($conversationList);
    }
    public function searchChat($search_text, $is_online = 0) {
        global $db, $console_log;
        $conversationList = array();
        $sql              = 'SELECT 
                  conversations.sender_id,
                  conversations.created_at,
                  (SELECT MAX(messages.`created_at`) AS created_at FROM messages WHERE `messages`.`from` = conversations.sender_id AND `messages`.`to` = 3) AS ct,
                  users.username,
                  users.first_name,
                  users.last_name,
                  users.avater,
                  users.lastseen,
                  (SELECT COUNT(*) FROM mediafiles WHERE mediafiles.user_id = conversations.sender_id) AS media_count,
                  users.admin,
                  users.phone_verified,
                  users.active
                FROM
                  conversations
                  INNER JOIN users ON (conversations.sender_id = users.id)
                WHERE
                  users.`verified` = \'1\' AND
                  conversations.receiver_id = ' . auth()->id . ' AND 
                  conversations.from_delete = 0 AND 
                  ((users.username LIKE \'%' . $search_text . '%\') OR CONCAT( users.first_name,  \' \', users.last_name ) LIKE  \'%' . $search_text . '%\') AND
                  conversations.sender_id NOT IN (SELECT `block_userid` FROM `blocks` WHERE `user_id` = ' . auth()->id . ')';
        $conversations    = $db->objectBuilder()->rawQuery($sql);
        $is_online1       = 0;
        $verified         = false;
        foreach ($conversations as $key => $value) {
            $_name     = ucfirst(trim($value->first_name . ' ' . $value->last_name));
            $full_name = ($_name == '') ? ucfirst(trim($value->username)) : $_name;
            if ($value->admin == 1) {
                $verified = true;
            }
            if ($value->phone_verified == 1 && $value->active == 1 && $value->media_count >= 5) {
                $verified = true;
            } else {
                $verified = false;
            }
            $lt                                  = ($value->ct == null) ? $value->created_at : strtotime($value->ct);
            $conversationList[$value->sender_id] = array(
                'user' => array(
                    'id' => $value->sender_id,
                    'avater' => GetMedia($value->avater),
                    'online' => (round(((time() - $value->lastseen) / 60), 0) < 3) ? 1 : 0,
                    'full_name' => $full_name,
                    'verified' => $verified
                ),
                'text' => $full_name,
                'time' => get_time_ago($lt),
                'lmtime' => Time_Elapsed_String($lt),
                'created_at' => date('c', $lt)
            );
            if ($is_online == 1) {
                if (round(((time() - $value->lastseen) / 60), 0) < 3) {
                } else {
                    unset($conversationList[$value->sender_id]);
                }
            }
        }
        return ToObject($conversationList);
    }
    /*API*/
    public function send_text_message() {
        global $db;
        if (empty($_POST['message']) || empty($_POST['to_userid']) || empty($_POST['access_token']) || empty($_POST['hash_id'])) {
            return json(array(
                'code' => 400,
                'errors' => array(
                    'error_id' => '19',
                    'error_text' => __('Bad Request, Invalid or missing parameter')
                )
            ), 400);
        } else {
            if (!is_numeric(Secure($_POST['to_userid']))) {
                return json(array(
                    'code' => 400,
                    'errors' => array(
                        'error_id' => '19',
                        'error_text' => __('Bad Request, Invalid or missing parameter')
                    )
                ), 400);
            }
            $user_id   = GetUserFromSessionID(Secure($_POST['access_token']));
            $to_userid = (int) Secure($_POST['to_userid']);
            $this->createNewConversation($to_userid, $user_id);
            $message = trim(Secure($_POST['message']));
            if (isUserInBlockList($to_userid, $user_id)) {
                return array(
                    'status' => 400,
                    'blacklist' => true,
                    'errors' => array(
                        'error_id' => '44',
                        'error_text' => __('User in black list')
                    )
                );
            }
            $data  = array();
            $saved = $db->insert('messages', array(
                'from' => (int) $user_id,
                'to' => (int) $to_userid,
                'text' => $message,
                'seen' => 0,
                'created_at' => date('Y-m-d H:i:s')
            ));
            if ($saved) {
                $data['id']          = $saved;
                $data['from']        = (int) $user_id;
                $data['from_delete'] = 0;
                $data['to']          = (int) $to_userid;
                $data['to_delete']   = 0;
                $data['text']        = $message;
                $data['media']       = "";
                $data['sticker']     = "";
                $data['seen']        = 0;
                $data['created_at']  = date('Y-m-d H:i:s');
                $data['message_type'] = 'text';

                return array(
                    'status' => 200,
                    'message' => __('Message sent successfully.'),
                    'data' => $data,
                    'hash_id' => $_POST['hash_id']
                );
            }
        }
    }
    /*API*/
    public function send_sticker_message() {
        global $db;
        if (empty($_POST['sticker_id']) || empty($_POST['to_userid']) || empty($_POST['access_token']) || empty($_POST['hash_id'])) {
            return json(array(
                'code' => 400,
                'errors' => array(
                    'error_id' => '19',
                    'error_text' => __('Bad Request, Invalid or missing parameter')
                )
            ), 400);
        } else {
            if (!is_numeric(Secure($_POST['to_userid'])) || !is_numeric(Secure($_POST['sticker_id']))) {
                return json(array(
                    'code' => 400,
                    'errors' => array(
                        'error_id' => '19',
                        'error_text' => __('Bad Request, Invalid or missing parameter')
                    )
                ), 400);
            }
            $user_id    = GetUserFromSessionID(Secure($_POST['access_token']));
            $to_userid  = (int) Secure($_POST['to_userid']);
            $sticker_id = (int) Secure($_POST['sticker_id']);
            if (isUserInBlockList($to_userid, $user_id)) {
                return array(
                    'status' => 400,
                    'blacklist' => true,
                    'errors' => array(
                        'error_id' => '44',
                        'error_text' => __('User in black list')
                    )
                );
            }
            $this->createNewConversation($to_userid, $user_id);
            $data  = array();
            $saved = $db->insert('messages', array(
                'from' => (int) $user_id,
                'to' => (int) $to_userid,
                'sticker' => $sticker_id,
                'seen' => 0,
                'created_at' => date('Y-m-d H:i:s')
            ));
            if ($saved) {
                $data['id']          = $saved;
                $data['from']        = (int) $user_id;
                $data['from_delete'] = 0;
                $data['to']          = (int) $to_userid;
                $data['to_delete']   = 0;
                $data['text']        = "";
                $data['media']       = "";
                $data['sticker']     = $sticker_id;
                $data['seen']        = 0;
                $data['created_at']  = date('Y-m-d H:i:s');
                $data['message_type'] = 'sticker';
                return array(
                    'status' => 200,
                    'message' => __('Sticker sent successfully.'),
                    'data' => $data,
                    'hash_id' => $_POST['hash_id']
                );
            }
        }
    }
    /*API*/
    public function send_media_message() {
        global $db, $_UPLOAD, $_DS;
        $data  = array();
        $error = false;
        if (empty($_FILES['media_file']) || empty($_POST['to_userid']) || empty($_POST['access_token']) || empty($_POST['hash_id'])) {
            return json(array(
                'code' => 400,
                'errors' => array(
                    'error_id' => '19',
                    'error_text' => __('Bad Request, Invalid or missing parameter')
                )
            ), 400);
        } else {
            if (!is_numeric(Secure($_POST['to_userid']))) {
                return json(array(
                    'code' => 400,
                    'errors' => array(
                        'error_id' => '19',
                        'error_text' => __('Bad Request, Invalid or missing parameter')
                    )
                ), 400);
            }
            if (!isset($_FILES) && empty($_FILES)) {
                return json(array(
                    'code' => 400,
                    'errors' => array(
                        'error_id' => '19',
                        'error_text' => __('Bad Request, Invalid or missing parameter')
                    )
                ), 400);
            }
            $user_id   = GetUserFromSessionID(Secure($_POST['access_token']));
            $to_userid = (int) Secure($_POST['to_userid']);
            if (isUserInBlockList($to_userid, $user_id)) {
                return array(
                    'status' => 400,
                    'blacklist' => true,
                    'errors' => array(
                        'error_id' => '44',
                        'error_text' => __('User in black list')
                    )
                );
            }
            $this->createNewConversation($to_userid, $user_id);
            $file = '';
            if (!file_exists($_UPLOAD . 'chat' . $_DS . date('Y'))) {
                mkdir($_UPLOAD . 'chat' . $_DS . date('Y'), 0777, true);
            }
            if (!file_exists($_UPLOAD . 'chat' . $_DS . date('Y') . $_DS . date('m'))) {
                mkdir($_UPLOAD . 'chat' . $_DS . date('Y') . $_DS . date('m'), 0777, true);
            }
            $dir = $_UPLOAD . 'chat' . $_DS . date('Y') . $_DS . date('m');
            foreach ($_FILES as $file) {
                $ext      = pathinfo($file['name'], PATHINFO_EXTENSION);
                $key      = GenerateKey();
                $filename = $dir . $_DS . $key . '.' . $ext;
                if (move_uploaded_file($file['tmp_name'], $filename)) {
                    $thumbfile = 'upload'. $_DS . 'chat' . $_DS . date('Y') . $_DS . date('m') . $_DS . $key . '_m.' . $ext;
                    $thumbnail = new ImageThumbnail($filename);
                    $thumbnail->save($thumbfile);
                    @unlink($filename);
                    if (is_file($thumbfile)) {
                        UploadToS3($thumbfile, array(
                            'amazon' => 0
                        ));
                    }
                    $msg               = array();
                    $msg['from']       = $user_id;
                    $msg['to']         = $to_userid;
                    $msg['media']      = 'upload/chat/' . date('Y') . '/' . date('m') . '/' . $key . '_m.' . $ext;
                    $msg['seen']       = 0;
                    $msg['created_at'] = date('Y-m-d H:i:s');
                    $saved             = $db->insert('messages', $msg);
                    if ($saved) {
                        $data['id']          = $saved;
                        $data['from']        = (int) $user_id;
                        $data['from_delete'] = 0;
                        $data['to']          = (int) $to_userid;
                        $data['to_delete']   = 0;
                        $data['text']        = "";
                        $data['media']       = $msg['media'];
                        $data['sticker']     = "";
                        $data['seen']        = 0;
                        $data['created_at']  = date('Y-m-d H:i:s');
                        $data['message_type'] = 'media';
                        $file                = GetMedia('upload/chat/' . date('Y') . '/' . date('m') . '/' . $key . '_m.' . $ext);
                    }
                } else {
                    $error = true;
                }
            }
            if (!$error) {
                return array(
                    'code' => 200,
                    'message' => __('Media message sent successfully.'),
                    'data' => $data,
                    'hash_id' => $_POST['hash_id']
                );
            }
        }
    }
    /*API*/
    public function delete_messages() {
        global $db;
        if (empty($_POST['to_userid']) || empty($_POST['access_token'])) {
            return json(array(
                'code' => 400,
                'errors' => array(
                    'error_id' => '19',
                    'error_text' => __('Bad Request, Invalid or missing parameter')
                )
            ), 400);
        } else {
            if (!is_numeric(Secure($_POST['to_userid']))) {
                return json(array(
                    'code' => 400,
                    'errors' => array(
                        'error_id' => '19',
                        'error_text' => __('Bad Request, Invalid or missing parameter')
                    )
                ), 400);
            }
            $user_id   = GetUserFromSessionID(Secure($_POST['access_token']));
            $to_userid = (int) Secure($_POST['to_userid']);
            $db->where('`to`', $to_userid)->where('`from`', $user_id)->update('messages', array(
                'from_delete' => '1'
            ));
            $db->where('`to`', $user_id)->where('`from`', $to_userid)->update('messages', array(
                'to_delete' => '1'
            ));
            $db->where('sender_id', $to_userid)->where('receiver_id', $user_id)->update('conversations', array(
                'from_delete' => '1'
            ));
            $db->where('sender_id', $user_id)->where('receiver_id', $to_userid)->update('conversations', array(
                'to_delete' => '1'
            ));
            $db->where('from_delete', '1')->where('to_delete', '1')->delete('messages');
            $db->where('from_delete', '1')->where('to_delete', '1')->delete('conversations');
            return array(
                'status' => 200,
                'message' => __('Messages deleted successfully.')
            );
        }
    }
    /*API*/
    public function get_conversation_list() {
        if (empty($_POST['access_token'])) {
            return json(array(
                'code' => 400,
                'errors' => array(
                    'error_id' => '19',
                    'error_text' => __('Bad Request, Invalid or missing parameter')
                )
            ), 400);
        } else {
            $user_id = GetUserFromSessionID(Secure($_POST['access_token']));
            $limit   = (isset($_POST['limit']) && is_numeric($_POST['limit']) && (int) $_POST['limit'] > 0) ? (int) $_POST['limit'] : 20;
            $offset  = (isset($_POST['offset'])) ? (int) $_POST['offset'] : 0;
            $list    = $this->getConversationList($user_id, $limit, $offset);
            return array(
                'code' => 200,
                'data' => $list
            );
        }
    }
    /*API*/
    public function get_chat_conversations() {
        global $db;
        if (empty($_POST['access_token']) || empty($_POST['to_userid'])) {
            return json(array(
                'code' => 400,
                'errors' => array(
                    'error_id' => '19',
                    'error_text' => __('Bad Request, Invalid or missing parameter')
                )
            ), 400);
        } else {
            if (!is_numeric(Secure($_POST['to_userid']))) {
                return json(array(
                    'code' => 400,
                    'errors' => array(
                        'error_id' => '19',
                        'error_text' => __('Bad Request, Invalid or missing parameter')
                    )
                ), 400);
            }
            $user_id   = GetUserFromSessionID(Secure($_POST['access_token']));
            $to_userid = (int) Secure($_POST['to_userid']);
            $limit     = (isset($_POST['limit']) && is_numeric($_POST['limit']) && (int) $_POST['limit'] > 0) ? (int) $_POST['limit'] : 20;
            $offset    = (isset($_POST['offset'])) ? (int) $_POST['offset'] : 0;
            $prev      = (isset($_POST['prev'])) ? '<' : '>';
            $db->where("( (`to` = ? and `from` = ?) OR (`to` = ? and `from` = ?) )", Array(
                $user_id,
                $to_userid,
                $to_userid,
                $user_id
            ));
            $db->where("m.id  " . $prev . ' ' . (int) $offset);
            $db->join("stickers s", "m.sticker=s.id", "LEFT");
            $db->join("users u", "m.`from`=u.id", "LEFT");
            $db->join("users u1", "m.`to`=u1.id", "LEFT");
            $db->orderBy('m.created_at', 'DESC');
            $chat_messages = $db->arrayBuilder()->get('messages m', $limit, array(
                'm.id',
                'u.username as from_name',
                'u.avater as from_avater',
                'u1.username as to_name',
                'u1.avater as to_avater',
                'm.`from`',
                'm.`to`',
                'm.text',
                'm.media',
                'm.from_delete',
                'm.to_delete',
                's.file as sticker',
                'm.created_at',
                'm.seen'
            ));
            usort($chat_messages, "chat_messages_sortFunction");
            foreach ($chat_messages as $key => $value) {
                $chat_messages[$key]['from_avater'] = GetMedia($chat_messages[$key]['from_avater']);
                $chat_messages[$key]['to_avater']   = GetMedia($chat_messages[$key]['to_avater']);
                $chat_messages[$key]['media']       = GetMedia($chat_messages[$key]['media']);
                $chat_messages[$key]['sticker']     = GetMedia($chat_messages[$key]['sticker']);
                if ($value['to'] !== $user_id) {
                    $chat_messages[$key]['type'] = 'sent';
                } else {
                    $chat_messages[$key]['type'] = 'received';
                }
                $chat_messages[$key]['message_type'] = 'text';
                if( $chat_messages[$key]['text'] !== '' && $chat_messages[$key]['media'] == '' && $chat_messages[$key]['sticker'] == ''){
                    $chat_messages[$key]['message_type'] = 'text';
                }
                if( $chat_messages[$key]['text'] == '' && $chat_messages[$key]['media'] !== '' && $chat_messages[$key]['sticker'] == ''){
                    $chat_messages[$key]['message_type'] = 'media';
                }
                if( $chat_messages[$key]['text'] == '' && $chat_messages[$key]['media'] == '' && $chat_messages[$key]['sticker'] !== ''){
                    $chat_messages[$key]['message_type'] = 'sticker';
                }
            }
            return array(
                'code' => 200,
                'message' => __('Chat conversations fetch successfully'),
                'data' => $chat_messages
            );
        }
    }
    /*API*/
    public function switch_online() {
        global $db;
        if (empty($_POST['access_token'])) {
            return json(array(
                'code' => 400,
                'errors' => array(
                    'error_id' => '19',
                    'error_text' => __('Bad Request, Invalid or missing parameter')
                )
            ), 400);
        } else {
            $user_id = GetUserFromSessionID(Secure($_POST['access_token']));
            $online  = (isset($_POST['online']) && Secure($_POST['online']) == 1) ? (int) Secure($_POST['online']) : 0;
            $updated = $db->where('id', $user_id)->update('users', array(
                'lastseen' => time(),
                'online' => $online
            ));
            if ($updated) {
                return array(
                    'code' => 200,
                    'message' => __('Operation successfully.')
                );
            }
        }
    }
    /*API*/
    public function create_new_video_call() {
        global $db,$_LIBS,$_DS,$config;
        if ( empty($_POST['to_userid']) || empty($_POST['access_token'])) {
            return json(array(
                'code' => 400,
                'errors' => array(
                    'error_id' => '19',
                    'error_text' => __('Bad Request, Invalid or missing parameter')
                )
            ), 400);
        } else {
            if (!is_numeric(Secure($_POST['to_userid']))) {
                return json(array(
                    'code' => 400,
                    'errors' => array(
                        'error_id' => '20',
                        'error_text' => __('Bad Request, Invalid or missing parameter')
                    )
                ), 400);
            }
            $user_id   = GetUserFromSessionID(Secure($_POST['access_token']));
            $to_userid = (int) Secure($_POST['to_userid']);
            require_once($_LIBS . 'twilio'.$_DS.'vendor'.$_DS.'autoload.php');
            $user_1       = userData($user_id);
            $user_2       = userData($to_userid);
            $room_script  = sha1(rand(1111111, 9999999));
            $accountSid   = $config->video_accountSid;
            $apiKeySid    = $config->video_apiKeySid;
            $apiKeySecret = $config->video_apiKeySecret;
            $call_id      = substr(md5(microtime()), 0, 15);
            $call_id_2    = substr(md5(time()), 0, 15);
            $token        = new AccessToken($accountSid, $apiKeySid, $apiKeySecret, 3600, $call_id);
            $grant        = new VideoGrant();
            $grant->setRoom($room_script);
            $token->addGrant($grant);
            $token_ = $token->toJWT();
            $token2 = new AccessToken($accountSid, $apiKeySid, $apiKeySecret, 3600, $call_id_2);
            $grant2 = new VideoGrant();
            $grant2->setRoom($room_script);
            $token2->addGrant($grant2);
            $token_2    = $token2->toJWT();
            $insertData = CreateNewVideoCall(array(
                'access_token' => Secure($token_),
                'from_id' => Secure($user_id),
                'to_id' => Secure($to_userid),
                'access_token_2' => Secure($token_2),
                'room_name' => $room_script
            ), true);
            if ($insertData > 0) {
                return array(
                    'status' => 200,
                    'data' => array(
                        'user1' => $user_1,
                        'user2' => $user_2,
                        'access_token' => $token_,
                        'id' => $insertData
                    )
                );
            }else{
                return json(array(
                    'code' => 400,
                    'errors' => array(
                        'error_id' => '21',
                        'error_text' => __('Bad Request, Invalid or missing parameter')
                    )
                ), 400);
            }
        }
    }
    /*API*/
    public function create_new_audio_call() {
        global $db,$_LIBS,$_DS,$config;
        if ( empty($_POST['to_userid']) || empty($_POST['access_token'])) {
            return json(array(
                'code' => 400,
                'errors' => array(
                    'error_id' => '19',
                    'error_text' => __('Bad Request, Invalid or missing parameter')
                )
            ), 400);
        } else {
            if (!is_numeric(Secure($_POST['to_userid']))) {
                return json(array(
                    'code' => 400,
                    'errors' => array(
                        'error_id' => '20',
                        'error_text' => __('Bad Request, Invalid or missing parameter')
                    )
                ), 400);
            }
            $user_id   = GetUserFromSessionID(Secure($_POST['access_token']));
            $to_userid = (int) Secure($_POST['to_userid']);
            require_once($_LIBS . 'twilio'.$_DS.'vendor'.$_DS.'autoload.php');
            $user_1       = userData($user_id);
            $user_2       = userData($to_userid);
            $room_script  = sha1(rand(1111111, 9999999));
            $accountSid   = $config->video_accountSid;
            $apiKeySid    = $config->video_apiKeySid;
            $apiKeySecret = $config->video_apiKeySecret;
            $call_id      = substr(md5(microtime()), 0, 15);
            $call_id_2    = substr(md5(time()), 0, 15);
            $token        = new AccessToken($accountSid, $apiKeySid, $apiKeySecret, 3600, $call_id);
            $grant        = new VideoGrant();
            $grant->setRoom($room_script);
            $token->addGrant($grant);
            $token_ = $token->toJWT();
            $token2 = new AccessToken($accountSid, $apiKeySid, $apiKeySecret, 3600, $call_id_2);
            $grant2 = new VideoGrant();
            $grant2->setRoom($room_script);
            $token2->addGrant($grant2);
            $token_2    = $token2->toJWT();
            $insertData = CreateNewAudioCall(array(
                'access_token' => Secure($token_),
                'from_id' => Secure($user_id),
                'to_id' => Secure($to_userid),
                'access_token_2' => Secure($token_2),
                'room_name' => $room_script
            ), true);
            if ($insertData > 0) {
                return array(
                    'status' => 200,
                    'data' => array(
                        'user1' => $user_1,
                        'user2' => $user_2,
                        'access_token' => $token_,
                        'id' => $insertData
                    )
                );
            }else{
                return json(array(
                    'code' => 400,
                    'errors' => array(
                        'error_id' => '21',
                        'error_text' => __('Bad Request, Invalid or missing parameter')
                    )
                ), 400);
            }
        }
    }
    /*API*/
    public function decline_video_call() {
        global $db,$_LIBS,$_DS,$config,$conn;
        if ( empty($_POST['id']) || empty($_POST['access_token'])) {
            return json(array(
                'code' => 400,
                'errors' => array(
                    'error_id' => '19',
                    'error_text' => __('Bad Request, Invalid or missing parameter')
                )
            ), 400);
        } else {
            if (!is_numeric(Secure($_POST['id']))) {
                return json(array(
                    'code' => 400,
                    'errors' => array(
                        'error_id' => '20',
                        'error_text' => __('Bad Request, Invalid or missing parameter')
                    )
                ), 400);
            }
            $user_id   = GetUserFromSessionID(Secure($_POST['access_token']));
            $id = (int) Secure($_POST['id']);
            $query = mysqli_query($conn, "UPDATE `videocalles` SET `declined` = '1' WHERE `id` = '$id'");
            if ($query) {
                return array(
                    'status' => 200,
                    'message' => 'success'
                );
            }else{
                return json(array(
                    'code' => 400,
                    'errors' => array(
                        'error_id' => '21',
                        'error_text' => __('Bad Request, Invalid or missing parameter')
                    )
                ), 400);
            }
        }
    }
    /*API*/
    public function decline_audio_call() {
        global $db,$_LIBS,$_DS,$config,$conn;
        if ( empty($_POST['id']) || empty($_POST['access_token'])) {
            return json(array(
                'code' => 400,
                'errors' => array(
                    'error_id' => '19',
                    'error_text' => __('Bad Request, Invalid or missing parameter')
                )
            ), 400);
        } else {
            if (!is_numeric(Secure($_POST['id']))) {
                return json(array(
                    'code' => 400,
                    'errors' => array(
                        'error_id' => '20',
                        'error_text' => __('Bad Request, Invalid or missing parameter')
                    )
                ), 400);
            }
            $user_id   = GetUserFromSessionID(Secure($_POST['access_token']));
            $id = (int) Secure($_POST['id']);
            $query = mysqli_query($conn, "UPDATE `audiocalls` SET `declined` = '1' WHERE `id` = '$id'");
            if ($query) {
                return array(
                    'status' => 200,
                    'message' => 'success'
                );
            }else{
                return json(array(
                    'code' => 400,
                    'errors' => array(
                        'error_id' => '21',
                        'error_text' => __('Bad Request, Invalid or missing parameter')
                    )
                ), 400);
            }
        }
    }
    /*API*/
    public function delete_video_call() {
        global $db,$_LIBS,$_DS,$config,$conn;
        if ( empty($_POST['id']) || empty($_POST['access_token'])) {
            return json(array(
                'code' => 400,
                'errors' => array(
                    'error_id' => '19',
                    'error_text' => __('Bad Request, Invalid or missing parameter')
                )
            ), 400);
        } else {
            if (!is_numeric(Secure($_POST['id']))) {
                return json(array(
                    'code' => 400,
                    'errors' => array(
                        'error_id' => '20',
                        'error_text' => __('Bad Request, Invalid or missing parameter')
                    )
                ), 400);
            }
            $user_id   = GetUserFromSessionID(Secure($_POST['access_token']));
            $id = (int) Secure($_POST['id']);
            $query = mysqli_query($conn, "DELETE FROM `videocalles` WHERE `id` = '$id'");
            if ($query) {
                return array(
                    'status' => 200,
                    'message' => 'success'
                );
            }else{
                return json(array(
                    'code' => 400,
                    'errors' => array(
                        'error_id' => '21',
                        'error_text' => __('Bad Request, Invalid or missing parameter')
                    )
                ), 400);
            }
        }
    }
    /*API*/
    public function delete_audio_call() {
        global $db,$_LIBS,$_DS,$config,$conn;
        if ( empty($_POST['id']) || empty($_POST['access_token'])) {
            return json(array(
                'code' => 400,
                'errors' => array(
                    'error_id' => '19',
                    'error_text' => __('Bad Request, Invalid or missing parameter')
                )
            ), 400);
        } else {
            if (!is_numeric(Secure($_POST['id']))) {
                return json(array(
                    'code' => 400,
                    'errors' => array(
                        'error_id' => '20',
                        'error_text' => __('Bad Request, Invalid or missing parameter')
                    )
                ), 400);
            }
            $user_id   = GetUserFromSessionID(Secure($_POST['access_token']));
            $id = (int) Secure($_POST['id']);
            $query = mysqli_query($conn, "DELETE FROM `audiocalls` WHERE `id` = '$id'");
            if ($query) {
                return array(
                    'status' => 200,
                    'message' => 'success'
                );
            }else{
                return json(array(
                    'code' => 400,
                    'errors' => array(
                        'error_id' => '21',
                        'error_text' => __('Bad Request, Invalid or missing parameter')
                    )
                ), 400);
            }
        }
    }
    /*API*/
    public function check_audio_in_call() {
        global $db,$_LIBS,$_DS,$config,$conn;
        if ( empty($_POST['to_id']) || empty($_POST['access_token'])) {
            return json(array(
                'code' => 400,
                'errors' => array(
                    'error_id' => '19',
                    'error_text' => __('Bad Request, Invalid or missing parameter')
                )
            ), 400);
        } else {
            if (!is_numeric(Secure($_POST['to_id']))) {
                return json(array(
                    'code' => 400,
                    'errors' => array(
                        'error_id' => '20',
                        'error_text' => __('Bad Request, Invalid or missing parameter')
                    )
                ), 400);
            }
            $user_id   = GetUserFromSessionID(Secure($_POST['access_token']));
            $to_id = (int) Secure($_POST['to_id']);
            $time = time() - 40;
            $query = mysqli_query($conn, "SELECT * FROM `audiocalls` WHERE `to_id` = '{$to_id}' AND `time` > '$time' AND `active` = '0' AND `declined` = '0'");
            if (mysqli_num_rows($query) > 0) {
                $sql = mysqli_fetch_assoc($query);
                if (isUserBlocked($sql['from_id'])) {
                    return array(
                        'status' => 200,
                        'data' => array()
                    );
                }
                return array(
                    'status' => 200,
                    'data' => array('id' => $sql['id'])
                );
            } else {
                return array(
                    'status' => 200,
                    'data' => array()
                );
            }
        }
    }
    /*API*/
    public function check_video_in_call() {
        global $db,$_LIBS,$_DS,$config,$conn;
        if ( empty($_POST['to_id']) || empty($_POST['access_token'])) {
            return json(array(
                'code' => 400,
                'errors' => array(
                    'error_id' => '19',
                    'error_text' => __('Bad Request, Invalid or missing parameter')
                )
            ), 400);
        } else {
            if (!is_numeric(Secure($_POST['to_id']))) {
                return json(array(
                    'code' => 400,
                    'errors' => array(
                        'error_id' => '20',
                        'error_text' => __('Bad Request, Invalid or missing parameter')
                    )
                ), 400);
            }
            $user_id   = GetUserFromSessionID(Secure($_POST['access_token']));
            $to_id = (int) Secure($_POST['to_id']);
            $time = time() - 40;
            $query = mysqli_query($conn, "SELECT * FROM `videocalles` WHERE `to_id` = '{$to_id}' AND `time` > '$time' AND `active` = '0' AND `declined` = '0'");
            if (mysqli_num_rows($query) > 0) {
                $sql = mysqli_fetch_assoc($query);
                if (isUserBlocked($sql['from_id'])) {
                    return array(
                        'status' => 200,
                        'data' => array()
                    );
                }
                return array(
                    'status' => 200,
                    'data' => array('id' => $sql['id'])
                );
            } else {
                return array(
                    'status' => 200,
                    'data' => array()
                );
            }
        }
    }
    /*API*/
    public function answer_video_call() {
        global $db,$_LIBS,$_DS,$config,$conn;
        if ( empty($_POST['id']) || empty($_POST['access_token'])) {
            return json(array(
                'code' => 400,
                'errors' => array(
                    'error_id' => '19',
                    'error_text' => __('Bad Request, Invalid or missing parameter')
                )
            ), 400);
        } else {
            if (!is_numeric(Secure($_POST['id']))) {
                return json(array(
                    'code' => 400,
                    'errors' => array(
                        'error_id' => '20',
                        'error_text' => __('Bad Request, Invalid or missing parameter')
                    )
                ), 400);
            }
            $user_id   = GetUserFromSessionID(Secure($_POST['access_token']));
            $id = (int) Secure($_POST['id']);
            $query = mysqli_query($conn, "UPDATE `videocalles` SET `active` = 1 WHERE `id` = '$id'");
            if ($query) {
                $call = mysqli_query($conn, "SELECT * FROM `videocalles` WHERE `id` = '{$id}'");
                $sql = mysqli_fetch_assoc($call);
                return array(
                    'status' => 200,
                    'data' => $sql
                );
            }else{
                return json(array(
                    'code' => 400,
                    'errors' => array(
                        'error_id' => '20',
                        'error_text' => __('Bad Request, Invalid or missing parameter')
                    )
                ), 400);
            }
        }
    }
    /*API*/
    public function answer_audio_call() {
        global $db,$_LIBS,$_DS,$config,$conn;
        if ( empty($_POST['id']) || empty($_POST['access_token'])) {
            return json(array(
                'code' => 400,
                'errors' => array(
                    'error_id' => '19',
                    'error_text' => __('Bad Request, Invalid or missing parameter')
                )
            ), 400);
        } else {
            if (!is_numeric(Secure($_POST['id']))) {
                return json(array(
                    'code' => 400,
                    'errors' => array(
                        'error_id' => '20',
                        'error_text' => __('Bad Request, Invalid or missing parameter')
                    )
                ), 400);
            }
            $user_id   = GetUserFromSessionID(Secure($_POST['access_token']));
            $id = (int) Secure($_POST['id']);
            $query = mysqli_query($conn, "UPDATE `audiocalls` SET `active` = 1 WHERE `id` = '$id'");
            if ($query) {
                $call = mysqli_query($conn, "SELECT * FROM `audiocalls` WHERE `id` = '{$id}'");
                $sql = mysqli_fetch_assoc($call);
                return array(
                    'status' => 200,
                    'data' => $sql
                );
            }else{
                return json(array(
                    'code' => 400,
                    'errors' => array(
                        'error_id' => '20',
                        'error_text' => __('Bad Request, Invalid or missing parameter')
                    )
                ), 400);
            }
        }
    }
    /*API*/
    function check_for_video_answer() {
        global $db,$_LIBS,$_DS,$config,$conn;
        if ( empty($_POST['id']) || empty($_POST['access_token'])) {
            return json(array(
                'code' => 400,
                'errors' => array(
                    'error_id' => '19',
                    'error_text' => __('Bad Request, Invalid or missing parameter')
                )
            ), 400);
        } else {
            if (!is_numeric(Secure($_POST['id']))) {
                return json(array(
                    'code' => 400,
                    'errors' => array(
                        'error_id' => '20',
                        'error_text' => __('Bad Request, Invalid or missing parameter')
                    )
                ), 400);
            }
            $user_id   = GetUserFromSessionID(Secure($_POST['access_token']));
            $id = (int) Secure($_POST['id']);
            $selectData = CheckCallAnswer($_POST['id'], true);
            if ($selectData !== false) {
                return array(
                    'status' => 200,
                    'data' => $selectData
                );
            } else {
                $check_declined = CheckCallAnswerDeclined($_POST['id'], true);
                $data = ['id' => $check_declined];
                if ($check_declined) {
                    return array(
                        'status' => 400,
                        'message' => 'declined'
                    );
                }else{
                    return array(
                        'status' => 300,
                        'message' => 'calling'
                    );
                }
            }
        }
    }
    /*API*/
    function check_for_audio_answer() {
        global $db,$_LIBS,$_DS,$config,$conn;
        if ( empty($_POST['id']) || empty($_POST['access_token'])) {
            return json(array(
                'code' => 400,
                'errors' => array(
                    'error_id' => '19',
                    'error_text' => __('Bad Request, Invalid or missing parameter')
                )
            ), 400);
        } else {
            if (!is_numeric(Secure($_POST['id']))) {
                return json(array(
                    'code' => 400,
                    'errors' => array(
                        'error_id' => '20',
                        'error_text' => __('Bad Request, Invalid or missing parameter')
                    )
                ), 400);
            }
            $user_id   = GetUserFromSessionID(Secure($_POST['access_token']));
            $id = (int) Secure($_POST['id']);
            $selectData = CheckAudioCallAnswer($_POST['id'], true);
            if ($selectData !== false) {
                return array(
                    'status' => 200,
                    'data' => $selectData
                );
            } else {
                $check_declined = CheckAudioCallAnswerDeclined($_POST['id'], true);
                $data = ['id' => $check_declined];
                if ($check_declined) {
                    return array(
                        'status' => 400,
                        'message' => 'declined'
                    );
                }else{
                    return array(
                        'status' => 300,
                        'message' => 'calling'
                    );
                }
            }
        }
    }
}
