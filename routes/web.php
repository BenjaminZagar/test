<?php

use Faker\Guesser\Name;
use PharIo\Manifest\Author;
use Illuminate\Http\Request;
use jcobhams\NewsApi\NewsApi;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/Top', function () {

    /* SET THE API KEY: */
        $your_api_key='5d3f7a63232944ecb667668cd827ae18';

    /* MAKE NEW "NewsApi" OBJECT THAT HANDLES REQUESTS:*/
        $newsapi = new NewsApi($your_api_key);

    /* ALL PARAMETERS FOR GET TopHeadlines FUNCTION:
        * $q : Keywords or a phrase to search for.

        * $sources: A comma-seperated string of identifiers for the news sources or blogs you want headlines from. 
            Use the getSources() method to locate these programmatically or look at the sources index. 
            Note: you can't mix this param with the country or category params.
            
        * $country: The 2-letter ISO 3166-1 code of the country you want to get headlines for. 
            Use the getCountries() method to locate these programmatically. 
            Note: you can't mix this param with the sources param.
            
        * $category: The category you want to get headlines for. Use the getCategories() method to locate these programmatically. 
             Note: you can't mix this param with the sources param.

        * $page_size: The number of results to return per page (request). 20 is the default, 100 is the maximum.

        * $page: Use this to page through the results if the total results found is greater than the page size. */

        $sources=null;
        $q=null;
        $country='us';
        $category=null;
        $page_size=10;
        $page=null;
        $language=null;
    
    /* TO SEE WHAT PARAMETERS ARE AVALIABLE: GET ALL AVALIABLE PARAMETERS: */
        /* Returns an array of allowed categories */
        $categories_All=$newsapi->getCategories();

        /* Returns an array of allowed countries */
        $countries_All=$newsapi->getCountries();

        /* Returns JSON object is successful or throws excpetions if invalid data or unsuccessful request. */
        /* $sources_All=$newsapi->getSources($category, $language, $country); */

        /* Returns an array of allowed languages */
        $languages_All=$newsapi->getLanguages();

    /*TO GET DATA:
        /* Returns JSON object is successful or throws excpetions if invalid data or unsuccessful request: */
        $news=$newsapi->getTopHeadlines($q, $sources, $country, $category, $page_size, $page);

    /* JSON STRUCTURE:
        *source: id, name
        *author
        *title
        *description
        *url
        *urlToImage
        *publishedAt
        *content */

    /* dd($countries_All); */
    return view('data_test', [
        'news'=>$news->articles
    ]);
});

Route::get('/everything', function () {

    /* SET THE API KEY: */
        $your_api_key='5d3f7a63232944ecb667668cd827ae18';

    /* MAKE NEW "NewsApi" OBJECT THAT HANDLES REQUESTS:*/
        $newsapi = new NewsApi($your_api_key);

    /* ALL PARAMETERS FOR GET Everything FUNCTION:
    
        * $domains: A comma-seperated string of domains (eg bbc.co.uk, techcrunch.com, engadget.com) to restrict the search to.

        * $exclude_domains: A comma-seperated string of domains (eg bbc.co.uk, techcrunch.com, engadget.com) to remove from the results.

        * $from: A date and optional time for the oldest article allowed. 
            This should be in ISO 8601 format (e.g. 2018-11-16 or 2018-11-16T16:19:03) 
            Default: the oldest according to your plan.

        * $to: A date and optional time for the newest article allowed. 
            This should be in ISO 8601 format (e.g. 2018-11-16 or 2018-11-16T16:19:03) 
            Default: the newest according to your plan.

        * $language: The 2-letter ISO-639-1 code of the language you want to get headlines for. 
             Possible options: ar de en es fr he it nl no pt ru se ud zh . 
             Default: all languages returned. Use the getLanguages() method to locate these programmatically.

        * $sort_by: The order to sort the articles in. Use the getSortBy() method to locate these programmatically. */

        $sources=null;
        $q='bitcoin';
        $domains=null;
        $exclude_domains=null;
        $from=null;
        $to=null;
        $sort_by=null;
        $page_size=null;
        $page=null;
        $language=null;
        $country='us';
        $category='business';
    
    /* TO SEE WHAT PARAMETERS ARE AVALIABLE: GET ALL AVALIABLE PARAMETERS: */
        /* Returns an array of allowed categories: */
        $categories_All=$newsapi->getCategories();

        /* Returns JSON object is successful or throws excpetions if invalid data or unsuccessful request. */
        $sources_All=$newsapi->getSources($category, $language, $country);
    
        /* Returns an array of allowed languages */
        $languages_All=$newsapi->getLanguages();

        /* Returns an array of allowed sort_by */
        $sortBy_All=$newsapi->getSortBy();

    /*TO GET DATA:
        /* Returns JSON object is successful or throws excpetions if invalid data or unsuccessful request: */
        $news=$newsapi->getEverything($q, $sources, $domains, $exclude_domains, $from, $to, $language, $sort_by, $page_size, $page);
  
    dd($news);
    return view('welcome');
});

Route::get('/news', function (Request $request) {

    extract($request->all());
    $perPage=2;
    if(extract($request->all())){    
        $page = (int)$page;
    }
    else{
        $page=null;
    }
    $your_api_key='5d3f7a63232944ecb667668cd827ae18';
    $newsapi = new NewsApi($your_api_key);
    $newsEverything=$newsapi->getEverything('bitcoin', null, null, null, null, null, null, null, 5, null);
    $newsTop=$newsapi->getTopHeadlines(null, null, 'us', null, $perPage, $page);
    $numberOfPages=$newsTop->totalResults/$perPage;
    $numberOfPages=ceil($numberOfPages);
    return view('data_test', [
        'newsTop'=>$newsTop->articles,
        'numberOfPages'=>$numberOfPages,
        'newsEverything'=>$newsEverything->articles
    ]);
});

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function (Request $request) {
    extract($request->all());
    $page=null;
    $q=null;
    $perPage=2;
    $category=null;
    
    if(extract($request->all())){    
        $page = (int)$page;
        $q = $q;
        $category = $category;
    }
    else{
        $page=null;
        $q=null;
        $category=null;
    }
    $your_api_key='5d3f7a63232944ecb667668cd827ae18';
    $newsapi = new NewsApi($your_api_key);
    $categoriesAll=$newsapi->getCategories();
    /* $newsEverything=$newsapi->getEverything('bitcoin', null, null, null, null, null, null, null, 5, null); */
    $newsTop=$newsapi->getTopHeadlines($q, null, 'us', $category, $perPage, $page);
    $numberOfPages=$newsTop->totalResults/$perPage;
    $numberOfPages=ceil($numberOfPages);
    return view('dashboard', [
        'newsTop'=>$newsTop->articles,
        'q'=>$q,
        'category'=>$category,
        'categoriesAll'=>$categoriesAll,
        'numberOfPages'=>$numberOfPages
        /* 'newsEverything'=>$newsEverything->articles */
    ]);
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
