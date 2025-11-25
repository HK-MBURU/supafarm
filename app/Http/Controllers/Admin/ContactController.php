<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

class ContactController extends Controller
{
    /**
     * Display a listing of the contacts.
     */
    public function index(Request $request): View
    {
        // Get search query
        $search = $request->get('search');

        // Build query with search and filters
        $contacts = Contact::when($search, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                      ->orWhere('subject', 'like', "%{$search}%")
                      ->orWhere('message', 'like', "%{$search}%");
                });
            })
            ->when($request->has('read_status'), function ($query) use ($request) {
                return $query->where('is_read', $request->get('read_status'));
            })
            ->orderBy('created_at', 'desc')
            ->paginate(15)
            ->withQueryString();

        // Get counts for filters
        $totalContacts = Contact::count();
        $unreadCount = Contact::where('is_read', false)->count();
        $readCount = Contact::where('is_read', true)->count();

        return view('admin.contacts.index', compact(
            'contacts',
            'search',
            'totalContacts',
            'unreadCount',
            'readCount'
        ));
    }

    /**
     * Show the form for creating a new contact.
     */
    public function create(): View
    {
        return view('admin.contacts.create');
    }

    /**
     * Store a newly created contact in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:500',
            'message' => 'required|string',
            'is_read' => 'sometimes|boolean',
        ]);

        Contact::create($validated);

        return redirect()->route('admin.contacts.index')
            ->with('success', 'Contact created successfully.');
    }

    /**
     * Display the specified contact.
     */
    public function show(Contact $contact): View
    {
        // Mark as read when viewing
        if (!$contact->is_read) {
            $contact->update(['is_read' => true]);
        }

        return view('admin.contacts.show', compact('contact'));
    }

    /**
     * Show the form for editing the specified contact.
     */
    public function edit(Contact $contact): View
    {
        return view('admin.contacts.edit', compact('contact'));
    }

    /**
     * Update the specified contact in storage.
     */
    public function update(Request $request, Contact $contact): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:500',
            'message' => 'required|string',
            'is_read' => 'sometimes|boolean',
        ]);

        $contact->update($validated);

        return redirect()->route('admin.contacts.index')
            ->with('success', 'Contact updated successfully.');
    }

    /**
     * Remove the specified contact from storage.
     */
    public function destroy(Contact $contact): RedirectResponse
    {
        $contact->delete();

        return redirect()->route('admin.contacts.index')
            ->with('success', 'Contact deleted successfully.');
    }

    /**
     * Bulk delete contacts.
     */
    public function bulkDestroy(Request $request): RedirectResponse
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:contacts,id'
        ]);

        $count = Contact::whereIn('id', $request->ids)->delete();

        return redirect()->route('admin.contacts.index')
            ->with('success', "{$count} contacts deleted successfully.");
    }

    /**
     * Mark contacts as read.
     */
    public function markAsRead(Request $request): RedirectResponse
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:contacts,id'
        ]);

        $count = Contact::whereIn('id', $request->ids)->update(['is_read' => true]);

        return redirect()->route('admin.contacts.index')
            ->with('success', "{$count} contacts marked as read.");
    }

    /**
     * Mark contacts as unread.
     */
    public function markAsUnread(Request $request): RedirectResponse
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:contacts,id'
        ]);

        $count = Contact::whereIn('id', $request->ids)->update(['is_read' => false]);

        return redirect()->route('admin.contacts.index')
            ->with('success', "{$count} contacts marked as unread.");
    }

    /**
     * Export contacts to CSV.
     */
    public function export(Request $request)
    {
        $contacts = Contact::when($request->has('read_status'), function ($query) use ($request) {
                return $query->where('is_read', $request->get('read_status'));
            })
            ->orderBy('created_at', 'desc')
            ->get();

        $fileName = 'contacts_' . date('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$fileName}\"",
        ];

        $callback = function () use ($contacts) {
            $file = fopen('php://output', 'w');

            // Add CSV headers
            fputcsv($file, ['ID', 'Name', 'Email', 'Subject', 'Message', 'Read Status', 'Created At']);

            // Add data
            foreach ($contacts as $contact) {
                fputcsv($file, [
                    $contact->id,
                    $contact->name,
                    $contact->email,
                    $contact->subject,
                    strip_tags($contact->message),
                    $contact->is_read ? 'Read' : 'Unread',
                    $contact->created_at->format('Y-m-d H:i:s')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Get contact statistics for dashboard.
     */
    public function getStats(): array
    {
        return [
            'total' => Contact::count(),
            'unread' => Contact::where('is_read', false)->count(),
            'today' => Contact::whereDate('created_at', today())->count(),
            'this_week' => Contact::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
        ];
    }

    /**
     * Get recent contacts for dashboard.
     */
    public function getRecent($limit = 5)
    {
        return Contact::orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }
}
