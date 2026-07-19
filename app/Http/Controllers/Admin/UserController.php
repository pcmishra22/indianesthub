<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Builder;
use App\Models\Dealer;
use App\Models\ServiceProvider;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class UserController extends Controller
{
    /**
     * Unified account list across every real, live account type on the
     * site: Users (customers), Dealers (shown publicly as "Agents"),
     * Builders, and Service Providers. Each type lives in its own table
     * with its own schema, so this pulls a normalized subset of fields
     * from each, tags it with its type, merges, sorts by newest first,
     * and paginates the merged result manually.
     *
     * Note: there is a legacy `agents` DB table/model with no login guard
     * and nothing in the live app that writes to it — it's not a real,
     * active account type, so it's intentionally not included here.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $type = $request->input('type'); // user | dealer | builder | service_provider | '' (all)

        $all = collect();

        if (!$type || $type === 'user') {
            $all = $all->merge($this->normalize(
                $this->applySearch(User::query(), $search, ['name', 'email', 'phone'])->get(),
                'user', 'Customer', 'admin-user'
            ));
        }

        if (!$type || $type === 'dealer') {
            $all = $all->merge($this->applySearch(
                Dealer::query(), $search, ['first_name', 'last_name', 'company_name', 'email', 'phone']
            )->get()->map(function ($d) {
                return $this->row(
                    'dealer',
                    'Agent / Dealer',
                    trim($d->first_name . ' ' . $d->last_name) ?: $d->company_name,
                    $d->email,
                    $d->phone,
                    $d->status,
                    $this->dealerBadge($d->status),
                    $d->created_at,
                    route('admin.dealers.show', $d->id)
                );
            }));
        }

        if (!$type || $type === 'builder') {
            $all = $all->merge($this->applySearch(
                Builder::query(), $search, ['name', 'company_name', 'email', 'phone']
            )->get()->map(function ($b) {
                return $this->row(
                    'builder',
                    'Builder',
                    $b->company_name ?: $b->name,
                    $b->email,
                    $b->phone,
                    $b->status,
                    $b->status === 'active' ? 'success' : 'danger',
                    $b->created_at,
                    route('admin.builders.show', $b->id)
                );
            }));
        }

        if (!$type || $type === 'service_provider') {
            $all = $all->merge($this->applySearch(
                ServiceProvider::query(), $search, ['full_name', 'business_name', 'email', 'phone']
            )->get()->map(function ($sp) {
                return $this->row(
                    'service_provider',
                    'Service Provider',
                    $sp->business_name ?: $sp->full_name,
                    $sp->email,
                    $sp->phone,
                    $sp->status,
                    $this->serviceProviderBadge($sp->status),
                    $sp->created_at,
                    route('admin.service-providers.show', $sp->id)
                );
            }));
        }

        $all = $all->sortByDesc('created_at')->values();

        $perPage = 20;
        $page = (int) $request->input('page', 1);
        $users = new LengthAwarePaginator(
            $all->forPage($page, $perPage),
            $all->count(),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        $counts = [
            'user'             => User::count(),
            'dealer'           => Dealer::count(),
            'builder'          => Builder::count(),
            'service_provider' => ServiceProvider::count(),
        ];

        return view('backend.users.index', compact('users', 'counts'));
    }

    protected function applySearch($query, ?string $search, array $columns)
    {
        if (!$search) {
            return $query;
        }

        return $query->where(function ($q) use ($search, $columns) {
            foreach ($columns as $column) {
                $q->orWhere($column, 'like', "%{$search}%");
            }
        });
    }

    protected function normalize(Collection $records, string $type, string $typeLabel, string $viewRouteName): Collection
    {
        return $records->map(function ($u) use ($type, $typeLabel, $viewRouteName) {
            return $this->row(
                $type,
                $typeLabel,
                $u->name,
                $u->email,
                $u->phone,
                $u->status,
                $u->status === 'active' ? 'success' : 'danger',
                $u->created_at,
                route('admin.users.show', $u->id)
            );
        });
    }

    protected function row(string $type, string $typeLabel, ?string $name, ?string $email, ?string $phone, ?string $status, string $badgeColor, $createdAt, ?string $viewUrl): array
    {
        return [
            'type'        => $type,
            'type_label'  => $typeLabel,
            'name'        => $name ?: '—',
            'email'       => $email ?: '—',
            'phone'       => $phone ?: '—',
            'status'      => $status ?: 'active',
            'badge_color' => $badgeColor,
            'created_at'  => $createdAt,
            'view_url'    => $viewUrl,
        ];
    }

    protected function dealerBadge(?string $status): string
    {
        return match ($status) {
            'active' => 'success',
            'inactive' => 'secondary',
            'blocked' => 'danger',
            default => 'secondary',
        };
    }

    protected function serviceProviderBadge(?string $status): string
    {
        return match ($status) {
            'approved' => 'success',
            'pending' => 'warning',
            'rejected', 'suspended' => 'danger',
            default => 'secondary',
        };
    }

    public function show($id)
    {
        $user = User::findOrFail($id);
        return view('backend.users.show', compact('user'));
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $name = $user->name;
        $user->delete();

        return back()->with('success', "User \"{$name}\" has been deleted.");
    }

    /**
     * Enable / disable a user account. Blocked users are signed out of
     * any active session and can no longer log back in (see UserLoginController).
     */
    public function toggleStatus($id)
    {
        $user = User::findOrFail($id);
        $user->status = ($user->status === 'blocked') ? 'active' : 'blocked';
        $user->save();

        $label = $user->status === 'active' ? 'Active' : 'Blocked';
        return back()->with('success', "User status updated to {$label}.");
    }
}
