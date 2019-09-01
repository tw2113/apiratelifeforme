<?php
namespace tw2113\YarrRSS\LastFM;

use tw2113\YarrRSS\Base\Base;
use tw2113\YarrRSS\Base\BaseInterface;

class LastFM extends Base implements BaseInterface {

    public function __construct( \WP_Query $wp_query ) {
        $this->wp_query = $wp_query;
    }

    public function hooks() {

    }

    public function loadTemplate() {

    }
}