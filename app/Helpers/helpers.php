<?php

if (! function_exists('word_limit')) {
    function word_limit($string, $word_limit) {
        $words = explode(' ', $string);
        return implode(' ', array_slice($words, 0, $word_limit)) . (count($words) > $word_limit ? '...' : '');
    }
}

if (! function_exists('char_limit')) {
    function char_limit($string, $char_limit) {
        $cleanString = strip_tags($string);
        return mb_substr($cleanString, 0, $char_limit) . (mb_strlen($cleanString) > $char_limit ? '...' : '');
    }
}
