const vid = new URLSearchParams(location.search).get('id');

api.post('/api/video/play', {vid}, res=>{
  if(res.data.can_play){
    player.src = res.data.play_url;
  }else{
    lock.innerHTML = `
      <button onclick="unlock()">解锁观看</button>
    `;
    player.currentTime = 0;
    setTimeout(()=>player.pause(), res.data.try_seconds*1000);
  }
});

function unlock(){
  // 弹出 VIP / 积分 / 卡密
}