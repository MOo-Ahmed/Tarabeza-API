<?php
return
[
    // API version
    "version" => "v1",

    // API URL
    "api_url" => "localhost",

    // logo url URL
    "logos_base_url" => "localhost/api/thumb.php?t=l&w=200&h=180&src=",

    // API Documentation URL
    "documentation_url" => "",

    // API URL
    "recommendation_systems" => 
    [
        "restaurant_url" => "https://nofipay.net/restaurant-recommender-system/restaurants/recommendations",
    ],

    // API Debug Mode
    "debug" => true,

    // JWT secret key
    "JWT_secret_key" => "b0P1RXh70QEAgqvCKGL3Rf1odXAhgFtxNaDOiaoJMFiyDeqkayNw6fI9eHWGJrkKgcPBpGal1acLWhLQ44nldyfTErGIzas9TV1eXQ9IS2aL9Tv7N5PIRYx8gGHq6OWPRkI7MerqWfOLUspeQtMdNUiIGHC5UhdhZ9hCYSxb5kyRzlXw9i1JIL6xPGM7mgjyhRwws59U4RZTzzc3N8d1roeFeLIHE6QJnFhDxiO7pLeB83kA6KcyCwAj9OoiaQeJxvVRVW4ai3C2Tmg04IvUE6EeX2pxshEGVl8xFi0ouJenSx4lN6ofdZVdTOQEnKAxHCEjA3vZTk9UpBAzyYt5JJAqSdT5xBxsKKxl6hEgiPZMrGy2afCUOR0uSjKeyc6H32Raspjtge1uV8gQcdfNpiroga6RwDGy40M49sM5fjcdCKMTaqyQZENWY5Fx1nfsB6IHpe8UvPnuC5sLwMz6YO2Kx0cfQXaEx3BGbtaQ9CaHJoGGFYOrGaYwnGSSv8Wq",

    // JWT cipher
    "JWT_cipher" => "HS256",

    // JWT time to live
    // 864000 Seconds to Days = 10
    "JWT_time_to_live" => 864000,
];