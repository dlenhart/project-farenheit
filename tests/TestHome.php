<?php
use PHPUnit\Framework\TestCase;
use GuzzleHttp;

/**
 * Class TestHome
 * @package APP\Tests
 */
 class TestHome extends TestCase
 {
     protected $client;
     public function setUp()
     {
       $this->client = new GuzzleHttp\Client([
          'base_uri' => 'http://localhost:8000'
      ]);
     }
     public function testHomeGet()
     {
       $response = $this->client->request('GET', '/');
       $this->assertEquals(200, $response->getStatusCode());
     }
 }
