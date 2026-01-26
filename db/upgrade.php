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
 * Upgrade steps for NSS/myCourse
 *
 * Documentation: {@link https://moodledev.io/docs/guides/upgrade}
 *
 * @package    block_nss
 * @category   upgrade
 * @copyright  2024 Solent University {@link https://www.solent.ac.uk}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Execute the plugin upgrade steps from the given old version.
 *
 * @param int $oldversion
 * @return bool
 */
function xmldb_block_nss_upgrade($oldversion) {
    global $DB;
    $dbman = $DB->get_manager();

    if ($oldversion < 2025012001) {
        $tableold = new xmldb_table('nss');
        $dbman->rename_table($tableold, 'block_nss');
        upgrade_plugin_savepoint(true, 2025012001, 'block', 'nss');
    }

    if ($oldversion < 2025012002) {
        $table = new xmldb_table('block_nss');
        $field = new xmldb_field('nss');
        if ($dbman->field_exists($table, $field)) {
            $dbman->drop_field($table, $field);
        }
        $field = new xmldb_field('banner', XMLDB_TYPE_CHAR, '20', null, XMLDB_NOTNULL, null, 'nss');
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }
        $config = get_config('block_nss');
        set_config('nss_displayfrom', $config->displayfrom);
        set_config('nss_displayto', $config->displayto);
        set_config('nss_link', $config->nsslink);
        set_config('nss_image', $config->image);
        set_config('nss_alttext', $config->alttext);
        unset_config('displayfrom', 'block_nss');
        unset_config('displayto', 'block_nss');
        unset_config('nsslink', 'block_nss');
        unset_config('image', 'block_nss');
        unset_config('alttext', 'block_nss');
        upgrade_plugin_savepoint(true, 2025012002, 'block', 'nss');
    }

    return true;
}
