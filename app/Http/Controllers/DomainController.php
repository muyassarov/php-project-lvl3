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
            'domain' => 'required|unique:domains,name|url'
        ]);
        if ($validator->fails()) {
            flash($validator->errors()->first())->error();
            return redirect()->route('home')->withInput();
        }

        // insert new domain
        $domain = $request->input('domain');
        DB::table('domains')->insert([
            'name' => $domain, 'created_at' => Carbon::now()
        ]);

        flash('Domain was successfully added')->success();
        return redirect()->route('home');
    }

    public function index()
    {
        $domains = DB::table('domains')->get(['id', 'name']);
        return view('domains.index', ['domains' => $domains]);
    }

    public function show($id)
    {
        $domain = DB::table('domains')->find($id, ['id', 'name']);
        if (!$domain) {
            flash('No domain found by given Id')->error();
            return redirect()->route('domains.index');
        }
        return view('domains.item', ['domain' => $domain]);
    }

    public function check($id)
    {
        dd($id);
    }
}
