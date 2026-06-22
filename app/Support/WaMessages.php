<?php

namespace App\Support;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;

/**
 * Varied WhatsApp message templates + file names so consecutive messages are
 * never identical (reduces spam heuristics on unofficial WhatsApp gateways).
 */
class WaMessages
{
    public static function received(string $name, string $card): string
    {
        return Arr::random([
            "أهلاً {$name} 👋\nاستلمنا قطعتك في Aura Tac بكرت رقم {$card}. مرفق كرت العمل، وهنطمّنك أول ما تجهز.",
            "عميلنا {$name}،\nتم تسجيل قطعتك (كرت {$card}) بقسم صيانة Aura Tac. الكرت مرفق وفيه تفاصيل التكلفة. نتشرّف بخدمتك.",
            "مرحباً {$name} 🙏\nقطعتك دخلت الصيانة لدى Aura Tac برقم {$card}. تجد كرت العمل مرفقاً، وسنراسلك عند الجاهزية.",
            "{$name}، تم استلام قطعتك بنجاح ✅\nرقم الكرت: {$card} — Aura Tac. مرفق الكرت، نشكر ثقتك.",
        ]);
    }

    public static function ready(string $name, string $card): string
    {
        return Arr::random([
            "بشرى سارة {$name} 🎉 قطعتك (كرت {$card}) جاهزة للاستلام من Aura Tac. بانتظارك!",
            "عميلنا {$name}، قطعتك جاهزة ✅ تقدر تستلمها من Aura Tac — كرت رقم {$card}.",
            "{$name}، خلّصنا صيانة قطعتك (كرت {$card}) وهي جاهزة للاستلام. نسعد بزيارتك في Aura Tac.",
            "أخبار حلوة {$name} 🌟 قطعتك بكرت {$card} جاهزة. تقدر تمر تستلمها من Aura Tac.",
        ]);
    }

    public static function delayed(string $name, string $card): string
    {
        return Arr::random([
            "عميلنا {$name}، قطعتك (كرت {$card}) محتاجة وقت إضافي بسيط لضمان أعلى جودة، وهنبلغك فور جاهزيتها. نشكر تفهّمك 🙏",
            "{$name}، نحرص على جودة عملنا، لذلك تحتاج قطعتك (كرت {$card}) مراجعة إضافية. سنراسلك عند الانتهاء. نعتذر عن التأخير.",
            "عذراً للتأخير {$name} — قطعتك (كرت {$card}) تمر بفحص جودة إضافي لضمان أفضل نتيجة، وسنخبرك حال جاهزيتها.",
        ]);
    }

    public static function delivered(string $name, string $card): string
    {
        return Arr::random([
            "تم تسليم قطعتك بنجاح {$name} ✅ شكراً لثقتك في Aura Tac (كرت {$card}). في خدمتك دائماً.",
            "{$name}، تم تسليم قطعتك (كرت {$card}). سعدنا بخدمتك في Aura Tac 🌟",
            "شكراً {$name} 🙏 استلمت قطعتك (كرت {$card}) من Aura Tac. نتطلّع لخدمتك مجدداً.",
        ]);
    }

    public static function fileName(string $card): string
    {
        return 'AuraTac-' . $card . '-' . now()->format('md') . strtoupper(Str::random(3)) . '.pdf';
    }
}
