"use strict"

window.addEventListener("load", function(){

    let state = {
        branch : {}
    }

    var s = new URLSearchParams(window.location.search.substring(1));
    const branchId = s.get("branch_id");

    function updatePage(){
        axios.get("branch/" + branchId)
            .then(resp => {
                state.branch = resp.data
                console.log(state.branch)
            })
            .catch((err)=>
            console.error(err.response.data)
        ) 
    }
    

    const app = new Vue({
        data: state,
        el: "#app-stops",
        methods : {
            updatePage : updatePage
        }
    })

    updatePage()

})