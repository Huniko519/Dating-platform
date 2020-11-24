<?php
class Options {
    private $_table = 'options';
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
            }
        }
    }
    /*API*/
    public function get_options($data = null) {
        global $config;
        return json(array(
            'message' => __('Options loaded successfully.'),
            'data' => $config,
            'code' => 200
        ), 200);
    }
    /*API*/
    public function get_gifts() {
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
            $gifts     = $db->objectBuilder()->orderBy('id', 'desc')->get('gifts', null, array(
                'id',
                'media_file'
            ));
            $gift_data = array();
            foreach ($gifts as $key => $value) {
                $gift_data[] = array(
                    'id' => $value->id,
                    'file' => GetMedia(trim($value->media_file))
                );
            }
            return json(array(
                'data' => $gift_data,
                'code' => 200
            ), 200);
        }
    }
    /*API*/
    public function get_stickers() {
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
            $stikcers     = $db->objectBuilder()->orderBy('id', 'desc')->get('stickers', null, array(
                'id',
                'file'
            ));
            $stikcer_data = array();
            foreach ($stikcers as $key => $value) {
                $stikcer_data[] = array(
                    'id' => $value->id,
                    'file' => GetMedia(trim($value->file))
                );
            }
            return json(array(
                'data' => $stikcer_data,
                'code' => 200
            ), 200);
        }
    }
}