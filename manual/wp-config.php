<?php
/** 
 * As configurações básicas do WordPress.
 *
 * Esse arquivo contém as seguintes configurações: configurações de MySQL, Prefixo de Tabelas,
 * Chaves secretas, Idioma do WordPress, e ABSPATH. Você pode encontrar mais informações
 * visitando {@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} Codex page. Você pode obter as configurações de MySQL de seu servidor de hospedagem.
 *
 * Esse arquivo é usado pelo script ed criação wp-config.php durante a
 * instalação. Você não precisa usar o site, você pode apenas salvar esse arquivo
 * como "wp-config.php" e preencher os valores.
 *
 * @package WordPress
 */

// ** Configurações do MySQL - Você pode pegar essas informações com o serviço de hospedagem ** //
/** O nome do banco de dados do WordPress */
define('DB_NAME', 'igsis_wp');

/** Usuário do banco de dados MySQL */
define('DB_USER', 'root');

/** Senha do banco de dados MySQL */
define('DB_PASSWORD', 'lic54eca');

/** nome do host do MySQL */
define('DB_HOST', 'localhost');

/** Conjunto de caracteres do banco de dados a ser usado na criação das tabelas. */
define('DB_CHARSET', 'utf8mb4');

/** O tipo de collate do banco de dados. Não altere isso se tiver dúvidas. */
define('DB_COLLATE', '');

/**#@+
 * Chaves únicas de autenticação e salts.
 *
 * Altere cada chave para um frase única!
 * Você pode gerá-las usando o {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * Você pode alterá-las a qualquer momento para desvalidar quaisquer cookies existentes. Isto irá forçar todos os usuários a fazerem login novamente.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         '6`9,a<NZLUsHp[j<AT DK,<G!N&4i>)Vp0X/4gO}QRw/XPj,JeQ.-4[Y*/co@L;o');
define('SECURE_AUTH_KEY',  '~9,R!YMm$d!j((XgvcH60q7*Fm~Sh?|R8Qv-=/g)5c0D+S>;#_d]MZ0SM4#IH)6}');
define('LOGGED_IN_KEY',    'Sz>pA[X6v*40jJe+5Q2L]5}@V<A8}E>gly9;w[&,{P,BSV>=bOB&B-A|.viUry l');
define('NONCE_KEY',        'G(P~%E*-I!8s5?q,EvpYKU$J0HE!3VX,/3Z~(M0G9Y7gEV-d0hk4P Wa5Z0wPZi.');
define('AUTH_SALT',        'i*|F7ra}?_#kFPu{#]mADUUxt&.4om%J)]3YGr7o(l{jk&Xvn]z?+dfF!a#)YlT5');
define('SECURE_AUTH_SALT', '!i[{[V=wD}0MU;Lu035`_Y/~(;7_m?Pj!t0O8TWeTouMpV58j<#v!y3(x7TDqS:F');
define('LOGGED_IN_SALT',   'X`0chug#5C-2qpiyT`9}J^:U }>+$%j~dJdh,-cRdT`3D+Vrj/6 ]$Y$Y{3c)?4y');
define('NONCE_SALT',       'e*GY]osvJD!v6L[k?2)BrP?5#?9=B~S&nW9RA-T)::tXT?,ycXE PRs/lHG%BDTK');

/**#@-*/

/**
 * Prefixo da tabela do banco de dados do WordPress.
 *
 * Você pode ter várias instalações em um único banco de dados se você der para cada um um único
 * prefixo. Somente números, letras e sublinhados!
 */
$table_prefix  = 'wp_';


/**
 * Para desenvolvedores: Modo debugging WordPress.
 *
 * altere isto para true para ativar a exibição de avisos durante o desenvolvimento.
 * é altamente recomendável que os desenvolvedores de plugins e temas usem o WP_DEBUG
 * em seus ambientes de desenvolvimento.
 */
define('WP_DEBUG', false);

/* Isto é tudo, pode parar de editar! :) */

/** Caminho absoluto para o diretório WordPress. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');
	
/** Configura as variáveis do WordPress e arquivos inclusos. */
require_once(ABSPATH . 'wp-settings.php');
