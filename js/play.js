const vid = new URLSearchParams(location.search).get('id');
const player = document.getElementById('player');
const lock = document.getElementById('lock');

api.post('/api/video/play', {vid}, res => {

  if (res.data.can_play) {
    player.src = res.data.play_url;
  } else {
    player.src = res.data.play_url || '';
    lock.style.display = 'block';

    // 试看时间
    setTimeout(() => {
      player.pause();
      lock.style.display = 'block';
    }, res.data.try_seconds * 1000);
  }
});

function usePoint(){
  api.post('/api/point/use', {vid}, res=>{
    alert(res.msg);
    location.reload();
  });
}

function useCard(){
  const code = prompt('请输入卡密');
  if(!code) return;
  api.post('/api/card/use', {code}, res=>{
    alert(res.msg);
    location.reload();
  });
}

function vip(){
  alert('跳转 VIP 支付');
}