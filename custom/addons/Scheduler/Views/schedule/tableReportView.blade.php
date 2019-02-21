@extends('Scheduler::partials._master')

@section('content')
    @if(config('app.name') == 'FusionInvoice') {!! Form::breadcrumbs() !!} @endif
    <div class="container col-lg-12">
        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title"><i
                                    class="fa fa-edit fa-fw"></i> {{ trans('Scheduler::texts.event_search') }}
                        </h3>
                    </div>
                    <div class="panel-body">
                        {!! Form::open(['id' => 'event', 'accept-charset' => 'utf-8', 'class' => 'form-horizontal', 'method' => 'post', 'url' => 'scheduler/table_report']) !!}
                        <div class="form-group">
                            <label for="start"
                                   class="col-sm-2 control-label">{{ trans('Scheduler::texts.start_date') }}</label>
                            <div class="col-sm-10">
                                <input type="text" readonly class="form-control from" id="start" name="start" required
                                        style="width: 60%">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="end"
                                   class="col-sm-2 control-label">{{ trans('Scheduler::texts.end_date') }}</label>
                            <div class="col-sm-10">
                                <input type="text" readonly class="form-control to" id="end" name="end" required
                                        style="width: 60%">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                                <button type="submit" class="btn btn-primary"><i class="fa fa-file-o"></i> {{ trans('Scheduler::texts.run_report') }}
                                </button>
                            </div>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
@section('javascript')

@stop