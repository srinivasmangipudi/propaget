
<div ng-controller="profileAddDetailCtrl">

     <h1>Add Detail Page</h1>
     <form name="editProfileForm" id="editProfileForm"  ng-submit="save_profile()" novalidate>
         <div class="form-group">
            <label>Role  :  </label>
            <input id="role_agent" ng-model="userdata.role" type="radio" value="agent">Agent
            <input id="role_client" ng-model="userdata.role" type="radio" value="client">Client
         </div>
         <div class="form-group">
            <label>Experience : </label>
            <input type='text' ng-model="userdata.experience" class="form-control" name="experience">
         </div>
         <div class="form-group">
            <label>Summary : </label>
            <textarea ng-model="userdata.summary"  name="summary" class="form-control" rows="6"></textarea>
         </div>
         <button type="submit" class="btn btn-primary">Submit</button>
     </form>

</div>
