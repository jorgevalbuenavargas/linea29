"use strict"

window.addEventListener("load", function(){

    let state = {branchs : [], newBranch : {}, editedBranch : {}, idBranch : "", deletedId: ""}

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
                                     
                })
            .catch((err)=>
                console.error(err.response.data)
            )    
        state.newBranch = {};         
    }
    
    function cleanInputs(){
        document.getElementById("branchsName").value = null;
        document.getElementById("branchsSchedule").value = null;
    }
    
    function asignIdToDelete(id){
        state.deletedId = id
    }

    function fillEditionForm(idBranch){
        axios.get("/branch/" + idBranch)
            .then((resp)=>{
                state.editedBranch = resp.data;
                document.getElementById("editedBranchsName").value = resp.data.name;
                document.getElementById("editedBranchsSchedule").value = resp.data.schedule
            })                        
            .catch((err)=>
                console.error(err.response.data)
            )
    }
    
    function updateBranch(id, editedBranch){
        console.log(id)
        console.log(editedBranch)
       
        axios.put("/branch/" + id, editedBranch)
            .then((resp)=>{
                console.log(resp.data);
                updateTable();
            })                        
            .catch((err)=>
                console.error(err.response.data)
            )
        state.editedBranch = {}    
    }
    
    function deleteBranch(id){
        console.log(id)
       
        axios.delete("/branch/" + id)
            .then((resp)=>{
                console.log(resp.data);
                updateTable();
            })                        
            .catch((err)=>
                console.error(err.response.data)
            )  
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