
<div ng-controller="distributionViewCtrl">
        <h1>Distribution View Members</h1>
        <div class="form-group">
        <div class="row">
            <table class="table table-striped">
               <thead>
                   <tr class="info">
                       <td>User Name</td>
                   </tr>
               </thead>
               <tbody>
                   <tr  ng-repeat='listmembers in filtered = (distributionMemberlist | filter: search | orderBy : predicate :reverse) | startFrom:(currentPage-1)*entryLimit | limitTo:entryLimit track by $index '>
                       <td><a href="#view/@{{ listmembers.name }}">@{{ listmembers.name }}</a></td>
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