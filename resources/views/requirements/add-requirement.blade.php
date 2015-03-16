@extends('app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12"><h1>Add new requirement</h1></div>
        </div>
        <div class="row">

            @foreach ($errors->all() as $error)

              <div class="alert-danger">{{ $error }}</div>

            @endforeach
        </div>

        <div class="row">
            <div class="col-md-6">
                <?php echo BootForm::open()->post()->action('save'); ?>
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <?php echo BootForm::text('Location', 'location')->placeholder('Enter the location.'); ?>
                {{ $errors->first('area') }}
                <?php echo BootForm::text('Area', 'area')->placeholder('Enter the area.'); ?>
                <?php echo BootForm::text('Range', 'range'); ?>
                <?php echo BootForm::text('Price', 'price'); ?>
                <?php echo BootForm::text('Price range', 'priceRange'); ?>

                <?php echo BootForm::select('Type', 'type')
                        ->options(['rent' => 'Rent', 'buy' => 'Buy'])
                        ->select('green'); ?>

                <?php echo BootForm::submit('Save', 'save'); ?>
            </div>
        </div>
    </div>
@endsection