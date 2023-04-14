<x-app-layout>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Search bar --}}
            <form class="flex items-center justify-center mb-8 w-full" action="/dashboard">
                <div class="relative mr-4 w-full">
                    <i class="absolute top-3 left-3 text-gray-400 hover:text-gray-500 fa fa-search"></i>
                    <input type="text" name="q" placeholder="Search top headlines..."
                        class="w-full h-12 pl-10 pr-16 rounded-lg focus:outline-none focus:shadow">
                </div>
                <button type="submit"
                    class="h-12 px-6 text-white rounded-lg bg-red-500 hover:bg-red-600">Search</button>
            </form>

            {{-- Categories --}}
            <div class="bg-gray-100 rounded-lg mb-6 shadow-md p-2">
                <div class="flex flex-wrap justify-center">
                    @foreach ($categoriesAll as $categoryAll)
                    @if($categoryAll==$category)
                        <a href="/dashboard?category={{ $categoryAll }}"
                            class="m-2 text-2xl text-blue-500 hover:underline">{{ strtoupper($categoryAll) }}</a>
                    @else
                    <a href="/dashboard?category={{ $categoryAll }}"
                            class="m-2 text-2xl text-black hover:underline">{{ strtoupper($categoryAll) }}</a>
                    @endif
                    @endforeach
                </div>
            </div>

            <div class="">



                {{-- Top headlines --}}
                <div class="bg-gray-100 rounded-lg shadow-md p-6">
                    <h2 class="text-2xl font-bold mb-4">Top Headlines</h2>
                    @if (count($newsTop) > 0)
                        @foreach ($newsTop as $index => $newTop)
                            <div class="mb-6">
                                @if($newTop->urlToImage)
                                    <img width="1600" height="500" src="{{ $newTop->urlToImage }}">
                                @else
                                    {{-- <img width="100" height="200" src="{{asset('pictures/no_image_r.jpeg')}}"> --}}
                                @endif
                                <a href="{{ $newTop->url }}"
                                    class="text-xl font-bold text-blue-500 hover:underline">{{ $newTop->title ?? 'No title' }}</a>
                                <p class="text-gray-600">{{ $newTop->description ?? 'No description' }}</p>
                                <p class="text-gray-700">By {{ $newTop->author ?? 'Unknown author' }}</p>
                                {{-- HERE IS USED FUNCTIONALITY OF MATCHING URLS. IT WILL HIDE THE ADD TO FAVOURITE BUTTON --}}
                                @if (array_search($newTop->url, $array))
                                    <div class="text-gray-500"  style="font-size:15px;"><span class="mt-5 font-xl fa fa-star checked text-yellow-400">&#160&#160</span> Added to favourites</div>
                                @else
                                    <form method="POST" action="/dashboard/favourite/create">
                                        @csrf
                                        <input type="hidden" id="title" name="title"
                                            value="{{ $newTop->title }}">
                                        <input type="hidden" id="url" name="url" value="{{ $newTop->url }}">
                                        <input type="hidden" id="author" name="author"
                                            value="{{ $newTop->author }}">
                                        <input type="hidden" id="description" name="description"
                                            value="{{ $newTop->description }}">
                                        <input type="hidden" id="image" name="image"
                                            value="{{ $newTop->urlToImage }}">
                                        <input type="hidden" id="user_id" name="user_id"
                                            value={{ auth()->user()->id }}>
                                        <button
                                            class="flex items-center justify-center mt-4 px-4 py-2 text-gray-600 bg-gray-200 rounded-lg hover:bg-gray-400">
                                            <i class="fa fa-heart text-red-500"> &#160</i> Add to favourites
                                        </button>
                                    </form>
                                @endif

                                <div class="mt-5">
                                    <div class="mt-5">
                                        <form class="mb-4 flex flex-row" method="POST" action="/dashboard/comments/create">
                                            @csrf
                                            <div class="flex flex-col mr-2 w-full">
                                                <textarea name="comment_text" id="comment_text" rows="1" placeholder="Leave your comment here"
                                                          class="rounded-lg border border-gray-300 p-2 focus:outline-none focus:ring-2 focus:ring-blue-400"></textarea>
                                            </div>
                                            <input type="hidden" id="user_id" name="user_id" value={{ auth()->user()->id }}>
                                            <input type="hidden" id="url" name="url" value="{{ $newTop->url }}">
                                            <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">Submit</button>
                                        </form>
                                    </div>

                                    <div class="mt-4 max-h-0 overflow-hidden transition-all duration-500 ease-in-out"
                                        id="comments-section-{{ $index }}">
                                        <!-- Comment 1 -->
                                        @php
                                            $commentCount = 0;
                                            $newTopCount = 0;
                                        @endphp
                                        @foreach($comments as $comment)
                                        @if ($comment->url == $newTop->url)
                                        @if ($comment->user_id == auth()->user()->id)
                                        <div class="bg-blue-500 rounded-lg shadow-md p-4 mb-4 flex">
                                            <div>
                                                <h4 class="text-lg font-medium text-white">{{$comment->user->name}}</h4>
                                                <p class="text-white">{{$comment->comment_text}}</p>
                                            </div>
                                            <div class="ml-auto">
                                                <form method="POST" action="/dashboard/comments/{{$comment->id}}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="flex items-center justify-center mt-4 px-4 py-2 text-gray-600 rounded-lg">
                                                        <i class="fa fa-remove text-white"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                        @else
                                        <div class="bg-gray-200 rounded-lg shadow-md p-4 mb-4">
                                            <h4 class="text-lg font-medium">{{$comment->user->name}}</h4>
                                            <p class="text-gray-600">{{$comment->comment_text}}</p>
                                        </div>
                                        @endif
                                        @php
                                            $commentCount++;
                                        @endphp
                                        @endif
                                        @endforeach
                                    </div>
                                    <h3 class="text-lg font-bold mb-5 text-gray-600"><button class="text-gray-600 hover:text-gray-800"
                                        id="expand-comments-btn-{{ $index }}">
                                        <i class="mr-2"></i>
                                        <span>Comments:</span>
                                    </button> {{$commentCount}}</h3>


                                </div>
                                <script>
                                    const expandBtn{{ $index }} = document.querySelector('#expand-comments-btn-{{ $index }}');
                                    const commentsSection{{ $index }} = document.querySelector('#comments-section-{{ $index }}');

                                    let isExpanded{{ $index }} = false;

                                    expandBtn{{ $index }}.addEventListener('click', () => {
                                        if (!isExpanded{{ $index }}) {
                                            commentsSection{{ $index }}.style.maxHeight =
                                                `${commentsSection{{ $index }}.scrollHeight}px`;
                                            expandBtn{{ $index }}.innerHTML =
                                                `<i class="mr-2"></i><span>Comments:</span>`;
                                        } else {
                                            commentsSection{{ $index }}.style.maxHeight = `0px`;
                                            expandBtn{{ $index }}.innerHTML =
                                                `<i class="mr-2"></i><span>Comments:</span>`;
                                        }
                                        isExpanded{{ $index }} = !isExpanded{{ $index }};
                                    });
                                </script>

                        @endforeach

                        @if ($numberOfPages > 1)
                            <div class="mt-6 flex justify-center items-center">
                                @if ($numberOfPages > 1)
                                    <div class="flex flex-wrap justify-center">
                                        @php
                                            $startPage = max($currentPage - 2, 1);
                                            $endPage = min($startPage + 4, $numberOfPages);
                                            if ($endPage - $startPage < 4) {
                                                $startPage = max($endPage - 4, 1);
                                        } @endphp @if ($startPage > 1)
                                            @if ($q)
                                                <a href="{{ $url }}?pageTop=1&q={{ $q }}"
                                                    class="mx-1 px-2 py-1 rounded-lg bg-gray-300 hover:bg-gray-400 text-sm font-medium">1</a>
                                            @elseif($category)
                                                <a href="{{ $url }}?pageTop=1&category={{ $category }}"
                                                    class="mx-1 px-2 py-1 rounded-lg bg-gray-300 hover:bg-gray-400 text-sm font-medium">1</a>
                                            @else
                                                <a href="{{ $url }}?pageTop=1"
                                                    class="mx-1 px-2 py-1 rounded-lg bg-gray-300 hover:bg-gray-400 text-sm font-medium">1</a>
                                            @endif
                                            @if ($startPage > 2)
                                                <span
                                                    class="mx-1 px-2 py-1 rounded-lg bg-gray-300 text-sm font-medium">...</span>
                                            @endif
                                        @endif
                                        @if ($q)
                                            @for ($x = $startPage; $x <= $endPage; $x++)
                                                <a href="{{ $url }}?pageTop={{ $x }}&q={{ $q }}"
                                                    class="mx-1 px-2 py-1 rounded-lg bg-gray-300 hover:bg-gray-400 text-sm font-medium @if ($x == $currentPage) font-bold @endif">
                                                    {{ $x }}</a>
                                            @endfor
                                        @elseif($category)
                                            @for ($x = $startPage; $x <= $endPage; $x++)
                                                <a href="{{ $url }}?pageTop={{ $x }}&category={{ $category }}"
                                                    class="mx-1 px-2 py-1 rounded-lg bg-gray-300 hover:bg-gray-400 text-sm font-medium @if ($x == $currentPage) font-bold @endif">
                                                    {{ $x }}</a>
                                            @endfor
                                        @else
                                            @for ($x = $startPage; $x <= $endPage; $x++)
                                                <a href="{{ $url }}?pageTop={{ $x }}"
                                                    class="mx-1 px-2 py-1 rounded-lg bg-gray-300 hover:bg-gray-400 text-sm font-medium @if ($x == $currentPage) font-bold @endif">
                                                    {{ $x }}</a>
                                            @endfor
                                        @endif
                                        @if ($endPage < $numberOfPages)
                                            @if ($endPage < $numberOfPages - 1)
                                                <span
                                                    class="mx-1 px-2 py-1 rounded-lg bg-gray-300 text-sm font-medium">
                                                    ...</span>
                                            @endif
                                            @if ($q)
                                                <a href="{{ $url }}?pageTop={{ $numberOfPages }}&q={{ $q }}"
                                                    class="mx-1 px-2 py-1 rounded-lg bg-gray-300 hover:bg-gray-400 text-sm font-medium">{{ $numberOfPages }}</a>
                                            @elseif($category)
                                                <a href="{{ $url }}?pageTop={{ $numberOfPages }}&category={{ $category }}"
                                                    class="mx-1 px-2 py-1 rounded-lg bg-gray-300 hover:bg-gray-400 text-sm font-medium">{{ $numberOfPages }}</a>
                                            @else
                                                <a href="{{ $url }}?pageTop={{ $numberOfPages }}"
                                                    class="mx-1 px-2 py-1 rounded-lg bg-gray-300 hover:bg-gray-400 text-sm font-medium">{{ $numberOfPages }}</a>
                                            @endif
                                        @endif
                                    </div>
                                @endif
                            </div>

                        @endif
                    @else
                        <p>No news found</p>
                    @endif
                </div>

                {{-- Favourites --}}
                {{-- <div class="bg-gray-100 rounded-lg shadow-md p-6">
                    <h2 class="text-2xl font-bold mb-4">Favourites</h2>
                    @if (count($favourites) > 0)
                    @foreach ($favourites as $favourite)
                    <div class="mb-6">
                        <img class="h-30 w-30" src="{{ $favourite->imageUrl }}">
                        <a href="{{ $favourite->url }}" class="text-xl font-bold text-blue-500 hover:underline">{{
                            $favourite->title ?? 'No title' }}</a>
                        <p class="text-gray-600">{{ $favourite->description ?? 'No description' }}</p>
                        <p class="text-gray-800">By {{ $favourite->author ?? 'Unknown author' }}</p>
                    </div>
                    @endforeach
                    @else
                    <p>No news found</p>
                    @endif
                </div> --}}


            </div>
        </div>
    </div>



</x-app-layout>
