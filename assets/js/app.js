document.querySelectorAll('.toggle-pass').forEach(function(btn){
  btn.addEventListener('click', function(){
    var input = document.getElementById(btn.dataset.target); if(!input) return;
    input.type = input.type === 'password' ? 'text' : 'password';
    btn.textContent = input.type === 'password' ? 'Show' : 'Hide';
  });
});
setTimeout(function(){ document.querySelectorAll('.alert').forEach(function(a){ a.style.opacity='0'; setTimeout(function(){a.remove();},400); }); }, 6000);
document.querySelectorAll('[data-confirm]').forEach(function(el){
  el.addEventListener('submit', function(ev){ if(!confirm(el.dataset.confirm)) ev.preventDefault(); });
  el.addEventListener('click', function(ev){ if(el.tagName === 'A' && !confirm(el.dataset.confirm)) ev.preventDefault(); });
});
