<?php

namespace Tests\Feature;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class DomainTest extends TestCase
{
    private $domainId;

    const DOMAIN_NAME = 'https://google.ru';
    const PAGE_TITLE  = 'Page Analyzer';

    protected function setUp(): void
    {
        parent::setUp();
        $data           = [
            'name'       => self::DOMAIN_NAME,
            'created_at' => Carbon::now()
        ];
        $this->domainId = DB::table('domains')->insertGetId($data);
    }

    public function testRoot()
    {
        $response = $this->get(route('root'));
        $response->assertSee(self::PAGE_TITLE);
        $response->assertOk();
    }

    public function testIndex()
    {
        $response = $this->get(route('domains.index'));
        $response->assertSee(self::DOMAIN_NAME);
        $response->assertOk();
    }

    public function testStore()
    {
        $data     = [
            'name' => self::DOMAIN_NAME
        ];
        $response = $this->post(route('domains.store'), $data);
        $response->assertSessionHasNoErrors();
        $response->assertRedirect();
        $this->assertDatabaseHas('domains', $data);
    }

    public function testShow()
    {
        $response = $this->get(route('domains.show', $this->domainId));
        $response->assertSee(self::DOMAIN_NAME);
        $response->assertOk();
    }
}
