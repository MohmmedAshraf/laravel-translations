import{_ as b}from"./layout-guest.vue_vue_type_script_setup_true_lang-dd10f5b6.js";import{d as V,T as x,o as c,c as B,w as r,a as u,t as n,u as e,b as t,e as o,j as $,f as k,g as l,h as C,Z as E,i as I}from"./app-4a4d2073.js";import{_ as L}from"./base-button.vue_vue_type_script_setup_true_lang-193acd9e.js";import{_ as N}from"./input-password.vue_vue_type_script_setup_true_lang-566986a0.js";import{_ as q,a as P}from"./input-label.vue_vue_type_script_setup_true_lang-746f051f.js";import{_ as S}from"./input-text.vue_vue_type_script_setup_true_lang-795089e2.js";import"./logo-2c45efc9.js";import"./_plugin-vue_export-helper-c27b6911.js";import"./icon-close-9c7a445c.js";import"./use-input-size-e87d9a0c.js";const T={key:0,class:"mb-4 text-sm font-medium text-green-600"},j={class:"space-y-1"},A={class:"space-y-1"},F={class:"mt-8 flex w-full justify-center"},R=V({__name:"login",props:{status:{}},setup(U){const s=x({email:"",password:"",remember:!1}),_=()=>{s.post(route("ltu.login.attempt"),{onFinish:()=>{s.reset("password")}})};return(m,a)=>{const f=E,d=q,g=S,p=P,w=N,y=L,h=I,v=b;return c(),B(v,null,{title:r(()=>[u(n(e(t)("Sign in to your account")),1)]),default:r(()=>[o(f,{title:"Log in"}),m.status?(c(),$("div",T,n(m.status),1)):k("",!0),l("form",{class:"space-y-6",onSubmit:C(_,["prevent"])},[l("div",j,[o(d,{for:"email",value:e(t)("Email Address"),class:"sr-only"},null,8,["value"]),o(g,{id:"email",modelValue:e(s).email,"onUpdate:modelValue":a[0]||(a[0]=i=>e(s).email=i),error:e(s).errors.email,type:"email",required:"",autofocus:"",placeholder:e(t)("Email Address"),autocomplete:"username",class:"bg-gray-50"},null,8,["modelValue","error","placeholder"]),o(p,{message:e(s).errors.email},null,8,["message"])]),l("div",A,[o(d,{for:"password",value:e(t)("Password"),class:"sr-only"},null,8,["value"]),o(w,{id:"password",modelValue:e(s).password,"onUpdate:modelValue":a[1]||(a[1]=i=>e(s).password=i),error:e(s).errors.password,required:"",autocomplete:"current-password",placeholder:e(t)("Password"),class:"bg-gray-50"},null,8,["modelValue","error","placeholder"]),o(p,{message:e(s).errors.password},null,8,["message"])]),o(y,{type:"submit",size:"lg",variant:"secondary","is-loading":e(s).processing,"full-width":""},{default:r(()=>[u(n(e(t)("Continue")),1)]),_:1},8,["is-loading"])],32),l("div",F,[o(h,{href:m.route("ltu.password.request"),class:"text-xs font-medium text-gray-500 hover:text-blue-500"},{default:r(()=>[u(n(e(t)("Forgot your password?")),1)]),_:1},8,["href"])])]),_:1})}}});export{R as default};
