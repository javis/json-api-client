<?php
use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Exception\RequestException;
use Javis\JsonApi\Client;


class ClientTest extends TestCase
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
      $client = new HttpClient(['handler' => $mock]);

      $var = new Client("http://url.com",[],$client);
      $this->assertTrue(is_object($var));
  }

  public function testEndpointValidation()
  {
      // Create a mock and queue two responses.
      $mock = new MockHandler();
      $client = new HttpClient(['handler' => $mock]);

      $api_client = new Client("http://url.com",[],$client);

      $this->setExpectedException(\Exception::class);

      $response = $api_client->endpoint('http://articles.com');
  }

  public function testResponseParsing()
  {
      // create mock response
      $mock = new MockHandler();
      $client = new HttpClient(['handler' => $mock]);

      $mock->append(new Response(200, [], file_get_contents(__DIR__ . '/fixtures/articles.json')));

      // create an API client with the mock response
      $api_client = new Client("http://example.com", [], $client);

      // do the request from the API and parses the response
      $response = $api_client->endpoint('articles')->get();

      // test basic structure
      $this->assertEquals(200 , $response->status);
      $this->assertTrue(is_object($response->data[0]));

      // test main resource properties
      $this->assertEquals('articles',$response->data[0]->type);
      $this->assertEquals(1,$response->data[0]->id);
      $this->assertEquals('JSON:API paints my bikeshed!',$response->data[0]->title);

      // test relationships
      $this->assertTrue(is_object($response->data[0]->author));
      $this->assertEquals('Dan',$response->data[0]->author->firstName);

      $this->assertTrue(is_array($response->data[0]->comments));
      $this->assertTrue(is_object($response->data[0]->comments[0]));
      $this->assertEquals('First!',$response->data[0]->comments[0]->body);


  }

}
