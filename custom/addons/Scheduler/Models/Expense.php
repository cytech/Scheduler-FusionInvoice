<?php

/**
 * This file is part of Scheduler Addon for FusionInvoice.
 * (c) Cytech <cytech@cytech-eng.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Addons\Scheduler\Models;

use FI\Modules\Expenses\Models\Expense as FIExpense;

class Expense extends FIExpense
{
    public function scopeDateRange($query, $fromDate, $toDate)
    {
        return $query->where('expense_date', '>=', $fromDate)
            ->where('expense_date', '<=', $toDate);
    }

}