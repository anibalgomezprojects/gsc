<?php
function check_blank($content)
{
    $check = str_replace(" ", "", $content);     // strips away all spaces so that only characters are left.
    if ($check)
    {
        return false;
        // if there's stuff left after the spaces are taken away then it's not empty so it returns false.
    }
    else
    {
        return true;
        // the whole string was spaces so it return true.
    }
}
// if (check_blank($message)) { die(); // the message was blank so the page doesn't load. }
?>