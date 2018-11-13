@extends('layouts.secured_header')
@section('title')
    {!! t('Context Finder') !!}
@stop
@section('content')
    <div class="container-fluid contributorMain definePage contextFinder">
        <div class="wrapperMask"></div>
        <div class="row">
            <div class="col-md-12">
                @include('layouts.flc_header')
            </div>

        </div>
        {!! Form::open(['url'=>lang_route('context-finder'), 'method'=>'post', 'id'=>'form-submission']) !!}
        <div class="row mt-4">
            <div class="col-md-12">
                <label class="customLabel pt-2 d-inline">Enter Your Text or Attach Document</label>
                <div class="float-right">
                    <label>
                        <input type="file" class="d-none" id='upload_file' onchange="onUpload()">
                        <span class="orangeBtn">upload document</span>
                    </label>
                </div>
                <div class="float-right">
                    <p id="error_upload" class="hidden bold red bc_none p10"></p>
                </div>
                {!! Form::textarea('context', null, ['id'=>'meaning-area','class'=>'enter-phrase', 'placeholder'=>'Enter phrase meaning']) !!}
                <p class="text-right white-text"><span id="count">{!! t('Characters') !!} {!! strlen(Input::old('meaning')) !!}/2500</span></p>
            </div>

            <div class="col-md-12 mt-4 text-center actionsBtn">
                <button class="orangeBtn ml-3 waves-light">Submit</button>
                <a href="{!! lang_route('context-finder') !!}" class="orangeBtn ml-3 waves-light">Reset</a>
            </div>
        </div>
        {!! Form::close() !!}
        @if($context)
            <div class="exportRow">
                <div class="col-md-6">
                    <span>Context</span>
                    <select class="selectOption">
                        <option>Oilfield</option>
                        <option>Oilfield</option>
                        <option>Oilfield</option>
                        <option>Oilfield</option>
                    </select>
                </div>

                <div class="col-md-6 text-right">
                    <button type="button" class="orangeBtn">Export report</button>
                </div>

            </div>

            <div class="col-md-12">
                <div class="exportBlock">
                    In <a href="#">petroleum production</a>, the <a href="#">casing hanger</a> is that portion of a wellhead assembly which provides support for the <a href="#">casing string</a> when it is lowered into the wellbore. It serves to ensure that the casing is properly located.
                </div>

                <div class="contextTabs">
                    <ul class="nav nav-pills" role="tablist">
                        <li>
                            <a class="active" data-toggle="pill" href="#tab1">Text Key Words</a>
                        </li>
                        <li>
                            <a class="" data-toggle="pill" href="#tab2">Context Glossary</a>
                        </li>
                    </ul>

                    <div class="tab-content">
                        <div id="tab1" class="tab-pane active">
                            <div class="table-responsive">
                                <table class="table contextTable">
                                    <tbody>
                                    <tr>
                                        <td>Jargon</td>
                                        <td>Meaning</td>
                                        <td>Illustration</td>
                                        <td>Related items</td>
                                        <td>Translation</td>
                                        <td class="text-right lastChild">
                                            <select class="languageBar">
                                                <option>French</option>
                                                <option>French</option>
                                                <option>French</option>
                                                <option>French</option>
                                                <option>French</option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Wellhead assembly</td>
                                        <td>Component at the surface of an <a href="#">oil gas</a> as well that provides the interface</td>
                                        <td><img src="images/context-finder-img.jpg" class="tableImg"></td>
                                        <td><a href="#">Blowout preventer, Christmas tre, Pipe ram, shear ram, Blind shear ram</a></td>
                                        <td>Assemblage de tete de puits</td>
                                        <td class="lastChild">Element a <a href="#">la surface</a> d’un puits de petrole ou de gas qui assure <a href="#">I’interface et la.</a></td>
                                    </tr>
                                    <tr>
                                        <td>Wellhead assembly</td>
                                        <td>Component at the surface of an <a href="#">oil gas</a> as well that provides the interface</td>
                                        <td><img src="images/context-finder-img.jpg" class="tableImg"></td>
                                        <td><a href="#">Blowout preventer, Christmas tre, Pipe ram, shear ram, Blind shear ram</a></td>
                                        <td>Assemblage de tete de puits</td>
                                        <td class="lastChild">Element a <a href="#">la surface</a> d’un puits de petrole ou de gas qui assure <a href="#">I’interface et la.</a></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div id="tab2" class="tab-pane fade">
                            <div class="table-responsive">
                                <table class="table contextTable">
                                    <tbody>
                                    <tr>
                                        <td>Jargon</td>
                                        <td>Meaning</td>
                                        <td>Illustration</td>
                                        <td>Related items</td>
                                        <td>Translation</td>
                                        <td class="text-right lastChild">
                                            <select class="languageBar">
                                                <option>French</option>
                                                <option>French</option>
                                                <option>French</option>
                                                <option>French</option>
                                                <option>French</option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Wellhead assembly</td>
                                        <td>Component at the surface of an <a href="#">oil gas</a> as well that provides the interface</td>
                                        <td><img src="images/context-finder-img.jpg" class="tableImg"></td>
                                        <td><a href="#">Blowout preventer, Christmas tre, Pipe ram, shear ram, Blind shear ram</a></td>
                                        <td>Assemblage de tete de puits</td>
                                        <td class="lastChild">Element a <a href="#">la surface</a> d’un puits de petrole ou de gas qui assure <a href="#">I’interface et la.</a></td>
                                    </tr>
                                    <tr>
                                        <td>Wellhead assembly</td>
                                        <td>Component at the surface of an <a href="#">oil gas</a> as well that provides the interface</td>
                                        <td><img src="images/context-finder-img.jpg" class="tableImg"></td>
                                        <td><a href="#">Blowout preventer, Christmas tre, Pipe ram, shear ram, Blind shear ram</a></td>
                                        <td>Assemblage de tete de puits</td>
                                        <td class="lastChild">Element a <a href="#">la surface</a> d’un puits de petrole ou de gas qui assure <a href="#">I’interface et la.</a></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
    <script type="text/javascript">
        function onUpload() {
            $("#meaning-area").val('');
            var file = document.getElementById("upload_file").files[0];
            var extension = file['name'].split('.').pop();
            if(extension == 'txt'){
                if(!$('#error_upload').hasClass('hidden')){
                    $('#error_upload').addClass('hidden');
                }
                var textArea = document.getElementById("meaning-area");
                var reader = new FileReader();
                reader.onload = function (e) {
                    textArea.value = e.target.result;
                };
                reader.readAsText(file);
            }else{
                $('#error_upload').removeClass('hidden');
                $('#error_upload').html('Only .txt type files can be uploaded!');
            }
        }
    </script>
    {!! HTML::script('assets/js/login.js') !!}
@endsection