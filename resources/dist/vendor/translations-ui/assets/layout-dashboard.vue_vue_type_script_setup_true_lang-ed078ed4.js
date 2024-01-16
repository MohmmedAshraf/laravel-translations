import{o as T,c as O,a as v,l as $,z as ee,$ as Z,d as A,b as D,y as se,A as ue,B as te,Y,E as V,F as G,h as w,w as E,x as X,n as ve,g as W,t as H,u as F,I as Ce,k as xe,e as Ne,i as pe,f as Ae,a0 as Le}from"./app-7542d82e.js";import{_ as je}from"./base-button.vue_vue_type_script_setup_true_lang-8a7810cf.js";import{_ as Ve}from"./logo-691e9cd3.js";import{u as Ke}from"./use-auth-1fe8432d.js";import{o as s,y as _e,w as we,h as Se,d as Pe,u as U,l as N,H as K,t as L,p as ie,e as q,b as k,v as He,N as C,_ as Ie,O as ze,m as ce,E as de,V as Ge,g as Ue,i as Ye,n as ke,f as le,a as oe,P as z,j,c as re,S as qe,k as fe}from"./transition-738a3b62.js";import{p as We}from"./use-tree-walker-bde861a2.js";function Ze(t,l){return T(),O("svg",{xmlns:"http://www.w3.org/2000/svg",viewBox:"0 0 20 20",fill:"currentColor","aria-hidden":"true","data-slot":"icon"},[v("path",{"fill-rule":"evenodd",d:"M8.22 5.22a.75.75 0 0 1 1.06 0l4.25 4.25a.75.75 0 0 1 0 1.06l-4.25 4.25a.75.75 0 0 1-1.06-1.06L11.94 10 8.22 6.28a.75.75 0 0 1 0-1.06Z","clip-rule":"evenodd"})])}function Qe(t,l){return T(),O("svg",{xmlns:"http://www.w3.org/2000/svg",fill:"none",viewBox:"0 0 24 24","stroke-width":"1.5",stroke:"currentColor","aria-hidden":"true","data-slot":"icon"},[v("path",{"stroke-linecap":"round","stroke-linejoin":"round",d:"M8.25 9V5.25A2.25 2.25 0 0 1 10.5 3h6a2.25 2.25 0 0 1 2.25 2.25v13.5A2.25 2.25 0 0 1 16.5 21h-6a2.25 2.25 0 0 1-2.25-2.25V15M12 9l3 3m0 0-3 3m3-3H2.25"})])}function Je(t,l){return T(),O("svg",{xmlns:"http://www.w3.org/2000/svg",fill:"none",viewBox:"0 0 24 24","stroke-width":"1.5",stroke:"currentColor","aria-hidden":"true","data-slot":"icon"},[v("path",{"stroke-linecap":"round","stroke-linejoin":"round",d:"M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"})])}function Xe(t,l){return T(),O("svg",{xmlns:"http://www.w3.org/2000/svg",fill:"none",viewBox:"0 0 24 24","stroke-width":"1.5",stroke:"currentColor","aria-hidden":"true","data-slot":"icon"},[v("path",{"stroke-linecap":"round","stroke-linejoin":"round",d:"M6 18 18 6M6 6l12 12"})])}function et(t){throw new Error("Unexpected object: "+t)}var R=(t=>(t[t.First=0]="First",t[t.Previous=1]="Previous",t[t.Next=2]="Next",t[t.Last=3]="Last",t[t.Specific=4]="Specific",t[t.Nothing=5]="Nothing",t))(R||{});function tt(t,l){let r=l.resolveItems();if(r.length<=0)return null;let c=l.resolveActiveIndex(),e=c??-1,a=(()=>{switch(t.focus){case 0:return r.findIndex(o=>!l.resolveDisabled(o));case 1:{let o=r.slice().reverse().findIndex((i,b,p)=>e!==-1&&p.length-b-1>=e?!1:!l.resolveDisabled(i));return o===-1?o:r.length-1-o}case 2:return r.findIndex((o,i)=>i<=e?!1:!l.resolveDisabled(o));case 3:{let o=r.slice().reverse().findIndex(i=>!l.resolveDisabled(i));return o===-1?o:r.length-1-o}case 4:return r.findIndex(o=>l.resolveId(o)===t.id);case 5:return null;default:et(t)}})();return a===-1?c:a}function me(t,l){if(t)return t;let r=l??"button";if(typeof r=="string"&&r.toLowerCase()==="button")return"button"}function $e(t,l){let r=$(me(t.value.type,t.value.as));return ee(()=>{r.value=me(t.value.type,t.value.as)}),Z(()=>{var c;r.value||s(l)&&s(l)instanceof HTMLButtonElement&&!((c=s(l))!=null&&c.hasAttribute("type"))&&(r.value="button")}),r}function he(t){return[t.screenX,t.screenY]}function nt(){let t=$([-1,-1]);return{wasMoved(l){let r=he(l);return t.value[0]===r[0]&&t.value[1]===r[1]?!1:(t.value=r,!0)},update(l){t.value=he(l)}}}let ge=/([\u2700-\u27BF]|[\uE000-\uF8FF]|\uD83C[\uDC00-\uDFFF]|\uD83D[\uDC00-\uDFFF]|[\u2011-\u26FF]|\uD83E[\uDD10-\uDDFF])/g;function be(t){var l,r;let c=(l=t.innerText)!=null?l:"",e=t.cloneNode(!0);if(!(e instanceof HTMLElement))return c;let a=!1;for(let i of e.querySelectorAll('[hidden],[aria-hidden],[role="img"]'))i.remove(),a=!0;let o=a?(r=e.innerText)!=null?r:"":c;return ge.test(o)&&(o=o.replace(ge,"")),o}function at(t){let l=t.getAttribute("aria-label");if(typeof l=="string")return l.trim();let r=t.getAttribute("aria-labelledby");if(r){let c=r.split(" ").map(e=>{let a=document.getElementById(e);if(a){let o=a.getAttribute("aria-label");return typeof o=="string"?o.trim():be(a).trim()}return null}).filter(Boolean);if(c.length>0)return c.join(", ")}return be(t).trim()}function lt(t){let l=$(""),r=$("");return()=>{let c=s(t);if(!c)return"";let e=c.innerText;if(l.value===e)return r.value;let a=at(c).trim().toLowerCase();return l.value=e,r.value=a,a}}var ot=(t=>(t[t.Open=0]="Open",t[t.Closed=1]="Closed",t))(ot||{}),rt=(t=>(t[t.Pointer=0]="Pointer",t[t.Other=1]="Other",t))(rt||{});function st(t){requestAnimationFrame(()=>requestAnimationFrame(t))}let Me=Symbol("MenuContext");function ne(t){let l=te(Me,null);if(l===null){let r=new Error(`<${t} /> is missing a parent <Menu /> component.`);throw Error.captureStackTrace&&Error.captureStackTrace(r,ne),r}return l}let ut=A({name:"Menu",props:{as:{type:[Object,String],default:"template"}},setup(t,{slots:l,attrs:r}){let c=$(1),e=$(null),a=$(null),o=$([]),i=$(""),b=$(null),p=$(1);function n(g=d=>d){let d=b.value!==null?o.value[b.value]:null,y=ze(g(o.value.slice()),h=>s(h.dataRef.domRef)),u=d?y.indexOf(d):null;return u===-1&&(u=null),{items:y,activeItemIndex:u}}let S={menuState:c,buttonRef:e,itemsRef:a,items:o,searchQuery:i,activeItemIndex:b,activationTrigger:p,closeMenu:()=>{c.value=1,b.value=null},openMenu:()=>c.value=0,goToItem(g,d,y){let u=n(),h=tt(g===R.Specific?{focus:R.Specific,id:d}:{focus:g},{resolveItems:()=>u.items,resolveActiveIndex:()=>u.activeItemIndex,resolveId:P=>P.id,resolveDisabled:P=>P.dataRef.disabled});i.value="",b.value=h,p.value=y??1,o.value=u.items},search(g){let d=i.value!==""?0:1;i.value+=g.toLowerCase();let y=(b.value!==null?o.value.slice(b.value+d).concat(o.value.slice(0,b.value+d)):o.value).find(h=>h.dataRef.textValue.startsWith(i.value)&&!h.dataRef.disabled),u=y?o.value.indexOf(y):-1;u===-1||u===b.value||(b.value=u,p.value=1)},clearSearch(){i.value=""},registerItem(g,d){let y=n(u=>[...u,{id:g,dataRef:d}]);o.value=y.items,b.value=y.activeItemIndex,p.value=1},unregisterItem(g){let d=n(y=>{let u=y.findIndex(h=>h.id===g);return u!==-1&&y.splice(u,1),y});o.value=d.items,b.value=d.activeItemIndex,p.value=1}};return _e([e,a],(g,d)=>{var y;S.closeMenu(),we(d,Se.Loose)||(g.preventDefault(),(y=s(e))==null||y.focus())},D(()=>c.value===0)),se(Me,S),Pe(D(()=>U(c.value,{0:N.Open,1:N.Closed}))),()=>{let g={open:c.value===0,close:S.closeMenu};return K({ourProps:{},theirProps:t,slot:g,slots:l,attrs:r,name:"Menu"})}}}),it=A({name:"MenuButton",props:{disabled:{type:Boolean,default:!1},as:{type:[Object,String],default:"button"},id:{type:String,default:()=>`headlessui-menu-button-${L()}`}},setup(t,{attrs:l,slots:r,expose:c}){let e=ne("MenuButton");c({el:e.buttonRef,$el:e.buttonRef});function a(p){switch(p.key){case k.Space:case k.Enter:case k.ArrowDown:p.preventDefault(),p.stopPropagation(),e.openMenu(),Y(()=>{var n;(n=s(e.itemsRef))==null||n.focus({preventScroll:!0}),e.goToItem(R.First)});break;case k.ArrowUp:p.preventDefault(),p.stopPropagation(),e.openMenu(),Y(()=>{var n;(n=s(e.itemsRef))==null||n.focus({preventScroll:!0}),e.goToItem(R.Last)});break}}function o(p){switch(p.key){case k.Space:p.preventDefault();break}}function i(p){t.disabled||(e.menuState.value===0?(e.closeMenu(),Y(()=>{var n;return(n=s(e.buttonRef))==null?void 0:n.focus({preventScroll:!0})})):(p.preventDefault(),e.openMenu(),st(()=>{var n;return(n=s(e.itemsRef))==null?void 0:n.focus({preventScroll:!0})})))}let b=$e(D(()=>({as:t.as,type:l.type})),e.buttonRef);return()=>{var p;let n={open:e.menuState.value===0},{id:S,...g}=t,d={ref:e.buttonRef,id:S,type:b.value,"aria-haspopup":"menu","aria-controls":(p=s(e.itemsRef))==null?void 0:p.id,"aria-expanded":e.menuState.value===0,onKeydown:a,onKeyup:o,onClick:i};return K({ourProps:d,theirProps:g,slot:n,attrs:l,slots:r,name:"MenuButton"})}}}),ct=A({name:"MenuItems",props:{as:{type:[Object,String],default:"div"},static:{type:Boolean,default:!1},unmount:{type:Boolean,default:!0},id:{type:String,default:()=>`headlessui-menu-items-${L()}`}},setup(t,{attrs:l,slots:r,expose:c}){let e=ne("MenuItems"),a=$(null);c({el:e.itemsRef,$el:e.itemsRef}),We({container:D(()=>s(e.itemsRef)),enabled:D(()=>e.menuState.value===0),accept(n){return n.getAttribute("role")==="menuitem"?NodeFilter.FILTER_REJECT:n.hasAttribute("role")?NodeFilter.FILTER_SKIP:NodeFilter.FILTER_ACCEPT},walk(n){n.setAttribute("role","none")}});function o(n){var S;switch(a.value&&clearTimeout(a.value),n.key){case k.Space:if(e.searchQuery.value!=="")return n.preventDefault(),n.stopPropagation(),e.search(n.key);case k.Enter:if(n.preventDefault(),n.stopPropagation(),e.activeItemIndex.value!==null){let g=e.items.value[e.activeItemIndex.value];(S=s(g.dataRef.domRef))==null||S.click()}e.closeMenu(),Ie(s(e.buttonRef));break;case k.ArrowDown:return n.preventDefault(),n.stopPropagation(),e.goToItem(R.Next);case k.ArrowUp:return n.preventDefault(),n.stopPropagation(),e.goToItem(R.Previous);case k.Home:case k.PageUp:return n.preventDefault(),n.stopPropagation(),e.goToItem(R.First);case k.End:case k.PageDown:return n.preventDefault(),n.stopPropagation(),e.goToItem(R.Last);case k.Escape:n.preventDefault(),n.stopPropagation(),e.closeMenu(),Y(()=>{var g;return(g=s(e.buttonRef))==null?void 0:g.focus({preventScroll:!0})});break;case k.Tab:n.preventDefault(),n.stopPropagation(),e.closeMenu(),Y(()=>He(s(e.buttonRef),n.shiftKey?C.Previous:C.Next));break;default:n.key.length===1&&(e.search(n.key),a.value=setTimeout(()=>e.clearSearch(),350));break}}function i(n){switch(n.key){case k.Space:n.preventDefault();break}}let b=ie(),p=D(()=>b!==null?(b.value&N.Open)===N.Open:e.menuState.value===0);return()=>{var n,S;let g={open:e.menuState.value===0},{id:d,...y}=t,u={"aria-activedescendant":e.activeItemIndex.value===null||(n=e.items.value[e.activeItemIndex.value])==null?void 0:n.id,"aria-labelledby":(S=s(e.buttonRef))==null?void 0:S.id,id:d,onKeydown:o,onKeyup:i,role:"menu",tabIndex:0,ref:e.itemsRef};return K({ourProps:u,theirProps:y,slot:g,attrs:l,slots:r,features:q.RenderStrategy|q.Static,visible:p.value,name:"MenuItems"})}}}),dt=A({name:"MenuItem",inheritAttrs:!1,props:{as:{type:[Object,String],default:"template"},disabled:{type:Boolean,default:!1},id:{type:String,default:()=>`headlessui-menu-item-${L()}`}},setup(t,{slots:l,attrs:r,expose:c}){let e=ne("MenuItem"),a=$(null);c({el:a,$el:a});let o=D(()=>e.activeItemIndex.value!==null?e.items.value[e.activeItemIndex.value].id===t.id:!1),i=lt(a),b=D(()=>({disabled:t.disabled,get textValue(){return i()},domRef:a}));ee(()=>e.registerItem(t.id,b)),ue(()=>e.unregisterItem(t.id)),Z(()=>{e.menuState.value===0&&o.value&&e.activationTrigger.value!==0&&Y(()=>{var u,h;return(h=(u=s(a))==null?void 0:u.scrollIntoView)==null?void 0:h.call(u,{block:"nearest"})})});function p(u){if(t.disabled)return u.preventDefault();e.closeMenu(),Ie(s(e.buttonRef))}function n(){if(t.disabled)return e.goToItem(R.Nothing);e.goToItem(R.Specific,t.id)}let S=nt();function g(u){S.update(u)}function d(u){S.wasMoved(u)&&(t.disabled||o.value||e.goToItem(R.Specific,t.id,0))}function y(u){S.wasMoved(u)&&(t.disabled||o.value&&e.goToItem(R.Nothing))}return()=>{let{disabled:u}=t,h={active:o.value,disabled:u,close:e.closeMenu},{id:P,..._}=t;return K({ourProps:{id:P,ref:a,role:"menuitem",tabIndex:u===!0?void 0:-1,"aria-disabled":u===!0?!0:void 0,disabled:void 0,onClick:p,onFocus:n,onPointerenter:g,onMouseenter:g,onPointermove:d,onMousemove:d,onPointerleave:y,onMouseleave:y},theirProps:{...r,..._},slot:h,attrs:r,slots:l,name:"MenuItem"})}}});var vt=(t=>(t[t.Open=0]="Open",t[t.Closed=1]="Closed",t))(vt||{});let Ee=Symbol("PopoverContext");function ae(t){let l=te(Ee,null);if(l===null){let r=new Error(`<${t} /> is missing a parent <${Te.name} /> component.`);throw Error.captureStackTrace&&Error.captureStackTrace(r,ae),r}return l}let pt=Symbol("PopoverGroupContext");function Fe(){return te(pt,null)}let De=Symbol("PopoverPanelContext");function ft(){return te(De,null)}let Te=A({name:"Popover",inheritAttrs:!1,props:{as:{type:[Object,String],default:"div"}},setup(t,{slots:l,attrs:r,expose:c}){var e;let a=$(null);c({el:a,$el:a});let o=$(1),i=$(null),b=$(null),p=$(null),n=$(null),S=D(()=>ce(a)),g=D(()=>{var f,x;if(!s(i)||!s(n))return!1;for(let J of document.querySelectorAll("body > *"))if(Number(J==null?void 0:J.contains(s(i)))^Number(J==null?void 0:J.contains(s(n))))return!0;let I=de(),B=I.indexOf(s(i)),Q=(B+I.length-1)%I.length,Oe=(B+1)%I.length,Re=I[Q],Be=I[Oe];return!((f=s(n))!=null&&f.contains(Re))&&!((x=s(n))!=null&&x.contains(Be))}),d={popoverState:o,buttonId:$(null),panelId:$(null),panel:n,button:i,isPortalled:g,beforePanelSentinel:b,afterPanelSentinel:p,togglePopover(){o.value=U(o.value,{0:1,1:0})},closePopover(){o.value!==1&&(o.value=1)},close(f){d.closePopover();let x=(()=>f?f instanceof HTMLElement?f:f.value instanceof HTMLElement?s(f):s(d.button):s(d.button))();x==null||x.focus()}};se(Ee,d),Pe(D(()=>U(o.value,{0:N.Open,1:N.Closed})));let y={buttonId:d.buttonId,panelId:d.panelId,close(){d.closePopover()}},u=Fe(),h=u==null?void 0:u.registerPopover,[P,_]=Ge(),m=Ue({mainTreeNodeRef:u==null?void 0:u.mainTreeNodeRef,portals:P,defaultContainers:[i,n]});function M(){var f,x,I,B;return(B=u==null?void 0:u.isFocusWithinPopoverGroup())!=null?B:((f=S.value)==null?void 0:f.activeElement)&&(((x=s(i))==null?void 0:x.contains(S.value.activeElement))||((I=s(n))==null?void 0:I.contains(S.value.activeElement)))}return Z(()=>h==null?void 0:h(y)),Ye((e=S.value)==null?void 0:e.defaultView,"focus",f=>{var x,I;f.target!==window&&f.target instanceof HTMLElement&&o.value===0&&(M()||i&&n&&(m.contains(f.target)||(x=s(d.beforePanelSentinel))!=null&&x.contains(f.target)||(I=s(d.afterPanelSentinel))!=null&&I.contains(f.target)||d.closePopover()))},!0),_e(m.resolveContainers,(f,x)=>{var I;d.closePopover(),we(x,Se.Loose)||(f.preventDefault(),(I=s(i))==null||I.focus())},D(()=>o.value===0)),()=>{let f={open:o.value===0,close:d.close};return V(G,[V(_,{},()=>K({theirProps:{...t,...r},ourProps:{ref:a},slot:f,slots:l,attrs:r,name:"Popover"})),V(m.MainTreeNode)])}}}),ye=A({name:"PopoverButton",props:{as:{type:[Object,String],default:"button"},disabled:{type:[Boolean],default:!1},id:{type:String,default:()=>`headlessui-popover-button-${L()}`}},inheritAttrs:!1,setup(t,{attrs:l,slots:r,expose:c}){let e=ae("PopoverButton"),a=D(()=>ce(e.button));c({el:e.button,$el:e.button}),ee(()=>{e.buttonId.value=t.id}),ue(()=>{e.buttonId.value=null});let o=Fe(),i=o==null?void 0:o.closeOthers,b=ft(),p=D(()=>b===null?!1:b.value===e.panelId.value),n=$(null),S=`headlessui-focus-sentinel-${L()}`;p.value||Z(()=>{e.button.value=n.value});let g=$e(D(()=>({as:t.as,type:l.type})),n);function d(m){var M,f,x,I,B;if(p.value){if(e.popoverState.value===1)return;switch(m.key){case k.Space:case k.Enter:m.preventDefault(),(f=(M=m.target).click)==null||f.call(M),e.closePopover(),(x=s(e.button))==null||x.focus();break}}else switch(m.key){case k.Space:case k.Enter:m.preventDefault(),m.stopPropagation(),e.popoverState.value===1&&(i==null||i(e.buttonId.value)),e.togglePopover();break;case k.Escape:if(e.popoverState.value!==0)return i==null?void 0:i(e.buttonId.value);if(!s(e.button)||(I=a.value)!=null&&I.activeElement&&!((B=s(e.button))!=null&&B.contains(a.value.activeElement)))return;m.preventDefault(),m.stopPropagation(),e.closePopover();break}}function y(m){p.value||m.key===k.Space&&m.preventDefault()}function u(m){var M,f;t.disabled||(p.value?(e.closePopover(),(M=s(e.button))==null||M.focus()):(m.preventDefault(),m.stopPropagation(),e.popoverState.value===1&&(i==null||i(e.buttonId.value)),e.togglePopover(),(f=s(e.button))==null||f.focus()))}function h(m){m.preventDefault(),m.stopPropagation()}let P=ke();function _(){let m=s(e.panel);if(!m)return;function M(){U(P.value,{[j.Forwards]:()=>z(m,C.First),[j.Backwards]:()=>z(m,C.Last)})===re.Error&&z(de().filter(f=>f.dataset.headlessuiFocusGuard!=="true"),U(P.value,{[j.Forwards]:C.Next,[j.Backwards]:C.Previous}),{relativeTo:s(e.button)})}M()}return()=>{let m=e.popoverState.value===0,M={open:m},{id:f,...x}=t,I=p.value?{ref:n,type:g.value,onKeydown:d,onClick:u}:{ref:n,id:f,type:g.value,"aria-expanded":e.popoverState.value===0,"aria-controls":s(e.panel)?e.panelId.value:void 0,disabled:t.disabled?!0:void 0,onKeydown:d,onKeyup:y,onClick:u,onMousedown:h};return V(G,[K({ourProps:I,theirProps:{...l,...x},slot:M,attrs:l,slots:r,name:"PopoverButton"}),m&&!p.value&&e.isPortalled.value&&V(le,{id:S,features:oe.Focusable,"data-headlessui-focus-guard":!0,as:"button",type:"button",onFocus:_})])}}}),mt=A({name:"PopoverOverlay",props:{as:{type:[Object,String],default:"div"},static:{type:Boolean,default:!1},unmount:{type:Boolean,default:!0}},setup(t,{attrs:l,slots:r}){let c=ae("PopoverOverlay"),e=`headlessui-popover-overlay-${L()}`,a=ie(),o=D(()=>a!==null?(a.value&N.Open)===N.Open:c.popoverState.value===0);function i(){c.closePopover()}return()=>{let b={open:c.popoverState.value===0};return K({ourProps:{id:e,"aria-hidden":!0,onClick:i},theirProps:t,slot:b,attrs:l,slots:r,features:q.RenderStrategy|q.Static,visible:o.value,name:"PopoverOverlay"})}}}),ht=A({name:"PopoverPanel",props:{as:{type:[Object,String],default:"div"},static:{type:Boolean,default:!1},unmount:{type:Boolean,default:!0},focus:{type:Boolean,default:!1},id:{type:String,default:()=>`headlessui-popover-panel-${L()}`}},inheritAttrs:!1,setup(t,{attrs:l,slots:r,expose:c}){let{focus:e}=t,a=ae("PopoverPanel"),o=D(()=>ce(a.panel)),i=`headlessui-focus-sentinel-before-${L()}`,b=`headlessui-focus-sentinel-after-${L()}`;c({el:a.panel,$el:a.panel}),ee(()=>{a.panelId.value=t.id}),ue(()=>{a.panelId.value=null}),se(De,a.panelId),Z(()=>{var h,P;if(!e||a.popoverState.value!==0||!a.panel)return;let _=(h=o.value)==null?void 0:h.activeElement;(P=s(a.panel))!=null&&P.contains(_)||z(s(a.panel),C.First)});let p=ie(),n=D(()=>p!==null?(p.value&N.Open)===N.Open:a.popoverState.value===0);function S(h){var P,_;switch(h.key){case k.Escape:if(a.popoverState.value!==0||!s(a.panel)||o.value&&!((P=s(a.panel))!=null&&P.contains(o.value.activeElement)))return;h.preventDefault(),h.stopPropagation(),a.closePopover(),(_=s(a.button))==null||_.focus();break}}function g(h){var P,_,m,M,f;let x=h.relatedTarget;x&&s(a.panel)&&((P=s(a.panel))!=null&&P.contains(x)||(a.closePopover(),((m=(_=s(a.beforePanelSentinel))==null?void 0:_.contains)!=null&&m.call(_,x)||(f=(M=s(a.afterPanelSentinel))==null?void 0:M.contains)!=null&&f.call(M,x))&&x.focus({preventScroll:!0})))}let d=ke();function y(){let h=s(a.panel);if(!h)return;function P(){U(d.value,{[j.Forwards]:()=>{var _;z(h,C.First)===re.Error&&((_=s(a.afterPanelSentinel))==null||_.focus())},[j.Backwards]:()=>{var _;(_=s(a.button))==null||_.focus({preventScroll:!0})}})}P()}function u(){let h=s(a.panel);if(!h)return;function P(){U(d.value,{[j.Forwards]:()=>{let _=s(a.button),m=s(a.panel);if(!_)return;let M=de(),f=M.indexOf(_),x=M.slice(0,f+1),I=[...M.slice(f+1),...x];for(let B of I.slice())if(B.dataset.headlessuiFocusGuard==="true"||m!=null&&m.contains(B)){let Q=I.indexOf(B);Q!==-1&&I.splice(Q,1)}z(I,C.First,{sorted:!1})},[j.Backwards]:()=>{var _;z(h,C.Previous)===re.Error&&((_=s(a.button))==null||_.focus())}})}P()}return()=>{let h={open:a.popoverState.value===0,close:a.close},{id:P,focus:_,...m}=t,M={ref:a.panel,id:P,onKeydown:S,onFocusout:e&&a.popoverState.value===0?g:void 0,tabIndex:-1};return K({ourProps:M,theirProps:{...l,...m},attrs:l,slot:h,slots:{...r,default:(...f)=>{var x;return[V(G,[n.value&&a.isPortalled.value&&V(le,{id:i,ref:a.beforePanelSentinel,features:oe.Focusable,"data-headlessui-focus-guard":!0,as:"button",type:"button",onFocus:y}),(x=r.default)==null?void 0:x.call(r,...f),n.value&&a.isPortalled.value&&V(le,{id:b,ref:a.afterPanelSentinel,features:oe.Focusable,"data-headlessui-focus-guard":!0,as:"button",type:"button",onFocus:u})])]}},features:q.RenderStrategy|q.Static,visible:n.value,name:"PopoverPanel"})}}});const gt={class:"mx-auto max-w-7xl px-2 sm:px-4 lg:px-8"},bt={class:"flex px-2 lg:px-0"},yt={class:"flex shrink-0 items-center"},xt={"aria-label":"Global",class:"hidden lg:ml-6 lg:flex lg:items-center lg:space-x-4"},_t={class:"flex items-center lg:hidden"},wt=v("span",{class:"absolute -inset-0.5"},null,-1),St=v("span",{class:"sr-only"},"Open main menu",-1),Pt={class:"lg:hidden"},It={class:"divide-y divide-gray-200 rounded-lg bg-white shadow-lg ring-1 ring-black/5"},kt={class:"pb-2 pt-3"},$t={class:"flex items-center justify-between px-4"},Mt={class:"-mr-2"},Et=v("span",{class:"absolute -inset-0.5"},null,-1),Ft=v("span",{class:"sr-only"},"Close menu",-1),Dt={class:"mt-3 space-y-1 px-2"},Tt={class:"py-4"},Ot={class:"flex items-center px-5"},Rt=v("div",{class:"shrink-0"},[v("div",{class:"h-10 w-10 rounded-full bg-gray-400"})],-1),Bt={class:"ml-3"},Ct={class:"text-base font-medium text-gray-800"},Nt={class:"text-sm font-medium text-gray-500"},At={type:"button",class:"relative ml-auto shrink-0 rounded-full bg-white p-1 text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"},Lt=v("span",{class:"absolute -inset-1.5"},null,-1),jt=v("span",{class:"sr-only"},"Log Out",-1),Vt={class:"hidden lg:ml-4 lg:flex lg:items-center"},Kt={class:"flex"},Ht=v("span",null,"Publish",-1),zt=v("span",{class:"sr-only"},"Open user menu",-1),Gt=v("div",{class:"h-8 w-8 rounded-full bg-gray-400"},null,-1),Ut=A({__name:"navbar",setup(t){const l=Ke(),r=[{name:"Translations",href:route("ltu.translation.index"),current:route().current("ltu.translation.*")||route().current("ltu.source_translation.*")||route().current("ltu.phrases.*")},{name:"Contributors",href:route("ltu.contributors.index"),current:route().current("ltu.contributors.*")}],c=[{name:"Your Profile",href:route("ltu.profile.edit"),method:"get"},{name:"Sign out",href:route("ltu.logout"),method:"post"}];return(e,a)=>{const o=Ve,i=xe,b=je;return T(),O("div",gt,[w(F(Te),{class:"flex h-16 justify-between"},{default:E(({open:p})=>[v("div",bt,[v("div",yt,[w(i,{href:e.route("ltu.translation.index")},{default:E(()=>[w(o,{class:"h-8 w-auto text-white"})]),_:1},8,["href"])]),v("nav",xt,[(T(),O(G,null,X(r,n=>w(i,{key:n.name,href:n.href,class:ve(["rounded-md px-3 py-2 text-sm font-medium",[n.current?"bg-blue-500 text-white":"text-white hover:bg-blue-700 hover:text-white"]]),"aria-current":n.current?"page":void 0},{default:E(()=>[W(H(n.name),1)]),_:2},1032,["href","class","aria-current"])),64))])]),v("div",_t,[w(F(ye),{class:"relative inline-flex items-center justify-center rounded-md p-2 text-gray-400 hover:bg-gray-100 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-blue-500"},{default:E(()=>[wt,St,w(F(Je),{class:"block h-6 w-6","aria-hidden":"true"})]),_:1})]),w(F(qe),{as:"template",show:p},{default:E(()=>[v("div",Pt,[w(F(fe),{as:"template",enter:"duration-150 ease-out","enter-from":"opacity-0","enter-to":"opacity-100",leave:"duration-150 ease-in","leave-from":"opacity-100","leave-to":"opacity-0"},{default:E(()=>[w(F(mt),{class:"fixed inset-0 z-20 bg-black/25","aria-hidden":"true"})]),_:1}),w(F(fe),{as:"template",enter:"duration-150 ease-out","enter-from":"opacity-0 scale-95","enter-to":"opacity-100 scale-100",leave:"duration-150 ease-in","leave-from":"opacity-100 scale-100","leave-to":"opacity-0 scale-95"},{default:E(()=>[w(F(ht),{focus:"",class:"absolute right-0 top-0 z-30 w-full max-w-none origin-top p-2 transition"},{default:E(()=>[v("div",It,[v("div",kt,[v("div",$t,[v("div",null,[w(o,{class:"h-8 w-auto"})]),v("div",Mt,[w(F(ye),{class:"relative inline-flex items-center justify-center rounded-md bg-white p-2 text-gray-400 hover:bg-gray-100 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-blue-500"},{default:E(()=>[Et,Ft,w(F(Xe),{class:"h-6 w-6","aria-hidden":"true"})]),_:1})])]),v("div",Dt,[(T(),O(G,null,X(r,n=>w(i,{key:n.name,href:n.href,class:"block rounded-md px-3 py-2 text-base font-medium text-gray-900 hover:bg-gray-100 hover:text-gray-800"},{default:E(()=>[W(H(n.name),1)]),_:2},1032,["href"])),64))])]),v("div",Tt,[v("div",Ot,[Rt,v("div",Bt,[v("div",Ct,H(F(l).name),1),v("div",Nt,H(F(l).email),1)]),v("button",At,[Lt,jt,w(F(Qe),{class:"h-6 w-6","aria-hidden":"true"})])])])])]),_:1})]),_:1})])]),_:2},1032,["show"]),v("div",Vt,[v("div",Kt,[w(b,{variant:"success",size:"xs"},{default:E(()=>[Ht]),_:1})]),w(F(ut),{as:"div",class:"relative ml-4 shrink-0"},{default:E(()=>[v("div",null,[w(F(it),{class:"flex rounded-full bg-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"},{default:E(()=>[zt,Gt]),_:1})]),w(Ce,{"enter-active-class":"transition ease-out duration-100","enter-from-class":"transform opacity-0 scale-95","enter-to-class":"transform opacity-100 scale-100","leave-active-class":"transition ease-in duration-75","leave-from-class":"transform opacity-100 scale-100","leave-to-class":"transform opacity-0 scale-95"},{default:E(()=>[w(F(ct),{class:"absolute right-0 z-30 mt-2 w-48 origin-top-right rounded-md bg-white py-1 shadow-lg ring-1 ring-black/5 focus:outline-none"},{default:E(()=>[(T(),O(G,null,X(c,n=>w(F(dt),{key:n.name},{default:E(({active:S})=>[w(i,{href:n.href,class:ve(["block px-4 py-2 text-sm text-gray-700",[S?"bg-gray-100":""]])},{default:E(()=>[W(H(n.name),1)]),_:2},1032,["href","class"])]),_:2},1024)),64))]),_:1})]),_:1})]),_:1})])]),_:1})])}}}),Yt={class:"min-h-full"},qt={class:"divide-y bg-blue-600 shadow"},Wt={key:0,class:"overflow-y-auto bg-white py-6"},Zt={class:"mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 xl:flex xl:items-center xl:justify-between"},Qt={class:"min-w-0 flex-1"},Jt={key:0,class:"flex","aria-label":"Breadcrumb"},Xt={role:"list",class:"flex items-center space-x-4"},en={key:1,class:"ml-4 whitespace-nowrap text-sm font-medium text-gray-500 hover:text-gray-700"},sn=A({__name:"layout-dashboard",props:{breadcrumbs:{}},setup(t){return(l,r)=>{const c=xe;return T(),O("div",Yt,[v("header",qt,[w(Ut),l.breadcrumbs?(T(),O("div",Wt,[v("div",Zt,[v("div",Qt,[l.breadcrumbs?(T(),O("nav",Jt,[v("ol",Xt,[v("li",null,[w(c,{href:l.route("ltu.translation.index"),class:"text-sm font-medium text-gray-500 hover:text-gray-700"},{default:E(()=>[W(" Dashboard ")]),_:1},8,["href"])]),(T(!0),O(G,null,X(l.breadcrumbs,(e,a)=>(T(),O("li",{key:a,class:"flex items-center"},[w(F(Ze),{class:"h-5 w-5 shrink-0 text-gray-400","aria-hidden":"true"}),e.link?(T(),Ne(c,{key:0,href:e.link,class:"ml-4 text-sm font-medium text-gray-500 hover:text-gray-700"},{default:E(()=>[W(H(e.label),1)]),_:2},1032,["href"])):(T(),O("span",en,H(e.label),1))]))),128))])])):pe("",!0)])])])):pe("",!0)]),v("main",null,[Ae(l.$slots,"default")]),w(F(Le))])}}});export{sn as _};
