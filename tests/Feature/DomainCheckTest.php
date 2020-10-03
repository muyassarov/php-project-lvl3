<?php

namespace Tests\Feature;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class DomainCheckTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testStore()
    {
        // insert new domain
        $id = DB::table('domains')->insertGetId([
            'name'       => 'https://test.com',
            'created_at' => Carbon::now(),
        ]);

        $statusCode  = 200;
        $keywords    = 'key,key1,key2,key3';
        $description = 'website description';
        $h1          = 'Hello, world!';
        $htmlBody    = <<<EOF
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="keywords" content="{$keywords}">
    <meta name="description" content="{$description}">
    <title>Hello, world!</title>
  </head>
  <body>
    <h1>{$h1}</h1>
  </body>
</html>
EOF;

        Http::fake([
            // Stub a http response for all endpoints...
            '*' => Http::response($htmlBody, 200),
        ]);

        $response = $this->post(route('domains.checks', [$id]));
        $response->assertSessionHasNoErrors();
        $response->assertRedirect();
        $this->assertDatabaseHas('domain_checks', [
            'domain_id'   => $id,
            'h1'          => $h1,
            'keywords'    => $keywords,
            'description' => $description,
            'status_code' => $statusCode
        ]);
    }
}
