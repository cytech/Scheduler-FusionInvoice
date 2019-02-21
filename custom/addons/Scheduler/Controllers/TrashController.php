<?php


/**
 * This file is part of Scheduler Addon for FusionInvoice.
 * (c) Cytech <cytech@cytech-eng.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */


namespace Addons\Scheduler\Controllers;

use Addons\Scheduler\Models\Schedule;
use Addons\Scheduler\Models\ScheduleReminder;
use Session;
use Illuminate\Http\Request;

class TrashController extends Controller {

	public function eventTrash() {
//		$data['events'] = Schedule::withOccurrences()->onlyTrashed()->orderBy( 'id', 'desc' )->get();
		//replaced to allow only first occurrence of recurring event
		$data['events'] = Schedule::with(['occurrences' => function ($q) {
			$q->latest();
		}])->onlyTrashed()->orderBy( 'id', 'desc' )->get();

		return view('Scheduler::schedule.eventTrash', $data );
	}

	public function trashEvent( Request $request ) {
		$event = Schedule::find( $request->id );
		$event->delete();

		return 'true';
	}

	public function trashReminder( Request $request ) {
		$event = ScheduleReminder::find( $request->id );
		$event->delete();

		return 'true';
	}

	public function deleteSingleTrash( Request $request ) {
		Schedule::onlyTrashed()->where( 'id', $request->id )->forceDelete();

		return 'true';
	}

	public function deleteAllTrash() {
		Schedule::onlyTrashed()->forceDelete();
		Session::flash( 'alertSuccess', 'All Trash Deleted Successfully' );

		return redirect()->route( 'scheduler.tableevent' );
	}

	public function restoreSingleTrash( Request $request ) {
		Schedule::onlyTrashed()->where( 'id', $request->input( 'id' ) )->restore();

		return 'true';
	}

	public function restoreAllTrash() {
		Schedule::onlyTrashed()->restore();
		Session::flash( 'alertSuccess', 'All Trash Restored Successfully' );

		return redirect()->route( 'scheduler.tableevent' );
	}

	public function bulkTrash()
	{
		foreach (Schedule::whereIn('id',request('ids'))->get() as $delschedule){

			$delschedule->delete();

		}

	}

	public function bulkDeleteTrash()
	{
		foreach (Schedule::onlyTrashed()->whereIn('id',request('ids'))->get() as $delschedule){

			$delschedule->forceDelete();

		}
	}

	public function bulkRestoreTrash()
	{
		foreach (Schedule::onlyTrashed()->whereIn('id',request('ids'))->get() as $resschedule){
			$resschedule->restore();

		}
	}
}