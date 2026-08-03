document.addEventListener('DOMContentLoaded',()=>{
 const search=document.getElementById('tableSearch');
 if(search){search.addEventListener('input',()=>{const q=search.value.toLowerCase();document.querySelectorAll('#dataTable tbody tr').forEach(r=>r.style.display=r.innerText.toLowerCase().includes(q)?'':'none')})}
 const ps=document.getElementById('productSearch'), list=document.getElementById('productList');
 if(ps&&list){ps.addEventListener('input',()=>{const q=ps.value.toLowerCase();list.querySelectorAll('.product').forEach(b=>b.style.display=b.innerText.toLowerCase().includes(q)?'':'none')})}
 const cartBox=document.getElementById('cart'), totalEl=document.getElementById('total'), input=document.getElementById('cartInput');
 if(!cartBox)return;
 let cart=[];
 function render(){cartBox.innerHTML='';let total=0;
  cart.forEach((x,i)=>{total+=x.price*x.qty;const row=document.createElement('div');row.className='cart-row';
   row.innerHTML=`<span>${esc(x.name)}</span><input type="number" min="1" max="${x.stock}" value="${x.qty}"><span>${(x.price*x.qty).toFixed(2)}</span><button type="button">×</button>`;
   row.querySelector('input').onchange=e=>{x.qty=Math.max(1,Math.min(x.stock,parseInt(e.target.value)||1));render()};
   row.querySelector('button').onclick=()=>{cart.splice(i,1);render()};cartBox.appendChild(row);
  }); totalEl.textContent=total.toFixed(2);input.value=JSON.stringify(cart);
 }
 function esc(s){return s.replace(/[&<>"']/g,m=>({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#039;'}[m]))}
 document.querySelectorAll('.product').forEach(b=>b.addEventListener('click',()=>{const id=+b.dataset.id;const old=cart.find(x=>x.id===id);if(old){old.qty=Math.min(old.stock,old.qty+1)}else cart.push({id,name:b.dataset.name,price:+b.dataset.price,stock:+b.dataset.stock,qty:1});render()}));
 render();
});