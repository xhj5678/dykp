const api = {
  get(url, cb){
    fetch(url).then(r=>r.json()).then(cb);
  },
  post(url, data, cb){
    fetch(url,{
      method:'POST',
      headers:{'Content-Type':'application/x-www-form-urlencoded'},
      body:new URLSearchParams(data)
    }).then(r=>r.json()).then(cb);
  }
}