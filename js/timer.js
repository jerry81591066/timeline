let date = new Date('Aug 27, 2019 00:00:00').getTime();

let x = setInterval(function () {
    var now = new Date().getTime();
    var diff = now - date;

    document.getElementById('days').innerText = (diff / (24*60*60*1000)).toFixed(8);
}, 10);
