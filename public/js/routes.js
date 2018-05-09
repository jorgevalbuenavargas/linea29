"use strict"

function routeApp(){

    let state = {
        branchs : [],
        branch : {},
        branch_id: ""
    }

    // Google Maps Begining //

    let directionsDisplay;
    let directionsService = new google.maps.DirectionsService();
    let map;   
    let routesDisplays = [];
    
    setTimeout( ()=>{
        directionsDisplay = new google.maps.DirectionsRenderer();
        let bsas = {lat: -34.6037, lng: -58.3816};
        let mapOptions = {
          zoom:12,
          center: bsas
        }
        map = new google.maps.Map(document.getElementById('map_inRoutes'), mapOptions);
        directionsDisplay.setMap(map);
    } , 100)

    function setGoogleMapsDisplays(){
        for (let branch of state.branchs){
            routesDisplays[branch.id] = new google.maps.DirectionsRenderer();
            axios.get("busRoutes/" + branch.id)
                .then(resp => {

                    branch = resp.data
                    
                    const points = branch.stops.map( s => ({lat:s.latitude,lng:s.longitude, id : s.number}))
                    const waypoints =  points.slice(1, -1).map( p => ({ location : p , stopover : false}))

                    let request = {
                        origin: points[0],
                        destination: points[points.length - 1],
                        waypoints: waypoints,
                        optimizeWaypoints: true,   
                        travelMode: 'DRIVING'
                    };
                    
                    let color = colorById(branch.id)

                    directionsService.route(request, function(result, status) {
                        if (status == 'OK') {
                            routesDisplays[branch.id].setMap(map);
                            routesDisplays[branch.id].setDirections(result);
                            routesDisplays[branch.id].setOptions({
                                suppressMarkers: true,
                                polylineOptions : {
                                    strokeColor : color,
                                    visible: true
                                }
                            })
                        } else {
                            console.error(response);
                        }
                    })                    
                })
                .catch((err)=>
                console.error(err.response.data)
            )
        }
    }

    //Google Maps Api End //

    function colorById(id){
        return "hsl(" + ( 100 + id * 137.5077640) % 360 + " , 100%,50%)";
    }

    function updateMap(){

        let checkedBranches = document.getElementsByClassName("checkedBranches") 
        for (let checkedBranch of checkedBranches){            
            if(!checkedBranch.checked){
                routesDisplays[checkedBranch.value].setMap(null);
            }else {
                routesDisplays[checkedBranch.value].setMap(map);
            }
        }
    }

    function updateBranchsList(){
        axios.get("/busRoutes")
            .then((resp)=>{
                state.branchs = resp.data
                setGoogleMapsDisplays()
            })
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
    
}

window.addEventListener("load", function(){
    routeApp()
})