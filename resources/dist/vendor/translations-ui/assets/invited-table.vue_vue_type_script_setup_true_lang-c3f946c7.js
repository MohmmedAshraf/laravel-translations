import{_ as m}from"./pagination.vue_vue_type_script_setup_true_lang-d9ec99e6.js";import{d as _,s as p,o as e,c as i,a as s,v as u,e as a,w as f,h as o,F as v,x as h,I as x,k as g}from"./app-b656bbba.js";import{_ as y}from"./icon-plus-5d1d96c0.js";import{_ as w}from"./invited-item.vue_vue_type_script_setup_true_lang-ca9beb00.js";const k={class:"w-full divide-y overflow-hidden rounded-md bg-white shadow"},b={class:"w-full shadow-md"},j={class:"flex h-14 w-full divide-x"},B=x('<div class="grid w-full grid-cols-3 divide-x"><div class="col-span-3 flex w-full items-center justify-start px-4 sm:col-span-1"><span class="text-sm font-medium text-gray-400">Email</span></div><div class="hidden w-full items-center justify-start px-4 md:flex"><span class="text-sm font-medium text-gray-400">Role</span></div><div class="hidden w-full items-center justify-start px-4 md:flex"><span class="text-sm font-medium text-gray-400">Invitation Date</span></div></div>',1),I={class:"grid w-16"},C={key:1,class:"flex w-full items-center justify-center bg-gray-50 px-4 py-12"},D=s("span",{class:"text-sm text-gray-400"},"There are no invitations yet..",-1),N=[D],z=_({__name:"invited-table",props:{invitations:{}},setup(V){return(t,E)=>{const l=y,r=g,d=m,c=p("tooltip");return e(),i("div",k,[s("div",b,[s("div",j,[B,s("div",I,[u((e(),a(r,{href:t.route("ltu.contributors.invite"),class:"group flex items-center justify-center px-3 hover:bg-blue-50"},{default:f(()=>[o(l,{class:"size-5 text-gray-400 group-hover:text-blue-600"})]),_:1},8,["href"])),[[c,"Invite Contributor"]])])])]),t.invitations.data.length?(e(!0),i(v,{key:0},h(t.invitations.data,n=>(e(),a(w,{key:n.id,invitation:n},null,8,["invitation"]))),128)):(e(),i("div",C,N)),o(d,{links:t.invitations.links,meta:t.invitations.meta},null,8,["links","meta"])])}}});export{z as _};
