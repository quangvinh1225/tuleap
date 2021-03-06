/**
 * Copyright (c) Enalean, 2016. All Rights Reserved.
 *
 * This file is a part of Tuleap.
 *
 * Tuleap is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * Tuleap is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Tuleap. If not, see <http://www.gnu.org/licenses/>.
 */

(function($) {
    toggle_addurlform = function() {
        $('#hudson_add_job').slideToggle(300);
    }

    toggle_iframe = function(joburl) {
        var hudson_iframe_div = $('#hudson_iframe_div');

        if (hudson_iframe_div.is(":visible") === false) {
            hudson_iframe_div.show();
        }

        $('#hudson_iframe').attr('src', joburl);
        $('#link_show_only').attr('href', joburl);
    }
})(jQuery);