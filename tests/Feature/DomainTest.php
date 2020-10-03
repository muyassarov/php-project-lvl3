<?php

namespace Tests\Feature;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class DomainTest extends TestCase
{
    public function testHomepage()
    {
        $response = $this->get(route('home'));
        $response->assertOk();
    }

    public function testStore()
    {
        $data     = [
            'name' => 'https://google.md'
        ];
        $response = $this->post(route('domains.store'), $data);
        $response->assertSessionHasNoErrors();
        $response->assertRedirect();
        $this->assertDatabaseHas('domains', $data);
    }

    public function testIndex()
    {
        $response = $this->get(route('domains.index'));
        $response->assertOk();
    }

    public function testShow()
    {
        $data     = [
            'name'       => 'https://google.ru',
            'created_at' => Carbon::now()
        ];
        $id       = DB::table('domains')->insertGetId($data);
        $response = $this->get(route('domains.show', [$id]));

        $response->assertOk();
    }
}
