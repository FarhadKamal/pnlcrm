@include('layouts.navbar')

<style>
    #annualAchievePerChartdiv {
        width: 100%;
        height: 300px;
        font-size: 0.8rem;
    }

    #q1AchievePerChartdiv {
        width: 100%;
        height: 200px;
        font-size: 0.6rem;
    }

    #q2AchievePerChartdiv {
        width: 100%;
        height: 200px;
        font-size: 0.6rem;
    }

    #q3AchievePerChartdiv {
        width: 100%;
        height: 200px;
        font-size: 0.6rem;
    }

    #q4AchievePerChartdiv {
        width: 100%;
        height: 200px;
        font-size: 0.6rem;
    }

    #topSoldProduct {
        width: 100%;
        height: 400px;
        font-size: 0.7rem;
    }

    #topSoldBrand {
        width: 100%;
        height: 400px;
        font-size: 0.7rem;
    }

    #totalOutstanding {
        width: 100%;
        height: 400px;
        font-size: 0.7rem;
    }

    #top5SalesPersonsCQ {
        width: 100%;
        height: 300px;
        font-size: 0.6rem;
    }
</style>

<div class="container mt-2 mb-3">
    <h5 class="text-center">Graph Report (Year: {{ $financialYear }})</h5>
    <hr>
    <div class="row shadow-4">
        <div class="col-md-5">
            <p class="text-center p-0 m-0 fw-bold fs-08rem">Annual Achievement</p>
            <div id="annualAchievePerChartdiv"></div>
        </div>
        <div class="col-md-7">
            <div class="row">
                <div class="col-md-6 p-1" style="background:rgb(240, 240, 240)">
                    <p class="text-center p-0 m-0 fw-bold fs-08rem">Qurter One Achievement</p>
                    <div id="q1AchievePerChartdiv"></div>
                </div>
                <div class="col-md-6 p-1" style="background:rgb(210, 210, 210)">
                    <p class="text-center p-0 m-0 fw-bold fs-08rem">Qurter Two Achievement</p>
                    <div id="q2AchievePerChartdiv"></div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 p-1" style="background:rgb(210, 210, 210)">
                    <p class="text-center p-0 m-0 fw-bold fs-08rem">Qurter Three Achievement</p>
                    <div id="q3AchievePerChartdiv"></div>
                </div>
                <div class="col-md-6 p-1" style="background:rgb(240, 240, 240)">
                    <p class="text-center p-0 m-0 fw-bold fs-08rem">Qurter Four Achievement</p>
                    <div id="q4AchievePerChartdiv"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-5 border">
        <div class="col-md-12">
            <p class="text-center p-0 m-0 fw-bold fs-08rem">Top 20 Sold Product</p>
            <div id="topSoldProduct"></div>
        </div>
    </div>
    <div class="row mt-5">
        <div class="col-md-6 p-1" style="background:rgb(240, 240, 240)">
            <p class="text-center p-0 m-0 fw-bold fs-08rem">Top Sold Brands</p>
            <div id="topSoldBrand"></div>
        </div>
        <div class="col-md-6">
            <p class="text-center p-0 m-0 fw-bold fs-08rem">Total Outstanding</p>
            <div id="totalOutstanding">
                <img src="{{ asset('images/system/waves.gif') }}" alt="" class="graphLoadingGif"
                    id="totalOutstandingLoader">
            </div>
        </div>
    </div>
    <div class="row mt-5">
        <div class="col-md-6 p-1">
            <p class="text-center p-0 m-0 fw-bold fs-08rem">Top 5 Salesperson Current Quarter (Target/Sales)</p>
            <div id="top5SalesPersonsCQ"></div>
        </div>
    </div>
</div>

<script src="https://cdn.amcharts.com/lib/5/index.js"></script>
<script src="https://cdn.amcharts.com/lib/5/xy.js"></script>
<script src="https://cdn.amcharts.com/lib/5/radar.js"></script>
<script src="https://cdn.amcharts.com/lib/5/themes/Animated.js"></script>


{{-- Document Load Script Start --}}
<script>
    var annualAchievementPer = 0;
    var top5SalesPersonGraph = 0;
    var q1AchievementPer = 0;
    var q2AchievementPer = 0;
    var q3AchievementPer = 0;
    var q4AchievementPer = 0;
    var q5AchievementPer = 0;
    var topSoldProduct = 0;
    var topSoldBrand = 0;
    var grandTotalOutstanding = 0;
    var grandTotaldueWithin30 = 0;
    var grandTotaldueWithin31_60 = 0;
    var grandTotaldueWithin61_90 = 0;
    var grandTotaldueWithin91_180 = 0;
    var grandTotaldueWithin180plus = 0;
    var grandTotaldueWithin365plus = 0;
    document.addEventListener('DOMContentLoaded', function() {
        fetch('/annualAchieveGraph')
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(json => {
                annualAchievementPer = json.annualAchievementPer;
                q1AchievementPer = json.q1AchievementPer;
                q2AchievementPer = json.q2AchievementPer;
                q3AchievementPer = json.q3AchievementPer;
                q4AchievementPer = json.q4AchievementPer;
                annualAch();
                q1Ach();
                q2Ach();
                q3Ach();
                q4Ach();
            })
            .catch(error => {
                console.error('There was a problem with the fetch operation:', error);
            });

        fetch('/top5SalesPersonGraph')
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(json => {
                top5SalesPersonGraph = json.top5SalesPersonsCQ;
                top5SalesPersons();
            })
            .catch(error => {
                console.error('There was a problem with the fetch operation:', error);
            });
        fetch('/topSoldProduct')
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(json => {
                topSoldProduct = json.topSoldProduct;
                topSoldProducts();
            })
            .catch(error => {
                console.error('There was a problem with the fetch operation:', error);
            });
        fetch('/topSoldBrand')
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(json => {
                topSoldBrand = json.topSoldBrand;
                topSoldBrands();
            })
            .catch(error => {
                console.error('There was a problem with the fetch operation:', error);
            });
        fetch('/totalOutstanding')
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(json => {
                grandTotalOutstanding = json.grandTotalOutstanding;
                grandTotaldueWithin30 = json.grandTotaldueWithin30;
                grandTotaldueWithin31_60 = json.grandTotaldueWithin31_60;
                grandTotaldueWithin61_90 = json.grandTotaldueWithin61_90;
                grandTotaldueWithin91_180 = json.grandTotaldueWithin91_180;
                grandTotaldueWithin180plus = json.grandTotaldueWithin180plus;
                grandTotaldueWithin365plus = json.grandTotaldueWithin365plus;
                totalOutstandings();
                document.querySelector('#totalOutstandingLoader').classList.add("d-none");
            })
            .catch(error => {
                console.error('There was a problem with the fetch operation:', error);
            });

    });
</script>
{{-- Document Load Script End  --}}


<script>
    function annualAch() {
        am5.ready(function() {

            // Create root element
            // https://www.amcharts.com/docs/v5/getting-started/#Root_element
            var root = am5.Root.new("annualAchievePerChartdiv");


            // Set themes
            // https://www.amcharts.com/docs/v5/concepts/themes/
            root.setThemes([
                am5themes_Animated.new(root)
            ]);


            // Create chart
            // https://www.amcharts.com/docs/v5/charts/radar-chart/
            var chart = root.container.children.push(am5radar.RadarChart.new(root, {
                panX: false,
                panY: false,
                startAngle: 160,
                endAngle: 380
            }));


            // Create axis and its renderer
            // https://www.amcharts.com/docs/v5/charts/radar-chart/gauge-charts/#Axes
            var axisRenderer = am5radar.AxisRendererCircular.new(root, {
                innerRadius: -40
            });

            axisRenderer.grid.template.setAll({
                stroke: root.interfaceColors.get("background"),
                visible: true,
                strokeOpacity: 0.8
            });

            var xAxis = chart.xAxes.push(am5xy.ValueAxis.new(root, {
                maxDeviation: 0,
                min: 0,
                max: 100,
                strictMinMax: true,
                renderer: axisRenderer
            }));


            // Add clock hand
            // https://www.amcharts.com/docs/v5/charts/radar-chart/gauge-charts/#Clock_hands
            var axisDataItem = xAxis.makeDataItem({});

            var clockHand = am5radar.ClockHand.new(root, {
                pinRadius: am5.percent(20),
                radius: am5.percent(100),
                bottomWidth: 40
            })

            var bullet = axisDataItem.set("bullet", am5xy.AxisBullet.new(root, {
                sprite: clockHand
            }));

            xAxis.createAxisRange(axisDataItem);

            var label = chart.radarContainer.children.push(am5.Label.new(root, {
                fill: am5.color(0xffffff),
                centerX: am5.percent(50),
                textAlign: "center",
                centerY: am5.percent(50),
                fontSize: "3em"
            }));

            axisDataItem.set("value", annualAchievementPer);
            bullet.get("sprite").on("rotation", function() {
                var value = axisDataItem.get("value");
                var text = Math.round(axisDataItem.get("value")).toString();
                var fill = am5.color(0x000000);
                xAxis.axisRanges.each(function(axisRange) {
                    if (value >= axisRange.get("value") && value <= axisRange.get("endValue")) {
                        fill = axisRange.get("axisFill").get("fill");
                    }
                })

                label.set("text", Math.round(value).toString());

                clockHand.pin.animate({
                    key: "fill",
                    to: fill,
                    duration: 500,
                    easing: am5.ease.out(am5.ease.cubic)
                })
                clockHand.hand.animate({
                    key: "fill",
                    to: fill,
                    duration: 500,
                    easing: am5.ease.out(am5.ease.cubic)
                })
            });

            // setInterval(function() {
            //     axisDataItem.animate({
            //         key: "value",
            //         // to: Math.round(Math.random() * 140 - 40),
            //         to: Math.round(63.5),
            //         duration: 500,
            //         easing: am5.ease.out(am5.ease.cubic)
            //     });
            // }, 2000)

            chart.bulletsContainer.set("mask", undefined);


            // Create axis ranges bands
            // https://www.amcharts.com/docs/v5/charts/radar-chart/gauge-charts/#Bands
            var bandsData = [{
                // title: "Foundational",
                color: "#FF0000",
                lowScore: 0,
                highScore: 20
            }, {
                // title: "Developing",
                // color: "#f3eb0c",
                color: "#F7E200",
                lowScore: 20,
                highScore: 40
            }, {
                // title: "Maturing",
                color: "#f3esds",
                lowScore: 40,
                highScore: 60
            }, {
                // title: "Sustainable",
                color: "#54b947",
                lowScore: 60,
                highScore: 80
            }, {
                // title: "High Perform",
                color: "#19461A",
                lowScore: 80,
                highScore: 100
            }];

            am5.array.each(bandsData, function(data) {
                var axisRange = xAxis.createAxisRange(xAxis.makeDataItem({}));

                axisRange.setAll({
                    value: data.lowScore,
                    endValue: data.highScore
                });

                axisRange.get("axisFill").setAll({
                    visible: true,
                    fill: am5.color(data.color),
                    fillOpacity: 0.8
                });

                axisRange.get("label").setAll({
                    text: data.title,
                    inside: true,
                    radius: 15,
                    fontSize: "0.9em",
                    fill: root.interfaceColors.get("background")
                });
            });


            // Make stuff animate on load
            chart.appear(1000, 100);

        }); // end am5.ready()
    }
</script>

<script>
    function q1Ach() {
        am5.ready(function() {

            // Create root element
            // https://www.amcharts.com/docs/v5/getting-started/#Root_element
            var root = am5.Root.new("q1AchievePerChartdiv");


            // Set themes
            // https://www.amcharts.com/docs/v5/concepts/themes/
            root.setThemes([
                am5themes_Animated.new(root)
            ]);


            // Create chart
            // https://www.amcharts.com/docs/v5/charts/radar-chart/
            var chart = root.container.children.push(am5radar.RadarChart.new(root, {
                panX: false,
                panY: false,
                startAngle: 160,
                endAngle: 380
            }));


            // Create axis and its renderer
            // https://www.amcharts.com/docs/v5/charts/radar-chart/gauge-charts/#Axes
            var axisRenderer = am5radar.AxisRendererCircular.new(root, {
                innerRadius: -20
            });

            axisRenderer.grid.template.setAll({
                stroke: root.interfaceColors.get("background"),
                visible: true,
                strokeOpacity: 0.8
            });

            var xAxis = chart.xAxes.push(am5xy.ValueAxis.new(root, {
                maxDeviation: 0,
                min: 0,
                max: 100,
                strictMinMax: true,
                renderer: axisRenderer
            }));


            // Add clock hand
            // https://www.amcharts.com/docs/v5/charts/radar-chart/gauge-charts/#Clock_hands
            var axisDataItem = xAxis.makeDataItem({});

            var clockHand = am5radar.ClockHand.new(root, {
                pinRadius: am5.percent(20),
                radius: am5.percent(100),
                bottomWidth: 40
            })

            var bullet = axisDataItem.set("bullet", am5xy.AxisBullet.new(root, {
                sprite: clockHand
            }));

            xAxis.createAxisRange(axisDataItem);

            var label = chart.radarContainer.children.push(am5.Label.new(root, {
                fill: am5.color(0xffffff),
                centerX: am5.percent(50),
                textAlign: "center",
                centerY: am5.percent(50),
                fontSize: "3em"
            }));

            axisDataItem.set("value", q1AchievementPer);
            bullet.get("sprite").on("rotation", function() {
                var value = axisDataItem.get("value");
                var text = Math.round(axisDataItem.get("value")).toString();
                var fill = am5.color(0x000000);
                xAxis.axisRanges.each(function(axisRange) {
                    if (value >= axisRange.get("value") && value <= axisRange.get("endValue")) {
                        fill = axisRange.get("axisFill").get("fill");
                    }
                })

                label.set("text", Math.round(value).toString());

                clockHand.pin.animate({
                    key: "fill",
                    to: fill,
                    duration: 500,
                    easing: am5.ease.out(am5.ease.cubic)
                })
                clockHand.hand.animate({
                    key: "fill",
                    to: fill,
                    duration: 500,
                    easing: am5.ease.out(am5.ease.cubic)
                })
            });

            // setInterval(function() {
            //     axisDataItem.animate({
            //         key: "value",
            //         // to: Math.round(Math.random() * 140 - 40),
            //         to: Math.round(63.5),
            //         duration: 500,
            //         easing: am5.ease.out(am5.ease.cubic)
            //     });
            // }, 2000)

            chart.bulletsContainer.set("mask", undefined);


            // Create axis ranges bands
            // https://www.amcharts.com/docs/v5/charts/radar-chart/gauge-charts/#Bands
            var bandsData = [{
                // title: "Foundational",
                color: "#FF0000",
                lowScore: 0,
                highScore: 20
            }, {
                // title: "Developing",
                // color: "#f3eb0c",
                color: "#F7E200",
                lowScore: 20,
                highScore: 40
            }, {
                // title: "Maturing",
                color: "#f3esds",
                lowScore: 40,
                highScore: 60
            }, {
                // title: "Sustainable",
                color: "#54b947",
                lowScore: 60,
                highScore: 80
            }, {
                // title: "High Perform",
                color: "#19461A",
                lowScore: 80,
                highScore: 100
            }];

            am5.array.each(bandsData, function(data) {
                var axisRange = xAxis.createAxisRange(xAxis.makeDataItem({}));

                axisRange.setAll({
                    value: data.lowScore,
                    endValue: data.highScore
                });

                axisRange.get("axisFill").setAll({
                    visible: true,
                    fill: am5.color(data.color),
                    fillOpacity: 0.8
                });

                axisRange.get("label").setAll({
                    text: data.title,
                    inside: true,
                    radius: 15,
                    fontSize: "0.9em",
                    fill: root.interfaceColors.get("background")
                });
            });


            // Make stuff animate on load
            chart.appear(1000, 100);

        }); // end am5.ready()
    }
</script>

<script>
    function q2Ach() {
        am5.ready(function() {

            // Create root element
            // https://www.amcharts.com/docs/v5/getting-started/#Root_element
            var root = am5.Root.new("q2AchievePerChartdiv");


            // Set themes
            // https://www.amcharts.com/docs/v5/concepts/themes/
            root.setThemes([
                am5themes_Animated.new(root)
            ]);


            // Create chart
            // https://www.amcharts.com/docs/v5/charts/radar-chart/
            var chart = root.container.children.push(am5radar.RadarChart.new(root, {
                panX: false,
                panY: false,
                startAngle: 160,
                endAngle: 380
            }));


            // Create axis and its renderer
            // https://www.amcharts.com/docs/v5/charts/radar-chart/gauge-charts/#Axes
            var axisRenderer = am5radar.AxisRendererCircular.new(root, {
                innerRadius: -20
            });

            axisRenderer.grid.template.setAll({
                stroke: root.interfaceColors.get("background"),
                visible: true,
                strokeOpacity: 0.8
            });

            var xAxis = chart.xAxes.push(am5xy.ValueAxis.new(root, {
                maxDeviation: 0,
                min: 0,
                max: 100,
                strictMinMax: true,
                renderer: axisRenderer
            }));


            // Add clock hand
            // https://www.amcharts.com/docs/v5/charts/radar-chart/gauge-charts/#Clock_hands
            var axisDataItem = xAxis.makeDataItem({});

            var clockHand = am5radar.ClockHand.new(root, {
                pinRadius: am5.percent(20),
                radius: am5.percent(100),
                bottomWidth: 40
            })

            var bullet = axisDataItem.set("bullet", am5xy.AxisBullet.new(root, {
                sprite: clockHand
            }));

            xAxis.createAxisRange(axisDataItem);

            var label = chart.radarContainer.children.push(am5.Label.new(root, {
                fill: am5.color(0xffffff),
                centerX: am5.percent(50),
                textAlign: "center",
                centerY: am5.percent(50),
                fontSize: "3em"
            }));

            axisDataItem.set("value", q2AchievementPer);
            bullet.get("sprite").on("rotation", function() {
                var value = axisDataItem.get("value");
                var text = Math.round(axisDataItem.get("value")).toString();
                var fill = am5.color(0x000000);
                xAxis.axisRanges.each(function(axisRange) {
                    if (value >= axisRange.get("value") && value <= axisRange.get("endValue")) {
                        fill = axisRange.get("axisFill").get("fill");
                    }
                })

                label.set("text", Math.round(value).toString());

                clockHand.pin.animate({
                    key: "fill",
                    to: fill,
                    duration: 500,
                    easing: am5.ease.out(am5.ease.cubic)
                })
                clockHand.hand.animate({
                    key: "fill",
                    to: fill,
                    duration: 500,
                    easing: am5.ease.out(am5.ease.cubic)
                })
            });

            // setInterval(function() {
            //     axisDataItem.animate({
            //         key: "value",
            //         // to: Math.round(Math.random() * 140 - 40),
            //         to: Math.round(63.5),
            //         duration: 500,
            //         easing: am5.ease.out(am5.ease.cubic)
            //     });
            // }, 2000)

            chart.bulletsContainer.set("mask", undefined);


            // Create axis ranges bands
            // https://www.amcharts.com/docs/v5/charts/radar-chart/gauge-charts/#Bands
            var bandsData = [{
                // title: "Foundational",
                color: "#FF0000",
                lowScore: 0,
                highScore: 20
            }, {
                // title: "Developing",
                // color: "#f3eb0c",
                color: "#F7E200",
                lowScore: 20,
                highScore: 40
            }, {
                // title: "Maturing",
                color: "#f3esds",
                lowScore: 40,
                highScore: 60
            }, {
                // title: "Sustainable",
                color: "#54b947",
                lowScore: 60,
                highScore: 80
            }, {
                // title: "High Perform",
                color: "#19461A",
                lowScore: 80,
                highScore: 100
            }];

            am5.array.each(bandsData, function(data) {
                var axisRange = xAxis.createAxisRange(xAxis.makeDataItem({}));

                axisRange.setAll({
                    value: data.lowScore,
                    endValue: data.highScore
                });

                axisRange.get("axisFill").setAll({
                    visible: true,
                    fill: am5.color(data.color),
                    fillOpacity: 0.8
                });

                axisRange.get("label").setAll({
                    text: data.title,
                    inside: true,
                    radius: 15,
                    fontSize: "0.9em",
                    fill: root.interfaceColors.get("background")
                });
            });


            // Make stuff animate on load
            chart.appear(1000, 100);

        }); // end am5.ready()
    }
</script>

<script>
    function q3Ach() {
        am5.ready(function() {

            // Create root element
            // https://www.amcharts.com/docs/v5/getting-started/#Root_element
            var root = am5.Root.new("q3AchievePerChartdiv");


            // Set themes
            // https://www.amcharts.com/docs/v5/concepts/themes/
            root.setThemes([
                am5themes_Animated.new(root)
            ]);


            // Create chart
            // https://www.amcharts.com/docs/v5/charts/radar-chart/
            var chart = root.container.children.push(am5radar.RadarChart.new(root, {
                panX: false,
                panY: false,
                startAngle: 160,
                endAngle: 380
            }));


            // Create axis and its renderer
            // https://www.amcharts.com/docs/v5/charts/radar-chart/gauge-charts/#Axes
            var axisRenderer = am5radar.AxisRendererCircular.new(root, {
                innerRadius: -20
            });

            axisRenderer.grid.template.setAll({
                stroke: root.interfaceColors.get("background"),
                visible: true,
                strokeOpacity: 0.8
            });

            var xAxis = chart.xAxes.push(am5xy.ValueAxis.new(root, {
                maxDeviation: 0,
                min: 0,
                max: 100,
                strictMinMax: true,
                renderer: axisRenderer
            }));


            // Add clock hand
            // https://www.amcharts.com/docs/v5/charts/radar-chart/gauge-charts/#Clock_hands
            var axisDataItem = xAxis.makeDataItem({});

            var clockHand = am5radar.ClockHand.new(root, {
                pinRadius: am5.percent(20),
                radius: am5.percent(100),
                bottomWidth: 40
            })

            var bullet = axisDataItem.set("bullet", am5xy.AxisBullet.new(root, {
                sprite: clockHand
            }));

            xAxis.createAxisRange(axisDataItem);

            var label = chart.radarContainer.children.push(am5.Label.new(root, {
                fill: am5.color(0xffffff),
                centerX: am5.percent(50),
                textAlign: "center",
                centerY: am5.percent(50),
                fontSize: "3em"
            }));

            axisDataItem.set("value", q3AchievementPer);
            bullet.get("sprite").on("rotation", function() {
                var value = axisDataItem.get("value");
                var text = Math.round(axisDataItem.get("value")).toString();
                var fill = am5.color(0x000000);
                xAxis.axisRanges.each(function(axisRange) {
                    if (value >= axisRange.get("value") && value <= axisRange.get("endValue")) {
                        fill = axisRange.get("axisFill").get("fill");
                    }
                })

                label.set("text", Math.round(value).toString());

                clockHand.pin.animate({
                    key: "fill",
                    to: fill,
                    duration: 500,
                    easing: am5.ease.out(am5.ease.cubic)
                })
                clockHand.hand.animate({
                    key: "fill",
                    to: fill,
                    duration: 500,
                    easing: am5.ease.out(am5.ease.cubic)
                })
            });

            // setInterval(function() {
            //     axisDataItem.animate({
            //         key: "value",
            //         // to: Math.round(Math.random() * 140 - 40),
            //         to: Math.round(63.5),
            //         duration: 500,
            //         easing: am5.ease.out(am5.ease.cubic)
            //     });
            // }, 2000)

            chart.bulletsContainer.set("mask", undefined);


            // Create axis ranges bands
            // https://www.amcharts.com/docs/v5/charts/radar-chart/gauge-charts/#Bands
            var bandsData = [{
                // title: "Foundational",
                color: "#FF0000",
                lowScore: 0,
                highScore: 20
            }, {
                // title: "Developing",
                // color: "#f3eb0c",
                color: "#F7E200",
                lowScore: 20,
                highScore: 40
            }, {
                // title: "Maturing",
                color: "#f3esds",
                lowScore: 40,
                highScore: 60
            }, {
                // title: "Sustainable",
                color: "#54b947",
                lowScore: 60,
                highScore: 80
            }, {
                // title: "High Perform",
                color: "#19461A",
                lowScore: 80,
                highScore: 100
            }];

            am5.array.each(bandsData, function(data) {
                var axisRange = xAxis.createAxisRange(xAxis.makeDataItem({}));

                axisRange.setAll({
                    value: data.lowScore,
                    endValue: data.highScore
                });

                axisRange.get("axisFill").setAll({
                    visible: true,
                    fill: am5.color(data.color),
                    fillOpacity: 0.8
                });

                axisRange.get("label").setAll({
                    text: data.title,
                    inside: true,
                    radius: 15,
                    fontSize: "0.9em",
                    fill: root.interfaceColors.get("background")
                });
            });


            // Make stuff animate on load
            chart.appear(1000, 100);

        }); // end am5.ready()
    }
</script>

<script>
    function q4Ach() {
        am5.ready(function() {

            // Create root element
            // https://www.amcharts.com/docs/v5/getting-started/#Root_element
            var root = am5.Root.new("q4AchievePerChartdiv");


            // Set themes
            // https://www.amcharts.com/docs/v5/concepts/themes/
            root.setThemes([
                am5themes_Animated.new(root)
            ]);


            // Create chart
            // https://www.amcharts.com/docs/v5/charts/radar-chart/
            var chart = root.container.children.push(am5radar.RadarChart.new(root, {
                panX: false,
                panY: false,
                startAngle: 160,
                endAngle: 380
            }));


            // Create axis and its renderer
            // https://www.amcharts.com/docs/v5/charts/radar-chart/gauge-charts/#Axes
            var axisRenderer = am5radar.AxisRendererCircular.new(root, {
                innerRadius: -20
            });

            axisRenderer.grid.template.setAll({
                stroke: root.interfaceColors.get("background"),
                visible: true,
                strokeOpacity: 0.8
            });

            var xAxis = chart.xAxes.push(am5xy.ValueAxis.new(root, {
                maxDeviation: 0,
                min: 0,
                max: 100,
                strictMinMax: true,
                renderer: axisRenderer
            }));


            // Add clock hand
            // https://www.amcharts.com/docs/v5/charts/radar-chart/gauge-charts/#Clock_hands
            var axisDataItem = xAxis.makeDataItem({});

            var clockHand = am5radar.ClockHand.new(root, {
                pinRadius: am5.percent(20),
                radius: am5.percent(100),
                bottomWidth: 40
            })

            var bullet = axisDataItem.set("bullet", am5xy.AxisBullet.new(root, {
                sprite: clockHand
            }));

            xAxis.createAxisRange(axisDataItem);

            var label = chart.radarContainer.children.push(am5.Label.new(root, {
                fill: am5.color(0xffffff),
                centerX: am5.percent(50),
                textAlign: "center",
                centerY: am5.percent(50),
                fontSize: "3em"
            }));

            axisDataItem.set("value", q4AchievementPer);
            bullet.get("sprite").on("rotation", function() {
                var value = axisDataItem.get("value");
                var text = Math.round(axisDataItem.get("value")).toString();
                var fill = am5.color(0x000000);
                xAxis.axisRanges.each(function(axisRange) {
                    if (value >= axisRange.get("value") && value <= axisRange.get("endValue")) {
                        fill = axisRange.get("axisFill").get("fill");
                    }
                })

                label.set("text", Math.round(value).toString());

                clockHand.pin.animate({
                    key: "fill",
                    to: fill,
                    duration: 500,
                    easing: am5.ease.out(am5.ease.cubic)
                })
                clockHand.hand.animate({
                    key: "fill",
                    to: fill,
                    duration: 500,
                    easing: am5.ease.out(am5.ease.cubic)
                })
            });

            // setInterval(function() {
            //     axisDataItem.animate({
            //         key: "value",
            //         // to: Math.round(Math.random() * 140 - 40),
            //         to: Math.round(63.5),
            //         duration: 500,
            //         easing: am5.ease.out(am5.ease.cubic)
            //     });
            // }, 2000)

            chart.bulletsContainer.set("mask", undefined);


            // Create axis ranges bands
            // https://www.amcharts.com/docs/v5/charts/radar-chart/gauge-charts/#Bands
            var bandsData = [{
                // title: "Foundational",
                color: "#FF0000",
                lowScore: 0,
                highScore: 20
            }, {
                // title: "Developing",
                // color: "#f3eb0c",
                color: "#F7E200",
                lowScore: 20,
                highScore: 40
            }, {
                // title: "Maturing",
                color: "#f3esds",
                lowScore: 40,
                highScore: 60
            }, {
                // title: "Sustainable",
                color: "#54b947",
                lowScore: 60,
                highScore: 80
            }, {
                // title: "High Perform",
                color: "#19461A",
                lowScore: 80,
                highScore: 100
            }];

            am5.array.each(bandsData, function(data) {
                var axisRange = xAxis.createAxisRange(xAxis.makeDataItem({}));

                axisRange.setAll({
                    value: data.lowScore,
                    endValue: data.highScore
                });

                axisRange.get("axisFill").setAll({
                    visible: true,
                    fill: am5.color(data.color),
                    fillOpacity: 0.8
                });

                axisRange.get("label").setAll({
                    text: data.title,
                    inside: true,
                    radius: 15,
                    fontSize: "0.9em",
                    fill: root.interfaceColors.get("background")
                });
            });


            // Make stuff animate on load
            chart.appear(1000, 100);

        }); // end am5.ready()
    }
</script>

<script>
    function topSoldProducts() {
        am5.ready(function() {

            // Create root element
            // https://www.amcharts.com/docs/v5/getting-started/#Root_element
            var root = am5.Root.new("topSoldProduct");

            // Set themes
            // https://www.amcharts.com/docs/v5/concepts/themes/
            root.setThemes([
                am5themes_Animated.new(root)
            ]);

            // Create chart
            // https://www.amcharts.com/docs/v5/charts/xy-chart/
            var chart = root.container.children.push(am5xy.XYChart.new(root, {
                panX: true,
                panY: true,
                wheelX: "panX",
                wheelY: "zoomX",
                pinchZoomX: true,
                paddingLeft: 0,
                paddingRight: 1
            }));

            // Add cursor
            // https://www.amcharts.com/docs/v5/charts/xy-chart/cursor/
            var cursor = chart.set("cursor", am5xy.XYCursor.new(root, {}));
            cursor.lineY.set("visible", false);


            // Create axes
            // https://www.amcharts.com/docs/v5/charts/xy-chart/axes/
            var xRenderer = am5xy.AxisRendererX.new(root, {
                minGridDistance: 30,
                minorGridEnabled: true
            });

            xRenderer.labels.template.setAll({
                rotation: -90,
                centerY: am5.p50,
                centerX: am5.p100,
                paddingRight: 15
            });

            xRenderer.grid.template.setAll({
                location: 1
            })

            var xAxis = chart.xAxes.push(am5xy.CategoryAxis.new(root, {
                maxDeviation: 0.3,
                categoryField: "country",
                renderer: xRenderer,
                tooltip: am5.Tooltip.new(root, {})
            }));

            var yRenderer = am5xy.AxisRendererY.new(root, {
                strokeOpacity: 0.1,
                minGridDistance: 10
            })

            var yAxis = chart.yAxes.push(am5xy.ValueAxis.new(root, {
                maxDeviation: 0.3,
                renderer: yRenderer
            }));

            // Create series
            // https://www.amcharts.com/docs/v5/charts/xy-chart/series/
            var series = chart.series.push(am5xy.ColumnSeries.new(root, {
                name: "Series 1",
                xAxis: xAxis,
                yAxis: yAxis,
                valueYField: "value",
                sequencedInterpolation: true,
                categoryXField: "country",
                tooltip: am5.Tooltip.new(root, {
                    labelText: "{valueY}"
                })
            }));

            series.columns.template.setAll({
                cornerRadiusTL: 5,
                cornerRadiusTR: 5,
                strokeOpacity: 0
            });
            series.columns.template.adapters.add("fill", function(fill, target) {
                return chart.get("colors").getIndex(series.columns.indexOf(target));
            });

            series.columns.template.adapters.add("stroke", function(stroke, target) {
                return chart.get("colors").getIndex(series.columns.indexOf(target));
            });


            // Set data
            var data = [];
            topSoldProduct.forEach(element => {
                let chartValue = {
                    "country": element.productName,
                    "value": element.totalSoldQty
                };
                data.push(chartValue);
            });


            xAxis.data.setAll(data);
            series.data.setAll(data);


            // Make stuff animate on load
            // https://www.amcharts.com/docs/v5/concepts/animations/
            series.appear(1000);
            chart.appear(1000, 100);

        }); // end am5.ready()
    }
</script>

<script>
    function topSoldBrands() {
        am5.ready(function() {

            // Create root element
            // https://www.amcharts.com/docs/v5/getting-started/#Root_element
            var root = am5.Root.new("topSoldBrand");


            // Set themes
            // https://www.amcharts.com/docs/v5/concepts/themes/
            root.setThemes([
                am5themes_Animated.new(root)
            ]);


            // Create chart
            // https://www.amcharts.com/docs/v5/charts/xy-chart/
            var chart = root.container.children.push(am5xy.XYChart.new(root, {
                panX: false,
                panY: false,
                wheelX: "none",
                wheelY: "none",
                paddingLeft: 0
            }));

            // We don't want zoom-out button to appear while animating, so we hide it
            chart.zoomOutButton.set("forceHidden", true);


            // Create axes
            // https://www.amcharts.com/docs/v5/charts/xy-chart/axes/
            var yRenderer = am5xy.AxisRendererY.new(root, {
                minGridDistance: 30,
                minorGridEnabled: true
            });

            yRenderer.grid.template.set("location", 1);

            var yAxis = chart.yAxes.push(am5xy.CategoryAxis.new(root, {
                maxDeviation: 0,
                categoryField: "network",
                renderer: yRenderer,
                tooltip: am5.Tooltip.new(root, {
                    themeTags: ["axis"]
                })
            }));

            var xAxis = chart.xAxes.push(am5xy.ValueAxis.new(root, {
                maxDeviation: 0,
                min: 0,
                numberFormatter: am5.NumberFormatter.new(root, {
                    "numberFormat": "#,###a"
                }),
                extraMax: 0.1,
                renderer: am5xy.AxisRendererX.new(root, {
                    strokeOpacity: 0.1,
                    minGridDistance: 80

                })
            }));


            // Add series
            // https://www.amcharts.com/docs/v5/charts/xy-chart/series/
            var series = chart.series.push(am5xy.ColumnSeries.new(root, {
                name: "Series 1",
                xAxis: xAxis,
                yAxis: yAxis,
                valueXField: "value",
                categoryYField: "network",
                tooltip: am5.Tooltip.new(root, {
                    pointerOrientation: "left",
                    labelText: "{valueX}"
                })
            }));


            // Rounded corners for columns
            series.columns.template.setAll({
                cornerRadiusTR: 5,
                cornerRadiusBR: 5,
                strokeOpacity: 0
            });

            // Make each column to be of a different color
            series.columns.template.adapters.add("fill", function(fill, target) {
                return chart.get("colors").getIndex(series.columns.indexOf(target));
            });

            series.columns.template.adapters.add("stroke", function(stroke, target) {
                return chart.get("colors").getIndex(series.columns.indexOf(target));
            });


            // Set data
            var data = [];
            topSoldBrand.forEach(element => {
                let chartValue = {
                    "network": element.brand_name,
                    "value": element.totalSoldQty
                };
                data.push(chartValue);
            });



            yAxis.data.setAll(data);
            series.data.setAll(data);
            sortCategoryAxis();

            // Get series item by category
            function getSeriesItem(category) {
                for (var i = 0; i < series.dataItems.length; i++) {
                    var dataItem = series.dataItems[i];
                    if (dataItem.get("categoryY") == category) {
                        return dataItem;
                    }
                }
            }

            chart.set("cursor", am5xy.XYCursor.new(root, {
                behavior: "none",
                xAxis: xAxis,
                yAxis: yAxis
            }));


            // Axis sorting
            function sortCategoryAxis() {

                // Sort by value
                series.dataItems.sort(function(x, y) {
                    return x.get("valueX") - y.get("valueX"); // descending
                    //return y.get("valueY") - x.get("valueX"); // ascending
                })

                // Go through each axis item
                am5.array.each(yAxis.dataItems, function(dataItem) {
                    // get corresponding series item
                    var seriesDataItem = getSeriesItem(dataItem.get("category"));

                    if (seriesDataItem) {
                        // get index of series data item
                        var index = series.dataItems.indexOf(seriesDataItem);
                        // calculate delta position
                        var deltaPosition = (index - dataItem.get("index", 0)) / series.dataItems
                            .length;
                        // set index to be the same as series data item index
                        dataItem.set("index", index);
                        // set deltaPosition instanlty
                        dataItem.set("deltaPosition", -deltaPosition);
                        // animate delta position to 0
                        dataItem.animate({
                            key: "deltaPosition",
                            to: 0,
                            duration: 1000,
                            easing: am5.ease.out(am5.ease.cubic)
                        })
                    }
                });

                // Sort axis items by index.
                // This changes the order instantly, but as deltaPosition is set,
                // they keep in the same places and then animate to true positions.
                yAxis.dataItems.sort(function(x, y) {
                    return x.get("index") - y.get("index");
                });
            }

            // Make stuff animate on load
            // https://www.amcharts.com/docs/v5/concepts/animations/
            series.appear(1000);
            chart.appear(1000, 100);

        }); // end am5.ready()
    }
</script>

<script>
    function totalOutstandings() {
        am5.ready(function() {

            // Create root element
            // https://www.amcharts.com/docs/v5/getting-started/#Root_element
            var root = am5.Root.new("totalOutstanding");


            // Set themes
            // https://www.amcharts.com/docs/v5/concepts/themes/
            root.setThemes([
                am5themes_Animated.new(root)
            ]);


            // Create chart
            // https://www.amcharts.com/docs/v5/charts/xy-chart/
            var chart = root.container.children.push(am5xy.XYChart.new(root, {
                panX: false,
                panY: false,
                wheelX: "panX",
                wheelY: "zoomX",
                paddingLeft: 0,
                layout: root.verticalLayout
            }));


            // Add legend
            // https://www.amcharts.com/docs/v5/charts/xy-chart/legend-xy-series/
            var legend = chart.children.push(am5.Legend.new(root, {
                centerX: am5.p50,
                x: am5.p50
            }))

            // Data
            var data = [{
                year: "Total",
                income: grandTotalOutstanding,
                // expenses: 18.1
            }, {
                year: "Within 30",
                // income: 26.2,
                expenses: grandTotaldueWithin30,
            }, {
                year: "31 To 60",
                // income: 30.1,
                expenses: grandTotaldueWithin31_60,
            }, {
                year: "61 To 90",
                // income: 29.5,
                expenses: grandTotaldueWithin61_90,
            }, {
                year: "91 To 180",
                // income: 24.6,
                expenses: grandTotaldueWithin91_180,
            }, {
                year: "180 Plus",
                // income: 24.6,
                expenses: grandTotaldueWithin180plus,
            }, {
                year: "365 Plus",
                // income: 24.6,
                expenses: grandTotaldueWithin365plus,
            }];


            // Create axes
            // https://www.amcharts.com/docs/v5/charts/xy-chart/axes/
            var yAxis = chart.yAxes.push(am5xy.CategoryAxis.new(root, {
                categoryField: "year",
                renderer: am5xy.AxisRendererY.new(root, {
                    inversed: true,
                    cellStartLocation: 0.1,
                    cellEndLocation: 0.9,
                    minorGridEnabled: true
                })
            }));

            yAxis.data.setAll(data);

            var xAxis = chart.xAxes.push(am5xy.ValueAxis.new(root, {
                renderer: am5xy.AxisRendererX.new(root, {
                    strokeOpacity: 0.1,
                    minGridDistance: 200
                }),
                min: 0
            }));


            // Add series
            // https://www.amcharts.com/docs/v5/charts/xy-chart/series/
            function createSeries(field, name) {
                var series = chart.series.push(am5xy.ColumnSeries.new(root, {
                    name: name,
                    xAxis: xAxis,
                    yAxis: yAxis,
                    valueXField: field,
                    categoryYField: "year",
                    sequencedInterpolation: true,
                    tooltip: am5.Tooltip.new(root, {
                        pointerOrientation: "horizontal",
                        labelText: "[bold]{name}[/]\n{categoryY}: {valueX}"
                    })
                }));

                series.columns.template.setAll({
                    height: am5.p100,
                    strokeOpacity: 1
                });


                series.bullets.push(function() {
                    return am5.Bullet.new(root, {
                        locationX: 1,
                        locationY: 0.5,
                        sprite: am5.Label.new(root, {
                            centerY: am5.p50,
                            text: "{valueX}",
                            populateText: true
                        })
                    });
                });

                series.bullets.push(function() {
                    return am5.Bullet.new(root, {
                        locationX: 1,
                        locationY: 0.5,
                        sprite: am5.Label.new(root, {
                            centerX: am5.p100,
                            centerY: am5.p50,
                            text: "{name}",
                            fill: am5.color(0xffffff),
                            populateText: true
                        })
                    });
                });

                series.data.setAll(data);
                series.appear();

                return series;
            }

            createSeries("income", "Total Net Due");
            createSeries("expenses", "Due");


            // Add legend
            // https://www.amcharts.com/docs/v5/charts/xy-chart/legend-xy-series/
            var legend = chart.children.push(am5.Legend.new(root, {
                centerX: am5.p50,
                x: am5.p50
            }));

            legend.data.setAll(chart.series.values);


            // Add cursor
            // https://www.amcharts.com/docs/v5/charts/xy-chart/cursor/
            var cursor = chart.set("cursor", am5xy.XYCursor.new(root, {
                behavior: "zoomY"
            }));
            cursor.lineY.set("forceHidden", true);
            cursor.lineX.set("forceHidden", true);


            // Make stuff animate on load
            // https://www.amcharts.com/docs/v5/concepts/animations/
            chart.appear(1000, 100);

        }); // end am5.ready()
    }
</script>

<script>
    function top5SalesPersons() {
        am5.ready(function() {

            // Create root element
            // https://www.amcharts.com/docs/v5/getting-started/#Root_element
            var root = am5.Root.new("top5SalesPersonsCQ");


            // Set themes
            // https://www.amcharts.com/docs/v5/concepts/themes/
            root.setThemes([
                am5themes_Animated.new(root)
            ]);


            // Create chart
            // https://www.amcharts.com/docs/v5/charts/xy-chart/
            var chart = root.container.children.push(am5xy.XYChart.new(root, {
                panX: false,
                panY: false,
                wheelX: "none",
                wheelY: "none",
                paddingLeft: 0
            }));

            // We don't want zoom-out button to appear while animating, so we hide it
            chart.zoomOutButton.set("forceHidden", true);


            // Create axes
            // https://www.amcharts.com/docs/v5/charts/xy-chart/axes/
            var yRenderer = am5xy.AxisRendererY.new(root, {
                minGridDistance: 30,
                minorGridEnabled: true
            });

            yRenderer.grid.template.set("location", 1);

            var yAxis = chart.yAxes.push(am5xy.CategoryAxis.new(root, {
                maxDeviation: 0,
                categoryField: "network",
                renderer: yRenderer,
                tooltip: am5.Tooltip.new(root, {
                    themeTags: ["axis"]
                })
            }));

            var xAxis = chart.xAxes.push(am5xy.ValueAxis.new(root, {
                maxDeviation: 0,
                min: 0,
                numberFormatter: am5.NumberFormatter.new(root, {
                    "numberFormat": "#,###a"
                }),
                extraMax: 0.1,
                renderer: am5xy.AxisRendererX.new(root, {
                    strokeOpacity: 0.1,
                    minGridDistance: 100

                })
            }));


            // Add series
            // https://www.amcharts.com/docs/v5/charts/xy-chart/series/
            var series = chart.series.push(am5xy.ColumnSeries.new(root, {
                name: "Series 1",
                xAxis: xAxis,
                yAxis: yAxis,
                valueXField: "value",
                categoryYField: "network",
                tooltip: am5.Tooltip.new(root, {
                    pointerOrientation: "left",
                    labelText: "{valueX}" + "%"
                })
            }));


            // Rounded corners for columns
            series.columns.template.setAll({
                cornerRadiusTR: 5,
                cornerRadiusBR: 5,
                strokeOpacity: 0
            });

            // Make each column to be of a different color
            series.columns.template.adapters.add("fill", function(fill, target) {
                return chart.get("colors").getIndex(series.columns.indexOf(target));
            });

            series.columns.template.adapters.add("stroke", function(stroke, target) {
                return chart.get("colors").getIndex(series.columns.indexOf(target));
            });


            // Set data
            let top5SalesPersonsCQ = top5SalesPersonGraph;
            var data = [];
            top5SalesPersonsCQ.forEach(element => {
                let per = Number(element.per.toFixed(2));
                let chartValue = {
                    "network": element.name,
                    "value": per
                };
                data.push(chartValue);
            });



            yAxis.data.setAll(data);
            series.data.setAll(data);
            sortCategoryAxis();

            // Get series item by category
            function getSeriesItem(category) {
                for (var i = 0; i < series.dataItems.length; i++) {
                    var dataItem = series.dataItems[i];
                    if (dataItem.get("categoryY") == category) {
                        return dataItem;
                    }
                }
            }

            chart.set("cursor", am5xy.XYCursor.new(root, {
                behavior: "none",
                xAxis: xAxis,
                yAxis: yAxis
            }));


            // Axis sorting
            function sortCategoryAxis() {

                // Sort by value
                series.dataItems.sort(function(x, y) {
                    return x.get("valueX") - y.get("valueX"); // descending
                    //return y.get("valueY") - x.get("valueX"); // ascending
                })

                // Go through each axis item
                am5.array.each(yAxis.dataItems, function(dataItem) {
                    // get corresponding series item
                    var seriesDataItem = getSeriesItem(dataItem.get("category"));

                    if (seriesDataItem) {
                        // get index of series data item
                        var index = series.dataItems.indexOf(seriesDataItem);
                        // calculate delta position
                        var deltaPosition = (index - dataItem.get("index", 0)) / series.dataItems
                            .length;
                        // set index to be the same as series data item index
                        dataItem.set("index", index);
                        // set deltaPosition instanlty
                        dataItem.set("deltaPosition", -deltaPosition);
                        // animate delta position to 0
                        dataItem.animate({
                            key: "deltaPosition",
                            to: 0,
                            duration: 1000,
                            easing: am5.ease.out(am5.ease.cubic)
                        })
                    }
                });

                // Sort axis items by index.
                // This changes the order instantly, but as deltaPosition is set,
                // they keep in the same places and then animate to true positions.
                yAxis.dataItems.sort(function(x, y) {
                    return x.get("index") - y.get("index");
                });
            }

            // Make stuff animate on load
            // https://www.amcharts.com/docs/v5/concepts/animations/
            series.appear(1000);
            chart.appear(1000, 100);

        }); // end am5.ready()
    }
</script>
