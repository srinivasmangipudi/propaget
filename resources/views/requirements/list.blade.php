
<div ng-controller="requirementController">
        <h1>Requirement</h1>
        <div class="row">
            <div class="col-md-4">
                <input type='text' ng-model="todo.title">
                <button class="btn btn-primary btn-md"  ng-click="addTodo()">Add</button>
                <i ng-show="loading" class="fa fa-spinner fa-spin"></i>
            </div>
        </div>
        <hr>

        <table class="table table-striped">
           <thead>
               <tr class="info">
                   <td>Location</td>
                   <td>Area</td>
                   <td>Price</td>
                   <td>Type</td>
                   <td>Edit</td>
               </tr>
           </thead>
           <tbody>
               <tr  ng-repeat='req in requirements'>
                   <td>@{{ req.location }}</td>
                   <td>@{{ req.area }}</td>
                   <td>@{{ req.price }}</td>
                   <td>@{{ req.type }}</td>
               </tr>
           </tbody>
       </table>

</div>