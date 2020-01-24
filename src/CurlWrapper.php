<?php

namespace Nsaliu\Uri;

use Nsaliu\Uri\Exceptions\CurlExtensionNotLoaded;

class CurlWrapper
{
    /**
     * @param string $uri
     *
     * @throws CurlExtensionNotLoaded
     *
     * @return int
     */
    public function getReturnCode(string $uri): int
    {
        if (!extension_loaded('curl')) {
            throw new CurlExtensionNotLoaded();
        }

        $ch = \curl_init($uri);

        \curl_setopt($ch, CURLOPT_NOBODY, true);
        \curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        \curl_exec($ch);

        $returnCode = \curl_getinfo($ch, CURLINFO_HTTP_CODE);
        \curl_close($ch);

        return (int) $returnCode;
    }
}
