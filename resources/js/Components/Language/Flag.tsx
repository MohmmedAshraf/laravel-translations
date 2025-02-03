import twemoji from 'twemoji';
// @ts-ignore
import countryCodeEmoji from 'country-code-emoji';
import React, { useEffect, useState } from 'react';

interface CountryFlagProps {
    countryCode?: string;
    width?: string;
}

const mapLanguageToCountry: Record<string, string> = {
    af: 'ZA',
    am: 'ET',
    sq: 'AL',
    ar: 'AE',
    hy: 'AM',
    as: 'IN',
    ay: 'BO',
    az: 'AZ',
    bm: 'ML',
    eu: 'ES',
    be: 'BY',
    bn: 'BD',
    bho: 'IN',
    bs: 'BA',
    bg: 'BG',
    ca: 'ES',
    ceb: 'PH',
    zh: 'CN',
    'zh-TW': 'CN',
    co: 'MF',
    hr: 'HR',
    cs: 'CZ',
    da: 'DK',
    dv: 'MV',
    nl: 'NL',
    en: 'GB',
    et: 'EE',
    fil: 'PH',
    fi: 'FI',
    fr: 'FR',
    gl: 'ES',
    ka: 'GE',
    de: 'DE',
    el: 'GR',
    gn: 'PY',
    gu: 'IN',
    ht: 'HT',
    ha: 'NE',
    haw: 'US',
    hi: 'IN',
    hu: 'HU',
    is: 'IS',
    ig: 'NG',
    id: 'ID',
    ga: 'IE',
    it: 'IT',
    ja: 'JP',
    jv: 'ID',
    kn: 'IN',
    kk: 'KZ',
    km: 'KH',
    rw: 'RW',
    ko: 'KR',
    ku: 'IQ',
    ky: 'KG',
    lo: 'TH',
    la: 'VA',
    lv: 'LV',
    ln: 'CD',
    lt: 'LT',
    mk: 'MK',
    ms: 'MY',
    ml: 'IN',
    mt: 'MT',
    mi: 'NZ',
    mr: 'IN',
    mn: 'MN',
    my: 'MM',
    ne: 'NP',
    no: 'NO',
    nb: 'NO',
    nn: 'NO',
    ps: 'AF',
    fa: 'IR',
    pl: 'PL',
    pt: 'PT',
    'pt-br': 'BR',
    pa: 'IN',
    ro: 'RO',
    ru: 'RU',
    sm: 'WS',
    sr: 'RS',
    st: 'LS',
    sn: 'ZW',
    sd: 'PK',
    Și: 'LK',
    sk: 'SK',
    sl: 'SI',
    so: 'SO',
    es: 'ES',
    su: 'ID',
    sw: 'KE',
    sv: 'SE',
    tg: 'TJ',
    ta: 'IN',
    te: 'IN',
    th: 'TH',
    tk: 'TM',
    tr: 'TR',
    uk: 'UA',
    ur: 'PK',
    uz: 'UZ',
    vi: 'VN',
    cy: 'GB',
    xh: 'ZA',
    yo: 'NG',
    jam: 'JM',
    or: 'IN',
    sa: 'IN',
    sat: 'IN',
    zu: 'ZA'
};

const CountryFlag: React.FC<CountryFlagProps> = ({ countryCode = '', width = 'w-5' }) => {
    const [emojiAlt, setEmojiAlt] = useState('');

    const getNormalizedCode = (): string => {
        const noScriptCode = countryCode.split('#')[0];
        const [lang, country] = noScriptCode.split('_');
        return (
            country ||
            mapLanguageToCountry[countryCode] ||
            mapLanguageToCountry[lang] ||
            ''
        );
    };

    const getCountryFlag = (): string => {
        try {
            return countryCodeEmoji(getNormalizedCode());
        } catch (e) {
            return '🗺️';
        }
    };

    useEffect(() => {
        setEmojiAlt(
            twemoji.parse(getCountryFlag(), {
                folder: 'svg',
                ext: '.svg',
                className: width,
                base: 'https://cdn.jsdelivr.net/gh/twitter/twemoji@14.0.2/assets/'
            })
        );
    }, [countryCode, width]);

    return <div dangerouslySetInnerHTML={{ __html: emojiAlt }} />;
};

export default CountryFlag;