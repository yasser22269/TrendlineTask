<?php

namespace App\Consts;

class ResponseMessageConsts
{
    public static function SUCCESS_MESSAGE()
    {
        return __('messages.success');
    }

    public static function FAILURE_MESSAGE()
    {
        return __('messages.failure');
    }

    public static function ERR_VALIDATION_RESPONSE()
    {
        return __('messages.validation_error');
    }

    public static function UNAUTHORIZED_MESSAGE()
    {
        return __('messages.unauthorized');
    }

    public static function FORBIDDEN_MESSAGE()
    {
        return __('messages.forbidden');
    }

    public static function NOT_FOUND_MESSAGE()
    {
        return __('messages.not_found');
    }

    public static function METHOD_NOT_ALLOWED_MESSAGE()
    {
        return __('messages.method_not_allowed');
    }

    public static function EXPIRED()
    {
        return __('messages.otp_expired');
    }

    public static function EXISTS()
    {
        return __('messages.exists');
    }
}
