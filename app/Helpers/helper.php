<?php

if (! function_exists('isValidUuid')) {
    function isValidUuid($saveUuidFromCall)
    {
        $uuidPattern = '/^[0-9a-fA-F]{8}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{12}$/';

        return preg_match($uuidPattern, $saveUuidFromCall);
    }
}

if (! function_exists('isValidYear')) {
    function isValidYear($saveYearFromCall)
    {
        return preg_match('/^\d{4}$/', $saveYearFromCall);
    }
}
