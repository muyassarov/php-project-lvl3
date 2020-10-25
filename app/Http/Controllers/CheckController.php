<?php

namespace App\Http\Controllers;

use App\Services\Domain\HttpChecker;
use App\Services\Domain\SeoAnalyzer;
use Carbon\Carbon;
use GuzzleHttp\Exception\RequestException;

class CheckController extends Controller
{

    public function store($id)
    {
        $domain = app('db')->table('domains')->find($id, [
            'id',
            'name',
        ]);
        abort_unless($domain, 404);

        try {
            $domainChecker        = new HttpChecker(['url' => $domain->name]);
            $domainSeoAnalyzer    = new SeoAnalyzer();
            $domainResponse       = $domainChecker->check();
            $domainSeoInformation = $domainSeoAnalyzer->analyze($domainResponse['body']);
            app('db')->table('domain_checks')->insert([
                'domain_id'   => $id,
                'status_code' => $domainResponse['statusCode'],
                'h1'          => $domainSeoInformation['h1'],
                'keywords'    => $domainSeoInformation['keywords'],
                'description' => $domainSeoInformation['description'],
                'created_at'  => Carbon::now(),
                'updated_at'  => Carbon::now(),
            ]);

            flash('Website has been checked!')->success();
        } catch (RequestException $e) {
            flash("Error occurred while checking domain. Message: {$e->getMessage()}")->error();
        }

        return redirect()->route('domains.show', [$id]);
    }
}
