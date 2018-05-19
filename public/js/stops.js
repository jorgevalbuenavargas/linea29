"use strict"

function stopApp(){

    let state = {
        branch : {},
        newStop : {},
        editedStop: {},
        deletedStop : ""
    }

    let s = new URLSearchParams(window.location.search.substring(1));
    const branchId = s.get("branch_id");

    $(document).ready(function(){
        $('[data-toggle="tooltip"]').tooltip();   
    });

    // Google Maps Begining //

    let directionsDisplay;
    let directionsService = new google.maps.DirectionsService();
    let map;
    let markers = [];   
    
    setTimeout( ()=>{
        directionsDisplay = new google.maps.DirectionsRenderer();
        let bsas = {lat: -34.6037, lng: -58.3816};
        let mapOptions = {
          zoom:12,
          center: bsas
        }
        map = new google.maps.Map(document.getElementById('map'), mapOptions);
        directionsDisplay.setMap(map);
    } , 100)

    function setMapInNewStopModal(){
        let directionsDisplay;
        let directionsService = new google.maps.DirectionsService();
        let map; 
        
        setTimeout( ()=>{
            directionsDisplay = new google.maps.DirectionsRenderer();
            let bsas = {lat: -34.6037, lng: -58.3816};
            let mapOptions = {
              zoom:12,
              center: bsas
            }
            map = new google.maps.Map(document.getElementById('map_newModal'), mapOptions);
            directionsDisplay.setMap(map);
    
            var marker = new google.maps.Marker({
                position: bsas,
                map: map,
                title: 'Click to zoom',
                draggable : true
            });
        
            marker.addListener('dragend', function() {
                state.newStop.number = state.branch.stops[state.branch.stops.length-1].number + 1 
                document.getElementById("stopsNumber").value = state.branch.stops[state.branch.stops.length-1].number + 1
                state.newStop.latitude = marker.position.lat()
                state.newStop.longitude = marker.position.lng()
                document.getElementById("stopsLatitude").value = marker.position.lat()
                document.getElementById("stopsLongitude").value = marker.position.lng()
            });
    
        }, 500)
    }

    function calcRoute(stops) {

        markers.forEach( m => m.setMap(null) )

        const points = stops.map( s => ({lat:s.latitude,lng:s.longitude, name : s.name , number : s.number, id : s.id}))
        
        points.forEach( p => {
           const m = new google.maps.Marker({
                position: p,
                map: map,
                label : String(p.number),
                draggable : true
            });
            markers.push(m);
            m.addListener("dragend",()=>{
                const stop = {
                    branch_id : branchId,
                    latitude :  m.position.lat(),
                    longitude : m.position.lng(),
                    number : p.number,
                    name : p.name
                }
                updateStop(p.id,stop)
            });
        });

        const waypoints =  points.slice(1, -1).map( p => ({ location : p , stopover : false}))

        let request = {
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
                if(resp.data != "El número de parada de este ramal ya existe"){
                    updatePage();    
                    cleanInputs(); 
                    $("#closeButton_Modal").click();
                    $("#stopsList").notify(
                        resp.data,
                        { position:"top center", className:"success" }
                    ); 
                    state.newStop = {};
                }else {
                    $("#saveButton_Modal").notify(
                        resp.data,
                        { position:"left", className:"error" }
                    )
                }                                 
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
                //initMap()
                calcRoute(state.branch.stops)
            })
            .catch((err)=>
            console.error(err.response ? err.response.data : err)
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
            deleteStop : deleteStop,
            setMapInNewStopModal : setMapInNewStopModal
        }
    })

    updatePage()
}

window.addEventListener("load", function(){
    stopApp()
})