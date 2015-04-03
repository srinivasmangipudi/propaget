<div ng-controller="requirementAddCtrl">
<style type="text/css">
  #addRequirementForm input.ng-invalid.ng-touched {
    border-color: #ff0011;
  }

  #addRequirementForm input.error {
    border-color: #ff0011;
  }

  #addRequirementForm input.ng-valid.ng-touched {
    border-color: #cccccc;
  }
</style>

    <h1 ng-show="!requirement.id">Add Requirement</h1>
    <h1 ng-show="requirement.id">Edit Requirement</h1>
        <form name="addRequirementForm" id="addRequirementForm"  ng-submit="save_requirement()" novalidate>
           <div class="form-group">
                <label>Title:</label>
                <input type='text' ng-model="requirement.title" name="title" class="form-control" required ng-class="show_error('title')">
            </div>

           <div class="form-group">
                <label>Description:</label>
                <textarea  ng-model="requirement.description" name="description" class="form-control" rows="6"></textarea>
           </div>

            <div class="form-group">
                <label>Client Email : </label>
                <input type='email' ng-model="requirement.client_email" class="form-control" name="client_email" required ng-class="show_error('client_email')" ng-hide="requirement.id">
                <span ng-show="requirement.id">@{{requirement.client_email}}</span>
            </div>

           <div class="form-group">
                <label>Location:</label>
                <input type='text' ng-model="requirement.location" class="form-control" name="location" required ng-class="show_error('location')">
           </div>

           <div class="form-group">
                <label>Area:</label>
                <input type='text' ng-model="requirement.area" class="form-control" name="area" required ng-class="show_error('area')">
           </div>

           <div class="form-group">
               <label>Area Range:</label>
               <input type='text' ng-model="requirement.range" class="form-control" name="range" required ng-class="show_error('range')">
          </div>

           <div class="form-group">
                <label>Price:</label>
                <input type='text' ng-model="requirement.price" class="form-control" name="price" required ng-class="show_error('price')">
           </div>

            <div class="form-group">
                <label>Price Range:</label>
                <input type='text' ng-model="requirement.price_range" class="form-control" name="price_range" required ng-class="show_error('price_range')">
            </div>

           <div class="form-group">
                <label>Type:</label>
                <select type='text' ng-model="requirement.type" class="form-control" name="type" required>
                    <option value="Buy">Buy</option>
                    <option value="Rent">Rent</option>
                </select>
           </div>

           <button type="submit" class="btn btn-primary">Submit</button>
       </form>
</div>