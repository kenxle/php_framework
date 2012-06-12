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
define("SESSION_UN", "s_dibs_username");
define("SESSION_TOKEN", "s_dibs_token");

define("ADMIN_MASK", 1);
define("SHIPPER_MASK", 2);
define("DEALER_MASK", 4);
define("EDITOR_MASK", 8);
define("MANAGING_EDITOR_MASK", 16);

include(BASE_PATH. "lib/auth/check_auth.php");
