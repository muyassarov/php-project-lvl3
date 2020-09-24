<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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

        // insert new domain
        DB::table('domain_checks')->insert([
            'domain_id'  => $id,
            'created_at' => Carbon::now(),
        ]);

        flash('Website has been checked!')->success();
        return redirect()->route('domains.show', [$id]);
    }
}
