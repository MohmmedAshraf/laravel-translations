<?php

namespace Outhebox\TranslationsUI\Database\Seeders;

use Illuminate\Database\Seeder;
use Outhebox\TranslationsUI\Models\Language;

class LanguagesTableSeeder extends Seeder
{
    public function run(): void
    {
        $languages = [
            [
                'code' => 'af',
                'name' => 'Afrikaans',
                'rtl' => false,
            ],
            [
                'code' => 'sq',
                'name' => 'Albanian',
                'rtl' => false,
            ],
            [
                'code' => 'am',
                'name' => 'Amharic',
                'rtl' => false,
            ],
            [
                'code' => 'ar',
                'name' => 'Arabic',
                'rtl' => true,
            ],
            [
                'code' => 'hy',
                'name' => 'Armenian',
                'rtl' => false,
            ],
            [
                'code' => 'as',
                'name' => 'Assamese',
                'rtl' => false,
            ],
            [
                'code' => 'ay',
                'name' => 'Aymara',
                'rtl' => false,
            ],
            [
                'code' => 'az',
                'name' => 'Azerbaijani',
                'rtl' => false,
            ],
            [
                'code' => 'bm',
                'name' => 'Bambara',
                'rtl' => false,
            ],
            [
                'code' => 'eu',
                'name' => 'Basque',
                'rtl' => false,
            ],
            [
                'code' => 'be',
                'name' => 'Belarusian',
                'rtl' => false,
            ],
            [
                'code' => 'bn',
                'name' => 'Bengali',
                'rtl' => false,
            ],
            [
                'code' => 'bho',
                'name' => 'Bhojpuri',
                'rtl' => false,
            ],
            [
                'code' => 'bs',
                'name' => 'Bosnian',
                'rtl' => false,
            ],
            [
                'code' => 'bg',
                'name' => 'Bulgarian',
                'rtl' => false,
            ],
            [
                'code' => 'ca',
                'name' => 'Catalan',
                'rtl' => false,
            ],
            [
                'code' => 'ceb',
                'name' => 'Cebuano',
                'rtl' => false,
            ],
            [
                'code' => 'zh',
                'name' => 'Chinese',
                'rtl' => false,
            ],
            [
                'code' => 'zh-TW',
                'name' => 'Chinese (Traditional)',
                'rtl' => false,
            ],
            [
                'code' => 'co',
                'name' => 'Corsican',
                'rtl' => false,
            ],
            [
                'code' => 'hr',
                'name' => 'Croatian',
                'rtl' => false,
            ],
            [
                'code' => 'cs',
                'name' => 'Czech',
                'rtl' => false,
            ],
            [
                'code' => 'da',
                'name' => 'Danish',
                'rtl' => false,
            ],
            [
                'code' => 'dv',
                'name' => 'Divehi',
                'rtl' => false,
            ],
            [
                'code' => 'nl',
                'name' => 'Dutch',
                'rtl' => false,
            ],
            [
                'code' => 'en',
                'name' => 'English',
                'rtl' => false,
            ],
            [
                'code' => 'et',
                'name' => 'Estonian',
                'rtl' => false,
            ],
            [
                'code' => 'fil',
                'name' => 'Filipino',
                'rtl' => false,
            ],
            [
                'code' => 'fi',
                'name' => 'Finnish',
                'rtl' => false,
            ],
            [
                'code' => 'fr',
                'name' => 'French',
                'rtl' => false,
            ],
            [
                'code' => 'gl',
                'name' => 'Galician',
                'rtl' => false,
            ],
            [
                'code' => 'ka',
                'name' => 'Georgian',
                'rtl' => false,
            ],
            [
                'code' => 'de',
                'name' => 'German',
                'rtl' => false,
            ],
            [
                'code' => 'el',
                'name' => 'Greek',
                'rtl' => false,
            ],
            [
                'code' => 'gn',
                'name' => 'Guarani',
                'rtl' => false,
            ],
            [
                'code' => 'gu',
                'name' => 'Gujarati',
                'rtl' => false,
            ],
            [
                'code' => 'ht',
                'name' => 'Haitian Creole',
                'rtl' => false,
            ],
            [
                'code' => 'ha',
                'name' => 'Hausa',
                'rtl' => false,
            ],
            [
                'code' => 'haw',
                'name' => 'Hawaiian',
                'rtl' => false,
            ],
            [
                'code' => 'he',
                'name' => 'Hebrew',
                'rtl' => true,
            ],
            [
                'code' => 'hi',
                'name' => 'Hindi',
                'rtl' => false,
            ],
            [
                'code' => 'hu',
                'name' => 'Hungarian',
                'rtl' => false,
            ],
            [
                'code' => 'is',
                'name' => 'Icelandic',
                'rtl' => false,
            ],
            [
                'code' => 'ig',
                'name' => 'Igbo',
                'rtl' => false,
            ],
            [
                'code' => 'id',
                'name' => 'Indonesian',
                'rtl' => false,
            ],
            [
                'code' => 'ga',
                'name' => 'Irish',
                'rtl' => false,
            ],
            [
                'code' => 'it',
                'name' => 'Italian',
                'rtl' => false,
            ],
            [
                'code' => 'ja',
                'name' => 'Japanese',
                'rtl' => false,
            ],
            [
                'code' => 'jv',
                'name' => 'Javanese',
                'rtl' => false,
            ],
            [
                'code' => 'kn',
                'name' => 'Kannada',
                'rtl' => false,
            ],
            [
                'code' => 'kk',
                'name' => 'Kazakh',
                'rtl' => false,
            ],
            [
                'code' => 'km',
                'name' => 'Khmer',
                'rtl' => false,
            ],
            [
                'code' => 'rw',
                'name' => 'Kinyarwanda',
                'rtl' => false,
            ],
            [
                'code' => 'ko',
                'name' => 'Korean',
                'rtl' => false,
            ],
            [
                'code' => 'ku',
                'name' => 'Kurdish',
                'rtl' => false,
            ],
            [
                'code' => 'ky',
                'name' => 'Kyrgyz',
                'rtl' => false,
            ],
            [
                'code' => 'lo',
                'name' => 'Lao',
                'rtl' => false,
            ],
            [
                'code' => 'la',
                'name' => 'Latin',
                'rtl' => false,
            ],
            [
                'code' => 'lv',
                'name' => 'Latvian',
                'rtl' => false,
            ],
            [
                'code' => 'ln',
                'name' => 'Lingala',
                'rtl' => false,
            ],
            [
                'code' => 'lt',
                'name' => 'Lithuanian',
                'rtl' => false,
            ],
            [
                'code' => 'mk',
                'name' => 'Macedonian',
                'rtl' => false,
            ],
            [
                'code' => 'ms',
                'name' => 'Malay',
                'rtl' => false,
            ],
            [
                'code' => 'ml',
                'name' => 'Malayalam',
                'rtl' => false,
            ],
            [
                'code' => 'mt',
                'name' => 'Maltese',
                'rtl' => false,
            ],
            [
                'code' => 'mi',
                'name' => 'Maori',
                'rtl' => false,
            ],
            [
                'code' => 'mr',
                'name' => 'Marathi',
                'rtl' => false,
            ],
            [
                'code' => 'mn',
                'name' => 'Mongolian',
                'rtl' => false,
            ],
            [
                'code' => 'ne',
                'name' => 'Nepali',
                'rtl' => false,
            ],
            [
                'code' => 'no',
                'name' => 'Norwegian',
                'rtl' => false,
            ],
            [
                'code' => 'ps',
                'name' => 'Pashto',
                'rtl' => true,
            ],
            [
                'code' => 'fa',
                'name' => 'Persian',
                'rtl' => true,
            ],
            [
                'code' => 'pl',
                'name' => 'Polish',
                'rtl' => false,
            ],
            [
                'code' => 'pt',
                'name' => 'Portuguese',
                'rtl' => false,
            ],
            [
                'code' => 'pt-br',
                'name' => 'Portuguese (Brazil)',
                'rtl' => false,
            ],
            [
                'code' => 'pa',
                'name' => 'Punjabi',
                'rtl' => false,
            ],
            [
                'code' => 'ro',
                'name' => 'Romanian',
                'rtl' => false,
            ],
            [
                'code' => 'ru',
                'name' => 'Russian',
                'rtl' => false,
            ],
            [
                'code' => 'sm',
                'name' => 'Samoan',
                'rtl' => false,
            ],
            [
                'code' => 'sr',
                'name' => 'Serbian',
                'rtl' => false,
            ],
            [
                'code' => 'st',
                'name' => 'Sesotho',
                'rtl' => false,
            ],
            [
                'code' => 'sn',
                'name' => 'Shona',
                'rtl' => false,
            ],
            [
                'code' => 'sd',
                'name' => 'Sindhi',
                'rtl' => false,
            ],
            [
                'code' => 'si',
                'name' => 'Sinhala',
                'rtl' => false,
            ],
            [
                'code' => 'sk',
                'name' => 'Slovak',
                'rtl' => false,
            ],
            [
                'code' => 'sl',
                'name' => 'Slovenian',
                'rtl' => false,
            ],
            [
                'code' => 'so',
                'name' => 'Somali',
                'rtl' => false,
            ],
            [
                'code' => 'es',
                'name' => 'Spanish',
                'rtl' => false,
            ],
            [
                'code' => 'su',
                'name' => 'Sundanese',
                'rtl' => false,
            ],
            [
                'code' => 'sw',
                'name' => 'Swahili',
                'rtl' => false,
            ],
            [
                'code' => 'sv',
                'name' => 'Swedish',
                'rtl' => false,
            ],
            [
                'code' => 'tg',
                'name' => 'Tajik',
                'rtl' => false,
            ],
            [
                'code' => 'ta',
                'name' => 'Tamil',
                'rtl' => false,
            ],
            [
                'code' => 'te',
                'name' => 'Telugu',
                'rtl' => false,
            ],
            [
                'code' => 'th',
                'name' => 'Thai',
                'rtl' => false,
            ],
            [
                'code' => 'tr',
                'name' => 'Turkish',
                'rtl' => false,
            ],
            [
                'code' => 'tk',
                'name' => 'Turkmen',
                'rtl' => false,
            ],
            [
                'code' => 'uk',
                'name' => 'Ukrainian',
                'rtl' => false,
            ],
            [
                'code' => 'ur',
                'name' => 'Urdu',
                'rtl' => false,
            ],
            [
                'code' => 'uz',
                'name' => 'Uzbek',
                'rtl' => false,
            ],
            [
                'code' => 'vi',
                'name' => 'Vietnamese',
                'rtl' => false,
            ],
            [
                'code' => 'cy',
                'name' => 'Welsh',
                'rtl' => false,
            ],
            [
                'code' => 'xh',
                'name' => 'Xhosa',
                'rtl' => false,
            ],
            [
                'code' => 'yi',
                'name' => 'Yiddish',
                'rtl' => false,
            ],
            [
                'code' => 'yo',
                'name' => 'Yoruba',
                'rtl' => false,
            ],
            [
                'code' => 'zu',
                'name' => 'Zulu',
                'rtl' => false,
            ],
        ];

        collect($languages)->each(
            fn ($language) => Language::create($language)
        );
    }
}
