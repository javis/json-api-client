<?php
use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Exception\RequestException;
use Javis\JsonApiClient\JsonApiClient;


class JsonApiClientTest extends TestCase
{

  /**
  * Just check if the YourClass has no syntax error
  *
  * This is just a simple check to make sure your library has no syntax error. This helps you troubleshoot
  * any typo before you even use this library in a real project.
  *
  */
  public function testIsThereAnySyntaxError()
  {
      // Create a mock and queue two responses.
      $mock = new MockHandler();
      $client = new Client(['handler' => $mock]);

      $var = new JsonApiClient("http://url.com",[],$client);
      $this->assertTrue(is_object($var));
  }

  public function testParsesResponse()
  {
      // create mock response
      $mock = new MockHandler();
      $client = new Client(['handler' => $mock]);

      $mock->append(new Response(200, [], file_get_contents(__DIR__ . '/fixtures/articles.json')));

      // create an API client with the mock response
      $api_client = new JsonApiClient("http://example.com", [], $client);

      // do the request from the API and parses the response
      $response = $api_client->endpoint('articles')->get();

      $this->assertEquals(200 , $response->status);

  }

}
