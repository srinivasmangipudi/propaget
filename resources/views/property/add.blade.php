<div ng-controller="propertyAddCtrl">
<style type="text/css">
  #addPropertyForm input.ng-invalid.ng-touched {
    border-color: #ff0011;
  }

    #addPropertyForm input.error {
    border-color: #ff0011;
  }

  #addPropertyForm input.ng-valid.ng-touched {
    border-color: #cccccc;
  }
</style>

    <h1 ng-show="!property.id">Add property</h1>
    <h1 ng-show="property.id">Edit property</h1>
        <form name="addPropertyForm" id="addPropertyForm"  ng-submit="save_property()" novalidate>
           <div class="form-group">
                <label>Title:</label>
                <input type='text' ng-model="property.title" name="title" class="form-control" required ng-class="show_error('title')">
            </div>

           <div class="form-group">
                <label>Description:</label>
                <textarea  ng-model="property.description" name="description" class="form-control" rows="6"></textarea>
           </div>

            <div class="form-group">
                <label>Client Email:</label>
                <input type='email' ng-model="property.clientEmail" class="form-control" name="clientEmail" required  ng-class="show_error('clientEmail')">
            </div>

           <div class="form-group">
                <label>Address:</label>
                <input type='text' ng-model="property.address" class="form-control" name="address">
            </div>

           <div class="form-group">
                <label>Location:</label>
                <input type='text' ng-model="property.location" class="form-control" name="location" required  ng-class="show_error('location')">
           </div>

           <div class="form-group">
                <label>Area:</label>
                <input type='text' ng-model="property.area" class="form-control" name="area" required  ng-class="show_error('area')">
           </div>

           <div class="form-group">
                <label>Price:</label>
                <input type='text' ng-model="property.price" class="form-control" name="price" required  ng-class="show_error('price')">
           </div>

           <div class="form-group">
                <label>Type:</label>
                <select type='text' ng-model="property.type" class="form-control" name="type" required  ng-class="show_error('type')">
                    <option value="Sale">Sale</option>
                    <option value="Lease">Lease</option>
                </select>
           </div>

           <button type="submit" class="btn btn-primary">Submit</button>
       </form>
</div>