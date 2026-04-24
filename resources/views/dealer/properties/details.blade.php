<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Property Details</title>
</head>
<body>
    <h2>Property Details</h2>
    <p><strong>Title:</strong> {{ $property->title }}</p>
    <p><strong>Description:</strong> {{ $property->description }}</p>
    <p><strong>Type:</strong> {{ $property->property_type }}</p>
    <p><strong>BHK Type:</strong> {{ $property->bhk_type }}</p>
    <p><strong>Option Type:</strong> {{ $property->option_type }}</p>
    <p><strong>Listing Type:</strong> {{ $property->listing_type }}</p>
    <p><strong>Address:</strong> {{ $property->address }}</p>
    <p><strong>City:</strong> {{ $property->city }}</p>
    <p><strong>State:</strong> {{ $property->state }}</p>
    <p><strong>Latitude:</strong> {{ $property->latitude }}</p>
    <p><strong>Longitude:</strong> {{ $property->longitude }}</p>
    <p><strong>Map URL:</strong> <a href="{{ $property->map_url }}" target="_blank">View Map</a></p>
    <p><strong>Price:</strong> {{ $property->price }}</p>
    <p><strong>Bedrooms:</strong> {{ $property->bedrooms }}</p>
    <p><strong>Bathrooms:</strong> {{ $property->bathrooms }}</p>
    <p><strong>Parking:</strong> {{ $property->parking }}</p>
    <p><strong>Pet Friendly:</strong> {{ $property->pet_friendly ? 'Yes' : 'No' }}</p>
    <p><strong>Area:</strong> {{ $property->area }}</p>
    <p><strong>Furnishing:</strong> {{ $property->furnishing }}</p>
    <p><strong>Amenities:</strong> {{ $property->amenities }}</p>
    <p><strong>Status:</strong> {{ $property->status }}</p>
    <p><strong>Is Real:</strong> {{ $property->isreal ? 'Yes' : 'No' }}</p>
    <p><strong>RERA ID:</strong> {{ $property->rera_id }}</p>
    <p><strong>Possession Date:</strong> {{ $property->possession_date }}</p>
    <p><strong>Price Range:</strong> {{ $property->price_range }}</p>
    <p><strong>Floor Plan:</strong> {{ $property->floor_plan }}</p>
    <p><strong>Video URL:</strong> <a href="{{ $property->video_url }}" target="_blank">Watch Video</a></p>
    <p><strong>Virtual Tour URL:</strong> <a href="{{ $property->virtual_tour_url }}" target="_blank">Virtual Tour</a></p>
    <p><strong>Is Featured:</strong> {{ $property->is_featured ? 'Yes' : 'No' }}</p>
    <p><strong>Is Premium:</strong> {{ $property->is_premium ? 'Yes' : 'No' }}</p>
    <p><strong>Views Count:</strong> {{ $property->views_count }}</p>
    <h3>Images</h3>
    @foreach($property->images as $image)
        <img src="{{ asset('storage/' . $image->image_url) }}" alt="Property Image" width="200" style="margin:5px;">
    @endforeach
    <br>
    <a href="{{ route('dealer.properties.index') }}">Back to Property List</a>
</body>
</html>
