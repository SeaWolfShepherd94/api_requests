<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <title>Sample API 2</title>
</head>

<style>

div#chart {width: 100%;}

</style>

<body>

<script src="https://d3js.org/d3.v4.js"></script>

<div id="chart"></div>

<script>
    d3.csv("daily.csv", function(error, data) {
            if (error) {
                throw error;
            }
            var width = 600,
				height = 460,
				radius = (Math.min(width, height) - 50) / 2;
            var svgPie = d3.select("#chart").append("svg")
				.attr("width", width)
				.attr("height", height)
				.append("g")
				.attr("transform", "translate(" + width / 2 + "," + height / 2 + ")");

    		var color = d3.scaleOrdinal(['red','orange','yellow','green','blue','purple','brown','gray','gold','cyan','silver','pink']);

    		// Generate the pie
    		var pie = d3.pie();

    		// Generate the arcs
    		var arc = d3.arc()
                .innerRadius(0)
                .outerRadius(radius);
                	
            var positiveIncreases = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
            var months = ["Jan", "Feb", "Mar", "April", "May", "June", "July", "Aug", "Sep", "Oct", "Nov", "Dec"];
            data.forEach(function(d, i) {
            	var str = d.date;
            	var date = +str.substring(4, 6);
            	var positiveIncrease = +d.positiveIncrease;
            	console.log(date);
				console.log(d.positiveIncrease);
				positiveIncreases[date-1] += positiveIncrease;
			});

    		//Generate groups
    		var arcs = svgPie.selectAll("arc")
                	.data(pie(positiveIncreases))
                	.enter()
                	.append("g")
                	.attr("class", "arc")
        
    		var path = d3.arc()
    			.outerRadius(radius - 10)
    			.innerRadius(0);
    	
    		var label = d3.arc()
    			.outerRadius(radius + 20)
	    		.innerRadius(radius-10);

            arcs.append("path")
        		.attr("fill", function(d, i) {
        			console.log(d);
            		return color(i);
        		})
        		.attr("d", arc);
        
            arcs.append("text")
               .attr("transform", function(d) { 
	  	var midAngle = d.endAngle < Math.PI ? d.startAngle/2 + d.endAngle/2 : d.startAngle/2  + d.endAngle/2 + Math.PI ;
	  	return "translate(" + label.centroid(d)[0] + "," + label.centroid(d)[1] + ") rotate(-90) rotate(" + (midAngle * 180/Math.PI) + ")"; })
               .text(function(d,i) { if (d.value > 0) { return months[i] + " - " + d.value; } else return "";});
            });
</script>
</body>
</html>
