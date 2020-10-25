<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>d3 Updating Bar Chart With Dropdown</title>
        <script src="https://d3js.org/d3.v3.min.js" charset="utf-8"></script>
        <style>
        body {
            font: 10px sans-serif;
        }
        select {
            display: block;
        }
        .bar {
            fill: steelblue;
            opacity: 0.8;
        }

        .axis path,
        .axis line {
            fill: none;
            stroke: #000;
            shape-rendering: crispEdges;
        }
        </style>
    </head>
    <body>
        <div id='vis-container'></div>
        <script type="text/javascript">
            // Load and munge data, then make the visualization.
            var fileName = "https://api.covidtracking.com/v1/us/daily.csv";
            var months = ["Jan", "Feb", "Mar", "April", "May", "June", "July", "Aug", "Sep", "Oct", "Nov", "Dec"];
            var positiveIncreases = [[], [], [], [], [], [], [], [], [], [], [], []];
            var days = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31];

            d3.csv(fileName, function(error, data) {
                var covidMap = {}
                for (var i in months) {
                	covidMap[months[i]] = [];
                }
                data.forEach(function(d) {
                    var str = d.date;
            		var date = +str.substring(4, 6);
            		var positiveIncrease = +d.positiveIncrease;
					positiveIncreases[date-1].push(positiveIncrease);
					var month = months[date-1];
					covidMap[month].push(+positiveIncrease);
                });
                makeVis(covidMap);
            });

            var makeVis = function(covidMap) {
                // Define dimensions of vis
                var margin = { top: 30, right: 50, bottom: 30, left: 50 },
                    width  = 550 - margin.left - margin.right,
                    height = 250 - margin.top  - margin.bottom;

                // Make x scale
                var xScale = d3.scale.ordinal()
                    .domain(days)
                    .rangeRoundBands([0, width], 0.1);

                // Make y scale, the domain will be defined on bar update
                var yScale = d3.scale.linear()
                    .range([height, 0]);

                // Create canvas
                var canvas = d3.select("#vis-container")
                  .append("svg")
                    .attr("width",  width  + margin.left + margin.right)
                    .attr("height", height + margin.top  + margin.bottom)
                  .append("g")
                    .attr("transform", "translate(" + margin.left + "," + margin.top + ")");

                // Make x-axis and add to canvas
                var xAxis = d3.svg.axis()
                    .scale(xScale)
                    .orient("bottom");

                canvas.append("g")
                    .attr("class", "x axis")
                    .attr("transform", "translate(0," + height + ")")
                    .call(xAxis);

                // Make y-axis and add to canvas
                var yAxis = d3.svg.axis()
                    .scale(yScale)
                    .orient("left");

                var yAxisHandleForUpdate = canvas.append("g")
                    .attr("class", "y axis")
                    .call(yAxis);

                yAxisHandleForUpdate.append("text")
                    .attr("transform", "rotate(-90)")
                    .attr("y", 6)
                    .attr("dy", ".71em")
                    .style("text-anchor", "end")
                    .text("Value");

                var updateBars = function(data,j) {
                	console.log(data);
                    // First update the y-axis domain to match data
                    yScale.domain( d3.extent(data) );
                    yAxisHandleForUpdate.call(yAxis);

                    var bars = canvas.selectAll(".bar").data(data);

                    // Add bars for new data
                    bars.enter()
                      .append("rect")
                        .attr("class", "bar")
                        .attr("x", function(d,i) { return xScale( days[i] ); })
                        .attr("width", xScale.rangeBand())
                        .attr("y", function(d,i) { return yScale(d); })
                        .attr("height", function(d,i) { return height - yScale(d); });

                    // Update old ones, already have x / width from before
                    bars
                        .transition().duration(250)
                        .attr("y", function(d,i) { return yScale(d); })
                        .attr("height", function(d,i) { return height - yScale(d); });

                    // Remove old ones
                    bars.exit().remove();
                };

                // Handler for dropdown value change
                var dropdownChange = function() {
                    var newMonth = d3.select(this).property('value'),
                        newData   = covidMap[newMonth];

                    updateBars(newData,newMonth);
                };

                // Get names of cereals, for dropdown
                var covidCases = Object.keys(covidMap);
                
                for (var i in covidCases) {
                	console.log(covidCases[i]);
                }

                var dropdown = d3.select("#vis-container")
                    .insert("select", "svg")
                    .on("change", dropdownChange);

                dropdown.selectAll("option")
                    .data(covidCases)
                  .enter().append("option")
                    .attr("value", function (d) { return d; })
                    .text(function (d) {
                        return d;
                    });

                var initialData = covidMap[ covidCases[0] ];
                updateBars(initialData,0);
            };
        </script>
    </body>
</html>