# Laravel URI package

[![MIT Licensed](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
![PHP from Packagist](https://img.shields.io/packagist/php-v/nsaliu/laravel-uri?label=php&style=flat-square)
![GitHub repo size](https://img.shields.io/github/repo-size/nsaliu/laravel-uri?style=flat-square)

A simple and useful URI package for Laravel framework.

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

### getScheme

Return the scheme part of the URI.

```
$uri = new Uri();
$uri->createFromString('https://test.test');
$uri->getScheme();
```

Return the scheme part of URI: ```https```

### getUsername

Return the user part from the URI.

```
$uri = new Uri();
$uri->createFromString('https://fakeuser:fakepass@test.test');
$uri->getUsername();
```

If a user is set returns ```fakeuser```, otherwise return an empty string.

### getPassword

Return the pass part from the URI.

```
$uri = new Uri();
$uri->createFromString('https://fakeuser:fakepass@test.test');
$uri->getPassword();
```

If a password is set return ```fakepass```, otherwise return an empty string.

### getAuthority

Return the authority part of the URI.

```
$uri = new Uri();
$uri->createFromString('https://fakeuser:fakepass@test.test');
$uri->getAuthority();
```

If no authority information is present, this method return an ```empty string```.

If authority is set return it in form of: ```jhon:doe@test.com```. 

If the port is a standard port for scheme, the port is not included, otherwise yes. 

Example:

```
// Create a URI with 'https' scheme and '80' as port (not default for https) 
$uri = new Uri();
$uri->createFromString('https://fakeuser:fakepass@test.test');
$uri->getAuthority();
```

Results in: ```jhon:doe@test.com:80```

### getAuthorityWithPort

Return the authority part of the URI with the port too, even if it's the default.

```
$uri = new Uri();
$uri->createFromString('https://fakeuser:fakepass@test.test:443');
$uri->getAuthorityWithPort();
```

Return: ```jhon:doe@test.com:443```

### getUserInfo

Return the user part of the URI.

```
$uri = new Uri();
$uri->createFromString('https://fakeuser:fakepass@test.test:443');
$uri->getUserInfo();
```

Return:
- if no user information is present return an empty string.
- if a user is present in the URI returns that value: ```fakeuser```
- if a password is present it returns the password too separated by ':' from the username: ```fakeuser:fakepass```

### getHost

Return the host part of the URI.

```
$uri = new Uri();
$uri->createFromString('https://test.test:443');
$uri->getHost();
```

Results in: ```test.test```

### getPort

Return the port part of the URI.

```
$uri = new Uri();
$uri->createFromString('https://fakeuser:fakepass@test.test:443');
$uri->getPort();
```
Return:
- if the port is present return the port as an integer: ```443```. 
- if the port is not present, return ```null```.

### isDefaultPort

Get whether the port value of the URI is the default for given scheme.

```
$uri = new Uri();
$uri->createFromString('https://fakeuser:fakepass@test.test:80');
$uri->isDefaultPort();

// return false because 80 isn't the default port for https scheme 
```

Return:
- ```true``` if the port is default for this scheme.
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
- if the query part of the URI is present: ```key1=value1&key2=value2```.
- if the query part of the URI is not present return an ```empty string```.

### getQueryValue

Get the query value given a query key.

```
$uri = new Uri();
$uri->createFromString('https://test.test/path1/path2/page.html?key1=value1&key2=value2');
$uri->getQueryValue('key1');
```

Return:
- if the key exists in query part return: ```value1```.
- if the key not exists in query part return an ```empty string```.

### getQueryAsArray

Get the query part as an array.

```
$uri = new Uri();
$uri->createFromString('https://test.test/path1/path2/page.html?key1=value1&key2=value2');
$uri->getQueryAsArray();
```

Return:
- if the query part exists return:
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
$uri->createFromString('https://test.test/path1/path2/page.html?key1=value1&key2=value2#fragment-1');
$uri->getFragment();
```

Return:
- if the path and query exists return ```/path1/path2/page.html?key1=value1&key2=value2```.
- if the path exists and query not exists return ```/path1/path2/page.html```.
- if the path not exists and query too return an ```empty string```.