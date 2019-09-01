<?php
namespace tw2113\YarrRSS\Base;

interface BaseInterface {
    public function hooks();

    public function loadTemplate();

    public function declareFeed();
}