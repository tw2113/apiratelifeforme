<?php
namespace tw2113\YarrRSS\LastFM;

use tw2113\YarrRSS\Base\Base;
use tw2113\YarrRSS\Base\BaseInterface;

class LastFM extends Base implements BaseInterface {

    public function __construct( \WP_Query $wp_query ) {
        $this->wp_query = $wp_query;
        $this->classname = $this->get_class_name_base();

        parent::__construct();
    }

    public function hooks() {
        add_action( 'init', [ $this, 'declareFeed' ] );
    }

    public function declareFeed() {
        $feed = strtolower( $this->classname );
        add_feed( $feed, [ $this, 'loadTemplate' ] );
    }

    public function loadTemplate() {
        require_once $this->dir . "templates/{$this->classname}.php";
    }

    public function get_class_name_base() {
        $name = explode('\\', __CLASS__);

        return array_pop( $name );
    }
}