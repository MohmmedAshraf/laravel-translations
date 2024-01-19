import{o as S,c as k,a,l as B,A as le,y as q,d as H,b as C,z as de,G as T,F as D,B as pe,C as oe,h as c,w,x as Q,n as Pe,g as R,t as A,u as P,k as ve,e as Se,i as se,f as ke,_ as $e}from"./app-6e494c7d.js";import{_ as Ee}from"./icon-publish-73e612df.js";import{_ as Oe}from"./logo-487e8493.js";import{u as Be}from"./use-auth-937202b1.js";import{o as l,m as ne,E as ae,u as V,d as Ce,l as G,V as Ie,p as Fe,e as Me,y as Te,w as Ne,h as je,H as Y,t as z,n as fe,f as X,a as ee,g as he,i as Z,b as N,P as j,N as F,j as M,c as te,S as Le,k as re}from"./transition-c671a28f.js";function Ae(s,r){return S(),k("svg",{xmlns:"http://www.w3.org/2000/svg",viewBox:"0 0 20 20",fill:"currentColor","aria-hidden":"true","data-slot":"icon"},[a("path",{"fill-rule":"evenodd",d:"M8.22 5.22a.75.75 0 0 1 1.06 0l4.25 4.25a.75.75 0 0 1 0 1.06l-4.25 4.25a.75.75 0 0 1-1.06-1.06L11.94 10 8.22 6.28a.75.75 0 0 1 0-1.06Z","clip-rule":"evenodd"})])}function ue(s,r){return S(),k("svg",{xmlns:"http://www.w3.org/2000/svg",fill:"none",viewBox:"0 0 24 24","stroke-width":"1.5",stroke:"currentColor","aria-hidden":"true","data-slot":"icon"},[a("path",{"stroke-linecap":"round","stroke-linejoin":"round",d:"M8.25 9V5.25A2.25 2.25 0 0 1 10.5 3h6a2.25 2.25 0 0 1 2.25 2.25v13.5A2.25 2.25 0 0 1 16.5 21h-6a2.25 2.25 0 0 1-2.25-2.25V15M12 9l3 3m0 0-3 3m3-3H2.25"})])}function ze(s,r){return S(),k("svg",{xmlns:"http://www.w3.org/2000/svg",fill:"none",viewBox:"0 0 24 24","stroke-width":"1.5",stroke:"currentColor","aria-hidden":"true","data-slot":"icon"},[a("path",{"stroke-linecap":"round","stroke-linejoin":"round",d:"M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"})])}function De(s,r){return S(),k("svg",{xmlns:"http://www.w3.org/2000/svg",fill:"none",viewBox:"0 0 24 24","stroke-width":"1.5",stroke:"currentColor","aria-hidden":"true","data-slot":"icon"},[a("path",{"stroke-linecap":"round","stroke-linejoin":"round",d:"M6 18 18 6M6 6l12 12"})])}function ie(s,r){if(s)return s;let d=r??"button";if(typeof d=="string"&&d.toLowerCase()==="button")return"button"}function Ve(s,r){let d=B(ie(s.value.type,s.value.as));return le(()=>{d.value=ie(s.value.type,s.value.as)}),q(()=>{var g;d.value||l(r)&&l(r)instanceof HTMLButtonElement&&!((g=l(r))!=null&&g.hasAttribute("type"))&&(d.value="button")}),d}var Ge=(s=>(s[s.Open=0]="Open",s[s.Closed=1]="Closed",s))(Ge||{});let me=Symbol("PopoverContext");function J(s){let r=oe(me,null);if(r===null){let d=new Error(`<${s} /> is missing a parent <${_e.name} /> component.`);throw Error.captureStackTrace&&Error.captureStackTrace(d,J),d}return r}let He=Symbol("PopoverGroupContext");function be(){return oe(He,null)}let ge=Symbol("PopoverPanelContext");function Ke(){return oe(ge,null)}let _e=H({name:"Popover",inheritAttrs:!1,props:{as:{type:[Object,String],default:"div"}},setup(s,{slots:r,attrs:d,expose:g}){var t;let e=B(null);g({el:e,$el:e});let i=B(1),f=B(null),O=B(null),h=B(null),y=B(null),I=C(()=>ne(e)),L=C(()=>{var o,u;if(!l(f)||!l(y))return!1;for(let W of document.querySelectorAll("body > *"))if(Number(W==null?void 0:W.contains(l(f)))^Number(W==null?void 0:W.contains(l(y))))return!0;let v=ae(),$=v.indexOf(l(f)),U=($+v.length-1)%v.length,xe=($+1)%v.length,ye=v[U],we=v[xe];return!((o=l(y))!=null&&o.contains(ye))&&!((u=l(y))!=null&&u.contains(we))}),x={popoverState:i,buttonId:B(null),panelId:B(null),panel:y,button:f,isPortalled:L,beforePanelSentinel:O,afterPanelSentinel:h,togglePopover(){i.value=V(i.value,{0:1,1:0})},closePopover(){i.value!==1&&(i.value=1)},close(o){x.closePopover();let u=(()=>o?o instanceof HTMLElement?o:o.value instanceof HTMLElement?l(o):l(x.button):l(x.button))();u==null||u.focus()}};de(me,x),Ce(C(()=>V(i.value,{0:G.Open,1:G.Closed})));let K={buttonId:x.buttonId,panelId:x.panelId,close(){x.closePopover()}},E=be(),m=E==null?void 0:E.registerPopover,[_,p]=Ie(),n=Fe({mainTreeNodeRef:E==null?void 0:E.mainTreeNodeRef,portals:_,defaultContainers:[f,y]});function b(){var o,u,v,$;return($=E==null?void 0:E.isFocusWithinPopoverGroup())!=null?$:((o=I.value)==null?void 0:o.activeElement)&&(((u=l(f))==null?void 0:u.contains(I.value.activeElement))||((v=l(y))==null?void 0:v.contains(I.value.activeElement)))}return q(()=>m==null?void 0:m(K)),Me((t=I.value)==null?void 0:t.defaultView,"focus",o=>{var u,v;o.target!==window&&o.target instanceof HTMLElement&&i.value===0&&(b()||f&&y&&(n.contains(o.target)||(u=l(x.beforePanelSentinel))!=null&&u.contains(o.target)||(v=l(x.afterPanelSentinel))!=null&&v.contains(o.target)||x.closePopover()))},!0),Te(n.resolveContainers,(o,u)=>{var v;x.closePopover(),Ne(u,je.Loose)||(o.preventDefault(),(v=l(f))==null||v.focus())},C(()=>i.value===0)),()=>{let o={open:i.value===0,close:x.close};return T(D,[T(p,{},()=>Y({theirProps:{...s,...d},ourProps:{ref:e},slot:o,slots:r,attrs:d,name:"Popover"})),T(n.MainTreeNode)])}}}),ce=H({name:"PopoverButton",props:{as:{type:[Object,String],default:"button"},disabled:{type:[Boolean],default:!1},id:{type:String,default:()=>`headlessui-popover-button-${z()}`}},inheritAttrs:!1,setup(s,{attrs:r,slots:d,expose:g}){let t=J("PopoverButton"),e=C(()=>ne(t.button));g({el:t.button,$el:t.button}),le(()=>{t.buttonId.value=s.id}),pe(()=>{t.buttonId.value=null});let i=be(),f=i==null?void 0:i.closeOthers,O=Ke(),h=C(()=>O===null?!1:O.value===t.panelId.value),y=B(null),I=`headlessui-focus-sentinel-${z()}`;h.value||q(()=>{t.button.value=y.value});let L=Ve(C(()=>({as:s.as,type:r.type})),y);function x(n){var b,o,u,v,$;if(h.value){if(t.popoverState.value===1)return;switch(n.key){case N.Space:case N.Enter:n.preventDefault(),(o=(b=n.target).click)==null||o.call(b),t.closePopover(),(u=l(t.button))==null||u.focus();break}}else switch(n.key){case N.Space:case N.Enter:n.preventDefault(),n.stopPropagation(),t.popoverState.value===1&&(f==null||f(t.buttonId.value)),t.togglePopover();break;case N.Escape:if(t.popoverState.value!==0)return f==null?void 0:f(t.buttonId.value);if(!l(t.button)||(v=e.value)!=null&&v.activeElement&&!(($=l(t.button))!=null&&$.contains(e.value.activeElement)))return;n.preventDefault(),n.stopPropagation(),t.closePopover();break}}function K(n){h.value||n.key===N.Space&&n.preventDefault()}function E(n){var b,o;s.disabled||(h.value?(t.closePopover(),(b=l(t.button))==null||b.focus()):(n.preventDefault(),n.stopPropagation(),t.popoverState.value===1&&(f==null||f(t.buttonId.value)),t.togglePopover(),(o=l(t.button))==null||o.focus()))}function m(n){n.preventDefault(),n.stopPropagation()}let _=fe();function p(){let n=l(t.panel);if(!n)return;function b(){V(_.value,{[M.Forwards]:()=>j(n,F.First),[M.Backwards]:()=>j(n,F.Last)})===te.Error&&j(ae().filter(o=>o.dataset.headlessuiFocusGuard!=="true"),V(_.value,{[M.Forwards]:F.Next,[M.Backwards]:F.Previous}),{relativeTo:l(t.button)})}b()}return()=>{let n=t.popoverState.value===0,b={open:n},{id:o,...u}=s,v=h.value?{ref:y,type:L.value,onKeydown:x,onClick:E}:{ref:y,id:o,type:L.value,"aria-expanded":t.popoverState.value===0,"aria-controls":l(t.panel)?t.panelId.value:void 0,disabled:s.disabled?!0:void 0,onKeydown:x,onKeyup:K,onClick:E,onMousedown:m};return T(D,[Y({ourProps:v,theirProps:{...r,...u},slot:b,attrs:r,slots:d,name:"PopoverButton"}),n&&!h.value&&t.isPortalled.value&&T(X,{id:I,features:ee.Focusable,"data-headlessui-focus-guard":!0,as:"button",type:"button",onFocus:p})])}}}),Re=H({name:"PopoverOverlay",props:{as:{type:[Object,String],default:"div"},static:{type:Boolean,default:!1},unmount:{type:Boolean,default:!0}},setup(s,{attrs:r,slots:d}){let g=J("PopoverOverlay"),t=`headlessui-popover-overlay-${z()}`,e=he(),i=C(()=>e!==null?(e.value&G.Open)===G.Open:g.popoverState.value===0);function f(){g.closePopover()}return()=>{let O={open:g.popoverState.value===0};return Y({ourProps:{id:t,"aria-hidden":!0,onClick:f},theirProps:s,slot:O,attrs:r,slots:d,features:Z.RenderStrategy|Z.Static,visible:i.value,name:"PopoverOverlay"})}}}),Ue=H({name:"PopoverPanel",props:{as:{type:[Object,String],default:"div"},static:{type:Boolean,default:!1},unmount:{type:Boolean,default:!0},focus:{type:Boolean,default:!1},id:{type:String,default:()=>`headlessui-popover-panel-${z()}`}},inheritAttrs:!1,setup(s,{attrs:r,slots:d,expose:g}){let{focus:t}=s,e=J("PopoverPanel"),i=C(()=>ne(e.panel)),f=`headlessui-focus-sentinel-before-${z()}`,O=`headlessui-focus-sentinel-after-${z()}`;g({el:e.panel,$el:e.panel}),le(()=>{e.panelId.value=s.id}),pe(()=>{e.panelId.value=null}),de(ge,e.panelId),q(()=>{var m,_;if(!t||e.popoverState.value!==0||!e.panel)return;let p=(m=i.value)==null?void 0:m.activeElement;(_=l(e.panel))!=null&&_.contains(p)||j(l(e.panel),F.First)});let h=he(),y=C(()=>h!==null?(h.value&G.Open)===G.Open:e.popoverState.value===0);function I(m){var _,p;switch(m.key){case N.Escape:if(e.popoverState.value!==0||!l(e.panel)||i.value&&!((_=l(e.panel))!=null&&_.contains(i.value.activeElement)))return;m.preventDefault(),m.stopPropagation(),e.closePopover(),(p=l(e.button))==null||p.focus();break}}function L(m){var _,p,n,b,o;let u=m.relatedTarget;u&&l(e.panel)&&((_=l(e.panel))!=null&&_.contains(u)||(e.closePopover(),((n=(p=l(e.beforePanelSentinel))==null?void 0:p.contains)!=null&&n.call(p,u)||(o=(b=l(e.afterPanelSentinel))==null?void 0:b.contains)!=null&&o.call(b,u))&&u.focus({preventScroll:!0})))}let x=fe();function K(){let m=l(e.panel);if(!m)return;function _(){V(x.value,{[M.Forwards]:()=>{var p;j(m,F.First)===te.Error&&((p=l(e.afterPanelSentinel))==null||p.focus())},[M.Backwards]:()=>{var p;(p=l(e.button))==null||p.focus({preventScroll:!0})}})}_()}function E(){let m=l(e.panel);if(!m)return;function _(){V(x.value,{[M.Forwards]:()=>{let p=l(e.button),n=l(e.panel);if(!p)return;let b=ae(),o=b.indexOf(p),u=b.slice(0,o+1),v=[...b.slice(o+1),...u];for(let $ of v.slice())if($.dataset.headlessuiFocusGuard==="true"||n!=null&&n.contains($)){let U=v.indexOf($);U!==-1&&v.splice(U,1)}j(v,F.First,{sorted:!1})},[M.Backwards]:()=>{var p;j(m,F.Previous)===te.Error&&((p=l(e.button))==null||p.focus())}})}_()}return()=>{let m={open:e.popoverState.value===0,close:e.close},{id:_,focus:p,...n}=s,b={ref:e.panel,id:_,onKeydown:I,onFocusout:t&&e.popoverState.value===0?L:void 0,tabIndex:-1};return Y({ourProps:b,theirProps:{...r,...n},attrs:r,slot:m,slots:{...d,default:(...o)=>{var u;return[T(D,[y.value&&e.isPortalled.value&&T(X,{id:f,ref:e.beforePanelSentinel,features:ee.Focusable,"data-headlessui-focus-guard":!0,as:"button",type:"button",onFocus:K}),(u=d.default)==null?void 0:u.call(d,...o),y.value&&e.isPortalled.value&&T(X,{id:O,ref:e.afterPanelSentinel,features:ee.Focusable,"data-headlessui-focus-guard":!0,as:"button",type:"button",onFocus:E})])]}},features:Z.RenderStrategy|Z.Static,visible:y.value,name:"PopoverPanel"})}}});const We={class:"mx-auto max-w-7xl px-2 sm:px-4 lg:px-8"},Ze={class:"flex px-2 lg:px-0"},qe={class:"flex shrink-0 items-center"},Ye={"aria-label":"Global",class:"hidden lg:ml-6 lg:flex lg:items-center lg:space-x-4"},Je={class:"flex items-center lg:hidden"},Qe=a("span",{class:"absolute -inset-0.5"},null,-1),Xe=a("span",{class:"sr-only"},"Open main menu",-1),et={class:"lg:hidden"},tt={class:"divide-y divide-gray-200 rounded-lg bg-white shadow-lg ring-1 ring-black/5"},lt={class:"pb-2 pt-3"},ot={class:"flex items-center justify-between px-4"},nt=a("h1",{class:"mt-1 text-xl font-medium text-gray-600"},[R("Translations "),a("span",{class:"font-bold text-blue-600"},"UI")],-1),at={class:"-mr-2"},st=a("span",{class:"absolute -inset-0.5"},null,-1),rt=a("span",{class:"sr-only"},"Close menu",-1),ut={class:"mt-6 space-y-1 px-2"},it={class:"py-4"},ct={class:"flex items-center px-5"},dt={class:"text-base font-medium text-gray-800"},pt={class:"text-sm font-medium text-gray-500"},vt=a("span",{class:"absolute -inset-1.5"},null,-1),ft=a("span",{class:"sr-only"},"Log Out",-1),ht={class:"hidden gap-4 lg:ml-4 lg:flex lg:items-center"},mt={class:"flex"},bt=a("span",null,"Publish",-1),gt=H({__name:"navbar",setup(s){const r=Be(),d=[{name:"Translations",href:route("ltu.translation.index"),current:route().current("ltu.translation*")||route().current("ltu.source_translation*")||route().current("ltu.phrases*")},{name:"Contributors",href:route("ltu.contributors.index"),current:route().current("ltu.contributors*")},{name:"Account Settings",href:route("ltu.profile.edit"),current:route().current("ltu.profile*")}];return(g,t)=>{const e=Oe,i=ve,f=Ee;return S(),k("div",We,[c(P(_e),{class:"flex h-16 justify-between"},{default:w(({open:O})=>[a("div",Ze,[a("div",qe,[c(i,{href:g.route("ltu.translation.index")},{default:w(()=>[c(e,{class:"h-8 w-auto text-white"})]),_:1},8,["href"])]),a("nav",Ye,[(S(),k(D,null,Q(d,h=>c(i,{key:h.name,href:h.href,class:Pe(["rounded-md px-3 py-2 text-sm font-medium",[h.current?"bg-blue-500 text-white":"text-white hover:bg-blue-700 hover:text-white"]]),"aria-current":h.current?"page":void 0},{default:w(()=>[R(A(h.name),1)]),_:2},1032,["href","class","aria-current"])),64))])]),a("div",Je,[c(P(ce),{class:"relative inline-flex items-center justify-center rounded-md p-2 text-white focus:outline-none"},{default:w(()=>[Qe,Xe,c(P(ze),{class:"block size-6","aria-hidden":"true"})]),_:1})]),c(P(Le),{as:"template",show:O},{default:w(()=>[a("div",et,[c(P(re),{as:"template",enter:"duration-150 ease-out","enter-from":"opacity-0","enter-to":"opacity-100",leave:"duration-150 ease-in","leave-from":"opacity-100","leave-to":"opacity-0"},{default:w(()=>[c(P(Re),{class:"fixed inset-0 z-20 bg-black/25","aria-hidden":"true"})]),_:1}),c(P(re),{as:"template",enter:"duration-150 ease-out","enter-from":"opacity-0 scale-95","enter-to":"opacity-100 scale-100",leave:"duration-150 ease-in","leave-from":"opacity-100 scale-100","leave-to":"opacity-0 scale-95"},{default:w(()=>[c(P(Ue),{focus:"",class:"absolute right-0 top-0 z-30 w-full max-w-none origin-top p-2 transition"},{default:w(()=>[a("div",tt,[a("div",lt,[a("div",ot,[c(i,{href:g.route("ltu.translation.index"),tabindex:"-1",class:"flex items-center gap-3"},{default:w(()=>[c(e,{class:"h-8 w-auto"}),nt]),_:1},8,["href"]),a("div",at,[c(P(ce),{class:"relative inline-flex items-center justify-center rounded-md bg-white p-2 text-gray-400 hover:bg-gray-100 hover:text-gray-500 focus:outline-none"},{default:w(()=>[st,rt,c(P(De),{class:"size-6","aria-hidden":"true"})]),_:1})])]),a("div",ut,[(S(),k(D,null,Q(d,h=>c(i,{key:h.name,href:h.href,class:"block rounded-md px-3 py-2 text-base font-medium text-gray-900 hover:bg-gray-100 hover:text-gray-800"},{default:w(()=>[R(A(h.name),1)]),_:2},1032,["href"])),64))])]),a("div",it,[a("div",ct,[a("div",null,[a("div",dt,A(P(r).name),1),a("div",pt,A(P(r).email),1)]),c(i,{as:"button",method:"POST",href:"{{ route('ltu.logout') }}",class:"relative ml-auto shrink-0 rounded-full bg-white p-1 text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"},{default:w(()=>[vt,ft,c(P(ue),{class:"size-6","aria-hidden":"true"})]),_:1})])])])]),_:1})]),_:1})])]),_:2},1032,["show"]),a("div",ht,[a("div",mt,[c(i,{href:g.route("ltu.translation.publish"),class:"btn btn-xs btn-success"},{default:w(()=>[c(f,{class:"size-4"}),bt]),_:1},8,["href"])]),c(i,{as:"button",method:"POST",href:"{{ route('ltu.logout') }}",class:"flex items-center text-white hover:text-gray-900"},{default:w(()=>[c(P(ue),{class:"size-6"})]),_:1})])]),_:1})])}}}),_t={class:"min-h-full"},xt={class:"divide-y bg-blue-600 shadow"},yt={key:0,class:"overflow-y-auto bg-white py-6"},wt={class:"mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 xl:flex xl:items-center xl:justify-between"},Pt={class:"min-w-0 flex-1"},St={key:0,class:"flex","aria-label":"Breadcrumb"},kt={role:"list",class:"flex items-center space-x-4"},$t={key:1,class:"ml-4 whitespace-nowrap text-sm font-medium text-gray-500 hover:text-gray-700"},Ft=H({__name:"layout-dashboard",props:{breadcrumbs:{}},setup(s){return(r,d)=>{const g=ve;return S(),k("div",_t,[a("header",xt,[c(gt),r.breadcrumbs?(S(),k("div",yt,[a("div",wt,[a("div",Pt,[r.breadcrumbs?(S(),k("nav",St,[a("ol",kt,[a("li",null,[c(g,{href:r.route("ltu.translation.index"),class:"text-sm font-medium text-gray-500 hover:text-gray-700"},{default:w(()=>[R(" Dashboard ")]),_:1},8,["href"])]),(S(!0),k(D,null,Q(r.breadcrumbs,(t,e)=>(S(),k("li",{key:e,class:"flex items-center"},[c(P(Ae),{class:"size-5 shrink-0 text-gray-400","aria-hidden":"true"}),t.link?(S(),Se(g,{key:0,href:t.link,class:"ml-4 text-sm font-medium text-gray-500 hover:text-gray-700"},{default:w(()=>[R(A(t.label),1)]),_:2},1032,["href"])):(S(),k("span",$t,A(t.label),1))]))),128))])])):se("",!0)])])])):se("",!0)]),a("main",null,[ke(r.$slots,"default")]),c(P($e))])}}});export{Ft as _};