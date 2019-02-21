@extends('Scheduler::partials._master')

@section('content')
    @if(config('app.name') == 'FusionInvoice') {!! Form::breadcrumbs() !!} @endif
    <div class="row" ng-app="event" ng-controller="scheduleEventController">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title"><i
                                class="fa fa-edit fa-fw"></i> {{ trans('Scheduler::texts.'.$title) }}</h3>
                </div>
                <div class="panel-body">
                    {!! Form::model($schedule,['id' => 'event', 'accept-charset' => 'utf-8', 'class' => 'form-horizontal',  'ng-submit'=>'create($event)']) !!}
                    {!! Form::hidden('id') !!}
                    {!! Form::hidden('oid') !!}
                    {{--{!! Form::hidden('public_id') !!}--}}
                    <div class="form-group">
                        {!! Form::label('title',trans('Scheduler::texts.title'),['class'=>'col-sm-2 control-label']) !!}
                        <div class="col-sm-6">
                            {!! Form::text('title',null,['class'=>'form-control']) !!}
                        </div>
                        <script>
                            @if (!empty(config('workorder_settings.version')))
                            $("#title").autocomplete({
                                appendTo: "#event",
                                source: "/scheduler/ajax/employee",
                                minLength: 2
                            }).autocomplete("widget").addClass("fixed-height");
                            @endif
                        </script>
                    </div>
                    <div class="form-group">
                        {!! Form::label('description',trans('Scheduler::texts.description'),['class'=>'col-sm-2 control-label']) !!}
                        <div class="col-sm-6">
                            {!! Form::text('description',null,['class'=>'form-control']) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('Start DateTime',null,['for'=>'start_date', 'class'=>'col-sm-2 control-label']) !!}
                        <div class="col-sm-6">
                            {!! Form::input('text','start_date',null, ['id'=>'start_date','class'=>'form-control datepicker from ','style'=>'cursor: pointer','readonly']) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('End DateTime',null,['for'=>'end_date', 'class'=>'col-sm-2 control-label']) !!}
                        <div class="col-sm-6">
                            {!! Form::input('text','end_date',null, ['id'=>'end_date','class'=>'form-control to','style'=>'cursor: pointer','readonly']) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('category_id',trans('Scheduler::texts.category'),['class'=>'col-sm-2 control-label']) !!}
                        <div class="col-sm-3">
                            {!! Form::select('category_id',$categories,null, ['id' => 'category_id','class'=>'form-control']) !!}
                        </div>
                    </div>

                    @if(!$schedule->reminders->isEmpty())
                        @foreach($schedule->reminders as $reminder)
                            <div class="reminder_delete_div">
                                <div class="form-group">
                                    <hr class="col-sm-8 width60 hr-clr-green"/>
                                    <span class="col-sm-1 pull-left reminder-cross-table delete_reminder"
                                          style="cursor: pointer"><i class="fa fa-times-circle"></i> </span>
                                </div>
                                <div class="form-group">
                                    {!! Form::label('reminder_date',trans('Scheduler::texts.reminder_date'),['for'=>'reminder_date', 'class'=>'col-sm-2 control-label']) !!}
                                    <div class="col-sm-10">
                                        {!! Form::input('text','reminder_date[]',$reminder->reminder_date, ['class'=>'form-control datepicker reminder_date ','style'=>'cursor: pointer','readonly']) !!}
                                    </div>
                                </div>
                                <div class="form-group">
                                    {!! Form::label('reminder_location',trans('Scheduler::texts.reminder_location'),['class'=>'col-sm-2 control-label']) !!}
                                    <div class="col-sm-10">
                                        {!! Form::text('reminder_location[]',$reminder->reminder_location ,['class'=>'form-control']) !!}
                                    </div>
                                </div>
                                <div class="form-group">
                                    {!! Form::label('reminder_text',trans('Scheduler::texts.reminder_text'),['class'=>'col-sm-2 control-label']) !!}
                                    <div class="col-sm-10">
                                        {!! Form::text('reminder_text[]',$reminder->reminder_text,['class'=>'form-control']) !!}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif

                    <div id="addReminderShow">
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <button type="button" id="addReminderCreate"
                                    class="btn btn-primary"><i class="fa fa-plus"></i> {{ trans('Scheduler::texts.add_reminder') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div style="text-align:center" class="buttons">
        <a class="btn btn-warning btn-lg" href={!! URL::previous()  !!}>{{ trans('Scheduler::texts.cancel') }} <span
                    class="glyphicon glyphicon-remove-circle"></span></a>
        <button type="submit" class="btn btn-success btn-lg">{{ trans('Scheduler::texts.'.$title) }} <span
                    class="glyphicon glyphicon-floppy-disk"></span></button>
        {{--{!! Button::normal(trans('texts.cancel'))--}}
        {{--->large()--}}
        {{--->asLinkTo(URL::previous())--}}
        {{--->appendIcon(Icon::create('remove-circle')) !!}--}}

        {{--{!! Button::success($title)--}}
        {{--->submit()--}}
        {{--->large()--}}
        {{--->appendIcon(Icon::create('floppy-disk')) !!}--}}
    </div>

    {!! Form::close() !!}

    <div class="addReminderView" style="display: none">
        @include('Scheduler::partials._reminderdiv')
    </div>
@stop
@section('javascript')

    <script>
        $(document).ready(function () {
            $("#addReminderCreate").click(function (event) {
                event.preventDefault();
                $("#addReminderCreate").html('<i class="fa fa-plus"></i> {{ trans('Scheduler::texts.add_another_reminder') }}');
                $("#addReminderShow").append($(".addReminderView").html());
            });
            //changed on focus to mousedown. was taking 2 clicks
            $(document).on('mousedown', '.reminder_date', function () {
                $(this).datetimepicker({
                    //timepicker: false,
                    format: 'Y-m-d H:i',
                    defaultDate: '+1970/01/08' //plus 1 week
                });
            });

            $(document).on('click', '.delete_reminder', function () {
                $(this).parent().parent().remove();
            });
        });
        var event = angular.module('event', [], function ($interpolateProvider) {
            $interpolateProvider.startSymbol('{dfh');
            $interpolateProvider.endSymbol('dfh}');
        });
        event.controller('scheduleEventController', function ($scope, $http) {
            $scope.create = function (event) {
                event.preventDefault();
                var req = {
                    method: 'POST',
                    url: "{!! route('scheduler.updateevent') !!}",
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'},//{"X-Requested-With":"XMLHttpRequest"}
                    data: $("#event").serialize()
                };

                $http(req).then(function (response) {
                    if (response.data.type === 'success') {
                        pnotify('{{trans('Scheduler::texts.'.$message)}}', 'success');
                        setTimeout(function() {//give pnotify a chance to display before redirect
                            window.location.href = "{!!  route('scheduler.tableevent') !!}";
                        }, 2000);
                    } else {
                        pnotify('{{trans('Scheduler::texts.unknown_error')}}', 'error');
                    }
                }).catch(function (response) {
                    var errors = '';
                    for (datas in response.data) {
                        errors += response.data[datas] + '<br>';
                    }
                    pnotify(errors, 'error');
                });
            };
        });
    </script>
@stop
