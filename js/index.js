api.get('/api/video/list', res => {
  const box = document.getElementById('list');
  res.data.list.forEach(v => {
    box.innerHTML += `
      <div class="video" onclick="go(${v.id})">
        <img src="${v.cover}">
        <p>${v.title}</p>
      </div>
    `;
  });
});

function go(id){
  location.href = 'play.html?id=' + id;
}