import{d as c,J as p,j as m,p as f,M as v,o as s,h as r,f as b,t as d,F as g,q as h,E as z,u as M}from"./app-d05ed321.js";import{u as V}from"./use-input-size-1ea8e246.js";const S={value:"",selected:""},_=["value"],x=c({__name:"input-native-select",props:p({items:{},size:{},error:{},placeholder:{}},{modelValue:{},modelModifiers:{}}),emits:["update:modelValue"],setup(l){const n=l,t=m(l,"modelValue"),{sizeClass:u}=V(n.size??"lg");return(o,a)=>f((s(),r("select",{"onUpdate:modelValue":a[0]||(a[0]=e=>t.value=e),class:z(["w-full rounded-md border border-gray-300 px-3 text-left shadow-sm focus:border-blue-500 focus:ring-blue-500",[M(u),{"border-red-300 text-red-900 placeholder:text-red-300 focus:border-red-500 focus:ring-red-500":o.error}]])},[b("option",S,d(o.placeholder??"Select an option"),1),(s(!0),r(g,null,h(o.items,(e,i)=>(s(),r("option",{key:i,value:e.value},d(e.label),9,_))),128))],2)),[[v,t.value]])}});export{x as _};