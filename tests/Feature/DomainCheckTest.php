<?php

namespace Tests\Feature;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Tests\Fixtures\DomainCheckFixture;
use Tests\TestCase;

class DomainCheckTest extends TestCase
{
    private $domainId;

    protected function setUp(): void
    {
        parent::setUp();
        $this->domainId = DB::table('domains')->insertGetId([
            'name'       => 'https://test.com',
            'created_at' => Carbon::now(),
        ]);
    }

    public function testStore()
    {
        $fixture     = new DomainCheckFixture();
        $fixtureData = $fixture->initFixtures();

        Http::fake([
            '*' => Http::response($fixtureData['htmlBody'], $fixtureData['statusCode']),
        ]);

        $response = $this->post(route('domains.checks.store', [$this->domainId]));
        $response->assertSessionHasNoErrors();
        $response->assertRedirect();
        $this->assertDatabaseHas('domain_checks', [
            'domain_id'   => $this->domainId,
            'h1'          => $fixtureData['h1'],
            'keywords'    => $fixtureData['keywords'],
            'description' => $fixtureData['description'],
            'status_code' => $fixtureData['statusCode']
        ]);
    }
}
