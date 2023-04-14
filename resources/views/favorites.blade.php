<x-app-layout>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">


                {{-- Favorites --}}
                <div class="bg-gray-100 rounded-lg shadow-md p-6">
                    <h2 class="text-2xl font-bold mb-4">Favorites</h2>
                    @if (count($favourites) > 0)
                        @foreach ($favourites as $index => $favorite)
                            <div class="mb-6">
                                @if($favorite->imageUrl)
                                    <img width="1600" height="500" src="{{ $favorite->imageUrl }}">
                                @else
                                    {{-- <img width="100" height="200" src="{{asset('pictures/no_image_r.jpeg')}}"> --}}
                                @endif
                                <a href="{{ $favorite->url }}"
                                    class="text-xl font-bold text-blue-500 hover:underline">{{ $favorite->title ?? 'No title' }}</a>
                                <p class="text-gray-600">{{ $favorite->description ?? 'No description' }}</p>
                                <p class="text-gray-700">By {{ $favorite->author ?? 'Unknown author' }}</p>

                                <form method="POST" action="/dashboard/favorites/{{$favorite->id}}">
                                    @csrf
                                    @method('DELETE')
                                    <button
                                        class="flex items-center justify-center mt-4 px-4 py-2 text-gray-600 bg-gray-200 rounded-lg hover:bg-gray-400">
                                        <i class="fa fa-remove text-red-500"> &#160</i> Remove from favorites
                                    </button>
                                </form>

                                <div class="mt-5">
                                    <div class="mt-5">
                                        <form class="mb-4 flex flex-row" method="POST" action="/dashboard/comments/create">
                                            @csrf
                                            <div class="flex flex-col mr-2 w-full">
                                                <textarea name="comment_text" id="comment_text" rows="1" placeholder="Leave your comment here"
                                                          class="rounded-lg border border-gray-300 p-2 focus:outline-none focus:ring-2 focus:ring-blue-400"></textarea>
                                            </div>
                                            <input type="hidden" id="user_id" name="user_id" value={{ auth()->user()->id }}>
                                            <input type="hidden" id="url" name="url" value="{{ $favorite->url }}">
                                            <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">Submit</button>
                                        </form>
                                    </div>

                                    <!-- Comment section body -->
                                    <div class="mt-4 max-h-0 overflow-hidden transition-all duration-500 ease-in-out"
                                        id="comments-section-{{ $index }}">
                                        <!-- Comment 1 -->
                                        @php
                                            $commentCount = 0;
                                        @endphp
                                        @foreach($comments as $comment)
                                        @if ($comment->url == $favorite->url)
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
                                        <div class="bg-gray-100 rounded-lg shadow-md p-4 mb-4">
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
                                                <a href="{{ $url }}?page=1"
                                                    class="mx-1 px-2 py-1 rounded-lg bg-gray-300 hover:bg-gray-400 text-sm font-medium">1</a>
                                            @if ($startPage > 2)
                                                <span
                                                    class="mx-1 px-2 py-1 rounded-lg bg-gray-300 text-sm font-medium">...</span>
                                            @endif
                                        @endif
                                            @for ($x = $startPage; $x <= $endPage; $x++)
                                                <a href="{{ $url }}?page={{ $x }}"
                                                    class="mx-1 px-2 py-1 rounded-lg bg-gray-300 hover:bg-gray-400 text-sm font-medium @if ($x == $currentPage) font-bold @endif">
                                                    {{ $x }}</a>
                                            @endfor
                                        @if ($endPage < $numberOfPages)
                                            @if ($endPage < $numberOfPages - 1)
                                                <span
                                                    class="mx-1 px-2 py-1 rounded-lg bg-gray-300 text-sm font-medium">
                                                    ...</span>
                                            @endif
                                                <a href="{{ $url }}?page={{ $numberOfPages }}"
                                                    class="mx-1 px-2 py-1 rounded-lg bg-gray-300 hover:bg-gray-400 text-sm font-medium">{{ $numberOfPages }}</a>
                                        @endif
                                    </div>
                                @endif
                            </div>

                        @endif
                    @else
                        <p>No news found</p>
                    @endif
                </div>
            </div>
        </div>
    </div>



</x-app-layout>
