
<div ng-controller="requirementController">
        <h1>Requirement</h1>
        <div class="row pull-right">
            <div class="col-md-4">
                <a href="#add" class="btn btn-primary" role="button">Add New Requirement</a>
                <i ng-show="loading" class="fa fa-spinner fa-spin"></i>
            </div>
        </div>

        <div class="form-group">
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
                       <td>Delete</td>
                   </tr>
               </thead>
               <tbody>
                   <tr  ng-repeat='req in filtered = (requirements | filter: search | orderBy : predicate :reverse) | startFrom:(currentPage-1)*entryLimit | limitTo:entryLimit track by $index '>
                       <td><a href="#view/@{{ req.id }}">@{{ req.title }}</a></td>
                       <td>@{{ req.location }}</td>
                       <td>@{{ req.area }}</td>
                       <td>@{{ req.price }}</td>
                       <td>@{{ req.type }}</td>
                       <td><a href="#edit/@{{ req.id }}">Edit</a></td>
                       <td ng-click="deleteRequirement(req.id)">Delete </td>
                   </tr>
               </tbody>
            </table>
           <div ng-show="filteredItems == 0">
               <div>
                   <h4>No Record found</h4>
               </div>
           </div>
           <div class="col-md-12" ng-show="filteredItems > entryLimit">
               <div pagination="" page="currentPage" on-select-page="setPage(page)" boundary-links="true" total-items="filteredItems" items-per-page="entryLimit" class="pagination-small" previous-text="&laquo;" next-text="&raquo;"></div>
           </div>

        </div>
        </div>
</div>