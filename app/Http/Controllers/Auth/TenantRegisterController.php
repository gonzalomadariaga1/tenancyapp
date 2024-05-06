<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\TenantRegisterRequest;
use App\Services\TenantService;
use Illuminate\Http\Request;

class TenantRegisterController extends Controller
{
    //
    public function create()
    {
        return view('auth.tenants-register');
    }

    public function store(TenantRegisterRequest $request, TenantService $tenantService)
    {
        return $tenantService->createTenant($request);
    }
}
