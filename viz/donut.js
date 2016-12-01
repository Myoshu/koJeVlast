/*d3 v4 */
/*canvas - https://bl.ocks.org/mbostock/2394b23da1994fc202e1*/
//convert svg to canvas and to png https://bl.ocks.org/biovisualize/8187844

function draw() {




    var canvas = document.querySelector("canvas"),
        context = canvas.getContext("2d");

    var width = canvas.width,
        height = canvas.height,
        radius = Math.min(width, height) / 2;

    var colors = ["#98abc5", "#8a89a6", "#7b6888", "#6b486b", "#a05d56", "#d0743c", "#ff8c00"];

    var arc = d3.arc()
        .outerRadius(radius - 10)
        .innerRadius(radius - 70)
        .context(context);

    var labelArc = d3.arc()
        .outerRadius(radius - 40)
        .innerRadius(radius - 40)
        .context(context);

    var pie = d3.pie()
        .sort(null)
        .value(function(d) {
            return d.population;
        });

    context.translate(width / 2, height / 2);

    d3.tsv("viz/data.tsv", function(d) {
        d.population = +d.population;
        return d;
    }, function(error, data) {
        if (error) throw error;

        var arcs = pie(data);

        arcs.forEach(function(d, i) {
            context.beginPath();
            arc(d);
            context.fillStyle = colors[i];
            context.fill();
        }); //foreach arcs

        context.beginPath();
        arcs.forEach(arc);
        context.strokeStyle = "#fff";
        context.stroke();

        context.textAlign = "center";
        context.textBaseline = "middle";
        context.fillStyle = "#000";
        arcs.forEach(function(d) {
            var c = labelArc.centroid(d);
            context.fillText(d.data.age, c[0], c[1]);
        }); //foreach arcs
    }); //request tsv
} //draw

//http://blockbuilder.org/vickygisel/c3f4eb2b16b86dd0f641263383f05a13
function drawSvg(argument) {


    var dataset = [
        { sala: "lactantes", value: 74 },
        { sala: "deambuladores", value: 85 },
        { sala: "2 años", value: 840 },
        { sala: "3 años", value: 4579 },
        { sala: "4 años", value: 5472 },
        { sala: "5 años", value: 7321 },
    ];


    var color = d3.scaleOrdinal(d3.schemeCategory20b);

    var width = 360;
    var height = 360;
    var radius = Math.min(width, height) / 2;

    var svg = d3.select('#viz')
        .append('svg')
        .attr('width', width)
        .attr('height', height)
        .append('g')
        .attr('transform', 'translate(' + (width / 2) +
            ',' + (height / 2) + ')');

    var colors = ["#98abc5", "#8a89a6", "#7b6888", "#6b486b", "#a05d56", "#d0743c", "#ff8c00"];

    var arc = d3.arc()
        .outerRadius(radius - 10)
        .innerRadius(radius - 70)
        .padAngle(0.01);

    var labelArc = d3.arc()
        .outerRadius(radius - 40)
        .innerRadius(radius - 40);

    var pie = d3.pie()
        .sort(null)
        .value(function(d) {
            return d.population;
        });



    //legend

    var legendRectSize = 18;
    var legendSpacing = 4;




    d3.tsv("viz/data.tsv", function(d) {
        d.population = +d.population;
        return d;
    }, function(error, data) {
        if (error) throw error;

        var path = svg.selectAll('path')
            .data(pie(data))
            .enter()
            .append('path')
            .attr('d', arc)
            .attr('fill', function(d, i) {
                return color(d.data.population);

            })
            .on("click",function (d,i) {
            	alert("sada")
            })
            .on("mouseover",function (d,i) {
            	var levo = d3.select(this);            	
            	levo.attr('class', 'pomeren');
            })
            .on("mouseout",function (d,i) {
            	var levo = d3.select(this);  
            	levo.classed('pomeren',false);

            });

        var legend = svg.selectAll('.legend')
            .data(color.domain())
            .enter()
            .append('g')
            .attr('class', 'legend')
            .attr('transform', function(d, i) {
                var height = legendRectSize + legendSpacing;
                var offset = height * color.domain().length / 2;
                var horz = -2 * legendRectSize;
                var vert = i * height - offset;
                return 'translate(' + horz + ',' + vert + ')';
            });

        legend.append('rect')
            .attr('width', legendRectSize)
            .attr('height', legendRectSize)
            .style('fill', color)
            .style('stroke', color);

        legend.append('text')
            .data(pie(data))
            .attr('x', legendRectSize + legendSpacing)
            .attr('y', legendRectSize - legendSpacing)
            .text(function(d) {
                console.log(d);
                return d.data.age;
            });

    }); // tsv load
}
