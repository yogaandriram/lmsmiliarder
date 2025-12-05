@props([
  'labels' => [],
  'bars' => [],
  'line' => [],
  'height' => 240,
])

@php $id = 'chart_'.uniqid(); @endphp
<div id="{{ $id }}" class="w-full" data-labels='@json($labels)' data-bars='@json($bars)' data-line='@json($line)' data-height='@json($height)'></div>
<script>
(function(){
  var el = document.getElementById('{{ $id }}');
  var labels = JSON.parse(el.getAttribute('data-labels')||'[]');
  var bars = JSON.parse(el.getAttribute('data-bars')||'[]');
  var line = JSON.parse(el.getAttribute('data-line')||'[]');
  var w = el.clientWidth || 800; var h = parseInt(el.getAttribute('data-height')||'240',10);
  var m = { t:10, r:10, b:30, l:10 };
  var cw = w - m.l - m.r; var ch = h - m.t - m.b;
  var maxBar = 1; if (bars.length) { maxBar = Math.max.apply(null, bars); }
  var maxLine = 1; if (line.length) { maxLine = Math.max.apply(null, line); }
  var stepX = cw / Math.max(1, labels.length);
  function yBar(v){ return ch - (v/maxBar)*ch; }
  function yLine(v){ return ch - (v/maxLine)*ch; }
  var grid = '';
  for (var i=0;i<=10;i++){
    var y = m.t + i*(ch/10);
    grid += '<line x1="'+m.l+'" y1="'+y+'" x2="'+(w-m.r)+'" y2="'+y+'" stroke="rgba(255,255,255,0.08)"/>';
  }
  var barsSvg = '';
  for (var j=0;j<labels.length;j++){
    var x = m.l + j*stepX + stepX*0.1; var bw = stepX*0.6; var yb = m.t + yBar(bars[j]||0); var bh = (ch - yBar(bars[j]||0));
    barsSvg += '<rect x="'+x+'" y="'+yb+'" width="'+bw+'" height="'+bh+'" fill="rgba(59,130,246,0.7)"/>';
  }
  var path = '';
  for (var k=0;k<labels.length;k++){
    var xl = m.l + k*stepX + stepX*0.4; var yl = m.t + yLine(line[k]||0);
    path += (k===0? 'M':'L')+xl+','+yl+' ';
  }
  var circles = '';
  for (var c=0;c<labels.length;c++){
    var xc = m.l + c*stepX + stepX*0.4; var yc = m.t + yLine(line[c]||0);
    circles += '<circle cx="'+xc+'" cy="'+yc+'" r="2.5" fill="#f59e0b"/>';
  }
  var ticks = '';
  for (var t=0;t<labels.length;t++){
    var xt = m.l + t*stepX + stepX*0.4;
    ticks += '<text x="'+xt+'" y="'+(h-8)+'" fill="rgba(255,255,255,0.6)" font-size="10" text-anchor="middle">'+labels[t]+'</text>';
  }
  el.innerHTML = '<svg width="'+w+'" height="'+h+'">'+grid+'<path d="'+path+'" stroke="#f59e0b" fill="none" stroke-width="2"/>' + barsSvg + circles + ticks + '</svg>';
})();
</script>
