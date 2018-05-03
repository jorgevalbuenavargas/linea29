"use strict"

function routeApp(){

    let state = {
        branchs : [],
        branch : {},
        branch_id: ""
    }

    // Google Maps Begining //

    var directionsDisplay;
    var directionsService = new google.maps.DirectionsService();
    var map;   
    
    function initMap() {
        directionsDisplay = new google.maps.DirectionsRenderer();
        var bsas = {lat: -34.6037, lng: -58.3816};
        var mapOptions = {
          zoom:12,
          center: bsas
        }
        map = new google.maps.Map(document.getElementById('map_inRoutes'), mapOptions);
        directionsDisplay.setMap(map);
    }
    

    function calcRoute(stops,color) {

        const points = stops.map( s => ({lat:s.latitude,lng:s.longitude, id : s.number}))

        const waypoints =  points.slice(1, -1).map( p => ({ location : p , stopover : false}))

        var request = {
            origin: points[0],
            destination: points[points.length - 1],
            waypoints: waypoints,
            optimizeWaypoints: true,
            travelMode: 'DRIVING'
          };
          directionsService.route(request, function(result, status) {
            if (status == 'OK') {
                directionsDisplay.setDirections(result);
                directionsDisplay.setOptions({
                    suppressMarkers: true,
                    polylineOptions : {
                        strokeColor : color,
                        visible: false
                    }
                });
            } else {
                console.error(response);
            }
        });
    }

    //Google Maps Api End //

    function colorById(id){
        return "hsl(" + (id * 205) % 360 + " , 100%,50%)";
    }

    function updateMap(){
        let branchId = document.getElementById("listOfBranchs").value;
        if (branchId == ""){
            branchId = 1
        }
        axios.get("busRoutes/" + branchId)
            .then(resp => {
                state.branch = resp.data
                initMap()
                calcRoute(state.branch.stops,colorById(branchId))
            })
            .catch((err)=>
            console.error(err.response.data)
        )        
    }

    function updateBranchsList(){
        axios.get("/busRoutes")
            .then((resp)=>state.branchs = resp.data)
            .catch((err)=>
                console.error(err.response.data)
            )
    }
    

    const app = new Vue({
        data: state,
        el: "#app-routes",
        methods : {
            updateMap : updateMap,
            updateBranchsList : updateBranchsList
        }
    })

    updateBranchsList()
    updateMap()
}

window.addEventListener("load", function(){
    routeApp()
})