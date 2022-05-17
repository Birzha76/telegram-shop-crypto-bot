<?php

namespace App\Http\Controllers;

use App\Enums\CheckStatus;
use App\Http\TGBot\TelegramBot;
use App\Models\Check;
use App\Models\TelegramUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CheckController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        $checks = Check::with('user')->orderBy('created_at', 'DESC')->paginate(20);

        return view('admin.checks.index', compact('checks'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function edit($id)
    {
        $check = Check::with('user')->find($id);

        return view('admin.checks.edit', compact('check'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required',
        ]);

        $data = $request->all();

        $check = Check::find($id);
        $check->update([
            'status' => $data['status'],
        ]);

        if ($data['status'] == CheckStatus::Considered) {
            $currentUser = TelegramUser::find($check->user_id);

            if ($currentUser->ban == 0) {
                $answer = __('answer.check_processed');
                $keyboard = TelegramBot::inlineKeyboard(__('menu.main_menu'));

                TelegramBot::sendMessage($currentUser->uid, $answer, $keyboard);
            }
        }

        return redirect()->route('admin.checks.index')->with('success', __('ui.check_status_updated'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $check = Check::find($id);

        Storage::delete($check->file_path);

        $check->delete();

        return redirect()->route('admin.checks.index')->with('success', __('ui.check_removed'));
    }
}
