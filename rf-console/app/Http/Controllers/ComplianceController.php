<?php

namespace App\Http\Controllers;

use App\Services\MockData;

class ComplianceController extends Controller
{
    public function index(MockData $data)
    {
        return view('compliance', [
            'summary' => $data->complianceSummary(),
            'findings' => $data->complianceFindings(),
        ]);
    }
}
