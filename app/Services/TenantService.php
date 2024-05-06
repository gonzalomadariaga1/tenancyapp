<?php

namespace App\Services;

use App\Models\Tenant;
use App\Http\Requests\TenantRegisterRequest;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Stancl\Tenancy\Database\Models\Domain;

class TenantService
{
    use ApiResponse;

    protected function tenantData($request): array
    {
        return [
            'password' => Hash::make($request['password']),
            'ready' => false,
            'is_banned' => false,
            'initial_setup' => false,
        ];
    }

    public function createTenant(TenantRegisterRequest $request): \Illuminate\Http\JsonResponse  
    {
        $tenant = Tenant::create(
            $request->safe()->except(['password']) +
                $this->tenantData($request)
        );

        $tenant->domains()->create([
            'domain' => $request->name.'.'.config('app.domain')
        ]);
        
        return $this->responseWithSuccess(
            'Registro exitoso. Revisa tu correo electrónico para verificarte.',
            $tenant
        );
    }

    public function impersonateAsTenant(Tenant $tenant)
    {
        //dd($tenant);
        $redirectUrl = '/dashboard';
        $domain = Domain::where('tenant_id',$tenant->id)->first();
        //dd($domain);
        $token = tenancy()->impersonate(
            $tenant,
            1,
            'http://' . $domain->domain .':7070/dashboard',
            'web',
        )->token;

        return $this->responseWithSuccess(
            'Login exitoso',
            [
                'redirect_url' => 'http://'. $domain->domain . ':7070/impersonate/' . $token
            ]
        );
    }
}