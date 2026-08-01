<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

/**
 * This screen used to operate on the exact same `bulk_emails` table as
 * Admin\UserBulkEmailController with no way to record which audience a
 * given draft was actually meant for — meaning a "dealer" draft could be
 * opened from the Users screen and accidentally sent to every customer
 * instead, or vice versa. Now that bulk_emails has an explicit `audience`
 * column and the Users screen supports selecting Dealers/Builders/Service
 * Providers/Everyone, this controller just redirects there instead of
 * running a second, ambiguity-prone copy of the same feature.
 */
class DealerBulkEmailController extends Controller
{
    public function index()
    {
        return redirect()->route('admin.users.bulk-email.index')
            ->with('success', 'Dealer bulk email has moved — use the audience selector below to target dealers specifically.');
    }

    public function create()
    {
        return redirect()->route('admin.users.bulk-email.create');
    }

    public function edit($id)
    {
        return redirect()->route('admin.users.bulk-email.edit', $id);
    }
}
