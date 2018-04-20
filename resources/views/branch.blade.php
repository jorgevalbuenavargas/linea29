@extends('layouts.app')

@section('content_javaScript')
<script src="{{ asset('js/branch.js') }}" defer></script>
@endsection

@section('content')

<div id="app-branch" class="container-fluid">        

    <!-- Dashboard --> 
    <div class="row justify-content-md-center" style="margin-top: 20px">            
        <h1 class="text text-center">Ramales</h1>
    </div>

    <div class="row justify-content-md-center">

        <div class="col-md-6" id="branchDashboard" style="margin-top: 20px">
            <button type='button' class="btn btn-primary" id="newBranchButton" data-toggle="modal" data-target="#newBranchForm_Modal" v-on:click="cleanInputs();">                        
                    <i class="fas fa-plus"></i>
            </button>

            <table id="branchList" class="table table-hover table-responsive-md" style="margin-top: 10px">
                <thead class="table_head">
                    <tr>
                        <th class="text text-center">Nombre del Ramal</th>
                        <th class="text text-center">Horario</th>
                        <th class="text text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody id="branchRows">
                    <tr v-for="branch of branchs">                            
                        <td>
                            @{{ branch.name }} 
                        </td>
                        <td>
                            @{{ branch.schedule }} 
                        </td>
                        <td class="text text-center">
                            <button type='button' class="btn btn-warning" style="margin:2px" data-toggle="modal" data-target="#editBranchForm_Modal" v-on:click="fillEditionForm(branch.id);">
                                    <i class="fas fa-edit"></i>
                            </button>
                            <button type='button' class="btn btn-danger" data-toggle="modal" data-target="#deleteBranchForm_Modal" v-on:click="asignIdToDelete(branch.id);">
                                    <i class="fas fa-trash-alt"></i>
                            </button>
                            <a class="btn btn-primary" style="margin:2px" v-bind:href="'/paradas?branch_id=' + branch.id" >
                                    <i class="fas fa-road"></i>
                            </a>
                        </td>
                    </tr>        
                </tbody>
            </table>

        <!--  Modal para el ingreso de nuevos ramales -->

        <div id="newBranchForm_Modal" class="modal fade" role="dialog">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Ingresa los siguientes datos:</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body" id="newBranch_modalBody">
                        <label for="branchsName">Nombre del ramal:</label>
                        <input type="text" class="form-control" id="branchsName" v-model="newBranch.name">
                        <label for="branchsSchedule">Horario:</label>
                        <input type="text" class="form-control" id="branchsSchedule" v-model="newBranch.schedule">    
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" id="saveButton_Modal" v-on:click="createBranch(newBranch);">Grabar</button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal" id="closeButton_Modal" v-on:click="cleanInputs();">Cerrar</button>
                    </div>
                </div>
            </div>          
        </div>

        <!--  Modal para modificar ramales existentes -->

        <div id="editBranchForm_Modal" class="modal fade" role="dialog">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Ingresa los siguientes datos:</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body" id="editBranch_modalBody">
                        <label for="editedBranchsName">Nombre del ramal:</label>
                        <input type="text" class="form-control" id="editedBranchsName" v-model="editedBranch.name">
                        <label for="editedBranchsSchedule">Horario:</label>
                        <input type="text" class="form-control" id="editedBranchsSchedule" v-model="editedBranch.schedule">    
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" id="saveButton_editModal" v-on:click="updateBranch(editedBranch.id, editedBranch);">Grabar</button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal" id="closeButton_editModal">Cerrar</button>
                    </div>
                </div>
            </div>          
        </div>

        <!-- MODAL PARA CONFIRMAR LA ELIMINACIÓN DE UN REGISTRO -->
        <div id="deleteBranchForm_Modal" class="modal fade" role="dialog">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Confirmar eliminación</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" class="form-control" id="deletedBranchsId">
                        <p>¿Estás seguro que deseas eliminar el registro?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal" id="confirmDelete_button" v-on:click="deleteBranch(deletedId);">Si</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal" id="cancelDelete_button">No</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection