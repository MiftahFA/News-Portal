<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\ContactMail;
use App\Models\Contact;
use App\Models\RecivedMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class ContactMessageController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:contact message index,admin', only: ['index']),
            new Middleware('permission:contact message update,admin', only: ['sendReplay']),
        ];
    }

    public function index()
    {
        RecivedMail::query()->update(['seen' => 1]);
        $messages = RecivedMail::all();
        return view('admin.contact-message.index', compact('messages'));
    }

    public function sendReplay(Request $request)
    {
        $request->validate([
            'subject' => 'required|max:255',
            'message' => 'required'
        ]);

        try {
            $contact = Contact::where('language', 'en')->first();
            Mail::to($request->email)->send(new ContactMail($request->subject, $request->message, $contact->email));

            $makeReplied = RecivedMail::find($request->message_id);
            $makeReplied->replied = 1;
            $makeReplied->save();
            toast(__('Mail Sent Successfully!'), 'success');
            return redirect()->back();
        } catch (\Throwable $th) {
            toast($th->getMessage(), 'error');
            return redirect()->back();
        }
    }
}
