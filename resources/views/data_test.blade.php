
<h1>------------------------------------------------------------------------</h1>
<h1>TOP</h1>

@foreach($newsTop as $newTop)
    {{-- JSON STRUCTURE:
        *source: id, name
        *author
        *title
        *description
        *url
        *urlToImage
        *publishedAt
        *content  --}}
    @php
        $authors=explode(', ', $newTop->author);
    @endphp

    @if($newTop->title)
        <a href="{{$newTop->url}}">{{$newTop->title}}<a>
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
            <p>Author: {{$newTop->author}}<p>
        @else
            <p>Authors: {{$newTop->author}}<p>
        @endif 
    @else
        <p>No author</p>
    @endif
    <p>*</p>
    <p>*</p>
@endforeach
@for($x=1; $x<=$numberOfPages; $x++)
   <a href="/?page={{$x}}"> {{$x}}</a>
@endfor


<h1>------------------------------------------------------------------------</h1>
    <h1>EVERYTHING</h1>

@foreach($newsEverything as $newEverything)
    {{-- JSON STRUCTURE:
        *source: id, name
        *author
        *title
        *description
        *url
        *urlToImage
        *publishedAt
        *content  --}}

    @php
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
        <p>Author: {{$newTop->author}}<p>
    @else
        <p>Authors: {{$newTop->author}}<p>
    @endif 
    @else
        <p>No author</p>
    @endif
    <p>*</p>
    <p>*</p>
@endforeach