<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use DiDom\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class DomainController extends Controller
{
    public function store(Request $request)
    {
        // validate input data:
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:domains,name|url',
        ]);
        if ($validator->fails()) {
            flash($validator->errors()->first())->error();
            return redirect()->route('home')->withInput();
        }

        // insert new domain
        DB::table('domains')->insert([
            'name'       => $request->input('name'),
            'created_at' => Carbon::now(),
        ]);

        flash('Domain was successfully added')->success();
        return redirect()->route('home');
    }

    public function index()
    {
        $subQuery = DB::table('domain_checks AS dc')
            ->select('dc.domain_id', 'dc.status_code', 'dc.created_at AS last_check_at')
            ->leftJoin('domain_checks AS dc1', function ($join) {
                $join->on('dc.domain_id', '=', 'dc1.domain_id');
                $join->on('dc.created_at', '<', 'dc1.created_at');
            })
            ->whereNull('dc1.domain_id');

        $domains = DB::table('domains AS d')->select([
            'd.id',
            'd.name',
            'dc.last_check_at',
            'dc.status_code',
        ])->leftJoin(DB::raw("({$subQuery->toSql()}) AS dc"), 'd.id', '=', 'dc.domain_id')->get();

        return view('domains.index', ['domains' => $domains]);
    }

    public function show($id)
    {
        $domain = DB::table('domains')->find($id, [
            'id',
            'name',
        ]);
        if (!$domain) {
            flash('No domain found by given Id')->error();
            return redirect()->route('domains.index');
        }
        $domainChecks = DB::table('domain_checks')->where('domain_id', '=', $id)->get();

        return view('domains.item', [
            'domain'       => $domain,
            'domainChecks' => $domainChecks,
        ]);
    }

    public function storeCheck($id)
    {
        $validator = Validator::make(['id' => $id], [
            'id' => 'required|exists:domains',
        ]);
        if ($validator->fails()) {
            flash($validator->errors()->first())->error();
            return redirect()->route('domains.index')->withInput();
        }

        $h1          = null;
        $description = null;
        $keywords    = null;
        $statusCode  = null;
        $domain      = DB::table('domains')->find($id, [
            'id',
            'name',
        ]);
        try {
            $httpResponse = Http::get($domain->name);
            $statusCode   = $httpResponse->status();
            if ($statusCode == 200) {
                $htmlBody           = $httpResponse->body();
                $htmlDocument       = new Document($htmlBody);
                $h1Element          = $htmlDocument->first('h1');
                $descriptionElement = $htmlDocument->first('meta[name="description"]');
                $keywordsElement    = $htmlDocument->first('meta[name="keywords"]');
                if ($descriptionElement) {
                    $description = $descriptionElement->getAttribute('content');
                }
                if ($keywordsElement) {
                    $keywords = $keywordsElement->getAttribute('content');
                }
                if ($h1Element) {
                    $h1 = $h1Element->text();
                }
            }
        } catch (\Exception $e) {
        }

        // insert new domain
        DB::table('domain_checks')->insert([
            'domain_id'   => $id,
            'status_code' => $statusCode,
            'h1'          => $h1,
            'keywords'    => $keywords,
            'description' => $description,
            'created_at'  => Carbon::now(),
        ]);

        flash('Website has been checked!')->success();
        return redirect()->route('domains.show', [$id]);
    }
}
