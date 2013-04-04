<?php

/**
 * cookie stuff
 */
define("COOKIE_UN", "sd_un");
define("COOKIE_TOKEN", "sd_token");
define("COOKIE_EXPIRATION", 3600 * 24 * 180);
define("COOKIE_PATH", "/");

/**
 * session stuff
 */
define("SESSION_UN", "s_username");
define("SESSION_TOKEN", "s_token");


include(BASE_PATH. "lib/auth/check_auth.php");
