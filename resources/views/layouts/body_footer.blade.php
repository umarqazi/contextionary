@if(Auth::check())
    @if((count($feedback) == 0)  || ($feedback[0]->user_id != Auth::user()->id))
        <div class="commentBox">
            <button class="icon comment-icon"></button>
            <div class="box">
                <h3>User Feedback</h3>
                <p>{{$settings->where('keys', 'Feedback Question')->first()->values}}</p>
                <form onsubmit="submit_feedback();">
                    <input type="email" class="fld" id="feed_email" placeholder="Your Email">
                    <textarea class="fld text-area msg" id="feed_msg" placeholder="Your Message"></textarea>
                    <button type="submit" class="orangeBtn">Submit</button>
                </form>
            </div>
        </div>
        <script type="text/javascript">
            function submit_feedback(){
                event.preventDefault();
                $.ajax({
                    type: "POST",
                    url: '/en/submit-feedback',
                    data: {
                        email: $('#feed_email').val(),
                        message: $('#feed_msg').val(),
                        user_id: {{Auth::user()->id}},
                        _token: '{{csrf_token()}}'
                    }
                }).done(function( res ) {
                    if(res == 1){
                        toastr.success("Feedback Submitted!");
                        $('.commentBox').remove();
                    }
                })
            }
        </script>
        @endif
        @endif
        {!! HTML::script('assets/js/popper.min.js') !!}
        {!! HTML::script('assets/js/bootstrap.min.js') !!}
        {!! HTML::script('assets/js/mdb.min.js') !!}
        {!! HTML::script('assets/js/custom.js') !!}
        {!! HTML::script('assets/js/jquery.mCustomScrollbar.concat.min.js') !!}
        </body>
        </html>
