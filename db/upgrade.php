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
 * Customcert module upgrade code.
 *
 * @package    mod_customcert
 * @copyright  2016 Mark Nelson <markn@moodle.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

/**
 * Customcert module upgrade code.
 *
 * @param int $oldversion the version we are upgrading from
 * @return bool always true
 */
function xmldb_customcert_upgrade($oldversion) {
    global $DB;

    $dbman = $DB->get_manager();

    if ($oldversion < 2016120503) {

        $table = new xmldb_table('customcert_templates');
        $field = new xmldb_field('name', XMLDB_TYPE_CHAR, '255', null, XMLDB_NOTNULL, null, null, 'id');
        $dbman->change_field_precision($table, $field);

        // Savepoint reached.
        upgrade_mod_savepoint(true, 2016120503, 'customcert');
    }

    if ($oldversion < 2016120505) {
        $table = new xmldb_table('customcert');
        $field = new xmldb_field('emailstudents', XMLDB_TYPE_INTEGER, '1', null, XMLDB_NOTNULL, null, '0', 'requiredtime');

        // Conditionally launch add field.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        $field = new xmldb_field('emailteachers', XMLDB_TYPE_INTEGER, '1', null, XMLDB_NOTNULL, null, '0', 'emailstudents');

        // Conditionally launch add field.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        $field = new xmldb_field('emailothers', XMLDB_TYPE_TEXT, null, null, null, null, null, 'emailteachers');

        // Conditionally launch add field.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        $table = new xmldb_table('customcert_issues');
        $field = new xmldb_field('emailed', XMLDB_TYPE_INTEGER, '1', null, XMLDB_NOTNULL, null, '0', 'code');

        // Conditionally launch add field.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Savepoint reached.
        upgrade_mod_savepoint(true, 2016120505, 'customcert');
    }

    if ($oldversion < 2017050501) {
        // Add column for new 'verifycertificateanyone' setting.
        $table = new xmldb_table('customcert');
        $field = new xmldb_field('verifyany', XMLDB_TYPE_INTEGER, '1', null, XMLDB_NOTNULL, null, '0',
            'requiredtime');

        // Conditionally launch add field.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Savepoint reached.
        upgrade_mod_savepoint(true, 2017050501, 'customcert');
    }

    return true;
}
