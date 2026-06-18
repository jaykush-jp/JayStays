<?php
return [
    'postmark' => ['token' => env('POSTMARK_TOKEN')],
    'ses'      => ['key'=>env('AWS_ACCESS_KEY_ID'),'secret'=>env('AWS_SECRET_ACCESS_KEY'),'region'=>env('AWS_DEFAULT_REGION','us-east-1')],
    'resend'   => ['key' => env('RESEND_KEY')],
    'slack'    => ['notifications' => ['bot_user_oauth_token'=>env('SLACK_BOT_USER_OAUTH_TOKEN'),'channel'=>env('SLACK_BOT_USER_DEFAULT_CHANNEL')]],
    'google'   => ['client_id'=>env('GOOGLE_CLIENT_ID'),'client_secret'=>env('GOOGLE_CLIENT_SECRET'),'redirect'=>env('GOOGLE_REDIRECT_URI')],
    'razorpay' => ['key_id'=>env('RAZORPAY_KEY_ID'),'key_secret'=>env('RAZORPAY_KEY_SECRET')],
    'phonepe'  => ['merchant_id'=>env('PHONEPE_MERCHANT_ID'),'salt_key'=>env('PHONEPE_SALT_KEY'),'salt_index'=>env('PHONEPE_SALT_INDEX',1),'env'=>env('PHONEPE_ENV','UAT')],
    'fast2sms' => ['api_key' => env('FAST2SMS_API_KEY')],
];
