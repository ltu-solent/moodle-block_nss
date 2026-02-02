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

namespace block_nss\tables;

use core\context\system;
use core\lang_string;
use core\url;
use core_table\sql_table;
use core_user\fields;

/**
 * Class users
 *
 * @package    block_nss
 * @copyright  2026 Southampton Solent University {@link https://www.solent.ac.uk}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class users extends sql_table {
    /**
     * Constructor
     *
     * @param string $uniqueid
     */
    public function __construct($uniqueid) {
        parent::__construct($uniqueid);
        $this->useridfield = 'userid';
        $columns = [
            'id' => 'id',
            'fullname' => new lang_string('fullname'),
            'banner' => new lang_string('banner', 'block_nss'),
        ];
        $this->define_columns(array_keys($columns));
        $this->define_headers(array_values($columns));
        $this->collapsible(false);
        $this->define_baseurl(new url('/blocks/nss/users.php'));
        $userfieldsapi = fields::for_identity(system::instance(), false)->with_userpic();
        $userfields = $userfieldsapi->get_sql('u', false, '', 'userid')->selects;
        $select = 'n.id, n.studentid, n.banner' . $userfields;
        $from = "{block_nss} n
        JOIN {user} u ON u.idnumber = n.studentid";
        $where = '1=1';

        $this->set_sql($select, $from, $where);
    }
}
