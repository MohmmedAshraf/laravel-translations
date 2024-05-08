import{u as T,_ as V}from"./use-confirmation-dialog-7483ccb2.js";import{_ as j}from"./base-button.vue_vue_type_script_setup_true_lang-193acd9e.js";import{_ as L}from"./icon-trash-75567c7f.js";import{_ as N}from"./icon-language-87fa8666.js";import{d as A,r as O,D as S,p as q,o as c,j as f,g as e,e as n,w as i,t as l,q as _,u as s,b as o,a8 as E,c as F,a as x,O as U,i as G}from"./app-4a4d2073.js";import{_ as H}from"./flag.vue_vue_type_script_setup_true_lang-0db41313.js";import{_ as J}from"./input-checkbox.vue_vue_type_script_setup_true_lang-e805bc21.js";const K={class:"flex h-14 flex-row gap-y-2 no-underline transition-colors duration-100 hover:bg-gray-50"},M={class:"relative flex flex-1 divide-x border-r no-underline"},P={class:"flex max-w-full"},Q={class:"mr-0 flex w-14 items-center justify-center"},R={class:"flex shrink-0 items-center"},W={class:"flex flex-1 justify-between gap-x-4 truncate px-4"},X={class:"flex w-full items-center truncate font-medium"},Y={class:"mr-2 max-w-full truncate text-base text-gray-700"},Z={class:"inline-block"},ee={class:"flex h-5 items-center rounded-md border px-1.5 text-xs font-normal leading-none text-gray-600"},te={class:"hidden flex-1 flex-wrap content-center items-center gap-1 px-4 md:flex md:max-w-72 lg:max-w-80 xl:max-w-96"},se={class:"w-full py-2"},ne={class:"translation-progress w-full overflow-hidden rounded-full bg-gray-200"},oe={class:"hidden w-full border-r sm:flex sm:w-14"},ae={class:"flex h-full"},le={class:"flex w-full max-w-full"},ie={class:"flex flex-col p-6"},re={class:"text-xl font-medium text-gray-700"},de={class:"mt-2 text-sm text-gray-500"},ce={class:"mt-4 flex gap-4"},xe=A({__name:"translation-item",props:{translation:{},selectedIds:{}},setup(v){const r=v,{loading:w,showDialog:y,openDialog:p,performAction:b,closeDialog:k}=T(),D=async t=>{await b(()=>U.delete(route("ltu.translation.delete",t)))},u=O(r.selectedIds.includes(r.translation.id));return S(()=>r.selectedIds,t=>{u.value=t.includes(r.translation.id)}),(t,a)=>{const C=J,z=H,g=G,$=N,B=L,h=j,I=V,m=q("tooltip");return c(),f("div",K,[e("div",M,[e("div",P,[e("div",Q,[e("div",R,[n(C,{modelValue:u.value,"onUpdate:modelValue":a[0]||(a[0]=d=>u.value=d),value:t.translation.id},null,8,["modelValue","value"])])])]),n(g,{href:t.route("ltu.phrases.index",t.translation.id),class:"flex w-full divide-x"},{default:i(()=>[e("div",W,[e("div",X,[n(z,{"country-code":t.translation.language.code,class:"mr-2"},null,8,["country-code"]),e("span",Y,l(t.translation.language.name),1),e("div",Z,[e("span",ee,l(t.translation.language.code),1)])])]),e("div",te,[_((c(),f("div",se,[e("div",ne,[e("div",{class:"h-2 bg-green-600",style:E({width:`${t.translation.progress}`})},null,4)])])),[[m,`${t.translation.progress} `+s(o)("strings translated")]])])]),_:1},8,["href"])]),e("div",oe,[_((c(),F(g,{href:t.route("ltu.phrases.index",t.translation.id),class:"relative inline-flex h-14 w-full cursor-pointer select-none items-center justify-center px-4 text-sm font-medium tracking-wide text-gray-400 outline-none transition-colors duration-150 ease-out hover:bg-blue-50 hover:text-blue-500 focus:border-blue-50"},{default:i(()=>[n($,{class:"hidden size-5 sm:flex"})]),_:1},8,["href"])),[[m,s(o)("Translate")]])]),e("div",ae,[_((c(),f("div",le,[e("button",{type:"button",class:"relative inline-flex size-14 cursor-pointer select-none items-center justify-center p-4 text-sm font-medium uppercase tracking-wide text-gray-400 no-underline outline-none transition-colors duration-150 ease-out hover:bg-red-50 hover:text-red-600",onClick:a[1]||(a[1]=(...d)=>s(p)&&s(p)(...d))},[n(B,{class:"size-5"})])])),[[m,s(o)("Delete")]]),n(I,{size:"sm",show:s(y)},{default:i(()=>[e("div",ie,[e("span",re,l(s(o)("Are you sure?")),1),e("span",de,l(s(o)("This action cannot be undone, This will permanently delete the :Language language and all of its translations.",{Language:t.translation.language.name})),1),e("div",ce,[n(h,{variant:"secondary",type:"button",size:"lg","full-width":"",onClick:s(k)},{default:i(()=>[x(l(s(o)("Cancel")),1)]),_:1},8,["onClick"]),n(h,{variant:"danger",type:"button",size:"lg","is-loading":s(w),"full-width":"",onClick:a[2]||(a[2]=d=>D(t.translation.id))},{default:i(()=>[x(l(s(o)("Delete")),1)]),_:1},8,["is-loading"])])])]),_:1},8,["show"])])])}}});export{xe as _};
