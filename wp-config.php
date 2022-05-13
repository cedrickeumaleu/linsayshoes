<?php
/**
 * La configuration de base de votre installation WordPress.
 *
 * Ce fichier est utilisé par le script de création de wp-config.php pendant
 * le processus d’installation. Vous n’avez pas à utiliser le site web, vous
 * pouvez simplement renommer ce fichier en « wp-config.php » et remplir les
 * valeurs.
 *
 * Ce fichier contient les réglages de configuration suivants :
 *
 * Réglages MySQL
 * Préfixe de table
 * Clés secrètes
 * Langue utilisée
 * ABSPATH
 *
 * @link https://fr.wordpress.org/support/article/editing-wp-config-php/.
 *
 * @package WordPress
 */

// ** Réglages MySQL - Votre hébergeur doit vous fournir ces informations. ** //
/** Nom de la base de données de WordPress. */
define( 'DB_NAME', 'linsayshoes' );

/** Utilisateur de la base de données MySQL. */
define( 'DB_USER', 'root' );

/** Mot de passe de la base de données MySQL. */
define( 'DB_PASSWORD', 'root' );

/** Adresse de l’hébergement MySQL. */
define( 'DB_HOST', 'localhost' );

/** Jeu de caractères à utiliser par la base de données lors de la création des tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/**
 * Type de collation de la base de données.
 * N’y touchez que si vous savez ce que vous faites.
 */
define( 'DB_COLLATE', '' );

/**#@+
 * Clés uniques d’authentification et salage.
 *
 * Remplacez les valeurs par défaut par des phrases uniques !
 * Vous pouvez générer des phrases aléatoires en utilisant
 * {@link https://api.wordpress.org/secret-key/1.1/salt/ le service de clés secrètes de WordPress.org}.
 * Vous pouvez modifier ces phrases à n’importe quel moment, afin d’invalider tous les cookies existants.
 * Cela forcera également tous les utilisateurs à se reconnecter.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'N)v71i/ _amSc7Flk$+5DVcf3pu/H!e9`te=Zfx*B6@%JV)Pourx |vRjtSeWw]|' );
define( 'SECURE_AUTH_KEY',  'w/&wcr}Mdw]%Q32HaF|TKo7<s~BnjDy3D4iI5MG(CFaVpVxX3vf&x$YRK(:4/Q|r' );
define( 'LOGGED_IN_KEY',    'cYl.OE$[C_S7TTgD;C~c;nOSW7hLZ=-_zao)tuV5Sri}2%vx5a#ldq8U^`uTRg0G' );
define( 'NONCE_KEY',        '~gTyzRIYZ35H3f 21HTWVcazykz;adwB*^*Djv9OVrg)f#enw:v5TJ$pG2$XU`.y' );
define( 'AUTH_SALT',        'y^)H9{ w.z9B-(Ze^_i5#;7,S]Yv^G^Eo==cqIe06gO3|:MTD=jW$pS%/us4Y;aH' );
define( 'SECURE_AUTH_SALT', ' G|LJ*JaMoGuKx7J2;*|`l4@/)gfGSI0M&sc{G38O8nORSQfuJc_fLy DUZ|D/b8' );
define( 'LOGGED_IN_SALT',   'Nq]Zc62kA?O(Bw?XLYNQhF[gkj5 R<MqC1xZ{zZ>Q i811uwy+25r?(drWhKmi>n' );
define( 'NONCE_SALT',       'X@&jZ=@%iMuR=.LD/*Aqr>vhQY+9U`vY>Ydog)aG[N/Y$*#cXtWaU;k5TOH=#6%z' );
/**#@-*/

/**
 * Préfixe de base de données pour les tables de WordPress.
 *
 * Vous pouvez installer plusieurs WordPress sur une seule base de données
 * si vous leur donnez chacune un préfixe unique.
 * N’utilisez que des chiffres, des lettres non-accentuées, et des caractères soulignés !
 */
$table_prefix = 'cd_';

/**
 * Pour les développeurs : le mode déboguage de WordPress.
 *
 * En passant la valeur suivante à "true", vous activez l’affichage des
 * notifications d’erreurs pendant vos essais.
 * Il est fortement recommandé que les développeurs d’extensions et
 * de thèmes se servent de WP_DEBUG dans leur environnement de
 * développement.
 *
 * Pour plus d’information sur les autres constantes qui peuvent être utilisées
 * pour le déboguage, rendez-vous sur le Codex.
 *
 * @link https://fr.wordpress.org/support/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );

/* C’est tout, ne touchez pas à ce qui suit ! Bonne publication. */

/** Chemin absolu vers le dossier de WordPress. */
if ( ! defined( 'ABSPATH' ) )
  define( 'ABSPATH', dirname( __FILE__ ) . '/' );

/** Réglage des variables de WordPress et de ses fichiers inclus. */
require_once( ABSPATH . 'wp-settings.php' );
