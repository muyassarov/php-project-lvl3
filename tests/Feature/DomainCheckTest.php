<?php

namespace Tests\Feature;

use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class DomainCheckTest extends TestCase
{
    use RefreshDatabase;

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

        $response = $this->post(route('domains.checks', [$id]));
        $response->assertSessionHasNoErrors();
        $response->assertRedirect();
        $this->assertDatabaseHas('domain_checks', [
            'domain_id' => $id,
        ]);
    }
}
