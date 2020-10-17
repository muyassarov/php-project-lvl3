<?php

namespace App\Services\Domain;

class DomainNormalizer
{
    public static function normalize(string $domain)
    {
        $domainComponents = parse_url($domain);
        if (!$domainComponents) {
            throw new \InvalidArgumentException("Malformed URL: {$domain}");
        }

        $scheme      = $domainComponents['scheme'] ?? 'https';
        $host        = $domainComponents['host'];
        $port        = $domainComponents['port'] ?? '';
        $urlPortPart = $port ? ":{$port}" : '';
        return mb_strtolower("{$scheme}://{$host}{$urlPortPart}");
    }
}
