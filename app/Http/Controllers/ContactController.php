<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Notifications\ContactFormSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class ContactController extends Controller
{
    public function showForm()
    {
        return view('contact.index');
    }

    /**
     * Handle the contact form submission.
     */
    public function submit(Request $request)
    {
        // Validate the form data
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'nullable|string|max:255',
            'message' => 'required|string',
        ]);

        // Create a new contact record
        $contact = Contact::create($validated);

        // Send email notification
        try {
            // Send to admin email address
            Notification::route('mail', config('mail.from.address'))
                ->notify(new ContactFormSubmission($contact));

            // If you want to send to multiple recipients
            Notification::route('mail', [
                'haronkiarii@gmail.com' => 'HK MBURU',
                'haronkiari@gmail.com' => 'Manager Name',
            ])->notify(new ContactFormSubmission($contact));
        } catch (\Exception $e) {
            // Log the error but don't stop execution
            \Log::error('Failed to send contact form email: ' . $e->getMessage());
        }

        // Return with success message
        return redirect()->back()->with('success', 'Thank you for your message! We will get back to you soon.');
    }
}
