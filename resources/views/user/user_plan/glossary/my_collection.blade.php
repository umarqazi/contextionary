@extends('layouts.secured_header')
@section('title')
    {!! t('My Collection') !!}
@stop
@section('content')
    <div class="container-fluid contributorMain funfact glossarybg">
        @include('layouts.flc_header')
        <div class="row mt-4">
            @if(!$glossary_items->isEmpty())
                @foreach($glossary_items as $glossary_item)
                    <div class="col-lg-3 col-md-4 col-sm-6 text-center glossary-div-block">
                    <div class="glossary-block">
                        <div class="img-holder">
                            <img src="{!! asset('storage/'.$glossary_item->thumbnail) !!}" class="cover-img">
                            <div class="mask"></div>
                            @if($glossary_item->users->contains(Auth::user()->id))
                                <a href="#" class="favIcon favIcon2" onclick="unfav(event, this,{{$glossary_item->id}});">
                                    <i class="fa fa-star"></i>
                                </a>
                            @endif
                            <div class="view">
                                <p>{{$glossary_item->name}}</p>
                                <a class="fancybox gallerypdf" rel="fancybox-thumb" href="{!! asset('storage/'.$glossary_item->file) !!}" title=""><i class="fa fa-eye"></i> Quick View</a>
                                <p>${{$glossary_item->price}}</p>
                            </div>
                        </div>

                        <div class="actionsBtns mt-3">
                            <a href="{{$glossary_item->url}}" class="orangeBtn waves-light">Buy Now</a>
                        </div>

                    </div>
                </div>
                @endforeach
            @else
                <div class="col-md-12">
                    <div class="text-center">
                        <strong class="record-message">{!! t('No Books in your collection!') !!}</strong>
                    </div>
                </div>
            @endif
        </div>
    </div>
    {!! HTML::script('assets/js/source/jquery.fancybox.pack.js') !!}
    {!! HTML::script(asset('assets/js/toaster.js')) !!}
    {!! HTML::style('//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.css') !!}
    <script>
        $(document).ready(function() {
            $(".gallerypdf").fancybox({
                openEffect: 'elastic',
                closeEffect: 'elastic',
                autoSize: true,
                type: 'iframe',
                showNavArrows : false,
                iframe: {
                    preload: false // fixes issue with iframe and IE
                },
                loop : false
            });
        });
    </script>
    <script>
        function unfav(event, elem, book_id){
            event.preventDefault();
            $.ajax({
                type: "POST",
                url: '/en/remove-from-fav',
                data: { book_id:book_id, _token: '{{csrf_token()}}'},
                async: false
            }).done(function( res ) {
                if(res == 1){
                    toastr.success("Removed from your collection!");
                    elem.closest('.glossary-div-block').parentNode.removeChild(elem.closest('.glossary-div-block'));
                }
            })
        }
    </script>
@endsection