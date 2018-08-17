<div class="box box-info">
    <div class="box-header with-border">
        <h3 class="box-title">{{ $form->title() }}</h3>

        <div class="box-tools">
            {!! $form->renderHeaderTools() !!}
        </div>
    </div>
    <!-- /.box-header -->
    <!-- form start -->
    @if($form->hasRows())
        {!! $form->open() !!}
    @else
        {!! $form->open(['class' => "form-horizontal"]) !!}
    @endif

    <div class="box-body profile-page">

        <div class="row">
            <div class="col-md-12">
                @if(!$tabObj->isEmpty())
                    @include('admin::form.tab', compact('tabObj'))
                @else
                    <div class="fields-group">

                        @if($form->hasRows())
                            @foreach($form->getRows() as $row)
                                {!! $row->render() !!}
                            @endforeach
                        @else
                            <div class="row">
                            @foreach($form->fields() as $indexKey =>  $field)
                                @if($indexKey == 0 )
                                    <div class="col-md-2 profile-image">
                                        {!! $field->render() !!}
                                    </div>
                                @endif
                                @if($indexKey == 1 )
                                    <div class="box-body">
                                        <div class="col-md-3 profile-personal-details">
                                @endif
                                @if($indexKey > 0 && $indexKey <= 5 )
                                        {!! $field->render() !!}
                                @endif
                                @if($indexKey == 5 )
                                    </div>
                                    <div class="col-md-3 profile-personal-details">
                                @endif
                                @if($indexKey >= 6 && $indexKey <= 10)
                                        {!! $field->render() !!}
                                @endif
                                @if($indexKey == 10 )
                                    </div>
                                    <div class="col-md-3 profile-personal-details">
                                @endif
                                @if($indexKey >= 11)
                                    {!! $field->render() !!}
                                @endif
                                @if($loop->last)
                                    </div>
                                </div>
                                @endif
                            @endforeach
                            </div>
                        @endif
                    </div>
                @endif
            </div>
        </div>

    </div>
    <!-- /.box-body -->
    <div class="box-footer">

        @if( ! $form->isMode(\Encore\Admin\Form\Builder::MODE_VIEW)  || ! $form->option('enableSubmit'))
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
        @endif
        <div class="col-md-{{$width['label']}}">

        </div>
        <div class="col-md-{{$width['field']}}">

            {!! $form->submitButton() !!}

            {!! $form->resetButton() !!}

        </div>

    </div>

    @foreach($form->getHiddenFields() as $hiddenField)
        {!! $hiddenField->render() !!}
    @endforeach

<!-- /.box-footer -->
    {!! $form->close() !!}
</div>

