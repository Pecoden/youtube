<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the website, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/documentation/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'youtube' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', '' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         '5gk0e~d2PgVO*fErA{,A?d_|QXvwXZ/Zoz?6Oew+}Y<YLc5JdW&P2#[x4U@3@`!z' );
define( 'SECURE_AUTH_KEY',  'xfK~BGPmBy.@|VFe}qvIjoOC[iy1Uy~{%mF)Q@kDCb%YoSDCF6h2)WM[y83I:m0y' );
define( 'LOGGED_IN_KEY',    'JDT;7NdrAIjA/ll0GcPxLXNpQ{{,h$2X ir_I@[Lz`$x2ll YBI+M>n$7!S!)n4o' );
define( 'NONCE_KEY',        ']+*g3A-eC5k>p1fg1pz{dck`IL,*jm$Gc$+sMUm.Ud0bSycn*Uh7[-R#g-6dLgz|' );
define( 'AUTH_SALT',        '*ow,!B6gS,mu#y?w0,V!((@~8ac0M5c<)Jx@$l_+tFIvT)FT1~gI}fiZ.ceGU7 ?' );
define( 'SECURE_AUTH_SALT', '*v&{$2_/:Y<2]Ip|A(Uc|Q<bFoSjbt?yjBr/D,cZ}5cfYb1DP&&B;IQS~705|krr' );
define( 'LOGGED_IN_SALT',   '-YzFP](7)v:6Dy@G[DBUKn?*C9Gvz.7N|N:ZgASV3B6m24K~T#-d}9lJ*<43tU-l' );
define( 'NONCE_SALT',       'VL7~e/xn jHBvN;Z`~,R(3-}@W~&S%/5ZRJF)oH)-&{r,qO<B.h@# ]AFSfp,(dX' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://wordpress.org/documentation/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
