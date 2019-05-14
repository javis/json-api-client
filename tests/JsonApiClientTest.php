<?php
use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Exception\RequestException;


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
    $mock = new MockHandler([
      new Response(200, ['X-Foo' => 'Bar'])
    ]);

    $handler = HandlerStack::create($mock);
    $client = new Client(['handler' => $handler]);

    $var = new Javis\JsonApiClient\JsonApiClient("http://url.com",[],$client);
    $this->assertTrue(is_object($var));
    unset($var);
  }

}
