<?php

namespace App\Http\Middleware;

use App\Services\Models\RoleService;
use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return array_merge(parent::share($request), [
            'auth' => [
                'user' => $this->getAuthenticatedUserData($request),
            ],
        ]);
    }

    private function getAuthenticatedUserData(Request $request): ?array
    {
        $user = $request->user();
        $roleService = app(RoleService::class);

        if (! $user) {
            return null;
        }

        return array_merge($user->toArray(), [
            'two_factor_enabled' => ! $user->two_factor_secret ? false : true,
            'roles' => $roleService->getRoleNames($user) ?? [],
            'permissions' => $roleService->getPermissions($user) ?? [],
        ]);
    }
}
