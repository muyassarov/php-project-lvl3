<?php

namespace App\Http\Controllers\Domain;

use App\Http\Controllers\Controller;
use App\Services\Domain\HttpChecker;
use App\Services\Domain\SeoAnalyzer;
use Carbon\Carbon;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Request;

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
            flash("Request failed. Message: {$e->getMessage()}")->error();
        } catch (ConnectionException $e) {
            flash("Connection error. Message: {$e->getMessage()}")->error();
        }

        return redirect()->route('domains.show', [$id]);
    }
}
