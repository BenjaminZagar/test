<x-app-layout>
    {{-- <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Home') }}
        </h2>
    </x-slot> --}}

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-black dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <p>
                <form action="/dashboard">
                    <div class="relative border-2 border-gray-100 m-4 rounded-lg">
                        <div class="absolute top-4 left-3">
                            <i class="fa fa-search text-gray-400 z-20 hover:text-gray-500"></i>
                        </div>
                        <input type="text" name="q"
                            class="h-14  pl-10 pr-20 rounded-lg z-0 focus:shadow focus:outline-none text-black"
                            placeholder="Search Top headlines..." />

                            <button type="submit" class="h-10 w-20 text-white rounded-lg bg-red-500 hover:bg-red-600">
                                Search
                            </button>

                </form>
                </p>



                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h1>-------------------------------------------------------------------------------------------------</h1>

                        @foreach($categoriesAll as $categoryAll)

                        <u> <a href="/dashboard?category={{$categoryAll}}"> {{$categoryAll}} </a></u>
                        @endforeach

                        <h1>---------------------------------------------------------------------------------------------</h1>



                    <h1>TOP HEADLINES:</h1>
                    <p>*</p>
                    <p>*</p>



                    @if(count($newsTop)>0)
                    @foreach($newsTop as $newTop)
                    {{-- JSON STRUCTURE:
                    *source: id, name
                    *author
                    *title
                    *description
                    *url
                    *urlToImage
                    *publishedAt
                    *content --}}
                    @php
                    $authors=explode(', ', $newTop->author);
                    @endphp

                    @if($newTop->title)
                    <a href="{{$newTop->url}}">{{$newTop->title}}</a>
                            @else
                            <p>No title</p>
                            @endif
                            @if($newTop->description)
                            <p>{{$newTop->description}}<p>
                                    @else
                                <p>No description</p>
                                @endif
                                @if($newTop->author)
                                @if(count($authors)==1)
                                <p>Author: {{$newTop->author}}
                                <p>
                                    @else
                                <p>Authors: {{$newTop->author}}
                                <p>
                                    @endif
                                    @else
                                <p>No author</p>
                                @endif
                                <form method="POST" action="/dashboard/favourite/create">
                                @csrf
                                <input type="hidden" id="title" name="title" value="{{$newTop->title}}">
                                <input type="hidden" id="url" name="url" value="{{$newTop->url}}">
                                <input type="hidden" id="author" name="author" value="{{$newTop->author}}">
                                <input type="hidden" id="description" name="description" value="{{$newTop->description}}">
                                <input type="hidden" id="image" name="image" value="{{$newTop->urlToImage}}">
                                <button class="h-10 w-100 text-white rounded-lg bg-red-500 hover:bg-red-600">
                                    Add to favourites
                                </button>
                                </form>
                                <p>*</p>
                                <p>*</p>
                                @endforeach
                                @if($numberOfPages>1)
                                @if($q)
                                @for($x=1; $x<=$numberOfPages; $x++) <a href="/dashboard?page={{$x}}&q={{$q}}"> {{$x}} </a>
                                @endfor
                                @elseif($category)
                                @for($x=1; $x<=$numberOfPages; $x++) <a href="/dashboard?page={{$x}}&category={{$category}}"> {{$x}} </a>
                                @endfor
                                @else
                                @for($x=1; $x<=$numberOfPages; $x++) <a href="/dashboard?page={{$x}}"> {{$x}} </a>
                                @endfor
                                @endif





                    @endif
                    @else
                    <p>No news found</p>
                    @endif


            {{--  <h1>------------------------------------------------------------------------</h1>
                    <h1>EVERYTHING</h1>

                    @foreach($newsEverything as $newEverything) --}}
                    {{-- JSON STRUCTURE:
                    *source: id, name
                    *author
                    *title
                    *description
                    *url
                    *urlToImage
                    *publishedAt
                    *content --}}

                    {{-- @php
                    $authors=explode(', ', $newEverything->author);
                    @endphp

                    @if($newEverything->title)
                    <a href="{{$newEverything->url}}">{{$newEverything->title}}<a>
                            @else
                            <p>No title</p>
                            @endif
                            @if($newEverything->description)
                            <p>{{$newEverything->description}}<p>
                                    @else
                                <p>No description</p>
                                @endif
                                @if($newEverything->author)
                                @if(count($authors)==1)
                                <p>Author: {{$newEverything->author}}
                                <p>
                                    @else
                                <p>Authors: {{$newEverything->author}}
                                <p>
                                    @endif
                                    @else
                                <p>No author</p>
                                @endif
                                <p>*</p>
                                <p>*</p>
                                @endforeach  --}}
                </div>
            </div>
        </div>
    </div>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="text-white bg-black dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">

                <h1>FAVOURITES:</h1>
                    <p>*</p>
                    <p>*</p>
                @foreach ($favourites as $favourite)
                <img class="h-30 w-30" src="{{$favourite->imageUrl}}" alt="Italian Trulli">
                @if($favourite->title)
                    <a href="{{$favourite->url}}">{{$favourite->title}}</a>
                            @else
                            <p>No title</p>
                            @endif
                            @if($favourite->description)
                            <p>{{$favourite->description}}<p>
                                    @else
                                <p>No description</p>
                                @endif
                                @if($favourite->author)
                                @if(count($authors)==1)
                                <p>Author: {{$favourite->author}}
                                <p>
                                    @else
                                <p>Authors: {{$favourite->author}}
                                <p>
                                    @endif
                                    @else
                                <p>No author</p>
                                @endif
                                <p>*</p>
                                <p>*</p>

                @endforeach


            </div>
        </div>
    </div>
</x-app-layout>
