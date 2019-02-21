<?php

/**
 * This file is part of Scheduler Addon for FusionInvoice.
 * (c) Cytech <cytech@cytech-eng.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Addons\Scheduler\Models;

use Addons\TimeTracking\Models\TimeTrackingProject as FITimeTrackingProject;

class TimeTrackingProject extends FITimeTrackingProject
{
    public function scopeDateRange($query, $fromDate, $toDate)
    {
        return $query->where('due_at', '>=', $fromDate)
            ->where('due_at', '<=', $toDate);
    }
}