<?php

class Config
{
    //	Cookie settings
    public $Cookie_enc_key;
    public $Login_cookie_name;

    // Boolean flag to prevent instantiating constructor directly
    public static $context = false;

    //	Static files version number
    public $version = '1.1.3';

    // is this a Single Page App? Default is `false`
    public $spa = true;


    public $absPath;

    private static $instance = null;

    public function __construct()
    {
        if ( ! self::$context )
            throw new Exception("You are not allowed to call constructor directly.", 1);
            
        $this->setup();
        self::$context = false;
        return $this;
    }

    public static function newInstance()
    {
        if ( is_null( self::$instance ) ){
            self::$context = true;
            self::$instance = new self();
        }
            
        return self::$instance;
    }

    

    private function setup()
    {
        $dotenv = new Dotenv\Dotenv(__DIR__);
        $dotenv->load();

        // Setup config constants
        $this->Cookie_enc_key = $_ENV[ 'COOKIE_ENC_KEY' ];
        $this->Login_cookie_name = $_ENV[ 'COOKIE_NAME' ];

        //	URL and protocol settings
        $scheme = $_SERVER['REQUEST_SCHEME'] ?? 'http';
        $scheme = ( ( isset( $_SERVER['HTTPS'] ) && $_SERVER['HTTPS'] == 'on') || ( isset( $_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == '443') ) ? 'https' : $scheme;
        $this->home_url = $scheme.'://'.$_SERVER['HTTP_HOST'] ;
        if (isset($_SERVER['PORT']) && ( ! array_search($_SERVER['PORT'], ['80', '8080', '443'])))
        $this->home_url = $this->home_url.":".$_SERVER['PORT'];

        // Setup directories
        $this->absPath = __DIR__.'/..';
        //	Directory containing core files
        $this->core_dir = $this->absPath.'/app/core';

        //	Includes directory
        $this->lib_dir = $this->absPath.'/app/lib';

        //	Class files directory
        $this->class_dir = $this->lib_dir.'/classes';

        //	Models
        $this->models = $this->absPath.'/app/models';

        //	Views
        $this->views = $this->absPath.'/app/views';

        //	Node modules
        $this->node = $this->absPath.'/node_modules';

        //	Templates directory
        $this->templates = $this->absPath.'/app/templates';

        //	Assets directory
        $this->assets_dir = $this->absPath.'/public/static/';

        //	Site image assets
        $this->img = $this->home_url.'/img';

        //	Profile pictures directory
        $this->profile_pictures_dir = $this->assets_dir.'img/profile';

        //	Profile pictures URI
        $this->profile_pictures_uri = $this->img.'/profile';

        //	Stylesheets
        $this->css = $this->home_url.'/css';

        //	Javascript files
        $this->js = $this->home_url.'/js';

        // Photos Base Url
        $this->photos = $this->home_url.'/photos';

        // Videos Base Url
        $this->videos = $this->home_url.'/videos';

        /**********************
        **** Templating *******
        **********************/

        //	Default page title
        $this->pageTitle = 'Welcome to '.basename(__DIR__);

        //	Templating
        $this->templateTags = [];
        $this->footerModals = [];
        $this->modalTags = [ 'version' => '?ver='.$this->version ];

        //	Header scripts
        $this->headerScripts = [];

        //	Footer scripts
        $this->footerScripts = [];
    }
}