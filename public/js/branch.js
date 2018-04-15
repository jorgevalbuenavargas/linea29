"use strict"

window.addEventListener("load", function(){

    let state = {
        branchs : [], 
        newBranch : {}, 
        editedBranch : {}, 
        idBranch : "", 
        deletedId: ""
    }
    
    $(document).ready(function(){
        $('[data-toggle="tooltip"]').tooltip();   
    });

    function updateTable(){
        axios.get("/branch")
            .then((resp)=>state.branchs = resp.data)
            .catch((err)=>
                console.error(err.response.data)
            )
    }
    
    function createBranch(branch){
        axios.post("/branch", branch)
            .then((resp)=>{
                console.log(resp.data);
                updateTable();    
                cleanInputs();
                $("#closeButton_Modal").click();
                $("#branchList").notify(
                    resp.data,
                    { position:"top center", className:"success" }
                );       
                state.newBranch = {};                              
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
        $("#branchsName").value = null;
        $("#branchsSchedule").value = null;
        state.newBranch = {}; 
    }
    
    function asignIdToDelete(id){
        state.deletedId = id
    }

    function fillEditionForm(idBranch){
        axios.get("/branch/" + idBranch)
            .then((resp)=>{
                state.editedBranch = resp.data;
            })                        
            .catch((err)=>
                console.error(err.response.data)
            )
    }
    
    function updateBranch(id, editedBranch){      
        axios.put("/branch/" + id, editedBranch)
            .then((resp)=>{
                console.log(resp.data);
                updateTable();
                $("#closeButton_editModal").click();
                $("#branchList").notify(
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
    
    function deleteBranch(id){      
        axios.delete("/branch/" + id)
            .then((resp)=>{
                console.log(resp.data);
                updateTable();
                $("#branchList").notify(
                    resp.data,
                    { position:"top center", className:"success" }
                );   
            })                        
            .catch((err)=>{
                console.error(err.response.data.message); 
                $("#branchList").notify(
                    "No es posible eliminar este Ramal debido a que tiene Paradas asociadas" + "\n,asegúrate de eliminarlas para poder eliminar el Ramal",
                    { position:"top center", className:"error" }
                )
            })   
    }

    const app = new Vue({
        data: state,
        el: "#app-branch",
        methods : {
            createBranch : createBranch,
            cleanInputs : cleanInputs,
            fillEditionForm : fillEditionForm,
            updateBranch : updateBranch,
            deleteBranch : deleteBranch,
            asignIdToDelete : asignIdToDelete
        }
    })
    
    updateTable();
})