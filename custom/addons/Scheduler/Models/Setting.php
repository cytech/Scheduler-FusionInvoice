<?php

/**
 * This file is part of Scheduler Addon for FusionInvoice.
 * (c) Cytech <cytech@cytech-eng.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */



namespace Addons\Scheduler\Models;

use Illuminate\Database\Eloquent\Model;
use FI\Modules\Addons\Models\Addon;


class Setting extends Model
{
    /**
     * Guarded properties
     * @var array
     */
    protected $guarded = ['id'];

    protected $table = 'schedule_settings';

    //TimeTracking module, need to see if it exists
    //this is a paid addon from FusionInvoice

    public static $coreevents = [
        'quote' => 1,
        'invoice' => 2,
        'payment' => 4,
        'expense' => 8,
    ];

    //TimeTracking module, need to see if it exists
    //this is a paid addon from FusionInvoice
    public static function initialize() {

        $addon = Addon::where('name', 'Time Tracking')->first();

        if ($addon && $addon->enabled == 1){
            self::$coreevents = array_merge(self::$coreevents,['project' => 16]);
            self::$coreevents = array_merge(self::$coreevents,['task' => 32]);
        }

    }

    public function isCoreeventEnabled($entityType)
    {
        if (! in_array($entityType, [
            'quote',
            'invoice',
            'payment',
            'expense',
            'project',
            'task',
        ])) {
            return true;
        }

        $enabledcoreevents = $this->where('setting_key', 'enabledCoreEvents')->value('setting_value');

        // note: single & checks bitmask match
        return $enabledcoreevents & static::$coreevents[$entityType];
    }

    public function coreeventsEnabled()
    {

        $filter = [];

        $enabledcoreevents = $this->where('setting_key', 'enabledCoreEvents')->value('setting_value');

        if ($enabledcoreevents == 0){
            $filter[] = 'none';
            return $filter;
        }

        foreach (static::$coreevents as $key => $value){
            if ($enabledcoreevents & $value){
                $filter[] = $key;
            }
        }

        return $filter;
    }

    public  function scopeLike($query, $field, $value){
        return $query->where($field, 'LIKE', "%$value%");
    }


}
//initialize $coreevent for TimeTracking addon
Setting::initialize();
