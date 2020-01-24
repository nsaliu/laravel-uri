# Laravel URI package

[![MIT Licensed](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)

A simple and useful URI package for Laravel framework. 

This package provides an object representation of a uniform resource identifier (URI), an easy access and manipulation to the parts of the URI.

## Installation

This package requires PHP 7.2 and Laravel 5.8 or higher.

Install with composer: 

``` composer require nsaliu/laravel-uri ```

## Methods

### createFromString

Creates a new instance with the specified URI. 

```
$uri = new Uri();
$uri->createFromString('https://test.test');
```

### toString

Return the string representation of the URI instance. 

```
$uri = new Uri();
$uri->createFromString('https://test.test');
$uri->toString();
```

### hostIsReachable

Check with a GET call if the host returns an HTTP status code equals to 200. 

```
$uri = new Uri();
$uri->createFromString('https://test.test');
$uri->hostIsReachable();
```

### equals

Check if a string representation of the URI equals to the URI instance. 

```
$uri = new Uri();
$uri->createFromString('https://test.test');
$uri->equals('https://test.test');
```

### getComponents

Gets all the URIs components if presents.

```
$uri = new Uri();
$uri->createFromString('https://fakeuser:fakepass@test.test:443/path1/path2/page.html?key1=value1&key2=value2#fragment');
$uri->getComponents();
```

Return:
```
[
  'scheme' => 'https'
  'host' => 'test.test'
  'port' => 443
  'user' => 'fakeuser'
  'pass' => 'fakepass'
  'path' => '/path1/path2/page.html'
  'query' => 'key1=value1&key2=value2'
  'fragment' => 'fragment'
]
```

## Getters

### getScheme

Get the scheme part of the URI.

```
$uri = new Uri();
$uri->createFromString('https://test.test');
$uri->getScheme();
```

If a scheme is present return ```https``` otherwise an ```empty string```.

### getUsername

Get the user part of the URI.

```
$uri = new Uri();
$uri->createFromString('https://fakeuser:fakepass@test.test');
$uri->getUsername();
```

If a user is present returns ```fakeuser```, otherwise return an ```empty string```.

### getPassword

Get the pass part of the URI.

```
$uri = new Uri();
$uri->createFromString('https://fakeuser:fakepass@test.test');
$uri->getPassword();
```

If a password is set return ```fakepass```, otherwise return an ```empty string```.

### getAuthority

Get the authority part of the URI.

```
$uri = new Uri();
$uri->createFromString('https://fakeuser:fakepass@test.test');
$uri->getAuthority();
```
Return:
- if no authority information is present, this method return an ```empty string```.
- if authority is set return it in form of: ```jhon:doe@test.com```. 
- if the port is a standard port for scheme, the port is not included, otherwise yes. 

Example:

```
// Create a URI with 'https' scheme and '80' as port (not default for https) 
$uri = new Uri();
$uri->createFromString('https://fakeuser:fakepass@test.test');
$uri->getAuthority();
```

Return ```jhon:doe@test.com:80```

### getAuthorityWithPort

Get the authority part of the URI with the port too, even if it's the default for the scheme.

```
$uri = new Uri();
$uri->createFromString('https://fakeuser:fakepass@test.test:443');
$uri->getAuthorityWithPort();
```

Return ```jhon:doe@test.com:443```

### getUserInfo

Get the user part of the URI.

```
$uri = new Uri();
$uri->createFromString('https://fakeuser:fakepass@test.test:443');
$uri->getUserInfo();
```

Return:
- if no user information is present return an ```empty string```.
- if a user is present in the URI return ```fakeuser```.
- if a password is present it returns the password too separated by ':' from the username ```fakeuser:fakepass```

### getHost

Get the host part of the URI.

```
$uri = new Uri();
$uri->createFromString('https://test.test');
$uri->getHost();
```

Return ```test.test```

### getPort

Get the port part of the URI.

```
$uri = new Uri();
$uri->createFromString('https://test.test:443');
$uri->getPort();
```
Return:
- if the port is present return the port as an integer: ```443```. 
- if the port is not present, return ```null```.

### isDefaultPort

Get whether the port value of the URI is the default for the scheme.

```
$uri = new Uri();
$uri->createFromString('https://test.test:80');
$uri->isDefaultPort();

// return false because 80 isn't the default port for https scheme 
```

Return:
- ```true``` if the port is default for the scheme.
- ```false``` if the port isn't the default.

### getPath

Get the path value of the URI.

```
$uri = new Uri();
$uri->createFromString('https://test.test/path1/path2/page.html');
$uri->getPath();
```

Return:
- if a path is present return ```/path1/path2/page.html```.
- if the path is not present return an ```empty string```.

### getPathAsArray

Get the path value of the URI as an array.

```
$uri = new Uri();
$uri->createFromString('https://test.test/path1/path2/page.html');
$uri->getPathAsArray();
```

Return:
- if the path is present return the path portion of the URI as an array:
```
[
    'path1',
    'path2',
    'page.html',    
]
```
- if path is not present return an ```empty string```.

### getQuery

Get the query part of the URI.

```
$uri = new Uri();
$uri->createFromString('https://test.test/path1/path2/page.html?key1=value1&key2=value2');
$uri->getQuery();
```

Return:
- if the query part of the URI is present return ```key1=value1&key2=value2```.
- if the query part of the URI is not present return an ```empty string```.

### getQueryValue

Get the value of a given query key. 

```
$uri = new Uri();
$uri->createFromString('https://test.test/path1/path2/page.html?key1=value1&key2=value2');
$uri->getQueryValue('key1');
```

Return:
- if the key exists in query return: ```value1```.
- if the key not exists in query part return an ```empty string```.

### getQueryAsArray

Get the query part of the URI as an array.

```
$uri = new Uri();
$uri->createFromString('https://test.test/path1/path2/page.html?key1=value1&key2=value2');
$uri->getQueryAsArray();
```

Return:
- if the query part is present return:
```
[
    'key1' => 'value1',
    'key2' => 'value2',
]
```
- if the query part not exists return an ```empty array```.

### getPathAndQuery

Get the path and query part of the URI.

```
$uri = new Uri();
$uri->createFromString('https://test.test/path1/path2/page.html?key1=value1&key2=value2');
$uri->getPathAndQuery();
```

Return:
- if the path and query exists return ```/path1/path2/page.html?key1=value1&key2=value2```.
- if the path exists and query not exists return ```/path1/path2/page.html```.
- if the path not exists and query too return an ```empty string```.

### getFragment

Get the fragment part of the URI.

```
$uri = new Uri();
$uri->createFromString('https://test.test/page.html?key1=value1&key2=value2#fragment-1');
$uri->getFragment();
```

Return:
- if the fragment is present return ```fragment-1```.
- if the fragment is not present return an ```empty string```.

## Setters

### setScheme

Set the scheme part of the URI.

```
$uri = new Uri();
$uri->setScheme('http');
```
### setUsername

Set the user part of the URI.

```
$uri = new Uri();
$uri->setUsername('username');
```
### setPassword

Set the password part of the URI.

```
$uri = new Uri();
$uri->setPassword('password');
```

### setUserInfo

Set the user and password part of the URI.

```
$uri = new Uri();
$uri->setUserInfo('username'); // without password
$uri->setUserInfo('username', 'password'); // or with password
```
### setHost

Set the host part of the URI.

```
$uri = new Uri();
$uri->setHost('test.test');
```
### setPort

Set the port part of the URI.

```
$uri = new Uri();
$uri->setPort(80);
```

### setPath

Set the path part of the URI.

```
$uri = new Uri();
$uri->setPath('path1/path2/page.html');
```
### setQuery

Set the query part of the URI.

```
$uri = new Uri();
$uri->setQuery('key1&value1&key2=value2');
```

### setQueryArray

Set the query part of the URI with an array.

```
$uri = new Uri();
$uri->setQueryArray(['key1' => 'value1', 'key2' => 'value2']);
```

### addQuery

Add a query key and value to the URI.

```
$uri = new Uri();
$uri->addQuery('key3', 'value3');
```

### changeQuery

Change a query part of the URI.

```
$uri = new Uri();
$uri->changeQuery('key1', 'new-value');
```

### setFragment

Set the fragment part of the URI.

```
$uri = new Uri();
$uri->setFragment('fragment-2');
```
