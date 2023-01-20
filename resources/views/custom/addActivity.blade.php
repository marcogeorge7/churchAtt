@extends('voyager::master')

@section('page_title', " Add Activity")

@push('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('page_header')
    <h1 class="page-title">
        <i class="{{ $dataType_trans->icon }}"></i>
        {{ __('voyager::generic.'.(isset($dataTypeContent->id) ? 'edit' : 'add')).' '.$dataType_trans->getTranslatedAttribute('display_name_singular') }}
    </h1>
@stop

@section('content')
    <div class="page-content container-fluid">
        <div class="row">
            <div class="col-md-8">
                <form class="form-edit-add" role="form" action="{{ route("store-session") }}"
                      method="POST" enctype="multipart/form-data" autocomplete="off">
                    {{ csrf_field() }}
                    <div class="panel panel-bordered">
                        @if (count($errors) > 0)
                            <div class="alert alert-danger">
                                <ul>@foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach</ul>
                            </div>
                        @endif

                        <div class="panel-body">
                            <div class="form-group col-md-6">
                                <label for="year_id">{{ __('Year') }}</label>
                                <select class="form-control select2" name="year_id" id="year_id">
                                    <option value="">{{__('voyager::generic.none')}}</option>
                                    @foreach($years as $year)
                                        <option
                                            {{ ($session != null && $session->year_id == $year->id)? "selected" : "" }} value="{{$year->id}}">{{$year->gregorian}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group col-md-6">
                                <label for="service_id">{{ __('Service') }}</label>
                                <select class="form-control select2" name="service_id" id="service_id">
                                    <option value="" >{{__('voyager::generic.none')}}</option>
                                    @foreach($services as $service)
                                        <option
                                            {{ ($session != null && $session->service_id == $service->id)? "selected" : "" }}
                                            value="{{$service->id}}">{{$service->all_parent}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group col-md-6">
                                <label for="activity_id">{{ __('Activity') }}</label>
                                <select class="form-control select2" name="activity_id" id="activity_id">
                                    <option value="">{{__('voyager::generic.none')}}</option>
                                    @foreach($activities as $act)
                                        <option
                                            {{ ($activity != null && $activity->id == $act ->id)? "selected" : "" }} value="{{$act->id}}">{{$act->name}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group col-md-6">
                                <label for="date">{{ __('Date') }}</label>
                                <input type="date" class="form-control" id="date" name="date"
                                       placeholder="Date" value="{{ old('date', '') }}">
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="session_id" value="{{ $session_id }}">
                    <button type="submit" class="btn btn-primary pull-right save">
                        {{ __('voyager::generic.save') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
    @if($session_id != null)
        <div class="page-content container-fluid">
            <div class="row">
                <div class="col-md-8">
                    <form class="form-edit-add" role="form" action="{{ route("store-session") }}"
                          method="POST" enctype="multipart/form-data" autocomplete="off">
                        {{ csrf_field() }}
                        <div class="panel panel-bordered">
                            @if (count($errors) > 0)
                                <div class="alert alert-danger">
                                    <ul>@foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach</ul>
                                </div>
                            @endif

                            <div class="panel-body">
                                <table
                                    class="text-center table table-striped table-bordered table-hover table-checkable order-column"
                                    id="usersTable">
                                    <thead>
                                    <tr>
                                        @foreach($requirements as $req)
                                            <th class="text-center">{{ $req->req_name }}</th>
                                        @endforeach
                                        <th class="text-center"> الأسم</th>
                                        <th class="text-center" width="3%"> #</th>
                                    </tr>
                                    </thead>
                                    <tbody id="records" class="text-center">
                                    @foreach($members as $mem)
                                        <tr>
                                            @foreach($requirements as $req)
                                                <th class="text-center">
                                                    <input type="checkbox" class="form-check-input check"
                                                           @if($trans->where('requirement_id', $req->id)->where('person_id', $mem->id)->first()) checked @endif
                                                           id="check_{{$req->id}}_{{$mem->id}}"
                                                           data-session="{{$session_id}}">
                                                </th>
                                            @endforeach
                                            <td>{{ $mem->persson_name }}</td>
                                            <th scope="row">{{ $loop->index +1 }}</th>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                                {{--                            <div class="form-group col-md-12">--}}
                                {{--                                <label for="year">{{ __('Year') }}</label>--}}
                                {{--                                <select class="form-control select2" name="year" id="year">--}}
                                {{--                                    <option value="">{{__('voyager::generic.none')}}</option>--}}
                                {{--                                    @foreach($years as $year)--}}
                                {{--                                        <option value="{{$year->id}}">{{$year->gregorian}}</option>--}}
                                {{--                                    @endforeach--}}
                                {{--                                </select>--}}
                                {{--                            </div>--}}
                            </div>
                        </div>
                        {{--                        <button type="submit" class="btn btn-primary pull-right save">--}}
                        {{--                            {{ __('voyager::generic.save') }}--}}
                        {{--                        </button>--}}
                    </form>
                </div>
            </div>
        </div>
    @endif

@stop

@push('javascript')
    <script type="text/javascript">
        $('document').ready(function () {
            $('#service_id option').hide();
            // // $('#service_id').trigger('change');
            // $('#service_id').select2().trigger('change');

            var allocation = @json($allocator);
            $('#year_id').change(function(){
                year = $("#year_id").val();
                serv_name = allocation[year];
                console.log(serv_name);

                $('.select2-results__option').hide();

                $('#select2-service_id-results').find('li[text*="'+ serv_name +'"]').show();
                // $('#service_id').trigger('change');
            });

            // $("#edit-field-service-sub-cat-value option[value=" + title + "]").hide();
            $('.form-group input[type=date]').each(function (idx, elt) {
                if (elt.hasAttribute('data-datepicker')) {
                    elt.type = 'text';
                    $(elt).datetimepicker($(elt).data('datepicker'));
                } else if (elt.type !== 'date') {
                    elt.type = 'text';
                    $(elt).datetimepicker({
                        format: 'L',
                        viewDate: '10-10-2022',
                        extraFormats: ['YYYY-MM-DD']
                    }).datetimepicker($(elt).data('datepicker'));
                }
            });
            @if((isset($session))) $("#date").val("{{$session->date}}");
            @endif
            $('#records').on('change', '.check', function () {
                var person = $(this).attr('id').replace('check_', ''),
                    id = $(this).attr('id'),
                    session_id = $(this).data('session');
                if ($(this).is(":checked")) {
                    if (confirm('هل تريد تسجيل لحضور بالفعل ؟ ')) {
                        var posting = $.post('/att/admin/attend/store/' + session_id,
                            {
                                _token: '{{ csrf_token() }}',
                                att: 1,
                                person: person
                            }).done(function () {

                        }).fail(function () {
                            $('#'+id).prop('checked', false);
                        })
                    } else {
                        $('#'+id).prop('checked', false);
                    }
                } else if ($(this).is(":not(:checked)")) {
                    if (confirm('هل تريد إلغاء تسجيل لحضور بالفعل ؟ ')) {
                        $.post({
                            url: '/att/admin/attend/store/' + session_id,
                            data: {
                                _token: '{{ csrf_token() }}',
                                att: 0,
                                person: person
                            }
                        }).done(function (e) {
                        }).fail(function (a) {
                            $('#'+id).prop('checked', true);
                        })
                    } else {
                        $('#'+id).prop('checked', true);
                    }
                }
            });
        });
    </script>
@endpush

