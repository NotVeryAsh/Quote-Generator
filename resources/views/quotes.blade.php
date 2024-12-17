<!DOCTYPE html>
<html lang="en">
    <head>
        <title>
            Daily Quote
        </title>
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <meta charset="utf-8" />
        <link rel="icon" type="image/x-icon" href="favicon.ico">
        <link rel="stylesheet" href="app.css">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
        <script src="https://cdn.tailwindcss.com"></script>
        <script src="https://kit.fontawesome.com/3d5cd4ba6c.js" crossorigin="anonymous"></script>
    </head>
    <body class="flex flex-col px-12 lg:px-20 2xl:px-12">
        <div id="quote" class="flex flex-col text-center m-auto h-[70vh] justify-center space-y-10">
            <h1 class="text-3xl italic">
                "{{ $selectedQuote->quote }}"
            </h1>
            <div class="flex justify-between text-center">
                <div class="w-3/12 sm:w-4/12 lg:w-4/12"></div>
                <p class="font-bold text-xl w-6/12 sm:w-4/12 lg:w-4/12">
                    - {{ $selectedQuote->author }}
                </p>
                <div class="flex w-3/12 sm:w-4/12 lg:w-4/12 mr-auto space-x-3 sm:space-x-5 pl-4 sm:pl-10">
                    @if($votedFor->contains($selectedQuote->id))
                        <div class="flex rounded-full h-8 w-8 sm:h-10 sm:w-10 hover:cursor-not-allowed shadow green-disabled" title="You upvoted this quote">
                            <i class="fa-solid fa-arrow-up m-auto"></i>
                        </div>
                    @else
                        <div id="quote-{{ $selectedQuote->id }}" class="flex rounded-full h-8 w-8 sm:h-10 sm:w-10 shadow green" title="Upvote this quote" onclick="upvote({{ $selectedQuote->id }})">
                            <i class="fa-solid fa-arrow-up m-auto"></i>
                        </div>
                    @endif
                    <p id="vote-count-{{ $selectedQuote->id }}" class="m-auto">
                        {{ $selectedQuote->upvote_count }}
                    </p>
                </div>
            </div>
        </div>
        <div id="quotes" class="flex flex-col text-center mt-10 w-12/12 lg:w-8/12 mx-auto">
                <h2 class="text-2xl font-bold mb-10">Other Quotes</h2>
            @foreach($quotes as $quote)
                <h3 class="italic text-xl mb-4">
                    "{{ $quote->quote }}"
                </h3>
                <div class="flex justify-between text-center mb-10">
                    <div class="w-3/12 sm:w-4/12 lg:w-4/12"></div>
                    <p class="font-bold w-6/12 sm:w-4/12 lg:w-4/12">
                        - {{ $quote->author }}
                    </p>
                    <div class="flex w-3/12 sm:w-4/12 lg:w-4/12 mr-auto space-x-5 pl-3 sm:pl-10">
                        @if($votedFor->contains($quote->id))
                            <div class="flex rounded-full h-8 w-8 shadow hover:cursor-not-allowed green-disabled" title="You upvoted this quote">
                                <i class="fa-solid fa-arrow-up m-auto"></i>
                            </div>
                        @else
                            <div id="quote-{{ $quote->id }}" class="flex rounded-full h-8 w-8 shadow green" title="Upvote this quote" onclick="upvote({{ $quote->id }})">
                                <i class="fa-solid fa-arrow-up m-auto"></i>
                            </div>
                        @endif
                        <p id="vote-count-{{ $quote->id }}" class="m-auto">
                            {{ $quote->upvote_count }}
                        </p>
                    </div>
                </div>
            @endforeach
        </div>
        <script>
            async function upvote(id) {
                const quote = document.getElementById(`quote-${id}`);
                const voteCount = document.getElementById(`vote-count-${id}`);
                voteCount.innerHTML = (parseInt(voteCount.innerHTML) + 1).toString();
                quote.classList.remove("green")
                quote.classList.add("hover:cursor-not-allowed")
                quote.classList.add("green-disabled")
                quote.setAttribute("title", "You upvoted this quote")
                quote.removeAttribute("onclick")
                quote.removeAttribute("id")

                await fetch(`/upvote/${id}`, {
                    "method": "PUT",
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                });
            }
        </script>
    </body>
</html>
