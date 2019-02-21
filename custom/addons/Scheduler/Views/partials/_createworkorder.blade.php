{!! Html::style('assets/addons/Scheduler/Assets/bootstrap-switch/dist/css/bootstrap3/bootstrap-switch.min.css') !!}
{!! Html::script('assets/addons/Scheduler/Assets/bootstrap-switch/dist/js/bootstrap-switch.min.js') !!}

<div id="create-workorder" title="{{ trans('Scheduler::texts.create_workorder') }}" style="display: none">
    {!! Form::open(['route' => 'api.createwo','id' => 'create-workorderform']) !!}
    <input type="hidden" name="_token" value="{{ csrf_token() }}" id="csrf">
    <input type="hidden" name="user_id" value="{{ auth()->user()->id }}" id="user_id">
    <div class="form-group">
        <label for="company_profile_id" class="col-sm-4 control-label">{{ trans('fi.company_profile') }}</label>
        <div class="col-sm-8">
            {!! Form::select('company_profile_id', $companyProfiles, config('fi.defaultCompanyProfile'),
            ['id' => 'company_profile_id', 'class' => 'form-control']) !!}
        </div>
    </div>
    <div class="form-group">
        <label for="client_name" class="col-sm-4 control-label">{{ trans('Scheduler::texts.customer') }}</label>
        <div class="col-sm-8">
            <input type="text" id="client_name" name="client_name" class="form-control"
                   placeholder="Enter Customer Name" minlength="3" required>
        </div>
    </div>
    <div class="form-group">
        <label for="summary" class="col-sm-4 control-label">{{ trans('Scheduler::texts.job_summary') }}</label>
        <div class="col-sm-8">
            <input type="text" id="summary" name="summary" class="form-control"
                   placeholder="Enter Job Summary - (500 characters max)"
                   value="" minlength="3" required>
        </div>
    </div>
    <input type="hidden" id="workorder_date" name="workorder_date" value="{!! date('Y-m-d') !!}">
    <input type="hidden" id="job_date" name="job_date" value="">
    <div class="form-group">
        <label for="start_time"
               class="col-sm-4 control-label">{{ trans('Scheduler::texts.start_datetime') }}</label>
        <div class="col-sm-8">
            <input type="text" id="start_time" required name="start_time"
                   class="form-control datepicker from readonly"
                   placeholder="Start DateTime">
        </div>
    </div>
    <div class="form-group">
        <label for="end_time" class="col-sm-4 control-label">{{ trans('Scheduler::texts.end_datetime') }}</label>
        <div class="col-sm-8">
            <input type="text" id="end_time" required name="end_time" class="form-control datepicker to readonly"
                   placeholder="End DateTime">
        </div>
    </div>
    <div class="form-group">
        <label for="will_call" class="col-sm-4 control-label"> {{ trans('Scheduler::texts.will_call') }}</label>
        <div class="col-sm-8">
            {!! Form::checkbox('will_call', 1, null, ['id' => 'will_call', 'class' => 'checkbox']) !!}
        </div>

        <script>
            $.fn.bootstrapSwitch.defaults.size = 'small';
            $.fn.bootstrapSwitch.defaults.onText = 'Yes';
            $.fn.bootstrapSwitch.defaults.offText = 'No';
            $.fn.bootstrapSwitch.defaults.onColor = 'success';
            $.fn.bootstrapSwitch.defaults.offColor = 'danger';
            $("[name='will_call']").bootstrapSwitch();
        </script>
    </div>
    <div class="row">
        <div class="col-md-6 text-center">
            <br>
            <b>{{ trans('Scheduler::texts.available_workers') }}</b><br>
            {!!  trans('Scheduler::texts.toworkorder_names_blue')  !!}
            <br>
            {{ trans('Scheduler::texts.select_workers_toworkorder') }}<br>
            <table style="margin-left:auto;margin-right:auto" id="wtable">
                <tr>
                    <th></th>
                </tr>
                {{--script to get selected worker checkboxes,format as uri, and open new fusioninvoice workorder--}}
                <script>
                    wstr = '';
                    $('#create-workorder').on('click', '[id*=worker]', function () {
                        counter = 0;
                        //var wsel = $('input[type=checkbox]:checked').map(function (_, el) {
                        var wsel = $('#worker:checked').map(function (_, el) {
                            counter++;
                            return "worker" + counter + "/" + $(el).val();
                        }).get();

                        wstr = wsel.join("/");
                        //console.log(wstr);
                    });
                </script>
            </table>
        </div>

        <div class="col-md-6 text-center">
            <br>
            <b>{{ trans('Scheduler::texts.available_equip') }}</b><br><br>
            {{ trans('Scheduler::texts.select_items_toworkorder') }}
            <table style='margin-left:auto;margin-right:auto' id="rtable">
                <tr>
                    <th></th>
                </tr>
                <script>
                    rstr = '';
                    $('#create-workorder').on('click', '[id*=resource]', function () {
                        counter = 0;
                        //var rsel = $('input[type=checkbox]:checked').map(function (_, el) {
                        var rsel = $('#resource:checked').map(function (_, el) {
                            counter++;
                            return "resource" + counter + "/" + $(el).val();
                        }).get();

                        rstr = rsel.join("/");
                        //console.log(rstr);
                    });
                </script>
            </table>
        </div>
    </div>
    {!! Form::close() !!}
</div>
