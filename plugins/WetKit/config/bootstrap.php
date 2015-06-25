<?php
/*
 * IMPORTANT: Do not modify this file, you can overwrite these configs
 * in your app's bootstrap file.
 */

use Cake\Core\Configure;

define("WETKIT_ROOT", ROOT . DS . "plugins" . DS . "WetKit");

/*
 * Define Language configuration variables
 */
Configure::write("wetkit.lang", "en");
Configure::write("wetkit.lang-switch", "fr");
Configure::write("wetkit.ISO639-2", "eng");
Configure::write("wetkit.locale_suffix", '_CA'); // i.e. _US _CA


/*
 * WET Configuration
 *
 * `theme` Value of the WET theme. [ theme-base | theme-gc-intranet | theme-gcwu-fegc | gcweb ]
 * `path` The relative path or full url of the WET location. Used to load js, css and other WET assets.
 * `version` The WET version you are using
 * `autopath`   If `path` is null and `autopath` is set, the path configuration will be auto generated when the WetKit
 *              is initialized. The following placeholders will be replaced by there corresponding WET configuration
 *              [basepath], [version], [theme] ** Square brackets are required.
 *
 */
Configure::write("wetkit.wet.theme", 'theme-gc-intranet');
Configure::write("wetkit.wet.basepath", '/share/wet');
Configure::write("wetkit.wet.path", null);
Configure::write("wetkit.wet.version", "4.0.14");
Configure::write("wetkit.wet.autopath", "[basepath]/[version]/[theme]");


// Specify versions of js/css frameworks/widgets
Configure::write("wetkit.tinymce-version", "4.0.16");
Configure::write("wetkit.jquery-ie-version", "1.11.1");
Configure::write("wetkit.jquery-version", "2.1.1");




// Encrypted Fields
// Array of Encrypted Fields, affects various view element in the WET kit
// ['controller.field'] ... i.e. ['articles.comments']
Configure::write("wetkit.encryptedFields", ['user.password']);


// Date Configuration
Configure::write("wetkit.dateFormat", "YYYY-MM-dd");
Configure::write("wetkit.timeFormat", "HH:mm:ss");
Configure::write("wetkit.dateTimeFormat", "YYYY-MM-dd HH:mm:ss");


/*
 * Environment Configuration
 *
 * Array of URLs or part-of URL string corresponding to each environement
 *
 */
Configure::write("wetkit.env.envBar", true); // Show warning bar for LOCAL,DEV and TEST environment
Configure::write("wetkit.env.local", ['localhost']);
Configure::write("wetkit.env.dev", ['-dev']);
Configure::write("wetkit.env.test", ['-test']);
Configure::write("wetkit.env.prod", []);


/*
 * WetKit UI configuration
 *
 */
Configure::write("wetkit.ui.leftmenu", true);
Configure::write("wetkit.ui.sitemenu", false);
Configure::write("wetkit.ui.megamenu", true);
Configure::write("wetkit.ui.breadcrumb", true);
Configure::write("wetkit.ui.search", true);
Configure::write("wetkit.ui.subsite", true);
Configure::write("wetkit.ui.language-bar", true);

