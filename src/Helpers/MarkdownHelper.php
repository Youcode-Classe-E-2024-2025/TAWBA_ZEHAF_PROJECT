<?php

require_once __DIR__ . '/../../vendor/autoload.php';
use Parsedown;
class MarkdownHelper {
    public static function parse($text) {
        $parsedown = new Parsedown();
        return $parsedown->text($text);
    }
}