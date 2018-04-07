"use strict"

window.addEventListener("load", function(){

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

    function updatePage(){
        axios.get("branch/" + branchId)
            .then(resp => {
                state.branch = resp.data
            })
            .catch((err)=>
            console.error(err.response.data)
        ) 
    }

    function createStop(nStop){
        nStop.branch_id = state.branch.id;
        console.log(nStop)
        axios.post("/stop", nStop)
            .then((resp)=>{
                console.log(resp.data);
                updatePage();    
                cleanInputs();                                     
                })
            .catch((err)=>
                console.error(err.response.data)
            )    
        state.newStop = {};      
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
        
        console.log(id)
        console.log(editedStop)
       
        axios.put("/stop/" + id, editedStop)
            .then((resp)=>{
                console.log(resp.data);
                updatePage();
            })                        
            .catch((err)=>
                console.error(err.response.data)
            )
        state.editedBranch = {}    
    }
    
    function deleteStop(id){
        console.log(id)
       
        axios.delete("/stop/" + id)
            .then((resp)=>{
                console.log(resp.data);
                updatePage();
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

})