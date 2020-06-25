<?php

namespace Linktracker\Tracking\Api;

interface ConfigInterface
{
    public const TRACKING_TABLE             = 'linktracker_tracking';

    public const STATUS_NEW                 = 1;
    public const STATUS_SEND                = 2;
    public const STATUS_RESEND              = 3;
    public const STATUS_FAILED              = 4;

    public const COOKIE_NAME                = 'lt_clickid';
    public const COOKIE_DURATION            = 86400 * 30; //in seconds, 30 days

    public const TRACKING_REQUEST_PARAMETER = 'lt_clickid';

    public const API_CONNECTION_TIMEOUT     = 3;
    public const API_PARAM_ORDER_ID         = 'order_id';
    public const API_PARAM_AMOUNT           = 'order_amount';
    public const API_PARAM_ID               = 'click_id';

}
