<?php

class YA_REST_Authentication
{


    protected $urlTemplate = 'api/(.*?)/(.+)';

    private static $__instance = null;

    /**
     * @return YA_REST_Authentication
     */
    public static function getInstance()
    {
        if (self::$__instance === null) {
            self::$__instance = new YA_REST_Authentication();
        }
        return self::$__instance;
    }

    private function __construct()
    {
        add_action('init', array($this,'init'));
    }

    public function init()
    {

        global $wp_rewrite;
        /** @var WP_Rewrite $wp_rewrite */

        $rules = get_option( 'rewrite_rules' );

        if (true || !isset($rules[$this->urlTemplate])) {
            $wp_rewrite->add_rule($this->urlTemplate, 'index.php?api_key=$matches[1]&rest_route=/wp/v2/$matches[2]');
            $wp_rewrite->add_rule('wp-json/(.*?)/wp/v2/(.+)', 'index.php?api_key=$matches[1]&rest_route=/wp/v2/$matches[2]');
            $wp_rewrite->flush_rules(  );
        }

        add_filter( 'query_vars', array($this, 'registerQueryVars') );

        add_filter( 'rest_authentication_errors', array($this, 'checkAuthorizationFilter'));
    }

    public function checkAuthorizationFilter( $result ) {

        if ( ! empty( $result ) ) {
            return $result;
        }

        if (preg_match('/wp-json\/(.*?)\/wp\/v2\/(.+)/', $_SERVER['REQUEST_URI'], $m)) {
            $key = $m[1];
        } elseif (preg_match('/api\/(.*?)\//', $_SERVER['REQUEST_URI'], $m)) {
            $key = $m[1];
        }

        if ( isset($key) || ! is_user_logged_in() ) {
            if (!isset($key) || !$this->checkAPIKey($key)) {
                return new WP_Error( 'yachtbase_api_key_wrong', 'Valid API key is missing.', array( 'status' => 401 ) );
            }
        }
        return $result;
    }

    public function registerQueryVars($vars)
    {
        $vars[] = 'api_key';
        return $vars;
    }

    public function checkAPIKey($key)
    {
        $client = WP_REST_OAuth1_Client::get_by_key( $key );
        return $client instanceof WP_Post;
    }

}