<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class CashController extends Controller
{
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function edit()
    {
        $data = [
            'text' => Setting::where('param', 'cash_text')->first()->content,
            'img' => Setting::where('param', 'cash_img')->first()->content,
        ];

        return view('admin.cash.index', compact('data'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        $request->validate([
            'text' => 'required|max:1024',
            'img' => 'required|mimes:jpeg,jpg,png|file|max:5000',
        ]);

        $cashText = Setting::where('param', 'cash_text')->first();
        $cashImg = Setting::where('param', 'cash_img')->first();

        $data = $request->all();

        if ($request->hasFile('img')) {
            $folder = date('Y-m-d');
            $data['img'] = $request->file('img')->store("public/images/{$folder}");
            $data['img'] = str_replace("public/", "", $data['img']);
        }

        $cashText->content = $data['text'];
        $cashText->save();

        $cashImg->content = $data['img'];
        $cashImg->save();

        return redirect()->route('admin.cash.index')->with('success', __('ui.cash_updated'));
    }
}
