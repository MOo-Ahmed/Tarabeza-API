<?php

return
[
    // Default Mailer
    "default" => "smtp",

    // Mailer Configurations
    "mailers" =>
    [
        "smtp" =>
        [
            "transport" => "smtp",
            "host" => "mail.nofipay.net",
            "port" => 465,
            "encryption" => "tls",
            "username" => "api@nofipay.net",
            "password" => "kY58&.D^#@Uk",
        ],
    ],

    /**
     *  From Address
     *  A name and address that is used globally for all e-mails that are sent by your application.
     */
    "from" =>
    [
        "address" => "api@nofipay.net",
        "name" => "test",
    ],
];