<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\Routing\Pipeline;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;

class HomeController extends Controller
{
    /**
     * Handle the incoming request for the root route.
     *
     * Serves the central marketing landing page on central domains,
     * or initializes tenant context and delegates to the StorefrontController
     * when accessed via a tenant subdomain or custom domain.
     */
    public function __invoke(Request $request): mixed
    {
        if (in_array($request->getHost(), config('tenancy.central_domains', []))) {
            $proPlan = Plan::where('slug', 'pro')->first();

            return inertia('Marketing/Home', [
                'proPrice' => $proPlan && $proPlan->price_monthly !== null
                    ? '₦'.number_format((float) $proPlan->price_monthly, 0)
                    : '₦4,999',
            ]);
        }

        return app(Pipeline::class)
            ->send($request)
            ->through([InitializeTenancyByDomain::class])
            ->then(fn () => app(StorefrontController::class)->index($request));
    }
}
