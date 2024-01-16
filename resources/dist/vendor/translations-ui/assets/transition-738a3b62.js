import{a8 as Le,E as C,F as $e,B as E,y as O,Y as Ae,$ as S,l as g,b as A,d as F,z as $,A as U,a9 as xe,W as je,D as Ne,n as Ce}from"./app-7542d82e.js";function x(e,t,...n){if(e in t){let o=t[e];return typeof o=="function"?o(...n):o}let r=new Error(`Tried to handle "${e}" but there is no handler defined. Only defined handlers are: ${Object.keys(t).map(o=>`"${o}"`).join(", ")}.`);throw Error.captureStackTrace&&Error.captureStackTrace(r,x),r}var oe=(e=>(e[e.None=0]="None",e[e.RenderStrategy=1]="RenderStrategy",e[e.Static=2]="Static",e))(oe||{}),w=(e=>(e[e.Unmount=0]="Unmount",e[e.Hidden=1]="Hidden",e))(w||{});function N({visible:e=!0,features:t=0,ourProps:n,theirProps:r,...o}){var l;let i=ie(r,n),a=Object.assign(o,{props:i});if(e||t&2&&i.static)return V(a);if(t&1){let u=(l=i.unmount)==null||l?0:1;return x(u,{0(){return null},1(){return V({...o,props:{...i,hidden:!0,style:{display:"none"}}})}})}return V(a)}function V({props:e,attrs:t,slots:n,slot:r,name:o}){var l,i;let{as:a,...u}=ue(e,["unmount","static"]),s=(l=n.default)==null?void 0:l.call(n,r),d={};if(r){let f=!1,c=[];for(let[m,p]of Object.entries(r))typeof p=="boolean"&&(f=!0),p===!0&&c.push(m);f&&(d["data-headlessui-state"]=c.join(" "))}if(a==="template"){if(s=ae(s??[]),Object.keys(u).length>0||Object.keys(t).length>0){let[f,...c]=s??[];if(!He(f)||c.length>0)throw new Error(['Passing props on "template"!',"",`The current component <${o} /> is rendering a "template".`,"However we need to passthrough the following props:",Object.keys(u).concat(Object.keys(t)).map(v=>v.trim()).filter((v,h,k)=>k.indexOf(v)===h).sort((v,h)=>v.localeCompare(h)).map(v=>`  - ${v}`).join(`
`),"","You can apply a few solutions:",['Add an `as="..."` prop, to ensure that we render an actual element instead of a "template".',"Render a single element as the child so that we can forward the props onto that element."].map(v=>`  - ${v}`).join(`
`)].join(`
`));let m=ie((i=f.props)!=null?i:{},u),p=Le(f,m);for(let v in m)v.startsWith("on")&&(p.props||(p.props={}),p.props[v]=m[v]);return p}return Array.isArray(s)&&s.length===1?s[0]:s}return C(a,Object.assign({},u,d),{default:()=>s})}function ae(e){return e.flatMap(t=>t.type===$e?ae(t.children):[t])}function ie(...e){if(e.length===0)return{};if(e.length===1)return e[0];let t={},n={};for(let r of e)for(let o in r)o.startsWith("on")&&typeof r[o]=="function"?(n[o]!=null||(n[o]=[]),n[o].push(r[o])):t[o]=r[o];if(t.disabled||t["aria-disabled"])return Object.assign(t,Object.fromEntries(Object.keys(n).map(r=>[r,void 0])));for(let r in n)Object.assign(t,{[r](o,...l){let i=n[r];for(let a of i){if(o instanceof Event&&o.defaultPrevented)return;a(o,...l)}}});return t}function bt(e){let t=Object.assign({},e);for(let n in t)t[n]===void 0&&delete t[n];return t}function ue(e,t=[]){let n=Object.assign({},e);for(let r of t)r in n&&delete n[r];return n}function He(e){return e==null?!1:typeof e.type=="string"||typeof e.type=="object"||typeof e.type=="function"}let Me=0;function ke(){return++Me}function De(){return ke()}var Ie=(e=>(e.Space=" ",e.Enter="Enter",e.Escape="Escape",e.Backspace="Backspace",e.Delete="Delete",e.ArrowLeft="ArrowLeft",e.ArrowUp="ArrowUp",e.ArrowRight="ArrowRight",e.ArrowDown="ArrowDown",e.Home="Home",e.End="End",e.PageUp="PageUp",e.PageDown="PageDown",e.Tab="Tab",e))(Ie||{});function j(e){var t;return e==null||e.value==null?null:(t=e.value.$el)!=null?t:e.value}let se=Symbol("Context");var y=(e=>(e[e.Open=1]="Open",e[e.Closed=2]="Closed",e[e.Closing=4]="Closing",e[e.Opening=8]="Opening",e))(y||{});function Be(){return de()!==null}function de(){return E(se,null)}function Re(e){O(se,e)}var Ue=Object.defineProperty,_e=(e,t,n)=>t in e?Ue(e,t,{enumerable:!0,configurable:!0,writable:!0,value:n}):e[t]=n,re=(e,t,n)=>(_e(e,typeof t!="symbol"?t+"":t,n),n);class qe{constructor(){re(this,"current",this.detect()),re(this,"currentId",0)}set(t){this.current!==t&&(this.currentId=0,this.current=t)}reset(){this.set(this.detect())}nextId(){return++this.currentId}get isServer(){return this.current==="server"}get isClient(){return this.current==="client"}detect(){return typeof window>"u"||typeof document>"u"?"server":"client"}}let H=new qe;function M(e){if(H.isServer)return null;if(e instanceof Node)return e.ownerDocument;if(e!=null&&e.hasOwnProperty("value")){let t=j(e);if(t)return t.ownerDocument}return document}let G=["[contentEditable=true]","[tabindex]","a[href]","area[href]","button:not([disabled])","iframe","input:not([disabled])","select:not([disabled])","textarea:not([disabled])"].map(e=>`${e}:not([tabindex='-1'])`).join(",");var Ve=(e=>(e[e.First=1]="First",e[e.Previous=2]="Previous",e[e.Next=4]="Next",e[e.Last=8]="Last",e[e.WrapAround=16]="WrapAround",e[e.NoScroll=32]="NoScroll",e))(Ve||{}),We=(e=>(e[e.Error=0]="Error",e[e.Overflow=1]="Overflow",e[e.Success=2]="Success",e[e.Underflow=3]="Underflow",e))(We||{}),Ge=(e=>(e[e.Previous=-1]="Previous",e[e.Next=1]="Next",e))(Ge||{});function fe(e=document.body){return e==null?[]:Array.from(e.querySelectorAll(G)).sort((t,n)=>Math.sign((t.tabIndex||Number.MAX_SAFE_INTEGER)-(n.tabIndex||Number.MAX_SAFE_INTEGER)))}var ce=(e=>(e[e.Strict=0]="Strict",e[e.Loose=1]="Loose",e))(ce||{});function pe(e,t=0){var n;return e===((n=M(e))==null?void 0:n.body)?!1:x(t,{0(){return e.matches(G)},1(){let r=e;for(;r!==null;){if(r.matches(G))return!0;r=r.parentElement}return!1}})}function wt(e){let t=M(e);Ae(()=>{t&&!pe(t.activeElement,0)&&ze(e)})}var Ke=(e=>(e[e.Keyboard=0]="Keyboard",e[e.Mouse=1]="Mouse",e))(Ke||{});typeof window<"u"&&typeof document<"u"&&(document.addEventListener("keydown",e=>{e.metaKey||e.altKey||e.ctrlKey||(document.documentElement.dataset.headlessuiFocusVisible="")},!0),document.addEventListener("click",e=>{e.detail===1?delete document.documentElement.dataset.headlessuiFocusVisible:e.detail===0&&(document.documentElement.dataset.headlessuiFocusVisible="")},!0));function ze(e){e==null||e.focus({preventScroll:!0})}let Xe=["textarea","input"].join(",");function Ye(e){var t,n;return(n=(t=e==null?void 0:e.matches)==null?void 0:t.call(e,Xe))!=null?n:!1}function Qe(e,t=n=>n){return e.slice().sort((n,r)=>{let o=t(n),l=t(r);if(o===null||l===null)return 0;let i=o.compareDocumentPosition(l);return i&Node.DOCUMENT_POSITION_FOLLOWING?-1:i&Node.DOCUMENT_POSITION_PRECEDING?1:0})}function Et(e,t){return Ze(fe(),t,{relativeTo:e})}function Ze(e,t,{sorted:n=!0,relativeTo:r=null,skipElements:o=[]}={}){var l;let i=(l=Array.isArray(e)?e.length>0?e[0].ownerDocument:document:e==null?void 0:e.ownerDocument)!=null?l:document,a=Array.isArray(e)?n?Qe(e):e:fe(e);o.length>0&&a.length>1&&(a=a.filter(p=>!o.includes(p))),r=r??i.activeElement;let u=(()=>{if(t&5)return 1;if(t&10)return-1;throw new Error("Missing Focus.First, Focus.Previous, Focus.Next or Focus.Last")})(),s=(()=>{if(t&1)return 0;if(t&2)return Math.max(0,a.indexOf(r))-1;if(t&4)return Math.max(0,a.indexOf(r))+1;if(t&8)return a.length-1;throw new Error("Missing Focus.First, Focus.Previous, Focus.Next or Focus.Last")})(),d=t&32?{preventScroll:!0}:{},f=0,c=a.length,m;do{if(f>=c||f+c<=0)return 0;let p=s+f;if(t&16)p=(p+c)%c;else{if(p<0)return 3;if(p>=c)return 1}m=a[p],m==null||m.focus(d),f+=u}while(m!==i.activeElement);return t&6&&Ye(m)&&m.select(),2}function B(e,t,n){H.isServer||S(r=>{document.addEventListener(e,t,n),r(()=>document.removeEventListener(e,t,n))})}function ve(e,t,n){H.isServer||S(r=>{window.addEventListener(e,t,n),r(()=>window.removeEventListener(e,t,n))})}function St(e,t,n=A(()=>!0)){function r(l,i){if(!n.value||l.defaultPrevented)return;let a=i(l);if(a===null||!a.getRootNode().contains(a))return;let u=function s(d){return typeof d=="function"?s(d()):Array.isArray(d)||d instanceof Set?d:[d]}(e);for(let s of u){if(s===null)continue;let d=s instanceof HTMLElement?s:j(s);if(d!=null&&d.contains(a)||l.composed&&l.composedPath().includes(d))return}return!pe(a,ce.Loose)&&a.tabIndex!==-1&&l.preventDefault(),t(l,a)}let o=g(null);B("pointerdown",l=>{var i,a;n.value&&(o.value=((a=(i=l.composedPath)==null?void 0:i.call(l))==null?void 0:a[0])||l.target)},!0),B("mousedown",l=>{var i,a;n.value&&(o.value=((a=(i=l.composedPath)==null?void 0:i.call(l))==null?void 0:a[0])||l.target)},!0),B("click",l=>{o.value&&(r(l,()=>o.value),o.value=null)},!0),B("touchend",l=>r(l,()=>l.target instanceof HTMLElement?l.target:null),!0),ve("blur",l=>r(l,()=>window.document.activeElement instanceof HTMLIFrameElement?window.document.activeElement:null),!0)}var me=(e=>(e[e.None=1]="None",e[e.Focusable=2]="Focusable",e[e.Hidden=4]="Hidden",e))(me||{});let Je=F({name:"Hidden",props:{as:{type:[Object,String],default:"div"},features:{type:Number,default:1}},setup(e,{slots:t,attrs:n}){return()=>{let{features:r,...o}=e,l={"aria-hidden":(r&2)===2?!0:void 0,style:{position:"fixed",top:1,left:1,width:1,height:0,padding:0,margin:-1,overflow:"hidden",clip:"rect(0, 0, 0, 0)",whiteSpace:"nowrap",borderWidth:"0",...(r&4)===4&&(r&2)!==2&&{display:"none"}}};return N({ourProps:l,theirProps:o,slot:{},attrs:n,slots:t,name:"Hidden"})}}});function et(e){typeof queueMicrotask=="function"?queueMicrotask(e):Promise.resolve().then(e).catch(t=>setTimeout(()=>{throw t}))}function X(){let e=[],t={addEventListener(n,r,o,l){return n.addEventListener(r,o,l),t.add(()=>n.removeEventListener(r,o,l))},requestAnimationFrame(...n){let r=requestAnimationFrame(...n);t.add(()=>cancelAnimationFrame(r))},nextFrame(...n){t.requestAnimationFrame(()=>{t.requestAnimationFrame(...n)})},setTimeout(...n){let r=setTimeout(...n);t.add(()=>clearTimeout(r))},microTask(...n){let r={current:!0};return et(()=>{r.current&&n[0]()}),t.add(()=>{r.current=!1})},style(n,r,o){let l=n.style.getPropertyValue(r);return Object.assign(n.style,{[r]:o}),this.add(()=>{Object.assign(n.style,{[r]:l})})},group(n){let r=X();return n(r),this.add(()=>r.dispose())},add(n){return e.push(n),()=>{let r=e.indexOf(n);if(r>=0)for(let o of e.splice(r,1))o()}},dispose(){for(let n of e.splice(0))n()}};return t}var tt=(e=>(e[e.Forwards=0]="Forwards",e[e.Backwards=1]="Backwards",e))(tt||{});function Tt(){let e=g(0);return ve("keydown",t=>{t.key==="Tab"&&(e.value=t.shiftKey?1:0)}),e}function Pt(e,t,n,r){H.isServer||S(o=>{e=e??window,e.addEventListener(t,n,r),o(()=>e.removeEventListener(t,n,r))})}let he=Symbol("ForcePortalRootContext");function nt(){return E(he,!1)}let Ot=F({name:"ForcePortalRoot",props:{as:{type:[Object,String],default:"template"},force:{type:Boolean,default:!1}},setup(e,{slots:t,attrs:n}){return O(he,e.force),()=>{let{force:r,...o}=e;return N({theirProps:o,ourProps:{},slot:{},slots:t,attrs:n,name:"ForcePortalRoot"})}}});function rt(e){let t=M(e);if(!t){if(e===null)return null;throw new Error(`[Headless UI]: Cannot find ownerDocument for contextElement: ${e}`)}let n=t.getElementById("headlessui-portal-root");if(n)return n;let r=t.createElement("div");return r.setAttribute("id","headlessui-portal-root"),t.body.appendChild(r)}let Ft=F({name:"Portal",props:{as:{type:[Object,String],default:"div"}},setup(e,{slots:t,attrs:n}){let r=g(null),o=A(()=>M(r)),l=nt(),i=E(ge,null),a=g(l===!0||i==null?rt(r.value):i.resolveTarget());S(()=>{l||i!=null&&(a.value=i.resolveTarget())});let u=E(K,null);return $(()=>{let s=j(r);s&&u&&U(u.register(s))}),U(()=>{var s,d;let f=(s=o.value)==null?void 0:s.getElementById("headlessui-portal-root");f&&a.value===f&&a.value.children.length<=0&&((d=a.value.parentElement)==null||d.removeChild(a.value))}),()=>{if(a.value===null)return null;let s={ref:r,"data-headlessui-portal":""};return C(xe,{to:a.value},N({ourProps:s,theirProps:e,slot:{},attrs:n,slots:t,name:"Portal"}))}}}),K=Symbol("PortalParentContext");function Lt(){let e=E(K,null),t=g([]);function n(l){return t.value.push(l),e&&e.register(l),()=>r(l)}function r(l){let i=t.value.indexOf(l);i!==-1&&t.value.splice(i,1),e&&e.unregister(l)}let o={register:n,unregister:r,portals:t};return[t,F({name:"PortalWrapper",setup(l,{slots:i}){return O(K,o),()=>{var a;return(a=i.default)==null?void 0:a.call(i)}}})]}let ge=Symbol("PortalGroupContext"),$t=F({name:"PortalGroup",props:{as:{type:[Object,String],default:"template"},target:{type:Object,default:null}},setup(e,{attrs:t,slots:n}){let r=je({resolveTarget(){return e.target}});return O(ge,r),()=>{let{target:o,...l}=e;return N({theirProps:l,ourProps:{},slot:{},attrs:t,slots:n,name:"PortalGroup"})}}});function At({defaultContainers:e=[],portals:t,mainTreeNodeRef:n}={}){let r=g(null),o=M(r);function l(){var i;let a=[];for(let u of e)u!==null&&(u instanceof HTMLElement?a.push(u):"value"in u&&u.value instanceof HTMLElement&&a.push(u.value));if(t!=null&&t.value)for(let u of t.value)a.push(u);for(let u of(i=o==null?void 0:o.querySelectorAll("html > *, body > *"))!=null?i:[])u!==document.body&&u!==document.head&&u instanceof HTMLElement&&u.id!=="headlessui-portal-root"&&(u.contains(j(r))||a.some(s=>u.contains(s))||a.push(u));return a}return{resolveContainers:l,contains(i){return l().some(a=>a.contains(i))},mainTreeNodeRef:r,MainTreeNode(){return n!=null?null:C(Je,{features:me.Hidden,ref:r})}}}function lt(e){let t={called:!1};return(...n)=>{if(!t.called)return t.called=!0,e(...n)}}function W(e,...t){e&&t.length>0&&e.classList.add(...t)}function R(e,...t){e&&t.length>0&&e.classList.remove(...t)}var z=(e=>(e.Finished="finished",e.Cancelled="cancelled",e))(z||{});function ot(e,t){let n=X();if(!e)return n.dispose;let{transitionDuration:r,transitionDelay:o}=getComputedStyle(e),[l,i]=[r,o].map(a=>{let[u=0]=a.split(",").filter(Boolean).map(s=>s.includes("ms")?parseFloat(s):parseFloat(s)*1e3).sort((s,d)=>d-s);return u});return l!==0?n.setTimeout(()=>t("finished"),l+i):t("finished"),n.add(()=>t("cancelled")),n.dispose}function le(e,t,n,r,o,l){let i=X(),a=l!==void 0?lt(l):()=>{};return R(e,...o),W(e,...t,...n),i.nextFrame(()=>{R(e,...n),W(e,...r),i.add(ot(e,u=>(R(e,...r,...t),W(e,...o),a(u))))}),i.add(()=>R(e,...t,...n,...r,...o)),i.add(()=>a("cancelled")),i.dispose}function P(e=""){return e.split(" ").filter(t=>t.trim().length>1)}let Y=Symbol("TransitionContext");var at=(e=>(e.Visible="visible",e.Hidden="hidden",e))(at||{});function it(){return E(Y,null)!==null}function ut(){let e=E(Y,null);if(e===null)throw new Error("A <TransitionChild /> is used but it is missing a parent <TransitionRoot />.");return e}function st(){let e=E(Q,null);if(e===null)throw new Error("A <TransitionChild /> is used but it is missing a parent <TransitionRoot />.");return e}let Q=Symbol("NestingContext");function _(e){return"children"in e?_(e.children):e.value.filter(({state:t})=>t==="visible").length>0}function ye(e){let t=g([]),n=g(!1);$(()=>n.value=!0),U(()=>n.value=!1);function r(l,i=w.Hidden){let a=t.value.findIndex(({id:u})=>u===l);a!==-1&&(x(i,{[w.Unmount](){t.value.splice(a,1)},[w.Hidden](){t.value[a].state="hidden"}}),!_(t)&&n.value&&(e==null||e()))}function o(l){let i=t.value.find(({id:a})=>a===l);return i?i.state!=="visible"&&(i.state="visible"):t.value.push({id:l,state:"visible"}),()=>r(l,w.Unmount)}return{children:t,register:o,unregister:r}}let be=oe.RenderStrategy,dt=F({props:{as:{type:[Object,String],default:"div"},show:{type:[Boolean],default:null},unmount:{type:[Boolean],default:!0},appear:{type:[Boolean],default:!1},enter:{type:[String],default:""},enterFrom:{type:[String],default:""},enterTo:{type:[String],default:""},entered:{type:[String],default:""},leave:{type:[String],default:""},leaveFrom:{type:[String],default:""},leaveTo:{type:[String],default:""}},emits:{beforeEnter:()=>!0,afterEnter:()=>!0,beforeLeave:()=>!0,afterLeave:()=>!0},setup(e,{emit:t,attrs:n,slots:r,expose:o}){let l=g(0);function i(){l.value|=y.Opening,t("beforeEnter")}function a(){l.value&=~y.Opening,t("afterEnter")}function u(){l.value|=y.Closing,t("beforeLeave")}function s(){l.value&=~y.Closing,t("afterLeave")}if(!it()&&Be())return()=>C(ct,{...e,onBeforeEnter:i,onAfterEnter:a,onBeforeLeave:u,onAfterLeave:s},r);let d=g(null),f=A(()=>e.unmount?w.Unmount:w.Hidden);o({el:d,$el:d});let{show:c,appear:m}=ut(),{register:p,unregister:v}=st(),h=g(c.value?"visible":"hidden"),k={value:!0},L=De(),D={value:!1},Z=ye(()=>{!D.value&&h.value!=="hidden"&&(h.value="hidden",v(L),s())});$(()=>{let b=p(L);U(b)}),S(()=>{if(f.value===w.Hidden&&L){if(c.value&&h.value!=="visible"){h.value="visible";return}x(h.value,{hidden:()=>v(L),visible:()=>p(L)})}});let J=P(e.enter),ee=P(e.enterFrom),we=P(e.enterTo),te=P(e.entered),Ee=P(e.leave),Se=P(e.leaveFrom),Te=P(e.leaveTo);$(()=>{S(()=>{if(h.value==="visible"){let b=j(d);if(b instanceof Comment&&b.data==="")throw new Error("Did you forget to passthrough the `ref` to the actual DOM node?")}})});function Pe(b){let q=k.value&&!m.value,T=j(d);!T||!(T instanceof HTMLElement)||q||(D.value=!0,c.value&&i(),c.value||u(),b(c.value?le(T,J,ee,we,te,I=>{D.value=!1,I===z.Finished&&a()}):le(T,Ee,Se,Te,te,I=>{D.value=!1,I===z.Finished&&(_(Z)||(h.value="hidden",v(L),s()))})))}return $(()=>{Ne([c],(b,q,T)=>{Pe(T),k.value=!1},{immediate:!0})}),O(Q,Z),Re(A(()=>x(h.value,{visible:y.Open,hidden:y.Closed})|l.value)),()=>{let{appear:b,show:q,enter:T,enterFrom:I,enterTo:pt,entered:vt,leave:mt,leaveFrom:ht,leaveTo:gt,...ne}=e,Oe={ref:d},Fe={...ne,...m.value&&c.value&&H.isServer?{class:Ce([n.class,ne.class,...J,...ee])}:{}};return N({theirProps:Fe,ourProps:Oe,slot:{},slots:r,attrs:n,features:be,visible:h.value==="visible",name:"TransitionChild"})}}}),ft=dt,ct=F({inheritAttrs:!1,props:{as:{type:[Object,String],default:"div"},show:{type:[Boolean],default:null},unmount:{type:[Boolean],default:!0},appear:{type:[Boolean],default:!1},enter:{type:[String],default:""},enterFrom:{type:[String],default:""},enterTo:{type:[String],default:""},entered:{type:[String],default:""},leave:{type:[String],default:""},leaveFrom:{type:[String],default:""},leaveTo:{type:[String],default:""}},emits:{beforeEnter:()=>!0,afterEnter:()=>!0,beforeLeave:()=>!0,afterLeave:()=>!0},setup(e,{emit:t,attrs:n,slots:r}){let o=de(),l=A(()=>e.show===null&&o!==null?(o.value&y.Open)===y.Open:e.show);S(()=>{if(![!0,!1].includes(l.value))throw new Error('A <Transition /> is used but it is missing a `:show="true | false"` prop.')});let i=g(l.value?"visible":"hidden"),a=ye(()=>{i.value="hidden"}),u=g(!0),s={show:l,appear:A(()=>e.appear||!u.value)};return $(()=>{S(()=>{u.value=!1,l.value?i.value="visible":_(a)||(i.value="hidden")})}),O(Q,a),O(Y,s),()=>{let d=ue(e,["show","appear","unmount","onBeforeEnter","onBeforeLeave","onAfterEnter","onAfterLeave"]),f={unmount:e.unmount};return N({ourProps:{...f,as:"template"},theirProps:{},slot:{},slots:{...r,default:()=>[C(ft,{onBeforeEnter:()=>t("beforeEnter"),onAfterEnter:()=>t("afterEnter"),onBeforeLeave:()=>t("beforeLeave"),onAfterLeave:()=>t("afterLeave"),...n,...f,...d},r.default)]},attrs:{},features:be,visible:i.value==="visible",name:"Transition"})}}});export{fe as E,N as H,bt as K,Ve as N,Qe as O,Ze as P,ct as S,ue as T,Ft as U,Lt as V,wt as _,me as a,Ie as b,We as c,Re as d,oe as e,Je as f,At as g,ce as h,Pt as i,tt as j,dt as k,y as l,M as m,Tt as n,j as o,de as p,ze as q,et as r,X as s,De as t,x as u,Et as v,pe as w,$t as x,St as y,Ot as z};
