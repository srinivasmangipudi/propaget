
<div ng-controller="propertyController">
            <div class="row"><h1>Property</h1></div>
            <div class="row pull-right">
                <div class="col-md-4">
                    <a href="#add" class="btn btn-primary" role="button">Add new property</a>
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
                        <tr  ng-repeat='property in properties'>
                            <td>@{{ property.title }}</td>
                            <td>@{{ property.location }}</td>
                            <td>@{{ property.area }}</td>
                            <td>@{{ property.price }}</td>
                            <td>@{{ property.type }}</td>
                            <td><a href="#edit/@{{ property.id }}">Edit</a></td>
                        </tr>
                    </tbody>
                </table>
            </div>
    </div>