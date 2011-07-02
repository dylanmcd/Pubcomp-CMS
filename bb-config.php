<?php
/** 
 * The base configurations of bbPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys and bbPress Language. You can get the MySQL settings from your
 * web host.
 *
 * This file is used by the installer during installation.
 *
 * @package bbPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for bbPress */
define( 'BBDB_NAME', 'pubcomp' );

/** MySQL database username */
define( 'BBDB_USER', 'root' );

/** MySQL database password */
define( 'BBDB_PASSWORD', '' );

/** MySQL hostname */
define( 'BBDB_HOST', 'localhost' );

/** Database Charset to use in creating database tables. */
define( 'BBDB_CHARSET', 'utf8' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'BBDB_COLLATE', '' );

/**#@+
 * Authentication Unique Keys.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/bbpress/ WordPress.org secret-key service}
 *
 * @since 1.0
 */
define( 'BB_AUTH_KEY', 'aEk}/QRg.jiUndayIU).Kr=*+Qnr<6`n(mMd 7g0l|%2@jj1-/ZKk(gO?v)Sk`ZQ' );
define( 'BB_SECURE_AUTH_KEY', '6+!=l9j;QOW-$m]Cm*gz<P}:4ogfxF^:gqs4c%F~}TnnLqk!;;WBBm<En$loD`1D' );
define( 'BB_LOGGED_IN_KEY', 'h4@5dZD3d:<-W IvE9-Z%`CXxesB1Kdjq61E3Z C@VWpt!D,96;1KZ{Wf:=RD,Lr' );
define( 'BB_NONCE_KEY', 'WTxy?f.7S|sU,cI1y^?T#@k-3l*MR,qkvW Wvgh[lv$SZDXohu O)ZSFrab8*v=c' );
/**#@-*/

/**
 * bbPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$bb_table_prefix = 'wp_bb_';

/**
 * bbPress Localized Language, defaults to English.
 *
 * Change this to localize bbPress. A corresponding MO file for the chosen
 * language must be installed to a directory called "my-languages" in the root
 * directory of bbPress. For example, install de.mo to "my-languages" and set
 * BB_LANG to 'de' to enable German language support.
 */
define( 'BB_LANG', '' );

$bb->custom_user_table = 'wp_users';
$bb->custom_user_meta_table = 'wp_usermeta';

$bb->uri = 'http://localhost/pubcomp/wp-content/plugins/buddypress/bp-forums/bbpress/';
$bb->name = ' Forums';

define('BB_AUTH_SALT', 'Y$CbgU3CYCnJWy}>UO.wX>~i8#@ckRIa^#LT9p,%w8C;FL6G-M(d_JXld10h(9~^');
define('BB_LOGGED_IN_SALT', 'h@~kN2v9W8?/ WO&P%z4H{BB/}/@ExCJvoGpP!/2m`:SY}AmMVkF@:85@a$Og15W');
define('BB_SECURE_AUTH_SALT', '&1#TQf-:9iOz3E%E#@<?9.|E<wc7QR6.`MU>yH,^+CDv6)UarrOR?g(zTl]9CX1x');

define('WP_AUTH_COOKIE_VERSION', 2);

?>