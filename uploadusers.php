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
 * Upload users to nss table
 *
 * @package    block_nss
 * @copyright  2024 Solent University {@link https://www.solent.ac.uk}
 * @author Mark Sharp <mark.sharp@solent.ac.uk>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use core\context\system;
use core\exception\moodle_exception;
use core\url;

require('../../config.php');
require_once($CFG->libdir . '/adminlib.php');

admin_externalpage_setup('block_nss_uploadusers');

$url = new url('/blocks/nss/uploadusers.php', []);
$PAGE->set_url($url);
$PAGE->set_context(system::instance());

$PAGE->set_heading(get_string('uploadusers', 'block_nss'));

$notification = '';
$notificationstatus = '';

$mappinguploadform = new block_nss\forms\mapping_upload_form();
if ($mappinguploadformdata = $mappinguploadform->get_data()) {
    $iid = csv_import_reader::get_new_iid('uploadmapping');
    $cir = new csv_import_reader($iid, 'uploadmapping');

    $content = $mappinguploadform->get_file_content('mappingfile');
    $readcount = $cir->load_csv_content($content, $mappinguploadformdata->encoding, $mappinguploadformdata->delimiter_name);
    $csvloaderror = $cir->get_error();
    unset($content);

    if (!is_null($csvloaderror)) {
        throw new moodle_exception('csvloaderror', '', $url, $csvloaderror);
    }

    $filecolumns = block_nss\helper::validate_columns($cir, ['studentid'], $url);
    $cir->init();
    $linenum = 1;
    $countadded = 0;
    // Delete all existing records.
    $DB->delete_records('nss');
    while ($line = $cir->next()) {
        $linenum++;
        $entry = new stdClass();
        foreach ($line as $keynum => $value) {
            if (!isset($filecolumns[$keynum])) {
                continue;
            }
            $key = $filecolumns[$keynum];
            $entry->$key = trim($value);
            if ($entry->$key == '') {
                // Not a valid entry.
                continue 2;
            }
        }
        // Zero pad an idnumber of length 7.
        if (strlen($entry->studentid) == 7) {
            $entry->studentid = '0' . $entry->studentid;
        }
        if ($existingentry = $DB->get_record('nss', ['studentid' => $entry->studentid])) {
            // Record already exists, so skip.
            continue;
        }

        $DB->insert_record('nss', $entry);
        $countadded++;
    }
    $notification = get_string('newmappingsadded', 'block_nss', (object)['new' => $countadded, 'supplied' => $linenum - 1]);
    $notificationstatus = \core\notification::SUCCESS;
}

echo $OUTPUT->header();

if ($notification !== '') {
    echo $OUTPUT->notification($notification, $notificationstatus);
}

echo $mappinguploadform->render();

echo $OUTPUT->footer();
