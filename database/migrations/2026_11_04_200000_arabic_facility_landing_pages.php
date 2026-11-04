<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Arabic facility setup landing pages, Dubai and Abu Dhabi (brief "alphahmc-arabic-facility-pages-build"; text approved
 * by Saroth and reviewed by Siraj H.M Ibrahim, 4 Nov 2026). Adds landing_pages.locale ("en" default, "ar" served at
 * /ar/facility-setup/{slug}, right to left) and the two pages, built block by block from
 * proposals/alphahmc-ar-facility-dubai.md and alphahmc-ar-facility-abu-dhabi.md in the shape of the English pages
 * they mirror (medical-centre-dubai, medical-facility-abu-dhabi). Case studies and related services are resolved by
 * slug here. Indexed, in the sitemap; their interface text is in App\Support\LandingText.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('landing_pages', 'locale')) {
            Schema::table('landing_pages', function (Blueprint $table) {
                $table->string('locale', 5)->default('en')->after('slug');
            });
        }

        $now = now();
        foreach (json_decode(self::PAGES, true) as $row) {
            if (DB::table('landing_pages')->where('slug', $row['slug'])->exists()) {
                continue;
            }
            $content = $row['content'];
            foreach ($content['sections'] as &$section) {
                if ($section['key'] === 'projects') {
                    foreach ($section['data']['items'] as &$item) {
                        $item = ['project_id' => (int) DB::table('projects')->where('slug', $item['project_slug'])->value('id')]
                            + array_diff_key($item, ['project_slug' => 1]);
                    }
                    unset($item);
                }
                if ($section['key'] === 'services') {
                    foreach ($section['data']['items'] as &$item) {
                        $item = ['target' => 'service:' . (int) DB::table('services')->where('slug', $item['target_slug'])->value('id'),
                                 'label' => $item['label']];
                    }
                    unset($item);
                }
            }
            unset($section);

            DB::table('landing_pages')->insert([
                'name' => $row['name'], 'slug' => $row['slug'], 'locale' => 'ar', 'status' => 'published', 'lead_type' => 'facility',
                'facility_type' => null, 'emirate' => $row['emirate'], 'seo_title' => $row['seo_title'],
                'meta_description' => $row['meta_description'], 'canonical' => null, 'indexable' => 1,
                'hero_image' => $row['hero_image'], 'hero_image_alt' => $row['hero_image_alt'], 'related_ids' => null,
                'plan_before_image' => $row['plan_before_image'], 'plan_before_alt' => $row['plan_before_alt'],
                'plan_after_image' => $row['plan_after_image'], 'plan_after_alt' => $row['plan_after_alt'],
                'content' => json_encode($content, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
                'reviewer' => 'siraj-hm-ibrahim', 'reviewed_at' => '2026-11-04',
                'published_at' => $now, 'created_at' => $now, 'updated_at' => $now,
            ]);
        }
    }

    public function down(): void
    {
        DB::table('landing_pages')->where('locale', 'ar')->whereIn('slug', ['dubai', 'abu-dhabi'])->delete();
        if (Schema::hasColumn('landing_pages', 'locale')) {
            Schema::table('landing_pages', fn (Blueprint $table) => $table->dropColumn('locale'));
        }
    }

    /** Built by the website terminal from the two .md files; every Arabic line checked against the output. */
    private const PAGES = <<<'JSON'
{
 "dubai": {
  "slug": "dubai",
  "name": "منشأة طبية، دبي",
  "emirate": "Dubai",
  "seo_title": "تأسيس وترخيص المنشآت الطبية في دبي",
  "meta_description": "افتح عيادتك أو مركزك الطبي في دبي مع فريق واحد للتخطيط والترخيص من هيئة الصحة بدبي والتفتيش النهائي. عرض بأتعاب ثابتة بعد مكالمة استشارية مجانية.",
  "hero_image": "uploads/landing_pages/nhs-medical-center-hero.jpg",
  "hero_image_alt": "مركز NHS الطبي، دبي. نفّذته Alpha من التخطيط حتى التشغيل.",
  "plan_before_image": null,
  "plan_after_image": null,
  "plan_before_alt": null,
  "plan_after_alt": null,
  "content": {
   "page": {
    "phone_display": "+971 4 272 4064",
    "phone_tel": "+97142724064",
    "wa_number": "971507807163",
    "wa_text": "مرحباً، أرغب في تأسيس منشأة طبية في دبي.",
    "wa_header": "راسلنا عبر واتساب",
    "dock_wa": "واتساب",
    "dock_proposal": "اطلب عرضاً",
    "wa_window_title": "",
    "wa_window_subtitle": "",
    "wa_window_message": "",
    "wa_window_send": ""
   },
   "hero": {
    "crumb_parent": "تأسيس وترخيص المنشآت",
    "crumb_current": "دبي",
    "h1_before": "افتح",
    "h1_highlight": "منشأتك الطبية",
    "h1_after": "في دبي، بتخطيط صحيح من أول مخطط.",
    "intro": "فريق واحد يخطط منشأتك حسب تخصصاتك، ويتولى ترخيصها من هيئة الصحة بدبي، ويجهّز وثائقها، ويرافقك في التفتيش النهائي.",
    "wa_button": "خطّط منشأتك عبر واتساب",
    "proposal_button": "اطلب عرضاً",
    "cta_note": "مكالمة استشارية مجانية، ثم عرض بأتعاب ثابتة وخطة مشروع بتواريخ محددة.",
    "chips_label": "",
    "list_label": "المنشآت التي نؤسسها في دبي:",
    "caption_bold": "مركز NHS الطبي، دبي.",
    "caption": "نفّذته Alpha من التخطيط حتى التشغيل.",
    "coverage": "نعمل في دبي وأبوظبي والشارقة.",
    "facilities": [
     {
      "name": "العيادات والعيادات التخصصية، ومنها العلاج الطبيعي وإعادة التأهيل"
     },
     {
      "name": "المراكز الطبية ومجمعات العيادات"
     },
     {
      "name": "مراكز الأسنان"
     },
     {
      "name": "مراكز جراحة اليوم الواحد"
     },
     {
      "name": "المختبرات الطبية ومختبرات الأسنان"
     },
     {
      "name": "مراكز التجميل"
     },
     {
      "name": "الرعاية الصحية المنزلية"
     }
    ]
   },
   "sections": [
    {
     "key": "authorities",
     "enabled": false,
     "data": {
      "label": "We license facilities with",
      "items": [
       {
        "name": "DHA"
       },
       {
        "name": "DED"
       }
      ]
     }
    },
    {
     "key": "thesis",
     "enabled": true,
     "data": {
      "quote_before": "معظم التأخير يبدأ من المخطط.",
      "quote_highlight": "خطّط منشأتك بشكل صحيح مرة واحدة",
      "quote_after": "، ويأتي الترخيص بعدها.",
      "items": [
       {
        "title": "التخطيط الضعيف",
        "text": "مساحة سريرية مهدرة بسبب توزيع غير مناسب، وتكلفة إنشاء أعلى، ومخططات تعيدها الجهة الصحية للتعديل."
       },
       {
        "title": "التخطيط السليم",
        "text": "استخدام كل متر مربع، وغرف موزعة حسب تخصصاتك، وطريق أقصر إلى الموافقة."
       },
       {
        "title": "فريق واحد",
        "text": "التخطيط والترخيص والوثائق والتفتيش النهائي مع الفريق نفسه، من الاجتماع الأول حتى صدور الترخيص."
       }
      ]
     }
    },
    {
     "key": "beyond",
     "enabled": true,
     "data": {
      "heading": "أبعد من موافقة هيئة الصحة بدبي. منشأة مهيأة للنجاح على المدى الطويل.",
      "text": "اجتياز تفتيش هيئة الصحة بدبي جزء واحد فقط من تجهيز منشأتك. نتعرف على احتياجاتك التشغيلية ونموذج عملك وخططك المستقبلية، ثم نساعدك على تخطيط منشأة جاهزة للتشغيل وتأسيسها.",
      "list_label": "فرقنا الداخلية التي تعمل على مشروعك:",
      "items": [
       {
        "title": "الهندسة",
        "text": "مخططات معمارية وهندسية تُعد وفق إرشادات الجهة الصحية."
       },
       {
        "title": "الهندسة الطبية الحيوية",
        "text": "تخطيط الأجهزة ومواصفاتها وفحوص التركيب حسب تخصصاتك."
       },
       {
        "title": "الجودة والأنظمة",
        "text": "السياسات والإجراءات والنماذج، وتجهيز فريقك للتفتيش."
       },
       {
        "title": "إدارة المشروع",
        "text": "خطة واحدة، وجهة تواصل واحدة، وتقرير بالتقدم في كل مرحلة."
       }
      ]
     }
    },
    {
     "key": "planning",
     "enabled": true,
     "data": {
      "nav_label": "التخطيط",
      "heading": "المساحة نفسها، بتخطيطين مختلفين.",
      "text": "تعتمد الغرف والمساحات والكوادر على التخصصات التي تقدمها. يطابقها فريق Alpha للهندسة الطبية مع متطلبات الجهة الصحية من البداية، قبل أي مخطط أو أعمال تجهيز. اسحب للمقارنة.",
      "tag_before": "دون تخطيط",
      "tag_after": "بتخطيط Alpha",
      "note": "رسم توضيحي لمساحة واحدة بتوزيعين مختلفين. يعتمد تصميم منشأتك على تخصصاتك وموقعها.",
      "legend_rooms": "غرف سريرية",
      "legend_lost": "مساحة مهدرة",
      "facts_heading": "ما الذي يحدد شكل منشأتك",
      "facts": [
       {
        "text": "تتبع الغرف والمساحات والكوادر التخصصات التي تختارها، ونحددها قبل بدء أي مخطط."
       },
       {
        "text": "إضافة تخصص لاحقاً أسهل عندما يترك التصميم الأول مساحة له."
       }
      ]
     }
    },
    {
     "key": "scope",
     "enabled": true,
     "data": {
      "nav_label": "خدماتنا",
      "heading": "كل ما بين الفكرة والترخيص.",
      "text": "تتعامل مع فريق واحد بدلاً من وكيل تراخيص ومصمم ومستشار وثائق.",
      "image_alt": "فريق Alpha للهندسة الطبية",
      "optional": "",
      "rows": [
       {
        "group": "التخطيط والتصميم",
        "title": "التخطيط الاستراتيجي",
        "text": "الخطة الرئيسية للمشروع، وتوزيع الخدمات حسب التخصص، وتحليل الفجوات قبل أن تلتزم."
       },
       {
        "group": "التخطيط والتصميم",
        "title": "التصميم والموافقة الهندسية",
        "text": "برنامج المساحات، ومخططات وفق إرشادات الجهة الصحية، وتقديمها ومتابعتها حتى الموافقة."
       },
       {
        "group": "التخطيط والتصميم",
        "title": "تخطيط الأجهزة الطبية",
        "text": "قوائم الأجهزة حسب التخصص، والمستهلكات، وعربة الطوارئ، ووثائق الهندسة الطبية الحيوية."
       },
       {
        "group": "الترخيص والكوادر",
        "title": "الرخصة التجارية",
        "text": "من دائرة الاقتصاد والسياحة في دبي للمنشآت في البر الرئيسي، أو من سلطة المنطقة الحرة لمدينة دبي الطبية للمنشآت في مدينة دبي الطبية، مع عقد التأسيس والموافقة الأمنية."
       },
       {
        "group": "الترخيص والكوادر",
        "title": "ترخيص المنشأة",
        "text": "التسجيل لدى هيئة الصحة بدبي حتى صدور الترخيص النهائي للمنشأة."
       },
       {
        "group": "الترخيص والكوادر",
        "title": "ترخيص الكوادر والموارد البشرية",
        "text": "ترخيص الكوادر وتفعيله ونقله، والمدير الطبي، وسياسات الموارد البشرية وملفاتها."
       },
       {
        "group": "الترخيص والكوادر",
        "title": "عقود الموردين",
        "text": "خدمات الطرف الثالث التي تطلبها الجهة الصحية، من الاختيار حتى التعاقد."
       },
       {
        "group": "التجهيز والافتتاح",
        "title": "وثائق الجودة",
        "text": "السياسات والإجراءات والنماذج والإرشادات السريرية وبرنامج تعريف الكوادر."
       },
       {
        "group": "التجهيز والافتتاح",
        "title": "التفتيش التجريبي",
        "text": "تفتيش يُجرى بالطريقة التي تتبعها الجهة الصحية، مع خطة إجراءات تصحيحية."
       },
       {
        "group": "التجهيز والافتتاح",
        "title": "التفتيش النهائي",
        "text": "نطلب موعد التفتيش، ونجهّز منشأتك له، ونحضره ونتولاه بالكامل بصفتنا مستشارك."
       }
      ]
     }
    },
    {
     "key": "turnkey",
     "enabled": true,
     "data": {
      "label": "خدمات اختيارية",
      "heading": "مشروع متكامل من البداية إلى التشغيل.",
      "text": "أضف أياً من هذه الخدمات، ويتولى فريق واحد مشروعك من أول مخطط حتى أشهر التشغيل الأولى.",
      "note": "*تطبق الشروط والأحكام.",
      "items": [
       {
        "title": "الهندسة في مرحلة الإنشاء",
        "text": "حزمة التصميم، وجدول الكميات، وتعيين المقاول، وشهادات عدم الممانعة، والإشراف على الموقع."
       },
       {
        "title": "الإنشاء والتجهيز الداخلي",
        "text": "ننفذ منشأتك ونجهزها وفق التصميم المعتمد."
       },
       {
        "title": "توظيف الكوادر",
        "text": "استقطاب فريقك السريري والمساند، جاهزاً للترخيص."
       },
       {
        "title": "الافتتاح التجريبي",
        "text": "افتتاح أولي مضبوط لاختبار سير العمل والأنظمة والفريق قبل التشغيل الكامل."
       },
       {
        "title": "ثلاثة أشهر من الدعم التشغيلي مجاناً*",
        "text": "بعد صدور الترخيص ندعمك ثلاثة أشهر دون تكلفة إضافية، لتُستكمل متطلبات ما بعد الترخيص وتسير مراحلك الأولى بسلاسة."
       }
      ]
     }
    },
    {
     "key": "plan",
     "enabled": true,
     "data": {
      "nav_label": "خطة المشروع",
      "heading": "خطة مشروعك مع Alpha.",
      "text": "خمس مراحل من الاجتماع الأول حتى الترخيص. يحدد التجهيز وإجراءات الجهة الصحية التوقيت، ونخبرك بموقع مشروعك في كل مرحلة. اختر مرحلة.",
      "foot_note": "خطة نموذجية لمنشأة صغيرة. للمنشآت الأكبر ومراكز جراحة اليوم الواحد خطة خاصة بها.",
      "foot_link": "احصل على خطة مشروعك",
      "phases": [
       {
        "title": "الاستكشاف والتخطيط الاستراتيجي",
        "text": "تقييم الموقع، وتوزيع الخدمات حسب التخصص، وتخطيط المساحة، والخطة الرئيسية للمشروع، وتحليل الفجوات مع قائمة المخاطر.",
        "start": 0,
        "end": 2
       },
       {
        "title": "الطلبات والمخططات الهندسية",
        "text": "الرخصة التجارية، وطلب ترخيص المنشأة لدى هيئة الصحة بدبي، والمخططات المعمارية والهندسية للموافقة. تبدأ أعمال التجهيز بعد اعتماد المخططات.",
        "start": 1,
        "end": 5
       },
       {
        "title": "وثائق الجودة",
        "text": "السياسات والإجراءات، وترخيص الكوادر وملفاتهم، ووثائق الأجهزة، وعقود الخدمات الإلزامية.",
        "start": 4,
        "end": 10
       },
       {
        "title": "الجاهزية للتفتيش والتفتيش التجريبي",
        "text": "تفتيش تجريبي وفق معايير الجهة الصحية، وقائمة بالفجوات مع المسؤول عن كل منها وموعد إغلاقها.",
        "start": 9,
        "end": 12
       },
       {
        "title": "التفتيش النهائي والترخيص",
        "text": "نحضر التفتيش مع فريقك ونتابع حتى صدور ترخيص المنشأة.",
        "start": 11,
        "end": 14
       }
      ]
     }
    },
    {
     "key": "others",
     "enabled": true,
     "data": {
      "heading": "افتح منشأة أخرى مع Alpha.",
      "text": "نؤسس منشآت أخرى من التخطيط حتى الترخيص النهائي.",
      "links": [
       {
        "label": "منشأة طبية في أبوظبي",
        "url": "/ar/facility-setup/abu-dhabi"
       },
       {
        "label": "منشأة طبية في الشارقة (بالإنجليزية)",
        "url": "/facility-setup/medical-facility-sharjah"
       }
      ]
     }
    },
    {
     "key": "excluded",
     "enabled": false,
     "data": {
      "heading": "What is not in our fee.",
      "text": "So you can budget with no surprises.",
      "items": [
       {
        "text": "Government fees: health authority, DED, Dataflow, Civil Defence and Municipality"
       },
       {
        "text": "Construction work and fit-out"
       },
       {
        "text": "Medical equipment, consumables and medication"
       },
       {
        "text": "IT systems and the electronic medical record"
       },
       {
        "text": "Malpractice insurance"
       },
       {
        "text": "Salaries of the staff you hire"
       }
      ]
     }
    },
    {
     "key": "projects",
     "enabled": true,
     "data": {
      "nav_label": "المشاريع",
      "heading": "منشآت نفذناها.",
      "text": "نفذنا أو قدمنا الاستشارة لأكثر من 250 منشأة صحية.",
      "see_all_label": "عرض جميع المشاريع",
      "card_link": "اقرأ دراسة الحالة (بالإنجليزية)",
      "items": [
       {
        "project_slug": "nhs-medical-center",
        "title": "مركز NHS الطبي",
        "type_label": "",
        "summary": "مجمع لفحوص التأشيرات والفحوص المهنية، نُفّذ من التخطيط حتى التشغيل.",
        "image_alt": "مركز NHS الطبي"
       },
       {
        "project_slug": "beijing-therapy",
        "title": "Beijing Well Being Medical Group",
        "type_label": "",
        "summary": "ترخيص شبكة فروع للطب الصيني التقليدي.",
        "image_alt": "Beijing Well Being Medical Group"
       }
      ]
     }
    },
    {
     "key": "rescue",
     "enabled": true,
     "data": {
      "heading": "بدأت مع جهة أخرى، أو لم تجتز منشأتك التفتيش؟",
      "text": "نتولى المشاريع غير المكتملة والمنشآت التي لم تجتز تفتيش الجهة الصحية. نراجع وضع المشروع، ونعالج الفجوات، ونتابعه حتى الترخيص."
     }
    },
    {
     "key": "faq",
     "enabled": true,
     "data": {
      "nav_label": "الأسئلة",
      "heading": "الأسئلة الشائعة",
      "items": [
       {
        "question": "كم يستغرق افتتاح منشأة طبية في دبي؟",
        "answer": "يعتمد ذلك على نوع منشأتك وأعمال التجهيز وإجراءات الجهات المختصة، ومنها موافقة الهيئة الاتحادية للرقابة النووية (FANR) إذا ركّبت أجهزة أشعة سينية. تحصل على خطة مشروع بتواريخ محددة بعد المكالمة الاستشارية المجانية."
       },
       {
        "question": "ما تكلفة تأسيس منشأة طبية في دبي؟",
        "answer": "تشمل ميزانيتك الرسوم الحكومية، وأعمال التجهيز، والأجهزة، وترخيص الكوادر، وأتعابنا. كل منشأة مختلفة، لذلك نقدم لك عرضاً بأتعاب ثابتة بعد مكالمة استشارية مجانية."
       },
       {
        "question": "هل يمكنني افتتاح منشأة طبية إذا لم أكن طبيباً؟",
        "answer": "نعم. يمكن للمستثمر أن يملك المنشأة، على أن يكون لها مدير طبي مرخّص، ونساعدك في تعيينه."
       },
       {
        "question": "هل يمكنني الافتتاح في مدينة دبي الطبية؟",
        "answer": "نعم. في مدينة دبي الطبية تصدر الرخصة التجارية من سلطة المنطقة الحرة لمدينة دبي الطبية، ويصدر الترخيص الطبي من هيئة الصحة بدبي. نتولى الاثنين."
       },
       {
        "question": "هل يمكنكم تولي مشروع غير مكتمل أو لم يجتز التفتيش؟",
        "answer": "نعم، في الحالتين. نراجع الوضع الحالي، ونعالج الفجوات، ونتابع المشروع حتى الترخيص النهائي."
       },
       {
        "question": "هل يمكنني شراء منشأة قائمة بدلاً من التأسيس؟",
        "answer": "نعم. تفحص خدمة العناية الواجبة قبل الاستحواذ الترخيص والمبنى وسجل التفتيش قبل أن توقّع."
       }
      ]
     }
    },
    {
     "key": "services",
     "enabled": true,
     "data": {
      "heading": "خدمات ذات صلة",
      "text": "",
      "items": [
       {
        "target_slug": "clinic-setup-licensing-uae",
        "label": "تأسيس وترخيص العيادات في دبي (بالإنجليزية)"
       },
       {
        "target_slug": "medical-centre-setup-licensing-dubai",
        "label": "تأسيس المراكز الطبية في دبي (بالإنجليزية)"
       },
       {
        "target_slug": "dha-audit-preparation-inspection-consultation",
        "label": "الاستعداد لتفتيش هيئة الصحة بدبي (بالإنجليزية)"
       }
      ]
     }
    },
    {
     "key": "contact",
     "enabled": true,
     "data": {
      "heading": "تحدث مع فريق المنشآت.",
      "text": "يرد فريقنا على رسائل واتساب والمكالمات بالعربية والإنجليزية.",
      "call_button": "اتصل بنا",
      "wa_button": "واتساب",
      "form_button": "أرسل استفساراً",
      "wa_label": "",
      "wa_display": "+971 50 780 7163",
      "call_label": "",
      "form_heading": "اطلب عرضاً",
      "submit_label": "أرسل الطلب",
      "success_text": "وصلنا طلبك. سيتواصل معك فريق المنشآت قريباً.",
      "fine_print": "نرد عليك خلال يوم عمل واحد."
     }
    }
   ]
  }
 },
 "abu-dhabi": {
  "slug": "abu-dhabi",
  "name": "منشأة طبية، أبوظبي",
  "emirate": "Abu Dhabi",
  "seo_title": "تأسيس وترخيص المنشآت الطبية في أبوظبي",
  "meta_description": "افتح عيادتك أو مركزك الطبي في أبوظبي مع فريق واحد للتخطيط والترخيص من دائرة الصحة والتفتيش النهائي. عرض بأتعاب ثابتة بعد مكالمة استشارية مجانية.",
  "hero_image": "uploads/landing_pages/nhs-medical-center-hero.jpg",
  "hero_image_alt": "مركز NHS الطبي، دبي. نفّذته Alpha من التخطيط حتى التشغيل.",
  "plan_before_image": null,
  "plan_after_image": null,
  "plan_before_alt": null,
  "plan_after_alt": null,
  "content": {
   "page": {
    "phone_display": "+971 4 272 4064",
    "phone_tel": "+97142724064",
    "wa_number": "971507807163",
    "wa_text": "مرحباً، أرغب في تأسيس منشأة طبية في أبوظبي.",
    "wa_header": "راسلنا عبر واتساب",
    "dock_wa": "واتساب",
    "dock_proposal": "اطلب عرضاً",
    "wa_window_title": "",
    "wa_window_subtitle": "",
    "wa_window_message": "",
    "wa_window_send": ""
   },
   "hero": {
    "crumb_parent": "تأسيس وترخيص المنشآت",
    "crumb_current": "أبوظبي",
    "h1_before": "افتح",
    "h1_highlight": "منشأتك الطبية",
    "h1_after": "في أبوظبي، بتخطيط صحيح من أول مخطط.",
    "intro": "فريق واحد يخطط منشأتك حسب تخصصاتك، ويتولى ترخيصها من دائرة الصحة في أبوظبي، ويجهّز وثائقها، ويرافقك في التفتيش النهائي.",
    "wa_button": "خطّط منشأتك عبر واتساب",
    "proposal_button": "اطلب عرضاً",
    "cta_note": "مكالمة استشارية مجانية، ثم عرض بأتعاب ثابتة وخطة مشروع بتواريخ محددة.",
    "chips_label": "",
    "list_label": "المنشآت التي نؤسسها في أبوظبي:",
    "caption_bold": "مركز NHS الطبي، دبي.",
    "caption": "نفّذته Alpha من التخطيط حتى التشغيل.",
    "coverage": "نعمل في أبوظبي ودبي والشارقة.",
    "facilities": [
     {
      "name": "العيادات"
     },
     {
      "name": "المراكز الطبية"
     },
     {
      "name": "عيادات الأسنان"
     }
    ]
   },
   "sections": [
    {
     "key": "authorities",
     "enabled": false,
     "data": {
      "label": "We license facilities with",
      "items": [
       {
        "name": "DOH"
       },
       {
        "name": "ADDED"
       }
      ]
     }
    },
    {
     "key": "thesis",
     "enabled": true,
     "data": {
      "quote_before": "معظم التأخير يبدأ من المخطط.",
      "quote_highlight": "خطّط منشأتك بشكل صحيح مرة واحدة",
      "quote_after": "، ويأتي الترخيص بعدها.",
      "items": [
       {
        "title": "التخطيط الضعيف",
        "text": "مساحة سريرية مهدرة بسبب توزيع غير مناسب، وتكلفة إنشاء أعلى، ومخططات تعيدها الجهة الصحية للتعديل."
       },
       {
        "title": "التخطيط السليم",
        "text": "استخدام كل متر مربع، وغرف موزعة حسب تخصصاتك، وطريق أقصر إلى الموافقة."
       },
       {
        "title": "فريق واحد",
        "text": "التخطيط والترخيص والوثائق والتفتيش النهائي مع الفريق نفسه، من الاجتماع الأول حتى صدور الترخيص."
       }
      ]
     }
    },
    {
     "key": "beyond",
     "enabled": true,
     "data": {
      "heading": "أبعد من موافقة دائرة الصحة في أبوظبي. منشأة مهيأة للنجاح على المدى الطويل.",
      "text": "اجتياز تفتيش دائرة الصحة في أبوظبي جزء واحد فقط من تجهيز منشأتك. نتعرف على احتياجاتك التشغيلية ونموذج عملك وخططك المستقبلية، ثم نساعدك على تخطيط منشأة جاهزة للتشغيل وتأسيسها.",
      "list_label": "فرقنا الداخلية التي تعمل على مشروعك:",
      "items": [
       {
        "title": "الهندسة",
        "text": "مخططات معمارية وهندسية تُعد وفق إرشادات الجهة الصحية."
       },
       {
        "title": "الهندسة الطبية الحيوية",
        "text": "تخطيط الأجهزة ومواصفاتها وفحوص التركيب حسب تخصصاتك."
       },
       {
        "title": "الجودة والأنظمة",
        "text": "السياسات والإجراءات والنماذج، وتجهيز فريقك للتفتيش."
       },
       {
        "title": "إدارة المشروع",
        "text": "خطة واحدة، وجهة تواصل واحدة، وتقرير بالتقدم في كل مرحلة."
       }
      ]
     }
    },
    {
     "key": "planning",
     "enabled": true,
     "data": {
      "nav_label": "التخطيط",
      "heading": "المساحة نفسها، بتخطيطين مختلفين.",
      "text": "تعتمد الغرف والمساحات والكوادر على التخصصات التي تقدمها. يطابقها فريق Alpha للهندسة الطبية مع متطلبات الجهة الصحية من البداية، قبل أي مخطط أو أعمال تجهيز. اسحب للمقارنة.",
      "tag_before": "دون تخطيط",
      "tag_after": "بتخطيط Alpha",
      "note": "رسم توضيحي لمساحة واحدة بتوزيعين مختلفين. يعتمد تصميم منشأتك على تخصصاتك وموقعها.",
      "legend_rooms": "غرف سريرية",
      "legend_lost": "مساحة مهدرة",
      "facts_heading": "ما الذي يحدد شكل منشأتك",
      "facts": [
       {
        "text": "تتبع الغرف والمساحات والكوادر التخصصات التي تختارها، ونحددها قبل بدء أي مخطط."
       },
       {
        "text": "إضافة تخصص لاحقاً أسهل عندما يترك التصميم الأول مساحة له."
       }
      ]
     }
    },
    {
     "key": "scope",
     "enabled": true,
     "data": {
      "nav_label": "خدماتنا",
      "heading": "كل ما بين الفكرة والترخيص.",
      "text": "تتعامل مع فريق واحد بدلاً من وكيل تراخيص ومصمم ومستشار وثائق.",
      "image_alt": "فريق Alpha للهندسة الطبية",
      "optional": "",
      "rows": [
       {
        "group": "التخطيط والتصميم",
        "title": "التخطيط الاستراتيجي",
        "text": "الخطة الرئيسية للمشروع، وتوزيع الخدمات حسب التخصص، وتحليل الفجوات قبل أن تلتزم."
       },
       {
        "group": "التخطيط والتصميم",
        "title": "التصميم والموافقة الهندسية",
        "text": "برنامج المساحات، ومخططات وفق إرشادات الجهة الصحية، وتقديمها ومتابعتها حتى الموافقة."
       },
       {
        "group": "التخطيط والتصميم",
        "title": "تخطيط الأجهزة الطبية",
        "text": "قوائم الأجهزة حسب التخصص، والمستهلكات، وعربة الطوارئ، ووثائق الهندسة الطبية الحيوية."
       },
       {
        "group": "الترخيص والكوادر",
        "title": "الرخصة التجارية",
        "text": "من دائرة التنمية الاقتصادية في أبوظبي أو من منطقة حرة، مع عقد التأسيس والموافقة الأمنية."
       },
       {
        "group": "الترخيص والكوادر",
        "title": "ترخيص المنشأة",
        "text": "التسجيل لدى دائرة الصحة في أبوظبي حتى صدور الترخيص النهائي للمنشأة."
       },
       {
        "group": "الترخيص والكوادر",
        "title": "ترخيص الكوادر والموارد البشرية",
        "text": "ترخيص الكوادر وتفعيله ونقله، والمدير الطبي، وسياسات الموارد البشرية وملفاتها."
       },
       {
        "group": "الترخيص والكوادر",
        "title": "عقود الموردين",
        "text": "خدمات الطرف الثالث التي تطلبها الجهة الصحية، من الاختيار حتى التعاقد."
       },
       {
        "group": "التجهيز والافتتاح",
        "title": "وثائق الجودة",
        "text": "السياسات والإجراءات والنماذج والإرشادات السريرية وبرنامج تعريف الكوادر."
       },
       {
        "group": "التجهيز والافتتاح",
        "title": "التفتيش التجريبي",
        "text": "تفتيش يُجرى بالطريقة التي تتبعها الجهة الصحية، مع خطة إجراءات تصحيحية."
       },
       {
        "group": "التجهيز والافتتاح",
        "title": "التفتيش النهائي",
        "text": "نطلب موعد التفتيش، ونجهّز منشأتك له، ونحضره ونتولاه بالكامل بصفتنا مستشارك."
       }
      ]
     }
    },
    {
     "key": "turnkey",
     "enabled": true,
     "data": {
      "label": "خدمات اختيارية",
      "heading": "مشروع متكامل من البداية إلى التشغيل.",
      "text": "أضف أياً من هذه الخدمات، ويتولى فريق واحد مشروعك من أول مخطط حتى أشهر التشغيل الأولى.",
      "note": "*تطبق الشروط والأحكام.",
      "items": [
       {
        "title": "الهندسة في مرحلة الإنشاء",
        "text": "حزمة التصميم، وجدول الكميات، وتعيين المقاول، وشهادات عدم الممانعة، والإشراف على الموقع."
       },
       {
        "title": "الإنشاء والتجهيز الداخلي",
        "text": "ننفذ منشأتك ونجهزها وفق التصميم المعتمد."
       },
       {
        "title": "توظيف الكوادر",
        "text": "استقطاب فريقك السريري والمساند، جاهزاً للترخيص."
       },
       {
        "title": "الافتتاح التجريبي",
        "text": "افتتاح أولي مضبوط لاختبار سير العمل والأنظمة والفريق قبل التشغيل الكامل."
       },
       {
        "title": "ثلاثة أشهر من الدعم التشغيلي مجاناً*",
        "text": "بعد صدور الترخيص ندعمك ثلاثة أشهر دون تكلفة إضافية، لتُستكمل متطلبات ما بعد الترخيص وتسير مراحلك الأولى بسلاسة."
       }
      ]
     }
    },
    {
     "key": "plan",
     "enabled": true,
     "data": {
      "nav_label": "خطة المشروع",
      "heading": "خطة مشروعك مع Alpha.",
      "text": "خمس مراحل من الاجتماع الأول حتى الترخيص. يحدد التجهيز وإجراءات الجهة الصحية التوقيت، ونخبرك بموقع مشروعك في كل مرحلة. اختر مرحلة.",
      "foot_note": "خطة نموذجية لمنشأة صغيرة. للمنشآت الأكبر ومراكز جراحة اليوم الواحد خطة خاصة بها.",
      "foot_link": "احصل على خطة مشروعك",
      "phases": [
       {
        "title": "الاستكشاف والتخطيط الاستراتيجي",
        "text": "تقييم الموقع، وتوزيع الخدمات حسب التخصص، وتخطيط المساحة، والخطة الرئيسية للمشروع، وتحليل الفجوات مع قائمة المخاطر.",
        "start": 0,
        "end": 2
       },
       {
        "title": "الطلبات والمخططات الهندسية",
        "text": "الرخصة التجارية، وطلب ترخيص المنشأة لدى دائرة الصحة في أبوظبي، والمخططات المعمارية والهندسية للموافقة. تبدأ أعمال التجهيز بعد اعتماد المخططات.",
        "start": 1,
        "end": 5
       },
       {
        "title": "وثائق الجودة",
        "text": "السياسات والإجراءات، وترخيص الكوادر وملفاتهم، ووثائق الأجهزة، وعقود الخدمات الإلزامية.",
        "start": 4,
        "end": 10
       },
       {
        "title": "الجاهزية للتفتيش والتفتيش التجريبي",
        "text": "تفتيش تجريبي وفق معايير الجهة الصحية، وقائمة بالفجوات مع المسؤول عن كل منها وموعد إغلاقها.",
        "start": 9,
        "end": 12
       },
       {
        "title": "التفتيش النهائي والترخيص",
        "text": "نحضر التفتيش مع فريقك ونتابع حتى صدور ترخيص المنشأة.",
        "start": 11,
        "end": 14
       }
      ]
     }
    },
    {
     "key": "others",
     "enabled": true,
     "data": {
      "heading": "افتح منشأة أخرى مع Alpha.",
      "text": "نؤسس منشآت أخرى من التخطيط حتى الترخيص النهائي.",
      "links": [
       {
        "label": "منشأة طبية في دبي",
        "url": "/ar/facility-setup/dubai"
       },
       {
        "label": "منشأة طبية في الشارقة (بالإنجليزية)",
        "url": "/facility-setup/medical-facility-sharjah"
       }
      ]
     }
    },
    {
     "key": "excluded",
     "enabled": false,
     "data": {
      "heading": "What is not in our fee.",
      "text": "So you can budget with no surprises.",
      "items": [
       {
        "text": "Government fees: health authority, ADDED, Dataflow, Civil Defence and Municipality"
       },
       {
        "text": "Construction work and fit-out"
       },
       {
        "text": "Medical equipment, consumables and medication"
       },
       {
        "text": "IT systems and the electronic medical record"
       },
       {
        "text": "Malpractice insurance"
       },
       {
        "text": "Salaries of the staff you hire"
       }
      ]
     }
    },
    {
     "key": "projects",
     "enabled": true,
     "data": {
      "nav_label": "المشاريع",
      "heading": "منشآت نفذناها.",
      "text": "نفذنا أو قدمنا الاستشارة لأكثر من 250 منشأة صحية.",
      "see_all_label": "عرض جميع المشاريع",
      "card_link": "اقرأ دراسة الحالة (بالإنجليزية)",
      "items": [
       {
        "project_slug": "cryo-health-clinic",
        "title": "CRYO Health Clinic",
        "type_label": "",
        "summary": "تأسيس مركز جديد للعلاج بالتبريد والعافية.",
        "image_alt": "CRYO Health Clinic"
       },
       {
        "project_slug": "mabc-abilities-development",
        "title": "MABC Abilities Development",
        "type_label": "",
        "summary": "تأسيس مركز علاجي للأطفال.",
        "image_alt": "MABC Abilities Development"
       }
      ]
     }
    },
    {
     "key": "rescue",
     "enabled": true,
     "data": {
      "heading": "بدأت مع جهة أخرى، أو لم تجتز منشأتك التفتيش؟",
      "text": "نتولى المشاريع غير المكتملة والمنشآت التي لم تجتز تفتيش الجهة الصحية. نراجع وضع المشروع، ونعالج الفجوات، ونتابعه حتى الترخيص."
     }
    },
    {
     "key": "faq",
     "enabled": true,
     "data": {
      "nav_label": "الأسئلة",
      "heading": "الأسئلة الشائعة",
      "items": [
       {
        "question": "كم يستغرق افتتاح منشأة طبية في أبوظبي؟",
        "answer": "يعتمد ذلك على نوع منشأتك وأعمال التجهيز وإجراءات الجهات المختصة، ومنها موافقة الهيئة الاتحادية للرقابة النووية (FANR) إذا ركّبت أجهزة أشعة سينية. تحصل على خطة مشروع بتواريخ محددة بعد المكالمة الاستشارية المجانية."
       },
       {
        "question": "ما تكلفة تأسيس منشأة طبية في أبوظبي؟",
        "answer": "تشمل ميزانيتك الرسوم الحكومية، وأعمال التجهيز، والأجهزة، وترخيص الكوادر، وأتعابنا. كل منشأة مختلفة، لذلك نقدم لك عرضاً بأتعاب ثابتة بعد مكالمة استشارية مجانية."
       },
       {
        "question": "هل يمكنني افتتاح منشأة طبية إذا لم أكن طبيباً؟",
        "answer": "نعم. يمكن للمستثمر أن يملك المنشأة، على أن يكون لها مدير طبي مرخّص، ونساعدك في تعيينه."
       },
       {
        "question": "هل يمكنكم تولي مشروع غير مكتمل أو لم يجتز التفتيش؟",
        "answer": "نعم، في الحالتين. نراجع الوضع الحالي، ونعالج الفجوات، ونتابع المشروع حتى الترخيص النهائي."
       },
       {
        "question": "هل يمكنني شراء منشأة قائمة بدلاً من التأسيس؟",
        "answer": "نعم. تفحص خدمة العناية الواجبة قبل الاستحواذ الترخيص والمبنى وسجل التفتيش قبل أن توقّع."
       }
      ]
     }
    },
    {
     "key": "services",
     "enabled": true,
     "data": {
      "heading": "خدمات ذات صلة",
      "text": "",
      "items": [
       {
        "target_slug": "doh-healthcare-facility-licensing-abu-dhabi",
        "label": "ترخيص المنشآت الصحية في أبوظبي (بالإنجليزية)"
       },
       {
        "target_slug": "doh-inspection-preparation-services",
        "label": "الاستعداد لتفتيش دائرة الصحة (بالإنجليزية)"
       },
       {
        "target_slug": "adhics-audit-consultation",
        "label": "تدقيق وتطبيق معيار ADHICS (بالإنجليزية)"
       }
      ]
     }
    },
    {
     "key": "contact",
     "enabled": true,
     "data": {
      "heading": "تحدث مع فريق المنشآت.",
      "text": "يرد فريقنا على رسائل واتساب والمكالمات بالعربية والإنجليزية.",
      "call_button": "اتصل بنا",
      "wa_button": "واتساب",
      "form_button": "أرسل استفساراً",
      "wa_label": "",
      "wa_display": "+971 50 780 7163",
      "call_label": "",
      "form_heading": "اطلب عرضاً",
      "submit_label": "أرسل الطلب",
      "success_text": "وصلنا طلبك. سيتواصل معك فريق المنشآت قريباً.",
      "fine_print": "نرد عليك خلال يوم عمل واحد."
     }
    }
   ]
  }
 }
}
JSON;
};
