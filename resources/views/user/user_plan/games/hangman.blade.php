@extends('layouts.secured_header')
@section('title')
    {!! t('Hangman') !!}
@stop
@section('content')
<div class="container-fluid contributorMain funfact pictionaryQuiz hangman">
    @include('layouts.flc_header')
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="gameTitle">
                Context: {{$context}}
            </div>
        </div>

        <div class="col-md-12">
            <a href="{!! lang_route('start-hangman') !!}" class="btn orangeBtn waves-effect waves-light p10 float-right">
                <img src="{!! asset('storage/images/replay-symbol.png') !!}">
            </a>
            <div class="gameWrapper">
                <img id="hangman" src="{!! asset('assets/images/0.png') !!}">
                <h3 class="category"></h3>
                <div id="container">
                    <!-- Random word Divs appended here -->
                </div>
                <div>
                    <button>A</button>
                    <button>B</button>
                    <button>C</button>
                    <button>D</button>
                    <button>E</button>
                    <button>F</button>
                    <button>G</button>
                    <button>H</button>
                    <button>I</button>
                </div>

                <div>
                    <button>J</button>
                    <button>K</button>
                    <button>L</button>
                    <button>M</button>
                    <button>N</button>
                    <button>O</button>
                    <button>P</button>
                    <button>Q</button>
                </div>

                <div>
                    <button>R</button>
                    <button>S</button>
                    <button>T</button>
                    <button>U</button>
                    <button>V</button>
                    <button>W</button>
                    <button>X</button>
                    <button>Y</button>
                    <button>Z</button>
                </div>
            </div>
        </div>

    </div>

</div>
<script type="text/javascript">
    $(document).ready(function () {

        // Pick a category and secret word
        var categories = [
            [
                @foreach($context_phrases as $context_phrase)
                    '{{$context_phrase->phrases->phrase_text}}',
                @endforeach
            ]
        ];
        var randomCategoryArray = categories[Math.floor((Math.random() * categories.length))];
        var randomWord = (randomCategoryArray[Math.floor((Math.random() * randomCategoryArray.length))]).toUpperCase();
        var randomWordArray = randomWord.split("");


        // Draw squares for secret word & hide letters
        for (var i = 0; i < randomWord.length; i++) {
            $('#container').append('<div class="letter ' + i + '"></div>');
            $('#container').find(":nth-child(" + (i + 1) + ")").text(randomWordArray[i]);
            $(".letter").css("color", "#000");
            $(".letter").css("background-color", "#000");
        }

        // Button click function
        var wrongGuesses = 0;
        $("button").on("click", function () {
            $(this).addClass("used");
            $(this).prop("disabled", "true");
            var matchFound = false;

            // Check if clicked letter is in secret word
            var userGuess = $(this).text();
            var elem = this;
            console.log($(elem).text());
            for (var i = 0; i < randomWord.length; i++) {
                if (userGuess === randomWord.charAt(i)) {
                    $(elem).addClass("correct_char");
                    $('#container').find(":nth-child(" + (i + 1) + ")").css("color", "#EFEFEF").addClass("winner");
                    matchFound = true;
                }
            }

            //Check for winner
            var goodGuesses = [];
            $(".letter").each(function (index) {
                if ($(this).hasClass("winner")) {
                    goodGuesses.push(index);
                    if (goodGuesses.length === randomWordArray.length) {
                        //$("#container").hide();
                        $("button").prop("disabled", "true");
                        $(".category").text("Great job you guessed the secret word!");
                        // $(".category").append("<br><button enabled class='play-again'>Play again?</button>");
                    }
                }
            });

            // If no match, increase count and add appropriate image
            if (matchFound === false) {
                wrongGuesses += 1;
                $("#hangman").attr("src", "/assets/images/" + wrongGuesses + ".png");
            }

            // If wrong guesses gets to 7 exit the game
            if (wrongGuesses === 7) {
                //$("#container").hide();
                $("button").prop("disabled", "true");
                $(".category").text("Sorry you lost! The secret word was " + randomWord);
                // $(".category").append("<br><button enabled class='play-again'>Play again?</button>");
            }

            // Play again button
            $(".play-again").on("click", function () {
                location.reload();
            });

        }); // End button click

    }); // End document.ready
</script>
@endsection