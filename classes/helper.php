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

namespace block_nss;

use core\exception\moodle_exception;
use core_text;
use csv_import_reader;

/**
 * Class helper
 *
 * @package    block_nss
 * @copyright  2024 Solent University {@link https://www.solent.ac.uk}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class helper {
    /**
     * Validates columns of CSV file
     *
     * @param csv_import_reader $cir CSV stream.
     * @param array $fields Valid fields we're expecting.
     * @param string $returnurl Where to go if it goes wrong.
     * @return array Filtered fields.
     */
    public static function validate_columns(csv_import_reader $cir, array $fields, $returnurl) {
        $columns = $cir->get_columns();

        if (empty($columns)) {
            $cir->close();
            $cir->cleanup();
            throw new moodle_exception('cannotreadtmpfile', 'error', $returnurl);
        }
        if (count($columns) != 2) {
            $cir->close();
            $cir->cleanup();
            throw new moodle_exception('csvincorrectfields', 'block_nss', $returnurl);
        }

        $processed = [];
        foreach ($columns as $key => $unused) {
            $field = core_text::strtolower(trim($columns[$key]));
            if (!in_array($field, $fields)) {
                $cir->close();
                $cir->cleanup();
                throw new moodle_exception('invalidfieldname', 'error', $returnurl, $field);
            }
            if (in_array($field, $processed)) {
                $cir->close();
                $cir->cleanup();
                throw new moodle_exception('duplicatefieldname', 'error', $returnurl, $field);
            }
            $processed[$key] = $field;
        }

        return $processed;
    }
}
