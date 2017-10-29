<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** MySQL settings ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'fictionalU' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', 'root' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         's}A;QHs S]9iaM3?<)vNW$?kb9!)/]PIW:j=yI~Yj6_o:-aKdm/j49X8DqZ-Z8u)');
define('SECURE_AUTH_KEY',  '%wokIGy+#aU>}}Yv=X0!VVnyD/Rx EP^RN9ZhGLg,!b[h$8D<: *w:|g;K .:jjZ');
define('LOGGED_IN_KEY',    'j=W]+yjb4(HM@eE|.y8c6U?30L/2$Kjs%;Jq=SOLZ&8R1=Mb#BQ?-Vt)+lY>P9d5');
define('NONCE_KEY',        'HqV~QYcMJwiqlyG=I8]`Nh87=/Jw^Wxbd]}f}n0AiD@m);z;+q^5I5-=Ex3:z:p:');
define('AUTH_SALT',        '>(1u-=CqdhN,37=:Zs>2<E-{pgB+:>}/IvdtSnp>Y4j-C#5zX_4dSaV)*aX2j!f%');
define('SECURE_AUTH_SALT', '4RCf0y%bWMBC<BfuR`f&4dFKcE_i!%rT[cE.r3_!J7F,0-a*sR?{$-Krlk)o7<g>');
define('LOGGED_IN_SALT',   '.mOFR/=:6Q1HLIN-$=fIWs?2lS9H)ptSLy-&.j_>c!^-me^Fu4K.J%.EW<Or/+Y*');
define('NONCE_SALT',       'Ji!BdvzqeFjR32`58p5t{VR3BxRC3kV1?B|,L{>)L-h/3*R%P/^z*5kFs%q?`$LE');


/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';


define( 'WP_DEBUG', true );

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) )
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
