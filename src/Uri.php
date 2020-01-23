<?php

namespace Nsaliu\Uri;

use Nsaliu\Uri\Exceptions\HostIsEmptyException;
use Nsaliu\Uri\Exceptions\InvalidUriException;
use Nsaliu\Uri\Exceptions\PortOutOfRangeException;
use Nsaliu\Uri\Exceptions\QueryCannotContainFragmentException;
use Nsaliu\Uri\Exceptions\QueryKeyAlreadyExistsException;
use Nsaliu\Uri\Exceptions\QueryKeyMustHaveAtLeastOneCharException;

class Uri
{
    #region properties

    /** @var string */
    private $scheme = '';

    /** @var string */
    private $host = '';

    /** @var  int|null */
    private $port = null;

    /** @var string */
    private $username = '';

    /** @var string */
    private $password = '';

    /** @var string */
    private $path = '';

    /** @var array */
    private $queryArray = [];

    /** @var string */
    private $fragment = '';

    /**
     * Taken from: https://github.com/guzzle/psr7/blob/master/src/Uri.php
     * @var array
     */
    private const DEFAULT_PORTS = [
        'http' => 80,
        'https' => 443,
        'ftp' => 21,
        'gopher' => 70,
        'nntp' => 119,
        'news' => 119,
        'telnet' => 23,
        'tn3270' => 23,
        'imap' => 143,
        'pop' => 110,
        'ldap' => 389,
    ];

    #endregion properties

    #region getters and setters

    /**
     * @return string
     */
    public function getScheme(): string
    {
        return $this->scheme;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @return string
     */
    public function getAuthority(): string
    {
        return $this->getAuthorityValue();
    }

    /**
     * @return string
     */
    public function getAuthorityWithPort(): string
    {
        return $this->getAuthorityValue(true);
    }

    /**
     * @return string
     */
    public function getUserInfo(): string
    {
        if ($this->username === '') {
            return '';
        }

        $userInfo = '';

        if ($this->username !== '') {
            $userInfo .= $this->username;
        }

        if ($this->password !== '') {
            $userInfo .= ':' . $this->password;
        }

        return $userInfo;
    }

    /**
     * @return string
     */
    public function getHost(): string
    {
        return $this->host;
    }

    /**
     * @return int|null
     */
    public function getPort(): ?int
    {
        return $this->port;
    }

    /**
     * @return bool
     */
    public function isDefaultPort(): bool
    {
        return self::DEFAULT_PORTS[$this->scheme] === $this->port;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        $tmp = '';
        if (substr($this->path, 0, 1) !== '/') {
            $tmp .= '/';
        }

        return $tmp . $this->path;
    }

    /**
     * @return array
     */
    public function getPathAsArray(): array
    {
        if ($this->path === '') {
            return [];
        }

        $tmp = explode('/', $this->path);
        if ($tmp[0] === '') {
            unset($tmp[0]);
        }

        return array_values($tmp);
    }

    /**
     * @return string
     */
    public function getQuery(): string
    {
        if (count($this->queryArray) > 0) {
            return $this->buildQueryString($this->queryArray);
        }

        return '';
    }

    /**
     * @param string $key
     * @return string|null
     */
    public function getQueryValue(string $key): ?string
    {
        if (!array_key_exists($key, $this->queryArray)) {
            return null;
        }

        return $this->queryArray[$key];
    }

    /**
     * @return array
     */
    public function getQueryAsArray(): array
    {
        return $this->queryArray;
    }

    /**
     * @return string
     */
    public function getPathAndQuery(): string
    {
        if ($this->path === '') {
            return '';
        }

        $query = $this->getQuery();
        $query = $query === '' ? '' : '?' . $query;

        return $this->getPath() . $query;
    }

    /**
     * @return string
     */
    public function getFragment(): string
    {
        return $this->fragment;
    }

    /**
     * @param string $scheme
     * @return $this
     */
    public function setScheme(string $scheme): self
    {
        $this->scheme = $scheme;

        return $this;
    }

    /**
     * @param string $username
     * @return $this
     */
    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @param string $password
     * @return $this
     */
    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @param string $user
     * @param string|null $password
     * @return $this
     */
    public function setUserInfo(string $user, ?string $password = null): self
    {
        $this->username = $user;

        if ($this->password !== null) {
            $this->password = $password;
        }

        return $this;
    }

    /**
     * @param string $host
     * @return $this
     */
    public function setHost(string $host): self
    {
        $this->host = $host;

        return $this;
    }

    /**
     * @param int $port
     * @return $this
     * @throws PortOutOfRangeException
     */
    public function setPort(int $port): self
    {
        if ($port <= 0 || $port > 65535) {
            throw new PortOutOfRangeException($port);
        }

        $this->port = $port;

        return $this;
    }

    /**
     * @param string $path
     * @return $this
     */
    public function setPath(string $path): self
    {
        $this->path = $path;

        return $this;
    }

    /**
     * @param string $query
     * @return $this
     * @throws QueryCannotContainFragmentException
     */
    public function setQuery(string $query): self
    {
        if (strpos($query, '#') !== false) {
            throw new QueryCannotContainFragmentException();
        }

        $this->setQueryArrayWithString($query);

        return $this;
    }

    /**
     * @param array $query
     * @return $this
     * @throws QueryCannotContainFragmentException
     */
    public function setQueryArray(array $query): self
    {
        if (count($query) === 0) {
            return $this;
        }

        foreach ($query as $key => $value) {
            if (strpos($key, '#') !== false ||
                strpos($value, '#') !== false) {
                throw new QueryCannotContainFragmentException();
            }
        }

        $this->setQuery($this->buildQueryString($query));

        return $this;
    }

    /**
     * @param string $key
     * @param string $value
     * @return $this
     * @throws QueryCannotContainFragmentException
     * @throws QueryKeyMustHaveAtLeastOneCharException
     * @throws QueryKeyAlreadyExistsException
     */
    public function addQuery(string $key, string $value): self
    {
        if (strlen($key) === 0) {
            throw new QueryKeyMustHaveAtLeastOneCharException();
        }

        if (array_key_exists($key, $this->queryArray)) {
            throw new QueryKeyAlreadyExistsException($key);
        }

        $this->setQueryArray(
            array_merge($this->queryArray, [$key => $value])
        );

        return $this;
    }

    /**
     * @param string $key
     * @param string $value
     * @return $this
     * @throws QueryCannotContainFragmentException
     * @throws QueryKeyMustHaveAtLeastOneCharException
     */
    public function changeQuery(string $key, string $value): self
    {
        if (strlen($key) === 0) {
            throw new QueryKeyMustHaveAtLeastOneCharException();
        }

        $this->setQueryArray(array_merge($this->queryArray, [$key => $value]));

        return $this;
    }

    /**
     * @param string $fragment
     * @return $this
     */
    public function setFragment(string $fragment): self
    {
        $this->fragment = $fragment;

        return $this;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->toString();
    }

    #endregion getters and setters

    #region public methods

    /**
     * @param string $uri
     * @return void
     * @throws QueryCannotContainFragmentException
     * @throws InvalidUriException
     */
    public function createFromString(string $uri): void
    {
        $uriComponents = parse_url($uri);

        if ($uriComponents === false) {
            throw new InvalidUriException();
        }

        $this->scheme = $this->getUriValue('scheme', $uriComponents);
        $this->host = $this->getUriValue('host', $uriComponents);
        $this->username = $this->getUriValue('user', $uriComponents);
        $this->password = $this->getUriValue('pass', $uriComponents);
        $this->path = $this->getUriValue('path', $uriComponents);
        $this->fragment = $this->getUriValue('fragment', $uriComponents);

        $this->port = $this->getPortUriValue($uriComponents);
        $this->setQuery(
            $this->getUriValue('query', $uriComponents)
        );
    }

    /**
     * @return string
     */
    public function toString(): string
    {
        $uri = '';

        if ($this->scheme !== '') {
            $uri .= $this->scheme . ':';
        }

        if ($this->getAuthority() !== '') {
            $uri .= '//' . $this->getAuthority();
        }

        if ($this->getAuthority() === '' && $this->host !== '') {
            $uri .= '//' . $this->host;
        }

        if ($this->path !== '') {
            if (substr($this->path, 0, 1) !== '/') {
                $uri .= '/';
            }

            $uri .= $this->path;
        }

        if (count($this->queryArray) > 0) {
            $uri .= '?' . $this->buildQueryString($this->queryArray);
        }

        if ($this->fragment !== '') {
            $uri .= '#' . $this->fragment;
        }

        return $uri;
    }

    /**
     * @return bool
     * @throws Exceptions\CurlExtensionNotLoaded
     * @throws HostIsEmptyException
     */
    public function hostIsReachable(): bool
    {
        if ($this->host === '') {
            throw new HostIsEmptyException();
        }

        $curlWrapper = new CurlWrapper();
        return $curlWrapper->getReturnCode($this->toString());
    }

    /**
     * @param string $url
     * @return bool
     */
    public function equals(string $url): bool
    {
        return $this->toString() === strtolower(trim($url));
    }

    /**
     * @return array
     */
    public function getComponents(): array
    {
        return [
            'scheme' => $this->getScheme(),
            'host' => $this->getHost(),
            'port' => $this->getPort(),
            'user' => $this->getUsername(),
            'pass' => $this->getPassword(),
            'path' => $this->getPath(),
            'query' => $this->getQuery(),
            'fragment' => $this->getFragment(),
        ];

    }

    #endregion public methods

    #region private methods

    /**
     * @param string $key
     * @param array $array
     * @return string
     */
    private function getUriValue(string $key, array &$array): string
    {
        if (!array_key_exists($key, $array)) {
            return '';
        }

        return strtolower($array[$key]);
    }

    /**
     * @param array $uriComponents
     * @return int|null
     */
    private function getPortUriValue(array &$uriComponents): ?int
    {
        if (!array_key_exists('port', $uriComponents)) {
            return null;
        }

        if ($uriComponents['port'] === null) {
            return null;
        }

        if (!is_integer($uriComponents['port'])) {
            return null;
        }

        return $uriComponents['port'];
    }

    /**
     * @param bool $withPort
     * @return string
     */
    private function getAuthorityValue(bool $withPort = false): string
    {
        if ($this->username === '') {
            return '';
        }

        $authority = $this->username;

        $authority .= ':' . $this->password;

        $authority .= '@' . $this->host;

        if ($this->port !== null) {
            if ($withPort) {
                $authority .= ':' . $this->port;
            } else {
                if (!$this->isDefaultPort()) {
                    $authority .= ':' . $this->port;
                }
            }
        }

        return $authority;
    }

    /**
     * @param string $part
     * @return array|null
     */
    protected function splitQueryPart(string $part)
    {
        $parts = explode('=', $part, 2);

        if (count($parts) === 1) {
            $parts = null;
        }

        return $parts;
    }

    /**
     * @param string $query
     */
    private function setQueryArrayWithString(string $query): void
    {
        if (strlen($query) === 0) {
            return;
        }

        $temp = explode('&', $query);
        foreach ($temp as $index => $part) {
            list($key, $value) = $this->splitQueryPart($part);

            if ($value === null) {
                $this->queryArray[$key] = null;
                continue;
            }

            $this->queryArray[$key] = $value;
        }
    }

    /**
     * @param array $queryArray
     * @return string
     */
    private function buildQueryString(array $queryArray): string
    {
        return http_build_query($queryArray, null, '&');
    }

    #endregion private methods
}
