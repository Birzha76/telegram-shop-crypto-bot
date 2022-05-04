<?php

namespace App\Http\Controllers;

use App\Models\TelegramUser;
use Illuminate\Http\Request;

class TelegramUserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = TelegramUser::paginate(20);
        return view('admin.tg-users.index', compact('users'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = TelegramUser::find($id);
        return view('admin.tg-users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'percent' => 'nullable|numeric',
        ]);

        $user = TelegramUser::find($id);
        $user->update($request->all());

        return redirect()->route('admin.tg-users.index')->with('success', 'Информация о пользователе обновлена');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = TelegramUser::find($id);
        $user->delete();

        return redirect()->route('admin.tg-users.index')->with('success', 'Пользователь удален');
    }
}
