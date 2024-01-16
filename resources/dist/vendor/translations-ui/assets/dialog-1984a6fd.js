import{m as ue,n as $e,f as ne,a as le,H as U,q as $,o as b,r as ie,i as se,u as W,j as H,P as B,N as T,c as Oe,t as J,s as de,p as Ae,l as N,V as Pe,g as Re,y as Me,U as Ce,x as ke,z as ae,e as oe,b as xe}from"./transition-738a3b62.js";import{d as V,l as g,b as c,z as O,A as P,E as S,F as He,$ as Q,D as q,y as X,B as I,u as Ne,a7 as je,Y as Be}from"./app-7542d82e.js";function We(){return/iPhone/gi.test(window.navigator.platform)||/Mac/gi.test(window.navigator.platform)&&window.navigator.maxTouchPoints>0}function Ue(e){function n(){document.readyState!=="loading"&&(e(),document.removeEventListener("DOMContentLoaded",n))}typeof window<"u"&&typeof document<"u"&&(document.addEventListener("DOMContentLoaded",n),n())}function ce(e){if(!e)return new Set;if(typeof e=="function")return new Set(e());let n=new Set;for(let t of e.value){let a=b(t);a instanceof HTMLElement&&n.add(a)}return n}var fe=(e=>(e[e.None=1]="None",e[e.InitialFocus=2]="InitialFocus",e[e.TabLock=4]="TabLock",e[e.FocusLock=8]="FocusLock",e[e.RestoreFocus=16]="RestoreFocus",e[e.All=30]="All",e))(fe||{});let C=Object.assign(V({name:"FocusTrap",props:{as:{type:[Object,String],default:"div"},initialFocus:{type:Object,default:null},features:{type:Number,default:30},containers:{type:[Object,Function],default:g(new Set)}},inheritAttrs:!1,setup(e,{attrs:n,slots:t,expose:a}){let l=g(null);a({el:l,$el:l});let o=c(()=>ue(l)),r=g(!1);O(()=>r.value=!0),P(()=>r.value=!1),qe({ownerDocument:o},c(()=>r.value&&!!(e.features&16)));let u=Ie({ownerDocument:o,container:l,initialFocus:c(()=>e.initialFocus)},c(()=>r.value&&!!(e.features&2)));Ye({ownerDocument:o,container:l,containers:e.containers,previousActiveElement:u},c(()=>r.value&&!!(e.features&8)));let i=$e();function s(m){let w=b(l);w&&(E=>E())(()=>{W(i.value,{[H.Forwards]:()=>{B(w,T.First,{skipElements:[m.relatedTarget]})},[H.Backwards]:()=>{B(w,T.Last,{skipElements:[m.relatedTarget]})}})})}let f=g(!1);function h(m){m.key==="Tab"&&(f.value=!0,requestAnimationFrame(()=>{f.value=!1}))}function v(m){if(!r.value)return;let w=ce(e.containers);b(l)instanceof HTMLElement&&w.add(b(l));let E=m.relatedTarget;E instanceof HTMLElement&&E.dataset.headlessuiFocusGuard!=="true"&&(ve(w,E)||(f.value?B(b(l),W(i.value,{[H.Forwards]:()=>T.Next,[H.Backwards]:()=>T.Previous})|T.WrapAround,{relativeTo:m.target}):m.target instanceof HTMLElement&&$(m.target)))}return()=>{let m={},w={ref:l,onKeydown:h,onFocusout:v},{features:E,initialFocus:K,containers:Z,...R}=e;return S(He,[!!(E&4)&&S(ne,{as:"button",type:"button","data-headlessui-focus-guard":!0,onFocus:s,features:le.Focusable}),U({ourProps:w,theirProps:{...n,...R},slot:m,attrs:n,slots:t,name:"FocusTrap"}),!!(E&4)&&S(ne,{as:"button",type:"button","data-headlessui-focus-guard":!0,onFocus:s,features:le.Focusable})])}}}),{features:fe}),F=[];Ue(()=>{function e(n){n.target instanceof HTMLElement&&n.target!==document.body&&F[0]!==n.target&&(F.unshift(n.target),F=F.filter(t=>t!=null&&t.isConnected),F.splice(10))}window.addEventListener("click",e,{capture:!0}),window.addEventListener("mousedown",e,{capture:!0}),window.addEventListener("focus",e,{capture:!0}),document.body.addEventListener("click",e,{capture:!0}),document.body.addEventListener("mousedown",e,{capture:!0}),document.body.addEventListener("focus",e,{capture:!0})});function Ve(e){let n=g(F.slice());return q([e],([t],[a])=>{a===!0&&t===!1?ie(()=>{n.value.splice(0)}):a===!1&&t===!0&&(n.value=F.slice())},{flush:"post"}),()=>{var t;return(t=n.value.find(a=>a!=null&&a.isConnected))!=null?t:null}}function qe({ownerDocument:e},n){let t=Ve(n);O(()=>{Q(()=>{var a,l;n.value||((a=e.value)==null?void 0:a.activeElement)===((l=e.value)==null?void 0:l.body)&&$(t())},{flush:"post"})}),P(()=>{n.value&&$(t())})}function Ie({ownerDocument:e,container:n,initialFocus:t},a){let l=g(null),o=g(!1);return O(()=>o.value=!0),P(()=>o.value=!1),O(()=>{q([n,t,a],(r,u)=>{if(r.every((s,f)=>(u==null?void 0:u[f])===s)||!a.value)return;let i=b(n);i&&ie(()=>{var s,f;if(!o.value)return;let h=b(t),v=(s=e.value)==null?void 0:s.activeElement;if(h){if(h===v){l.value=v;return}}else if(i.contains(v)){l.value=v;return}h?$(h):B(i,T.First|T.NoScroll)===Oe.Error&&console.warn("There are no focusable elements inside the <FocusTrap />"),l.value=(f=e.value)==null?void 0:f.activeElement})},{immediate:!0,flush:"post"})}),l}function Ye({ownerDocument:e,container:n,containers:t,previousActiveElement:a},l){var o;se((o=e.value)==null?void 0:o.defaultView,"focus",r=>{if(!l.value)return;let u=ce(t);b(n)instanceof HTMLElement&&u.add(b(n));let i=a.value;if(!i)return;let s=r.target;s&&s instanceof HTMLElement?ve(u,s)?(a.value=s,$(s)):(r.preventDefault(),r.stopPropagation(),$(i)):$(a.value)},!0)}function ve(e,n){for(let t of e)if(t.contains(n))return!0;return!1}let _=new Map,k=new Map;function re(e,n=g(!0)){Q(t=>{var a;if(!n.value)return;let l=b(e);if(!l)return;t(function(){var r;if(!l)return;let u=(r=k.get(l))!=null?r:1;if(u===1?k.delete(l):k.set(l,u-1),u!==1)return;let i=_.get(l);i&&(i["aria-hidden"]===null?l.removeAttribute("aria-hidden"):l.setAttribute("aria-hidden",i["aria-hidden"]),l.inert=i.inert,_.delete(l))});let o=(a=k.get(l))!=null?a:0;k.set(l,o+1),o===0&&(_.set(l,{"aria-hidden":l.getAttribute("aria-hidden"),inert:l.inert}),l.setAttribute("aria-hidden","true"),l.inert=!0)})}let pe=Symbol("StackContext");var z=(e=>(e[e.Add=0]="Add",e[e.Remove=1]="Remove",e))(z||{});function _e(){return I(pe,()=>{})}function ze({type:e,enabled:n,element:t,onUpdate:a}){let l=_e();function o(...r){a==null||a(...r),l(...r)}O(()=>{q(n,(r,u)=>{r?o(0,e,t):u===!0&&o(1,e,t)},{immediate:!0,flush:"sync"})}),P(()=>{n.value&&o(1,e,t)}),X(pe,o)}let me=Symbol("DescriptionContext");function Ge(){let e=I(me,null);if(e===null)throw new Error("Missing parent");return e}function Je({slot:e=g({}),name:n="Description",props:t={}}={}){let a=g([]);function l(o){return a.value.push(o),()=>{let r=a.value.indexOf(o);r!==-1&&a.value.splice(r,1)}}return X(me,{register:l,slot:e,name:n,props:t}),c(()=>a.value.length>0?a.value.join(" "):void 0)}let rt=V({name:"Description",props:{as:{type:[Object,String],default:"p"},id:{type:String,default:()=>`headlessui-description-${J()}`}},setup(e,{attrs:n,slots:t}){let a=Ge();return O(()=>P(a.register(e.id))),()=>{let{name:l="Description",slot:o=g({}),props:r={}}=a,{id:u,...i}=e,s={...Object.entries(r).reduce((f,[h,v])=>Object.assign(f,{[h]:Ne(v)}),{}),id:u};return U({ourProps:s,theirProps:i,slot:o.value,attrs:n,slots:t,name:l})}}});function Qe(e){let n=je(e.getSnapshot());return P(e.subscribe(()=>{n.value=e.getSnapshot()})),n}function Xe(e,n){let t=e(),a=new Set;return{getSnapshot(){return t},subscribe(l){return a.add(l),()=>a.delete(l)},dispatch(l,...o){let r=n[l].call(t,...o);r&&(t=r,a.forEach(u=>u()))}}}function Ke(){let e;return{before({doc:n}){var t;let a=n.documentElement;e=((t=n.defaultView)!=null?t:window).innerWidth-a.clientWidth},after({doc:n,d:t}){let a=n.documentElement,l=a.clientWidth-a.offsetWidth,o=e-l;t.style(a,"paddingRight",`${o}px`)}}}function Ze(){if(!We())return{};let e;return{before(){e=window.pageYOffset},after({doc:n,d:t,meta:a}){function l(r){return a.containers.flatMap(u=>u()).some(u=>u.contains(r))}if(window.getComputedStyle(n.documentElement).scrollBehavior!=="auto"){let r=de();r.style(n.documentElement,"scroll-behavior","auto"),t.add(()=>t.microTask(()=>r.dispose()))}t.style(n.body,"marginTop",`-${e}px`),window.scrollTo(0,0);let o=null;t.addEventListener(n,"click",r=>{if(r.target instanceof HTMLElement)try{let u=r.target.closest("a");if(!u)return;let{hash:i}=new URL(u.href),s=n.querySelector(i);s&&!l(s)&&(o=s)}catch{}},!0),t.addEventListener(n,"touchmove",r=>{r.target instanceof HTMLElement&&!l(r.target)&&r.preventDefault()},{passive:!1}),t.add(()=>{window.scrollTo(0,window.pageYOffset+e),o&&o.isConnected&&(o.scrollIntoView({block:"nearest"}),o=null)})}}}function et(){return{before({doc:e,d:n}){n.style(e.documentElement,"overflow","hidden")}}}function tt(e){let n={};for(let t of e)Object.assign(n,t(n));return n}let D=Xe(()=>new Map,{PUSH(e,n){var t;let a=(t=this.get(e))!=null?t:{doc:e,count:0,d:de(),meta:new Set};return a.count++,a.meta.add(n),this.set(e,a),this},POP(e,n){let t=this.get(e);return t&&(t.count--,t.meta.delete(n)),this},SCROLL_PREVENT({doc:e,d:n,meta:t}){let a={doc:e,d:n,meta:tt(t)},l=[Ze(),Ke(),et()];l.forEach(({before:o})=>o==null?void 0:o(a)),l.forEach(({after:o})=>o==null?void 0:o(a))},SCROLL_ALLOW({d:e}){e.dispose()},TEARDOWN({doc:e}){this.delete(e)}});D.subscribe(()=>{let e=D.getSnapshot(),n=new Map;for(let[t]of e)n.set(t,t.documentElement.style.overflow);for(let t of e.values()){let a=n.get(t.doc)==="hidden",l=t.count!==0;(l&&!a||!l&&a)&&D.dispatch(t.count>0?"SCROLL_PREVENT":"SCROLL_ALLOW",t),t.count===0&&D.dispatch("TEARDOWN",t)}});function nt(e,n,t){let a=Qe(D),l=c(()=>{let o=e.value?a.value.get(e.value):void 0;return o?o.count>0:!1});return q([e,n],([o,r],[u],i)=>{if(!o||!r)return;D.dispatch("PUSH",o,t);let s=!1;i(()=>{s||(D.dispatch("POP",u??o,t),s=!0)})},{immediate:!0}),l}var lt=(e=>(e[e.Open=0]="Open",e[e.Closed=1]="Closed",e))(lt||{});let G=Symbol("DialogContext");function ge(e){let n=I(G,null);if(n===null){let t=new Error(`<${e} /> is missing a parent <Dialog /> component.`);throw Error.captureStackTrace&&Error.captureStackTrace(t,ge),t}return n}let j="DC8F892D-2EBD-447C-A4C8-A03058436FF4",ut=V({name:"Dialog",inheritAttrs:!1,props:{as:{type:[Object,String],default:"div"},static:{type:Boolean,default:!1},unmount:{type:Boolean,default:!0},open:{type:[Boolean,String],default:j},initialFocus:{type:Object,default:null},id:{type:String,default:()=>`headlessui-dialog-${J()}`}},emits:{close:e=>!0},setup(e,{emit:n,attrs:t,slots:a,expose:l}){var o;let r=g(!1);O(()=>{r.value=!0});let u=g(0),i=Ae(),s=c(()=>e.open===j&&i!==null?(i.value&N.Open)===N.Open:e.open),f=g(null),h=c(()=>ue(f));if(l({el:f,$el:f}),!(e.open!==j||i!==null))throw new Error("You forgot to provide an `open` prop to the `Dialog`.");if(typeof s.value!="boolean")throw new Error(`You provided an \`open\` prop to the \`Dialog\`, but the value is not a boolean. Received: ${s.value===j?void 0:e.open}`);let v=c(()=>r.value&&s.value?0:1),m=c(()=>v.value===0),w=c(()=>u.value>1),E=I(G,null)!==null,[K,Z]=Pe(),{resolveContainers:R,mainTreeNodeRef:ee,MainTreeNode:he}=Re({portals:K,defaultContainers:[c(()=>{var d;return(d=M.panelRef.value)!=null?d:f.value})]}),we=c(()=>w.value?"parent":"leaf"),te=c(()=>i!==null?(i.value&N.Closing)===N.Closing:!1),be=c(()=>E||te.value?!1:m.value),Ee=c(()=>{var d,p,y;return(y=Array.from((p=(d=h.value)==null?void 0:d.querySelectorAll("body > *"))!=null?p:[]).find(L=>L.id==="headlessui-portal-root"?!1:L.contains(b(ee))&&L instanceof HTMLElement))!=null?y:null});re(Ee,be);let ye=c(()=>w.value?!0:m.value),Le=c(()=>{var d,p,y;return(y=Array.from((p=(d=h.value)==null?void 0:d.querySelectorAll("[data-headlessui-portal]"))!=null?p:[]).find(L=>L.contains(b(ee))&&L instanceof HTMLElement))!=null?y:null});re(Le,ye),ze({type:"Dialog",enabled:c(()=>v.value===0),element:f,onUpdate:(d,p)=>{if(p==="Dialog")return W(d,{[z.Add]:()=>u.value+=1,[z.Remove]:()=>u.value-=1})}});let Se=Je({name:"DialogDescription",slot:c(()=>({open:s.value}))}),x=g(null),M={titleId:x,panelRef:g(null),dialogState:v,setTitleId(d){x.value!==d&&(x.value=d)},close(){n("close",!1)}};X(G,M);let Te=c(()=>!(!m.value||w.value));Me(R,(d,p)=>{M.close(),Be(()=>p==null?void 0:p.focus())},Te);let Fe=c(()=>!(w.value||v.value!==0));se((o=h.value)==null?void 0:o.defaultView,"keydown",d=>{Fe.value&&(d.defaultPrevented||d.key===xe.Escape&&(d.preventDefault(),d.stopPropagation(),M.close()))});let De=c(()=>!(te.value||v.value!==0||E));return nt(h,De,d=>{var p;return{containers:[...(p=d.containers)!=null?p:[],R]}}),Q(d=>{if(v.value!==0)return;let p=b(f);if(!p)return;let y=new ResizeObserver(L=>{for(let Y of L){let A=Y.target.getBoundingClientRect();A.x===0&&A.y===0&&A.width===0&&A.height===0&&M.close()}});y.observe(p),d(()=>y.disconnect())}),()=>{let{id:d,open:p,initialFocus:y,...L}=e,Y={...t,ref:f,id:d,role:"dialog","aria-modal":v.value===0?!0:void 0,"aria-labelledby":x.value,"aria-describedby":Se.value},A={open:v.value===0};return S(ae,{force:!0},()=>[S(Ce,()=>S(ke,{target:f.value},()=>S(ae,{force:!1},()=>S(C,{initialFocus:y,containers:R,features:m.value?W(we.value,{parent:C.features.RestoreFocus,leaf:C.features.All&~C.features.FocusLock}):C.features.None},()=>S(Z,{},()=>U({ourProps:Y,theirProps:{...L,...t},slot:A,attrs:t,slots:a,visible:v.value===0,features:oe.RenderStrategy|oe.Static,name:"Dialog"})))))),S(he)])}}}),it=V({name:"DialogPanel",props:{as:{type:[Object,String],default:"div"},id:{type:String,default:()=>`headlessui-dialog-panel-${J()}`}},setup(e,{attrs:n,slots:t,expose:a}){let l=ge("DialogPanel");a({el:l.panelRef,$el:l.panelRef});function o(r){r.stopPropagation()}return()=>{let{id:r,...u}=e,i={id:r,ref:l.panelRef,onClick:o};return U({ourProps:i,theirProps:u,slot:{open:l.dialogState.value===0},attrs:n,slots:t,name:"DialogPanel"})}}});export{rt as E,it as G,Je as M,ut as U};
