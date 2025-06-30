<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Visitor;
use App\Models\User;

class VisitorController extends Controller
{
    public function store(Request $request)
{
    $request->validate([
        'user_id' => 'required|exists:users,id',
        'visitor_name' => 'required|string',
        'company_name' => 'required|string',
        'valid_for' => 'required|string',
        'barcode' => 'required|string', // الكود نفسه وليس صورة
    ]);

    $visitor = Visitor::create([
        'user_id' => $request->user_id,
        'visitor_name' => $request->visitor_name,
        'company_name' => $request->company_name,
        'valid_for' => $request->valid_for,
        'barcode' => $request->barcode,
    ]);

    return response()->json(['visitor' => $visitor], 201);
}


public function getVisitorsByUserCompany($userId)
{
    // هات الشركة المرتبطة باليوزر ده
    $user = User::with('company')->findOrFail($userId);

    // هات كل اليوزرات اللي تبع نفس الشركة
    $userIdsInCompany = User::where('company_id', $user->company_id)->pluck('id');

    // هات الزوار اللي اليوزر بتاعهم تبع نفس الشركة
    $visitors = Visitor::whereIn('user_id', $userIdsInCompany)->get();

    return response()->json(['visitors' => $visitors]);
}

}
