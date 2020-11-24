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
    public function get_langs($data = null) {
        global $conn, $wo;
        $data  = array();
        $query = mysqli_query($conn, "SHOW COLUMNS FROM `langs`");
        while ($fetched_data = mysqli_fetch_assoc($query)) {
            if( $fetched_data['Field'] == 'id' || $fetched_data['Field'] == 'ref' || $fetched_data['Field'] == 'lang_key' ){
            }else{
                $data[] = array($fetched_data['Field'] => $fetched_data['Field']);
            }
        }
        // unset($data[0]);
        // unset($data[1]);
        // unset($data[2]);
        // unset($data[3]);
        return json(array(
            'data' => $data,
            'code' => 200
        ), 200);
    }
    /*API*/
    public function get_options($data = null) {
        global $config;
        foreach( Dataset::height() as $k => $v){
            $config->Height[] = array( $k => $v);
        }
        foreach( Dataset::notification() as $k => $v){
            $config->Notification[] = array( $k => $v);
        }
        foreach( Dataset::gender() as $k => $v){
            $config->Gender[] = array( $k => $v);
        }
        foreach( Dataset::blog_categories() as $k => $v){
            $config->BlogCategories[] = array( $k => $v);
        }
        foreach( Dataset::countries() as $k => $v){
            $config->Countries[] = array( $k => $v);
        }
        foreach( Dataset::hair_color() as $k => $v){
            $config->HairColor[] = array( $k => $v);
        }
        foreach( Dataset::travel() as $k => $v){
            $config->Travel[] = array( $k => $v);
        }
        foreach( Dataset::drink() as $k => $v){
            $config->Drink[] = array( $k => $v);
        }
        foreach( Dataset::smoke() as $k => $v){
            $config->Smoke[] = array( $k => $v);
        }
        foreach( Dataset::religion() as $k => $v){
            $config->Religion[] = array( $k => $v);
        }
        foreach( Dataset::car() as $k => $v){
            $config->Car[] = array( $k => $v);
        }
        foreach( Dataset::live_with() as $k => $v){
            $config->LiveWith[] = array( $k => $v);
        }
        foreach( Dataset::pets() as $k => $v){
            $config->Pets[] = array( $k => $v);
        }
        foreach( Dataset::friends() as $k => $v){
            $config->Friends[] = array( $k => $v);
        }
        foreach( Dataset::children() as $k => $v){
            $config->Children[] = array( $k => $v);
        }
        foreach( Dataset::character() as $k => $v){
            $config->Character[] = array( $k => $v);
        }
        foreach( Dataset::body() as $k => $v){
            $config->Body[] = array( $k => $v);
        }
        foreach( Dataset::ethnicity() as $k => $v){
            $config->Ethnicity[] = array( $k => $v);
        }
        foreach( Dataset::education() as $k => $v){
            $config->Education[] = array( $k => $v);
        }
        foreach( Dataset::work_status() as $k => $v){
            $config->WorkStatus[] = array( $k => $v);
        }
        foreach( Dataset::relationship() as $k => $v){
            $config->Relationship[] = array( $k => $v);
        }
        foreach( Dataset::language() as $k => $v){
            $config->Language[] = array( $k => $v);
        }
        $config->custom_fields = GetProfileFields('admin');
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
                'file',
                'is_pro'
            ));
            $stikcer_data = array();
            foreach ($stikcers as $key => $value) {
                $stikcer_data[] = array(
                    'id' => $value->id,
                    'file' => GetMedia(trim($value->file)),
                    'is_pro' => $value->id_pro
                );
            }
            return json(array(
                'data' => $stikcer_data,
                'code' => 200
            ), 200);
        }
    }
    /*API*/
    public function upload_bank_recipt() {
        global $db,$_UPLOAD,$_DS,$config;
        if (!isset($_FILES['receipt']) || empty($_FILES['receipt'])) {
            return json(array(
                'code' => 400,
                'errors' => array(
                    'error_id' => '35',
                    'error_text' => __('Bad Request, Invalid or missing parameter')
                )
            ), 400);
        }
        if (empty($_POST['access_token'])||empty($_POST['transfer_mode'])||empty($_POST['price'])||empty($_POST['description'])) {
            return json(array(
                'code' => 400,
                'errors' => array(
                    'error_id' => '19',
                    'error_text' => __('Bad Request, Invalid or missing parameter')
                )
            ), 400);
        } else {
            if( $_POST['transfer_mode'] !== 'credits' && $_POST['transfer_mode'] !== 'membership' ){
                return json(array(
                    'code' => 400,
                    'errors' => array(
                        'error_id' => '20',
                        'error_text' => __('Bad Request, Invalid or missing parameter')
                    )
                ), 400);
            }
            $user_id     = GetUserFromSessionID(Secure($_POST['access_token']));
            $mode        = secure($_POST['transfer_mode']);
            $price       = (int) secure($_POST['price']);
            $description = secure($_POST['description']);
            $data        = array();

            $file  = '';
            if (!file_exists($_UPLOAD . 'photos' . $_DS . date('Y'))) {
                mkdir($_UPLOAD . 'photos' . $_DS . date('Y'), 0777, true);
            }
            if (!file_exists($_UPLOAD . 'photos' . $_DS . date('Y') . $_DS . date('m'))) {
                mkdir($_UPLOAD . 'photos' . $_DS . date('Y') . $_DS . date('m'), 0777, true);
            }
            $dir = $_UPLOAD . 'photos' . $_DS . date('Y') . $_DS . date('m');
            $ext = pathinfo($_FILES['receipt']['name'], PATHINFO_EXTENSION);
            $key = GenerateKey();
            $filename = $dir . $_DS . $key . '.' . $ext;
            if (move_uploaded_file($_FILES['receipt']['tmp_name'], $filename)) {
                $thumbfile = '../upload' . $_DS . 'photos' . $_DS .  date('Y') . $_DS . date('m') . $_DS . $key . '_avater.' . $ext;
                $thumbnail = new ImageThumbnail($filename);
                $thumbnail->setSize($config->profile_picture_width_crop, $config->profile_picture_height_crop);
                $thumbnail->save($thumbfile);
                @unlink($filename);
                if (is_file($thumbfile)) {
                    $upload_s3 = UploadToS3($thumbfile, array(
                        'amazon' => 0
                    ));
                }
                $info                  = array();
                $info[ 'user_id' ]     = $user_id;
                $info[ 'receipt_file' ]= 'upload/photos/' . date('Y') . '/' . date('m') . '/' . $key . '_avater.' . $ext;
                $info[ 'created_at' ]  = date('Y-m-d H:i:s');
                $info[ 'description' ] = $description;
                $info[ 'price' ]       = $price;
                $info[ 'mode' ]        = $mode;
                $info[ 'approved' ]    = 0;
                $saved                 = $db->insert('bank_receipts', $info);
                if($saved){
                    return json(array(
                        'data' => $saved,
                        'code' => 200
                    ), 200);
                }else{
                    return json(array(
                        'code' => 400,
                        'errors' => array(
                            'error_id' => '63',
                            'error_text' => __('Bad Request, Invalid or missing parameter')
                        )
                    ), 400);
                }
            }else{
                return json(array(
                    'code' => 400,
                    'errors' => array(
                        'error_id' => '43',
                        'error_text' => __('Bad Request, Invalid or missing parameter')
                    )
                ), 400);
            }
        }
    }
}