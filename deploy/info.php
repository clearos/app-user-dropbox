<?php

/////////////////////////////////////////////////////////////////////////////
// General information
/////////////////////////////////////////////////////////////////////////////

$app['basename'] = 'user_dropbox';
$app['version'] = '2.3.1';
$app['release'] = '1';
$app['vendor'] = 'ClearFoundation';
$app['packager'] = 'ClearFoundation';
$app['license'] = 'GPLv3';
$app['license_core'] = 'LGPLv3';
$app['description'] = lang('user_dropbox_app_description');

/////////////////////////////////////////////////////////////////////////////
// App name and categories
/////////////////////////////////////////////////////////////////////////////

$app['name'] = lang('user_dropbox_app_name');
$app['category'] = lang('base_category_system');
$app['subcategory'] = lang('base_subcategory_my_account');

$app['user_access'] = TRUE;

/////////////////////////////////////////////////////////////////////////////
// Controllers
/////////////////////////////////////////////////////////////////////////////

$app['controllers']['user_dropbox']['title'] = $app['name'];
$app['controllers']['dropbox']['title'] = lang('user_dropbox_app_name');

/////////////////////////////////////////////////////////////////////////////
// Packaging
/////////////////////////////////////////////////////////////////////////////

$app['requires'] = array(
    'app-accounts'
);

$app['core_requires'] = array(
    'app-accounts-core',
    'app-dropbox-core',
    'app-user-dropbox-plugin-core',
);

$app['core_file_manifest'] = array(
   'user_dropbox.acl' => array( 'target' => '/var/clearos/base/access_control/authenticated/user_dropbox' ),
);
