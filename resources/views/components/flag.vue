<script setup lang="ts">
import twemoji from "twemoji"
import { onMounted, ref } from "vue"
import countryCodeEmoji from "country-code-emoji"

const props = withDefaults(
    defineProps<{
        countryCode?: string
        width?: string
    }>(),
    {
        countryCode: "",
        width: "w-5",
    },
)

const mapLanguageToCountry = {
    af: "ZA",
    am: "ET",
    sq: "AL",
    ar: "AE",
    hy: "AM",
    as: "IN",
    ay: "BO",
    az: "AZ",
    bm: "ML",
    eu: "ES",
    be: "BY",
    bn: "BD",
    bho: "IN",
    bs: "BA",
    bg: "BG",
    ca: "ES",
    ceb: "PH",
    zh: "CN",
    "zh-TW": "CN",
    co: "MF",
    hr: "HR",
    cs: "CZ",
    da: "DK",
    dv: "MV",
    nl: "NL",
    en: "GB",
    et: "EE",
    fil: "PH",
    fi: "FI",
    fr: "FR",
    gl: "ES",
    ka: "GE",
    de: "DE",
    el: "GR",
    gn: "PY",
    gu: "IN",
    ht: "HT",
    ha: "NE",
    haw: "US",
    hi: "IN",
    hu: "HU",
    is: "IS",
    ig: "NG",
    id: "ID",
    ga: "IE",
    it: "IT",
    ja: "JP",
    jv: "ID",
    kn: "IN",
    kk: "KZ",
    km: "KH",
    rw: "RW",
    ko: "KR",
    ku: "IQ",
    ky: "KG",
    lo: "TH",
    la: "VA",
    lv: "LV",
    ln: "CD",
    lt: "LT",
    mk: "MK",
    ms: "MY",
    ml: "IN",
    mt: "MT",
    mi: "NZ",
    mr: "IN",
    mn: "MN",
    my: "MM",
    ne: "NP",
    no: "NO",
    nb: "NO",
    nn: "NO",
    ps: "AF",
    fa: "IR",
    pl: "PL",
    pt: "PT",
    "pt-br": "BR",
    pa: "IN",
    ro: "RO",
    ru: "RU",
    sm: "WS",
    sr: "RS",
    st: "LS",
    sn: "ZW",
    sd: "PK",
    si: "LK",
    sk: "SK",
    sl: "SI",
    so: "SO",
    es: "ES",
    su: "ID",
    sw: "KE",
    sv: "SE",
    tg: "TJ",
    ta: "IN",
    te: "IN",
    th: "TH",
    tk: "TM",
    tr: "TR",
    uk: "UA",
    ur: "PK",
    uz: "UZ",
    vi: "VN",
    cy: "GB",
    xh: "ZA",
    yo: "NG",
    jam: "JM",
    or: "IN",
    sa: "IN",
    sat: "IN",
    zu: "ZA",
}

const normalizedCode = computed(() => {
    const noScriptCode = props.countryCode.split("#")[0]
    const [lang, country] = noScriptCode.split("_")

    return country || (mapLanguageToCountry as Record<string, string>)[props.countryCode] || (mapLanguageToCountry as Record<string, string>)[lang] || ""
})

const countryFlag = computed(() => {
    try {
        return countryCodeEmoji(normalizedCode.value)
    } catch (e) {
        return "🗺️"
    }
})

const emojiAlt = ref("")

onMounted(() => {
    emojiAlt.value = twemoji.parse(countryFlag.value, { folder: "svg", ext: ".svg", className: props.width, base: "https://cdn.jsdelivr.net/gh/twitter/twemoji@14.0.2/assets/" })
})
</script>

<template>
    <div v-html="emojiAlt"></div>
</template>
