<?php

namespace Charm\Cron;

use Charm\DataType\DateTime;
use Charm\WordPress\Cron\Schedule as WpSchedule;

/**
 * Class Schedule
 *
 * @author Ryan Sechrest
 * @package Charm\Cron
 */
class Schedule extends WpSchedule
{
    /************************************************************************************/
    // Cast methods

    /**
     * Get schedules as HTML table
     *
     * @return string
     */
    public static function to_html(): string
    {
        $output = '<table>';
        $output .= '<thead>';
        $output .= '<tr>';
        $output .= '<th>' . _x('Display Name', 'Tools: Cron Viewer', 'charm') .'</th>';
        $output .= '<th>' . _x('Name', 'Tools: Cron Viewer', 'charm') .'</th>';
        $output .= '<th>' . _x('Repeat Every', 'Tools: Cron Viewer', 'charm') .'</th>';
        $output .= '<th>' . _x('Interval', 'Tools: Cron Viewer', 'charm') .' ↓</th>';
        $output .= '</tr>';
        $output .= '</thead>';
        $output .= '<tbody>';
        foreach (Schedule::get() as $schedule) {
            $output .= '<tr>';
            $output .= '<td>' . $schedule->get_display_name() . '</td>';
            $output .= '<td>' . $schedule->get_name() . '</td>';
            $output .= '<td>' . $schedule->get_repeat_every() . '</td>';
            $output .= '<td>' . $schedule->get_interval() . '</td>';
            $output .= '</tr>';
        }
        $output .= '</tbody>';
        $output .= '</table>';

        return $output;
    }

    /************************************************************************************/
    // Get methods

    /**
     * Get repeat every
     *
     * @return string
     */
    public function get_repeat_every(): string
    {
        return DateTime::duration(0, $this->get_interval());
    }
}