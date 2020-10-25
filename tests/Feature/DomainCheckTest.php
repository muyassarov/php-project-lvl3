<?php

namespace Tests\Feature;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class DomainCheckTest extends TestCase
{
    private $domainId;
    private $htmlContent;

    protected function setUp(): void
    {
        parent::setUp();

        $fixtureFilepath   = implode(DIRECTORY_SEPARATOR, [__DIR__, '..', 'Fixtures', 'index.html']);
        $this->htmlContent = file_get_contents($fixtureFilepath);
        $this->domainId    = DB::table('domains')->insertGetId([
            'name'       => 'https://test.com',
            'created_at' => Carbon::now(),
        ]);
    }

    public function testStore()
    {
        Http::fake([
            '*' => Http::response($this->htmlContent, 200),
        ]);

        $response = $this->post(route('domains.checks.store', [$this->domainId]));
        $response->assertSessionHasNoErrors();
        $response->assertRedirect();
        $this->assertDatabaseHas('domain_checks', [
            'domain_id'   => $this->domainId,
            'h1'          => 'Hello, world!',
            'keywords'    => 'key,key1,key2,key3',
            'description' => 'website description',
            'status_code' => 200
        ]);
    }
}
