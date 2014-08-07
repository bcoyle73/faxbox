@section('head')
<link rel="stylesheet" href="{{ asset('jquery-upload/css/jquery.fileupload.css') }}">
@stop

@section('content')
<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Send Fax</h1>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    
    <div class="row">
        {{ Form::open(array('action' => 'FaxController@store',
        'enctype' => 'multipart/form-data', 'class' => 'fax-form',
        'id' => 'faxform', 'novalidate' => '')) }}

        <div class="form-group">
            <div class="col-md-4">
            <label for="toName" class="control-label sr-only">Recipient's
                Fax Number</label>

            <div class="input-group">
                <div class="input-group-btn">
                    <button type="button"
                            class="btn btn-default dropdown-toggle"
                            data-toggle="dropdown"><img id="flag" src="/images/flags-iso/shiny/16/{{ strtoupper($countries[0]['short']) }}.png">
                        +<span
                            id="cc">{{ $countries[0]['code'] }}</span>
                        <span class="caret"></span></button>
                    <ul class="dropdown-menu text-left">
                        @foreach($countries as $country)
                        <li
                        {{ $country['style'] }}><a
                            class="changeCountry"
                            data-country="{{ $country['short'] }}"
                            data-code="{{ $country['code'] }}"
                            data-flag="/images/flags-iso/shiny/16/{{ strtoupper($country['short']) }}.png"
                            href="#"><img
                                src="/images/flags-iso/shiny/16/{{ strtoupper($country['short']) }}.png" alt="{{ strtoupper($country['name']) }}">
                            {{ $country['name'] }}
                            <small>+{{ $country['code'] }}</small>
                        </a></li>
                        @endforeach
                    </ul>
                </div>
                <!-- /btn-group -->
                {{ Form::text('number', null, array('placeholder' => 'Recipient\'s Fax Number', 'class' => 'form-control', 'id' => 'number', 'pattern' => '\d*')) }}
            </div>
            </div>
            <!-- /.col-md-4 -->
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-4">
            <span class="btn btn-success fileinput-button">
            <i class="glyphicon glyphicon-plus"></i>
            <span>Add files...</span>
                <!-- The file input field used as target for the file upload widget -->
                <input id="fileupload" type="file" name="files[]" multiple>
            </span>
            <br>
            <br>
            <!-- The global progress bar -->
            <div id="progress" class="progress">
                <div class="progress-bar progress-bar-success"></div>
            </div>
            <!-- The container for the uploaded files -->
            <div id="files" class="files"></div>
        </div>
    </div>

    {{ Form::hidden('toPhoneArea', Input::old('toPhoneArea'), array('id' => 'toPhoneArea')) }}

    {{ Form::hidden('toPhoneCountry', Input::old('toPhoneCountry', $countries[0]['short']), array('id' => 'toPhoneCountry')) }}
    
    {{ Form::submit() }}
    {{ Form::close() }}
</div>
@stop

@section('scripts')
<script src="{{ asset('jquery-upload/js/vendor/jquery.ui.widget.js') }}"></script>
<!-- The Load Image plugin is included for the preview images and image resizing functionality -->
<script src="http://blueimp.github.io/JavaScript-Load-Image/js/load-image.min.js"></script>
<!-- The Canvas to Blob plugin is included for image resizing functionality -->
<script src="http://blueimp.github.io/JavaScript-Canvas-to-Blob/js/canvas-to-blob.min.js"></script>
<!-- The Iframe Transport is required for browsers without support for XHR file uploads -->
<script src="{{ asset('jquery-upload/js/jquery.iframe-transport.js') }}"></script>
<!-- The basic File Upload plugin -->
<script src="{{ asset('jquery-upload/js/jquery.fileupload.js') }}"></script>
<!-- The File Upload processing plugin -->
<script src="{{ asset('jquery-upload/js/jquery.fileupload-process.js') }}"></script>
<!-- The File Upload image preview & resize plugin -->
<script src="{{ asset('jquery-upload/js/jquery.fileupload-image.js') }}"></script>
<!-- The File Upload audio preview plugin -->
<script src="{{ asset('jquery-upload/js/jquery.fileupload-audio.js') }}"></script>
<!-- The File Upload video preview plugin -->
<script src="{{ asset('jquery-upload/js/jquery.fileupload-video.js') }}"></script>
<!-- The File Upload validation plugin -->
<script src="{{ asset('jquery-upload/js/jquery.fileupload-validate.js') }}"></script>

<script>
    $(function () {

        $("#toPhoneArea").val($('.changeCountry').data('code'));

        $(document).on('click', '.changeCountry', function () {
            $("#cc").html($(this).data('code'));
            $("#toPhoneArea").val($(this).data('code'));
            $("#toPhoneCountry").val($(this).data('country'));
            
            $("#flag").attr('src', $(this).data('flag'));
        });

        $("form#faxform").submit(function () {
            $("#toPhoneArea").val($("#cc").html());

            var number = $("#toPhoneArea").val() + $('input[name="number"]').val();
            $('<input type="hidden">')
                .attr('name', 'fullNumber')
                .attr('type', 'hidden')
                .val(number)
                .appendTo('#faxform');
            
            return true;
        });

    });

    $(function () {
        'use strict';
        // Change this to the location of your server-side upload handler:
        var url = '/faxes/upload';

        $('#fileupload').fileupload({
            url: url,
            dataType: 'json',
            done: function (e, data) {
                $.each(data.files, function (index, file) {
                    $('<p/>').text(file.name).appendTo('#files');
                    $('<input type="hidden">')
                        .attr('name', 'files[]')
                        .attr('type', 'hidden')
                        .val(data.result[index])
                        .appendTo('#faxform');
                });
            },
            progressall: function (e, data) {
                var progress = parseInt(data.loaded / data.total * 100, 10);
                $('#progress .progress-bar').css(
                    'width',
                    progress + '%'
                );
            },
            formData: [
                { name: '_token', value: $('meta[name="csrf-token"]').attr('content') }
            ]
        }).prop('disabled', !$.support.fileInput)
            .parent().addClass($.support.fileInput ? undefined : 'disabled');
    });
</script>
@stop