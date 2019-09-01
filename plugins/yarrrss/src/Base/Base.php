<?php
namespace tw2113\YarrRSS\Base;

class Base {

    public $postcount = 10;

    protected $wp_query;

    protected $dir;

    public function __construct() {
        $this->dir = plugin_dir_path( dirname( __FILE__ ) );
    }
}