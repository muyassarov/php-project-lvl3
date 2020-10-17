<?php

namespace Tests\Fixtures;

class DomainCheckFixture
{
    public function initFixtures()
    {
        $keywords    = 'key,key1,key2,key3';
        $description = 'website description';
        $h1          = 'Hello, world!';
        return [
            'statusCode'  => 200,
            'keywords'    => $keywords,
            'description' => $description,
            'h1'          => $h1,
            'htmlBody'    => <<<EOF
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="keywords" content="{$keywords}">
    <meta name="description" content="{$description}">
    <title>Title</title>
  </head>
  <body>
    <h1>{$h1}</h1>
  </body>
</html>
EOF
        ];
    }
}
