<x-app-layout>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Search bar --}}
            <form class="flex items-center justify-center mt-4 mb-8 w-full" action="/dashboard">
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
                        <a href="/dashboard?category={{$categoryAll}}" class="m-2 text-2xl text-black hover:underline">{{ strtoupper($categoryAll) }}</a>
                    @endforeach
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">



                {{-- Top headlines --}}
                <div class="bg-gray-100 rounded-lg shadow-md p-6">
                    <h2 class="text-2xl font-bold mb-4">Top Headlines</h2>
                    @if (count($newsTop) > 0)
                    @foreach ($newsTop as $newTop)
                    <div class="mb-6">
                        <img class="h-30 w-30" src="{{ $newTop->urlToImage }}">
                        <a href="{{ $newTop->url }}" class="text-xl font-bold text-blue-500 hover:underline">{{
                            $newTop->title ?? 'No title' }}</a>
                        <p class="text-gray-600">{{ $newTop->description ?? 'No description' }}</p>
                        <p class="text-gray-700">By {{ $newTop->author ?? 'Unknown author' }}</p>
                        <form method="POST" action="/dashboard/favourite/create">
                            @csrf
                            <input type="hidden" id="title" name="title" value="{{ $newTop->title }}">
                            <input type="hidden" id="url" name="url" value="{{ $newTop->url }}">
                            <input type="hidden" id="author" name="author" value="{{ $newTop->author }}">
                            <input type="hidden" id="description" name="description" value="{{ $newTop->description }}">
                            <input type="hidden" id="image" name="image" value="{{ $newTop->urlToImage }}">
                            <button class="flex items-center justify-center mt-4 px-4 py-2 text-gray-600 bg-gray-200 rounded-lg hover:bg-gray-400">
                                <i class="fa fa-heart text-red-500"> &#160</i> Add to favourites
                              </button>
                        </form>
                        @if(array_search($newTop->url, $array))
                        <p>FAVORIT</p>
                        @endif
                    </div>
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
                                    }
                                @endphp
                                @if ($startPage > 1)
                                    @if($q)
                                        <a href="{{ $url }}?page=1&q={{$q}}" class="mx-1 px-2 py-1 rounded-lg bg-gray-300 hover:bg-gray-400 text-sm font-medium">1</a>
                                    @elseif($category)
                                        <a href="{{ $url }}?page=1&category={{$category}}" class="mx-1 px-2 py-1 rounded-lg bg-gray-300 hover:bg-gray-400 text-sm font-medium">1</a>
                                    @else
                                        <a href="{{ $url }}?page=1" class="mx-1 px-2 py-1 rounded-lg bg-gray-300 hover:bg-gray-400 text-sm font-medium">1</a>
                                    @endif
                                    @if ($startPage > 2)
                                        <span class="mx-1 px-2 py-1 rounded-lg bg-gray-300 text-sm font-medium">...</span>
                                    @endif
                                @endif
                                @if($q)
                                    @for ($x = $startPage; $x <= $endPage; $x++)
                                        <a href="{{ $url }}?page={{ $x }}&q={{$q}}" class="mx-1 px-2 py-1 rounded-lg bg-gray-300 hover:bg-gray-400 text-sm font-medium @if ($x == $currentPage) font-bold @endif">{{ $x }}</a>
                                    @endfor
                                @elseif($category)
                                    @for ($x = $startPage; $x <= $endPage; $x++)
                                        <a href="{{ $url }}?page={{ $x }}&category={{$category}}" class="mx-1 px-2 py-1 rounded-lg bg-gray-300 hover:bg-gray-400 text-sm font-medium @if ($x == $currentPage) font-bold @endif">{{ $x }}</a>
                                    @endfor
                                @else
                                    @for ($x = $startPage; $x <= $endPage; $x++)
                                         <a href="{{ $url }}?page={{ $x }}" class="mx-1 px-2 py-1 rounded-lg bg-gray-300 hover:bg-gray-400 text-sm font-medium @if ($x == $currentPage) font-bold @endif">{{ $x }}</a>
                                    @endfor
                                @endif
                                @if ($endPage < $numberOfPages)
                                    @if ($endPage < $numberOfPages - 1)
                                        <span class="mx-1 px-2 py-1 rounded-lg bg-gray-300 text-sm font-medium">...</span>
                                    @endif
                                    @if($q)
                                        <a href="{{ $url }}?page={{ $numberOfPages }}&q={{$q}}" class="mx-1 px-2 py-1 rounded-lg bg-gray-300 hover:bg-gray-400 text-sm font-medium">{{ $numberOfPages }}</a>
                                    @elseif($category)
                                        <a href="{{ $url }}?page={{ $numberOfPages }}&category={{$category}}" class="mx-1 px-2 py-1 rounded-lg bg-gray-300 hover:bg-gray-400 text-sm font-medium">{{ $numberOfPages }}</a>
                                    @else
                                        <a href="{{ $url }}?page={{ $numberOfPages }}" class="mx-1 px-2 py-1 rounded-lg bg-gray-300 hover:bg-gray-400 text-sm font-medium">{{ $numberOfPages }}</a>
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
                <div class="bg-gray-100 rounded-lg shadow-md p-6">
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
                </div>


            </div>
        </div>
    </div>



</x-app-layout>
