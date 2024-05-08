import{_ as ce}from"./layout-dashboard.vue_vue_type_script_setup_true_lang-71f2b65d.js";import{u as F,_ as G,r as de,a as ue,b as pe}from"./use-language-code-conversion-851700bb.js";import{_ as _e}from"./icon-language-87fa8666.js";import{_ as K}from"./_plugin-vue_export-helper-c27b6911.js";import{o as a,j as r,g as e,d as Z,r as h,z as W,p as q,e as n,t as l,q as m,u as s,b as o,G as O,F as R,s as he,c as T,T as me,n as A,w as _,f as ge,a as fe,Z as ve,i as xe}from"./app-4a4d2073.js";import{u as J}from"./index-439f98a0.js";import{_ as ye}from"./base-button.vue_vue_type_script_setup_true_lang-193acd9e.js";import{_ as be,a as we}from"./icon-key-e4e5e2b4.js";import{_ as Ce}from"./icon-trash-75567c7f.js";import{_ as Le}from"./icon-pencil-4c57a524.js";import{_ as $e}from"./icon-clipboard-3e1826ec.js";import{_ as E}from"./flag.vue_vue_type_script_setup_true_lang-0db41313.js";import{_ as ke}from"./icon-arrow-right-7f7a44a3.js";import{r as ze}from"./XCircleIcon-b3205d77.js";import"./icon-publish-b2d2c7a1.js";import"./logo-2c45efc9.js";import"./use-auth-b15d7d50.js";import"./transition-6f9813b8.js";const Te={},He={viewBox:"0 0 15 15",xmlns:"http://www.w3.org/2000/svg",fill:"currentColor"},Ve=e("path",{"fill-rule":"evenodd","clip-rule":"evenodd",d:"M14 2.25H7.16L6.5 0H2C1.175 0 0.5 0.675 0.5 1.5V11.25C0.5 12.075 1.175 12.75 2 12.75H7.25L8 15H14C14.825 15 15.5 14.325 15.5 13.5V3.75C15.5 2.925 14.825 2.25 14 2.25ZM4.3775 9.4425C2.69 9.4425 1.31 8.07 1.31 6.375C1.31 4.68 2.6825 3.3075 4.3775 3.3075C5.1575 3.3075 5.87 3.585 6.4325 4.11L6.485 4.155L5.5625 5.04L5.5175 5.0025C5.3 4.8 4.9325 4.56 4.3775 4.56C3.395 4.56 2.5925 5.3775 2.5925 6.375C2.5925 7.3725 3.395 8.19 4.3775 8.19C5.405 8.19 5.8475 7.5375 5.9675 7.095H4.31V5.9325H7.2725L7.28 5.985C7.31 6.1425 7.3175 6.285 7.3175 6.4425C7.3175 8.205 6.11 9.4425 4.3775 9.4425ZM9.7925 9.435C9.455 9.045 9.1475 8.61 8.9 8.16L9.3875 9.8325L9.7925 9.435ZM9.4775 7.59H8.735L8.5025 6.81H11.495C11.495 6.81 11.24 7.7925 10.325 8.865C9.935 8.4 9.6575 7.9425 9.4775 7.59ZM14 14.25C14.4125 14.25 14.75 13.9125 14.75 13.5V3.75C14.75 3.3375 14.4125 3 14 3H7.385L8.27 6.03H9.74V5.25H10.52V6.03H13.25V6.81H12.2975C12.0575 7.755 11.5325 8.67 10.8575 9.4425L12.89 11.4525L12.3425 12L10.3325 9.9825L9.6425 10.6725L10.25 12.75L8.75 14.25H14Z"},null,-1),Me=[Ve];function Se(g,d){return a(),r("svg",He,Me)}const je=K(Te,[["render",Se]]),Be={class:"flex flex-wrap divide-x"},Ie={class:"px-4 py-3 md:w-64"},Ze={class:"mt-2 flex items-center"},De=["textContent"],Pe={class:"flex flex-1 items-center px-4 py-3"},Ue={class:"text-gray-700"},Ne={class:"flex divide-x"},Ae=["disabled"],Ee=Z({__name:"machine-translate-item",props:{language:{},phrase:{}},emits:["useTranslation"],setup(g,{emit:d}){const i=g,b=d,p=t=>{b("useTranslation",t)},{convertLanguageCode:k}=F(),w=h(1),f=h(1),H=i.phrase.value,c=k(i.language),V=c||"en-US",z=h(void 0),v=J(H,{lang:V,voice:z,pitch:f,rate:w});let C;const L=h([]);return W(()=>{v.isSupported.value&&setTimeout(()=>{C=window.speechSynthesis,L.value=C.getVoices(),z.value=L.value[0]})}),(t,u)=>{const M=je,S=_e,x=G,y=q("tooltip");return a(),r("div",Be,[e("div",Ie,[e("div",Ze,[n(M,{class:"mr-2 size-4 text-gray-500"}),e("div",{class:"text-sm font-medium text-gray-700",textContent:l(t.phrase.engine)},null,8,De)])]),e("div",Pe,[e("span",Ue,l(t.phrase.value),1)]),e("div",Ne,[m((a(),r("button",{class:"flex w-14 items-center justify-center px-4 py-3 text-gray-400 transition-colors duration-100 hover:bg-blue-100 hover:text-blue-600",onClick:u[0]||(u[0]=j=>p(t.phrase.value))},[n(S,{class:"size-6"})])),[[y,s(o)("Use this")]]),m((a(),r("button",{class:O(["flex w-14 items-center justify-center px-4 py-3 text-gray-400 transition-colors duration-100",{"cursor-not-allowed opacity-50":!s(c)," hover:bg-blue-100 hover:text-blue-600":s(c)}]),disabled:!s(c),onClick:u[1]||(u[1]=j=>s(c)&&s(v).speak())},[n(x,{class:"size-6"})],10,Ae)),[[y,s(c)?s(o)("Speak"):s(o)("Language not supported")]])])])}}}),Fe={key:0,class:"flex w-full flex-col divide-y"},Ge={class:"flex h-20 w-full items-center justify-center px-4 py-6"},Ke={class:"text-sm text-gray-500"},We={key:1,class:"relative flex size-full min-h-[250px]"},qe={class:"absolute left-0 top-0 flex min-h-full w-full flex-col items-center justify-center backdrop-blur-sm"},Oe={class:"mt-4 text-gray-500"},Re=Z({__name:"machine-translate",props:{language:{},suggestedTranslations:{}},emits:["useTranslation"],setup(g,{emit:d}){const i=d,b=p=>{i("useTranslation",p)};return(p,k)=>{const w=Ee;return Object.keys(p.suggestedTranslations).length>0?(a(),r("div",Fe,[(a(!0),r(R,null,he(p.suggestedTranslations,f=>(a(),T(w,{key:f.id,phrase:f,language:p.language,onUseTranslation:b},null,8,["phrase","language"]))),128)),e("div",Ge,[e("span",Ke,l(s(o)("More integrations coming in next releases...")),1)])])):(a(),r("div",We,[e("div",qe,[n(s(de),{class:"size-12 text-gray-200"}),e("span",Oe,l(s(o)("No suggestions found...")),1)])]))}}}),Je={},Qe={xmlns:"http://www.w3.org/2000/svg",fill:"none",viewBox:"0 0 24 24","stroke-width":"1.5",stroke:"currentColor","aria-hidden":"true"},Xe=e("path",{"stroke-linecap":"round","stroke-linejoin":"round",d:"M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"},null,-1),Ye=[Xe];function es(g,d){return a(),r("svg",Qe,Ye)}const ss=K(Je,[["render",es]]),ts={class:"w-full bg-white shadow"},ns={class:"mx-auto flex w-full max-w-7xl items-center justify-between px-6 lg:px-8"},os={class:"flex w-full items-center"},as={class:"flex w-full items-center gap-3 py-4"},ls={class:"h-5 shrink-0"},rs={class:"flex items-center space-x-2"},is=["textContent"],cs={class:"flex items-center gap-2 rounded-md border border-blue-100 bg-blue-50 px-2 py-1 text-blue-500"},ds=["textContent"],us={class:"mx-auto max-w-7xl px-6 py-10 lg:px-8"},ps={class:"flex w-full flex-col lg:flex-row"},_s={class:"relative w-full overflow-hidden rounded-md bg-white shadow ring-2 ring-blue-100"},hs={class:"flex items-center justify-between border-b"},ms={class:"flex gap-2 px-4 py-2.5"},gs={class:"h-5 shrink-0"},fs={class:"flex items-center space-x-2"},vs=["textContent"],xs=["textContent"],ys={class:"flex items-center divide-x border-l"},bs={type:"button",class:"group h-full p-3 hover:bg-red-50"},ws={dir:"auto",class:"flex min-h-[204px] w-full px-4 py-2.5"},Cs={class:"flex size-full flex-wrap gap-x-1 gap-y-0.5"},Ls={key:1,class:"flex h-full text-gray-600"},$s={class:"flex w-full items-center justify-center gap-4 border-t border-blue-200 px-4 py-1.5"},ks={class:"flex w-full select-none items-center gap-1 text-gray-400"},zs=["innerHTML"],Ts=["disabled"],Hs={class:"flex h-16 w-full items-center justify-center lg:h-auto lg:w-32"},Vs={class:"w-full overflow-hidden rounded-md bg-white shadow ring-2 ring-blue-100 focus-within:ring-blue-400"},Ms={class:"flex items-center justify-between border-b px-4"},Ss={class:"flex gap-2 py-2.5"},js={class:"h-5 shrink-0"},Bs={class:"flex items-center space-x-2"},Is=["textContent"],Zs=["textContent"],Ds={key:0,class:"rounded-md border border-red-400 bg-red-50 px-3 py-1"},Ps={class:"flex items-center gap-1"},Us={class:"shrink-0"},Ns={class:"text-sm text-red-700"},As={class:"flex"},Es={class:"grid grid-cols-2 border-t border-blue-200"},Fs={class:"flex w-auto"},Gs={class:"hidden md:flex"},Ks={class:"flex h-48 w-full items-center justify-center px-4 py-6"},Ws={class:"text-sm text-gray-500"},ut=Z({__name:"edit",props:{phrase:{},translation:{},source:{},similarPhrases:{},suggestedTranslations:{}},setup(g){const d=g,i=me({phrase:h(d.phrase.value)||h("")}),b=()=>{i.post(route("ltu.phrases.update",{translation:d.translation.id,phrase:d.phrase.uuid}),{onSuccess:()=>{i.reset()}})},p=t=>{i.phrase=t},{convertLanguageCode:k}=F(),w=h(1),f=h(1),H=d.phrase.value,c=k(d.translation.language.code),V=c||"en-US",z=h(void 0),v=J(H,{lang:V,pitch:f,rate:w});let C;const L=h([]);return W(()=>{v.isSupported.value&&setTimeout(()=>{C=window.speechSynthesis,L.value=C.getVoices(),z.value=L.value[0]})}),(t,u)=>{const M=ve,S=E,x=xe,y=ke,j=be,D=E,Q=$e,X=Le,Y=Ce,ee=ue,se=ss,te=G,ne=we,oe=ye,ae=Re,B=A("tab"),le=pe,re=A("tabs"),ie=ce,$=q("tooltip");return a(),r(R,null,[n(M,{title:s(o)("Translate")},null,8,["title"]),n(ie,null,{default:_(()=>{var P,U,N;return[e("div",ts,[e("div",ns,[e("div",os,[e("div",as,[n(x,{href:t.route("ltu.phrases.index",t.translation.id),class:"flex items-center gap-2 rounded-md border border-transparent bg-gray-50 px-2 py-1 hover:border-blue-400 hover:bg-blue-100"},{default:_(()=>[e("div",ls,[n(S,{"country-code":t.translation.language.code,width:"w-5"},null,8,["country-code"])]),e("div",rs,[e("div",{class:"text-sm font-semibold text-gray-600",textContent:l(t.translation.language.name)},null,8,is)])]),_:1},8,["href"]),e("div",null,[n(y,{class:"size-6 text-gray-400"})]),e("div",cs,[n(j,{class:"size-4"}),e("span",{class:"text-sm",textContent:l(t.phrase.key)},null,8,ds)])])]),m((a(),T(x,{href:t.route("ltu.phrases.index",t.translation.id),class:"flex size-10 items-center justify-center rounded-full bg-gray-100 p-1 hover:bg-gray-200"},{default:_(()=>[n(y,{class:"size-6 text-gray-400"})]),_:1},8,["href"])),[[$,s(o)("Go back")]])])]),e("div",us,[e("div",ps,[e("div",_s,[e("div",hs,[e("div",ms,[e("div",gs,[n(D,{"country-code":t.source.language.code},null,8,["country-code"])]),e("div",fs,[e("div",{class:"text-sm font-semibold text-gray-800",textContent:l(t.source.language.name)},null,8,vs),e("div",{class:"rounded-md border px-1.5 py-0.5 text-xs text-gray-500",textContent:l(t.source.language.code)},null,8,xs)])]),e("div",ys,[m((a(),r("button",{type:"button",class:"group h-full p-3 hover:bg-blue-50",onClick:u[0]||(u[0]=I=>p(t.phrase.source.value))},[n(Q,{class:"size-5 text-gray-400 group-hover:text-blue-600"})])),[[$,s(o)("Copy")]]),m((a(),T(x,{href:t.route("ltu.source_translation.edit",t.phrase.source.uuid),type:"button",class:"group h-full p-3 hover:bg-blue-50"},{default:_(()=>[n(X,{class:"size-5 text-gray-400 group-hover:text-blue-600"})]),_:1},8,["href"])),[[$,s(o)("Edit Source Key")]]),m((a(),r("button",bs,[n(Y,{class:"size-5 text-gray-400 group-hover:text-red-600"})])),[[$,s(o)("Delete")]])])]),e("div",ws,[e("div",Cs,[(P=t.phrase.source)!=null&&P.value_html.length?(a(),T(ee,{key:0,phrase:t.phrase.source.value_html,copyable:""},null,8,["phrase"])):(a(),r("div",Ls,l((U=t.phrase.source)==null?void 0:U.value),1))])]),e("div",$s,[e("div",ks,[n(se,{class:"size-4"}),e("div",{class:"text-xs",innerHTML:(N=t.phrase.source)==null?void 0:N.file.nameWithExtension},null,8,zs)]),m((a(),r("button",{class:O(["flex size-6 shrink-0 items-center justify-center text-gray-400",{"cursor-not-allowed opacity-50":!s(c),"hover:text-gray-700":s(c)}]),disabled:!s(c)&&!s(v).isPlaying.value,onClick:u[1]||(u[1]=I=>s(c)&&s(v).speak())},[n(te,{class:"size-5"})],10,Ts)),[[$,s(c)?s(o)("Speak"):s(o)("Language not supported")]])])]),e("div",Hs,[n(y,{class:"size-10 rotate-90 text-blue-200 lg:rotate-0"})]),e("div",Vs,[e("div",Ms,[e("div",Ss,[e("div",js,[n(D,{"country-code":t.translation.language.code},null,8,["country-code"])]),e("div",Bs,[e("div",{class:"text-sm font-semibold text-gray-800",textContent:l(t.translation.language.name)},null,8,Is),e("div",{class:"rounded-md border px-1.5 py-0.5 text-xs text-gray-500",textContent:l(t.translation.language.code)},null,8,Zs)])]),s(i).errors.phrase?(a(),r("div",Ds,[e("div",Ps,[e("div",Us,[n(s(ze),{class:"size-5 text-red-400","aria-hidden":"true"})]),e("div",Ns,l(s(i).errors.phrase),1)])])):ge("",!0)]),e("div",As,[n(ne,{id:"textArea",modelValue:s(i).phrase,"onUpdate:modelValue":u[2]||(u[2]=I=>s(i).phrase=I),dir:"auto",rows:"7",autofocus:"",class:"size-full resize-none rounded-none border-none px-4 py-2.5 shadow-none ring-transparent focus:outline-none focus:ring-0 focus-visible:outline-none focus-visible:ring-0"},null,8,["modelValue"])]),e("div",Es,[n(x,{href:t.route("ltu.phrases.index",t.translation.id),class:"flex items-center justify-center bg-blue-50 py-4 text-sm font-medium uppercase text-blue-600 hover:bg-blue-100"},{default:_(()=>[fe(l(s(o)("Cancel")),1)]),_:1},8,["href"]),n(oe,{"is-loading":s(i).processing,variant:"primary",class:"items-center rounded-none !py-4 !text-sm !font-medium uppercase",onClick:b},{default:_(()=>[e("span",Fs,l(s(o)("Save")),1),e("span",Gs,l(s(o)("Translation")),1)]),_:1},8,["is-loading"])])])]),n(re,null,{default:_(()=>[n(B,{name:s(o)("Suggestions"),class:"w-full",prefix:'<svg viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="h-5 w-5"><path fill-rule="evenodd" clip-rule="evenodd" d="M12.3417 7.60001L18.3334 8.11668L13.7917 12.0583L15.15 17.9167L10 14.8083L4.85002 17.9167L6.21669 12.0583L1.66669 8.11668L7.65835 7.60834L10 2.08334L12.3417 7.60001ZM6.86669 15.1417L10 13.25L13.1417 15.15L12.3084 11.5833L15.075 9.18334L11.425 8.86668L10 5.50001L8.58335 8.85834L4.93335 9.17501L7.70002 11.575L6.86669 15.1417Z"></path></svg>'},{default:_(()=>[n(ae,{"suggested-translations":t.suggestedTranslations,language:t.translation.language.code,onUseTranslation:p},null,8,["suggested-translations","language"])]),_:1},8,["name"]),n(B,{name:s(o)("Similar"),prefix:'<svg viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="h-5 w-5"><path fill-rule="evenodd" clip-rule="evenodd" d="M12.5 3.33334C8.81665 3.33334 5.83331 6.31668 5.83331 10C5.83331 13.6833 8.81665 16.6667 12.5 16.6667C16.1833 16.6667 19.1666 13.6833 19.1666 10C19.1666 6.31668 16.1833 3.33334 12.5 3.33334ZM12.5 15C9.74165 15 7.49998 12.7583 7.49998 10C7.49998 7.24168 9.74165 5.00001 12.5 5.00001C15.2583 5.00001 17.5 7.24168 17.5 10C17.5 12.7583 15.2583 15 12.5 15ZM5.83331 5.29168C3.89165 5.97501 2.49998 7.82501 2.49998 10C2.49998 12.175 3.89165 14.025 5.83331 14.7083V16.45C2.95831 15.7083 0.833313 13.1083 0.833313 10C0.833313 6.89168 2.95831 4.29168 5.83331 3.55001V5.29168Z"></path></svg>'},{default:_(()=>[n(le,{"similar-phrases":t.similarPhrases},null,8,["similar-phrases"])]),_:1},8,["name"]),n(B,{name:s(o)("Versions"),prefix:'<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-5 w-5"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M13 3c-4.97 0-9 4.03-9 9H1l3.89 3.89.07.14L9 12H6c0-3.87 3.13-7 7-7s7 3.13 7 7-3.13 7-7 7c-1.93 0-3.68-.79-4.94-2.06l-1.42 1.42C8.27 19.99 10.51 21 13 21c4.97 0 9-4.03 9-9s-4.03-9-9-9zm-1 5v5l4.25 2.52.77-1.28-3.52-2.09V8z"/></svg>'},{default:_(()=>[e("div",Ks,[e("span",Ws,l(s(o)("Coming soon...")),1)])]),_:1},8,["name"])]),_:1})])]}),_:1})],64)}}});export{ut as default};
