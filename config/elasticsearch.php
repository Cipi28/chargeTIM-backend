<?php

if (env('FOUNDELASTICSEARCH_URL', false)) {
    /*
     * see: https://www.bountysource.com/issues/10349649-just-doesn-t-work-no-matter-what-i-try
     * When provided https:// without a port, elasticsearch assumes 9200 => SSL fail
     * To fix we add :443 port
     */
    $url_parts = parse_url(env('FOUNDELASTICSEARCH_URL'));
    if (isset($url_parts['scheme']) && strtolower($url_parts['scheme']) === 'https') {
        $url_parts['port'] = isset($url_parts['port']) ? $url_parts['port'] : 443;
    }
    $host = http_build_url($url_parts);
} else {
    // default
    $host = 'localhost:9200';
}

// trim trailing slashes /
$host = rtrim($host, '/');

return [
    /*
    |--------------------------------------------------------------------------
    | Elasticsearch host urls.
    |--------------------------------------------------------------------------
    |
    | Set the hosts to use for connecting to elasticsearch.
    |
    */
    'hosts' => [$host],
    'basicAuthentication' => [$url_parts['user'], $url_parts['pass']]
];
