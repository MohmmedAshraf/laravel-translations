import{_ as m}from"./icon-pencil-4c57a524.js";import{o as t,j as c,g as s,d as f,p as g,G as v,c as o,e as a,w as l,t as r,q as w,u as x,b as y,i as b}from"./app-4a4d2073.js";import{_ as k}from"./icon-language-87fa8666.js";import{_ as j}from"./_plugin-vue_export-helper-c27b6911.js";const z={},B={xmlns:"http://www.w3.org/2000/svg",viewBox:"0 0 24 24",fill:"currentColor"},C=s("path",{d:"M0 0h24v24H0z",fill:"none"},null,-1),L=s("path",{d:"M9 16.2L4.8 12l-1.4 1.4L9 19 21 7l-1.4-1.4L9 16.2z"},null,-1),$=[C,L];function D(d,e){return t(),c("svg",B,$)}const I=j(z,[["render",D]]),E={class:"w-full hover:bg-gray-100"},M={class:"flex h-14 w-full divide-x"},N={class:"flex w-full items-center justify-start px-4"},V={class:"truncate rounded-md border bg-white px-1.5 py-0.5 text-sm font-medium text-gray-600 hover:border-blue-400 hover:bg-blue-50 hover:text-blue-600"},q={class:"hidden w-full items-center justify-start px-4 md:flex"},G={class:"truncate whitespace-nowrap text-sm font-medium text-gray-400"},H={class:"flex w-full items-center justify-start px-4"},P={class:"w-full truncate whitespace-nowrap text-sm font-medium text-gray-600"},S={class:"grid w-[67px] grid-cols-1 divide-x"},Q=f({__name:"phrase-item",props:{phrase:{},translation:{}},setup(d){return(e,A)=>{const p=I,_=k,n=b,u=m,h=g("tooltip");return t(),c("div",E,[s("div",M,[s("div",{class:v(["hidden w-20 items-center justify-center px-4 md:flex",{"bg-green-50":e.phrase.state,"hover:bg-green-100":e.phrase.state}])},[e.phrase.state?(t(),o(p,{key:0,class:"size-5 text-green-600"})):(t(),o(_,{key:1,class:"size-5 text-gray-500"}))],2),a(n,{href:e.route("ltu.phrases.edit",{translation:e.translation.id,phrase:e.phrase.uuid}),class:"grid w-full grid-cols-2 divide-x md:grid-cols-3"},{default:l(()=>{var i;return[s("div",N,[s("div",V,r(e.phrase.key),1)]),s("div",q,[s("div",G,r((i=e.phrase.source)==null?void 0:i.value),1)]),s("div",H,[s("div",P,r(e.phrase.value),1)])]}),_:1},8,["href"]),s("div",S,[w((t(),o(n,{href:e.route("ltu.phrases.edit",{translation:e.translation.id,phrase:e.phrase.uuid}),class:"group flex items-center justify-center px-3 hover:bg-blue-50"},{default:l(()=>[a(u,{class:"size-5 text-gray-400 group-hover:text-blue-600"})]),_:1},8,["href"])),[[h,x(y)("Edit")]])])])])}}});export{Q as _};
