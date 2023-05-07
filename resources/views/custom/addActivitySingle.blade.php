@extends('voyager::master')

@section('page_title', " Add Activity One")

@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        .select2-container--default .select2-results__option[aria-disabled=true] {
            display: none;
        !important;
        }

    </style>
@endsection

@section('page_header')
    <h1 class="page-title">
        <i class=""></i>
        {{ __('voyager::generic.add') }}
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
                                    <option value="">{{__('voyager::generic.none')}}</option>
                                    @foreach($services as $service)
                                        <option @if(!$is_admin) disabled @endif
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
                    <form class="form-edit-add" role="form" action="#" id="single-att"
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
                                    <label for="requirement_id">{{ __('Requirements') }}</label>
                                    <select class="form-control select2" name="requirement_id" id="requirement_id">
                                        <option value="">{{__('voyager::generic.none')}}</option>
                                        @foreach($requirements as $req)
                                            <option
                                                {{ (session('requirement') != null && session('requirement') == $req->id)? "selected" : "" }} value="{{$req->id}}">{{$req->req_name}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="code_id">{{ __('name') }}</label>
                                    <select class="form-control select2" name="code_id" id="code_id">
                                        <option value="">{{__('voyager::generic.none')}}</option>
                                        @foreach($members ?? [] as $each)
                                            <option value="{{$each->id}}">{{$each->persson_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="code">الكود</label>
                                    <input type="text" name="code" id="code">
                                </div>

                                <div class="form-group col-md-4">
                                    <div id="reader" width="600px"></div>
                                    <button id="rescan" class="btn btn-primary pull-right save">
                                        {{ __('rescan') }}
                                    </button>
                                </div>

                            </div>
                        </div>
                        <input type="hidden" id="session_id" name="session_id" value="{{ $session_id }}">
                        <button type="submit" class="btn btn-primary pull-right save">
                            {{ __('voyager::generic.save') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>

    @endif

@stop

@push('javascript')

    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>

    <script type="text/javascript">
        $('document').ready(function () {

            const html5QrCode = new Html5Qrcode("reader");
            const qrCodeSuccessCallback = (decodedText, decodedResult) => {
                $("#code").val(decodedText);
                html5QrCode.stop();
                $("#rescan").show();
            };
            const config = { fps: 10, qrbox: { width: 250, height: 250 } };
            html5QrCode.start({ facingMode: "environment" }, config, qrCodeSuccessCallback);
            $("#rescan").hide();

            $("#rescan").click(function (e) {
                e.preventDefault();
                html5QrCode.start({ facingMode: "environment" }, config, qrCodeSuccessCallback);
                $("#rescan").hide();
            });


            var allocation = @json($allocator);
            @if(!$is_admin)
            $('#year_id').change(function () {
                const options = $('#service_id option');
                options.prop("disabled", true);
                year = $("#year_id").val();
                serv_name = allocation[year];
                options.filter(function () {
                    return $(this).html().indexOf(serv_name) !== -1;
                }).prop("disabled", false);
            });
            @endif
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

            $('form #single-att').submit(function (e) {
                e.preventDefault(); // avoid to execute the actual submit of the form.

                var person = $('#code_id').val(),
                    requirement = $('#requirement_id').val(),

                    session_id = $('#session_id').val();
                var posting = $.post('/att/admin/attend/store/' + session_id,
                    {
                        _token: '{{ csrf_token() }}',
                        att: 1,
                        person: requirement + "_" + person
                    }).done(function () {

                }).fail(function () {
                    $('#' + id).prop('checked', false);
                })
            });
            @endif
        })
        ;
    </script>
@endpush
