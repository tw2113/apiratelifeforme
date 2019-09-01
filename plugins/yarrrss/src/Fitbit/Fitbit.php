<?php
namespace tw2113\YarrRSS\Fitbit;

use tw2113\YarrRSS\Base\Base;

class Fitbit extends Base implements BaseInterface {

    public function __construct( \WP_Query $wp_query  ) {
        $this->wp_query = $wp_query;
    }

    public function hooks() {
    }

    public function loadTemplate() {
    }
}