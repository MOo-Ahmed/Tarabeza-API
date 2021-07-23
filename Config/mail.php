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
            "host" => "smtp.gmail.com",
            "port" => 465,
            "encryption" => "tls",
            "username" => "engr2017@gmail.com",
            "password" => "bellaciao99",
        ],
    ],

    /**
     *  From Address
     *  A name and address that is used globally for all e-mails that are sent by your application.
     */
    "from" =>
    [
        "address" => "engr2017@gmail.com",
        "name" => "Tarabeza",
    ],
];