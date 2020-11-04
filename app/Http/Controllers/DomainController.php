<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DomainController extends Controller
{

    public function index()
    {
        $distinctOn = app('db')->raw('DISTINCT domain_id, created_at, status_code');
        $domains    = app('db')->table('domains')->get();
        $lastChecks = app('db')
            ->table('domain_checks')
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

        $inputDomainName      = $request->input('name');
        $domainComponents     = parse_url($inputDomainName);
        $scheme               = $domainComponents['scheme'];
        $host                 = $domainComponents['host'];
        $port                 = $domainComponents['port'] ?? '';
        $urlPortPart          = $port ? ":{$port}" : '';
        $normalizedDomainName = mb_strtolower("{$scheme}://{$host}{$urlPortPart}");

        $domain = app('db')->table('domains')->where('name', $normalizedDomainName)->first();
        if ($domain) {
            $domainId = $domain->id;
            flash('Domain already existed')->info();
        } else {
            $domainId = app('db')->table('domains')->insertGetId([
                'name'       => $normalizedDomainName,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
            flash('Domain was successfully added')->success();
        }

        return redirect()->route('domains.show', [$domainId]);
    }

    public function show($id)
    {
        $domain = app('db')->table('domains')->find($id, [
            'id',
            'name',
        ]);
        abort_unless($domain, 404);

        $domainChecks = app('db')->table('domain_checks')->where('domain_id', $id)->latest()->get();
        return view('domains.show', compact('domain', 'domainChecks'));
    }
}
