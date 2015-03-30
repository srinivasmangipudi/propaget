<div ng-controller="requirementViewCtrl">
    <h1>View Requirement</h1>
       <div class="form-group">
            <label class="text-primary">Title : </label>
            @{{requirement.title}}
        </div>

       <div class="form-group">
            <label class="text-primary">Description : </label>
            @{{requirement.description}}
       </div>

        <div class="form-group">
            <label class="text-primary">Client Email : </label>
            @{{ requirement.client_email }}
        </div>

       <div class="form-group">
            <label class="text-primary">Location : </label>
            @{{ requirement.location }}
       </div>

       <div class="form-group">
            <label class="text-primary">Area : </label>
            @{{ requirement.area }}
       </div>

       <div class="form-group">
           <label class="text-primary">Range : </label>
           @{{ requirement.range }}
      </div>

       <div class="form-group">
            <label  class="text-primary">Price : </label>
            @{{ requirement.price }}
       </div>

        <div class="form-group">
            <label  class="text-primary">Price Range : </label>
            @{{ requirement.price_range }}
        </div>

       <div class="form-group">
            <label  class="text-primary">Type : </label>
            @{{ requirement.type }}
       </div>
</div>