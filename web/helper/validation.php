<?php

/**
 * Convert utf8 string into unicode array
 * @param string $str
 * @return array
 */
function utf8_to_unicode($str) {

    $unicode = array();
    $values = array();
    $lookingFor = 1;

    for ($i = 0; $i < strlen($str); $i++) {

        $thisValue = ord($str[$i]);

        if ($thisValue < 128)
            $unicode[] = $thisValue;
        else {

            if (count($values) == 0)
                $lookingFor = ( $thisValue < 224 ) ? 2 : 3;

            $values[] = $thisValue;

            if (count($values) == $lookingFor) {

                $number = ( $lookingFor == 3 ) ?
                        ( ( $values[0] % 16 ) * 4096 ) + ( ( $values[1] % 64 ) * 64 ) + ( $values[2] % 64 ) :
                        ( ( $values[0] % 32 ) * 64 ) + ( $values[1] % 64 );

                $unicode[] = $number;
                $values = array();
                $lookingFor = 1;
            } // if
        } // if
    } // for

    return $unicode;
}

/**
 * Detect whether a unicode char is in CJK
 * @param int $char code point of this unicode char
 * @return bool TRUE for in CJK
 */
function detect_CJK($char) {
    $CJKrange = array(
        array(0x3400, 0x4DB5),
        array(0x4E00, 0x9FA5),
        array(0x9FA6, 0x9FBB),
        array(0xF900, 0xFA2D),
        array(0xFA30, 0xFA6A),
        array(0xFA70, 0xFAD9),
        array(0x20000, 0x2A6D6),
        array(0x2F800, 0x2FA1D),
        array(0xFA30, 0xFA6A),
        array(0xFA70, 0xFAD9),
        array(0x20000, 0x2A6D6),
        array(0x2F800, 0x2FA1D)
    );
    foreach ($CJKrange as $range) {
        if ($char >= $range[0] && $char <= $range[1]) {
            return TRUE;
        }
    }
    return FALSE;
}

/**
 * Check whether a string is composed with chinese chars
 * @param string $str UTF8-encoded str
 * @return bool TRUE for chinese str
 */
function is_chinese($str) {
    $unicode = utf8_to_unicode($str);
    foreach ($unicode as $char) {
        if (!detect_CJK($char)) {
            return FALSE;
        }
    }
    return TRUE;
}

/**
 * Check whether a student id is valid
 * @param type $str
 * @return type 
 */
function is_student_id_valid($str) {
    return ctype_digit($str) && strlen($str) == 8;
}

/**
 * Check whether the profile info is complete for the registering courses
 * @global type $login_uid
 * @global type $logged
 * @return type 
 */
function is_info_complete() {
    if (is_admins() || is_manager())
        return true;
    global $login_uid;
    global $logged;
    if (!$logged)
        return false;
    $userTbl = new UserTbl($login_uid);
    if (!$userTbl->Get()) {
        return false;
    }
    $user = $userTbl->detail;
    return trim($user['cn_name'])
            && is_chinese($user['cn_name'])
            && trim($user['major'])
            && trim($user['grade'])
            && is_grade_valid($user['grade'])
            && trim($user['class'])
            && trim($user['student_id'])
            && is_student_id_valid($user['student_id']);
}

/**
 * Check whether a email is valid
 * @param type $str
 * @return type 
 */
function is_email_valid($str) {
    return preg_match(
                    '/^[a-zA-Z0-9][._+a-zA-Z0-9]*@[a-zA-Z0-9]+\.[.a-zA-Z0-9]+$/', $str);
}

/**
 * Check whether a grade number is valid
 * @param type $str
 * @return type 
 */
function is_grade_valid($str) {
    return ctype_digit($str)
            && intval($str) >= 2000
            && intval($str) <= 2020;
}



?>
