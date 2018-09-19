@extends('layouts.secured_header')
@section('title')
    {!! t('Glossary') !!}
@stop
@section('content')
    <div class="container-fluid contributorMain funfact glossarybg">
        @include('layouts.flc_header')
        <div class="row mt-4">
            @if($glossary_items)
                @foreach($glossary_items as $glossary_item)
                    <div class="col-lg-3 col-md-4 col-sm-6 text-center">
                    <div class="glossary-block">
                        <div class="img-holder">
                            <img src="{!! asset('storage/'.$glossary_item->thumbnail) !!}" class="cover-img">
                            <div class="mask"></div>
                            @if(!$glossary_item->users->contains(Auth::user()->id))
                                <a href="#" class="favIcon" onclick="fav(event, this,{{$glossary_item->id}});">
                                    <i class="fa fa-star"></i>
                                </a>
                            @elseif($glossary_item->users->contains(Auth::user()->id))
                                <a href="#" class="favIcon favIcon2" onclick="unfav(event, this,{{$glossary_item->id}});">
                                    <i class="fa fa-star"></i>
                                </a>
                            @endif
                            <div class="view">
                                <p>{{$glossary_item->name}}</p>
                            <a class="fancybox gallerypdf" rel="fancybox-thumb" href="{!! asset('storage/'.$glossary_item->file) !!}" title=""><i class="fa fa-eye"></i> Quick View</a>
                            </div>
                        </div>

                        <div class="actionsBtns mt-3">
                            <a class="orangeBtn waves-light blue float-left">${{$glossary_item->price}}</a>
                            <a href="{{$glossary_item->url}}" class="orangeBtn waves-light float-right">Buy Now</a>
                        </div>

                    </div>
                </div>
                @endforeach
            @endif
        </div>
    </div>
    {!! HTML::script('assets/js/source/jquery.fancybox.pack.js') !!}
    {!! HTML::script(asset('assets/js/toaster.js')) !!}
    {!! HTML::style('//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.css') !!}
    <script>
        function fav(event, elem, book_id){
            event.preventDefault();
            $.ajax({
                type: "POST",
                url: '/en/add-to-fav',
                data: { book_id:book_id, _token: '{{csrf_token()}}'}
            }).done(function( res ) {
                if(res == 1){
                    toastr.success("Added to your collection!");
                    $(elem).addClass('favIcon2');
                    $(elem).attr('onclick', 'unfav(event, this,{{$glossary_item->id}});');
                }
            })
        }
        function unfav(event, elem, book_id){
            event.preventDefault();
            $.ajax({
                type: "POST",
                url: '/en/remove-from-fav',
                data: { book_id:book_id, _token: '{{csrf_token()}}'}
            }).done(function( res ) {
                if(res == 1){
                    toastr.success("Removed from your collection!");
                    $(elem).removeClass('favIcon2');
                    $(elem).attr('onclick', 'fav(event, this,{{$glossary_item->id}});');
                }
            })
        }
    </script>
@endsection