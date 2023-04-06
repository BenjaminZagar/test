<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use jcobhams\NewsApi\NewsApi;

class ExampleTest extends TestCase
{
    /**
     * Test that homepage is avaliable for any user.
     */

    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    /**
     * Test for NewsAPI PHP client getTopHeadlines function.
     */

    public function test_the_Newsapi_returns_successful_response_for_getTopHeadlines(): void 
    {

        //preparation / prepare
        $your_api_key='5d3f7a63232944ecb667668cd827ae18'; 
        $newsapi = new NewsApi($your_api_key);

        //action / perform
        $response=$newsapi->getTopHeadlines(null, null, 'us', null, null, null);

        //assertion / predict
        $this->assertEquals('ok', $response->status);
    }

    /**
     * Test for NewsAPI PHP client getEverything function.
     */

    public function test_the_Newsapi_returns_successful_response_for_getEverything(): void 
    {

        //preparation / prepare
        $your_api_key='5d3f7a63232944ecb667668cd827ae18'; 
        $newsapi = new NewsApi($your_api_key);

        //action / perform
        $response=$newsapi->getEverything('bitcoin', null, null, null, null, null, null, null,  null , null);

        //assertion / predict
        $this->assertEquals('ok', $response->status);
    }

    /**
     * Test for getTopHeadlines news shown on homepage.
     */

    public function test_the_news_are_shown_on_homepage_for_getTopHeadlines(): void 
    {

        //preparation / prepare
        $your_api_key='5d3f7a63232944ecb667668cd827ae18'; 
        $newsapi = new NewsApi($your_api_key);

        //action / perform
        $response= $this->get('/');
        $news=$newsapi->getTopHeadlines(null, null, 'us', null, null, null);

        //assertion / predict
        $response->assertSee($news->articles[0]->title);
    }

    /**
     * Test for getEverything news shown on homepage.
     */

    public function test_the_news_are_shown_on_homepage_for_getEverything(): void 
    {

        //preparation / prepare
        $your_api_key='5d3f7a63232944ecb667668cd827ae18'; 
        $newsapi = new NewsApi($your_api_key);

        //action / perform
        $response= $this->get('/');
        $news=$newsapi->getEverything('bitcoin', null, null, null, null, null, null, null,  5 , null);


        //assertion / predict
        $response->assertSee($news->articles[0]->title);
    }

     /**
     * Test for getTopHeadlines pagination is working.
     */

    public function test_the_pagination_of_getTopHeadlines_is_working(): void 
    {

        //preparation / prepare
        $your_api_key='5d3f7a63232944ecb667668cd827ae18'; 
        $newsapi = new NewsApi($your_api_key);

        //action / perform
        $response= $this->get('/?page=10');
        $news=$newsapi->getTopHeadlines(null, null, 'us', null, 2, 10);

        //assertion / predict
        $response->assertSee($news->articles[0]->title);
    }
}
