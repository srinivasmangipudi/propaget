
<div ng-controller="requirementController">
        <h1>Requirement</h1>
        <div class="row pull-right">
            <div class="col-md-4">
                <a href="#add" class="btn btn-primary" role="button">Add New Requirement</a>
                <i ng-show="loading" class="fa fa-spinner fa-spin"></i>
            </div>
        </div>

        <div class="row">
            <table class="table table-striped">
               <thead>
                   <tr class="info">
                       <td>Title</td>
                       <td>Location</td>
                       <td>Area</td>
                       <td>Price</td>
                       <td>Type</td>
                       <td>Edit</td>
                   </tr>
               </thead>
               <tbody>
                   <tr  ng-repeat='req in requirements'>
                       <td>@{{ req.title }}</td>
                       <td>@{{ req.location }}</td>
                       <td>@{{ req.area }}</td>
                       <td>@{{ req.price }}</td>
                       <td>@{{ req.type }}</td>
                       <td><a href="#edit/@{{ req.id }}">Edit</a></td>
                   </tr>
               </tbody>
            </table>
        </div>
</div>