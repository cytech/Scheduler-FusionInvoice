<?php

/**
 * This file is part of Scheduler Addon for FusionInvoice.
 * (c) Cytech <cytech@cytech-eng.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Addons\Scheduler\Models;

use Addons\TimeTracking\Models\TimeTrackingTask as FITimeTrackingTask;

class TimeTrackingTask extends FITimeTrackingTask
{

    public function scopeDateRange($query, $fromDate, $toDate)
    {
        return $query->whereHas('timers', function ($q) use ($fromDate, $toDate) {
            $q->where('start_at', '>=', $fromDate)->where('start_at', '<=', $toDate);
        });
    }
}