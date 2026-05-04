<?php

namespace App\Http\Controllers;

use App\Services\MispClient;
use App\Services\MispTransformer;

class AttributeController extends Controller
{
    public function index(MispClient $client, MispTransformer $tx)
    {
        $rows = $client->searchAttributes(['limit' => 200]);

        $infoIndex = [];
        foreach ($client->searchEvents(['limit' => 200]) as $env) {
            $e = $env['Event'] ?? [];
            if (isset($e['id'])) {
                $infoIndex[(string) $e['id']] = (string) ($e['info'] ?? '');
            }
        }

        $attributes = array_map(function ($a) use ($tx, $infoIndex) {
            $eid = (string) ($a['event_id'] ?? '');
            $rawEid = $tx->rawId($eid);
            return $tx->attribute($a, $eid, $infoIndex[$rawEid] ?? null);
        }, $rows);

        return view('attributes', [
            'attributes' => $attributes,
        ]);
    }
}
