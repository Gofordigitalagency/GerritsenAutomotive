/* Gerritsen Automotive 2026 */
'use strict';
(()=>{try{if(localStorage.getItem('ga_theme')==='light')document.documentElement.classList.add('light')}catch(e){}})();

document.addEventListener('DOMContentLoaded',()=>{

  // Scroll animations
  const aObs=new IntersectionObserver(en=>{en.forEach(e=>{if(e.isIntersecting){e.target.classList.add('vis');aObs.unobserve(e.target)}})},{threshold:.05,rootMargin:'0px 0px -20px 0px'});
  document.querySelectorAll('[data-a]').forEach(el=>aObs.observe(el));

  // Hero particles
  const pc=document.getElementById('heroParticles');
  if(pc)for(let i=0;i<20;i++){const s=document.createElement('span');s.style.left=Math.random()*100+'%';s.style.animationDuration=(12+Math.random()*20)+'s';s.style.animationDelay=Math.random()*14+'s';s.style.width=s.style.height=(1.5+Math.random()*2.5)+'px';pc.appendChild(s)}

  // Nav
  const tog=document.getElementById('menuToggle'),ov=document.getElementById('navOverlay'),cls=document.getElementById('menuClose'),nav=document.querySelector('.navbar');
  const setOff=()=>{if(nav)document.documentElement.style.setProperty('--header-offset',nav.offsetHeight+'px')};
  function openM(){document.body.classList.add('menu-open');tog?.classList.add('is-open');tog?.setAttribute('aria-expanded','true');ov?.classList.add('is-open');ov?.setAttribute('aria-hidden','false')}
  function closeM(){document.body.classList.remove('menu-open');tog?.classList.remove('is-open');tog?.setAttribute('aria-expanded','false');ov?.classList.remove('is-open');ov?.setAttribute('aria-hidden','true')}
  tog?.addEventListener('click',()=>ov?.classList.contains('is-open')?closeM():openM());
  cls?.addEventListener('click',closeM);
  ov?.addEventListener('click',e=>{if(e.target===ov)closeM()});
  document.addEventListener('keydown',e=>{if(e.key==='Escape'&&ov?.classList.contains('is-open'))closeM()});
  const onScroll=()=>{if(!nav)return;nav.classList.toggle('scrolled',scrollY>8);setOff()};
  const getOff=()=>{const v=getComputedStyle(document.documentElement).getPropertyValue('--header-offset').trim();const n=parseInt(v,10);return Number.isFinite(n)?n:(nav?.offsetHeight||0)};
  const scrollTo=el=>{window.scrollTo({top:Math.max(0,el.getBoundingClientRect().top+scrollY-getOff()-12),behavior:'smooth'})};
  const samePage=a=>{if(!a.hash?.startsWith('#'))return false;return location.pathname.replace(/\/+$/,'')===a.pathname.replace(/\/+$/,'')};
  document.querySelectorAll('a[href^="#"]').forEach(a=>{a.addEventListener('click',e=>{if(!samePage(a))return;const t=document.querySelector(a.hash);if(!t)return;e.preventDefault();if(ov?.classList.contains('is-open'))closeM();scrollTo(t);history.pushState(null,'',a.hash)})});
  onScroll();setOff();
  window.addEventListener('scroll',onScroll,{passive:true});
  window.addEventListener('resize',setOff);
  if('ResizeObserver'in window&&nav)new ResizeObserver(setOff).observe(nav);

  // Theme
  const TK='ga_theme';
  function isTheme(el){return!!el&&(el.id==='themeToggle'||el.id==='themeToggleMobile'||el.closest?.('#themeToggle,#themeToggleMobile'))}
  function syncA(){const l=document.documentElement.classList.contains('light');document.querySelectorAll('#themeToggle,#themeToggleMobile').forEach(b=>b.setAttribute('aria-pressed',String(l)))}
  function toggle(){const n=!document.documentElement.classList.contains('light');document.documentElement.classList.toggle('light',n);try{localStorage.setItem(TK,n?'light':'dark')}catch(e){}syncA()}
  document.addEventListener('click',e=>{if(isTheme(e.target)){e.preventDefault();toggle()}});syncA();

  // Auto-animate car cards
  const grid=document.getElementById('nieuwGrid');
  if(grid){
    const mo=new MutationObserver(()=>{grid.querySelectorAll('.car-card:not([data-a])').forEach((c,i)=>{c.setAttribute('data-a','');c.style.transitionDelay=(i*.03)+'s';aObs.observe(c)})});
    mo.observe(grid,{childList:true});
    grid.querySelectorAll('.car-card').forEach((c,i)=>{c.setAttribute('data-a','');c.style.transitionDelay=(i*.03)+'s';aObs.observe(c)});
  }
});
