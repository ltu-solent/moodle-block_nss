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

use core\output\html_writer;

/**
 * NSS block
 *
 * @package   block_nss
 * @author    Mark Sharp <mark.sharp@solent.ac.uk>
 * @copyright 2024 Solent University {@link https://www.solent.ac.uk}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class block_nss extends block_base {
    /**
     * Init block
     *
     * @return void
     */
    public function init() {
        $this->title = get_string('nss', 'block_nss');
    }

    /**
     * Has config
     *
     * @return boolean
     */
    public function has_config() {
        return true;
    }

    /**
     * Hide header
     *
     * @return boolean
     */
    public function hide_header() {
        return true;
    }

    /**
     * Get content
     *
     * @return object
     */
    public function get_content(): object {
        global $DB, $USER;
        if ($this->content !== null) {
            return $this->content;
        }

        $this->content = new stdClass();
        $this->content->text = '';
        // Check date and which banner should be shown.
        $time = new DateTime("now", core_date::get_user_timezone_object());
        $now = $time->getTimestamp();
        $config = get_config('block_nss');
        $idnumber = $USER->idnumber;
        if (trim($idnumber) == '') {
            return $this->content;
        }
        // There shouldn't be more than one banner for this user, but it's technically possible.
        $userbanners = $DB->get_records('block_nss', ['studentid' => $idnumber]);
        if (count($userbanners) == 0) {
            return $this->content;
        }
        foreach ($userbanners as $userbanner) {
            // Default to using nss banner, if not set.
            $banner = $userbanner->banner ?? 'nss';
            $displayfrom = $config->{$banner . "_displayfrom"};
            $displayto = $config->{$banner . "_displayto"};

            if (($now > $displayfrom) && ($now < $displayto)) {
                $image = $config->{$banner . "_image"};
                $link = $config->{$banner . "_link"};
                $alttext = $config->{$banner . "_alttext"};
                $gtag = "gtag('event', 'click', { 'event_category': 'Survey Banner', 'event_action': 'Click'," .
                    "'event_label': '" . strtoupper($banner) . "'});";
                // This will write over whatever has come before. There will only ever be 1 banner.
                $this->content->text = html_writer::link(
                    $link,
                    html_writer::img(
                        "/blocks/nss/images/{$image}",
                        format_text($alttext, FORMAT_PLAIN),
                        ['class' => 'img_nss']
                    ),
                    [
                        'target' => '_blank',
                        'onclick' => $gtag,
                    ]
                );
            }
        }
        return $this->content;
    }
}
