<?php
Class Story extends Theme {
    public static $page_data = array('title' => 'Story');
    public static $partial = 'story';
    public static function init_data() {
        global $config;
        parent::init_data();
        if (isset(self::$page_data['title']) && self::$page_data['title'] !== '') {
            parent::$data['title'] = __('Story') . ' . ' . $config->site_name;
        }
        parent::$data['name'] = self::$partial;
    }
    public static function show($partial = '') {
        global $config,$db;
        self::init_data();

        if (!empty(route(2))) {

            $arr = explode("_",route(2));
            $story      = $db->where('id', Secure((int)$arr[0]) )->getOne('success_stories',array('*'));
            if( !$story ){
                header('location: ' . $config->uri);
                exit();
            }

            if( (int)self::ActiveUser()->id !== (int)$story['user_id'] ) {
                if ($story['status'] == "0") {
                    header('location: ' . $config->uri);
                    exit();
                }
            }

            $story['user1Data']      = userData($story['user_id']);
            $story['user2Data']      = userData($story['story_user_id']);
            parent::$data['story'] = $story;

            $quote = $story['quote'];
            if ($quote !== '') {
                parent::$data['title'] = $quote . ' . ' . $config->site_name;
            }

            parent::show(self::$partial);
        } else {
            header('location: ' . $config->uri);
            exit();
        }
    }
}