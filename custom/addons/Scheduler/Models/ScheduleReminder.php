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
use Illuminate\Database\Eloquent\SoftDeletes;
use Auth;
use Carbon\Carbon;

class ScheduleReminder extends Model
{

	public $timestamps = true;

	protected $guarded = ['id'];

	protected $dates = ['reminder_date'];

	protected $table = 'schedule_reminders';



    public function newQuery()
    {
        if(Auth::check()){
            $query = parent::newQuery();
            $query->whereHas('Schedule', function($q)
            {
                $q->where('user_id', Auth::user()->id);

            });
            return $query;
        }else{
            $query = parent::newQuery();
            $query->whereHas('Schedule', function($q)
            {
                $q->where('user_id', '!=', 0);

            });
            return $query;
        }
    }

	public function getReminderDateAttribute($date){
		return Carbon::parse($this->attributes['reminder_date'])->format('Y-m-d H:i');
	}

    public function schedule()
    {
        return $this->belongsTo('Addons\Scheduler\Models\Schedule', 'schedule_id', 'id');
    }
}
