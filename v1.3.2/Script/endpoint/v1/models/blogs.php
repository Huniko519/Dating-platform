<?php
class Blogs{
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
    public function Articles($data = null){
        global $db,$site_url;
        if (empty($_POST['access_token'])) {
            return json(array(
                'code' => 400,
                'errors' => array(
                    'error_id' => '19',
                    'error_text' => __('Bad Request, Invalid or missing parameter')
                )
            ), 400);
        } else {
            $article_array = array();
            $limit = ( isset($_POST['limit']) && is_numeric($_POST['limit']) && (int)$_POST['limit'] > 0 ) ? (int)$_POST['limit'] : 20;
            $offset  = ( isset($_POST['offset']) && is_numeric($_POST['offset']) && (int)$_POST['offset'] > 0 ) ? (int)$_POST['offset'] : 0;

            if( isset( $_POST['category_id'] ) && (int)$_POST['category_id'] > 0 ){
                $db->where('category',Secure($_POST['category_id']));
            }

            $db->where('id',$offset , '>');

            $keyword = '';
            if(isset($_POST['search_keyword']) && $_POST['search_keyword'] !== ''){
                $keyword = Secure($_POST['search_keyword']);
            }
            if($keyword !== ''){
                $db->where("title", '%'.$keyword.'%', 'like');
                $db->orWhere("content", '%'.$keyword.'%', 'like');
                $db->orWhere("description", '%'.$keyword.'%', 'like');
                $db->orWhere("tags", '%'.$keyword.'%', 'like');
            }

            $articles = $db->orderBy('created_at','DESC')->get('blog',$limit,array('*'));
            foreach ($articles as $key => $article) {
                $article['thumbnail'] = GetMedia($article['thumbnail']);
                $article['category_name'] = Dataset::blog_categories()[$article['category']];
                $article['url'] = $site_url.'/article/'.$article['id'].'_'.url_slug(html_entity_decode($article['title']));
                $article_array[] = $article;
            }
            return json(array(
                'data' => $article_array,
                'code' => 200
            ), 200);
        }
    }

    /*API*/
    public function Article($data = null){
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
            $article_array = array();

            if( isset( $_POST['id'] ) && (int)$_POST['id'] > 0 ){
                $db->where('id',Secure($_POST['id']));
            }

            $articles = $db->orderBy('created_at','DESC')->get('blog',1,array('*'));
            foreach ($articles as $key => $article) {
                $article_array = $article;
                $article_array['thumbnail'] = GetMedia($article['thumbnail']);
                $article_array['category_name'] = Dataset::blog_categories()[$article['category']];
            }
            if($article_array) {
                return json(array(
                    'data' => $article_array,
                    'code' => 200
                ), 200);
            }else{
                return json(array(
                    'code' => 400,
                    'errors' => array(
                        'error_id' => '19',
                        'error_text' => 'No article found with this id'
                    )
                ), 400);
            }
        }
    }

    /*API*/
    public function BlogCategories($data = null){
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
            return json(array(
                'data' => Dataset::blog_categories(),
                'code' => 200
            ), 200);
        }
    }
}