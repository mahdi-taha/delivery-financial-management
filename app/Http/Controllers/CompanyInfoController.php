<?php
namespace App\Http\Controllers;
use App\Helpers\ActivityLogger;
use App\Models\CompanyInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
class CompanyInfoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $company = CompanyInfo::first();
        return view('settings.company_info.index', compact('company'));
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'name'    => ['required', 'string', 'max:255'],
            'phone'   => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:255'],
            'email'   => ['nullable', 'email', 'max:255'],
            'website' => ['nullable', 'url', 'max:255'],
            'logo'    => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);
        // get first or create if not exists
        $company = CompanyInfo::firstOrNew([]);
        $old = $company->toArray();
        if ($request->hasFile('logo')) {
            if ($company->exists && $company->logo && Storage::disk('public')->exists($company->logo)) {
                Storage::disk('public')->delete($company->logo);
            }
            $validated['logo'] = $request->file('logo')->store('company', 'public');
        }
        $company->fill($validated);
        $company->save();
        ActivityLogger::log(
            'updated',
            $company,
            "Updated company {$company->name}",
            $old,
            $company->fresh()->toArray()
        );
        return redirect()
            ->back()
            ->with('success',  __('messages.updated_successfully', [
                    'item' => __('messages.company_info')
                ]));
    }
}
