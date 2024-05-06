<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use App\Services\TenantService;
use Illuminate\Http\Request;

class TenantController extends Controller
{
    //
    public function impersonate(Tenant $tenant, TenantService $tenantService)
    {
        return $tenantService->impersonateAsTenant($tenant);
    }
}
