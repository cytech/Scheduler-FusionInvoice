{{-- allow reload on back button --}}
{!! header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0") !!}
{!! header("Cache-Control: post-check=0, pre-check=0", false) !!}
{!! header("Pragma: no-cache")!!}

@extends('Scheduler::partials._master')

@section('content')

    <br>
    <div class="container col-lg-12">
        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title"><i class="fa fa-fw fa-th fa-fw"></i><a
                                    href="{{ route('scheduler.index') }}">{{ trans('Scheduler::texts.schedule') }}</a> /{{ trans('Scheduler::texts.calendar') }}</h3>
                    </div>
                    <div class="panel-body">
                        <div id="calendar">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="calEventDialog" title="{{ trans('Scheduler::texts.create_event_calendar') }}" style="display: none">
            <form class="form-horizontal" id="saveCalendarEvent">
                <input type="hidden" name="_token" value="{{ csrf_token() }}" id="csrf">
                <div class="form-group">
                    <label for="title" class="col-sm-4 control-label">{{ trans('Scheduler::texts.title') }}</label>
                    <div class="col-sm-8">
                        <input type="text" id="title" name="title" class="form-control" value="">
                    </div>
                    <script>
                        @if (!empty(config('workorder_settings.version')))
                        $("#title").autocomplete({
                            appendTo: "#updateCalendarEvent",
                            source: "/scheduler/ajax/employee",
                            minLength: 2
                        }).autocomplete("widget").addClass("fixed-height");
                        @endif
                    </script>
                </div>
                <div class="form-group">
                    <label for="description"
                           class="col-sm-4 control-label">{{ trans('Scheduler::texts.description') }}</label>
                    <div class="col-sm-8">
                        <input type="text" id="description" name="description" class="form-control"
                                value="">
                    </div>
                </div>
                <div class="form-group">
                    <label for="from"
                           class="col-sm-4 control-label">{{ trans('Scheduler::texts.start_datetime') }}</label>
                    <div class="col-sm-8">
                        <input type="text" id="from" name="start_date"
                               class="form-control from readonly" style="cursor: pointer"
                               >
                    </div>
                </div>
                <div class="form-group">
                    <label for="to"
                           class="col-sm-4 control-label">{{ trans('Scheduler::texts.end_datetime') }}</label>
                    <div class="col-sm-8">
                        <input type="text" id="to" name="end_date" class="form-control to readonly"
                               style="cursor: pointer"
                               >
                    </div>
                </div>
                <div class="form-group">
                    <label for="category"
                           class="col-sm-4 control-label">{{ trans('Scheduler::texts.category') }}</label>
                    <div class="col-sm-8">
                        {!! Form::select('category_id',$categories,'category', ['id' => 'category', 'class'=> 'form-control']) !!}
                    </div>
                </div>

                <div id="addReminderShow">

                </div>
                <div class="form-group">
                    <div class="col-sm-offset-3 col-sm-8">
                        <button type="button" id="addReminderCreate" class="btn btn-primary"><i class="fa fa-plus"></i>
                            {{ trans('Scheduler::texts.add_reminder') }}
                        </button>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-sm-offset-3 col-sm-8">
                        <button type="submit" id="" class="btn btn-default"><i class="fa fa-fw fa-plus"></i>
                            {{ trans('Scheduler::texts.create') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>

        @include('Scheduler::partials._createworkorder')

        <div id="editEvent" title="{{ trans('Scheduler::texts.update_event_calendar') }}" style="display: none">
            <form class="form-horizontal" id="updateCalendarEvent">
                <input type="hidden" name="_token" value="{{ csrf_token() }}" id="csrf">
                <div class="form-group">
                    <label for="editTitle"
                           class="col-sm-4 control-label">{{ trans('Scheduler::texts.title') }}</label>
                    <div class="col-sm-8">
                        <input type="text" id="editTitle" name="title" class="form-control" >
                    </div>
                    <script>
                        @if (!empty(config('workorder_settings.version')))
                        $("#editTitle").autocomplete({
                            appendTo: "#updateCalendarEvent",
                            source: "/scheduler/ajax/employee",
                            minLength: 2
                        }).autocomplete("widget").addClass("fixed-height");
                        @endif
                    </script>
                </div>
                <div class="form-group">
                    <label for="editDescription"
                           class="col-sm-4 control-label">{{ trans('Scheduler::texts.description') }}</label>
                    <div class="col-sm-8">
                        <input type="text" id="editDescription" name="description" class="form-control"
                               >
                    </div>
                </div>
                <div class="form-group">
                    <label for="editStart"
                           class="col-sm-4 control-label">{{ trans('Scheduler::texts.start_date') }}</label>
                    <div class="col-sm-8">
                        <input type="text" id="editStart" name="start_date" class="form-control from readonly"
                               style="cursor: pointer"
                               >
                    </div>
                </div>
                <div class="form-group">
                    <label for="editEnd"
                           class="col-sm-4 control-label">{{ trans('Scheduler::texts.end_date') }}</label>
                    <div class="col-sm-8">
                        <input type="text" id="editEnd" name="end_date" class="form-control to readonly"
                               style="cursor: pointer"
                               >
                        <input type="hidden" id="editID" name="id" class="form-control">
                        <input type="hidden" id="editOID" name="oid" class="form-control">
                    </div>
                </div>
                <div class="form-group">
                    <label for="editCategory"
                           class="col-sm-4 control-label">{{ trans('Scheduler::texts.category') }}</label>
                    <div class="col-sm-8">
                        {!! Form::select('category_id',$categories,'category', ['id' => 'editCategory', 'class'=> 'form-control']) !!}
                    </div>
                </div>
                <div id="reminderShowFormCalendar">

                </div>
                <div id="updateReminderShow">

                </div>
                <div class="form-group">
                    <div class="col-sm-offset-3 col-sm-8">
                        <button type="button" id="updateReminderCreate" class="btn btn-primary"><i
                                    class="fa fa-plus"></i>
                            {{ trans('Scheduler::texts.add_reminder') }}
                        </button>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-offset-3 col-sm-8">
                        <button type="submit" id="updateEvent" class="btn btn-default"><i class="fa fa-fw fa-edit"></i>
                            {{ trans('Scheduler::texts.update') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <div class="addReminderView" style="display: none">
            <div class="reminder_delete_div">
                <div class="form-group">
                    <hr class="col-sm-10 hr-clr-green"/>
                    <span class="col-sm-1 pull-right reminder-cross delete_reminder" style="cursor: pointer"><i
                                class="fa fa-times-circle"></i> </span>
                </div>
                <div class="form-group">
                    <label for="reminder_date" class="col-sm-4 control-label">{{ trans('Scheduler::texts.reminder_date') }}</label>
                    <div class="col-sm-8">
                        <input type="text" name="reminder_date[]" class="form-control reminder_date "
                               style="cursor: pointer" readonly>
                    </div>
                </div>
                <div class="form-group">
                    <label for="reminder_location" class="col-sm-4 control-label">{{ trans('Scheduler::texts.reminder_location') }}</label>
                    <div class="col-sm-8">
                        <input type="text" name="reminder_location[]" class="form-control">
                    </div>
                </div>
                <div class="form-group">
                    <label for="reminder_text" class="col-sm-4 control-label">{{ trans('Scheduler::texts.reminder_text') }}</label>
                    <div class="col-sm-8">
                        <textarea name="reminder_text[]" class="form-control"></textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('Scheduler::partials._js_event')

@stop



