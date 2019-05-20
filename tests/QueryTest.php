<?php
use PHPUnit\Framework\TestCase;
use Javis\JsonApi\Client;
use Javis\JsonApi\Query;


class QueryTest extends TestCase
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
      // Create a mock
      $client = Mockery::mock(Client::class);

      $var = new Query($client,'articles');
      $this->assertTrue(is_object($var));
  }

}
