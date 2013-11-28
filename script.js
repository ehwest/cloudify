

    d3.json(url, update_data);

 
// make these things debuggable from console
var my = { };
 
function draw_graph(data) {
  var results,
      chart,
      dots,
      margin = 100,
      w = 8,
      h = 500,
      x, y,
      width = $('#lab').width( ),
      xAxis, yAxis
      zoom = 40
      ;
  var scaleExtent = [ 0, 200 ];
 
  // allow some granularity between our notion of scale and d3's
  my.zScale = d3.scale.linear( ).domain(scaleExtent).rangeRound( [0, 1000] );
  // but force only 8 layout switches
  my.zSwitching = d3.scale.linear( ).domain([0,1000]).rangeRound([0,8]);
 
//  console.log( $('#lab'), $('#lab').width( ) );
  $('#lab #test').remove( );
  $('#lab').append( $('#clone').clone(true).attr('id', 'test') );
 
  chart = d3.select( '#test' ).append( 'svg' )
      .attr( 'class', 'chart' )
      .attr( 'width', width )
      .attr( 'height', h )
      .append('g');
 
  d3.select('svg g')
      .attr('transform', 'translate(50, 50)');
 
  var first = d3.time.day.round(data[0].date),
      last  = d3.time.day.round(d3.time.day.offset(data[data.length-1].date, 1))
      ;
  my.first = first;
  my.last = last;
  my.range = d3.time.day.range(first, last);
  console.log(my);
  x = d3.time.scale()
      .domain( [first, last] )
      .range(  [0, ( 66 * my.range.length ) - margin] )
      ;
 
  y = d3.scale.linear()
      .domain( [0, d3.max( data, function( d ) { return d.sugar; } )] )
      .rangeRound( [0, h - margin] );
 
  my.x = x;
  my.y = y;
  // safety bars
  var safeties = {
    low: 70,
    high: 140,
    x: x.range()[0],
    y: (h - margin) - y(140) + .5,
    width: (width),
    height: y(140) -  y(70)  + .5
 
  };
  var bars = chart.append('g')
          .attr('class', 'safety');
 
  bars.append('rect')
      .attr('class', 'safe-sugar')
      ;
 
 
  // Bars
  dots = chart.append('g')
      .attr('class', 'dots');
 
  dots.selectAll( 'circle' )
      .data( data )
    .enter().append( 'circle' )
      .attr( 'r', '.5ex')
      // .attr( 'width', w )
      // .attr( 'height', function( d ) { return y( d.population ) } )
      ;
 
  // Axis
  xAxis = d3.svg.axis()
      .scale(x)
      .ticks(my.range.length)
      .tickSize(12, 1, 1)
      ;
      // .tickFormat(d3.time.format('%m/%d/%y'))
      //.tickSize(25, 18);
 
  yAxis = d3.svg.axis()
      .scale(d3.scale.linear().domain( [0, d3.max( data, function( d ) { return d.sugar || 0; } )] ).rangeRound( [h - margin, 0] ))
      .ticks(7)
      .tickSize(6, 3, 1)
      .orient('left');
 
  chart.append('g')
      .attr('class', 'x axis')
      .attr('transform', 'translate(0, ' + (h - margin) + ')');
 
  chart.append('g')
      .attr('class', 'y axis');
 
  d3.select("svg").call(d3.behavior.zoom()
    //  By supplying only .x() any pan/zoom can only alter the x scale.  y
    //  scale remains fixed.
      .x(x)
      .scaleExtent( scaleExtent )
      .scale( 4 )
      .on("zoom",render)
  );
 
  function render() {
    var num_t = my.range.length;
    var halve = d3.round(num_t / 2);
    var factors = [ 88, 66, 44, 50, 66, 88, 66, 22, 22];
    var num_ticks = [ d3.round(halve * .5), halve, d3.round(halve * 1.5),
                      num_t,
                      d3.round(halve * 1.75),
                      halve, halve, halve, halve, halve ];
    // if the zoom level changes, consider resizing the scales.
    var old_zoom = zoom;
    if (d3.event) {
      zoom = my.zScale( d3.event.scale );
      var i = my.zSwitching(zoom);
      if ( i != my.zSwitching(old_zoom) ) {
        console.log('changing scales i', i, 'zoom', zoom, 'old_zoom', old_zoom);
        // zoom = d3.round(d3.event.scale * 10);
        my.pixel_factor = 66;
        // reset some how much space each unit of time takes at different zoom
        // levels..
        my.pixel_factor = factors[i] || 66;
        // changing the output range of the scale will change how much space is
        // taken up per unit of time.
        x.range(  [0, ( my.pixel_factor * my.range.length ) - margin] )
        ;
        // maybe change number of ticks
         // xAxis = xAxis.ticks(num_ticks[i] || num_t);
      }
      console.log('i', i, 'z', zoom, d3.event.scale, d3.event, my, 'old', old_zoom);
    }
    bars.selectAll("rect")
      .attr( 'x', safeties.x)
      .attr( 'y', safeties.y)
      .attr( 'width', safeties.width)
      .attr( 'height', safeties.height)
      ;
    dots.selectAll("circle")
      .attr( 'cx', function( d, i ) { return x( d.date ) - .5; } )
      .attr( 'cy', function( d ) { return (h - margin) - y( d.sugar ) + .5 } )
      ;
    chart.select(".x.axis").call(xAxis);
    chart.select(".y.axis").call(yAxis);
  }
 
  //  Call this to place initially
  render();
}
 
 
 
function update_data(rows)  {
  // console.log('XXX', this, arguments);
  if (rows) {
    rows.forEach(fix_row);
    draw_graph(rows);
  }
}
 
function fix_row(row, i) {
  row.date = d3.time.format.iso.parse(row.date);
  row.sugar = parseInt(row.sugar);
  // console.log('rows each', this, arguments);
 
}
