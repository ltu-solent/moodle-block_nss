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
 * Mapping upload form
 *
 * @package   block_nss
 * @author    Mark Sharp <m.sharp@chi.ac.uk
 * @copyright 2020 University of Chichester {@link https://www.chi.ac.uk}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace block_nss\forms;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/csvlib.class.php');
require_once($CFG->libdir . '/formslib.php');

use core\lang_string;
use core\output\html_writer;
use core\url;
use core_text;
use csv_import_reader;
use moodleform;

/**
 * CSV upload form for student idnumbers
 */
class mapping_upload_form extends moodleform {
    /**
     * Defintion of form fields for Mapping uploader.
     */
    public function definition() {
        $mform = $this->_form;

        $mform->addElement('header', 'mappingheader', get_string('upload'));

        $url = new url('example.csv');
        $link = html_writer::link($url, 'example.csv');
        $mform->addElement('static', 'examplecsv', get_string('examplecsv', 'block_nss'), $link);
        $mform->addHelpButton('examplecsv', 'examplecsv', 'block_nss');

        $mform->addElement('filepicker', 'mappingfile', get_string('file'));

        $choices = csv_import_reader::get_delimiter_list();
        $mform->addElement('select', 'delimiter_name', get_string('csvdelimiter', 'block_nss'), $choices);
        if (array_key_exists('cfg', $choices)) {
            $mform->setDefault('delimiter_name', 'cfg');
        } else if (get_string('listsep', 'langconfig') == ';') {
            $mform->setDefault('delimiter_name', 'semicolon');
        } else {
            $mform->setDefault('delimiter_name', 'comma');
        }

        $choices = core_text::get_encodings();
        $mform->addElement('select', 'encoding', get_string('encoding', 'tool_uploaduser'), $choices);
        $mform->setDefault('encoding', 'UTF-8');

        $mform->addElement('selectyesno', 'truncate', new lang_string('truncate', 'block_nss'));

        $this->add_action_buttons(false, get_string('uploadusers', 'block_nss'));
    }
}
