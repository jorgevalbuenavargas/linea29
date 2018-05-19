@extends('layouts.app')

@section('content_javaScript')
<script src="{{ asset('js/stops.js') }}" defer></script>
@endsection


@section('content')

<div id="app-stops" class="container">

    <!-- Dashboard --> 
    <div class="row justify-content-md-center" style="margin-top: 20px">            
        <h1 class="text text-center">Listado de paradas del Ramal: @{{ branch.name }}</h1>
    </div>

    <div class="row justify-content-md-center">

        <div id="stopsDashboard" style="margin-top: 20px">
            <button type='button' class="btn btn-primary" id="newStopButton" data-toggle="modal" data-target="#newStopForm_Modal" v-on:click='setMapInNewStopModal()'>                        
                    <i class="fas fa-plus"></i>
            </button>

            <a class="btn btn-success" style="margin:2px" href="/ramal" data-toggle="tooltip" title="Regresar a Ramales">
                <i class="fas fa-bus"></i>
            </a>

            <table id="stopsList" class="table table-hover table-responsive" style="margin-top: 10px">
                <thead class="table_head">
                    <tr>
                        <th class="text text-center">Número de parada</th>
                        <th class="text text-center">Nombre de la Parada</th>
                        <th class="text text-center">Latitud</th>
                        <th class="text text-center">Longitud</th>
                        <th class="text text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody id="stopsRows" >
                    <tr v-for="stopRow of branch.stops">                            
                        <td class="text text-center">
                            @{{ stopRow.number }}
                        </td>
                        <td class="text text-center">
                            @{{ stopRow.name }}
                        </td>
                        <td class = "text text-right">
                            @{{ stopRow.latitude }}
                        </td>
                        <td class = "text text-right">
                            @{{ stopRow.longitude }}
                        </td>
                        <td class="text text-center">
                            <button type='button' class="btn btn-warning" style="margin:2px" data-toggle="modal" data-target="#editStopForm_Modal" v-on:click="fillEditionForm(stopRow.id)">
                                    <i class="fas fa-edit"></i>
                            </button>
                            <button type='button' class="btn btn-danger" data-toggle="modal" data-target="#deleteStopForm_Modal" v-on:click="asignIdToDelete(stopRow.id);">
                                    <i class="fas fa-trash-alt"></i>
                            </button>                            
                        </td>
                    </tr>        
                </tbody>
            </table>
            
            <!--  Modal para el ingreso de nuevas paradas -->

            <div id="newStopForm_Modal" class="modal fade" role="dialog">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Ingresa los siguientes datos:</h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
                        <div class="modal-body" id="newStop_modalBody">
                            <div class="col-12">
                            <label for="stopsNumber">Número de parada:</label>
                            <input type="number" min="1" class="form-control" id="stopsNumber" v-model="newStop.number">
                            <label for="stopsName">Nombre de la parada:</label>
                            <input type="text" class="form-control" id="stopsName" v-model="newStop.name">                                
                            <label for="stopsLatitude">Latitud:</label>
                            <input type="number" class="form-control" id="stopsLatitude" v-model="newStop.latitude"> 
                            <label for="stopsLongitude">Longitud:</label>
                            <input type="number" class="form-control" id="stopsLongitude" v-model="newStop.longitude">
                            </div>
                            <div class="col-12 googleMap">
                                <h6 class="text text-center">Mueve el marcador en el mapa para crear una nueva parada</h4>
                                <div id="map_newModal"></div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary" id="saveButton_Modal" v-on:click="createStop(newStop);">Grabar</button>
                            <button type="button" class="btn btn-danger" data-dismiss="modal" id="closeButton_Modal" v-on:click="cleanInputs();">Cerrar</button>
                        </div>
                    </div>
                </div>          
            </div>

            <!--  Modal para la modificación de paradas -->

            <div id="editStopForm_Modal" class="modal fade" role="dialog">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Ingresa los siguientes datos:</h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
                        <div class="modal-body" id="editStop_modalBody">                                    
                            <label for="editedStopsNumber">Número de parada:</label>
                            <input type="number" min="1" class="form-control" id="editedStopsNumber" v-model="editedStop.number">
                            <label for="editedStopsName">Nombre de la parada:</label>
                            <input type="text" class="form-control" id="editedStopsName" v-model="editedStop.name">
                            <!--<label for="editedStopsLatitude">Latitud:</label>
                            <input type="number" class="form-control" id="editedStopsLatitude" v-model="editedStop.latitude"> 
                            <label for="editedStopsLongitude">Longitud:</label>
                            <input type="number" class="form-control" id="editedStopsLongitude" v-model="editedStop.longitude">-->
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary" id="saveButton_editModal" v-on:click="updateStop(editedStop.id, editedStop)">Grabar</button>
                            <button type="button" class="btn btn-danger" data-dismiss="modal" id="closeButton_editModal" v-on:click="cleanInputs();">Cerrar</button>
                        </div>
                    </div>
                </div>          
            </div>


            <!-- MODAL PARA CONFIRMAR LA ELIMINACIÓN DE UN REGISTRO -->
            <div id="deleteStopForm_Modal" class="modal fade" role="dialog">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Confirmar eliminación</h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" class="form-control" id="deletedStopsId">
                            <p>¿Estás seguro que deseas eliminar el registro?</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal" id="confirmDelete_button" v-on:click="deleteStop(deletedStop);">Si</button>
                            <button type="button" class="btn btn-default" data-dismiss="modal" id="cancelDelete_button">No</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>          

    <!-- Google Maps -->
    <div class="googleMap">
        <h3 class="text text-center">Puedes mover los marcadores de cada parada para actualizar su ubicación</h4>
        <div id="map"></div>
        <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD-9eVfLZ8aOuIUrh84nDcvAZsS53RRoiQ&callback=stopApp">
        </script>
    <div>

</div>
@endsection


