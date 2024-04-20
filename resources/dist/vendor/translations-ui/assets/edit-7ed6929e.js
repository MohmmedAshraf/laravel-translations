import{_ as q}from"./layout-dashboard.vue_vue_type_script_setup_true_lang-73b88de8.js";import{u as J,_ as O,b as Q}from"./use-language-code-conversion-6d16aff3.js";import{_ as X}from"./base-button.vue_vue_type_script_setup_true_lang-1fc87352.js";import{_ as Y,a as C}from"./icon-key-5ba1abd7.js";import{_ as ee,a as se}from"./input-label.vue_vue_type_script_setup_true_lang-d26cd3bf.js";import{_ as te}from"./input-native-select.vue_vue_type_script_setup_true_lang-653689b9.js";import{_ as V}from"./flag.vue_vue_type_script_setup_true_lang-0fffba80.js";import{_ as oe}from"./icon-arrow-right-b4bc3d12.js";import{d as ne,T as ae,r as i,s as le,y as re,l as z,n as ie,o as p,h as f,b as s,w as l,f as e,t as u,p as k,c as ce,u as t,e as de,E as ue,a as pe,F as me,Z as _e,i as fe}from"./app-d05ed321.js";import{u as he}from"./index-781dd8f3.js";import{r as ge}from"./XCircleIcon-94ba7dc3.js";import"./icon-publish-3dc73452.js";import"./_plugin-vue_export-helper-c27b6911.js";import"./logo-44dbb4de.js";import"./use-auth-fa75b04f.js";import"./transition-dbb1d324.js";import"./icon-pencil-11c54fe0.js";import"./icon-close-05208328.js";import"./use-input-size-1ea8e246.js";const xe={class:"w-full bg-white shadow"},ve={class:"mx-auto flex w-full max-w-7xl items-center justify-between px-6 lg:px-8"},be={class:"flex w-full items-center"},we={class:"flex w-full items-center gap-3 py-4"},ye={class:"h-5 shrink-0"},Ce={class:"flex items-center space-x-2"},Ve=["textContent"],ze={class:"flex items-center gap-2 rounded-md border border-blue-100 bg-blue-50 px-2 py-1 text-blue-500"},ke=["textContent"],Se={class:"mx-auto max-w-7xl px-6 py-10 lg:px-8"},$e={class:"flex w-full flex-col gap-8 lg:flex-row"},Be={class:"relative w-full overflow-hidden rounded-md bg-white shadow ring-2 ring-blue-100 focus-within:ring-blue-400"},Le={class:"flex items-center justify-between border-b px-4"},je={class:"flex gap-2 py-2.5"},Ie={class:"h-5 shrink-0"},Me={class:"flex items-center space-x-2"},Te=["textContent"],Fe=["textContent"],He={key:0,class:"rounded-md border border-red-400 bg-red-50 px-3 py-1"},Ne={class:"flex items-center gap-1"},De={class:"shrink-0"},Ee={class:"text-sm text-red-700"},Pe={class:"flex h-[calc(100%-80px)]"},Ue={class:"flex w-full items-center justify-center gap-4 border-t border-blue-200 px-4 py-1.5"},Ze={class:"flex w-full items-center"},Ae={class:"text-xs text-gray-400"},Ge=["disabled"],Ke={class:"w-full overflow-hidden rounded-md bg-white shadow ring-2 ring-blue-100"},Re={class:"flex min-h-[231px] flex-col p-4"},We={class:"w-full space-y-1"},qe={class:"mt-4 w-full space-y-1"},Je={class:"grid grid-cols-2 border-t border-blue-200"},Oe=e("span",{class:"flex w-auto"},"Save",-1),Qe=e("span",{class:"hidden md:flex"},"Translation",-1),Xe=e("div",{class:"relative flex size-full min-h-[250px] w-full items-center justify-center px-4 py-6"},[e("span",{class:"text-sm text-gray-500"},"Coming soon...")],-1),xs=ne({__name:"edit",props:{phrase:{},translation:{},source:{},similarPhrases:{},files:{}},setup(S){const r=S,o=ae({note:r.phrase.note||i(""),phrase:r.phrase.value||i(""),file:r.phrase.translation_file_id||i("")}),$=()=>{o.post(route("ltu.source_translation.update",r.phrase.uuid),{onSuccess:()=>{o.reset()}})},B=le(()=>r.files.map(n=>({value:n.id,label:n.nameWithExtension}))),{convertLanguageCode:L}=J(),j=i(1),I=i(1),M=r.phrase.value,c=L(r.translation.language.code),T=c||"en-US",F=i(void 0),m=he(M,{lang:T,pitch:I,rate:j});let h;const g=i([]);return re(()=>{m.isSupported.value&&setTimeout(()=>{h=window.speechSynthesis,g.value=h.getVoices(),F.value=g.value[0]})}),(n,a)=>{const H=_e,N=V,_=fe,x=oe,D=Y,E=V,P=C,U=O,v=ee,Z=te,b=se,A=C,G=X,K=Q,w=z("tab"),R=z("tabs"),W=q,y=ie("tooltip");return p(),f(me,null,[s(H,{title:"Translate"}),s(W,null,{default:l(()=>[e("div",xe,[e("div",ve,[e("div",be,[e("div",we,[s(_,{href:n.route("ltu.source_translation"),class:"flex items-center gap-2 rounded-md border border-transparent bg-gray-50 px-2 py-1 hover:border-blue-400 hover:bg-blue-100"},{default:l(()=>[e("div",ye,[s(N,{"country-code":n.translation.language.code,width:"w-5"},null,8,["country-code"])]),e("div",Ce,[e("div",{class:"text-sm font-semibold text-gray-600",textContent:u(n.translation.language.name)},null,8,Ve)])]),_:1},8,["href"]),e("div",null,[s(x,{class:"size-6 text-gray-400"})]),e("div",ze,[s(D,{class:"size-4"}),e("span",{class:"text-sm",textContent:u(n.phrase.key)},null,8,ke)])])]),k((p(),ce(_,{href:n.route("ltu.source_translation"),class:"flex size-10 items-center justify-center rounded-full bg-gray-100 p-1 hover:bg-gray-200"},{default:l(()=>[s(x,{class:"size-6 text-gray-400"})]),_:1},8,["href"])),[[y,"Go back"]])])]),e("div",Se,[e("div",$e,[e("div",Be,[e("div",Le,[e("div",je,[e("div",Ie,[s(E,{"country-code":n.translation.language.code},null,8,["country-code"])]),e("div",Me,[e("div",{class:"text-sm font-semibold text-gray-800",textContent:u(n.translation.language.name)},null,8,Te),e("div",{class:"rounded-md border px-1.5 py-0.5 text-xs text-gray-500",textContent:u(n.translation.language.code)},null,8,Fe)])]),t(o).errors.phrase?(p(),f("div",He,[e("div",Ne,[e("div",De,[s(t(ge),{class:"size-5 text-red-400","aria-hidden":"true"})]),e("div",Ee,u(t(o).errors.phrase),1)])])):de("",!0)]),e("div",Pe,[s(P,{id:"textArea",modelValue:t(o).phrase,"onUpdate:modelValue":a[0]||(a[0]=d=>t(o).phrase=d),rows:"7",autofocus:"",class:"size-full resize-none rounded-none border-none px-4 py-2.5 shadow-none ring-transparent focus:outline-none focus:ring-0 focus-visible:outline-none focus-visible:ring-0"},null,8,["modelValue"])]),e("div",Ue,[e("div",Ze,[e("span",Ae,"Characters Length: "+u(t(o).phrase.length),1)]),k((p(),f("button",{class:ue(["flex size-6 shrink-0 items-center justify-center text-gray-400",{"cursor-not-allowed opacity-50":!t(c),"hover:text-gray-700":t(c)}]),disabled:!t(c)&&!t(m).isPlaying.value,onClick:a[1]||(a[1]=d=>t(c)&&t(m).speak())},[s(U,{class:"size-5"})],10,Ge)),[[y,t(c)?"Speak":"Language not supported"]])])]),e("div",Ke,[e("div",Re,[e("div",We,[s(v,{for:"file",value:"File"}),s(Z,{id:"file",modelValue:t(o).file,"onUpdate:modelValue":a[2]||(a[2]=d=>t(o).file=d),size:"md",error:t(o).errors.file,items:B.value},null,8,["modelValue","error","items"]),s(b,{message:t(o).errors.file},null,8,["message"])]),e("div",qe,[s(v,{for:"note",value:"Translation note"}),s(A,{id:"note",modelValue:t(o).note,"onUpdate:modelValue":a[3]||(a[3]=d=>t(o).note=d),size:"md",rows:"3",class:"resize-none",error:t(o).errors.note,placeholder:"Add a note to this translation"},null,8,["modelValue","error"]),s(b,{message:t(o).errors.note},null,8,["message"])])]),e("div",Je,[s(_,{href:n.route("ltu.source_translation"),class:"flex items-center justify-center bg-blue-50 py-4 text-sm font-medium uppercase text-blue-600 hover:bg-blue-100"},{default:l(()=>[pe(" Cancel ")]),_:1},8,["href"]),s(G,{"is-loading":t(o).processing,variant:"primary",class:"items-center rounded-none py-4 !text-sm !font-medium uppercase",onClick:$},{default:l(()=>[Oe,Qe]),_:1},8,["is-loading"])])])]),s(R,null,{default:l(()=>[s(w,{prefix:'<svg viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="h-5 w-5"><path fill-rule="evenodd" clip-rule="evenodd" d="M12.5 3.33334C8.81665 3.33334 5.83331 6.31668 5.83331 10C5.83331 13.6833 8.81665 16.6667 12.5 16.6667C16.1833 16.6667 19.1666 13.6833 19.1666 10C19.1666 6.31668 16.1833 3.33334 12.5 3.33334ZM12.5 15C9.74165 15 7.49998 12.7583 7.49998 10C7.49998 7.24168 9.74165 5.00001 12.5 5.00001C15.2583 5.00001 17.5 7.24168 17.5 10C17.5 12.7583 15.2583 15 12.5 15ZM5.83331 5.29168C3.89165 5.97501 2.49998 7.82501 2.49998 10C2.49998 12.175 3.89165 14.025 5.83331 14.7083V16.45C2.95831 15.7083 0.833313 13.1083 0.833313 10C0.833313 6.89168 2.95831 4.29168 5.83331 3.55001V5.29168Z"></path></svg>',name:"Similar"},{default:l(()=>[s(K,{"similar-phrases":n.similarPhrases},null,8,["similar-phrases"])]),_:1}),s(w,{name:"History",prefix:'<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-5 w-5"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M13 3c-4.97 0-9 4.03-9 9H1l3.89 3.89.07.14L9 12H6c0-3.87 3.13-7 7-7s7 3.13 7 7-3.13 7-7 7c-1.93 0-3.68-.79-4.94-2.06l-1.42 1.42C8.27 19.99 10.51 21 13 21c4.97 0 9-4.03 9-9s-4.03-9-9-9zm-1 5v5l4.25 2.52.77-1.28-3.52-2.09V8z"/></svg>'},{default:l(()=>[Xe]),_:1})]),_:1})])]),_:1})],64)}}});export{xs as default};