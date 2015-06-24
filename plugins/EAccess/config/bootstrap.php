<?php
use Cake\Core\Configure;

Configure::write('Session', [
    'defaults' => 'php',
    'timeout' => 18, // eAccess session timeout
]);


/*
 * eAccess Configurations
 */
Configure::write("eaccess.auto-save", true);


/*
 * User ID of the system. This ID is used when eAccess
 * automatically adds users and groups
 */
Configure::write("eaccess.system-id", 1);


/*
 * A list of groups which the eAccess plugin automatically associates
 * based on the user's Active Directory groups
 */
Configure::write("eaccess.groups", [
    // group_id => ["ad-group1","ad-group2",...]
    1 => ["ENT\Infor-Application_Platforms"], // Master Admin Group
]);