<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <title>Sample API 2</title>
</head>

<body>

<script src="https://d3js.org/d3.v4.js"></script>

<svg width="300" height="200"> </svg>
<script>
    d3.csv("daily.csv", function(error, data) {
            if (error) {
                throw error;
            }
            
            var svg = d3.select("svg"),
        		width = svg.attr("width"),
        		height = svg.attr("height"),
        		radius = Math.min(width, height) / 2,
        		g= svg.append("g").attr("transform", "translate(" + width / 2 + "," + height / 2 + ")");

    		var color = d3.scaleOrdinal(['red','orange','yellow','green','blue','purple','brown','gray','gold','cyan','silver','pink']);

    		// Generate the pie
    		var pie = d3.pie();

    		// Generate the arcs
    		var arc = d3.arc()
                .innerRadius(0)
                .outerRadius(radius);
                	
            var positiveIncreases = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
            data.forEach(function(d, i) {
            	var str = d.date;
            	var date = +str.substring(4, 6);
            	var positiveIncrease = +d.positiveIncrease;
            	console.log(date);
				console.log(d.positiveIncrease);
				positiveIncreases[date-1] += positiveIncrease;
			});

    		//Generate groups
    		var arcs = g.selectAll("arc")
                	.data(pie(positiveIncreases))
                	.enter()
                	.append("g")
                	.attr("class", "arc")
        
    		var path = d3.arc()
    			.outerRadius(radius - 10)
    			.innerRadius(0);
    	
    		var label = d3.arc()
    			.outerRadius(radius)
    			.innerRadius(radius - 80);

            arcs.append("path")
        		.attr("fill", function(d, i) {
        			console.log(d);
            		return color(i);
        		})
        		.attr("d", arc);
        
            arcs.append("text")
               .attr("transform", function(d) { 
                        return "translate(" + label.centroid(d) + ")"; 
                })
               .text(function(d) { if (d.value > 0) return d.value; else return "";});
            });
</script>
</body>
</html>