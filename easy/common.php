<?php
/**
 * common functions
 */
function outputjson($json, $header = NULL, $exit = TRUE) {
    $data = is_string($json) ? $json : json_encode($json);
    if (is_array($header)) {
        foreach ($header as $h) {
            header($h);
        }
    } elseif (is_string($header)) {
        header($header);
    }
    header("Content-Type: application/json; charset=UTF-8");
    echo $data;
    if ($exit) {
        exit;
    }
}