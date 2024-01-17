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
    if ($oldversion < 2021020401) {
        $table = new xmldb_table('nss');
        $field = new xmldb_field('studentid', XMLDB_TYPE_CHAR, 255, null, true, false);
        // Need to drop the key before change the field type as this is foreign key.
        $key = new xmldb_key('studentid', XMLDB_KEY_FOREIGN, ['studentid'], 'user', ['idnumber']);
        $dbman->drop_key($table, $key);
        if ($dbman->field_exists($table, $field)) {
            $dbman->change_field_type($table, $field);
        }
        $dbman->add_key($table, $key);
    }

    return true;
}
