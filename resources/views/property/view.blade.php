<div ng-controller="propertyViewCtrl">
    <h1>View property</h1>
       <div class="form-group">
            <label class="text-primary">Title : </label>@{{ property.title }}
        </div>

       <div class="form-group">
            <label class="text-primary">Description : </label>@{{ property.description }}
       </div>

        <div class="form-group">
            <label class="text-primary">Client Email : </label>@{{ property.clientEmail }}
        </div>

       <div class="form-group">
            <label class="text-primary">Address : </label>@{{ property.address }}
        </div>

       <div class="form-group">
            <label class="text-primary">Location : </label>@{{ property.location }}
       </div>

       <div class="form-group">
            <label class="text-primary">Area : </label>@{{ property.area }}
       </div>

       <div class="form-group">
            <label class="text-primary">Price : </label>@{{ property.price }}
       </div>

       <div class="form-group">
            <label class="text-primary">Type : </label>@{{ property.type }}
       </div>
</div>