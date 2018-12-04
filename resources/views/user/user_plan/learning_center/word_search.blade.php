<form id="form-submission" action="{{ lang_route('explore-word-search') }}" method="post">
    <div class="explore-search">
        <label>Enter Word:</label>
        <input type="text" name="search" class="fld" placeholder="Enter Your Keyword"/>
        <input type="hidden" name="_token" value="{{csrf_token()}}" />
        <input type="hidden" name="_method" value="post" />
        <button class="search-btn" type="submit" form="form-submission" value="Submit"><i class="fa fa-search"></i></button>
    </div>
</form>