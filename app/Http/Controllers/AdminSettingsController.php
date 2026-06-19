<?php

namespace App\Http\Controllers;

use App\Models\AppSetting;
use App\Services\ProjectPlannerAI;
use Illuminate\Http\Request;

class AdminSettingsController extends Controller
{
    public function edit(ProjectPlannerAI $ai)
    {
        return view('dashboard.settings.index', [
            'aiEnabled'     => AppSetting::bool('ai_planner_enabled', false),
            'aiProvider'    => AppSetting::get('ai_provider', 'gemini'),
            'geminiModel'   => AppSetting::get('gemini_model', 'gemini-2.5-flash'),
            'aiModel'       => AppSetting::get('ai_model', 'claude-haiku-4-5-20251001'),
            'hasGeminiKey'  => (bool) (AppSetting::getSecret('gemini_api_key') ?: config('services.gemini.key')),
            'hasKey'        => (bool) (AppSetting::getSecret('anthropic_api_key') ?: config('services.anthropic.key')),
            'aiActive'      => $ai->enabled(),
            'contactTiming' => AppSetting::get('planner_contact_timing', 'before'),
            'showRaw'       => AppSetting::bool('planner_show_raw', false),
            'whatsappNumber'=> AppSetting::get('whatsapp_default_number', '97158128418'),
        ]);
    }

    public function save(Request $request)
    {
        $request->validate([
            'ai_provider'            => 'nullable|in:gemini,anthropic',
            'ai_model'               => 'nullable|string|max:80',
            'gemini_model'           => 'nullable|string|max:80',
            'anthropic_api_key'      => 'nullable|string|max:255',
            'gemini_api_key'         => 'nullable|string|max:255',
            'planner_contact_timing' => 'nullable|in:before,after',
            'whatsapp_default_number'=> 'nullable|string|max:25',
        ]);

        AppSetting::set('ai_planner_enabled', $request->boolean('ai_planner_enabled') ? '1' : '0');
        AppSetting::set('ai_provider', $request->input('ai_provider') === 'anthropic' ? 'anthropic' : 'gemini');
        AppSetting::set('ai_model', $request->input('ai_model') ?: 'claude-haiku-4-5-20251001');
        AppSetting::set('gemini_model', $request->input('gemini_model') ?: 'gemini-2.5-flash');
        AppSetting::set('planner_contact_timing', $request->input('planner_contact_timing') === 'after' ? 'after' : 'before');
        AppSetting::set('planner_show_raw', $request->boolean('planner_show_raw') ? '1' : '0');

        // Floating WhatsApp default (global) number — digits only (strip +, spaces, dashes).
        $wa = preg_replace('/\D+/', '', (string) $request->input('whatsapp_default_number'));
        if ($wa !== '') {
            AppSetting::set('whatsapp_default_number', $wa);
        }

        // Only overwrite a key when a new one is entered; "__clear__" removes it.
        foreach (['anthropic_api_key', 'gemini_api_key'] as $field) {
            $key = trim((string) $request->input($field));
            if ($key === '__clear__') {
                AppSetting::setSecret($field, null);
            } elseif ($key !== '') {
                AppSetting::setSecret($field, $key);
            }
        }

        return back()->with('success', 'Settings saved.');
    }

    /** AJAX: live test of the configured AI provider. */
    public function test(ProjectPlannerAI $ai)
    {
        return response()->json($ai->testConnection());
    }
}
