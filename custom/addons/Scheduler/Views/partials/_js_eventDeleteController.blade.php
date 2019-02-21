<script>
    var event = angular.module('event', [], function ($interpolateProvider) {
        $interpolateProvider.startSymbol('{dfh');
        $interpolateProvider.endSymbol('dfh}');
    });
    event.controller('eventDeleteController', function ($scope, $http) {
        $scope.delete = function (id) {
            var req = {
                method: 'GET',
                url: "{!! route($droute) !!}",
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                params: {id: id}
            };
            pconfirm_def.text = '{!! $pCnote !!}';
            new PNotify(pconfirm_def).get().on('pnotify.confirm', function () {
                $http(req).then(function (response) {
                    if (response.data === 'true') {
                        $("#" + id).hide();
                        pnotify('{!! $pnote !!}', 'success');
                    } else {
                        pnotify('{{ trans('Scheduler::texts.unknown_error') }}', 'error');
                    }
                }).catch(function (response) {
                    var errors = '';
                    for (datas in response.data) {
                        errors += response.data[datas] + '<br>';
                    }
                    pnotify(errors, 'error');
                    });
            }).on('pnotify.cancel', function () {
                //Do Nothing
                });
        };
        $scope.restore = function (id) {
            var req = {
                method: 'GET',
                url: "{!! route('scheduler.restoresingletrash') !!}",
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                params: {id: id}
            };
            pconfirm_def.text = '{{ trans('Scheduler::texts.trash_restoresingle_warning') }}';
            new PNotify(pconfirm_def).get().on('pnotify.confirm', function () {
                $http(req).then(function (response) {
                    if (response.data === 'true') {
                        $("#" + id).hide();
                        pnotify('{{ trans('Scheduler::texts.trash_restore_success') }}', 'success');
                    } else {
                        pnotify('{{ trans('Scheduler::texts.unknown_error') }}', 'error');
                    }
                }).catch(function (response) {
                    var errors = '';
                    for (datas in response.data) {
                        errors += response.data[datas] + '<br>';
                    }
                    pnotify(errors, 'error');
                    });
            }).on('pnotify.cancel', function () {
                //Do Nothing
                });
        };
    });
</script>