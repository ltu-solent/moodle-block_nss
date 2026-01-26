<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * This file defines the admin settings for this plugin
 *
 * @package   block_nss
 * @copyright 2018 Southampton Solent University
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use block_nss\admin_setting_configdatetime;
use core\lang_string;
use core\url;

defined('MOODLE_INTERNAL') || die();

$ADMIN->add('blocksettings', new admin_category('nssfolder', new lang_string('pluginname', 'block_nss')));
$ADMIN->add('nssfolder', new admin_externalpage(
    'block_nss_uploadusers',
    get_string('uploadusers', 'block_nss'),
    new url('/blocks/nss/uploadusers.php')
));
$settings = null;

if ($ADMIN->fulltree) {
    $settingspage = new admin_settingpage($section, new lang_string('settings'));
    $ADMIN->add('nssfolder', $settingspage);
    $settingspage->add(
        new admin_setting_heading(
            'block_nss/nss_banner',
            new lang_string('nssbanner', 'block_nss'),
            ''
        )
    );
    $settingspage->add(new admin_setting_configdatetime(
        'block_nss/nss_displayfrom',
        new lang_string('displayfrom', 'block_nss'),
        '',
        0
    ));
    $settingspage->add(new admin_setting_configdatetime(
        'block_nss/nss_displayto',
        new lang_string('displayto', 'block_nss'),
        '',
        0
    ));
    $settingspage->add(new admin_setting_configtext(
        'block_nss/nss_link',
        new lang_string('nsslink', 'block_nss'),
        '',
        'https://www.thestudentsurvey.com',
        PARAM_URL
    ));
    $settingspage->add(new admin_setting_configtext(
        'block_nss/nss_image',
        new lang_string('image', 'block_nss'),
        'nss.jpg',
        PARAM_FILE
    ));
    $default = 'Final year students: we want to know how you\'ve found your time at Solent. ' .
        'Complete the National Student Survey by Sunday 30 April and you could win a £400 ' .
        'Currys PC World voucher or one of ten £50 Amazon vouchers.';
    $settingspage->add(new admin_setting_configtextarea(
        'block_nss/nss_alttext',
        new lang_string('alttext', 'block_nss'),
        '',
        $default,
        PARAM_RAW
    ));

    $settingspage->add(
        new admin_setting_heading(
            'block_nss/ycs_banner',
            new lang_string('ycsbanner', 'block_nss'),
            ''
        )
    );
    $settingspage->add(new admin_setting_configdatetime(
        'block_nss/ycs_displayfrom',
        new lang_string('displayfrom', 'block_nss'),
        '',
        0
    ));
    $settingspage->add(new admin_setting_configdatetime(
        'block_nss/ycs_displayto',
        new lang_string('displayto', 'block_nss'),
        '',
        0
    ));
    $settingspage->add(new admin_setting_configtext(
        'block_nss/ycs_link',
        new lang_string('nsslink', 'block_nss'),
        '',
        'https://www.thestudentsurvey.com',
        PARAM_URL
    ));
    $settingspage->add(new admin_setting_configtext(
        'block_nss/ycs_image',
        new lang_string('image', 'block_nss'),
        'nss.jpg',
        PARAM_FILE
    ));
    $default = 'Final year students: we want to know how you\'ve found your time at Solent. ' .
        'Complete the Your Course Survey by Sunday 30 April and you could win a £400 ' .
        'Currys PC World voucher or one of ten £50 Amazon vouchers.';
    $settingspage->add(new admin_setting_configtextarea(
        'block_nss/ycs_alttext',
        new lang_string('alttext', 'block_nss'),
        '',
        $default,
        PARAM_RAW
    ));
}
