<?php

namespace App\Http\Controllers\owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Announcement;
use App\Models\Company;



class EventController extends Controller
{
    public function eventView(){
        $announcements = Announcement::latest()->get(); // جلب كل الإعلانات
        $companies = Company::all(); // ✅ أضف هذه السطر لجلب الشركات

        return view('owner.event.event', compact('announcements', 'companies'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'sub_title' => 'nullable|string|max:255',
            'body' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
            'type' => 'required|string',
            'published_by' => 'required|integer',
            'requested_by' => 'required|string',
            'visible_to' => 'required|array', // ✅ مصفوفة
        ]);

        // دمج الشركات المحددة إلى نص
        $data['visible_to'] = implode(',', $data['visible_to']);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('announcements', 'public');
        }

        Announcement::create($data);

        return redirect()->back()->with('success', 'added');
    }
}
