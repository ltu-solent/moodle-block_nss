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
 * TODO describe file users
 *
 * @package    block_nss
 * @copyright  2026 Southampton Solent University {@link https://www.solent.ac.uk}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use core\context\system;
use core\output\html_writer;
use core\url;

require('../../config.php');
require_once($CFG->libdir . '/adminlib.php');


$url = new url('/blocks/nss/users.php', []);
admin_externalpage_setup('block_nss_users');

$PAGE->set_context(system::instance());

$PAGE->set_heading(get_string('nssusers', 'block_nss'));
$PAGE->set_title(get_string('nssusers', 'block_nss'));
echo $OUTPUT->header();

$table = new \block_nss\tables\users('nssusers');

echo html_writer::tag('h3', get_string('nssusers', 'block_nss'));

$table->out(100, true);

echo $OUTPUT->footer();
