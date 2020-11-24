<?php
class Stories{
    private $_table = 'options';
    private $_requestMethod;
    private $_id;

    public function __construct($IsLoadFromLoadEndPointResource = false){
        global $_id;
        $this->_id = $_id;
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
            }
        }
    }

    /*API*/
    public function ListAll($data = null){
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
            $story_array = array();

            $limit = ( isset($_POST['limit']) && is_numeric($_POST['limit']) && (int)$_POST['limit'] > 0 ) ? (int)$_POST['limit'] : 20;
            $offset  = ( isset($_POST['offset']) && is_numeric($_POST['offset']) && (int)$_POST['offset'] > 0 ) ? (int)$_POST['offset'] : 0;

            $db->where('id',$offset , '>');

            if( isset( $_POST['category_id'] ) && (int)$_POST['category_id'] > 0 ){
                $db->where('category',Secure($_POST['category_id']));
            }
            $stories = $db->orderBy('created_at','DESC')->get('success_stories',$limit,array('*'));
            foreach ($stories as $key => $story) {
                $story_array[$story['id']] = $story;
                $story_array['user_data'] = userData($story['user_id']);
                $story_array['story_userdata'] = userData($story['story_user_id']);
                $story_array[$story['id']]['thumbnail'] = GetMedia($story['thumbnail']);
                $story_array[$story['id']]['category_name'] = Dataset::blog_categories()[$story['category']];
            }
            return json(array(
                'data' => $story_array,
                'code' => 200
            ), 200);
        }
    }

    /*API*/
    public function get($data = null){
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
            $story_array = array();

            if( isset( $_POST['id'] ) && (int)$_POST['id'] > 0 ){
                $db->where('id',Secure($_POST['id']));
            }
            $stories = $db->orderBy('created_at','DESC')->get('success_stories',null,array('*'));
            foreach ($stories as $key => $story) {
                $story_array = $story;
                $story_array['user_data'] = userData($story['user_id']);
                $story_array['story_userdata'] = userData($story['story_user_id']);
                $story_array['thumbnail'] = GetMedia($story['thumbnail']);
                $story_array['category_name'] = Dataset::blog_categories()[$story['category']];
            }
            if($story_array) {
                return json(array(
                    'data' => $story_array,
                    'code' => 200
                ), 200);
            }else{
                return json(array(
                    'code' => 400,
                    'errors' => array(
                        'error_id' => '19',
                        'error_text' => 'No story found with this id'
                    )
                ), 400);
            }
        }
    }

    public function approve_story(){
        global $db;
        if (empty($_POST['access_token']) || empty($_POST['id'])) {
            return json(array(
                'code' => 400,
                'errors' => array(
                    'error_id'   => '19',
                    'error_text' => __('Bad Request, Invalid or missing parameter')
                )
            ), 400);
        } else {
            $storyid = null;
            if( !is_numeric( Secure($_POST['id']) ) ){
                return json(array(
                    'code' => 400,
                    'errors' => array(
                        'error_id'   => '19',
                        'error_text' => __('Bad Request, Invalid or missing parameter')
                    )
                ), 400);
            }else{
                $storyid = (int)Secure($_POST['id']);
            }
            $user_id = GetUserFromSessionID(Secure($_POST['access_token']));
            $userData = userData($user_id);
            $story = $db->where('id', $storyid)->getOne('success_stories', array('quote','user_id','story_user_id'));
            if( (int)$story['user_id'] !== (int)$user_id ){
                return array(
                    'status'  => 400,
                    'message' => __('Forbidden')
                );
            }
            $saved = $db->where('id', $storyid)->where('user_id', $user_id)->update('success_stories', array(
                'status' => 1
            ));
            if ($saved) {
                $Notification = LoadEndPointResource('Notifications');
                if ($Notification) {
                    $Notification->createNotification($userData->web_device_id, $userData->id, $story['story_user_id'], 'approve_story', '', '/story/' . $storyid. '_'. url_slug($story['quote']));
                }
                return array(
                    'status'  => 200,
                    'message' => __('Story approved successfully.')
                );
            } else {
                return array(
                    'status'  => 400,
                    'message' => __('Forbidden')
                );
            }
        }
    }

    public function disapprove_story(){
        global $db;
        if (empty($_POST['access_token']) || empty($_POST['id'])) {
            return json(array(
                'code' => 400,
                'errors' => array(
                    'error_id'   => '19',
                    'error_text' => __('Bad Request, Invalid or missing parameter')
                )
            ), 400);
        } else {
            $storyid = null;
            if( !is_numeric( Secure($_POST['id']) ) ){
                return json(array(
                    'code' => 400,
                    'errors' => array(
                        'error_id'   => '19',
                        'error_text' => __('Bad Request, Invalid or missing parameter')
                    )
                ), 400);
            }else{
                $storyid = (int)Secure($_POST['id']);
            }
            $user_id = GetUserFromSessionID(Secure($_POST['access_token']));
            $userData = userData($user_id);
            $story = $db->where('id', $storyid)->getOne('success_stories', array('quote','user_id','story_user_id'));
            if( (int)$story['user_id'] !== (int)$user_id ){
                return array(
                    'status'  => 400,
                    'message' => __('Forbidden')
                );
            }
            $saved = $db->where('id', $storyid)->where('user_id', $user_id)->update('success_stories', array(
                'status' => 0
            ));
            if ($saved) {
                $Notification = LoadEndPointResource('Notifications');
                if ($Notification) {
                    $Notification->createNotification($userData->web_device_id, $userData->id, $story['story_user_id'], 'disapprove_story', '', '/story/' . $storyid. '_'. url_slug($story['quote']));
                }
                return array(
                    'status'  => 200,
                    'message' => __('Story disapproved successfully.')
                );
            } else {
                return array(
                    'status'  => 400,
                    'message' => __('Forbidden')
                );
            }
        }
    }
}