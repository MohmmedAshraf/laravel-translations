import{m as f}from"./transition-738a3b62.js";import{$ as d}from"./app-7542d82e.js";function m({container:e,accept:r,walk:n,enabled:t}){d(()=>{let a=e.value;if(!a||t!==void 0&&!t.value)return;let c=f(e);if(!c)return;let l=Object.assign(i=>r(i),{acceptNode:r}),o=c.createTreeWalker(a,NodeFilter.SHOW_ELEMENT,l,!1);for(;o.nextNode();)n(o.currentNode)})}export{m as p};
