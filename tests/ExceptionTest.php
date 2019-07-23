<?php
use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Exception\RequestException;
use Javis\JsonApi\Exceptions\ApiException;


class ExceptionTest extends TestCase
{


  public function testParseErrorMessages()
  {
      $error = new \stdClass();

      $error->title = "Title";
      $error->detail = "Details";

      $e = new ApiException( [
         $error,
      ]);

      $this->assertEquals("Title: Details",$e->getMessage());
  }

  public function testParseErrorWithoutMessages()
  {
      $error = new \stdClass();


      $e = new ApiException( [
         $error,
      ]);

      $this->assertEquals("API Server Responded with error",$e->getMessage());
  }



}
