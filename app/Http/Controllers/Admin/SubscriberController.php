<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\Newsletter;
use App\Models\Subscriber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class SubscriberController extends Controller implements HasMiddleware
{
  public static function middleware(): array
  {
    return [
      new Middleware('permission:subscribers index,admin', only: ['index', 'store']),
      new Middleware('permission:subscribers delete,admin', only: ['destroy']),
    ];
  }

  public function index()
  {
    $subs = Subscriber::all();
    return view('admin.subscriber.index', compact('subs'));
  }

  public function store(Request $request)
  {
    $request->validate([
      'subject' => 'required|max:255',
      'message' => 'required'
    ]);

    $subscribers = Subscriber::pluck('email')->toArray();
    Mail::to($subscribers)->send(new Newsletter($request->subject, $request->message));
    toast(__('admin.Mail Sent Successfully'), 'success');
    return redirect()->back();
  }

  public function destroy(string $id)
  {
    Subscriber::findOrFail($id)->delete();
    return response(['status' => 'success', 'message' => __('admin.Deleted Successfully')]);
  }
}
