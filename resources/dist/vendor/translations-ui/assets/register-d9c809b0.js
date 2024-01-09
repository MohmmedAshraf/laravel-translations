import{_ as v}from"./layout-guest-3a3e54e5.js";import{_ as h}from"./icon-google-b8dff0eb.js";import{_ as y}from"./base-button.vue_vue_type_script_setup_true_lang-06674713.js";import{_ as b,a as V}from"./input-label.vue_vue_type_script_setup_true_lang-d5924b94.js";import{_ as x}from"./input-text.vue_vue_type_script_setup_true_lang-d2077799.js";import{d as C,T as B,o as q,c as I,w as n,a as m,b as o,e as r,u as s,f as N,Z as U,i as $}from"./app-862d929f.js";import"./logo-609fef59.js";import"./_plugin-vue_export-helper-c27b6911.js";const k={class:"space-y-1"},G={class:"space-y-1"},L={class:"space-y-1"},T={class:"space-y-1"},A={class:"mt-10"},E=r("div",{class:"relative"},[r("div",{class:"absolute inset-0 flex items-center","aria-hidden":"true"},[r("div",{class:"w-full border-t border-gray-200"})]),r("div",{class:"relative flex justify-center text-sm font-medium leading-6"},[r("span",{class:"bg-white px-6 text-gray-900"},"Or continue with")])],-1),P={class:"mt-6 grid grid-cols-1 gap-4"},S=r("span",null,"Continue with Google",-1),K=C({__name:"register",setup(j){const e=B({name:"",email:"",password:"",password_confirmation:""}),p=()=>{e.post(route("ltu.register"),{onFinish:()=>{e.reset("password","password_confirmation")}})};return(c,a)=>{const _=U,u=$,i=b,l=x,d=V,f=y,w=h,g=v;return q(),I(g,null,{title:n(()=>[m(" Create an account ")]),subtitle:n(()=>[m(" Already have an account? "),o(u,{href:c.route("ltu.login"),class:"font-semibold text-blue-600 hover:text-blue-500"},{default:n(()=>[m(" Sign in ")]),_:1},8,["href"])]),default:n(()=>[o(_,{title:"Create an account"}),r("form",{class:"space-y-6",onSubmit:N(p,["prevent"])},[r("div",k,[o(i,{for:"name",value:"Name"}),o(l,{id:"name",modelValue:s(e).name,"onUpdate:modelValue":a[0]||(a[0]=t=>s(e).name=t),error:s(e).errors.name,type:"text",required:"",autofocus:"",autocomplete:"name"},null,8,["modelValue","error"]),o(d,{message:s(e).errors.name},null,8,["message"])]),r("div",G,[o(i,{for:"email",value:"Email Address"}),o(l,{id:"email",modelValue:s(e).email,"onUpdate:modelValue":a[1]||(a[1]=t=>s(e).email=t),error:s(e).errors.email,type:"email",required:"",autocomplete:"username"},null,8,["modelValue","error"]),o(d,{message:s(e).errors.email},null,8,["message"])]),r("div",L,[o(i,{for:"password",value:"Password"}),o(l,{id:"password",modelValue:s(e).password,"onUpdate:modelValue":a[2]||(a[2]=t=>s(e).password=t),error:s(e).errors.password,type:"password",required:"",autocomplete:"new-password"},null,8,["modelValue","error"]),o(d,{message:s(e).errors.password},null,8,["message"])]),r("div",T,[o(i,{for:"password_confirmation",value:"Confirm Password"}),o(l,{id:"password_confirmation",modelValue:s(e).password_confirmation,"onUpdate:modelValue":a[3]||(a[3]=t=>s(e).password_confirmation=t),error:s(e).errors.password_confirmation,type:"password",required:"",autocomplete:"new-password"},null,8,["modelValue","error"]),o(d,{message:s(e).errors.password_confirmation},null,8,["message"])]),o(f,{type:"submit",variant:"primary","is-loading":s(e).processing,"full-width":""},{default:n(()=>[m(" Create account ")]),_:1},8,["is-loading"])],32),r("div",A,[E,r("div",P,[o(u,{href:"#",class:"btn btn-lg border border-slate-200 text-gray-700 transition duration-150 hover:border-gray-400 hover:text-gray-900 hover:shadow"},{default:n(()=>[o(w,{class:"h-5 w-5"}),S]),_:1})])])]),_:1})}}});export{K as default};
