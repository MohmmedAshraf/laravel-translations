import{_ as b}from"./layout-guest-c6e25067.js";import{d as x,T as h,o as V,e as k,w as n,g as m,h as e,a as t,u as s,t as C,j as I,Z as B,k as $}from"./app-7542d82e.js";import{_ as N}from"./base-button.vue_vue_type_script_setup_true_lang-8a7810cf.js";import{_ as j}from"./input-password.vue_vue_type_script_setup_true_lang-6e21d2d4.js";import{_ as q,a as A}from"./input-label.vue_vue_type_script_setup_true_lang-1dbf30eb.js";import{_ as E}from"./input-text.vue_vue_type_script_setup_true_lang-bf75cbe0.js";import"./logo-691e9cd3.js";import"./_plugin-vue_export-helper-c27b6911.js";import"./icon-close-53390060.js";import"./use-input-size-1415f3d5.js";const L={class:"space-y-1"},P={class:"space-y-1"},T=["textContent"],U={class:"space-y-1"},G={class:"space-y-1"},S={class:"mt-8 flex w-full justify-center"},Q=x({__name:"accept",props:{email:{},token:{}},setup(u){const d=u,o=h({name:"",password:"",email:d.email,token:d.token,password_confirmation:""}),_=()=>{o.post(route("ltu.invitation.accept.store"),{onFinish:()=>{o.reset("password","password_confirmation")}})};return(p,a)=>{const f=B,i=q,w=E,l=A,c=j,g=N,y=$,v=b;return V(),k(v,null,{title:n(()=>[m(" Accept Invitation ")]),subtitle:n(()=>[m(" You have been invited to join the team! ")]),default:n(()=>[e(f,{title:"Accept Invitation"}),t("form",{class:"space-y-6",onSubmit:I(_,["prevent"])},[t("div",L,[e(i,{for:"name",value:"Name",class:"sr-only"}),e(w,{id:"name",modelValue:s(o).name,"onUpdate:modelValue":a[0]||(a[0]=r=>s(o).name=r),error:s(o).errors.name,type:"text",required:"",autofocus:"",autocomplete:"name",placeholder:"Enter your name",class:"bg-gray-50"},null,8,["modelValue","error"]),e(l,{message:s(o).errors.name},null,8,["message"])]),t("div",P,[e(i,{for:"email",value:"Email Address",class:"sr-only"}),t("div",{class:"w-full cursor-not-allowed rounded-md border border-gray-300 bg-gray-100 px-2 py-2.5 text-sm",textContent:C(p.email)},null,8,T),e(l,{message:s(o).errors.email},null,8,["message"])]),t("div",U,[e(i,{for:"password",value:"Password"}),e(c,{id:"password",modelValue:s(o).password,"onUpdate:modelValue":a[1]||(a[1]=r=>s(o).password=r),error:s(o).errors.password,required:"",autocomplete:"new-password",class:"bg-gray-50"},null,8,["modelValue","error"]),e(l,{message:s(o).errors.password},null,8,["message"])]),t("div",G,[e(i,{for:"password_confirmation",value:"Confirm Password"}),e(c,{id:"password_confirmation",modelValue:s(o).password_confirmation,"onUpdate:modelValue":a[2]||(a[2]=r=>s(o).password_confirmation=r),error:s(o).errors.password_confirmation,required:"",autocomplete:"new-password",class:"bg-gray-50"},null,8,["modelValue","error"]),e(l,{message:s(o).errors.password_confirmation},null,8,["message"])]),e(g,{type:"submit",variant:"secondary","is-loading":s(o).processing,"full-width":""},{default:n(()=>[m(" Continue ")]),_:1},8,["is-loading"])],32),t("div",S,[e(y,{href:p.route("ltu.login"),class:"text-xs font-medium text-gray-500 hover:text-blue-500"},{default:n(()=>[m(" Go back to sign in ")]),_:1},8,["href"])])]),_:1})}}});export{Q as default};
