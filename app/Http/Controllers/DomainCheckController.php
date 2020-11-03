<?php

namespace App\Http\Controllers;

use App\Services\Domain\SeoAnalyzer;
use Carbon\Carbon;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;

class DomainCheckController extends Controller
{

    public function store($id)
    {
        $domain = app('db')->table('domains')->find($id, [
            'id',
            'name',
        ]);
        abort_unless($domain, 404);

        try {
            $httpResponse         = Http::get($domain->name);
            $domainSeoAnalyzer    = new SeoAnalyzer();
            $domainSeoInformation = $domainSeoAnalyzer->analyze($httpResponse->body());
            app('db')->table('domain_checks')->insert([
                'domain_id'   => $id,
                'status_code' => $httpResponse->status(),
                'h1'          => $domainSeoInformation['h1'],
                'keywords'    => $domainSeoInformation['keywords'],
                'description' => $domainSeoInformation['description'],
                'created_at'  => Carbon::now(),
                'updated_at'  => Carbon::now(),
            ]);

            flash('Website has been checked!')->success();
        } catch (RequestException $e) {
            flash("Request failed. Message: {$e->getMessage()}")->error();
        } catch (ConnectionException $e) {
            flash("Connection error. Message: {$e->getMessage()}")->error();
        }

        return redirect()->route('domains.show', [$id]);
    }
}
