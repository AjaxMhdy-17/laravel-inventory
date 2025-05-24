<?php

function cleanWithoutcharAndNumber($str)
{
    return strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $str));
}
