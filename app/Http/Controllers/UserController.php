<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        return User::all();
    }

    public function store(Request $request)
    {
        $user = User::create($request->all());
        return response()->json($user);
    }

    public function destroy(User $user)
    {
        $user->campaigns()->delete();  // Удаление всех кампаний пользователя
        $user->delete();
        return response()->json(['message' => 'Пользователь удалён']);
    }
}
