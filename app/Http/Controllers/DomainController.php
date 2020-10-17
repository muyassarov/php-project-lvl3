<?php

namespace App\Http\Controllers;

use App\Services\Domain\DomainNormalizer;
use App\Services\Domain\HttpChecker;
use App\Services\Domain\SeoAnalyzer;
use Carbon\Carbon;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DomainController extends Controller
{

    public function index()
    {
        $distinctOn = app('db')->raw('DISTINCT domain_id, created_at, status_code');
        $domains    = app('db')->table('domains')->get();
        $lastChecks = app('db')->table('domain_checks')
            ->select($distinctOn)
            ->orderBy("domain_id")
            ->orderBy("created_at", "desc")
            ->whereIn('domain_id', $domains->pluck('id'))
            ->get()
            ->keyBy('domain_id');

        return view('domains.index', compact('domains', 'lastChecks'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|url',
        ]);
        if ($validator->fails()) {
            flash($validator->errors()->first())->error();
            return redirect()->route('root')->withInput();
        }

        $domainName = DomainNormalizer::normalize($request->input('name'));
        $domain     = app('db')->table('domains')->where('name', '=', $domainName)->first();
        if ($domain) {
            $domainId = $domain->id;
            flash('Domain already existed')->info();
        } else {
            $domainId = app('db')->table('domains')->insertGetId([
                'name'       => $domainName,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
            flash('Domain was successfully added')->success();
        }

        return redirect()->route('domains.show', [$domainId]);
    }

    public function storeCheck($id)
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
            ]);

            flash('Website has been checked!')->success();
        } catch (RequestException $e) {
            flash("Error occurred while checking domain. Message: {$e->getMessage()}")->error();
        }

        return redirect()->route('domains.show', [$id]);
    }

    public function show($id)
    {
        $domain = app('db')->table('domains')->find($id, [
            'id',
            'name',
        ]);
        abort_unless($domain, 404);

        $domainChecks = app('db')->table('domain_checks')->where('domain_id', '=', $id)
            ->latest()->get();
        return view('domains.item', compact('domain', 'domainChecks'));
    }
}
