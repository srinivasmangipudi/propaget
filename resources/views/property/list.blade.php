
<div ng-controller="propertyController">
            <div class="row"><h1>Property</h1></div>
            <div class="row pull-right">
                <div class="col-md-4">
                    <a href="#add" class="btn btn-primary" role="button">Add new property</a>
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
                        <tr  ng-repeat='property in filtered = (properties | filter: search | orderBy : predicate :reverse) | startFrom:(currentPage-1)*entryLimit | limitTo:entryLimit track by $index '>
                            <td>@{{ property.title }}</td>
                            <td>@{{ property.location }}</td>
                            <td>@{{ property.area }}</td>
                            <td>@{{ property.price }}</td>
                            <td>@{{ property.type }}</td>
                            <td><a href="#edit/@{{ property.id }}">Edit</a></td>
                            <td ng-click="deleteProperty(property.id)">Delete </td>
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