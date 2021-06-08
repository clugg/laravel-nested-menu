<?php

namespace App\Http\Controllers;

use App\Models\MenuItem;
use Illuminate\Http\Request;

class MenuItemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return MenuItem::orderBy('label')->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $this->validate($request, [
            'label' => 'string',
            'parent_id' => ['integer', 'exists:'.MenuItem::class.',id'],
        ]);

        return MenuItem::create($data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\MenuItem $menuItem
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, MenuItem $menuItem)
    {
        $data = $this->validate($request, [
            'label' => 'string',
        ]);

        return ['success' => $menuItem->update($data)];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\MenuItem $menuItem
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(MenuItem $menuItem)
    {
        return ['success' => $menuItem->delete()];
    }
}
