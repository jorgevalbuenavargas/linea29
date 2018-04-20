"use strict"

function stopApp(){

    let state = {
        branch : {},
        newStop : {},
        editedStop: {},
        deletedStop : ""
    }

    var s = new URLSearchParams(window.location.search.substring(1));
    const branchId = s.get("branch_id");

    $(document).ready(function(){
        $('[data-toggle="tooltip"]').tooltip();   
    });

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
        map = new google.maps.Map(document.getElementById('map'), mapOptions);
        directionsDisplay.setMap(map);
    }
    

    function calcRoute(stops) {

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
                    suppressMarkers: true
                });
            } else {
                console.error(response);
            }
        });
    }
   
    //Google Maps Api End //


    function createStop(nStop){
        nStop.branch_id = state.branch.id;
        axios.post("/stop", nStop)
            .then((resp)=>{
                console.log(resp.data);
                updatePage();    
                cleanInputs(); 
                $("#closeButton_Modal").click();
                $("#stopsList").notify(
                    resp.data,
                    { position:"top center", className:"success" }
                ); 
                state.newStop = {};                                        
                })
            .catch((err)=>{
                console.error(err.response.data.message); 
                $("#saveButton_Modal").notify(
                    "Ningún campo puede quedar vacío",
                    { position:"left", className:"error" }
                )
            })
    }
    
    function cleanInputs(){
        $("#stopsName").value = null;
        $("#stopsNumber").value = null;
        $("#stopsLatitude").value = null;
        $("#stopsLongitude").value = null;
        state.newStop = {}; 
    }
    
    function asignIdToDelete(id){
        state.deletedStop = id
    }

    function fillEditionForm(idStop){
        axios.get("/stop/" + idStop)
            .then((resp)=>{
                state.editedStop = resp.data;
            })                        
            .catch((err)=>
                console.error(err.response.data)
            )
    }
    
    function updateStop(id, editedStop){       
        axios.put("/stop/" + id, editedStop)
            .then((resp)=>{
                console.log(resp.data);
                updatePage();
                $("#closeButton_editModal").click();
                $("#stopsList").notify(
                    resp.data,
                    { position:"top center", className:"success" }
                );
                state.editedBranch = {}
            })                        
            .catch((err)=>{
                console.error(err.response.data.message); 
                $("#saveButton_editModal").notify(
                    "Ningún campo puede quedar vacío",
                    { position:"left", className:"error" }
                )
            })       
    }
    
    function deleteStop(id){       
        axios.delete("/stop/" + id)
            .then((resp)=>{
                console.log(resp.data);
                updatePage();
                $("#stopsList").notify(
                    resp.data,
                    { position:"top center", className:"success" }
                ); 
            })                        
            .catch((err)=>{
                console.error(err.response.data.message); 
                $("#stopsList").notify(
                    err.response.data.message,
                    { position:"left", className:"error" }
                )
            })       
    }

    function updatePage(){
        axios.get("branch/" + branchId)
            .then(resp => {
                state.branch = resp.data
                initMap()
                calcRoute(state.branch.stops)
            })
            .catch((err)=>
            console.error(err.response.data)
        ) 
    }
    
    const app = new Vue({
        data: state,
        el: "#app-stops",
        methods : {
            updatePage : updatePage,
            createStop : createStop,
            cleanInputs : cleanInputs,
            asignIdToDelete : asignIdToDelete,
            fillEditionForm : fillEditionForm,
            updateStop : updateStop,
            deleteStop : deleteStop
        }
    })

    updatePage()
}

window.addEventListener("load", function(){
    stopApp()
})