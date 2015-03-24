
<div ng-controller="distributionController">
        <h1>My Distribution List</h1>

        <div class="form-group">
        <div class="row">
            <table class="table table-striped">
               <thead>
                   <tr class="info">
                       <td>Distribution Name</td>
                       <td>Total Users</td>
                       <!--td>Delete</td-->
                   </tr>
               </thead>
               <tbody>
                   <tr  ng-repeat='dislist in filtered = (distributionlist | filter: search | orderBy : predicate :reverse) | startFrom:(currentPage-1)*entryLimit | limitTo:entryLimit track by $index '>
                       <td><a href="#view/@{{ dislist.id }}">@{{ dislist.name }}</a></td>
                       <td>@{{ dislist.totalusers }}</td>
                       <!--td ng-click="deleteDistribution(dislist.id)">Delete </td-->
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