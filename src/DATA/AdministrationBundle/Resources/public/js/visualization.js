$(document).ready(function() {
    $.ajax({
        dataType: "json",
        url: Routing.generate('data_administration_visualization_get'),
        success: function(data){
            console.log(data);

            //ARTWORK
            var labels = [];
            for(var fieldNb in data['artwork']) {
                labels.push(data['artwork'][fieldNb].field);
            }

            var values = [];
            for(var fieldNb2 in data['artwork']) {
                values.push(data['artwork'][fieldNb2].current);
            }

            var dataArtwork = {
                labels: labels,
                datasets: [
                    {
                        label: "Artwork",
                        backgroundColor: "rgba(255,99,132,0.2)",
                        borderColor: "rgba(255,99,132,1)",
                        borderWidth: 1,
                        hoverBackgroundColor: "rgba(255,99,132,0.4)",
                        hoverBorderColor: "rgba(255,99,132,1)",
                        data: values,
                    }
                ]
            };

            var myBarChart = new Chart($('#artwork'), {
                type: 'horizontalBar',
                data: dataArtwork
            });

            //Building
            var labelsBuilding = [];
            for(var fieldNbBuilding in data['building']) {
                labelsBuilding.push(data['building'][fieldNbBuilding].field);
            }

            var valuesBuilding = [];
            for(var fieldNb2Building in data['building']) {
                valuesBuilding.push(data['building'][fieldNb2Building].current);
            }

            var dataBuilding = {
                labels: labelsBuilding,
                datasets: [
                    {
                        label: "Building",
                        backgroundColor: "rgba(255,99,132,0.2)",
                        borderColor: "rgba(255,99,132,1)",
                        borderWidth: 1,
                        hoverBackgroundColor: "rgba(255,99,132,0.4)",
                        hoverBorderColor: "rgba(255,99,132,1)",
                        data: valuesBuilding,
                    }
                ]
            };

            console.log(dataBuilding);
            var myBarChartBuilding = new Chart($('#building'), {
                type: 'horizontalBar',
                data: dataBuilding
            });
        },
        error: function(error){
            console.log(dump(error));
        }
    });
});

