<?php

namespace App\Support;

use App\Models\AppSetting;
use App\Models\Category;
use App\Models\Service;
use App\Models\ServiceGroup;

/**
 * Resolves the right WhatsApp number + page context for the current request.
 *
 * On a Service/Category/ServiceGroup detail page, prefers that page's linked
 * agent's WhatsApp number (falling back to the legacy inq_officer_phone
 * column), and builds a page-aware message. Everywhere else, falls back to
 * the site-wide AppSetting('whatsapp_default_number').
 *
 * Ported from the original inline logic in front/partials/ai-assistant.blade.php
 * so the floating WhatsApp button and the dwell-popup can share it instead of
 * duplicating the leader-resolution block a second/third time.
 */
class WaContext
{
    private const LEADER_ROUTES = [
        'front.service'          => Service::class,
        'front.service-category' => Category::class,
        'service-packages'       => ServiceGroup::class,
    ];

    /**
     * @param  mixed $fallbackLeader  Optional model instance to use as the "leader"
     *                                when route-parameter/slug resolution finds none
     *                                (mirrors the blade-local `$service ?? $category ?? ...` fallback).
     */
    public static function resolve($fallbackLeader = null): array
    {
        $defaultPhone = AppSetting::get('whatsapp_default_number', '97158128418');
        $targetPhone  = $defaultPhone;
        $officerName  = 'our experts';

        $route            = request()->route();
        $currentRouteName = $route ? $route->getName() : null;
        $leader           = null;

        if ($currentRouteName && isset(self::LEADER_ROUTES[$currentRouteName])) {
            $modelClass = self::LEADER_ROUTES[$currentRouteName];

            foreach ($route->parameters() as $param) {
                if ($param instanceof $modelClass) {
                    $leader = $param;
                    break;
                }
            }

            if (!$leader) {
                $slug = $route->parameter('slug');
                if (is_string($slug) && $slug !== '') {
                    $leader = $modelClass::where('slug', $slug)->first();
                }
            }

            if (!$leader) {
                $leader = $fallbackLeader;
            }
        }

        if ($leader) {
            $_agent = $leader->agent ?? null;
            if ($_agent && !empty(trim((string) $_agent->whatsapp))) {
                $targetPhone = trim($_agent->whatsapp);
                if (!empty($_agent->title)) {
                    $officerName = $_agent->title;
                }
            } elseif (!empty(trim((string) $leader->inq_officer_phone))) {
                $targetPhone = trim($leader->inq_officer_phone);
                if (!empty($leader->inq_officer_name)) {
                    $officerName = $leader->inq_officer_name;
                }
            }
        }

        $targetPhone = preg_replace('/\D+/', '', (string) $targetPhone);
        if ($targetPhone === '') {
            $targetPhone = preg_replace('/\D+/', '', (string) $defaultPhone) ?: '97158128418';
        }

        return [
            'phone'       => $targetPhone,
            'officerName' => $officerName,
            'pageName'    => $leader ? trim(strip_tags((string) ($leader->name ?? ''))) : '',
            'pageUrl'     => url()->current(),
        ];
    }

    /**
     * Build a wa.me link for the current request, defaulting to a page-aware
     * message when a leader entity was resolved.
     */
    public static function link(?string $customText = null, $fallbackLeader = null): string
    {
        $ctx = self::resolve($fallbackLeader);

        $text = $customText ?? ($ctx['pageName'] !== ''
            ? "Hi, I'd like to enquire about \"{$ctx['pageName']}\" — {$ctx['pageUrl']}"
            : "Hi, I'd like to enquire about Alpha Health Group's services.");

        return 'https://wa.me/' . $ctx['phone'] . '?text=' . rawurlencode($text);
    }
}
