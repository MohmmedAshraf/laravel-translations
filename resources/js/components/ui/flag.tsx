import { cn } from '@/lib/utils';

const LANGUAGE_TO_COUNTRY: Record<string, string> = {
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
    'zh-TW': 'TW',
    co: 'FR',
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
    he: 'IL',
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
    lo: 'LA',
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
    'pt-BR': 'BR',
    pa: 'IN',
    ro: 'RO',
    ru: 'RU',
    sm: 'WS',
    sr: 'RS',
    st: 'LS',
    sn: 'ZW',
    sd: 'PK',
    si: 'LK',
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
    zu: 'ZA',
};

function getCountryCode(languageCode: string): string {
    const clean = languageCode.split('#')[0];
    const [lang, country] = clean.split('_');

    return (
        (country?.toUpperCase()) ||
        LANGUAGE_TO_COUNTRY[languageCode] ||
        LANGUAGE_TO_COUNTRY[lang] ||
        ''
    );
}

function countryCodeToTwemojiUrl(countryCode: string): string | null {
    if (!countryCode || countryCode.length < 2) return null;

    const upper = countryCode.toUpperCase();
    const codePoints = [...upper].map(
        (char) => (0x1f1e6 + char.charCodeAt(0) - 65).toString(16),
    );

    return `https://cdn.jsdelivr.net/gh/twitter/twemoji@14.0.2/assets/svg/${codePoints.join('-')}.svg`;
}

interface FlagProps {
    code: string;
    className?: string;
}

export function Flag({ code, className }: FlagProps) {
    const countryCode = getCountryCode(code);
    const url = countryCodeToTwemojiUrl(countryCode);

    if (!url) {
        return <span className={cn('inline-block size-5', className)}>🏳️</span>;
    }

    return (
        <img
            src={url}
            alt={`${code} flag`}
            className={cn('inline-block size-5', className)}
            loading="lazy"
        />
    );
}
