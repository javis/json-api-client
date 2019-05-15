# JsonApi Client library for PHP

[![Build Status](https://travis-ci.org/javis/json-api-client.svg?branch=master)](https://travis-ci.org/javis/json-api-client)
[![Latest Stable Version](https://poser.pugx.org/javis/json-api-client/v/stable.svg)](https://packagist.org/packages/javis/json-api-client) [![Total Downloads](https://poser.pugx.org/javis/json-api-client/downloads.svg)](https://packagist.org/packages/javis/json-api-client)
[![Latest Unstable Version](https://poser.pugx.org/javis/json-api-client/v/unstable.svg)](https://packagist.org/packages/javis/json-api-client) [![License](https://poser.pugx.org/javis/json-api-client/license.svg)](https://packagist.org/packages/javis/json-api-client)

Client for easy access of data from [{json:api}](http://jsonapi.org/) API.
Making requests to API by using PHP HTTP Clients like Guzzle or CURL requires to much code.
This package tries to simplify this process, by allowing to get data from API as simply as:

```php
$client = new Client('http://my.api.com');
$response = $client->endpoint('users')->get();
return $response->data;
```


## Requirements

* PHP >= 5.5.9

## Installation

    composer require javis/json-api-client

## Usage
### Configuring the client

### Making requests
* get($endpoint)
```php
$client->endpoint('users')->get(); //get users
```
* post($endpoint)
```php
$client->endpoint('users')->withJsonData([])->post();//store user
```
* patch($endpoint)
```php
$client->endpoint('users')->withJsonData([])->patch();//do patch request
```
#### Request options
* `$client->endpoint('users')->include(['posts'])->get()` - adds query param `include=posts` to request URL. See http://jsonapi.org/format/#fetching-includes
* `$client->endpoint('users')->fields(['user'=> ['id','name']])->get()` - adds query param `fields[users]=id,name`. See http://jsonapi.org/format/#fetching-sparse-fieldsets
* `$client->endpoint('users')->filter(['users'=>['id'=>['eq'=>1]]])->get()` - adds query param `filter[users][id][eq]=1`. {json:api} is agnostic about filtering, so you can choose your filtering strategy and pass what ever array you want. See http://jsonapi.org/format/#fetching-filtering.
* `$client->endpoint('users')->withQuery(['field'=>1])->get()` - adds query param `field=1=1`. In theory adding filter, includes, fields and pagination fields should be sufficient.
* `Client::limit($limit, $offset)->get('users')` - add result constraints to query param `page[limit]=x&page[offet]=y`. See http://jsonapi.org/format/#fetching-pagination
* `$client->endpoint('users')->withFormData(['name'=>'John'])->post()` - define post form data. Form data can contain files i.e `$client->endpoint('photos')->withFormData(['image'=> $request->file('image')])->post()`.
* `$client->endpoint('users')->withJsonData(['name'=>'John'])->post()` - define post JSON data.

### Handling response
Requests will return `Response` object. It will contain public variables:
* `$resopnse->data` - contains response data.
* `$resopnse->meta` - contains meta data of a response.
* `$resopnse->errors` - contains errors data of a response.
* `$resopnse->status` - holds HTTP status code of request.
