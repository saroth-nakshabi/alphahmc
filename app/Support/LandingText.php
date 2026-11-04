<?php

namespace App\Support;

/**
 * Interface text of the facility landing pages that is not page copy (buttons, labels, form, footer), per language.
 * English is the text the templates printed before the Arabic pages; Arabic is the SEO agent's list, approved with
 * the Arabic pages (brief "alphahmc-arabic-facility-pages-build", 4 Nov 2026). Page copy stays in the page's content.
 */
class LandingText
{
    private const TEXT = [
        'en' => [
            'contact'            => 'Contact',
            'menu_call'          => 'Call',
            'menu_wa'            => 'WhatsApp',
            'menu_wa_sub'        => 'Chat with the facility team',
            'nav_aria'           => 'On this page',
            'home_aria'          => 'Alpha Health Group home',
            'dock_wa'            => 'WhatsApp',
            'dock_proposal'      => 'Request a proposal',
            'facility_types_aria'=> 'Facility types',
            'drawing_aria'       => 'Illustration of an unplanned layout: two treatment rooms, a long corridor and an oversized waiting area',
            'compare_aria'       => 'Compare the unplanned and the planned layout',
            'phase'              => 'Phase :n',
            'see_projects'       => 'See our projects',
            'proposal'           => 'Request a proposal',
            'call'               => 'Call',
            'whatsapp'           => 'WhatsApp',
            'send_inquiry'       => 'Send an inquiry',
            'f_name'             => 'Full name',
            'f_phone'            => 'Mobile with country code',
            'f_email'            => 'Email',
            'f_type'             => 'Facility',
            'f_type_choose'      => 'Choose a facility',
            'f_emirate'          => 'Emirate',
            'f_message'          => 'Message',
            'f_consent_before'   => 'I agree to be contacted by Alpha Health Group about this request and accept the',
            'f_consent_link'     => 'privacy policy',
            'f_consent_after'    => '.',
            'err_name'           => 'Enter your full name.',
            'err_phone'          => 'Enter a mobile number with country code, for example +971 50 000 0000.',
            'err_email'          => 'Enter a valid email address, or leave it empty.',
            'err_consent'        => 'Tick the box so the facility team can contact you.',
            'sending'            => 'Sending',
            'failure'            => 'We could not send your request. Please try again, or message us on WhatsApp.',
            'failure_network'    => 'We could not send your request. Check your connection and try again, or message us on WhatsApp.',
            'copied'             => 'Copied',
            'selected'           => 'Selected',
            'policies_aria'      => 'Policies',
            'privacy'            => 'Privacy Policy',
            'terms'              => 'Terms of Service',
            'cookies'            => 'Cookie Policy',
            'gdpr'               => 'GDPR & Data Protection',
            'connect'            => 'Connect With Us:',
            'legal'              => 'Alpha Health Group provides healthcare management consultancy, accreditation support, and advisory services to healthcare organisations across the GCC and Middle East. All consultancy services are provided for organisational and management purposes only and do not constitute clinical medical advice or treatment.',
            'rights'             => 'All rights reserved.',
        ],
        'ar' => [
            'contact'            => 'تواصل معنا',
            'menu_call'          => 'اتصل بنا',
            'menu_wa'            => 'راسلنا عبر واتساب',
            'menu_wa_sub'        => 'تحدث مع فريق المنشآت',
            'nav_aria'           => 'في هذه الصفحة',
            'home_aria'          => 'الصفحة الرئيسية لمجموعة Alpha Health Group',
            'dock_wa'            => 'واتساب',
            'dock_proposal'      => 'اطلب عرضاً',
            'facility_types_aria'=> 'أنواع المنشآت',
            'drawing_aria'       => 'رسم توضيحي لتوزيع دون تخطيط: غرفتا علاج، وممر طويل، ومنطقة انتظار أكبر من الحاجة',
            'compare_aria'       => 'قارن بين التوزيع دون تخطيط والتوزيع المخطط',
            'phase'              => 'المرحلة :n',
            'see_projects'       => 'عرض جميع المشاريع',
            'proposal'           => 'اطلب عرضاً',
            'call'               => 'اتصل بنا',
            'whatsapp'           => 'واتساب',
            'send_inquiry'       => 'أرسل استفساراً',
            'f_name'             => 'الاسم الكامل',
            'f_phone'            => 'رقم الهاتف',
            'f_email'            => 'البريد الإلكتروني',
            'f_type'             => 'نوع المنشأة',
            'f_type_choose'      => 'اختر نوع المنشأة',
            'f_emirate'          => 'الإمارة',
            'f_message'          => 'أخبرنا عن مشروعك',
            'f_consent_before'   => 'أوافق على أن تتواصل معي Alpha Health Group بشأن هذا الطلب، وأقبل',
            'f_consent_link'     => 'سياسة الخصوصية',
            'f_consent_after'    => ' (بالإنجليزية).',
            'err_name'           => 'أدخل اسمك الكامل.',
            'err_phone'          => 'أدخل رقم هاتف صحيحاً مع رمز الدولة.',
            'err_email'          => 'أدخل بريداً إلكترونياً صحيحاً.',
            'err_consent'        => 'ضع علامة في المربع ليتمكن فريق المنشآت من التواصل معك.',
            'sending'            => 'جارٍ الإرسال',
            'failure'            => 'تعذّر إرسال الطلب. حاول مرة أخرى أو راسلنا عبر واتساب.',
            'failure_network'    => 'تعذّر إرسال الطلب. حاول مرة أخرى أو راسلنا عبر واتساب.',
            'copied'             => 'تم النسخ',
            'selected'           => 'تم التحديد',
            'policies_aria'      => 'السياسات',
            'privacy'            => 'سياسة الخصوصية',
            'terms'              => 'شروط الخدمة',
            'cookies'            => 'سياسة ملفات تعريف الارتباط',
            'gdpr'               => 'حماية البيانات واللائحة العامة لحماية البيانات (GDPR)',
            'connect'            => 'تابعنا:',
            'legal'              => 'تقدم Alpha Health Group خدمات الاستشارات في الإدارة الصحية ودعم الاعتماد والخدمات الاستشارية للمؤسسات الصحية في دول الخليج والشرق الأوسط. تُقدَّم جميع خدماتنا الاستشارية لأغراض تنظيمية وإدارية فقط، ولا تُعد نصيحة طبية سريرية أو علاجاً.',
            'rights'             => 'جميع الحقوق محفوظة.',
            'required'           => 'حقل مطلوب',
            'reviewed_by'        => 'راجع المحتوى:',
            'reviewer_title'     => 'الرئيس التنفيذي وخبير الاستثمار في القطاع الصحي',
            'updated'            => 'آخر تحديث:',
            'home'               => 'الرئيسية',
            'crumb_parent'       => 'تأسيس وترخيص المنشآت',
        ],
    ];

    /** Labels of the form's stored English option values, per language (the CRM keeps the English value). */
    private const OPTIONS = [
        'ar' => [
            'Dental centre' => 'مركز أسنان', 'Medical centre' => 'مركز طبي', 'Aesthetic centre' => 'مركز تجميل',
            'Home healthcare' => 'رعاية صحية منزلية', 'Clinical laboratory' => 'مختبر طبي', 'Dental laboratory' => 'مختبر أسنان',
            'Day surgery centre' => 'مركز جراحة اليوم الواحد', 'Other facility' => 'منشأة أخرى',
            'Dubai' => 'دبي', 'Abu Dhabi' => 'أبوظبي', 'Sharjah' => 'الشارقة', 'Northern Emirates' => 'الإمارات الشمالية',
            'Not decided' => 'لم أحدد بعد',
            // Floor drawing labels (the slider labels in the Arabic page text).
            'Waiting' => 'الانتظار', 'Reception' => 'الاستقبال', 'Corridor with no clinical use' => 'ممر بلا استخدام سريري',
            'Treatment' => 'غرفة علاج', 'Store placed in clinical space' => 'مخزن داخل المساحة السريرية', 'Staff' => 'الطاقم',
            'Sterilisation, far from treatment' => 'التعقيم بعيد عن غرف العلاج', 'Short patient corridor' => 'ممر قصير للمرضى',
            'Sterilisation, central' => 'التعقيم في موقع مركزي', 'X-ray, shielded' => 'غرفة أشعة محمية',
            'Staff and store' => 'الطاقم والمخزن',
        ],
    ];

    private const MONTHS_AR = ['يناير', 'فبراير', 'مارس', 'أبريل', 'مايو', 'يونيو', 'يوليو', 'أغسطس', 'سبتمبر', 'أكتوبر', 'نوفمبر', 'ديسمبر'];

    public static function locales(): array
    {
        return array_keys(self::TEXT);
    }

    public static function get(?string $locale, string $key, array $replace = []): string
    {
        $text = self::TEXT[$locale][$key] ?? self::TEXT['en'][$key] ?? $key;
        foreach ($replace as $name => $value) {
            $text = str_replace(':' . $name, (string) $value, $text);
        }

        return $text;
    }

    public static function option(?string $locale, string $value): string
    {
        return self::OPTIONS[$locale][$value] ?? $value;
    }

    /** Every string the landing script shows, for window._lpText. */
    public static function script(?string $locale): array
    {
        $keys = ['err_name', 'err_phone', 'err_email', 'err_consent', 'sending', 'failure', 'failure_network', 'copied', 'selected'];

        return array_combine($keys, array_map(fn ($k) => self::get($locale, $k), $keys));
    }

    /** "4 November 2026", or "4 نوفمبر 2026" in Arabic (Latin digits, as the theme formats dates). */
    public static function date(?string $locale, \DateTimeInterface $date): string
    {
        if ($locale === 'ar') {
            return $date->format('j') . ' ' . self::MONTHS_AR[(int) $date->format('n') - 1] . ' ' . $date->format('Y');
        }

        return $date->format('j F Y');
    }
}
