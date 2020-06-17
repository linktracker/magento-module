<?php

namespace Linktracker\Tracking\Api;

interface Config
{
    const TRACKING_TABLE = 'linktracker_tracking';

    const STATUS_NEW = 1;
    const STATUS_SEND = 2;
    const STATUS_RESEND = 3;
    const STATUS_FAILED = 4;

    const STATUS = [
        self::STATUS_CREATED => 'new',
        self::STATUS_SEND => 'send',
        self::STATUS_RESEND => 'resend',
        self::STATUS_FAILED => 'failed',
    ];

    const COOKIE_NAME = 'ltt-cookie';
    const COOKIE_DURATION = 86400; //in seconds, one day

    const TRAKING_REQUEST_PARAMETER = 'tracking';
}
